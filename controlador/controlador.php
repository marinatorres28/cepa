<?php
session_start();
/**
 * Array de errores que se utiliza tanto en el formulario1 como en el formulario2
 * Se inicializa cada vez que se llama el archivo controlador
 */
$errores=array();
/**
 * El condicional lee desde donde se recibe el formulario y valida los campos importantes u obligatorios
 * Si todo es correcto, envía al siguiente formulario
 * En caso de error, envia cuales campos tienen error para su correcion
 */

if ($_REQUEST["origen"]==="formulario1") {

    validarCpostal($_REQUEST["cp"]);
    validarDNI($_REQUEST["dni"]);
    validarEdad($_REQUEST["fNacimiento"]);
    validarTexto($_REQUEST["nombre"],"nombre");
    validarTexto($_REQUEST["pApellido"],"apellido");
    validarTelefono($_REQUEST["telefono"]);
    validarVacio($_REQUEST["provincia"],"La Provincia");
    validarVacio($_REQUEST["fUltimoEstudio"],"La fecha ultimo estudio");
    validarVacio($_REQUEST["direccion"],"La direccion");
    validarVacio($_REQUEST["uEstudio"],"El ultimo estudio");

    if (count($errores)>0){
        for ($i=0;$i<count($errores);$i++){
            $todosLosErrores.=$errores[$i];
        }
        header("Location:../vista/formulario1.php?errores=$todosLosErrores");
    }else{
        $_SESSION["insertarAlumno"]="insert into alumno (nombre, primerApellido, segundoApellido, dni, telefono, direccion, cp, ciudad, fechaUltimoEst, idProvincia, idEstudios, fechaNacimiento) values('".$_REQUEST["nombre"]."',
        '".$_REQUEST["pApellido"]."',
        '".$_REQUEST["sApellido"]."',
        '".$_REQUEST["dni"]."',
        ".$_REQUEST["telefono"].",
        '".$_REQUEST["direccion"]."',
        '".$_REQUEST["cp"]."',
        '".$_REQUEST["ciudad"]."',
        '".$_REQUEST["fUltimoEstudio"]."',
        ".$_REQUEST["provincia"].",
        ".$_REQUEST["uEstudio"].",
        '".$_REQUEST["fNacimiento"]."'
        )";
        //echo $_SESSION["insertarAlumno"];
        header("Location:../vista/formulario2.php");
    }

}
/**
 * El condicional lee desde donde se recibe el formulario y valida los campos importantes u obligatorios
 * Si todo es correcto, envía al siguiente formulario
 * En caso de error, envia cuales campos tienen error para su correcion
 */
if ($_REQUEST["origen"]==="formulario2") {
    validarTexto($_REQUEST["nombreFamiliar"],"Persona Contacto");
    validarTelefono($_REQUEST["telefonoFamiliar"]);
    validarVacio($_REQUEST["relacion"],"La relacion");
    if (count($errores)>0){
        for ($i=0;$i<count($errores);$i++){
            $todosLosErrores.=$errores[$i];
        }
        header("Location:../vista/formulario2.php?errores=$todosLosErrores");
    }else{
        require_once ("../modelo/conexion.php"); // llamamos a la conexion
        $link=conectar();
        $insertarFamiliar="insert into familiar(nombreFamiliar, telefono, idRelacion) values ('".$_REQUEST["nombreFamiliar"]."',
         ".$_REQUEST["telefonoFamiliar"].",
         ".$_REQUEST["relacion"]."
        )";

        $resultado=mysqli_query($link,$insertarFamiliar); // ejecuta la consulta
        $idFamiliar=mysqli_insert_id($link); // recupero el id del ultimo link que he insertado
        // echo $idFamiliar;
        $insertarAlumno=$_SESSION["insertarAlumno"];
        $resultado=mysqli_query($link,$insertarAlumno);
        $idAlumno=mysqli_insert_id($link);
        $_SESSION["idRegistro"]=$idAlumno;
        $insertarFamiliarAlumno="update alumno set idFamiliar=".$idFamiliar." where idAlumno=".$idAlumno;
        $resultado=mysqli_query($link,$insertarFamiliarAlumno);
        // Una vez insertados los datos del alumno y del familiar, puedo recuperar su nombre,apellido,telefono
        $consultaDatosAlumno="select nombre, primerApellido, dni, telefono, direccion, cp, ciudad, fechaUltimoEst, fechaNacimiento from alumno where idAlumno=".$idAlumno;
        $resultado=mysqli_query($link,$consultaDatosAlumno);
        $arrayAlumno[]=mysqli_fetch_array($resultado);

        foreach ($arrayAlumno as $alumno){
            $_SESSION["datosCompletos"] = "<li>".$alumno["nombre"]." ".$alumno["primerApellido"]."</li>
                                           <li>".$alumno["dni"]."</li>
                                           <li>".$alumno["telefono"]."</li>
                                           <li>".$alumno["direccion"]." ".$alumno["cp"]." ".$alumno["ciudad"]."</li>
                                           <li>".$alumno["fechaUltimoEst"]."</li>
                                           <li>".$alumno["fechaNacimiento"]."</li>";


        }
        mysqli_close($link); // cierra la BBDD
        header("Location:../vista/confirmacion.php");
    }
}
/**
 * @param $valor
 * @param $variable
 * @return void
 * Funcion que recibe un valor y su campo a la que hace referencia
 * En caso de estar vacio, guarda en la variable global el mensaje de error haciendo referencia a su campo
 * Ej: La ciudad no puede ser vacio
 */
