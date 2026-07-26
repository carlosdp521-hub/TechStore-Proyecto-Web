<?php

require_once("../php/funciones.php");

iniciarSesionSegura();

verificarAdmin("../");

$error = "";

/* ==============================
    FILTROS
================================ */

$clienteFiltro = isset($_GET["cliente"]) ? (int) $_GET["cliente"] : null;
$pagoFiltro = trim($_GET["pago"] ?? "");
$fechaDesde = trim($_GET["desde"] ?? "");
$fechaHasta = trim($_GET["hasta"] ?? "");

/* ==============================
    LISTAR COMPRAS SEGÚN FILTROS
================================ */

$compras = [];
$totalFiltrado = 0;

$pdo = null;

try {

    $pdo = conectarDB();

    $sql = "
        SELECT c.id_compra, cl.nombre AS cliente_nombre, p.nombre AS producto_nombre,
               c.cantidad, c.total, c.fecha_compra, c.metodo_pago
        FROM compra c
        LEFT JOIN cliente cl ON cl.id_cliente = c.id_cliente
        JOIN producto p ON p.id_producto = c.id_producto
        WHERE 1 = 1
    ";

    $params = [];

    if ($clienteFiltro) {
        $sql .= " AND c.id_cliente = ?";
        $params[] = $clienteFiltro;
    }

    if (!empty($pagoFiltro)) {
        $sql .= " AND c.metodo_pago = ?";
        $params[] = $pagoFiltro;
    }

    if (!empty($fechaDesde)) {
        $sql .= " AND c.fecha_compra >= ?";
        $params[] = $fechaDesde . " 00:00:00";
    }

    if (!empty($fechaHasta)) {
        $sql .= " AND c.fecha_compra <= ?";
        $params[] = $fechaHasta . " 23:59:59";
    }

    $sql .= " ORDER BY c.fecha_compra DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $compras = $stmt->fetchAll();

    foreach ($compras as $c) {
        $totalFiltrado += $c["total"];
    }

} catch (PDOException $e) {
    $error = "Error al obtener el listado de compras.";
}

/* ==============================
    NOMBRE DEL CLIENTE FILTRADO (para mostrar en el título)
================================ */

$nombreClienteFiltro = "";

if ($clienteFiltro) {
    try {
        $stmt = $pdo->prepare("SELECT nombre FROM cliente WHERE id_cliente = ?");
        $stmt->execute([$clienteFiltro]);
        $fila = $stmt->fetch();
        $nombreClienteFiltro = $fila ? $fila["nombre"] : "";
    } catch (PDOException $e) {
        $nombreClienteFiltro = "";
    }
}

?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Compras | TechStore Admin</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
        <link rel="stylesheet" href="../css/estilos.css">
    </head>
    <body>
        <header>
            <h1>💻 TechStore | Panel Administrador</h1>
            <p>Bienvenido <strong><?php echo $_SESSION["admin_nombre"]; ?></strong></p>
            <nav>
                <a href="dashboard.php"><i class="fa-solid fa-chart-line"></i> Dashboard</a>
                <a href="productos.php"><i class="fa-solid fa-box-open"></i> Productos</a>
                <a href="clientes.php"><i class="fa-solid fa-users"></i> Clientes</a>
                <a href="compras.php"><i class="fa-solid fa-receipt"></i> Compras</a>
                <a href="../cerrarSesionAdmin.php"><i class="fa-solid fa-right-from-bracket"></i> Cerrar Sesión</a>
            </nav>
        </header>

        <section>
            <h2>
                Historial de Compras
                <?php if ($nombreClienteFiltro != "") { ?>
                — <?php echo htmlspecialchars($nombreClienteFiltro); ?>
                <?php } ?>
            </h2>

            <?php if ($error != "") { ?>
            <p style="color:red;font-weight:bold;"><?php echo $error; ?></p>
            <?php } ?>

            <?php if ($clienteFiltro) { ?>
            <p><a href="compras.php">✕ Quitar filtro de cliente</a></p>
            <?php } ?>

            <form method="GET" style="display:flex; flex-wrap:wrap; gap:15px; align-items:end;">
                <?php if ($clienteFiltro) { ?>
                <input type="hidden" name="cliente" value="<?php echo $clienteFiltro; ?>">
                <?php } ?>

                <div style="flex:1; min-width:180px;">
                    <label>Método de Pago</label>
                    <select name="pago">
                        <option value="">Todos</option>
                        <option value="Tarjeta de Crédito" <?php echo $pagoFiltro == "Tarjeta de Crédito" ? "selected" : ""; ?>>Tarjeta de Crédito</option>
                        <option value="Tarjeta de Débito" <?php echo $pagoFiltro == "Tarjeta de Débito" ? "selected" : ""; ?>>Tarjeta de Débito</option>
                        <option value="Transferencia" <?php echo $pagoFiltro == "Transferencia" ? "selected" : ""; ?>>Transferencia</option>
                    </select>
                </div>

                <div style="flex:1; min-width:150px;">
                    <label>Desde</label>
                    <input type="date" name="desde" value="<?php echo htmlspecialchars($fechaDesde); ?>">
                </div>

                <div style="flex:1; min-width:150px;">
                    <label>Hasta</label>
                    <input type="date" name="hasta" value="<?php echo htmlspecialchars($fechaHasta); ?>">
                </div>

                <div style="flex:1; min-width:150px;">
                    <button type="submit">Filtrar</button>
                </div>
            </form>

            <?php if (count($compras) == 0) { ?>
            <p>No se encontraron compras con estos filtros.</p>
            <?php } else { ?>
            <table>
                <tr>
                    <th>N° Compra</th>
                    <th>Cliente</th>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Total</th>
                    <th>Método de Pago</th>
                    <th>Fecha</th>
                </tr>
                <?php foreach ($compras as $c) { ?>
                <tr>
                    <td>#<?php echo $c["id_compra"]; ?></td>
                    <td><?php echo $c["cliente_nombre"] ? htmlspecialchars($c["cliente_nombre"]) : "Invitado"; ?></td>
                    <td><?php echo htmlspecialchars($c["producto_nombre"]); ?></td>
                    <td><?php echo $c["cantidad"]; ?></td>
                    <td>$<?php echo number_format($c["total"],0,",","."); ?></td>
                    <td><?php echo htmlspecialchars($c["metodo_pago"]); ?></td>
                    <td><?php echo date("d/m/Y H:i", strtotime($c["fecha_compra"])); ?></td>
                </tr>
                <?php } ?>
                <tr>
                    <th colspan="4">TOTAL FILTRADO</th>
                    <th>$<?php echo number_format($totalFiltrado,0,",","."); ?></th>
                    <th colspan="2"></th>
                </tr>
            </table>
            <?php } ?>
        </section>

        <footer>
            <hr>
            <p>© 2026 TechStore Programación Web II | Panel Administrador</p>
        </footer>
    </body>
</html>