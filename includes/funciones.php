<?php 

define("TEMPLATES_URL", __DIR__ . "/templates"); // C:\xampp\htdocs\bienesraices\includes/templates
define("FUNCIONES_URL", __DIR__ . "/funciones.php"); // C:\xampp\htdocs\bienesraices\includes/funciones.php
define("CARPETA_IMAGENES", __DIR__ . "/../imagenes/");

function incluirTemplate (string $nombre, bool $inicio = false) {
    include TEMPLATES_URL . "/$nombre.php";
}

// valida si el usuario esta logueado. Si no, redirige al home del sitio
function userLogued() {
    session_start();
    $auth = $_SESSION["login"];
    if(!$auth)
        header("Location: /bienesraices");
}

function debuguear($variable) {
    echo "<pre>";
    //print_r($variable);
    var_dump($variable);
    echo "</pre>";
    exit;
}

// escapar/sanitizar el HTML 
function s ($html) : string {
    $s = htmlspecialchars($html);
    return $s;
}

// funcion para asegurarnos que los registros a borrar en /admin/index.php sean de las tablas propiedades o vendedores 
function validarTipoContenido($tipo) {
    $tipos = ["propiedad", "vendedor"];
    return in_array($tipo, $tipos);
}

function mostrarNotificacion($codigo) {
    $mensaje = "";
    switch($codigo) {
        case 1: 
            $mensaje = "Registro creado Correctamente";
            break;
        case 2: 
            $mensaje = "Registro actualizado Correctamente";
            break;
        case 3: 
            $mensaje = "Registro eliminado Correctamente";
            break;
        default:
            $mensaje = NULL;
            break;
    }
    return $mensaje;
}