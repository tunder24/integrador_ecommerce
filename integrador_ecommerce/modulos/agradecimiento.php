<?php
include("../includes/conexion.php");
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: modulos/login.php");
    exit();
}

$nombre_usuario = $_SESSION['nombre_usuario'];


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gracias !</title>
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
       <a href="modulos/logout.php" class=>
               <h3>Cerrar sesión</h3>
           </a>
   
         
         </div>
</header>
<div class="inicio_index">

        <h3>Muchas gracias por su compra</h3>

    </div>

</body>
</html>