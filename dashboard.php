<?php

session_start();

if (sizeof($_SESSION) == 0 || !isset($_SESSION['id_usuario'])) {
    header("Location: contratos.php");
}

include("./db.php");
include_once("headers/header_admin.php");

$db = new Db();


$total_contratos = $db->row("SELECT count(*) as total from contratos")['total'];
$total_clientes = $db->row("SELECT count(*) as total from clientes")['total'];
$total_responsables = $db->row("SELECT count(*) as total from responsables")['total'];
$total_usuarios = $db->row("SELECT count(*) as total from usuarios")['total'];

?>


<div id="app">

    <div>
        <div class="container mt-4">

            <h3>Dashboard</h3>

            <div class="row mt-4">
                <div class="col-md-4 col-xs-6 mt-2">
                    <div class="card text-left">
                        <div class="card-body">
                            <h4 class="card-title"><?= $total_contratos ?></h4>
                            <p class="card-text">Total contratos</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 col-xs-6 mt-2">
                    <div class="card text-left">
                        <div class="card-body">
                            <h4 class="card-title"><?= $total_clientes ?></h4>
                            <p class="card-text">Total clientes</p>
                        </div>
                    </div>
                </div>


                <div class="col-md-4 col-xs-6 mt-2">
                    <div class="card text-left">
                        <div class="card-body">
                            <h4 class="card-title"><?= $total_responsables ?></h4>
                            <p class="card-text">Total responsables</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 col-xs-6 mt-2">
                    <div class="card text-left">
                        <div class="card-body">
                            <h4 class="card-title"><?= $total_usuarios ?></h4>
                            <p class="card-text">Total usuarios</p>
                        </div>
                    </div>
                </div>
            </div>



        </div>
    </div>
</div>


<?php include('footer.php'); ?>