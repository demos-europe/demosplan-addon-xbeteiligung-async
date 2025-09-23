# XML Validation Debug

This file contains the debug version of the `isValidMessage` method for troubleshooting XML validation issues.

## Debug Version

```php
public function isValidMessage(
    string $message,
    bool $verboseDebug = false,
    string $path = '',
    string $messageClass = ''
): bool
{
    if ('' === $path) {
        $path = AddonPath::getRootPath('Resources/xsd/');
    }

    printf("DEBUG: Starting validation with messageClass '%s'\n", $messageClass);

    $xsdFile = $this->resolveXsdFilePath($messageClass);
    printf("DEBUG: Resolved XSD file: '%s'\n", $xsdFile);

    $fullPath = $path . $xsdFile;
    printf("DEBUG: Full XSD path: '%s'\n", $fullPath);
    if (!file_exists($fullPath)) {
        printf("ERROR: XSD file does not exist at path: '%s'\n", $fullPath);
        return false;
    }

    $document = new DOMDocument();
    // Suppress errors and allow internal error handling
    libxml_use_internal_errors(true);

    printf("DEBUG: About to load XML message of length %d\n", strlen($message));
    printf("DEBUG: First 100 chars of message: %s\n", substr($message, 0, 100));

    if (!$document->loadXML($message)) {
        $errors = libxml_get_errors();
        printf("ERROR: Failed to load XML. Errors:\n");
        foreach ($errors as $i => $error) {
            printf("  Error %d: [Line %d] %s\n",
                $i + 1,
                $error->line,
                $error->message
            );
        }

        $this->logger->error('Failed to load XML, probably invalid',
            [
                'message' => $message,
                'errors' => $errors
            ]
        );

        return false;
    }

    printf("DEBUG: XML loaded successfully, validating against schema\n");

    $isValid = $document->schemaValidate($fullPath);
    if (!$isValid) {
        $errors = libxml_get_errors();
        printf("ERROR: XML validation failed. Schema validation errors:\n");
        foreach ($errors as $i => $error) {
            printf("  Validation error %d: [Line %d] %s\n",
                $i + 1,
                $error->line,
                $error->message
            );
        }

        foreach ($errors as $error) {
            $this->logger->warning('Invalid XML message', [$error]);
            if ($verboseDebug) {
                $this->logger->debug('XML validation error', ['error' => $error]);
            }
        }
        libxml_clear_errors();

        return false;
    }

    printf("DEBUG: XML validated successfully against schema '%s'\n", $xsdFile);
    return true;
}
```

## Production Version (Clean)

The production version should remove all `printf` debug statements while keeping the error logging functionality.