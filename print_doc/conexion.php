<?php
$conexion = mysqli_connect("mysql.hostinger.co.uk", "u111477705_root5","usuarios", "u111477705_usuarios");

if(!$conexion){
    echo 'Error al conectar';
} else {
    // AGREGA ESTA LÍNEA AQUÍ:
    // Esto ajusta la zona horaria de la sesión de MySQL a Bogotá (-5 horas)
    mysqli_query($conexion, "SET time_zone = '-05:00'");
}
?>