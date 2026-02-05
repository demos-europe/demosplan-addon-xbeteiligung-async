<?php

declare(strict_types=1);

/**
 * This file is part of the package demosplan.
 *
 * (c) 2010-present DEMOS plan GmbH, for more information see the license file.
 *
 * All rights reserved
 */

namespace DemosEurope\DemosplanAddon\XBeteiligung\Tests\Controller;

use DemosEurope\DemosplanAddon\Contracts\Config\GlobalConfigInterface;
use DemosEurope\DemosplanAddon\Contracts\Logger\ApiLoggerInterface;
use DemosEurope\DemosplanAddon\Contracts\MessageBagInterface;
use DemosEurope\DemosplanAddon\XBeteiligung\Controller\XBeteiligungRestController;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\ResponseValue;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\MessageHandler\MessageHandlerSelector;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\MessageHandler\Incoming\IncomingMessageHandlerInterface;
use EDT\JsonApi\RequestHandling\MessageFormatter;
use EDT\JsonApi\Validation\FieldsValidator;
use EDT\Wrapping\TypeProviders\PrefilledTypeProvider;
use EDT\Wrapping\Utilities\SchemaPathProcessor;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

class XBeteiligungRestControllerTest extends TestCase
{
    private const VALID_ROUTING_KEY = 'a.cockpit.a.00.01.00000000.a.00.00.00000000.a';

    private XBeteiligungRestController $controller;
    private MockObject|MessageHandlerSelector $messageHandlerSelector;
    private MockObject|IncomingMessageHandlerInterface $messageHandler;
    private MockObject|LoggerInterface $logger;
    private MockObject|ContainerInterface $container;
    private MockObject|ParameterBagInterface $parameterBag;
    private MockObject|ApiLoggerInterface $apiLogger;
    private MockObject|PrefilledTypeProvider $resourceTypeProvider;
    private MockObject|FieldsValidator $fieldsValidator;
    private MockObject|TranslatorInterface $translator;
    private MockObject|GlobalConfigInterface $globalConfig;
    private MockObject|MessageBagInterface $messageBag;
    private MockObject|SchemaPathProcessor $schemaPathProcessor;
    private MockObject|MessageFormatter $messageFormatter;

    protected function setUp(): void
    {
        $this->messageHandlerSelector = $this->createMock(MessageHandlerSelector::class);
        $this->messageHandler = $this->createMock(IncomingMessageHandlerInterface::class);
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->container = $this->createMock(ContainerInterface::class);
        $this->parameterBag = $this->createMock(ParameterBagInterface::class);
        $this->apiLogger = $this->createMock(ApiLoggerInterface::class);
        $this->resourceTypeProvider = $this->createMock(PrefilledTypeProvider::class);
        $this->fieldsValidator = $this->createMock(FieldsValidator::class);
        $this->translator = $this->createMock(TranslatorInterface::class);
        $this->globalConfig = $this->createMock(GlobalConfigInterface::class);
        $this->messageBag = $this->createMock(MessageBagInterface::class);
        $this->schemaPathProcessor = $this->createMock(SchemaPathProcessor::class);
        $this->messageFormatter = $this->createMock(MessageFormatter::class);

        $this->controller = $this->getMockBuilder(XBeteiligungRestController::class)
            ->setConstructorArgs([
                $this->apiLogger,
                $this->resourceTypeProvider,
                $this->fieldsValidator,
                $this->translator,
                $this->logger,
                $this->globalConfig,
                $this->messageBag,
                $this->schemaPathProcessor,
                $this->messageFormatter
            ])
            ->onlyMethods(['getParameter'])
            ->getMock();
    }

