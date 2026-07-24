<?php

require_once("php/funciones.php");

iniciarSesionSegura();

/* ==============================
    SI YA INICIÓ SESIÓN
================================ */

if (isset($_SESSION["usuario"])) {

    header("Location: index.php");
    exit();

}

?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Mi Cuenta | TechStore</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
        <link rel="stylesheet" href="css/estilos.css">
    </head>
    <body>
        <header>
            <h1>💻 TechStore</h1>
            <p>Programación Web II</p>
        </header>
        <section style="max-width:450px;">
            <h2>Mi Cuenta</h2>
            <p>Elige una opción para continuar.</p>
            <br>
            <a href="login.php"><button><i class="fa-solid fa-right-to-bracket"></i> Ya tengo cuenta, Iniciar Sesión</button></a>
            <br><br>
            <a href="registro.php"><button><i class="fa-solid fa-user-plus"></i> Soy nuevo, Crear Cuenta</button></a>
            <br><br>
            <a href="index.php">Volver al Inicio</a>
        </section>
        <footer>
            <hr>
            <p>© 2026 TechStore</p>
        </footer>
    </body>
</html>