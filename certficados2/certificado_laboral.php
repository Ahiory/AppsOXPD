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
        $query = "SELECT nombre, cc, ciudad, cargo, fecha_ini, salario from datos where usuario = '$usuario'";

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

                         Que él(a) señor(a) <?php echo "$row[0]"; ?>, identificado(a) con cedula de ciudadanía No. <?php echo "$row[1] ";?> de la ciudad de <?php echo " $row[2]"; ?> labora en la Empresa desde <?php echo " $row[4] "; ?>a la fecha, desempeñando el cargo de <?php echo " $row[3]"; ?> con un contrato a término indefinido y devengando un salario mensual de $ <?php echo " $row[5]"; ?>.<br><br> <br> <br> <br><br><br>
                       
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
