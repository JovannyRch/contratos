<?php include('header.php'); ?>
<div class="container" style="margin-top: 3%; width: 30rem">
    <div class="card">
        <div class="card-header bg">
            <h3>Bienvenido</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <label>Nombre de usuario</label>
                    <input type="text" class="form-control" placeholder="Usuario" maxlength="40">
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-12">
                    <label>Contraseña</label>
                    <input type="password" class="form-control" placeholder="Contraseña" maxlength="20">
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-12">
                <button type="submit" class="btn btn-secondary">Iniciar sesión</button>
                </div>
             </div>
             <br>
             <div class="row">
               <div class="col-md-12">
               <a href="registros.php" class="link-secondary">Registrarme</a>
               </div> 
             </div>
        </div>
    </div>
</div>
<?php include('footer.php'); ?>