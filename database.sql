

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

