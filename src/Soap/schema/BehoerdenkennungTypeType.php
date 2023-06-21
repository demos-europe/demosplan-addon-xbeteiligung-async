<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing BehoerdenkennungTypeType
 *
 * Die Behoerdenkennung fasst die Elemente zusammen, unter denen eine Behörde als Anbieter elektronischer Services im DVDV verzeichnet ist. Sie besteht aus einem Präfix und der eigentlichen Kennung. Grundsätzlich gibt es zu jedem Präfix eine entsprechende Schlüsseltabelle für die Kennung. Zum Beispiel werden Standesämter über das Präfix psw und die Standesamtsnummer adressiert.
 * XSD Type: BehoerdenkennungType
 */
class BehoerdenkennungTypeType
{
    /**
     * Klasse für Behördenkennungen. Die Liste der Präfixe für Behördenkennungen wird durch das Bundesverwaltungsamt (BVA) als koordinierende Stelle für das DVDV verwaltet.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodePraefixTypeType $praefix
     */
    private $praefix = null;

    /**
     * Dieses Element kennzeichnet eine Behörde innerhalb der durch den Präfix bezeichneten Klasse eindeutig.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeBehoerdenkennungTypeType $kennung
     */
    private $kennung = null;

    /**
     * Gets as praefix
     *
     * Klasse für Behördenkennungen. Die Liste der Präfixe für Behördenkennungen wird durch das Bundesverwaltungsamt (BVA) als koordinierende Stelle für das DVDV verwaltet.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodePraefixTypeType
     */
    public function getPraefix()
    {
        return $this->praefix;
    }

    /**
     * Sets a new praefix
     *
     * Klasse für Behördenkennungen. Die Liste der Präfixe für Behördenkennungen wird durch das Bundesverwaltungsamt (BVA) als koordinierende Stelle für das DVDV verwaltet.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodePraefixTypeType $praefix
     * @return self
     */
    public function setPraefix(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodePraefixTypeType $praefix)
    {
        $this->praefix = $praefix;
        return $this;
    }

    /**
     * Gets as kennung
     *
     * Dieses Element kennzeichnet eine Behörde innerhalb der durch den Präfix bezeichneten Klasse eindeutig.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeBehoerdenkennungTypeType
     */
    public function getKennung()
    {
        return $this->kennung;
    }

    /**
     * Sets a new kennung
     *
     * Dieses Element kennzeichnet eine Behörde innerhalb der durch den Präfix bezeichneten Klasse eindeutig.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeBehoerdenkennungTypeType $kennung
     * @return self
     */
    public function setKennung(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeBehoerdenkennungTypeType $kennung)
    {
        $this->kennung = $kennung;
        return $this;
    }
}

