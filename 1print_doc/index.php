<html>

<head>
    <title>Carpetas</title>
    <link rel="stylesheet" href="styles.css">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <script src="logger.js"></script>
    <script src="libs/jquery-1.11.0.min.js"></script>
    <script src="libs/oxpddiscovery-2.0.0.min.js"></script>
    <script src="libs/oxpdprint-2.0.0.min.js"></script>
    <script src="main.js"></script>
    <link rel="shortcut icon">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        @media only screen and (max-width: 480px) {
            body {
                background-image: url(fondo2.1.png);
            }

            #cerrar {
                position: absolute !important;
                left: 83% !important;
                top: 3% !important;
                width: 70px !important;
            }

            #titulo {
                position: absolute !important;
                left: 3% !important;

            }

            #boton1 {
                position: absolute;
                
                width: 280px;
                height: 40px;
                font-family: Helvetica, sans-serif;
                color: white;
                font-size: 30px;
                text-align: center;
              
                background-color: #00B4FF;
            }

            #img1 {
                position: absolute;
                top: 1px;
                left: 82%;
                width: 44px;
            }
            #div2{
                position: absolute;
                left: 20%;
                height: 800px;
            }
       
        }
    </style>
</head>

<body>

    <?php
    session_start();

    $_SESSION['nombre'] = "Jhon"; //$_POST['nombre'];
    
    $area = "Formatos_Recursos_Humanos";
    ?>

    <div id="div1">
        <h1 id="titulo">Formatos - Muestra</h1>
    </div>
    <div id="div2">

        <form action="docs.php" method="post" id="form">
            <?php
            $host = $_SERVER['HTTP_HOST'];
            $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
            $base = "http://" . $host . $uri . "/";

            $dir = '/xampp/htdocs/1print_doc/padre/Formatos_Recursos_Humanos';
            $files = scandir($dir);
            sort($files);
            foreach ($files as $archivo) {
                if ($archivo != '.' && $archivo != '..') {
                    ?>
                    <input type="text" value="<?php echo $area; ?>" name="area" hidden>
                    <button type="submit" name="carpeta" value="<?php echo $archivo; ?>" id="boton1">
                        <?php echo $archivo; ?> <img src="carpeta.png" id="img1">
                    </button>

                    <br><br><br>
                    <?php
                }
            }
            ?>
        </form>
    </div>
    <input id="cerrar" type="image" src="salir1.png" id="salir" onclick="window.close();" style="position: absolute;left: 4%; ">
</body>

</html>