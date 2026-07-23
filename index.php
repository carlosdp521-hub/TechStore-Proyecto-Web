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
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>TechStore | Inicio</title>
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
      <link rel="stylesheet" href="css/estilos.css">
   </head>
   <body>
      <header>
         <h1>💻 TechStore</h1>
         <?php if (isset($_SESSION["usuario"])) { ?>
         <p>Bienvenido <strong><?php echo htmlspecialchars($_SESSION["usuario"]); ?></strong></p>
         <?php } else { ?>
         <p>Encuentra los mejores productos tecnológicos al mejor precio.</p>
         <?php } ?>
         <nav>
            <a href="index.php"><i class="fa-solid fa-house"></i>Inicio</a>
            <a href="carrito.php"><i class="fa-solid fa-cart-shopping"></i>Carrito(<?php echo $cantidadProductos; ?>)</a>
            <?php if (isset($_SESSION["usuario"])) { ?>
            <a href="cerrarSesion.php"><i class="fa-solid fa-right-from-bracket"></i>Cerrar Sesión</a>
            <?php } else { ?>
            <a href="miCuenta.php"><i class="fa-solid fa-user"></i>Mi Cuenta</a>
            <?php } ?>
         </nav>
      </header>
      <section class="banner" style="background:linear-gradient(rgba(115, 116, 117, 0.75),rgba(111, 176, 226, 0.75)),url('img/notebook.jpg') center/cover no-repeat;color:#fff;text-align:center;padding:80px 20px;border-radius:8px;">
         <h2 style="font-size:2rem;margin-bottom:10px;">🔥 Ofertas Tecnológicas TechStore</h2>
         <p style="font-size:1.1rem;margin-bottom:25px;">Notebooks, monitores y accesorios con hasta 20% de descuento por tiempo limitado.</p>
         <a href="productos.php"><button>Ver Catálogo</button></a>     
      </section>
 
      <?php if (isset($_SESSION["usuario"])) { ?>
      <section>
         <h2>Resumen del Carrito</h2>
         <?php
         if ($cantidadProductos == 0) {
         ?>
         <p>No existen productos agregados.</p>
         <?php
         } else {
         ?>
         <table>
            <tr>
               <th>Productos</th>
               <th>Total</th>
            </tr>
            <tr>
               <td><?php echo $cantidadProductos; ?></td>
               <td>$<?php echo number_format($totalCarrito,0,",","."); ?></td>
            </tr>
         </table>
         <br>
         <a href="carrito.php"><button>Ir al Carrito</button></a>
         <?php
         }
         ?>
      </section>
      <?php } ?>
      <section>
         <h2>¿Por qué comprar en TechStore?</h2>
         <ul>
            <li>Productos originales.</li>
            <li>Garantía en todos los equipos.</li>
            <li>Despacho a todo Chile.</li>
            <li>Compra segura mediante sesiones PHP.</li>
         </ul>
      </section>
      <footer>
         <hr>
         <p>© 2026 TechStore Programación Web II</p>
      </footer>
   </body>
</html>