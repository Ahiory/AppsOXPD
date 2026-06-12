/* Se inicia funcion   */
function getCustomerInfo()
{
    var dfd = new $.Deferred();
    oxpddiscovery.GetOXPdDiscoveryTree()
            .then(function (discoveryTree) {
                try
                {
                    oxpddeviceinfo.Setup(discoveryTree);
                    oxpddeviceinfo.GetCustomerInfo()
                            .then(function (deviceinfo)
                            {
                                try
                                {
                                    oxpddeviceinfo.GetCustomerInfo();
                                    dfd.resolve();
                                    document.getElementById("deviceLocation").value = deviceinfo.deviceLocation;
                                    document.getElementById("machineName").value = deviceinfo.machineName;
                                } catch (err)
                                {
                                    console.log('CustomerInfo: ' + 'err.name="' + err.name + '", ' + 'err.message="' + err.message);
                                    dfd.reject(err);
                                }
                            }
                            );
                } catch (err)
                {
                    console.log('DiscoveryTree: ' + 'err.name="' + err.name + '", ' + 'err.message="' + err.message);
                    dfd.reject(err);
                }
            }
            );
    return dfd.promise();
}
;

/////
