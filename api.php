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
                $db->log("registró al cliente $nombre");
                break;
            case 'registrar_responsable':
                $nombre = $datos['nombre'];
                $last_id = $db->insert("INSERT INTO responsables(nombre) values('$nombre')");

                if ($last_id) {
                    responder("Responsable registrado id: $last_id");
                    $db->log("registró al responsable $nombre");
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



                $db->log("registró el contrato con número de expediente $no_expediente");
                break;

            case 'anexos':
                $path = $datos["path"];
                $id_contrato = $datos["id_contrato"];
                $nombre = $datos["nombre"];
                $last_id = $db->insert("INSERT INTO anexos(path, nombre, id_contrato) values('$path', '$nombre', '$id_contrato')");

                $contrato = $db->row("SELECT * from contratos where id_contrato = $id_contrato");

                $db->log("agregó el anexo al contrato con número de expediente $contrato[no_expediente]");
                responder("Datos guardados");
                break;
            case 'eliminar_contrato':
                $id = $datos['id'];

                $contrato = $db->row("SELECT * from contratos where id_contrato = $id");
                $no_expediente = $contrato['no_expediente'];

                $db->query("DELETE from contratos where id_contrato = $id");

                responder("Contrato eliminado");
                $db->log("eliminó el contrato con número de expediente $no_expediente");
                break;
            case 'eliminar_anexo':
                $id = $datos['id'];
                $anexo = $db->row("SELECT * from anexos where id_anexos= $id");
                $contrato = $db->row("SELECT * from contratos where $anexo[id_contrato]");

                $db->query("DELETE from anexos where id_anexos = $id");

                responder("Anexo eliminado");

                $db->log("eliminó el anexo con id $id del contrato con número de expediente $contrato[no_expediente]");


                break;

            case 'eliminar_cliente':
                $id = $datos['id'];
                $cliente = $db->row("SELECT * from clientes where id_cliente = $id");
                $db->query("DELETE from clientes where id_cliente = $id");
                responder("Cliente eliminado");

                $db->log("eliminó el cliente con id $cliente[id_cliente] con nombre $cliente[nombre]");
                break;
            case 'eliminar_responsable':
                $id = $datos['id'];
                $responsable = $db->row("SELECT * from responsables where id_responsable = $id");
                $db->query("DELETE from responsables where id_responsable = $id");
                responder("Responsable eliminado");

                $db->log("eliminó el responsable con id $responsable[id_responsable] con nombre $responsable[nombre]");
                break;
            case 'get_anexos':
                $id = $datos['id'];
                responder($db->array("SELECT * from anexos where id_contrato = $id"));
                break;
            case 'actualizar_cliente':
                $id = $datos['id'];
                $cliente = $db->row("SELECT * from clientes where id_cliente = $id");
                $nombre = $datos['nombre'];
                $response = $db->query("UPDATE clientes set nombre = '$nombre' where id_cliente = $id");
                responder("Cliente actualizado");

                $db->log("actualizó el cliente con id $id con nombre $cliente[nombre] a $nombre");

                break;
            case 'actualizar_responsable':
                $id = $datos['id'];
                $nombre = $datos['nombre'];

                $responsable = $db->row("SELECT * from responsables where id_responsable = $id");

                $response = $db->query("UPDATE responsables set nombre = '$nombre' where id_responsable = $id");
                responder("Responsable actualizado");

                $db->log("actualizó el responsable con id $id nombre $responsable[nombre] a $nombre");
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
                $contrato = $db->row("SELECT * from contratos where id_contrato = $id");
                $status = $datos['status'];
                $db->query("UPDATE contratos set status = '$status' WHERE id_contrato = $id");
                $db->log("actualizó el estatus del contrato con id $id, número de expediente $contrato[no_expediente] de $contrato[status] a $status");
                break;
        }
        break;
}
