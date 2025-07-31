<?php

declare(strict_types=1);

/**
 * This file is part of the package demosplan.
 *
 * (c) 2010-present DEMOS E-Partizipation GmbH, for more information see the license file.
 *
 * All rights reserved
 */

namespace DemosEurope\DemosplanAddon\XBeteiligung\Logic;

use DemosEurope\DemosplanAddon\Contracts\Entities\CustomerInterface;
use DemosEurope\DemosplanAddon\Contracts\Services\CustomerServiceInterface;
use Exception;
use InvalidArgumentException;
use Psr\Log\LoggerInterface;

/**
 * Service for mapping German AGS (Amtlicher Gemeindeschlüssel) codes to DemosPlan customer subdomains.
 *
 * Extracts federal state codes from AGS codes and maps them to corresponding customer subdomains
 * for multi-tenant support in XBeteiligung message processing.
 */
class XBeteiligungCustomerMappingService
{
    /**
     * Federal state code to customer subdomain mapping.
     * Based on German federal state codes (01-16).
     */
    private const FEDERAL_STATE_TO_SUBDOMAIN_MAP = [
        '01' => 'sh',  // Schleswig-Holstein
        '02' => 'hh',  // Hamburg
        '03' => 'ni',  // Niedersachsen
        '04' => 'hb',  // Bremen
        '05' => 'nw',  // Nordrhein-Westfalen
        '06' => 'he',  // Hessen
        '07' => 'rp',  // Rheinland-Pfalz
        '08' => 'bw',  // Baden-Württemberg
        '09' => 'by',  // Bayern
        '10' => 'sl',  // Saarland
        '11' => 'be',  // Berlin
        '12' => 'bb',  // Brandenburg
        '13' => 'mv',  // Mecklenburg-Vorpommern
        '14' => 'sn',  // Sachsen
        '15' => 'st',  // Sachsen-Anhalt
        '16' => 'th',  // Thüringen
    ];

    public function __construct(
        private readonly CustomerServiceInterface $customerService,
        private readonly LoggerInterface $logger
    ) {
    }

    /**
     * Extract the federal state code from an AGS code.
     *
     * AGS codes are structured hierarchically with the first 2 digits representing
     * the federal state (Bundesland) code.
     *
     * @param string $agsCode The AGS code (minimum 2 digits)
     * @return string The federal state code (2 digits)
     * @throws InvalidArgumentException If AGS code is invalid
     */
    public function extractFederalStateCode(string $agsCode): string
    {
        if (strlen($agsCode) < 2) {
            throw new InvalidArgumentException(
                "AGS code must be at least 2 characters long, got: {$agsCode}"
            );
        }

        if (!ctype_digit($agsCode)) {
            throw new InvalidArgumentException(
                "AGS code must contain only digits, got: {$agsCode}"
            );
        }

        $federalStateCode = substr($agsCode, 0, 2);

        $this->logger->debug('Extracted federal state code from AGS', [
            'ags_code' => $agsCode,
            'federal_state_code' => $federalStateCode
        ]);

        return $federalStateCode;
    }

    /**
     * Map an AGS code to a customer subdomain.
     *
     * @param string $agsCode The AGS code
     * @return string The customer subdomain
     * @throws InvalidArgumentException If AGS code or federal state mapping is invalid
     */
    public function mapAgsToCustomerSubdomain(string $agsCode): string
    {
        $federalStateCode = $this->extractFederalStateCode($agsCode);

        if (!array_key_exists($federalStateCode, self::FEDERAL_STATE_TO_SUBDOMAIN_MAP)) {
            throw new InvalidArgumentException(
                "No subdomain mapping found for federal state code: {$federalStateCode} (AGS: {$agsCode})"
            );
        }

        $subdomain = self::FEDERAL_STATE_TO_SUBDOMAIN_MAP[$federalStateCode];

        $this->logger->debug('Mapped AGS code to customer subdomain', [
            'ags_code' => $agsCode,
            'federal_state_code' => $federalStateCode,
            'subdomain' => $subdomain
        ]);

        return $subdomain;
    }

    /**
     * Get a customer entity by AGS code.
     *
     * @param string $agsCode The AGS code
     *
     * @return CustomerInterface The customer entity
     * @throws Exception
     */
    public function getCustomerByAgsCode(string $agsCode): CustomerInterface
    {
        $subdomain = $this->mapAgsToCustomerSubdomain($agsCode);

        try {
            $customer = $this->customerService->findCustomerBySubdomain($subdomain);

            $this->logger->info('Successfully mapped AGS code to customer', [
                'ags_code' => $agsCode,
                'subdomain' => $subdomain,
                'customer_id' => $customer->getId()
            ]);

            return $customer;
        } catch (Exception $e) {
            $this->logger->error('Customer not found for AGS code', [
                'ags_code' => $agsCode,
                'subdomain' => $subdomain,
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }
}
