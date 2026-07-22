<?php
/* ===================================================
   TechStore
   Programación Web II
   Archivo: funciones.php
=================================================== */

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

function limpiarDato($dato)
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

function formatoPrecio($precio)
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

function mensajeExito($mensaje)
{
    return "<p style='color:green;font-weight:bold;'>$mensaje</p>";
}

/* ===========================
   MENSAJE DE ERROR
=========================== */

function mensajeError($mensaje)
{
    return "<p style='color:red;font-weight:bold;'>$mensaje</p>";
}