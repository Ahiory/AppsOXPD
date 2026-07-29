var main = function () {
    var instance = {};

    window.addEventListener('load', init);
    //inicializar funcion ,arbol de descubrimiento
    function init() {
        logger.Setup(".Estado");
        console.log("Iniciando...");

        var futureTree = oxpddiscovery.GetOXPdDiscoveryTree();

        futureTree.then(
            function (tree) {
                try {
                    oxpdprint.Setup(tree);
                    console.log("Lista");
                } catch (error) {
                    console.error(error);
                }
            },
            function (error) {
                console.error(error);
            }
        );
    };


    instance.print = function (document_location) {
        // Submit para mandar trabajo Onclick
        var futurePrint = oxpdprint.PrintUri({
            documentUri: document_location
        });

        // Momitor de trabajo 
        futurePrint.then(function (jobId) {
                var interval = setInterval(function () {
                    var futureJob = oxpdprint.GetJobAttributes(jobId);
                    futureJob.then(function (job) {

                            if (job.jobState === oxpdprint.JobState.Aborted ||
                                job.jobState === oxpdprint.JobState.Canceled ||
                                job.jobState === oxpdprint.JobState.Completed) {
                                // Trabajo se completo
                                console.log(document_location + " : " + job.jobStateReasons);
                                clearInterval(interval);

                            } else {
                                // El trabajo todavia esta siendo procesado
                                console.log(document_location + " : " + job.jobState + " Trabajo " + jobId + "...");
                            }
                        },
                        function (error) {
                            console.error(error);
                            clearInterval(interval);
                        })
                }, 200);
            },
            function (error) {
                console.error(error);
                alert("Uh oh...\nNO se ha podido localizar su trabajo\n\n" + document_location + "\n\n" + error);
            });
    };

    return instance;
}();
