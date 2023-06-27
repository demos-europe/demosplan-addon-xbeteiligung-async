<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\ProzessnachrichtenFormellePruefungBefundliste1140;

use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\NachrichtG2GTypeType;

/**
 * Class representing ProzessnachrichtenFormellePruefungBefundliste1140AnonymousPHPType
 */
class ProzessnachrichtenFormellePruefungBefundliste1140AnonymousPHPType extends NachrichtG2GTypeType
{
    /**
     * Bezug auf Vorgang und Nachricht, auf die sich die Inhalte der vorliegenden Nachricht beziehen.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BezugErweitertTypeType $bezug
     */
    private $bezug = null;

    /**
     * In diesem Element sind die Ergebnisse der formellen Prüfung enthalten. Sie haben die Form einer Liste von Mängeln des geprüften Gegenstandes.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BefundlisteFormellTypeType\BefundAnonymousPHPType[] $befundliste
     */
    private $befundliste = null;

    /**
     * In dieses Element wird - falls im entsprechenden Vorgang vorgesehen - die durch die Behörde gesetzte Frist eingetragen, innerhalb derer die genannten Mängel zu beseitigen und die Unterlagen erneut einzureichen sind.
     *
     * @var \DateTime $frist
     */
    private $frist = null;

    /**
     * Gets as bezug
     *
     * Bezug auf Vorgang und Nachricht, auf die sich die Inhalte der vorliegenden Nachricht beziehen.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BezugErweitertTypeType
     */
    public function getBezug()
    {
        return $this->bezug;
    }

    /**
     * Sets a new bezug
     *
     * Bezug auf Vorgang und Nachricht, auf die sich die Inhalte der vorliegenden Nachricht beziehen.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BezugErweitertTypeType $bezug
     * @return self
     */
    public function setBezug(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BezugErweitertTypeType $bezug)
    {
        $this->bezug = $bezug;
        return $this;
    }

    /**
     * Adds as befund
     *
     * In diesem Element sind die Ergebnisse der formellen Prüfung enthalten. Sie haben die Form einer Liste von Mängeln des geprüften Gegenstandes.
     *
     * @return self
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BefundlisteFormellTypeType\BefundAnonymousPHPType $befund
     */
    public function addToBefundliste(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BefundlisteFormellTypeType\BefundAnonymousPHPType $befund)
    {
        $this->befundliste[] = $befund;
        return $this;
    }

    /**
     * isset befundliste
     *
     * In diesem Element sind die Ergebnisse der formellen Prüfung enthalten. Sie haben die Form einer Liste von Mängeln des geprüften Gegenstandes.
     *
     * @param int|string $index
     * @return bool
     */
    public function issetBefundliste($index)
    {
        return isset($this->befundliste[$index]);
    }

    /**
     * unset befundliste
     *
     * In diesem Element sind die Ergebnisse der formellen Prüfung enthalten. Sie haben die Form einer Liste von Mängeln des geprüften Gegenstandes.
     *
     * @param int|string $index
     * @return void
     */
    public function unsetBefundliste($index)
    {
        unset($this->befundliste[$index]);
    }

    /**
     * Gets as befundliste
     *
     * In diesem Element sind die Ergebnisse der formellen Prüfung enthalten. Sie haben die Form einer Liste von Mängeln des geprüften Gegenstandes.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BefundlisteFormellTypeType\BefundAnonymousPHPType[]
     */
    public function getBefundliste()
    {
        return $this->befundliste;
    }

    /**
     * Sets a new befundliste
     *
     * In diesem Element sind die Ergebnisse der formellen Prüfung enthalten. Sie haben die Form einer Liste von Mängeln des geprüften Gegenstandes.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BefundlisteFormellTypeType\BefundAnonymousPHPType[] $befundliste
     * @return self
     */
    public function setBefundliste(array $befundliste)
    {
        $this->befundliste = $befundliste;
        return $this;
    }

    /**
     * Gets as frist
     *
     * In dieses Element wird - falls im entsprechenden Vorgang vorgesehen - die durch die Behörde gesetzte Frist eingetragen, innerhalb derer die genannten Mängel zu beseitigen und die Unterlagen erneut einzureichen sind.
     *
     * @return \DateTime
     */
    public function getFrist()
    {
        return $this->frist;
    }

    /**
     * Sets a new frist
     *
     * In dieses Element wird - falls im entsprechenden Vorgang vorgesehen - die durch die Behörde gesetzte Frist eingetragen, innerhalb derer die genannten Mängel zu beseitigen und die Unterlagen erneut einzureichen sind.
     *
     * @param \DateTime $frist
     * @return self
     */
    public function setFrist(?\DateTime $frist = null)
    {
        $this->frist = $frist;
        return $this;
    }
}

