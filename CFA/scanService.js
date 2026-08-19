var ScanService = {
    Initialize: function () {
        var dfd = new $.Deferred();

        this.InitializeScanLibWithDiscoveryTree()
            .then(this.GetTransmissionModeProfile)
            .then(this.GetDefaultBasicOptions)
            .then(this.GetDefaultFileOptions)
            .then(function(){
                dfd.resolve();
            }, function(err){
                dfd.reject(err);
            });

        return dfd;
    },

    InitializeScanLibWithDiscoveryTree: function() {
        var dfd = new $.Deferred();
        oxpddiscovery.Setup(instance.configuration.ScanServiceHostName);
        oxpddiscovery.GetOXPdDiscoveryTree().then(
            function(discoveryTree){
                try{
                    oxpdscan.Setup(discoveryTree, instance.configuration.ScanServiceHostName);
                    dfd.resolve();
                } catch(err){
                    dfd.reject(err);
                }
            },
            function(err){ dfd.reject(err); }
        );
        return dfd.promise();
    },

    GetTransmissionModeProfile: function() {
        var dfd = new $.Deferred();
        oxpdscan.GetTransmissionModeProfile().then(function(profile){
            instance.configuration.ScanSetting.TransmissionMode = 'Job';
            if(profile.jobModeDestinationTypes === null){
                instance.configuration.ScanSetting.TransmissionMode = 'Image';
            }
            dfd.resolve();
        }, function(err){ dfd.reject(); });
        return dfd.promise();
    },

    GetDefaultBasicOptions: function() {
        var dfd = new $.Deferred();
        oxpdscan.GetDefaultBasicOptions(instance.configuration.ScanSetting.TransmissionMode).then(
            function(basicOptions){
                instance.configuration.basicOptions = basicOptions;               
                
                // Ocultar cuadro de diálogo nativo de HP
                instance.configuration.basicOptions.progressDialogMode = "Off"; 
                
                // Habilitar escaneo a doble cara usando la propiedad correcta: plexMode
                instance.configuration.basicOptions.plexMode = "Duplex";

                // Eliminar páginas en blanco usando la propiedad correcta: blankImageRemovalMode
                instance.configuration.basicOptions.blankImageRemovalMode = "On";

                // Establecer resolución
                instance.configuration.basicOptions.resolution = "Res300x300";

                dfd.resolve();               
            }, function(err){ dfd.reject(); });
        return dfd.promise();
    },

    GetDefaultFileOptions: function() {
        var dfd = new $.Deferred();
        oxpdscan.GetDefaultFileOptions(instance.configuration.FileType, instance.configuration.ColorMode).then(
            function(fileOptions){
                instance.configuration.fileOptions = fileOptions;
                dfd.resolve();
            }, function(err){ dfd.reject(); });
        return dfd.promise();
    },

    Scan: function (contextId, docType, fileNameBase, mapaTxt, configEscaneo) {        
        var dfd = new $.Deferred();
        
        // Llamamos al destino sin enviarle parámetros extra en la URL para evitar bloqueos del firmware
        var destination = this.BuildScanDestination();
        
        var scanTicket = this.BuildScanTicket(destination, docType, fileNameBase, mapaTxt, configEscaneo);

        oxpdscan.StartScanJob(contextId, scanTicket).then(
            function(scanJobId){ dfd.resolve(scanJobId); },
            function(err){ 
                dfd.reject(err);
            }
        );
        return dfd;
    },

    BuildScanDestination: function() {
        var t = instance.configuration.ScanTarget;
        
        // Dejamos el Endpoint limpio, sin inyectar variables en la URL
        var uri = t.Scheme + '://' + t.Host + (t.Port ? (':' + t.Port) : '') + t.Endpoint;
        
        return new oxpdscan.Destination(uri, t.Username, t.Password, t.Domain, t.ConnectionTimeout, t.ResponseTimeout, t.RetryCount, t.RetryInterval);
    },

    BuildScanTicket: function(destination, docType, fileNameBase, mapaTxt, configEscaneo) {
        if (docType == 'document') {
            instance.configuration.basicOptions.fileType = "Pdf";
            instance.configuration.fileOptions.pdfCompressionMode = "Normal";
            instance.configuration.fileOptions.tiffCompressionMode = "NotApplicable";
        }

        // --- APLICACIÓN ESTRICTA DE CONFIGURACIONES ---
        if (configEscaneo) {
            // 1. Resolución
            instance.configuration.basicOptions.resolution = configEscaneo.resolucion;
            
            // 2. Dúplex (Aplicando plexMode y el enumerador oxpdscan.PlexMode)
            if (configEscaneo.duplex === "Duplex") {
                instance.configuration.basicOptions.plexMode = (typeof oxpdscan.PlexMode !== 'undefined') 
                    ? oxpdscan.PlexMode.Duplex 
                    : "Duplex";
            } else {
                instance.configuration.basicOptions.plexMode = (typeof oxpdscan.PlexMode !== 'undefined') 
                    ? oxpdscan.PlexMode.Simplex 
                    : "Simplex";
            }

            // 3. Páginas en blanco (Aplicando blankImageRemovalMode)
            if (configEscaneo.blancas === "On") {
                instance.configuration.basicOptions.blankImageRemovalMode = (typeof oxpdscan.BlankImageRemovalMode !== 'undefined')
                    ? oxpdscan.BlankImageRemovalMode.On
                    : "On";
            } else {
                instance.configuration.basicOptions.blankImageRemovalMode = (typeof oxpdscan.BlankImageRemovalMode !== 'undefined')
                    ? oxpdscan.BlankImageRemovalMode.Off
                    : "Off";
            }
        }

        var contenidoTxt = mapaTxt;
        
        var txtMetadata = new oxpdscan.Metadata(
            null, 
            fileNameBase, 
            ".txt", 
            contenidoTxt
        );

        var ticket = new oxpdscan.ScanTicket(
            instance.configuration.basicOptions, 
            instance.configuration.fileOptions,
            destination,
            txtMetadata, 
            fileNameBase, 
            instance.configuration.ScanSetting.TransmissionMode
        );

        // Imprime en el log si la impresora acepta el ticket
        if (typeof logToScreen === 'function') {
            oxpdscan.ValidateScanTicket(ticket).then(
                function(validation) { logToScreen("Ticket Validado: " + (validation.isValid ? "Sí" : "No")); },
                function(err) { logToScreen("Error validando ticket: " + err); }
            );
        }

        return ticket;
    }
};