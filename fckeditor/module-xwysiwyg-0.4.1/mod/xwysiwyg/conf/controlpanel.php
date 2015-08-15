<?php
/**
 * This is a xwysiwyg control panel configuration file.
 *
 * $Id: controlpanel.php,v 1.3 2004/11/19 19:46:18 ykuendig Exp $
 */

$image["name"] = "xwysiwyg.png";
$image["alt"] = "xwysiwyg-Manager";

/* Create a link to your module */
$link[] = array ("label"=>"xwysiwyg-Manager",
		 "module"=>"xwysiwyg",
		 "url"=>"index.php?module=xwysiwyg&amp;action=admin",
		 "image"=>$image,
		 "admin"=>TRUE,
		 "description"=>"Control the use of xwysiwyg in Your phpWebSite",
		 "tab"=>"administration");

?>