<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul;

/**
 * Class representing GeoreferenzierteFlaecheType
 *
 * In eine Instanz diesen Typs werden die Geodaten eines Vorhabens oder einer sonstigen Entität in Form von Flächen eingetragen.
 * XSD Type: GeoreferenzierteFlaeche
 */
class GeoreferenzierteFlaecheType
{
    /**
     * In diesem Element lassen sich georeferenzierte Daten zu Bauvorhaben, Baulasten oder sonstigen Objekten übermitteln, also zu Gegenständen, die im Zusammenhang von XBau-Prozessen näher bestimmt werden sollen. Es lassen sich hier z. B. die äußeren Kanten eines geplanten Gebäudes (Bauvorhaben) georeferenziert abbilden.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\GeoreferenzierteFlaecheType\FlaecheAnonymousPHPType[] $flaeche
     */
    private $flaeche = [
        
    ];

    /**
     * Hier können ergänzend Erläuterungen zur Lage des Bauvorhabens (Vorhabens) auf dem Baugrundstück gegeben werden (z. B. "Anbau an Nordseite").
     *
     * @var string $erlaeuterung
     */
    private $erlaeuterung = null;

    /**
     * Adds as flaeche
     *
     * In diesem Element lassen sich georeferenzierte Daten zu Bauvorhaben, Baulasten oder sonstigen Objekten übermitteln, also zu Gegenständen, die im Zusammenhang von XBau-Prozessen näher bestimmt werden sollen. Es lassen sich hier z. B. die äußeren Kanten eines geplanten Gebäudes (Bauvorhaben) georeferenziert abbilden.
     *
     * @return self
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\GeoreferenzierteFlaecheType\FlaecheAnonymousPHPType $flaeche
     */
    public function addToFlaeche(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\GeoreferenzierteFlaecheType\FlaecheAnonymousPHPType $flaeche)
    {
        $this->flaeche[] = $flaeche;
        return $this;
    }

    /**
     * isset flaeche
     *
     * In diesem Element lassen sich georeferenzierte Daten zu Bauvorhaben, Baulasten oder sonstigen Objekten übermitteln, also zu Gegenständen, die im Zusammenhang von XBau-Prozessen näher bestimmt werden sollen. Es lassen sich hier z. B. die äußeren Kanten eines geplanten Gebäudes (Bauvorhaben) georeferenziert abbilden.
     *
     * @param int|string $index
     * @return bool
     */
    public function issetFlaeche($index)
    {
        return isset($this->flaeche[$index]);
    }

    /**
     * unset flaeche
     *
     * In diesem Element lassen sich georeferenzierte Daten zu Bauvorhaben, Baulasten oder sonstigen Objekten übermitteln, also zu Gegenständen, die im Zusammenhang von XBau-Prozessen näher bestimmt werden sollen. Es lassen sich hier z. B. die äußeren Kanten eines geplanten Gebäudes (Bauvorhaben) georeferenziert abbilden.
     *
     * @param int|string $index
     * @return void
     */
    public function unsetFlaeche($index)
    {
        unset($this->flaeche[$index]);
    }

    /**
     * Gets as flaeche
     *
     * In diesem Element lassen sich georeferenzierte Daten zu Bauvorhaben, Baulasten oder sonstigen Objekten übermitteln, also zu Gegenständen, die im Zusammenhang von XBau-Prozessen näher bestimmt werden sollen. Es lassen sich hier z. B. die äußeren Kanten eines geplanten Gebäudes (Bauvorhaben) georeferenziert abbilden.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\GeoreferenzierteFlaecheType\FlaecheAnonymousPHPType[]
     */
    public function getFlaeche()
    {
        return $this->flaeche;
    }

    /**
     * Sets a new flaeche
     *
     * In diesem Element lassen sich georeferenzierte Daten zu Bauvorhaben, Baulasten oder sonstigen Objekten übermitteln, also zu Gegenständen, die im Zusammenhang von XBau-Prozessen näher bestimmt werden sollen. Es lassen sich hier z. B. die äußeren Kanten eines geplanten Gebäudes (Bauvorhaben) georeferenziert abbilden.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\GeoreferenzierteFlaecheType\FlaecheAnonymousPHPType[] $flaeche
     * @return self
     */
    public function setFlaeche(array $flaeche)
    {
        $this->flaeche = $flaeche;
        return $this;
    }

    /**
     * Gets as erlaeuterung
     *
     * Hier können ergänzend Erläuterungen zur Lage des Bauvorhabens (Vorhabens) auf dem Baugrundstück gegeben werden (z. B. "Anbau an Nordseite").
     *
     * @return string
     */
    public function getErlaeuterung()
    {
        return $this->erlaeuterung;
    }

    /**
     * Sets a new erlaeuterung
     *
     * Hier können ergänzend Erläuterungen zur Lage des Bauvorhabens (Vorhabens) auf dem Baugrundstück gegeben werden (z. B. "Anbau an Nordseite").
     *
     * @param string $erlaeuterung
     * @return self
     */
    public function setErlaeuterung($erlaeuterung)
    {
        $this->erlaeuterung = $erlaeuterung;
        return $this;
    }
}

