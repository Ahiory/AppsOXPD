<html>
    <head>
        <title>Documentos</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <script src="logger.js"></script>
        <script src="libs/jquery-1.11.0.min.js"></script>
        <script src="libs/oxpddiscovery-2.6.0.min.js"></script>
        <script src="libs/oxpdprint-2.6.0.min.js"></script>
        <script src="main.js"></script>
        <link rel="shortcut icon" >
        <style>
            body {
                margin: 0; padding: 0; width: 100%; height: 100%; position: absolute; top: 0; left: 0; overflow: hidden;
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            }
            #div1 h1 {
                text-align: center; padding-top: 30px; margin: 0; font-size: 38px;
                color: #222; text-shadow: 2px 2px 5px rgba(255,255,255,0.9);
            }
            #div2 {
                width: 85%; 
                height: 420px; 
                margin: 30px auto 0 auto;
                background: rgba(255, 255, 255, 0.7); 
                border-radius: 15px;
                box-shadow: 0 10px 25px rgba(0,0,0,0.15); 
                padding: 25px;
                overflow-y: auto;
                box-sizing: border-box; 
                -webkit-overflow-scrolling: touch; 
            }

            /* Estilos de barra de desplazamiento (Scrollbar) */
            #div2::-webkit-scrollbar {
                width: 20px;
            }
            #div2::-webkit-scrollbar-track {
                background: rgba(0, 0, 0, 0.05);
                border-radius: 10px;
            }
            #div2::-webkit-scrollbar-thumb {
                background: rgba(0, 0, 0, 0.25);
                border-radius: 10px;
                border: 4px solid rgba(255, 255, 255, 0.7);
            }
            #div2::-webkit-scrollbar-thumb:active {
                background: rgba(0, 0, 0, 0.45);
            }

            /* Estilos de los botones y contenido interno (Reemplaza a Flexbox) */
            .btn-documento {
                background: #ffffff; border: 2px solid transparent; border-radius: 12px;
                padding: 15px 20px; font-size: 18px; font-weight: 600; color: #333;
                display: block; /* Estándar, sin flex */
                box-shadow: 0 4px 8px rgba(0,0,0,0.1); cursor: pointer;
                width: 100%; 
                margin-bottom: 12px; 
                box-sizing: border-box; 
                text-align: left;
            }
            .btn-documento:active { background: #f0f0f0; }
            
            .icono-documento { 
                display: inline-block; 
                vertical-align: middle;
                margin: 0 15px 0 0; 
                width: 45px; 
                height: 45px; 
            }

            .texto-documento {
                display: inline-block;
                vertical-align: middle;
                width: calc(100% - 65px); 
                word-wrap: break-word;
                white-space: normal;
            }

            /* Estilos de los botones flotantes */
            .btn-flotante {
                position: absolute; width: 55px; height: 55px;
                background: rgba(255,255,255,0.9); border-radius: 50%;
                box-shadow: 0 4px 10px rgba(0,0,0,0.2); border: none;
            }
            .btn-cancelar { right: 35px; top: 25px; }
            .btn-atras { left: 35px; bottom: 35px; }

            /* Adaptación para pantallas de 800x484 */
            @media screen and (max-width: 820px) {
                #div1 h1 { font-size: 30px; padding-top: 15px; }
                #div2 { 
                    height: 400px; 
                    margin-top: 25px; 
                    padding: 15px; 
                    box-sizing: border-box; 
                }
                .btn-documento { padding: 12px 15px; font-size: 15px; margin-bottom: 10px; }
                .icono-documento { width: 35px; height: 35px; margin-right: 15px; }
                .btn-flotante { width: 45px; height: 45px; }
                .btn-cancelar { right: 20px; top: 15px; }
                .btn-atras { left: 20px; bottom: 20px; }
            }
        </style>
    </head>

    <body background="home-carvajal.png" style="background-size: cover;">
        <div id="div1">
            <h1><?php echo $carpeta = $_POST['carpeta']; ?></h1>
        </div>
        <div id="div2">
            <form action="pagina3.php" method="post">
                <?php
                $host = $_SERVER['HTTP_HOST'];
                $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                $base = "http://" . $host . $uri . "/";

                $ruta = "/home/u111477705/domains/desarrolloscarvajal.com/public_html/desarrollos/print_doc/padre/$carpeta";
                $ruta2 = "$base/padre/$carpeta";
                    
                $archivos = scandir($ruta);
                ?>
                
                <!-- Input hidden movido fuera del bucle para no repetir código HTML innecesario -->
                <input type="hidden" name="ruta" value="<?php echo $ruta2 ?>">

                <?php
                foreach ($archivos as $archivo) {
                    if ($archivo != "." && $archivo != "..") {
                        $ruta_completa = $ruta . "/" . $archivo;

                        if (is_dir($ruta_completa)) {
                            echo ""; 
                        } else {
                            ?>
                            <button type="submit" name="documento" value="<?php echo $archivo; ?>" class="btn-documento">
                                <img src="documentos.png" class="icono-documento">
                                <span class="texto-documento"><?php echo $archivo; ?></span>
                            </button>
                            <?php
                        }
                    }
                }
                ?>
            </form>
        </div>
        <input type="image" src="atras.png" class="btn-flotante btn-atras" onclick="location.href = 'index.php'">
        <input type="image" src="cancelar.png" class="btn-flotante btn-cancelar" onclick="window.close();">
    </body>
</html>