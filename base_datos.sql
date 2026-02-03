-- Script para crear tablas de dimensiones y hechos
-- Sistema de gestión de refrigerios y comidas
-- Ejecuta este script en phpMyAdmin

CREATE DATABASE IF NOT EXISTS `info`;

USE `info`;

-- ========================
-- TABLAS DE DIMENSIONES
-- ========================

-- Dimensión: Áreas
CREATE TABLE IF NOT EXISTS `areas` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nombre` VARCHAR(100) NOT NULL UNIQUE,
  `finca` VARCHAR(100) NOT NULL,
  `activo` BOOLEAN DEFAULT TRUE,
  `fecha_creacion` DATETIME DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_nombre (nombre)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dimensión: Secciones
CREATE TABLE IF NOT EXISTS `secciones` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `id_area` INT NOT NULL,
  `nombre` VARCHAR(100) NOT NULL,
  `activo` BOOLEAN DEFAULT TRUE,
  `fecha_creacion` DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (id_area) REFERENCES areas(id) ON DELETE CASCADE,
  INDEX idx_area (id_area),
  UNIQUE KEY unique_seccion_area (id_area, nombre)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dimensión: Proveedores
CREATE TABLE IF NOT EXISTS `proveedores` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nombre` VARCHAR(150) NOT NULL,
  `nit` VARCHAR(20) NOT NULL UNIQUE,
  `descuento_administrativo` BOOLEAN DEFAULT FALSE,
  `activo` BOOLEAN DEFAULT TRUE,
  `fecha_creacion` DATETIME DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_nombre (nombre),
  INDEX idx_nit (nit)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dimensión: Fechas
CREATE TABLE IF NOT EXISTS `fechas` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `fecha` DATE NOT NULL UNIQUE,
  `año` INT NOT NULL,
  `mes` INT NOT NULL,
  `quincena` INT NOT NULL,
  `dia_semana` VARCHAR(15),
  `fecha_creacion` DATETIME DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_fecha (fecha),
  INDEX idx_año_mes (año, mes)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dimensión: Refrigerios
CREATE TABLE IF NOT EXISTS `refrigerios` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nombre` VARCHAR(100) NOT NULL,
  `comida` BOOLEAN DEFAULT FALSE COMMENT 'TRUE = Comida completa, FALSE = Refrigerio',
  `descripcion` TEXT,
  `activo` BOOLEAN DEFAULT TRUE,
  `fecha_creacion` DATETIME DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_nombre (nombre),
  INDEX idx_comida (comida)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dimensión: Jornadas
CREATE TABLE IF NOT EXISTS `jornadas` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nombre` VARCHAR(50) NOT NULL UNIQUE COMMENT 'Mañana, Tarde, Noche',
  `hora_inicio` TIME,
  `hora_fin` TIME,
  `activo` BOOLEAN DEFAULT TRUE,
  `fecha_creacion` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dimensión: Valores
CREATE TABLE IF NOT EXISTS `valores` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `refrigerio_id` INT NOT NULL,
  `jornada_id` INT NOT NULL,
  `proveedor_id` INT NOT NULL,
  `valor` DECIMAL(10, 2) NOT NULL,
  `fecha_vigencia_inicio` DATE NOT NULL,
  `fecha_vigencia_fin` DATE,
  `activo` BOOLEAN DEFAULT TRUE,
  `fecha_creacion` DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (refrigerio_id) REFERENCES refrigerios(id) ON DELETE CASCADE,
  FOREIGN KEY (jornada_id) REFERENCES jornadas(id) ON DELETE CASCADE,
  FOREIGN KEY (proveedor_id) REFERENCES proveedores(id) ON DELETE CASCADE,
  INDEX idx_refrigerio (refrigerio_id),
  INDEX idx_jornada (jornada_id),
  INDEX idx_proveedor (proveedor_id),
  INDEX idx_vigencia (fecha_vigencia_inicio, fecha_vigencia_fin),
  UNIQUE KEY unique_valor (refrigerio_id, jornada_id, proveedor_id, fecha_vigencia_inicio)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================
-- TABLA DE HECHOS
-- ========================

