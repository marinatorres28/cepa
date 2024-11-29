<?php
session_start();
if (empty($_SESSION['datosCompletos'])) {
    header('Location: formulario1.php?errores=Debe completar ambos formularios');
    exit();
}


// llamamos al archivo que carga la BBDD
include_once("../modelo/conexion.php");
// ejecuta la funcion conectar
$link = conectar();
// hacemos la consulta que se guarda en una variable ($consulta)
$consulta = "SELECT * FROM alumno WHERE idAlumno = ".$_SESSION["idRegistro"];
// se ejecuta la consulta
$resultado = mysqli_query($link, $consulta);
$arrayAlumnos[] = mysqli_fetch_assoc($resultado);

include_once "header.php";
?>


<div class="container">
<h1 class="centrado">Formulario de Alta de nuevo Alumno</h1>
<h2 class="centrado hforms">3️⃣ Alta Confirmada </h2>
<div class="formulario unaColumna">
    <div>
        <p><b>Le informamos que el alta al sistema de Inscripcion del Cento de Educacion Para Adultos se ha realizado con éxito.</b></p>
        <br><br>
        <p>Sus datos son:</p>
        <ul>
           <?=$_SESSION["datosCompletos"]?>
        </ul>
        <p>Nota: Su numero de registro es: <span class="error"><?=$_SESSION["idRegistro"]?></span> </p>
        <br><br>
        <h2>¡Gracias por darte de alta en CEPA Valdemoro! </h2>
        <div class="botones centrado">
            <a href="index.php" class="boton" id="boton">Más Información</a>
        </div>
    </div>
</div>
</div>

</body>
</html>
