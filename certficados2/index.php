<html>
    <head>
        <title>Certificados</title>
        <link rel="stylesheet" href="estilos.css">
    </head>
    <body background="home-carvajal.jpg" style="background-size: cover;">
        <input type="image" src="cancelar.png" style="position: absolute;left: 88%; top: 20px; width: 50px; height: 50px;" onclick="window.close();">

        <?php
        
        
        $host = $_SERVER['HTTP_HOST'];
        $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        $base = "http://" . $host . $uri . "/";
        
        echo "Host: $host <br> Uri: $uri <br> Base: $base <br>";
        echo __FILE__;
        
        
        
        require './vendor/autoload.php';

        use Spipu\Html2Pdf\Html2Pdf;

       if (isset($_POST['crear'])) {
            ob_start();
            require_once 'carta_con_salario.php';
            $html = ob_get_clean();

            $html2pdf = new Html2Pdf('P', 'ANSI_A', 'es', 'true', 'UTF-8');

            $html2pdf->writeHTML($html);
            $html2pdf->output('/home/u111477705/domains/desarrolloscarvajal.com/public_html/desarrollos/certificados2/certificados/carta_con_salario.pdf', 'f');
           
            ob_start();
            require_once 'carta_sin_salario.php';
            $html = ob_get_clean();

            $html2pdf = new Html2Pdf('P', 'ANSI_A', 'es', 'true', 'UTF-8');

            $html2pdf->writeHTML($html);
           
 
           $html2pdf->output('/home/u111477705/domains/desarrolloscarvajal.com/public_html/desarrollos/certificados2/certificados/carta_sin_salario.pdf', "f");
           
            ob_start();
            require_once 'certificado_laboral.php';
            $html = ob_get_clean();

            $html2pdf = new Html2Pdf('P', 'ANSI_A', 'es', 'true', 'UTF-8');

            $html2pdf->writeHTML($html);
           
 
           $html2pdf->output('/home/u111477705/domains/desarrolloscarvajal.com/public_html/desarrollos/certificados2/certificados/certificado_laboral.pdf', "f");
           
                               include 'conexion.php';
           
           $usuario = strtoupper ( $_POST['usuario']);
           
           $verifica_usuario = mysqli_query($conexion, "SELECT * FROM datos WHERE usuario =  '$usuario'");
           
        
           if (mysqli_num_rows($verifica_usuario)>0){

               header("location:seleccion_documentos.php");
            }else
           {
               header("location:incorrecto.php");
           }
            
        }
        
        
        ?>

        <div id="div1" >

         <h1>Certificados Laborales</h1>

            <form action="" method="POST">
                <input type="text" placeholder="Usuario" name="usuario" id="usuario" >
                <input type="submit" value="Enviar" name="crear" id="enviar">
            </form>    

        </div>

    </body>

</html>