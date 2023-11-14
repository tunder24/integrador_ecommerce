<?php
session_start();
include_once("../includes/conexion.php");

// Asegúrate de que solo los administradores pueden acceder a este script
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header('Location: index.php');
    exit();
}

$productoId = isset($_GET['id']) ? $_GET['id'] : null;
$producto = null;

// Conectar a la base de datos
$con = conectar();
if (!$con) {
    die("Error al conectar con la base de datos: " . mysqli_connect_error());
}

// Si se ha enviado el formulario de edición del producto
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Aquí procesarías el formulario y actualizarías el producto en la base de datos
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];
    $descripcion = $_POST['descripcion'];
    // ... y cualquier otro campo que desees actualizar
    
    $stmt = $con->prepare("UPDATE productos SET nombre = ?, precio = ?, descripcion = ? WHERE id = ?");
    $stmt->bind_param("sssi", $nombre, $precio, $descripcion, $productoId);
    $stmt->execute();
    $stmt->close();
    
    header('Location: productos_box.php?mensaje=ProductoEditado');
    exit();
}

// Si no se ha enviado el formulario, obtener la información actual del producto
if ($productoId) {
    $stmt = $con->prepare("SELECT * FROM productos WHERE id = ?");
    $stmt->bind_param("i", $productoId);
    $stmt->execute();
    $resultado = $stmt->get_result();
    if ($resultado) {
        $producto = $resultado->fetch_assoc();
    }
    $stmt->close();
}
$con->close();

// Si no se encuentra el producto, redirigir a la lista de productos
if (!$producto) {
    header('Location: productos_box.php?error=ProductoNoEncontrado');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Producto</title>
    <link rel="stylesheet" href="../css/estilo_general.css">
</head>
<body>
    <h1>Editar Producto</h1>
    <form action="editar_producto.php?id=<?php echo htmlspecialchars($productoId); ?>" method="post">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($producto['nombre']); ?>" required>
        
        <label for="precio">Precio:</label>
        <input type="number" id="precio" name="precio" value="<?php echo htmlspecialchars($producto['precio']); ?>" required>
        
        <label for="descripcion">Descripción:</label>
        <textarea id="descripcion" name="descripcion" required><?php echo htmlspecialchars($producto['descripcion']); ?></textarea>
        
        <!-- Aquí podrías incluir campos adicionales para editar imágenes u otros atributos del producto -->
        
        <input type="submit" value="Actualizar Producto">
    </form>
    <a href="productos_box.php">Volver a la lista de productos</a>
</body>
</html>
