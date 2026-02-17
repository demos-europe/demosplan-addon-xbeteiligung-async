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

use DemosEurope\DemosplanAddon\XBeteiligung\Configuration\XBeteiligungConfiguration;
use DemosEurope\DemosplanAddon\XBeteiligung\Services\XBeteiligungMessageTransport;
use Exception;
use RuntimeException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

#[AsCommand(
    name: 'xbeteiligung:send-to-rabbitmq',
    description: 'Send XBeteiligung XML to RabbitMQ exchange with custom routing'
)]
class SendXmlToRabbitMqCommand extends Command
{
    private const OUTPUT_DIR = 'develop_xmls';

    public function __construct(
        private readonly XBeteiligungMessageTransport $messageTransport,
        private readonly XBeteiligungConfiguration $config,
        private readonly ParameterBagInterface $parameterBag
    ) {
        parent::__construct();
    }

    // Available projects
    private const AVAILABLE_PROJECTS = [
        'diplanbau',
        'diplanfest',
        'diplanrog',
    ];

    // Federal state codes (Bundesland AGS) with subdomain mapping
    private const BUNDESLAND_CODES = [
        '01' => ['name' => 'Schleswig-Holstein', 'subdomain' => 'sh'],
        '02' => ['name' => 'Hamburg', 'subdomain' => 'hh'],
        '03' => ['name' => 'Niedersachsen', 'subdomain' => 'ni'],
        '04' => ['name' => 'Bremen', 'subdomain' => 'hb'],
        '05' => ['name' => 'Nordrhein-Westfalen', 'subdomain' => 'nw'],
        '06' => ['name' => 'Hessen', 'subdomain' => 'he'],
        '07' => ['name' => 'Rheinland-Pfalz', 'subdomain' => 'rp'],
        '08' => ['name' => 'Baden-Württemberg', 'subdomain' => 'bw'],
        '09' => ['name' => 'Bayern', 'subdomain' => 'by'],
        '10' => ['name' => 'Saarland', 'subdomain' => 'sl'],
        '11' => ['name' => 'Berlin', 'subdomain' => 'be'],
        '12' => ['name' => 'Brandenburg', 'subdomain' => 'bb'],
        '13' => ['name' => 'Mecklenburg-Vorpommern', 'subdomain' => 'mv'],
        '14' => ['name' => 'Sachsen', 'subdomain' => 'sn'],
        '15' => ['name' => 'Sachsen-Anhalt', 'subdomain' => 'st'],
        '16' => ['name' => 'Thüringen', 'subdomain' => 'th'],
    ];

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Send XBeteiligung XML to RabbitMQ');

