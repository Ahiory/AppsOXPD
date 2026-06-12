<?php
$conexion = new mysqli("10.73.31.46","esneyder","esneyder","u111477705_usuarios");
if ($mysqli->connect_errno) {
    echo "Fallo al conectar a MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}
echo $mysqli->host_info . "\n";


?> 