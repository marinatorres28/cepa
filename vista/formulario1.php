
<?php
include_once "header.php";
?>
<div class="container">
<h1 class="centrado">Formulario de Alta de nuevo Alumno</h1>
<h2 class="centrado hforms">1️⃣ Datos personales del alumno </h2>

<form action="../controlador/controlador.php" method="post">
    <input type="hidden" name="origen" value="formulario1">
    <div class="formulario dosColumnas">

        <div class="izquierda">
        <!--NOMBRE-->
            <p>
                <label for="nombre">Nombre</label>
                <input type="text" name="nombre" id="nombre">
            </p>
        <!--PRIMER APELLIDO-->
            <p>
                <label for="pApellido">Primer Apellido</label>
                <input type="text" name="pApellido" id="pApellido">
            </p>
        <!--SEGUNDO APELLIDO-->
            <p>
                <label for="sApellido">Segundo Apellido</label>
                <input type="text" name="sApellido" id="sApellido">
            </p>
        <!--DNI-->
            <p>
                <label for="DNI">DNI</label>
                <input type="text" name="dni" id="dni">
            </p>
        <!--ULTIMO ESTUDIO-->
            <p>
                <label for="uEstudio">Ultimo Estudio Cursado</label>
                <select name="uEstudio" id="uEstudio">
                <!-- ponemos un option vacio para que el select salga vacio-->
                        <option value=""></option>
                    <?php
                    // llamamos al archivo que carga la BBDD
                    include_once ("../modelo/conexion.php");
                    // ejecuta la funcion conectar
                    $link=conectar();
                    // hacemos la consulta que se guarda en una variable ($consulta)
                    $consulta="SELECT * FROM nivelestudios";
                    // se ejecuta la consulta
                    $resultado=mysqli_query($link,$consulta);
                    // recorrer el array y guardar en $fila que es cada registro asociado a cada campo -> ej: $fila["idEstudios"] / $fila["nombreNivel"]
                    while($fila=mysqli_fetch_assoc($resultado)){
                        echo "<option value='$fila[idEstudios]'>$fila[nombreNivel]</option>";
                    }
                    ?>
                </select>
            </p>
            <!--FECHA ULTIMO ESTUDIO-->
            <p>
                <label for="fUltimoEstudio">Fecha Ultimo Estudio</label>
                <input type="date" name="fUltimoEstudio" id="fUltimoEstudio">
            </p>

        </div>

        <div class="derecha">
        <!--TELEFONO-->
            <p>
                <label for="telefono">Telefono</label>
                <input type="text" name="telefono" id="telefono">
            </p>
        <!--DIRECCION-->
            <p>
                <label for="direccion">Dirección</label>
                <input type="text" name="direccion" id="direccion">
            </p>
        <!--CODIGO POSTAL-->
            <p>
                <label for="cp">Código Postal</label>
                <input type="text" name="cp" id="cp" maxlength="5">
            </p>
        <!--PROVINCIAS-->
            <p>
                <label for="provincia">Provincia</label>
                <select name="provincia" id="provincia">
                    <option value=""></option>
                <?php
                $link=conectar();
                $consulta="SELECT * FROM provincia";
                $resultado=mysqli_query($link,$consulta);
                while ($fila=mysqli_fetch_assoc($resultado)){
                    echo "<option value='$fila[idProvincia]'>$fila[nombreProvincia]</option>";
                }
                ?>
                </select>
            </p>
        <!--CIUDAD-->
            <p>
                <label for="ciudad">Ciudad</label>
                <input type="text" name="ciudad" id="ciudad">
            </p>
        <!--FECHA NACIMIENTO-->
            <p>
                <label for="fNacimiento">Fecha Nacimiento</label>
                <input type="date" name="fNacimiento" id="fNacimiento">
            </p>

        </div>
        <!--BOTON-->
        <div class="enviarBoton">
            <input type="submit" name="enviarFormulario1" value="Siguiente →" class="boton">
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
