<?php

declare(strict_types=1);

/**
 * This file is part of the package demosplan.
 *
 * (c) 2010-present DEMOS E-Partizipation GmbH, for more information see the license file.
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
    public function setProductInfo(NachrichtG2GTypeType $messageObject): NachrichtG2GTypeType
    {
        $author = $this->commonHelpers->mapClassToMessageIndentifier($messageObject)['author'];
        $messageObject->setProdukt($author); // required
        $messageObject->setProdukthersteller('DEMOS plan GmbH'); // required
        $messageObject->setProduktversion('1.1'); // optional
        $messageObject->setStandard(XBeteiligungService::STANDARD); // required
        $messageObject->setVersion('1.3'); // required

        return $messageObject;
    }

    /**
     * @throws Exception
     */
    public function createMessageHeadFor(NachrichtG2GTypeType $messageObject): NachrichtenkopfG2g
    {
        $messageHead = new NachrichtenkopfG2g();
        $messageHead->setIdentifikationNachricht($this->createMessageIdentification($messageObject)); // required
        $messageHead->setLeser($this->createReaderInformation($messageObject)); // required
        $messageHead->setAutor($this->createAuthorInformation($messageObject)); // required

        return $messageHead;
    }

    /**
     * @throws UnsupportedMessageTypeException
     */
    private function createMessageIdentification(NachrichtG2GTypeType $messageObject): IdentifikationNachricht
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

    public function createReaderInformation(NachrichtG2GTypeType $messageObject): Leser
    {
        $headerInformationForMessage = $this->commonHelpers->mapClassToMessageIndentifier($messageObject);

        $reader = new Leser();
        $reader->setKennung(''); // required
        $reader->setName($headerInformationForMessage['recipient']); // required
        $verzeichnisdienst = new CodeVerzeichnisdienstTypeType();
        $verzeichnisdienst->setListVersionID('');
        $verzeichnisdienst->setListURI('urn:xoev-de:kosit:codeliste:verzeichnisdienst');
        $verzeichnisdienst->setCode(XBeteiligungService::NON_EXISTING_CODE);
        $reader->setVerzeichnisdienst($verzeichnisdienst); // required


        $codeAuthorityIdentification = new Erreichbarkeit();
        $kanal = new CodeKommunikationKanalTypeType();
        $kanal->setListVersionID('');
        $kanal->setListURI(XBeteiligungService::CODELIST_ERREICHBARKEIT);
        $kanal->setCode(XBeteiligungService::NON_EXISTING_CODE);
        $codeAuthorityIdentification->setKanal($kanal);
        $codeAuthorityIdentification->setKennung(''); // required
        $reader->setErreichbarkeit([$codeAuthorityIdentification]); // required

        return $reader;
    }

    public function createAuthorInformation(NachrichtG2GTypeType $messageObject): Autor
    {
        $headerInformationForMessage = $this->commonHelpers->mapClassToMessageIndentifier($messageObject);

        $author = new Autor();
        $author->setKennung('');
        $author->setName($headerInformationForMessage['author']); // required
        $prefixType = new CodeVerzeichnisdienstTypeType();
        $prefixType->setListVersionID('');
        $prefixType->setListURI('urn:xoev-de:kosit:codeliste:verzeichnisdienst');
        $prefixType->setCode(XBeteiligungService::NON_EXISTING_CODE);
        $author->setVerzeichnisdienst($prefixType); // required

        $codeAuthorityIdentification = new Erreichbarkeit();
        $kanal = new CodeKommunikationKanalTypeType();
        $kanal->setListVersionID('');
        $kanal->setListURI(XBeteiligungService::CODELIST_ERREICHBARKEIT);
        $kanal->setCode(XBeteiligungService::NON_EXISTING_CODE);
        $codeAuthorityIdentification->setKanal($kanal);
        $codeAuthorityIdentification->setKennung(''); // required
        $author->setErreichbarkeit([$codeAuthorityIdentification]); // required
        $author->addToErreichbarkeit($this->addAuthorCommunicationType()); // required list 1 entry


        return $author;
    }

    private function addAuthorCommunicationType(): Erreichbarkeit
    {
        $communicationType = new Erreichbarkeit();
        $comCode = new CodeKommunikationKanalTypeType();
        // Quelle - AdoRepo: Erreichbarkeit-3.xml
        // 01 -> E-Mail, 02 -> Telefon Festnetz, 03 -> Telefon mobil, 04 -> Fax, 05 -> Instant Messenger,
        // 06 -> Pager, 07 -> Sonstiges, (08 -> DE-Mail, 09 -> Web - these don't exist in validation)
        $comCode->setCode('07');
        //$comCode->setName('Sonstiges'); // not expected in validation
        $comCode->setListURI(XBeteiligungService::CODELIST_ERREICHBARKEIT);
        $comCode->setListVersionID('1');
        $communicationType->setKanal($comCode); // required
        $communicationType->setKennung('https://demosplan.com/impressum.html'); // required
        $communicationType->setZusatz(''); // optional

        return $communicationType;
    }

}
