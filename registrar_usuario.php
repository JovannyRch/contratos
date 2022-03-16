<?php

session_start();
require 'db.php';

if (sizeof($_SESSION) == 0 || !isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
}

$mensaje = null;
$db = new Db();

if (sizeof($_POST) > 0) {
    $paterno = $_POST['paterno'];
    $materno = $_POST['materno'];
    $nombre = $_POST['nombre'];
    $puesto = $_POST['puesto'];
    $correo = $_POST['correo'];
    $password = $_POST['password'];

    $response = $db->registrar($nombre, $paterno, $materno, $puesto, $correo, $password);

    if (is_null($response)) {
        $mensaje = array("type" => "warning", "text" => "Ocurrió un error al intentar registrar el usuario");
    } else {
        $mensaje = array("type" => "success", "text" => "Usuario creado exitosamente");
    }
}

include_once('headers/header_admin.php');


$puestos = $db->getPuestos();

?>

<div class="container mt-4">
    <div class="card">
        <div class="card-header bg">
            <h3>Registrar usuario</h3>
        </div>
        <form action="registrar_usuario.php" method="post">
            <div class="card-body">

                <div class="row">
                    <div class="col-md-12">
                        <label>Apellido Paterno</label>
                        <input type="text" required class="form-control" name="paterno" placeholder="Ingrese apellido paterno">
                    </div>
                </div>
                <br>

                <div class="row">
                    <div class="col-md-12">
                        <label>Apellido Materno</label>
                        <input type="text" required class="form-control" name="materno" placeholder="Ingrese apellido materno">
                    </div>
                </div>
                <br>

                <div class="row">
                    <div class="col-md-12">
                        <label>Nombre(s)</label>
                        <input type="text" required class="form-control" name="nombre" placeholder="Ingrese nombre(s)">
                    </div>
                </div>
                <br>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="puesto">Puesto</label>
                            <select class="form-control" name="puesto" id="puesto">
                                <?php foreach ($puestos as $puesto) { ?>
                                    <option value="<?= $puesto["id_puesto"] ?>"><?= $puesto["nombre"] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <label>Correo o nombre de usuario para iniciar sesión</label>
                        <input type="text" required class="form-control" name="correo" placeholder="Ingrese correo o nombre de usuario">
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12">
                        <label>Contraseña</label>
                        <input type="password" required name="password" class="form-control" placeholder="Contraseña">
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-sm-12">
                        <button type="submit" class="btn btn-success">Registrar usuario</button>
                    </div>
                </div>
                <br>

            </div>
        </form>





    </div>

    <?php
    if (!is_null($mensaje)) { ?>
        <br />
        <div class="alert alert-<?= $mensaje["type"] ?> text-center" role="alert">
            <strong> <?= $mensaje["text"] ?></strong>
        </div>
    <?php } ?>
</div>


<?php include('footer.php'); ?>