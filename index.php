<?php

require_once("php/funciones.php");

/* ==============================
   INICIAR SESIÓN SEGURA
================================ */

iniciarSesionSegura();

regenerarSesion();

controlarTiempoSesion();

/* ==============================
   VALIDAR USUARIO
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
   CALCULAR CANTIDAD Y TOTAL
================================ */

$cantidadProductos = 0;
$totalCarrito = 0;

foreach ($_SESSION["carrito"] as $producto) {

   $cantidadProductos += $producto["cantidad"];
   $totalCarrito += $producto["precio"] * $producto["cantidad"];

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
         <p>Bienvenido<strong><?php echo htmlspecialchars($_SESSION["usuario"]); ?></strong></p>
         <nav>
            <a href="index.php"><i class="fa-solid fa-house"></i>Inicio</a>
            <a href="productos.php"><i class="fa-solid fa-laptop"></i>Productos</a>
            <a href="carrito.php"><i class="fa-solid fa-cart-shopping"></i>Carrito(<?php echo $cantidadProductos; ?>)</a>
            <a href="cerrarSesion.php"><i class="fa-solid fa-sign-out-alt"></i>Cerrar Sesión</a>
         </nav>
      </header>
      <section>
         <h2>Bienvenido a TechStore</h2>
         <p>Encuentra los mejores productos tecnológicos al mejor precio.</p>
         <br>
         <a href="productos.php"><button>Ver Catálogo</button></a>
      </section>
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