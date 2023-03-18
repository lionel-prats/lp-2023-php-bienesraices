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