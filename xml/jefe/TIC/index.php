<?
require('../../../bootstrap.php');


if(!(stristr($_SESSION['cargo'],'1') == TRUE))
{
header('Location:'.'http://'.$config['dominio'].'/intranet/salir.php');
exit;	
}
?>
