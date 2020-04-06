---
id: sdk-php
title: Introduction
---

In this documentation we will explain the use of the PHP SDK, for the documentation on the JavaScript SDK use the following [link.](https://github.com/waiap/javascript-sdk/blob/master/README_EN.md)  

The PHP SDK for Payment Wall is focused on minimizing the effort required to develop and integrate the Payment Wall payment method into any type of integration by abstracting the user from the internal logic

## Requirements

For the use of the library you need PHP v5.6 or higher and the <a href="https://www.php.net/manual/es/curl.setup.php" target="_blank">libcurl</a> library.

The library can be required using composer:

```bash 
composer require waiap/pwall-sdk
```

## Classes

To begin with we will review the different classes used by the SDK. These will help the developer build a request, proxy the servers and process a response for the Payment Wall that is in the frontend fo the application.

### Class \PWall\Request

For information about the class that builds the requests use the following [link.](docs/sdk-php-request_EN.md) 

### Class \PWall\Client

For information about the class used to send requests to Waiap use the following [link.](docs/sdk-php-client_EN.md) 

### Class \PWall\Response

For information about the class that processes the answers use the following [link.](docs/sdk-php-response_EN.md) 
