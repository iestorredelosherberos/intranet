<?
require('../../bootstrap.php');


$profe = $_SESSION['profi'];
if(!(stristr($_SESSION['cargo'],'1') == TRUE))
{
header('Location:'.'http://'.$config['dominio'].'/intranet/salir.php');
exit;	
}


include("../../menu.php");
?>
<div class="container">
<br />
<div class="page-header">
  <h2>Administración <small> importación de la Jornada Escolar del centro</small></h2>
  </div>

<br />
<div class="row">
<div class="col-sm-6 col-sm-offset-3">
<FORM ENCTYPE="multipart/form-data" ACTION="jornada.php" METHOD="post">
<div class="form-group">
<p class="help-block"><span style="color:#9d261d">(*) </span>Descarga el archivo <strong>DetTipJornda.txt</strong> desde Séneca --> Centro --> Calendario y Jornada --> Jornada escolar. Pulsa sobre la <strong>Jornada Escolar</strong> (en azul) y luego sobre <strong>Detalle</strong> para acceder a la página de la Jornada Escolar. Selecciona <strong>Texto Plano</strong> como tipo de archivo para la exportación.</p>
  <br />
  <div class="well well-large" style="width:600px; margin:auto;" align="left">
  <div class="controls">
  <label class="control-label" for="file">Selecciona el archivo con los datos de la Jornada Escolar
  </label>
  <input type="file" name="archivo" class="input input-file " id="file">
  <hr>
  <div align="center">
    <INPUT type="submit" name="enviar" value="Aceptar" class="btn btn-primary">
  </div>
  </div>
  </div>
  </div>
  </div>
  </div>
</FORM>
</div>
    <?php 
include("../../pie.php");
?>
</body>
</html>
