<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing RangeSetTypeType
 *
 *
 * XSD Type: RangeSetType
 */
class RangeSetTypeType
{
    /**
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\DataBlock $dataBlock
     */
    private $dataBlock = null;

    /**
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\File $file
     */
    private $file = null;

    /**
     * Gets as dataBlock
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\DataBlock
     */
    public function getDataBlock()
    {
        return $this->dataBlock;
    }

    /**
     * Sets a new dataBlock
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\DataBlock $dataBlock
     * @return self
     */
    public function setDataBlock(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\DataBlock $dataBlock = null)
    {
        $this->dataBlock = $dataBlock;
        return $this;
    }

    /**
     * Gets as file
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\File
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Sets a new file
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\File $file
     * @return self
     */
    public function setFile(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\File $file = null)
    {
        $this->file = $file;
        return $this;
    }
}

