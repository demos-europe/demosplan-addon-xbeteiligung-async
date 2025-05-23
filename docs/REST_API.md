# XBeteiligung REST API Documentation

## Overview

This document describes the REST API endpoint that enables processing XBeteiligung messages via HTTP requests instead of using the RabbitMQ message broker. The endpoint accepts the same payload structure that was previously sent via RabbitMQ and returns XML responses.

## Authentication

All requests to the API must include a valid authentication token in the custom **X-Addon-XBeteiligung-Authorization** header:

```
X-Addon-XBeteiligung-Authorization: Bearer your-token-here
```

This custom header is used specifically for XBeteiligung addon authentication to avoid interference with the core application's authentication mechanisms.

The token value must match the `addon_xbeteiligung_async_xbeteiligung_api_token` parameter value configured in the application parameters.

## Endpoint

**POST** `/addon/xbeteiligung/procedure/create`

This endpoint processes XBeteiligung messages for procedure creation and returns responses in XML format.

### Request Format

The request body should contain the raw XML message content directly. No JSON wrapping is required.

For example:
```xml
<xbeteiligung:planung2Beteiligung.BeteiligungKommunalNeu.0401>
    <!-- XML content -->
</xbeteiligung:planung2Beteiligung.BeteiligungKommunalNeu.0401>
```

The API will automatically detect the message type from the XML content.

### Response Format

The response will be an XML string with a `Content-Type: application/xml` header.

### Error Handling

The API returns the following HTTP status codes:

- `200 OK`: The request was successful
- `400 Bad Request`: The request format is invalid, or the message cannot be processed
- `401 Unauthorized`: Invalid or missing authentication token
- `500 Internal Server Error`: An error occurred while processing the request

Error responses include a message explaining the error.

## Examples

### Example Request

```
POST /addon/xbeteiligung/procedure/create
Content-Type: application/xml
X-Addon-XBeteiligung-Authorization: Bearer your-token-here

<xbeteiligung:planung2Beteiligung.BeteiligungKommunalNeu.0401>
    <!-- XML content here -->
</xbeteiligung:planung2Beteiligung.BeteiligungKommunalNeu.0401>
```

### Example Response

```
HTTP/1.1 200 OK
Content-Type: application/xml

<xbeteiligung:beteiligung2Planung.BeteiligungKommunalNeuOK.0411>...</xbeteiligung:beteiligung2Planung.BeteiligungKommunalNeuOK.0411>
```

## Comparison with RabbitMQ Implementation

This REST API endpoint processes messages with the same business logic as the RabbitMQ implementation, but through an HTTP interface instead of a message queue. The key advantages include:

1. Simpler integration for systems that don't use RabbitMQ
2. Synchronous request-response pattern
3. Immediate response without polling

The authorization mechanism and message format remain consistent with the existing implementation.

## Supported Message Types

The API supports the same message types as the RabbitMQ implementation:

- `kommunal.Initiieren.0401`: Create new kommunale procedure
- Other message types as they are added to the XBeteiligungService

## Implementation Details

The REST API controller:
1. Receives the HTTP request
2. Validates the authentication token
3. Parses the JSON request body
4. Delegates processing to the XBeteiligungService (the same service used by the RabbitMQ implementation)
5. Returns the XML response
