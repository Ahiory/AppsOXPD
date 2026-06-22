CREATE DATABASE Carvajal_HP_Presmed_Local;
GO

USE Carvajal_HP_Presmed_Local;
GO

CREATE TABLE dbo.UsuariosRedLocal (
    IdUsuario           INT IDENTITY(1,1) PRIMARY KEY,
    Cedula              VARCHAR(20) NOT NULL UNIQUE,
    UsuarioRed          CHAR(15) NOT NULL UNIQUE,
    NombreCompleto      VARCHAR(200) NOT NULL,
    Activo              BIT NOT NULL DEFAULT 1,
    Retirado            BIT NOT NULL DEFAULT 0,
    HabilitadoPresmed   BIT NOT NULL DEFAULT 1,
    PermiteReemplazo    BIT NOT NULL DEFAULT 0
);
GO

CREATE TABLE dbo.EstadosSolicitudLocal (
    CodigoEstado        CHAR(1) PRIMARY KEY,
    NombreEstado        VARCHAR(50) NOT NULL,
    PermiteAdjuntar     BIT NOT NULL
);
GO

CREATE TABLE dbo.SolicitudesAutoLocal (
    SolicitudAuto       BIGINT PRIMARY KEY,
    CodigoEstado        CHAR(1) NOT NULL,
    NombreAfiliado      VARCHAR(200) NOT NULL,
    FechaRegistro       DATETIME NOT NULL DEFAULT GETDATE(),
    CONSTRAINT FK_Solicitudes_Estados
        FOREIGN KEY (CodigoEstado) REFERENCES dbo.EstadosSolicitudLocal(CodigoEstado)
);
GO

CREATE TABLE dbo.TiposAdjuntoLocal (
    CodigoTipo          VARCHAR(10) PRIMARY KEY,
    NombreTipo          VARCHAR(100) NOT NULL,
    Activo              BIT NOT NULL DEFAULT 1
);
GO

CREATE TABLE dbo.SolicitudAdjuntosLocal (
    IdAdjunto           INT IDENTITY(1,1) PRIMARY KEY,
    SolicitudAuto       BIGINT NOT NULL,
    CodigoTipo          VARCHAR(10) NOT NULL,
    NombreArchivo       VARCHAR(255) NULL,
    FilenameApi         VARCHAR(255) NULL,
    PesoMb              DECIMAL(10,2) NULL,
    EstadoCarga         VARCHAR(20) NOT NULL DEFAULT 'CARGADO',
    FechaCarga          DATETIME NULL,
    UsuarioRed          CHAR(15) NULL,
    CONSTRAINT FK_Adj_Solicitud
        FOREIGN KEY (SolicitudAuto) REFERENCES dbo.SolicitudesAutoLocal(SolicitudAuto),
    CONSTRAINT FK_Adj_Tipo
        FOREIGN KEY (CodigoTipo) REFERENCES dbo.TiposAdjuntoLocal(CodigoTipo)
);
GO