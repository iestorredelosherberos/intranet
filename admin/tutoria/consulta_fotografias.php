<?php
session_start();
include("../../config.php");

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

// COMPROBACION DE ACCESO AL MODULO
if(!stristr($_SESSION['cargo'],'1') == TRUE || stristr($_SESSION['cargo'],'2') == TRUE || stristr($_SESSION['cargo'],'8') == TRUE) {
	
	if (isset($_SESSION['mod_tutoria'])) unset($_SESSION['mod_tutoria']);
	die ("<h1>FORBIDDEN</h1>");
	
}
else {
	
	// COMPROBAMOS SI ES EL TUTOR, SINO ES DEL EQ. DIRECTIVO U ORIENTADOR
	if (stristr($_SESSION['cargo'],'2') == TRUE) {
		
		$_SESSION['mod_tutoria']['tutor']  = $_SESSION['tut'];
		$_SESSION['mod_tutoria']['unidad'] = $_SESSION['s_unidad'];
		
	}
	else {
	
		if(isset($_POST['tutor'])) {
			$exp_tutor = explode('==>', $_POST['tutor']);
			$_SESSION['mod_tutoria']['tutor'] = trim($exp_tutor[0]);
			$_SESSION['mod_tutoria']['unidad'] = trim($exp_tutor[1]);
		}
		else{
			if (!isset($_SESSION['mod_tutoria'])) {
				header('Location:'.'tutores.php');
			}
		}
		
	}
}


// ENVIO DEL FORMULARIO
if (isset($_POST['enviar'])) {
	
	$claveal = $_POST['alumno'];
	$fotografia = $_FILES['foto']['tmp_name'];
	
	if (empty($claveal) || empty($fotografia)) {
		$msg_error = "Todos los campos del formulario son obligatorios.";
	}
	else {
		
		require('../../lib/class.Images.php');
		$image = new Image($fotografia);
		$image->resize(240,320,'crop');
		$image->save($claveal, '../../xml/fotos/', 'jpg');
		
		$msg_success = "La fotograf�a se ha actualizado.";
	}
	
}


registraPagina($_SERVER['REQUEST_URI'],$db_host,$db_user,$db_pass,$db);

