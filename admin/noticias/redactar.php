<?php
require('../../bootstrap.php');

if (file_exists('config.php')) {
	include('config.php');
}

$profesor = $_SESSION ['profi'];

if (isset($_GET['id'])) $id = $_GET['id'];

// ENVIO DEL FORMULARIO
if (isset($_POST['enviar'])) {

	// VARIABLES DEL FORMULARIO
	$titulo = $_POST['titulo'];
	$contenido = preg_replace('/&quot;/i', '', $_POST['contenido']);
	$contenido = preg_replace('/&nbsp;&nbsp;/i', '', $contenido);
	$contenido = preg_replace('/<h([1-6]) style=".*?"/i', '<h$1', $contenido);
	$contenido = preg_replace('/font-family:.*?;/i', '', $contenido);
	$contenido = preg_replace('/font-size: medium;/i', '', $contenido);
	$contenido = preg_replace('/margin:.*?;/i', '', $contenido);
	$contenido = preg_replace('/padding:.*?;/i', '', $contenido);
	$contenido = preg_replace('/class="MsoNormal"/i', '', $contenido);
	$contenido = preg_replace('/<o:p>.*?<\/o:p>/i', '', $contenido);
	$contenido = preg_replace('/<p style="\s*">/i', '<p>', $contenido);
	$contenido = preg_replace('/<img style="(.*?)float: left;(.*?)"/i', '<img style="$1float: left; margin: 0 15px 15px 0; $2"', $contenido);
	$contenido = preg_replace('/<img style="(.*?)float: right;(.*?)"/i', '<img style="$1float: right; margin: 0 0 15px 15px; $2"', $contenido);
	$contenido = preg_replace('/(<div class="embed-responsive embed-responsive-16by9">)?<iframe (.*?)><\/iframe>(<\/div>)?/i', '<div class="embed-responsive embed-responsive-16by9"><iframe $2></iframe></div>', $contenido);
	$contenido = addslashes($contenido);
	$caracteres_contenido = strlen(trim(strip_tags($contenido)));
	$autor = $_POST['autor'];
	$fechapub = $_POST['fechapub'];
	$categoria = $_POST['categoria'];
	$ndias = $_POST['ndias'];
	$intranet = $_POST['intranet'];
	$principal = $_POST['principal'];
	$permanente = $_POST['permanente'];
	$pagina = $intranet.$principal.$permanente;
	if (empty($titulo) || empty($contenido) || empty($fechapub)) {
		$msg_error = "Todos los campos del formulario son obligatorios.";
	}
	elseif ($caracteres_contenido < 150) {
		$msg_error = "Debe introducir al menos un p??rrafo con 150 caracteres.";
	}
	else {

			if ($ndias == 0) $fechafin = '';
			else $fechafin = date("Y-m-d", strtotime("$fechapub +$ndias days"));

			if(empty($intranet) && empty($principal)) {
				$msg_error = "Debe indicar d??nde desea publicar la noticia.";
			}
			else {
				// COMPROBAMOS SI INSERTAMOS O ACTUALIZAMOS
				if(isset($id)) {
					// ACTUALIZAMOS LA NOTICIA
					$result = mysqli_query($db_con, "UPDATE noticias SET titulo='$titulo', contenido='$contenido', autor='$autor', fechapub='$fechapub', fechafin='$fechafin', categoria='$categoria', pagina='$pagina' WHERE id = $id LIMIT 1");
					if (!$result) $msg_error = "No se ha podido actualizar la noticia. Error: ".mysqli_error($db_con);
					else $msg_success = "La noticia ha sido actualizada correctamente.";
				}
				else {
					// INSERTAMOS LA NOTICIA
					$result = mysqli_query($db_con, "INSERT INTO noticias (titulo, contenido, autor, fechapub, fechafin, categoria, pagina) VALUES ('$titulo','$contenido','$autor','$fechapub','$fechafin','$categoria','$pagina')");
					if (!$result) $msg_error = "No se ha podido publicar la noticia. Error: ".mysqli_error($db_con);
					else $msg_success = "La noticia ha sido publicada correctamente.";
				}
			}
		}
	}


