<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<title>Encuesta de Servicio</title>
	<!-- Librerías OXPd -->
	<script async src="libs/jquery-1.11.0.min.js"></script>
	<script async src="libs/oxpddeviceinfo-2.6.0.min.js"></script>
	<script async src="libs/oxpddiscovery-2.6.0.min.js"></script>
	<script async src="libs/oxpdprint-2.6.0.min.js"></script>
	<script async src="libs/oxpdscan-2.6.0.min.js"></script>

	<style>
    /* Diseño Ajustado para Paneles de Impresora OXPd */
    body {
        background-color: #ffffff;
        /* Fondo blanco total para no ver cortes */
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        color: #333;
        margin: 0;
        padding: 15px;
        /* Margen interno suave */
        /* Se elimina height: 100vh y flexbox en el body para evitar desbordes */
    }

    .container {
        width: 100%;
        margin: 0 auto;
        margin-top: 80px; /* Esta es la línea nueva que empuja todo hacia abajo */
        text-align: center;
        position: relative;
    }

    .step {
        display: none;
    }

    .step.active {
        display: block;
        animation: fadeIn 0.4s;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(5px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    h2 {
        color: #005487;
        margin-bottom: 20px;
        font-size: 26px;
    }

    /* Color alineado con tu nuevo header */
    h3 {
        color: #555;
        margin-bottom: 20px;
        font-weight: normal;
        font-size: 20px;
    }

    /* Grilla de Botones Forzada con Inline-Block para máxima compatibilidad */
    .options-grid {
        text-align: center;
        /* Centra los elementos hijos */
        width: 100%;
        margin-top: 20px;
    }

    .card-btn {
        border: 2px solid; /* El color se define ahora en cada tarjeta */
        border-radius: 12px;
        padding: 40px 10px; 
        cursor: pointer;
        display: inline-block; 
        vertical-align: top;
        width: 30%; 
        margin: 0 1%; 
        box-sizing: border-box;
        box-shadow: 0 4px 10px rgba(0,0,0,0.05); 
        transition: all 0.1s ease; /* Transición suave para el toque */
    }
    
    /* Nuevos fondos y bordes suaves para cada opción */
    .card-muy {
        background-color: #EAFDF4; /* Verde menta muy pálido */
        border-color: #A9DFBF;
    }
    .card-satis {
        background-color: #FEF7EC; /* Naranja durazno muy pálido */
        border-color: #F8C471;
    }
    .card-insatis {
        background-color: #FDEDEC; /* Rojo rosado muy pálido */
        border-color: #F5B7B1;
    }
    
    .card-btn img { 
        max-width: 130px; /* Tamaño máximo de las caras */
        margin-bottom: 20px; /* Más espacio entre la cara y el texto */
    }

    .card-btn span { 
        display: block; 
        font-size: 24px; /* Fuente mucho más grande */
        font-weight: bold; 
    }

    .btn-muy { color: #27AE60; }
    .btn-satis { color: #E67E22; }
    .btn-insatis { color: #E74C3C; }

    /* --- ESTILO 3D PARA LOS BOTONES SECUNDARIOS --- */
    .list-btn {
        display: block;
        width: 90%;
        max-width: 400px;
        margin: 0 auto 12px auto;
        padding: 15px;
        font-size: 18px;
        color: #fff;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        
        /* Efecto 3D (Sombra sólida) */
        position: relative;
        box-shadow: 0 6px 0px rgba(0, 0, 0, 0.25); 
        transition: all 0.1s ease;
    }

    .btn-green { background-color: #27AE60; }
    .btn-orange { background-color: #E67E22; }
    .btn-red { background-color: #E74C3C; }
    .btn-dark {
        background-color: #005487;
        width: 120px;
        margin: 0 10px;
        display: inline-block;
    }

    .btn-back {
        background-color: #95A5A6; 
        color: #ffffff; 
        border: none;
        border-radius: 8px;
        padding: 15px 40px; 
        font-size: 22px; 
        font-weight: bold;
        cursor: pointer;
        margin-top: 35px; 
        text-decoration: none; 
        
        /* Efecto 3D (Sombra sólida) */
        position: relative;
        box-shadow: 0 6px 0px rgba(0, 0, 0, 0.25); 
        transition: all 0.1s ease;
    }

    .close-btn-img {
        position: absolute;
        top: -60px; /* Ajusta este número si lo quieres más arriba o más abajo */
        right: 20px; 
        cursor: pointer;
        max-width: 60px; 
    }

    /* --- EFECTOS MECÁNICOS AL PRESIONAR EL DEDO TÁCTIL (:active) --- */
    
    /* Botones 3D: Se hunden hacia abajo y reducen su sombra */
    .list-btn:active, .btn-back:active {
        transform: translateY(4px); 
        box-shadow: 0 2px 0px rgba(0, 0, 0, 0.25); 
    }

    /* Cambio de color al presionar los botones */
    .btn-green:active { background-color: #1E8449; }
    .btn-orange:active { background-color: #D35400; }
    .btn-red:active { background-color: #C0392B; }
    .btn-dark:active { background-color: #003d63; }
    .btn-back:active { background-color: #7F8C8D; }
    
    /* Las tarjetas principales se encogen un poco y bajan su opacidad al tocarlas */
    .card-btn:active {
        transform: scale(0.96);
        opacity: 0.8;
    }
</style>
</head>

<body onload="getManufacturerInfo();">

	<div class="container">
		<!-- Puedes quitar este botón si el usuario ya cierra desde el botón físico/nativo del panel -->
		<img src="cerrar.png" class="close-btn-img" onclick="window.close();" alt="Cerrar">

		<form id="encuestaForm" action="procesar.php" method="POST">
			<!-- Campos ocultos para BD -->
			<input type="hidden" id="SerialNumber" name="serial">
			<input type="hidden" id="ipAddress" name="ip">
			<input type="hidden" id="inputSatisfaccion" name="satisfaccion">
			<input type="hidden" id="inputRazon" name="razon">
			<input type="hidden" id="inputServicio" name="servicio">

			<!-- PASO 1 -->
            <div id="step1" class="step active">
                <h2>Encuesta de Satisfacción</h2>
                <h3>Seleccione una opción:</h3>
                <div class="options-grid">
                    <!-- Agregamos la clase card-muy -->
                    <div class="card-btn card-muy" onclick="setSatisfaccion('1', 'step2_muy')">
                        <img src="muy satisfecho.png" alt="Muy Satisfecho">
                        <span class="btn-muy">Muy Satisfecho</span>
                    </div>
                    <!-- Agregamos la clase card-satis -->
                    <div class="card-btn card-satis" onclick="setSatisfaccion('2', 'step2_satis')">
                        <img src="satisfecho.png" alt="Satisfecho">
                        <span class="btn-satis">Satisfecho</span>
                    </div>
                    <!-- Agregamos la clase card-insatis -->
                    <div class="card-btn card-insatis" onclick="setSatisfaccion('3', 'step2_insatis')">
                        <img src="insatisfecho.png" alt="Insatisfecho">
                        <span class="btn-insatis">Insatisfecho</span>
                    </div>
                </div>
            </div>

			<!-- PASO 2: Muy Satisfecho -->
			<div id="step2_muy" class="step">
				<h2>Muy Satisfecho</h2>
				<h3>¿Cuál es la razón principal?</h3>
				<button type="button" class="list-btn btn-green" onclick="setRazon('Es facil de usar')">Es fácil de
					usar</button>
				<button type="button" class="list-btn btn-green" onclick="setRazon('Siempre esta disponible')">Siempre
					está disponible</button>
				<button type="button" class="list-btn btn-green" onclick="setRazon('Cumple con las necesidades')">Cumple
					con las necesidades</button>
				<button type="button" class="btn-back" onclick="goBack('step1')">Volver</button>
			</div>

			<!-- PASO 2: Satisfecho -->
			<div id="step2_satis" class="step">
				<h2>Satisfecho</h2>
				<h3>¿Cuál es la razón principal?</h3>
				<button type="button" class="list-btn btn-orange"
					onclick="setRazon('Algunas opciones no son faciles de usar')">Opciones no son fáciles de
					usar</button>
				<button type="button" class="list-btn btn-orange"
					onclick="setRazon('Algunas veces no esta disponble')">A veces no está disponible</button>
				<button type="button" class="list-btn btn-orange"
					onclick="setRazon('No siempre cumple con las necesidades')">No siempre cumple las
					necesidades</button>
				<button type="button" class="btn-back" onclick="goBack('step1')">Volver</button>
			</div>

			<!-- PASO 2: Insatisfecho -->
			<div id="step2_insatis" class="step">
				<h2>Insatisfecho</h2>
				<h3>¿Cuál es la razón principal?</h3>
				<button type="button" class="list-btn btn-red" onclick="setRazon('No es facil de usar')">No es fácil de
					usar</button>
				<button type="button" class="list-btn btn-red" onclick="setRazon('Nunca esta disponible')">Nunca está
					disponible</button>
				<button type="button" class="list-btn btn-red" onclick="setRazon('No cumple con las necesidades')">No
					cumple con las necesidades</button>
				<button type="button" class="btn-back" onclick="goBack('step1')">Volver</button>
			</div>

			<!-- PASO 3 -->
			<div id="step3" class="step">
				<h2>Recomendación</h2>
				<h3>¿Recomendaría el servicio de impresión Carvajal?</h3>
				<div style="margin-top: 20px;">
					<button type="button" class="list-btn btn-dark" onclick="submitForm('Si')">Sí</button>
					<button type="button" class="list-btn btn-dark" onclick="submitForm('No')">No</button>
				</div>
				<br>
				<button type="button" class="btn-back" onclick="goBack('step1')">Volver al Inicio</button>
			</div>
		</form>
	</div>

	<script type="text/javascript">
		// 1. Configuración de Interfaz OXPd inyectada por jQuery
		$(document).ready(function () {
			if (window.OXPd !== undefined && window.OXPd !== null) {
				window.OXPd.headerBackgroundColor = '#005487';
				window.OXPd.headerForegroundColor = '#FFFFFF';
				window.OXPd.footerCollapsed = true; // Fundamental para ganar espacio vertical
			}
		});

		// 2. Captura de Datos Nativos
		function getManufacturerInfo() {
			var dfd = new $.Deferred();
			try {
				oxpddiscovery.GetOXPdDiscoveryTree().then(function (discoveryTree) {
					oxpddeviceinfo.Setup(discoveryTree);
					oxpddeviceinfo.GetManufacturerInfo().then(function (deviceinfo) {
						document.getElementById("SerialNumber").value = deviceinfo.deviceSerialNumber;
						document.getElementById("ipAddress").value = deviceinfo.ipAddress;
						dfd.resolve();
					}).catch(function (err) { console.log(err); });
				}).catch(function (err) { console.log(err); });
			} catch (e) { console.log("Fuera del entorno OXPd o error de carga."); }
			return dfd.promise();
		}

		// 3. Control de Navegación de la SPA
		function hideAllSteps() {
			$('.step').removeClass('active');
		}

		function setSatisfaccion(valor, nextStepId) {
			document.getElementById('inputSatisfaccion').value = valor;
			hideAllSteps();
			document.getElementById(nextStepId).classList.add('active');
		}

		function setRazon(valor) {
			document.getElementById('inputRazon').value = valor;
			hideAllSteps();
			document.getElementById('step3').classList.add('active');
		}

		function submitForm(valorServicio) {
			document.getElementById('inputServicio').value = valorServicio;
			document.getElementById('encuestaForm').submit();
		}

		function goBack(stepId) {
			hideAllSteps();
			document.getElementById(stepId).classList.add('active');
		}
	</script>
</body>

</html>