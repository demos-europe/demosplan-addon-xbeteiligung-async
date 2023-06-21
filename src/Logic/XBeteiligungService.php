<?php

/**
 * This file is part of the package demosplan.
 *
 * (c) 2010-present DEMOS E-Partizipation GmbH, for more information see the license file.
 *
 * All rights reserved
 */

namespace DemosEurope\DemosplanAddon\XBeteiligung\Logic;

use DateTime;
use DemosEurope\DemosplanAddon\Contracts\Entities\ProcedureInterface;
use DemosEurope\DemosplanAddon\Utilities\AddonPath;
use DemosEurope\DemosplanAddon\Contracts\Config\GlobalConfigInterface;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\AnschriftTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BehoerdeErreichbarTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BehoerdenkennungTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BehoerdeTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BeteiligungTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeBehoerdenkennungTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeErreichbarkeitTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodePraefixTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\IdentifikationNachrichtTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\KommunikationTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\NachrichtenkopfG2GTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\NachrichtG2GTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\NameOrganisationTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\OrganisationTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Planung2BeteiligungBeteiligungKommuneAktualisieren0402;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Planung2BeteiligungBeteiligungKommuneLoeschen0409;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Planung2BeteiligungBeteiligungKommuneNeu0401;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Planung2BeteiligungBeteiligungKommuneNeu0401\Planung2BeteiligungBeteiligungKommuneNeu0401AnonymousPHPType\NachrichteninhaltAnonymousPHPType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\PostalischeInlandsanschriftGebaeudeanschriftTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\PostalischeInlandsanschriftGebaeudeanschriftTypeType\HausnummernBisAnonymousPHPType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\PostalischeInlandsanschriftPostfachanschriftTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\PostalischeInlandsanschriftTypeType;
use Exception;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Routing\RouterInterface;
use DemosEurope\DemosplanAddon\Contracts\UserHandlerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class XBeteiligungService
{
    public const XBETEILIGUNG_VERSION = 'V11';
    private \JMS\Serializer\Serializer $serializer;

    public function __construct(
        private readonly GlobalConfigInterface  $globalConfig,
        private readonly LoggerInterface        $logger,
        private readonly RouterInterface                         $router,
        SerializerFactory                       $serializerFactory,
        private readonly TranslatorInterface                     $translator,
        private readonly UserHandlerInterface                    $userHandler,
    ) {
        $this->serializer                           = $serializerFactory->getSerializer();
    }

    // todo information needs to be gathered
    /**
     * @throws Exception
     */
    public function createProcedure401FromObject(ProcedureInterface $procedure): string
    {
        $procedureCreated401Object = new Planung2BeteiligungBeteiligungKommuneNeu0401();
        $procedureCreated401Object->setNachrichtenkopf(
            $this->createMessageHeadFor($procedureCreated401Object)
        ); // required
        $procedureCreated401Object->setNachrichteninhalt(
            $this->generateMain401MessageContent($procedure)
        ); // required
        $procedureCreated401Object->setProdukt(''); // required
        $procedureCreated401Object->setProdukthersteller(''); // required
        $procedureCreated401Object->setProduktversion(''); // optional
        $procedureCreated401Object->setStandard(''); // required
        $procedureCreated401Object->setTest(''); // optional
        $procedureCreated401Object->setVersion(''); // required

        return '';
    }

    // todo does the id matter here?
    private function generateMain401MessageContent(ProcedureInterface $procedure): NachrichteninhaltAnonymousPHPType
    {
        $messageContent = new NachrichteninhaltAnonymousPHPType();
        $messageContent->setVorgangsID($this->uuid());

        $organisationType = new OrganisationTypeType();

        $organisationName = new NameOrganisationTypeType();
        $organisationName->setName($procedure->getOrga()?->getName() ?? '');
        $organisationType->setName($organisationName);
        $postalInformation = new AnschriftTypeType();
        $postalInformation->setStrasse($procedure->getOrga()?->getStreet() ?? '');
        $postalInformation->setHausnummer($procedure->getOrga()?->getHouseNumber() ?? '');
        $postalInformation->setPostleitzahl($procedure->getOrga()?->getPostalcode() ?? '');
        $postalInformation->setOrt($procedure->getOrga()?->getCity() ?? '');
        $organisationType->setAnschrift([$postalInformation]);
        $messageContent->setInitiator($organisationType);

        $beteiligungType = new BeteiligungTypeType();


        $messageContent->setBeteiligung();
    }

    /**
     * @throws Exception
     */
    private function createMessageHeadFor(NachrichtG2GTypeType $messageObject): NachrichtenkopfG2GTypeType
    {
        $messageHead = new NachrichtenkopfG2GTypeType();
        $messageHead->setIdentifikationNachricht($this->createMessageIdentification($messageObject)); // required
        $messageHead->setLeser($this->createReaderInformation()); // required
        $messageHead->setAutor($this->createAuthorInformation()); // required

        return $messageHead;
    }

    // todo information needs to be provided - check if optional or not
    private function createReaderInformation(): BehoerdeTypeType
    {
        $reader = new BehoerdeTypeType();
        $reader->setBehoerdenkennung($this->addReadingAuthorityIdentificationType());
        // $reader->setErreichbarkeit($this->addReaderCommunicationType());
        // $reader->setAnschrift($this->addReaderPostalInformation());
        $reader->setBehoerdenname('');

        return $reader;
    }

    // todo fill in the correct information - is it demosplan here?
    private function createAuthorInformation(): BehoerdeErreichbarTypeType
    {
        $author = new BehoerdeErreichbarTypeType();
        $author->setBehoerdenkennung($this->addAuthorityIdentificationOfAuthor());
        $author->setErreichbarkeit($this->addAuthorCommunicationType());
        $author->setAnschrift($this->addAuthorPostalInformation());
        $author->setBehoerdenname('');

        return $author;
    }

    // todo information needs to be provided
    private function addReadingAuthorityIdentificationType(): BehoerdenkennungTypeType
    {
        $authorityIdentificationType = new BehoerdenkennungTypeType();

        $prefixType = new CodePraefixTypeType();
        $prefixType->setListVersionID('');
        $prefixType->setListURI('');
        $prefixType->setName('');
        $prefixType->setCode('diplanfhh');
        $authorityIdentificationType->setPraefix($prefixType);

        $codeAuthorityIdentification = new CodeBehoerdenkennungTypeType();
        $codeAuthorityIdentification->setListVersionID('');
        $codeAuthorityIdentification->setListURI('');
        $codeAuthorityIdentification->setName('');
        $codeAuthorityIdentification->setCode('0400');
        $authorityIdentificationType->setKennung($codeAuthorityIdentification);

        return $authorityIdentificationType;
    }

    // todo information needs to be provided
    private function addAuthorityIdentificationOfAuthor(): BehoerdenkennungTypeType
    {
        $authorityIdentificationType = new BehoerdenkennungTypeType();

        $prefixType = new CodePraefixTypeType();
        $prefixType->setListVersionID('');
        $prefixType->setListURI('');
        $prefixType->setName('');
        $prefixType->setCode('diplanfhh');
        $authorityIdentificationType->setPraefix($prefixType);

        $codeAuthorityIdentification = new CodeBehoerdenkennungTypeType();
        $codeAuthorityIdentification->setListVersionID('');
        $codeAuthorityIdentification->setListURI('');
        $codeAuthorityIdentification->setName('');
        $codeAuthorityIdentification->setCode('0200');
        $authorityIdentificationType->setKennung($codeAuthorityIdentification);

        return $authorityIdentificationType;
    }

    // todo information needs to be provided
    /**
     * @return array<int, KommunikationTypeType>
     */
    private function addReaderCommunicationType(): array
    {
        $communicationType = new KommunikationTypeType(); // seemingly optional
        $comCode = new CodeErreichbarkeitTypeType();
        $comCode->setCode('');
        $comCode->setName('');
        $comCode->setListURI('');
        $comCode->setListVersionID('');
        $communicationTypeList = [$communicationType];

        return $communicationTypeList;
    }

    // todo information needs to be provided
    /**
     * @return array<int, KommunikationTypeType>
     */
    private function addAuthorCommunicationType(): array
    {
        $communicationType = new KommunikationTypeType(); // seemingly optional
        $comCode = new CodeErreichbarkeitTypeType();
        $comCode->setCode('');
        $comCode->setName('');
        $comCode->setListURI('');
        $comCode->setListVersionID('');
        $communicationTypeList = [$communicationType];

        return $communicationTypeList;
    }

    // todo information needs to be provided
    private function addReaderPostalInformation(): PostalischeInlandsanschriftTypeType
    {
        $postAddress = new PostalischeInlandsanschriftTypeType(); // seemingly optional

        $buildingAddress = new PostalischeInlandsanschriftGebaeudeanschriftTypeType(); // seemingly optional
        $buildingNumber = new HausnummernBisAnonymousPHPType();
        $buildingNumber->setHausnummerBis('');
        $buildingNumber->setHausnummerbuchstabezusatzzifferBis('');
        $buildingNumber->setTeilnummerderhausnummerBis('');
        $buildingAddress->setHausnummernBis($buildingNumber);
        $buildingAddress->setWohnort('');
        $buildingAddress->setPostleitzahl('');
        $buildingAddress->setHausnummer('');
        $buildingAddress->setHausnummerBuchstabeZusatzziffer('');
        $buildingAddress->setStockwerkswohnungsnummer('');
        $buildingAddress->setStrasse('');
        $postAddress->setGebaeude($buildingAddress);

        $postMailBoxAddress = new PostalischeInlandsanschriftPostfachanschriftTypeType(); // seemingly optional
        $postMailBoxAddress->setPostfach('')
            ->setPostleitzahl('')
            ->setWohnort('')
        ;
        $postAddress->setPostfach($postMailBoxAddress);

        return $postAddress;
    }

    // todo information needs to be provided
    private function addAuthorPostalInformation(): PostalischeInlandsanschriftTypeType
    {
        $postAddress = new PostalischeInlandsanschriftTypeType(); // seemingly optional

        $buildingAddress = new PostalischeInlandsanschriftGebaeudeanschriftTypeType(); // seemingly optional
        $buildingNumber = new HausnummernBisAnonymousPHPType();
        $buildingNumber->setHausnummerBis('');
        $buildingNumber->setHausnummerbuchstabezusatzzifferBis('');
        $buildingNumber->setTeilnummerderhausnummerBis('');
        $buildingAddress->setHausnummernBis($buildingNumber);
        $buildingAddress->setWohnort('');
        $buildingAddress->setPostleitzahl('');
        $buildingAddress->setHausnummer('');
        $buildingAddress->setHausnummerBuchstabeZusatzziffer('');
        $buildingAddress->setStockwerkswohnungsnummer('');
        $buildingAddress->setStrasse('');
        $postAddress->setGebaeude($buildingAddress);

        $postMailBoxAddress = new PostalischeInlandsanschriftPostfachanschriftTypeType(); // seemingly optional
        $postMailBoxAddress->setPostfach('')
            ->setPostleitzahl('')
            ->setWohnort('')
        ;
        $postAddress->setPostfach($postMailBoxAddress);

        return $postAddress;
    }

    /**
     * @throws Exception
     */
    private function createMessageIdentification(NachrichtG2GTypeType $messageObject): IdentifikationNachrichtTypeType
    {
        if ($messageObject instanceof Planung2BeteiligungBeteiligungKommuneNeu0401) {
            $code = '0401';
            $name = 'planung2Beteiligung.BeteiligungNeu.0401';
        } elseif ($messageObject instanceof Planung2BeteiligungBeteiligungKommuneAktualisieren0402) {
            $code = '0402';
            $name = 'planung2Beteiligung.BeteiligungAktualisieren.0402';
        } elseif ($messageObject instanceof  Planung2BeteiligungBeteiligungKommuneLoeschen0409) {
            $code = '0409';
            $name = 'planung2Beteiligung.BeteiligungLoeschen.0409';
        } else {
            $this->logger->error('Class '.$messageObject::class.' not supported yet');
            throw new Exception(
                $messageObject::class . ' is not supported - unable to set messageIdentification code'
            );
        }

        $identificationMessage = new IdentifikationNachrichtTypeType();

        $messageTypeCode = new CodeType();
        $messageTypeCode->setListURI('urn:de:xbeteiligung:codeliste:xbeteiligungnachrichtencodeliste');
        $messageTypeCode->setListVersionID('1.0');
        $messageTypeCode->setName($name);
        $messageTypeCode->setCode($code);

        // id has to match pattern: '[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}'
        $identificationMessage->setNachrichtenUUID($this->uuid()); // required
        $identificationMessage->setErstellungszeitpunkt(new DateTime()); // required
        $identificationMessage->setNachrichtentyp($messageTypeCode); // required

        return $identificationMessage;
    }

    private function uuid(): string
    {
        $uuid = '';
        $tryAgain = true;
        while ($tryAgain) {
            $uuid = Uuid::uuid4()->toString();
            if (0 !== preg_match('/[A-Za-z]/', $uuid[0])) {
                $tryAgain = false;
            }
        }

        return $uuid;
    }

    /**
     * Validates a message against a given xsd file located in plugin xsd folder.
     */
    public function isValidMessage(string $message, bool $verboseDebug = false, string $xsdFile = 'xbeteiligung-beteiligung2planung.xsd'): bool
    {
        $path = AddonPath::getRootPath('Resources/xsd/' . $xsdFile);
        $document = new \DOMDocument();
        $document->loadXML($message);
        $isValid = $document->schemaValidate($path);
        if ($isValid) {
            return true;
        }
        // revalidate with error handling
        libxml_use_internal_errors(true);
        $document->schemaValidate($path);
        $errors = libxml_get_errors();
        foreach ($errors as $error) {
            $this->logger->warning('Invalid xta message', [$error]);
            if ($verboseDebug) {
                print_r($error);
            }
        }
        libxml_clear_errors();
        libxml_use_internal_errors(false);
        if ($verboseDebug) {
            print_r($message);
        }

        return false;
    }

    /**
     * @return \JMS\Serializer\Serializer
     */
    public function getSerializer()
    {
        return $this->serializer;
    }

    /**
     * @param \JMS\Serializer\Serializer $serializer
     */
    public function setSerializer($serializer)
    {
        $this->serializer = $serializer;
    }

}
