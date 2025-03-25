<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Logic\MessageFactory;

use DemosEurope\DemosplanAddon\XBeteiligung\Enum\ProcedureMessageTyp;
use DemosEurope\DemosplanAddon\XBeteiligung\Exeption\NamespaceAdditionException;
use DemosEurope\DemosplanAddon\XBeteiligung\Exeption\ProjectPrefixNotFoundException;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\AllgemeinerNameType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\AllgemeinStellungnahmeNeuabgegeben0701;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\AllgemeinStellungnahmeNeuabgegeben0701\AllgemeinStellungnahmeNeuabgegeben0701AnonymousPHPType\NachrichteninhaltAnonymousPHPType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BeteiligungKommunalTOEBType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BeteiligungPlanfeststellungType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BeteiligungRaumordnungType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeAbwaegungsvorschlagType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeArtDerRueckmeldungType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeArtDerStellungnahmeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodePrioritaetDerStellungnahmeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeStatusDerStellungnahmeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeVerfahrensschrittKommunalType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeVerfahrensschrittPlanfeststellungType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeVerfahrensschrittRaumordnungType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeVerfahrensteilschrittType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\NameNatuerlichePersonType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\StellungnahmeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\VerfasserType;
use DemosEurope\DemosplanAddon\XBeteiligung\ValueObject\StatementCreated;
use Exception;
use JsonException;

class StatementMessageFactory extends XBeteiligungResponseMessageFactory
{
    public const PROJECT_PREFIX_DIPLANBAU = 'diplanbau';
    public const PROJECT_PREFIX_DIPLANROG = 'diplanrog';
    public const PROJECT_PREFIX_DIPLANFEST = 'diplanfest';

    /**
     * Builds a valid XBeteiligungsmessage as a response of creating a statement.
     *
     * @throws Exception
     */
    public function createBeteiligung2PlanungStellungnahmeNeu0701(StatementCreated $statementCreated): string
    {
        $message = new AllgemeinStellungnahmeNeuabgegeben0701();

        $this->xBeteiligungService->setProductInfo($message);
        $header = $this->buildHeader('0701', 'LGV');
        $message->setNachrichtenkopfG2g($header);

        $content = $this->createXBeteiligungStellungnahmeNeu0701Content($statementCreated);
        $message->setNachrichteninhalt($content);

        $messageXml = $this->xBeteiligungService->serializeData($message);

        return $this->addNamespacesTo70xXML($messageXml);
    }


