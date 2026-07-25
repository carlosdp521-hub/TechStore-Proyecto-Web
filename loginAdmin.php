<?php

require_once("php/funciones.php");

iniciarSesionSegura();

/* Si ya inició sesión como admin */

if (isset($_SESSION["admin_id"])) {

    header("Location: admin/dashboard.php");
    exit();

}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $usuario = trim($_POST["usuario"] ?? "");
    $password = trim($_POST["password"] ?? "");

    if (empty($usuario) || empty($password)) {

        $error = "Debe ingresar usuario y contraseña.";

    } else {

        try {

            $stmt = conectarDB()->prepare("SELECT id_admin, usuario, password, nombre FROM administrador WHERE usuario = ?");
            $stmt->execute([$usuario]);
            $fila = $stmt->fetch();

            if ($fila && password_verify($password, $fila["password"])) {

                session_regenerate_id(true);
                $_SESSION["regenerada"] = true;
                $_SESSION["admin_id"] = $fila["id_admin"];
                $_SESSION["admin_nombre"] = htmlspecialchars($fila["nombre"], ENT_QUOTES, "UTF-8");

                header("Location: admin/dashboard.php");
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
        <title>Acceso Administrador | TechStore</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
        <link rel="stylesheet" href="css/estilos.css">
    </head>
    <body>
        <header>
            <h1>💻 TechStore</h1>
            <p>Panel de Administración</p>
        </header>
        <section style="max-width:450px;">
            <h2>Acceso Administrador</h2>
            <?php if ($error != "") { ?>
            <p style="color:red;font-weight:bold;"><?php echo $error; ?></p>
            <?php } ?>
            <form method="POST">
                <label>Usuario</label>
                <input type="text" name="usuario" placeholder="Usuario administrador" required>
                <br><br>
                <label>Contraseña</label>
                <input type="password" name="password" placeholder="Contraseña" required>
                <br><br>
                <button type="submit">Ingresar</button>
            </form>
            <br>
            <a href="index.php">Volver al sitio</a>
        </section>
        <footer>
            <hr>
            <p>© 2026 TechStore</p>
        </footer>
    </body>
</html>