<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\ProzessnachrichtenAktenzeichen1121;

use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\NachrichtG2GTypeType;

/**
 * Class representing ProzessnachrichtenAktenzeichen1121AnonymousPHPType
 */
class ProzessnachrichtenAktenzeichen1121AnonymousPHPType extends NachrichtG2GTypeType
{
    /**
     * Dieses Element enthält zur Information die Vorgangsnummer bzw. das Aktenzeichen, unter der die Behörde das Anliegen bearbeitet. Mit der Angabe der Referenz des Antragstellers wird der Bezug für diesen hergestellt. Eine Referenzierung auf eine Nachricht wird in diese Nachricht nicht eingetragen.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BezugTypeType $bezug
     */
    private $bezug = null;

    /**
     * Gets as bezug
     *
     * Dieses Element enthält zur Information die Vorgangsnummer bzw. das Aktenzeichen, unter der die Behörde das Anliegen bearbeitet. Mit der Angabe der Referenz des Antragstellers wird der Bezug für diesen hergestellt. Eine Referenzierung auf eine Nachricht wird in diese Nachricht nicht eingetragen.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BezugTypeType
     */
    public function getBezug()
    {
        return $this->bezug;
    }

    /**
     * Sets a new bezug
     *
     * Dieses Element enthält zur Information die Vorgangsnummer bzw. das Aktenzeichen, unter der die Behörde das Anliegen bearbeitet. Mit der Angabe der Referenz des Antragstellers wird der Bezug für diesen hergestellt. Eine Referenzierung auf eine Nachricht wird in diese Nachricht nicht eingetragen.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BezugTypeType $bezug
     * @return self
     */
    public function setBezug(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BezugTypeType $bezug)
    {
        $this->bezug = $bezug;
        return $this;
    }
}

