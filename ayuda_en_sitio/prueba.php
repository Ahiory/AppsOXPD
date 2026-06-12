<html>
    <head>
        <title>Prueba2</title>
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
    <body>
      <input type=image src="salirr.png" style="position: absolute;left: 700px; top: 20px;" onclick="window.close();">
       Prueba<br>
       SerialNumber<input type="text" id="SerialNumber" name="serial">
       AssetNumber<input type="text" id="deviceLocation" name="deviceLocation"><br>
       CompanyContact<input type="text" id="machineName" name="machineName"><br>
    </body>
</html>