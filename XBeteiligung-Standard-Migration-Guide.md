# XBeteiligung Standard Migration Guide: Version Update Instructions

This document provides step-by-step instructions for migrating the XBeteiligung standard between different versions in the demosplan-addon-xbeteiligung-async project. This guide covers both upgrades and downgrades.

## Overview

XBeteiligung standard migration involves updating namespace configurations, regenerating PHP classes from XSD files, applying metadata adjustments, and updating test data to ensure compatibility with the target version.

## Prerequisites

- Access to the demosplan-addon-xbeteiligung-async project
- XSD files for the target version already present in `Resources/xsd/` directory
- xsd2php tool available via `vendor/bin/xsd2php`
- PHPUnit for testing

## Step-by-Step Instructions

### 1. Identify Current and Target Versions

Before starting the migration, identify:
- **Current version**: Check existing namespace in `config/xsd2php.yml`
- **Target version**: Verify the target namespace in the new XSD files

```bash
# Check current namespace in configuration
grep "xleitstelle.de/xbeteiligung" config/xsd2php.yml

# Check target namespace in XSD files
grep -r "targetNamespace" Resources/xsd/*.xsd | grep xbeteiligung
```

**Common version mappings:**
- Version 1.2: `https://www.xleitstelle.de/xbeteiligung/12`
- Version 1.3: `https://www.xleitstelle.de/xbeteiligung/1/3`
- Version 1.4: `https://www.xleitstelle.de/xbeteiligung/14`

### 2. Update XSD2PHP Configuration

**File:** `config/xsd2php.yml`

Update the XBeteiligung namespace configuration to match your target version:

```yaml
# Example migration from 1.3 to 1.2:
# Change from:
'https://www.xleitstelle.de/xbeteiligung/1/3': 'DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung'

# To:
'https://www.xleitstelle.de/xbeteiligung/12': 'DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung'
```

**Important:** Verify that other namespaces match the XSD files:
```bash
# Check xbau-kernmodul namespace
grep "xleitstelle.de/xbau/kernmodul" Resources/xsd/xbau-kernmodul-*.xsd

# Update accordingly in xsd2php.yml
```

### 3. Fix GML Profile XSD (if required)

**File:** `Resources/xsd/gmlProfilexplan.xsd`

Some versions require the `type="anyType"` attribute for the AbstractObject element:

```xml
<!-- If xsd2php fails with "Can't find type named anyType", change: -->
<element name="AbstractObject" abstract="true"/>

<!-- To: -->
<element name="AbstractObject" abstract="true" type="anyType"/>
```

### 4. Regenerate PHP Classes

Run the xsd2php conversion command:

```bash
vendor/bin/xsd2php convert config/xsd2php.yml Resources/xsd/*.xsd
```

**Note:** This command will overwrite existing PHP classes and JMS metadata files.

### 5. Apply Required Metadata Adjustments

The following metadata adjustments are typically required after generation:

#### 5.1 Add XBeteiligung Prefix to XML Root Names

Find all schema files that need the `xbeteiligung:` prefix:

```bash
# Find files with xml_root_name
grep -l "xml_root_name:" src/Soap/Metadata/Schema.XBeteiligung.*.yml
```

Update the main message type files to add `xbeteiligung:` prefix:

**Key files to update:**
- `Schema.XBeteiligung.KommunalInitiieren*.yml`
- `Schema.XBeteiligung.KommunalAktualisieren*.yml`
- `Schema.XBeteiligung.KommunalLoeschen*.yml`
- `Schema.XBeteiligung.RaumordnungInitiieren*.yml`
- `Schema.XBeteiligung.RaumordnungAktualisieren*.yml`
- `Schema.XBeteiligung.RaumordnungLoeschen*.yml`
- `Schema.XBeteiligung.PlanfeststellungAktualisieren*.yml`
- `Schema.XBeteiligung.PlanfeststellungInitiieren*.yml`
- `Schema.XBeteiligung.PlanfeststellungLoeschen*.yml`
- `Schema.XBeteiligung.AllgemeinStellungnahmeNeuabgegeben*.yml`

**Example change:**
```yaml
# Change from:
xml_root_name: kommunal.Initiieren.0401

# To:
xml_root_name: xbeteiligung:kommunal.Initiieren.0401
```

#### 5.2 Comment Out Namespace in Code Schema

**File:** `src/Soap/Metadata/Schema.Code.CodeType.yml`

Comment out namespace entries for `code` and `name` fields:

```yaml
# For both code and name fields, change:
xml_element:
    namespace: 'http://xoev.de/schemata/code/1_0'

# To:
xml_element:
    # namespace: 'http://xoev.de/schemata/code/1_0'
```

