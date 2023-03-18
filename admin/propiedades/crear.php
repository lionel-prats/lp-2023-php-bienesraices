<?php
    require "../../includes/app.php";
    use App\Propiedad;
    use Intervention\Image\ImageManagerStatic as Image;

    userLogued();

    $db = conectarDB();

    $query2 = "SELECT * FROM vendedores";
    $resultado2 = mysqli_query($db, $query2); 

    $errores = Propiedad::getErrores();

    $titulo = "";
    $precio = "";
    $descripcion = "";
    $habitaciones = "";
    $wc = "";
    $estacionamientos = "";
    $vendedores_id = "";
    
    if($_SERVER["REQUEST_METHOD"] === "POST") {

        $propiedad = new Propiedad($_POST);

        // verifico si el usuario envio una imagen
        if($_FILES["imagen"]["tmp_name"]) {
            // genero un nombre unico para la imagen enviada por el usuario 
            $extension_image = substr($_FILES["imagen"]["type"], 6);
            $numero_10_digitos_aleatorio = rand(); 
            $nombre_imagen = md5( uniqid( $numero_10_digitos_aleatorio, true ) ) . "." . $extension_image; 
 
            // seteo el atributo $imagen con el nombre generado y almacenado en $nombre_imagen 
            $propiedad->setImagen($nombre_imagen);
        }
        
        // valido posibles errores en los datos enviados por el usuario
        $errores = $propiedad->validar();
     
        if(empty($errores)) {

            if(!is_dir(CARPETA_IMAGENES)) 
                mkdir(CARPETA_IMAGENES);
            
            // realizo un resize a la imagen con la libreria importada intervention image
            $image = Image::make($_FILES["imagen"]["tmp_name"])->fit(800, 600);

            // guardo la imagen enviada por el usuario en el servidor, usando la libreria intervention image
            $image->save(CARPETA_IMAGENES. $nombre_imagen);
            
            $resultado = $propiedad->guardar();

            if($resultado){
                header("Location: /bienesraices/admin?result=1");
            }
        }
    }

    incluirTemplate("header");
?>

    <main class="contenedor seccion">
        <h1>Crear</h1>

        <a href="/bienesraices/admin/" class="boton boton-verde">Volver</a>

        <?php foreach ($errores as $key => $error): ?>
            <div class="alerta error">
                <?php echo $error; ?> 
            </div>
        <?php endforeach ?>

        <form class="formulario" method="POST" action="/bienesraices/admin/propiedades/crear.php" enctype="multipart/form-data"> 
            <fieldset>
                <legend>Información General</legend>

                <label for="titulo">Título de la propiedad:</label>
                <input type="text" id="titulo" name="titulo" placeholder="Título Propiedad" value="<?php  echo $titulo; ?>">
                
                <label for="precio">Precio propiedad:</label>
                <input type="number" id="precio" name="precio" placeholder="Precio Propiedad" value="<?php  echo $precio; ?>">
                
                <label for="imagen">Imagen:</label>
                <input type="file" id="imagen" accept="image/jpeg, image/png" name="imagen">
                
                <label for="descripcion">Descripción:</label>
                <textarea id="descripcion" name="descripcion"><?php echo $descripcion; ?></textarea>
            </fieldset>

            <fieldset>
                <legend>Información de la propiedad</legend>

                <label for="habitaciones">Habitaciones:</label>
                <input type="number" id="habitaciones" name="habitaciones" placeholder="Ej: 3" min="0" max="9" value="<?php echo $habitaciones; ?>">
                
                <label for="wc">Baños:</label>
                <input type="number" id="wc" name="wc" placeholder="Ej: 3" min="0" max="9" value="<?php  echo $wc; ?>">
                
                <label for="estacionamientos">Estacionamientos:</label>
                <input type="number" id="estacionamientos" name="estacionamientos" placeholder="Ej: 3" min="0" max="9" value="<?php echo $estacionamientos; ?>"> 
            
            </fieldset>

            <fieldset>
                <legend>Vendedor</legend>
                <select name="vendedores_id">
                    <option value="" disabled selected>-- Seleccione --</option>
                    <?php while($row = mysqli_fetch_assoc($resultado2) ): ?>
                        <option 
                            value="<?php echo $row["id"]; ?>" 
                            <?php echo $vendedores_id == $row["id"] ? "selected" : "";  ?>
                        >
                            <?php echo $row["nombre"] . " " . $row["apellido"]; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </fieldset>
            <input type="submit" value="Crear Propiedad" class="boton boton-verde">
        </form>
    </main>
    <?php 
    ?>
<?php incluirTemplate("footer"); ?>
