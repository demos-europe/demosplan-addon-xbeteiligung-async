<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Logic\MessageFactory;

use DemosEurope\DemosplanAddon\Utilities\AddonPath;
use DemosEurope\DemosplanAddon\XBeteiligung\Configuration\Permissions\Features;
use DemosEurope\DemosplanAddon\XBeteiligung\Exeption\NamespaceAdditionException;
use DemosEurope\DemosplanAddon\XBeteiligung\Exeption\ProjectPrefixNotFoundException;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\SerializerFactory;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\AllgemeinStellungnahmeNeuabgegeben0701\AllgemeinStellungnahmeNeuabgegeben0701AnonymousPHPType\NachrichteninhaltAnonymousPHPType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\AllgemeinStellungnahmeNeuabgegeben0701;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\BeteiligungKommunalTOEBType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\BeteiligungPlanfeststellungType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\BeteiligungRaumordnungType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\CodeAbwaegungsvorschlagType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\CodeArtDerRueckmeldungType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\CodeArtDerStellungnahmeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\CodePrioritaetDerStellungnahmeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\CodeStatusDerStellungnahmeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\CodeVerfahrensschrittKommunalType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\CodeVerfahrensschrittPlanfeststellungType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\CodeVerfahrensschrittRaumordnungType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\CodeVerfahrensteilschrittType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\StellungnahmeType;
use DemosEurope\DemosplanAddon\XBeteiligung\ValueObject\StatementCreated;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\MessageFactory\VerfasserBuilder;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\CommonHelpers;
use DemosEurope\DemosplanAddon\Permission\PermissionEvaluatorInterface;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\MessageFactory\ReusableMessageBlocks;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\MessageFactory\PhaseBuilder;
use DemosEurope\DemosplanAddon\XBeteiligung\XBeteiligungAsyncAddon;
use Exception;
use JsonException;
use Psr\Log\LoggerInterface;

class StatementMessageFactory extends XBeteiligungResponseMessageFactory
{
    /**
     * Default fallback codes for XBeteiligung mappings
     */
    private const DEFAULT_STATUS_CODE = '1000'; // "neue Stellungnahme" - most common starting state
    private const DEFAULT_STATEMENT_ART_CODE = '9999'; // "sonstiges" - catch-all for unknown types
    private const DEFAULT_FEEDBACK_CODE = '1000'; // "E-Mail" - most common feedback method
    private const DEFAULT_PRIORITY_CODE = '3'; // "nicht vergeben" - priority not assigned
    private const DEFAULT_CONSIDERATION_CODE = '5000'; // "Die Stellungnahme wird zur Kenntnis genommen."

    private const DEFAULT_PROCEDURE_PHASE_CODE = '0815';

    private const LIST_VERSION_ID = '3';

    public function __construct(
        CommonHelpers                     $commonHelpers,
        LoggerInterface                   $logger,
        PermissionEvaluatorInterface      $permissionEvaluator,
        ReusableMessageBlocks             $reusableMessageBlocks,
        private readonly VerfasserBuilder $verfasserBuilder,
        private readonly PhaseBuilder     $phaseBuilder,
    ) {
        parent::__construct($commonHelpers, $logger, $permissionEvaluator, $reusableMessageBlocks);
    }

    /**
     * Builds a valid XBeteiligungsmessage as a response of creating a statement.
     *
     * @throws Exception
     */
    public function createBeteiligung2PlanungStellungnahmeNeu0701(StatementCreated $statementCreated): string
    {
        $message = new AllgemeinStellungnahmeNeuabgegeben0701();

        /** @var AllgemeinStellungnahmeNeuabgegeben0701 $message */
        $message = $this->reusableMessageBlocks->setProductInfo($message);
        $header = $this->reusableMessageBlocks->createMessageHeadFor($message);
        $message->setNachrichtenkopfG2g($header);
        $content = $this->createXBeteiligungStellungnahmeNeu0701Content($statementCreated);
        $message->setNachrichteninhalt($content);

        $messageXml = SerializerFactory::serializeData($message, $this->logger);
        $messageXml = $this->addNamespacesTo70xXML($messageXml);
        $path = AddonPath::getRootPath(
            'addons/vendor/'.XBeteiligungAsyncAddon::ADDON_NAME.'/Resources/xsd/'
        );
        $this->commonHelpers->isValidMessage($messageXml, path: $path, messageClass: $message::class);

        return $messageXml;
    }


