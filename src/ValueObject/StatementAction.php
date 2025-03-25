<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\ValueObject;

use DateTime;
use DemosEurope\DemosplanAddon\Contracts\Entities\ProcedureInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\StatementInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\StatementMetaInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\UserInterface;
use Doctrine\Common\Collections\Collection;

class StatementAction extends ValueObject
{
    protected string $publicId;
    protected string $description;
    protected string $planId;
    protected string $procedureName;
    protected string $procedureId;
    protected ProcedureInterface $procedure;
    protected DateTime $createdAt;
    protected string $plannerDetailViewUrl;
    protected string $phaseKey = '';
    protected bool $publicUseName = false;
    protected string $priority;
    protected string $organizationName;
    protected string $phase;
    protected string $status;
    protected string $title;
    protected string $feedback;
    protected  string $file;
    protected Collection $votes;
    protected array $tags;
    protected string $publicStatement = '';
    protected UserInterface $user;
    protected StatementMetaInterface $meta;

    public function __construct(UserInterface $user, ProcedureInterface $procedure, StatementMetaInterface $meta)
    {
        $this->user = $user;
        $this->procedure = $procedure;
        $this->meta = $meta;
    }


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

    public function getProcedureId(): string
    {
        return $this->procedureId;
    }

    public function setProcedureId(string $procedureId): void
    {
        $this->procedureId = $procedureId;
    }

    public function getProcedure(): ProcedureInterface
    {
        return $this->procedure;
    }

    public function getPlanId(): string
    {
        return $this->planId;
    }

    public function setPlanId(string $planId): void
    {
        $this->planId = $planId;
    }

    public function getPublicUseName(): string
    {
        return $this->publicUseName ? 'anonym' : 'namentlich';
    }

