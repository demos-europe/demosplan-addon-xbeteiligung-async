<?php

declare(strict_types=1);

/**
 * This file is part of the package demosplan.
 *
 * (c) 2010-present DEMOS plan GmbH, for more information see the license file.
 *
 * All rights reserved
 */

namespace DemosEurope\DemosplanAddon\XBeteiligung\Logic\MessageFactory;

use DateTime;
use DemosEurope\DemosplanAddon\XBeteiligung\Exeption\UnsupportedMessageTypeException;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\CommonHelpers;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\XBeteiligungService;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Basisnachricht\Behoerde\CodeVerzeichnisdienstTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Basisnachricht\G2g\Autor;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Basisnachricht\G2g\IdentifikationNachricht;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Basisnachricht\G2g\Leser;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Basisnachricht\G2g\NachrichtenkopfG2g;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Basisnachricht\G2g\NachrichtG2GTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Basisnachricht\Kommunikation\CodeKommunikationKanalTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Basisnachricht\Unqualified\BehoerdeTypeType as UnqualifiedBehoerdeTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Basisnachricht\Unqualified\CodeKommunikationKanalTypeType as UnqualifiedCodeKommunikationKanalTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Basisnachricht\Unqualified\CodeVerzeichnisdienstTypeType as UnqualifiedCodeVerzeichnisdienstTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Basisnachricht\Unqualified\IdentifikationNachrichtTypeType as UnqualifiedIdentifikationNachrichtTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Basisnachricht\Unqualified\KommunikationTypeType as UnqualifiedKommunikationTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Basisnachricht\Unqualified\NachrichtenkopfG2GTypeType as UnqualifiedNachrichtenkopfG2GTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Basisnachricht\Unqualified\NachrichtG2GTypeType as UnqualifiedNachrichtG2GTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Basisnachricht\Kommunikation\Erreichbarkeit;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\CodeXBeteiligungNachrichtenType;
use Exception;

class ReusableMessageBlocks
{
    public function __construct(private readonly CommonHelpers $commonHelpers)
    {
    }

    /**
     * @throws UnsupportedMessageTypeException
     */
    public function setProductInfo(NachrichtG2GTypeType|UnqualifiedNachrichtG2GTypeType $messageObject): NachrichtG2GTypeType|UnqualifiedNachrichtG2GTypeType
    {
        $messageObject->setProdukt('demosplan'); // required
        $messageObject->setProdukthersteller('DEMOS plan GmbH'); // required
        $messageObject->setProduktversion('1.1'); // optional
        $messageObject->setStandard(XBeteiligungService::STANDARD); // required
        $messageObject->setVersion('1.3'); // required

        return $messageObject;
    }

    /**
     * @throws Exception
     */
    public function createMessageHeadFor(NachrichtG2GTypeType|UnqualifiedNachrichtG2GTypeType $messageObject): NachrichtenkopfG2g|UnqualifiedNachrichtenkopfG2GTypeType
    {
        if ($messageObject instanceof UnqualifiedNachrichtG2GTypeType) {
            return $this->createUnqualifiedMessageHeadFor($messageObject);
        }

        $messageHead = new NachrichtenkopfG2g();
        $messageHead->setIdentifikationNachricht($this->createMessageIdentification($messageObject)); // required
        $messageHead->setLeser($this->createReaderInformation($messageObject)); // required
        $messageHead->setAutor($this->createAuthorInformation($messageObject)); // required

        return $messageHead;
    }

    /**
     * @throws Exception
     */
    private function createUnqualifiedMessageHeadFor(UnqualifiedNachrichtG2GTypeType $messageObject): UnqualifiedNachrichtenkopfG2GTypeType
    {
        $messageHead = new UnqualifiedNachrichtenkopfG2GTypeType();
        $messageHead->setIdentifikationNachricht($this->createUnqualifiedMessageIdentification($messageObject)); // required
        $messageHead->setLeser($this->createUnqualifiedReaderInformation($messageObject)); // required
        $messageHead->setAutor($this->createUnqualifiedAuthorInformation($messageObject)); // required

        return $messageHead;
    }

