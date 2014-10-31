<?
session_start();
include("../../../config.php");
include_once('../../../config/version.php');
// COMPROBAMOS LA SESION
if ($_SESSION['autentificado'] != 1) {
	$_SESSION = array();
	session_destroy();
	header('Location:'.'http://'.$dominio.'/intranet/salir.php');
	exit();
}

if($_SESSION['cambiar_clave']) {
	header('Location:'.'http://'.$dominio.'/intranet/clave.php');
}

registraPagina($_SERVER['REQUEST_URI'],$db_host,$db_user,$db_pass,$db);


if (isset($_POST['curso'])) {$curso = $_POST['curso'];} elseif (isset($_GET['curso'])) { $curso = $_GET['curso'];}


include("../../../menu.php");
if (isset($_GET['menu_cuaderno'])) {
	include("../../../cuaderno/menu.php");
	echo "<br>";
}
include("menu.php");
?>

<div class="container"><!-- TITULO DE LA PAGINA -->
<div class="page-header">
<h2>Actividades de Grupo <small>Calendario de las unidades</small></h2>
</div>

<?php if(isset($_POST['curso']) or isset($_GET['curso'])): ?> <legend
	class="text-center">Calendario del Grupo <br><span class="text-info"><?php echo $curso; ?></span></legend>

<br>

<div class="row">
<div class="col-sm-8"><?php $result = mysqli_query($db_con, "SELECT id, fecha, grupo, materia, tipo, titulo FROM diario WHERE grupo like '%".$curso."%' and date(fecha)>'$inicio_curso' and profesor = '".$_SESSION['profi']."'"); ?>
<table class="table table-striped table-bordered">
	<thead>
		<th colspan="4"><span class="text-info">Mis Actividades</span></th>
	</thead>
	<thead>
		<th>Fecha</th>
		<th>Grupo</th>
		<th>Materia</th>
		<th>T�tulo</th>
	</thead>
	<tbody>
	<?php while ($row = mysqli_fetch_array($result)): ?>
		<tr>
			<td nowrap><?php echo $row[1]; ?></td>
			<td><?php echo $row[2]; ?></td>
			<td><?php echo $row[3]; ?></td>
			<td><?php echo $row[5]; ?></td>
		</tr>
		<?php endwhile; ?>
	</tbody>
</table>

		<?php $result = mysqli_query($db_con, "SELECT id, fecha, grupo, materia, tipo, titulo FROM diario WHERE grupo like '%".$curso."%' and date(fecha)>'$inicio_curso' and profesor not like '".$_SESSION['profi']."'"); ?>
<table class="table table-striped table-bordered">
	<thead>
		<th colspan="4"><span class="text-info">Actividades de otros
		profesores</span></th>
	</thead>
	<thead>
		<th>Fecha</th>
		<th>Grupo</th>
		<th>Materia</th>
		<th>T�tulo</th>
	</thead>
	<tbody>
	<?php while ($row = mysqli_fetch_array($result)): ?>
		<tr>
			<td nowrap><?php echo $row[1]; ?></td>
			<td><?php echo $row[2]; ?></td>
			<td><?php echo $row[3]; ?></td>
			<td><?php echo $row[5]; ?></td>
		</tr>
		<?php endwhile; ?>
	</tbody>
</table>
</div>
<div class="col-sm-4"><?php include("calendario_grupos.php"); ?></div>
</div>



		<?php endif; ?></div>
<!-- /.container -->

		<?php include("../../../pie.php"); ?>

</body>
</html>
