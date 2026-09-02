function getManufacturerInfo() {
    
    // PRUEBA 1: oxpddiscovery (Base para las demás)
    oxpddiscovery.GetOXPdDiscoveryTree().done(function(discoveryTree) {
        document.getElementById("statusDiscovery").innerHTML = "OK ✓";
        document.getElementById("statusDiscovery").style.color = "green";

        try {
            // Inicializar las 3 librerías de servicio
            oxpddeviceinfo.Setup(discoveryTree);
            oxpdprint.Setup(discoveryTree);
            oxpdscan.Setup(discoveryTree);

            // PRUEBA 2: oxpddeviceinfo
            oxpddeviceinfo.GetManufacturerInfo().done(function(deviceinfo) {
                document.getElementById("statusDeviceInfo").innerHTML = "OK ✓";
                document.getElementById("statusDeviceInfo").style.color = "green";
                
                // Llenar HTML
                document.getElementById("SerialNumber").innerHTML = deviceinfo.deviceSerialNumber;
                document.getElementById("hostName").innerHTML = deviceinfo.hostName;
                document.getElementById("firmwareVersion").innerHTML = deviceinfo.firmwareVersion;
                document.getElementById("formatterSerialNumber").innerHTML = deviceinfo.formatterSerialNumber;
                document.getElementById("ipAddress").innerHTML = deviceinfo.ipAddress;
                document.getElementById("macAddress").innerHTML = deviceinfo.macAddress;
                document.getElementById("deviceId").innerHTML = deviceinfo.deviceId;
            }).fail(function(err) {
                document.getElementById("statusDeviceInfo").innerHTML = "ERROR: " + err.message;
                document.getElementById("statusDeviceInfo").style.color = "red";
            });

            // PRUEBA 3: oxpdprint
            oxpdprint.GetPrinterAttributes().done(function(printAttr) {
                document.getElementById("statusPrint").innerHTML = "OK ✓ (" + printAttr.printerState + ")";
                document.getElementById("statusPrint").style.color = "green";
            }).fail(function(err) {
                document.getElementById("statusPrint").innerHTML = "ERROR: " + err.message;
                document.getElementById("statusPrint").style.color = "red";
            });

            // PRUEBA 4: oxpdscan
            oxpdscan.GetScannerStatus().done(function(scanStatus) {
                document.getElementById("statusScan").innerHTML = "OK ✓";
                document.getElementById("statusScan").style.color = "green";
            }).fail(function(err) {
                document.getElementById("statusScan").innerHTML = "ERROR: " + err.message;
                document.getElementById("statusScan").style.color = "red";
            });

        } catch (setupError) {
            // Este catch atrapa errores si la impresora dice que no soporta el servicio
            document.getElementById("statusDeviceInfo").innerHTML = "ERROR SETUP: " + setupError.message;
            document.getElementById("statusDeviceInfo").style.color = "red";
            document.getElementById("statusPrint").innerHTML = "Cancelado por error de Setup";
            document.getElementById("statusScan").innerHTML = "Cancelado por error de Setup";
        }

    }).fail(function(err) {
        // Si falla Discovery, ninguna otra funciona.
        document.getElementById("statusDiscovery").innerHTML = "ERROR: " + err.message;
        document.getElementById("statusDiscovery").style.color = "red";
        
        document.getElementById("statusDeviceInfo").innerHTML = "Fallo por Discovery";
        document.getElementById("statusPrint").innerHTML = "Fallo por Discovery";
        document.getElementById("statusScan").innerHTML = "Fallo por Discovery";
    });
}