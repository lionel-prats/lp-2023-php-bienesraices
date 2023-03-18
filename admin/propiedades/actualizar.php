<?php
    use App\Propiedad;
    require "../../includes/app.php";
    userLogued();
    
    //debuguear($_SERVER);
    // validar la URL por id valido
    $id_propiedad = $_GET["id"];
    $id_propiedad =  filter_var($id_propiedad, FILTER_VALIDATE_INT); 
    if(!$id_propiedad)
        header("Location: /bienesraices/admin");
    
   
    $propiedad = Propiedad::find($id_propiedad);

    $query2 = "SELECT * FROM vendedores";
    $resultado2 = mysqli_query($db, $query2); 

    $errores = []; 

    $modificado = date("Y/m/d");
    
    if($_SERVER["REQUEST_METHOD"] === "POST") {

        $imagen = $_FILES["imagen"];

        // con la funcion mysqli_real_escape_string() evitamos la inyeccion SQL
        $titulo = mysqli_real_escape_string($db, $_POST["titulo"]);
        $precio = mysqli_real_escape_string($db, $_POST["precio"]);
        $descripcion = mysqli_real_escape_string($db, $_POST["descripcion"]);
        $habitaciones = mysqli_real_escape_string($db, $_POST["habitaciones"]);
        $wc = mysqli_real_escape_string($db, $_POST["wc"]);
        $estacionamientos = mysqli_real_escape_string($db, $_POST["estacionamientos"]);
        if(isset($_POST["vendedores_id"]))
            $vendedores_id = mysqli_real_escape_string($db, $_POST["vendedores_id"]);
            
        if(!$titulo) {
            $errores[] = "Debes añadir un título";
        }
        if(!$precio) {
            $errores[] = "El precio es obligatorio";
        }
        if(strlen($descripcion) < 50) {
            $errores[] = "La descripción es obligatoria y debe ser de al menos 50 caracteres";
        }
        if($habitaciones === "") {
            $errores[] = "El numero de habitaciones es obligatorio";
        }
        if($wc === "") {
            $errores[] = "El numero de baños es obligatorio";
        }
        if($estacionamientos === "") {
            $errores[] = "El numero de estacionamientos es obligatorio";
        }
        if(!$vendedores_id) {
            $errores[] = "Elige un vendedor";
        } 

        
        // validacion imagen
        $types_image_allowed = ['image/jpg', 'image/jpeg','image/png', 'image/webp'];
        $type_allowed = false;
        
        // validacion de imagen, si es que el usuario cargo 1 nueva
        if($imagen["name"]){
            foreach($types_image_allowed as $type){
                if($imagen["type"] == $type) {
                    $type_allowed = true;
                    break;
                }
            }
            if(!$type_allowed)
                $errores[] = "El formato de archivo no es válido"; 
            else {
                $peso_maximo_imagen = 1000 * 400; // 1kb == 1000 bytes -> tamaño maximo permitido para imagen == 100kb
                if($imagen["size"] > $peso_maximo_imagen)
                    $errores[] = "La imagen es muy pesada"; 
            }
        }
        // fin validacion imagen
       

        if(empty($errores)) {
            
            $nombre_imagen = $property_image;

            // borrado de imagen anterior y subida al server de imagen nueva, si el usuario cargo una nueva imagen para la propiedad            
            if($imagen["name"]){
                $carpeta_imagenes = "../../imagenes/";
                unlink( $carpeta_imagenes . $property_image ); 
                // funcion php para eliminar archivos que esten dentro del servidor
                // le pasamos el path relativo del archivo que queremos eliminar (../../imagenes/ae55166e7c9db8ed239ad5910bbba41c.jpeg)

                // generar un nombre unico para las imagenes 
                $extension_image = substr($imagen["type"], 6);
                $numero_10_digitos_aleatorio = rand(); // ver descripcion en crear.php
                $nombre_imagen = md5( uniqid( $numero_10_digitos_aleatorio, true ) ) . "." . $extension_image; // ver descripcion en crear.php

                // subir imagen
                move_uploaded_file($imagen["tmp_name"], $carpeta_imagenes . $nombre_imagen); // ver descripcion en crear.php
            }

            // update en la DB
            $query = "UPDATE propiedades SET titulo = '$titulo', precio = $precio, imagen = '$nombre_imagen' ,descripcion = '$descripcion', habitaciones = $habitaciones, wc = $wc, estacionamiento = $estacionamientos, vendedores_id = $vendedores_id, modificado = '$modificado' WHERE id = $id_propiedad";

            // echo $query; 
    
            $resultado = mysqli_query($db, $query); 
            // le paso la instancia de la conexion y la query
            // la ejecucion de mysql_query arroja un bool -> true si el insert se ejecuto correctamente 

            if($resultado){
                // redireccionar al usuario luego de creado el registro 
                // esta funcion sirve para enviar datos en el encabezado de una peticion HTTP
                header("Location: /bienesraices/admin?result=2");
            }
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
