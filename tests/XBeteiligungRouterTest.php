<?php

declare(strict_types=1);

namespace DemosEurope\DemosplanAddon\XBeteiligung\Tests;

use DemosEurope\DemosplanAddon\XBeteiligung\Logic\XBeteiligungRouter;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

class XBeteiligungRouterTest extends TestCase
{
    /**
     * @var XBeteiligungRouter
     */
    protected $xt;

    public function setUp(): void
    {
        parent::setUp();

        $parameterBag = new ParameterBag([
            'xbeteiligung_api_baseurl' => 'https://xbeteiligung/',
        ]);

        $this->xt = new XBeteiligungRouter($parameterBag);
    }

    public function testRoutes(): void
    {
        $routes = [
            ['https://xbeteiligung/api/procedure/1234/', $this->xt->procedureDetail('1234')],
        ];

        foreach ($routes as $route) {
            [$expected, $actual] = $route;
            self::assertEquals($expected, $actual);
        }
    }
}