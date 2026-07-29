


<?php

include 'conexion.php';

$documento = strtoupper($_POST['documento']);
$machineName = $_POST['machineName'];
$ip      = substr(($_POST['ip']),7);
$serial  = $_POST['serial'];
$deviceLocation = $_POST['deviceLocation'];

$insertar = "insert into imp_documentos (nombre_doc,modelo,ip,sn,localizacion) values ('$documento','$machineName','$ip','$serial','$deviceLocation')";

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

