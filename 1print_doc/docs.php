<html>

<head>
    <title>Documentos</title>
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
                left: 80% !important;
                top: 6% !important;
                width: 75px !important;
            }

            #titulo {
                position: absolute !important;
                left: 14% !important;

            }

            #boton1 {
                position: absolute;
                width: 280px;
                height: 40px;
                font-family: Helvetica, sans-serif;
                color: white;
                font-size: 30px;
                text-align: center;
                border-radius: 8px;
                background-color: #00B4FF;
            }

            #boton2 {
                position: absolute;
                left: 2%;
                width: 380px;
                height: 50px;
                font-family: Helvetica, sans-serif;
                color: white;
                font-size: 20px;
                text-align: center;
                border-radius: 8px;
                background-color: #00B4FF;

            }

            #div2 {
                position: absolute;
                top: 100px;
                left: 35px;
                height: 275px;
                width: 500px;
                overflow: scroll;
                overflow-x: hidden;
                overflow-y: hidden;
            }

            #div1 {
                border: 2px;
                position: absolute;
                height: 90px;
                width: 785px;
                text-align: center;
            }

            #atras {
                position: absolute;
                left: 3%;
                top: 115%;
                width: 50px;
                height: 50px;
            }

        }
    </style>
</head>

<script>
    function PromptDemo() {
        //Ingresamos un mensaje a mostrar
        //Detectamos si el usuario ingreso un valor
        if (copias != "" || copias != null) {

            var b = 0;
            do {

                if (b > 0) {
                    alert("Digite un número entre 1 a 10");
                }

                var copias = parseInt(prompt("Ingrese el Número de Copias a Imprimir", ""));
                b = 1;
            } while (isNaN(copias) || copias > 10 || copias < 1);

            document.getElementById("copia2").value = copias;

        }
        //Detectamos si el usuario NO ingreso un valor
        else {
            alert("No has ingresado una copias");
        }
    }


</script>

<body>

    <form action="print.php" method="post">
        <div id="div1">
            <h1 id="titulo">
                <?php echo $carpeta = $_POST['carpeta']; ?>
            </h1>
        </div>
        <div id="div2">

            <?php
            session_start();
            $nombre = $_SESSION['nombre'];
            $area = $_POST['area'];

            $host = $_SERVER['HTTP_HOST'];
            $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
            $base = "http://" . $host . $uri . "/";

            $dir = "/xampp/htdocs/1print_doc/padre/$area/$carpeta"; // Ruta local dentro del disco
            
            $ruta2 = "$base/padre/"; // Ruta "web" del documento
            
            $files = scandir($dir);
            sort($files);
            foreach ($files as $archivo) {
                if ($archivo != '.' && $archivo != '..') {
                    ?>
                   
                    <input type="text" name="area" value="<?php echo $area ?>" hidden>
                    <input type="text" name="carpeta" value="<?php echo $carpeta ?> " hidden>
                    <input type="text" name="ruta" value="<?php echo $ruta2 ?>" hidden>
                    <button type="submit" name="documento" value="<?php echo $archivo; ?>" id="boton2" onclick="PromptDemo()">
                        <?php echo $archivo; ?> <img src="expediente.png" id="img1">

                    </button>
                    <br><br><br><br>

                    <?php
                }
            }
            ?>

        </div>
        <div id="div4">

            <input type="text" name="copia" value="" id="copia2" hidden>

        </div>

    </form>

    <input id="cerrar" type="image" src="salir1.png" id="salir" onclick="window.close();" style="position: absolute;left: 4%; ">
    <form method="post" action="index.php">
        <button type="submit" name="nombre" value="<?php echo $nombre; ?>"><img src="atras.png" id="atras"> </button>

    </form>
</body>

</html>