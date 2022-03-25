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
                $res = $db->array("SELECT c.*, clientes.nombre as cliente from contratos c natural join clientes");
                responder($res);
                break;

            case 'clientes':
                $clientes = $db->array("SELECT * from clientes");

                foreach ($clientes as &$cliente) {
                    $id_cliente = $cliente['id_cliente'];
                    $total = $db->row("SELECT count(*) total from contratos where id_cliente = $id_cliente")['total'];
                    $cliente['total_contratos'] = $total;
                }

                responder($clientes);
                break;
            default:
                responder("Recurso no encontrado con GET");
                break;
        }
        break;
    case 'POST':
        switch ($ruta) {

            case 'registrar_cliente':
                $nombre = $datos['nombre'];
                $last_id = $db->insert("INSERT INTO clientes(nombre) values('$nombre')");
                responder("Cliente registrado");
                break;
            case 'contratos':
                $no_expediente = $datos['no_expediente'];
                $id_cliente = $datos['id_cliente'];
                $responsable_ejecucion = $datos['responsable_ejecucion'];
                $fecha_inicio = $datos['fecha_inicio'];
                $fecha_termino = $datos['fecha_termino'];
                $path = $datos['path'];
                $last_id = $db->insert("INSERT INTO contratos(no_expediente, id_cliente, responsable_ejecucion, fecha_inicio, fecha_termino, path) values('$no_expediente', '$id_cliente', '$responsable_ejecucion', '$fecha_inicio', '$fecha_termino', '$path')");
                responder("datos guardados");
                break;

            case 'anexos':
                $path = $datos["path"];
                $id_contrato = $datos["id_contrato"];
                $nombre = $datos["nombre"];
                $last_id = $db->insert("INSERT INTO anexos(path, nombre, id_contrato) values('$path', '$nombre', '$id_contrato')");
                responder("Datos guardados");
                break;
            case 'eliminar_contrato':
                $id = $datos['id'];
                $db->query("DELETE from contratos where id_contrato = $id");
                responder("Contrato eliminado");
                break;
            case 'eliminar_anexo':
                $id = $datos['id'];
                $db->query("DELETE from anexos where id_anexos = $id");
                responder("Anexo eliminado");
                break;

            case 'eliminar_cliente':
                $id = $datos['id'];
                $db->query("DELETE from clientes where id_cliente = $id");
                responder("Cliente eliminado");
                break;
            case 'get_anexos':
                $id = $datos['id'];
                responder($db->array("SELECT * from anexos where id_contrato = $id"));
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
