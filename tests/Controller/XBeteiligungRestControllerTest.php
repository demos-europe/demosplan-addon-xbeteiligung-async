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

    public function testCreateProcedureWithValidData(): void
    {
        // Mock the getParameter method to return a valid token
        $this->controller->expects($this->once())
            ->method('getParameter')
            ->with('xbeteiligung_api_token')
            ->willReturn('valid-token');

        // Prepare test data - now just XML content
        $xmlData = '<xbeteiligung:planung2Beteiligung.BeteiligungKommunalNeu.0401>test</xbeteiligung:planung2Beteiligung.BeteiligungKommunalNeu.0401>';

        // Mock the request
        $request = new Request([], [], [], [], [], [], $xmlData);
        $request->headers->set('X-Addon-XBeteiligung-Authorization', 'Bearer valid-token');

        // Setup response from service
        $responseValue = new ResponseValue();
        $responseValue->setPayload('<xml>response</xml>');

        // Mock service response - check that the correct message structure is passed
        $this->xBeteiligungService->expects($this->once())
            ->method('determineMessageContextAndDelegateAction')
            ->with($this->callback(function($message) use ($xmlData) {
                return is_array($message)
                    && isset($message['messageData'])
                    && $message['messageData'] === $xmlData
                    && isset($message['messageTypeCode'])
                    && $message['messageTypeCode'] === 'kommunal.Initiieren.0401';
            }))
            ->willReturn($responseValue);

        // Execute controller method
        $response = $this->controller->createProcedure($request, $this->xBeteiligungService, $this->logger);

        // Assertions
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('<xml>response</xml>', $response->getContent());
        $this->assertEquals('application/xml', $response->headers->get('Content-Type'));
    }

    public function testCreateProcedureWithInvalidToken(): void
    {
        // Mock the getParameter method to return a valid token
        $this->controller->expects($this->once())
            ->method('getParameter')
            ->with('xbeteiligung_api_token')
            ->willReturn('valid-token');

        // Mock the request with invalid token
        $request = new Request();
        $request->headers->set('X-Addon-XBeteiligung-Authorization', 'Bearer invalid-token');

        // Execute controller method
        $response = $this->controller->createProcedure($request, $this->xBeteiligungService, $this->logger);

        // Assertions
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(401, $response->getStatusCode());
        $this->assertEquals('Unauthorized', $response->getContent());
    }

    public function testCreateProcedureWithEmptyPayload(): void
    {
        // Mock the getParameter method to return a valid token
        $this->controller->expects($this->once())
            ->method('getParameter')
            ->with('xbeteiligung_api_token')
            ->willReturn('valid-token');

        // Mock the request with invalid data (empty body)
        $request = new Request([], [], [], [], [], [], '');
        $request->headers->set('X-Addon-XBeteiligung-Authorization', 'Bearer valid-token');

        // Execute controller method
        $response = $this->controller->createProcedure($request, $this->xBeteiligungService, $this->logger);

        // Assertions
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals('Empty request payload', $response->getContent());
    }
}
