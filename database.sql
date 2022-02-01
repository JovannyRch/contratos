

create table contratos(
    id_contrato int primary key not null auto_increment,
    no_expediente varchar(20) unique,
    cliente varchar(50),
    responsable_ejecucion varchar(50),
    fecha_inicio varchar(50)
);

create table contratos(
    id_contrato int primary key not null auto_increment,
    titulo varchar(50) unique,
    descripcion varchar(50)
);

create table anexos (
    id_anexos int primary key not null auto_increment,
    path blob (100),
    nombre varchar (50),
    id_contrato int,
    foreign key(id_contrato)references contratos(id_contrato) on delete cascade
);


