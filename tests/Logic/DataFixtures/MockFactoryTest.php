<?php

declare(strict_types=1);

/**
 * This file is part of the package demosplan.
 *
 * (c) 2010-present DEMOS E-Partizipation GmbH, for more information see the license file.
 *
 * All rights reserved
 *
 * This class is a helper for creating mocks, not a test class itself.
 * It's used by other test classes to create mock objects.
 */

namespace DemosEurope\DemosplanAddon\XBeteiligung\Tests\Logic\DataFixtures;

use DateTime;
use DemosEurope\DemosplanAddon\Contracts\Entities\OrgaInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\ProcedureInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\ProcedurePhaseInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\ProcedureSettingsInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\ProcedureTypeInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\UserInterface;
use DemosEurope\DemosplanAddon\Contracts\Form\Procedure\AbstractProcedureFormTypeInterface;
use DemosEurope\DemosplanAddon\Contracts\Services\CurrentUserProviderInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\CustomerInterface;
use DemosEurope\DemosplanAddon\Contracts\Services\CustomerServiceInterface;
use DemosEurope\DemosplanAddon\Contracts\Services\OrgaServiceInterface;
use DemosEurope\DemosplanAddon\Contracts\Services\ProcedureServiceInterface;
use DemosEurope\DemosplanAddon\Contracts\Services\ProcedureServiceStorageInterface;
use DemosEurope\DemosplanAddon\Contracts\Services\ProcedureTypeServiceInterface;
use DemosEurope\DemosplanAddon\Contracts\Services\TransactionServiceInterface;
use DemosEurope\DemosplanAddon\Contracts\UserHandlerInterface;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\Kommunale\ProcedurePhaseExtractor;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\MessageFactory\KommunaleMessageFactory;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\MessageFactory\PlanfeststellungMessageFactory;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\MessageFactory\RaumordnungMessageFactory;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\XBeteiligungAgsService;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\XBeteiligungCustomerMappingService;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\XBeteiligungMapService;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Illuminate\Support\Collection;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\Translation\Translator;

class MockFactoryTest
{
    private TestCase $testCase;
    private ProcedureInterface|MockObject|null $procedure;

    public function __construct(TestCase $testCase)
    {
        $this->testCase = $testCase;
    }

    public function getTranslatorMock(): MockObject|Translator
    {
        return $this->testCase->createMockObject(Translator::class);
    }

    public function getProcedureTypeService(): ProcedureTypeServiceInterface
    {
        return $this->testCase->createMockObject(ProcedureTypeServiceInterface::class);
    }

    public function getTransactionServiceInterfaceMock(): TransactionServiceInterface
    {
        $transactionServiceInterfaceMock = $this->testCase->createMockObject(TransactionServiceInterface::class);
        $transactionServiceInterfaceMock->method('executeAndFlushInTransaction')->willReturnCallback(
            function ($callback) {
                return $callback();
            }
        );

        return $transactionServiceInterfaceMock;
    }

    public function getKommunaleResponseMessageFactory()
    {
        return $this->testCase->createMockObject(KommunaleMessageFactory::class);
    }

    public function getPlanfeststellungResponseMessageFactory()
    {
        return $this->testCase->createMockObject(PlanfeststellungMessageFactory::class);
    }

    public function getRaumordnungResponseMessageFactory()
    {
        return $this->testCase->createMockObject(RaumordnungMessageFactory::class);
    }

    public function getProcedureMock(): MockObject|ProcedureInterface
    {
        $procedureMock = $this->testCase->createMockObject(ProcedureInterface::class);
        $procedureMock->method('getId')->willReturn('a2780f23-160b-4a8b-a48b-f9448dc1bc24');
        $procedureMock->method('getOrgaId')->willReturn('a2734f23-175b-4a8b-a48b-f9351dc1bc24');

        return $procedureMock;
    }

