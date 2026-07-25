<?php

require_once("../php/funciones.php");

iniciarSesionSegura();

verificarAdmin();

/* ==============================
    RESUMEN GENERAL
================================ */

$totalCompras = 0;
$totalIngresos = 0;
$totalClientes = 0;
$totalProductos = 0;

// Intentar conectar a la base de datos una sola vez
$pdo = null;

try {
    $pdo = conectarDB();

    // Total de compras e ingresos
    $stmt = $pdo->query("SELECT COUNT(*) AS total_compras, COALESCE(SUM(total),0) AS ingresos FROM compra");
    $resumen = $stmt->fetch();
    $totalCompras = $resumen["total_compras"];
    $totalIngresos = $resumen["ingresos"];

    // Total de clientes registrados
    $stmt = $pdo->query("SELECT COUNT(*) AS total FROM cliente");
    $totalClientes = $stmt->fetch()["total"];

    // Total de productos en catálogo
    $stmt = $pdo->query("SELECT COUNT(*) AS total FROM producto");
    $totalProductos = $stmt->fetch()["total"];

} catch (PDOException $e) {
    echo "Error al obtener el resumen general.";
}

/* ==============================
    PRODUCTOS MÁS VENDIDOS
================================ */

$masVendidos = [];

try {
    $stmt = $pdo->query(
        "SELECT p.nombre, SUM(c.cantidad) AS total_vendido, SUM(c.total) AS ingresos_producto
         FROM compra c
         JOIN producto p ON p.id_producto = c.id_producto
         GROUP BY c.id_producto
         ORDER BY total_vendido DESC
         LIMIT 5"
    );
    $masVendidos = $stmt->fetchAll();
} catch (PDOException $e) {
    echo "Error al obtener productos más vendidos.";
}

/* ==============================
    CLIENTES MÁS FRECUENTES
================================ */

$topClientes = [];

try {
    $stmt = $pdo->query(
        "SELECT cl.nombre, COUNT(*) AS cantidad_compras, SUM(c.total) AS total_gastado
         FROM compra c
         JOIN cliente cl ON cl.id_cliente = c.id_cliente
         GROUP BY c.id_cliente
         ORDER BY total_gastado DESC
         LIMIT 5"
    );
    $topClientes = $stmt->fetchAll();
} catch (PDOException $e) {
    echo "Error al obtener clientes frecuentes.";
}

/* ==============================
    VENTAS POR MÉTODO DE PAGO
================================ */

$ventasPorPago = [];

try {
    $stmt = $pdo->query(
        "SELECT metodo_pago, COUNT(*) AS cantidad, SUM(total) AS total_pago
         FROM compra
         GROUP BY metodo_pago
         ORDER BY total_pago DESC"
    );
    $ventasPorPago = $stmt->fetchAll();
} catch (PDOException $e) {
    echo "Error al obtener ventas por método de pago.";
}

/* ==============================
    ÚLTIMAS COMPRAS (actividad reciente)
================================ */

$ultimasCompras = [];

try {
    $stmt = $pdo->query(
        "SELECT c.id_compra, cl.nombre AS cliente, p.nombre AS producto, c.cantidad, c.total, c.fecha_compra, c.metodo_pago
         FROM compra c
         LEFT JOIN cliente cl ON cl.id_cliente = c.id_cliente
         JOIN producto p ON p.id_producto = c.id_producto
         ORDER BY c.fecha_compra DESC
         LIMIT 10"
    );
    $ultimasCompras = $stmt->fetchAll();
} catch (PDOException $e) {
    echo "Error al obtener las últimas compras.";
}

