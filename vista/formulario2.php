<?php
session_start();
if (empty($_SESSION['insertarAlumno'])) {
    header('Location: formulario1.php?errores=Debe completar ambos formularios');
    exit();
}
include_once "header.php";
?>

<div class="container">
    <h1 class="centrado">Formulario de Alta de nuevo Alumno</h1>
    <h2 class="centrado hforms">2️⃣ Datos de persona Contacto</h2>
    <form action="../controlador/controlador.php">
        <input type="hidden" name="origen" value="formulario2">
    <div class="formulario unaColumna">
        <div>
        <!--NOMBRE CONTACTO-->
            <p>
                <label for="nombreFamiliar">Nombre persona de contacto</label>
                <input type="text" name="nombreFamiliar" id="nombreFamiliar">
            </p>
        <!--TELEFONO CONTACTO-->
            <p>
                <label for="telefonoFamiliar">Telefono del contacto</label>
                <input type="text" name="telefonoFamiliar" id="telefonoFamiliar">
            </p>
        <!--RELACION-->
            <p>
                <label for="relacion">Relacion</label>
                <select name="relacion" id="relacion">
                    <option value=""></option>
                    <?php
                    include_once ("../modelo/conexion.php");
                    $link=conectar();
                    $consulta="SELECT * FROM parentesco";
                    $resultado=mysqli_query($link,$consulta);
                    while($fila=mysqli_fetch_assoc($resultado)){
                        echo "<option value='$fila[idRelacion]'>$fila[nombreRelacion]</option>";
                    }
                    ?>
                </select>
            </p>
            <p>
                <input type="checkbox" id="casilla">Acepta la Política de <a href="http://aepd.es" target="_blank">Privacidad y Protección de Datos</a>
            </p>
        </div>
    </div>
<!--BOTON ENVIAR-->
        <div class="enviarBoton">
            <input type="submit" name="enviarFormulario2" value="Finalizar" class="botonDesactivado" disabled id="enviarFormulario2">
            <p class="error">
                <?php
                if(!empty($_GET["errores"])){
                    echo $_GET["errores"];
                }
                ?>
            </p>
        </div>


    </div>



    </form>
</div>

</body>
</html>