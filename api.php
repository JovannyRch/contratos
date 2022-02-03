<?php

require 'db.php';
session_start();

function responder($res)
{
    echo json_encode($res);
}


$db = new Db();


$metodo = $_SERVER["REQUEST_METHOD"];
$ruta = implode("/", array_slice(explode("/", $_SERVER["REQUEST_URI"]), 3));
$datos = json_decode(file_get_contents("php://input"), true);
switch ($metodo) {
    case 'GET':
        switch ($ruta) {
            case 'contratos':
                $res = $db->array("SELECT * from contratos");
                responder($res);
                break;
            default:
                responder("Recurso no encontrado con GET");
                break;
        }
        break;
    case 'POST':
        switch ($ruta) {
            case 'libros':
                $titulo = $datos['titulo'];
                $descripcion = $datos['descripcion'];
                $last_id = $db->insert("INSERT INTO libros(titulo, descripcion) values('$titulo', '$descripcion')");
                responder("Libro guardado");
                break;

            case 'contratos':
                $no_expediente = $datos['no_expediente'];
                $cliente = $datos['cliente'];
                $responsable_ejecucion = $datos['responsable_ejecucion'];
                $fecha_inicio = $datos['fecha_inicio'];
                $fecha_termino = $datos['fecha_termino'];
                $path = $datos['path'];
                $last_id = $db->insert("INSERT INTO contratos(no_expediente, cliente, responsable_ejecucion, fecha_inicio, fecha_termino, path) values('$no_expediente', '$cliente', '$responsable_ejecucion', '$fecha_inicio', '$fecha_termino', '$path')");
                responder("datos guardados");
                break;

            case 'eliminar_libro':
                $id = $datos['id'];
                $db->query("DELETE from libros where id_libro = $id");
                responder("Libro eliminado");
                break;
        }
        break;
    case 'PUT':
        switch ($ruta) {
        }
        break;
    case 'DELETE':
        switch ($ruta) {
            case 'libros':
                $id = $datos['id'];
                $db->query("DELETE libros where id_libro = $id");
                break;
        }
        break;
}