    /**
     * @throws JsonException
     * @throws ProjectPrefixNotFoundException
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
        $this->verfasserBuilder->setVerfasser($statementCreated, $statement);

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
        $this->phaseBuilder->setProcedurePhase($statementCreated, $statement);
        // set verfahrensteilschritt
        $partParticipationType = new CodeVerfahrensteilschrittType();
        $partParticipationType->setCode(self::DEFAULT_PROCEDURE_PHASE_CODE);
        $partParticipationType->setName($this->phaseBuilder->getPhaseName($statementCreated));
        $partParticipationType->setListVersionID(self::LIST_VERSION_ID);
        $statement->setVerfahrensteilschritt($partParticipationType);
        // set priority
        $priority = new CodePrioritaetDerStellungnahmeType();
        $priority->setCode($this->getPriority($statementCreated->getPriority()));
        $statement->setPrioritaet($priority);
        // set Abwaegungsvorschlag - optional field - only set it if a value is given
        if (null !== $statementCreated->getVotePla()) {
            $abwaegungVorschlag = new CodeAbwaegungsvorschlagType();
            $statement->setAbwaegungsvorschlag(
                $abwaegungVorschlag->setCode(
                    $this->getAbwaegungVorschlag($statementCreated->getVotePla())
                )
            );
        }
        // set Schlagwort
        $statement->setSchlagwort($statementCreated->getTags());
        $nachricht = new NachrichteninhaltAnonymousPHPType();
        $nachricht->setStellungnahme($statement);
        $nachricht->setVorgangsID($this->commonHelpers->uuid());

        return $nachricht;
    }

    private function statusDerStellungnahme($statusDerStellungnahme): string
    {
        $statusDerStellungnahmeMapping = [
            'new'                => '1000', //neue Stellungnahme
            'processing'         => '2000', //in Bearbeitung befindliche Stellungnahme
            'processed'          => '3000', //Stellungnahme wurde bearbeitet
            'statementFinalSent' => '4000', //Schlussmitteilung einer Stellungnahme wurde versendet
        ];
        if (array_key_exists($statusDerStellungnahme, $statusDerStellungnahmeMapping)) {
            return $statusDerStellungnahmeMapping[$statusDerStellungnahme];
        }

        // Default fallback when no mapping is found to avoid empty code field
        // which would cause XSD validation to fail
        $this->logger->warning(
            'Unknown status value encountered in XBeteiligung mapping',
            ['value' => $statusDerStellungnahme, 'fallback' => self::DEFAULT_STATUS_CODE]
        );
        return self::DEFAULT_STATUS_CODE; // "neue Stellungnahme" - most common starting state
    }

    private function getArtOfStatement($artOfStatement): string
    {
        $artOfStatementMapping = [
            'anonym'     => '1000',
            'namentlich' => '2000',
            'sonstiges'  => '9999',
        ];
        if (array_key_exists($artOfStatement, $artOfStatementMapping)) {
            return $artOfStatementMapping[$artOfStatement];
        }

        // Default fallback when no mapping is found to avoid empty code field
        // which would cause XSD validation to fail
        $this->logger->warning(
            'Unknown statement art value encountered in XBeteiligung mapping',
            ['value' => $artOfStatement, 'fallback' => self::DEFAULT_STATEMENT_ART_CODE]
        );
        return self::DEFAULT_STATEMENT_ART_CODE; // "sonstiges" - catch-all for unknown types
    }

    private function getArtOfFeedback($artOfFeedback): string
    {
        $artOfFeedbackMapping = [
            'email' => '1000',
            'post' => '2000',
        ];
        if (array_key_exists($artOfFeedback, $artOfFeedbackMapping)) {
            return $artOfFeedbackMapping[$artOfFeedback];
        }

        // Default fallback when no mapping is found to avoid empty code field
        // which would cause XSD validation to fail
        $this->logger->warning(
            'Unknown feedback value encountered in XBeteiligung mapping',
            ['value' => $artOfFeedback, 'fallback' => self::DEFAULT_FEEDBACK_CODE]
        );
        return self::DEFAULT_FEEDBACK_CODE; // "E-Mail" - most common feedback method
    }

    private function getPriority($priority): string
    {
        $priorityMapping = [
            'A-Punkt'        => '1',
            'B-Punkt'        => '2',
            '' => '3', // means not assigned
        ];
        if (array_key_exists($priority, $priorityMapping)) {
            return $priorityMapping[$priority];
        }

        // Default fallback when no mapping is found to avoid empty code field
        // which would cause XSD validation to fail
        $this->logger->warning(
            'Unknown priority value encountered in XBeteiligung mapping',
            ['value' => $priority, 'fallback' => self::DEFAULT_PRIORITY_CODE]
        );
        return self::DEFAULT_PRIORITY_CODE; // "nicht vergeben" - priority not assigned
    }

    /**
     * @throws ProjectPrefixNotFoundException
     */
    private function getIteration(StellungnahmeType $statement, StatementCreated $statementCreated): void
    {
        $procedure = null;
        if ($this->permissionEvaluator->isPermissionEnabled(Features::feature_procedure_message_kom_create())) {
            $procedure = new BeteiligungKommunalTOEBType();
        }elseif ($this->permissionEvaluator->isPermissionEnabled(Features::feature_procedure_message_rog_create())) {
            $procedure = new BeteiligungRaumordnungType();
        }elseif ($this->permissionEvaluator->isPermissionEnabled(Features::feature_procedure_message_pln_create())) {
            $procedure = new BeteiligungPlanfeststellungType();
        }elseif ($procedure === null) {
            $this->logger->error('No procedure found.');
            throw new ProjectPrefixNotFoundException();
        }
        $procedure->setDurchgang($statementCreated->getProcedure()->getPhaseObject()->getIteration());
        $statement->setDurchgang($procedure->getDurchgang());
    }


