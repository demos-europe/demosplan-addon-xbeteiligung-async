<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Logic\MessageFactory;

use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2PlanungStellungnahmeNeu0701\Beteiligung2PlanungStellungnahmeNeu0701AnonymousPHPType\NachrichteninhaltAnonymousPHPType;
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
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeXBauMimeTypeTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\GeoreferenzierungType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\MetadatenAnlageType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\NameOrganisationTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\OrganisationTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\StellungnahmeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\ZustimmungType;
use DemosEurope\DemosplanAddon\XBeteiligung\ValueObject\StatementCreated;
use JsonException;

class StatementMessageFactory extends XBeteiligungResponseMessageFactory
{
    public const PROJECT_PREFIX_DIPLANBAU = 'diplanbau';
    public const PROJECT_PREFIX_DIPLANROG = 'diplanrog';
    public const PROJECT_PREFIX_DIPLANFEST = 'diplanfest';

    /**
     * @throws JsonException
     */
    public function createBeteiligung2PlanungStellungnahmeNeu0701Content(StatementCreated $statementCreated): NachrichteninhaltAnonymousPHPType
    {
        $statement = new StellungnahmeType();
        // create message content
        $statement->setStellungnahmeID($statementCreated->getPublicId());
        $statement->setPlanID($statementCreated->getPlanId());
        $statement->setBeteiligungsID($statementCreated->getProcedureId());
        $status = new CodeStatusDerStellungnahmeType();
        $status->setCode($this->statusDerStellungnahme($statementCreated->getStatus()));
        $statement->setStatus($status);
        $verfasser = new OrganisationTypeType();
        $orgaName = new NameOrganisationTypeType();
        $verfasser->setName($orgaName->setName($statementCreated->getOrganizationName()));
        $statement->setVerfasser($verfasser);
        $statement->setTitel($statementCreated->getTitle());
        $statement->setBeschreibung($statementCreated->getDescription());
        $this->getDurchgang($statementCreated);
        $statement->setDatum($statementCreated->getCreatedAt());
        $zustimmung = new ZustimmungType();
        $artDerRueckmeldung = new CodeArtDerRueckmeldungType();
        $artDerRueckmeldung->setCode($this->getArtDerRueckmeldung($statementCreated->getFeedback()));
        $zustimmung->setArtDerRueckmeldung($artDerRueckmeldung);
        $artDerStellungnahme = new CodeArtDerStellungnahmeType();
        $artDerStellungnahme->setCode($this->getArtDerStellungnahme($statementCreated->getPublicUseName()));
        $zustimmung->setArtDerStellungnahme($artDerStellungnahme);
        $statement->setZustimmung($zustimmung);
        // TODO: how can insert verfahrenSchritt in statement
        $this->getVerfahrenSchritt($statementCreated);
        // TODO: we don't have verfahrensteilschritt class
        $geoReferenzierung = $this->getGeoReferenzierung($statementCreated);
        $statement->setGeoreferenzierung([$geoReferenzierung]);
        $prioritaet = new CodePrioritaetDerStellungnahmeType();
        $prioritaet->setCode($this->getPrioritaet($statementCreated->getPriority()));
        $abwaegungVorschlag = new CodeAbwaegungsvorschlagType();
        $statement->setAbwaegungsvorschlag($abwaegungVorschlag->setCode($this->getAbwaegungVorschlag($statementCreated->getVotes())));
        $statement->setSchlagwort($statementCreated->getTags());
        $anlagen = new MetadatenAnlageType();
        $mime = new CodeXBauMimeTypeTypeType();
        $mime->setCode(explode(':', $statementCreated->getFile())[3]);
        $anlagen->setMimeType($mime);
        $statement->setAnlagen([$anlagen]);
        $nachricht = new NachrichteninhaltAnonymousPHPType();
        $nachricht->setStellungnahme($statement);
        $nachricht->setVorgangsID($this->uuid());

        return $nachricht;
    }

