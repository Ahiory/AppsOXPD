<html>
    <head>
        <title>Gracias</title>
        <link rel="stylesheet" href="estiloregistro.css">
        <style>
            /* Aseguramos la resolución de la pantalla de la impresora */
            body {
                margin: 0; padding: 0; 
                width: 1024px; height: 620px; 
                overflow: hidden;
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                background-image: url('home-carvajal.jpg');
                background-size: cover;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            
            /* Contenedor central elegante */
            #contenedor-final {
                width: 50%;
                background: rgba(255, 255, 255, 0.9); 
                border-radius: 20px;
                box-shadow: 0 15px 35px rgba(0,0,0,0.2);
                padding: 40px;
                text-align: center;
                backdrop-filter: blur(5px);
            }
            
            h1 {
                color: #2c3e50;
                font-size: 40px;
                font-weight: bold;
                margin-bottom: 20px;
            }
            
            p {
                font-size: 22px;
                color: #555;
            }
        </style>
    </head>
    <body>

        <div id="contenedor-final">
            <h1>¡Gracias!</h1>
            <p>El proceso ha finalizado correctamente.</p>
            <p style="font-size: 16px; color: #888;">Cerrando sesión...</p>
        </div>

        <input type="image" src="imprimiendo.png" id="cerrar" style="display:none;" onclick="window.close();">

        <script type="text/javascript">
            // Espera 4 segundos para que el usuario lea el mensaje y luego cierra automáticamente
            window.setTimeout(function () {
                document.getElementById("cerrar").click();
            }, 4000);
        </script>
          
    </body>
</html>
