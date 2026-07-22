<?php
/* ===================================================
   TechStore
   Programación Web II
   Archivo: funciones.php
=================================================== */

/* ===========================
   CONEXIÓN A LA BASE DE DATOS
=========================== */

function conectarDB()
{
    static $pdo = null;
    if ($pdo === null) {
        $pdo = new PDO(
            "mysql:host=localhost;
            dbname=tienda;charset=utf8", 
            "root", 
            "",
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,]
        );  
    }
    return $pdo;
}

/* ===========================
   INICIAR SESIÓN SEGURA
=========================== */

function iniciarSesionSegura()
{
    if (session_status() === PHP_SESSION_NONE) {

        session_set_cookie_params([
            "lifetime" => 0,
            "path" => "/",
            "httponly" => true,
            "secure" => false,      // Cambiar a true cuando uses HTTPS
            "samesite" => "Strict"
        ]);

        session_start();
    }
}

/* ===========================
   REGENERAR ID DE SESIÓN
=========================== */

function regenerarSesion()
{
    if (!isset($_SESSION["regenerada"])) {

        session_regenerate_id(true);

        $_SESSION["regenerada"] = true;

    }
}

/* ===========================
   CONTROL DE INACTIVIDAD
=========================== */

function controlarTiempoSesion($minutos = 15)
{
    $tiempoMaximo = $minutos * 60;

    if (isset($_SESSION["ultimoAcceso"])) {

        if ((time() - $_SESSION["ultimoAcceso"]) > $tiempoMaximo) {

            session_unset();

            session_destroy();

            header("Location: login.php?error=La sesión expiró por inactividad.");

            exit();

        }

    }

    $_SESSION["ultimoAcceso"] = time();
}

/* ===========================
   LIMPIAR DATOS
=========================== */

function limpiarDato(string $dato): string
{
    return htmlspecialchars(trim($dato), ENT_QUOTES, "UTF-8");
}

/* ===========================
   VALIDAR CAMPOS
=========================== */

function validarCampos(array $campos)
{
    foreach ($campos as $campo) {

        if (empty(trim($campo))) {

            return false;

        }

    }

    return true;
}

/* ===========================
   FORMATEAR PRECIO
=========================== */

function formatoPrecio(string $precio): string
{
    return "$" . number_format($precio, 0, ",", ".");
}

/* ===========================
   CALCULAR TOTAL DEL CARRITO
=========================== */

function calcularTotalCarrito()
{
    $total = 0;

    if (!isset($_SESSION["carrito"])) {
        return 0;
    }

    foreach ($_SESSION["carrito"] as $producto) {

        $total += $producto["precio"] * $producto["cantidad"];

    }

    return $total;
}

/* ===========================
   CALCULAR CANTIDAD DE PRODUCTOS
=========================== */

function cantidadProductos()
{
    $cantidad = 0;

    if (!isset($_SESSION["carrito"])) {
        return 0;
    }

    foreach ($_SESSION["carrito"] as $producto) {

        $cantidad += $producto["cantidad"];

    }

    return $cantidad;
}

/* ===========================
   MENSAJE DE ÉXITO
=========================== */

function mensajeExito(string $mensaje)
{
    return "<p style='color:green;font-weight:bold;'>$mensaje</p>";
}

/* ===========================
   MENSAJE DE ERROR
=========================== */

function mensajeError(string $mensaje)
{
    return "<p style='color:red;font-weight:bold;'>$mensaje</p>";
}