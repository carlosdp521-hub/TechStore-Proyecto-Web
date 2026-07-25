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
        <title>Registro</title>
        <link rel="stylesheet"href="css/estilos.css">
        <link rel="stylesheet"href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    </head>
    <body>
        <div class="registro-container">
            <div class="registro-left">
                <h1>Crear Cuenta</h1>
                <p>Únete a TechStore.</p>
            </div>
            <div class="registro-right">
                <h2>Registro</h2>
                <?php if(!empty($mensaje)): ?>
                <div class="correcto">
                    <?= htmlspecialchars($mensaje) ?>
                </div>
                <?php endif; ?>
                <?php if(!empty($error)): ?>
                <div class="alerta">
                    <?= htmlspecialchars($error) ?>
                </div>
                <?php endif; ?>
                <form method="POST">
                    <input type="text" name="nombre" placeholder="Nombre completo" required>
                    <input type="email" name="email" placeholder="Correo electrónico" required> <br>
                    <input type="text" name="direccion" placeholder="Dirección">
                    <input type="text" name="telefono" placeholder="Teléfono"><br>
                    <input type="text" name="usuario" placeholder="Usuario" required>
                    <input type="password" name="password" placeholder="Contraseña" required><br>
                    <input type="password" name="passwordConfirmar" placeholder="Confirmar Contraseña" required>
                    <button><i class="fa-solid fa-user-plus"></i> Crear Cuenta</button>
                </form>
                <br>
                <a href="login.php"><button><i class="fa-solid fa-sign-in-alt"></i> Ya tengo una cuenta</button></a>
            </div>
        </div>
    </body>
</html>