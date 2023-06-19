<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing NachrichtenkopfG2GType
 *
 * Nachrichtenkopf für Nachrichten zwischen Behörden und anderen (öffentlichen) Stellen.
 * XSD Type: Nachrichtenkopf.G2G
 */
class NachrichtenkopfG2GType
{
    /**
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\IdentifikationNachrichtType $identifikationNachricht
     */
    private $identifikationNachricht = null;

    /**
     * Hier werden Angaben über den Leser der Nachricht übermittelt. Der Leser ist die Behörde oder andere (öffentliche) Stelle, der die Nachricht zugestellt werden soll.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BehoerdeType $leser
     */
    private $leser = null;

    /**
     * Hier werden Angaben über den Autor der Nachricht übermittelt, die es dem Leser ermöglichen mit dem Autor in Verbindung zu treten. Der Autor ist die Behörde oder andere (öffentliche) Stelle, die aufgrund eines Geschäftsvorfalls die Nachricht erstellt, also bspw. eine Meldebehörde oder ein Standesamt. Für die sendende Behörde wird in der behoerdenkennung immer die Kennung der für den Betroffenen zuständigen Gemeinde bzw. Ausländerbehörde oder die Kennung des für den Personenstandsfall zuständigen Standesamtes übermittelt. Sofern die sendende Behörde für einen Gemeindeverbund oder im Auftrag einer anderen Behörde handelt, ist in diesen Fällen deshalb die Angabe der zuständigen Stelle (Gemeinde oder Behörde) verpflichtend. Daraus ergibt sich auch die Konsequenz, dass Sammelnachrichten nur für die einzelnen Gemeinden bzw. Behörden zulässig sind. Sofern es keine dem obigen Sinn nach zuständige Gemeinde oder Behörde gibt (bspw. bei der Beantragung eine Führungszeugnisses in einer nicht für den Wohnort des Beantragenden zuständigen Meldebehörde), ist in dem Kindelement behoerdenkennung eine beliebige Kennung zu übermitteln, mit der der Autor im DVDV adressiert werden kann.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BehoerdeErreichbarType $autor
     */
    private $autor = null;

    /**
     * Gets as identifikationNachricht
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\IdentifikationNachrichtType
     */
    public function getIdentifikationNachricht()
    {
        return $this->identifikationNachricht;
    }

    /**
     * Sets a new identifikationNachricht
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\IdentifikationNachrichtType $identifikationNachricht
     * @return self
     */
    public function setIdentifikationNachricht(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\IdentifikationNachrichtType $identifikationNachricht)
    {
        $this->identifikationNachricht = $identifikationNachricht;
        return $this;
    }

    /**
     * Gets as leser
     *
     * Hier werden Angaben über den Leser der Nachricht übermittelt. Der Leser ist die Behörde oder andere (öffentliche) Stelle, der die Nachricht zugestellt werden soll.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BehoerdeType
     */
    public function getLeser()
    {
        return $this->leser;
    }

    /**
     * Sets a new leser
     *
     * Hier werden Angaben über den Leser der Nachricht übermittelt. Der Leser ist die Behörde oder andere (öffentliche) Stelle, der die Nachricht zugestellt werden soll.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BehoerdeType $leser
     * @return self
     */
    public function setLeser(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BehoerdeType $leser)
    {
        $this->leser = $leser;
        return $this;
    }

    /**
     * Gets as autor
     *
     * Hier werden Angaben über den Autor der Nachricht übermittelt, die es dem Leser ermöglichen mit dem Autor in Verbindung zu treten. Der Autor ist die Behörde oder andere (öffentliche) Stelle, die aufgrund eines Geschäftsvorfalls die Nachricht erstellt, also bspw. eine Meldebehörde oder ein Standesamt. Für die sendende Behörde wird in der behoerdenkennung immer die Kennung der für den Betroffenen zuständigen Gemeinde bzw. Ausländerbehörde oder die Kennung des für den Personenstandsfall zuständigen Standesamtes übermittelt. Sofern die sendende Behörde für einen Gemeindeverbund oder im Auftrag einer anderen Behörde handelt, ist in diesen Fällen deshalb die Angabe der zuständigen Stelle (Gemeinde oder Behörde) verpflichtend. Daraus ergibt sich auch die Konsequenz, dass Sammelnachrichten nur für die einzelnen Gemeinden bzw. Behörden zulässig sind. Sofern es keine dem obigen Sinn nach zuständige Gemeinde oder Behörde gibt (bspw. bei der Beantragung eine Führungszeugnisses in einer nicht für den Wohnort des Beantragenden zuständigen Meldebehörde), ist in dem Kindelement behoerdenkennung eine beliebige Kennung zu übermitteln, mit der der Autor im DVDV adressiert werden kann.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BehoerdeErreichbarType
     */
    public function getAutor()
    {
        return $this->autor;
    }

    /**
     * Sets a new autor
     *
     * Hier werden Angaben über den Autor der Nachricht übermittelt, die es dem Leser ermöglichen mit dem Autor in Verbindung zu treten. Der Autor ist die Behörde oder andere (öffentliche) Stelle, die aufgrund eines Geschäftsvorfalls die Nachricht erstellt, also bspw. eine Meldebehörde oder ein Standesamt. Für die sendende Behörde wird in der behoerdenkennung immer die Kennung der für den Betroffenen zuständigen Gemeinde bzw. Ausländerbehörde oder die Kennung des für den Personenstandsfall zuständigen Standesamtes übermittelt. Sofern die sendende Behörde für einen Gemeindeverbund oder im Auftrag einer anderen Behörde handelt, ist in diesen Fällen deshalb die Angabe der zuständigen Stelle (Gemeinde oder Behörde) verpflichtend. Daraus ergibt sich auch die Konsequenz, dass Sammelnachrichten nur für die einzelnen Gemeinden bzw. Behörden zulässig sind. Sofern es keine dem obigen Sinn nach zuständige Gemeinde oder Behörde gibt (bspw. bei der Beantragung eine Führungszeugnisses in einer nicht für den Wohnort des Beantragenden zuständigen Meldebehörde), ist in dem Kindelement behoerdenkennung eine beliebige Kennung zu übermitteln, mit der der Autor im DVDV adressiert werden kann.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BehoerdeErreichbarType $autor
     * @return self
     */
    public function setAutor(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BehoerdeErreichbarType $autor)
    {
        $this->autor = $autor;
        return $this;
    }
}

