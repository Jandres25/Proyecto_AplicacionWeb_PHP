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
