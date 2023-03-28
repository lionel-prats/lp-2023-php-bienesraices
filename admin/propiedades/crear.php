<?php
   
    require "../../includes/app.php";
    use App\Propiedad;
    use App\Vendedor;
    use Intervention\Image\ImageManagerStatic as Image;

    //debuguear(TEMPLATES_URL . "/formulario_propiedades.php");
 
    userLogued();
    
    // aca instaciamos Propiedad para que los placeholder del form de creacion de inmueble no tiren warnings (ver formulario_propiedads.php)
    $propiedad = new Propiedad();
   
    $vendedores = Vendedor::all();

    $errores = Propiedad::getErrores();
 
    if($_SERVER["REQUEST_METHOD"] === "POST") {
        $propiedad = new Propiedad($_POST["propiedad"]);
        //debuguear($propiedad);

        // verifico si el usuario envio una imagen
        if($_FILES["propiedad"]["tmp_name"]["imagen"]) {
            // genero un nombre unico para la imagen enviada por el usuario 
            $extension_image = substr($_FILES["propiedad"]["type"]["imagen"], 6);
            $numero_10_digitos_aleatorio = rand(); 
            $nombre_imagen = md5( uniqid( $numero_10_digitos_aleatorio, true ) ) . "." . $extension_image; 
 
            // seteo el atributo $imagen (de la instancia de la clase Propiedad - $propiedad -) con el nombre generado y almacenado en $nombre_imagen 
            $propiedad->setImagen($nombre_imagen);
        }
        
        // valido posibles errores en los datos enviados por el usuario
        $errores = $propiedad->validar();
     
        if(empty($errores)) {

            if(!is_dir(CARPETA_IMAGENES)) 
                mkdir(CARPETA_IMAGENES);
            
            // realizo un resize a la imagen con la libreria importada intervention image
            $image = Image::make($_FILES["propiedad"]["tmp_name"]["imagen"])->fit(800, 600);

            // guardo la imagen enviada por el usuario en el servidor, usando la libreria intervention image
            $image->save(CARPETA_IMAGENES. $nombre_imagen);
            
            $propiedad->guardar();
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

            <?php //incluirTemplate("formulario_propiedades"); ?>

            <?php include('../../includes/templates/formulario_propiedades.php'); ?>
            
            <?php //include('C:\xampp\htdocs\bienesraices\includes/templates/formulario_propiedades.php'); ?>
            
            <input type="submit" value="Crear Propiedad" class="boton boton-verde">
        </form>
    </main>
    <?php 
    ?>
<?php incluirTemplate("footer"); ?>
