<?php
$env = parse_ini_file(__DIR__ . '/.env');
$contextId = '';
if (isset($_SERVER['HTTP_OXPD_UICONTEXTID'])) {
    $contextId = $_SERVER['HTTP_OXPD_UICONTEXTID'];
} else {
    if (function_exists('getallheaders')) {
        foreach (getallheaders() as $name => $value) {
            if (strtolower($name) === 'oxpd.uicontextid') {
                $contextId = $value;
                break;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title> FÁBRICA DE CRÉDITOS</title>

    <!-- Librerías OXPd -->
    <script src="libs/jquery-1.11.0.min.js"></script>
    <script src="libs/oxpddeviceinfo-2.6.0.min.js"></script>
    <script src="libs/oxpddiscovery-2.6.0.min.js"></script>
    <script src="libs/oxpdscan-2.6.0.min.js"></script>

    <script type="text/javascript">
        var INJECTED_CONTEXT_ID = "<?php echo htmlspecialchars($contextId); ?>";

        // Pasamos las variables del .env a JavaScript
        var APP_CONFIG = {
            scheme: "<?php echo htmlspecialchars($env['SCAN_SCHEME'] ?? 'http'); ?>",
            host: "<?php echo htmlspecialchars($env['SCAN_HOST'] ?? '192.168.137.1'); ?>",
            port: <?php echo htmlspecialchars($env['SCAN_PORT'] ?? 80); ?>,
            endpoint: "<?php echo htmlspecialchars($env['SCAN_ENDPOINT'] ?? '/CFA/receiver.php'); ?>"
        };
    </script>

    <script src="main.js?v=<?php echo filemtime(__DIR__ . '/main.js'); ?>"></script>
    <script src="scanService.js?v=<?php echo filemtime(__DIR__ . '/scanService.js'); ?>"></script>


    <style>
        /* 1. Definir Open Sans Regular (400) */
        @font-face {
            font-family: 'Open Sans';
            font-style: normal;
            font-weight: 400;
            src: url('fonts/OpenSans-Regular.ttf') format('truetype');
        }

        /* 2. Definir Open Sans SemiBold (600) */
        @font-face {
            font-family: 'Open Sans';
            font-style: normal;
            font-weight: 600;
            src: url('fonts/OpenSans-SemiBold.ttf') format('truetype');
        }

        /* 3. Definir Open Sans Bold (700) */
        @font-face {
            font-family: 'Open Sans';
            font-style: normal;
            font-weight: 700;
            src: url('fonts/OpenSans-Bold.ttf') format('truetype');
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Open Sans', sans-serif;
        }

        body {
            width: 800px;
            background-color: #005487;
            overflow-y: auto;
            -webkit-overflow-scrolling: touch;
            padding: 5px 20px 5px 20px;
            /* Arriba: 5px | Derecha: 25px | Abajo: 25px | Izquierda: 25px */
            text-align: center;
        }

        .header {
            margin-bottom: 25px;
        }

        .header h1 {
            color: #005487;
            font-size: 28px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .card-container {
            background-color: #ffffff;
            border-radius: 12px;
            border: 1px solid #cbd5e1;
            width: 100%;
            padding: 35px;
            margin-bottom: 20px;
            display: inline-block;
            text-align: left;
        }

        .form-grid {
            width: 100%;
        }

        .form-group {
            display: inline-block;
            width: 47%;
            vertical-align: top;
            margin-bottom: 20px;
        }

        .form-group:nth-child(odd) {
            margin-right: 4%;
        }

        .form-group label {
            display: block;
            font-size: 16px;
            color: #444;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .required {
            color: #CC3362;
            font-weight: bold;
        }

        .form-control {
            display: block;
            width: 100%;
            height: 55px;
            border: 2px solid #cbd5e1;
            border-radius: 8px;
            padding: 0 15px;
            font-size: 18px;
            color: #333;
            background-color: #f6f6f6;
            outline: none;
            -webkit-appearance: none;
        }

        .form-control:focus {
            border-color: #005487;
            background-color: #ffffff;
        }

        .fake-select {
            cursor: pointer;
            line-height: 51px;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23005487' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 15px center;
            background-size: 24px;
            padding-right: 45px;
            color: #888;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .fake-select.has-value {
            color: #333;
        }

        .footer-action {
            display: block;
            margin-top: 20px;
            padding-top: 25px;
            border-top: 1px solid #cbd5e1;
            text-align: center;
        }

        .footer-action span {
            font-size: 14px;
            color: #64748b;
            float: left;
            margin-top: 15px;
        }

        .btn-scan {
            background-color: #137906;
            color: #ffffff;
            border: none;
            border-radius: 8px;
            padding: 15px 40px;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
        }

        .btn-scan:active {
            background-color: #0091be;
        }

        #error-msg {
            color: #CC3362;
            font-weight: bold;
            float: left;
            margin-top: 15px;
            margin-right: 15px;
            display: none;
        }

        #touchOverlay {
            display: none;
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            min-height: 100%;
            background: #ffffff;
            z-index: 10000;
            padding: 20px;
            text-align: left;
        }

        #overlayTitle {
            font-size: 22px;
            color: #005487;
            font-weight: bold;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #005487;
        }

        .giant-option {
            display: block;
            width: 100%;
            padding: 14px 15px;
            margin-bottom: 10px;
            font-size: 22px;
            background-color: #f6f6f6;
            border: 2px solid #cbd5e1;
            border-radius: 8px;
            text-align: left;
            color: #333;
            font-weight: bold;
            cursor: pointer;
        }

        .giant-option:active {
            background-color: #e2e6f1;
            border-color: #0091be;
        }

        .giant-cancel {
            background-color: #CC3362;
            color: #ffffff;
            border-color: #CC3362;
            text-align: center;
            margin-top: 20px;
            width: 300px;
            margin: 0 auto;
        }

        /* Botones Deslizables para Configuración (Compatibilidad HP OXPd) */
        .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
            margin-top: 5px;
            cursor: pointer;
        }

        .switch input {
            display: none;
            /* Ocultamos el checkbox nativo de forma segura */
        }

        .slider {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #cbd5e1;
            -webkit-transition: 0.4s;
            transition: 0.4s;
            border-radius: 34px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            /* Usamos 'left' en vez de transform para navegadores embebidos */
            left: 4px;
            bottom: 4px;
            background-color: white;
            -webkit-transition: 0.4s;
            transition: 0.4s;
            border-radius: 50%;
        }

        /* Clases activas controladas por JS */
        .slider.activo {
            background-color: #009D9A;
        }

        .slider.activo:before {
            left: 30px;
        }
    </style>
</head>

<body>

    <!-- CONTENEDOR PRINCIPAL -->
    <div class="card-container" id="mainCard">
        <form id="scanForm" class="form-grid">

            <div class="form-group">
                <label>Oficina <span class="required">*</span></label>
                <!-- El select real se oculta para que no lance el menú nativo -->
                <select id="oficina" class="real-select" style="display:none;" data-required="true">
                    <option value="">Seleccione una oficina...</option>
                    <option value="18">ALPUJARRA</option>
                    <option value="22">ANDES</option>
                    <option value="42">APARTADO</option>
                    <option value="30">ARANJUEZ</option>
                    <option value="2">BARBOSA</option>
                    <option value="3">BARRANQUILLA</option>
                    <option value="40">BELLO</option>
                    <option value="38">BRICEÑO</option>
                    <option value="14">CALI</option>
                    <option value="8">CAMINO REAL</option>
                    <option value="54">CANAL DE RELACIONES INSTITUCIONALES</option>
                    <option value="57">CANAL EXTERNO</option>
                    <option value="59">CANAL EXTERNO MICRO</option>
                    <option value="61">CANAL EXTERNO MICRO BOGOTA</option>
                    <option value="27">CAUCASIA</option>
                    <option value="80">CENTRO DE EXPERIENCIA</option>
                    <option value="15">CISNEROS</option>
                    <option value="10">CIUDAD BOLIVAR</option>
                    <option value="32">CONCORDIA</option>
                    <option value="4">COPACABANA</option>
                    <option value="1">DON MATIAS</option>
                    <option value="43">ENGATIVA</option>
                    <option value="43">ENVIGADO</option>
                    <option value="53">EXITO CARTAGENA</option>
                    <option value="5">GIRARDOTA</option>
                    <option value="6">HISPANIA</option>
                    <option value="44">ITAGUI</option>
                    <option value="33">LA SIERRA PUERTO NARE</option>
                    <option value="34">LIBORINA</option>
                    <option value="25">MONTERIA</option>
                    <option value="49">OFICINA BOSA</option>
                    <option value="47">OFICINA EXITO BELEN</option>
                    <option value="50">OFICINA EXITO CUBA</option>
                    <option value="26">PEREIRA</option>
                    <option value="23">PUERTO BERRIO</option>
                    <option value="41">REMEDIOS</option>
                    <option value="20">RIONEGRO</option>
                    <option value="35">SALGAR</option>
                    <option value="36">SAN JERONIMO</option>
                    <option value="37">SAN JOSE DEL NUS</option>
                    <option value="11">SAN PEDRO</option>
                    <option value="12">SANTA FE DE ANTIOQUIA</option>
                    <option value="29">SANTA ROSA DE OSOS</option>
                    <option value="7">SEGOVIA</option>
                    <option value="13">SOPETRAN</option>
                    <option value="58">SUBA</option>
                    <option value="48">SURAMERICANA</option>
                    <option value="56">TERMINAL DEL NORTE</option>
                    <option value="28">URRAO</option>
                    <option value="24">YARUMAL</option>
                </select>
            </div>

            <div class="form-group">
                <label>Línea de Crédito <span class="required">*</span></label>
                <select id="linea_credito" class="real-select" style="display:none;" data-required="true">
                    <option value="">Seleccione línea...</option>
                    <option value="BANCO DISTRITAL">BANCO DISTRITAL</option>
                    <option value="CORRESPONSALES NO BANCARIOS-CNB">CORRESPONSALES NO BANCARIOS-CNB</option>
                    <option value="CREDIAPORTES COMERCIAL TASA FIJA">CREDIAPORTES COMERCIAL TASA FIJA</option>
                    <option value="CREDIAPORTES COMERCIAL TASA VARIABLE">CREDIAPORTES COMERCIAL TASA VARIABLE</option>
                    <option value="CREDIAPORTES CONSUMO TASA FIJA">CREDIAPORTES CONSUMO TASA FIJA</option>
                    <option value="CREDIAPORTES MICRO TASA FIJA">CREDIAPORTES MICRO TASA FIJA</option>
                    <option value="CREDIAPORTES TASA VARIABLE">CREDIAPORTES TASA VARIABLE</option>
                    <option value="CREDITO POPULAR PRODUCTIVO RURAL">CREDITO POPULAR PRODUCTIVO RURAL</option>
                    <option value="CREDITO POPULAR PRODUCTIVO URBANO">CREDITO POPULAR PRODUCTIVO URBANO</option>
                    <option value="CREDITO PRODUCTIVO DE MAYOR MONTO">CREDITO PRODUCTIVO DE MAYOR MONTO</option>
                    <option value="CREDITO PRODUCTIVO RURAL">CREDITO PRODUCTIVO RURAL</option>
                    <option value="CREDITO PRODUCTIVO URBANO">CREDITO PRODUCTIVO URBANO</option>
                    <option value="CUPO">CUPO</option>
                    <option value="CUPO ESTUDIANTIL">CUPO ESTUDIANTIL</option>
                    <option value="DECISION">DECISION</option>
                    <option value="ECONOMIA VERDE">ECONOMIA VERDE</option>
                    <option value="EMPLEADOS CFA">EMPLEADOS CFA</option>
                    <option value="ENERGIAS RENOVABLES BID">ENERGIAS RENOVABLES BID</option>
                    <option value="ESPECIAL ENVIGADO">ESPECIAL ENVIGADO</option>
                    <option value="ESPECIAL GIRARDOTA">ESPECIAL GIRARDOTA</option>
                    <option value="ESPECIAL MUJER">ESPECIAL MUJER</option>
                    <option value="ESPECIAL MUJER MICRO">ESPECIAL MUJER MICRO</option>
                    <option value="ESPECIAL PRODUCTIVO">ESPECIAL PRODUCTIVO</option>
                    <option value="ESPECIAL PRODUCTIVO SUSTITUTA">ESPECIAL PRODUCTIVO SUSTITUTA</option>
                    <option value="ESPECIAL PRODUCTIVO TASA FIJA">ESPECIAL PRODUCTIVO TASA FIJA</option>
                    <option value="FACTORING">FACTORING</option>
                    <option value="FINAGRO">FINAGRO</option>
                    <option value="FUENTE DE PAGO CDT">FUENTE DE PAGO CDT</option>
                    <option value="LIBRE INVERSION">LIBRE INVERSION</option>
                    <option value="LIBRE INVERSION TASA VARIABLE">LIBRE INVERSION TASA VARIABLE</option>
                    <option value="LINEA DE CONSUMO PARA MICROEMPRESARIOS">LINEA DE CONSUMO PARA MICROEMPRESARIOS
                    </option>
                    <option value="LINEA DONMATIAS EU">LINEA DONMATIAS EU</option>
                    <option value="MAS CUPO">MAS CUPO</option>
                    <option value="MAS CUPO AUTOMATICO">MAS CUPO AUTOMATICO</option>
                    <option value="MI PRIMER CREDITO">MI PRIMER CREDITO</option>
                    <option value="MI PRIMER CREDITO MICRO">MI PRIMER CREDITO MICRO</option>
                    <option value="MICRO VINCULACION CENTRALIZADA">MICRO VINCULACION CENTRALIZADA</option>
                    <option value="MIGRANTES VENEZOLANOS">MIGRANTES VENEZOLANOS</option>
                    <option value="NO VIS">NO VIS</option>
                    <option value="PERSONA JURIDICA">PERSONA JURIDICA</option>
                    <option value="POLIZAS DE SEGUROS">POLIZAS DE SEGUROS</option>
                    <option value="PREAPROBADOS">PREAPROBADOS</option>
                    <option value="PRODUCTIVO TASA FIJA">PRODUCTIVO TASA FIJA</option>
                    <option value="PRODUCTIVO TASA VARIABLE">PRODUCTIVO TASA VARIABLE</option>
                    <option value="RETANQUEO">RETANQUEO</option>
                    <option value="RETENCION ASOCIADO (CREDIAPORTES)">RETENCION ASOCIADO (CREDIAPORTES)</option>
                    <option value="SEGUROS PROVISION">SEGUROS PROVISION</option>
                    <option value="VALORA Y DE TRAYECTORIA">VALORA Y DE TRAYECTORIA</option>
                    <option value="VINCULACION CENTRALIZADA">VINCULACION CENTRALIZADA</option>
                    <option value="VINCULACION CENTRALIZADA PJ">VINCULACION CENTRALIZADA PJ</option>
                    <option value="VINCULACION CENTRALIZADA OTRAS LINEAS">VINCULACION CENTRALIZADA OTRAS LINEAS</option>
                    <option value="VIVIENDA DE INTERES SOCIAL">VIVIENDA DE INTERES SOCIAL</option>
                    <option value="WHATSAPP">WHATSAPP</option>
                </select>
            </div>

            <div class="form-group">
                <label for="monto_solicitado">Monto Solicitado <span class="required">*</span></label>
                <input type="text" id="monto_solicitado" class="form-control val-input" data-required="true"
                    placeholder="$ 5.000.000">
            </div>
            <div class="form-group">
                <label for="codigo_cliente">Código Cliente</label>
                <input type="number" id="codigo_cliente" class="form-control val-input" placeholder="Ingrese código">
            </div>

            <div class="form-group">
                <label for="doc_identidad">Doc. Identidad Cliente</label>
                <input type="tel" id="doc_identidad" class="form-control val-input" placeholder="Número de documento"
                    oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 15);">
            </div>

            <div class="form-group">
                <label>Tipo Documento <span class="required">*</span></label>
                <select id="tipo_documento" class="real-select" style="display:none;" data-required="true">
                    <option value="">Seleccione tipo...</option>
                    <option value="1">Solicitud de Crédito Solicitante</option>
                    <option value="2">Documento de Identidad</option>
                    <option value="3">Documentación Financiera Solicitante</option>
                    <option value="7">Solicitud Codeudor u Otro 1</option>
                    <option value="8">Documentación Financiera Codeudor u Otros 1</option>
                    <option value="12">Solicitud Codeudor u otro 2</option>
                    <option value="13">Documentación Financiera Codeudor u Otro 2</option>
                    <option value="18">Documentación Financiera Codeudor u Otro 3</option>
                    <option value="24">Pagaré</option>
                    <option value="30">Tratamientos de Datos</option>
                    <option value="34">Proyecto Planificación</option>
                    <option value="36">Informe de Visita y/o Confirmación de Arriendos</option>
                </select>
            </div>

            <div class="form-group">
                <label for="asesor">Asesor <span class="required">*</span></label>
                <!-- Se mantiene number y se añade oninput para controlar la longitud -->
                <input type="number" id="asesor" class="form-control val-input" data-required="true"
                    placeholder="Código de asesor"
                    oninput="if(this.value.length > 20) this.value = this.value.slice(0, 20);">
            </div>

            <div class="form-group">
                <label>Tipo de Persona <span class="required">*</span></label>
                <select id="tipo_persona" class="real-select" style="display:none;" data-required="true">
                    <option value="">Seleccione tipo...</option>
                    <option value="PN">Natural</option>
                    <option value="PJ">Jurídica</option>
                </select>
            </div>

            <div class="form-group">
                <label>Destino</label>
                <select id="destino" class="real-select" style="display:none;" data-required="false">
                    <option value="">Seleccione destino...</option>
                    <option value="COMPRA DE VIVIENDA / CONSTRUCCION VIVIENDA">COMPRA DE VIVIENDA / CONSTRUCCION
                        VIVIENDA</option>
                    <option value="CAPITAL DE TRABAJO">CAPITAL DE TRABAJO</option>
                    <option value="PAGO DE SEGUROS">PAGO DE SEGUROS</option>
                    <option value="COMPRA DE CARTERA">COMPRA DE CARTERA</option>
                    <option value="LIBRE DESTINACIÓN">LIBRE DESTINACIÓN</option>
                    <option value="VEHICULO">VEHICULO</option>
                    <option value="INVERSION EN PROYECTO (CODIGO CIIU)">INVERSION EN PROYECTO (CODIGO CIIU)</option>
                </select>
            </div>

            <!-- Separador visual -->
            <div class="form-group"
                style="width: 100%; border-top: 2px dashed #cbd5e1; margin-top: 10px; padding-top: 20px;">
                <h3 style="color: #005487; font-size: 18px;">Configuración de Escaneo</h3>
            </div>

            <!-- Select de Resolución (usará el mismo menú gigante de tu app) -->
            <div class="form-group">
                <label>Resolución</label>
                <select id="config_resolucion" class="real-select" style="display:none;" data-required="false">
                    <option value="Res300x300">300 ppi (Alta Calidad)</option>
                    <option value="Res400x400">400 ppi (Extra Calidad)</option>
                    <option value="Res200x200">200 ppi (Normal)</option>
                    <option value="Res150x150">150 ppi (Baja)</option>
                </select>
            </div>
            <!-- Botón Dúplex -->
            <div class="form-group" style="padding-top: 5px;">
                <label>Doble Cara (Dúplex)</label>
                <div class="switch">
                    <input type="checkbox" id="config_duplex" checked>
                    <span class="slider activo"></span>
                </div>
            </div>

            <!-- Botón Páginas en Blanco -->
            <div class="form-group" style="padding-top: 5px;">
                <label>Omitir Págs. Blancas</label>
                <div class="switch">
                    <input type="checkbox" id="config_blancas" checked>
                    <span class="slider activo"></span>
                </div>
            </div>

            <div style="clear: both;"></div>
        </form>

        <div class="footer-action">
            <span id="error-msg">Complete los campos en rojo.</span>
            <button type="button" class="btn-scan" onclick="validarYScanear()">Iniciar Escaneo</button>
        </div>
    </div>

    <!-- PANTALLA GIGANTE DE SELECCIÓN (Inicia Oculta) -->
    <div id="touchOverlay">
        <div id="overlayTitle">Seleccione una opción</div>
        <div id="overlayOptions"></div>
    </div>
    <!-- PANTALLAS DE ESTADO Y CIERRE -->
    <div id="statusOverlay"
        style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.85); z-index: 20000;">

        <div id="loadingBox" class="status-box"
            style="display:none; position: absolute; left: 50%; margin-left: -300px; top: 25%; background: white; padding: 40px; border-radius: 12px; text-align: center; width: 600px;">
            <h2 style="color: #005487; font-size: 28px; margin-bottom: 15px;">Enviando documentos...</h2>
            <p style="font-size: 18px; color: #555;">Por favor, espere un momento.</p>
        </div>

        <div id="successBox" class="status-box"
            style="display:none; position: absolute; left: 50%; margin-left: -300px; top: 15%; background: white; padding: 40px; border-radius: 12px; text-align: center; width: 600px;">
            <h2 style="color: #009D9A; font-size: 32px; margin-bottom: 15px;">¡Escaneo Exitoso!</h2>
            <p style="font-size: 24px; font-weight: 600; color: #333; margin: 25px 0;">¿Desea escanear otro documento?
            </p>
            <div style="margin-top: 35px; text-align: center;">
                <button onclick="resetApp()"
                    style="background-color: #009D9A; color: white; border: none; border-radius: 8px; padding: 15px 30px; font-size: 20px; font-weight: bold; cursor: pointer; width: 45%; display: inline-block; margin-right: 2%;">Sí,
                    continuar</button>
                <button onclick="window.close()"
                    style="background-color: #CC3362; color: white; border: none; border-radius: 8px; padding: 15px 30px; font-size: 20px; font-weight: bold; cursor: pointer; width: 45%; display: inline-block; margin-left: 2%;">No,
                    salir</button>
            </div>
        </div>

        <div id="errorBox" class="status-box"
            style="display:none; position: absolute; left: 50%; margin-left: -300px; top: 25%; background: white; padding: 40px; border-radius: 12px; text-align: center; width: 600px;">
            <h2 style="color: #CC3362; font-size: 28px; margin-bottom: 15px;">Error en el escaneo</h2>
            <p id="errorDetail" style="font-size: 18px; color: #555;">Hubo un problema al procesar el documento.</p>
            <button onclick="resetApp()"
                style="background-color: #64748b; color: white; border: none; border-radius: 8px; padding: 15px 30px; font-size: 18px; font-weight: bold; cursor: pointer; margin-top: 20px;">Volver</button>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('.real-select').each(function () {
                var $select = $(this);
                var defaultText = $select.find('option:first').text();

                var $fakeSelect = $('<div>', {
                    class: 'form-control fake-select',
                    text: defaultText
                });

                $select.after($fakeSelect);

                $fakeSelect.on('click', function () {
                    abrirMenuGigante($select, $fakeSelect);
                });
            });

            $('#monto_solicitado').on('keyup input', function () {
                var input = $(this).val();

                // 1. Limpiar todo lo que no sea número
                var numeros = input.replace(/\D/g, "");

                if (numeros === "") {
                    $(this).val("");
                    return;
                }

                // 2. Quitar ceros a la izquierda y aplicar puntos de mil
                numeros = parseInt(numeros, 10).toString();
                var formateado = numeros.replace(/\B(?=(\d{3})+(?!\d))/g, ".");

                // 3. Asignar el símbolo de pesos
                $(this).val("$ " + formateado);
            });

            // --- AQUÍ AGREGAMOS EL CÓDIGO PARA LOS BOTONES TÁCTILES HP ---
            $('.switch').on('click', function () {
                var $input = $(this).find('input');
                var $slider = $(this).find('.slider');

                // Si estaba encendido, lo apagamos
                if ($input.prop('checked')) {
                    $input.prop('checked', false);
                    $slider.removeClass('activo');
                }
                // Si estaba apagado, lo encendemos
                else {
                    $input.prop('checked', true);
                    $slider.addClass('activo');
                }
            });
            // --------------------------------------------------------------
        });

        // Variable global para recordar dónde estaba el usuario
        var scrollPosicion = 0;

        function abrirMenuGigante($select, $fakeSelect) {
            var $overlay = $('#touchOverlay');
            var $optionsContainer = $('#overlayOptions');

            $optionsContainer.empty();
            var titulo = $select.closest('.form-group').find('label').text().replace('*', '').trim();
            $('#overlayTitle').text(titulo);

            $select.find('option').each(function () {
                if ($(this).val() === '') return;

                var val = $(this).val();
                var text = $(this).text();

                var $btn = $('<button>', { class: 'giant-option', text: text });

                $btn.on('click', function () {
                    $select.val(val);
                    $fakeSelect.text(text).addClass('has-value').css('border-color', '#cbd5e1');
                    cerrarMenuGigante();
                });

                $optionsContainer.append($btn);
            });

            var $cancelBtn = $('<button>', { class: 'giant-option giant-cancel', text: 'Volver / Cancelar' });
            $cancelBtn.on('click', cerrarMenuGigante);
            $optionsContainer.append($cancelBtn);

            // NUEVO: Guardar la posición actual antes de ocultar la tarjeta principal
            scrollPosicion = $(window).scrollTop();

            $('.card-container').hide();
            $overlay.show();
            window.scrollTo(0, 0); // Esto sube el menú gigante para que se lea desde arriba
        }

        function cerrarMenuGigante() {
            $('#touchOverlay').hide();
            $('.card-container').show();
            // NUEVO: Restaurar el scroll a la posición exacta donde estaba el campo
            window.scrollTo(0, scrollPosicion);
        }

        function mostrarCargando() {
            $('#statusOverlay').css('display', 'block');
            $('.status-box').hide();
            $('#loadingBox').show();
        }

        function mostrarExito() {
            $('#statusOverlay').css('display', 'block');
            $('.status-box').hide();
            $('#successBox').show();
        }

        function mostrarError(detalle) {
            $('#statusOverlay').css('display', 'block');
            $('.status-box').hide();
            if (detalle) $('#errorDetail').text(detalle);
            $('#errorBox').show();
        }

        function resetApp() {
            $('#statusOverlay').hide();
            window.scrollTo(0, 0);
        }

        function validarYScanear() {
            var formValido = true;
            $('#error-msg').text("Complete los campos en rojo.");

            // 1. Validar selects
            $('.real-select').each(function () {
                var esRequerido = $(this).attr('data-required') === 'true';
                var $fakeSelect = $(this).next('.fake-select');
                if (esRequerido && $(this).val() === '') {
                    $fakeSelect.css('border-color', '#CC3362');
                    formValido = false;
                } else {
                    $fakeSelect.css('border-color', '#cbd5e1');
                }
            });

            // 2. Validar Inputs de Texto normales y Monto
            $('.val-input').each(function () {
                var esRequerido = $(this).attr('data-required') === 'true';
                var valorText = $(this).val().trim();
                var isMonto = $(this).attr('id') === 'monto_solicitado';

                if (esRequerido && valorText === '') {
                    $(this).css('border-color', '#CC3362'); // Error
                    formValido = false;
                } else if (isMonto && valorText !== '') {
                    // Extraer el número real quitando el '$' y los puntos
                    var valorNumerico = parseInt(valorText.replace(/\D/g, ""), 10);

                    // Validar límites: Mínimo 100.000 y Máximo 20.000.000.000
                    if (valorNumerico < 100000 || valorNumerico > 20000000000) {
                        $(this).css('border-color', '#CC3362');
                        formValido = false;
                        $('#error-msg').text("El monto debe estar entre $100.000 y $20.000.000.000.");
                    } else {
                        $(this).css('border-color', '#cbd5e1');
                    }
                } else {
                    $(this).css('border-color', '#cbd5e1'); // Normal
                }
            });

            // 3. Ejecutar escaneo FUERA de los ciclos .each()
            if (formValido) {
                $('#error-msg').hide();

                // Capturar todos los valores del formulario
                var oficinaVal = $('#oficina').val();
                var lineaCredito = $('#linea_credito').val();
                var montoSolicitado = $('#monto_solicitado').val().replace(/\D/g, "");
                var codCliente = $('#codigo_cliente').val() || "";
                var docIdentidad = $('#doc_identidad').val() || "Sin_Documento";
                var asesor = $('#asesor').val();
                var tipoPersona = $('#tipo_persona').val();

                // Nuevo campo: Número del documento de fábrica
                // TODO: Vincular a un select en la UI (ej. $('#tipo_doc_fabrica').val())
                // Capturamos el valor (1, 2, 7, 24, etc.) del select "Tipo Documento"
                var numDocFabrica = $('#tipo_documento').val();

                // Construir la cadena del mapa separada por comas exactamente en el orden requerido
                var mapaTxt = [
                    oficinaVal,       // 0: Número de oficina
                    lineaCredito,     // 1: Línea de crédito
                    montoSolicitado,  // 2: Monto solicitado
                    codCliente,       // 3: Código de asociado
                    docIdentidad,     // 4: Cédula o nit
                    numDocFabrica,    // 5: Número del documento de fábrica (1, 2, 3...)
                    asesor,           // 6: Código de Asesor
                    tipoPersona       // 7: Pn o PJ
                ].join(",");

                // El nombre base de ambos archivos (PDF y TXT) será el id del tipo de documento
                var fileNameBase = numDocFabrica;
                // --- NUEVO: Capturar configuraciones de los botones ---
                var configEscaneo = {
                    resolucion: $('#config_resolucion').val() || "Res300x300",
                    duplex: $('#config_duplex').is(':checked') ? "Duplex" : "Simplex",
                    blancas: $('#config_blancas').is(':checked') ? "On" : "Off"
                };

                // Pasamos la cadena completa (mapaTxt) a la función principal
                ScanDocument('document', fileNameBase, mapaTxt, configEscaneo);
            } else {
                $('#error-msg').show();
            }
        }

        // Verificar que el objeto OXPd esté disponible (para evitar errores si abres la app en un navegador de PC)
        if (window.OXPd !== undefined && window.OXPd !== null) {
            // Cambiar el color de fondo de la barra superior al azul de tu diseño
            window.OXPd.headerBackgroundColor = '#005487'; // Cambiar el color del texto y los iconos a blanco para mantener el contraste
            window.OXPd.headerForegroundColor = '#FFFFFF';
            window.OXPd.footerCollapsed = true;
        }

    </script>
</body>

</html>