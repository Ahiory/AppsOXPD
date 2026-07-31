# HP Presmed - Integracion documental

Prototipo funcional para ejecutar el panel HP desde un navegador o una impresora
en la misma red del PC. El PC aloja el front, la API Node/Express y SQL Server.

## Estado del desarrollo

El flujo local disponible es:

1. Validar usuario mediante `prs_valida_usuario_soli_externo`.
2. Consultar solicitud mediante `prs_valida_solicitudes_auto_externo`.
3. Consultar adjuntos mediante `prs_solicitudes_auto_adjuntos_externo`.
4. Seleccionar un PDF para simular la digitalizacion.
5. Validar extension PDF y tamano maximo de 20 MB.
6. Subir el archivo por `POST /api/files/upload`.
7. Construir el XML y asociarlo por `POST /api/adjuntos/asociar`.
8. Ejecutar `DELETE /api/files/delete` si falla la asociacion.

El flujo fue validado localmente y desde un celular conectado a la misma red.
El File Server, la identidad de la impresora y la captura del escaner siguen
simulados.

## Componentes

- `hp-panel-app`: front JavaScript modular para el panel.
- `Api-local`: API Node/Express y adaptador del File Server.
- `db-local`: tablas, datos de prueba y procedimientos almacenados mock.
- `docs`: checklist de impresora y documento de transferencia.

## Requisitos

- Node.js 20 o superior.
- SQL Server accesible desde el PC.
- VS Code con Live Server, o un servidor HTTP equivalente para el front.
- PC e impresora conectados a la misma red.
- Puertos TCP 3000 y 5500 habilitados en la red privada del PC.

## Preparar la base local

Ejecutar en este orden:

1. `db-local/001_create_db_tables.sql`
2. `db-local/002_create_datosMocks.sql`
3. `db-local/100_sp_valida_usuario.sql`
4. `db-local/110_sp_valida_solicitud.sql`
5. `db-local/120_sp_consulta_adjuntos_mock.sql`
6. `db-local/130_sp_asocia_adjunto_mock.sql`

Los scripts de datos no son idempotentes. En una instalacion nueva deben
ejecutarse una sola vez.

## Configurar la API

Desde `Api-local`:

```powershell
Copy-Item .env.example .env
npm.cmd install
npm.cmd run dev
```

Completar en `.env` las credenciales de SQL Server. El archivo esta excluido de
Git y no debe entregarse con secretos reales.

Para la prueba local desde la impresora se puede mantener:

```dotenv
NODE_ENV=development
CORS_ALLOW_ALL=true
FILE_SERVER_MODE=mock
```

Para preproduccion se debe usar `CORS_ALLOW_ALL=false` y declarar en
`CORS_ORIGINS` solamente la URL desde la cual se publicara el panel.

## Iniciar el front

Abrir `hp-panel-app/index.html` con Live Server. Verificar primero en el PC:

```text
http://localhost:5500
http://localhost:3000/health
```

Consultar la IPv4 del PC con `ipconfig`. Desde otro dispositivo de la red usar:

```text
http://<IP_DEL_PC>:5500
http://<IP_DEL_PC>:3000/health
```

La API del front se resuelve automaticamente con la misma IP desde la que fue
abierto el panel y el puerto 3000.

## Prueba rapida

Con la API iniciada:

```powershell
cd Api-local
npm.cmd run test:smoke
```

La prueba solo realiza consultas. No carga archivos ni modifica la base de
datos.

## Datos mock principales

- Usuario habilitado: `JMARQUEZ`.
- Usuario no habilitado: `USRNOHAB`.
- Solicitud registrada: `1001001`.
- Solicitud devuelta: `1001002`.
- Solicitud aprobada y bloqueada: `1001003`.
- Tipos: Historia Clinica (`HC`), Orden Medica (`OM`) y Anexo (`ANX`).
- Adjunto inicial: Historia Clinica de `1001001`, 3.20 MB.
- File Server: upload y delete simulados sin almacenamiento fisico.

La base conserva los resultados de las pruebas. Para una demostracion limpia se
debe reinstalar el esquema o preparar datos nuevos controlados.

## Activar el File Server real

Se requiere confirmacion de Coomeva antes de cambiar el modo:

```dotenv
FILE_SERVER_MODE=real
FILE_SERVER_BASE_URL=http://<SERVIDOR>:<PUERTO>
FILE_SERVER_UPLOAD_PATH=/API/FILESERVER/Upload
FILE_SERVER_DELETE_PATH=<RUTA_CONFIRMADA_POR_COOMEVA>
FILE_SERVER_FULLPATH_PREFIX=PruebasCoreInfomedical/SolicitudesAuto
```

No activar `real` hasta tener conectividad, contrato de delete, autenticacion y
datos de preproduccion confirmados.

## Limitaciones conocidas

- El usuario `JMARQUEZ` esta asignado temporalmente en el front.
- El selector de PDF simula la captura; no inicia el escaner HP/OXPd.
- El mock de File Server no almacena ni elimina archivos fisicos.
- `PermiteReemplazo` no se aplica; el SP mock permite cargar todos los tipos.
- No se ha probado upload, asociacion ni rollback contra servicios de Coomeva.
- La API local no incorpora autenticacion propia y solo debe exponerse en una
  red controlada de pruebas.

El detalle del traspaso esta en `docs/ENTREGA_ESNEYDER.md`.

