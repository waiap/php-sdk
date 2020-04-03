---
id: sdk-php-request
title: Clase Request
---

La clase `\PWall\Request` se encarga de encapsular la llamada realizada por parte del Payment Wall en el frontend de la aplicación para que pueda ser enviada a la llamada proxy que hará `\PWall\Client`. 

Esta clase recibe dos parámetros en el constructor:

- request: petición realizada por el Payment Wall en formato `string `.
- isBackOffice: `boolean` para realizar la petición en modo backoffice. Si la petición que estamos recibiendo se utiliza para mostrar el Payment Wall en el administrador de la tienda podemos pasar este parámetro a true de manera que no habrá que llamar al resto de métodos. Por defecto este parámetro se pasa a `false` para las peticiones de venta. 

Por ejemplo, para realizar una petición que se usará en el administrador de la tienda bastará con construir la petición de la siguiente manera
```php
$frontend_request = file_get_contents('php://input'); //obtener de acuerdo los estándares del framework utilizado 
$request = new \PWall\Request($frontend_request, true);
```

Para utilizar la clase para una petición de venta, normalmente realizada en la página de pago de la tienda, se deben utilizar los métodos disponibles en la clase.

## Métodos

La llamada a estos métodos es **obligatoria** en el caso de encontrarnos en la página de pago de la tienda.

- `setOrderId(String $order_id)`: Utilizado para establecer el identificador de pedido con un máximo de 12 carácteres. Debe ser único para cada pedido. 
- `setAmount(float $amount)`: Utilizado para establecer la cantidad a pagar del pedido.
- `setCurrency(String $currency)`: Utilizado para establecer la moneda de la transacción. Debe seguir el estándar **ISO 4217**.
- `setGroupId(int $customer_id)`: Utilizado para establecer el identificador del cliente. Para clientes no registrados se debe pasar 0.

Un ejemplo de uso de estos métodos para un pago sería el siguiente:
```php
$request = new \PWall\Request($server_request, false);
$request->setOrderId("1");
$request->setAmount(1.00);
$request->setCurrency("EUR");
$request->setGroupId(0);
```