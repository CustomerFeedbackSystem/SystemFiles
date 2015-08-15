<?php
/**
 * This is a xwysiwyg boost.php configuration file.
 *
 * $Id: boost.php,v 1.8 2005/11/17 23:23:03 ykuendig Exp $
 */

$version = "0.4.1";
$mod_title = "xwysiwyg";
$mod_pname = "xwysiwyg-Manager";
$mod_directory = "xwysiwyg";
$mod_filename = "index.php";
$priority = 98;
$allow_view = "all";
$user_mod = 0;
$admin_mod = 1;
$deity_mod = 0;
$mod_class_files = array("xwysiwyg.php");
$mod_sessions = array("PHPWS_xwysiwyg");
$active = "on";
$branch_allow = 1;
$install_file = "install.php";
$uninstall_file = "uninstall.php";

?>