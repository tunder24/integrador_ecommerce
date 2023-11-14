<?php
include("../includes/conexion.php");
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: modulos/login.php");
    exit();
}

$nombre_usuario = $_SESSION['nombre_usuario'];


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["imagen"])) {
    $con = conectar();

    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];
    $descripcion = $_POST['descripcion'];
    $nombreImagen = $_FILES['imagen']['name'];
    $rutaTempImagen = $_FILES['imagen']['tmp_name'];

    // Mover la imagen al directorio deseado
    $rutaDestino = "../imagenes/" . $nombreImagen;
    move_uploaded_file($rutaTempImagen, $rutaDestino);

    $stmt = $con->prepare("INSERT INTO productos (nombre, precio, imagen, descripcion) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nombre, $precio, $rutaDestino, $descripcion);

    if ($stmt->execute()) {
        echo "<script>alert('Producto agregado exitosamente');</script>";
    } else {
        echo "<script>alert('Error al agregar producto: " . $stmt->error . "');</script>";
    }

    $stmt->close();
    $con->close();
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
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

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
    Nombre: <input type="text" name="nombre" required><br>
    Precio: <input type="text" name="precio" required><br>
    Imagen: <input type="file" name="imagen" required><br>
    Descripción: <textarea name="descripcion" required></textarea><br>
    <input type="submit" value="Agregar Producto">
</form>

</body>
</html>