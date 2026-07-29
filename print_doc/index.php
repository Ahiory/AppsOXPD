<html>
    <head>
        <title>Libreria de documentos</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <script src="logger.js"></script>
        <script src="libs/jquery-1.11.0.min.js"></script>
        <script src="libs/oxpddiscovery-2.6.0.min.js"></script>
        <script src="libs/oxpdprint-2.6.0.min.js"></script>
        <script src="main.js"></script>
        <link rel="shortcut icon">
        <style>
            /* Método universal para el tamaño de la pantalla */
            body {
                margin: 0;
                padding: 0;
                width: 100%; 
                height: 100%;
                position: absolute;
                top: 0; left: 0;
                overflow: hidden; 
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            }
            #div1 h1 {
                text-align: center;
                padding-top: 30px;
                margin: 0;
                font-size: 38px;
                color: #222;
                text-shadow: 2px 2px 5px rgba(255,255,255,0.9);
            }
            #div2 {
                width: 85%;
                height: 450px;
                margin: 30px auto 0 auto;
                background: rgba(255, 255, 255, 0.7); 
                border-radius: 15px;
                box-shadow: 0 10px 25px rgba(0,0,0,0.15);
                padding: 25px;
                overflow-y: auto; 
                text-align: center; /* Centra los botones inline-block */
            }
            /* Botones estilo clásico inline-block para compatibilidad total */
            #boton1 {
                background: #ffffff;
                border: 2px solid transparent;
                border-radius: 12px;
                padding: 15px 10px;
                font-size: 18px;
                font-weight: 600;
                color: #333;
                display: inline-block; /* Reemplaza Flex y Grid */
                vertical-align: top;
                text-align: center;
                box-shadow: 0 4px 8px rgba(0,0,0,0.1);
                cursor: pointer;
                height: 150px;
                width: 180px; /* Ancho fijo en lugar de 100% */
                margin: 15px; /* Espaciado entre botones */
                transition: transform 0.1s ease, background 0.2s ease;
            }
            #boton1:active {
                background: #f0f0f0;
                border-color: #ccc;
            }
            #img1 {
                display: block; /* Fuerza a la imagen a estar arriba del texto */
                margin: 0 auto 10px auto; /* Centra la imagen */
                width: 54px;
                height: 54px;
            }
            .btn-cancelar {
                position: absolute;
                right: 35px;
                top: 25px;
                width: 55px;
                height: 55px;
                background: rgba(255,255,255,0.9);
                border-radius: 50%;
                box-shadow: 0 4px 10px rgba(0,0,0,0.2);
                border: none;
            }

            /* Adaptación para pantallas de 800x484 */
            @media screen and (max-width: 820px) {
                #div1 h1 { font-size: 30px; padding-top: 15px; }
                #div2 { height: auto; margin-top: 40px; padding: 15px; }
                #boton1 { height: 150px; width: 250px; font-size: 15px; margin: 10px; }
                #img1 { width: 70px; height: 70px; margin-bottom: 5px; }
                .btn-cancelar { width: 45px; height: 45px; right: 20px; top: 15px; }
            }
        </style>
    </head>
    <script>
            $(document).ready(function () {
                // 1. Configurar colores de la interfaz OXPd al iniciar
                if (window.OXPd !== undefined && window.OXPd !== null) {
                    window.OXPd.headerBackgroundColor = '#005487';
                    window.OXPd.headerForegroundColor = '#FFFFFF';
                    window.OXPd.footerCollapsed = true;
                }
            });
        </script>
    <body background="home-carvajal.png" style="background-size: cover;">            
        <div id="div1">
            <h1>Seleccione la carpeta</h1>
        </div>
        
        <div id="div2">
            <form action="pagina2.php" method="post">
                <?php
                $host = $_SERVER['HTTP_HOST'];
                $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                $base = "http://" . $host . $uri . "/";
                $ruta = "/home/u111477705/domains/desarrolloscarvajal.com/public_html/desarrollos/print_doc/padre/";

                $archivos = scandir($ruta);
                natsort($archivos);

                foreach ($archivos as $archivo) {
                    if ($archivo != "." && $archivo != "..") {
                        $ruta_completa = $ruta . $archivo;
                        if (is_dir($ruta_completa)) {
                            ?>
                            <button type="submit" name="carpeta" value="<?php echo $archivo; ?>" id="boton1"> 
                                <img src="carpeta.png" id="img1">
                                <?php echo str_replace('_', ' ', $archivo); ?> 
                            </button>
                            <?php
                        }
                    }
                }
                ?>
            </form>
        </div>
        <input type="image" src="cancelar.png" class="btn-cancelar" onclick="window.close();">
    </body>
</html>