    /**
     * Helper method to execute procedure tests with common setup.
     */
    private function executeProcedureTest(
        string $methodName,
        string $xmlData,
        string $responsePayload,
        string $authToken,
        bool $shouldThrowException = false,
        ?string $routingKey = self::VALID_ROUTING_KEY
    ): Response {
        // Mock the getParameter method to return a valid token
        $this->controller->expects($this->once())
            ->method('getParameter')
            ->with('addon_xbeteiligung_async_rest_authentication')
            ->willReturn('valid-token');

        // Mock the request
        $request = new Request([], [], [], [], [], [], $xmlData);
        if ($authToken) {
            $request->headers->set('X-Addon-XBeteiligung-Authorization', $authToken);
        }
        if (null !== $routingKey) {
            $request->headers->set('X-Addon-XBeteiligung-RoutingKey', $routingKey);
        }

        // Setup service response (only if we expect the service to be called)
        // For empty payload or missing routing key, the service won't be called due to early validation
        if (!empty($xmlData) && null !== $routingKey && '' !== $routingKey) {
            if ($shouldThrowException) {
                $this->messageHandlerSelector->expects($this->once())
                    ->method('getHandlerForMessageType')
                    ->willReturn($this->messageHandler);
                $this->messageHandler->expects($this->once())
                    ->method('handleIncomingMessage')
                    ->willThrowException(new \Exception('Service error'));
            } else {
                $responseValue = new ResponseValue();
                $responseValue->setMessageXml($responsePayload);

                $this->messageHandlerSelector->expects($this->once())
                    ->method('getHandlerForMessageType')
                    ->willReturn($this->messageHandler);
                $this->messageHandler->expects($this->once())
                    ->method('handleIncomingMessage')
                    ->with($xmlData, true, $routingKey)
                    ->willReturn($responseValue);
            }
        } else {
            // For empty payload or missing routing key, service should never be called
            $this->messageHandlerSelector->expects($this->never())
                ->method('getHandlerForMessageType');
        }

        // Execute controller method
        return $this->controller->$methodName($request, $this->messageHandlerSelector, $this->logger);
    }

    /**
     * Helper method to execute simple authentication tests.
     */
    private function executeAuthenticationTest(string $methodName, string $authToken): Response
    {
        // Mock the getParameter method to return a valid token
        $this->controller->expects($this->once())
            ->method('getParameter')
            ->with('addon_xbeteiligung_async_rest_authentication')
            ->willReturn('valid-token');

        // Mock the request
        $request = new Request();
        if ($authToken) {
            $request->headers->set('X-Addon-XBeteiligung-Authorization', $authToken);
        }

        // Execute controller method
        return $this->controller->$methodName($request, $this->messageHandlerSelector, $this->logger);
    }

    /**
     * Helper method to assert successful responses.
     */
    private function assertSuccessfulResponse(Response $response, string $expectedContent): void
    {
        static::assertSame(200, $response->getStatusCode());
        static::assertSame($expectedContent, $response->getContent());
        static::assertSame('application/xml', $response->headers->get('Content-Type'));
    }

    /**
     * Helper method to assert unauthorized responses.
     */
    private function assertUnauthorizedResponse(Response $response): void
    {
        static::assertSame(401, $response->getStatusCode());
        static::assertSame('Unauthorized', $response->getContent());
    }

    /**
     * Helper method to assert bad request responses.
     */
    private function assertBadRequestResponse(Response $response, string $expectedMessage): void
    {
        static::assertSame(400, $response->getStatusCode());
        static::assertSame($expectedMessage, $response->getContent());
    }

    /**
     * Helper method to assert server error responses.
     */
    private function assertServerErrorResponse(Response $response, string $expectedMessageSubstring): void
    {
        static::assertSame(500, $response->getStatusCode());
        static::assertStringContainsString($expectedMessageSubstring, $response->getContent());
    }

    public function testCreateProcedureWithValidData(): void
    {
        $xmlData = '<ns5:kommunal.Initiieren.0401 xmlns:ns5="https://www.xleitstelle.de/xbeteiligung/12">test content</ns5:kommunal.Initiieren.0401>';
        $expectedResponse = '<xml>response</xml>';

        $response = $this->executeProcedureTest(
            'createProcedure',
            $xmlData,
            $expectedResponse,
            'Bearer valid-token'
        );

        $this->assertSuccessfulResponse($response, $expectedResponse);
    }

    public function testCreateProcedureWithInvalidToken(): void
    {
        $response = $this->executeAuthenticationTest('createProcedure', 'Bearer invalid-token');
        $this->assertUnauthorizedResponse($response);
    }

    public function testCreateProcedureWithEmptyPayload(): void
    {
        $response = $this->executeProcedureTest(
            'createProcedure',
            '',
            '',
            'Bearer valid-token'
        );
        $this->assertBadRequestResponse($response, 'Empty request payload');
    }

