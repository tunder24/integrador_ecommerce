<?php
session_start();
include_once("../includes/conexion.php");

// Function to get a single product from the database
function obtenerProductoPorId($con, $id) {
    $stmt = $con->prepare("SELECT * FROM productos WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// Check if a product ID is passed in the URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $con = conectar();
    if (!$con) {
        die("Error al conectar con la base de datos: " . mysqli_connect_error());
    }

    $producto = obtenerProductoPorId($con, $_GET['id']);
} else {
    // Redirect or handle the case where no product ID is provided
    header('Location: index.php'); // Adjust the redirect location as necessary
    exit();
}

// Check if the user is an admin
$esAdmin = isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin';
$nombre_usuario = isset($_SESSION['nombre_usuario']) ? $_SESSION['nombre_usuario'] : 'Invitado';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($producto['nombre']); ?></title>
    <link rel="stylesheet" href="../css/estilo_carrito.css">
</head>
<body>

<div class="ficha-producto">
    <?php if ($producto): ?>
        <div class="flex-producto">
            <img src="../imagenes/<?php echo htmlspecialchars($producto['imagen']); ?>" alt="<?php echo htmlspecialchars($producto['nombre']); ?>">
            <h3><?php echo htmlspecialchars($producto['nombre']); ?></h3>
            <p>$<?php echo htmlspecialchars($producto['precio']); ?></p>
            <p><?php echo htmlspecialchars($producto['descripcion']); ?></p>
            <?php if ($esAdmin): ?>
                <!-- Opciones solo para admin -->
                <a href="editar_producto.php?id=<?php echo $producto['id']; ?>">Editar</a>
                <a href="eliminar_producto.php?id=<?php echo $producto['id']; ?>">Eliminar</a>
            <?php else: ?>
                <!-- Opciones para usuarios generales -->
                <a href="carrito.php?action=add&id=<?php echo $producto['id']; ?>">Añadir al carrito</a>
            <?php endif; ?>
            <a href="productos_box.php?id=<?php echo $producto['id']; ?>">Volver atras</a>
        </div>
    <?php else: ?>
        <p>Producto no encontrado.</p>
    <?php endif; ?>
</div>

</body>
</html>
