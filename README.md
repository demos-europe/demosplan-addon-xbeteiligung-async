# Generation of php classes from xsd files

We use https://github.com/goetas-webservices/xsd2php to automagically
generate php classes from the xsd files from the XBeteiligung specification.

# Usage
How to use it is documented in tests like testReadBeteiligung2PlanungBeteiligungNeuOK0410()

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

Add `xbeteiligung:` as prefix to xml_root_name in

[`Schema.XBeteiligung.KommunalInitiieren0401.yml`](src/Soap/Metadata/Schema.XBeteiligung.KommunalInitiieren0401.yml),
[`Schema.XBeteiligung.KommunalAktualisieren0402.yml`](src/Soap/Metadata/Schema.XBeteiligung.KommunalAktualisieren0402.yml),
[`Schema.XBeteiligung.KommunalLoeschen0409.yml`](src/Soap/Metadata/Schema.XBeteiligung.KommunalLoeschen0409.yml),
[`Schema.XBeteiligung.RaumordnungInitiieren0301.yml`](src/Soap/Metadata/Schema.XBeteiligung.RaumordnungInitiieren0301.yml),
[`Schema.XBeteiligung.RaumordnungAktualisieren0302.yml`](src/Soap/Metadata/Schema.XBeteiligung.RaumordnungAktualisieren0302.yml),
[`Schema.XBeteiligung.RaumordnungLoeschen0309.yml`](src/Soap/Metadata/Schema.XBeteiligung.RaumordnungLoeschen0309.yml),
[`Schema.XBeteiligung.PlanfeststellungAktualisieren0202.yml`](src/Soap/Metadata/Schema.XBeteiligung.PlanfeststellungAktualisieren0202.yml),
[`Schema.XBeteiligung.PlanfeststellungInitiieren0201.yml`](src/Soap/Metadata/Schema.XBeteiligung.PlanfeststellungInitiieren0201.yml),
[`Schema.XBeteiligung.PlanfeststellungLoeschen0209.yml`](src/Soap/Metadata/Schema.XBeteiligung.PlanfeststellungLoeschen0209.yml),
[`Schema.AllgemeinStellungnahmeNeuabgegeben0701.yml`](src/Soap/Metadata/Schema.XBeteiligung.AllgemeinStellungnahmeNeuabgegeben0701.yml),

Example - `xml_root_name`: `xbeteiligung:planung2Beteiligung.BeteiligungKommunalNeu.0401`

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


comment out `namespace: ...` in [`Schema.Code.CodeType.yml`](src/Soap/metadata/Schema.Code.CodeType.yml)
for the fiels `code` and `name`.

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
