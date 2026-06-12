<?php

include 'conexion.php';
session_start();
$usuario = "Usuario"; // $_SESSION['nombre'];
$documento = strtoupper($_POST['documento']);
$copias = intval($_POST['copias']);
$machineName = $_POST['machineName'];
$ip = substr(($_POST['ip']), 7);
$serial = $_POST['serial'];
$deviceLocation = $_POST['deviceLocation'];



$insertar = "insert into imp_documentos (usuario,nombre_doc,copias,modelo,ip,sn,localizacion) values ('$usuario', '$documento','$copias','$machineName','$ip','$serial','$deviceLocation')";

$resultado = mysqli_query($conexion, $insertar);

if (!$resultado) {
   echo $estado = 'Error al Registrar';
} else {
   echo $estado = 'Registro Con Éxito';
}

mysqli_close($conexion);
session_destroy();

header("location: gracias.php");
exit();
?>
