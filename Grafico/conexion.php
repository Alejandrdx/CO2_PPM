<?php
    class conexion 
    {
       private $servidor;
       private $usuario;
       private $contrasena;
       private $basedatos;
       public $conexion;
       public function __construct()
       {
           $this->servidor="127.0.0.1";
           $this->usuario="root";
           $this->contrasena="";
           $this->basedatos="ppm";
       }
       function conectar()
       {
           $this->conexion = new mysqli($this->servidor, $this->usuario, $this->contrasena, $this->basedatos);
           $this->conexion->set_charset("utf-8");
       }
       function cerrar()
       {
           $this->conexion->close();
       }
    }















?>