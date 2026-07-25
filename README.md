# 💻 TechStore - Proyecto Web II

Proyecto desarrollado para la asignatura **Programación Web II**, correspondiente a una aplicación web de comercio electrónico (E-Commerce) desarrollada en PHP y MySQL.

---

## 📌 Descripción

TechStore es una tienda virtual que permite a los usuarios registrarse, iniciar sesión, visualizar productos, agregar artículos al carrito de compras y realizar pedidos.

El proyecto implementa conceptos de desarrollo web, manejo de sesiones, acceso a bases de datos mediante PDO y control de usuarios.

---

## 🚀 Tecnologías utilizadas

- PHP 8.x
- MySQL / MariaDB
- HTML5
- CSS3
- JavaScript
- Font Awesome
- XAMPP
- Git
- GitHub

---

## 📁 Estructura del proyecto

```
TechStore/
│
├── css/
│   └── estilos.css
│
├── img/
│
├── php/
│   ├── conexion.php
│   ├── funciones.php
│   └── ...
│
├── carrito.php
├── index.php
├── login.php
├── logout.php
├── miCuenta.php
├── pedido.php
├── procesarPedido.php
├── productos.php
├── registro.php
└── README.md
```

---

## ⚙️ Funcionalidades

- Registro de usuarios
- Inicio y cierre de sesión
- Manejo seguro de sesiones
- Catálogo de productos
- Buscador de productos
- Carrito de compras
- Modificación de cantidades
- Eliminación de productos
- Vaciado del carrito
- Registro de pedidos
- Resumen de compra
- Integración con base de datos MySQL

---

## 🗄️ Base de datos

El proyecto utiliza una base de datos MySQL llamada:

```
techstore
```

Tablas principales:

- cliente
- producto
- compra

---

## 🔧 Instalación

### 1. Clonar el repositorio

```bash
git clone https://github.com/USUARIO/TechStore-Proyecto-Web.git
```

---

### 2. Copiar el proyecto

Copiar la carpeta dentro de:

```
xampp/htdocs/
```

---

### 3. Crear la Base de Datos

Desde phpMyAdmin:

```
techstore
```

Importar el archivo SQL del proyecto.

---

### 4. Configurar conexión

Editar el archivo:

```
php/conexion.php
```

Configurando:

```php
$host = "localhost";
$db = "techstore";
$user = "root";
$password = "";
```

---

### 5. Ejecutar

Abrir el navegador:

```
http://localhost/TechStore-Proyecto-Web/
```

---

## 👤 Usuarios

Los usuarios pueden:

- Registrarse
- Iniciar sesión
- Comprar productos
- Consultar su carrito

---

## 🛒 Flujo de compra

```
Registro

↓

Login

↓

Productos

↓

Carrito

↓

Pedido

↓

Procesar Compra

↓

Compra Exitosa
```

---

## 🔐 Seguridad implementada

- Uso de PDO
- Password Hash
- Password Verify
- Sesiones seguras
- Regeneración de ID de sesión
- Sanitización de datos
- Validación de formularios

---

## 📷 Capturas

Se recomienda agregar imágenes como:

- Página principal
- Login
- Registro
- Productos
- Carrito
- Pedido
- Compra Exitosa

---

## 📈 Mejoras futuras

- Integración con WebPay
- Historial de pedidos
- Panel de administración
- Gestión de stock
- Facturación PDF
- Confirmación por correo electrónico
- Recuperación de contraseña
- Perfil de usuario
- Reportes de ventas

---

## 👨‍💻 Autor

Carlos Di Piazza

Proyecto desarrollado para la asignatura **Programación Web II**.

---

## 📄 Licencia

Proyecto con fines exclusivamente académicos.
