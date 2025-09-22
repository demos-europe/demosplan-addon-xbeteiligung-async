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

use DemosEurope\DemosplanAddon\XBeteiligung\ValueObject\Procedure\ProcedureDataValueObject;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Basisnachricht\G2g\NachrichtG2GTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\PlanfeststellungInitiieren0201;
use DateTime;
use Psr\Log\LoggerInterface;

/**
 * Service to extract and map procedure data from XML objects to value objects.
 * Handles the conversion from XBeteiligung XML schema objects to internal data structures.
 */
class XmlDataExtractorService
{
    private const PARTICIPATION_METHODS = ['getBeteiligung', 'getVerfahren', 'getProcedure'];
    private const DATE_METHODS = [
        'start' => ['getStartdatum', 'getStartDatum'],
        'end' => ['getEnddatum', 'getEndDatum']
    ];

    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly SafeExtractionHelper $safeExtractor,
        private readonly NameExtractor $nameExtractor
    ) {
    }

    /**
     * Generic method to extract procedure data from any NachrichtG2GTypeType XML object.
     * Maps the XML structure to a ProcedureDataValueObject for further processing.
     *
     * @param NachrichtG2GTypeType $xmlObject The parsed XML object extending NachrichtG2GTypeType
     * @return ProcedureDataValueObject The extracted and mapped data
     */
    public function extractFromG2GMessage(NachrichtG2GTypeType $xmlObject): ProcedureDataValueObject
    {
        $xmlObjectClass = get_class($xmlObject);
        $this->logger->debug('Starting extraction from G2G message', ['class' => $xmlObjectClass]);

        $procedureData = new ProcedureDataValueObject();

        $this->extractMessageMetadata($xmlObject, $procedureData);

        $messageContent = $this->getMessageContent($xmlObject);
        if (null === $messageContent) {
            $this->logger->warning('No message content found in XML object', ['class' => $xmlObjectClass]);
            return $procedureData;
        }

        $this->extractProcessId($messageContent, $procedureData);

        $participation = $this->getParticipation($messageContent);
        if (null !== $participation) {
            $this->extractProcedureData($participation, $procedureData);
        } else {
            $this->logger->warning('No participation found in message content', ['class' => $xmlObjectClass]);
        }

        $this->logger->info('Successfully extracted procedure data from G2G message', [
            'class' => $xmlObjectClass
        ]);

        return $procedureData;
    }

    /**
     * Legacy method for backward compatibility.
     * @deprecated Use extractFromG2GMessage instead
     */
    public function extractFromPlanfeststellungInitiieren0201(PlanfeststellungInitiieren0201 $xmlObject): ProcedureDataValueObject
    {
        return $this->extractFromG2GMessage($xmlObject);
    }

    /**
     * Extracts the contact organization name from XML message content.
     * This is used specifically for validation purposes.
     *
     * @param mixed $messageContent The nachrichteninhalt from any message type
     * @return string|null The organization name if found, null otherwise
     */
    public function extractOrganizationName(mixed $messageContent): ?string
    {
        $participation = $this->getParticipation($messageContent);
        if (null === $participation || !method_exists($participation, 'getAkteurVorhaben')) {
            return null;
        }

        $projectActor = $participation->getAkteurVorhaben();
        if (null === $projectActor || !method_exists($projectActor, 'getVeranlasser')) {
            return null;
        }

        $veranlasser = $projectActor->getVeranlasser();
        return $this->nameExtractor->extractOrganizationName($veranlasser);
    }

    /**
     * Extracts message metadata from the base G2G message type.
     */
    private function extractMessageMetadata(NachrichtG2GTypeType $xmlObject, ProcedureDataValueObject $procedureData): void
    {
        $additionalInfo = [];

        if (null !== $xmlObject->getProdukt()) {
            $additionalInfo['product'] = $xmlObject->getProdukt();
        }

        if (null !== $xmlObject->getVersion()) {
            $additionalInfo['version'] = $xmlObject->getVersion();
        }

        if (null !== $xmlObject->getStandard()) {
            $additionalInfo['standard'] = $xmlObject->getStandard();
        }

        if (null !== $xmlObject->getTest()) {
            $additionalInfo['testMessage'] = $xmlObject->getTest();
        }

        $procedureData->setAdditionalInformation($additionalInfo);
    }

    /**
     * Generic method to get message content from any G2G message type.
     */
    private function getMessageContent(NachrichtG2GTypeType $xmlObject): mixed
    {
        if (method_exists($xmlObject, 'getNachrichteninhalt')) {
            return $xmlObject->getNachrichteninhalt();
        }

        $this->logger->warning('XML object does not have getNachrichteninhalt method', [
            'class' => get_class($xmlObject)
        ]);

        return null;
    }

    /**
     * Extracts process ID from message content.
     */
    private function extractProcessId(mixed $messageContent, ProcedureDataValueObject $procedureData): void
    {
        if (method_exists($messageContent, 'getVorgangsID')) {
            $processId = $messageContent->getVorgangsID();
            if (null !== $processId) {
                $processIdValue = $this->nameExtractor->extractContactValue($processId);
                if (null !== $processIdValue) {
                    $procedureData->setProcessId($processIdValue);
                }
            }
        }
    }

    /**
     * Generic method to get participation object from message content.
     */
    private function getParticipation(mixed $messageContent): mixed
    {
        foreach (self::PARTICIPATION_METHODS as $method) {
            if (method_exists($messageContent, $method)) {
                return $messageContent->$method();
            }
        }

        return null;
    }

    /**
     * Consolidated method to extract all procedure-related data from participation object.
     */
    private function extractProcedureData(mixed $participation, ProcedureDataValueObject $procedureData): void
    {
        $this->safeExtractor->safelyExtract(
            fn() => $this->extractBasicPlanInformation($participation, $procedureData),
            'basic plan information'
        );

        $this->safeExtractor->safelyExtract(
            fn() => $this->extractProceduralInformation($participation, $procedureData),
            'procedural information'
        );

        $this->safeExtractor->safelyExtract(
            fn() => $this->extractTemporalInformation($participation, $procedureData),
            'temporal information'
        );

        $this->safeExtractor->safelyExtract(
            fn() => $this->extractContactInformation($participation, $procedureData),
            'contact information'
        );

        $this->safeExtractor->safelyExtract(
            fn() => $this->extractAdditionalMetadata($participation, $procedureData),
            'additional metadata'
        );
    }

    /**
     * Extracts basic plan information (ID, name, title, type).
     */
    private function extractBasicPlanInformation(mixed $participation, ProcedureDataValueObject $procedureData): void
    {
        $planId = $participation->getPlanID();
        if (null !== $planId) {
            $planId = str_replace(XBeteiligungService::STATEMENT_ID_PREFIX, '', $planId);
            $procedureData->setPlanId($planId);
        }

        $planName = $participation->getPlanname();
        if (null !== $planName) {
            $procedureData->setPlanName($planName);
        }

        $workingTitle = $participation->getArbeitstitel();
        if (null !== $workingTitle) {
            $procedureData->setWorkingTitle($workingTitle);
        }

        $planType = $participation->getPlanart();
        if (null !== $planType) {
            $planTypeValue = method_exists($planType, 'getValue')
                ? $planType->getValue()
                : (property_exists($planType, 'value') ? $planType->value : null);
            $procedureData->setPlanType($planTypeValue);
        }
    }

    /**
     * Extracts procedural information (participation type, procedural step).
     */
    private function extractProceduralInformation(mixed $participation, ProcedureDataValueObject $procedureData): void
    {
        if (method_exists($participation, 'getBeteiligungsart')) {
            $participationType = $participation->getBeteiligungsart();
            if (null !== $participationType) {
                $participationTypeValue = method_exists($participationType, 'getValue') ? $participationType->getValue() : null;
                $procedureData->setParticipationType($participationTypeValue);
            }
        }

        if (method_exists($participation, 'getVerfahrensschritt')) {
            $proceduralStep = $participation->getVerfahrensschritt();
            if (null !== $proceduralStep) {
                $proceduralStepValue = method_exists($proceduralStep, 'getValue') ? $proceduralStep->getValue() : (string) $proceduralStep;
                $procedureData->setProceduralStep($proceduralStepValue);
            }
        }
    }

    /**
     * Extracts temporal information (start/end dates).
     */
    private function extractTemporalInformation(mixed $participation, ProcedureDataValueObject $procedureData): void
    {
        if (method_exists($participation, 'getZeitraum')) {
            $zeitraum = $participation->getZeitraum();
            if (null !== $zeitraum) {
                if (method_exists($zeitraum, 'getBeginn')) {
                    $startDateRaw = $zeitraum->getBeginn();
                    if (null !== $startDateRaw) {
                        $startDate = $this->parseDateTime($startDateRaw);
                        if (null !== $startDate) {
                            $procedureData->setStartDate($startDate);
                        }
                    }
                }

                if (method_exists($zeitraum, 'getEnde')) {
                    $endDateRaw = $zeitraum->getEnde();
                    if (null !== $endDateRaw) {
                        $endDate = $this->parseDateTime($endDateRaw);
                        if (null !== $endDate) {
                            $procedureData->setEndDate($endDate);
                        }
                    }
                }
            }
        }

        if (method_exists($participation, 'getStartdatum') || method_exists($participation, 'getStartDatum')) {
            $startDateRaw = method_exists($participation, 'getStartdatum')
                ? $participation->getStartdatum()
                : $participation->getStartDatum();

            if (null !== $startDateRaw) {
                $startDate = $this->parseDateTime($startDateRaw);
                if (null !== $startDate) {
                    $procedureData->setStartDate($startDate);
                }
            }
        }

        if (method_exists($participation, 'getEnddatum') || method_exists($participation, 'getEndDatum')) {
            $endDateRaw = method_exists($participation, 'getEnddatum')
                ? $participation->getEnddatum()
                : $participation->getEndDatum();

            if (null !== $endDateRaw) {
                $endDate = $this->parseDateTime($endDateRaw);
                if (null !== $endDate) {
                    $procedureData->setEndDate($endDate);
                }
            }
        }

        if (method_exists($participation, 'getBekanntmachung')) {
            $bekanntmachungRaw = $participation->getBekanntmachung();
            if (null !== $bekanntmachungRaw) {
                $bekanntmachung = $this->parseDateTime($bekanntmachungRaw);
                if (null !== $bekanntmachung) {
                    $procedureData->setAnnouncementDate($bekanntmachung);
                }
            }
        }
    }

    /**
     * Extracts contact information from the XML object.
     */
    private function extractContactInformation(mixed $participation, ProcedureDataValueObject $procedureData): void
    {
        if (method_exists($participation, 'getAkteurVorhaben')) {
            $projectActor = $participation->getAkteurVorhaben();
            if (null !== $projectActor) {
                $this->extractContactFromActor($projectActor, $procedureData);
            }
        }

        if (method_exists($participation, 'getKontakt')) {
            $contact = $participation->getKontakt();
            if (null !== $contact) {
                $this->extractDirectContactInfo($contact, $procedureData);
            }
        }
    }

    /**
     * Extracts contact information from project actor object.
     */
    private function extractContactFromActor(mixed $projectActor, ProcedureDataValueObject $procedureData): void
    {
        if (method_exists($projectActor, 'getVeranlasser')) {
            $veranlasser = $projectActor->getVeranlasser();
            $nameValue = $this->nameExtractor->extractOrganizationName($veranlasser);
            if (null !== $nameValue) {
                $procedureData->setContactOrganization($nameValue);
                $this->logger->debug('Extracted organization name from veranlasser', [
                    'organizationName' => $nameValue
                ]);
            }
        }

        if (method_exists($projectActor, 'getOrganisation')) {
            $organization = $projectActor->getOrganisation();
            if (null !== $organization && method_exists($organization, 'getName')) {
                $orgName = $organization->getName();
                if (null !== $orgName) {
                    $nameValue = method_exists($orgName, 'getValue') ? $orgName->getValue() : (string) $orgName;
                    try {
                        $existingOrgName = $procedureData->getContactOrganization();
                        if (null === $existingOrgName) {
                            $procedureData->setContactOrganization($nameValue);
                        }
                    } catch (\Error $e) {
                        $procedureData->setContactOrganization($nameValue);
                    }
                }
            }
        }

        if (method_exists($projectActor, 'getAnsprechpartner')) {
            $contactPerson = $projectActor->getAnsprechpartner();
            if (null !== $contactPerson) {
                $this->extractPersonContact($contactPerson, $procedureData);
            }
        }
    }

    /**
     * Extracts person contact information.
     */
    private function extractPersonContact(mixed $contactPerson, ProcedureDataValueObject $procedureData): void
    {
        $personName = $this->nameExtractor->extractPersonName($contactPerson);
        if (null !== $personName) {
            $procedureData->setContactPerson($personName);
        }

        if (method_exists($contactPerson, 'getEmail')) {
            $email = $contactPerson->getEmail();
            $emailValue = $this->nameExtractor->extractContactValue($email);
            if (null !== $emailValue) {
                $procedureData->setContactEmail($emailValue);
            }
        }

        if (method_exists($contactPerson, 'getTelefon')) {
            $phone = $contactPerson->getTelefon();
            $phoneValue = $this->nameExtractor->extractContactValue($phone);
            if (null !== $phoneValue) {
                $procedureData->setContactPhone($phoneValue);
            }
        }
    }

    /**
     * Extracts direct contact information fields.
     */
    private function extractDirectContactInfo(mixed $contact, ProcedureDataValueObject $procedureData): void
    {
        $this->logger->debug('Extracting direct contact information', ['contact' => get_class($contact)]);
    }

    /**
     * Extracts additional metadata and stores it in additional information.
     */
    private function extractAdditionalMetadata(mixed $participation, ProcedureDataValueObject $procedureData): void
    {
        $additionalInfo = [];

        if (method_exists($participation, 'getOrtslage')) {
            $location = $participation->getOrtslage();
            if (null !== $location) {
                $locationValue = method_exists($location, 'getValue') ? $location->getValue() : null;
                $procedureData->setLocation($locationValue);
                $additionalInfo['location'] = $locationValue;
            }
        }

        if (method_exists($participation, 'getBeschreibung')) {
            $description = $participation->getBeschreibung();
            if (null !== $description) {
                $descriptionValue = method_exists($description, 'getValue') ? $description->getValue() : (string) $description;
                $procedureData->setDescription($descriptionValue);
                $additionalInfo['description'] = $descriptionValue;
            }
        }

        if (method_exists($participation, 'getBeschreibungPlanungsanlass')) {
            $planDescription = $participation->getBeschreibungPlanungsanlass();
            if (null !== $planDescription) {
                $planDescriptionValue = method_exists($planDescription, 'getValue') ? $planDescription->getValue() : (string) $planDescription;
                $procedureData->setPlanDescription($planDescriptionValue);
                $additionalInfo['planDescription'] = $planDescriptionValue;
            }
        }

        if (method_exists($participation, 'getFlaechenabgrenzungUrl')) {
            $areaUrl = $participation->getFlaechenabgrenzungUrl();
            if (null !== $areaUrl) {
                $areaUrlValue = method_exists($areaUrl, 'getValue') ? $areaUrl->getValue() : (string) $areaUrl;
                $procedureData->setAreaDelimitationUrl($areaUrlValue);
                $additionalInfo['areaDelimitationUrl'] = $areaUrlValue;
            }
        }

        if (method_exists($participation, 'getGeltungsbereich')) {
            $jurisdiction = $participation->getGeltungsbereich();
            if (null !== $jurisdiction) {
                $jurisdictionValue = method_exists($jurisdiction, 'getValue') ? $jurisdiction->getValue() : (string) $jurisdiction;
                $procedureData->setJurisdiction($jurisdictionValue);
                $additionalInfo['jurisdiction'] = $jurisdictionValue;
            }
        }

        if (method_exists($participation, 'getRaeumlicheBeschreibung')) {
            $spatialDescription = $participation->getRaeumlicheBeschreibung();
            if (null !== $spatialDescription) {
                $spatialDescriptionValue = method_exists($spatialDescription, 'getValue') ? $spatialDescription->getValue() : (string) $spatialDescription;
                $procedureData->setSpatialDescription($spatialDescriptionValue);
                $additionalInfo['spatialDescription'] = $spatialDescriptionValue;
            }
        }

        if (method_exists($participation, 'getBeteiligungURL')) {
            $participationUrl = $participation->getBeteiligungURL();
            if (null !== $participationUrl) {
                $participationUrlValue = method_exists($participationUrl, 'getValue') ? $participationUrl->getValue() : (string) $participationUrl;
                $procedureData->setParticipationUrl($participationUrlValue);
                $additionalInfo['participationUrl'] = $participationUrlValue;
            }
        }

        if (method_exists($participation, 'getDurchgang')) {
            $durchgang = $participation->getDurchgang();
            if (null !== $durchgang) {
                $durchgangValue = method_exists($durchgang, 'getValue') ? $durchgang->getValue() : (int) $durchgang;
                $procedureData->setIteration($durchgangValue);
                $additionalInfo['iteration'] = $durchgangValue;
            }
        }

        if (method_exists($participation, 'getWebsite') || method_exists($participation, 'getUrl')) {
            $website = method_exists($participation, 'getWebsite')
                ? $participation->getWebsite()
                : $participation->getUrl();

            if (null !== $website) {
                $websiteValue = method_exists($website, 'getValue') ? $website->getValue() : (string) $website;
                $procedureData->setWebsiteUrl($websiteValue);
                $additionalInfo['websiteUrl'] = $websiteValue;
            }
        }

        $procedureData->setAdditionalInformation($additionalInfo);
    }

    /**
     * Parses a date/time value from various possible formats.
     */
    private function parseDateTime(mixed $dateValue): ?DateTime
    {
        if (null === $dateValue) {
            return null;
        }

        if ($dateValue instanceof DateTime) {
            return $dateValue;
        }

        if (method_exists($dateValue, 'getValue')) {
            $dateValue = $dateValue->getValue();
        }

        $dateString = (string) $dateValue;
        if (empty($dateString)) {
            return null;
        }

        try {
            return new DateTime($dateString);
        } catch (\Exception $e) {
            $this->logger->warning('Failed to parse date value', [
                'dateValue' => $dateString,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }
}
