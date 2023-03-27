<fieldset>
    <legend>Información General</legend>

    <label for="titulo">Título de la propiedad:</label>
    <input type="text" id="titulo" name="propiedad[titulo]" placeholder="Título Propiedad" value="<?php echo s($propiedad->titulo); ?>">
    
    <label for="precio">Precio propiedad:</label>
    <input type="number" id="precio" name="propiedad[precio]" placeholder="Precio Propiedad" value="<?php echo s($propiedad->precio); ?>">
    
    <label for="imagen">Imagen:</label>
    <input type="file" id="imagen" accept="image/jpeg, image/png" name="propiedad[imagen]">
    
    <?php 
        if($_SERVER["SCRIPT_NAME"] === "/bienesraices/admin/propiedades/actualizar.php" and $propiedad->imagen) { 
    ?>
        <img src="/bienesraices/imagenes/<?php echo $propiedad->imagen; ?>" alt="imagen propiedad" class="imagen-small">
    <?php } ?>

    <label for="descripcion">Descripción:</label>
    <textarea id="descripcion" name="propiedad[descripcion]"><?php echo s($propiedad->descripcion); ?></textarea>
</fieldset>

<fieldset>
    <legend>Información de la propiedad</legend>

    <label for="habitaciones">Habitaciones:</label>
    <input type="number" id="habitaciones" name="propiedad[habitaciones]" placeholder="Ej: 3" min="0" max="9" value="<?php echo s($propiedad->habitaciones); ?>">
    
    <label for="wc">Baños:</label>
    <input type="number" id="wc" name="propiedad[wc]" placeholder="Ej: 3" min="0" max="9" value="<?php  echo s($propiedad->wc); ?>">
    
    <label for="estacionamientos">Estacionamientos:</label>
    <input type="number" id="estacionamientos" name="propiedad[estacionamiento]" placeholder="Ej: 3" min="0" max="9" value="<?php echo s($propiedad->estacionamiento); ?>"> 

</fieldset>

<fieldset>
    <legend>Vendedor</legend>
    <label for="vendedor">Vendedor</label>
    <select name="propiedad[vendedores_id]" id="vendedor">
        <option value="" disabled selected>-- Seleccione --</option>
        <?php foreach($vendedores as $vendedor){ ?>    
            <option 
                <?php echo $propiedad->vendedores_id === $vendedor->id ? "selected" : ""; ?>
                value="<?php echo s($vendedor->id); ?>"
            >
                <?php echo s($vendedor->nombre) . " " . s($vendedor->apellido); ?>
            </option>
        <?php } ?>
    </select>
</fieldset>
