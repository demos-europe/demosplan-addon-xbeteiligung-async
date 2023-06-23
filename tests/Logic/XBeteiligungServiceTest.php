<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Tests\Logic;

use DateInterval;
use DateTime;
use DemosEurope\DemosplanAddon\Contracts\Config\GlobalConfigInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\OrgaInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\ProcedureInterface;
use DemosEurope\DemosplanAddon\Contracts\UserHandlerInterface;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\SerializerFactory;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\XBeteiligungService;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class XBeteiligungServiceTest extends TestCase
{

     protected function setUp(): void
    {
        parent::setUp();

        $serializer = new SerializerFactory();
        $this->sut = new XBeteiligungService(
            $this->createMock(GlobalConfigInterface::class),
            $this->createMock(LoggerInterface::class),
            $this->createMock(RouterInterface::class),
            $serializer,
            $this->createMock(TranslatorInterface::class),
            $this->createMock(UserHandlerInterface::class)
        );
    }

    public function testPlanung2BeteiligungBeteiligungNeu0401()
    {
        $xml = '<ns6:planung2Beteiligung.BeteiligungNeu.0401 xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xmlns:ns2="http://xbeteiligung.de/addendum" xmlns:ns3="http://www.osci.de/xinneres/rueckweisung/3" xmlns:ns4="http://www.osci.de/xinneres/weiterleitung/4" xmlns:ns5="http://www.osci.de/xinneres/quittung/1" xmlns:ns6="http://xbeteiligung.de/V0103" xmlns:ns7="http://www.opengis.net/gml/3.2" xmlns:ns8="http://www.w3.org/1999/xlink" xmlns:ns9="http://www.xleitstelle.de/xbau/2/2" xmlns:ns10="http://docs.oasis-open.org/codelist/ns/genericode/1.0/" produkt="K1" produkthersteller="]init[ AG" standard="XBeteiligung" version="1.2.0">
    <nachrichtenkopf xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="ns6:Nachrichtenkopf.G2G">
        <identifikation.nachricht xsi:type="ns6:Identifikation.Nachricht">
            <nachrichtenUUID>1663ad58-cd71-42af-9b02-fd20c287a5b4</nachrichtenUUID>
            <nachrichtentyp xsi:type="ns6:Code.XBeteiligungsNachrichten" listURI="urn:de:xbeteiligung:codeliste:xbeteiligungnachrichtencodeliste" listVersionID="1.0">
                <code>0401</code>
                <name>planung2Beteiligung.BeteiligungNeu.0401 (XTA)</name>
            </nachrichtentyp>
            <erstellungszeitpunkt>2021-10-20T13:47:49.684+02:00</erstellungszeitpunkt>
        </identifikation.nachricht>
        <leser>
            <behoerdenkennung>
                <praefix listVersionID="">
                    <code>diplanfhh</code>
                </praefix>
                <kennung listURI="" listVersionID="">
                    <code>0400</code>
                </kennung>
            </behoerdenkennung>
            <behoerdenname></behoerdenname>
        </leser>
        <autor>
            <behoerdenkennung>
                <praefix listVersionID="">
                    <code>diplanfhh</code>
                </praefix>
                <kennung listURI="" listVersionID="">
                    <code>0200</code>
                </kennung>
            </behoerdenkennung>
            <erreichbarkeit>
                <kanal>
                    <code>02</code>
                </kanal>
                <kennung>0049 40 42 82 80</kennung>
            </erreichbarkeit>
            <erreichbarkeit>
                <kanal>
                    <code>01</code>
                </kanal>
                <kennung>info@gv.hamburg.de</kennung>
            </erreichbarkeit>
            <anschrift>
                <gebaeude>
                    <hausnummer>19</hausnummer>
                    <hausnummerBuchstabeZusatzziffer>b</hausnummerBuchstabeZusatzziffer>
                    <postleitzahl>21109</postleitzahl>
                    <stockwerkswohnungsnummer>3</stockwerkswohnungsnummer>
                    <strasse>Neuenfelder Straße</strasse>
                    <teilnummerDerHausnummer>4</teilnummerDerHausnummer>
                    <wohnort>Freie und Hansestadt Hamburg</wohnort>
                    <wohnortFruehererGemeindename></wohnortFruehererGemeindename>
                    <wohnungsinhaber></wohnungsinhaber>
                    <zusatzangaben>Hinterhaus</zusatzangaben>
                    <hausnummern.bis>
                        <hausnummer.bis>22</hausnummer.bis>
                        <hausnummerbuchstabezusatzziffer.bis>c</hausnummerbuchstabezusatzziffer.bis>
                        <teilnummerderhausnummer.bis>3</teilnummerderhausnummer.bis>
                    </hausnummern.bis>
                </gebaeude>
            </anschrift>
            <behoerdenname></behoerdenname>
        </autor>
    </nachrichtenkopf>
    <ns6:nachrichteninhalt>
        <ns6:vorgangsID>ID_e7a61459-9318-4233-8caa-5480b26df815</ns6:vorgangsID>
        <ns6:beteiligung>
            <ns6:planname>Bop01</ns6:planname>
            <ns6:planID>ID_7606f622-439b-4929-8625-0856c161409e</ns6:planID>
            <ns6:beteiligungszeitraum datumsstatus="geplant">
                <ns6:beginn>2021-10-04+02:00</ns6:beginn>
                <ns6:ende>2021-10-08+02:00</ns6:ende>
            </ns6:beteiligungszeitraum>
            <ns6:verfahrensunterlage>
                <ns6:fileID>ID_0732bbc5-dc5e-4aa9-9a59-53d2a95724d6</ns6:fileID>
                <ns6:unterlageTyp listURI="urn:de:xbeteiligung:codeliste:verfahrensunterlagetypcodeliste" listVersionID="1.0">
                    <code>1130</code>
                    <name>Protokoll</name>
                </ns6:unterlageTyp>
            </ns6:verfahrensunterlage>
            <ns6:verfahrensteilschritt listURI="urn:de:xbeteiligung:codeliste:verfahrensteilschrittecodeliste" listVersionID="1.0">
                <code>0200</code>
                <name>Grobabstimmung</name>
            </ns6:verfahrensteilschritt>
            <ns6:unterverfahrensteilschritt listURI="urn:de:xbeteiligung:codeliste:unterverfahrensteilschrittecodeliste" listVersionID="1.0">
                <code>0102</code>
                <name>Grobabstimmung und Scoping durchführen</name>
            </ns6:unterverfahrensteilschritt>
            <ns6:flaechenabgrenzungWmsUrl>https://geodienste.hamburg.de/HH_WMS_xplan_pre?SERVICE=WMS&amp;VERSION=1.1.1&amp;REQUEST=GetMap&amp;LAYERS=BP_Planvektor,BP_Planraster&amp;STYLES=&amp;FORMAT=image/png&amp;TRANSPARENT=true&amp;EXCEPTIONS=application/vnd.ogc.se_inimage&amp;SRS=epsg:25832&amp;BBOX=557416.351,5936517.767,557918.78,5936850.388&amp;WIDTH=503&amp;HEIGHT=333</ns6:flaechenabgrenzungWmsUrl>
            <ns6:verfahrensschritt listURI="urn:de:xbeteiligung:codeliste:verfahrensschrittecodeliste" listVersionID="1.0">
                <code>2000</code>
                <name>Frühzeitige Behördenbeteiligung</name>
            </ns6:verfahrensschritt>
            <ns6:beschreibungPlanungsanlass>lorem ipsum</ns6:beschreibungPlanungsanlass>
            <ns6:beschreibungGeltungsbereich>von oben nach unten, von links nach rechts</ns6:beschreibungGeltungsbereich>
            <ns6:nutzerID>bielermi</ns6:nutzerID>
            <ns6:funktionspostfach>stadt-und-landschaftsplanung@bergedorf.hamburg.de</ns6:funktionspostfach>
        </ns6:beteiligung>
    </ns6:nachrichteninhalt>
</ns6:planung2Beteiligung.BeteiligungNeu.0401>

';
        $procedure = $this->createMock(ProcedureInterface::class);
        $procedure->method('getId')->willReturn('ID_7606f622-439b-4929-8625-0856c161409e');
        $procedure->method('getXtaPlanId')->willReturn('ID_7606f622-439b-4929-8625-0856c161409e');
        $orga = $this->createMock(OrgaInterface::class);
        $orga->method('getName')->willReturn('SoFreshAndSoClean');
        $procedure->method('getOrga')->willReturn($orga);
        $procedure->method('getName')->willReturn('Mars 2050');
        $procedure->method('getDesc')->willReturn('return will be planned on the fly :)');
        $procedure->method('getStartDate')->willReturn(new DateTime());
        $procedure->method('getEndDate')->willReturn((new DateTime())->add(new DateInterval('P7D')));


        $procedureXml = $this->sut->createProcedureNew401FromObject($procedure);
        echo ($procedureXml);

        $isValid = $this->sut->isValidMessage($xml, true, 'xbeteiligung-planung2beteiligung.xsd');
        self::assertTrue($isValid);
    }


}
