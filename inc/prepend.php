<?php
/**
 * @project		Memo
 * @author		Olivier Gaillard
 * @version		1.0 du 21/02/2017
 * @desc	   	Initialisation des ressources
 */

// Start the session
session_start();

ini_set('display_errors', 1);
error_reporting(E_ALL);

define('PATH_APP', realpath(dirname (__FILE__).'/..'));
define('PATH_TPL', PATH_APP.'/tpl/'); // Dossier des templates

// Initialisation du gestionnaire de templates
$smarty = new Smarty;
$smarty->setTemplateDir(PATH_TPL);
//$smarty->config_dir    =  'lang';
$smarty->compile_dir   = 'tpl_cache';
$smarty->compile_check = true;
$smarty->force_compile = true;
$smarty->cache_lifetime = 0;

// Gestion des notifications
if (isset($_SESSION["notification"])) {
    $smarty->assign("alert", array(
        "type" => $_SESSION["notification"]["type"], 
        "msg" => $_SESSION["notification"]["msg"]
        ));
        unset($_SESSION["notification"]);
    }

?>

