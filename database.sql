drop database if exists contratos_db;

create database contratos_db;

use contratos_db;

create table clientes(
    id_cliente int primary key not null auto_increment,
    nombre varchar (255)
);

create table contratos(
    id_contrato int primary key not null auto_increment,
    no_expediente varchar(20) unique,
    id_cliente int not null,
    responsable_ejecucion varchar(50),
    fecha_inicio varchar(50),
    fecha_termino varchar(50),
    path varchar(200),
    foreign key(id_cliente) references clientes(id_cliente) on delete cascade
);

create table anexos (
    id_anexos int primary key not null auto_increment,
    path varchar(200),
    nombre varchar (50),
    id_contrato int,
    foreign key(id_contrato) references contratos(id_contrato) on delete cascade
);


create table puestos(
    id_puesto int primary key not null auto_increment,
    nombre varchar (255)
);

create table usuarios(
    id_usuario int primary key not null auto_increment,
    nombre varchar(50),
    materno varchar(50),
    paterno varchar(50),
    id_puesto int,
    correo varchar(255),
    password varchar(255),
    foreign key(id_puesto) references puestos(id_puesto) on delete cascade
);


insert into puestos(nombre) values('Administrador');

insert into usuarios(nombre, materno, paterno, id_puesto, correo, password) values('Administrador', '', '',1, "admin@admin.com", "admin");

