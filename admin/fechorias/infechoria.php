<?
require('../../bootstrap.php');

include("../../menu.php");
include("menu.php");
?>
<div class="container">
<div class="page-header">
<h2 style="display: inline;">Problemas de convivencia <small>Registro de
un Problema de Convivencia</small></h2>
<!-- Button trigger modal --> <a href="#"
	class="btn btn-default btn-sm pull-right" data-toggle="modal"
	data-target="#myModal" style="display: inline;"> <span
	class="fa fa-question fa-lg"></span> </a> <!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog"
	aria-labelledby="myModalLabel" aria-hidden="true">
<div class="modal-dialog modal-lg">
<div class="modal-content">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal"><span
	aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
<h4 class="modal-title" id="myModalLabel">Instrucciones de uso.</h4>
</div>
<div class="modal-body">
<p>
El registro de un Problema de Convivencia comienza con la selecci�n de la <em><b>fecha</b></em> en que sucedi�. <br>Contin�a con la selecci�n de la <em><b>Unidad o Grupo de alumnos</b></em> dentro del cual se encuentra el autor del problema. El Grupo no es un campo obligatorio, simplemente facilita la b�squeda al reducir la lista de alumnos. <br>
El campo <em><b>Alumno/a</b></em> presenta al principio la lista de todos los alumnos del Centro ordenada alfab�ticamente. Si elegimos un Grupo aparecer�n  los alumnos de ese Grupo. Tanto en la lista total como en la lista de un Grupo podemos selecccionar uno o varios alumnos. Como se se�ala en el texto de ayuda del formulario, se pueden seleccionar m�ltiples alumnos mediante el uso de la tecla CTRL + click sobre los distintos elementos; si queremos seleccionar a todo el Grupo, hacemos click sbre el primero de la lista y, manteniendo presionada la tecla May�sculas (SHIFT), seleccionamos el �tlimo de la lista.<br><br>
El segundo bloque de campos del formulario comienza con la elecci�n de la <em><b>Gravedad</b></em> del Problema que vamos a registrar. La Gevedad puede ser: Leve, Grave y Muy Grave. Cada categor�a va asociada a un conjunto de <em><b>Conductas Negativas</b></em> que aparecen en el ROF (Reglamento de Organizaci�n y Funcionamiento) del Centro y que puede ser editado por parte de los Administradores de la Intranet (Administracci�n de la Intranet --> A principio de Curso --> Modificar ROF). Al cargar una de las categor�as, el desplegable muestra las Conductas Negativas propias de esa categor�a. Seleccionamos una Conducta y aparecer�n al mismo tiempo la <em><b>Medida Adoptada</b></em> administrativamente (si procede seg�n el ROF) y las <em><b>Medidas Complementarias</b></em> que deben tomarse (seg�n el ROF). Si el alumno ha sido <em><b>expulsado del Aula</b></em>, debe marcarse la opci�n correspondiente. <br><br>
En el campo <em><b>Observaciones</b></em> describimos el acontecimiento que hemos tipificado. La descripci�n debe ser precisa y completa, de tal modo que tanto el Tutor como el Jefe de Estudios como los propios Padres del alumno puedan hacerse una idea ajustada de lo sucedido.<br>
<br>El <em><b>Profesor</b></em> que informa del Problema coincide con el Profesor que ha abierto la sesi�n de la Intranet, excepto en el caso de los miembros del Equipo Directivo que pueden elegir entre la lista de todos los Profesores. 
<br>El bot�n <em><b>Registrar</b></em> env�a los datos del formulario y completa el proceso de registro.<br><br>
Hay que tener en cuenta algunos detalles que suceden al registrar un Problema de Convivencia.

