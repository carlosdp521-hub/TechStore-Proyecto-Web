<?php

require_once("../php/funciones.php");

iniciarSesionSegura();

verificarAdmin();

$error = "";
$exito = "";

/* ==============================
    AGREGAR PRODUCTO
================================ */

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["accion"]) && $_POST["accion"] == "agregar") {

    $nombre = trim($_POST["nombre"] ?? "");
    $categoria = trim($_POST["categoria"] ?? "");
    $precio = (int) ($_POST["precio"] ?? 0);
    $stock = (int) ($_POST["stock"] ?? 0);
    $imagen = trim($_POST["imagen"] ?? "");

    if (empty($nombre) || empty($categoria) || $precio <= 0 || empty($imagen)) {

        $error = "Debe completar todos los campos correctamente.";

    } else {

        try {
            $stmt = conectarDB()->prepare(
                "INSERT INTO producto (nombre, categoria, precio, stock, imagen) VALUES (?, ?, ?, ?, ?)"
            );
            $stmt->execute([
                limpiarDato($nombre),
                limpiarDato($categoria),
                $precio,
                $stock,
                limpiarDato($imagen)
            ]);

            $exito = "Producto agregado correctamente.";

        } catch (PDOException $e) {
            $error = "Error al agregar el producto.";
        }

    }

}

/* ==============================
    EDITAR PRODUCTO
================================ */

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["accion"]) && $_POST["accion"] == "editar") {

    $id = (int) ($_POST["id_producto"] ?? 0);
    $nombre = trim($_POST["nombre"] ?? "");
    $categoria = trim($_POST["categoria"] ?? "");
    $precio = (int) ($_POST["precio"] ?? 0);
    $stock = (int) ($_POST["stock"] ?? 0);
    $imagen = trim($_POST["imagen"] ?? "");

    if (empty($nombre) || empty($categoria) || $precio <= 0 || empty($imagen)) {

        $error = "Debe completar todos los campos correctamente.";

    } else {

        try {
            $stmt = conectarDB()->prepare(
                "UPDATE producto SET nombre = ?, categoria = ?, precio = ?, stock = ?, imagen = ? WHERE id_producto = ?"
            );
            $stmt->execute([
                limpiarDato($nombre),
                limpiarDato($categoria),
                $precio,
                $stock,
                limpiarDato($imagen),
                $id
            ]);

            $exito = "Producto actualizado correctamente.";

        } catch (PDOException $e) {
            $error = "Error al actualizar el producto.";
        }

    }

}

/* ==============================
    ELIMINAR PRODUCTO
================================ */

if (isset($_GET["eliminar"])) {

    $id = (int) $_GET["eliminar"];

    try {
        $stmt = conectarDB()->prepare("DELETE FROM producto WHERE id_producto = ?");
        $stmt->execute([$id]);
        $exito = "Producto eliminado correctamente.";
    } catch (PDOException $e) {
        // Si el producto ya tiene compras asociadas, la FK impedirá el borrado
        $error = "No se pudo eliminar el producto. Es posible que tenga compras asociadas.";
    }

}

/* ==============================
    OBTENER PRODUCTO PARA EDITAR
================================ */

$productoEditar = null;

if (isset($_GET["editar"])) {

    $id = (int) $_GET["editar"];

    try {
        $stmt = conectarDB()->prepare("SELECT * FROM producto WHERE id_producto = ?");
        $stmt->execute([$id]);
        $productoEditar = $stmt->fetch();
    } catch (PDOException $e) {
        $error = "No se pudo cargar el producto.";
    }

}

/* ==============================
    LISTAR TODOS LOS PRODUCTOS
================================ */

$productos = [];

try {
    $stmt = conectarDB()->query("SELECT * FROM producto ORDER BY categoria, nombre");
    $productos = $stmt->fetchAll();
} catch (PDOException $e) {
    $error = "Error al obtener el listado de productos.";
}

?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Productos | TechStore Admin</title>
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

        <section style="max-width:600px;">
            <h2><?php echo $productoEditar ? "Editar Producto" : "Agregar Producto"; ?></h2>

            <?php if ($error != "") { ?>
            <p style="color:red;font-weight:bold;"><?php echo $error; ?></p>
            <?php } ?>
            <?php if ($exito != "") { ?>
            <p style="color:green;font-weight:bold;"><?php echo $exito; ?></p>
            <?php } ?>

            <form method="POST">
                <input type="hidden" name="accion" value="<?php echo $productoEditar ? "editar" : "agregar"; ?>">
                <?php if ($productoEditar) { ?>
                <input type="hidden" name="id_producto" value="<?php echo $productoEditar["id_producto"]; ?>">
                <?php } ?>

                <label>Nombre del Producto</label>
                <input type="text" name="nombre" placeholder="Ej: Notebook Lenovo 15'" required
                    value="<?php echo $productoEditar ? htmlspecialchars($productoEditar["nombre"]) : ""; ?>">
                <br><br>

                <label>Categoría</label>
                <input type="text" name="categoria" placeholder="Ej: Notebooks" required
                    value="<?php echo $productoEditar ? htmlspecialchars($productoEditar["categoria"]) : ""; ?>">
                <br><br>

                <label>Precio</label>
                <input type="number" name="precio" placeholder="Ej: 350000" min="1" required
                    value="<?php echo $productoEditar ? $productoEditar["precio"] : ""; ?>">
                <br><br>

                <label>Stock</label>
                <input type="number" name="stock" placeholder="Ej: 10" min="0" required
                    value="<?php echo $productoEditar ? $productoEditar["stock"] : "0"; ?>">
                <br><br>

                <label>URL Imagen</label>
                <input type="text" name="imagen" placeholder="img/producto.jpg" required
                    value="<?php echo $productoEditar ? htmlspecialchars($productoEditar["imagen"]) : ""; ?>">
                <br><br>

                <button type="submit"><?php echo $productoEditar ? "Guardar Cambios" : "Agregar Producto"; ?></button>
                <?php if ($productoEditar) { ?>
                <a href="productos.php"><button type="button">Cancelar</button></a>
                <?php } ?>
            </form>
        </section>

        <section>
            <h2>Catálogo de Productos</h2>
            <?php if (count($productos) == 0) { ?>
            <p>No hay productos registrados.</p>
            <?php } else { ?>
            <table>
                <tr>
                    <th>Nombre</th>
                    <th>Categoría</th>
                    <th>Precio</th>
                    <th>Stock</th>
                    <th>Acciones</th>
                </tr>
                <?php foreach ($productos as $p) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($p["nombre"]); ?></td>
                    <td><?php echo htmlspecialchars($p["categoria"]); ?></td>
                    <td>$<?php echo number_format($p["precio"],0,",","."); ?></td>
                    <td>
                        <?php if ($p["stock"] <= 5) { ?>
                        <strong style="color:red;"><?php echo $p["stock"]; ?></strong>
                        <?php } else { ?>
                        <?php echo $p["stock"]; ?>
                        <?php } ?>
                    </td>
                    <td>
                        <a href="productos.php?editar=<?php echo $p["id_producto"]; ?>"><button>Editar</button></a>
                        <a href="productos.php?eliminar=<?php echo $p["id_producto"]; ?>"
                           onclick="return confirm('¿Eliminar este producto?');"><button>Eliminar</button></a>
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