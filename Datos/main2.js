/* Se inicia funcion   */
function getCustomerInfo() {
    var dfd = new $.Deferred();
    oxpddiscovery.GetOXPdDiscoveryTree()
        .then(function(discoveryTree) {
            try {
                oxpddeviceinfo.Setup(discoveryTree);
                oxpddeviceinfo.GetCustomerInfo()
                    .then(function(deviceinfo) {
                        try {
                            oxpddeviceinfo.GetCustomerInfo();
                            dfd.resolve();
                            document.getElementById("companyContact").value = deviceinfo.companyContact;
                            document.getElementById("companyName").value = deviceinfo.companyName;
                            document.getElementById("deviceLocation").value = deviceinfo.deviceLocation;
                            document.getElementById("machineName").value = deviceinfo.machineName;
                            document.getElementById("assetNumber").value = deviceinfo.assetNumber;
                        } catch (err) {
                            console.log('CustomerInfo: ' + 'err.name="' + err.name + '", ' + 'err.message="' + err.message);
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