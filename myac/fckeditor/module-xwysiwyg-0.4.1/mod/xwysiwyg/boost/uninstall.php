<?php
/**
 * This is the xwysiwyg-version of an uninstall file for boost. Edit it to
 * be used with your module.
 *
 * $Id: uninstall.php,v 1.3 2005/11/06 18:48:47 ykuendig Exp $
 */

/* Make sure the user is a deity before running this script */
if(!$_SESSION["OBJ_user"]->isDeity()){
  header("location:index.php");
  exit();
}

/* Import the uninstall database file and dump the result into the status variable */

if($status = $GLOBALS["core"]->sqlImport(PHPWS_SOURCE_DIR . "mod/xwysiwyg/boost/uninstall.sql", 1, 1)) {
	$content .= "All xwysiwyg tables successfully removed!<br />";
	if(is_dir("{$GLOBALS['core']->home_dir}files/xwysiwyg/"))
		if(PHPWS_File::rmdir(PHPWS_HOME_DIR . "files/xwysiwyg"))
			$content .= "All Files in /files/xwysiwyg removed!<br /><br />";
		else {
			$content .= "Directory /files/xwysiwyg could NOT be deleted!<br />";
			$content .= "Please delete it manually.<br /><br />";
		}
} else {
	$content .= "There was a problem accessing the database.<br /><br />";
}

$status = 1; // instead
?>