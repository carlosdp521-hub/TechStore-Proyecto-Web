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
        <title>Login</title>
        <link rel="stylesheet" href="css/estilos.css">
        <link rel="stylesheet"href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    </head>
    <body>
        <div class="login-container">
            <div class="login-left">
                <h1>💻 TechStore</h1>
                <p>La mejor tecnología al mejor precio.</p>
            </div>
            <div class="login-right">
                <h2>Iniciar Sesión</h2>
                <?php if($error!=""): ?>
                <div class="alerta"><?= $error ?></div>
                <?php endif; ?>
                <form method="POST">
                    <div class="campo">
                        <i class="fa fa-user"></i>
                        <input type="text"name="usuario"placeholder="Usuario"required>
                    </div>
                    <div class="campo">
                        <i class="fa fa-lock"></i>
                        <input type="password"name="password"placeholder="Contraseña"required>
                    </div>
                    <button>Ingresar</button>
                </form>
                <p>¿No tienes cuenta?</p>
                <a href="registro.php"><button><i class="fa-solid fa-user-plus"></i> Crear Cuenta</button></a>
            </div>
        </div>
    </body>
</html>