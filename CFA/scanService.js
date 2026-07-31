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

    // --- AQUÍ EMPIEZAN LOS CAMBIOS PARA CREACIÓN DE CARPETAS ---
    
    Scan: function (contextId, docType, fileNameBase, mapaTxt) {        
        var dfd = new $.Deferred();
        
        // 1. Extraemos el documento de identidad desde mapaTxt
        // Al separar por comas, el doc_identidad AHORA queda en el índice 4
        var arregloDatos = mapaTxt.split(',');
        var docIdentidad = arregloDatos[4] || 'Sin_Documento';

        // 2. Le pasamos el documento a la función que construye el destino
        var destination = this.BuildScanDestination(docIdentidad);
        
        var scanTicket = this.BuildScanTicket(destination, docType, fileNameBase, mapaTxt);

        oxpdscan.StartScanJob(contextId, scanTicket).then(
            function(scanJobId){ dfd.resolve(scanJobId); },
            function(err){ 
                dfd.reject(err);
            }
        );
        return dfd;
    },

    // 3. Recibimos el docIdentidad y lo agregamos como parámetro a la URL
    BuildScanDestination: function(docIdentidad) {
        var t = instance.configuration.ScanTarget;
        
        // Inyectamos el parámetro ?doc= al final del Endpoint
        var uri = t.Scheme + '://' + t.Host + (t.Port ? (':' + t.Port) : '') + t.Endpoint + '?doc=' + encodeURIComponent(docIdentidad);
        
        return new oxpdscan.Destination(uri, t.Username, t.Password, t.Domain, t.ConnectionTimeout, t.ResponseTimeout, t.RetryCount, t.RetryInterval);
    },

    BuildScanTicket: function(destination, docType, fileNameBase, mapaTxt) {
        if (docType == 'document') {
            instance.configuration.basicOptions.fileType = "Pdf";
            instance.configuration.fileOptions.pdfCompressionMode = "Normal";
            instance.configuration.fileOptions.tiffCompressionMode = "NotApplicable";
        }

        // El contenido del TXT ahora es directamente nuestra cadena formateada
        var contenidoTxt = mapaTxt;
        
        // Instanciamos la clase Metadata con el nombre base (ej. "18")
        var txtMetadata = new oxpdscan.Metadata(
            null, 
            fileNameBase, 
            ".txt", 
            contenidoTxt
        );

        // Retornamos el ticket
        return new oxpdscan.ScanTicket(
            instance.configuration.basicOptions, 
            instance.configuration.fileOptions,
            destination,
            txtMetadata, 
            fileNameBase, 
            instance.configuration.ScanSetting.TransmissionMode
        );
    }
};