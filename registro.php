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

$error = "";
$exito = "";

/* ==============================
    PROCESAR REGISTRO
================================ */

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $usuario = trim($_POST["usuario"]);
    $password = trim($_POST["password"]);
    $passwordConfirmar = trim($_POST["passwordConfirmar"]);
    $nombre = trim($_POST["nombre"]);
    $email = trim($_POST["email"]);
    $direccion = trim($_POST["direccion"]);
    $telefono = trim($_POST["telefono"]);

    if (empty($usuario) || empty($password) || empty($passwordConfirmar) || empty($nombre) || empty($email) || empty($direccion) || empty($telefono)) {

        $error = "Debe completar todos los campos.";

    } elseif ($password !== $passwordConfirmar) {

        $error = "Las contraseñas no coinciden.";

    } elseif (strlen($password) < 6) {

        $error = "La contraseña debe tener al menos 6 caracteres.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $error = "El correo electrónico no es válido.";

    } else {

        try {

            $pdo = conectarDB();

            /* Verificar si el usuario ya existe */

            $stmt = $pdo->prepare("SELECT id_cliente FROM cliente WHERE usuario = ?");
            $stmt->execute([$usuario]);

            if ($stmt->fetch()) {

                $error = "Ese nombre de usuario ya está registrado.";

            } else {

                $passwordHash = password_hash($password, PASSWORD_DEFAULT);

                $stmt = $pdo->prepare(
                    "INSERT INTO cliente (usuario, password, nombre, email, direccion, telefono, fecha_registro)
                     VALUES (?, ?, ?, ?, ?, ?, NOW())"
                );

                $stmt->execute([
                    limpiarDato($usuario),
                    $passwordHash,
                    limpiarDato($nombre),
                    limpiarDato($email),
                    limpiarDato($direccion),
                    limpiarDato($telefono)
                ]);

                $exito = "Cuenta creada correctamente. Ya puedes iniciar sesión.";

            }

        } catch (PDOException $e) {

            $error = "Ocurrió un error al registrar la cuenta. Intenta nuevamente.";

        }

    }

}

?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Crear Cuenta | TechStore</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
        <link rel="stylesheet" href="css/estilos.css">
    </head>
    <body>
        <header>
            <h1>💻 TechStore</h1>
            <p>Programación Web II</p>
        </header>
        <section style="max-width:450px;">
            <h2>Crear Cuenta</h2>
            <?php
            if ($error != "") {
            ?>
            <p style="color:red;font-weight:bold;"><?php echo $error; ?></p>
            <?php
            }
            if ($exito != "") {
            ?>
            <p style="color:green;font-weight:bold;"><?php echo $exito; ?></p>
            <?php
            }
            ?>
            <form method="POST">
                <label>Usuario</label>
                <input type="text" name="usuario" required value="<?php echo isset($_POST["usuario"]) ? htmlspecialchars($_POST["usuario"]) : ""; ?>">
                <br><br>

                <label>Nombre Completo</label>
                <input type="text" name="nombre" required value="<?php echo isset($_POST["nombre"]) ? htmlspecialchars($_POST["nombre"]) : ""; ?>">
                <br><br>

                <label>Correo Electrónico</label>
                <input type="email" name="email" required value="<?php echo isset($_POST["email"]) ? htmlspecialchars($_POST["email"]) : ""; ?>">
                <br><br>

                <label>Dirección</label>
                <input type="text" name="direccion" required value="<?php echo isset($_POST["direccion"]) ? htmlspecialchars($_POST["direccion"]) : ""; ?>">
                <br><br>

                <label>Teléfono</label>
                <input type="text" name="telefono" required value="<?php echo isset($_POST["telefono"]) ? htmlspecialchars($_POST["telefono"]) : ""; ?>">
                <br><br>

                <label>Contraseña</label>
                <input type="password" name="password" required minlength="6">
                <br><br>

                <label>Confirmar Contraseña</label>
                <input type="password" name="passwordConfirmar" required minlength="6">
                <br><br>

                <button type="submit">Crear Cuenta</button>
            </form>
            <a href="index.php"><button>Volver al Inicio</button></a>
            
        </section>
        <footer>
            <hr>
            <p>© 2026 TechStore</p>
        </footer>
    </body>
</html>