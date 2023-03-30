<?php 

namespace App;

class Propiedad extends ActiveRecord {
    protected static $tabla = "propiedades";
    protected static $columnasDB = ['id','titulo','precio','imagen','descripcion','habitaciones','wc','estacionamiento','creado','vendedores_id'];
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
        $this->vendedores_id = $args['vendedores_id'] ?? "";
    }
    public function validar() {
        if(!$this->titulo) {
            // $errores es un atributo estatico (un array) heredado de ActiveRecord
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
            // strpos("monitos.jpg", ".") -> retorna la posicion del caracter pasado como 2do. argumento (en este caso 7)
            // substr("monitos.jpg", 8) -> retorna un substring empezando por la posicion pasada como 2do argumento (en este caso "jpg");
            foreach(self::$types_image_allowed as $type){
                // ejemplo de archivo .pdf (no permitido) -> "b34d0f814ebed5139445c05b2ac70ce1.ation/pdf"
                if(substr($this->imagen, strpos($this->imagen, ".") + 1) === $type) {
                // if("ation/pdf" === "jpg")
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
}