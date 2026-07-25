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
        <meta name="viewport"content="width=device-width, initial-scale=1.0">
        <title>Productos | TechStore</title>
        <link rel="stylesheet"href="css/estilos.css">
        <link rel="stylesheet"href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    </head>
    <body>
        <header class="header">
            <div class="logo">
                <h1>💻 TechStore</h1>
                <p>Catálogo de Productos</p>
            </div>
            <nav>
                <a href="index.php">Inicio</a>
                <a href="productos.php"><i class="fa-solid fa-list"></i>Productos</a>
                <a href="carrito.php"><i class="fa-solid fa-cart-shopping"></i>Carrito</a>
                <?php if(isset($_SESSION["usuario"])): ?>
                    <a href="cerrarSesion.php"><i class="fa-solid fa-right-from-bracket"></i>Cerrar Sesión</a>
                <?php else: ?>
                    <a href="login.php"><i class="fa-solid fa-user"></i>Mi Cuenta</a>
                <?php endif; ?>
            </nav>
        </header>
        <section>
            <h2>Nuestros Productos</h2>
            <div class="barra-busqueda">
                <input type="text"id="buscar"placeholder="Buscar producto...">
                <select id="categoria">
                    <option value="">Todas las categorías</option>
                    <option value="Tecnología">Tecnología</option>
                    <option value="Oficina">Oficina</option>
                </select>
            </div>
            <div class="productos-grid" id="contenedorProductos">
                <?php foreach($productos as $producto): ?>
                <div class="producto"data-nombre="<?= strtolower($producto["nombre"]) ?>"data-categoria="<?= $producto["categoria"] ?>">
                    <img src="<?= htmlspecialchars($producto["imagen"]) ?>"alt="<?= htmlspecialchars($producto["nombre"]) ?>">
                    <div class="contenido-producto">
                        <h3><?= htmlspecialchars($producto["nombre"]) ?></h3>
                        <p class="categoria"><?= htmlspecialchars($producto["categoria"]) ?></p>
                        <p class="descripcion"><?= htmlspecialchars($producto["descripcion"]) ?></p>
                        <div class="precio"><?= formatoPrecio($producto["precio"]) ?></div>
                        <div class="stock">Stock:<strong><?= $producto["stock"] ?></strong></div>
                        <form action="carrito.php"method="POST">
                            <input type="hidden"name="id_producto" value="<?= $producto["id_producto"] ?>">
                            <input type="hidden" name="nombre" value="<?= $producto["nombre"] ?>">
                            <input type="hidden" name="precio" value="<?= $producto["precio"] ?>">
                            <input type="hidden" name="cantidad" value="1">
                            <button><i class="fa-solid fa-cart-plus"></i>Agregar al carrito</button>
                        </form>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
        <footer>
            <p>© <?= date("Y") ?> TechStore | Programación Web II</p>
        </footer>
        <script>
        const buscar=document.getElementById("buscar");
        const categoria=document.getElementById("categoria");
        const productos=document.querySelectorAll(".producto");
        function filtrar(){
            const texto=buscar.value.toLowerCase();
            productos.forEach(producto=>{
                const nombre=producto.dataset.nombre;
                const cat=producto.dataset.categoria;
                const visible=nombre.includes(texto)&&(categoria.value===""||categoria.value===cat);
                producto.style.display=visible?"block":"none";
            });
        }
        buscar.addEventListener("keyup",filtrar);
        categoria.addEventListener("change",filtrar);
        </script>
    </body>
</html>