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

use Psr\Log\LoggerInterface;

/**
 * Helper class to safely execute extraction operations with centralized error handling.
 */
class SafeExtractionHelper
{
    public function __construct(private readonly LoggerInterface $logger)
    {
    }

    /**
     * Safely executes an extraction operation with error handling and logging.
     *
     * @param callable $extractor The extraction operation to execute
     * @param string $operation A description of the operation being performed
     * @param array<string, mixed> $context Additional context for logging
     */
    public function safelyExtract(callable $extractor, string $operation, array $context = []): void
    {
        try {
            $extractor();
        } catch (\Throwable $e) {
            $this->logger->error("Error extracting {$operation}", array_merge($context, [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]));
        }
    }

    /**
     * Safely extracts a value using a callback, returning null on any error.
     *
     * @template T
     * @param callable(): T $extractor The extraction operation to execute
     * @param string $operation A description of the operation being performed
     * @param array<string, mixed> $context Additional context for logging
     * @return T|null The extracted value or null if extraction failed
     */
    public function safelyExtractValue(callable $extractor, string $operation, array $context = []): mixed
    {
        try {
            return $extractor();
        } catch (\Throwable $e) {
            $this->logger->error("Error extracting {$operation}", array_merge($context, [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]));
            return null;
        }
    }
}