// OBTENEMOS LOS DATOS SI SE OBTIENE EL ID DE LA NOTICIA
if (isset($id) && (int) $id) {

	$result = mysqli_query($db_con, "SELECT titulo, contenido, autor, fechapub, DATEDIFF(fechafin, fechapub) AS ndias, categoria, pagina FROM noticias WHERE id = $id LIMIT 1");
	if (!mysqli_num_rows($result)) {
		$msg_error = "La noticia que intenta editar no existe.";
		unset($id);
	}
	else {
		$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
		if (stristr($_SESSION['cargo'],'1') == TRUE || $row['autor'] == $_SESSION['profi']) {
			$titulo = ((strstr($row['titulo'], ' [Actualizado]') == true) || (stristr($row['titulo'], ' (Actualizado)') == true)) ? $row['titulo'] : $row['titulo'].' [Actualizado]';
			$contenido = $row['contenido'];
			$autor = $row['autor'];
			$fechapub = $row['fechapub'];
			$categoria = $row['categoria'];
			$ndias = $row['ndias'];
			$pagina = $row['pagina'];

			// OBTENEMOS LOS LUGARES DONDE SE HA PUBLICADO LA NOTICIA
			if (strstr($pagina, '1') == true) $intranet = 1;
			if (strstr($pagina, '2') == true) $principal = 2;
			if (strstr($pagina, '3') == true) $permanente = 3;
		}
		else {
			$msg_error = "No eres el autor o no tienes privilegios administrativos para editar esta noticia.";
			unset($id);
		}

		mysqli_free_result($result);
	}

}


include ("../../menu.php");
include ("menu.php");
?>

	<div class="container">

		<!-- TITULO DE LA PAGINA -->
		<div class="page-header">
			<h2>Noticias <small>Redactar nueva noticia</small></h2>
		</div>

		<!-- MENSAJES -->
		<?php if (isset($msg_error)): ?>
		<div class="alert alert-danger">
			<?php echo $msg_error; ?>
		</div>
		<?php endif; ?>

		<?php if (isset($msg_success)): ?>
		<div class="alert alert-success">
			<?php echo $msg_success; ?>
		</div>
		<?php endif; ?>


		<!-- SCAFFOLDING -->
		<div class="row">


			<form method="post" action="">

				<!-- COLUMNA IZQUIERDA -->
				<div class="col-sm-9">

					<div class="well">

						<fieldset>
							<legend>Redactar nueva noticia</legend>

							<input type="hidden" name="token" value="<?php echo $token; ?>">

								<div class="form-group">
									<label for="titulo">T??tulo</label>
									<input type="text" class="form-control" id="titulo" name="titulo" placeholder="T??tulo de la noticia" value="<?php echo (isset($titulo) && $titulo) ? htmlspecialchars($titulo) : ''; ?>" maxlength="120" autofocus>
								</div>

								<div class="form-group">
									<label for="contenido" class="sr-only">Contenido</label>
									<textarea class="form-control" id="contenido" name="contenido" rows="10" maxlength="3000"><?php echo (isset($contenido) && $contenido) ? stripslashes($contenido) : ''; ?></textarea>
								</div>

								<button type="submit" class="btn btn-primary" name="enviar"><?php echo (isset($id) && $id) ? 'Actualizar' : 'Publicar'; ?></button>
								<button type="reset" class="btn btn-default">Cancelar</button>

						</fieldset>

					</div>

				</div><!-- /.col-sm-9 -->


				<!-- COLUMNA DERECHA -->
				<div class="col-sm-3">

					<div class="well">

						<fieldset>
							<legend>Opciones</legend>


							<div class="form-group hidden">
								<label for="autor">Autor</label>
								<input type="text" class="form-control" id="autor" name="autor" value="<?php echo (isset($autor) && $autor) ? $autor : $_SESSION['profi']; ?>" readonly>
							</div>

							<div class="form-group" id="datetimepicker1">
								<label for="fechapub">Fecha de publicaci??n</label>
								<div class="input-group">
									<input type="text" class="form-control" id="fechapub" name="fechapub" value="<?php echo (isset($fechapub) && $fechapub) ? $fechapub : date('Y-m-d H:i:s'); ?>" data-date-format="YYYY-MM-DD HH:mm:ss">
									<span class="input-group-addon"><span class="far fa-calendar"></span></span>
								</div>
							</div>

							<div class="form-group">
								<label for="clase">Categor??a</label>
								<select class="form-control" id="categoria" name="categoria">
								<?php foreach ($cat as $item_categoria): ?>
								<?php if(stristr($_SESSION['cargo'],'1') == FALSE and ($item_categoria=="Direcci??n del Centro" or $item_categoria=="Jefatura de Estudios" or $item_categoria=="Secretar??a")) {} else {?>
									<option value="<?php echo $item_categoria; ?>" <?php echo (isset($item_categoria) && $item_categoria == $categoria) ? 'selected' : ''; ?>><?php echo $item_categoria; ?></option>
									<?php } ?>
								<?php endforeach; ?>
								</select>
							</div>

							<?php if (stristr($_SESSION['cargo'],'1') == TRUE): ?>

							<div class="form-group">
								<label for="ndias">Noticia destacada (en d??as)</label>
								<div class="row">
									<div class="col-sm-5">
										<input type="number" class="form-control" id="ndias" name="ndias" value="<?php echo (isset($ndias) && $ndias) ? $ndias : '0'; ?>" min="0" max="31" maxlength="2">
									</div>
								</div>
							</div>



							<label>Publicar en...</label>

							<div class="form-group">
								<div class="checkbox">
									<label>
										<input type="checkbox" name="intranet" value="1" <?php echo (isset($intranet) && $intranet) ? 'checked' : ''; ?>> Intranet
									</label>
								</div>
							</div>

							<?php if (isset($config['noticias']['web_centro']) && $config['noticias']['web_centro']): ?>
							<div class="form-group">
								<div class="checkbox">
									<label>
										<input type="checkbox" name="principal" value="2" <?php echo (isset($principal) && $principal) ? 'checked' : ''; ?>> P??gina externa
									</label>
								</div>
							</div>
							<?php endif; ?>

							<div class="form-group">
								<div class="checkbox">
									<label>
										<input type="checkbox" name="permanente" value="3" <?php echo (isset($permanente) && $permanente) ? 'checked' : ''; ?>> C??mo se hace...
									</label>
								</div>
							</div>

							<?php else: ?>

							<input type="hidden" name="intranet" value="1">

							<?php endif; ?>

						</fieldset>

					</div>

				</div><!-- /.col-sm-3 -->

			</form>


		</div><!-- /.row -->

	</div><!-- /.container -->

