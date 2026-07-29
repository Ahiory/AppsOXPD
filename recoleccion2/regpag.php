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
$para  = 'jhon.martinez@carvajal.com' . ', '; // atención a la coma
$para .= 'jhon.martinez@carvajal.com';
// título
$título = 'Recoleccion de suministros';
// mensaje
$mensaje = '
<html>
<head>
 <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <title>Esto es un mensaje de recoleccion generado por su impresora Hp</title>
</head>
<body>
  <h3>¡Esto es un mensaje de recolección generado por su impresora Hp!<h3>
</body>
</html>
'. "Su impresora $nombre envió una solicitud de recolección el día  : $hoy con una cantidad de $servicio suministros. <br>Desde la Ip :$ip.<br> Serial número :$serial.<br> Código de producto :$product. <br><h3>A continuación, se relacionan los datos de contacto registrados en la MFP.</h3>Contacto :$contacto. <br>Empresa :$empresa.<br> Dirección :$direccion<br> <br>Por favor no responda este mensaje";

// Para enviar un correo HTML, debe establecerse la cabecera Content-type
$cabeceras  = 'MIME-Version: 1.0' . "\r\n";
$cabeceras .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
$cabeceras .= 'From:<esneyder.sanchez@carvajal.com>' . "\r\n";


// Enviarlo
mail($para, $título, $mensaje, $cabeceras);

echo"<script languaje='javascript' type='text/javascript'>window.close();</script>";
?>
