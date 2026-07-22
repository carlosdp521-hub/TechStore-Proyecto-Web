<?php

require_once("php/funciones.php");

iniciarSesionSegura();

regenerarSesion();

controlarTiempoSesion();

if(!isset($_SESSION["usuario"])){

header("Location:login.php");

exit();

}

if (!isset($_SESSION["carrito"]) || count($_SESSION["carrito"]) == 0) {

    header("Location: carrito.php");

    exit();

}

?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Finalizar Compra</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
        <link rel="stylesheet" href="css/estilos.css">
    </head>
    <body>
        <header>
            <h1>Finalizar Compra</h1>
            <nav>
                <a href="index.php"><i class="fa-solid fa-house"></i>Inicio</a>
                <a href="productos.php"><i class="fa-solid fa-box-open"></i>Productos</a>
                <a href="carrito.php"><i class="fa-solid fa-shopping-cart"></i>Carrito</a>
            </nav>
        </header>
        <section>
            <h2>Datos del Cliente</h2>
            <?php
            if (isset($_GET["error"])) {
                echo '<div class="error">' .
                    htmlspecialchars($_GET["error"]) .
                    '</div>';
            }
            ?>
            <form action="procesarPedido.php" method="POST">
                <label>Nombre Completo</label>
                <input type="text" name="nombre" required>
                <br><br>
                <label>Correo Electrónico</label>
                <input type="email" name="correo" required>
                <br><br>
                <label>Dirección</label>
                <input type="text" name="direccion" required>
                <br><br>
                <label>Forma de Pago</label>
                <select name="pago">
                    <option>Tarjeta de Crédito</option>
                    <option>Tarjeta de Débito</option>
                    <option>Transferencia</option>
                </select>
                <br><br>
                <button type="submit">Confirmar Compra</button>
            </form>
        </section>
        <footer>
        <hr>
        <p>© 2026 TechStore Programación Web II</p>
        </footer>
    </body>
</html>