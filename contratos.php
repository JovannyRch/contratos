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
                <form @submit.prevent="enviarDatos" enctype="multipart/form-data">

                    <div class="row" style="margin-top:5%">
                        <div class="col-2">
                            <label for="noExpediente">No. Expediente</label>
                            <input type="text" v-model="no_expediente" class="form-control form-control-sm" maxlength="40">
                        </div>
                        <div class="col-4">
                            <label for="client">Cliente</label>
                            <input type="text" v-model="cliente" class="form-control form-control-sm" maxlength="40">
                        </div>
                        <div class="col-4">
                            <label for="responsable">Responsable</label>
                            <input type="text" v-model="responsable_ejecucion" class="form-control form-control-sm" maxlength="40">
                        </div>
                    </div>

                    <div class="row" style="margin-top:1%">
                        <div class="col-3">
                            <label for="noExpediente">Fecha Inicio</label>
                            <input type="date" v-model="fecha_inicio" class="form-control form-control-sm" maxlength="40">
                        </div>

                        <div class="col-3">
                            <label for="noExpediente">Fecha Termino</label>
                            <input type="date" v-model="fecha_termino" class="form-control form-control-sm" maxlength="40">
                        </div>

                        <div class="col-4">
                            <label for="subirContrato">Subir Contrato</label>
                            <div class="custom-file form-control-sm">
                                <input type="file" ref="file" class="custom-file-input" @change="onChangeFileUpload" id="customFile">
                                <label class="custom-file-label" for="customFile">Selecciona Archivo...</label>
                            </div>
                        </div>

                        <div class="col-2" style="margin-top:3%">
                            <button type="submit" class="btn btn-sm btn-secondary"><i class="bi bi-save2"></i>&nbsp;Guardar</button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="container-fluid" style="margin-top:4%">
                <table class="table">
                    <thead>
                        <tr class="table-warning">
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
            cliente: "",
            responsable_ejecucion: "",
            fecha_inicio: "",
            fecha_termino: "",
            file: '',
            contratos: [],
            esInvitado: "<?= $es_invitado ?>" === '1'
        },
        created: function() {
            this.cargarDatos();
        },
        methods: {
            enviarDatos: async function() {
                const respUploadFile = await subirArchivo(this.file);
                const {
                    data
                } = respUploadFile;
                const response = await axios.post('api.php/contratos', {
                    no_expediente: this.no_expediente,
                    cliente: this.cliente,
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
            },
            onChangeFileUpload: async function() {
                this.file = this.$refs.file.files[0];
                console.log("File", this.file);
            },
            cargarDatos: async function() {
                const response = await axios("api.php/contratos");
                this.contratos = response.data;
            },
            eliminar: async function(libro) {

                const respuestaUsuario = confirm(`¿Estas seguro de eliminar el libro ${libro.titulo}?`);
                if (respuestaUsuario) {
                    const response = await axios.post('api.php/eliminar_libro', {
                        id: libro.id_libro
                    });
                    this.cargarDatos();
                }
            }
        },


    });
</script>
<?php include('footer.php'); ?>