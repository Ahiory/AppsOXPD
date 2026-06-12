<?php
date_default_timezone_set('America/Bogota');
include 'conexion.php';

$hoy = date("Y-m-d H:i:s");
$servicio = $_POST['servicio'];
$serial = $_POST['serial'];
$ip = substr(($_POST['ip']),7);
$product = $_POST['product'];
$contacto = $_POST['contacto'];
$empresa = $_POST['empresa'];
$direccion = $_POST['direccion'];
$nombre = $_POST['nombre'];

$insertar = "Insert into datos(numero,serial,ip,product,contacto,empresa,direccion,nombre,fecha) values ('$servicio','$serial','$ip','$product','$contacto','$empresa','$direccion','$nombre','$hoy')";

$resultado = mysqli_query($conexion, $insertar);
if (!$resultado) {
	$estado ='Error al registrar';
}else 
{
	$estado = 'Registro con exito';
}
mysqli_close($conexion);
// Varios destinatarios


// Enviarlo
//mail($para, $título, $mensaje, $cabeceras);

echo"<script languaje='javascript' type='text/javascript'>window.close();</script>";
?>
