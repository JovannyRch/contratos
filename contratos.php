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
            <div class="container-fluid mt-4" v-if="!esInvitado">

                <h3>Control de Contratos</h3>

                <div v-if="loading" class="text-center mt-5">
                    <div class="spinner-border text-primary " role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
                <div v-else>
                    <div v-if="clientes.length === 0 || responsables.length === 0" class="mt-4">
                        <div v-if="clientes.length === 0" class="alert alert-info text-center" role="alert" class="mt-4">
                            <strong>No se han encontrado clientes, por favor registre clientes para poder crear contratos</strong>
                        </div>

                        <div v-if="responsables.length === 0" class="alert alert-info text-center" role="alert" class="mt-4">
                            <strong>No se han encontrado responsables, por favor registre responsables para poder crear contratos</strong>
                        </div>

                    </div>

                    <div v-else>
                        <div style="display: flex; justify-content: flex-end; width: 100%;" class="mb-4">
                            <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#modal-form" @click="initForm">
                                <i class="fa fa-plus"></i>
                                Agregar contrato
                            </button>

                            <button data-toggle="modal" data-target="#modal-status-form" id="modal-status-form-btn" style="display:none">

                            </button>
                        </div>

                        <div class="row">
                            <div class="col-md-4 com-sm-12">
                                <div class="form-group">
                                    <label for="">Filtrar por status</label>
                                    <select class="custom-select" v-model="filtroStatus">
                                        <option value="">Sin filtro</option>
                                        <option :value="estado" v-for="estado in estados">{{estado}}</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-warning mt-2 text-center" role="alert" v-if="contratos.length === 0">
                            <strong>No se han encontrado contratos</strong>
                        </div>

                        <table class="table mt-2" v-else>
                            <thead class="table-dark">
                                <tr>
                                    <th>Id</th>
                                    <th>No. Expediente</th>
                                    <th>Cliente</th>
                                    <th>Responsable</th>
                                    <th>Status</th>
                                    <th>Fecha Inicio</th>
                                    <th>Fecha Termino</th>
                                    <th>Contrato</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="contrato in filtro">
                                    <td>{{contrato.id_contrato}}</td>
                                    <td>{{contrato.no_expediente}}</td>
                                    <td>{{contrato.cliente}}</td>
                                    <td>{{contrato.responsable_ejecucion}}</td>
                                    <td>{{contrato.status}}</td>
                                    <td>{{contrato.fecha_inicio}}</td>
                                    <td>{{contrato.fecha_termino}}</td>
                                    <td>
                                        <a :href="contrato.path" target="_blank">Ver archivo</a>
                                    </td>
                                    <td v-if="esInvitado">
                                        <a :href="`contrato.php?id=${contrato.id_contrato}`" type="button" class="btn btn-success btn-sm"><i class="fa fa-eye"></i></a>
                                    </td>
                                    <td v-else>
                                        <a :href="`contrato.php?id=${contrato.id_contrato}`" type="button" class="btn btn-dark btn-sm"><i class="fa fa-eye"></i></a>
                                        <button @click="editarStatus(contrato)" type="button" class="btn btn-dark btn-sm"><i class="fa fa-pen"></i> Status</button>

                                        <button @click="eliminar(contrato)" type="button" class="btn btn-dark btn-sm"><i class="fa fa-trash"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
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
                                            <select required class="form-control" v-model="id_responsable">
                                                <option value="" disabled>Seleccione un responsable</option>
                                                <option v-for="responsable in responsables" :value="responsable.id_responsable">{{responsable.nombre}}</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="status">Status</label>
                                            <select required class="form-control" v-model="status">
                                                <option v-for="estado in estados" :value="estado">{{estado}}</option>
                                            </select>
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
                                    <button type="button" class="btn btn-secondary" id="close-modal-btn" data-dismiss="modal">Cancelar</button>
                                    <button type="button" class="btn btn-success" @click="submitForm">Guardar</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="modal-status-form" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Actualizar status del contrato</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">

                                    <div class="form-group">
                                        <label for="status">Status</label>
                                        <select required class="form-control" v-model="contrato_actual.status">
                                            <option v-for="estado in estados" :value="estado">{{estado}}</option>
                                        </select>
                                    </div>

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" id="close-status-modal-btn" data-dismiss="modal">Cancelar</button>
                                    <button type="button" class="btn btn-success" @click="actualizarCambiosStatus">Guardar cambios</button>
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
            id_responsable: '',
            clientes: [],
            responsables: [],
            loading: true,
            status: 'En Ejecución',
            estados: [
                'En Ejecución',
                'Suspendido',
                'Terminado',
                'En Prorroga de Terminación',
                'Cancelado',
                'En Proceso de Cierre',
                'Penalizado'
            ],
            filtroStatus: '',
            contrato_actual: {
                status: 'En Ejecución',
            }
        },
        created: function() {
            this.cargarDatos();
        },

        computed: {

            filtro: function() {

                if (!this.filtroStatus.length) {
                    return this.contratos;
                }

                return this.contratos.filter((item) => item.status === this.filtroStatus);
            }
        },
        methods: {
            submitForm() {
                document.getElementById('form-submit-btn').click()
            },

            editarStatus(contrato) {
                this.contrato_actual = Object.assign({}, contrato);
                document.getElementById('modal-status-form-btn').click()
            },
            actualizarCambiosStatus: async function() {
                if (!this.contrato_actual.id_contrato) return;
                await axios.post('api.php/actualizar_status_contrato', {
                    id: this.contrato_actual.id_contrato,
                    status: this.contrato_actual.status
                })
                document.getElementById('close-status-modal-btn').click();
                this.cargarDatos();
            },

            initForm: function() {
                this.file = null;
                this.no_expediente = '';
                this.fecha_inicio = '';
                this.fecha_termino = '';
                this.id_cliente = '';
                this.id_responsable = '';
                this.status = 'En Ejecución';
            },

            enviarDatos: async function() {

                if (!this.id_cliente || !this.fecha_termino || !this.fecha_inicio || !this.id_responsable) {
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
                    id_responsable: this.id_responsable,
                    fecha_inicio: this.fecha_inicio,
                    fecha_termino: this.fecha_termino,
                    path: data.ruta,
                    status: this.status
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
                this.loading = true;
                const response = await axios("api.php/contratos");
                this.contratos = response.data;

                await this.fetchClientes();
                await this.fetchResponsables();

                this.loading = false;
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
            fetchResponsables: async function() {
                const response = await axios("api.php/responsables");
                this.responsables = response.data;
            }
        },


    });
</script>
<?php include('footer.php'); ?>