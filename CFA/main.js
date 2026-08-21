var instance = {
    configuration: {
        ScanServiceHostName: null,
        FileType: 'Pdf',
        ColorMode: 'Auto',
        ScanSetting: {
            TransmissionMode: 'Job',
            FileNameBase: 'Prueba_Escaneo' 
        },
        ScanTarget: {
            Scheme: typeof APP_CONFIG !== 'undefined' ? APP_CONFIG.scheme : 'http',
            Host: typeof APP_CONFIG !== 'undefined' ? APP_CONFIG.host : '192.168.137.1', 
            Port: typeof APP_CONFIG !== 'undefined' ? APP_CONFIG.port : 80,
            Endpoint: typeof APP_CONFIG !== 'undefined' ? APP_CONFIG.endpoint : '/CFA/receiver.php',
            Username: null, Password: null, Domain: null,
            ConnectionTimeout: 60, ResponseTimeout: 120, RetryCount: 0, RetryInterval: 60
        }
    },
    UIContextId: null 
};

function logToScreen(msg) {
    var panel = document.getElementById('debug-panel');
    if (panel) {
        panel.innerHTML += "<span>> " + msg + "</span><br/>";
        panel.scrollTop = panel.scrollHeight;
    }
}

function RetrieveUIContext() {
    logToScreen("Buscando UIContextId en Headers HTTP...");

    if (typeof INJECTED_CONTEXT_ID !== 'undefined' && INJECTED_CONTEXT_ID !== '') {
        logToScreen("¡Contexto encontrado desde PHP!");
        return INJECTED_CONTEXT_ID;
    }

    var regex = new RegExp('[\\?&](uiContextId)=([^&#]*)', 'i'); 
    var results = regex.exec(location.search);
    if (results !== null) return decodeURIComponent(results[2].replace(/\+/g, ' '));

    return null;
}

$(document).ready(function() {
    logToScreen("Iniciando aplicación OXPd...");
    
    instance.UIContextId = RetrieveUIContext();
    
    if (instance.UIContextId) {
        logToScreen("ÉXITO: Contexto capturado: " + instance.UIContextId);
    } else {
        logToScreen("CRÍTICO: No se encontró contexto de sesión.");
        instance.UIContextId = "00000000-0000-0000-0000-000000000000"; 
    }
    
    ScanService.Initialize().then(
        function() { logToScreen("Inicialización Exitosa."); },
        function(err) { logToScreen("ERROR en Init."); }
    );
});

// Cambiamos el tercer parámetro a 'mapaTxt'
function ScanDocument(DocumentType, fileNameBase, mapaTxt,configEscaneo) {
    if (instance.UIContextId === "00000000-0000-0000-0000-000000000000") {
        logToScreen("<span style='color:orange;'>Advertencia: Intentando escanear sin Contexto válido. Probablemente falle.</span>");
    }
    
    if (typeof mostrarCargando === 'function') {
        mostrarCargando();
    }
    
    // Le pasamos el mapa completo a ScanService
    var dfd = ScanService.Scan(instance.UIContextId, DocumentType, fileNameBase, mapaTxt,configEscaneo);
    
    dfd.then(function(scanJobId) {
        instance.ScanJobId = scanJobId;
        monitorearEscaneo(scanJobId);
    }, function(err) {
        logToScreen("<span style='color:red;'>FALLA StartScanJob</span>");
    });
}

function monitorearEscaneo(jobId) {
    oxpdscan.GetScanJobStatus(jobId).then(function(status) {
        
        var state = status.resultCode || status.ResultCode || status.state;
        
        // Agregamos 'Processing' por si la HP usa ese estado intermedio
        if (state === 'Pending' || state === oxpdscan.ResultCode.Pending || state === 'Processing') {
            setTimeout(function() { monitorearEscaneo(jobId); }, 2000);
        } else if (state === 'Succeeded' || state === oxpdscan.ResultCode.Succeeded) {
            if (typeof mostrarExito === 'function') mostrarExito();
        } else {
            // NUEVO: Oculta el "cargando" y muestra el error real al asesor
            logToScreen("Finalizado con estado: " + state);
            if (typeof mostrarError === 'function') {
                mostrarError("No se pudo guardar el archivo. Verifica la ruta de red poseidon. (Estado HP: " + state + ")");
            }
        }
        
    }, function(err) {
        // CORRECCIÓN: Ahora muestra error en lugar de mostrarExito()
        logToScreen("Error consultando estado: " + err);
        if (typeof mostrarError === 'function') {
            mostrarError("Error interno al comunicarse con el escáner.");
        }
    });
}
