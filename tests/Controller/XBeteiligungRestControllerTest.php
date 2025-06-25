<?php

declare(strict_types=1);

namespace DemosEurope\DemosplanAddon\XBeteiligung\Tests\Controller;

use DemosEurope\DemosplanAddon\Contracts\Config\GlobalConfigInterface;
use DemosEurope\DemosplanAddon\Contracts\Logger\ApiLoggerInterface;
use DemosEurope\DemosplanAddon\Contracts\MessageBagInterface;
use DemosEurope\DemosplanAddon\XBeteiligung\Controller\XBeteiligungRestController;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\ResponseValue;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\XBeteiligungService;
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
    private XBeteiligungRestController $controller;
    private MockObject|XBeteiligungService $xBeteiligungService;
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
        $this->xBeteiligungService = $this->createMock(XBeteiligungService::class);
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
        string $expectedMessageType,
        string $responsePayload,
        string $authToken,
        bool $shouldThrowException = false
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

        // Setup service response (only if we expect the service to be called)
        // For empty payload, the service won't be called due to early validation
        if (!empty($xmlData)) {
            if ($shouldThrowException) {
                $this->xBeteiligungService->expects($this->once())
                    ->method('determineMessageContextAndDelegateAction')
                    ->willThrowException(new \Exception('Service error'));
            } else {
                $responseValue = new ResponseValue();
                $responseValue->setPayload($responsePayload);

                $this->xBeteiligungService->expects($this->once())
                    ->method('determineMessageContextAndDelegateAction')
                    ->with($this->callback(function($message) use ($xmlData, $expectedMessageType) {
                        return is_array($message)
                            && isset($message['messageData'])
                            && $message['messageData'] === $xmlData
                            && isset($message['messageTypeCode'])
                            && $message['messageTypeCode'] === $expectedMessageType;
                    }))
                    ->willReturn($responseValue);
            }
        } else {
            // For empty payload, service should never be called
            $this->xBeteiligungService->expects($this->never())
                ->method('determineMessageContextAndDelegateAction');
        }

        // Execute controller method
        return $this->controller->$methodName($request, $this->xBeteiligungService, $this->logger);
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
        return $this->controller->$methodName($request, $this->xBeteiligungService, $this->logger);
    }

    /**
     * Helper method to assert successful responses.
     */
    private function assertSuccessfulResponse(Response $response, string $expectedContent): void
    {
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($expectedContent, $response->getContent());
        $this->assertEquals('application/xml', $response->headers->get('Content-Type'));
    }

    /**
     * Helper method to assert unauthorized responses.
     */
    private function assertUnauthorizedResponse(Response $response): void
    {
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(401, $response->getStatusCode());
        $this->assertEquals('Unauthorized', $response->getContent());
    }

    /**
     * Helper method to assert bad request responses.
     */
    private function assertBadRequestResponse(Response $response, string $expectedMessage): void
    {
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals($expectedMessage, $response->getContent());
    }

    /**
     * Helper method to assert server error responses.
     */
    private function assertServerErrorResponse(Response $response, string $expectedMessageSubstring): void
    {
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(500, $response->getStatusCode());
        $this->assertStringContainsString($expectedMessageSubstring, $response->getContent());
    }

    public function testCreateProcedureWithValidData(): void
    {
        $xmlData = '<xbeteiligung:planung2Beteiligung.BeteiligungKommunalNeu.0401>test</xbeteiligung:planung2Beteiligung.BeteiligungKommunalNeu.0401>';
        $expectedMessageType = 'kommunal.Initiieren.0401';
        $expectedResponse = '<xml>response</xml>';

        $response = $this->executeProcedureTest(
            'createProcedure',
            $xmlData,
            $expectedMessageType,
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
            '',
            'Bearer valid-token'
        );
        $this->assertBadRequestResponse($response, 'Empty request payload');
    }

    public function testUpdateProcedureWithValidData(): void
    {
        $xmlData = '<xbeteiligung:planung2Beteiligung.BeteiligungKommunalAktualisieren.0402>test update</xbeteiligung:planung2Beteiligung.BeteiligungKommunalAktualisieren.0402>';
        $expectedMessageType = 'kommunal.Aktualisieren.0402';
        $expectedResponse = '<xml>update response</xml>';

        $response = $this->executeProcedureTest(
            'updateProcedure',
            $xmlData,
            $expectedMessageType,
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
            '',
            'Bearer valid-token'
        );
        $this->assertBadRequestResponse($response, 'Empty request payload');
    }

    public function testUpdateProcedureWithServiceException(): void
    {
        $xmlData = '<xbeteiligung:planung2Beteiligung.BeteiligungKommunalAktualisieren.0402>test update</xbeteiligung:planung2Beteiligung.BeteiligungKommunalAktualisieren.0402>';

        $response = $this->executeProcedureTest(
            'updateProcedure',
            $xmlData,
            'kommunal.Aktualisieren.0402',
            '',
            'Bearer valid-token',
            true
        );

        $this->assertServerErrorResponse($response, 'Error processing procedure update request');
    }

    public function testCreateProcedureWithServiceException(): void
    {
        $xmlData = '<xbeteiligung:planung2Beteiligung.BeteiligungKommunalNeu.0401>test</xbeteiligung:planung2Beteiligung.BeteiligungKommunalNeu.0401>';

        $response = $this->executeProcedureTest(
            'createProcedure',
            $xmlData,
            'kommunal.Initiieren.0401',
            '',
            'Bearer valid-token',
            true
        );

        $this->assertServerErrorResponse($response, 'Error processing procedure creation request');
    }

    public function testAuthTokenWithoutBearerPrefix(): void
    {
        $xmlData = '<xbeteiligung:planung2Beteiligung.BeteiligungKommunalNeu.0401>test</xbeteiligung:planung2Beteiligung.BeteiligungKommunalNeu.0401>';
        $expectedResponse = '<xml>response</xml>';

        $response = $this->executeProcedureTest(
            'createProcedure',
            $xmlData,
            'kommunal.Initiieren.0401',
            $expectedResponse,
            'valid-token' // Without "Bearer " prefix
        );

        $this->assertSuccessfulResponse($response, $expectedResponse);
    }
}
