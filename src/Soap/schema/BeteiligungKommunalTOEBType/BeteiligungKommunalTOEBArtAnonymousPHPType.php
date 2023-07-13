<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BeteiligungKommunalTOEBType;

/**
 * Class representing BeteiligungKommunalTOEBArtAnonymousPHPType
 */
class BeteiligungKommunalTOEBArtAnonymousPHPType
{
    /**
     * Hier ist zu übermitteln, ob es sich um eine frühzeitige Behördenbeteiligung (2000) oder eine Beteiligung der Träger öffentlicher Belange (5000) handelt.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeVerfahrensschrittType $beteiligungKommunalFormalTOEB
     */
    private $beteiligungKommunalFormalTOEB = null;

    /**
     * Hier kann die Art des informellen Verfahrens zur Beteiligung der Träger öffentlicher Belange übermittelt werden.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BeteiligungKommunalTOEBType\BeteiligungKommunalTOEBArtAnonymousPHPType\BeteiligungKommunalInformellTOEBAnonymousPHPType $beteiligungKommunalInformellTOEB
     */
    private $beteiligungKommunalInformellTOEB = null;

    /**
     * Gets as beteiligungKommunalFormalTOEB
     *
     * Hier ist zu übermitteln, ob es sich um eine frühzeitige Behördenbeteiligung (2000) oder eine Beteiligung der Träger öffentlicher Belange (5000) handelt.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeVerfahrensschrittType
     */
    public function getBeteiligungKommunalFormalTOEB()
    {
        return $this->beteiligungKommunalFormalTOEB;
    }

    /**
     * Sets a new beteiligungKommunalFormalTOEB
     *
     * Hier ist zu übermitteln, ob es sich um eine frühzeitige Behördenbeteiligung (2000) oder eine Beteiligung der Träger öffentlicher Belange (5000) handelt.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeVerfahrensschrittType $beteiligungKommunalFormalTOEB
     * @return self
     */
    public function setBeteiligungKommunalFormalTOEB(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeVerfahrensschrittType $beteiligungKommunalFormalTOEB = null)
    {
        $this->beteiligungKommunalFormalTOEB = $beteiligungKommunalFormalTOEB;
        return $this;
    }

    /**
     * Gets as beteiligungKommunalInformellTOEB
     *
     * Hier kann die Art des informellen Verfahrens zur Beteiligung der Träger öffentlicher Belange übermittelt werden.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BeteiligungKommunalTOEBType\BeteiligungKommunalTOEBArtAnonymousPHPType\BeteiligungKommunalInformellTOEBAnonymousPHPType
     */
    public function getBeteiligungKommunalInformellTOEB()
    {
        return $this->beteiligungKommunalInformellTOEB;
    }

    /**
     * Sets a new beteiligungKommunalInformellTOEB
     *
     * Hier kann die Art des informellen Verfahrens zur Beteiligung der Träger öffentlicher Belange übermittelt werden.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BeteiligungKommunalTOEBType\BeteiligungKommunalTOEBArtAnonymousPHPType\BeteiligungKommunalInformellTOEBAnonymousPHPType $beteiligungKommunalInformellTOEB
     * @return self
     */
    public function setBeteiligungKommunalInformellTOEB(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BeteiligungKommunalTOEBType\BeteiligungKommunalTOEBArtAnonymousPHPType\BeteiligungKommunalInformellTOEBAnonymousPHPType $beteiligungKommunalInformellTOEB = null)
    {
        $this->beteiligungKommunalInformellTOEB = $beteiligungKommunalInformellTOEB;
        return $this;
    }
}

