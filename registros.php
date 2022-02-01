<?php include('header.php'); ?>
<div class="container" style="margin-top: 3%; width: 35rem">
    <div class="card">
        <div class="card-header bg">
            <h3>Registro</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <label>Apellido Paterno</label>
                    <input type="text" class="form-control" placeholder="Ingresa apellido paterno" maxlength="20">
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-12">
                    <label>Apellido Materno</label>
                    <input type="text" class="form-control" placeholder="Ingresa apellido materno" maxlength="20">
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-12">
                    <label>Nombre</label>
                    <input type="text" class="form-control" placeholder="Ingresa nombre" maxlength="20">
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-12">
                    <label>Contraseña</label>
                    <input type="password" class="form-control" placeholder="Ingresa contraseña" maxlength="20">
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-12">
                    <label>Confirmación de Contraseña</label>
                    <input type="password" class="form-control" placeholder="Ingresa contraseña nuevamente" maxlength="30">
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-secondary btn-sm">Registrarme</button>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-12">
                    <a href="login.php" class="link-secondary">Iniciar Sesión</a>
                </div>
            </div>

        </div>
    </div>
</div>
<?php include('footer.php'); ?>