<?php
    // __DIR__ === C:\xampp\htdocs\bienesraices
    require "includes/app.php";
    use App\Propiedad;
    $id_property = $_GET["id"];
    $id_property =  filter_var($id_property, FILTER_VALIDATE_INT); 
    if(!$id_property)
        header("Location: /bienesraices/error.php");
    $propiedad = Propiedad::find($id_property);
    if(is_null($propiedad))
        header("Location: /bienesraices/error.php");
    incluirTemplate("header");
?>
    <main class="contenedor seccion contenido-centrado">
        <h1><?php echo $propiedad->titulo; ?></h1>
        <!-- <picture>
            <source srcset="build/img/destacada.webp" type="image/webp">
            <source srcset="build/img/destacada.jpg" type="image/jpeg">
        </picture> -->
        <img loading="lazy" src="imagenes/<?php echo $propiedad->imagen; ?>" alt="imagen de la propiedad">
        <div class="resumen-propiedad">
            <p class="precio">USD <?php echo number_format($propiedad->precio, 0, ",", ".")?></p>
            <ul class="iconos-caracteristicas">
                <li>
                    <img class="icono" src="build/img/icono_dormitorio.svg" alt="icono dormitorio">
                    <p><?php echo $propiedad->habitaciones; ?></p>
                </li>    
                <li>
                    <img class="icono" src="build/img/icono_wc.svg" alt="icono wc">
                    <p><?php echo $propiedad->wc ?></p>
                </li>
                <li>
                    <img class="icono" src="build/img/icono_estacionamiento.svg" alt="icono estacionamiento">
                    <p><?php echo $propiedad->estacionamiento ?></p>
                </li>
            </ul>
            <p><?php echo $propiedad->descripcion ?></p>
        </div>
    </main>
<?php 
    //mysqli_close($db);
    incluirTemplate("footer"); 
?>