    private function statusDerStellungnahme($statusDerStellungnahme)
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

    private function getArtDerStellungnahme($artDerStellungnahme)
    {
        $artDerStellungnahmeCode = '';
        $artDerStellungnahmeMapping = [
            'anonym'     => '1000',
            'namentlich' => '2000',
            'sonstiges'  => '9999',
        ];
        if (array_key_exists($artDerStellungnahme, $artDerStellungnahmeMapping)) {
            $artDerStellungnahmeCode = $artDerStellungnahmeMapping[$artDerStellungnahme];
        }
        return $artDerStellungnahmeCode;
    }

    private function getArtDerRueckmeldung($artDerRueckmeldung)
    {
        $artDerRueckmeldungCode = '';
        $artDerRueckmeldungMapping = [
            'E-Mail' => '1000',
            'Post' => '2000',
        ];
        if (array_key_exists($artDerRueckmeldung, $artDerRueckmeldungMapping)) {
            $artDerRueckmeldungCode = $artDerRueckmeldungMapping[$artDerRueckmeldung];
        }
        return $artDerRueckmeldungCode;
    }

    private function getPrioritaet($prioritaet)
    {
        $prioritaetCode = '';
        $prioritaetMapping = [
            'A-Punkt'        => '1',
            'B-Punkt'        => '2',
            'nicht vergeben' => '3',
        ];
        if (array_key_exists($prioritaet, $prioritaetMapping)) {
            $prioritaetCode = $prioritaetMapping[$prioritaet];
        }
        return $prioritaetCode;
    }

    private function getDurchgang(StatementCreated $statementCreated)
    {
        $durchgang = 1;
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
                $procedure = null;
        }

        if ($procedure !== null) {
            $durchgang = $procedure->setDurchgang($statementCreated->getProcedure()->getPhaseObject()->getIteration());
        }
        return $durchgang;
    }

    private function getVerfahrenSchritt(StatementCreated $statementCreated)
    {
        $phaseCode = '';
        $participationType = null;
        $projectPrefix = $this->globalConfig->getProjectPrefix();

        switch ($projectPrefix) {
            case self::PROJECT_PREFIX_DIPLANBAU:
                $participationType = new CodeVerfahrensschrittKommunalType();
                $phaseCode = $statementCreated->getPhaseCodeKommunale();
                break;
            case self::PROJECT_PREFIX_DIPLANROG:
                $participationType = new CodeVerfahrensschrittRaumordnungType();
                $phaseCode = $statementCreated->getPhaseCodeRaumordnung();
                break;
            case self::PROJECT_PREFIX_DIPLANFEST:
                $participationType = new CodeVerfahrensschrittPlanfeststellungType();
                $phaseCode = $statementCreated->getPhaseCodePlanfeststellung();
                break;
        }

        return $participationType->setCode($phaseCode);
    }

    /**
     * @throws JsonException
     */
    private function getGeoReferenzierung(StatementCreated $statementCreated)
    {
        $polygon = json_decode($statementCreated->getPolygon(), true, 512, JSON_THROW_ON_ERROR);
        $georeferenzierung = new GeoreferenzierungType();
        $features = [];

        foreach ($polygon['features'] as $feature) {
            $geometryType = $feature['geometry']['type'];
            switch ($geometryType) {
                case 'Polygon':
                    $features['flaeche'][] = $feature;
                    break;
                case 'LineString':
                    $features['linie'][] = $feature;
                    break;
                case 'Point':
                    $features['punkt'][] = $feature;
                    break;
            }
        }

        if (isset($features['flaeche'])) {
            $georeferenzierung->setFlaeche($features['flaeche']);
        }
        if (isset($features['linie'])) {
            $georeferenzierung->setLinie($features['linie']);
        }
        if (isset($features['punkt'])) {
            $georeferenzierung->setPunkt($features['punkt']);
        }

        return $georeferenzierung;
    }

    private function getAbwaegungVorschlag($abwaegungVorschlag)
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
}