<li class="text-info">El Tutor recibe un mensaje en la P�gina principal cuando se registra un Problema Grave o Muy Grave de alguno de sus alumnos. El mensaje ofrece datos sobre el problema e indica el procedimiento a seguir. El Jefe de Estudios tambi�n ve los Problemas que se van registrando en el momento de producirse.</li>
<li class="text-info">Si el problema es Leve, el sistema registra un nuevo problema Grave por reiteraci�n: cada 5 problemas Leves se crea un Problema Grave de forma autom�tica. El Tutor y Jefe de Estudios reciben una notificaci�n.</li>
<li class="text-info">Los Problemas de Convivencia caducan seg�n el tiempo especificado en el ROF. Los valores por defecto de la aplicaci�n son los siguinetes: 30 d�as para los Leves y Graves; 60 d�as para los Muy Graves.</li>
<li class="text-info">Se puede editar el Problema registrado en los dos d�as siguientes a la fecha en la que sucedi�. Posteriormente, la edici�n queda bloqueada.</li>

</p>
</div>
<div class="modal-footer">
<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
</div>
</div>
</div>
</div>
</div>
<br />

<div class="row"><?
//variables();
if ( $_POST['unidad']) {$unidad = $_POST['unidad'];}
if (isset($_GET['id'])) { $id = $_GET['id'];}elseif (isset($_POST['id'])) { $id = $_POST['id'];}
if (isset($_GET['nombre'])) { $nombre = $_GET['nombre'];}elseif (isset($_POST['nombre'])) { $nombre = $_POST['nombre'];}
if (isset($_GET['claveal'])) { $claveal = $_GET['claveal'];}elseif (isset($_POST['claveal'])) { $claveal = $_POST['claveal'];}

$notas = $_POST['notas']; $grave = $_POST['grave']; $asunto = $_POST['asunto'];$fecha = $_POST['fecha'];$informa = $_POST['informa']; $medidaescr = $_POST['medidaescr']; $medida = $_POST['medida']; $expulsionaula = $_POST['expulsionaula'];

if (isset($_POST['submit1']))
{
	include("fechoria25.php");
}

// Actualizar datos
if ($_POST['submit2']) {
	mysqli_query($db_con, "update Fechoria set claveal='$nombre', asunto = '$asunto', notas = '$notas', grave = '$grave', medida = '$medida', expulsionaula = '$expulsionaula', informa='$informa' where id = '$id'");
	echo '<br /><div align="center"><div class="alert alert-success alert-block fade in">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            Los datos se han actualizado correctamente.
          </div></div><br />';
}

// Si se envian datos desde el campo de b�squeda de alumnos, se separa claveal para procesarlo.
if ($_GET['seleccionado']=="1") {
	$claveal=$_GET['nombre'];
	//$nombrel=$claveal;
	$ng_al0=mysqli_query($db_con, "select unidad, apellidos, nombre from FALUMNOS where claveal = '$claveal'");
	$ng_al=mysqli_fetch_array($ng_al0);
	$unidad=$ng_al[0];
	$nombre_al=$ng_al[1].", ".$ng_al[2];
}
if ($_GET['id'] or $_POST['id']) {
	$result = mysqli_query($db_con, "select FALUMNOS.apellidos, FALUMNOS.nombre, FALUMNOS.unidad, FALUMNOS.nc, Fechoria.fecha, Fechoria.notas, Fechoria.asunto, Fechoria.informa, Fechoria.grave, Fechoria.medida, listafechorias.medidas2, Fechoria.expulsion, Fechoria.tutoria, Fechoria.inicio, Fechoria.fin, aula_conv, inicio_aula, fin_aula, Fechoria.horas, expulsionaula from Fechoria, FALUMNOS, listafechorias where Fechoria.claveal = FALUMNOS.claveal and listafechorias.fechoria = Fechoria.asunto  and Fechoria.id = '$id' order by Fechoria.fecha DESC");

	if ($row = mysqli_fetch_array($result))
	{

		$nombre_al = "$row[0], $row[1]";
		$unidad = $row[2];
		$fecha = $row[4];
		$notas = $row[5];

		$informa = $row[7];
		if ($asunto or $grave) {}else{
			$grave = $row[8];
			$asunto = $row[6];
		}
		$expulsionaula = $row[19];
		$medida = $row[9];
		$medidas2 = $row[10];
		$expulsion = $row[11];
		$tutoria = $row[12];
		$inicio = $row[13];
		$fin = $row[14];
		$convivencia = $row[15];
		$inicio_aula = $row[16];
		$fin_aula = $row[17];
		$horas = $row[18];
	}
}
?>

