<?php

session_start();

if (sizeof($_SESSION) == 0 || !isset($_SESSION['id_usuario'])) {
    header("Location: contratos.php");
}

include_once("headers/header_admin.php");

?>


<div id="app">

    <div>
        <div class="container mt-4">

            <h3>Control de clientes</h3>

            <div style="display: flex; justify-content: flex-end; width: 100%;">
                <button class="btn btn-primary" data-toggle="modal" data-target="#modal-form" @click="initForm">Nuevo cliente</button>
            </div>

            <table class="table mt-3">
                <thead>
                    <tr class="table-light">
                        <th>Id</th>
                        <th>Nombre del cliente</th>
                        <th>Total contratos</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="cliente in clientes">
                        <td>{{cliente.id_cliente}}</td>
                        <td>{{cliente.nombre}}</td>
                        <td>{{cliente.total_contratos}}</td>


                        <td>
                            <button @click="eliminar(cliente)" type="button" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Registrar nuevo cliente</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">

                            <div class="form-group">
                                <label for="nombre">Nombre del cliente</label>
                                <input v-model="nombreCliente" type="text" class="form-control" name="nombre" id="nombre" placeholder="Ingrese nombre del cliente">

                            </div>


                        </div>
                        <div class="modal-footer">
                            <button type="button" id="close-modal-btn" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-success" @click="registrar">Registrar</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>


<script>
    new Vue({
        el: '#app',
        data: {
            clientes: [],
            nombreCliente: '',
        },
        created: function() {
            this.cargarDatos();
        },
        methods: {

            initForm: function() {
                this.nombreCliente = '';
            },
            registrar: async function() {

                if (this.nombreCliente.length === 0) {
                    return;
                }

                const response = await axios.post('api.php/registrar_cliente', {
                    nombre: this.nombreCliente
                });
                Swal.fire(
                    'Cliente registrado',
                    'Se ha guardado el nuevo cliente con éxito',
                    'success'
                )
                document.getElementById('close-modal-btn').click();
                this.cargarDatos();
            },
            cargarDatos: async function() {
                const response = await axios("api.php/clientes");
                this.clientes = response.data;
            },
            eliminar: async function(cliente) {
                if (confirm(`¿Está seguro de eliminar al cliente '${cliente.nombre}'?`)) {
                    const response = await axios.post('api.php/eliminar_cliente', {
                        id: cliente.id_cliente
                    });
                    Swal.fire(
                        'Cliente eliminado',
                        'Se ha guardado el cliente con éxito',
                        'success'
                    )
                    this.cargarDatos();
                }
            }
        },


    });
</script>
<?php include('footer.php'); ?>