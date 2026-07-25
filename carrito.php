<?php

require_once("php/funciones.php");

iniciarSesionSegura();

regenerarSesion();

controlarTiempoSesion();

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

    $idProducto = filter_input(INPUT_POST, "id_producto", FILTER_VALIDATE_INT);

    if (!$idProducto) {
        header("Location: productos.php");
        exit();
    }

    try {

        $pdo = conectarDB();

        $stmt = $pdo->prepare("
            SELECT id_producto, nombre, precio, stock
            FROM producto
            WHERE id_producto = ?
        ");

        $stmt->execute([$idProducto]);

        $producto = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$producto) {
            header("Location: productos.php");
            exit();
        }

        if ($producto["stock"] <= 0) {
            header("Location: productos.php");
            exit();
        }

        if (isset($_SESSION["carrito"][$idProducto])) {

            $_SESSION["carrito"][$idProducto]["cantidad"]++;

        } else {

            $_SESSION["carrito"][$idProducto] = [

                "id" => $producto["id_producto"],
                "nombre" => $producto["nombre"],
                "precio" => $producto["precio"],
                "cantidad" => 1

            ];

        }

    } catch (PDOException $e) {

        die("Error al agregar producto.");

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

$total = 0;

?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Carrito | TechStore</title>
        <link rel="stylesheet" href="css/estilos.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    </head>
    <body>
        <header class="header">
            <div class="logo">
                <h1>🛒 TechStore</h1>
                <p>Carrito de Compras</p>
            </div>
            <nav>
                <a href="index.php">Inicio</a>
                <a href="productos.php"><i class="fa-solid fa-list"></i>Productos</a>
                <a href="carrito.php"><i class="fa-solid fa-cart-shopping"></i>Carrito</a>
                <?php if(isset($_SESSION["usuario"])): ?>
                <a href="cerrarSesion.php"><i class="fa-solid fa-right-from-bracket"></i>Cerrar Sesión</a>
                <?php else: ?>
                <a href="login.php"><i class="fa-solid fa-right-to-bracket"></i>Mi Cuenta</a>
                <?php endif; ?>
            </nav>
        </header>
        <section class="carrito">
            <h2>Mi Carrito</h2>
            <?php if(empty($_SESSION["carrito"])): ?>
            <div class="carrito-vacio">
                <i class="fa-solid fa-cart-shopping"></i>
                <h3>Tu carrito está vacío</h3>
                <p>Agrega productos para comenzar tu compra.</p>
                <a href="productos.php"><button>Ver Productos</button></a>
            </div>
            <?php else: ?>
            <?php foreach($_SESSION["carrito"] as $item):
            $subtotal = $item["precio"] * $item["cantidad"];
            $total += $subtotal;
            ?>
            <div class="item-carrito">
                <div class="detalle">
                    <h3><?= htmlspecialchars($item["nombre"]) ?></h3>
                    <p>Precio unitario</p>
                    <strong><?= formatoPrecio($item["precio"]) ?></strong>
                </div>
                <div class="cantidad">
                    <a class="btn-cantidad" href="carrito.php?restar=<?= $item["id"] ?>"> - </a>
                    <span><?= $item["cantidad"] ?></span>
                    <a class="btn-cantidad" href="carrito.php?sumar=<?= $item["id"] ?>"> + </a>
                </div>
                <div class="subtotal"><?= formatoPrecio($subtotal) ?></div>
                <div>
                    <a class="eliminar" href="carrito.php?eliminar=<?= $item["id"] ?>">
                        <i class="fa-solid fa-trash"></i>
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
            <div class="resumen">
                <h2>Resumen del Pedido</h2>
                <p>Productos:<strong><?= count($_SESSION["carrito"]) ?></strong></p>
                <h3><?= formatoPrecio($total) ?></h3>
                <a href="pedido.php"><button>Finalizar Compra</button></a>
                <br><br>
                <a href="productos.php"><button style="background:#007bff">Seguir Comprando</button></a>
                <br><br>
                <a href="carrito.php?vaciar=1"><button style="background:#dc3545">Vaciar Carrito</button></a>
            </div>
            <?php endif; ?>
        </section>
        <footer>
            <p>© <?= date("Y") ?> TechStore | Programación Web II</p>
        </footer>
    </body>
</html>