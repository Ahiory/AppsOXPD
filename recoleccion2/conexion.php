

<?php
$conexion = new mysqli("192.168.137.1", "esneyder", "esneyder", "u111477705_re");

if ($conexion->connect_errno) {
    echo "Fallo al conectar a MySQL: (" . $conexion->connect_errno . ") " . $conexion->connect_error;
} else {
    echo $conexion->host_info . "\n";
}
?>
