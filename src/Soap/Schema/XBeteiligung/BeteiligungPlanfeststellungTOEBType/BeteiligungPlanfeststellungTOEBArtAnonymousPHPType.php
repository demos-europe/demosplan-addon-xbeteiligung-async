<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\BeteiligungPlanfeststellungTOEBType;

/**
 * Class representing BeteiligungPlanfeststellungTOEBArtAnonymousPHPType
 */
class BeteiligungPlanfeststellungTOEBArtAnonymousPHPType
{
    /**
     * Hier ist zu übermitteln, ob es sich um eine frühzeitige Behördenbeteiligung (2000) oder eine Beteiligung der Träger öffentlicher Belange (5000) handelt.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\CodeVerfahrensschrittPlanfeststellungType $beteiligungPlanfeststellungFormalTOEB
     */
    private $beteiligungPlanfeststellungFormalTOEB = null;

    /**
     * Hier kann die Art des informellen Verfahrens zur Beteiligung der Träger öffentlicher Belange übermittelt werden.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\CodeBeteiligungPlanfeststellungInformellTOEBType $beteiligungPlanfeststellungInformellTOEB
     */
    private $beteiligungPlanfeststellungInformellTOEB = null;

    /**
     * Gets as beteiligungPlanfeststellungFormalTOEB
     *
     * Hier ist zu übermitteln, ob es sich um eine frühzeitige Behördenbeteiligung (2000) oder eine Beteiligung der Träger öffentlicher Belange (5000) handelt.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\CodeVerfahrensschrittPlanfeststellungType
     */
    public function getBeteiligungPlanfeststellungFormalTOEB()
    {
        return $this->beteiligungPlanfeststellungFormalTOEB;
    }

    /**
     * Sets a new beteiligungPlanfeststellungFormalTOEB
     *
     * Hier ist zu übermitteln, ob es sich um eine frühzeitige Behördenbeteiligung (2000) oder eine Beteiligung der Träger öffentlicher Belange (5000) handelt.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\CodeVerfahrensschrittPlanfeststellungType $beteiligungPlanfeststellungFormalTOEB
     * @return self
     */
    public function setBeteiligungPlanfeststellungFormalTOEB(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\CodeVerfahrensschrittPlanfeststellungType $beteiligungPlanfeststellungFormalTOEB = null)
    {
        $this->beteiligungPlanfeststellungFormalTOEB = $beteiligungPlanfeststellungFormalTOEB;
        return $this;
    }

    /**
     * Gets as beteiligungPlanfeststellungInformellTOEB
     *
     * Hier kann die Art des informellen Verfahrens zur Beteiligung der Träger öffentlicher Belange übermittelt werden.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\CodeBeteiligungPlanfeststellungInformellTOEBType
     */
    public function getBeteiligungPlanfeststellungInformellTOEB()
    {
        return $this->beteiligungPlanfeststellungInformellTOEB;
    }

    /**
     * Sets a new beteiligungPlanfeststellungInformellTOEB
     *
     * Hier kann die Art des informellen Verfahrens zur Beteiligung der Träger öffentlicher Belange übermittelt werden.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\CodeBeteiligungPlanfeststellungInformellTOEBType $beteiligungPlanfeststellungInformellTOEB
     * @return self
     */
    public function setBeteiligungPlanfeststellungInformellTOEB(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\CodeBeteiligungPlanfeststellungInformellTOEBType $beteiligungPlanfeststellungInformellTOEB = null)
    {
        $this->beteiligungPlanfeststellungInformellTOEB = $beteiligungPlanfeststellungInformellTOEB;
        return $this;
    }
}

