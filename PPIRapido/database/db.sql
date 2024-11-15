CREATE TABLE Tickets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT,
    area INT,
    descripcion VARCHAR(200),
    fecha_creacion DATETIME,
    fecha_cierre DATETIME,
    agente_asignado INT,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    FOREIGN KEY (agente_asignado) REFERENCES agentes(id)
);

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50),
    correo_empresarial VARCHAR(40),
    contrasena VARCHAR(20),
    fecha_creacion DATETIME,
    fecha_modificacion DATETIME,
    rol_id INT,
    FOREIGN KEY (rol_id) REFERENCES roles(id)
);

CREATE TABLE roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(30),
    crear BOOLEAN,
    modificar BOOLEAN,
    eliminar BOOLEAN
);

CREATE TABLE agentes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(30),
    correo_empresarial VARCHAR(40),
    contrasena VARCHAR(20),
    fecha_creacion DATETIME,
    fecha_modificacion DATETIME,
    rol_id INT,
    FOREIGN KEY (rol_id) REFERENCES roles(id)
);

---------------------------------------------------------------------------------------
CREATE TABLE Tickets (
    id_ticket                   INT AUTO_INCREMENT PRIMARY KEY,
    usuario_ticket              INT,
    area_ticket                 INT,
    correo_electronico          VARCHAR(60),
    descripcion_ticket          VARCHAR(200),
    fecha_creacion_ticket       DATETIME,
    fecha_cierre_ticket         DATETIME,
    agente_asignado_ticket      INT,
    estado_ticket               BOOLEAN,
);



CREATE TABLE usuarios (
    id_usuario                                  INT AUTO_INCREMENT PRIMARY KEY,
    nombre_usuario                              VARCHAR(50) NOT NULL,
    correo_empresarial_usuario                  VARCHAR(50) UNIQUE NOT NULL,
    contrasena_usuario                          VARCHAR(255) NOT NULL,
    fecha_creacion_usuario                      DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_modificacion_usuario                  DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    id_rol                                      INT,
    es_agente_usuario                           BOOLEAN DEFAULT FALSE,

    FOREIGN KEY (id_rol) REFERENCES roles(id_rol) ON DELETE SET NULL
);

CREATE TABLE roles (
    id_rol                      INT AUTO_INCREMENT PRIMARY KEY,
    nombre_rol                  VARCHAR(30) NOT NULL
);

CREATE TABLE permisos (
    id_permiso                  INT AUTO_INCREMENT PRIMARY KEY,
    nombre_permiso              VARCHAR(30) NOT NULL
);

CREATE TABLE roles_permisos (
    id_rol                              INT,
    id_permiso                          INT,

    PRIMARY KEY (id_rol, id_permiso),
    FOREIGN KEY (id_rol) REFERENCES roles(id_rol) ON DELETE CASCADE,
    FOREIGN KEY (id_permiso) REFERENCES permisos(id_permiso) ON DELETE CASCADE
);

CREATE TABLE areas(
    id_area                     INT AUTO_INCREMENT PRIMARY KEY,
    nombre_area                 VARCHAR(50)
);


CREATE TABLE comentarios (
    id_comentario INT AUTO_INCREMENT PRIMARY KEY,
    id_ticket INT NOT NULL,
    id_usuario INT NOT NULL,
    comentario TEXT NOT NULL,
    fecha_comentario TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_ticket) REFERENCES tickets(id_ticket),
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario)
);


CREATE TABLE conversaciones (
    id_conversacion INT AUTO_INCREMENT PRIMARY KEY,
    id_cliente INT NOT NULL,
    id_asesor INT NOT NULL,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (id_cliente) REFERENCES usuarios(id_usuario),
    FOREIGN KEY (id_asesor) REFERENCES usuarios(id_usuario)
);

ALTER TABLE conversaciones ADD estado ENUM('activa', 'cerrada') NOT NULL DEFAULT 'activa';
ALTER TABLE conversaciones ADD fecha_cierre TIMESTAMP NULL;

mysqldump -u root -p darkar11 ppi > database.sql
