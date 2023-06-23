<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Logic;

use DemosEurope\DemosplanAddon\XBeteiligung\Exeption\ProcedureMessageException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

/**
 * A specialized URL generator for XBeteiligung.
 *
 * This can be used to generate the urls for interacting
 * with a coupled XBeteiligung instance.
 *
 * The urls are absolute based on the configuration
 * parameter `xbeteiligung_api_baseurl` which should always
 * be a full http(s) base url to a XBeteiligung instance.
 *
 * If you can open that URL (given access to the
 * configured network) and receive 'OK. BYE.' as
 * a response, it is the correct URL.
 *
 * The route methods are named after their common
 * {json:api} names, i.e. the `create` action is a `POST`
 * on the `list` route of a resource.
 *
 * While XBeteiligung has more exposed routes, all other ones
 * are intrinsically available via it's API responses and
 * thus never have to be generated.
 */
class XBeteiligungRouter
{

    private const PROCEDURE_DETAIL = 'procedure_detail';

    /**
     * @var UrlGeneratorInterface
     */
    private $generator;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        if (!$parameterBag->has('xbeteiligung_api_baseurl')) {
            throw ProcedureMessageException::missingParameter('xbeteiligung_api_baseurl');
        }

        $routes = new RouteCollection();
        $routes->add(self::PROCEDURE_DETAIL, new Route('/api/procedure/{procedure}/'));

        $requestContext = RequestContext::fromUri($parameterBag->get('xbeteiligung_api_baseurl'));

        $this->generator = new UrlGenerator($routes, $requestContext);
    }


    /**
     * Creates URL for deleting users(allowed senders) or getting user(allowed senders) details
     *
     * @return string
     */
    public function procedureDetail(string $procedureId): string
    {
        return $this->generator->generate(self::PROCEDURE_DETAIL, compact('procedureId'), UrlGeneratorInterface::ABSOLUTE_URL);
    }

}