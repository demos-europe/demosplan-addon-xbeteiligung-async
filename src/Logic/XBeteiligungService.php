<?php

/**
 * This file is part of the package demosplan.
 *
 * (c) 2010-present DEMOS E-Partizipation GmbH, for more information see the license file.
 *
 * All rights reserved
 */

namespace DemosEurope\DemosplanAddon\XBeteiligung\Logic;

use DateInterval;
use DateTime;
use DemosEurope\DemosplanAddon\Contracts\Entities\ProcedureInterface;
use DemosEurope\DemosplanAddon\Utilities\AddonPath;
use DemosEurope\DemosplanAddon\Contracts\Config\GlobalConfigInterface;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\AkteurVorhabenTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\AnschriftTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BehoerdeErreichbarTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BehoerdenkennungTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BehoerdeTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BeteiligungKommuneTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BeteiligungTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeBehoerdenkennungTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeErreichbarkeitTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodePlanartKommuneTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodePraefixTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeVerfahrensartTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeVerfahrensschrittTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\IdentifikationNachrichtTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\KommunikationTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\MetadatenAnlageTypeType;
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
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\VerfahrenTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\ZeitraumTypeType;
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

    private function generateMain401MessageContent(ProcedureInterface $procedure): NachrichteninhaltAnonymousPHPType
    {
        $messageContent = new NachrichteninhaltAnonymousPHPType();
        $messageContent->setVorgangsID($this->uuid());  // required
        $messageContent->setBeteiligung($this->generateParticipationContent($procedure)); // optional

        return $messageContent;
    }

    private function generateParticipationContent(ProcedureInterface $procedure): BeteiligungKommuneTypeType
    {
        $participationType = new BeteiligungKommuneTypeType();

        $procedureInitiatingOrganisation = new AkteurVorhabenTypeType();
        // kteurVorhaben
        $organisationType = new OrganisationTypeType();
        $organisationName = new NameOrganisationTypeType();
        $organisationName->setName($procedure->getOrga()?->getName() ?? '');
        $organisationType->setName($organisationName);
        $procedureInitiatingOrganisation->setVeranlasser($organisationType);
        $participationType->setAkteurVorhaben($procedureInitiatingOrganisation); // required

        $participationType->setPlanname($procedure->getName()); // required
        // planart
        $planType = new CodePlanartKommuneTypeType();
        $planType->setCode('1000')
            ->setName('Einfacher Bebauungsplan')
            ->setListVersionID('1.0')
            ->setListURI('urn:xoev-de:xleitstelle:codeliste:planart');
        $participationType->setPlanart($planType); // optional - we want to use it

        // Hier ist die ID des Planverfahrens zu übermitteln, innerhalb dessen das Beteiligungsverfahren durchgeführt wird
        $participationType->setPlanID($procedure->getXtaPlanId() ?? $procedure->getId()); // required
        $participationType->setBeschreibungPlanungsanlass($procedure->getDesc()); // optional - we want to use it
        $participationType->setFlaechenabgrenzungUrl(''); // optional - we want to use it

        // Hier ist die räumliche Beschreibung des Geltungsbereichs als Polygon im Format GeoJSON FG Notation zu über-
        // mitteln. todo Format wird noch geprüft.
        $participationType->setGeltungsbereich(''); // required - we dont want to use it
        $participationType->setRaeumlicheBeschreibung(''); // required - we dont want it
        // zeitraum
        $timeSpan = new ZeitraumTypeType();
        $timeSpan->setBeginn($procedure->getStartDate());
        $timeSpan->setEnde($procedure->getEndDate());
        $participationType->setZeitraum($timeSpan); // optional - we want to use it

        // Termin, zu dem der Start der Beteiligung bekannt gemacht wird (mind. eine Woche vor Start der Beteiligung).
        $participationType->setBekanntmachung(
            $procedure->getStartDate()->sub(new DateInterval('P7D'))
        ); // required - we dont want it
        // verfahren? wird hier an dieser Stelle in Excel-sheet gelistet
        // todo email an Stefan Conrad ist raus - der MetadatenAnlageTypeType ist broken zur Zeit
        $participationType->setAnlagen([new MetadatenAnlageTypeType()]); // optional - we want to use it

        // todo Code liste urn:xoev-de:xleitstelle:codeliste:verfahrensschritt existiert nicht
        $participationType->setVerfahrensschritt(new CodeVerfahrensschrittTypeType()); // required - we want to use it
        // $participationType->setVerfahrensart(new CodeVerfahrensartTypeType()); // optional
        // todo die sind scheinbar nicht an der Procedure entity
        $participationType->setAktuelleMitteilung(['', '']); // optional - we want to use it
        // $participationType->setArbeitstitel(''); // optional
        // $participationType->setPlanart(new CodePlanartKommuneTypeType()); // otional
        $participationType->setDurchgang(1); // required not documented not wanted

        return $participationType;
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
        $reader->setBehoerdenkennung($this->addReadingAuthorityIdentificationType()); // required
//        $reader->setErreichbarkeit($this->addReaderCommunicationType()); // optional list
//        $reader->setAnschrift($this->addReaderPostalInformation()); // optional
        $reader->setBehoerdenname(''); // required

        return $reader;
    }

    // todo fill in the correct information - is it demosplan here?
    private function createAuthorInformation(): BehoerdeErreichbarTypeType
    {
        $author = new BehoerdeErreichbarTypeType();
        $author->setBehoerdenkennung($this->addAuthorityIdentificationOfAuthor()); // required
        $author->setErreichbarkeit($this->addAuthorCommunicationType()); // required list 1 entry
        $author->setAnschrift($this->addAuthorPostalInformation()); // required
        $author->setBehoerdenname(''); // required

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
        $authorityIdentificationType->setPraefix($prefixType); // required

        $codeAuthorityIdentification = new CodeBehoerdenkennungTypeType();
        $codeAuthorityIdentification->setListVersionID('');
        $codeAuthorityIdentification->setListURI('');
        $codeAuthorityIdentification->setName('');
        $codeAuthorityIdentification->setCode('0400');
        $authorityIdentificationType->setKennung($codeAuthorityIdentification); // required

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
        $authorityIdentificationType->setPraefix($prefixType); // required

        $codeAuthorityIdentification = new CodeBehoerdenkennungTypeType();
        $codeAuthorityIdentification->setListVersionID('');
        $codeAuthorityIdentification->setListURI('');
        $codeAuthorityIdentification->setName('');
        $codeAuthorityIdentification->setCode('0200');
        $authorityIdentificationType->setKennung($codeAuthorityIdentification); // required

        return $authorityIdentificationType;
    }

    /**
     * @return array<int, KommunikationTypeType>
     */
    private function addReaderCommunicationType(): array
    {
        $communicationType = new KommunikationTypeType();
        $comCode = new CodeErreichbarkeitTypeType();
        // 01 -> E-Mail, 02 -> Telefon Festnetz, 03 -> Telefon mobil, 04 -> Fax, 05 -> Instant Messenger, 06 -> Pager, 07 -> Sonstiges
        $comCode->setCode('');
        $comCode->setName('');
        $comCode->setListURI('');
        $comCode->setListVersionID('');
        $communicationType->setKanal($comCode); // required
        // kennung: In der Regel werden hier Adressangaben eingetragen, etwa die Telefonnummer oder die E-Mail-Adresse.
        $communicationType->setKennung(''); // required
        $communicationType->setZusatz(''); // optional

        return [$communicationType];
    }

    // todo information needs to be provided
    /**
     * @return array<int, KommunikationTypeType>
     */
    private function addAuthorCommunicationType(): array
    {
        $communicationType = new KommunikationTypeType();
        $comCode = new CodeErreichbarkeitTypeType();
        // 01 -> E-Mail, 02 -> Telefon Festnetz, 03 -> Telefon mobil, 04 -> Fax, 05 -> Instant Messenger, 06 -> Pager, 07 -> Sonstiges
        $comCode->setCode('');
        $comCode->setName('');
        $comCode->setListURI('');
        $comCode->setListVersionID('');
        $communicationType->setKanal($comCode); // required
        // kennung: In der Regel werden hier Adressangaben eingetragen, etwa die Telefonnummer oder die E-Mail-Adresse.
        $communicationType->setKennung(''); // required
        $communicationType->setZusatz(''); // optional

        return [$communicationType];
    }

    private function addReaderPostalInformation(): PostalischeInlandsanschriftTypeType
    {
        $postAddress = new PostalischeInlandsanschriftTypeType();

        $buildingAddress = new PostalischeInlandsanschriftGebaeudeanschriftTypeType();
        $buildingNumber = new HausnummernBisAnonymousPHPType();
        $buildingNumber->setHausnummerBis('');
        $buildingNumber->setHausnummerbuchstabezusatzzifferBis('');
        $buildingNumber->setTeilnummerderhausnummerBis('');
        $buildingAddress->setHausnummernBis($buildingNumber); // optional
        $buildingAddress->setWohnort(''); // required
        $buildingAddress->setPostleitzahl(''); // required
        $buildingAddress->setHausnummer(''); // optional
        $buildingAddress->setHausnummerBuchstabeZusatzziffer(''); // optional
        $buildingAddress->setStockwerkswohnungsnummer(''); // optional
        $buildingAddress->setStrasse(''); // required
        $postAddress->setGebaeude($buildingAddress); // required

        $postMailBoxAddress = new PostalischeInlandsanschriftPostfachanschriftTypeType();
        $postMailBoxAddress->setPostfach('') // optional
            ->setPostleitzahl('') // required
            ->setWohnort('') // required
        ;
        $postAddress->setPostfach($postMailBoxAddress);

        return $postAddress;
    }

    // todo information needs to be provided
    private function addAuthorPostalInformation(): PostalischeInlandsanschriftTypeType
    {
        $postAddress = new PostalischeInlandsanschriftTypeType();

        $buildingAddress = new PostalischeInlandsanschriftGebaeudeanschriftTypeType();
        $buildingNumber = new HausnummernBisAnonymousPHPType();
        $buildingNumber->setHausnummerBis('');
        $buildingNumber->setHausnummerbuchstabezusatzzifferBis('');
        $buildingNumber->setTeilnummerderhausnummerBis('');
        $buildingAddress->setHausnummernBis($buildingNumber); // optional
        $buildingAddress->setWohnort(''); // required
        $buildingAddress->setPostleitzahl(''); // required
        $buildingAddress->setHausnummer(''); // optional
        $buildingAddress->setHausnummerBuchstabeZusatzziffer(''); // optional
        $buildingAddress->setStockwerkswohnungsnummer(''); // oprional
        $buildingAddress->setStrasse(''); // required
        $postAddress->setGebaeude($buildingAddress); // required

        $postMailBoxAddress = new PostalischeInlandsanschriftPostfachanschriftTypeType();
        $postMailBoxAddress->setPostfach('') // optional
            ->setPostleitzahl('') // required
            ->setWohnort('') // required
        ;
        $postAddress->setPostfach($postMailBoxAddress); // required

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
