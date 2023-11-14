<?php
include_once("../includes/conexion.php");


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["nombre"], $_POST["clave"], $_POST["email"], $_POST["rol"])) {
    // Conectarse a la base de datos
    $con = conectar();
    
    if ($con) {
        // Preparar y ejecutar la consulta para verificar si el usuario ya existe
        $stmt = $con->prepare("SELECT id FROM usuarios WHERE nombre = ? OR email = ?");
        $stmt->bind_param("ss", $_POST['nombre'], $_POST['email']);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            echo "<script>alert('El nombre de usuario o el correo electrónico ya están en uso.');</script>";
        } else {
            // Preparar la inserción del nuevo usuario con el rol seleccionado
            $clave_hash = password_hash($_POST['clave'], PASSWORD_DEFAULT);
            $stmt = $con->prepare("INSERT INTO usuarios (nombre, clave, email, rol) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $_POST['nombre'], $clave_hash, $_POST['email'], $_POST['rol']);

            if ($stmt->execute()) {
                echo "<script>alert('Registro exitoso.');</script>";
                header("Location: login.php");
                exit();
            } else {
                echo "<script>alert('ERROR: No se pudo completar el registro.');</script>";
            }
            $stmt->close();
        }
        $con->close();
    } else {
        echo "<script>alert('ERROR: No se pudo conectar a la base de datos');</script>";
    }
}
// ...

?>

<!-- Formulario de registro HTML -->


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Registro</title>
    <link rel="stylesheet" href="../css/estilo_general.css">
</head>
<body>

<div class="inicio_index">
<div class="login-container">
<h2>Registro</h2> <!-- Uso la misma clase que el login para tener el mismo estilo de contenedor -->
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="login-form">
        <div class="form-group">
            <label for="nombre" class="form-label">Nombre de Usuario:</label>
            <input type="text" id="nombre" name="nombre" required class="form-control">
        </div>

        <div class="form-group">
            <label for="email" class="form-label">Correo Electrónico:</label>
            <input type="email" id="email" name="email" required class="form-control">
        </div>

        <div class="form-group">
            <label for="clave" class="form-label">Contraseña:</label>
            <input type="password" id="clave" name="clave" required class="form-control">
        </div>
        <div class="form-group">
    <label for="rol" class="form-label">Tipo de Usuario:</label>
    <select name="rol" id="rol" class="form-control" required>
        <option value="cliente">Cliente</option>
        <option value="admin">Administrador</option>
    </select>
</div>

        <button type="submit" class="btn">Registrarse</button>
    </form>
  
    <div class="register-link"> <!-- Esta clase puede ser opcional si no necesitas un enlace de registro -->
        ¿Ya tienes cuenta? <a href="login.php">Iniciar sesión</a>
    </div>
</div>
</div>

</body>
</html>
