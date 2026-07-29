
/* Se inicia funcion   */
function getManufacturerInfo()
{var dfd = new $.Deferred();
    oxpddiscovery.GetOXPdDiscoveryTree()
        .then(function(discoveryTree){
            try
            { oxpddeviceinfo.Setup(discoveryTree);
                oxpddeviceinfo.GetManufacturerInfo()
                    .then(function(deviceinfo)
                    {
                        try
                        {   oxpddeviceinfo.GetManufacturerInfo();
                            dfd.resolve(); 
                            document.getElementById("SerialNumber").value = deviceinfo.deviceSerialNumber;
                            document.getElementById("ipAddress").value = deviceinfo.ipAddress;                          
                        }
                        catch(err)
                        {
                            console.log('ManufacturerInfo: ' + 'err.name="' + err.name + '", ' + 'err.message="' + err.message);
                            dfd.reject(err);
                        }
                    }
                    ); 
            }
            catch(err)
            {
                console.log('DiscoveryTree: ' + 'err.name="' + err.name + '", ' + 'err.message="' + err.message);
                dfd.reject(err);
            }
        } 
        );
    return dfd.promise();  
};
 
 
$(document).ready(function() {
    setTimeout(function() {
        $(".content").fadeOut(1500);
    },500);
	
    setTimeout(function() {
        $(".content2").fadeIn(200);
    },2000);
});