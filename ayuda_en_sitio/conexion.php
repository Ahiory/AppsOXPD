<?php
$conexion = mysqli_connect("localhost", "root","", "fallas_mfp");
if(!$conexion){
    echo 'Error al conectar';
}else{
    echo 'conectado';
}
?>
