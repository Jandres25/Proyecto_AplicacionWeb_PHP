-- database/seeder.sql
USE `proyecto`;

-- Datos para la tabla `propietarios`
INSERT INTO `propietarios` (`Nombre`, `Telefono`) VALUES
('Carlos Alberto Gómez', '71234567'),
('María Fernanda López', '72345678'),
('Ricardo Antonio Sosa', '73456789'),
('Lucía Valentina Ruiz', '74567890');

-- Datos para la tabla `taxis`
-- Asumiendo que los IDs de propietarios empiezan en 1
INSERT INTO `taxis` (`Modelo`, `Marca`, `Idpropietario`) VALUES
('Corolla 2022', 'Toyota', 1),
('Versa 2023', 'Nissan', 1),
('Accent 2021', 'Hyundai', 2),
('Rio 2022', 'Kia', 3),
('Logán 2023', 'Renault', 4);

-- Datos para la tabla `conductores`
-- Asumiendo que las placas (IDs) de taxis empiezan en 1
INSERT INTO `conductores` (`Nombres`, `Telefono`, `Placa`) VALUES
('Juan Pablo Duarte', '61000001', 1),
('Andrés Manuel Ibarra', '61000002', 2),
('Sofía Elena Vargas', '61000003', 3),
('Roberto Carlos Méndez', '61000004', 4),
('Laura Isabel Castro', '61000005', 5);

-- Datos para la tabla `usuarios`
-- Contraseña para todos: admin123
INSERT INTO `usuarios` (`Nombres`, `Apellidos`, `Usuario`, `Clave`, `Correo`, `is_admin`) VALUES
('Sistema', 'Admin', 'Administrador', '$2y$10$UYGCkF/zfz4HeyQRCAYy5utUTjkChfTi4Tl9rL/mIt3X0XWs/nhm2', 'admin@example.com', 1),
('Soporte', 'Técnico', 'support_user', '$2y$10$HZXfvwGGw/qQ.5JR3mEqWuc56sdQGN/8XzeRt9QoXn.i.UeM663QW', 'support@example.com', 0),
('Invitado', 'Especial', 'guest_view', '$2y$10$UcwO2HSSpp93lTXKxUzmNuRroSqN/qj72IMkYBg7EljVDkKtvWDB6', 'guest@example.com', 0);

-- Datos para la tabla `turnos`
-- 3 turnos de prueba no solapados (conductores y taxis distintos o rangos no solapados)
INSERT INTO `turnos` (`conductor_id`, `placa`, `inicio`, `fin`) VALUES
(1, 1, '2026-06-23 06:00:00', '2026-06-23 14:00:00'),
(2, 2, '2026-06-23 08:00:00', '2026-06-23 16:00:00'),
(3, 3, '2026-06-23 14:00:00', '2026-06-23 22:00:00');

-- Datos para la tabla `audit_log`
-- Registros de ejemplo que cubren los distintos módulos y acciones
INSERT INTO `audit_log` (`usuario_id`, `usuario_nombre`, `accion`, `entidad`, `entidad_id`, `descripcion`, `ip`, `creado_en`) VALUES
(1, 'Administrador', 'auth.login',          'auth',         NULL,  'Inicio de sesión exitoso',                    '127.0.0.1', '2026-06-19 08:00:00'),
(1, 'Administrador', 'taxi.created',         'taxis',        '1',   'Taxi creado: Corolla 2022 (Toyota)',           '127.0.0.1', '2026-06-19 08:05:00'),
(1, 'Administrador', 'taxi.created',         'taxis',        '2',   'Taxi creado: Versa 2023 (Nissan)',             '127.0.0.1', '2026-06-19 08:06:00'),
(1, 'Administrador', 'propietario.created',  'propietarios', '1',   'Propietario creado: Carlos Alberto Gómez',    '127.0.0.1', '2026-06-19 08:10:00'),
(1, 'Administrador', 'conductor.created',    'conductores',  '1',   'Conductor creado: Juan Pablo Duarte',         '127.0.0.1', '2026-06-19 08:15:00'),
(1, 'Administrador', 'taxi.updated',         'taxis',        '1',   'Taxi actualizado: Corolla 2022 (Toyota)',      '127.0.0.1', '2026-06-19 09:00:00'),
(2, 'support_user',  'auth.login',           'auth',         NULL,  'Inicio de sesión exitoso',                    '10.0.0.2',  '2026-06-19 09:30:00'),
(1, 'Administrador', 'usuario.created',      'usuarios',     '2',   'Usuario creado: support_user',                '127.0.0.1', '2026-06-19 10:00:00'),
(1, 'Administrador', 'conductor.deleted',    'conductores',  '5',   'Conductor eliminado: Laura Isabel Castro',    '127.0.0.1', '2026-06-19 10:30:00'),
(1, 'Administrador', 'auth.logout',          'auth',         NULL,  'Cierre de sesión',                            '127.0.0.1', '2026-06-19 11:00:00');
