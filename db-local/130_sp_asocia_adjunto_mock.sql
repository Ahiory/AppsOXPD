USE Carvajal_HP_Presmed_Local;
GO

CREATE OR ALTER PROCEDURE dbo.prs_asocia_adjunto_soli_externo
    @prm_SOLI_AUTO BIGINT,
    @prm_USUARIO   CHAR(15),
    @prm_TIPO      VARCHAR(10),
    @prm_FILENAME  VARCHAR(255),
    @prm_NOMBRE    VARCHAR(255),
    @prm_PESO_MB   DECIMAL(10,2)
AS
BEGIN
    SET NOCOUNT ON;

    IF NOT EXISTS (SELECT 1 FROM dbo.SolicitudesAutoLocal WHERE SolicitudAuto = @prm_SOLI_AUTO)
    BEGIN
        SELECT 1 AS CODIGO, 'Solicitud no encontrada' AS DESCRIPCION;
        RETURN;
    END

    MERGE dbo.SolicitudAdjuntosLocal AS T
    USING (
        SELECT @prm_SOLI_AUTO AS SolicitudAuto, @prm_TIPO AS CodigoTipo
    ) AS S
    ON T.SolicitudAuto = S.SolicitudAuto AND T.CodigoTipo = S.CodigoTipo
    WHEN MATCHED THEN
        UPDATE SET
            NombreArchivo = @prm_NOMBRE,
            FilenameApi = @prm_FILENAME,
            PesoMb = @prm_PESO_MB,
            EstadoCarga = 'CARGADO',
            FechaCarga = GETDATE(),
            UsuarioRed = @prm_USUARIO
    WHEN NOT MATCHED THEN
        INSERT (SolicitudAuto, CodigoTipo, NombreArchivo, FilenameApi, PesoMb, EstadoCarga, FechaCarga, UsuarioRed)
        VALUES (@prm_SOLI_AUTO, @prm_TIPO, @prm_NOMBRE, @prm_FILENAME, @prm_PESO_MB, 'CARGADO', GETDATE(), @prm_USUARIO);

    SELECT 0 AS CODIGO, 'Adjunto asociado correctamente' AS DESCRIPCION;
END
GO

CREATE OR ALTER PROCEDURE dbo.pru_solicitudes_auto_adjuntos
    @prm_soli_auto BIGINT,
    @prm_origen    CHAR(1),
    @prm_usuari    CHAR(15),
    @prm_prestad   VARCHAR(20) = NULL,
    @prm_adjuntos  XML
AS
BEGIN
    SET NOCOUNT ON;

    IF NOT EXISTS (SELECT 1 FROM dbo.SolicitudesAutoLocal WHERE SolicitudAuto = @prm_soli_auto)
    BEGIN
        SELECT 1 AS CODIGO, 'Solicitud no encontrada' AS DESCRIPCION;
        RETURN;
    END

    IF @prm_adjuntos IS NULL
    BEGIN
        SELECT 1 AS CODIGO, 'Adjuntos no informados' AS DESCRIPCION;
        RETURN;
    END

    ;WITH Adjuntos AS (
        SELECT
            Tipo = A.N.value('(tipo/text())[1]', 'VARCHAR(10)'),
            Nombre = A.N.value('(nombre/text())[1]', 'VARCHAR(255)'),
            Filename = A.N.value('(id/text())[1]', 'VARCHAR(255)'),
            PesoMb = A.N.value('(pesoMb/text())[1]', 'DECIMAL(10,2)')
        FROM @prm_adjuntos.nodes('/adjuntos/adjunto') AS A(N)
    )
    MERGE dbo.SolicitudAdjuntosLocal AS T
    USING (
        SELECT
            @prm_soli_auto AS SolicitudAuto,
            CASE
                WHEN Tipo = '1' THEN 'HC'
                WHEN Tipo = '2' THEN 'OM'
                WHEN Tipo = '3' THEN 'ANX'
                ELSE Tipo
            END AS CodigoTipo,
            Nombre,
            Filename,
            PesoMb
        FROM Adjuntos
    ) AS S
    ON T.SolicitudAuto = S.SolicitudAuto AND T.CodigoTipo = S.CodigoTipo
    WHEN MATCHED THEN
        UPDATE SET
            NombreArchivo = S.Nombre,
            FilenameApi = S.Filename,
            PesoMb = S.PesoMb,
            EstadoCarga = 'CARGADO',
            FechaCarga = GETDATE(),
            UsuarioRed = @prm_usuari
    WHEN NOT MATCHED THEN
        INSERT (SolicitudAuto, CodigoTipo, NombreArchivo, FilenameApi, PesoMb, EstadoCarga, FechaCarga, UsuarioRed)
        VALUES (S.SolicitudAuto, S.CodigoTipo, S.Nombre, S.Filename, S.PesoMb, 'CARGADO', GETDATE(), @prm_usuari);

    SELECT 0 AS CODIGO, 'Adjunto asociado correctamente' AS DESCRIPCION;
END
GO
