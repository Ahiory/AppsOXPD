<html>
    <head>
        <title></title>
            <link rel="stylesheet" type="text/css" href="style.css">
            <!--Se agregan las librerias para la toma de informacion servicios- manejar localmente o server 
                LIbreria principal-->
            <script src="libs/jquery-1.11.0.min.js"></script>
            <!-- Libreria para llamar informacion basica de impresora -->
            <script src="libs/oxpddeviceinfo-2.0.0.min.js"></script>
            <!-- Libreria llama a metodo basico para descubrir cada elemento de impresora-->
            <script src="libs/oxpddiscovery-2.0.0.min.js"></script>
            <!--Libreria para llamar funciones de impresion basicas -->
            <script src="libs/oxpdprint-2.0.0.min.js"></script>
            <!--Libreria para scanear ,llama a metodos y tikets -->
            <script src="libs/oxpdscan-2.0.0.min.js"></script>
            <!--llama funcion donde se ejecutan datos -->
            <script src="main.js"></script>
                      <script src="main2.js"></script>
                       <script type="text/javascript"> 
window.onload = function() {
  //funciones a ejecutar
  getManufacturerInfo();
  getCustomerInfo();
  };
</script>
    </head>
    <body background="fondo2.jpg">
     <form action="usuario_s.php" method="post">
         <span>Digite su usuario de red para que lo podamos contactar<br></span>
      
       <input type="text" id="SerialNumber" name="serial" style="visibility:hidden"><br>
       <input type="text" id="ipAddress" name="ip" style="visibility:hidden"><br>
       <input type="text" id="deviceLocation" name="deviceLocation" style="visibility:hidden"><br>
       <input type="text" id="machineName" name="machineName" style="visibility:hidden"><br>
      
      
           <input class="text" type="text" name="usuario" placeholder="Usuario"><br>
           <input class="button button-green" type="submit" name="enviar" value="Enviar">
       
      <input type=image src="salirr.png" style="position: absolute;left: 700px; top: 20px;" onclick="window.close();"> 
     </form>
    </body>
</html>