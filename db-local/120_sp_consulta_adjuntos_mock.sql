USE Carvajal_HP_Presmed_Local;
GO

CREATE OR ALTER PROCEDURE dbo.prs_solicitudes_auto_adjuntos_externo
    @prm_soli_auto BIGINT
AS
BEGIN
    SET NOCOUNT ON;

    IF NOT EXISTS (
        SELECT 1 FROM dbo.SolicitudesAutoLocal WHERE SolicitudAuto = @prm_soli_auto
    )
    BEGIN
        SELECT
            1 AS CODIGO,
            'NO EXISTE' AS DESCRIPCION,
            'Solicitud no encontrada' AS MENSAJE;
        RETURN;
    END

    SELECT
        0 AS CODIGO,
        'OK' AS DESCRIPCION,
        t.CodigoTipo AS Tipo_adjunto,
        t.CodigoTipo,
        t.NombreTipo AS Descripcion_tipo_adjunto,
        t.NombreTipo,
        CASE WHEN a.IdAdjunto IS NULL THEN 'N' ELSE 'S' END AS Tiene_adjunto,
        CASE WHEN a.IdAdjunto IS NULL THEN 0 ELSE 1 END AS YaCargado,
        CAST(20 AS DECIMAL(10,2)) AS Tamanio_adjunto,
        ISNULL(a.PesoMb, 0) AS PesoMb,
        1 AS PermiteCargar
    FROM dbo.TiposAdjuntoLocal t
    LEFT JOIN dbo.SolicitudAdjuntosLocal a
        ON a.SolicitudAuto = @prm_soli_auto
       AND a.CodigoTipo = t.CodigoTipo
    WHERE t.Activo = 1
    ORDER BY t.CodigoTipo;
END
GO

CREATE OR ALTER PROCEDURE dbo.prs_consulta_adjuntos_soli_externo
    @prm_SOLI_AUTO BIGINT,
    @prm_USUARIO   CHAR(15)
AS
BEGIN
    SET NOCOUNT ON;

    EXEC dbo.prs_solicitudes_auto_adjuntos_externo
        @prm_soli_auto = @prm_SOLI_AUTO;
END
GO
