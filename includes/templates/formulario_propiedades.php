<fieldset>
    <legend>Información General</legend>

    <label for="titulo">Título de la propiedad:</label>
    <input type="text" id="titulo" name="titulo" placeholder="Título Propiedad" value="<?php echo s($propiedad->titulo); ?>">
    
    <label for="precio">Precio propiedad:</label>
    <input type="number" id="precio" name="precio" placeholder="Precio Propiedad" value="<?php echo s($propiedad->precio); ?>">
    
    <label for="imagen">Imagen:</label>
    <input type="file" id="imagen" accept="image/jpeg, image/png" name="imagen">
    
    <label for="descripcion">Descripción:</label>
    <textarea id="descripcion" name="descripcion"><?php echo s($propiedad->descripcion); ?></textarea>
</fieldset>

<fieldset>
    <legend>Información de la propiedad</legend>

    <label for="habitaciones">Habitaciones:</label>
    <input type="number" id="habitaciones" name="habitaciones" placeholder="Ej: 3" min="0" max="9" value="<?php echo s($propiedad->habitaciones); ?>">
    
    <label for="wc">Baños:</label>
    <input type="number" id="wc" name="wc" placeholder="Ej: 3" min="0" max="9" value="<?php  echo s($propiedad->wc); ?>">
    
    <label for="estacionamientos">Estacionamientos:</label>
    <input type="number" id="estacionamientos" name="estacionamientos" placeholder="Ej: 3" min="0" max="9" value="<?php echo s($propiedad->estacionamiento); ?>"> 

</fieldset>

<fieldset>
    <legend>Vendedor</legend>
    <select name="vendedores_id">
        <option value="" disabled selected>-- Seleccione --</option>
    </select>
</fieldset>