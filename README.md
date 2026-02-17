# Overview Efa components
https://miro.com/app/board/uXjVKuYy5ow=/

# Generation of php classes from xsd files

We use https://github.com/goetas-webservices/xsd2php to automagically
generate php classes from the xsd files from the XBeteiligung specification.

# IDE Configuration for XSD Schemas

## PhpStorm XML Schema Resolution

When working with XBeteiligung XML files in PhpStorm, you may see errors like "Cannot resolve symbol xbeteiligung:..." or "Element must be declared". This happens because PhpStorm cannot locate the XSD schema files for validation and code completion.

### Solution: Map XML Namespaces to Local XSD Files

Configure PhpStorm to recognize the XBeteiligung namespace:

1. Open **Settings/Preferences** → **Languages & Frameworks** → **Schemas and DTDs**
2. Click the **+** button to add a new External Schema
3. Configure the mapping:
   - **URI**: `https://www.xleitstelle.de/xbeteiligung/12`
   - **File**: Browse to `Resources/xsd/xbeteiligung.xsd` in this repository
4. Click **OK** to save

After this configuration, PhpStorm will:
- Validate XML files against the XBeteiligung schema
- Provide auto-completion for XBeteiligung elements
- Show inline documentation from the XSD files
- Highlight schema validation errors

**Note:** You may also need to map the related XOeV namespaces:
- `http://xoev.de/schemata/basisnachricht/g2g/1_1` → `Resources/xsd/xoev-basisnachricht-g2g_1.1.xsd`
- `http://xoev.de/schemata/basisnachricht/behoerde/1_1` → `Resources/xsd/xoev-basisnachricht-behoerde_1.1.xsd`
- `http://xoev.de/schemata/basisnachricht/kommunikation/1_1` → `Resources/xsd/xoev-basisnachricht-kommunikation_1.1.xsd`

# Usage
How to use it is documented in tests like testReadBeteiligung2PlanungBeteiligungNeuOK0410()

# REST API

A REST API endpoint is available for processing XBeteiligung messages as an alternative to RabbitMQ. 

## Endpoint

`POST /addon/xbeteiligung/procedure/create`

## Authentication

Authentication is done via a Bearer token in the **X-Addon-XBeteiligung-Authorization** custom header.
The token value must match the `addon_xbeteiligung_async_api_token` parameter value.

```
X-Addon-XBeteiligung-Authorization: Bearer your-token-here
```

This custom header is used specifically for XBeteiligung addon authentication to avoid interference with the core application's authentication.

## Request Format

The request body should contain the raw XML message content directly:

```xml
<xbeteiligung:planung2Beteiligung.BeteiligungKommunalNeu.0401>
    <!-- XML content here -->
</xbeteiligung:planung2Beteiligung.BeteiligungKommunalNeu.0401>
```

The API automatically detects the message type from the XML content.

## Response

The response will be an XML string with Content-Type: application/xml

# Generate from Standard

When you want to generate the php classes from the standard you need to copy 
the xsd files from the standard build/xsd/ folder to the Resources/xsd folder 
within this repository.

# Create php classes manually

To manually create the php classes and metadata you need to perform 
following steps

