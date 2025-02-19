<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Tests\Logic\DataFixtures;

use DateInterval;
use DateTime;
use DemosEurope\DemosplanAddon\Contracts\Entities\CustomerInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\GisLayerCategoryInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\GisLayerInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\OrgaInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\ProcedureInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\ProcedurePhaseInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\ProcedureSettingsInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\ProcedureTypeInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\RoleInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\UserInterface;
use DemosEurope\DemosplanAddon\Contracts\Form\Procedure\AbstractProcedureFormTypeInterface;
use DemosEurope\DemosplanAddon\Contracts\Services\CurrentUserProviderInterface;
use DemosEurope\DemosplanAddon\Contracts\Services\ProcedureServiceInterface;
use DemosEurope\DemosplanAddon\Contracts\Services\ProcedureServiceStorageInterface;
use DemosEurope\DemosplanAddon\Contracts\Services\ProcedureTypeServiceInterface;
use DemosEurope\DemosplanAddon\Contracts\Services\TransactionServiceInterface;
use DemosEurope\DemosplanAddon\Contracts\UserHandlerInterface;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\Diplanbau\KommunaleProcedureCreater;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\XBeteiligungResponseMessageFactory;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\XBeteiligungService;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\Translation\Translator;
use Symfony\Contracts\Translation\TranslatorInterface;

class MockFactory extends TestCase
{
    private ProcedureInterface|MockObject|null $procedure;
    protected XBeteiligungService $xBeteiligungService;

    public function createNewMock(string $className): MockObject
    {
        return $this->createMock($className);
    }

    public function getResponseMessageFactoryMock(): MockObject|XBeteiligungResponseMessageFactory
    {
        return $this->createMock(XBeteiligungResponseMessageFactory::class);
    }

    public function getTranslatorMock(): MockObject|Translator
    {
        return $this->createMock(Translator::class);
    }

    public function getProcedureTypeService(): ProcedureTypeServiceInterface
    {
        return $this->createMock(ProcedureTypeServiceInterface::class);
    }

    public function getTransActionServiceInterfaceMock(): TransactionServiceInterface
    {
        return $this->createMock(TransactionServiceInterface::class);
    }

    public function getXBeteiligungServiceMock(): XBeteiligungService
    {
        return $this->xBeteiligungService;
    }

    public function getProcedureMock(): MockObject|ProcedureInterface
    {
        $procedureMock = $this->createMock(ProcedureInterface::class);
        $procedureMock->method('getId')->willReturn('a2780f23-160b-4a8b-a48b-f9448dc1bc24');
        $procedureMock->method('getOrgaId')->willReturn('a2734f23-175b-4a8b-a48b-f9351dc1bc24');

        return $procedureMock;
    }

    public function getLoggerInterfaceMock(): LoggerInterface
    {
        return $this->createMock(LoggerInterface::class);

    }

    public function getUserHandlerMock(): UserHandlerInterface
    {
        return $this->createMock(UserHandlerInterface::class);
    }

    public function getTranslatorInterfaceMock(): TranslatorInterface
    {
        return $this->createMock(TranslatorInterface::class);
    }

    public function getUser1(string $cockpitUserId = ''): UserInterface
    {
        $userMock = $this->createMock(UserInterface::class);
        $userMock->method('getId')->willReturnCallback(
            function () use ($cockpitUserId): string
            {
                return '' === $cockpitUserId ? 'a2780f23-160b-4a8b-a48b-f9351dc1bc24' : $cockpitUserId;
            }
        );
        $userMock->method('getOrga')->willReturn($this->getOrgaMock());
        $userMock->method('getOrganisationId')->willReturn('a2734f23-175b-4a8b-a48b-f9351dc1bc24');
        $userMock->method('getEmail')->willReturn('user1@test.de');
        $userMock->method('getLogin')->willReturn('FHHNET\\ZinkDav');
        $userMock->method('getLastname')->willReturn('Tester user1');
        $userMock->method('getFirstname')->willReturn('another FP');
        $userMock->method('getNewsletter')->willReturn(false);
        $userMock->method('getNoPiwik')->willReturn(true);
        $userMock->method('getForumNotification')->willReturn(false);
        $userMock->method('getDplanroles')->willReturn(new ArrayCollection([$this->getRoleFP()]));
        $userMock->method('getCurrentCustomer')->willReturn($this->getCustomerMock());

        return $userMock;
    }

    public function getOrgaMock()
    {
        $orga = $this->createMock(OrgaInterface::class);
        $orga->method('getId')->willReturn('a2734f23-175b-4a8b-a48b-f9351dc1bc24');
        $orga->method('getName')->willReturn('Test Orga');

        return $orga;
    }