CREATE TABLE IF NOT EXISTS `hechos` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `fecha_id` INT NOT NULL,
  `proveedor_id` INT NOT NULL,
  `seccion_id` INT NOT NULL,
  `refrigerio_id` INT NOT NULL,
  `jornada_id` INT NOT NULL,
  `cantidad` INT NOT NULL DEFAULT 1,
  `valor_unitario` DECIMAL(10, 2),
  `valor_total` DECIMAL(10, 2),
  `cuenta_cobro` VARCHAR(50),
  `observaciones` TEXT,
  `fecha_creacion` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `fecha_actualizacion` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (fecha_id) REFERENCES fechas(id) ON DELETE CASCADE,
  FOREIGN KEY (proveedor_id) REFERENCES proveedores(id) ON DELETE CASCADE,
  FOREIGN KEY (seccion_id) REFERENCES secciones(id) ON DELETE CASCADE,
  FOREIGN KEY (refrigerio_id) REFERENCES refrigerios(id) ON DELETE CASCADE,
  FOREIGN KEY (jornada_id) REFERENCES jornadas(id) ON DELETE CASCADE,
  INDEX idx_fecha (fecha_id),
  INDEX idx_proveedor (proveedor_id),
  INDEX idx_seccion (seccion_id),
  INDEX idx_refrigerio (refrigerio_id),
  INDEX idx_jornada (jornada_id),
  INDEX idx_fecha_proveedor (fecha_id, proveedor_id),
  INDEX idx_fecha_creacion (fecha_creacion)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================
-- DATOS DE EJEMPLO
-- ========================

-- Áreas
INSERT INTO `areas` (`nombre`, `finca`) VALUES 
('Administrativa', 'Finca Central'),
('Producción', 'Finca Central'),
('Mantenimiento', 'Finca Sur');

-- Secciones
INSERT INTO `secciones` (`id_area`, `nombre`) VALUES 
(1, 'Contabilidad'),
(1, 'Recursos Humanos'),
(2, 'Cosecha'),
(2, 'Packing'),
(3, 'Mecánica');

-- Proveedores
INSERT INTO `proveedores` (`nombre`, `nit`, `descuento_administrativo`) VALUES 
('Cafetería del Pueblo', '1234567890', FALSE),
('Comidas Rápidas Express', '9876543210', TRUE),
('Servicios de Catering', '5555555555', FALSE);

-- Jornadas
INSERT INTO `jornadas` (`nombre`, `hora_inicio`, `hora_fin`) VALUES 
('Mañana', '06:00', '12:00'),
('Tarde', '12:00', '18:00'),
('Noche', '18:00', '06:00');

-- Refrigerios
INSERT INTO `refrigerios` (`nombre`, `comida`, `descripcion`) VALUES 
('Café con pan', FALSE, 'Café y pan tostado'),
('Refrigerio mañana', FALSE, 'Bebida y galleta'),
('Almuerzo completo', TRUE, 'Comida completa del mediodía'),
('Refrigerio tarde', FALSE, 'Bebida y snack'),
('Cena completa', TRUE, 'Comida nocturna completa');

-- Fechas (insertando algunas fechas de ejemplo para febrero 2026)
INSERT INTO `fechas` (`fecha`, `año`, `mes`, `quincena`, `dia_semana`) VALUES 
('2026-02-01', 2026, 2, 1, 'Domingo'),
('2026-02-02', 2026, 2, 1, 'Lunes'),
('2026-02-03', 2026, 2, 1, 'Martes'),
('2026-02-04', 2026, 2, 1, 'Miércoles'),
('2026-02-05', 2026, 2, 1, 'Jueves'),
('2026-02-15', 2026, 2, 2, 'Domingo'),
('2026-02-16', 2026, 2, 2, 'Lunes');

-- Valores
INSERT INTO `valores` (`refrigerio_id`, `jornada_id`, `proveedor_id`, `valor`, `fecha_vigencia_inicio`) VALUES 
(1, 1, 1, 8500.00, '2026-01-01'),
(2, 1, 1, 6000.00, '2026-01-01'),
(3, 2, 1, 15000.00, '2026-01-01'),
(4, 2, 2, 7500.00, '2026-01-01'),
(5, 3, 2, 14000.00, '2026-01-01');

-- Hechos (registros de ejemplo)
INSERT INTO `hechos` (`fecha_id`, `proveedor_id`, `seccion_id`, `refrigerio_id`, `jornada_id`, `cantidad`, `valor_unitario`, `valor_total`, `observaciones`) VALUES 
(2, 1, 1, 1, 1, 5, 8500.00, 42500.00, 'Desayuno equipo administrativo'),
(2, 1, 3, 3, 2, 12, 15000.00, 180000.00, 'Almuerzo equipo de cosecha'),
(3, 2, 4, 4, 2, 8, 7500.00, 60000.00, 'Refrigerio tarde packing'),
(4, 1, 5, 2, 1, 3, 6000.00, 18000.00, 'Desayuno mecánica');
