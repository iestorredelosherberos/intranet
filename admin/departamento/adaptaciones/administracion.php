<?php
require('../../../bootstrap.php');

if (file_exists('config.php')) {
	include('config.php');
}

acl_acceso($_SESSION['cargo'], array(1,2,4));

$uri = 'index.php';

include ("../../../menu.php");
include("../menu.php");
include ("menu.php");
?>

<?php

if (strstr($_SESSION['cargo'], "2")) {
	$extra = " and adaptaciones.unidad like '".$_SESSION['mod_tutoria']['unidad']."'";
	$extra_titulo = " de ".$_SESSION['mod_tutoria']['unidad'];
}
elseif(strstr($_SESSION['cargo'], "4")){
	$extra = " and departamento like '".$_SESSION['dpt']."'";
	$extra_titulo = " del departamento de  ".$_SESSION['dpt'];
}
else{
	$extra="";
	$extra_titulo = " de los alumnos del centro";
}
?>
<div class="container">

	<div class="page-header">
		<h2>Adaptaciones curriculares <small>Administrar adaptaciones</small></h2>
	</div>

	<div class="row">

		<div class="col-sm-12">

			<h3>Adaptaciones <?php echo $extra_titulo; ?></h3>

				<?php $result = mysqli_query($db_con, "SELECT id, apellidos, nombre, adaptaciones.fecha, adaptaciones.materia, adaptaciones.unidad, adaptaciones.departamento FROM adaptaciones, alma WHERE alma.claveal = adaptaciones.alumno $extra ORDER BY apellidos, nombre DESC"); ?>
				<?php if (mysqli_num_rows($result)): ?>
				<legend class="text-muted">Documentos registrados</legend>
				<table class="table table-bordered table-hover table-striped" style="width:auto">
					<thead>
						<th>Alumno</th>
						<th>Grupo</th>
						<th>Materia</th>
						<th>Departamento</th>
						<th>Opciones</th>
					</thead>
					<tbody>
						<?php while ($row = mysqli_fetch_array($result)): ?>
						<tr>
							<td>
								<?php echo $row['apellidos'].", ".$row['nombre']; ?><br />
								<small class="text-muted"><?php echo "<em>".$row['fecha']."</em>"; ?></small>
							</td>
							<td>
								<?php echo $row['unidad']; ?>
							</td>
							<td>
								<?php echo $row['materia']; ?>
							</td>
							<td>
								<?php echo $row['departamento']; ?>
							</td>
							<td>
								<a href="<?php echo $uri; ?>?edit_id=<?php echo $row['id']; ?>" data-bs="tooltip" title="Editar documento"><span class="far fa-edit fa-fw fa-lg"></span></a>
								<a href="pdf.php?id=<?php echo $row['id']; ?>&amp;imprimir=1" target="_blank" data-bs="tooltip" title="Imprimir"><span class="fas fa-print fa-fw fa-lg"></span></a>
								<a href="<?php echo $uri; ?>?eliminar_id=<?php echo $row['id']; ?>" data-bs="tooltip" title="Eliminar documento" data-bb="confirm-delete"><span class="far fa-trash-alt fa-fw fa-lg"></span></a>
							</td>
						</tr>
						<?php endwhile; ?>
					</tbody>
				</table>

				<?php else: ?>
				<p class="lead text-muted text-center">No se ha registrado ning??n acta en este departamento.</p>
				<?php endif; ?>			

		</div>

	</div>


</div>

<?php include("../../../pie.php"); ?>

	<script>
	$(document).ready(function() {

		$(document).on("click", "a[data-bb]", function(e) {
		    e.preventDefault();
		    var type = $(this).data("bb");
				var link = $(this).attr("href");

				if (type == 'confirm-print') {
					bootbox.setDefaults({
					  locale: "es",
					  show: true,
					  backdrop: true,
					  closeButton: true,
					  animate: true,
					  title: "Confirmaci??n para imprimir",
					});

					bootbox.confirm("Esta acci??n bloquear?? permanentemente la edici??n de las actas de este departamento. ??Seguro que desea continuar? Antes de Aceptar, es recomendable que realice una copia de seguridad en la Administraci??n de la Intranet.", function(result) {
					    if (result) {
					    	document.location.href = link;
					    }
					});
				}

				if (type == 'confirm-delete2') {
					bootbox.setDefaults({
					  locale: "es",
					  show: true,
					  backdrop: true,
					  closeButton: true,
					  animate: true,
					  title: "Confirmaci??n para eliminar",
					});

					bootbox.confirm("Esta acci??n eliminar?? permanentemente las actas de este departamento. ??Seguro que desea continuar? Antes de Aceptar, es recomendable que realice una copia de seguridad en la Administraci??n de la Intranet.", function(result) {
					    if (result) {
					    	document.location.href = link;
					    }
					});
				}
		});

	});
	</script>

</body>
</html>
