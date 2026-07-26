<?php

require_once("../php/funciones.php");

iniciarSesionSegura();

verificarAdmin("../");

$error = "";
$exito = "";

/* ==============================
    ELIMINAR CLIENTE
================================ */

if (isset($_GET["eliminar"])) {

    $id = (int) $_GET["eliminar"];

    try {
        $stmt = conectarDB()->prepare("DELETE FROM cliente WHERE id_cliente = ?");
        $stmt->execute([$id]);
        $exito = "Cliente eliminado correctamente.";
    } catch (PDOException $e) {
        // Si el cliente tiene compras asociadas, la FK impedirá el borrado
        $error = "No se pudo eliminar el cliente. Es posible que tenga compras registradas.";
    }

}

/* ==============================
    BUSCAR CLIENTES
================================ */

$busqueda = trim($_GET["buscar"] ?? "");

/* ==============================
    LISTAR CLIENTES CON RESUMEN DE COMPRAS
================================ */

$clientes = [];

try {

    $pdo = conectarDB();

    $sql = "
        SELECT cl.id_cliente, cl.usuario, cl.nombre, cl.email, cl.direccion, cl.telefono, cl.fecha_registro,
               COUNT(c.id_compra) AS cantidad_compras,
               COALESCE(SUM(c.total),0) AS total_gastado
        FROM cliente cl
        LEFT JOIN compra c ON c.id_cliente = cl.id_cliente
    ";

    $params = [];

    if (!empty($busqueda)) {
        $sql .= " WHERE cl.nombre LIKE ? OR cl.usuario LIKE ? OR cl.email LIKE ?";
        $like = "%" . $busqueda . "%";
        $params = [$like, $like, $like];
    }

    $sql .= " GROUP BY cl.id_cliente ORDER BY cl.nombre";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $clientes = $stmt->fetchAll();

} catch (PDOException $e) {
    $error = "Error al obtener el listado de clientes.";
}

?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Clientes | TechStore Admin</title>
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
            <h2>Clientes Registrados</h2>

            <?php if ($error != "") { ?>
            <p style="color:red;font-weight:bold;"><?php echo $error; ?></p>
            <?php } ?>
            <?php if ($exito != "") { ?>
            <p style="color:green;font-weight:bold;"><?php echo $exito; ?></p>
            <?php } ?>

            <form method="GET" style="margin-bottom:20px;">
                <input type="text" name="buscar" placeholder="Buscar por nombre, usuario o correo..."
                    value="<?php echo htmlspecialchars($busqueda); ?>">
                <button type="submit" style="width:auto; padding:12px 30px;">Buscar</button>
            </form>

            <?php if (count($clientes) == 0) { ?>
            <p>No se encontraron clientes.</p>
            <?php } else { ?>
            <table>
                <tr>
                    <th>Usuario</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                    <th>Fecha Registro</th>
                    <th>Compras</th>
                    <th>Total Gastado</th>
                    <th>Acciones</th>
                </tr>
                <?php foreach ($clientes as $c) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($c["usuario"]); ?></td>
                    <td><?php echo htmlspecialchars($c["nombre"]); ?></td>
                    <td><?php echo htmlspecialchars($c["email"]); ?></td>
                    <td><?php echo htmlspecialchars($c["telefono"]); ?></td>
                    <td><?php echo date("d/m/Y", strtotime($c["fecha_registro"])); ?></td>
                    <td><?php echo $c["cantidad_compras"]; ?></td>
                    <td>$<?php echo number_format($c["total_gastado"],0,",","."); ?></td>
                    <td>
                        <a href="compras.php?cliente=<?php echo $c["id_cliente"]; ?>"><button>Ver Compras</button></a>
                        <a href="clientes.php?eliminar=<?php echo $c["id_cliente"]; ?>"
                           onclick="return confirm('¿Eliminar este cliente?');"><button>Eliminar</button></a>
                    </td>
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