?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Dashboard | TechStore Admin</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
        <link rel="stylesheet" href="../css/estilos.css">
    </head>
    <body>
        <header>
            <h1>💻 TechStore | Panel Administrador</h1>
            <p>Bienvenido <strong><?php echo $_SESSION["admin_nombre"]; ?></strong></p>
            <nav>
                <a href="dashboard.php"><i class="fa-solid fa-chart-line"></i>Dashboard</a>
                <a href="productos.php"><i class="fa-solid fa-box-open"></i>Productos</a>
                <a href="../cerrarSesionAdmin.php"><i class="fa-solid fa-right-from-bracket"></i>Cerrar Sesión</a>
            </nav>
        </header>

        <section>
            <h2>Resumen General</h2>
            <table>
                <tr>
                    <th>Total Compras</th>
                    <th>Ingresos Totales</th>
                    <th>Clientes Registrados</th>
                    <th>Productos en Catálogo</th>
                </tr>
                <tr>
                    <td><?php echo $totalCompras; ?></td>
                    <td>$<?php echo number_format($totalIngresos,0,",","."); ?></td>
                    <td><?php echo $totalClientes; ?></td>
                    <td><?php echo $totalProductos; ?></td>
                </tr>
            </table>
        </section>

        <section>
            <h2>Productos Más Vendidos</h2>
            <?php if (count($masVendidos) == 0) { ?>
            <p>Aún no hay compras registradas.</p>
            <?php } else { ?>
            <table>
                <tr>
                    <th>Producto</th>
                    <th>Unidades Vendidas</th>
                    <th>Ingresos Generados</th>
                </tr>
                <?php foreach ($masVendidos as $p) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($p["nombre"]); ?></td>
                    <td><?php echo $p["total_vendido"]; ?></td>
                    <td>$<?php echo number_format($p["ingresos_producto"],0,",","."); ?></td>
                </tr>
                <?php } ?>
            </table>
            <?php } ?>
        </section>

        <section>
            <h2>Clientes Más Frecuentes</h2>
            <?php if (count($topClientes) == 0) { ?>
            <p>Aún no hay compras de clientes registrados.</p>
            <?php } else { ?>
            <table>
                <tr>
                    <th>Cliente</th>
                    <th>N° de Compras</th>
                    <th>Total Gastado</th>
                </tr>
                <?php foreach ($topClientes as $c) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($c["nombre"]); ?></td>
                    <td><?php echo $c["cantidad_compras"]; ?></td>
                    <td>$<?php echo number_format($c["total_gastado"],0,",","."); ?></td>
                </tr>
                <?php } ?>
            </table>
            <?php } ?>
        </section>

        <section>
            <h2>Ventas por Método de Pago</h2>
            <?php if (count($ventasPorPago) == 0) { ?>
            <p>Sin datos disponibles.</p>
            <?php } else { ?>
            <table>
                <tr>
                    <th>Método de Pago</th>
                    <th>Cantidad de Ventas</th>
                    <th>Total</th>
                </tr>
                <?php foreach ($ventasPorPago as $v) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($v["metodo_pago"]); ?></td>
                    <td><?php echo $v["cantidad"]; ?></td>
                    <td>$<?php echo number_format($v["total_pago"],0,",","."); ?></td>
                </tr>
                <?php } ?>
            </table>
            <?php } ?>
        </section>

        <section>
            <h2>Últimas Compras</h2>
            <?php if (count($ultimasCompras) == 0) { ?>
            <p>Aún no hay compras registradas.</p>
            <?php } else { ?>
            <table>
                <tr>
                    <th>N° Compra</th>
                    <th>Cliente</th>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Total</th>
                    <th>Pago</th>
                    <th>Fecha</th>
                </tr>
                <?php foreach ($ultimasCompras as $c) { ?>
                <tr>
                    <td>#<?php echo $c["id_compra"]; ?></td>
                    <td><?php echo $c["cliente"] ? htmlspecialchars($c["cliente"]) : "Invitado"; ?></td>
                    <td><?php echo htmlspecialchars($c["producto"]); ?></td>
                    <td><?php echo $c["cantidad"]; ?></td>
                    <td>$<?php echo number_format($c["total"],0,",","."); ?></td>
                    <td><?php echo htmlspecialchars($c["metodo_pago"]); ?></td>
                    <td><?php echo date("d/m/Y H:i", strtotime($c["fecha_compra"])); ?></td>
                </tr>
                <?php } ?>
            </table>
            <?php } ?>
        </section>

        <footer>
            <hr>
            <p>© 2026 TechStore Programación Web II | Panel Administrador</p>
        </footer>
    </body>
</html>