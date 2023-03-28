<?php
    // __DIR__ = C:\xampp\htdocs\bienesraices\admin
    
    require __DIR__ . '/../includes/app.php';
    userLogued();
    
    use App\Propiedad;
    use App\Vendedor;

    $propiedades = Propiedad::all(); // arreglo de objetos (1 objeto por propiedad)
    $vendedores = Vendedor::all();

    // bloque para eliminar un registro de propiedades
    if($_SERVER["REQUEST_METHOD"] === "POST") {

        $id_property = $_POST["id_property"];
        $id_property = filter_var($id_property, FILTER_VALIDATE_INT);
        // verificamos que haya llegado un int (evitamos inyeccion SQL, ya que se puede modificar el value del input:hidden - chequeado que funciona)
    
        if($id_property){          
            $propiedad = Propiedad::find($id_property);
            $propiedad->eliminar();
        } else {
            echo "<h1>ERROR</h1>";
            exit;
        }
    }
    
    // confirmacion de exito si una propiedad se cargo correctamente (se envia desde crear.php, como parte de la query string del header("Location":...))
    $result = $_GET["result"] ?? null;
    // "??" placeholder php que, si no existe lo que se le pasa antes de "??" a $result (en este caso $_GET["result"]), le asignará lo que especifiquemos despues de "??" a $result (en este caso null) 

    incluirTemplate("header");
?>

    <main class="contenedor seccion">
        <h1>Administrador de Bienes Raices</h1>

        <!-- mensaje de exito al crear una nueva propiedad correctamente -->
        <?php if(intval($result) === 1): // intval() devuelve el valor integer de una variable ?>  
            <p class="alerta exito">Publicación Creada Correctamente</p> 
        <?php elseif(intval($result) === 2): ?>  
            <p class="alerta exito">Publicación Actualizada Correctamente</p> 
        <?php elseif(intval($result) === 3): ?>  
            <p class="alerta exito">Publicación Eliminada Correctamente</p> 
        <?php endif; ?>
        
        <a href="/bienesraices/admin/propiedades/crear.php" class="boton boton-verde">Nueva Propiedad</a>
        <!-- 
        BOTONES PARA PRUEBAS DE INYECCION SQL (APUNTAN A ARCHIVOS DENTRO DE /admin/propiedades)
        <a href="/bienesraices/admin/propiedades/inyeccion.php" class="boton boton-amarillo">Buscar Vendedor</a>
        <a href="/bienesraices/admin/propiedades/inyeccion2.php" class="boton boton-verde">Login Devstagram</a>
        <a href="/bienesraices/admin/propiedades/inyeccion3.php" class="boton boton-amarillo">Baja de Usuario</a>
         -->
        <h2>Propiedades</h2>
        
        <table class="propiedades">
            <thead>
                <th>ID</th>
                <th>Título</th>
                <th>Imagen</th>
                <th>Precio</th>
                <th>Acciones</th>
            </thead>
            <tbody>
                <?php foreach($propiedades as $propiedad): ?>
                    <tr>
                        <td><?php echo $propiedad->id; ?></td>
                        <td><?php echo $propiedad->titulo; ?></td>
                        <td>
                            <img src="../imagenes/<?php echo $propiedad->imagen; ?>" class="imagen-tabla" alt="imagen propiedad"> 
                        </td>
                        <td>$ <?php echo $propiedad->precio; ?></td>
                        <td>
                            <a href="/bienesraices/admin/propiedades/actualizar.php?id=<?php echo $propiedad->id; ?>" class="boton-amarillo-block">Actualizar</a>
                            <form method="POST" class="w-100">
                                <input type="hidden" name="id_property" value="<?php echo $propiedad->id; ?>">
                                <input type="submit" class="boton-rojo-block w-100 lh-default" value="Eliminar">
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h2>Vendedores</h2>
        <table class="propiedades">
            <thead>
                <th>ID</th>
                <th>Nombre</th>
                <th>Teléfono</th>
                <th>Acciones</th>
            </thead>
            <tbody>
                <?php foreach($vendedores as $vendedor): ?>
                    <tr>
                        <td><?php echo $vendedor->id; ?></td>
                        <td><?php echo $vendedor->nombre . " " . $vendedor->apellido;; ?></td>
                        <td><?php echo $vendedor->telefono; ?></td> 
                        <td>

                            <a href="/bienesraices/admin/propiedades/actualizar.php?id=<?php echo $propiedad->id; ?>" class="boton-amarillo-block">Actualizar</a>

                            <form method="POST" class="w-100">
                                <input type="hidden" name="id_property" value="<?php echo $propiedad->id; ?>">
                                <input type="submit" class="boton-rojo-block w-100 lh-default" value="Eliminar">
                            </form>
                            
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    </main>

<?php

    // mysqli_close($db); // cierro la conexion a la DB // en el VIDEO 386 lo elimina porque "ya no se requiere" - seguramente es porque estamos usando la forma POO de la API mysqli (/includes/config/database.php) (VERIFICAR)

    incluirTemplate("footer"); 
?>