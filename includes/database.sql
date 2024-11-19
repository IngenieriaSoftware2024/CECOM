CREATE TABLE CECOM_SITUACIONES(
  SIT_LLAVE SERIAL PRIMARY KEY NOT NULL,
  SIT_DESCRIPCION VARCHAR(100)
);


CREATE TABLE CECOM_DEST_BRGS(
    UBI_ID SERIAL PRIMARY KEY NOT NULL,
    UBI_DEPENDENCIA SMALLINT NOT NULL,
    UBI_NOMBRE VARCHAR (250) NOT NULL,
    UBI_LATITUD VARCHAR (250) NOT NULL,
    UBI_LONGITUD VARCHAR (250) NOT NULL,
    UBI_SITUACION INT DEFAULT 1,
    FOREIGN KEY (UBI_DEPENDENCIA) REFERENCES MDEP(DEP_LLAVE)
);

CREATE TABLE CECOM_MARCAS(
    MAR_ID SERIAL PRIMARY KEY NOT NULL,
    MAR_DESCRIPCION VARCHAR(100) NOT NULL
);


CREATE TABLE CECOM_CARACTERISTICAS(
    CAR_ID SERIAL PRIMARY KEY NOT NULL,
    CAR_NOMBRE VARCHAR(250)
);

CREATE TABLE CECOM_ACCESORIOS(
    ACC_ID SERIAL PRIMARY KEY NOT NULL,
    ACC_NOMBRE VARCHAR(250),
    ACC_TIPO INT,
    FOREIGN KEY (ACC_TIPO) REFERENCES CECOM_CARACTERISTICAS(CAR_ID)
);


CREATE TABLE CECOM_EQUIPO(
    EQP_ID SERIAL PRIMARY KEY NOT NULL,
    EQP_CLASE INT NOT NULL,
    EQP_SERIE VARCHAR(250) UNIQUE,
    EQP_GAMA INT,
    EQP_MARCA INT NOT NULL,
    EQP_ESTADO INT NOT NULL,
    EQP_SITUACION SMALLINT DEFAULT 1,
    FOREIGN KEY (EQP_CLASE) REFERENCES CECOM_CARACTERISTICAS(CAR_ID),
    FOREIGN KEY (EQP_GAMA) REFERENCES CECOM_CARACTERISTICAS(CAR_ID),
    FOREIGN KEY (EQP_MARCA) REFERENCES CECOM_MARCAS(MAR_ID),
    FOREIGN KEY (EQP_ESTADO) REFERENCES CECOM_SITUACIONES(SIT_LLAVE)
);

CREATE TABLE CECOM_ASIG_ACCESORIOS (
    ASIG_EQUIPO INT NOT NULL,
    ASIG_ACCESORIO INT NOT NULL,
    ASIG_CANTIDAD INT NOT NULL,
    ASIG_ESTADO INT NOT NULL,
    ASIG_SITUACION SMALLINT DEFAULT 1,
    PRIMARY KEY (ASIG_EQUIPO, ASIG_ACCESORIO),
    FOREIGN KEY (ASIG_EQUIPO) REFERENCES CECOM_EQUIPO (EQP_ID),
    FOREIGN KEY (ASIG_ACCESORIO) REFERENCES CECOM_ACCESORIOS (ACC_ID),
    FOREIGN KEY (ASIG_ESTADO) REFERENCES CECOM_SITUACIONES(SIT_LLAVE)
);

CREATE TABLE CECOM_ASIG_EQUIPO(
    ASI_ID SERIAL PRIMARY KEY NOT NULL,
    ASI_EQUIPO INT,
    ASI_DEPENDENCIA SMALLINT,
    ASI_DESTACAMENTO INT,
    ASI_FECHA DATE,
    ASI_RESPONSABLE INT,
    ASI_MOTIVO VARCHAR(250),
    ASI_STATUS INT,
    ASI_SITUACION SMALLINT DEFAULT 1,
    FOREIGN KEY (ASI_EQUIPO) REFERENCES CECOM_EQUIPO(EQP_ID),
    FOREIGN KEY (ASI_DEPENDENCIA) REFERENCES MDEP(DEP_LLAVE),
    FOREIGN KEY (ASI_DESTACAMENTO) REFERENCES CECOM_DEST_BRGS(UBI_ID),
    FOREIGN KEY (ASI_RESPONSABLE) REFERENCES MORG (ORG_PLAZA),
    FOREIGN KEY (ASI_STATUS) REFERENCES CECOM_SITUACIONES(SIT_LLAVE)
);

CREATE TABLE CECOM_MANTTO_REP (
    REP_ID SERIAL PRIMARY KEY NOT NULL,
    REP_EQUIPO INT NOT NULL,
    REP_FECHA DATE NOT NULL,
    REP_DESC VARCHAR(250),
    REP_ESTADO_ANT INT,
    REP_ESTADO_ACTUAL INT,
    REP_RESPONSABLE INT,
    REP_STATUS INT,
    REP_OBS VARCHAR(250),
    FOREIGN KEY (REP_EQUIPO) REFERENCES CECOM_EQUIPO (EQP_ID),
    FOREIGN KEY (REP_ESTADO_ANT) REFERENCES CECOM_SITUACIONES (SIT_LLAVE),
    FOREIGN KEY (REP_ESTADO_ACTUAL) REFERENCES CECOM_SITUACIONES (SIT_LLAVE),
    FOREIGN KEY (REP_STATUS) REFERENCES CECOM_SITUACIONES (SIT_LLAVE),
    FOREIGN KEY (REP_RESPONSABLE) REFERENCES MPER (PER_CATALOGO)
);



--  INSERTS
INSERT INTO CECOM_CARACTERISTICAS(CAR_NOMBRE) VALUES ("ANTENA")
INSERT INTO CECOM_CARACTERISTICAS(CAR_NOMBRE) VALUES ("REPETIDORA")
INSERT INTO CECOM_CARACTERISTICAS(CAR_NOMBRE) VALUES ("RADIO BASE(ESTACION FIJA)")
INSERT INTO CECOM_CARACTERISTICAS(CAR_NOMBRE) VALUES ("RADIO PORTATIL")
INSERT INTO CECOM_CARACTERISTICAS(CAR_NOMBRE) VALUES ("RADIO MOVIL")
INSERT INTO CECOM_CARACTERISTICAS(CAR_NOMBRE) VALUES ("RADIO AÉREO(TIERRA/AIRE)")




INSERT INTO CECOM_SITUACIONES (SIT_DESCRIPCION) VALUES ("BUEN ESTADOOO")
INSERT INTO CECOM_SITUACIONES (SIT_DESCRIPCION) VALUES ("REGULAR ESTADOOO")
INSERT INTO CECOM_SITUACIONES (SIT_DESCRIPCION) VALUES ("MAL ESTADOOO")
INSERT INTO CECOM_SITUACIONES (SIT_DESCRIPCION) VALUES ("OPERATIVO")
INSERT INTO CECOM_SITUACIONES (SIT_DESCRIPCION) VALUES ("ALMACÉN")
INSERT INTO CECOM_SITUACIONES (SIT_DESCRIPCION) VALUES ("MANTENIMIENTO")
INSERT INTO CECOM_SITUACIONES (SIT_DESCRIPCION) VALUES ("BAJA")
INSERT INTO CECOM_SITUACIONES (SIT_DESCRIPCION) VALUES ("PENDIENTE DE RECIBIDO")