<?php 

namespace App;

class Vendedor extends ActiveRecord {
    protected static $tabla = "vendedores";
    protected static $columnasDB = ['id','nombre','apellido','telefono'];
    public $id;
    public $nombre;
    public $apellido;
    public $telefono;
    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? '';
        $this->nombre = $args['nombre'] ?? '';
        $this->apellido = $args['apellido'] ?? '';
        $this->telefono = $args['telefono'] ?? '';
    }
    public function validar() {
        if(!$this->nombre) {
            self::$errores[] = "El nombre es obligatorio"; 
        }
        if(!$this->apellido) {
            self::$errores[] = "El apellido es obligatorio";
        }
        if(!$this->telefono) {
            self::$errores[] = "El nro. de teléfono es obligatorio";
        } elseif(!is_numeric($this->telefono)) {
            self::$errores[] = "El nro. de teléfono ingresado no es válido";
        } elseif(strlen($this->telefono) < 8 or strlen($this->telefono) > 10) {
            self::$errores[] = "El nro. de teléfono debe tener entre 8 y 10 dígitos";
        }
        return self::$errores;
    }
}