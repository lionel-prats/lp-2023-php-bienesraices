<?php 

namespace App;

class Propiedad {
    
    // en este atributo estatico almacenamos una instancia de la conexion a la base de datos base de datos
    // este atributo lo seteamos con el metodo estatico setDB()
    // este seteo lo hacemos en el archivo app.php, y asi estara disponible la instancia de mysqli en todo archivo donde importemos app.php
    protected static $db;
    
    protected static $columnasDB = ['id','titulo','precio','imagen','descripcion','habitaciones','wc','estacionamiento','creado','vendedores_id'];
    
    // formato de imagenes permitido
    protected static $types_image_allowed = ['jpg', 'jpeg','png', 'webp'];
    // validador del formato de imagen
    protected static $type_allowed = false;

    // errores
    protected static $errores = [];

    public $id;
    public $titulo;
    public $precio;
    public $imagen;
    public $descripcion;
    public $habitaciones;
    public $wc;
    public $estacionamiento;
    public $creado;
    public $vendedores_id;

    // definir la conexion a la BD
    public static function setDB($database) {
        self::$db =$database;
    }

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? '';
        $this->titulo = $args['titulo'] ?? '';
        $this->precio = $args['precio'] ?? '';
        $this->imagen = $args['imagen'] ?? '';
        $this->descripcion = $args['descripcion'] ?? '';
        $this->habitaciones = $args['habitaciones'] ?? '';
        $this->wc = $args['wc'] ?? '';
        $this->estacionamiento = $args['estacionamiento'] ?? '';
        $this->creado = date("Y/m/d") ?? '';
        $this->vendedores_id = $args['vendedores_id'] ?? 1;
    }

    public function guardar() {

        $atributos = $this->sanitizarAtributos();

        $keysAtributos = array_keys($atributos);
        $keysToString = join(', ', $keysAtributos);
        $valuesAtributos = array_values($atributos);
        $valuesToString = join("', '", $valuesAtributos);
        
        $query = "INSERT INTO propiedades ($keysToString) VALUES ('$valuesToString')";

        $resultado = self::$db->query($query);

        return $resultado;
    }

    public function atributos(){
        $atributos = [];
        foreach(self::$columnasDB as $columna) {
            if($columna === "id")
                continue; 
            $atributos[$columna] = $this->$columna;
        }
        return $atributos;
    }
    
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

    public static function getErrores() {
        return self::$errores;
    }

    public function validar() {

        if(!$this->titulo) {
            self::$errores[] = "Debes añadir un título";
        }
        if(!$this->precio) {
            self::$errores[] = "El precio es obligatorio";
        }
        if(strlen($this->descripcion) < 50) {
            self::$errores[] = "La descripción es obligatoria y debe ser de al menos 50 caracteres";
        }
        if($this->habitaciones === "") {
            self::$errores[] = "El numero de habitaciones es obligatorio";
        }
        if($this->wc === "") {
            self::$errores[] = "El numero de baños es obligatorio";
        }
        if($this->estacionamiento === "") {
            self::$errores[] = "El numero de estacionamientos es obligatorio";
        }
        if(!$this->vendedores_id) {
            self::$errores[] = "Elige un vendedor";
        }  
        if(!$this->imagen) 
            self::$errores[] = "La imagen es obligatoria";
        else {
            foreach(self::$types_image_allowed as $type){
                if(substr($this->imagen, strpos($this->imagen, ".") + 1) === $type) {
                    self::$type_allowed = true;
                    break;
                }
            }
            if(!self::$type_allowed) {
                self::$errores[] = "El formato de archivo no es válido"; 
            }
        }   
        return self::$errores;
    }

    // trae todas las propiedades de la tabla
    public static function all(){
        $query = "SELECT * FROM propiedades";
        $resultado = self::consultarSQL($query);
        return $resultado;
    }

    // busca una propiedad por id
    public static function find($id){
        $query = "SELECT * FROM propiedades WHERE id = $id";
        $resultado = self::consultarSQL($query);
        return array_shift($resultado);
        // array_shift() elimina el primer elemento de un array, y a su vez lo retorna (puedo guardarlo en una variable, o retornarlo como hace esta funcion)
    }

    public static function consultarSQL($query) {
        
        // consultar la bd
        $resultado = self::$db->query($query);
        
        // iterar los resultados
        // aca armamos un array de objetos. Habra un objeto asociado a cada registro de la tabla propiedades
        $array = [];
        while($registro = $resultado->fetch_assoc() ):
            $array[] = self::crearObjeto($registro);
        endwhile;

        // liberar la memoria (VIDEO 370)
        $resultado->free();

        // retornar los resultados (array de objetos)
        return $array;
    }
    protected static function crearObjeto($registro) {
        // con "new self" creo una instancia de la clase Propiedad (esta misma clase)
        $objeto = new self;
        foreach($registro as $key => $value) {
            if(property_exists($objeto, $key))
                $objeto->$key = $value;
        }
        return $objeto;
    }

    // sincroniza el objeto en memoria con los cambios realizados por el usuario (actualizar propiedad)
    // metodo utilizado en actualizar.php: va a reescribir el objeto en memoria (inmmueble a actualizar, return de ::find()), con los datos que vengan del formulario de edicion de un inmueble
    public function sincronizar( $args = [] ) {
        // con $this referenciamos (hacemos referencia, invocamos) a la instancia creada en actualizar.php (inmueble a editar)
        foreach($args as $key => $value) {
            if(property_exists($this, $key)/*  and !empty($value) */)
                $this->$key = $value;
        }
        //debuguear($this);
        return;
    }
}