    /**
     * @throws UnsupportedMessageTypeException
     */
    private function createMessageIdentification(NachrichtG2GTypeType|UnqualifiedNachrichtG2GTypeType $messageObject): IdentifikationNachricht
    {
        $headerInformationForMessage = $this->commonHelpers->mapClassToMessageIndentifier($messageObject);

        $identificationMessage = new IdentifikationNachricht();

        $messageTypeCode = new CodeXBeteiligungNachrichtenType();
        $messageTypeCode->setListURI('urn:xoev-de:xleitstelle:codeliste:xbeteiligung-nachrichten');
        $messageTypeCode->setListVersionID('1.3');
        $messageTypeCode->setName($headerInformationForMessage['name']);
        $messageTypeCode->setCode($headerInformationForMessage['code']);

        // id has to match pattern: '[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}'
        $identificationMessage->setNachrichtenUUID($this->commonHelpers->uuid()); // required
        $identificationMessage->setErstellungszeitpunkt(new DateTime()); // required
        $identificationMessage->setNachrichtentyp($messageTypeCode); // required

        return $identificationMessage;

    }

    /**
     * @throws UnsupportedMessageTypeException
     */
    public function createReaderInformation(NachrichtG2GTypeType|UnqualifiedNachrichtG2GTypeType $messageObject): Leser
    {
        $headerInformationForMessage = $this->commonHelpers->mapClassToMessageIndentifier($messageObject);

        $reader = new Leser();
        $reader->setKennung('xyz:0002'); // required
        $reader->setName($headerInformationForMessage['recipient']); // required
        $reader->setVerzeichnisdienst($this->createVerzeichnisdienst()); // required

        $erreichbarkeit = new Erreichbarkeit();
        $kanal = new CodeKommunikationKanalTypeType();
        $kanal->setListVersionID('3');
        $kanal->setListURI(XBeteiligungService::CODELIST_ERREICHBARKEIT);
        $kanal->setCode('07');
        $erreichbarkeit->setKanal($kanal);
        $erreichbarkeit->setKennung(''); // required
        $reader->setErreichbarkeit([$erreichbarkeit]); // required

        return $reader;
    }

    public function createAuthorInformation(NachrichtG2GTypeType|UnqualifiedNachrichtG2GTypeType $messageObject): Autor
    {
        $headerInformationForMessage = $this->commonHelpers->mapClassToMessageIndentifier($messageObject);

        $author = new Autor();
        $author->setKennung('xyz:0001');
        $author->setName($headerInformationForMessage['author']); // required
        $author->setVerzeichnisdienst($this->createVerzeichnisdienst()); // required

        $erreichbarkeit = new Erreichbarkeit();
        $kanal = new CodeKommunikationKanalTypeType();
        $kanal->setListVersionID('3');
        $kanal->setListURI(XBeteiligungService::CODELIST_ERREICHBARKEIT);
        // Quelle - AdoRepo: Erreichbarkeit-3.xml
        // (https://www.xrepository.de/details/urn:de:xoev:codeliste:erreichbarkeit_3#version)
        // 01 -> E-Mail, 02 -> Telefon Festnetz, 03 -> Telefon mobil, 04 -> Fax, 05 -> Instant Messenger,
        // 06 -> Pager, 07 -> Sonstiges, 08 -> DE-Mail, 09 -> Web
        $kanal->setCode('09');
        //$kanal->setName('Web'); // not expected in validation
        $erreichbarkeit->setKanal($kanal); // required
        $erreichbarkeit->setKennung('https://demos-deutschland.de/impressum.html'); // required
        $erreichbarkeit->setZusatz(''); // optional
        $author->setErreichbarkeit([$erreichbarkeit]); // required

        return $author;
    }

    private function createVerzeichnisdienst(): CodeVerzeichnisdienstTypeType
    {
        $verzeichnisdienst = new CodeVerzeichnisdienstTypeType();
        $verzeichnisdienst->setListVersionID('3');
        $verzeichnisdienst->setListURI('urn:xoev-de:kosit:codeliste:verzeichnisdienst');
        $verzeichnisdienst->setCode('DVDV');

        return $verzeichnisdienst;
    }

