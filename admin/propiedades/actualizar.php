<?php

    // en este bloque valido si llego el query string "id" y si tiene valor numerico (seria el id de una propiedad a editar)
    // si "id" no llego, o no es un numero entero, redirecciono a la pantalla de inicio del administrador
    $id_propiedad = $_GET["id"];
    $id_propiedad =  filter_var($id_propiedad, FILTER_VALIDATE_INT); 
    if(!$id_propiedad)
        header("Location: /bienesraices/admin");

    require "../../includes/config/database.php";
    $db = conectarDB();

    // *** bloque que obtiene los datos de la propiedad a editar y la guarda en $property_founded 
    $query2 = "SELECT * FROM propiedades WHERE id = $id_propiedad";
    $resultado2 = mysqli_query($db, $query2); 
    
    $property_founded = mysqli_fetch_assoc($resultado2); 
    // de esta forma capturo solo el primer elemento de todos los registros que pueda retornar una consulta a la BD (que igual en este caso se de antemano que va a ser solo 1 registro)
    // notar que la funcion mysqli_fetch_assoc() tambien sirve para iterar una consulta que retorne multiples registros, junto a un while() (ejemplo, <select> de vendedores en el form de esta pagina)
    // *** fin del bloque 

    /* echo "<pre>";
    print_r($property_founded);
    echo "</pre>"; */

    $query2 = "SELECT * FROM vendedores";
    $resultado2 = mysqli_query($db, $query2); 

    $errores = []; 

    $titulo = $property_founded["titulo"];
    $precio = $property_founded["precio"];
    $descripcion = $property_founded["descripcion"];
    $habitaciones = $property_founded["habitaciones"];
    $wc = $property_founded["wc"];
    $estacionamientos = $property_founded["estacionamiento"];
    $vendedores_id = $property_founded["vendedores_id"];
    $property_image = $property_founded["imagen"];

    $modificado = date("Y/m/d");
    
    
    if($_SERVER["REQUEST_METHOD"] === "POST") {
        echo "<pre>";
        print_r($_POST);
        echo "</pre>";

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

        /*
        // validacion imagen
        $types_image_allowed = ['image/jpg', 'image/jpeg','image/png', 'image/webp'];
        $type_allowed = false;
        if(!$imagen["name"] || $imagen["error"]) 
            $errores[] = "La imagen es obligatoria";
        else {
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
        */

        if(empty($errores)) {


            /*

            // subida de imagenes al servidor

            // crear carpeta
            $carpeta_imagenes = "../../imagenes/";
            if(!is_dir($carpeta_imagenes)) // is_dir('path/nombre_carpeta') -> funcion php que nos indica si existe o no la carpeta especificada dentro del proyecto
                mkdir($carpeta_imagenes); // mkdir() -> funcion php para crear una carpeta dentro del proyecto, donde le especifiquemos (en este caso, creamos la carpeta imagenes en la raiz, por eso nos movemos 2 instancias para atras)
            
            // generar un nombre unico para las imagenes 
            $extension_image = substr($imagen["type"], 6);
            $numero_10_digitos_aleatorio = rand(); // funcion php que por default genera un numero aleatorio de 10 digitos, pero podemos pasarle minimo y maximo como argumentos 
            $nombre_imagen = md5( uniqid( $numero_10_digitos_aleatorio, true ) ) . "." . $extension_image; // generamos un nombre aleatorio para cada imagen que subamos al servidor

            // subir imagen
            move_uploaded_file($imagen["tmp_name"], $carpeta_imagenes . $nombre_imagen); 
            // move_uploaded_file() -> funcion php para guardar un archivo en el servidor 
            // como 1er parametro le pasamos la ubicacion temporal en la que esta almacenada la imagen (viene en el superglobal $_FILES)
            // como 2do parametro le pasamos el path definitivo dentro del proyecto incluyendo el nombre que le daremos a la imagen en el servidor

            */
            
            // update en la DB
            $query = "UPDATE propiedades SET titulo = '$titulo', precio = $precio, descripcion = '$descripcion', habitaciones = $habitaciones, wc = $wc, estacionamiento = $estacionamientos, vendedores_id = $vendedores_id, modificado = '$modificado' WHERE id = $id_propiedad";

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

    require "../../includes/funciones.php";
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
            <fieldset>
                <legend>Información General</legend>

                <label for="titulo">Título de la propiedad:</label>
                <input type="text" id="titulo" name="titulo" action="/bienesraices/admin/propiedades/actualizar.php?id=2" placeholder="Título Propiedad" value="<?php  echo $titulo; ?>">
                
                <label for="precio">Precio propiedad:</label>
                <input type="number" id="precio" name="precio" placeholder="Precio Propiedad" value="<?php  echo $precio; ?>">
                
                <label for="imagen">Imagen:</label>
                <input type="file" id="imagen" accept="image/jpeg, image/png" name="imagen">
                
                <img src="/bienesraices/imagenes/<?php echo $property_image; ?>" alt="imagen propiedad" class="imagen-small">

                <label for="descripcion">Descripción:</label>
                <textarea id="descripcion" name="descripcion"><?php  echo $descripcion; ?></textarea>
            </fieldset>

            <fieldset>
                <legend>Información de la propiedad</legend>

                <label for="habitaciones">Habitaciones:</label>
                <input type="number" id="habitaciones" name="habitaciones" placeholder="Ej: 3" min="0" max="9" value="<?php  echo $habitaciones; ?>">
                
                <label for="wc">Baños:</label>
                <input type="number" id="wc" name="wc" placeholder="Ej: 3" min="0" max="9" value="<?php  echo $wc; ?>">
                
                <label for="estacionamientos">Estacionamientos:</label>
                <input type="number" id="estacionamientos" name="estacionamientos" placeholder="Ej: 3" min="0" max="9" value="<?php  echo $estacionamientos; ?>"> 
            
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
            <input type="submit" value="Actualizar Propiedad" class="boton boton-verde">
        </form>
    </main>
    <?php 
    ?>
<?php incluirTemplate("footer"); ?>
