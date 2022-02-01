<?php include('header.php'); ?>

<?php
require 'db.php';


$db = new Db();
$mensaje = null;
if (isset($_POST['password']) && isset($_POST['correo'])) {
    $correo = $_POST['correo'];
    $password = $_POST['password'];

    $usuario = $db->login($correo, $password);


    if ($usuario == null) {
        $mensaje = "Credenciales no encontradas";
    } else {
        //INICIAR SESION 
        header("Location: control_contratos.php");
    }
}

?>

<div class="container" style="margin-top: 3%; width: 30rem">
    <div class="card">
        <div class="card-header bg">
            <h3>Bienvenido</h3>
        </div>
        <form action="login.php" method="post">

            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <label>Nombre de usuario</label>
                        <input type="text" class="form-control" name="correo" placeholder="NombreUsuario" maxlength="40">
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
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-secondary">Iniciar sesión</button>
                    </div>
                </div>
                <br>
                <!--    <div class="row">
               <div class="col-md-12">
               <a href="registros.php" class="link-secondary">Registrarme</a>
               </div> 
             </div> -->
            </div>
        </form>




        <?php
        if (!is_null($mensaje)) { ?>
            <br />
            <div class="alert alert-warning" role="alert">
                <strong> <?= $mensaje ?></strong>
            </div>
        <?php } ?>
    </div>
</div>
<?php include('footer.php'); ?>