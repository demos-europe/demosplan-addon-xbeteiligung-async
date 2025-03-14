<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Basisnachricht\Behoerde;

/**
 * Class representing BehoerdeTypeType
 *
 * Dieser Typ enthält Angaben zur Identifikation einer Behörde / öffentlichen Stelle in einem Verzeichnisdienst sowie ihrer Erreichbarkeit.
 * XSD Type: BehoerdeType
 */
class BehoerdeTypeType
{
    /**
     * Angabe des Verzeichnisdienstes (bspw. DVDV), in welchem die Behörde / öffentliche Stelle unter der nachfolgend angegebenen Kennung eingetragen ist.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Basisnachricht\Behoerde\CodeVerzeichnisdienstTypeType $verzeichnisdienst
     */
    private $verzeichnisdienst = null;

    /**
     * Dieses Element enthält die eindeutige Kennzeichnung der Behörde / öffentlichen Stelle innerhalb des angegebenen Verzeichnisdienstes. Für den Verzeichnisdienst „DVDV“ enthält die Kennzeichnung das „Präfix“ und die „Kennung“ getrennt durch das Zeichen ':', also bspw. 'psw:01003110'.
     *
     * @var string $kennung
     */
    private $kennung = null;

    /**
     * Dieses Element enthält den Namen der Behörde / öffentlichen Stelle.
     *
     * @var string $name
     */
    private $name = null;

    /**
     * Angaben zur Erreichbarkeit dieser Behörde per Telefon, Telefax, E-Mail etc.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Basisnachricht\Kommunikation\Erreichbarkeit[] $erreichbarkeit
     */
    private $erreichbarkeit = [
        
    ];

    /**
     * Gets as verzeichnisdienst
     *
     * Angabe des Verzeichnisdienstes (bspw. DVDV), in welchem die Behörde / öffentliche Stelle unter der nachfolgend angegebenen Kennung eingetragen ist.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Basisnachricht\Behoerde\CodeVerzeichnisdienstTypeType
     */
    public function getVerzeichnisdienst()
    {
        return $this->verzeichnisdienst;
    }

    /**
     * Sets a new verzeichnisdienst
     *
     * Angabe des Verzeichnisdienstes (bspw. DVDV), in welchem die Behörde / öffentliche Stelle unter der nachfolgend angegebenen Kennung eingetragen ist.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Basisnachricht\Behoerde\CodeVerzeichnisdienstTypeType $verzeichnisdienst
     * @return self
     */
    public function setVerzeichnisdienst(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Basisnachricht\Behoerde\CodeVerzeichnisdienstTypeType $verzeichnisdienst)
    {
        $this->verzeichnisdienst = $verzeichnisdienst;
        return $this;
    }

    /**
     * Gets as kennung
     *
     * Dieses Element enthält die eindeutige Kennzeichnung der Behörde / öffentlichen Stelle innerhalb des angegebenen Verzeichnisdienstes. Für den Verzeichnisdienst „DVDV“ enthält die Kennzeichnung das „Präfix“ und die „Kennung“ getrennt durch das Zeichen ':', also bspw. 'psw:01003110'.
     *
     * @return string
     */
    public function getKennung()
    {
        return $this->kennung;
    }

    /**
     * Sets a new kennung
     *
     * Dieses Element enthält die eindeutige Kennzeichnung der Behörde / öffentlichen Stelle innerhalb des angegebenen Verzeichnisdienstes. Für den Verzeichnisdienst „DVDV“ enthält die Kennzeichnung das „Präfix“ und die „Kennung“ getrennt durch das Zeichen ':', also bspw. 'psw:01003110'.
     *
     * @param string $kennung
     * @return self
     */
    public function setKennung($kennung)
    {
        $this->kennung = $kennung;
        return $this;
    }

    /**
     * Gets as name
     *
     * Dieses Element enthält den Namen der Behörde / öffentlichen Stelle.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets a new name
     *
     * Dieses Element enthält den Namen der Behörde / öffentlichen Stelle.
     *
     * @param string $name
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Adds as erreichbarkeit
     *
     * Angaben zur Erreichbarkeit dieser Behörde per Telefon, Telefax, E-Mail etc.
     *
     * @return self
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Basisnachricht\Kommunikation\Erreichbarkeit $erreichbarkeit
     */
    public function addToErreichbarkeit(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Basisnachricht\Kommunikation\Erreichbarkeit $erreichbarkeit)
    {
        $this->erreichbarkeit[] = $erreichbarkeit;
        return $this;
    }

    /**
     * isset erreichbarkeit
     *
     * Angaben zur Erreichbarkeit dieser Behörde per Telefon, Telefax, E-Mail etc.
     *
     * @param int|string $index
     * @return bool
     */
    public function issetErreichbarkeit($index)
    {
        return isset($this->erreichbarkeit[$index]);
    }

    /**
     * unset erreichbarkeit
     *
     * Angaben zur Erreichbarkeit dieser Behörde per Telefon, Telefax, E-Mail etc.
     *
     * @param int|string $index
     * @return void
     */
    public function unsetErreichbarkeit($index)
    {
        unset($this->erreichbarkeit[$index]);
    }

    /**
     * Gets as erreichbarkeit
     *
     * Angaben zur Erreichbarkeit dieser Behörde per Telefon, Telefax, E-Mail etc.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Basisnachricht\Kommunikation\Erreichbarkeit[]
     */
    public function getErreichbarkeit()
    {
        return $this->erreichbarkeit;
    }

    /**
     * Sets a new erreichbarkeit
     *
     * Angaben zur Erreichbarkeit dieser Behörde per Telefon, Telefax, E-Mail etc.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Basisnachricht\Kommunikation\Erreichbarkeit[] $erreichbarkeit
     * @return self
     */
    public function setErreichbarkeit(?array $erreichbarkeit = null)
    {
        $this->erreichbarkeit = $erreichbarkeit;
        return $this;
    }
}