    /**
     * @throws JsonException
     */
    public function createXBeteiligungStellungnahmeNeu0701Content(StatementCreated $statementCreated): NachrichteninhaltAnonymousPHPType
    {
        $statement = new StellungnahmeType();
        // create message content
        $statement->setStellungnahmeID($statementCreated->getPublicId());
        $statement->setPlanID($statementCreated->getPlanId());
        $statement->setBeteiligungsID($statementCreated->getProcedureId());
        // set status
        $status = new CodeStatusDerStellungnahmeType();
        $status->setCode($this->statusDerStellungnahme($statementCreated->getStatus()));
        $statement->setStatus($status);
        // set Verfasser --> user data
        if ($this->getTypeOfPerson($statementCreated) === true) {
            $verfasser = new VerfasserType();
            $verfasser->setPrivatperson(true);
            $natuerlichePerson = new NameNatuerlichePersonType();
            $natuerlichePerson->setTitel($statementCreated->getUser()->getTitle());
            $fname = new AllgemeinerNameType();
            $lname = new AllgemeinerNameType();
            $fname->setName($statementCreated->getUser()->getFirstName());
            $lname->setName($statementCreated->getUser()->getLastName());
            $natuerlichePerson->setFamilienname($lname);
            $natuerlichePerson->setVorname($fname);
            $natuerlichePerson->setAnrede($statementCreated->getUser()->getGender());
            $verfasser->setName($natuerlichePerson);
            $statement->setVerfasser($verfasser);
        }
        // set title
        $statement->setTitel($statementCreated->getTitle());
        // set beschreibung
        $statement->setBeschreibung($statementCreated->getDescription());
        //set durchgang
        $this->getIteration($statement, $statementCreated);
        // set datum
        $statement->setDatum($statementCreated->getCreatedAt());
        // set art der rueckmeldung
        $feedbackArt = new CodeArtDerRueckmeldungType();
        $feedbackArt->setCode($this->getArtOfFeedback($statementCreated->getFeedback()));
        $statement->setArtDerRueckmeldung($feedbackArt);
        // set art der stellungnahme
        $artDerStellungnahme = new CodeArtDerStellungnahmeType();
        $artDerStellungnahme->setCode($this->getArtOfStatement($statementCreated->getPublicUseName()));
        $statement->setArtDerStellungnahme($artDerStellungnahme);
        // set verfahrenschritt
        $this->getProcedurePhase($statementCreated, $statement);
        // set verfahrensteilschritt
        $partParticipationType = new CodeVerfahrensteilschrittType();
        $phaseCode = $statementCreated->getPartPhaseCode($statementCreated->getPhase(), $statementCreated->getPublicStatement());
        $partParticipationType->setCode($phaseCode);
        $partParticipationType->setListVersionID('3');
        $statement->setVerfahrensteilschritt($partParticipationType);
        // set priority
        $priority = new CodePrioritaetDerStellungnahmeType();
        $priority->setCode($this->getPriority($statementCreated->getPriority()));
        $statement->setPrioritaet($priority);
        // set Abwaegungsvorschlag
        $abwaegungVorschlag = new CodeAbwaegungsvorschlagType();
        $statement->setAbwaegungsvorschlag($abwaegungVorschlag->setCode($this->getAbwaegungVorschlag($statementCreated->getVotes()->first())));
        // set Schlagwort
        $statement->setSchlagwort($statementCreated->getTags());
        $nachricht = new NachrichteninhaltAnonymousPHPType();
        $nachricht->setStellungnahme($statement);
        $nachricht->setVorgangsID($this->xBeteiligungService->uuid());

        return $nachricht;
    }

    private function getTypeOfPerson(StatementCreated $statementCreated): bool
    {
        $privatPerson = true;
        if ($statementCreated->getMeta()->getOrgaName() !== 'Privatperson') {
            $privatPerson = false;
        }
        return $privatPerson;
    }

    private function statusDerStellungnahme($statusDerStellungnahme): string
    {
        $statusDerStellungnahmeCode = '';
        $statusDerStellungnahmeMapping = [
            'neue Stellungnahme'                                    => '1000',
            'in Bearbeitung befindliche Stellungnahme'              => '2000',
            'Stellungnahme wurde bearbeitet'                        => '3000',
            'Schlussmitteilung einer Stellungnahme wurde versendet' => '4000',
        ];
        if (array_key_exists($statusDerStellungnahme, $statusDerStellungnahmeMapping)) {
            $statusDerStellungnahmeCode = $statusDerStellungnahmeMapping[$statusDerStellungnahme];
        }
        return $statusDerStellungnahmeCode;
    }

    private function getArtOfStatement($artOfStatement): string
    {
        $artOfStatementCode = '';
        $artOfStatementMapping = [
            'anonym'     => '1000',
            'namentlich' => '2000',
            'sonstiges'  => '9999',
        ];
        if (array_key_exists($artOfStatement, $artOfStatementMapping)) {
            $artOfStatementCode = $artOfStatementMapping[$artOfStatement];
        }
        return $artOfStatementCode;
    }

    private function getArtOfFeedback($artOfFeedback): string
    {
        $artOfFeedbackCode = '';
        $artOfFeedbackMapping = [
            'E-Mail' => '1000',
            'Post' => '2000',
        ];
        if (array_key_exists($artOfFeedback, $artOfFeedbackMapping)) {
            $artOfFeedbackCode = $artOfFeedbackMapping[$artOfFeedback];
        }
        return $artOfFeedbackCode;
    }

    private function getPriority($priority): string
    {
        $priorityCode = '';
        $priorityMapping = [
            'A-Punkt'        => '1',
            'B-Punkt'        => '2',
            'nicht vergeben' => '3',
        ];
        if (array_key_exists($priority, $priorityMapping)) {
            $priorityCode = $priorityMapping[$priority];
        }
        return $priorityCode;
    }