    public function getRoleFP(): RoleInterface
    {
        $role = $this->createMock(RoleInterface::class);
        $role->method('getName')->willReturn('Fachplaner-Admin');
        $role->method('getCode')->willReturn(RoleInterface::PLANNING_AGENCY_ADMIN);
        $role->method('getGroupCode')->willReturn(RoleInterface::GLAUTH);
        $role->method('getGroupName')->willReturn('Kommune');

        return $role;
    }

    public function getCustomerMock(): CustomerInterface
    {
        $customerMock = $this->createMock(CustomerInterface::class);
        $customerMock->method('getId')->willReturn('1');
        $customerMock->method('getName')->willReturn('Test Customer');

        return $customerMock;
    }

    public function getKommunaleProcedureCreatorMock(): KommunaleProcedureCreater
    {
        return $this->createMock(KommunaleProcedureCreater::class);

    }

    public function getProcedureType(): MockObject|ProcedureTypeInterface
    {
        $procedureType = $this->createMock(ProcedureTypeInterface::class);
        $procedureType->method('getId')->willReturn('a2788d23-175b-4a8b-a48b-f9351dc1bc24');

        return $procedureType;
    }

    public function getProcedureServiceStorage(): ProcedureServiceStorageInterface
    {
        $procedureServiceStorage = $this->createMock(ProcedureServiceStorageInterface::class);
        $procedureServiceStorage->method('administrationNewHandler')
            ->willReturnCallback(
                function ($data, $fhhUserId) {
                    $this->procedure = $this->getProcedureMock();
                    $this->procedure->method('getName')->willReturn(isset($data['r_name']) ? $data['r_name'] : null);
                    $this->procedure->method('getExternalName')->willReturn(isset($data['r_name']) ? $data['r_name'] : null);
                    $this->procedure->method('getDesc')->willReturn(isset($data['r_desc']) ? $data['r_desc'] : null);
                    $this->procedure->method('getExternalDesc')->willReturn(isset($data['r_externalDesc']) ? $data['r_externalDesc'] : null);
                    $this->procedure->method('getStartDate')->willReturn(isset($data['r_startdate']) ? new DateTime($data['r_startdate']) : '');
                    $this->procedure->method('getPublicParticipationStartDate')->willReturn(isset($data['r_startdate']) ? new DateTime($data['r_startdate']) : '');
                    $this->procedure->method('getEndDate')->willReturn(isset($data['r_enddate']) ? new DateTime($data['r_enddate']) : '');
                    $this->procedure->method('getPublicParticipationEndDate')->willReturn(isset($data['r_enddate']) ? new DateTime($data['r_enddate']) : '');
                    $this->procedure->method('getPublicParticipationPublicationEnabled')->willReturn(false);
                    $this->procedure->method('getOrga')->willReturn(isset($data['orgaId']) ? $this->getOrgaMock() : null);
                    $this->procedure->method('getOrgaName')->willReturn(isset($data['orgaName']) ? $data['orgaName'] : null);
                    //$procedure->method('Was Muss Hier gesetzt werden?')->willReturn(isset($data['action']));
                    $this->procedure->method('getMaster')->willReturn('false');
                    //$procedure->method('Was Muss Hier gesetzt werden?')->willReturn(isset($data['r_copymaster']) ? $data['r_copymaster'] : '');
                    $this->procedure->method('getAgencyMainEmailAddress')->willReturn(isset($data[AbstractProcedureFormTypeInterface::AGENCY_MAIN_EMAIL_ADDRESS]) ? $data[AbstractProcedureFormTypeInterface::AGENCY_MAIN_EMAIL_ADDRESS] : '');

                    $procedureType = $this->getProcedureType();
                    $this->procedure->method('getProcedureType')->willReturn($procedureType);
                    $procedureType->method('getId')
                        ->willReturn($data['r_procedure_type'] ?? null);

                    $this->procedure->method('getXtaPlanId')->willReturn(isset($data['xtaPlanId']) ? $data['xtaPlanId'] : null);
                    return $this->procedure;
                }
            );

        return $procedureServiceStorage;
    }

    public function getCurrentUserProviderInterfaceMock(): CurrentUserProviderInterface
    {
        return $this->createMock(CurrentUserProviderInterface::class);

    }

