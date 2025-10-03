<?php

declare(strict_types=1);

/**
 * This file is part of the package demosplan.
 *
 * (c) 2010-present DEMOS plan GmbH, for more information see the license file.
 *
 * All rights reserved
 */

namespace DemosEurope\DemosplanAddon\XBeteiligung\Controller;

use DemosEurope\DemosplanAddon\Controller\APIController;
use DemosEurope\DemosplanAddon\Exception\JsonException;
use DemosEurope\DemosplanAddon\XBeteiligung\Enum\XBeteiligungMessageType;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\MessageHandler\MessageHandlerSelector;
use Exception;
use GoetasWebservices\XML\XSDReader\Schema\Exception\SchemaException;
use InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * REST API controller for XBeteiligung requests that were previously handled by RabbitMQ.
 * This controller accepts the same payload format and returns XML responses.
 */
class XBeteiligungRestController extends APIController
{
    /**
     * Creates a procedure via XBeteiligung REST API instead of using RabbitMQ.
     * This endpoint handles the initial procedure creation message (401).
     *
     * @throws JsonException
     */
    #[Route(
        path: '/addon/xbeteiligung/procedure/create',
        name: 'dplan_addon_xbeteiligung_procedure_create',
        methods: ['POST']
    )]
    public function createProcedure(
        Request $request,
        MessageHandlerSelector $messageHandlerSelector,
        LoggerInterface $logger
    ): Response {
        return $this->processProcedureRequest($request, $messageHandlerSelector, $logger, 'creation');
    }

    /**
     * Updates a procedure via XBeteiligung REST API instead of using RabbitMQ.
     * This endpoint handles procedure update messages (402/302/202).
     *
     * @throws JsonException
     */
    #[Route(
        path: '/addon/xbeteiligung/procedure/update',
        name: 'dplan_addon_xbeteiligung_procedure_update',
        methods: ['PATCH']
    )]
    public function updateProcedure(
        Request $request,
        MessageHandlerSelector $messageHandlerSelector,
        LoggerInterface $logger
    ): Response {
        return $this->processProcedureRequest($request, $messageHandlerSelector, $logger, 'update');
    }

    /**
     * Common logic for processing XBeteiligung procedure requests (create/update).
     *
     * @throws JsonException
     */
    private function processProcedureRequest(
        Request $request,
        MessageHandlerSelector $messageHandlerSelector,
        LoggerInterface $logger,
        string $operationType
    ): Response {
        try {
            // Verify that the request has a valid API token using a custom header specific to XBeteiligung
            if ($this->hasNoValidAuthToken($request->headers->get('X-Addon-XBeteiligung-Authorization'))) {
                throw new AccessDeniedException('Unauthorized');
            }

            // Get the request payload - simply use the raw XML content
            $xmlContent = $request->getContent();

            if (empty($xmlContent)) {
                throw new InvalidArgumentException('Empty request payload');
            }

            $logger->info("Processing XBeteiligung procedure {$operationType} request", [
                'xmlStartsWith' => substr($xmlContent, 0, 200), // First 200 chars for debugging
                'content_length' => strlen($xmlContent)
            ]);

            // Process the message using MessageHandlerSelector
            $messageType = XBeteiligungMessageType::fromXmlContent($xmlContent);
            $handler = $messageHandlerSelector->getHandlerForMessageType($messageType);
            $responseObject = $handler->handleIncomingMessage($xmlContent, false, null);

            // Prepare the XML response
            $xmlPayload = $responseObject->getMessageXml();
            $response = new Response($xmlPayload);
            $response->headers->set('Content-Type', 'application/xml');

        } catch (Exception $e) {
            // Determine the appropriate status code and message based on the exception type
            $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
            $message = "Error processing procedure {$operationType} request: " . $e->getMessage();
            $logContext = [$e, $e->getTraceAsString()];
            $logMessage = "Error processing procedure {$operationType} request";

            if ($e instanceof AccessDeniedException) {
                // Handle unauthorized access (401 Unauthorized)
                $statusCode = Response::HTTP_UNAUTHORIZED;
                $message = 'Unauthorized';
                $logMessage = "Unauthorized access attempt to XBeteiligung procedure {$operationType}: Invalid X-Addon-XBeteiligung-Authorization header";
                $logContext = [$e];
            } elseif ($e instanceof InvalidArgumentException || $e instanceof SchemaException) {
                // Handle validation errors (400 Bad Request)
                $statusCode = Response::HTTP_BAD_REQUEST;
                $message = $e->getMessage();
                $logMessage = $e instanceof SchemaException
                    ? "XBeteiligung procedure {$operationType} message could not be parsed"
                    : "XBeteiligung procedure {$operationType} payload not supported";
                $logContext = [$e];
            }

            $logger->error($logMessage, $logContext);
            $response = new Response($message, $statusCode);
        }

        return $response;
    }

    /**
     * Check if the provided token is valid in the custom X-Addon-XBeteiligung-Authorization header.
     * Validates against the addon_xbeteiligung_async_rest_authentication parameter.
     */
    private function hasNoValidAuthToken(?string $authToken): bool
    {
        if (empty($authToken)) {
            $this->logger->warning('Missing X-Addon-XBeteiligung-Authorization header');
            return true;
        }

        $authString = $this->getParameter('addon_xbeteiligung_async_rest_authentication');
        if (strlen($authString) < 7) {
            $this->logger->warning('Invalid authentication token configured - must be 7 at least characters');
            return true;
        }

        // Extract token from "Bearer {token}" format if present
        if (str_starts_with($authToken, 'Bearer ')) {
            $this->logger->info('Extracting token from Bearer scheme');
            $authToken = substr($authToken, 7);
            $this->logger->info('Extracted token length: ' . strlen($authToken));
        }

        return $authToken !== $authString;
    }
}
