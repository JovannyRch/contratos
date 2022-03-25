<?php

include('headers/header_files.php');
require 'db.php';
session_start();


if (sizeof($_SESSION) > 0) {
    header("Location: logout.php");
}

$db = new Db();
$mensaje = null;
if (isset($_POST['password']) && isset($_POST['correo'])) {
    $correo = $_POST['correo'];
    $password = $_POST['password'];

    $usuario = $db->login($correo, $password);

    print_r($usuario);
    if (is_null($usuario)) {
        $mensaje = "Credenciales no encontradas";
    } else {
        $_SESSION['id_usuario'] = $usuario['id_usuario'];
        $_SESSION['nombre'] = $usuario['nombre'];
        $_SESSION['paterno'] = $usuario['paterno'];
        $_SESSION['materno'] = $usuario['materno'];
        $_SESSION['puesto'] = $usuario['puesto'];

        header("Location: contratos.php");
    }
}

?>

<div class="container" style="margin-top: 3%; width: 30rem">
    <div class="card">
        <div class="card-header bg">
            <h3>Inicio de sesión</h3>
        </div>
        <form action="index.php" method="post">

            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <img src="assets/logo.jpeg" height="200px" />
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <label>Nombre de usuario</label>
                        <input type="text" class="form-control" name="correo" placeholder="Nombre de usuario o correo" maxlength="40">
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12">
                        <label>Contraseña</label>
                        <input type="password" name="password" class="form-control" placeholder="Contraseña" maxlength="20">
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-sm-12">
                        <button type="submit" class="btn btn-success">Iniciar sesión</button>
                    </div>

                    <div class="col-sm-12 mt-2">
                        <a href="contratos.php">Continuar como invitado</a>
                    </div>


                </div>
                <br>

            </div>
        </form>





    </div>
    <?php
    if (!is_null($mensaje)) { ?>
        <br />
        <div class="alert alert-warning text-center" role="alert">
            <strong> <?= $mensaje ?></strong>
        </div>
    <?php } ?>
</div>
<?php include('footer.php'); ?>