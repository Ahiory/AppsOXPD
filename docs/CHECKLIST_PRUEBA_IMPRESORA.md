# Checklist de prueba local desde impresora HP

Fecha: ____________________

Responsable: ____________________

Modelo y firmware de impresora: ____________________

IP del PC servidor: ____________________

IP de la impresora: ____________________

## Preparacion

- [ ] PC e impresora estan en la misma red.
- [ ] SQL Server local esta iniciado.
- [ ] La API esta iniciada en el puerto 3000.
- [ ] Live Server publica el front en el puerto 5500.
- [ ] `http://localhost:3000/health` responde en el PC.
- [ ] `http://<IP_DEL_PC>:3000/health` responde desde otro dispositivo.
- [ ] `http://<IP_DEL_PC>:5500` abre correctamente desde otro dispositivo.
- [ ] Los puertos 3000 y 5500 estan permitidos en el perfil de red privada.
- [ ] `FILE_SERVER_MODE=mock` para esta primera prueba.
- [ ] No se compartio el archivo `.env` con credenciales.

## Flujo principal

- [ ] La impresora abre `http://<IP_DEL_PC>:5500`.
- [ ] No aparecen errores de certificado, CORS o contenido bloqueado.
- [ ] El usuario mock `JMARQUEZ` es habilitado.
- [ ] La pantalla de solicitud se visualiza sin cortes ni solapamientos.
- [ ] La solicitud `1001001` carga afiliado, estado y adjuntos.
- [ ] La solicitud `1001002` carga afiliado, estado y adjuntos.
- [ ] La solicitud `1001003` muestra el bloqueo por estado Aprobada.
- [ ] Los botones pueden operarse desde la pantalla tactil.
- [ ] El boton Atras regresa a la consulta de solicitud.

## Archivo PDF

- [ ] El panel permite abrir el selector de archivo disponible en la impresora.
- [ ] Un PDF menor o igual a 20 MB es aceptado.
- [ ] Despues de asociar, se actualizan Ya cargado y Peso.
- [ ] El mismo tipo puede seleccionarse nuevamente para probar reemplazo mock.
- [ ] Un archivo no PDF es rechazado.
- [ ] Un PDF mayor de 20 MB es rechazado.
- [ ] Cancelar el selector no inicia ninguna carga.

Nota: si el navegador de la impresora no ofrece archivos al selector, registrar
el resultado como una limitacion esperada del simulador. La captura real debe
implementarse posteriormente con las capacidades HP/OXPd.

## Evidencias

- [ ] Foto de la pantalla inicial en la impresora.
- [ ] Foto de una solicitud valida.
- [ ] Foto de la tabla de adjuntos.
- [ ] Foto o video corto de la seleccion y carga del PDF.
- [ ] Captura de la respuesta del health check.
- [ ] Resultado de `npm.cmd run test:smoke`.
- [ ] Fecha, modelo, firmware e IP utilizadas quedaron registradas.

## Resultado

- [ ] Aprobado para transferencia a Esneyder.
- [ ] Aprobado con observaciones.
- [ ] Requiere ajuste antes de la transferencia.

Observaciones:

______________________________________________________________________________

______________________________________________________________________________

______________________________________________________________________________