<form method="post" action="infechoria.php" name="Cursos">
<fieldset>

<div class="col-sm-6">

<div class="well">
<div class="form-group" id="datetimepicker1"><label for="fecha">Fecha</label>
<div class="input-group"><input name="fecha" type="text"
	class="form-control" data-date-format="DD-MM-YYYY" id="fecha"
	value="<?if($fecha == "") { echo date('d-m-Y'); } else { echo $fecha;}?>"
	required> <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
</div>
</div>
<div class="form-group"><label for="unidad">Unidad</label> <select
	class="form-control" id="unidad" name="unidad" onchange="submit()">

	<option><? echo $unidad;?></option>

	<? unidad($db_con);?>
</select></div>
<label for="nombre">Alumno/a</label> <?
if ((isset($nombre)) and isset($unidad) and !(is_array($nombre)))
{

	//echo "<OPTION value='$claveal' selected>$nombre_al</OPTION>";

	echo '<select class="form-control" id="nombre" name="nombre" required>';
	$alumnos = mysqli_query($db_con, "SELECT distinct APELLIDOS, NOMBRE, claveal FROM FALUMNOS WHERE unidad = '$unidad' order by APELLIDOS asc");

	while($falumno = mysqli_fetch_array($alumnos))
	{
			
		if ($nombre==$falumno[2]){
			$sel = " selected ";
		}
		else{
			$sel="";
		}

		echo "<OPTION value='$falumno[2]'  $sel>$falumno[0], $falumno[1]</OPTION>";

	}
	?>
	</select>
	<?
}
else{

	?> <select class="form-control" id="nombre" name="nombre[]"
	multiple='multiple' style='height: 450px;' required>
	<?
	if ($unidad) {
		$uni = " WHERE unidad like '$unidad%'";
	}
	else{
		$uni="";
	}
	$alumnos = mysqli_query($db_con, " SELECT distinct APELLIDOS, NOMBRE, claveal FROM FALUMNOS $uni order by APELLIDOS asc");
	while($falumno = mysqli_fetch_array($alumnos))
	{
		$sel="";
		if (is_array($nombre)) {
			foreach($nombre as $n_alumno){
					
				if ($n_alumno==$falumno[2]){
					$sel = " selected ";
				}
			}
		}

		echo "<OPTION value='$falumno[2]'  $sel>$falumno[0], $falumno[1]</OPTION>";

	}
?>
</select>
<p class="help-block">Puedes seleccionar varios alumnos manteniendo
presionada la tecla <em>Ctrl</em> mientras haces click con el rat�n
sobre los mismos. <br>Si has seleccionado un Grupo y quieres registrar un Problema a todo los alumnos del Grupo, marca con el rat�n el primer alumno y, mientras mantines pulsada la tecla <em>May�sculas (Shift)</em>, marca el �ltimo de los alumnos para seleccionarlos a todos.</p>
<?
}
?>


</div>
</div>
<div class="col-sm-6">
<div class="well">



<div class="form-group"><label for="grave"> Gravedad</label> <select
	class="form-control" id="grave" name="grave" onchange="submit()" required>
	<option><? echo $grave;?></option>
	<?
	tipo($db_con);
	?>
</select></div>

<div class="form-group"><label for="asunto">Conducta negativa</label> <select
	class="form-control" id="asunto" name="asunto" onchange="submit()" required>
	<option><? 

	$sql0 = mysqli_query($db_con, "select tipo from listafechorias where fechoria = '$asunto'");
	$sql1 = mysqli_fetch_array($sql0);
	if($sql1[0] !== $grave)
	{
		echo "<OPTION></OPTION>";
	}
	else
	{ echo $asunto;}  ?></option>
	<?
	fechoria($db_con, $grave);
	?>