<?php include("../../pie.php"); ?>

	<script>
	$(document).ready(function() {

		// EDITOR DE TEXTO
		tinymce.init({
			selector: 'textarea#contenido',
			language: 'es',
			height: 500,
			plugins: 'print preview fullpage paste importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media template table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern noneditable help charmap quickbars',
			imagetools_cors_hosts: ['picsum.photos'],
			menubar: 'file edit view insert format tools table help',
			toolbar: 'undo redo | bold italic underline strikethrough | fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor removeformat | pagebreak | charmap | fullscreen  preview save print | insertfile image media template link anchor | ltr rtl',
			toolbar_sticky: true,
			autosave_ask_before_unload: true,
			autosave_interval: "30s",
			autosave_prefix: "{path}{query}-{id}-",
			autosave_restore_when_empty: false,
			autosave_retention: "2m",
			image_advtab: true,

			/* enable title field in the Image dialog*/
			image_title: true,
			/* enable automatic uploads of images represented by blob or data URIs*/
			automatic_uploads: true,
			/*
			URL of our upload handler (for more details check: https://www.tiny.cloud/docs/configure/file-image-upload/#images_upload_url)
			images_upload_url: 'postAcceptor.php',
			here we add custom filepicker only to Image dialog
			*/
			file_picker_types: 'image',
			/* and here's our custom image picker*/
			file_picker_callback: function (cb, value, meta) {
			var input = document.createElement('input');
			input.setAttribute('type', 'file');
			input.setAttribute('accept', 'image/*');

			/*
			  Note: In modern browsers input[type="file"] is functional without
			  even adding it to the DOM, but that might not be the case in some older
			  or quirky browsers like IE, so you might want to add it to the DOM
			  just in case, and visually hide it. And do not forget do remove it
			  once you do not need it anymore.
			*/

			input.onchange = function () {
			  var file = this.files[0];

			  var reader = new FileReader();
			  reader.onload = function () {
			    /*
			      Note: Now we need to register the blob in TinyMCEs image blob
			      registry. In the next release this part hopefully won't be
			      necessary, as we are looking to handle it internally.
			    */
			    var id = 'blobid' + (new Date()).getTime();
			    var blobCache =  tinymce.activeEditor.editorUpload.blobCache;
			    var base64 = reader.result.split(',')[1];
			    var blobInfo = blobCache.create(id, file, base64);
			    blobCache.add(blobInfo);

			    /* call the callback and populate the Title field with the file name */
			    cb(blobInfo.blobUri(), { title: file.name });
			  };
			  reader.readAsDataURL(file);
			};

			input.click();
			}
		});


	});

	// DATETIMEPICKER
	$(function () {
	    $('#datetimepicker1').datetimepicker({
	    	language: 'es',
	    	useSeconds: true,
	    });
	});
	</script>

</body>
</html>