#### 5.3 Comment Out Namespace in BeteiligungKommunalOeffentlichkeitType

**File:** `src/Soap/Metadata/Schema.XBeteiligung.BeteiligungKommunalOeffentlichkeitType.yml`

Comment out namespace for `anlagen.xml_list`:

```yaml
# In the anlagen property xml_list section, comment out namespace
xml_list:
    inline: false
    entry_name: anlage
    skip_when_empty: true
    # namespace: 'https://www.xleitstelle.de/xbeteiligung/[VERSION]'
```

#### 5.4 Comment Out Namespaces in MetadatenAnlageType

**File:** `src/Soap/Metadata/Schema.XBeteiligung.MetadatenAnlageType.yml`

Comment out namespace entries for these fields:
- `bezeichnung`
- `versionsnummer`
- `datum`
- `anlageart`
- `mimeType`
- `anhangOderVerlinkung`

**Example:**
```yaml
# For each field, change:
xml_element:
    namespace: 'https://www.xleitstelle.de/xbeteiligung/[VERSION]'

# To:
xml_element:
    # namespace: 'https://www.xleitstelle.de/xbeteiligung/[VERSION]'
```

### 6. Update Test Data

**File(s):** `tests/res/xmlv*/xbeteiligung-test-*.xml`

Update test XML files to match the target version:

```xml
<!-- Update version attribute -->
version="[TARGET_VERSION]"

<!-- Update namespace declaration -->
xmlns:ns5="https://www.xleitstelle.de/xbeteiligung/[TARGET_NAMESPACE]"
```

**Examples:**
- For version 1.2: `version="1.2"` and `xmlns:ns5="https://www.xleitstelle.de/xbeteiligung/12"`
- For version 1.3: `version="1.3"` and `xmlns:ns5="https://www.xleitstelle.de/xbeteiligung/1/3"`

### 7. Run Tests to Verify Migration

Execute comprehensive tests to ensure the migration was successful:

```bash
# Run specific XBeteiligung service tests
./vendor/bin/phpunit tests/Logic/XBeteiligingService/ --verbose

# Run all addon tests
./vendor/bin/phpunit tests/ --verbose
```

**Expected Results:**
- All tests should pass
- Only deprecation warnings should be present (these are acceptable)

## Migration Checklist

- [ ] Current and target versions identified
- [ ] xsd2php.yml updated with correct target namespace
- [ ] Other namespaces verified against XSD files
- [ ] gmlProfilexplan.xsd fixed if needed
- [ ] PHP classes regenerated successfully
- [ ] XBeteiligung prefix added to xml_root_name in key metadata files
- [ ] Namespace entries commented out in Code.CodeType.yml
- [ ] Namespace commented out for anlagen.xml_list
- [ ] Namespaces commented out for specified fields in MetadatenAnlageType
- [ ] Test XML files updated to target version
- [ ] All tests passing

## Troubleshooting

### Common Issues

1. **xsd2php conversion fails with type errors**
   - Check if `type="anyType"` is needed in gmlProfilexplan.xsd
   - Verify all namespaces in xsd2php.yml match XSD files exactly

2. **Tests fail with message parsing errors**
   - Ensure test XML files use correct version and namespace
   - Check that xml_root_name has proper xbeteiligung: prefix

3. **Namespace mismatch during XML processing**
   - Verify namespace commenting is applied correctly
   - Check that target namespace matches between config and XSD files

### Validation Commands

```bash
# Verify XSD file namespaces
grep -r "targetNamespace" Resources/xsd/*.xsd

# Check generated metadata prefixes
grep -H "xml_root_name:" src/Soap/Metadata/Schema.XBeteiligung.*.yml

# Validate namespace configuration
grep "xleitstelle.de" config/xsd2php.yml
```

## Version-Specific Notes

### Version 1.2
- Uses simplified namespace: `/12`
- May require gmlProfilexplan.xsd fix
- Test files should use `version="1.2"`

### Version 1.3
- Uses nested namespace: `/1/3`
- Typically works without gmlProfilexplan.xsd fix
- Test files should use `version="1.3"`

### Version 1.4
- Uses simplified namespace: `/14`
- Check for additional XSD changes
- Test files should use `version="1.4"`

## Post-Migration Tasks

1. **Update Documentation**
   - Update README.md if version references exist
   - Update API documentation if applicable

2. **Verify Integration**
   - Test XML message processing end-to-end
   - Validate against external XBeteiligung systems if available

3. **Update Deployment**
   - Ensure deployment scripts account for version change
   - Update any version-specific configuration

## Conclusion

This guide provides a systematic approach to migrating between XBeteiligung standard versions. The process ensures compatibility with the target version while maintaining all existing functionality through comprehensive testing and validation.