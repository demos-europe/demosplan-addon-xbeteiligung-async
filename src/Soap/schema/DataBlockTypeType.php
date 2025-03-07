<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing DataBlockTypeType
 *
 *
 * XSD Type: DataBlockType
 */
class DataBlockTypeType
{
    /**
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\RangeParameters $rangeParameters
     */
    private $rangeParameters = null;

    /**
     * @var string[] $doubleOrNilReasonTupleList
     */
    private $doubleOrNilReasonTupleList = null;

    /**
     * Gets as rangeParameters
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\RangeParameters
     */
    public function getRangeParameters()
    {
        return $this->rangeParameters;
    }

    /**
     * Sets a new rangeParameters
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\RangeParameters $rangeParameters
     * @return self
     */
    public function setRangeParameters(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\RangeParameters $rangeParameters)
    {
        $this->rangeParameters = $rangeParameters;
        return $this;
    }

    /**
     * Adds as doubleOrNilReasonTupleList
     *
     * @return self
     * @param string $doubleOrNilReasonTupleList
     */
    public function addToDoubleOrNilReasonTupleList($doubleOrNilReasonTupleList)
    {
        $this->doubleOrNilReasonTupleList[] = $doubleOrNilReasonTupleList;
        return $this;
    }

    /**
     * isset doubleOrNilReasonTupleList
     *
     * @param int|string $index
     * @return bool
     */
    public function issetDoubleOrNilReasonTupleList($index)
    {
        return isset($this->doubleOrNilReasonTupleList[$index]);
    }

    /**
     * unset doubleOrNilReasonTupleList
     *
     * @param int|string $index
     * @return void
     */
    public function unsetDoubleOrNilReasonTupleList($index)
    {
        unset($this->doubleOrNilReasonTupleList[$index]);
    }

    /**
     * Gets as doubleOrNilReasonTupleList
     *
     * @return string[]
     */
    public function getDoubleOrNilReasonTupleList()
    {
        return $this->doubleOrNilReasonTupleList;
    }

    /**
     * Sets a new doubleOrNilReasonTupleList
     *
     * @param string $doubleOrNilReasonTupleList
     * @return self
     */
    public function setDoubleOrNilReasonTupleList(array $doubleOrNilReasonTupleList)
    {
        $this->doubleOrNilReasonTupleList = $doubleOrNilReasonTupleList;
        return $this;
    }
}

