<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Logic;

use DemosEurope\DemosplanAddon\Contracts\ApiClientInterface;
use DemosEurope\DemosplanAddon\Utilities\Json;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;

class PiResourceFetcher
{
    private ApiClientInterface $apiClient;
    private LoggerInterface $logger;

    public function __construct(ApiClientInterface $apiClient, LoggerInterface $logger)
    {
        $this->apiClient = $apiClient;
        $this->logger = $logger;
    }

    public function getPiResourceInfo(Request $request): string
    {
        $piResourceUrl = $this->getPiSegmentsProposalResourceUrl($request);
        $this->logger->info(">$piResourceUrl<");
        if ('' === $piResourceUrl || null === $piResourceUrl) {
            return '';
        }
        $options = ['http_errors' => false];

        return $this->apiClient->request($piResourceUrl, $options, ApiClientInterface::GET);
    }

    public function getPiSegmentsProposalResourceUrl(Request $request): string
    {
        $requestBody = Json::decodeToArray($request->getContent());

        return isset($requestBody['result_url']) ? $requestBody['result_url'] : '';
    }

}