# ha-rest-api-client

[![Latest Version on Packagist](https://img.shields.io/packagist/v/indexzer0/ha-rest-api-client.svg?style=flat-square)](https://packagist.org/packages/indexzer0/ha-rest-api-client)
[![Tests](https://img.shields.io/github/actions/workflow/status/indexzer0/ha-rest-api-client/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/indexzer0/ha-rest-api-client/actions/workflows/run-tests.yml)
[![codecov](https://codecov.io/gh/IndexZer0/ha-rest-api-client/graph/badge.svg?token=JMF8UG8S4Y)](https://codecov.io/gh/IndexZer0/ha-rest-api-client)
[![Total Downloads](https://img.shields.io/packagist/dt/indexzer0/ha-rest-api-client.svg?style=flat-square)](https://packagist.org/packages/indexzer0/ha-rest-api-client)

Simple client for accessing HomeAssistant Rest API.

## Requirements

- PHP Version >= 8.2
- A PSR-18 compatible http client.
- A PSR-17 compatible factory.

## Installation

You can install the package via composer:

```bash
composer require indexzer0/ha-rest-api-client
```

This library does not have a dependency on Guzzle or any other library that sends HTTP requests. This packages uses the awesome HTTPlug to achieve the decoupling. We want you to choose what library to use for sending HTTP requests. Consult this list of packages that support [php-http/client-implementation](https://packagist.org/providers/php-http/client-implementation) to find clients to use. For more information about virtual packages please refer to [HTTPlug](https://docs.php-http.org/en/latest/httplug/users.html).

Install your choice of http client.

Example:
```bash
composer require guzzlehttp/guzzle
composer require guzzlehttp/psr7
```

## Prerequisites

- Check out the [Home Assistant Rest Api docs](https://developers.home-assistant.io/docs/api/rest/).
  - Add the [API integration](https://www.home-assistant.io/integrations/api/) to your `configuration.yaml`.
  - Create a `Long-Lived Access Token` in your profile.

## Usage

This package provides two clients. `HaRestApiClient` and `HaWebhookClient`.

### Basic (Http Client Auto Discovery)
```php
/*
 * ---------------------------
 * HaRestApiClient
 * ---------------------------
 */
$restApiClient = new \IndexZer0\HaRestApiClient\HaRestApiClient(
    'token',
    'http://localhost:8123/api/'
);
$restApiClient->status(); // ['message' => 'API running.']

/*
 * ---------------------------
 * HaWebhookClient
 * ---------------------------
 */
$webhookClient = new \IndexZer0\HaRestApiClient\HaWebhookClient(
    'http://localhost:8123/api/'
);
$webhookClient->send('GET', 'webhook_id'); // ['response' => '']
```

### Custom Http Client (optional)

The client needs to know what library you are using to send HTTP messages. You could provide an instance of a PSR-18 compatible http client and PSR-17 compatible factory, or you could fallback on auto discovery (basic example above). Below is an example on where you provide a Guzzle7 instance.

```php
/*
 * ---------------------------
 * HaRestApiClient
 * ---------------------------
 */
$restApiClient = new \IndexZer0\HaRestApiClient\HaRestApiClient(
    'token',
    'http://localhost:8123/api/',
    new \IndexZer0\HaRestApiClient\HttpClient\Builder(
        new \GuzzleHttp\Client(),
        new \GuzzleHttp\Psr7\HttpFactory(),
        new \GuzzleHttp\Psr7\HttpFactory(),
        new \GuzzleHttp\Psr7\HttpFactory(),
    )
);
$restApiClient->status(); // ['message' => 'API running.']

/*
 * ---------------------------
 * HaWebhookClient
 * ---------------------------
 */
$webhookClient = new \IndexZer0\HaRestApiClient\HaWebhookClient(
    'http://localhost:8123/api/',
    new \IndexZer0\HaRestApiClient\HttpClient\Builder(
        new \GuzzleHttp\Client(),
        new \GuzzleHttp\Psr7\HttpFactory(),
        new \GuzzleHttp\Psr7\HttpFactory(),
        new \GuzzleHttp\Psr7\HttpFactory(),
    )
);
$webhookClient->send('GET', 'webhook_id'); // ['response' => '']
```

### HaRestApiClient - Available Methods

```php
$restApiClient = new \IndexZer0\HaRestApiClient\HaRestApiClient(
    'token',
    'http://localhost:8123/api/'
);
$restApiClient->status();
$restApiClient->config();
$restApiClient->events();
$restApiClient->services();
$restApiClient->history(['light.bedroom_ceiling']);
$restApiClient->logbook();
$restApiClient->states();
$restApiClient->state('light.bedroom_ceiling');
$restApiClient->errorLog();
$restApiClient->calendars();
$restApiClient->calendarEvents('calendar.birthdays');
$restApiClient->updateState('light.bedroom_ceiling', 'on');
$restApiClient->fireEvent('script_started', [
    'name'      => 'Turn All Lights Off',
    'entity_id' => 'script.turn_all_lights_off'
]);
$restApiClient->callService('light', 'turn_on', [
    'entity_id' => 'light.bedroom_ceiling'
]);
$restApiClient->renderTemplate("The bedroom ceiling light is {{ states('light.bedroom_ceiling') }}.");
$restApiClient->checkConfig();
$restApiClient->handleIntent([
    'name' => 'SetTimer',
    'data' => [
        'seconds' => '30',
    ]
]);
```

### HaWebhookClient - Available Methods

```php
$webhookClient = new \IndexZer0\HaRestApiClient\HaWebhookClient(
    'http://localhost:8123/api/'
);

/*
 * ---------------------------
 * GET request example
 * ---------------------------
 */
$webhookClient->send(
    method: 'GET',
    webhookId: 'webhook_id',
    queryParams: ['query' => 'param'],
);

/*
 * ---------------------------
 * POST request example - with json body
 * ---------------------------
 */
$webhookClient->send(
    method: 'POST',
    webhookId: 'webhook_id',
    payloadType: 'json',
    data: ['json' => 'data']
);

/*
 * ---------------------------
 * POST request example - with form params body
 * ---------------------------
 */
$webhookClient->send(
    method: 'POST',
    webhookId: 'webhook_id',
    payloadType: 'form_params',
    data: ['form' => 'param']
);
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

- Currently accepting PR for```$client->camera();``` as I don't have a camera entity to develop against.

Please see [CONTRIBUTING](https://github.com/spatie/.github/blob/main/CONTRIBUTING.md) for details.

## Credits

- [IndexZer0](https://github.com/IndexZer0)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
