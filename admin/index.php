<?php
    require "../includes/funciones.php";
    incluirTemplate("header");
?>

    <main class="contenedor seccion">
        <h1>Administrador de Bienes Raices</h1>

        <a href="/bienesraices/admin/propiedades/crear.php" class="boton boton-verde">Nueva Propiedad</a>
        <!-- 
        PRUEBAS DE INYECCION SQL
        <a href="/bienesraices/admin/propiedades/inyeccion.php" class="boton boton-amarillo">Buscar Vendedor</a>
        <a href="/bienesraices/admin/propiedades/inyeccion2.php" class="boton boton-verde">Login Devstagram</a>
        <a href="/bienesraices/admin/propiedades/inyeccion3.php" class="boton boton-amarillo">Baja de Usuario</a>
         -->
    </main>

<?php incluirTemplate("footer"); ?>