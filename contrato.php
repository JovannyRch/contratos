<?php
require('db.php');

$id_contrato = $_GET['id'];

$db = new Db();


$contrato = $db->row("SELECT contratos.*, clientes.nombre as cliente from contratos natural join clientes where contratos.id_contrato = $id_contrato");
$contrato['anexos'] = $db->array("SELECT * from anexos where id_contrato = $id_contrato");


session_start();

$es_invitado = true;
$header = '';
if (sizeof($_SESSION) == 0 || !isset($_SESSION['id_usuario'])) {
    $header = 'headers/header_invitado.php';
    $es_invitado = true;
} else {
    $header = 'headers/header_admin.php';
    $es_invitado = false;
}

include_once($header);


?>




<div>

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


                        <div v-if="!esInvitado" class="w-100 mb-4" style="display: flex; justify-content: flex-end;">
                            <button class="btn btn-primary" @click="iniciarAnexoData" data-toggle="modal" data-target="#agregar-anexo">Agregar anexo</buttonc>
                        </div>

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
                                            <th>Tipo de anexo</th>
                                            <th>Archivo</th>
                                            <th v-if="!esInvitado">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(anexo, index) in anexos">
                                            <td>
                                                {{index + 1}}
                                            </td>
                                            <td>
                                                {{anexo.nombre}}
                                            </td>
                                            <td>
                                                <a :href="anexo.path" target="_blank">Ver anexo</a>
                                            </td>
                                            <td v-if="!esInvitado">
                                                <button @click="eliminarAnexo(anexo)" type="button" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="agregar-anexo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Agregar anexo</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form method="POST" enctype="multipart/form-data" class="w-100">
                                        <div class="form-group">
                                            <label for="tipo_anexo">Seleccione tipo de anexo</label>
                                            <select class="form-control" name="tipo_anexo" id="tipo_anexo" v-model="tipoArchivo">
                                                <option value="">Seleccione tipo de archivo</option>
                                                <option v-for="tipo in tiposAnexo" :value="tipo">{{tipo}} </option>
                                            </select>
                                        </div>
                                        <input style="opacity: 0; width: 0px; height: 0px;" type="file" ref="file" class="custom-file-input" @change="onChangeFileUpload" id="customFile">
                                    </form>

                                    <div class="alert alert-success" role="alert" v-if="file">
                                        <strong>Archivo cargado correctamente</strong>
                                    </div>
                                    <button v-else class="btn btn-primary mb-4" @click="agregarAnexo">
                                        <i class="fa fa-plus"></i>
                                        Cargar archivo
                                    </button>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" id="close-modal-btn" data-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-success" @click="guardarAnexo">Guardar anexo</button>
                                </div>
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
            return null;
        }

    }

    const app = new Vue({
        el: "#content",
        data: {
            file: null,
            id_contrato: '<?= $id_contrato ?>',
            anexos: [],
            loading: false,
            tipoArchivo: "",
            esInvitado: "<?= $es_invitado ?>" === '1',
            tiposAnexo: ["Contrato", "Acta entrega recepción", "Finiquito", "Fianzas y seguros", "Precios extraordinarios y adicionales", "Convenios", "Órdenes de compra", "Estimaciones"]
        },
        created: function() {
            this.cargarDatos();
        },
        methods: {

            iniciarAnexoData() {
                this.file = null;
                this.tipoArchivo = "";
            },

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
            },
            guardarAnexo: async function() {
                if (this.tipoArchivo.length === 0) {
                    Swal.fire(
                        'Error',
                        'Seleccione tipo de archivo',
                        'error'
                    )
                    return;
                }

                if (!this.file) {
                    Swal.fire(
                        'Error',
                        'Cargue archivo',
                        'error'
                    )
                    return;
                }



                const respUploadFile = await subirArchivo(this.file);
                const {
                    data
                } = respUploadFile;
                const response = await axios.post('api.php/anexos', {
                    path: data.ruta,
                    nombre: this.tipoArchivo,
                    id_contrato: this.id_contrato
                });
                Swal.fire(
                    '¡Anexo guardado!',
                    'El anexo se ha guardado con éxito',
                    'success'
                )
                document.getElementById('close-modal-btn').click();
                this.cargarDatos();
            },
            eliminarAnexo: async function(anexo) {
                const respuestaUsuario = confirm(`¿Estas seguro de eliminar el anexo tipo: '${anexo.nombre}?'`);
                if (respuestaUsuario) {
                    const response = await axios.post('api.php/eliminar_anexo', {
                        id: anexo.id_anexos
                    });
                    this.cargarDatos();
                }
            }
        }
    })
</script>

<?php include('footer.php'); ?>