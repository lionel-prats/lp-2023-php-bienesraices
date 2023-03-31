<?php 

namespace App;

class Vendedor extends ActiveRecord {
    protected static $tabla = "vendedores";
    protected static $columnasDB = ['id','nombre','apellido','telefono'];

    protected static $expresion_regular= "|^\d\d\d\d\d\d\d\d\d?\d?$|"; 
    // entre 8 y 10 caracteres numericos
    // ver z.expresiones-regulares.php

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
        /*
        SOLUCION LIO VIDEO 389 - SIN UNSAR EXPRESIONES REGULARES
        if(!$this->telefono) {
            self::$errores[] = "El nro. de teléfono es obligatorio";
        } elseif(!is_numeric($this->telefono)) {
            self::$errores[] = "El nro. de teléfono ingresado no es válido";
        } elseif(strlen($this->telefono) < 8 or strlen($this->telefono) > 10) {
            self::$errores[] = "El nro. de teléfono debe tener entre 8 y 10 dígitos";
        }
        */
        /* 
        SOLUCION PROFESOR VIDEO 389 - NO FUNCIONA CORRECTAMENTE
        if(!$this->telefono) {
            self::$errores[] = "El nro. de teléfono es obligatorio";
        } elseif(!preg_match('/[0-9]{10}/', $this->telefono)) {
            self::$errores[] = "El nro. de teléfono ingresado no es válido";
        } 
        */
        // SOLUCION LIO 389 USANDO EXPRESIONES REGULARES
        if(!$this->telefono) {
            self::$errores[] = "El nro. de teléfono es obligatorio";
        } elseif(!preg_match(self::$expresion_regular, $this->telefono)) {
            self::$errores[] = "El nro. de teléfono ingresado no es válido";
        }
        return self::$errores;
    }
}