    /**
     * @throws ProjectPrefixNotFoundException
     */
    private function getIteration(StellungnahmeType $statement, StatementCreated $statementCreated): void
    {
        $projectPrefix = $this->globalConfig->getProjectPrefix();
        switch ($projectPrefix) {
            case self::PROJECT_PREFIX_DIPLANBAU:
                $procedure = new BeteiligungKommunalTOEBType();
                break;
            case self::PROJECT_PREFIX_DIPLANROG:
                $procedure = new BeteiligungRaumordnungType();
                break;
            case self::PROJECT_PREFIX_DIPLANFEST:
                $procedure = new BeteiligungPlanfeststellungType();
                break;
            default:
                $this->dplanCockpitLogger->error('No project prefix found.');
                throw new ProjectPrefixNotFoundException();
        }
        $procedure->setDurchgang($statementCreated->getProcedure()->getPhaseObject()->getIteration());
        $statement->setDurchgang($procedure->getDurchgang());
    }

    /**
     * @throws ProjectPrefixNotFoundException
     */
    private function getProcedurePhase(StatementCreated $statementCreated, StellungnahmeType $statement): void
    {
        $projectPrefix = $this->globalConfig->getProjectPrefix();
        switch ($projectPrefix) {
            case self::PROJECT_PREFIX_DIPLANBAU:
                $participationType = new CodeVerfahrensschrittKommunalType();
                $phaseCode = $statementCreated->getPhaseCodeKommunale($statementCreated->getPhase(), $statementCreated->getPublicStatement());
                $participationType->setCode($phaseCode);
                $participationType->setListVersionID('3');
                $statement->setVerfahrensschrittKommunal($participationType);
                break;
            case ProcedureMessageTyp::RAUMORDNUNG:
                $participationType = new CodeVerfahrensschrittRaumordnungType();
                $phaseCode = $statementCreated->getPhaseCodeRaumordnung($statementCreated->getPhase(), $statementCreated->getPublicStatement());
                $participationType->setCode($phaseCode);
                $participationType->setListVersionID('3');
                $statement->setVerfahrensschrittRaumordnung($participationType);
                break;
            case ProcedureMessageTyp::PLANFESTSTELLUNG:
                $participationType = new CodeVerfahrensschrittPlanfeststellungType();
                $phaseCode = $statementCreated->getPhaseCodePlanfeststellung() ?? 'configuration';
                $participationType->setCode($phaseCode);
                $statement->setVerfahrensschrittPlanfeststellung($participationType);
                break;
            default:
                $this->dplanCockpitLogger->error('No project prefix found.');
                throw new ProjectPrefixNotFoundException();
        }

    }

    private function getAbwaegungVorschlag($abwaegungVorschlag): string
    {
        $abwaegungVorschlagCode = '';
        $abwaegungVorschlagMapping = [
            'Der Stellungnahme wird gefolgt.'                   => '1000',
            'Der Stellungnahme wurde bereits gefolgt.'          => '2000',
            'Der Stellungnahme wird nicht gefolgt.'             => '3000',
            'Die Stellungnahme wird im Arbeitskreis behandelt.' => '4000',
            'Die Stellungnahme wird zur Kenntnis genommen.'     => '5000',
        ];
        if (array_key_exists($abwaegungVorschlag, $abwaegungVorschlagMapping)) {
            $abwaegungVorschlagCode = $abwaegungVorschlagMapping[$abwaegungVorschlag];
        }
        return $abwaegungVorschlagCode;
    }

    /**
     * @throws NamespaceAdditionException
     */
    private function addNamespacesTo70xXML(string $xml): string
    {
        $simpleXML = simplexml_load_string($xml);

        $simpleXML->addAttribute('xmlns:xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
        $simpleXML->addAttribute('xmlns:xmlns:gml', 'http://www.opengis.net/gml/3.2');

        $result = $simpleXML->asXML();
        if (!is_string($result)) {
            $this->dplanCockpitLogger->error('Failed to add namespaces to XML.');
            throw new NamespaceAdditionException('Failed to add namespaces');
        }

        return $result;
    }
}