<?php
/*
Este archivo contiene todo lo necesario para que esta aplicacion se conecte con la BBDD  y puedan realizarse consultas, inserciones, eliminaciones y actualizacion
**/
// 1. Definir los parametros de conexion
$servidor="localhost"; // nombre del servidor
$usuario="root"; // nombre del usuario
$password=""; // contraseña del usuario
$puerto="3306"; // puerto de conexion a la base de datos
$bbdd="cepa";
// creamos la conexion
function conectar(){
    global $servidor, $usuario, $password, $puerto, $bbdd;
    $conexion=mysqli_connect($servidor.":".$puerto,$usuario,$password);
    // verificar si se conecta la bbdd
    if (mysqli_error($conexion)){
        //echo "Error al conectar la base de datos.";
    }else{
        //echo "Conexion realizada correctamente"; // temporalmente
    }

    if (!mysqli_select_db($conexion,$bbdd)){
        //echo "<br>Error al conectar la base de datos.";
        exit();
    }else{
        //echo "<br>Conexion con la BBDD realizada correctamente";
    }
    return $conexion;
}

//$conexion=conectar();
