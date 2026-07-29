<?php
// 1. Obtenemos el número de documento desde la URL (enviado por JS)
$doc_identidad = isset($_GET['doc']) && $_GET['doc'] !== '' ? $_GET['doc'] : 'Sin_Documento';

// 2. Configuramos la ruta base y le agregamos la carpeta del documento al final
$target_dir = "C:\\Users\\esnsanma\\Desktop\\ScanHp\\" . $doc_identidad . "\\";

// Cambia "192.168.1.100" por la IP o nombre de tu servidor SMB y "ArchivosCredito" por el nombre de tu carpeta compartida.
//$target_dir = "\\\\192.168.1.100\\ArchivosCredito\\" . $doc_identidad . "\\";

// 3. Creamos la carpeta del documento si no existe (0777 otorga permisos, true permite subcarpetas)
if (!file_exists($target_dir)) {
    mkdir($target_dir, 0777, true);
}

// La impresora HP envía el archivo como un POST multipart
if (!empty($_FILES)) {
    foreach ($_FILES as $key => $file) {
        // Obtenemos el nombre original
        $filename = basename($file["name"]);
        
        // Expresión regular para limpiar cualquier sufijo numérico (-01) de HP
        $filename = preg_replace('/[-_]\d+(\.[a-zA-Z0-9]+)$/', '$1', $filename);
        
        // Ahora guardamos el archivo en la subcarpeta que acabamos de crear
        $target_file = $target_dir . $filename;
        
        if (move_uploaded_file($file["tmp_name"], $target_file)) {
            error_log("Archivo guardado exitosamente: " . $target_file);
        } else {
            error_log("Fallo al guardar el archivo: " . $target_file);
        }
    }
} else {
    // Si la impresora lo envía crudo en el body
    $input = file_get_contents('php://input');
    if (!empty($input)) {
        $filename = "ScanRaw_" . time() . ".pdf";
        file_put_contents($target_dir . $filename, $input);
    }
}

// HP requiere que el servidor responda con un 200 OK
http_response_code(200);
echo "Recibido";
?>