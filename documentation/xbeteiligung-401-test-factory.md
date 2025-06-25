# XBeteiligung 401 Test Factory Documentation

## Overview

The XBeteiligung 401 Test Factory provides a dynamic, template-based approach for generating XML test data for XBeteiligung 401 (kommunal.Initiieren) messages. This system replaces static XML files with configurable test scenarios, enabling more flexible and comprehensive testing.

## Architecture

### Components

1. **XBeteiligung401TestFactory** (`tests/DataFactory/XBeteiligung401TestFactory.php`)
   - Core factory class for dynamic XML generation
   - Handles template processing and placeholder replacement
   - Supports conditional sections for different participation types

2. **XML Template** (`tests/fixtures/xbeteiligung/templates/kommunal-initiieren-0401.xml.template`)
   - Base XML template with placeholders and conditional sections
   - Uses proper namespace conventions (xbeteiligung:, g2g:, behoerde:, kommunikation:)
   - Supports dynamic content injection

3. **Test Scenarios Configuration** (`tests/fixtures/xbeteiligung/test-data/kommunal-initiieren-0401-scenarios.yml`)
   - YAML configuration defining test scenarios
   - Includes both valid and invalid test cases
   - Provides default values to reduce duplication

4. **Unit Tests** (`tests/DataFactory/XBeteiligung401TestFactoryTest.php`)
   - Comprehensive test coverage for the factory
   - Tests all scenarios and edge cases
   - Validates XML generation and conditional logic

## Usage

### Basic Usage

```php
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\CommonHelpers;
use DemosEurope\DemosplanAddon\XBeteiligung\Tests\DataFactory\XBeteiligung401TestFactory;

// Initialize the factory
$commonHelpers = new CommonHelpers($logger);
$factory = new XBeteiligung401TestFactory(
    AddonPath::getRootPath(),
    $commonHelpers
);

// Generate XML for a specific scenario
$xml = $factory->createXML('quickborn_minimal', true);

// Generate XML for all valid scenarios
$allValidXML = $factory->createAllXML(true);

// Generate XML for invalid scenarios
$invalidXML = $factory->createXML('unknown_organization', false);
```

### Available Test Scenarios

#### Valid Scenarios

1. **quickborn_minimal**
   - Basic procedure creation with Stadt Quickborn
   - Minimal required fields only
   - No public participation or TOEB sections

2. **quickborn_comprehensive**
   - Comprehensive procedure with public participation
   - Includes beteiligungOeffentlichkeit section
   - Full set of participation metadata

3. **buero_flachennutzung**
   - Procedure with Büro organization
   - Flächennutzungsplan type
   - Includes TOEB participation section

4. **quickborn_with_attachments**
   - Procedure with document attachments
   - Includes anlagen sections
   - Tests file metadata handling

#### Invalid Scenarios

1. **unknown_organization**
   - Tests with non-existent organization
   - Should trigger validation errors

2. **empty_organization**
   - Tests with empty organization name
   - Tests required field validation

3. **missing_plan_name**
   - Tests with missing plan name
   - Tests required field validation

4. **invalid_planart_code**
   - Tests with invalid plan type code
   - Tests code validation

5. **malformed_geltungsbereich**
   - Tests with invalid GeoJSON
   - Tests territory validation

### Integration with Existing Tests

The factory integrates seamlessly with existing test infrastructure:

```php
class KommunaleProcedureCreatorTest extends TestCase
{
    protected XBeteiligung401TestFactory $xmlFactory;

    protected function setUp(): void
    {
        // ... existing setup ...
        
        $commonHelpers = new CommonHelpers($this->logger);
        $this->xmlFactory = new XBeteiligung401TestFactory(
            AddonPath::getRootPath(),
            $commonHelpers
        );
    }

    /**
     * @dataProvider getTestScenarios()
     */
    public function testCreateProcedure(string $scenarioName): void
    {
        $xml = $this->xmlFactory->createXML($scenarioName, true);
        // ... test logic ...
    }

    public static function getTestScenarios(): array
    {
        return [
            'Stadt Quickborn minimal' => ['quickborn_minimal'],
            'Stadt Quickborn comprehensive' => ['quickborn_comprehensive'],
            'Büro Flächennutzungsplan' => ['buero_flachennutzung'],
        ];
    }
}
```

## Template System

### Placeholder Format

The template uses double curly braces for placeholders:

```xml
<xbeteiligung:planname>{{PLAN_NAME}}</xbeteiligung:planname>
<name>{{ORG_NAME}}</name>
<code>{{PLANART_CODE}}</code>
```

### Conditional Sections

Conditional sections allow including/excluding parts of the XML based on scenario configuration:

