<?php

declare(strict_types=1);

/**
 * This file is part of the package demosplan.
 *
 * (c) 2010-present DEMOS E-Partizipation GmbH, for more information see the license file.
 *
 * All rights reserved
 */

namespace DemosEurope\DemosplanAddon\XBeteiligung\Tests\Logic;

use DemosEurope\DemosplanAddon\Contracts\Services\CustomerServiceInterface;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\XBeteiligungCustomerMappingService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class XBeteiligungCustomerMappingServiceTest extends TestCase
{
    private XBeteiligungCustomerMappingService $sut;
    private CustomerServiceInterface|MockObject $customerService;
    private LoggerInterface|MockObject $logger;

    protected function setUp(): void
    {
        $this->customerService = $this->createMock(CustomerServiceInterface::class);
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->sut = new XBeteiligungCustomerMappingService($this->customerService, $this->logger);
    }
}