``
[pathToAddon]/vendor/bin/xsd2php convert config/xsd2php.yml Resources/xsd/*.xsd
``

XML messages could automatically be casted to php objects by using the
JMS-Serializer-Bundle http://jmsyst.com/bundles/JMSSerializerBundle. It is
also easily possible to cast php classes back to xml!
add type to AbstractObject:
````
Resources/xsd/gmlProfilexplan.xsd:
    <element name="AbstractObject" abstract="true" type="anyType"/>
````
# Necessary adjustments after standard update

In order for generated XML messages to be successfully validated,
the following adjustments must be made after generating the classes and
Yml files:

Add `xbeteiligung:` as prefix to xml_root_name and add `xml_namespaces` configuration to prevent auto-generated namespace prefixes in:

**Input Message Files:**
[`Schema.XBeteiligung.KommunalInitiieren0401.yml`](src/Soap/Metadata/Schema.XBeteiligung.KommunalInitiieren0401.yml),
[`Schema.XBeteiligung.KommunalAktualisieren0402.yml`](src/Soap/Metadata/Schema.XBeteiligung.KommunalAktualisieren0402.yml),
[`Schema.XBeteiligung.KommunalLoeschen0409.yml`](src/Soap/Metadata/Schema.XBeteiligung.KommunalLoeschen0409.yml),
[`Schema.XBeteiligung.RaumordnungInitiieren0301.yml`](src/Soap/Metadata/Schema.XBeteiligung.RaumordnungInitiieren0301.yml),
[`Schema.XBeteiligung.RaumordnungAktualisieren0302.yml`](src/Soap/Metadata/Schema.XBeteiligung.RaumordnungAktualisieren0302.yml),
[`Schema.XBeteiligung.RaumordnungLoeschen0309.yml`](src/Soap/Metadata/Schema.XBeteiligung.RaumordnungLoeschen0309.yml),
[`Schema.XBeteiligung.PlanfeststellungAktualisieren0202.yml`](src/Soap/Metadata/Schema.XBeteiligung.PlanfeststellungAktualisieren0202.yml),
[`Schema.XBeteiligung.PlanfeststellungInitiieren0201.yml`](src/Soap/Metadata/Schema.XBeteiligung.PlanfeststellungInitiieren0201.yml),
[`Schema.XBeteiligung.PlanfeststellungLoeschen0209.yml`](src/Soap/Metadata/Schema.XBeteiligung.PlanfeststellungLoeschen0209.yml),
[`Schema.AllgemeinStellungnahmeNeuabgegeben0701.yml`](src/Soap/Metadata/Schema.XBeteiligung.AllgemeinStellungnahmeNeuabgegeben0701.yml)

**Response Message Files (OK/NOK):**

*Kommunal Series (400):*
[`Schema.XBeteiligung.KommunalInitiierenOK0411.yml`](src/Soap/Metadata/Schema.XBeteiligung.KommunalInitiierenOK0411.yml),
[`Schema.XBeteiligung.KommunalInitiierenNOK0421.yml`](src/Soap/Metadata/Schema.XBeteiligung.KommunalInitiierenNOK0421.yml),
[`Schema.XBeteiligung.KommunalAktualisierenOK0412.yml`](src/Soap/Metadata/Schema.XBeteiligung.KommunalAktualisierenOK0412.yml),
[`Schema.XBeteiligung.KommunalAktualisierenNOK0422.yml`](src/Soap/Metadata/Schema.XBeteiligung.KommunalAktualisierenNOK0422.yml),
[`Schema.XBeteiligung.KommunalLoeschenOK0419.yml`](src/Soap/Metadata/Schema.XBeteiligung.KommunalLoeschenOK0419.yml),
[`Schema.XBeteiligung.KommunalLoeschenNOK0429.yml`](src/Soap/Metadata/Schema.XBeteiligung.KommunalLoeschenNOK0429.yml)

*Raumordnung Series (300):*
[`Schema.XBeteiligung.RaumordnungInitiierenOK0311.yml`](src/Soap/Metadata/Schema.XBeteiligung.RaumordnungInitiierenOK0311.yml),
[`Schema.XBeteiligung.RaumordnungInitiierenNOK0321.yml`](src/Soap/Metadata/Schema.XBeteiligung.RaumordnungInitiierenNOK0321.yml),
[`Schema.XBeteiligung.RaumordnungAktualisierenOK0312.yml`](src/Soap/Metadata/Schema.XBeteiligung.RaumordnungAktualisierenOK0312.yml),
[`Schema.XBeteiligung.RaumordnungAktualisierenNOK0322.yml`](src/Soap/Metadata/Schema.XBeteiligung.RaumordnungAktualisierenNOK0322.yml),
[`Schema.XBeteiligung.RaumordnungLoeschenOK0319.yml`](src/Soap/Metadata/Schema.XBeteiligung.RaumordnungLoeschenOK0319.yml),
[`Schema.XBeteiligung.RaumordnungLoeschenNOK0329.yml`](src/Soap/Metadata/Schema.XBeteiligung.RaumordnungLoeschenNOK0329.yml)

*Planfeststellung Series (200):*
[`Schema.XBeteiligung.PlanfeststellungInitiierenOK0211.yml`](src/Soap/Metadata/Schema.XBeteiligung.PlanfeststellungInitiierenOK0211.yml),
[`Schema.XBeteiligung.PlanfeststellungInitiierenNOK0221.yml`](src/Soap/Metadata/Schema.XBeteiligung.PlanfeststellungInitiierenNOK0221.yml),
[`Schema.XBeteiligung.PlanfeststellungAktualisierenOK0212.yml`](src/Soap/Metadata/Schema.XBeteiligung.PlanfeststellungAktualisierenOK0212.yml),
[`Schema.XBeteiligung.PlanfeststellungAktualisierenNOK0222.yml`](src/Soap/Metadata/Schema.XBeteiligung.PlanfeststellungAktualisierenNOK0222.yml),
[`Schema.XBeteiligung.PlanfeststellungLoeschenOK0219.yml`](src/Soap/Metadata/Schema.XBeteiligung.PlanfeststellungLoeschenOK0219.yml),
[`Schema.XBeteiligung.PlanfeststellungLoeschenNOK0229.yml`](src/Soap/Metadata/Schema.XBeteiligung.PlanfeststellungLoeschenNOK0229.yml)

**Example configuration for input message files:**
```yaml
DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\KommunalInitiieren0401:
    xml_root_name: xbeteiligung:kommunal.Initiieren.0401
    xml_root_namespace: 'https://www.xleitstelle.de/xbeteiligung/12'
    xml_namespaces:
        xbeteiligung: 'https://www.xleitstelle.de/xbeteiligung/12'
        g2g: 'http://xoev.de/schemata/basisnachricht/g2g/1_1'
        behoerde: 'http://xoev.de/schemata/basisnachricht/behoerde/1_1'
        kommunikation: 'http://xoev.de/schemata/basisnachricht/kommunikation/1_1'
```

**Example configuration for response message files:**
```yaml
DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\KommunalInitiierenOK0411:
    xml_root_name: xbeteiligung:kommunal.Initiieren.OK.0411
    xml_root_namespace: 'https://www.xleitstelle.de/xbeteiligung/12'
    xml_namespaces:
        xbeteiligung: 'https://www.xleitstelle.de/xbeteiligung/12'
        g2g: 'http://xoev.de/schemata/basisnachricht/g2g/1_1'
        behoerde: 'http://xoev.de/schemata/basisnachricht/behoerde/1_1'
        kommunikation: 'http://xoev.de/schemata/basisnachricht/kommunikation/1_1'
        xoev-code: 'http://xoev.de/schemata/code/1_0'
        xsi: 'http://www.w3.org/2001/XMLSchema-instance'
```

The `xml_namespaces` configuration ensures that JMS Serializer uses clean, readable namespace prefixes like `g2g:`, `behoerde:`, and `kommunikation:` instead of random generated ones like `ns-625090a5:`. This configuration replaces the previous manual namespace handling approach for both input and response messages.

Add the Following to Schemas:

[`src/Soap/Metadata/Schema.Basisnachricht.Behoerde.BehoerdeTypeType.yml`](src/Soap/Metadata/Schema.Basisnachricht.Behoerde.BehoerdeTypeType.yml):
```yaml
Verzeichnisdienst:
    xml_element:
        namespace: 'http://xoev.de/schemata/basisnachricht/behoerde/1_1'
kennung:
    xml_element:
        namespace: 'http://xoev.de/schemata/basisnachricht/behoerde/1_1'
name:
    xml_element:
        namespace: 'http://xoev.de/schemata/basisnachricht/behoerde/1_1'
erreichbarkeit:
    xml_list:
        namespace: 'http://xoev.de/schemata/basisnachricht/behoerde/1_1'
```

[`src/Soap/Metadata/Schema.Basisnachricht.G2g.IdentifikationNachrichtTypeType.yml`](src/Soap/Metadata/Schema.Basisnachricht.G2g.IdentifikationNachrichtTypeType.yml):
```yaml
nachrichtenUUID:
    xml_element:
        namespace: 'http://xoev.de/schemata/basisnachricht/g2g/1_1'
nachrichtentyp:
    xml_element:
        namespace: 'http://xoev.de/schemata/basisnachricht/g2g/1_1'
erstellungszeitpunkt:
    xml_element:
        namespace: 'http://xoev.de/schemata/basisnachricht/g2g/1_1'
```

[`src/Soap/Metadata/Schema.Basisnachricht.Kommunikation.KommunikationTypeType.yml`](src/Soap/Metadata/Schema.Basisnachricht.Kommunikation.KommunikationTypeType.yml):
```yaml
kanal:
    xml_element:
        namespace: 'http://xoev.de/schemata/basisnachricht/kommunikation/1_1'
kennung:
    xml_element:
        namespace: 'http://xoev.de/schemata/basisnachricht/kommunikation/1_1'
zusatz:
    xml_element:
        namespace: 'http://xoev.de/schemata/basisnachricht/kommunikation/1_1'
```

[`src/Soap/Metadata/Schema.Basisnachricht.G2g.NachrichtenkopfG2GTypeType.yml`](src/Soap/Metadata/Schema.Basisnachricht.G2g.NachrichtenkopfG2GTypeType.yml):
```yaml
identifikationNachricht:
    xml_element:
        namespace: 'http://xoev.de/schemata/basisnachricht/g2g/1_1'
leser:
    xml_element:
        namespace: 'http://xoev.de/schemata/basisnachricht/g2g/1_1'
autor:
    xml_element:
        namespace: 'http://xoev.de/schemata/basisnachricht/g2g/1_1'
```


comment out `namespace: ...` in [`Schema.Code.CodeType.yml`](src/Soap/Metadata/Schema.Code.CodeType.yml)
for the fields `code` and `name`.
comment out `namespace: ...` in [`Schema.XBeteiligung.BeteiligungKommunalOeffentlichkeitType.yml`](src/Soap/Metadata/Schema.XBeteiligung.BeteiligungKommunalOeffentlichkeitType.yml)
for the fields: `anlagen.xml_list`
comment out `namespace: ...` in [`Schema.XBeteiligung.BeteiligungKommunalTOEBType.yml`](src/Soap/Metadata/Schema.XBeteiligung.BeteiligungKommunalTOEBType.yml)
for the field: `anlagen.xml_list` (specifically the `entry_name: anlage` namespace configuration)
comment out `namespace: ...` in [`Schema.XBeteiligung.BeteiligungRaumordnungType.yml`](src/Soap/Metadata/Schema.XBeteiligung.BeteiligungRaumordnungType.yml)
for the fields: `anlagen.xml_list` (specifically the `entry_name: anlage` namespace configuration)
comment out `namespace: ...` in [`Schema.XBeteiligung.MetadatenAnlageType.yml`](src/Soap/Metadata/Schema.XBeteiligung.MetadatenAnlageType.yml)
for the fields: `bezeichnung`, `versionsnummer`, `datum`, `anlageart`, `mimeType` and `anhangOderVerlinkung`
comment out `namespace: ...` in [`Schema.XBeteiligung.BeteiligungPlanfeststellungOeffentlichkeitType.yml`](src/Soap/Metadata/Schema.XBeteiligung.BeteiligungPlanfeststellungOeffentlichkeitType.yml)
for the field: `anlagen.xml_list` (specifically the `entry_name: anlage` namespace configuration)
comment out `namespace: ...` in [`Schema.XBeteiligung.BeteiligungPlanfeststellungTOEBType.yml`](src/Soap/Metadata/Schema.XBeteiligung.BeteiligungPlanfeststellungTOEBType.yml)
for the field: `anlagen.xml_list` (specifically the `entry_name: anlage` namespace configuration)
comment out `namespace: ...` in [`Schema.XBeteiligung.AnlagenType.yml`](src/Soap/Metadata/Schema.XBeteiligung.AnlagenType.yml)
for both `xml_element` and `xml_list` namespace entries for the `anlage` field
comment out `namespace: ...` in [`Schema.XBeteiligung.AnlagenLinkType.yml`](src/Soap/Metadata/Schema.XBeteiligung.AnlagenLinkType.yml)
for both `xml_element` and `xml_list` namespace entries for the `anlage` field

**Important:** The `anlage` elements within `anlagen` collections must NOT have namespace prefixes according to the XBeteiligung XSD schema specification. These elements are defined with `form="unqualified"` in the schema, which means they should appear as `<anlage>` rather than `<xbeteiligung:anlage>`. Failure to comment out the namespace configuration will result in XSD validation errors.

**Note:** The container `anlagen` element itself SHOULD keep its namespace configuration (for the element, not the list entries), as it appears as `<xbeteiligung:anlagen>` in the XML. Only the individual `<anlage>` entries within the collection should be unqualified.

When updating to a new xBeteiligung standard version, update the hardcoded 
namespace version in `XBeteiligungIncomingMessageParser::validateRequiredNamespace()` 
from `/12` to the new version number. This validation only triggers a warning so far.

Run the unit tests XBeteiligungServiceTest- (401, 402, 409) and fix any bugs that appear.
Update what is documented here if there are any changes to be aware of.

# Release

To release a new version of this library you need to perform the script `release.sh` 
in the root folder of this repository.:
    
    ./release.sh <version>
    
It updates the Tag in the changelog, sets the new version in composer.json and package.json if applicable, 
creates a tag and pushes it to the remote repository.
You may do this at main or release branch if you have the rights to do so, otherwise you may create 
a pull request and merge it directly after the release.
