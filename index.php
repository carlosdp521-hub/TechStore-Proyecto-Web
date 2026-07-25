<?php

require_once("php/funciones.php");

/* ==============================
   INICIAR SESIÓN SEGURA
================================ */

iniciarSesionSegura();

regenerarSesion();


/* ==============================
   CALCULAR CANTIDAD Y TOTAL
================================ */

$cantidadProductos = 0;
$totalCarrito = 0;

if (isset ($_SESSION["usuario"])) {

   controlarTiempoSesion();

   if (!isset($_SESSION["carrito"])) {
   $_SESSION["carrito"] = [];
   }

   foreach ($_SESSION["carrito"] as $producto) {

      $cantidadProductos += $producto["cantidad"];
      $totalCarrito += $producto["precio"] * $producto["cantidad"];
   }
}

/* ==============================
   OBTENER PRODUCTOS DE LA BASE DE DATOS
================================ */
 
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
      <title>💻 TechStore | Tienda Online</title>
      <link rel="stylesheet"href="css/estilos.css">
      <link rel="stylesheet"href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
   </head>
   <body>
      <header class="header">
         <div class="logo">
            <h1>💻 TechStore</h1>
            <p>Tecnología al mejor precio</p>
         </div>
         <nav>
            <a href="index.php">Inicio</a>
            <a href="productos.php"><i class="fa-solid fa-list"></i>Productos</a>
            <a href="carrito.php"><i class="fa-solid fa-cart-shopping"></i>Carrito</a>
            <?php if(isset($_SESSION["usuario"])): ?>
            <a href="cerrarSesion.php"><i class="fa-solid fa-right-from-bracket"></i>Cerrar Sesión</a>
            <?php else: ?>
            <a href="miCuenta.php"><i class="fa-solid fa-user"></i>Mi Cuenta</a>
            <?php endif; ?>
         </nav>
      </header>
      <section class="hero">
         <h2>Todo lo que necesitas en tecnología</h2>
         <p>Notebooks, celulares, accesorios y mucho más.</p>
         <a href="productos.php"><button>Comprar Ahora</button></a>
      </section>
      <section>
         <h2>Categorías</h2>
         <div class="categorias">
            <div class="categoria">
               <i class="fa-solid fa-laptop"></i>
               <h3>Notebook</h3>
            </div>
            <div class="categoria">
               <i class="fa-solid fa-mobile-screen"></i>
               <h3>Celulares</h3>
            </div>
            <div class="categoria">
               <i class="fa-solid fa-tablet-screen-button"></i>
               <h3>Tablets</h3>
            </div>
            <div class="categoria">
               <i class="fa-solid fa-headphones"></i>
               <h3>Accesorios</h3>
            </div>
         </div>
      </section>
      <section>
         <h2>Productos Destacados</h2>
         <div class="productos-grid">
            <?php foreach($productos as $producto): ?>
            <div class="producto">
               <img src="<?= htmlspecialchars($producto["imagen"]) ?>">
               <h3><?= htmlspecialchars($producto["nombre"]) ?></h3>
               <p><?= htmlspecialchars($producto["categoria"]) ?></p>
               <h4><?= formatoPrecio($producto["precio"]) ?></h4>
               <form action="carrito.php" method="POST">
                  <input type="hidden" name="id_producto" value="<?= $producto["id_producto"] ?>">
                  <input type="hidden" name="nombre" value="<?= $producto["nombre"] ?>">
                  <input type="hidden" name="precio" value="<?= $producto["precio"] ?>">
                  <input type="hidden" name="cantidad" value="1">
                  <button>Agregar al carrito</button>
               </form>
            </div>
            <?php endforeach; ?>
         </div>
      </section>
      <section class="beneficios">
         <div>
            <i class="fa-solid fa-truck-fast"></i>
            <h3>Despacho Rápido</h3>
         </div>
         <div>
            <i class="fa-solid fa-shield-halved"></i>
            <h3>Compra Segura</h3>
         </div>
         <div>
            <i class="fa-solid fa-medal"></i>
            <h3>Garantía Oficial</h3>
         </div>
         <div>
            <i class="fa-solid fa-headset"></i>
            <h3>Soporte 24/7</h3>
         </div>
      </section>
      <footer>
         <p>© <?= date("Y") ?> TechStore | Programación Web II</p>
      </footer>
   </body>
</html>