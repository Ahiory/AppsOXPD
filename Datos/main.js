function getManufacturerInfo() {
    var dfd = new $.Deferred();
    oxpddiscovery.GetOXPdDiscoveryTree()
        .then(function(discoveryTree) {
            try {
                oxpddeviceinfo.Setup(discoveryTree);
                oxpddeviceinfo.GetManufacturerInfo()
                    .then(function(deviceinfo) {
                        try {
                            oxpddeviceinfo.GetManufacturerInfo();
                            dfd.resolve();
                            document.getElementById("SerialNumber").value = deviceinfo.deviceSerialNumber;
                            document.getElementById("ipAddress").value = deviceinfo.ipAddress;
                            document.getElementById("productNumber").value = deviceinfo.productNumber;
                            document.getElementById("modelName").value = deviceinfo.modelName;
                            document.getElementById("macAddress").value = deviceinfo.macAddress;
                            document.getElementById("hostName").value = deviceinfo.hostName;
                            document.getElementById("firmwareVersion").value = deviceinfo.firmwareVersion;
                            document.getElementById("deviceId").value = deviceinfo.deviceId;
                            document.getElementById("formatterSerialNumber").value = deviceinfo.formatterSerialNumber;
                        } catch (err) {
                            console.log('ManufacturerInfo: ' + 'err.name="' + err.name + '", ' + 'err.message="' + err.message);
                            dfd.reject(err);
                        }
                    });
            } catch (err) {
                console.log('DiscoveryTree: ' + 'err.name="' + err.name + '", ' + 'err.message="' + err.message);
                dfd.reject(err);
            }
        });
    return dfd.promise();
};