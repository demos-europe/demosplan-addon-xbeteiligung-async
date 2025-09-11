<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\BeteiligungPlanfeststellungOeffentlichkeitType;

/**
 * Class representing BeteiligungPlanfeststellungOeffentlichkeitArtAnonymousPHPType
 */
class BeteiligungPlanfeststellungOeffentlichkeitArtAnonymousPHPType
{
    /**
     * Hier ist zu übermitteln, ob es sich um eine frühzeitige Öffentlichkeitsbeteiligung (4000) oder eine digitale Veröffentlichung (6000) handelt.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\CodeVerfahrensschrittPlanfeststellungType $beteiligungPlanfeststellungFormalOeffentlichkeit
     */
    private $beteiligungPlanfeststellungFormalOeffentlichkeit = null;

    /**
     * Hier kann die Art des informellen Verfahrens zur Beteiligung der Öffentlichkeit übermittelt werden.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\CodeBeteiligungPlanfeststellungInformellOeffentlichkeitType $beteiligungPlanfeststellungInformellOeffentlichkeit
     */
    private $beteiligungPlanfeststellungInformellOeffentlichkeit = null;

    /**
     * Gets as beteiligungPlanfeststellungFormalOeffentlichkeit
     *
     * Hier ist zu übermitteln, ob es sich um eine frühzeitige Öffentlichkeitsbeteiligung (4000) oder eine digitale Veröffentlichung (6000) handelt.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\CodeVerfahrensschrittPlanfeststellungType
     */
    public function getBeteiligungPlanfeststellungFormalOeffentlichkeit()
    {
        return $this->beteiligungPlanfeststellungFormalOeffentlichkeit;
    }

    /**
     * Sets a new beteiligungPlanfeststellungFormalOeffentlichkeit
     *
     * Hier ist zu übermitteln, ob es sich um eine frühzeitige Öffentlichkeitsbeteiligung (4000) oder eine digitale Veröffentlichung (6000) handelt.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\CodeVerfahrensschrittPlanfeststellungType $beteiligungPlanfeststellungFormalOeffentlichkeit
     * @return self
     */
    public function setBeteiligungPlanfeststellungFormalOeffentlichkeit(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\CodeVerfahrensschrittPlanfeststellungType $beteiligungPlanfeststellungFormalOeffentlichkeit = null)
    {
        $this->beteiligungPlanfeststellungFormalOeffentlichkeit = $beteiligungPlanfeststellungFormalOeffentlichkeit;
        return $this;
    }

    /**
     * Gets as beteiligungPlanfeststellungInformellOeffentlichkeit
     *
     * Hier kann die Art des informellen Verfahrens zur Beteiligung der Öffentlichkeit übermittelt werden.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\CodeBeteiligungPlanfeststellungInformellOeffentlichkeitType
     */
    public function getBeteiligungPlanfeststellungInformellOeffentlichkeit()
    {
        return $this->beteiligungPlanfeststellungInformellOeffentlichkeit;
    }

    /**
     * Sets a new beteiligungPlanfeststellungInformellOeffentlichkeit
     *
     * Hier kann die Art des informellen Verfahrens zur Beteiligung der Öffentlichkeit übermittelt werden.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\CodeBeteiligungPlanfeststellungInformellOeffentlichkeitType $beteiligungPlanfeststellungInformellOeffentlichkeit
     * @return self
     */
    public function setBeteiligungPlanfeststellungInformellOeffentlichkeit(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\CodeBeteiligungPlanfeststellungInformellOeffentlichkeitType $beteiligungPlanfeststellungInformellOeffentlichkeit = null)
    {
        $this->beteiligungPlanfeststellungInformellOeffentlichkeit = $beteiligungPlanfeststellungInformellOeffentlichkeit;
        return $this;
    }
}

