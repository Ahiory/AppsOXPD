<?php
$para      = 'esneyder.sanchez@carvajal.com';
$titulo    = 'Recoleccion';
$mensaje   = 'Hola prueba de envio exitosa 7';
$cabeceras = 'From: esneyder.sanchez@carvajal.com' . "\r\n" .
    'Reply-To: esneyder.sanchez@carvajal.com' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

mail($para, $titulo, $mensaje, $cabeceras);
?>