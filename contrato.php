<?php
require('db.php');

$id_contrato = $_GET['id'];

$db = new Db();


$contrato = $db->row("SELECT * from contratos where id_contrato = $id_contrato");
$contrato['anexos'] = $db->array("SELECT * from anexos where id_contrato = $id_contrato");


?>

<?php include('header.php'); ?>


<div id="viewport">
    <!-- Sidebar -->
    <div id="sidebar">
        <header>
            <a href="#">MENÚ</a>
        </header>
        <ul class="nav">
            <a href="#">CONTROL DE CONTRATOS</a>
            <a href="#">CONTRATOS CON RETRASO</a>
            <a href="#">ANEXOS DEL CONTRATO</a>
            <a href="#">CONTRATOS VIGENTES</a>
            <a href="#">CONTRATOS TERMINADOS</a>
        </ul>
    </div>
    <!-- Content -->
    <div id="content">
        <div class="container-fluid">
            <div class="pt-4">

                <div class="card">
                    <div class="card-body">
                        <h3 class="mb-3">Detalles del contrato</h3>
                        <ul class="list-group">
                            <li class="list-group-item ">
                                <b>Número de expediente: </b>
                                <?= $contrato['no_expediente'] ?>
                            </li>
                            <li class="list-group-item ">
                                <b>Cliente: </b>
                                <?= $contrato['cliente'] ?>
                            </li>
                            <li class="list-group-item ">
                                <b>Responsable ejecución: </b>
                                <?= $contrato['responsable_ejecucion'] ?>
                            </li>
                            <li class="list-group-item ">
                                <b>Fecha inicio: </b>
                                <?= $contrato['fecha_inicio'] ?>
                            </li>
                            <li class="list-group-item ">
                                <b>Fecha termino: </b>
                                <?= $contrato['fecha_termino'] ?>
                            </li>
                            <li class="list-group-item">
                                <a href="<?= $contrato['path'] ?>">Ver archivo</a>
                            </li>
                        </ul>



                        <h5 class="mt-4 mb-3">Anexos</h5>
                        
                        <?php if(sizeof($contrato['anexos']) == 0){ ?>
                            
                            <div class="alert alert-info" role="alert">
                                <strong>No se han agregado archivos anexos al contrato</strong>
                            </div>
                        <?php
                            }
                        ?>
                    </div>


                </div>
            </div>
        </div>
    </div>
</div>

<?php include('footer.php'); ?>