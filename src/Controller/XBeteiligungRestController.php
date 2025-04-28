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

use DemosEurope\DemosplanAddon\Contracts\Config\GlobalConfigInterface;
use DemosEurope\DemosplanAddon\Contracts\Logger\ApiLoggerInterface;
use DemosEurope\DemosplanAddon\Contracts\MessageBagInterface;
use DemosEurope\DemosplanAddon\Controller\APIController;
use DemosEurope\DemosplanAddon\Exception\JsonException;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\XBeteiligungService;
use DemosEurope\DemosplanAddon\XBeteiligung\Repository\ProcedureMessageRepository;
use EDT\JsonApi\RequestHandling\MessageFormatter;
use EDT\JsonApi\Validation\FieldsValidator;
use EDT\Wrapping\TypeProviders\PrefilledTypeProvider;
use EDT\Wrapping\Utilities\SchemaPathProcessor;
use Exception;
use GoetasWebservices\XML\XSDReader\Schema\Exception\SchemaException;
use InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

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
        XBeteiligungService $xBeteiligungService,
        LoggerInterface $logger
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

            // Create a message structure expected by the service
            // We'll extract the message type from the XML content for logging
            $messageTypeCode = $this->extractMessageTypeFromXml($xmlContent);
            $message = [
                'messageData' => $xmlContent,
                'messageTypeCode' => $messageTypeCode
            ];

            $logger->info('Processing XBeteiligung procedure creation request', [
                'messageTypeCode' => $messageTypeCode,
                'xmlStartsWith' => substr($xmlContent, 0, 200), // First 200 chars for debugging
                'content_length' => strlen($xmlContent)
            ]);

            // Process the message using the same service that RabbitMQ would use
            $responseObject = $xBeteiligungService->determineMessageContextAndDelegateAction($message);

            // Prepare the XML response
            $xmlPayload = $responseObject->getPayload();
            $response = new Response($xmlPayload);
            $response->headers->set('Content-Type', 'application/xml');

        } catch (Exception $e) {
            // Determine the appropriate status code and message based on the exception type
            $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
            $message = 'Error processing procedure creation request: ' . $e->getMessage();
            $logContext = [$e, $e->getTraceAsString()];
            $logMessage = 'Error processing procedure creation request';

            if ($e instanceof AccessDeniedException) {
                // Handle unauthorized access (401 Unauthorized)
                $statusCode = Response::HTTP_UNAUTHORIZED;
                $message = 'Unauthorized';
                $logMessage = 'Unauthorized access attempt to XBeteiligung procedure creation: Invalid X-Addon-XBeteiligung-Authorization header';
                $logContext = [$e];
            } elseif ($e instanceof InvalidArgumentException || $e instanceof SchemaException) {
                // Handle validation errors (400 Bad Request)
                $statusCode = Response::HTTP_BAD_REQUEST;
                $message = $e->getMessage();
                $logMessage = $e instanceof SchemaException
                    ? 'XBeteiligung procedure creation message could not be parsed'
                    : 'XBeteiligung procedure creation payload not supported';
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
            return true;
        }

        // Extract token from "Bearer {token}" format if present
        if (str_starts_with($authToken, 'Bearer ')) {
            $authToken = substr($authToken, 7);
        }

        return $authToken !== $this->getParameter('addon_xbeteiligung_async_rest_authentication');
    }

    /**
     * Extracts the message type from XML content.
     * This helps identify the type of XBeteiligung message for logging purposes.
     */
    private function extractMessageTypeFromXml(string $xmlContent): string
    {
        // Default message type if we can't extract it
        $defaultType = 'unknown';

        try {
            // Check for common message types in the XML
            $patterns = [
                'kommunal.Initiieren.0401' => '/<.*?:?planung2Beteiligung\.BeteiligungKommunalNeu\.0401/i',
                'kommunal.Aktualisieren.0402' => '/<.*?:?planung2Beteiligung\.BeteiligungKommunalAktualisieren\.0402/i',
                'kommunal.Loeschen.0409' => '/<.*?:?planung2Beteiligung\.BeteiligungKommunalLoeschen\.0409/i',
                'raumordnung.Initiieren.0301' => '/<.*?:?planung2Beteiligung\.BeteiligungRaumordnungNeu\.0301/i',
                'raumordnung.Aktualisieren.0302' => '/<.*?:?planung2Beteiligung\.BeteiligungRaumordnungAktualisieren\.0302/i',
                'raumordnung.Loeschen.0309' => '/<.*?:?planung2Beteiligung\.BeteiligungRaumordnungLoeschen\.0309/i',
                'planfeststellung.Initiieren.0201' => '/<.*?:?planung2Beteiligung\.BeteiligungPlanfeststellungNeu\.0201/i',
                'planfeststellung.Aktualisieren.0202' => '/<.*?:?planung2Beteiligung\.BeteiligungPlanfeststellungAktualisieren\.0202/i',
                'planfeststellung.Loeschen.0209' => '/<.*?:?planung2Beteiligung\.BeteiligungPlanfeststellungLoeschen\.0209/i',
            ];

            // If we can't match the expected pattern, try to identify the message by code number
            $codePatterns = [
                'kommunal.Initiieren.0401' => '/0401/i',
                'kommunal.Aktualisieren.0402' => '/0402/i',
                'kommunal.Loeschen.0409' => '/0409/i',
                'raumordnung.Initiieren.0301' => '/0301/i',
                'raumordnung.Aktualisieren.0302' => '/0302/i',
                'raumordnung.Loeschen.0309' => '/0309/i',
                'planfeststellung.Initiieren.0201' => '/0201/i',
                'planfeststellung.Aktualisieren.0202' => '/0202/i',
                'planfeststellung.Loeschen.0209' => '/0209/i',
            ];

            // First try with the specific patterns
            foreach ($patterns as $type => $pattern) {
                if (preg_match($pattern, $xmlContent)) {
                    return $type;
                }
            }
            
            // Extract the root element to make a better guess
            if (preg_match('/<([^:\s>]+:)?([^:\s>]+)/', $xmlContent, $matches)) {
                $rootElement = $matches[2] ?? '';
                
                // If we found a root element, try to match it against code patterns
                foreach ($codePatterns as $type => $pattern) {
                    if (preg_match($pattern, $rootElement)) {
                        return $type;
                    }
                }
            }
            
            // As a last resort, check if any of the codes appear in the XML
            foreach ($codePatterns as $type => $pattern) {
                if (preg_match($pattern, $xmlContent)) {
                    return $type;
                }
            }

            return $defaultType;
        } catch (Exception $e) {
            // If something goes wrong, return the default type
            return $defaultType;
        }
    }
}
