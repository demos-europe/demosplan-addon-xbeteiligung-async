# Generation of php classes from xsd files

We use https://github.com/goetas-webservices/xsd2php to automagically
generate php classes from the xsd files from the XBeteiligung specification.

# Usage
How to use it is documented in tests like testReadBeteiligung2PlanungBeteiligungNeuOK0410()

# Generate from Standard

When you want to generate the php classes from the standard you need to copy 
the xsd files from the standard build/xsd/ folder to the Resources/xsd folder 
within this repository.

# Before creating php classes
During the generation of the soap files for the standard update 1.3 an error occurred:
`PHP Fatal error: 
Uncaught TypeError: GoetasWebservices\Xsd\XsdToPhp\Php\PhpConverter::visitElement():
Argument #3 ($element) must be of type
GoetasWebservices\XML\XSDReader\Schema\Element\ElementSingle,
GoetasWebservices\XML\XSDReader\Schema\Element\Choice given,
called in /srv/www/addonDev/demosplan-addon-xbeteiligung-async/
vendor/goetas-webservices/xsd2php/src/Php/PhpConverter.php on line 176
and defined in /srv/www/addonDev/demosplan-addon-xbeteiligung-async/
vendor/goetas-webservices/xsd2php/src/Php/PhpConverter.php:463`

To fix this the following code was adjusted in the file
`vendor/goetas-webservices/xsd2php/src/Php/PhpConverter.php` line 170 method `visitGroup`:
old code:
```
    private function visitGroup(PHPClass $class, Schema $schema, Group $group): void
    {
        foreach ($this->filterElements($group) as $childGroup) {
            if ($childGroup instanceof Group) {
                $this->visitGroup($class, $schema, $childGroup);
            } else {
                $property = $this->visitElement($class, $schema, $childGroup);
                $class->addProperty($property);
            }
        }
    }
```
new code:
```
    private function visitGroup(PHPClass $class, Schema $schema, Group $group): void
    {
        foreach ($this->filterElements($group) as $childGroup) {
            if ($childGroup instanceof Group) {
                $this->visitGroup($class, $schema, $childGroup);
            } elseif ($childGroup instanceof Choice) {
                $this->visitChoice($class, $schema, $childGroup);
            }
            else {
                $property = $this->visitElement($class, $schema, $childGroup);
                $class->addProperty($property);
            }
        }
    }
```

# Create php classes manually

To manually create the php classes and metadata you need to perform 
following steps

``
[pathToAddon]/vendor/bin/xsd2php convert config/xsd2php.yml Resources/xsd/*.xsd
``

XML messages could automatically be casted to php objects by using the
JMS-Serializer-Bundle http://jmsyst.com/bundles/JMSSerializerBundle. It is
also easily possible to cast php classes back to xml!

# Necessary adjustments after standard update

In order for generated XML messages to be successfully validated,
the following adjustments must be made after generating the classes and
Yml files:

add `xbeteiligung:` as prefix to xml_root_name in 
`schema.Planung2BeteiligungBeteiligungKommunalNeu0401.yml`,
`schema.Planung2BeteiligungBeteiligungKommunalAktualisieren0402.yml`,
`schema.Planung2BeteiligungBeteiligungKommunalLoeschen0409.yml`,
`schema.Planung2BeteiligungBeteiligungRaumordnungNeu0301.yml`,
`schema.Planung2BeteiligungBeteiligungRaumordnungAktualisieren0302.yml`,
`schema.Planung2BeteiligungBeteiligungRaumordnungLoeschen0309.yml`

Example - xml_root_name: `xbeteiligung:planung2Beteiligung.BeteiligungKommunalNeu.0401`

Add the following to enum in xbeteiligung-codes.xsd (search for "0401" then you should find it)
`<xs:enumeration value="0301">
    <xs:annotation>
        <xs:appinfo>
            <beschreibung>planung2Beteiligung.RaumordnungNeu.0301</beschreibung>
        </xs:appinfo>
    </xs:annotation>
</xs:enumeration>
<xs:enumeration value="0302">
    <xs:annotation>
        <xs:appinfo>
            <beschreibung>planung2Beteiligung.RaumordnungAktualisieren.0302</beschreibung>
        </xs:appinfo>
    </xs:annotation>
</xs:enumeration>
<xs:enumeration value="0309">
    <xs:annotation>
        <xs:appinfo>
            <beschreibung>planung2Beteiligung.RaumordnungLoeschen.0309</beschreibung>
        </xs:appinfo>
    </xs:annotation>
</xs:enumeration>`

comment out `namespace: ...` in `schema.CodeType.yml` for
`code` and `name`

comment out `namespace: ...` in `src/Soap/metadata/schema.BeteiligungKommunalOeffentlichkeitType.yml`
for `anlagen`->`xml_list`
comment out `namespace: ...` in `src/Soap/metadata/schema.BeteiligungRaumordnungType.yml`
for `anlagen`->`xml_list`

comment out `namespace: ...` in `src/Soap/metadata/schema.MetadatenAnlageType.yml`
for `bezeichnung` and `anlageart` and `mimeType` and `anhangOderVerlinkung`.

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
