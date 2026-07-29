<?php
date_default_timezone_set('America/Bogota');
include 'conexion.php';

// Capturamos todos los datos que vienen por POST desde index.php
$satisfaccion = isset($_POST['satisfaccion']) ? $_POST['satisfaccion'] : '';
$razon = isset($_POST['razon']) ? $_POST['razon'] : '';
$servicio = isset($_POST['servicio']) ? $_POST['servicio'] : '';
$ip = isset($_POST['ip']) ? $_POST['ip'] : 'Desconocida';
$serial = isset($_POST['serial']) ? $_POST['serial'] : 'Desconocido';
$hoy = date("Y-m-d H:i:s");

// Inserción en base de datos
$insertar = "INSERT INTO encuesta(satisfaccion, razon, servicio, ip, serial, fecha) 
             VALUES ('$satisfaccion', '$razon', '$servicio', '$ip', '$serial', '$hoy')";

$resultado = mysqli_query($conexion, $insertar);
mysqli_close($conexion);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Gracias</title>
    <style>
        body {
            background-color: #F4F7F6;
            font-family: 'Segoe UI', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            text-align: center;
        }
        .card {
            background: #fff;
            padding: 60px 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            width: 80%;
            max-width: 800px;
        }
        
        /* Clases para hacer el texto gigante */
        .titulo-gracias { 
            color: #27AE60; 
            font-size: 70px; 
            margin-bottom: 20px;
            margin-top: 0;
        }
        .texto-gracias {
            font-size: 30px; 
            color: #555;
            margin-bottom: 60px; 
        }
        
        /* Botón de cerrar adaptado para panel táctil */
        .btn-cerrar {
            padding: 20px 50px; 
            border-radius: 8px; 
            border: none; 
            background: #34495E; 
            color: #fff; 
            cursor: pointer;
            font-size: 26px; 
            font-weight: bold;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
    </style>
    <script type="text/javascript">
        // Lógica de auto-cierre 
        function Cerraraliniciar() {
            setTimeout(function() {
                var ventana = window.self;
                ventana.opener = window.self;
                ventana.close();
            }, 3500); // 3.5 segundos para que alcancen a leer el mensaje grande
        }
    </script>
</head>
<body onload="Cerraraliniciar();">
    <div class="card">
        <?php if ($resultado): ?>
            <h2 class="titulo-gracias">¡Muchísimas Gracias!</h2>
            <p class="texto-gracias">Tus respuestas han sido registradas exitosamente.</p>
        <?php else: ?>
            <h2 class="titulo-gracias" style="color: #E74C3C;">Error al registrar</h2>
            <p class="texto-gracias">Por favor, inténtalo de nuevo.</p>
        <?php endif; ?>
        
        <button class="btn-cerrar" onclick="window.close();">Cerrar ventana</button>
    </div>
</body>
</html>