    public function testUpdateProcedureWithValidData(): void
    {
        $xmlData = '<ns5:kommunal.Aktualisieren.0402 xmlns:ns5="https://www.xleitstelle.de/xbeteiligung/12">test update</ns5:kommunal.Aktualisieren.0402>';
        $expectedResponse = '<xml>update response</xml>';

        $response = $this->executeProcedureTest(
            'updateProcedure',
            $xmlData,
            $expectedResponse,
            'Bearer valid-token'
        );

        $this->assertSuccessfulResponse($response, $expectedResponse);
    }

    public function testUpdateProcedureWithInvalidToken(): void
    {
        $response = $this->executeAuthenticationTest('updateProcedure', 'Bearer invalid-token');
        $this->assertUnauthorizedResponse($response);
    }

    public function testUpdateProcedureWithEmptyPayload(): void
    {
        $response = $this->executeProcedureTest(
            'updateProcedure',
            '',
            '',
            'Bearer valid-token'
        );
        $this->assertBadRequestResponse($response, 'Empty request payload');
    }

    public function testUpdateProcedureWithServiceException(): void
    {
        $xmlData = '<ns5:kommunal.Aktualisieren.0402 xmlns:ns5="https://www.xleitstelle.de/xbeteiligung/12">test update</ns5:kommunal.Aktualisieren.0402>';

        $response = $this->executeProcedureTest(
            'updateProcedure',
            $xmlData,
            '',
            'Bearer valid-token',
            true
        );

        $this->assertServerErrorResponse($response, 'Error processing procedure update request');
    }

    public function testCreateProcedureWithServiceException(): void
    {
        $xmlData = '<ns5:kommunal.Initiieren.0401 xmlns:ns5="https://www.xleitstelle.de/xbeteiligung/12">test</ns5:kommunal.Initiieren.0401>';

        $response = $this->executeProcedureTest(
            'createProcedure',
            $xmlData,
            '',
            'Bearer valid-token',
            true
        );

        $this->assertServerErrorResponse($response, 'Error processing procedure creation request');
    }

    public function testAuthTokenWithoutBearerPrefix(): void
    {
        $xmlData = '<ns5:kommunal.Initiieren.0401 xmlns:ns5="https://www.xleitstelle.de/xbeteiligung/12">test</ns5:kommunal.Initiieren.0401>';
        $expectedResponse = '<xml>response</xml>';

        $response = $this->executeProcedureTest(
            'createProcedure',
            $xmlData,
            $expectedResponse,
            'valid-token' // Without "Bearer " prefix
        );

        $this->assertSuccessfulResponse($response, $expectedResponse);
    }

    public function testCreateProcedureWithMissingRoutingKey(): void
    {
        $xmlData = '<ns5:kommunal.Initiieren.0401 xmlns:ns5="https://www.xleitstelle.de/xbeteiligung/12">test</ns5:kommunal.Initiieren.0401>';

        $response = $this->executeProcedureTest(
            'createProcedure',
            $xmlData,
            '',
            'Bearer valid-token',
            false,
            null // No routing key
        );

        $this->assertBadRequestResponse($response, 'No routing key provided in X-Addon-XBeteiligung-RoutingKey header');
    }

    public function testCreateProcedureWithEmptyRoutingKey(): void
    {
        $xmlData = '<ns5:kommunal.Initiieren.0401 xmlns:ns5="https://www.xleitstelle.de/xbeteiligung/12">test</ns5:kommunal.Initiieren.0401>';

        $response = $this->executeProcedureTest(
            'createProcedure',
            $xmlData,
            '',
            'Bearer valid-token',
            false,
            '' // Empty routing key
        );

        $this->assertBadRequestResponse($response, 'No routing key provided in X-Addon-XBeteiligung-RoutingKey header');
    }

    public function testUpdateProcedureWithMissingRoutingKey(): void
    {
        $xmlData = '<ns5:kommunal.Aktualisieren.0402 xmlns:ns5="https://www.xleitstelle.de/xbeteiligung/12">test update</ns5:kommunal.Aktualisieren.0402>';

        $response = $this->executeProcedureTest(
            'updateProcedure',
            $xmlData,
            '',
            'Bearer valid-token',
            false,
            null // No routing key
        );

        $this->assertBadRequestResponse($response, 'No routing key provided in X-Addon-XBeteiligung-RoutingKey header');
    }
}
