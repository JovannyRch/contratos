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
                        <form style="opacity: 0" method="POST" enctype="multipart/form-data">
                            <input type="file" ref="file" class="custom-file-input" @change="onChangeFileUpload" id="customFile">
                        </form>
                        <button class="btn btn-success mb-4" @click="agregarAnexo">
                            <i class="fa fa-plus"></i>
                            Agregar anexo
                        </button>

                        <div v-if="loading">

                        </div>
                        <div v-else>
                            <div v-if="anexos.length === 0">
                                <div class="alert alert-info" role="alert">
                                    <strong>No se han agregado archivos anexos al contrato</strong>
                                </div>
                            </div>
                            <div v-else>

                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Archivo</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(anexo, index) in anexos">
                                            <td>
                                                {{index + 1}}
                                            </td>
                                            <td>
                                                <a :href="anexo.path" target="_blank">Ver anexo</a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>
</div>

<script>
    async function subirArchivo(file) {

        try {
            let formData = new FormData();
            formData.append('file', file);

            const resp = await axios.post('upload_file.php',
                formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }
            );
            return resp;
        } catch (error) {
            console.log("Error", error);
            return null;
        }

    }

    const app = new Vue({
        el: "#content",
        data: {
            file: '',
            id_contrato: '<?= $id_contrato ?>',
            anexos: [],
            loading: false,
        },
        created: function() {
            this.cargarDatos();
        },
        methods: {
            async cargarDatos() {
                this.loading = true;
                const resp = await axios.post("api.php/get_anexos", {
                    id: this.id_contrato
                });
                this.anexos = resp.data;
                this.loading = false;
            },
            agregarAnexo() {
                document.getElementById('customFile').click()
            },
            onChangeFileUpload: async function() {
                this.file = this.$refs.file.files[0];
                const respUploadFile = await subirArchivo(this.file);
                const {
                    data
                } = respUploadFile;
                const response = await axios.post('api.php/anexos', {
                    path: data.ruta,
                    id_contrato: this.id_contrato
                });
                Swal.fire(
                    '¡Anexo guardado!',
                    'El anexo se ha guardado con éxito',
                    'success'
                )
                this.cargarDatos();
            },
        }
    })
</script>

<?php include('footer.php'); ?>