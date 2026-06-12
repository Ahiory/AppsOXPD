<html>
    <head>
          <link rel="stylesheet" type="text/css" href="style.css">
       	<link rel="stylesheet" href="code/bootstrap.min.css">
        <link rel="stylesheet" href="code/font-awesome.min.css" >
        <link rel="stylesheet" href="assets/css/styles.css">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        
        <title></title>
    </head>
    <body background="fondo3.jpg">
      <input type=image src="salirr.png" style="position: absolute;left: 700px; top: 20px;" onclick="window.close();">
       <form action="guarda_bd.php" method="post">
        <div>
            <font face="verdana" size="3.5" style="text-align: center;">
            <span>¿Que problema está generando la Multifuncional?</span>
            </font>
        </div>
        <div>
           <table>
            <tr><td><input type="submit" class="buttonn buttonn-orange" name="falla" value="Está presentando atascos al escanear"><br></td>
            <td><input type="submit" class="buttonn buttonn-orange" name="falla" value="Está presentando atascos al imprimir"><br></td></tr>
            <tr><td><input type="submit" class="buttonn buttonn-orange" name="falla" value="Se agotó un suministro"><br></td>
            <td><input type="submit" class="buttonn buttonn-orange" name="falla" value="Se agotó el papel"><br></td></tr>
                        <tr><td><input type="submit" class="buttonn buttonn-orange" name="falla" value="Escanea con líneas verticales negras"><br></td>
            <td><input type="submit" class="buttonn buttonn-orange" name="falla" value="Arruga o rasga las hojas"><br></td></tr>
            </table>
        </div>
        </form>
    </body>
</html>