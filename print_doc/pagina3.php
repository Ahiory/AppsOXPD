<html>
    <head>
        <title>Imprimiendo</title>
        <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="css/bootstrap-theme.min.css">
        <script src="js/bootstrap.min.js"></script>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <link rel="stylesheet" href="style.css">
        <script src="logger.js"></script>
        <script src="libs/jquery-1.11.0.min.js"></script>
        <script src="libs/oxpddiscovery-2.6.0.min.js"></script>
        <script src="libs/oxpdprint-2.6.0.min.js"></script>
        <script src="libs/oxpddeviceinfo-2.6.0.min.js"></script>
        <script src="libs/oxpdscan-2.6.0.min.js"></script>
        <script src="main.js"></script>
        <script src="main2.js"></script>
        <script src="main3.js"></script>
        <link rel="shortcut icon" type="image/x-icon" href="carvajal-logo.ico" />

        <style>
            body {
                margin: 0; 
                padding: 0; 
                width: 100%; 
                height: 100%;
                position: absolute;
                top: 0; left: 0;
                overflow: hidden;
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                background-image: url('home-carvajal.jpg');
                background-size: cover;
            }
            
            #contenedor-impresion {
                width: 65%; /* Más ancho para que la tarjeta sea más prominente (de 55%) */
                min-height: 500px; /* Mucho más alto, y adaptable (de height: 350px) */
                height: auto; 
                margin: 100px auto 0 auto; /* Un poco menos de margen superior (de 120px) */
                background: rgba(255, 255, 255, 0.85); 
                border-radius: 20px;
                box-shadow: 0 15px 35px rgba(0,0,0,0.2);
                text-align: center;
                padding: 40px; /* Más espacio interno */
                box-sizing: border-box; /* Asegura que el padding no afecte el ancho total */
            }
            #contenedor-impresion h1 {
                color: #2c3e50;
                font-size: 42px; /* Texto más grande (de 38px) */
                margin-top: 20px;
                margin-bottom: 10px;
                font-weight: bold;
            }
            #contenedor-impresion h3 {
                color: #444;
                font-size: 28px; /* Texto más grande (de 24px) */
                margin-bottom: 30px;
                word-wrap: break-word; 
            }
            
            .imagen-animada {
                width: 350px; /* Duplicado considerablemente (de 120px) */
                height: auto;
                margin-bottom: 20px;
            }

            @media screen and (max-width: 820px) {
                #contenedor-impresion {
                    width: 75%; /* Ocupa más ancho en pantallas pequeñas (de 65%) */
                    min-height: 380px; /* Mucho más alto y adaptable (de height: 260px) */
                    height: auto;
                    margin-top: 50px; /* Menos margen superior (de 60px) */
                    padding: 25px;
                }
                #contenedor-impresion h1 { font-size: 34px; margin-top: 15px; } /* Texto más grande (de 30px) */
                #contenedor-impresion h3 { font-size: 22px; margin-bottom: 20px; } /* Texto más grande (de 18px) */
                .imagen-animada { width: 250px; } /* Aumentado drásticamente para la pantalla (de 90px) */
            }
        </style>

        <script type="text/javascript">
            function carta() {
                let selectedValue = document.getElementById("seleccion").value;
                alert(selectedValue);
            }
            
            window.onload = function() {
                getManufacturerInfo();
                getCustomerInfo();
            };
        </script>
    </head>
    <body>
        <?php
        $ruta = $_POST['ruta']; 
        $documento = $_POST['documento']; 
        
        $url_impresion = $ruta . "/" . $documento;
        ?>

        <div id="contenedor-impresion">
            <h1>Imprimiendo...</h1>
            <h3><?php echo $documento; ?></h3>
            <img src="imprimiendo.png" class="imagen-animada" alt="Imprimiendo">
        </div>

        <input type="button" value="Imprimir" onclick="main.print('<?php echo $url_impresion; ?>');" id="imprimir" hidden>

        <script type="text/javascript">
            window.setTimeout(function () {
                document.getElementById("imprimir").click();
            }, 2000);
        </script>
        
        <form action="almacenar.php" method="post">
           <input type="text" value="<?php echo $documento; ?>" name="documento" hidden>
           <input type="text" id="SerialNumber" name="serial" hidden>
           <input type="text" id="ipAddress" name="ip" hidden>
           <input type="text" id="deviceLocation" name="deviceLocation" hidden>
           <input type="text" id="machineName" name="machineName" hidden>
           <input type="submit" value="imprimir" name="imprimir" id="enviar" hidden>
        </form>
        
        <script type="text/javascript">
            window.setTimeout(function () {
                document.getElementById("enviar").click();
            }, 5000);
        </script>
        
    </body>
</html>