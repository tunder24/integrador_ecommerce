<?php
include("includes/conexion.php");
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: modulos/login.php");
    exit();
}

$nombre_usuario = $_SESSION['nombre_usuario'];
$esCliente = isset($_SESSION['rol']) && $_SESSION['rol'] === 'cliente';

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Principal</title>
    <link rel="stylesheet" href="css/estilo_general.css">
</head>

<body>
<header>
    <div class="container">
        <nav id="menu">
            <ul class="menu-list">
                <li class="menu-item"><a href="../index.php">Inicio</a></li>
                <li class="menu-item"><a href="modulos/productos_lista.php">Productos</a></li>
                <?php if ($esCliente): ?>
                <li class="menu-item"><a href="modulos/carrito.php">Carrito</a></li>
    <?php endif; ?>
            </ul>
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
   


    <div id="listados">
      <a href="modulos/productos_lista.php" class="modern-link">
        <h1>Productos lista</h1>
      </a>
      <a href="modulos/productos_box.php" class="modern-link">
        <h1>Productos box</h1>
      </a>
      <a href="carrusel_tp.php" class="modern-link">
        <h1>Carrusel tp</h1>
        


        <a href="modulos/logout.php" class="modern-link">
            <h1>Cerrar sesión</h1>
        </a>

      </a>
    </div>


  </div>
   
</body>
</html>
