<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing FileTypeType
 *
 *
 * XSD Type: FileType
 */
class FileTypeType
{
    /**
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\RangeParameters $rangeParameters
     */
    private $rangeParameters = null;

    /**
     * @var string $fileReference
     */
    private $fileReference = null;

    /**
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeTypeType $fileStructure
     */
    private $fileStructure = null;

    /**
     * @var string $mimeType
     */
    private $mimeType = null;

    /**
     * @var string $compression
     */
    private $compression = null;

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
     * Gets as fileReference
     *
     * @return string
     */
    public function getFileReference()
    {
        return $this->fileReference;
    }

    /**
     * Sets a new fileReference
     *
     * @param string $fileReference
     * @return self
     */
    public function setFileReference($fileReference)
    {
        $this->fileReference = $fileReference;
        return $this;
    }

    /**
     * Gets as fileStructure
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeTypeType
     */
    public function getFileStructure()
    {
        return $this->fileStructure;
    }

    /**
     * Sets a new fileStructure
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeTypeType $fileStructure
     * @return self
     */
    public function setFileStructure(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeTypeType $fileStructure)
    {
        $this->fileStructure = $fileStructure;
        return $this;
    }

    /**
     * Gets as mimeType
     *
     * @return string
     */
    public function getMimeType()
    {
        return $this->mimeType;
    }

    /**
     * Sets a new mimeType
     *
     * @param string $mimeType
     * @return self
     */
    public function setMimeType($mimeType)
    {
        $this->mimeType = $mimeType;
        return $this;
    }

    /**
     * Gets as compression
     *
     * @return string
     */
    public function getCompression()
    {
        return $this->compression;
    }

    /**
     * Sets a new compression
     *
     * @param string $compression
     * @return self
     */
    public function setCompression($compression)
    {
        $this->compression = $compression;
        return $this;
    }
}

