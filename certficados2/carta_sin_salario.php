<html>

    <head>
        <link rel="stylesheet" type="text/css" href="estilo.css">
        <meta charset="Utf-8">
        <title>Certificación Laboral</title>
    </head>

    <body>
        <?php
        include 'conexion.php';


        $hoy = date("d");
        $mes = date("M");
        $anio = date("Y");

        $usuario =strtoupper ( $_POST['usuario']);
        $query = "SELECT nombre, cc, ciudad, cargo, fecha_ini from datos where usuario = '$usuario'";

        $result = mysqli_query($conexion, $query);
        $row = mysqli_fetch_array($result, MYSQLI_NUM);
        ?>
        <div id="tabla">
        </div>
        <table>
            <tr>


                <td>
                    <br> <br> <br>

                    <p>Santafé de Bogotá, <?php echo $hoy; ?> de <?php echo $mes; ?> de <?php echo $anio; ?> </p>
                </td>
            </tr>
            <tr>
                <td >
                    <p align="center">  <br> <br> <br> A QUIEN PUEDA INTERESAR:<br> <br> <br> <br></p>
                </td>
            </tr>
            <tr>
                <td>
                    <p>Certifica</p>
                    <p>

                        La empresa Carvajal con Nit 12345678 certifica que la señor(a) <?php echo "$row[0]"; ?> con cédula de ciudadanía <?php echo "$row[1] "; ?>de <?php echo " $row[2]"; ?> nos presta servicios profesionales como <?php echo " $row[3]"; ?> desde el <?php echo " $row[4]"; ?>. <br><br>

                        Para Información adicional, comuníquese a los teléfonos 5555555 ó 5555577. <br><br> <br> <br> <br><br><br>

                        Cordialmente,<br> <br> <br> <br> <br><br><br><br><br><br>

                        ROSA ELENA LÓPEZ MEJÍA<br>

                        Jefe de personal <br>

                    </p>
                </td>
            </tr>

        </table>


        <?php mysqli_close($conexion); ?>

    </body>

</html>
