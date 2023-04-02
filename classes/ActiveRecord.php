<?php 

namespace App;

class ActiveRecord {
    
    // en este atributo estatico almacenamos una instancia de la conexion a la base de datos base de datos
    // este atributo lo seteamos con el metodo estatico setDB()
    // este seteo lo hacemos en el archivo app.php, y asi estara disponible la instancia de mysqli en todo archivo donde importemos app.php
    protected static $db;

    // protected static $columnasDB = []; // comente esta linea en el VIDEO 385 ya que $columnasDB existe en Propiedad y en Vendedor - por ahora funciona

    // protected static $tabla = ""; // comente esta linea en el VIDEO 385 ya que $tabla existe en Propiedad y en Vendedor - por ahora funciona

    // formato de imagenes permitido
    protected static $types_image_allowed = ['jpg', 'jpeg','png', 'webp'];
    // validador del formato de imagen
    protected static $type_allowed = false;

    // errores
    protected static $errores = [];

    // definir la conexion a la BD
    public static function setDB($database) {
        self::$db =$database;
    }
    
    public function guardar() {
        if($this->id) {
            return $this->actualizar();   
        } else {
            return $this->crear();   
        }
    }

    public function crear() {
        // array sincronizado con el objeto en memoria (inmueble o vendedor a crear) ya sanitizado y listo para guardar en BD
        $atributos = $this->sanitizarAtributos();
        $keysAtributos = array_keys($atributos);
        $keysToString = join(', ', $keysAtributos);
        $valuesAtributos = array_values($atributos);
        $valuesToString = join("', '", $valuesAtributos);

        $query = "INSERT INTO " . static::$tabla . " ($keysToString) VALUES ('" . $valuesToString. "')";
        $resultado = self::$db->query($query);

        if($resultado){
            header("Location: /bienesraices/admin?result=1");
        }
    }

    public function actualizar() {
        // array sincronizado con el objeto en memoria (inmueble o vendedor a editar) ya sanitizado y listo para guardar en BD
        $atributos = $this->sanitizarAtributos();

        $valores = [];
        foreach($atributos as $key => $value) {
            $valores[] = "$key = '$value'";
        }

        $query = "UPDATE " . static::$tabla . " SET ";
        $query .= join(', ', $valores);
        $query .= " WHERE id = '"; 
        $query .= self::$db->escape_string($this->id);
        $query .= "' LIMIT 1";

        $resultado = self::$db->query($query);

        if($resultado){
            // redireccionar al usuario luego de creado el registro 
            // esta funcion sirve para enviar datos en el encabezado de una peticion HTTP
            header("Location: /bienesraices/admin?result=2");
        }
    }

    public function eliminar() {
        $query = "DELETE FROM " . static::$tabla . " WHERE id = " . self::$db->escape_string($this->id) . " LIMIT 1";
        //debuguear($query);
        $resultado = self::$db->query($query);

        
        if($resultado and static::$tabla === "propiedades"){
            $this->deleteImage($this->imagen);
        } 
        header("Location: /bienesraices/admin?result=3");
    }


    // este metodo mapea el objeto en memoria (datos de un inmueble) y retorna un array asociativo con los datos de ese inmueble
    public function atributos(){
        $atributos = [];
        foreach(static::$columnasDB as $columna) {
            if($columna === "id")
            continue; 
            $atributos[$columna] = $this->$columna;
        }
        return $atributos;
    }
    
    // este metodo retorna un array asociativo con los datos del objeto en memoria, ya sanitizados y listos para mandarlos a la BD
    public function sanitizarAtributos() {
        $atributos = $this->atributos();
        $sanitizado = [];
        foreach($atributos as $key => $value) {
            $sanitizado[$key] = self::$db->escape_string($value);
        }
        return $sanitizado;
    }

    public function setImagen($imagen){
        if($imagen)
            $this->imagen = $imagen;
    }

    public function deleteImage($oldImage) {
        // si existe id en la instancia, se que estoy editando un inmueble (cuando creo no existe id)
        if($this->id) {
            $existeArchivo = file_exists(CARPETA_IMAGENES . $oldImage);
            if($existeArchivo)
                unlink(CARPETA_IMAGENES . $oldImage); 
        }
    }

    public static function getErrores() {
        //return self::$errores;
        return static::$errores; // cambia el modificador en el VIDEO 384
    }

    public function validar() {
        debuguear("pilu"); 
        // agregue esto en el VIDEO 384 - por ahora compruebo que se ejecuta el metodo validar() de Propiedad y de Vendedor, y que este no se esta ejecutando
        // hata el VIDEO 388 lo sigo compronando
        
        static::$errores = []; // agrega esto en el VIDEO 384 
        
        return static::$errores;
        
        // hasta el VIDEO 384 verifique que puedo eliminar este metodo (ya que existe en Propiedad y en Vendedor) y todo funciona correctamente - por las dudas por ahora lo dejo
        // hata el VIDEO 388 lo sigo compronando
    }

    // trae todas las propiedades de la tabla
    public static function all(){
        $query = "SELECT * FROM " . static::$tabla;
        $resultado = self::consultarSQL($query);
        return $resultado;
    }
    
    public static function get($cantidad){
        $query = "SELECT * FROM " . static::$tabla . " LIMIT " . $cantidad;
        //debuguear($query);
        $resultado = self::consultarSQL($query);
        return $resultado;
    }

    // busca una propiedad por id
    public static function find($id){
        $query = "SELECT * FROM " . static::$tabla . " WHERE id = $id";
        $resultado = self::consultarSQL($query);
        return array_shift($resultado);
        // array_shift() elimina el primer elemento de un array, y a su vez lo retorna (puedo guardarlo en una variable, o retornarlo como hace esta funcion)
        // est metodo retorna un objeto con los datos de un registro
    }

    public static function consultarSQL($query) {
   
        // consultar la bd
        $resultado = self::$db->query($query);
        // iterar los resultados
        // aca armamos un array de objetos. Habra un objeto asociado a cada registro de la tabla propiedades
        $array = [];
        while($registro = $resultado->fetch_assoc() ):
            $array[] = self/* static */::crearObjeto($registro);
            // en el VIDEO 385 dice que conviene cambiar a static - por ahora funciona asi que mantengo el self 
        endwhile;

        // liberar la memoria (VIDEO 370)
        $resultado->free();

        // retornar los resultados (array de objetos)
        return $array;
    }
    protected static function crearObjeto($registro) {
        // con "new self" creo una instancia de esta misma clase (es decir, con los atributos que le especificamos en esta clase)
        // con new static voy a crear una instancia de la la clase desde donde se invoque a este metodo crearObjeto() (VIDEO 382)
        $objeto = new /* self */ static;
        foreach($registro as $key => $value) {
            if(property_exists($objeto, $key))
                $objeto->$key = $value;
        }
        return $objeto;
    }

    // sincroniza el objeto en memoria con los cambios realizados por el usuario (actualizacion de inmueble o vendedor)
    // metodo utilizado en actualizar.php: va a reescribir el objeto en memoria (inmmueble o vendedor a actualizar, return de ::find()), con los datos que vengan del formulario de edicion de un inmueble
    public function sincronizar( $args = [] ) {
        // con $this referenciamos (hacemos referencia, invocamos) a la instancia creada en actualizar.php (inmueble a editar)
        foreach($args as $key => $value) {
            if(property_exists($this, $key)/*  and !empty($value) */)
                $this->$key = $value;
        }
        //debuguear($this);
        return "mocos";
    }
}