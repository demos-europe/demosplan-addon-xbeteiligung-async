<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\BeteiligungRaumordnungTOEBType;

/**
 * Class representing BeteiligungRaumordnungTOEBArtAnonymousPHPType
 */
class BeteiligungRaumordnungTOEBArtAnonymousPHPType
{
    /**
     * Hier ist zu übermitteln, ob es sich um eine frühzeitige Behördenbeteiligung (2000) oder eine Beteiligung der Träger öffentlicher Belange (5000) handelt.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\CodeVerfahrensschrittRaumordnungType $beteiligungRaumordnungFormalTOEB
     */
    private $beteiligungRaumordnungFormalTOEB = null;

    /**
     * Hier kann die Art des informellen Verfahrens zur Beteiligung der Träger öffentlicher Belange übermittelt werden.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\CodeBeteiligungRaumordnungInformellTOEBType $beteiligungRaumordnungInformellTOEB
     */
    private $beteiligungRaumordnungInformellTOEB = null;

    /**
     * Gets as beteiligungRaumordnungFormalTOEB
     *
     * Hier ist zu übermitteln, ob es sich um eine frühzeitige Behördenbeteiligung (2000) oder eine Beteiligung der Träger öffentlicher Belange (5000) handelt.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\CodeVerfahrensschrittRaumordnungType
     */
    public function getBeteiligungRaumordnungFormalTOEB()
    {
        return $this->beteiligungRaumordnungFormalTOEB;
    }

    /**
     * Sets a new beteiligungRaumordnungFormalTOEB
     *
     * Hier ist zu übermitteln, ob es sich um eine frühzeitige Behördenbeteiligung (2000) oder eine Beteiligung der Träger öffentlicher Belange (5000) handelt.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\CodeVerfahrensschrittRaumordnungType $beteiligungRaumordnungFormalTOEB
     * @return self
     */
    public function setBeteiligungRaumordnungFormalTOEB(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\CodeVerfahrensschrittRaumordnungType $beteiligungRaumordnungFormalTOEB = null)
    {
        $this->beteiligungRaumordnungFormalTOEB = $beteiligungRaumordnungFormalTOEB;
        return $this;
    }

    /**
     * Gets as beteiligungRaumordnungInformellTOEB
     *
     * Hier kann die Art des informellen Verfahrens zur Beteiligung der Träger öffentlicher Belange übermittelt werden.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\CodeBeteiligungRaumordnungInformellTOEBType
     */
    public function getBeteiligungRaumordnungInformellTOEB()
    {
        return $this->beteiligungRaumordnungInformellTOEB;
    }

    /**
     * Sets a new beteiligungRaumordnungInformellTOEB
     *
     * Hier kann die Art des informellen Verfahrens zur Beteiligung der Träger öffentlicher Belange übermittelt werden.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\CodeBeteiligungRaumordnungInformellTOEBType $beteiligungRaumordnungInformellTOEB
     * @return self
     */
    public function setBeteiligungRaumordnungInformellTOEB(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\CodeBeteiligungRaumordnungInformellTOEBType $beteiligungRaumordnungInformellTOEB = null)
    {
        $this->beteiligungRaumordnungInformellTOEB = $beteiligungRaumordnungInformellTOEB;
        return $this;
    }
}