function validarVacio($valor,$variable){
    global $errores;
    if (empty($valor)){
        $errores[]="<p class='error'>$variable no puede estar vacio</p>";
    }
}

/**
 * @param $texto
 * @param $variable
 * @return void
 * Funcion que valida cualquier texto, indicará un error en la variable global en caso de estar vacio o tener algun numero
 */
function validarTexto($texto,$variable){
    global $errores;
    if (empty($texto) || !is_string($texto) || preg_match("/[0-9]/",$texto)){ // no es un string y tiene numeros
        $errores[]="<p class='error'>El $variable no puede estar vacio ni contener numeros</p>";
    }
}

/**
 * @param $telefono
 * @return void
 * Funcion que valida un numero de telefono de España con 9 digitos y que comience con 6/7/8/9
 * En caso de error guarda en la variable global el mensaje
 */
function validarTelefono($telefono)
{
    global $errores;
    if (empty($telefono) || !is_numeric($telefono) || !preg_match("/^[6789]\d{8}$/", $telefono)) {
        $errores[] = "<p class='error'>El formato del telefono es incorrecto, debe comenzar por 6/7/8/9 y tener 9 digitos</p>";
    }
}
    /**
     * @param $fecha
     * @return void
     * Funcion que recibe la fecha de nacimiento y calcula con respecto a la fecha actual la edad del alumno
     * En caso de no tener 18 años o mas, se guarda un error en la variable global que no puede ser menor de edad
     */
    function validarEdad($fecha)
    {
        global $errores;
        $fechaN = new DateTime($fecha); // la variable que se lee de la fecha de nacimiento creamos como tipo dateTime
        // obtener la fecha actual
        $fechaActual = new DateTime(); // leemos la fecha actual

        // calcular la diferencia entre la fecha actual y la fecha de nacimiento
        $diferencia = $fechaActual->diff($fechaN); // metodo que calcula la diferencia entre dos fechas

        // obtener la edad en años
        $edad = $diferencia->y;
        if ($edad < 18) {
            $errores[] = "<p class='error'>Tienes $edad anios, la edad no puede ser menor a 18.</p>";
        }
    }

    /**
     * @param $dni
     * @return void
     * Funcion que valida el dni con el formato y la letra correcta
     * En caso de error, se guarda en la variable global si el error es de formato o de la letra
     */
    function validarDNI($dni)
    {
        global $errores;
        if (preg_match("/^[0-9]{8}[A-Za-z]$/", $dni)) { // expresion regular que valida el formato del dni
            // separar la letra del dni
            $numero = substr($dni, 0, 8);
            $letra = strtoupper(substr($dni, -1));

            // letras de control
            $letrasValidas = "TRWAGMYFPDXBNJZSQVHLCKE";

            // calcular la letra correspondiente al numero
            $indice = $numero % 23;
            $letraCorrecta = $letrasValidas[$indice];

            // verificar si la letra coincide
            if ($letraCorrecta != $letra) {
                $errores[] = "<p class='error'>DNI invalido (letra incorrecta)</p>";
            }

        } else {
            $errores[] = "<p class='error'>El DNI tiene formato incorrecto</p>";
        }
    }

    /**
     * @param $cp
     * @return void
     * Funcion que recibe un codigo postal y valida que sean 5 digitos, solo numeros
     * En caso de error, guarda en la variable global el mensaje
     */
    function validarCpostal($cp)
    {
        global $errores;
        if (empty($cp) || !preg_match("/^[0-9]{5}$/", $cp)) {
            $errores[] = "<p class='error'>El codigo postal no puede ser vacio y debe contener 5 numeros</p>";
        }
    }



