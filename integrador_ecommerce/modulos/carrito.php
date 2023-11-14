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
            if (isset($_SESSION['carrito'][$productId])) {
                // Si el producto ya está en el carrito, incrementar la cantidad
                $_SESSION['carrito'][$productId]['cantidad'] += 1;
            } else {
                // Si no está en el carrito, agregarlo con cantidad 1
                $_SESSION['carrito'][$productId] = [
                    'nombre' => $producto['nombre'],
                    'precio' => $producto['precio'],
                    'cantidad' => 1
                ];
            }
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

// Procesar la compra
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['procesar_carrito'])) {
    $con = conectar();
    if ($con) {
        // Iniciar transacción
        $con->begin_transaction();

        try {
            foreach ($_SESSION['carrito'] as $productId => $producto) {
                $stmt = $con->prepare("INSERT INTO carrito (usuario_id, producto_id, cantidad) VALUES (?, ?, ?)");
                $stmt->bind_param("iii", $_SESSION['id'], $productId, $producto['cantidad']);
                $stmt->execute();
                $stmt->close();
            }
            $con->commit();
            $_SESSION['carrito'] = [];
            $mensajeCompra = 'Compra realizada con éxito.';
            header("Location: realizar_compra.php"); // Redirigir a la página de agradecimiento
            exit();
        } catch (Exception $e) {
            $con->rollback();
            $mensajeCompra = 'Error al procesar la compra.';
        }
    } else {
        $mensajeCompra = 'No se pudo conectar a la base de datos.';
    }
}

$nombre_usuario = $_SESSION['nombre_usuario'] ?? 'Invitado';
$totalCarrito = calcularTotal($_SESSION['carrito']);

// Calcula el total del carrito
function calcularTotal($carrito) {
    $total = 0;
    foreach ($carrito as $producto) {
        $total += $producto['precio'] * $producto['cantidad'];
    }
    return $total;
}
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
                <li class="menu-item"><a href="productos_lista.php">Productos</a></li>
                <li class="menu-item"><a href="carrito.php">Carrito</a></li>
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




<h2>Carrito de Compras</h2>

<?php if (!empty($_SESSION['carrito'])): ?>
    <table>
        <tr>
            <th>Producto</th>
            <th>Precio</th>
            <th>Cantidad</th>
            <th>Subtotal</th>
            <th>Acción</th>
        </tr>
        <?php foreach ($_SESSION['carrito'] as $id => $producto): ?>
            <tr>
                <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                <td><?php echo htmlspecialchars($producto['precio']); ?></td>
                <td><?php echo htmlspecialchars($producto['cantidad']); ?></td>
                <td><?php echo htmlspecialchars($producto['precio'] * $producto['cantidad']); ?></td>
                <td>
                    <a href="carrito.php?action=remove&id=<?php echo $id; ?>">Eliminar</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    <p>Tu carrito está vacío.</p>
<?php endif; ?>

<a href="productos_lista.php">Continuar comprando</a>
<form action="carrito.php" method="post">
        <input type="submit" name="procesar_carrito" value="Procesar Compra">
    </form>
<!-- Aquí podrías agregar más detalles y opciones, como actualizar cantidades, proceder al checkout, etc. -->
<?php if (isset($mensajeCompra)): ?>
        <p><?php echo $mensajeCompra; ?></p>
    <?php endif; ?>

    <p>Total a pagar: $<?php echo number_format($totalCarrito, 2); ?></p>

   
</body>
</html>
