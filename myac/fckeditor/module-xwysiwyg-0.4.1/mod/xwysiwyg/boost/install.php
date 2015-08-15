<?php
/**
 * This is a xwysiwyg of an installation file for boost.  Edit it to be
 * used with your module.
 *
 * $Id: install.php,v 1.4 2005/09/25 17:19:58 ykuendig Exp $
 */

/* Make sure the user is a deity before running this script */
if (!$_SESSION["OBJ_user"]->isDeity()){
  header("location:index.php");
  exit();
}

$status = 0;

if (version_compare($GLOBALS['core']->version, "0.9.3-4") < 0) {
  $content .= "This module requires a phpWebSite core version of 0.9.3-4 or greater to install.<br />";
  $content .= "<br />You are currently using phpWebSite core version " . $GLOBALS["core"]->version . ".<br />";
  return;
} 

/* Import installation database and dump result into status variable */
if($status = $GLOBALS["core"]->sqlImport(PHPWS_SOURCE_DIR . "mod/xwysiwyg/boost/install.sql", TRUE)) {
  $content .= "All xwysiwyg tables successfully written.<br /><br />";
  $status = 1;
} else {
  $content .= "There was a problem writing to the database!<br /><br />";
  return;
}

  /* Create images directory */
  if(!is_dir("{$GLOBALS['core']->home_dir}images/javascript/wysiwyg/"))
  	PHPWS_File::makeDir($GLOBALS['core']->home_dir . "images/javascript/wysiwyg/");
  /* Create files/xwysiwyg directory */
  if(!is_dir("{$GLOBALS['core']->home_dir}files/xwysiwyg/"))
  	PHPWS_File::makeDir($GLOBALS['core']->home_dir . "files/xwysiwyg/");
  /* Create files/xwysiwyg/editors directory */
  if(!is_dir("{$GLOBALS['core']->home_dir}files/xwysiwyg/editors/"))
  	PHPWS_File::makeDir($GLOBALS['core']->home_dir . "files/xwysiwyg/editors/");

  if(is_dir("{$GLOBALS['core']->home_dir}images/javascript/wysiwyg/")) {
  	PHPWS_File::fileCopy(PHPWS_SOURCE_DIR . "mod/xwysiwyg/img/xw.gif", $GLOBALS['core']->home_dir . "images/javascript/wysiwyg/", "xw.gif", false, false);
	$status = 1;
  } else { 
  	$content .= "There was a problem copying the icons!<br /><br />";
	return;
  }
  
$status = 1; //instead

?>
