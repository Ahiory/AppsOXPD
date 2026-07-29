<?php
$conexion = mysqli_connect("mysql.hostinger.co.uk", "u111477705_root", "Esn.2017*", "u111477705_en");

if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}

mysqli_set_charset($conexion, "utf8");
?>