    public function getProcedureSettingsMock(): ProcedureSettingsInterface
    {
        $procedureSettingsMock = $this->testCase->createMockObject(ProcedureSettingsInterface::class);
        $procedureSettingsMock->method('getTerritory')->willReturn('{"type":"Polygon","coordinates":[[[1122490.3573962983,7071484.285754054],[1122482.2362970402,7071490.40680759],[1122478.999109264,7071492.845304786],[1122475.2042036585,7071495.781567061],[1122471.5251732466,7071498.5058959145],[1122471.360964572,7071498.627772408],[1122469.9660731317,7071499.224038446],[1122463.02764217,7071505.135395698],[1122451.0108444085,7071515.071890943],[1122435.128717185,7071525.847386543],[1122434.268690556,7071526.4304593485],[1122410.0366806341,7071547.389219202],[1122421.1579644377,7071561.588730869],[1122441.4799638607,7071587.54543312],[1122456.3661907252,7071606.558539621],[1122430.3149269223,7071588.72595191],[1122402.707278053,7071568.131007109],[1122396.0806524877,7071563.187822573],[1122350.858337287,7071529.455500149],[1122335.8999894143,7071516.840991722],[1122325.8392592138,7071506.425138527],[1122316.3031768298,7071497.64565565],[1122300.8185980634,7071483.391426601],[1122293.7498677047,7071475.827006324],[1122290.965665827,7071472.846312364],[1122288.0672533428,7071469.744618191],[1122283.0888017584,7071464.416527989],[1122299.6096302131,7071442.753501105],[1122320.7897785942,7071414.985345006],[1122334.505386014,7071395.280075988],[1122332.6508171991,7071393.875809563],[1122332.830013415,7071393.636017209],[1122335.6712878277,7071389.863623659],[1122356.6622858304,7071362.0278163785],[1122359.7101755266,7071358.9416223075],[1122366.7414734326,7071366.0306309415],[1122363.7870400304,7071369.307077891],[1122340.8624427796,7071399.7128248485],[1122320.6475335688,7071426.528118282],[1122317.0646003806,7071431.280250348],[1122319.3874984237,7071433.704692957],[1122362.406354156,7071478.592353006],[1122388.8004967493,7071460.632449447],[1122400.3920099915,7071477.869576714],[1122411.4351000325,7071469.425922151],[1122428.9701568882,7071458.0419582715],[1122432.4341652468,7071454.964494912],[1122435.8556671,7071452.624101453],[1122437.2985384408,7071451.763145272],[1122445.9607397611,7071446.593966149],[1122456.4597913737,7071440.3308425555],[1122450.2102129434,7071445.540627205],[1122452.4186055246,7071450.145783418],[1122454.3879646042,7071454.25186411],[1122455.6115985825,7071454.862045752],[1122475.7322453903,7071464.8924076725],[1122476.1818033245,7071465.115902283],[1122490.3573962983,7071484.285754054]]]}');
        $procedureSettingsMock->method('getBoundingBox')->willReturn('1121972.185910,7070987.516246,1122801.260288,7071977.983916');
        $procedureSettingsMock->method('getMapExtent')->willReturn('1122283.0888018,7071358.9416223,1122490.3573963,7071606.5585396');

        return $procedureSettingsMock;
    }

    public function getLoggerInterfaceMock(): LoggerInterface
    {
        return $this->testCase->createMockObject(LoggerInterface::class);

    }

    public function getUserHandlerMock(): UserHandlerInterface
    {
        return $this->testCase->createMockObject(UserHandlerInterface::class);
    }

    public function getUserMock(): UserInterface
    {
        $userMock = $this->testCase->createMockObject(UserInterface::class);
        $userMock->method('getId')->willReturn('a2780f23-160b-4a8b-a48b-f9448dc1bc24');
        $userMock->method('getName')->willReturn('Admin User');
        $userMock->method('isPlanner')->willReturn(true);

        return $userMock;
    }

    public function getOrgaMock()
    {
        $orga = $this->testCase->createMockObject(OrgaInterface::class);
        $orga->method('getId')->willReturn('a2734f23-175b-4a8b-a48b-f9351dc1bc24');
        $orga->method('getName')->willReturn('Musterzuständigkeit');
        $orga->method('getUsers')->willReturn(new Collection(
            [$this->getUserMock()]
        ));

        return $orga;
    }

    public function getProcedureType(): MockObject|ProcedureTypeInterface
    {
        $procedureType = $this->testCase->createMockObject(ProcedureTypeInterface::class);
        $procedureType->method('getId')->willReturn('a2788d23-175b-4a8b-a48b-f9351dc1bc24');

        return $procedureType;
    }