        try {
            // Step 1: Select XML file to send
            $xmlFile = $this->askXmlFile($input, $output, $io);
            $xml = file_get_contents($xmlFile);

            if (false === $xml) {
                $io->error("Failed to read XML file: {$xmlFile}");
                return Command::FAILURE;
            }

            // Extract message type from XML
            $messageType = $this->extractMessageType($xml);

            // Step 2: Select RabbitMQ exchange
            $exchange = $this->askExchange($input, $output, $io);

            // Step 3: Select project and federal state (determines BAP, BDP, and customer subdomain)
            $projectData = $this->askProject($input, $output, $io);

            // Step 4: Build routing key
            $routingKey = $this->buildRoutingKey(
                $projectData['bap_code'],
                $projectData['bdp_code'],
                $messageType
            );

            // Show summary
            $io->section('Message Summary');
            $io->definitionList(
                ['XML File' => basename($xmlFile)],
                ['File Size' => number_format(strlen($xml)) . ' bytes'],
                ['Message Type' => $messageType],
                ['Exchange' => $exchange],
                ['Project' => $projectData['name']],
                ['Customer Subdomain' => $projectData['customer_subdomain']],
                ['BAP Code' => $projectData['bap_code']],
                ['BDP Code' => $projectData['bdp_code']],
                ['Routing Key' => $routingKey]
            );

            // Confirm before sending
            if (!$io->confirm('Send message to RabbitMQ?', false)) {
                $io->note('Cancelled by user');
                return Command::SUCCESS;
            }

            // Step 6: Send to RabbitMQ
            $io->section('Sending to RabbitMQ');
            $messageId = $this->sendToRabbitMq($xml, $exchange, $routingKey);

            $io->success('Message sent successfully!');
            $io->definitionList(
                ['Message ID' => $messageId],
                ['Exchange' => $exchange],
                ['Routing Key' => $routingKey]
            );

            return Command::SUCCESS;

        } catch (Exception $e) {
            $io->error([
                'Failed to send message',
                'Error: ' . $e->getMessage(),
                'File: ' . $e->getFile() . ':' . $e->getLine(),
            ]);

            return Command::FAILURE;
        }
    }

    private function askXmlFile(InputInterface $input, OutputInterface $output, SymfonyStyle $io): string
    {
        $addonRootPath = dirname(__DIR__, 2);
        $xmlDir = $addonRootPath . '/' . self::OUTPUT_DIR;

        if (!is_dir($xmlDir)) {
            throw new RuntimeException("XML output directory not found: {$xmlDir}");
        }

        $xmlFiles = glob($xmlDir . '/*.xml');

        if (empty($xmlFiles)) {
            throw new RuntimeException("No XML files found in {$xmlDir}. Generate some XML first using xbeteiligung:generate-xml");
        }

        // Sort by modification time (newest first)
        usort($xmlFiles, function($a, $b) {
            return filemtime($b) - filemtime($a);
        });

        // Create choices with filename and timestamp
        $choices = [];
        foreach ($xmlFiles as $file) {
            $basename = basename($file);
            $time = date('Y-m-d H:i:s', filemtime($file));
            $size = number_format(filesize($file) / 1024, 1) . ' KB';
            $choices[$basename] = "{$basename} ({$time}, {$size})";
        }

        $question = new ChoiceQuestion(
            'Select XML file to send',
            $choices,
            0
        );

        $helper = $this->getHelper('question');
        $selectedFilename = $helper->ask($input, $output, $question);

        // The question returns the key (filename) from the choices array
        return $xmlDir . '/' . $selectedFilename;
    }

    private function extractMessageType(string $xml): string
    {
        // Try to extract message type from root element
        if (preg_match('/<xbeteiligung:kommunal\.(\w+)\.(\d+)/', $xml, $matches)) {
            return $matches[1] . '.' . $matches[2];
        }

        // Try to extract from nachrichtentyp
        if (preg_match('/<name>kommunal\.(\w+)\.(\d+)<\/name>/', $xml, $matches)) {
            return $matches[1] . '.' . $matches[2];
        }

        throw new RuntimeException('Could not extract message type from XML');
    }

    private function askExchange(InputInterface $input, OutputInterface $output, SymfonyStyle $io): string
    {
        $io->text('Fetching exchanges from RabbitMQ...');

        try {
            $exchanges = $this->getAvailableExchanges();

            if (empty($exchanges)) {
                throw new RuntimeException(
                    'No exchanges found in RabbitMQ. Please configure RabbitMQ first.'
                );
            }

            $question = new ChoiceQuestion(
                'Select RabbitMQ exchange',
                $exchanges,
                0
            );

            $helper = $this->getHelper('question');

            return $helper->ask($input, $output, $question);

        } catch (Exception $e) {
            throw new RuntimeException(
                'Could not connect to RabbitMQ: ' . $e->getMessage() .
                '. Please ensure RabbitMQ is running and configured correctly.'
            );
        }
    }

    private function getAvailableExchanges(): array
    {
        // Get exchange from configuration
        $configuredExchange = $this->config->getRabbitMqExchange();

        // Common exchanges based on demosplan setup
        $exchanges = [
            $configuredExchange,
            'init.cockpit',
            'init.demosplan',
        ];

        // Remove duplicates and empty values
        $exchanges = array_unique(array_filter($exchanges));

        return array_values($exchanges);
    }

    private function askProject(InputInterface $input, OutputInterface $output, SymfonyStyle $io): array
    {
        $helper = $this->getHelper('question');

        // Step 1: Select project
        $projects = array_merge(self::AVAILABLE_PROJECTS, ['Custom...']);
        $projectQuestion = new ChoiceQuestion(
            'Select project',
            $projects,
            0
        );

        $projectName = $helper->ask($input, $output, $projectQuestion);

        if ($projectName === 'Custom...') {
            $nameQuestion = new Question('Enter project name: ');
            $projectName = $helper->ask($input, $output, $nameQuestion);
        }

        // Step 2: Select federal state (Bundesland)
        $bundeslandChoices = [];
        foreach (self::BUNDESLAND_CODES as $code => $data) {
            $bundeslandChoices[] = "{$code} - {$data['name']} ({$data['subdomain']})";
        }
        $bundeslandChoices[] = 'Custom code...';

        $bundeslandQuestion = new ChoiceQuestion(
            'Select federal state (Bundesland)',
            $bundeslandChoices,
            0
        );

        $selected = $helper->ask($input, $output, $bundeslandQuestion);

        if ($selected === 'Custom code...') {
            $codeQuestion = new Question('Enter Bundesland code (01-16): ');
            $bundeslandCode = $helper->ask($input, $output, $codeQuestion);

            $subdomainQuestion = new Question('Enter subdomain: ');
            $subdomain = $helper->ask($input, $output, $subdomainQuestion);
        } else {
            // Extract code from selection (format: "01 - Schleswig-Holstein (sh)")
            preg_match('/^(\d+)/', $selected, $matches);
            $bundeslandCode = $matches[1];
            $subdomain = self::BUNDESLAND_CODES[$bundeslandCode]['subdomain'];
        }

        // Construct BAP code: 02.{bundesland_code}.0001
        $bapCode = "02.{$bundeslandCode}.0001";

        // Construct BDP code: 02.{bundesland_code}.0002
        $bdpCode = "02.{$bundeslandCode}.0002";

        return [
            'name' => $projectName,
            'bap_code' => $bapCode,
            'customer_subdomain' => $subdomain,
            'bdp_code' => $bdpCode,
        ];
    }


    private function buildRoutingKey(string $bapCode, string $bdpCode, string $messageType): string
    {
        // Message type format: "Initiieren.0401" or "Aktualisieren.0402"
        // Split into action and code
        $parts = explode('.', $messageType);
        if (count($parts) !== 2) {
            throw new RuntimeException("Invalid message type format: {$messageType}");
        }

        $action = strtolower($parts[0]); // initiieren, aktualisieren, etc.
        $code = $parts[1]; // 0401, 0402, etc.

        // Format: init.cockpit.bap.02.98.0001.bdp.02.98.0002.kommunal.initiieren.0401
        return "init.cockpit.bap.{$bapCode}.bdp.{$bdpCode}.kommunal.{$action}.{$code}";
    }

    private function sendToRabbitMq(string $xml, string $exchange, string $routingKey): string
    {
        // Generate message ID
        $messageId = uniqid('manual_', true);

        // Use the configured message transport to publish with user-selected exchange
        $publisher = $this->messageTransport->createDirectPublisher();
        $success = $publisher->publish($xml, $exchange, $routingKey);

        if (!$success) {
            throw new RuntimeException('Failed to publish message to RabbitMQ');
        }

        return $messageId;
    }
}
