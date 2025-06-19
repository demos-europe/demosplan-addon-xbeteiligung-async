<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\KommunalInformationGeloescht0459;

use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Basisnachricht\G2g\NachrichtG2GTypeType;

/**
 * Class representing KommunalInformationGeloescht0459AnonymousPHPType
 */
class KommunalInformationGeloescht0459AnonymousPHPType extends NachrichtG2GTypeType
{
    /**
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\KommunalInformationGeloescht0459\KommunalInformationGeloescht0459AnonymousPHPType\NachrichteninhaltAnonymousPHPType $nachrichteninhalt
     */
    private $nachrichteninhalt = null;

    /**
     * Gets as nachrichteninhalt
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\KommunalInformationGeloescht0459\KommunalInformationGeloescht0459AnonymousPHPType\NachrichteninhaltAnonymousPHPType
     */
    public function getNachrichteninhalt()
    {
        return $this->nachrichteninhalt;
    }

    /**
     * Sets a new nachrichteninhalt
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\KommunalInformationGeloescht0459\KommunalInformationGeloescht0459AnonymousPHPType\NachrichteninhaltAnonymousPHPType $nachrichteninhalt
     * @return self
     */
    public function setNachrichteninhalt(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\KommunalInformationGeloescht0459\KommunalInformationGeloescht0459AnonymousPHPType\NachrichteninhaltAnonymousPHPType $nachrichteninhalt)
    {
        $this->nachrichteninhalt = $nachrichteninhalt;
        return $this;
    }
}

