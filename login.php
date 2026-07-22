<?php

require_once("php/funciones.php");

/* ==============================
    INICIAR SESIÓN SEGURA
================================ */

iniciarSesionSegura();

/* Si ya inició sesión */

if (isset($_SESSION["usuario"])) {

    header("Location: index.php");
    exit();

}

$error = "";

/* ==============================
    USUARIOS DE PRUEBA
================================ */

$usuarios = [

    "admin" => "1234",
    "carlos" => "5678"

];

/* ==============================
    VALIDAR LOGIN
================================ */

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $usuario = trim($_POST["usuario"]);
    $password = trim($_POST["password"]);

    if (empty($usuario) || empty($password)) {

        $error = "Debe ingresar usuario y contraseña.";

    } elseif (isset($usuarios[$usuario]) && $usuarios[$usuario] === $password) {

        session_regenerate_id(true);

        $_SESSION["usuario"] = htmlspecialchars($usuario, ENT_QUOTES, "UTF-8");

        $_SESSION["ultimoAcceso"] = time();

        $_SESSION["carrito"] = [];

        header("Location: index.php");

        exit();

    } else {

        $error = "Usuario o contraseña incorrectos.";

    }

}

?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Iniciar Sesión</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
        <link rel="stylesheet" href="css/estilos.css">
    </head>
    <body>
        <header>
            <h1>💻 TechStore</h1>
            <p>Programación Web II</p>
        </header>
        <section style="max-width:450px;">
            <h2>Inicio de Sesión</h2>
            <?php
            if($error!=""){
            ?>
            <p style="color:red;font-weight:bold;"><?php echo $error; ?></p>
            <?php
            }
            ?>
            <form method="POST">
                <label>Usuario</label>
                <input type="text" name="usuario" required>
                <br><br>
                <label>Contraseña</label>
                <input type="password" name="password" required>
                <br><br>
                <button type="submit">Ingresar</button>
            </form>
            <hr>
            /* Usuarios de prueba.
            En una aplicación real las credenciales
            se almacenarían en una base de datos
            utilizando contraseñas cifradas. */
            <h3>Usuarios disponibles</h3>
            <table>
                <tr>
                    <th>Usuario</th>
                    <th>Contraseña</th>
                </tr>
                <tr>
                    <td>admin</td>
                    <td>1234</td>
                </tr>
                <tr>
                    <td>carlos</td>
                    <td>5678</td>
                </tr>
            </table>
        </section>
        <footer>
            <hr>
            <p>© 2026 TechStore</p>
        </footer>
    </body>
</html>