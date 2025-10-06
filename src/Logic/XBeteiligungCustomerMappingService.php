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
        '98' => 'hh',  // develop environment     maybe we should use a test customer here?
        '99' => 'hh'   // integration environment maybe we should use a test customer here?
    ];

    public function __construct(
        private readonly CustomerServiceInterface $customerService,
        private readonly LoggerInterface $logger
    ) {
    }

    /**
     * Get a customer entity by federal state code.
     *
     * @param string $federalStateCode The federal state code (2 digits)
     * @return CustomerInterface The customer entity
     * @throws Exception
     */
    public function getCustomerByFederalStateCode(string $federalStateCode): CustomerInterface
    {
        if (!array_key_exists($federalStateCode, self::FEDERAL_STATE_TO_SUBDOMAIN_MAP)) {
            throw new InvalidArgumentException(
                "No subdomain mapping found for federal state code: {$federalStateCode}"
            );
        }

        $subdomain = self::FEDERAL_STATE_TO_SUBDOMAIN_MAP[$federalStateCode];

        try {
            $customer = $this->customerService->findCustomerBySubdomain($subdomain);

            $this->logger->info('Successfully mapped federal state code to customer', [
                'federal_state_code' => $federalStateCode,
                'subdomain' => $subdomain,
                'customer_id' => $customer->getId()
            ]);

            return $customer;
        } catch (Exception $e) {
            $this->logger->error('Customer not found for federal state code', [
                'federal_state_code' => $federalStateCode,
                'subdomain' => $subdomain,
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }
}
