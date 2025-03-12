<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing CodeAnlageArtType
 *
 * Dieser Code-Datentyp dient der Einbindung einer Codeliste, die Arten von Bauvorlagen oder sonstigen Anlagen unterscheidet, die einer XBau-Nachricht als Anlage beigefügt sein können. In diesen Typ ist eine auszuwählende bzw. selbst zu definierende Codeliste einzubinden, die eine solche Klassifikation bietet. Im Anwendungskontext sind in die Attribute des vorliegenden Typs die Codelisten-URI und die Nummer der Version der ausgewählten Codeliste (in die XBau-Nachrichteninstanzen) einzutragen. Codelisten, die im Rahmen des Betriebs XBau definiert und im XRepository (www.xrepository.de) bereitgestellt werden, und ggf. für den vorliegenden Zweck geeignet sein können: urn:xoev-de:xbau:codeliste:anlagen
 * XSD Type: Code.AnlageArt
 */
class CodeAnlageArtType extends CodeType
{
}

