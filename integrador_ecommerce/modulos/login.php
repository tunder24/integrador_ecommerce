<?php

session_start();
include_once("../includes/conexion.php");

if (isset($_POST["nombre"]) && isset($_POST["clave"])) {
    $con = conectar();
    if ($con) {
        // Selecciona sólo los campos necesarios: id, nombre y clave
        $stmt = $con->prepare("SELECT id, nombre, clave, rol FROM usuarios WHERE nombre = ?");
        $stmt->bind_param("s", $_POST['nombre']);
        
        $stmt->execute();
        $result = $stmt->get_result();

        if ($user = $result->fetch_assoc()) {
            // Verifica la contraseña
            if (password_verify($_POST['clave'], $user['clave'])) {
                // Si es correcta, almacena los datos necesarios en la sesión
                $_SESSION["id"] = $user["id"];
                $_SESSION["nombre_usuario"] = $user["nombre"];
                $_SESSION["rol"] = $user["rol"];
                // Redirecciona al usuario al index.php
                header("Location: ../index.php");
                // Después de un inicio de sesión exitoso:

                exit();
            } else {
                // Contraseña incorrecta
                echo "<script>alert('Contraseña incorrecta.');</script>";
            }
        } else {
            // No se encontró el usuario
            echo "<script>alert('Nombre de usuario incorrecto o no registrado.');</script>";
        }
        $stmt->close();
        $con->close();
    } else {
        // Error de conexión
        echo "<script>alert('ERROR: No se pudo conectar a la base de datos');</script>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../css/estilo_general.css">
</head>
<body>


    <div class="inicio_index">
<div class="login-container">
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" class="login-form">

        <div class="form-group">
            <label for="nombre" class="form-label">Nombre de Usuario:</label>
            <input type="text" id="nombre" name="nombre" required class="form-control">
        </div>

        <div class="form-group">
            <label for="clave" class="form-label">Contraseña:</label>
            <input type="password" id="clave" name="clave" required class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Iniciar Sesión</button>

        <div class="register-link">
            ¿No tienes cuenta? <a href="registro.php">Regístrate</a>
        </div>
    </form>
</div>
</div>
</body>
</html>

