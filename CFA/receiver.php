<?php
// 1. Leemos el archivo de configuración .env
$env = parse_ini_file(__DIR__ . '/.env');

// 2. Obtenemos la ruta base desde el .env
// (Se mantiene un fallback por seguridad, puedes quitarlo si prefieres que sea estricto)
$base_dir = isset($env['SAVE_PATH']) ? $env['SAVE_PATH'] : "C:\\Users\\esnsanma\\Desktop\\ScanHp\\";

// 3. Obtenemos el número de documento desde la URL (enviado por JS)
$doc_identidad = isset($_GET['doc']) && $_GET['doc'] !== '' ? $_GET['doc'] : 'Sin_Documento';

// 4. Configuramos la ruta final concatenando la ruta base y la carpeta del documento
// rtrim limpia barras sobrantes y DIRECTORY_SEPARATOR pone la barra correcta (\ en Windows, / en Linux)
$target_dir = rtrim($base_dir, "\\/") . DIRECTORY_SEPARATOR . $doc_identidad . DIRECTORY_SEPARATOR;

// 5. Creamos la carpeta del documento si no existe (0777 otorga permisos, true permite subcarpetas)
if (!file_exists($target_dir)) {
    mkdir($target_dir, 0777, true);
}

// 6. La impresora HP envía el archivo como un POST multipart
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

// 7. HP requiere que el servidor responda con un 200 OK
http_response_code(200);
echo "Recibido";
?>