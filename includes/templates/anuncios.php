<?php 
    // __DIR__ === C:\xampp\htdocs\bienesraices\includes\templates
    // "los require son relativos a los documentos que los esta mandando llamar (VIDEO 331)"
    use App\Propiedad;
    
    if($_SERVER["SCRIPT_NAME"] === "/bienesraices/index.php") {
        $propiedades = Propiedad::get(3);
    } else {
        $propiedades = Propiedad::all();
    }
?>
<div class="contenedor-anuncios"> 
    <?php foreach($propiedades as $propiedad): ?> 
        <div class="anuncio">
            <img loading="lazy" src="imagenes/<?php echo $propiedad->imagen; ?>" alt="anuncio">
            <div class="contenido-anuncio">
                <h3><?php echo $propiedad->titulo; ?></h3>
                <p><?php echo $propiedad->descripcion; ?></p>
                <p class="precio">USD <?php echo number_format($propiedad->precio, 0, ",", ".")?></p>
                <ul class="iconos-caracteristicas">
                    <li>
                        <img class="icono" src="build/img/icono_dormitorio.svg" alt="icono dormitorio">
                        <p><?php echo $propiedad->habitaciones; ?></p>
                    </li>
                    <li>
                        <img class="icono" src="build/img/icono_wc.svg" alt="icono wc">
                        <p><?php echo $propiedad->wc; ?></p>
                    </li>
                    <li>
                        <img class="icono" src="build/img/icono_estacionamiento.svg" alt="icono estacionamiento">
                        <p><?php echo $propiedad->estacionamiento; ?></p>
                    </li>
                </ul>
                <a href="anuncio.php?id=<?php echo $propiedad->id; ?>" class="boton-amarillo-block">Ver Propiedad</a>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<?php 
    // mysqli_close($db);
    // ya no hace falta liberar la memoria de esta forma 
    // ver explicacion en z.notas.txt - VIDEO 392
?>