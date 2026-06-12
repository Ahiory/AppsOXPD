<?php
session_start();
$_SESSION['usuario']=$_POST['usuario'];
$_SESSION['serial']=$_POST['serial'];
$_SESSION['ip']=$_POST['ip'];
$_SESSION['deviceLocation']=$_POST['deviceLocation'];
$_SESSION['machineName']=$_POST['machineName'];
header("location: index.php");
?>