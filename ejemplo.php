<?php include('header.php'); ?>

<div class="container" id="app">
    {{saludo}}
    <button type="button" class="btn btn-primary" @click="saludar">Saludar</button>
    <button type="button" class="btn btn-primary" @click="saludar2()">Saludar desde consola</button>
</div>
<script>
    const app = new Vue({
        el: "#app",
        data: {
            saludo: "Hola mundo con Vue!",
            contratos: [],
        },
        mounted: function(){
            alert("Inicio");
        },
        methods: {
            saludar: function() {
                alert("Hola desde Vue");
            },
            saludar2: function() {
                console.log("Hola desed la consola");
            },
        }
    });
</script>



<?php include('footer.php'); ?>