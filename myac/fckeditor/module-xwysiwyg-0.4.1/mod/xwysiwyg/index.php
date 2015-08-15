<?php

/**
 * Main switch for the xwysiwyg-module, all operations 
 * pass though this switch.
 *
 * @version $Id: index.php,v 1.2 2004/10/31 19:52:05 ykuendig Exp $
 */

if (!isset($GLOBALS['core'])){
  header("location:../../");
  exit();
}

/* Check to see if xwysiwyg sessions is set and set it if it's not. */
if(!isset($_SESSION["PHPWS_xwysiwyg"])) {
  $_SESSION["PHPWS_xwysiwyg"] = new PHPWS_xwysiwyg;
}

$GLOBALS["CNT_xwysiwyg"] = array("title"=>NULL,"content"=>NULL);

if(isset($_GET['action']) && isset($_SESSION['PHPWS_xwysiwyg'])) {
  $_SESSION['PHPWS_xwysiwyg']->action();
}

?>