<?php
session_start();
include 'conexion.php';

$usuario = strtoupper($_SESSION['usuario']);
$falla = $_POST['falla'];
$machineName = $_SESSION['machineName'];
$ip      = substr(($_SESSION['ip']),7);
$serial  = $_SESSION['serial'];
$deviceLocation = $_SESSION['deviceLocation'];


$insertar = "insert into mfp (usuario,falla,modelo,ip,sn,localizacion) values ('$usuario','$falla','$machineName','$ip','$serial','$deviceLocation')";

$resultado = mysqli_query($conexion,$insertar);

if (!$resultado) {
    $estado = 'Error al Registrar';
} else {
    $estado = 'Registro Con Éxito';
}

mysqli_close($conexion);
header("location: gracias.php");

exit();

?>