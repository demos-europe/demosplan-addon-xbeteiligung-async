<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\BeteiligungRaumordnungOeffentlichkeitType;

/**
 * Class representing BeteiligungRaumordnungOeffentlichkeitArtAnonymousPHPType
 */
class BeteiligungRaumordnungOeffentlichkeitArtAnonymousPHPType
{
    /**
     * Hier ist zu übermitteln, ob es sich um eine frühzeitige Öffentlichkeitsbeteiligung (4000) oder eine digitale Veröffentlichung (6000) handelt.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\CodeVerfahrensschrittRaumordnungType $beteiligungRaumordnungFormalOeffentlichkeit
     */
    private $beteiligungRaumordnungFormalOeffentlichkeit = null;

    /**
     * Hier kann die Art des informellen Verfahrens zur Beteiligung der Öffentlichkeit übermittelt werden.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\CodeBeteiligungRaumodnungInformellOeffentlichkeitType $beteiligungRaumordnungInformellOeffentlichkeit
     */
    private $beteiligungRaumordnungInformellOeffentlichkeit = null;

    /**
     * Gets as beteiligungRaumordnungFormalOeffentlichkeit
     *
     * Hier ist zu übermitteln, ob es sich um eine frühzeitige Öffentlichkeitsbeteiligung (4000) oder eine digitale Veröffentlichung (6000) handelt.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\CodeVerfahrensschrittRaumordnungType
     */
    public function getBeteiligungRaumordnungFormalOeffentlichkeit()
    {
        return $this->beteiligungRaumordnungFormalOeffentlichkeit;
    }

    /**
     * Sets a new beteiligungRaumordnungFormalOeffentlichkeit
     *
     * Hier ist zu übermitteln, ob es sich um eine frühzeitige Öffentlichkeitsbeteiligung (4000) oder eine digitale Veröffentlichung (6000) handelt.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\CodeVerfahrensschrittRaumordnungType $beteiligungRaumordnungFormalOeffentlichkeit
     * @return self
     */
    public function setBeteiligungRaumordnungFormalOeffentlichkeit(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\CodeVerfahrensschrittRaumordnungType $beteiligungRaumordnungFormalOeffentlichkeit = null)
    {
        $this->beteiligungRaumordnungFormalOeffentlichkeit = $beteiligungRaumordnungFormalOeffentlichkeit;
        return $this;
    }

    /**
     * Gets as beteiligungRaumordnungInformellOeffentlichkeit
     *
     * Hier kann die Art des informellen Verfahrens zur Beteiligung der Öffentlichkeit übermittelt werden.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\CodeBeteiligungRaumodnungInformellOeffentlichkeitType
     */
    public function getBeteiligungRaumordnungInformellOeffentlichkeit()
    {
        return $this->beteiligungRaumordnungInformellOeffentlichkeit;
    }

    /**
     * Sets a new beteiligungRaumordnungInformellOeffentlichkeit
     *
     * Hier kann die Art des informellen Verfahrens zur Beteiligung der Öffentlichkeit übermittelt werden.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\CodeBeteiligungRaumodnungInformellOeffentlichkeitType $beteiligungRaumordnungInformellOeffentlichkeit
     * @return self
     */
    public function setBeteiligungRaumordnungInformellOeffentlichkeit(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\CodeBeteiligungRaumodnungInformellOeffentlichkeitType $beteiligungRaumordnungInformellOeffentlichkeit = null)
    {
        $this->beteiligungRaumordnungInformellOeffentlichkeit = $beteiligungRaumordnungInformellOeffentlichkeit;
        return $this;
    }
}

