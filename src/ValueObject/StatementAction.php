<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\ValueObject;

use DateTime;
use DemosEurope\DemosplanAddon\Contracts\Entities\CountyInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\ElementsInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\GdprConsentInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\MunicipalityInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\OrgaInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\OriginalStatementAnonymizationInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\PriorityAreaInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\ProcedureInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\SingleDocumentInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\StatementAttachmentInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\StatementInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\StatementMetaInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\StatementVersionFieldInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\StatementVoteInterface;
use Doctrine\Common\Collections\Collection;

class StatementAction extends ValueObject
{
    protected string $publicId;
    protected string $description;
    protected string $planId;
    protected ProcedureInterface $procedure;
    protected string $procedureName;
    protected string $procedureId;
    protected DateTime $createdAt;
    protected string $plannerDetailViewUrl;
    protected string $phaseKey;
    protected bool $publicUseName = false;
    protected string $priority;
    protected OrgaInterface $organization;
    protected string $organizationName;
    protected string $departmentName;
    protected string $phase;
    protected string $status;
    protected string $title;
    protected string $text;
    protected string $feedback;
    protected  string $file;
    protected string $mapFile;
    protected SingleDocumentInterface $document;
    protected ElementsInterface $element;
    protected string $polygon = '';
    protected StatementMetaInterface $meta;
    protected StatementVersionFieldInterface $version;
    /**
     * @var Collection<int,StatementVoteInterface>
     */
    protected Collection $votes;
    protected Collection $tags;
    protected array $files;
    protected string $publicStatement;

    /**
     * @var Collection<int, StatementAttachmentInterface>
     */
    protected Collection $attachments;


    public function getPublicId(): string
    {
        return $this->publicId;
    }

