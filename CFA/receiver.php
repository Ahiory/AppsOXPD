<?php
// 1. Leemos el archivo de configuración .env
$env = parse_ini_file(__DIR__ . '/.env');

// 2. Obtenemos la ruta base
$base_dir = isset($env['SAVE_PATH']) ? $env['SAVE_PATH'] : "C:\\Users\\esnsanma.CARVAJAL\\Desktop\\ScanHp\\";

// Valores por defecto en caso de emergencia
$oficina = 'Sin_Oficina';
$doc_identidad = 'Sin_Documento';

if (!empty($_FILES)) {
    
    // PASO A: Buscar el archivo TXT para extraer los datos ANTES de crear carpetas
    foreach ($_FILES as $key => $file) {
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        if (strtolower($ext) === 'txt') {
            // Leemos el contenido temporal del archivo de texto
            $contenido = file_get_contents($file['tmp_name']);
            $datos = explode(',', $contenido);
            
            // Validamos que existan los índices 0 (Oficina) y 4 (Documento)
            if (isset($datos[0]) && trim($datos[0]) !== '') {
                $oficina = trim($datos[0]);
            }
            if (isset($datos[4]) && trim($datos[4]) !== '') {
                $doc_identidad = trim($datos[4]);
            }
            break; // Terminamos de buscar, ya tenemos los datos
        }
    }

    // PASO B: Construir la ruta y crear carpetas dinámicamente con los datos reales
    $target_dir = rtrim($base_dir, "\\/") . DIRECTORY_SEPARATOR . $oficina . DIRECTORY_SEPARATOR . $doc_identidad . DIRECTORY_SEPARATOR;
    
    if (!file_exists($target_dir)) {
        if (!mkdir($target_dir, 0777, true)) {
            // Imprime el error si no puede crear la carpeta
            $error = error_get_last();
            echo "<h3 style='color:red;'>Error creando carpeta: " . $error['message'] . "</h3>";
            echo "<p>Ruta intentada: " . $target_dir . "</p>";
        }
    }

    // PASO C: Mover todos los archivos (PDF y TXT) a la carpeta final
    foreach ($_FILES as $key => $file) {
        $filename = basename($file["name"]);
        $filename = preg_replace('/[-_]\d+(\.[a-zA-Z0-9]+)$/', '$1', $filename);
        $target_file = $target_dir . $filename;
        
        if (move_uploaded_file($file["tmp_name"], $target_file)) {
            echo "<p style='color:green;'>Archivo guardado: " . $target_file . "</p>";
        } else {
            // Imprime el error si no puede mover el archivo
            echo "<p style='color:red;'>Error al guardar el archivo: " . $target_file . " (Verifica permisos de red)</p>";
        }
    }
} else {
    // Si la impresora lo envía crudo en el body (poco probable que pase con metadatos)
    $input = file_get_contents('php://input');
    if (!empty($input)) {
        $target_dir = rtrim($base_dir, "\\/") . DIRECTORY_SEPARATOR . $oficina . DIRECTORY_SEPARATOR . $doc_identidad . DIRECTORY_SEPARATOR;
        if (!file_exists($target_dir)) mkdir($target_dir, 0777, true);
        
        $filename = "ScanRaw_" . time() . ".pdf";
        file_put_contents($target_dir . $filename, $input);
    }
}

// HP requiere que el servidor responda obligatoriamente con un código 200 OK
http_response_code(200);
echo "Recibido";
?>