<?php 

// VIDEO 361 - "este archivo app sera un archivo que mande a llamar funciones, la conexion a la base de datos y autoload de nuestras clases"

require 'funciones.php';
require 'config/database.php';
require __DIR__ . '/../vendor/autoload.php';

// en $db almacenamos una instancia de la conexion a la base de datos llamando a conectarDB()
// conectarDB() es una funcion declarada en database.php
$db = conectarDB();

use App\ActiveRecord;

// llamamos al metodo estatico setDB() de la clase ActiveRecord, que a su vez almacenara en el atributo estatico $db (de la clase) la instancia de la conexion a la BD
ActiveRecord::setDB($db);