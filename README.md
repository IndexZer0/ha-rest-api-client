# Simple client for accessing home assistant rest api.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/indexzer0/ha-rest-api-client.svg?style=flat-square)](https://packagist.org/packages/indexzer0/ha-rest-api-client)
[![Tests](https://img.shields.io/github/actions/workflow/status/indexzer0/ha-rest-api-client/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/indexzer0/ha-rest-api-client/actions/workflows/run-tests.yml)
[![codecov](https://codecov.io/gh/IndexZer0/ha-rest-api-client/graph/badge.svg?token=JMF8UG8S4Y)](https://codecov.io/gh/IndexZer0/ha-rest-api-client)
[![Total Downloads](https://img.shields.io/packagist/dt/indexzer0/ha-rest-api-client.svg?style=flat-square)](https://packagist.org/packages/indexzer0/ha-rest-api-client)

Simple client wrapper around GuzzleHttp for accessing HomeAssistant Rest API.

## Installation

You can install the package via composer:

```bash
composer require indexzer0/ha-rest-api-client
```

## Usage

```php
$client = new \IndexZer0\HaRestApiClient();
echo $client->status()['message'];
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](https://github.com/spatie/.github/blob/main/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [IndexZer0](https://github.com/IndexZer0)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
