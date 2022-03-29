<?php

session_start();

if (sizeof($_SESSION) == 0 || !isset($_SESSION['id_usuario'])) {
    header("Location: contratos.php");
}

include_once("headers/header_admin.php");

require 'db.php';


$db = new Db();

$logs = $db->array("SELECT * from logs order by id_log desc");

?>


<div id="app">

    <div>
        <div class="container mt-4">

            <h3>Logs</h3>


            <table class="table mt-3" v-else>
                <thead>
                    <tr class="table-light">
                        <th>Id</th>
                        <th>Actividad</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>

                    <?php foreach ($logs as $log) { ?>
                        <tr>
                            <td><?= $log['id_log'] ?></td>
                            <td><?= $log['log'] ?></td>
                            <td><?= $log['fecha'] ?></td>
                        </tr>
                    <?php } ?>

                </tbody>
            </table>

        </div>
    </div>
</div>

<?php include('footer.php'); ?>