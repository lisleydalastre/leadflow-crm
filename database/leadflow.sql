-- =============================================================
-- LeadFlow CRM - Script de Base de Datos
-- Versión: 1.0.0
-- Descripción: Schema normalizado para gestión de leads,
--              fuentes de adquisición y conversiones
-- =============================================================

CREATE DATABASE IF NOT EXISTS leadflow_crm
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE leadflow_crm;

-- -------------------------------------------------------------
-- Tabla: usuarios
-- Descripción: Usuarios del sistema CRM
-- -------------------------------------------------------------
CREATE TABLE usuarios (
    id          INT UNSIGNED    AUTO_INCREMENT PRIMARY KEY,
    nombre      VARCHAR(100)    NOT NULL,
    email       VARCHAR(150)    NOT NULL UNIQUE,
    password    VARCHAR(255)    NOT NULL,
    rol         ENUM('admin','vendedor','viewer') NOT NULL DEFAULT 'vendedor',
    activo      TINYINT(1)      NOT NULL DEFAULT 1,
    created_at  DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at  DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_activo (activo)
) ENGINE=InnoDB;

-- -------------------------------------------------------------
-- Tabla: fuentes
-- Descripción: Canales de adquisición de leads
-- -------------------------------------------------------------
CREATE TABLE fuentes (
    id          INT UNSIGNED    AUTO_INCREMENT PRIMARY KEY,
    nombre      VARCHAR(80)     NOT NULL,
    icono       VARCHAR(50)     NOT NULL DEFAULT 'bi-globe',
    color       VARCHAR(7)      NOT NULL DEFAULT '#6c757d',
    activo      TINYINT(1)      NOT NULL DEFAULT 1,
    created_at  DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- -------------------------------------------------------------
-- Tabla: estados_lead
-- Descripción: Pipeline comercial (etapas del lead)
-- -------------------------------------------------------------
CREATE TABLE estados_lead (
    id          INT UNSIGNED    AUTO_INCREMENT PRIMARY KEY,
    nombre      VARCHAR(80)     NOT NULL,
    color       VARCHAR(7)      NOT NULL DEFAULT '#0d6efd',
    orden       TINYINT UNSIGNED NOT NULL DEFAULT 0,
    es_final    TINYINT(1)      NOT NULL DEFAULT 0,
    created_at  DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_orden (orden)
) ENGINE=InnoDB;

-- -------------------------------------------------------------
-- Tabla: leads
-- Descripción: Prospectos/contactos principales
-- -------------------------------------------------------------
CREATE TABLE leads (
    id              INT UNSIGNED    AUTO_INCREMENT PRIMARY KEY,
    nombre          VARCHAR(100)    NOT NULL,
    email           VARCHAR(150)    NULL,
    telefono        VARCHAR(30)     NULL,
    empresa         VARCHAR(120)    NULL,
    fuente_id       INT UNSIGNED    NOT NULL,
    estado_id       INT UNSIGNED    NOT NULL,
    usuario_id      INT UNSIGNED    NULL,
    notas           TEXT            NULL,
    valor_estimado  DECIMAL(12,2)   NOT NULL DEFAULT 0.00,
    created_at      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (fuente_id)   REFERENCES fuentes(id)      ON UPDATE CASCADE ON DELETE RESTRICT,
    FOREIGN KEY (estado_id)   REFERENCES estados_lead(id) ON UPDATE CASCADE ON DELETE RESTRICT,
    FOREIGN KEY (usuario_id)  REFERENCES usuarios(id)     ON UPDATE CASCADE ON DELETE SET NULL,
    INDEX idx_fuente  (fuente_id),
    INDEX idx_estado  (estado_id),
    INDEX idx_usuario (usuario_id),
    INDEX idx_created (created_at)
) ENGINE=InnoDB;

-- -------------------------------------------------------------
-- Tabla: historial_estados
-- Descripción: Auditoría de cambios de estado por lead
-- -------------------------------------------------------------
CREATE TABLE historial_estados (
    id              INT UNSIGNED    AUTO_INCREMENT PRIMARY KEY,
    lead_id         INT UNSIGNED    NOT NULL,
    estado_anterior INT UNSIGNED    NULL,
    estado_nuevo    INT UNSIGNED    NOT NULL,
    usuario_id      INT UNSIGNED    NULL,
    comentario      TEXT            NULL,
    created_at      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (lead_id)         REFERENCES leads(id)        ON DELETE CASCADE,
    FOREIGN KEY (estado_anterior) REFERENCES estados_lead(id) ON DELETE SET NULL,
    FOREIGN KEY (estado_nuevo)    REFERENCES estados_lead(id) ON DELETE RESTRICT,
    FOREIGN KEY (usuario_id)      REFERENCES usuarios(id)     ON DELETE SET NULL,
    INDEX idx_lead (lead_id)
) ENGINE=InnoDB;

-- -------------------------------------------------------------
-- Tabla: ventas
-- Descripción: Conversiones/cierres asociados a un lead
-- -------------------------------------------------------------
CREATE TABLE ventas (
    id              INT UNSIGNED    AUTO_INCREMENT PRIMARY KEY,
    lead_id         INT UNSIGNED    NOT NULL,
    usuario_id      INT UNSIGNED    NULL,
    monto           DECIMAL(12,2)   NOT NULL,
    producto        VARCHAR(150)    NOT NULL,
    fecha_venta     DATE            NOT NULL,
    metodo_pago     VARCHAR(60)     NULL,
    notas           TEXT            NULL,
    created_at      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (lead_id)    REFERENCES leads(id)    ON DELETE CASCADE,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE SET NULL,
    INDEX idx_lead      (lead_id),
    INDEX idx_fecha     (fecha_venta),
    INDEX idx_usuario   (usuario_id)
) ENGINE=InnoDB;

-- =============================================================
-- DATOS SEMILLA
-- =============================================================

-- Fuentes de adquisición
INSERT INTO fuentes (nombre, icono, color) VALUES
    ('Meta Ads',    'bi-facebook',   '#1877F2'),
    ('Google Ads',  'bi-google',     '#EA4335'),
    ('WhatsApp',    'bi-whatsapp',   '#25D366'),
    ('Instagram',   'bi-instagram',  '#E1306C'),
    ('Orgánico',    'bi-search',     '#6c757d'),
    ('Referido',    'bi-people',     '#fd7e14'),
    ('Email',       'bi-envelope',   '#0dcaf0'),
    ('Otro',        'bi-three-dots', '#adb5bd');

-- Pipeline de estados
INSERT INTO estados_lead (nombre, color, orden, es_final) VALUES
    ('Nuevo',           '#0d6efd', 1, 0),
    ('Contactado',      '#6610f2', 2, 0),
    ('Interesado',      '#fd7e14', 3, 0),
    ('En negociación',  '#ffc107', 4, 0),
    ('Propuesta enviada','#20c997',5, 0),
    ('Cerrado - Ganado','#198754', 6, 1),
    ('Cerrado - Perdido','#dc3545',7, 1);

-- Usuario administrador por defecto
-- Password: Admin1234! (cambiar en producción)
INSERT INTO usuarios (nombre, email, password, rol) VALUES
    ('Administrador', 'admin@leadflow.com',
     '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uZutcm9i',
     'admin');
