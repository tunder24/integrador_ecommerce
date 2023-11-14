<?php
session_start();
include_once("../includes/conexion.php");

// Use the same obtenerProductos function from productos_box.php
function obtenerProductos($con) {
    $productos = [];
    $resultado = $con->query("SELECT * FROM productos");
    while($producto = $resultado->fetch_assoc()) {
        $productos[] = $producto;
    }
    return $productos;
}

// Connect to the database and fetch products
$con = conectar();
if (!$con) {
    die("Error al conectar con la base de datos: " . mysqli_connect_error());
}

$productos = obtenerProductos($con);

$nombre_usuario = $_SESSION['nombre_usuario'];


// Check if the user is an admin
$esAdmin = isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin';
$esCliente = isset($_SESSION['rol']) && $_SESSION['rol'] === 'cliente';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado tabla</title>
    <link rel="stylesheet" href="../css/estilo_general.css">
    
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

</head>
<body>
<header>
    <div class="container">
        <nav id="menu">
            <ul class="menu-list">
                <li class="menu-item"><a href="../index.php">Inicio</a></li>
                <li class="menu-item"><a href="productos_lista.php">Productos</a></li>
              
        <?php if ($esCliente): ?>
                <li class="menu-item"><a href="carrito.html">Carrito</a></li>
    <?php endif; ?>
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


       <!-- Admin link to add products if admin -->
       <?php if ($esAdmin): ?>
        <a href="agregar_productos.php">Agregar producto</a>
    <?php endif; ?>

    <table class="estilo-tabla">
        <tbody>
            <?php foreach ($productos as $producto): ?>
                <tr>
                    <td class="estilo-td"><?php echo htmlspecialchars($producto['nombre']); ?></td>
                    <td class="estilo-td">$<?php echo htmlspecialchars($producto['precio']); ?></td>
                    <td class="estilo-td">
                        <img class="estilo-imagenes-tabla" src="../imagenes/<?php echo htmlspecialchars($producto['imagen']); ?>" alt="<?php echo htmlspecialchars($producto['nombre']); ?>">
                    </td>
                    <td class="estilo-td">
                        <!-- Add to cart or edit/delete based on admin status -->
                        <?php if ($esAdmin): ?>
                            <a href="editar_producto.php?id=<?php echo $producto['id']; ?>">Editar</a>
                            <a href="eliminar_producto.php?id=<?php echo $producto['id']; ?>">Eliminar</a>
                        <?php else: ?>
                            <a href="carrito.php?action=add&id=<?php echo $producto['id']; ?>">Añadir al carrito</a>
                        <?php endif; ?>
                    </td>
                    <td class="estilo-td">
                        <a class="estilo-main-a efecto-boton-main-a" href="ficha_producto.php?id=<?php echo $producto['id']; ?>">Ver</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <footer>
      <div class="container">
          <div class="footer-content">
              <div class="footer-description">
                  <p>Somos un ecommerce de venta de cerveza de calidad y variedad. Contamos con las mejores marcas y precios del mercado.</p>
              </div>
              <div class="footer-social">
                  <p>Síguenos en:</p>
                  <a href="#" class="social-link">Instagram: @cervezaya</a>
                  <a href="#" class="social-link">WhatsApp: +54 376 1234567</a>
              </div>
              <div class="footer-copyright">
                  <p>© 2023 Madelaire Corp. Todos los derechos reservados.</p>
              </div>
          </div>
      </div>
  </footer>
  <script src="script/script.js"></script>
</body>
</html>