```xml
{{#INCLUDE_BETEILIGUNG_OEFFENTLICHKEIT}}
<xbeteiligung:beteiligungOeffentlichkeit>
    <xbeteiligung:beteiligungsID>{{BETEILIGUNG_OEFFENTLICHKEIT_ID}}</xbeteiligung:beteiligungsID>
    <!-- ... more content ... -->
</xbeteiligung:beteiligungOeffentlichkeit>
{{/INCLUDE_BETEILIGUNG_OEFFENTLICHKEIT}}
```

Supported conditional sections:
- `INCLUDE_BETEILIGUNG_OEFFENTLICHKEIT` - Public participation
- `INCLUDE_BETEILIGUNG_TOEB` - TOEB participation  
- `INCLUDE_ANLAGEN_OEFFENTLICHKEIT` - Public participation attachments
- `INCLUDE_ANLAGEN_TOEB` - TOEB attachments

### Dynamic Parameters

The factory automatically generates certain parameters:

- **UUIDs**: `nachrichten_uuid`, `vorgangs_id`, `plan_id`
- **Timestamps**: `erstellungszeitpunkt` in ISO format
- **Parameter transformation**: Snake_case keys converted to UPPER_CASE placeholders

## Configuration

### YAML Structure

```yaml
# Default values used across all scenarios
defaults:
  produkt: "DiPlan Cockpit"
  produkthersteller: "DEMOS plan GmbH"
  # ... more defaults ...

# Valid test scenarios
valid_scenarios:
  scenario_name:
    description: "Description of what this scenario tests"
    org_name: "Organization Name"
    plan_name: "Plan Name"
    # ... scenario-specific values ...

# Invalid test scenarios  
invalid_scenarios:
  scenario_name:
    description: "Description of expected failure"
    expected_error: "Expected error message"
    # ... invalid values ...
```

### Adding New Scenarios

1. Add scenario configuration to the YAML file
2. Include any new placeholders in the template if needed
3. Add test cases in the factory unit tests
4. Update integration tests to use the new scenario

## Benefits

### Compared to Static XML Files

✅ **Dynamic Generation**: Generate XML with different data combinations  
✅ **Configurable**: Easy to modify test scenarios without editing XML  
✅ **Maintainable**: Single template for all variations  
✅ **Comprehensive**: Support for both valid and invalid scenarios  
✅ **Consistent**: Proper namespace conventions throughout  
✅ **Testable**: Unit tests ensure factory reliability  

### Testing Improvements

- **Multiple Scenarios**: Test different organizations, plan types, participation types
- **Edge Cases**: Easily test invalid data and error conditions  
- **Consistency**: All tests use the same namespace conventions
- **Flexibility**: Add new scenarios without code changes
- **Validation**: Both positive and negative test cases

## File Structure

```
tests/
├── DataFactory/
│   ├── XBeteiligung401TestFactory.php          # Core factory class
│   └── XBeteiligung401TestFactoryTest.php      # Unit tests
├── fixtures/
│   └── xbeteiligung/
│       ├── templates/
│       │   └── kommunal-initiieren-0401.xml.template  # XML template
│       └── test-data/
│           └── kommunal-initiieren-0401-scenarios.yml # Test scenarios
└── Logic/
    └── KommunaleTest/
        └── KommunaleProcedureCreatorTest.php   # Integration tests
```

## Migration from Static XML

The factory system replaces static XML files:

**Before:**
```php
$xml = file_get_contents(AddonPath::getRootPath($filePath));
```

**After:**
```php
$xml = $this->xmlFactory->createXML($scenarioName, true);
```

This migration provides:
- Better test coverage with multiple scenarios
- Easier maintenance of test data
- Consistent namespace usage
- Support for both valid and invalid test cases

## Troubleshooting

### Common Issues

1. **Template Not Found**
   - Check that `AddonPath::getRootPath()` points to the correct directory
   - Verify template file exists at expected path

2. **Placeholder Not Replaced**
   - Check placeholder format uses double braces: `{{PLACEHOLDER}}`
   - Verify placeholder key exists in scenario configuration
   - Check for typos in placeholder names

3. **Conditional Section Not Working**
   - Verify conditional parameter is set to boolean value
   - Check that section tags match exactly: `{{#NAME}}` and `{{/NAME}}`

4. **Invalid XML Generated**
   - Run factory unit tests to verify template processing
   - Check that all required placeholders have values
   - Validate XML against XSD schema

### Debugging

Enable debugging in the factory for troubleshooting:

```php
// Add debug output to replacePlaceholders method
error_log("DEBUG: Replacing {$placeholder} with {$value}");
```

## Future Enhancements

Potential improvements to the factory system:

1. **Multiple Templates**: Support for different message types (402, 409, etc.)
2. **Schema Validation**: Automatic XSD validation of generated XML
3. **Custom Validators**: Pluggable validation for specific scenarios
4. **Performance Optimization**: Caching of parsed templates and scenarios
5. **IDE Support**: JSON schema for YAML configuration validation