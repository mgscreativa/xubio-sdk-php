# Xubio SDK Api Integration Library

* [Install](#install)
* [Specific methods](#specific-methods)

<a name="install"></a>
## Install

### With Composer

From command line

```
composer require xubio/sdk:0.1.0
```

As a dependency in your project's composer.json

```json
{
    "require": {
        "xubio/sdk": "0.1.0"
    }
}
```

### By downloading

1. Clone/download this repository
2. Copy `lib` folder to your project's desired folder.

<a name="specific-methods"></a>
## Specific methods

### Return Values
Api returns all values as **array**

### Configure your credentials

* Get your **CLIENT_ID** and **SECRET_ID** from Xubio

```php
require_once ('lib/xubio.php');

try {
    $xb = new XubioApi('CLIENT_ID', 'SECRET_ID');
} catch (Exception $e) {
    echo $e->getMessage() . ' ' . $e->getCode();
}
```

### Methods

#### Get Client

```php

$client = $xb->get_client($id);

print_r($client);
```

#### Create Client

```php
$client = array(
    "nombre" => 'Juan Perez',
    "identificacionTributaria" => array(
        'nombre' => 'CUIT',
        'codigo' => 'CUIT',
        'id' => '9',
    ),
    "categoriaFiscal" => array(
        'nombre' => 'Responsable Inscripto',
        'codigo' => 'RI',
        'id' => '1',
    ),
    "provincia" => array(
        'nombre' => 'Ciudad Autónoma de Buenos Aires',
        'codigo' => 'CIUDAD_AUTONOMA_DE_BUENOS_AIRES',
        'id' => '43',
    ),
    "direccion" => 'dirección 123',
    "email" => 'test@test.com',
    "telefono" => '1122334455',
    "razonSocial" => 'Empresa Test',
    "codigoPostal" => '1234',
    "cuentaCompra_id" => array (
        "nombre" => "Proveedores",
        "codigo" => "PROVEEDORES",
        "id" => -7,
    ),
    "cuentaVenta_id" => array (
        "nombre" => "Deudores por Venta",
        "codigo" => "DEUDORES_POR_VENTA",
        "id" => -3,
    ),
    "pais" => array (
        "nombre" => "Argentina",
        "codigo" => "ARGENTINA",
        "id" => 1,
    ),
    "localidad" => array (
        "nombre" => 'Floresta',
        "codigo" => 'FLORESTA',
        "id" => '43',
    ),
    "descripcion" => "cLIENTE CREADO DESE API Xubio",
    "CUIT" => '30144555774',
);

$result = $xb->create_client($client);

print_r($result);
```

#### Get Products

```php
$productos = $xb->get_product();

print_r($productos);
```

#### Create Invoice

```php
$invoiceData = array(
    "circuitoContable" => array (
        "ID" => -2,
    ),
    "descripcion" => "Invoide description",
    "fecha" => date("Y-m-d\Z", time()),
    "moneda" => array (
        "ID" => -2,
    ),
    "condicionDePago" => 7,
    "cotizacion" => 1,
    "cotizacionListaDePrecio" => 1,
    "deposito" => array (
        "ID" => -2,
    ),
    "fechaVto" => date("Y-m-d\Z", time()),
    "cliente" => array (
        "ID" => "XXXX",
    ),
    "facturaNoExportacion" => false,
    "porcentajeComision" => 0,
    "puntoVenta" => array (
        "ID" => "XXXX",
    ),
    "transaccionProductoItems" => array (
        array (
            "cantidad" => 3,
            "precio" => 160,
            "producto" => array (
                "ID" => "XXXX",
            ),
        ),
        array (
            "cantidad" => 6,
            "precio" => 360,
            "producto" => array (
                "ID" => "XXXX",
            ),
        ),
        array (
            "cantidad" => 1,
            "precio" => 60,
            "producto" => array (
                "ID" => "XXXX",
            ),
        ),
    ),
    "tipo" => 1,   
);

$result = $xb->create_invoice($invoiceData);

print_r($result);
