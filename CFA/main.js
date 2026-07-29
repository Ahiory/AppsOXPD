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
            Scheme: 'http',
            Host: '192.168.2.5', 
            Port: 80,
            Endpoint: '/CFA/receiver.php',
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
function ScanDocument(DocumentType, fileNameBase, mapaTxt) {
    if (instance.UIContextId === "00000000-0000-0000-0000-000000000000") {
        logToScreen("<span style='color:orange;'>Advertencia: Intentando escanear sin Contexto válido. Probablemente falle.</span>");
    }
    
    if (typeof mostrarCargando === 'function') {
        mostrarCargando();
    }
    
    // Le pasamos el mapa completo a ScanService
    var dfd = ScanService.Scan(instance.UIContextId, DocumentType, fileNameBase, mapaTxt);
    
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
        
        if (state === 'Pending' || state === oxpdscan.ResultCode.Pending) {
            setTimeout(function() { monitorearEscaneo(jobId); }, 2000);
        } else if (state === 'Succeeded' || state === oxpdscan.ResultCode.Succeeded) {
            if (typeof mostrarExito === 'function') mostrarExito();
        } else {
            logToScreen("Finalizado con estado: " + state);
        }
        
    }, function(err) {
        if (typeof mostrarExito === 'function') mostrarExito();
    });
}