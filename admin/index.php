<?php
    /*
    echo "<pre>";
    print_r($_GET);
    echo "</pre>";
    */

    require "../includes/config/database.php";
    $db = conectarDB(); // instancia de la conexion a la BD
    $query = "SELECT * FROM propiedades";
    $result_query = mysqli_query($db, $query);


    // confirmacion de exito si una propiedad se cargo correctamente (se envia desde crear.php, como parte de la query string del header("Location":...))
    $result = $_GET["result"] ?? null;
    // "??" placeholder php que, si no existe lo que se le pasa antes de "??" a $result (en este caso $_GET["result"]), le asignará lo que especifiquemos despues de "??" a $result (en este caso null) 

    require "../includes/funciones.php";
    incluirTemplate("header");
?>

    <main class="contenedor seccion">
        <h1>Administrador de Bienes Raices</h1>

        <!-- mensaje de exito al crear una nueva propiedad correctamente -->
        <?php if(intval($result) === 1): // intval() devuelve el valor integer de una variable ?>  
            <p class="alerta exito">Anuncio creado correctamente</p> 
        <?php elseif(intval($result) === 2): ?>  
            <p class="alerta exito">Anuncio editado correctamente</p> 
        <?php endif; ?>
        
        <a href="/bienesraices/admin/propiedades/crear.php" class="boton boton-verde">Nueva Propiedad</a>
        <!-- 
        BOTONES PARA PRUEBAS DE INYECCION SQL (APUNTAN A ARCHIVOS DENTRO DE /admin/propiedades)
        <a href="/bienesraices/admin/propiedades/inyeccion.php" class="boton boton-amarillo">Buscar Vendedor</a>
        <a href="/bienesraices/admin/propiedades/inyeccion2.php" class="boton boton-verde">Login Devstagram</a>
        <a href="/bienesraices/admin/propiedades/inyeccion3.php" class="boton boton-amarillo">Baja de Usuario</a>
         -->

        <table class="propiedades">
            <thead>
                <th>ID</th>
                <th>Título</th>
                <th>Imagen</th>
                <th>Precio</th>
                <th>Acciones</th>
            </thead>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($result_query) ): ?>
                    <tr>
                        <td><?php echo $row["id"]; ?></td>
                        <td><?php echo $row["titulo"]; ?></td>
                        <td><img src="../imagenes/<?php echo $row["imagen"]; ?>" class="imagen-tabla" alt="imagen propiedad"></td>
                        <td>$ <?php echo $row["precio"]; ?></td>
                        <td>
                            <a href="/bienesraices/admin/propiedades/actualizar.php?id=<?php echo $row['id']; ?>" class="boton-amarillo-block">Actualizar</a>
                            <a href="#" class="boton-rojo-block">Eliminar</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

    </main>

<?php
    mysqli_close($db); // cierro la conexion a la DB
    incluirTemplate("footer"); 
?>