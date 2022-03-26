<?php

session_start();

if (sizeof($_SESSION) == 0 || !isset($_SESSION['id_usuario'])) {
    header("Location: contratos.php");
}

include_once("headers/header_admin.php");

?>
<div id="app">

    <div class="container mt-4">
        <h3>Detalles cliente</h3>

        <div v-if="loading" class="text-center mt-5">
            <div class="spinner-border text-primary " role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <div v-else>
            <div class="card mt-3">
                <div class="card-body">
                    Nombre del cliente: <b>{{cliente.nombre}}</b> <br />
                    Total de contratos: <b>{{contratos.length}}</b> <br />
                </div>
            </div>
            <div v-if="contratos.length" class="mt-4">
                <h5 class="mb-3">Contratos</h5>
                <table class="table table-hover ">
                    <thead class="thead-dark ">
                        <tr>
                            <th># Expediente</th>
                            <th>Responsable</th>
                            <th>Fecha Inicio</th>
                            <th>Fecha Termino</th>
                            <th>Archivo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="contrato in contratos">
                            <td>{{contrato.no_expediente}}</td>
                            <td>{{contrato.responsable_ejecucion}}</td>
                            <td>{{contrato.fecha_inicio}}</td>
                            <td>{{contrato.fecha_termino}}</td>
                            <td>
                                <a :href="contrato.path" target="_blank">Ver archivo</a> <br />
                                <a :href="`contrato.php?id=${contrato.id_contrato}`">Ver detalles</a>
                            </td>
                        </tr>
                        <tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    new Vue({
        el: '#app',
        data: {
            loading: true,
            id_cliente: `<?= $_GET['id'] ?>`,
            cliente: {
                total: 0,
                nombre: 0
            },
            contratos: [],
        },
        created: function() {
            if (!this.id_cliente) {
                history.back()
            }
            this.cargarDatos();
        },
        methods: {
            cargarDatos: async function() {
                this.loading = true;
                await this.cargarDetalles();
                await this.cargarContratos();
                this.loading = false;
            },
            cargarDetalles: async function() {
                const response = await axios.post("api.php/get_cliente", {
                    id: this.id_cliente
                });
                this.cliente = response.data;
            },
            cargarContratos: async function() {
                const response = await axios.post("api.php/get_contratos_cliente", {
                    id: this.id_cliente
                });
                this.contratos = response.data;
            }
        }
    })
</script>