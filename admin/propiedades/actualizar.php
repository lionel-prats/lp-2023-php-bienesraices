<?php
    use App\Propiedad;
    use App\Vendedor;
    use Intervention\Image\ImageManagerStatic as Image;
    require "../../includes/app.php";
    userLogued();
    
    //debuguear($_SERVER);
    // validar la URL por id valido
    $id_propiedad = $_GET["id"];
    $id_propiedad =  filter_var($id_propiedad, FILTER_VALIDATE_INT); 
    if(!$id_propiedad)
        header("Location: /bienesraices/admin");
    
    $propiedad = Propiedad::find($id_propiedad);

    $vendedores = Vendedor::all();

    // inicializamos la variable $errores como array vacio para evitar warnings en la renderizacion de la vista
    $errores = Propiedad::getErrores(); 

    $modificado = date("Y/m/d");
    
    if($_SERVER["REQUEST_METHOD"] === "POST") {

        $args = $_POST["propiedad"];

        $propiedad->sincronizar($args);

        // verifico si el usuario envio una imagen
        if($_FILES["propiedad"]["tmp_name"]["imagen"]) {
            // guardo en memoria el nombre de la vieja imagen para eliminarla luego, ya que el usuario la quiere cambiar
            $oldImage = $propiedad->imagen;
            
            // genero un nombre unico para la imagen enviada por el usuario 
            $extension_image = substr($_FILES["propiedad"]["type"]["imagen"], 6);
            $numero_10_digitos_aleatorio = rand(); 
            $nombre_imagen = md5( uniqid( $numero_10_digitos_aleatorio, true ) ) . "." . $extension_image; 
            // seteo el atributo $imagen (de la instancia de la clase Propiedad - $propiedad -) con el nombre generado y almacenado en $nombre_imagen 
            $propiedad->setImagen($nombre_imagen);
        }

        $errores = $propiedad->validar();
    
        if(empty($errores)) {            
            // si el usuario quiere cambiar la imagen, elimino la anterior del servidor
            if(isset($oldImage)) {
                $propiedad->deleteImage($oldImage);
                // almaceno la nueva imagen en el disco duro
                $image = Image::make($_FILES["propiedad"]["tmp_name"]["imagen"])->fit(800, 600);    
                $image->save(CARPETA_IMAGENES. $nombre_imagen);
            }
            
            // UPDATE de registro en DB
            $propiedad->guardar();
        }
    }
    
    incluirTemplate("header");
?>

    <main class="contenedor seccion">
        <h1>Actualizar Propiedad</h1>

        <a href="/bienesraices/admin/" class="boton boton-verde">Volver</a>

        <?php foreach ($errores as $key => $error): ?>
            <div class="alerta error">
                <?php echo $error; ?> 
            </div>
        <?php endforeach ?>

        <!-- VIDEO 328 -->
        <!-- si omito el action="...", al submitear el form, este se envia a la misma URL en la que estaba parado antes del submit -->
        <!-- en este caso http://localhost/bienesraices/admin/propiedades/actualizar.php?id=2 -->
        <!-- en este caso me sirve porque al principio validamos si llego un "id" valido por GET -->
        <!-- es lo mismo que completar el action asi -> action="/bienesraices/admin/propiedades/actualizar.php?id=2" -->
        <!-- ya que aunque modifique el valor del id por un string en la URL -> id="hola" antes de submitear, el form se va a enviar por POST a lo que especifiquemos (u omitamos / herencia) en el atributo action -->
        <!-- de esta forma salta la validacion del id, no termino de entender como funciona con las pruebas que hice, pero funciona, asi que sigo avanzando -->
        <form class="formulario" method="POST" enctype="multipart/form-data"> 
            
            <?php include('../../includes/templates/formulario_propiedades.php'); ?>

            <input type="submit" value="Actualizar Propiedad" class="boton boton-verde">
        </form>
    </main>
    <?php 
    ?>
<?php incluirTemplate("footer"); ?>
