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
        <div class="container-fluid" id="app">

            <h3>Control de Contratos</h3>
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
                        <input type="file" class="custom-file-input" id="customFile">
                        <label class="custom-file-label" for="customFile">Selecciona Archivo...</label>
                    </div>
                </div>

                <div class="col-2" style="margin-top:3%">
                    <button type="button" @click="enviarDatos" class="btn btn-sm btn-secondary"><i class="bi bi-save2"></i>&nbsp;Guardar</button>
                </div>
            </div>
        </div>

        <div class="container-fluid" id="appTwo" style="margin-top:4%">
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
                        <td></td>
                        <td>
                            <button @click="eliminar(libro)" type="button" class="btn btn-secondary btn-sm"><i class="bi bi-trash"></i></button>
                            <button @click="eliminar(libro)" type="button" class="btn btn-secondary btn-sm"><i class="bi bi-pencil-fill"></i></button>
                            <button @click="eliminar(libro)" type="button" class="btn btn-secondary btn-sm"><i class="bi bi-file-earmark-pdf"></i></button>
                            <button @click="eliminar(libro)" type="button" class="btn btn-secondary btn-sm"><i class="bi bi-arrow-counterclockwise"></i></button>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    const app = new Vue({
        el: '#app',
        data: {
            no_expediente: "",
            cliente: "",
            responsable_ejecucion: "",
            fecha_inicio: "",
            fecha_termino: "",
        },

        methods: {
            enviarDatos: async function() {
                const response = await axios.post('api.php/contratos', {
                    no_expediente: this.no_expediente,
                    cliente: this.cliente,
                    responsable_ejecucion: this.responsable_ejecucion,
                    fecha_inicio: this.fecha_inicio,
                    fecha_termino: this.fecha_termino,
                });
                this.no_expediente = "";
                this.cliente = "";
                this.responsable_ejecucion = "";
                this.fecha_inicio = "";
                this.fecha_termino = "";
            },

        }

    });


    const appTwo = new Vue({
        el: "#appTwo",
        data: {
            contratos: [],
        },
        created: function() {
            this.cargarDatos();
        },
        methods: {
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
        }
    });
</script>
<?php include('footer.php'); ?>