USE Carvajal_HP_Presmed_Local;
GO

CREATE OR ALTER PROCEDURE dbo.prs_valida_solicitudes_auto_externo
    @prm_SOLI_AUTO BIGINT
AS
BEGIN
    SET NOCOUNT ON;

    IF @prm_SOLI_AUTO IS NULL OR @prm_SOLI_AUTO <= 0
    BEGIN
        SELECT
            1 AS CODIGO,
            CAST(NULL AS BIGINT) AS SOLICITUD,
            'NO EXISTE' AS DESCRIPCION,
            CAST(NULL AS VARCHAR(50)) AS ESTADO_SOLICITUD,
            CAST(NULL AS VARCHAR(200)) AS NOMBRE_AFILIADO,
            'NÚMERO DE SOLICITUD NO INFORMADO.' AS MENSAJE;
        RETURN;
    END

    IF NOT EXISTS (
        SELECT 1
        FROM dbo.SolicitudesAutoLocal
        WHERE SolicitudAuto = @prm_SOLI_AUTO
    )
    BEGIN
        SELECT
            1 AS CODIGO,
            CAST(NULL AS BIGINT) AS SOLICITUD,
            'NO EXISTE' AS DESCRIPCION,
            CAST(NULL AS VARCHAR(50)) AS ESTADO_SOLICITUD,
            CAST(NULL AS VARCHAR(200)) AS NOMBRE_AFILIADO,
            'La solicitud ingresada no existe. Por favor validar.' AS MENSAJE;
        RETURN;
    END

    IF EXISTS (
        SELECT 1
        FROM dbo.SolicitudesAutoLocal s
        JOIN dbo.EstadosSolicitudLocal e ON e.CodigoEstado = s.CodigoEstado
        WHERE s.SolicitudAuto = @prm_SOLI_AUTO
          AND e.PermiteAdjuntar = 1
    )
    BEGIN
        SELECT
            0 AS CODIGO,
            s.SolicitudAuto AS SOLICITUD,
            'EXISTE' AS DESCRIPCION,
            e.NombreEstado AS ESTADO_SOLICITUD,
            s.NombreAfiliado AS NOMBRE_AFILIADO,
            CAST(NULL AS VARCHAR(200)) AS MENSAJE
        FROM dbo.SolicitudesAutoLocal s
        JOIN dbo.EstadosSolicitudLocal e ON e.CodigoEstado = s.CodigoEstado
        WHERE s.SolicitudAuto = @prm_SOLI_AUTO;

        RETURN;
    END

    SELECT
        1 AS CODIGO,
        s.SolicitudAuto AS SOLICITUD,
        'NO EXISTE' AS DESCRIPCION,
        e.NombreEstado AS ESTADO_SOLICITUD,
        s.NombreAfiliado AS NOMBRE_AFILIADO,
        CONCAT('No es posible adjuntar documentos para solicitudes en estado ', e.NombreEstado, '.') AS MENSAJE
    FROM dbo.SolicitudesAutoLocal s
    JOIN dbo.EstadosSolicitudLocal e ON e.CodigoEstado = s.CodigoEstado
    WHERE s.SolicitudAuto = @prm_SOLI_AUTO;
END
GO