    public function setPublicUseName(bool $publicUseName): void
    {
        $this->publicUseName = $publicUseName;
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

    public function setPlannerDetailViewUrl(string $plannerDetailViewUrl): void
    {
        $this->plannerDetailViewUrl = $plannerDetailViewUrl;
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

    public function getFeedback(): string
    {
        return $this->feedback;
    }

    public function setFeedback(string $feedback): void
    {
        $this->feedback = $feedback;
    }

    public function setFile(string $file): void
    {
        $this->file = $file;
    }

    public function getPublicStatement(): string
    {
        return $this->publicStatement;
    }

    public function setPublicStatement(string $publicStatement): void
    {
        $this->publicStatement = $publicStatement;
    }

    public function getVotes(): Collection
    {
        return $this->votes;
    }

    public function setVotes(Collection $votes): void
    {
        $this->votes = $votes;
    }

    public function getTags(): array
    {
        return $this->tags;
    }

    public function setTags($tags=[]): void
    {
        $this->tags = $tags;
    }

    public function getUser(): UserInterface
    {
        return $this->user;
    }

    public function getMeta(): StatementMetaInterface
    {
        return $this->meta;
    }

    /**
     * Frühzeitige Beteiligung der Behörden und TöB => 0300
     * Frühzeitige Öffentlichkeitsbeteiligung       => 0600
     * Beteiligung der Behörden und TöB             => 0800
     * Öffentlichkeitsbeteiligung                   => 1200
     * kein VS                                      => 9998
     */

    public static function mapPartPhaseKey($phaseKey, $publicStatement): ?string
    {
        $mappedPartPhaseCode = '';
        if ($publicStatement === StatementInterface::INTERNAL) {
            switch ($phaseKey) {
                case 'earlyparticipation': // Frühzeitige Behördenbeteiligung
                case 'participation':
                    $mappedPartPhaseCode = '0300';
                    break;
                case 'anotherparticipation': // Beteiligung der Behörden und TöB
                    $mappedPartPhaseCode = '0800';
                    break;
                default:
                    $mappedPartPhaseCode = '9998';
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
                    $mappedPartPhaseCode = '9998';
            }
        }

        return $mappedPartPhaseCode;
    }

    /**
     *  'Frühzeitige Beteiligung der Behörden und TöB'  => '0300',
     *  'Frühzeitige Öffentlichkeitsbeteiligung'        => '0600',
     *  'Beteiligung der Behörden und TöB'              => '0800',
     *  'Öffentlichkeitsbeteiligung'                    => '1200',
     *  'kein VS'                                       => '9998',
     */

    /**
     *  'Vorplanung'                                    => '0000',
     *  'Einleitungsphase'                              => '1000',
     *  'Frühzeitige Behördenbeteiligung'               => '2000',
     *  'Aufstellungsbeschluss'                         => '3000',
     *  'Einleitungszustimmung'                         => '3600',
     *  'Frühzeitige Öffentlichkeitsbeteiligung'        => '4000',
     *  'Beteiligung der Träger öffentlicher Belange'   => '5000',
     *  'Digitale Veröffentlichung'                     => '6000',
     *  'Feststellungsverfahren'                        => '7000',
     *  'Schlussphase'                                  => '8000',
     *  'kein VS'                                       => '9998',
     */
    public static function mapPhaseKeyKommunale($phaseKey, $publicStatement): ?string
    {
        $mappedPhaseCode = '';
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
                case 'closed': // Schlussphase
                    $mappedPhaseCode = '8000';
                    break;
                default:
                    $mappedPhaseCode = '9998';
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
                case 'closed': // Schlussphase
                    $mappedPhaseCode = '8000';
                    break;
                default:
                    $mappedPhaseCode = '9998';
            }
        }

        return $mappedPhaseCode;
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
     *  This function map can not be correct as it is not clear what the phaseKey is
     */
    public static function mapPhaseKeyRaumordnung($phaseKey, $publicStatement): ?string
    {
        $mappedPhaseCode = '';
        if ($publicStatement === StatementInterface::INTERNAL) {
            switch ($phaseKey) {
                case 'configuration': // Konfiguration TöB
                    $mappedPhaseCode = '4000';
                    break;
                case 'earlyparticipation': // Erneute Anhörung TöB (Durchlaufnummer)
                case 'renewparticipation':
                    $mappedPhaseCode = '4500';
                    break;
                case 'participation': // Anhörung TöB
                    $mappedPhaseCode = '4200';
                    break;
                case 'closed': // Beschlussfassung TöB
                    $mappedPhaseCode = '4700';
                    break;
                default:
                    $mappedPhaseCode = '9998';
            }
        } elseif ($publicStatement === StatementInterface::EXTERNAL) {
            switch ($phaseKey) {
                case 'configuration': // Konfiguration betroffene Öffentlichkeit
                    $mappedPhaseCode = '5000';
                    break;
                case 'earlyparticipation': // Ermittlung und Information Betroffene (durch Gemeinden)
                    $mappedPhaseCode = '5100';
                    break;
                case 'participation': //Anhörung Betroffener (Öffentlichkeit)
                    $mappedPhaseCode = '5200';
                    break;
                case 'anotherparticipation': // Erneute Anhörung Betroffener (Öffentlichkeit) (Durchlaufnummer)
                    $mappedPhaseCode = '5500';
                    break;
                case 'closed': // Beschlussfassung betroffene Öffentlichkeit
                    $mappedPhaseCode =  '5700';
                    break;
                default:
                    $mappedPhaseCode = '9998';
            }
        }

        return $mappedPhaseCode;
    }

    /**
     * 'kein VS' => '9998',
     */
    public static function mapPhaseKeyPlanfeststellung($phaseKey): ?string
    {
        switch ($phaseKey) {
            case 'configuration': // Konfiguration = Vorplanung
                $mappedPhaseCode = '0000';
                break;
            case 'affectedmunicipalities':
                $mappedPhaseCode = '0600';
                break;
            case 'consultation':
                $mappedPhaseCode = '1200';
                break;
            case 'discussionmeeting':
            case 'reconsultation':
            case 'replayevaluating':
                $mappedPhaseCode =  '0200';
                break;
            default:
                $mappedPhaseCode = '';
        }

        return $mappedPhaseCode;
    }

    public function getPhaseCodeKommunale($phaseKey, $publicStatement): ?string
    {
        return self::mapPhaseKeyKommunale($phaseKey, $publicStatement);
    }
    public function getPartPhaseCode($phaseKey, $publicStatement): ?string
    {
        return self::mapPartPhaseKey($phaseKey, $publicStatement);
    }

    public function getPhaseCodeRaumordnung($phaseKey, $publicStatement): ?string
    {
        return self::mapPhaseKeyRaumordnung($phaseKey, $publicStatement);
    }

    public function getPhaseCodePlanfeststellung(): ?string
    {
        return self::mapPhaseKeyPlanfeststellung($this->phaseKey);
    }

}