    private function createUnqualifiedVerzeichnisdienst(): UnqualifiedCodeVerzeichnisdienstTypeType
    {
        $verzeichnisdienst = new UnqualifiedCodeVerzeichnisdienstTypeType();
        $verzeichnisdienst->setListVersionID('3');
        $verzeichnisdienst->setListURI('urn:xoev-de:kosit:codeliste:verzeichnisdienst');
        $verzeichnisdienst->setCode('DVDV');

        return $verzeichnisdienst;
    }

    /**
     * @throws UnsupportedMessageTypeException
     */
    private function createUnqualifiedMessageIdentification(UnqualifiedNachrichtG2GTypeType $messageObject): UnqualifiedIdentifikationNachrichtTypeType
    {
        $headerInformationForMessage = $this->commonHelpers->mapClassToMessageIndentifier($messageObject);

        $identificationMessage = new UnqualifiedIdentifikationNachrichtTypeType();

        $messageTypeCode = new CodeXBeteiligungNachrichtenType();
        $messageTypeCode->setListURI('urn:xoev-de:xleitstelle:codeliste:xbeteiligung-nachrichten');
        $messageTypeCode->setListVersionID('1.3');
        $messageTypeCode->setName($headerInformationForMessage['name']);
        $messageTypeCode->setCode($headerInformationForMessage['code']);

        $identificationMessage->setNachrichtenUUID($this->commonHelpers->uuid()); // required
        $identificationMessage->setErstellungszeitpunkt(new DateTime()); // required
        $identificationMessage->setNachrichtentyp($messageTypeCode); // required

        return $identificationMessage;
    }

    /**
     * @throws UnsupportedMessageTypeException
     */
    private function createUnqualifiedReaderInformation(UnqualifiedNachrichtG2GTypeType $messageObject): UnqualifiedBehoerdeTypeType
    {
        $headerInformationForMessage = $this->commonHelpers->mapClassToMessageIndentifier($messageObject);

        $reader = new UnqualifiedBehoerdeTypeType();
        $reader->setKennung('xyz:0002'); // required
        $reader->setName($headerInformationForMessage['recipient']); // required
        $reader->setVerzeichnisdienst($this->createUnqualifiedVerzeichnisdienst()); // required

        $erreichbarkeit = new UnqualifiedKommunikationTypeType();
        $kanal = new UnqualifiedCodeKommunikationKanalTypeType();
        $kanal->setListVersionID('3');
        $kanal->setListURI(XBeteiligungService::CODELIST_ERREICHBARKEIT);
        $kanal->setCode('07');
        $erreichbarkeit->setKanal($kanal);
        $erreichbarkeit->setKennung(''); // required
        $reader->setErreichbarkeit([$erreichbarkeit]); // required

        return $reader;
    }

    /**
     * @throws UnsupportedMessageTypeException
     */
    private function createUnqualifiedAuthorInformation(UnqualifiedNachrichtG2GTypeType $messageObject): UnqualifiedBehoerdeTypeType
    {
        $headerInformationForMessage = $this->commonHelpers->mapClassToMessageIndentifier($messageObject);

        $author = new UnqualifiedBehoerdeTypeType();
        $author->setKennung('xyz:0001');
        $author->setName($headerInformationForMessage['author']); // required
        $author->setVerzeichnisdienst($this->createUnqualifiedVerzeichnisdienst()); // required

        $erreichbarkeit = new UnqualifiedKommunikationTypeType();
        $kanal = new UnqualifiedCodeKommunikationKanalTypeType();
        $kanal->setListVersionID('3');
        $kanal->setListURI(XBeteiligungService::CODELIST_ERREICHBARKEIT);
        $kanal->setCode('09');
        $erreichbarkeit->setKanal($kanal); // required
        $erreichbarkeit->setKennung('https://demos-deutschland.de/impressum.html'); // required
        $erreichbarkeit->setZusatz(''); // optional
        $author->setErreichbarkeit([$erreichbarkeit]); // required

        return $author;
    }
}