    public function getProcedureServiceStorage(): ProcedureServiceStorageInterface
    {
        $procedureServiceStorage = $this->testCase->createMockObject(ProcedureServiceStorageInterface::class);
        $procedureServiceStorage->method('administrationNewHandler')
            ->willReturnCallback(
                function ($data, $fhhUserId) {
                    $this->procedure = $this->getProcedureMock();
                    $this->procedure->method('getName')->willReturn(isset($data['r_name']) ? $data['r_name'] : null);
                    $this->procedure->method('getExternalName')->willReturn(isset($data['r_name']) ? $data['r_name'] : null);
                    $this->procedure->method('getDesc')->willReturn(isset($data['r_desc']) ? $data['r_desc'] : null);
                    $this->procedure->method('getExternalDesc')->willReturn(isset($data['r_externalDesc']) ? $data['r_externalDesc'] : null);
                    $this->procedure->method('getStartDate')->willReturn(isset($data['r_startdate']) ? new DateTime($data['r_startdate']) : new DateTime());
                    $this->procedure->method('getPublicParticipationStartDate')->willReturn(isset($data['r_startdate']) ? new DateTime($data['r_startdate']) : new DateTime());
                    $this->procedure->method('getEndDate')->willReturn(isset($data['r_enddate']) ? new DateTime($data['r_enddate']) : new DateTime('+1 week'));
                    $this->procedure->method('getPublicParticipationEndDate')->willReturn(isset($data['r_enddate']) ? new DateTime($data['r_enddate']) : new DateTime('+1 week'));
                    $this->procedure->method('getPublicParticipationPublicationEnabled')->willReturn(false);
                    $this->procedure->method('getOrga')->willReturn(isset($data['orgaId']) ? $this->getOrgaMock() : null);
                    $this->procedure->method('getOrgaName')->willReturn(isset($data['orgaName']) ? $data['orgaName'] : null);
                    $this->procedure->method('getMaster')->willReturn('false');
                    $this->procedure->method('getAgencyMainEmailAddress')->willReturn(isset($data[AbstractProcedureFormTypeInterface::AGENCY_MAIN_EMAIL_ADDRESS]) ? $data[AbstractProcedureFormTypeInterface::AGENCY_MAIN_EMAIL_ADDRESS] : '');

                    $this->procedure->method('getSettings')->willReturn($this->getProcedureSettingsMock());

                    // Mock phase objects
                    $phaseObj = $this->testCase->createMockObject(ProcedurePhaseInterface::class);
                    $phaseObj->method('getStartDate')->willReturn(new DateTime());
                    $phaseObj->method('getEndDate')->willReturn(new DateTime('+1 week'));
                    $phaseObj->method('getIteration')->willReturn(1);
                    $this->procedure->method('getPhaseObject')->willReturn($phaseObj);
                    $this->procedure->method('getPublicParticipationPhaseObject')->willReturn($phaseObj);

                    $procedureType = $this->getProcedureType();
                    $this->procedure->method('getProcedureType')->willReturn($procedureType);
                    $procedureType->method('getId')
                        ->willReturn($data['r_procedure_type'] ?? null);

                    $this->procedure->method('getXtaPlanId')->willReturn(isset($data['xtaPlanId']) ? $data['xtaPlanId'] : null);
                    $this->procedure->method('getAuthorizedUsers')->willReturn(new ArrayCollection(
                        [$this->getUserMock()]
                    ));

                    return $this->procedure;
                }
            );

        return $procedureServiceStorage;
    }

    public function getCurrentUserProviderInterfaceMock(): CurrentUserProviderInterface
    {
        return $this->testCase->createMockObject(CurrentUserProviderInterface::class);

    }

    public function getProcedureServiceInterface(): ProcedureServiceInterface
    {
        $procedureServiceInterfaceMock = $this->testCase->createMockObject(ProcedureServiceInterface::class);
        $procedureServiceInterfaceMock->method('getProcedure')->willReturnCallback(
            function (string $procedureId): null|MockObject|ProcedureInterface {
                if (str_contains(strtolower($procedureId), 'invalid')) {

                    return null;
                }
                return $this->procedure ?? $this->getProcedureMock();
            }
        );

        return $procedureServiceInterfaceMock;
    }

    public function getEntityManagerMock(): EntityManagerInterface|MockObject
    {
        $entityManagerMock = $this->testCase->createMockObject(EntityManagerInterface::class);
        $repositoryMock = $this->testCase->createMockObject(EntityRepository::class);
        $entityManagerMock->method('getRepository')->willReturn($repositoryMock);

        return $entityManagerMock;
    }

    public function getProcedurePhaseExtractorMock(): ProcedurePhaseExtractor|MockObject
    {
        return $this->testCase->createMockObject(ProcedurePhaseExtractor::class);
    }

    public function getOrgaServiceInterfaceMock(): OrgaServiceInterface|MockObject
    {
        $orgaServiceInterfaceMock = $this->testCase->createMockObject(OrgaServiceInterface::class);
        $orgaServiceInterfaceMock->method('getOrgaByFields')->willReturn([$this->getOrgaMock()]);

        return $orgaServiceInterfaceMock;
    }

    public function getXBeteiligungMapServiceMock(): XBeteiligungMapService|MockObject
    {
        return $this->testCase->createMockObject(XBeteiligungMapService::class);
    }

    public function getCustomerServiceInterfaceMock(): CustomerServiceInterface|MockObject
    {
        return $this->testCase->createMockObject(CustomerServiceInterface::class);
    }

    public function getXBeteiligungCustomerMappingServiceMock(): XBeteiligungCustomerMappingService|MockObject
    {
        $mock = $this->testCase->createMockObject(XBeteiligungCustomerMappingService::class);
        $mock->method('getCustomerByAgsCode')->willReturnCallback(function ($agsCode) {
            $customerMock = $this->testCase->createMockObject(\DemosEurope\DemosplanAddon\Contracts\Entities\CustomerInterface::class);
            $customerMock->method('getId')->willReturn('test-customer-id');
            return $customerMock;
        });
        return $mock;
    }

    public function getXBeteiligungAgsServiceMock(): XBeteiligungAgsService|MockObject
    {
        $mock = $this->testCase->createMockObject(XBeteiligungAgsService::class);
        $mock->method('extractAgsCodesFromXmlObject')->willReturn([
            'sender' => '02000000000',  // Valid Hamburg AGS code
            'receiver' => '01001000000'  // Valid Schleswig-Holstein AGS code
        ]);
        return $mock;
    }
}
