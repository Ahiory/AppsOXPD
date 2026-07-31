# Entrega tecnica a Esneyder

Fecha de corte: 31 de julio de 2026

## Objetivo de la entrega

Transferir el prototipo funcional del panel HP para que Esneyder asuma la
continuidad, la prueba en impresora y la integracion con el ambiente
preproductivo de Coomeva/PRESMED.

## Alcance entregado

- Front modular para consulta de solicitud y adjuntos.
- API Node/Express conectada a SQL Server.
- Validacion de usuario y solicitud mediante SP mock.
- Consulta de tipos y adjuntos existentes mediante SP mock.
- Seleccion de un PDF para simular digitalizacion.
- Validacion de extension y limite de 20 MB en front y backend.
- Endpoint local de upload.
- Construccion del XML de adjuntos.
- Asociacion mediante `pru_solicitudes_auto_adjuntos` mock.
- Delete compensatorio cuando la asociacion falla despues del upload.
- Configuracion externa para SQL, CORS y File Server.
- Prueba automatizada de lectura con `npm.cmd run test:smoke`.

## Que se encuentra simulado

| Elemento | Comportamiento actual |
| --- | --- |
| Usuario del panel | `JMARQUEZ` asignado en el arranque del front |
| Solicitudes | Datos almacenados en `Carvajal_HP_Presmed_Local` |
| Estados | Registrada, Devuelta, En proceso, Aprobada y Anulada |
| Tipos de adjunto | HC, OM y ANX |
| Upload | Genera metadata y un filename aleatorio; no guarda el PDF |
| Delete | Responde correctamente; no elimina archivos fisicos |
| Asociacion | Persiste metadata en la base local mediante SP mock |
| Digitalizacion | Selector de archivos del navegador |
| Reemplazo | El SP mock permite cargar todos los tipos activos |

## Datos de prueba iniciales

| Caso | Valor | Resultado esperado |
| --- | --- | --- |
| Usuario habilitado | `JMARQUEZ` | Acceso permitido |
| Usuario no habilitado | `USRNOHAB` | Acceso rechazado |
| Solicitud registrada | `1001001` | Permite continuar |
| Solicitud devuelta | `1001002` | Permite continuar |
| Solicitud aprobada | `1001003` | Rechaza adjuntar |

La base usada durante desarrollo contiene cargas adicionales realizadas en las
pruebas. El seed original solamente crea una Historia Clinica de 3.20 MB para
la solicitud `1001001`.

## Responsabilidades posteriores

### Esneyder / Carvajal

- Ejecutar el checklist en la impresora HP.
- Obtener el usuario real desde el contexto de la impresora.
- Sustituir el selector de archivo por el mecanismo de captura HP/OXPd.
- Configurar el despliegue estable del front y de la API.
- Configurar CORS y reglas de red del ambiente asignado.
- Activar `FILE_SERVER_MODE=real` cuando Coomeva entregue los accesos.
- Ejecutar pruebas integrales y documentar evidencias.
- Coordinar correcciones surgidas en preproduccion.

### Coomeva

- Entregar acceso a SQL Server preproductivo.
- Confirmar nombres, parametros, permisos y respuestas de los SP reales.
- Entregar conectividad al File Server desde el servidor Coomeva.
- Confirmar URL de upload, URL de delete y autenticacion requerida.
- Confirmar el XML definitivo y el catalogo de tipos documentales.
- Entregar solicitudes y usuarios controlados para pruebas.
- Confirmar las reglas reales de carga y reemplazo.

## Criterio de cierre de esta etapa

Esta etapa queda cerrada cuando:

- La API y el front se ejecutan en el PC servidor.
- La impresora puede abrir `http://<IP_DEL_PC>:5500`.
- Desde la impresora se completa el flujo con File Server mock.
- Se adjuntan capturas o video corto como evidencia.
- Esneyder recibe repositorio, configuracion ejemplo y esta documentacion.

La prueba en impresora valida compatibilidad de navegador, red y experiencia del
panel. No constituye una prueba punta a punta con PRESMED mientras SQL y File
Server permanezcan en modo local/mock.

## Control de version antes de enviar

1. Revisar `git status` y excluir `.env`, logs y `node_modules`.
2. Confirmar que todos los archivos nuevos de `Api-local/src` estan incluidos.
3. Ejecutar `git diff --check`.
4. Ejecutar `npm.cmd run test:smoke` con la API iniciada.
5. Crear el commit de entrega en la rama acordada con el equipo.
6. Compartir el identificador del commit en el correo de transferencia.

