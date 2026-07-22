<?php

require_once("php/funciones.php");

iniciarSesionSegura();

regenerarSesion();

controlarTiempoSesion();

/* ==============================
    VERIFICAR USUARIO
================================ */

if (!isset($_SESSION["usuario"])) {

    header("Location: login.php");
    exit();

}

/* ==============================
    CREAR CARRITO
================================ */

if (!isset($_SESSION["carrito"])) {

    $_SESSION["carrito"] = [];

}

/* ==============================
    AGREGAR PRODUCTO
================================ */

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id = $_POST["id"];

    if (isset($_SESSION["carrito"][$id])) {

        $_SESSION["carrito"][$id]["cantidad"]++;

    } else {

        $_SESSION["carrito"][$id] = [

            "id" => $_POST["id"],
            "nombre" => htmlspecialchars($_POST["nombre"], ENT_QUOTES, "UTF-8"),
            "precio" => (int) $_POST["precio"],
            "cantidad" => 1

        ];

    }

    header("Location: carrito.php");
    exit();

}

/* ==============================
    SUMAR CANTIDAD
================================ */

if (isset($_GET["sumar"])) {

    $id = $_GET["sumar"];

    if (isset($_SESSION["carrito"][$id])) {

        $_SESSION["carrito"][$id]["cantidad"]++;

    }

    header("Location: carrito.php");
    exit();

}

/* ==============================
    RESTAR CANTIDAD
================================ */

if (isset($_GET["restar"])) {

    $id = $_GET["restar"];

    if (isset($_SESSION["carrito"][$id])) {

        $_SESSION["carrito"][$id]["cantidad"]--;

        if ($_SESSION["carrito"][$id]["cantidad"] <= 0) {

            unset($_SESSION["carrito"][$id]);

        }

    }

    header("Location: carrito.php");
    exit();

}

/* ==============================
    ELIMINAR PRODUCTO
================================ */

if (isset($_GET["eliminar"])) {

    $id = $_GET["eliminar"];

    if (isset($_SESSION["carrito"][$id])) {

        unset($_SESSION["carrito"][$id]);

    }

    header("Location: carrito.php");
    exit();

}

/* ==============================
    VACIAR CARRITO
================================ */

if (isset($_GET["vaciar"])) {

    $_SESSION["carrito"] = [];

    header("Location: carrito.php");
    exit();

}

?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Carrito de Compras</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
        <link rel="stylesheet" href="css/estilos.css">
    </head>
    <body>
        <header>
            <h1>🛒 Carrito de Compras</h1>
            <p>Bienvenido<strong><?php echo $_SESSION["usuario"]; ?></strong></p>
            <nav>
                <a href="index.php"><i class="fa-solid fa-house"></i>Inicio</a>
                <a href="productos.php"><i class="fa-solid fa-box-open"></i>Productos</a>
                <a href="pedido.php"><i class="fa-solid fa-shopping-cart"></i>Finalizar Compra</a>
                <a href="cerrarSesion.php"><i class="fa-solid fa-right-from-bracket"></i>Cerrar Sesión</a>
            </nav>
        </header>
        <section>
            <h2>Productos Seleccionados</h2>
            <?php
            if (count($_SESSION["carrito"]) == 0) {
                echo "<p><strong>El carrito está vacío.</strong></p>";
                } else {
            ?>
            <table>
                <tr>
                    <th>Producto</th>
                    <th>Precio</th>
                    <th>Cantidad</th>
                    <th>Subtotal</th>
                    <th>Acción</th>
                </tr>
                <?php
                $total = 0;
                foreach ($_SESSION["carrito"] as $producto) {
                    $subtotal = $producto["precio"] * $producto["cantidad"];
                    $total += $subtotal;
                ?>
                <tr>
                    <td><?php echo $producto["nombre"]; ?></td>
                    <td>$<?php echo number_format($producto["precio"],0,",","."); ?></td>
                    <td>
                        <a href="carrito.php?restar=<?php echo $producto["id"]; ?>"><button>-</button></a>
                        <strong><?php echo $producto["cantidad"]; ?></strong>
                        <a href="carrito.php?sumar=<?php echo $producto["id"]; ?>"><button>+</button></a>
                    </td>
                    <td>
                        $<?php echo number_format($subtotal,0,",","."); ?>
                    </td>
                    <td>
                        <a href="carrito.php?eliminar=<?php echo $producto["id"]; ?>"><button>Eliminar</button></a>
                    </td>
                </tr>
                <?php
                }
                ?>
                <tr>
                    <th colspan="3">TOTAL</th>
                    <th>$<?php echo number_format($total,0,",","."); ?></th>
                    <th></th>
                </tr>
            </table>
            <br>
            <a href="productos.php"><button>Seguir Comprando</button></a>
            <a href="carrito.php?vaciar=1"><button>Vaciar Carrito</button></a>
            <a href="pedido.php"><button>Finalizar Compra</button></a>
            <?php
            }
            ?>
        </section>
        <footer>
            <hr>
            <p>© 2026 TechStore Programación Web II</p>
        </footer>
    </body>
</html>