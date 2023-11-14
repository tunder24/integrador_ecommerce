<?php
session_start();
include_once("../includes/conexion.php");

// Chequear si el usuario es cliente
$esCliente = isset($_SESSION['rol']) && $_SESSION['rol'] === 'cliente';

// Si no es cliente, redirigir a otra página
if (!$esCliente) {
    header('Location: index.php');
    exit();
}

// Inicializar carrito si no existe
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

// Agregar producto al carrito
if (isset($_GET['action']) && $_GET['action'] == 'add' && isset($_GET['id'])) {
    $productId = $_GET['id'];
    $con = conectar();

    // Obtener detalles del producto
    if ($con) {
        $stmt = $con->prepare("SELECT nombre, precio FROM productos WHERE id = ?");
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        $result = $stmt->get_result();
        $producto = $result->fetch_assoc();

        if ($producto) {
            // Agregar producto al carrito
            $_SESSION['carrito'][$productId] = [
                'nombre' => $producto['nombre'],
                'precio' => $producto['precio'],
                'cantidad' => 1 // Por simplicidad, se agrega uno cada vez
            ];
        }
        $stmt->close();
    }
    $con->close();
}

// Eliminar producto del carrito
if (isset($_GET['action']) && $_GET['action'] == 'remove' && isset($_GET['id'])) {
    $productId = $_GET['id'];
    if (isset($_SESSION['carrito'][$productId])) {
        unset($_SESSION['carrito'][$productId]);
    }
}

$nombre_usuario = $_SESSION['nombre_usuario'];

//------------------------
function calcularTotal($carrito) {
    $total = 0;
    foreach ($carrito as $item) {
        $total += $item['precio'] * $item['cantidad'];
    }
    return $total;
}

// Procesar la compra (simulación)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['procesar_compra'])) {
    // Aquí procesarías el pago con los datos de la tarjeta
    // Esto es solo una simulación, en un caso real deberías integrar un sistema de pago

    // Vacía el carrito después de la compra
    $_SESSION['carrito'] = [];
    $mensajeCompra = 'Compra realizada con éxito.';
}

$totalCarrito = calcularTotal($_SESSION['carrito']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras</title>
  
    <link rel="stylesheet" href="../css/estilo_general.css">
</head>
<body>
<header>
    <div class="container">
        <nav id="menu">
            <ul class="menu-list">
                <li class="menu-item"><a href="../index.php">Inicio</a></li>
                <li class="menu-item"><a href="listado_box.html">Productos</a></li>
                <li class="menu-item"><a href="carrito.html">Carrito</a></li>
            </ul>
        </nav>
    </div>

    <div id="dato_usuario">
      <!-- Agrega aquí la bienvenida con el nombre de usuario y el rol -->
               <h2>Bienvenido, <?php echo htmlspecialchars($nombre_usuario); ?>!</h2>
       <a href="modulos/logout.php" class=>
               <h3>Cerrar sesión</h3>
           </a>
   
         
         </div>
</header>


<!-- Aquí podrías agregar más detalles y opciones, como actualizar cantidades, proceder al checkout, etc. -->
<?php if (isset($mensajeCompra)): ?>
        <p><?php echo $mensajeCompra; ?></p>
    <?php endif; ?>

    <p>Total a pagar: $<?php echo number_format($totalCarrito, 2); ?></p>
    <a href="productos_lista.php">Continuar comprando</a>
    <form action="carrito.php" method="post">
        <h3>Detalles de pago</h3>
        <p>
            <label for="tarjeta_numero">Número de tarjeta:</label>
            <input type="text" id="tarjeta_numero" name="tarjeta_numero" required>
        </p>
        <p>
            <label for="tarjeta_fecha">Fecha de expiración:</label>
            <input type="month" id="tarjeta_fecha" name="tarjeta_fecha" required>
        </p>
        <p>
            <label for="tarjeta_cvv">CVV:</label>
            <input type="text" id="tarjeta_cvv" name="tarjeta_cvv" required>
        </p>
        <input type="submit" name="procesar_compra" value="Procesar Compra">
    </form>
</body>
</html>