</select></div>

<div class="form-group"><label class="medida">Medida Adoptada</label> <?

$tipo = "select distinct medidas from listafechorias where fechoria = '$asunto'";
$tipo1 = mysqli_query($db_con, $tipo);
while($tipo2 = mysqli_fetch_array($tipo1))
{
	if($tipo2[0] == "Amonestaci�n escrita")
	{
		$medidaescr = $tipo2[0];
		echo '<input type="hidden" id="medida" name="medida" value="'.$tipo2[0].'">';
	}
	else
	{
		echo '<input  type="hidden"id="medida" name="medida" value="'.$tipo2[0].'">';
	}
}

?> <input type="text" value="<? echo $medidaescr;?>" readonly
	class="form-control" /></div>

<div class="form-group"><label for="medidas">Medidas complementarias que
deben tomarse</label> <textarea class="form-control" id="medidas"
	name="medidas" rows="7" disabled><? if($medidas){ echo $medidad; }else{  medida2($db_con, $asunto);} ?></textarea>
</div>

<?
if($grave == 'grave' or $grave == 'muy grave'){
	?>
<div class="checkbox"><label> <input type="checkbox" id="expulsionaula"
	name="expulsionaula" value="1"
	<?  if ($expulsionaula == "1") { echo " checked ";}?>> El alumno ha
sido <u>expulsado</u> del aula </label></div>

	<?
}
?>

<div class="form-group"><label for="notas">Descripci�n:</label> <textarea
	class="form-control" id="notas" name="notas" rows="7"
	placeholder="Describe aqu� los detalles del incidente..." required><? echo $notas; ?></textarea>
</div>

<?
if ($id) {
	?>

<div class="form-group"><label for="informa">Profesor</label> <select
	class="form-control" id="informa" name="informa">
	<?
	if ($id) {
		echo "<OPTION>".$informa."</OPTION>";
	}

	$profe = mysqli_query($db_con, " SELECT distinct prof FROM horw order by prof asc");
	while($filaprofe = mysqli_fetch_array($profe)) {
		echo"<OPTION>$filaprofe[0]</OPTION>";
	}
	?>
</select></div>
	<?
}
else{
	if(stristr($_SESSION['cargo'],'1') == TRUE OR stristr($_SESSION['cargo'],'b') == TRUE){
		?>
<div class="form-group"><label class="informa">Profesor</label> <select
	class="form-control" id="informa" name="informa">
	<?
	if ($id) {
		echo '<OPTION value="'.$informa.'">'.nomprofesor($informa).'</OPTION>';
	}
	else{
		echo '<OPTION value="'.$_SESSION['profi'].'">'.nomprofesor($_SESSION['profi']).'</OPTION>';
	}
	$profe = mysqli_query($db_con, " SELECT distinct prof FROM horw order by prof asc");
	while($filaprofe = mysqli_fetch_array($profe)) {
		echo '<OPTION value="'.$filaprofe[0].'">'.nomprofesor($filaprofe[0]).'</OPTION>';
	}
	?>
</select></div>
	<?
	}
	else{
		?> <input type="hidden" id="informa" name="informa"
	value="<? echo $_SESSION['profi'];?>"> <?
	}

}

?></div>


<hr />
<?
if ($id) {
	echo '<input type="hidden" name="id" value="'.$id.'">';
	echo '<input type="hidden" name="claveal" value="'.$claveal.'">';
	echo '<input name = "submit2" type="submit" value="Actualizar datos" class="btn btn-warning btn-lg">';
}
else{
	echo '<input name=submit1 type=submit value="Registrar" class="btn btn-primary btn-lg">';
}
?></div>
</fieldset>
</form>
</div>
</div>
<?
include("../../pie.php");
?>
<script>  
	$(function ()  
	{ 
		$('#datetimepicker1').datetimepicker({
			language: 'es',
			pickTime: false
		})
	});  
	</script>
</BODY>
</HTML>
