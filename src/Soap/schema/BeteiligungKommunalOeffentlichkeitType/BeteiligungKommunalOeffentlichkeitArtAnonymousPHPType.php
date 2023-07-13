<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BeteiligungKommunalOeffentlichkeitType;

/**
 * Class representing BeteiligungKommunalOeffentlichkeitArtAnonymousPHPType
 */
class BeteiligungKommunalOeffentlichkeitArtAnonymousPHPType
{
    /**
     * Hier ist zu übermitteln, ob es sich um eine frühzeitige Öffentlichkeitsbeteiligung (4000) oder eine öffentliche Auslegung (6000) handelt.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeVerfahrensschrittType $beteiligungKommunalFormalOeffentlichkeit
     */
    private $beteiligungKommunalFormalOeffentlichkeit = null;

    /**
     * Hier kann die Art des informellen Verfahrens zur Beteiligung der Öffentlichkeit übermittelt werden.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BeteiligungKommunalOeffentlichkeitType\BeteiligungKommunalOeffentlichkeitArtAnonymousPHPType\BeteiligungKommunalInformellOeffentlichkeitAnonymousPHPType $beteiligungKommunalInformellOeffentlichkeit
     */
    private $beteiligungKommunalInformellOeffentlichkeit = null;

    /**
     * Gets as beteiligungKommunalFormalOeffentlichkeit
     *
     * Hier ist zu übermitteln, ob es sich um eine frühzeitige Öffentlichkeitsbeteiligung (4000) oder eine öffentliche Auslegung (6000) handelt.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeVerfahrensschrittType
     */
    public function getBeteiligungKommunalFormalOeffentlichkeit()
    {
        return $this->beteiligungKommunalFormalOeffentlichkeit;
    }

    /**
     * Sets a new beteiligungKommunalFormalOeffentlichkeit
     *
     * Hier ist zu übermitteln, ob es sich um eine frühzeitige Öffentlichkeitsbeteiligung (4000) oder eine öffentliche Auslegung (6000) handelt.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeVerfahrensschrittType $beteiligungKommunalFormalOeffentlichkeit
     * @return self
     */
    public function setBeteiligungKommunalFormalOeffentlichkeit(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeVerfahrensschrittType $beteiligungKommunalFormalOeffentlichkeit = null)
    {
        $this->beteiligungKommunalFormalOeffentlichkeit = $beteiligungKommunalFormalOeffentlichkeit;
        return $this;
    }

    /**
     * Gets as beteiligungKommunalInformellOeffentlichkeit
     *
     * Hier kann die Art des informellen Verfahrens zur Beteiligung der Öffentlichkeit übermittelt werden.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BeteiligungKommunalOeffentlichkeitType\BeteiligungKommunalOeffentlichkeitArtAnonymousPHPType\BeteiligungKommunalInformellOeffentlichkeitAnonymousPHPType
     */
    public function getBeteiligungKommunalInformellOeffentlichkeit()
    {
        return $this->beteiligungKommunalInformellOeffentlichkeit;
    }

    /**
     * Sets a new beteiligungKommunalInformellOeffentlichkeit
     *
     * Hier kann die Art des informellen Verfahrens zur Beteiligung der Öffentlichkeit übermittelt werden.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BeteiligungKommunalOeffentlichkeitType\BeteiligungKommunalOeffentlichkeitArtAnonymousPHPType\BeteiligungKommunalInformellOeffentlichkeitAnonymousPHPType $beteiligungKommunalInformellOeffentlichkeit
     * @return self
     */
    public function setBeteiligungKommunalInformellOeffentlichkeit(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BeteiligungKommunalOeffentlichkeitType\BeteiligungKommunalOeffentlichkeitArtAnonymousPHPType\BeteiligungKommunalInformellOeffentlichkeitAnonymousPHPType $beteiligungKommunalInformellOeffentlichkeit = null)
    {
        $this->beteiligungKommunalInformellOeffentlichkeit = $beteiligungKommunalInformellOeffentlichkeit;
        return $this;
    }
}

