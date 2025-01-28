
# **Carrito de Compras**

Este proyecto implementa un sistema de carrito de compras con funcionalidades para agregar productos, actualizar cantidades, eliminar productos, obtener el total de productos y confirmar compras. Está diseñado utilizando PHP, PDO para la conexión con MySQL, PHPUnit para pruebas unitarias y PHPStan para garantizar la calidad del código.

---
---

## **Requisitos**

- PHP >= 8.0 con las extensiones:
  - `pdo_mysql`
- MySQL >= 5.7
- Composer
- Docker (opcional para desarrollo local)
- PHPUnit >= 9.0
- PHPStan >= 2.1

---

## **Instalación**

1. Clona el repositorio:
   ```bash
   git clone https://github.com/ruthbri/carrito-compras.git
   cd carrito-compras
   ```

2. Instala las dependencias usando Composer:
   ```bash
   composer install
   ```

3. Costruye el contenedor:
   ```
   docker-compose build;
   docker-compose up -d
   ```


---

## **Análisis del Código con PHPStan**

Este proyecto incluye configuración para PHPStan, una herramienta que realiza análisis estático del código para encontrar errores antes de la ejecución.
```bash
vendor/bin/phpstan analyse
```


---

## **Ejecución de Pruebas**

Este proyecto incluye pruebas unitarias implementadas con PHPUnit. Para ejecutarlas, usa el siguiente comando:
```bash
vendor/bin/phpunit --testdox
```

---

## **Endpoints de la API**

### **Base URL**: `http://localhost:8080`

| Método | Endpoint                       | Descripción                            |
|--------|--------------------------------|----------------------------------------|
| POST   | `/cart/products`               | Agregar un producto al carrito.        |
| PUT    | `/cart/products/{product_id}`  | Actualizar la cantidad de un producto. |
| DELETE | `/cart/products/{product_id}`  | Eliminar un producto del carrito.      |
| GET    | `/cart/products`               | Obtener todos los productos.           |
| GET    | `/cart/products/total`         | Obtener el total de productos.         |
| POST   | `/cart/checkout`               | Confirmar la compra del carrito.       |

---

## **Estructura del Proyecto**

```
src/
├── Application/
│   ├── DTO/
│   │   └── CartItemDTO.php
│   ├── Services/
│       └── CartService.php
├── Domain/
│   ├── Cart/
│   │   ├── Cart.php
│   │   ├── CartItem.php
│   │   └── CartRepository.php
│   └── Exceptions/
│       └── CartException.php
├── Infrastructure/
│   ├── HTTP/
│   │   └── Controllers/
│   │       └── CartController.php
│   └── Persistence/
│       └── MySQLCartRepository.php
└── Tests/
    ├── Application/
    │   └── CartServiceTest.php
    ├── Domain/
    │   └── CartTest.php
bootstrap.php
```

---
