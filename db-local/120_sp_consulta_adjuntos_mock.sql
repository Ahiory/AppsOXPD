USE Carvajal_HP_Presmed_Local;
GO

CREATE OR ALTER PROCEDURE dbo.prs_consulta_adjuntos_soli_externo
    @prm_SOLI_AUTO BIGINT,
    @prm_USUARIO   CHAR(15)
AS
BEGIN
    SET NOCOUNT ON;

    IF NOT EXISTS (
        SELECT 1 FROM dbo.SolicitudesAutoLocal WHERE SolicitudAuto = @prm_SOLI_AUTO
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
        t.CodigoTipo,
        t.NombreTipo,
        CASE WHEN a.IdAdjunto IS NULL THEN 0 ELSE 1 END AS YaCargado,
        ISNULL(a.PesoMb, 0) AS PesoMb,
        CASE
            WHEN a.IdAdjunto IS NULL THEN 1
            WHEN u.PermiteReemplazo = 1 THEN 1
            ELSE 0
        END AS PermiteCargar
    FROM dbo.TiposAdjuntoLocal t
    CROSS JOIN (
        SELECT TOP 1 PermiteReemplazo
        FROM dbo.UsuariosRedLocal
        WHERE UsuarioRed = @prm_USUARIO
    ) u
    LEFT JOIN dbo.SolicitudAdjuntosLocal a
        ON a.SolicitudAuto = @prm_SOLI_AUTO
       AND a.CodigoTipo = t.CodigoTipo
    WHERE t.Activo = 1
    ORDER BY t.CodigoTipo;
END
GO