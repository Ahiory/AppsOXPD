<html>
<head>
		<title>Certificados laborales</title>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/bootstrap-theme.min.css">
    <script src="js/bootstrap.min.js"></script>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="stylesheet" href="style.css">
    <script src="logger.js"></script>
    <script src="libs/jquery-1.11.0.min.js"></script>
    <script src="libs/oxpddiscovery-2.0.0.min.js"></script>
    <script src="libs/oxpdprint-2.0.0.min.js"></script>
    <script src="main.js"></script>
    <link rel="shortcut icon" type="image/x-icon" href="carvajal-logo.ico" />
<script type="text/javascript">
	function carta(){
  let selectedValue = document.getElementById("seleccion").value;
  alert(selectedValue);
}
</script>
</head>

<?php
    
     $host = $_SERVER['HTTP_HOST'];
        $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        $base = "http://" . $host . $uri . "/";
        
    ?>
    
    
<body background="home-carvajal.jpg" style="background-size: cover;">
 <input type="image" src="cancelar.png" style="position: absolute;left: 88%; top: 20px; width: 50px; height: 50px;" onclick="window.close();">

 <!--   prmer recuadro -->
  <div style="width: 500px;height: 80px;text-align: center;position: absolute;left: 15%;top: 5%;margin: 20px;border: red 2px; padding: 20px;border-radius: 5px;background-color: black;opacity: 0.6;">

</div>
<div style="width: 450px;height: 70px;text-align: center;position: absolute;left: 18%;top: 10%;margin: 20px;">
	<p style="text-align: left; color: white;font-size: 20px;">Carta con Salario </p>
	<input type="button" value="Imprimir"  onclick="main.print('<?php echo "$base/certificados/carta_con_salario.pdf" ?>');" style="position: absolute;left: 80%;top: -10%;" class="btn btn-success btn-lg">
	<br>

</div>
<!--   segundo recuadro -->
  <div style="width: 500px;height: 80px;text-align: center;position: absolute;left: 15%;top: 25%;margin: 20px;border: red 2px; padding: 20px;border-radius: 5px;background-color: black;opacity: 0.6;">
</div>
<div style="width: 450px;height: 70px;text-align: center;position: absolute;left: 18%;top:30%;margin: 20px;">
	<p style="text-align: left; color: white;font-size: 20px;">Carta sin Salario</p>
	<input type="button" value="Imprimir"  onclick="main.print('<?php echo "$base/certificados/carta_sin_salario.pdf" ?>');"style="position: absolute;left: 80%;top: -10%;" class="btn btn-success btn-lg">
	<br>
</div>
<!--   tercer recuadro -->
<div style="width: 500px;height: 80px;text-align: center;position: absolute;left: 15%;top:45%;margin: 20px;border: red 2px; padding: 20px;border-radius: 5px;background-color: black;opacity: 0.6;">
</div>
<div style="width: 450px;height: 70px;text-align: center;position: absolute;left: 18%;top: 50%;margin: 20px;">
	<p style="text-align: left; color: white;font-size: 20px;">Certificado Laboral</p>
	<input type="button" value="Imprimir" onclick="main.print('<?php echo "$base/certificados/certificado_laboral.pdf" ?>');" style="position: absolute;left: 80%;top: -10%;" class="btn btn-success btn-lg">
	<br>
</div>

<input type=image src="atras.png" style="position: absolute;left: 5%;top: 85%; width: 50px; height: 50px;" onclick="location.href = 'index.php'"></body>
</html>