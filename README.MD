---
id: sdk-php
title: Introducción
---

En esta documentación vamos a explicar el uso del SDK de PHP, para la documentación sobre el SDK de Javascript ir al siguiente [enlace.](https://github.com/waiap/javascript-sdk/blob/master/README.md)  

El SDK de PHP para Payment Wall está enfocado en minimizar el esfuerzo requerido para desarrollar e integrar el método de pago Payment Wall en cualquier tipo de integración abstrayendo al usuario de la lógica interna necesaria para el funcionamiento.

## Requisitos

Para el uso de la librería se requiere la versión de PHP 5.6 o superior y la librería <a href="https://www.php.net/manual/es/curl.setup.php" target="_blank">libcurl.</a>

La librería puede ser requerida a través del uso de composer:

```bash 
composer require waiap/pwall-sdk
```

## Clases

Para empezar vamos a repasar las distintas clases usadas por el SDK. Estás ayudarán al desarrollador a construir una petición, hacer el proxy a los servidores y procesar una respuesta para el Payment Wall que se encuentra en el frontend de la aplicación.

### Clase \PWall\Request

Para información sobre la clase que construye las peticiones utilizar el siguiente [enlace.](sdk-php-request.md) 

### Clase \PWall\Client

Para información sobre la clase que se utiliza para enviar las peticiones a Sipay utilizar el siguiente [enlace.](sdk-php-client.md) 

### Clase \PWall\Response

Para información sobre la clase que procesa las respuestas utilizar el siguiente [enlace.](sdk-php-response.md) 
