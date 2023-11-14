<?php
session_start();
include_once("../includes/conexion.php");

function obtenerProductos($con) {
    // Asegúrate de que la consulta a la base de datos es exitosa
    $productos = [];
    $resultado = $con->query("SELECT * FROM productos");
    if ($resultado) {
        while($producto = $resultado->fetch_assoc()) {
            $productos[] = $producto;
        }
    }
    return $productos;
}

// Conectar a la base de datos y obtener productos
$con = conectar();
if (!$con) {
    die("Error al conectar con la base de datos: " . mysqli_connect_error());
}

$productos = obtenerProductos($con);

// Verificar si el usuario es administrador
$esAdmin = isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin';
$nombre_usuario = $_SESSION['nombre_usuario'];

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagina Box</title>
    <link rel="stylesheet" href="../css/estilo_general.css">
</head>
<header>
    <div class="container">
        <nav id="menu">
            <ul class="menu-list">
                <li class="menu-item"><a href="../index.php">Inicio</a></li>
                <li class="menu-item"><a href="productos_lista.php">Productos</a></li>
                <li class="menu-item"><a href="carrito.html">Carrito</a></li>
            </ul>
        </nav>
    </div>

    <div id="dato_usuario">
      <!-- Agrega aquí la bienvenida con el nombre de usuario y el rol -->
               <h2>Bienvenido, <?php echo htmlspecialchars($nombre_usuario); ?>!</h2>
       <a href="logout.php" class=>
               <h3>Cerrar sesión</h3>
           </a>
   
         
         </div>
</header>

<body>
    <?php if ($esAdmin): ?>
        <!-- Mostrar enlace a agregar_productos.php para admin -->
        <a href="agregar_productos.php">Agregar producto</a>
    <?php endif; ?>

    <div class="flex-container">
        <?php foreach ($productos as $producto): ?>
            <div class="flex-item">
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
                <a href="ficha_producto.php?id=<?php echo $producto['id']; ?>">Ver producto</a>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- aquí iría el footer -->
</body>
</html>