    private function getAbwaegungVorschlag($abwaegungVorschlag): string
    {
        $abwaegungVorschlagMapping = [
            'following'      => '1000',
            'followed'       => '2000',
            'noFollow'       => '3000',
            'workInProgress' => '4000',
            'acknowledge'    => '5000',
        ];
        if (array_key_exists($abwaegungVorschlag, $abwaegungVorschlagMapping)) {
            return $abwaegungVorschlagMapping[$abwaegungVorschlag];
        }

        // Default fallback when no mapping is found to avoid empty code field
        // which would cause XSD validation to fail
        $this->logger->warning(
            'Unknown consideration proposal value encountered in XBeteiligung mapping',
            ['value' => $abwaegungVorschlag, 'fallback' => self::DEFAULT_CONSIDERATION_CODE]
        );
        return self::DEFAULT_CONSIDERATION_CODE; // "Die Stellungnahme wird zur Kenntnis genommen."
    }

    /**
     * @throws NamespaceAdditionException
     */
    private function addNamespacesTo70xXML(string $xml): string
    {
        $simpleXML = simplexml_load_string($xml);

        $simpleXML->addAttribute('xmlns:xmlns:xsi', 'https://www.w3.org/2001/XMLSchema-instance');
        $simpleXML->addAttribute('xmlns:xmlns:gml', 'https://www.opengis.net/gml/3.2');

        $result = $simpleXML->asXML();
        if (!is_string($result)) {
            $this->logger->error('Failed to add namespaces to XML.');
            throw new NamespaceAdditionException('Failed to add namespaces');
        }

        return $result;
    }


}
