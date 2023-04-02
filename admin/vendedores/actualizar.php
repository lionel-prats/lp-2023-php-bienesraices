<?php
    require "../../includes/app.php";
    use App\Vendedor;
    userLogued();
    $id_vendedor = $_GET["id"];
    $id_vendedor =  filter_var($id_vendedor, FILTER_VALIDATE_INT); 
    if(!$id_vendedor)
        header("Location: /bienesraices/admin");
    $vendedor = Vendedor::find($id_vendedor);
    $errores = Vendedor::getErrores(); 
    if($_SERVER["REQUEST_METHOD"] === "POST") {
        $args = $_POST["vendedor"];
        $vendedor->sincronizar($args);
        $errores = $vendedor->validar();
        if(empty($errores)) {
            $vendedor->guardar();
        }
    }
    incluirTemplate("header");
?>
<main class="contenedor seccion">
    <h1>Actualizar Vendedor</h1>
    <a href="/bienesraices/admin/" class="boton boton-verde">Volver</a>
    <?php foreach ($errores as $key => $error): ?>
        <div class="alerta error">
            <?php echo $error; ?> 
        </div>
    <?php endforeach ?>
    <form class="formulario" method="POST"> 
        <?php include('../../includes/templates/formulario_vendedores.php'); ?>
        <input type="submit" value="Guardar cambios" class="boton boton-verde">
    </form>
</main>
<?php incluirTemplate("footer"); ?>