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

## REST API Integration Testing

The factory system enables comprehensive integration testing of XBeteiligung REST API endpoints. The `XBeteiligungRestApiIntegrationTest` class demonstrates end-to-end testing capabilities.

### Integration Test Features

```php
use DemosEurope\DemosplanAddon\XBeteiligung\Tests\Integration\XBeteiligungRestApiIntegrationTest;

// The integration test class provides:
// - Real HTTP requests to running application
// - Authentication testing with proper tokens
// - Validation of XBeteiligung protocol responses
// - Dynamic test data preventing conflicts
// - Comprehensive error scenario testing
```

### Test Configuration

The integration tests use actual application configuration:

```php
// Endpoint configuration
$this->baseUrl = 'http://diplanbau.dplan.local/app_dev.php';

// Authentication from parameters.yml
$this->authToken = 'some_api_token'; // matches addon_xbeteiligung_async_rest_authentication

// Factory initialization
$this->xmlFactory = new XBeteiligung401TestFactory(
    AddonPath::getRootPath(),
    $commonHelpers
);
```

### XBeteiligung Protocol Behavior

Important insights from integration testing:

1. **HTTP Status Codes**: XBeteiligung always returns 200, even for errors
2. **Success Responses**: Contain `kommunal.Initiieren.OK.0411` message type
3. **Error Responses**: Contain `kommunal.Initiieren.NOK.0421` message type
4. **Content Type**: Always `application/xml` regardless of request content type

### Test Scenarios Coverage

#### Valid Scenarios Tested
- **Minimal procedures**: Basic required fields only
- **Comprehensive procedures**: With public participation sections
- **Different organizations**: Stadt Quickborn, Büro
- **Different plan types**: Bebauungsplan, Flächennutzungsplan
- **With attachments**: Document uploads and metadata

#### Invalid Scenarios Tested
- **Unknown organizations**: Non-existent organization names
- **Empty required fields**: Missing organization names, plan names
- **Malformed data**: Invalid GeoJSON territory definitions

#### Authentication Scenarios
- **Valid authentication**: Using correct token from parameters.yml
- **Invalid authentication**: Wrong token values
- **Missing authentication**: No authorization header

### Running Integration Tests

```bash
# Run all integration tests
vendor/bin/phpunit tests/Integration/XBeteiligungRestApiIntegrationTest.php

# Run specific scenario
vendor/bin/phpunit tests/Integration/XBeteiligungRestApiIntegrationTest.php --filter "Quickborn minimal"

# Run only valid scenarios
vendor/bin/phpunit tests/Integration/XBeteiligungRestApiIntegrationTest.php --filter "testCreateProcedureWithValidScenarios"

# Run only authentication tests
vendor/bin/phpunit tests/Integration/XBeteiligungRestApiIntegrationTest.php --filter "Authentication"
```

### Test Environment Requirements

1. **Running Application**: Development server must be accessible
2. **Addon Configuration**: Proper authentication token in parameters.yml
3. **Database Access**: For organization lookup and procedure creation
4. **Network Access**: HTTP client can reach application endpoints

### Example Test Flow

```php
public function testCreateProcedureWithValidScenarios(string $scenarioName): void
{
    // 1. Generate dynamic XML from scenario
    $xml = $this->xmlFactory->createXML($scenarioName, true);
    
    // 2. Send HTTP POST request
    $response = $this->sendCreateProcedureRequest($xml);
    
    // 3. Validate response
    $this->assertEquals(200, $response->getStatusCode());
    $this->assertStringContainsString('kommunal.Initiieren.OK.0411', $responseContent);
    
    // 4. Verify scenario-specific behavior
    $scenarioInfo = $this->xmlFactory->getScenarioInfo($scenarioName, true);
    // ... additional validations
}
```

### Benefits of Integration Testing

✅ **End-to-End Validation**: Tests complete request/response cycle  
✅ **Real Environment**: Uses actual application configuration  
✅ **Authentication Verification**: Validates token-based security  
✅ **Protocol Compliance**: Ensures XBeteiligung standard adherence  
✅ **Dynamic Data**: Prevents test conflicts with unique identifiers  
✅ **Scenario Coverage**: Tests all factory scenarios automatically  

### Debugging Integration Tests

When tests fail, check:

1. **Application Status**: Is development server running?
2. **Authentication**: Does token match parameters.yml?
3. **Network Access**: Can test reach application URL?
4. **Database State**: Are test organizations available?
5. **XML Validation**: Are all placeholders properly replaced?

### Integration vs Unit Testing

| Aspect | Unit Tests | Integration Tests |
|--------|------------|-------------------|
| **Scope** | Factory logic only | Full HTTP request cycle |
| **Dependencies** | Mocked services | Real application services |
| **Speed** | Fast (milliseconds) | Slower (seconds) |
| **Environment** | Test-only | Development server |
| **Purpose** | Code correctness | System integration |

### Extending Integration Tests

To add new integration test scenarios:

1. **Add YAML Scenario**: Define new test case in scenarios file
2. **Update Test Data Provider**: Include scenario in getValidScenarios()
3. **Add Specific Assertions**: Test scenario-specific behavior
4. **Document Expectations**: Note any special validation requirements

```php
// Example: Adding new organization test
public static function getValidScenarios(): array
{
    return [
        'Stadt Quickborn minimal' => ['quickborn_minimal'],
        'New Organization Test' => ['new_org_scenario'], // Add here
        // ... existing scenarios
    ];
}
```

### Performance Considerations

- **Test Duration**: Integration tests are slower than unit tests
- **Network Timeouts**: Configure appropriate HTTP timeouts
- **Database Impact**: Tests create real database records
- **Parallel Execution**: Be cautious with concurrent test runs
- **Cleanup**: Consider test data cleanup strategies

### Best Practices

1. **Isolation**: Each test should be independent
2. **Cleanup**: Clean up created test data when possible
3. **Meaningful Names**: Use descriptive scenario names
4. **Error Messages**: Provide clear failure descriptions
5. **Documentation**: Keep integration test docs updated

## Future Enhancements

Potential improvements to the factory system:

### Core Factory Enhancements
1. **Multiple Templates**: Support for different message types (402, 409, etc.)
2. **Schema Validation**: Automatic XSD validation of generated XML
3. **Custom Validators**: Pluggable validation for specific scenarios
4. **Performance Optimization**: Caching of parsed templates and scenarios
5. **IDE Support**: JSON schema for YAML configuration validation

### Integration Testing Enhancements
1. **Test Data Cleanup**: Automatic cleanup of created procedures
2. **Database Fixtures**: Pre-configured test organizations and users
3. **Response Validation**: Automatic validation against XBeteiligung XSD schemas
4. **Performance Testing**: Load testing with factory-generated data
5. **Multi-Environment**: Support for testing across different environments
6. **Async Testing**: Support for testing asynchronous message processing
7. **Monitoring Integration**: Integration with application monitoring for test insights