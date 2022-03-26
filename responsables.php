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

            <h3>Control de responsables</h3>

            <div style="display: flex; justify-content: flex-end; width: 100%;">
                <button class="btn btn-success btn-sm" data-toggle="modal" id="open-modal-btn" data-target="#modal-form" @click="initForm">
                    <i class="fa fa-plus"></i>
                    Nuevo responsable
                </button>
            </div>

            <div>
                <form class="form-inline">
                    <div class="form-group">
                        <input type="text" v-model="busqueda" class="form-control" placeholder="Bucar responsable">
                    </div>
                </form>
            </div>

            <div class="alert alert-info text-center mt-4" role="alert" v-if="filtro.length === 0">
                <strong>No se han encontrado responsables</strong>
            </div>

            <table class="table mt-3" v-else>
                <thead>
                    <tr class="table-light">
                        <th>Id</th>
                        <th>Nombre del Responsable</th>
                        <th>Total contratos</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="responsable in filtro">
                        <td>{{responsable.id_responsable}}</td>
                        <td>{{responsable.nombre}}</td>
                        <td>{{responsable.total_contratos}}</td>
                        <td>
                            <a :href="`responsable.php?id=${responsable.id_responsable}`" type="button" class="btn btn-dark btn-sm"><i class="fa fa-eye"></i></a>
                            <button @click="editar(responsable)" type="button" class="btn btn-dark btn-sm"><i class="fa fa-pen"></i></button>
                            <button @click="eliminar(responsable)" type="button" class="btn btn-dark btn-sm"><i class="fa fa-trash"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Registrar nuevo responsable</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">

                            <div class="form-group">
                                <label for="nombre">Nombre del responsable</label>
                                <input v-model="nombreResponsable" type="text" class="form-control" name="nombre" id="nombre" placeholder="Ingrese nombre del responsable">

                            </div>


                        </div>
                        <div class="modal-footer">
                            <button type="button" id="close-modal-btn" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-success btn-sm" @click="registrar" v-if="id_responsable == null">
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
            responsables: [],
            nombreResponsable: '',
            id_responsable: null,
            busqueda: '',
        },
        created: function() {
            this.cargarDatos();
        },

        computed: {
            filtro: function() {

                if (this.busqueda.length === 0) {
                    return this.responsables;
                }
                return this.responsables.filter((item) => item.nombre.toLowerCase().includes(this.busqueda.toLowerCase()));
            }
        },
        methods: {

            initForm: function() {
                this.id_responsable = null;
                this.nombreResponsable = '';
            },

            guardarCambios: async function() {

                if (this.id_responsable == null) return;

                if (this.nombreResponsable.length === 0) {
                    alert("El nombre del responsable no puede estar vacío")
                    return;
                }
                const response = await axios.post('api.php/actualizar_responsable', {
                    id: this.id_responsable,
                    nombre: this.nombreResponsable
                });
                Swal.fire(
                    'Responsable actualizado',
                    'Se han guardado los cambios del responsable',
                    'success'
                )
                document.getElementById('close-modal-btn').click();
                this.cargarDatos();
            },

            registrar: async function() {

                if (this.nombreResponsable.length === 0) {
                    return;
                }

                const response = await axios.post('api.php/registrar_responsable', {
                    nombre: this.nombreResponsable
                });
                Swal.fire(
                    'Responsable registrado',
                    'Se ha guardado el nuevo responsable con éxito',
                    'success'
                )
                document.getElementById('close-modal-btn').click();
                this.cargarDatos();
            },
            cargarDatos: async function() {
                const response = await axios("api.php/responsables_con_total");
                this.responsables = response.data;
            },
            eliminar: async function(responsable) {
                if (confirm(`¿Está seguro de eliminar al responsable '${responsable.nombre}'?`)) {
                    const response = await axios.post('api.php/eliminar_responsable', {
                        id: responsable.id_responsable
                    });
                    Swal.fire(
                        'Responsable eliminado',
                        'Se ha eliminado al responsable con éxito',
                        'success'
                    )
                    this.cargarDatos();
                }
            },
            editar: function(responsable) {
                document.getElementById('open-modal-btn').click();
                this.id_responsable = responsable.id_responsable;
                this.nombreResponsable = responsable.nombre;
            }
        },


    });
</script>
<?php include('footer.php'); ?>