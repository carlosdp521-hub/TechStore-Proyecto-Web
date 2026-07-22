<?php

require_once("php/funciones.php");

iniciarSesionSegura();

regenerarSesion();

controlarTiempoSesion();

if($_SERVER["REQUEST_METHOD"]!="POST"){

header("Location:pedido.php");

exit();

}

if (!isset($_SESSION["carrito"]) || count($_SESSION["carrito"]) == 0) {

    header("Location: productos.php");

    exit();

}

$nombre = htmlspecialchars(trim($_POST["nombre"]), ENT_QUOTES, "UTF-8");
$correo = htmlspecialchars(trim($_POST["correo"]), ENT_QUOTES, "UTF-8");
$direccion = htmlspecialchars(trim($_POST["direccion"]), ENT_QUOTES, "UTF-8");
$pago = htmlspecialchars(trim($_POST["pago"]), ENT_QUOTES, "UTF-8");

if (empty($nombre) || empty($correo) || empty($direccion) || empty($pago)) {

    header("Location: pedido.php?error=Debe completar todos los campos");

    exit();

}

$total = 0;

foreach($_SESSION["carrito"] as $producto){

$total += $producto["precio"] * $producto["cantidad"];

}

?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Compra Exitosa</title>
        <link rel="stylesheet" href="css/estilos.css">
    </head>
    <body>
        <header>
            <h1>Compra Realizada</h1>
        </header>
        <section>
            <h2>Resumen del Pedido</h2>
            <p><strong>N° Pedido:</strong> <?php echo rand(10000,99999); ?></p>
            <p><strong>Cliente:</strong> <?php echo $nombre; ?></p>
            <p><strong>Correo:</strong> <?php echo $correo; ?></p>
            <p><strong>Dirección:</strong> <?php echo $direccion; ?></p>
            <p><strong>Pago:</strong> <?php echo $pago; ?></p>
            <p><strong>Fecha:</strong> <?php echo date("d/m/Y H:i"); ?></p>
            <hr>
            <h3>Productos Comprados</h3>
            <table>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Subtotal</th>
                </tr>
                
                <?php
                foreach($_SESSION["carrito"] as $producto){
                    $subtotal = $producto["precio"] * $producto["cantidad"];
                ?>
                
                <tr>
                    <td><?php echo $producto["nombre"]; ?></td>
                    <td><?php echo $producto["cantidad"]; ?></td>
                    <td>$<?php echo number_format($subtotal,0,",","."); ?></td>
                </tr>
                <?php } ?>
                <tr>
                    <th colspan="2">TOTAL</th>
                    <th>$<?php echo number_format($total,0,",","."); ?></th>
                </tr>
            </table>
            <br>
            <h2 style="color:green;">✔ Compra realizada correctamente.</h2>
        </section>
    </body>
</html>
<?php
/* Vaciar carrito después de comprar */
$_SESSION["carrito"] = [];
?>