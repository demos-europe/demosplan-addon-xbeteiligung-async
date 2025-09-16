<?php

declare(strict_types=1);

/**
 * This file is part of the package demosplan.
 *
 * (c) 2010-present DEMOS plan GmbH, for more information see the license file.
 *
 * All rights reserved
 */

namespace DemosEurope\DemosplanAddon\XBeteiligung\Logic;

/**
 * Utility class to extract names from various XML object types.
 * Handles different name formats and structures used in XBeteiligung schema.
 */
class NameExtractor
{
    /**
     * Extracts organization name from an XML element.
     * Handles multiple name formats including NameOrganisationType.
     *
     * @param mixed $xmlElement The XML element containing name information
     * @return string|null The extracted name or null if not found
     */
    public function extractOrganizationName(mixed $xmlElement): ?string
    {
        if (null === $xmlElement) {
            return null;
        }

        if (method_exists($xmlElement, 'getName')) {
            $nameElement = $xmlElement->getName();
            if (null !== $nameElement) {
                return $this->extractNameValue($nameElement);
            }
        }

        return $this->extractNameValue($xmlElement);
    }

    /**
     * Extracts the actual name value from a name element.
     * Handles nested name structures and different value accessors.
     *
     * @param mixed $nameElement The name element to extract value from
     * @return string|null The extracted name value or null if not found
     */
    public function extractNameValue(mixed $nameElement): ?string
    {
        if (null === $nameElement) {
            return null;
        }

        if (method_exists($nameElement, 'getName')) {
            $nestedName = $nameElement->getName();
            if (null !== $nestedName && '' !== trim($nestedName)) {
                return trim($nestedName);
            }
        }

        if (method_exists($nameElement, 'getValue')) {
            $value = $nameElement->getValue();
            if (null !== $value && '' !== trim((string) $value)) {
                return trim((string) $value);
            }
        }

        $stringValue = (string) $nameElement;
        if ('' !== trim($stringValue)) {
            return trim($stringValue);
        }

        return null;
    }

    /**
     * Extracts person name from a contact person XML element.
     *
     * @param mixed $contactPersonElement The contact person XML element
     * @return string|null The extracted person name or null if not found
     */
    public function extractPersonName(mixed $contactPersonElement): ?string
    {
        if (null === $contactPersonElement || !method_exists($contactPersonElement, 'getName')) {
            return null;
        }

        $nameElement = $contactPersonElement->getName();
        return $this->extractNameValue($nameElement);
    }

    /**
     * Extracts contact value (email, phone, etc.) from an XML element.
     *
     * @param mixed $contactElement The contact XML element
     * @return string|null The extracted contact value or null if not found
     */
    public function extractContactValue(mixed $contactElement): ?string
    {
        if (null === $contactElement) {
            return null;
        }

        if (method_exists($contactElement, 'getValue')) {
            $value = $contactElement->getValue();
            if (null !== $value && '' !== trim((string) $value)) {
                return trim((string) $value);
            }
        }

        $stringValue = (string) $contactElement;
        if ('' !== trim($stringValue)) {
            return trim($stringValue);
        }

        return null;
    }
}