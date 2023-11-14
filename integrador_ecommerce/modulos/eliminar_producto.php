<?php
session_start();
include_once("../includes/conexion.php");

// Asegúrate de que solo los administradores pueden acceder a este script
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    // Redirigir al usuario a la página de inicio si no es administrador
    header('Location: index.php');
    exit();
}

// Chequear si se ha proporcionado un ID de producto
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $productoId = $_GET['id'];

    // Conectar a la base de datos
    $con = conectar();
    if (!$con) {
        die("Error al conectar con la base de datos: " . mysqli_connect_error());
    }

    // Preparar la consulta para eliminar el producto
    $stmt = $con->prepare("DELETE FROM productos WHERE id = ?");
    $stmt->bind_param("i", $productoId);
    $resultado = $stmt->execute();

    // Cerrar la sentencia y la conexión
    $stmt->close();
    $con->close();

    if ($resultado) {
        // Si el producto fue eliminado exitosamente, redirigir al administrador a una página de confirmación o de vuelta al listado
        header('Location: productos_box.php?mensaje=ProductoEliminado');
    } else {
        // Si hubo un error al eliminar el producto, mostrar un mensaje
        echo "<script>alert('No se pudo eliminar el producto.'); window.location.href='productos_box.php';</script>";
    }
} else {
    // Redirigir al usuario si no se proporcionó un ID de producto
    header('Location: productos_box.php');
    exit();
}
?>
