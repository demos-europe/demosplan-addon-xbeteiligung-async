<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul;

/**
 * Class representing KodierungFlurstueckType
 *
 * Dieser Typ deckt Informationen zur Identifikation von Flurstücken (Liegenschaftskataster) ab.
 * XSD Type: KodierungFlurstueck
 */
class KodierungFlurstueckType
{
    /**
     * Durch dieses Elements wird die Gemarkung bezeichnet. Sie ist entweder deskriptiv oder als Nummer dargestellt. Eine Gemarkung ist eine zusammenhängende, aus mehreren Fluren bestehende Fläche des Liegenschaftskatasters.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\KodierungFlurstueckType\GemarkungAnonymousPHPType $gemarkung
     */
    private $gemarkung = null;

    /**
     * Mit diesem Element kann die Nummer der Flur (Flurnummer; maximal 3 Stellen) angegeben werden. Die Flur ist eine zusammenhängende, aus mehreren Flurstücken bestehende Fläche des Liegenschaftskatasters.
     *
     * @var string $flur
     */
    private $flur = null;

    /**
     * Dieses Element steht für den Zähler der Flurstücknummer (maximal 5 Stellen). Der Zähler ist der erste Teil der Flurstücksnummer und muss in jedem Fall angegeben werden.
     *
     * @var string $zaehler
     */
    private $zaehler = null;

    /**
     * Dieses Element steht für den ggf. anzuführenden Nenner der Flurstücknummer (maximal 4 Stellen). Der Nenner ist der zweite Teil der Flurstücksnummer, üblicherweise in einer Notation durch Schrägstrich vom Zähler getrennt. Die Angabe ist optional.
     *
     * @var string $nenner
     */
    private $nenner = null;

    /**
     * Dieses Element bietet die Möglichkeit, im Rahmen der Flurstücksnummer auch eine Flurstücksfolge anzugeben (maximal 2 Stellen). Die Folge ist eine weitere Präzisiserung einer Flurstücksnummer und kann ergänzend angegeben werden.
     *
     * @var string $folge
     */
    private $folge = null;

    /**
     * Gets as gemarkung
     *
     * Durch dieses Elements wird die Gemarkung bezeichnet. Sie ist entweder deskriptiv oder als Nummer dargestellt. Eine Gemarkung ist eine zusammenhängende, aus mehreren Fluren bestehende Fläche des Liegenschaftskatasters.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\KodierungFlurstueckType\GemarkungAnonymousPHPType
     */
    public function getGemarkung()
    {
        return $this->gemarkung;
    }

    /**
     * Sets a new gemarkung
     *
     * Durch dieses Elements wird die Gemarkung bezeichnet. Sie ist entweder deskriptiv oder als Nummer dargestellt. Eine Gemarkung ist eine zusammenhängende, aus mehreren Fluren bestehende Fläche des Liegenschaftskatasters.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\KodierungFlurstueckType\GemarkungAnonymousPHPType $gemarkung
     * @return self
     */
    public function setGemarkung(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\KodierungFlurstueckType\GemarkungAnonymousPHPType $gemarkung)
    {
        $this->gemarkung = $gemarkung;
        return $this;
    }

    /**
     * Gets as flur
     *
     * Mit diesem Element kann die Nummer der Flur (Flurnummer; maximal 3 Stellen) angegeben werden. Die Flur ist eine zusammenhängende, aus mehreren Flurstücken bestehende Fläche des Liegenschaftskatasters.
     *
     * @return string
     */
    public function getFlur()
    {
        return $this->flur;
    }

    /**
     * Sets a new flur
     *
     * Mit diesem Element kann die Nummer der Flur (Flurnummer; maximal 3 Stellen) angegeben werden. Die Flur ist eine zusammenhängende, aus mehreren Flurstücken bestehende Fläche des Liegenschaftskatasters.
     *
     * @param string $flur
     * @return self
     */
    public function setFlur($flur)
    {
        $this->flur = $flur;
        return $this;
    }

    /**
     * Gets as zaehler
     *
     * Dieses Element steht für den Zähler der Flurstücknummer (maximal 5 Stellen). Der Zähler ist der erste Teil der Flurstücksnummer und muss in jedem Fall angegeben werden.
     *
     * @return string
     */
    public function getZaehler()
    {
        return $this->zaehler;
    }

    /**
     * Sets a new zaehler
     *
     * Dieses Element steht für den Zähler der Flurstücknummer (maximal 5 Stellen). Der Zähler ist der erste Teil der Flurstücksnummer und muss in jedem Fall angegeben werden.
     *
     * @param string $zaehler
     * @return self
     */
    public function setZaehler($zaehler)
    {
        $this->zaehler = $zaehler;
        return $this;
    }

    /**
     * Gets as nenner
     *
     * Dieses Element steht für den ggf. anzuführenden Nenner der Flurstücknummer (maximal 4 Stellen). Der Nenner ist der zweite Teil der Flurstücksnummer, üblicherweise in einer Notation durch Schrägstrich vom Zähler getrennt. Die Angabe ist optional.
     *
     * @return string
     */
    public function getNenner()
    {
        return $this->nenner;
    }

    /**
     * Sets a new nenner
     *
     * Dieses Element steht für den ggf. anzuführenden Nenner der Flurstücknummer (maximal 4 Stellen). Der Nenner ist der zweite Teil der Flurstücksnummer, üblicherweise in einer Notation durch Schrägstrich vom Zähler getrennt. Die Angabe ist optional.
     *
     * @param string $nenner
     * @return self
     */
    public function setNenner($nenner)
    {
        $this->nenner = $nenner;
        return $this;
    }

    /**
     * Gets as folge
     *
     * Dieses Element bietet die Möglichkeit, im Rahmen der Flurstücksnummer auch eine Flurstücksfolge anzugeben (maximal 2 Stellen). Die Folge ist eine weitere Präzisiserung einer Flurstücksnummer und kann ergänzend angegeben werden.
     *
     * @return string
     */
    public function getFolge()
    {
        return $this->folge;
    }

    /**
     * Sets a new folge
     *
     * Dieses Element bietet die Möglichkeit, im Rahmen der Flurstücksnummer auch eine Flurstücksfolge anzugeben (maximal 2 Stellen). Die Folge ist eine weitere Präzisiserung einer Flurstücksnummer und kann ergänzend angegeben werden.
     *
     * @param string $folge
     * @return self
     */
    public function setFolge($folge)
    {
        $this->folge = $folge;
        return $this;
    }
}

