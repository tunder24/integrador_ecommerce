<?php
function conectar()
{
    $con = mysqli_connect("localhost", "root", "", "ecommerce");

    if (mysqli_connect_errno()) {
        printf("Fallo la conexion: %s\n", mysqli_connect_error());
        return false; // Devolver falso si la conexión falla
    } else {
        $con->set_charset("utf8");
        return $con; // Devolver el objeto de conexión si es exitoso
    }
}

?>