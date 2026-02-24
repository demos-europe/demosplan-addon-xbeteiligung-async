<?php

declare(strict_types=1);

/**
 * This file is part of the package demosplan.
 *
 * (c) 2010-present DEMOS plan GmbH, for more information see the license file.
 *
 * All rights reserved
 */

namespace DemosEurope\DemosplanAddon\XBeteiligung\Command;

use DemosEurope\DemosplanAddon\XBeteiligung\DataFactory\XBeteiligungXmlGenerator;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\CommonHelpers;
use Exception;
use Psr\Log\NullLogger;
use RuntimeException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'xbeteiligung:generate:xml',
    description: 'Generate XBeteiligung test XML from YAML scenario files (supports multiple message types)'
)]
class GenerateXBeteiligungXmlCommand extends Command
{
    private const OUTPUT_DIR = 'develop_xmls';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('XBeteiligung XML Generator');

        try {
            // Get addon root path
            $addonRootPath = dirname(__DIR__, 2);

            // Step 1: Ask user to select message type
            $messageTypes = XBeteiligungXmlGenerator::getAvailableMessageTypes($addonRootPath);

            if (empty($messageTypes)) {
                $io->error('No message types found in test-data directory!');
                return Command::FAILURE;
            }

            $messageType = $this->askMessageType($input, $output, $messageTypes);

            // Step 2: Initialize factory with selected message type
            $factory = $this->createFactory($messageType);

            // Step 3: Get available scenarios for this message type
            $scenarios = $factory->getAvailableScenarios();

            if (empty($scenarios['valid']) && empty($scenarios['invalid'])) {
                $io->error("No scenario files found for message type '{$messageType}'!");
                return Command::FAILURE;
            }

            // Step 4: Ask user to select scenario type (valid/invalid)
            $isValid = $this->askScenarioType($input, $output, $io);

            // Get scenarios for selected type
            $availableScenarios = $isValid ? $scenarios['valid'] : $scenarios['invalid'];

            if (empty($availableScenarios)) {
                $io->warning('No scenarios found for type: ' . ($isValid ? 'valid' : 'invalid'));
                return Command::FAILURE;
            }

            // Step 5: Ask user to select specific scenario
            $scenarioName = $this->askScenarioName($input, $output, $availableScenarios);

            // Show scenario info
            $info = $factory->getScenarioInfo($scenarioName, $isValid);
            $io->section('Scenario Information');
            $io->definitionList(
                ['Message Type' => $messageType],
                ['Scenario Name' => $scenarioName],
                ['Scenario Type' => $isValid ? 'Valid' : 'Invalid'],
                ['Description' => $info['description']]
            );

            // Generate XML
            $io->section('Generating XML');
            $xml = $factory->createXML($scenarioName, $isValid);

            // Save to file
            $outputFile = $this->saveXmlToFile($messageType, $scenarioName, $xml);

            // Show success message and statistics
            $io->success('XML generated successfully!');
            $io->definitionList(
                ['Output file' => $outputFile],
                ['File size' => number_format(strlen($xml)) . ' bytes'],
                ['Anlagen sections' => (string) substr_count($xml, '<xbeteiligung:anlagen>')],
                ['Dokument elements' => (string) substr_count($xml, '<xbeteiligung:dokument>')]
            );

            $dokumentCount = substr_count($xml, '<xbeteiligung:dokument>');
            if ($dokumentCount > 0) {
                $io->note('File attachments successfully processed and base64-encoded!');
            }

            return Command::SUCCESS;

        } catch (Exception $e) {
            $io->error([
                'Failed to generate XML',
                'Error: ' . $e->getMessage(),
                'File: ' . $e->getFile() . ':' . $e->getLine(),
            ]);

            return Command::FAILURE;
        }
    }

    private function createFactory(string $messageType): XBeteiligungXmlGenerator
    {
        $logger = new NullLogger();
        $commonHelpers = new CommonHelpers($logger);

        // Get addon root path: Command is in src/Command/, so go up 2 levels
        $addonRootPath = dirname(__DIR__, 2);

        return new XBeteiligungXmlGenerator(
            $addonRootPath,
            $commonHelpers,
            $messageType
        );
    }

    private function askMessageType(InputInterface $input, OutputInterface $output, array $messageTypes): string
    {
        $question = new ChoiceQuestion(
            'Select message type',
            $messageTypes,
            0  // default to first message type
        );

        $helper = $this->getHelper('question');

        return $helper->ask($input, $output, $question);
    }

    private function askScenarioType(InputInterface $input, OutputInterface $output, SymfonyStyle $io): bool
    {
        $question = new ChoiceQuestion(
            'Select scenario type',
            ['valid', 'invalid'],
            0  // default to 'valid'
        );

        $helper = $this->getHelper('question');
        $type = $helper->ask($input, $output, $question);

        return 'valid' === $type;
    }

    private function askScenarioName(InputInterface $input, OutputInterface $output, array $scenarios): string
    {
        $question = new ChoiceQuestion(
            'Select scenario to generate XML from',
            $scenarios,
            0  // default to first scenario
        );

        $helper = $this->getHelper('question');

        return $helper->ask($input, $output, $question);
    }

    private function saveXmlToFile(string $messageType, string $scenarioName, string $xml): string
    {
        $timestamp = date('Y-m-d_H-i-s');
        $addonRootPath = dirname(__DIR__, 2);
        $outputDir = $addonRootPath . '/' . self::OUTPUT_DIR;
        $outputFile = "{$outputDir}/{$messageType}_{$scenarioName}_{$timestamp}.xml";

        // Ensure output directory exists
        if (!is_dir($outputDir) && !mkdir($outputDir, 0755, true) && !is_dir($outputDir)) {
            throw new RuntimeException(sprintf('Directory "%s" was not created', $outputDir));
        }

        // Save XML to file
        file_put_contents($outputFile, $xml);

        return $outputFile;
    }
}
