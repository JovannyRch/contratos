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
                $res = $db->array("SELECT c_x_c.*, r.nombre as responsable_ejecucion from (SELECT c.*, clientes.nombre as cliente from contratos c natural join clientes) as c_x_c natural join responsables r");
                responder($res);
                break;
            case 'responsables':
                responder($db->array("SELECT * from responsables"));
                break;
            case 'clientes':
                responder($db->array("SELECT * from clientes"));
                break;

            case 'clientes_con_total':
                $clientes = $db->array("SELECT * from clientes");

                foreach ($clientes as &$cliente) {
                    $id_cliente = $cliente['id_cliente'];
                    $total = $db->row("SELECT count(*) total from contratos where id_cliente = $id_cliente")['total'];
                    $cliente['total_contratos'] = $total;
                }

                responder($clientes);
                break;
            case 'responsables_con_total':
                $responsables = $db->array("SELECT * from responsables");

                foreach ($responsables as &$responsable) {
                    $id_responsable = $responsable['id_responsable'];
                    $total = $db->row("SELECT count(*) total from contratos where id_responsable = $id_responsable")['total'];
                    $responsable['total_contratos'] = $total;
                }

                responder($responsables);
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
            case 'registrar_responsable':
                $nombre = $datos['nombre'];
                $last_id = $db->insert("INSERT INTO responsables(nombre) values('$nombre')");

                if ($last_id) {
                    responder("Responsable registrado id: $last_id");
                } else {
                    responder("Error");
                }

                break;
            case 'contratos':
                $no_expediente = $datos['no_expediente'];
                $id_cliente = $datos['id_cliente'];
                $id_responsable = $datos['id_responsable'];
                $fecha_inicio = $datos['fecha_inicio'];
                $fecha_termino = $datos['fecha_termino'];
                $path = $datos['path'];
                $status = $datos['status'];
                $last_id = $db->insert("INSERT INTO contratos(no_expediente, id_cliente, id_responsable, fecha_inicio, fecha_termino, path, status) values('$no_expediente', '$id_cliente', '$id_responsable', '$fecha_inicio', '$fecha_termino', '$path', '$status')");
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
            case 'eliminar_responsable':
                $id = $datos['id'];
                $db->query("DELETE from responsables where id_responsable = $id");
                responder("Responsable eliminado");
                break;
            case 'get_anexos':
                $id = $datos['id'];
                responder($db->array("SELECT * from anexos where id_contrato = $id"));
                break;
            case 'actualizar_cliente':
                $id = $datos['id'];
                $nombre = $datos['nombre'];
                $response = $db->query("UPDATE clientes set nombre = '$nombre' where id_cliente = $id");
                responder("Cliente actualizado");
                break;
            case 'actualizar_responsable':
                $id = $datos['id'];
                $nombre = $datos['nombre'];
                $response = $db->query("UPDATE responsables set nombre = '$nombre' where id_responsable = $id");
                responder("Responsable actualizado");
                break;
            case 'get_cliente':
                $id = $datos['id'];
                responder($db->row("SELECT * from clientes where id_cliente = $id"));
                break;
            case 'get_responsable':
                $id = $datos['id'];
                responder($db->row("SELECT * from responsables where id_responsable = $id"));
                break;
            case 'get_contratos_cliente':
                $id = $datos['id'];
                responder($db->array("SELECT c.*, r.nombre as responsable_ejecucion from contratos c natural join responsables r where c.id_cliente = $id"));
                break;
            case 'get_contratos_responsable':
                $id = $datos['id'];
                responder($db->array("SELECT c.*, clientes.nombre as cliente from contratos c natural join clientes where c.id_responsable = $id"));
                break;
            case 'actualizar_status_contrato':
                $id = $datos['id'];
                $status = $datos['status'];
                $db->query("UPDATE contratos set status = '$status' WHERE id_contrato = $id");
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
