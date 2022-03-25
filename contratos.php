<?php

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


<div id="app">

    <div>
        <!-- Content -->
        <div id="content">
            <div class="container mt-4" v-if="!esInvitado">

                <h3>Control de Contratos</h3>

                <div class="alert alert-info" role="alert" v-if="clientes.length === 0" class="mt-4">
                    <strong>No se han encontrado clientes, por favor registre clientes para poder crear contratos</strong>
                </div>
                <div style="display: flex; justify-content: flex-end; width: 100%;" v-if="clientes.length !== 0">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#modal-form" @click="initForm">Agregar contrato</button>
                </div>
                <table class="table" v-if="clientes.length > 0">
                    <thead>
                        <tr class="table-light">
                            <th>Id</th>
                            <th>No. Expediente</th>
                            <th>Cliente</th>
                            <th>Responsable</th>
                            <th>Fecha Inicio</th>
                            <th>Fecha Termino</th>
                            <th>Contrato</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="contrato in contratos">
                            <td>{{contrato.id_contrato}}</td>
                            <td>{{contrato.no_expediente}}</td>
                            <td>{{contrato.cliente}}</td>
                            <td>{{contrato.responsable_ejecucion}}</td>
                            <td>{{contrato.fecha_inicio}}</td>
                            <td>{{contrato.fecha_termino}}</td>
                            <td>
                                <a :href="contrato.path" target="_blank">Ver archivo</a>
                            </td>
                            <td v-if="esInvitado">
                                <a :href="`contrato.php?id=${contrato.id_contrato}`" type="button" class="btn btn-success btn-sm"><i class="fa fa-eye"></i></a>
                            </td>
                            <td v-else>
                                <button @click="eliminar(contrato)" type="button" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                                <a :href="`contrato.php?id=${contrato.id_contrato}`" type="button" class="btn btn-success btn-sm"><i class="fa fa-eye"></i></a>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Agregar contrato</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">

                                <form @submit.prevent="enviarDatos" id="form" enctype="multipart/form-data">


                                    <div class="form-group">
                                        <label for="numero_expediente">Número de Expediente</label>
                                        <input required type="text" v-model="no_expediente" class="form-control" name="numero_expediente" id="numero_expediente" placeholder="Ingrese número de expediente">
                                    </div>

                                    <div class="form-group">
                                        <label for="cliente">Cliente</label>
                                        <select required class="form-control" v-model="id_cliente">
                                            <option value="" disabled>Seleccione un cliente</option>
                                            <option v-for="cliente in clientes" :value="cliente.id_cliente">{{cliente.nombre}}</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="responsable">Responsable</label>
                                        <input required type="text" v-model="responsable_ejecucion" class="form-control" name="responsable" id="responsable" placeholder="Ingrese nombre del responsable">
                                    </div>

                                    <div class="form-group">
                                        <label for="noExpediente">Fecha Inicio</label>
                                        <input required type="date" v-model="fecha_inicio" class="form-control form-control-sm" maxlength="40">
                                    </div>


                                    <div class="form-group">
                                        <label for="noExpediente">Fecha Termino</label>
                                        <input required type="date" v-model="fecha_termino" class="form-control form-control-sm" maxlength="40">
                                    </div>

                                    <div class="alert alert-success" role="alert" v-if="file">
                                        <strong>Archivo cargado correctamente</strong>
                                    </div>
                                    <div class="form-group" v-else>
                                        <label for="subirContrato">Subir Contrato</label>
                                        <div class="custom-file form-control-sm">
                                            <input type="file" ref="file" class="custom-file-input" @change="onChangeFileUpload" id="customFile">
                                            <label class="custom-file-label" for="customFile">Selecciona Archivo...</label>
                                        </div>
                                    </div>
                                    <button id="form-submit-btn" type="submit" style="opacity: 0; width:0; height: 0;">as</button>
                                </form>


                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" id="close-modal-btn" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-success" @click="submitForm">Guardar</button>
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


    new Vue({
        el: '#app',
        data: {
            no_expediente: "",
            responsable_ejecucion: "",
            fecha_inicio: "",
            fecha_termino: "",
            file: null,
            contratos: [],
            esInvitado: "<?= $es_invitado ?>" === '1',
            id_cliente: '',
            clientes: [],
        },
        created: function() {
            this.cargarDatos();
        },
        methods: {
            submitForm() {
                document.getElementById('form-submit-btn').click()
            },

            initForm: function() {
                this.file = null;
                this.no_expediente = '';
                this.fecha_inicio = '';
                this.fecha_termino = '';
                this.id_cliente = '';
            },

            enviarDatos: async function() {

                if (!this.id_cliente || !this.fecha_termino || !this.fecha_inicio || !this.id_cliente) {
                    Swal.fire(
                        'Datos incompletos',
                        'Ingrese los datos del contrato',
                        'warning'
                    )
                    return;
                }

                if (this.file === null) {
                    Swal.fire(
                        'Datos incompletos',
                        'Suba archivo del contrato',
                        'warning'
                    )
                    return;
                }


                const respUploadFile = await subirArchivo(this.file);
                const {
                    data
                } = respUploadFile;
                const response = await axios.post('api.php/contratos', {
                    no_expediente: this.no_expediente,
                    id_cliente: this.id_cliente,
                    responsable_ejecucion: this.responsable_ejecucion,
                    fecha_inicio: this.fecha_inicio,
                    fecha_termino: this.fecha_termino,
                    path: data.ruta
                });
                this.no_expediente = "";
                this.cliente = "";
                this.responsable_ejecucion = "";
                this.fecha_inicio = "";
                this.fecha_termino = "";
                this.cargarDatos();
                Swal.fire(
                    '¡Contrato guardado!',
                    'El contrato se ha guardado con éxito',
                    'success'
                )
                document.getElementById('close-modal-btn').click();
            },
            onChangeFileUpload: async function() {
                this.file = this.$refs.file.files[0];
            },
            cargarDatos: async function() {
                const response = await axios("api.php/contratos");
                await this.fetchClientes();
                this.contratos = response.data;
            },
            eliminar: async function(contrato) {

                const respuestaUsuario = confirm(`¿Estas seguro de eliminar el contrato #${contrato.no_expediente}?`);
                if (respuestaUsuario) {
                    const response = await axios.post('api.php/eliminar_contrato', {
                        id: contrato.id_contrato
                    });
                    this.cargarDatos();
                }
            },
            fetchClientes: async function() {
                const response = await axios("api.php/clientes");
                this.clientes = response.data;
            },
        },


    });
</script>
<?php include('footer.php'); ?>