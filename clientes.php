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
                <button class="btn btn-success btn-sm" data-toggle="modal" id="open-modal-btn" data-target="#modal-form" @click="initForm">
                    <i class="fa fa-plus"></i>
                    Nuevo cliente
                </button>
            </div>

            <div>
                <form class="form-inline">
                    <div class="form-group">
                        <input type="text" v-model="busqueda" class="form-control" placeholder="Bucar cliente">
                    </div>
                </form>
            </div>

            <div class="alert alert-info text-center mt-4" role="alert" v-if="filtroClientes.length === 0">
                <strong>No se han encontrado clientes</strong>
            </div>

            <table class="table mt-3" v-else>
                <thead>
                    <tr class="table-light">
                        <th>Id</th>
                        <th>Nombre del cliente</th>
                        <th>Total contratos</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="cliente in filtroClientes">
                        <td>{{cliente.id_cliente}}</td>
                        <td>{{cliente.nombre}}</td>
                        <td>{{cliente.total_contratos}}</td>
                        <td>
                            <a :href="`cliente.php?id=${cliente.id_cliente}`" type="button" class="btn btn-dark btn-sm"><i class="fa fa-eye"></i></a>
                            <button @click="editar(cliente)" type="button" class="btn btn-dark btn-sm"><i class="fa fa-pen"></i></button>
                            <button @click="eliminar(cliente)" type="button" class="btn btn-dark btn-sm"><i class="fa fa-trash"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
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
                            <button type="button" id="close-modal-btn" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-success btn-sm" @click="registrar" v-if="id_cliente == null">
                                Registrar
                            </button>
                            <button type="button" class="btn btn-success btn-sm" @click="guardarCambios" v-else>
                                Guardar cambios
                            </button>
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
            id_cliente: null,
            busqueda: '',
        },
        created: function() {
            this.cargarDatos();
        },

        computed: {
            filtroClientes: function() {

                if (this.busqueda.length === 0) {
                    return this.clientes;
                }
                return this.clientes.filter((cliente) => cliente.nombre.toLowerCase().includes(this.busqueda.toLowerCase()));
            }
        },
        methods: {

            initForm: function() {
                this.id_cliente = null;
                this.nombreCliente = '';
            },

            guardarCambios: async function() {

                if (this.id_cliente == null) return;

                if (this.nombreCliente.length === 0) {
                    alert("El nombre del cliente no puede estar vac??o")
                    return;
                }
                const response = await axios.post('api.php/actualizar_cliente', {
                    id: this.id_cliente,
                    nombre: this.nombreCliente
                });
                Swal.fire(
                    'Cliente actualizado',
                    'Se han guardado los cambios del cliente',
                    'success'
                )
                document.getElementById('close-modal-btn').click();
                this.cargarDatos();
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
                    'Se ha guardado el nuevo cliente con ??xito',
                    'success'
                )
                document.getElementById('close-modal-btn').click();
                this.cargarDatos();
            },
            cargarDatos: async function() {
                const response = await axios("api.php/clientes_con_total");
                this.clientes = response.data;
            },
            eliminar: async function(cliente) {
                if (confirm(`??Est?? seguro de eliminar al cliente '${cliente.nombre}'?`)) {
                    const response = await axios.post('api.php/eliminar_cliente', {
                        id: cliente.id_cliente
                    });
                    Swal.fire(
                        'Cliente eliminado',
                        'Se ha eliminado al cliente con ??xito',
                        'success'
                    )
                    this.cargarDatos();
                }
            },
            editar: function(cliente) {
                document.getElementById('open-modal-btn').click();
                this.id_cliente = cliente.id_cliente;
                this.nombreCliente = cliente.nombre;
            }
        },


    });
</script>
<?php include('footer.php'); ?>