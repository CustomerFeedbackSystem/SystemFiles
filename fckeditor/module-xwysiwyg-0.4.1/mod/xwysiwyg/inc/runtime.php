<?php

/**
 * Runtime for xwysiwyg module
 *
 * $Id: runtime.php,v 1.2 2005/07/17 18:04:54 ykuendig Exp $
 * @author Yves Kuendig <phpws@NOSPAM.firebird.ch>
 * @module xwysiwyg
 * @moduletype mixed module / hack
 * @package phpwebsite = 0.9.3-4 +
 */

if (isset($GLOBALS['xwysiwyg_areas'])) {
	require_once(PHPWS_SOURCE_DIR.'mod/xwysiwyg/class/xwysiwyg.php');//include the xwysiwyg code
	$xwysiwyg = new PHPWS_xwysiwyg;
	$GLOBALS['core']->js_func[] = $xwysiwyg->makeJS();	//build main-js
}

?>