    public function setPublicId(string $publicId): void
    {
        $this->publicId = $publicId;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getProcedure(): ProcedureInterface
    {
        return $this->procedure;
    }

    public function setProcedure(ProcedureInterface $procedure): void
    {
        $this->procedure = $procedure;
    }

    public function getProcedureId(): string
    {
        return $this->procedureId;
    }

    public function setProcedureId(string $procedureId): void
    {
        $this->procedureId = $procedureId;
    }

    public function getPlanId(): string
    {
        return $this->planId;
    }

    public function setPlanId(string $planId): void
    {
        $this->planId = $planId;
    }

    public function getPublicUseName(): bool
    {
        return $this->publicUseName ? 'anonym' : 'namentlich';
    }

    public function setPublicUseName(bool $publicUseName): void
    {
        $this->publicUseName = $publicUseName;
    }

    public function getProcedureName(): string
    {
        return $this->procedureName;
    }

    public function setProcedureName(string $procedureName): void
    {
        $this->procedureName = $procedureName;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getPlannerDetailViewUrl(): string
    {
        return $this->plannerDetailViewUrl;
    }

    public function setPlannerDetailViewUrl(string $plannerDetailViewUrl): void
    {
        $this->plannerDetailViewUrl = $plannerDetailViewUrl;
    }

    public function getOrganizationName(): string
    {
        return $this->organizationName;
    }

    public function setOrganizationName(string $organizationName): void
    {
        $this->organizationName = $organizationName;
    }

    public function getPriority(): string
    {
        return $this->priority;
    }

    public function setPriority(string $priority): void
    {
        $this->priority = $priority;
    }

    public function getOrganization(): OrgaInterface
    {
        return $this->organization;
    }

    public function setOrganization(OrgaInterface $organization): void
    {
        $this->organization = $organization;
    }

    public function getPhase(): string
    {
        return $this->phase;
    }

    public function setPhase(string $phase): void
    {
        $this->phase = $phase;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setText(string $text): void
    {
        $this->text = $text;
    }

    public function getFeedback(): string
    {
        return $this->feedback;
    }

    public function setFeedback(string $feedback): void
    {
        $this->feedback = $feedback;
    }

    public function getFile(): string
    {
        return $this->file;
    }

    public function setFile(string $file): void
    {
        $this->file = $file;
    }

    public function getDocument(): SingleDocumentInterface
    {
        return $this->document;
    }

    public function setDocument(SingleDocumentInterface $document): void
    {
        $this->document = $document;
    }

    public function getElement(): ElementsInterface
    {
        return $this->element;
    }

    public function setElement(ElementsInterface $element): void
    {
        $this->element = $element;
    }

    public function getPolygon(): string
    {
        return $this->polygon;
    }

    public function setPolygon(string $polygon): void
    {
        $this->polygon = $polygon;
    }

    public function getPublicStatement(): string
    {
        return $this->publicStatement;
    }

    public function setPublicStatement(string $publicStatement): void
    {
        $this->publicStatement = $publicStatement;
    }

    public function getMeta(): StatementMetaInterface
    {
        return $this->meta;
    }

    public function setMeta(StatementMetaInterface $meta): void
    {
        $this->meta = $meta;
    }

    public function getVersion(): StatementVersionFieldInterface
    {
        return $this->version;
    }

    public function setVersion(StatementVersionFieldInterface $version): void
    {
        $this->version = $version;
    }

    public function getVotes(): Collection
    {
        return $this->votes;
    }

    public function setVotes(Collection $votes): void
    {
        $this->votes = $votes;
    }

    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function setTags(Collection $tags): void
    {
        $this->tags = $tags;
    }

    public function getFiles(): array
    {
        return $this->files;
    }

    public function setFiles(array $files): void
    {
        $this->files = $files;
    }

    /*
     * Verfahrenteilschritt
     * verfahrenteilschritt:
     * 'Frühzeitige Beteiligung der Behörden und TöB'  => '0300',
       'Frühzeitige Öffentlichkeitsbeteiligung'        => '0600',
       'Beteiligung der Behörden und TöB'              => '0800',
       'Öffentlichkeitsbeteiligung'                    => '1200',
       'kein VS'                                       => '9998',
     */

    /*
     * Verfahrenschritt
     * 'Vorplanung'                                    => '0000',
       'Einleitungsphase'                              => '1000',
       'Frühzeitige Behördenbeteiligung'               => '2000',
       'Aufstellungsbeschluss'                         => '3000',
       'Einleitungszustimmung'                         => '3600',
       'Frühzeitige Öffentlichkeitsbeteiligung'        => '4000',
       'Beteiligung der Träger öffentlicher Belange'   => '5000',
       'Digitale Veröffentlichung'                     => '6000',
       'Feststellungsverfahren'                        => '7000',
       'Schlussphase'                                  => '8000',
       'kein VS'                                       => '9998',
     */
    public static function mapPhaseKeyKommunale($phaseKey, $publicStatement): ?string
    {
        if ($publicStatement === StatementInterface::INTERNAL) {
            switch ($phaseKey) {
                case 'configuration': // Einleitungsphase
                    $mappedPhaseCode = '1000';
                    break;
                case 'earlyparticipation': // Frühzeitige Behördenbeteiligung
                    $mappedPhaseCode = '2000';
                    break;
                case 'anotherparticipation': // Beteiligung der Träger öffentlicher Belange
                case 'participation':
                    $mappedPhaseCode = '5000';
                    break;
                default:
                    $mappedPhaseCode = '';
            }
        } elseif ($publicStatement === StatementInterface::EXTERNAL) {
            switch ($phaseKey) {
                case 'configuration': // Einleitungsphase
                    $mappedPhaseCode = '1000';
                    break;
                case 'earlyparticipation': // Frühzeitige Öffentlichkeitsbeteiligung
                    $mappedPhaseCode = '4000';
                    break;
                case 'anotherparticipation': // Digitale Veröffentlichung
                case 'participation':
                    $mappedPhaseCode = '6000';
                    break;
                default:
                    $mappedPhaseCode = '';
            }
        }

        return $mappedPhaseCode;
    }

    public static function mapPartPhaseKeyKommunale($phaseKey, $publicStatement): ?string
    {
        if ($publicStatement === StatementInterface::INTERNAL) {
            switch ($phaseKey) {
                case 'earlyparticipation': // Frühzeitige Behördenbeteiligung
                case 'anotherparticipation': // Beteiligung der Behörden und TöB
                case 'participation':
                    $mappedPartPhaseCode = '0300';
                    break;
                default:
                    $mappedPartPhaseCode = '';
            }
        } elseif ($publicStatement === StatementInterface::EXTERNAL) {
            switch ($phaseKey) {
                case 'earlyparticipation': // Frühzeitige Öffentlichkeitsbeteiligung
                    $mappedPartPhaseCode = '0600';
                    break;
                case 'anotherparticipation': // Öffentlichkeitsbeteiligung
                case 'participation':
                $mappedPartPhaseCode = '1200';
                    break;
                default:
                    $mappedPartPhaseCode = '';
            }
        }

        return $mappedPartPhaseCode;
    }

    /**
     * 'Konfiguration TöB'                                                 => '4000',
     * 'Ermittlung und Information Behörden und berührte Gemeinden*'       => '4100',
     * 'Anhörung TöB'                                                      => '4200',
     * 'Erwiderung /Planänderung bzw. Auswertung'                          => '4300',
     * 'Erörterungstermin'                                                 => '4400',
     * 'Erneute Anhörung TöB (Durchlaufnummer)'                            => '4500',
     * 'Auswertung TöB'                                                    => '4600',
     * 'Beschlussfassung TöB'                                              => '4700',
     * 'Konfiguration betroffene Öffentlichkeit'                           => '5000',
     * 'Ermittlung und Information Betroffene (durch Gemeinden)'           => '5100',
     * 'Anhörung Betroffener (Öffentlichkeit)'                             => '5200',
     * 'Erwiderung /Planänderung bzw. Auswertung'                          => '5300',
     * 'Erörterungstermin'                                                 => '5400',
     * 'Erneute Anhörung Betroffener (Öffentlichkeit) (Durchlaufnummer)'   => '5500',
     * 'Auswertung betroffene Öffentlichkeit'                              => '5600',
     * 'Beschlussfassung betroffene Öffentlichkeit'                        => '5700',
     * 'kein VS'                                                           => '9998',
     *
     */
    public static function mapPhaseKeyRaumordnung($phaseKey): ?string
    {
        switch ($phaseKey) {
            case 'configuration': // Konfiguration TöB Konfiguration betroffene Öffentlichkeit
                $mappedPhaseCode = '0000'; // ?
                break;
            case 'earlyparticipation': // Frühzeitige Beteiligung Öffentlichkeit
                $mappedPhaseCode = '0600';
                break;
            case 'participation': // Öffentlichkeitsbeteiligung
                $mappedPhaseCode = '1200';
                break;
            case 'discussiondate': //
                $mappedPhaseCode =  '0200';
                break;
            case 'renewparticipation': //
                $mappedPhaseCode =  '0200';
                break;
            default:
                $mappedPhaseCode = '';
        }

        return $mappedPhaseCode;
    }

    /*
     * 'kein VS' => '9998',
     */
    public static function mapPhaseKeyPlanfeststellung($phaseKey): ?string
    {
        switch ($phaseKey) {
            case 'configuration': // Konfiguration = Vorplanung
                $mappedPhaseCode = '0000';
                break;
            case 'affectedmunicipalities': // Frühzeitige Beteiligung Öffentlichkeit
                $mappedPhaseCode = '0600';
                break;
            case 'consultation': // Öffentlichkeitsbeteiligung
                $mappedPhaseCode = '1200';
                break;
            case 'replayevaluating': // Grobabstimmung
                $mappedPhaseCode =  '0200';
                break;
            case 'discussionmeeting': // Grobabstimmung
                $mappedPhaseCode =  '0200';
                break;
            case 'reconsultation': // Grobabstimmung
                $mappedPhaseCode =  '0200';
                break;
            default:
                $mappedPhaseCode = '';
        }

        return $mappedPhaseCode;
    }

    public function getPhaseCodeKommunale(): ?string
    {
        return self::mapPhaseKeyKommunale($this->phaseKey, $this->getPublicStatement());
    }
    public function getPartPhaseCodeKommunale(): ?string
    {
        return self::mapPartPhaseKeyKommunale($this->phaseKey, $this->getPublicStatement());
    }

    public function getPhaseCodeRaumordnung(): ?string
    {
        return self::mapPhaseKeyRaumordnung($this->phaseKey);
    }

    public function getPhaseCodePlanfeststellung(): ?string
    {
        return self::mapPhaseKeyPlanfeststellung($this->phaseKey);
    }

}