# Resultado de pruebas locales

Fecha: 31 de julio de 2026

## Ambiente

- PC servidor: Windows.
- IPv4 utilizada: `192.168.1.14`.
- Node.js: `v24.15.0`.
- API: `http://192.168.1.14:3000`.
- Front: `http://192.168.1.14:5500`.
- Base de datos: `Carvajal_HP_Presmed_Local`.
- File Server: `mock`.

## Resultado

| Prueba | Resultado |
| --- | --- |
| Health check local | Aprobada |
| Health check por IP LAN | Aprobada, HTTP 200 |
| Front por IP LAN | Aprobada, HTTP 200 |
| CORS desde el origen LAN | Aprobada |
| Usuario habilitado | Aprobada |
| Usuario no habilitado | Aprobada |
| Solicitud abierta | Aprobada |
| Solicitud cerrada | Aprobada |
| Consulta de adjuntos | Aprobada |
| Upload PDF mock | Aprobada |
| Fallo controlado de asociacion | Aprobada, HTTP 400 esperado |
| Delete compensatorio mock | Aprobada |

La prueba automatizada termino con seis de seis consultas aprobadas.

La prueba de rollback utilizo una solicitud inexistente para provocar el rechazo
de asociacion sin modificar solicitudes validas. El upload mock genero metadata
y el delete mock respondio correctamente.

## Alcance de la evidencia

Estos resultados validan el flujo tecnico local y la disponibilidad del PC como
servidor dentro de la red. No validan almacenamiento fisico en Coomeva, SP
preproductivos, autenticacion real ni captura desde el escaner HP/OXPd.