    public function getProcedureServiceInterface(): ProcedureServiceInterface
    {
        $procedureServiceInterfaceMock = $this->createMock(ProcedureServiceInterface::class);
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

    public function getProcedureTypeServiceInterfaceMock(): ProcedureTypeServiceInterface
    {
        $procedureTypeServiceInterfaceMock = $this->createMock(ProcedureTypeServiceInterface::class);
        $procedureTypeServiceInterfaceMock->method('getProcedureTypeByName')
            ->willReturn($this->getProcedureType());

        return $procedureTypeServiceInterfaceMock;
    }

    public function getEntityManagerMock(): EntityManagerInterface|MockObject
    {
        $entityManagerMock = $this->createMock(EntityManagerInterface::class);
        $repositoryMock = $this->createMock(EntityRepository::class);
        $entityManagerMock->method('getRepository')->willReturn($repositoryMock);

        return $entityManagerMock;
    }

    public function getTestProcedure(MockObject $procedureSettingsMock)
    {
        $gisLayerCategoryInterfaceMock = $this->createMock(
            GisLayerCategoryInterface::class
        );
        $gisMo = $this->createMock(GisLayerInterface::class);
        $gisMo->method('getName')->willReturn('basemap');
        $gisMo->method('getUrl')->willReturn('https://sgx.geodatenzentrum.de/wms_basemapde');
        $gisMo->method('getLayerVersion')->willReturn('1.3.0');
        $gisMo->method('getLayers')->willReturn('de_basemapde_web_raster_farbe');
        $gisLayerCategoryInterfaceMock->method('getGisLayers')->willReturn(new ArrayCollection([$gisMo]));
        $this->gisLayerCategoryRepository->method('getRootLayerCategory')->willReturn($gisLayerCategoryInterfaceMock);

        $procedure = $this->createMock(ProcedureInterface::class);
        $procedure->method('getId')->willReturn('7606f622-439b-4929-8625-0856c161409e');
        $procedure->method('getXtaPlanId')->willReturn('7606f622-439b-4929-8625-0856c161409e');
        $orga = $this->createMock(OrgaInterface::class);
        $orga->method('getName')->willReturn('SoFreshAndSoClean');
        $procedure->method('getOrga')->willReturn($orga);
        $procedure->method('getName')->willReturn('Mars 2050');
        $procedure->method('getDesc')->willReturn('return will be planned on the fly :)');
        $startDate = new DateTime();
        $endDate = (new DateTime())->add(new DateInterval('P7D'));
        $procedure->method('getStartDate')->willReturn($startDate);
        $procedure->method('getEndDate')->willReturn($endDate);
        $procedurePhaseMock = $this->createMock(ProcedurePhaseInterface::class);
        $procedurePhaseMock->method('getStartDate')->willReturn($startDate);
        $procedurePhaseMock->method('getEndDate')->willReturn($endDate);
        $procedure->method('getPhaseObject')->willReturn($procedurePhaseMock);
        $procedure->method('getPublicParticipationPhaseObject')->willReturn($procedurePhaseMock);
        $procedure->method('getPublicParticipationPhase')->willReturn('configuration');
        $procedure->method('getPhase')->willReturn('earlyparticipation');
        $procedure->method('getSettings')->willReturn($procedureSettingsMock);
        $this->procedureNewsService->method('getProcedureNewsAdminList')->willReturn(
            [
                'result' => [
                    [
                        'title' => 'Test Titel',
                        'text' => 'Test Inhalt',
                        'roles' => [
                            [
                                'groupCode' => 'GPSORG',
                                'code' => RoleInterface::PLANNING_AGENCY_WORKER
                            ]
                        ]
                    ],
                    [
                        'title' => 'noch ein <p>Test Titel</p>',
                        'text' => '<b>Test</b> These Tags will be removed via strip_tags()',
                        'roles' => [
                            [
                                'groupCode' => 'GPSORG',
                                'code' => RoleInterface::CITIZEN
                            ]
                        ]
                    ],
                ]
            ]
        );
        return $procedure;
    }

    public function getTestProcedureSettings(bool $withBBox = true)
    {
        $procedureSettingsMock = $this->createMock(
            ProcedureSettingsInterface::class
        );
        $procedureSettingsMock->method('getTerritory')
            ->willReturn(self::GEO_JSON_FG_TEST);
        if ($withBBox) {
            $procedureSettingsMock->method('getMapExtent')
                ->willReturn(
                    '904640.92309477,7067292.9633037,1195347.6354542,7350657.5148909'
                );
        }
        if (!$withBBox) {
            $procedureSettingsMock->method('getMapExtent')
                ->willReturn('');
        }

        return $procedureSettingsMock;
    }

    public function validateProcedureXML(string $procedureXml): void
    {
        $isValid = $this->xBeteiligungService->isValidMessage($procedureXml, true);
        self::assertTrue($isValid);
    }

}