<?php
// 1. Leemos el archivo de configuración .env
$env = parse_ini_file(__DIR__ . '/.env');

// 2. Obtenemos la ruta base
$base_dir = isset($env['SAVE_PATH']) ? $env['SAVE_PATH'] : "C:\\Users\\esnsanma.CARVAJAL\\Desktop\\ScanHp\\";

// Valores por defecto
$oficina = 'Sin_Oficina';
$doc_identidad = 'Sin_Documento';
$tipo_doc = 'Documento';

// NUEVO: Variable de control para saber si TODO el proceso fue exitoso
$guardado_exitoso = true; 

if (!empty($_FILES)) {
    
    // PASO A: Extraer datos del TXT
    foreach ($_FILES as $key => $file) {
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        if (strtolower($ext) === 'txt') {
            $contenido = file_get_contents($file['tmp_name']);
            $datos = explode(',', $contenido);
            
            if (isset($datos[0]) && trim($datos[0]) !== '') $oficina = trim($datos[0]);
            if (isset($datos[4]) && trim($datos[4]) !== '') $doc_identidad = trim($datos[4]);
            if (isset($datos[5]) && trim($datos[5]) !== '') $tipo_doc = trim($datos[5]);
            break; 
        }
    }

    // PASO B: Construir y crear ruta
    $target_dir = rtrim($base_dir, "\\/") . DIRECTORY_SEPARATOR . $oficina . DIRECTORY_SEPARATOR . $doc_identidad . DIRECTORY_SEPARATOR;
    
    if (!file_exists($target_dir)) {
        if (!mkdir($target_dir, 0777, true)) {
            $guardado_exitoso = false; // Falló al crear la carpeta
        }
    }

    // PASO C: Mover archivos solo si la carpeta existe/fue creada
    if ($guardado_exitoso) {
        foreach ($_FILES as $key => $file) {
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = $tipo_doc . '.' . strtolower($ext);
            $target_file = $target_dir . $filename;
            
            if (!move_uploaded_file($file["tmp_name"], $target_file)) {
                $guardado_exitoso = false; // Falló al mover el archivo a la ruta final
            }
        }
    }
} else {
    // Escaneo raw
    $input = file_get_contents('php://input');
    if (!empty($input)) {
        $target_dir = rtrim($base_dir, "\\/") . DIRECTORY_SEPARATOR . $oficina . DIRECTORY_SEPARATOR . $doc_identidad . DIRECTORY_SEPARATOR;
        if (!file_exists($target_dir) && !mkdir($target_dir, 0777, true)) {
            $guardado_exitoso = false;
        }
        
        $filename = "ScanRaw_" . time() . ".pdf";
        if ($guardado_exitoso && file_put_contents($target_dir . $filename, $input) === false) {
             $guardado_exitoso = false;
        }
    } else {
        $guardado_exitoso = false; // No llegó ningún archivo
    }
}

// NUEVO: Respuesta estricta hacia la impresora HP
if ($guardado_exitoso) {
    http_response_code(200);
    echo "Recibido y guardado exitosamente";
} else {
    http_response_code(500); // Esto fuerza a la impresora a detectar un fallo de transmisión
    echo "Error interno: No se pudo guardar en la ruta de red";
}
?>