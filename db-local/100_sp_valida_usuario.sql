USE Carvajal_HP_Presmed_Local;
GO

CREATE OR ALTER PROCEDURE dbo.prs_valida_usuario_soli_externo
    @prm_USUARIO CHAR(15)
AS
BEGIN
    SET NOCOUNT ON;

    IF @prm_USUARIO IS NULL OR LTRIM(RTRIM(@prm_USUARIO)) = ''
    BEGIN
        SELECT 1 AS CODIGO, 'NO HABILITADO' AS DESCRIPCION;
        RETURN;
    END

    IF NOT EXISTS (
        SELECT 1
        FROM dbo.UsuariosRedLocal
        WHERE UsuarioRed = @prm_USUARIO
          AND Activo = 1
          AND Retirado = 0
          AND HabilitadoPresmed = 1
    )
    BEGIN
        SELECT 1 AS CODIGO, 'NO HABILITADO' AS DESCRIPCION;
        RETURN;
    END

    SELECT 0 AS CODIGO, 'HABILITADO' AS DESCRIPCION;
END
GO