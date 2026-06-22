INSERT INTO dbo.EstadosSolicitudLocal (CodigoEstado, NombreEstado, PermiteAdjuntar)
VALUES
('R', 'Registrada', 1),
('D', 'Devuelta', 1),
('C', 'En Proceso de Carga', 1),
('A', 'Aprobada', 0),
('X', 'Anulada', 0);

INSERT INTO dbo.TiposAdjuntoLocal (CodigoTipo, NombreTipo)
VALUES
('HC', 'Historia Clínica'),
('OM', 'Orden Médica'),
('ANX', 'Anexo');

INSERT INTO dbo.UsuariosRedLocal
(Cedula, UsuarioRed, NombreCompleto, Activo, Retirado, HabilitadoPresmed, PermiteReemplazo)
VALUES
('12345678', 'JMARQUEZ', 'Jose Luis Marquez', 1, 0, 1, 1),
('99999999', 'USRNOHAB', 'Usuario No Habilitado', 1, 0, 0, 0);

INSERT INTO dbo.SolicitudesAutoLocal (SolicitudAuto, CodigoEstado, NombreAfiliado)
VALUES
(1001001, 'R', 'Emmanuel Perez'),
(1001002, 'D', 'Maria Fernanda Rojas'),
(1001003, 'A', 'Carlos Gomez');
GO

INSERT INTO dbo.SolicitudAdjuntosLocal
(SolicitudAuto, CodigoTipo, NombreArchivo, FilenameApi, PesoMb, EstadoCarga, FechaCarga, UsuarioRed)
VALUES
(1001001, 'HC', 'hc_1001001.pdf', 'abc123.pdf', 3.20, 'CARGADO', GETDATE(), 'JMARQUEZ');
GO