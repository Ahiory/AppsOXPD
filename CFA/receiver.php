<?php
// 1. Leemos el archivo de configuración .env
$env = parse_ini_file(__DIR__ . '/.env');

// 2. Obtenemos la ruta base desde el .env (apuntando al servidor SMB)[cite: 1, 5]
$base_dir = isset($env['SAVE_PATH']) ? $env['SAVE_PATH'] : "\\\\10.91.1.29\\img_ricoh\\CREDITOS\\";

// 3. Obtenemos el número de documento y la oficina desde la URL (enviados por JS)[cite: 5]
$doc_identidad = isset($_GET['doc']) && $_GET['doc'] !== '' ? $_GET['doc'] : 'Sin_Documento';
$oficina = isset($_GET['oficina']) && $_GET['oficina'] !== '' ? $_GET['oficina'] : 'Sin_Oficina';

// 4. Configuramos la ruta final con la estructura: img_ricoh/CREDITOS/#OFICINA/CEDULA/[cite: 5]
// rtrim limpia barras sobrantes y DIRECTORY_SEPARATOR maneja las diagonales correctamente
$target_dir = rtrim($base_dir, "\\/") . DIRECTORY_SEPARATOR . $oficina . DIRECTORY_SEPARATOR . $doc_identidad . DIRECTORY_SEPARATOR;

// 5. Creamos la estructura de carpetas si no existe (0777 otorga permisos, true permite subcarpetas recursivas)[cite: 5]
if (!file_exists($target_dir)) {
    mkdir($target_dir, 0777, true);
}

// 6. La impresora HP envía los archivos como un POST multipart[cite: 2, 5]
if (!empty($_FILES)) {
    foreach ($_FILES as $key => $file) {
        // Obtenemos el nombre original del archivo[cite: 5]
        $filename = basename($file["name"]);
        
        // Expresión regular para limpiar cualquier sufijo numérico (-01) generado por HP[cite: 5]
        $filename = preg_replace('/[-_]\d+(\.[a-zA-Z0-9]+)$/', '$1', $filename);
        
        // Guardamos el archivo en la subcarpeta correspondiente[cite: 5]
        $target_file = $target_dir . $filename;
        
        if (move_uploaded_file($file["tmp_name"], $target_file)) {
            error_log("Archivo guardado exitosamente: " . $target_file);
        } else {
            error_log("Fallo al guardar el archivo: " . $target_file);
        }
    }
} else {
    // Si la impresora lo envía de forma cruda en el body[cite: 5]
    $input = file_get_contents('php://input');
    if (!empty($input)) {
        $filename = "ScanRaw_" . time() . ".pdf";
        file_put_contents($target_dir . $filename, $input);
    }
}

// 7. HP requiere que el servidor responda obligatoriamente con un código 200 OK[cite: 5]
http_response_code(200);
echo "Recibido";
?>