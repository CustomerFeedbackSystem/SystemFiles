<?php

if (!$_SESSION["OBJ_user"]->isDeity()){
	header("location:index.php");
	exit();
}
$status = 1;

if (version_compare($currentVersion, "0.2.0") < 0) {
	$content .= "Xwysiwyg Updates (Version 0.2.0)<br />";
	$content .= "+ FCKeditor updated to v2 RC1<br />";
	$content .= "+ wysiwyg enabled for Firefox 1.0 and later<br /><br />";
}

if (version_compare($currentVersion, "0.3.0") < 0) {
  $content .= "Xwysiwyg Updates (Version 0.3.0)<br />";
	if($GLOBALS['core']->query("ALTER TABLE ".PHPWS_TBL_PREFIX."mod_xwysiwyg_conf ADD `enable_css` SMALLINT(1) DEFAULT '1' NOT NULL AFTER `width`")) {
	$content .= "Added database attribute for enable_css.<br />";
	$content .= "Added custom ha-toolbar related to textSettings.php.<br />";
	$content .= "Width and Height for FCKeditor.<br /><br />";
	} else {
	$content .= "Boost failed to create the attribute 'enable_css' under the table 'mod_xwysiwyg_conf'. <br /><br />";
	$status = 0;
	}
}

if (version_compare($currentVersion, "0.4.0") < 0) {
	$content .= "Xwysiwyg Updates (Version 0.4.0)<br />";
	$update = 0;
	if($status = $GLOBALS["core"]->sqlImport(PHPWS_SOURCE_DIR . "mod/xwysiwyg/boost/install.sql", TRUE))	$update=1;
	if($GLOBALS['core']->query("DROP TABLE mod_xw_areas", TRUE))	$update = $update + 2;
	if($GLOBALS['core']->query("ALTER TABLE mod_xwysiwyg_areas RENAME {$GLOBALS['core']->tbl_prefix}mod_xw_areas",TRUE))	$update = $update + 4;
	if($GLOBALS['core']->sqlTableExists("mod_xwysiwyg_areas_seq",TRUE)) {
		if($GLOBALS['core']->query("ALTER TABLE mod_xwysiwyg_areas_seq RENAME {$GLOBALS['core']->tbl_prefix}mod_xw_areas_seq",TRUE))	$update = $update + 8;
	} else {	$update = $update + 8;
	}
	if($GLOBALS['core']->query("DROP TABLE mod_xwysiwyg_conf", TRUE))	$update = $update + 16;
	/* Create files/xwysiwyg directory */
	if(!is_dir("{$GLOBALS['core']->home_dir}files/xwysiwyg/"))
		PHPWS_File::makeDir($GLOBALS['core']->home_dir . "files/xwysiwyg/");
	/* Create files/xwysiwyg/editors directory */
	if(!is_dir("{$GLOBALS['core']->home_dir}files/xwysiwyg/editors/"))
		PHPWS_File::makeDir($GLOBALS['core']->home_dir . "files/xwysiwyg/editors/");

	if($update>=31){
	$content .= "Altered database-scheme.<br />";
	$content .= "New module-layout.<br />";
	$content .= "More Settings.<br /><br />";
	} else {
	$content .= "Boost failed to update or alter tables. <br /><br />";
	$status = 0;
	}
}

if (version_compare($currentVersion, "0.4.1") < 0) {
	@unlink(PHPWS_HOME_DIR."files/xwysiwyg/editors/list.txt");
	$content .= "Xwysiwyg Updates (Version 0.4.1)<br />";
	$content .= "+ Bugfix in manager<br /><br />";
}

?>