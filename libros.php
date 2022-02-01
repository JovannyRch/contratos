<?php include('header.php'); ?>

<div class="container" id="app">
    <table class="table">
        <thead>
            <tr>
                <th>Id</th>
                <th>Titulo</th>
                <th>Descripcion</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="libro in libros">
                <td>{{libro.id_libro}}</td>
                <td>{{libro.titulo}}</td>
                <td>{{libro.descripcion}}</td>
                <td>
                    <button @click="eliminar(libro)" type="button" class="btn btn-danger">Eliminar</button>
                </td>
            </tr>
        </tbody>
    </table>
</div>
<script>
    const app = new Vue({
        el: "#app",
        data: {
            libros: [],
        },
        created: function() {
            this.cargarDatos();
        },
        methods: {
            cargarDatos: async function() {
                const response = await axios("api.php/libros");
                this.libros = response.data;
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