include("../../menu.php");
include("menu.php");
?>
	
	<style type="text/css">
	.img-thumbnail {
		margin-bottom: 5px;
	}
	
	@media print {
		.container {
			width: 100%;
		}
		
		body {
			font-size: 10px;
		}
		
		h2 {
			font-size: 22px;
		}
		
		h4 {
			font-size: 14px;
		}
	}
	</style>
	

	<div class="container">
		
		<!-- TITULO DE LA PAGINA -->
		<div class="page-header">
			<h2>Tutor�a de <?php echo $_SESSION['mod_tutoria']['unidad']; ?> <small>Fotograf�as de los alumnos</small></h2>
			<h4 class="text-info">Tutor/a: <?php echo mb_convert_case($_SESSION['mod_tutoria']['tutor'], MB_CASE_TITLE, "iso-8859-1"); ?></h4>
		</div>
		
		<!-- MENSAJES -->
		<?php if(isset($msg_error)): ?>
		<div class="alert alert-danger" role="alert">
			<?php echo $msg_error; ?>
		</div>
		<?php endif; ?>
		
		<?php if(isset($msg_success)): ?>
		<div class="alert alert-success" role="alert">
			<?php echo $msg_success; ?>
		</div>
		<?php endif; ?>
		
		
		<!-- SCAFFOLDING -->
		<div class="row hidden-print">
		
			<!-- COLUMNA IZQUIERDA -->
			<div class="col-sm-6">
			
				<div class="well">
				
					<form method="post" action="" enctype="multipart/form-data">
						
						<fieldset>
							<legend>Actualizar la fotograf�a del alumno/a</legend>
							
							<div class="form-group">
								<label for="alumno">Alumno/a</label>
								<?php $result = mysql_query("SELECT claveal, apellidos, nombre FROM alma WHERE unidad='".$_SESSION['mod_tutoria']['unidad']."' ORDER BY apellidos ASC, nombre ASC"); ?>
								<?php if(mysql_num_rows($result)): ?>
							  <select class="form-control" id="alumno" name="alumno">
							  	<option value=""></option>
							  	<?php while($row = mysql_fetch_array($result)): ?>
							  	<option value="<?php echo $row['claveal']; ?>"><?php echo $row['apellidos'].', '.$row['nombre']; ?></option>
							  	<?php endwhile; ?>
							  </select>
							  <?php else: ?>
							   <select class="form-control" id="alumno" name="alumno" disabled></select>
							  <?php endif; ?>
							  <?php mysql_free_result($result); ?>
							</div>
							
							<div class="form-group">
								<label for="foto">Fotograf�a (formato JPEG)</label>
								<input type="file" id="foto" name="foto" accept="image/jpeg">
							</div>
							
							<button type="submit" class="btn btn-primary" name="enviar">Actualizar</button>
						
						</fieldset>
					
					</form>
				
				</div><!-- /.well -->
			
			</div><!-- /.col-sm-6 -->
			
			
			<!-- COLUMNA DERECHA -->
			<div class="col-sm-6">
			
				<h3>Informaci�n sobre las fotograf�as</h3>
				
				<p>La foto debe cumplir la norma especificada:<p>
				 	
				<ul>
					<li>Tener el fondo de un �nico color, liso y claro.</li>
					<li>La foto ha de ser reciente y tener menos de 6 meses de antig�edad.</li>
					<li>Foto tipo carnet, la imagen no puede estar inclinada, tiene que mostrar la cara claramente de frente.</li>
					<li>Fotograf�a de cerca que incluya la cabeza y parte superior de los hombros, la cara ocupar�a un 70-80% de la fotograf�a.</li>
					<li>Fotograf�a perfectamente enfocada y clara.</li>
				</ul>
			
			</div><!-- /.col-sm-6 -->
		
		</div>
		
				
		<div class="row">
			
			
			<!-- COLUMNA CENTRAL -->
			<div class="col-sm-12">

				<?php $result = mysql_query("SELECT claveal, apellidos, nombre FROM alma WHERE unidad='".$_SESSION['mod_tutoria']['unidad']."'"); ?>
				<?php $columnas = 6; ?>
				<?php $fila = 0; ?>
				<?php $num = 0; ?>
				<?php while ($row = mysql_fetch_array($result)): ?>
				
				<?php if ($num == 0 || (($num % $columnas) == 0)): ?>
				<div class="row">
				<?php $fila++; ?>
				<?php endif; ?>
					
					<?php $foto = '../../xml/fotos/'.$row['claveal'].'.jpg'; ?>
					<?php if (file_exists($foto)): ?>
					<div class="col-sm-2 text-center">
						<img class="img-thumbnail" src="<?php echo $foto; ?>?t=<?php echo time(); ?>" alt="<?php echo $row['apellidos'].', '.$row['nombre']; ?>" width="165">
						<?php echo $row['apellidos'].', '.$row['nombre']; ?>
					</div>
					<?php else: ?>
					<div class="col-sm-2 text-center">
						<div class="img-thumbnail" style="display: block;">
							<br><br><br>
							<span class="fa fa-user fa-5x"></span>
							<br><br><br><br>
						</div>
						<?php echo $row['apellidos'].', '.$row['nombre']; ?>
					</div>
					<?php endif; ?>
				
				<?php $num++; ?>
				
				<?php if (($num % $columnas) == 0): ?>
				</div>
				
				<hr>
				<?php endif; ?>				
				
				<?php endwhile; ?>
				
				<?php echo (mysql_num_rows($result) < ($columnas * $fila)) ? '</div><hr>' : ''; ?>
				
				
				<div class="hidden-print">
					<a href="#" class="btn btn-primary" onclick="javascript:print();">Imprimir</a>
				</div>

			</div><!-- /.col-sm-12 -->
			
		
		</div><!-- /.row -->
		
	</div><!-- /.container -->
  
<?php include("../../pie.php"); ?>

</body>
</html>
