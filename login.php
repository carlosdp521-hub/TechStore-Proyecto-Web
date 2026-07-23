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
    VALIDAR LOGIN
================================ */

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $usuario = trim($_POST["usuario"]);
    $password = trim($_POST["password"]);

    if (empty($usuario) || empty($password)) {

        $error = "Debe ingresar usuario y contraseña.";

    } else {
        try{
            $stmt = conectarDB()->prepare("SELECT id_cliente, usuario, password FROM cliente WHERE usuario = ?");
            $stmt->execute([$usuario]);
            $fila = $stmt->fetch();
            if ($fila && password_verify($password, $fila["password"])) {
                session_regenerate_id(true); // Regenerar ID de sesión para mayor seguridad
                $_SESSION["usuario"] = htmlspecialchars($fila["usuario"], ENT_QUOTES, "UTF-8");
                $_SESSION["cliente_id"] = $fila["id_cliente"];
                $_SESSION["ultimo_acceso"] = time(); // Guardar el tiempo del último acceso
                $_SESSION["carrito"] = []; // Inicializar el carrito de compras

                header("Location: index.php");
                exit();

            } else {

                $error = "Usuario o contraseña incorrectos.";

            }

        } catch (PDOException $e) {

            $error = "Error al intentar iniciar sesión.";

        }
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
            <!-- Usuarios de prueba, solo para la demo -->
            <h3>Usuarios disponibles</h3>
            <table>
                <tr>
                    <th>Usuario</th>
                    <th>Contraseña</th>
                </tr>
                <tr>
                    <td>admin</td>
                    <td>123456</td>
                </tr>
                <!-- <tr>
                    <td>carlos</td>
                    <td>345678</td>
                </tr> -->
            </table>
            <a href="index.php"><button>Volver al Inicio</button></a>
        </section>
        <footer>
            <hr>
            <p>© 2026 TechStore</p>
        </footer>
    </body>
</html>