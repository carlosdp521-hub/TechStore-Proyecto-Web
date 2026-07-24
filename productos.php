<?php

require_once("php/funciones.php");

iniciarSesionSegura();

regenerarSesion();

/* ==============================
    CALCULAR CANTIDAD DE CARRITO
    (solo si hay sesión iniciada)
================================ */

$cantidadProductos = 0;

if (isset($_SESSION["usuario"])) {

    controlarTiempoSesion();

    if (!isset($_SESSION["carrito"])) {
        $_SESSION["carrito"] = [];
    }

    foreach ($_SESSION["carrito"] as $producto) {
        $cantidadProductos += $producto["cantidad"];
    }

}

/* =======================================
    OBTENER PRODUCTOS DE LA BASE DE DATOS
========================================= */

$productos = [];

try {
    $stmt = conectarDB()->query("SELECT * FROM producto ORDER BY categoria, nombre");
    $productos = $stmt->fetchAll();
} catch (PDOException $e) {
    echo "Error al obtener los productos.";
}

?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Productos | TechStore</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
        <link rel="stylesheet" href="css/estilos.css">
    </head>
    <body>
        <header>
            <h1>💻 TechStore</h1>
            <?php if (isset($_SESSION["usuario"])) { ?>
            <p>Bienvenido<strong><?php echo htmlspecialchars($_SESSION["usuario"]); ?></strong></p>
            <?php } else { ?>
            <p>Encuentra los mejores productos tecnológicos al mejor precio.</p>
            <?php } ?>
            <nav>
                <a href="index.php"><i class="fa-solid fa-house"></i>Inicio</a>
                <a href="carrito.php"><i class="fa-solid fa-shopping-cart"></i>Carrito(<?php echo $cantidadProductos; ?>)</a>
                <?php if (isset($_SESSION["usuario"])) { ?>
                <a href="cerrarSesion.php"><i class="fa-solid fa-right-from-bracket"></i>Cerrar Sesión</a>
                <?php } else { ?>
                <a href="miCuenta.php"><i class="fa-solid fa-user"></i>Mi Cuenta</a>
                <?php } ?>
            </nav>
        </header>
        <section>
            <h2>Catálogo de Productos</h2>
            <input type="text" id="buscar" placeholder="Buscar productos...">
            <div class="catalogo"><?php foreach($productos as $producto){ ?>
            <div class="producto">
                <img src="<?php echo $producto["imagen"]; ?>" alt="<?php echo $producto["nombre"]; ?>">
                <h3><?php echo $producto["nombre"]; ?></h3>
                <p>Categoría:<strong><?php echo $producto["categoria"]; ?></strong></p>
                <h2>$<?php echo number_format($producto["precio"],0,",","."); ?></h2>
                <form action="carrito.php" method="POST">
                    <input type="hidden" name="id" value="<?php echo $producto["id_producto"]; ?>">
                    <input type="hidden" name="nombre" value="<?php echo htmlspecialchars($producto["nombre"]); ?>">
                    <input type="hidden" name="precio" value="<?php echo $producto["precio"]; ?>">
                    <button type="submit">Agregar al carrito</button>
                </form>
            </div>
            <?php } ?>
            </div>
        </section>
        <footer>
        <hr>
        <p>© 2026 TechStore Programación Web II</p>
        </footer>
        <script src="js/app.js"></script>
    </body>
</html>