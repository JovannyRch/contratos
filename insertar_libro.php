<?php include('header.php'); ?>

<div class="container pt-4" id="app">
    <div class="form-group">
      <label for="">Titulo del libro</label>
      <input type="text"
        class="form-control" v-model="titulo" placeholder="Ingresa el titulo del libro">

    </div>

    <div class="form-group">
      <label for="">Descripcion del libro</label>
       <textarea class="form-control"  v-model="descripcion" rows="3"></textarea>

    </div>

    <button type="button" @click="enviarDatos" class="btn btn-success">Guardar</button>
</div>

<script>
    const app = new Vue({
        el: "#app",
        data: {
            titulo: "",
            descripcion: "",
        },
        methods: {
            enviarDatos: async function() {
                const response = await axios.post('api.php/libros', { titulo: this.titulo, descripcion: this.descripcion});
                alert("Libro guardado correctamente");
                this.titulo = "";
                this.descripcion = "";
            }
        }
    });
</script>



<?php include('footer.php'); ?>