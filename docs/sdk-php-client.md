---
id: sdk-php-client
title: Clase Client
---

La clase `\PWall\Client` se encarga de hacer las peticiones proxy tanto para el Payment Wall de la página de pago como el del administrador de la tienda. Se puede inicializar de dos maneras distintas, mediante un archivo de configuración o mediante parámetros.

## Configuración

### Mediante archivo de configuración

Este archivo utilizará la directiva de PHP define para definir las siguientes constantes:

- `PWALL_ENV`: Determina el entorno que se utilizará para realizar las peticiones. Cuenta con dos valores posibles, sandbox utilizado para realizar pruebas sin llegar a realizar cobros reales y live utilizado para realizar cobros reales en producción.
- `PWALL_KEY`: El parámetro key facilitado por Sipay.
- `PWALL_RESOURCE`: El parámetro resource facilitado por Sipay.
- `PWALL_SECRET`: El parámetro secret facilitado por Sipay.
- `PWALL_BACKEND_URL`: Establece la URL donde se está renderizando el Payment Wall, de manera que puedan realizarse redirreciones si el método de pago lo require.
- `PWALL_DEBUG_FILE`: Establece una ruta donde realizar un registro de las llamadas y respuestas recibidas por cliente. Este parámetro es opcional. 
- `PWALL_TIMEOUT`:  Establece el tiempo máximo de respuesta al realizar una petición. Este parámetro es opcional.

Utilizando este archivo de configuración podremos inicializar el cliente de la siguiente manera:

``` php
$client = new \PWall\Client();
```

Por el contrario si deseamos utilizar la forma de inicialización mediante parámetros deberemos usar los métodos de la clase.

### Métodos

Los métodos utilizados para la inicialización del cliente son los siguientes:

- `setEnvironment(String $enviroment)`: Equivalente a la constante `PWALL_ENV`
- `setKey(String $key)`: Equivalente a la constante `PWALL_KEY`
- `setResource(String $resource)`: Equivalente a la constante `PWALL_RESOURCE`
- `setSecret(String $secret)`: Equivalente a la constante `PWALL_SECRET`
- `setBackendUrl(String $url)`: Equivalente a la constante `PWALL_BACKEND_URL`
- `setDebugFile(String $path)`: Equivalente a la constante `PWALL_DEBUG_FILE`
- `setTimeout(int $timeout)`: Equivalente a la constante `PWALL_TIMEOUT`

Un ejemplo de utilización de los métodos para la inicialización sería el siguiente:

``` php
$client = new \PWall\Client();
$client->setEnvironment("sandbox");
$client->setKey("key");
$client->setResource("resource");
$client->setSecret("secret");
$client->setBackendUrl("https://mi-sitio-web/checkout");
$client->setDebugFile("path_del_fichero_log");
$client->setTimeout(30000);
```

## Uso para enviar petición

Por último, el método encargado de realizar las llamadas proxy para las distintas comunicaciones será el método `proxy`. Este método devuelve una clase `\PWall\Response` de la que se habla en el siguiente apartado.

Para realizar una petición proxy de cualquier tipo bastará con construir una petición con la clase `\PWall\Request` y pasarla al método proxy de `\PWall\Client`. Un ejemplo de utilización sería el siguiente:

``` php
$server_request = file_get_contents('php://input');
$client = new \PWall\Client(); // Utilizando la inicialización por archivo de configuración
$request = new \PWall\Request($server_request, true); // Petición de tipo administrador

$response = $client->proxy($request);
```