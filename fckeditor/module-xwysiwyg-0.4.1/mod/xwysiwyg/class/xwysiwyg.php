<?php
require_once(PHPWS_SOURCE_DIR . "core/Text.php"); //this we use to parse input and for ezTable
/**
 * Class file for xwysiwyg module 
 *
 * $Id: xwysiwyg.php,v 1.35 2005/11/08 21:56:13 ykuendig Exp $
 * @author Yves Kuendig <phpws@NOSPAM.firebird.ch>
 * @module xwysiwyg
 * @moduletype mixed module / hack
 * @package phpwebsite = 0.9.3-4
 */

class PHPWS_xwysiwyg {

	function pickCSS() {   
		$pick_css = $_SESSION['OBJ_layout']->theme_dir."browsers.txt";
		if (file_exists($pick_css)){
			$allBrowsers = file($pick_css);
			foreach ($allBrowsers as $browser) {
				$temp = explode("::", $browser);
				if (preg_match("/".$temp[0]."/", $_SERVER["HTTP_USER_AGENT"]) && file_exists($_SESSION['OBJ_layout']->theme_dir.trim($temp[1]))) {
					$css = $_SESSION['OBJ_layout']->theme_address . trim($temp[1]);
					$browser_css = 1;
					break;
				}
			}
		}
		if (!isset($browser_css) && file_exists($_SESSION['OBJ_layout']->theme_dir."style.css"))
			$css = $_SESSION['OBJ_layout']->theme_address . "style.css";
		return "http://".PHPWS_SOURCE_HTTP.$css;
	}


function readConfig() {
	if(!isset($GLOBALS['xwysiwyg_settings'])) {
		$sql		= "SELECT editor FROM mod_xw_editor";
		$result		= $GLOBALS['core']->quickFetch($sql,TRUE);
		if(!$result)
			return $_SESSION['translate']->it("No editor is installed or selected")."<br />";
		$editor		= $result['editor'];
		if(!is_file(PHPWS_HOME_DIR."files/xwysiwyg/editors/".$editor.".php"))
			return $_SESSION['translate']->it("Editor file [var1].php not found",$editor)."<br />";
		$sql		= "SELECT * FROM mod_xw_config WHERE editor = '$editor'";
		$settings	= $GLOBALS['core']->quickFetch($sql,TRUE);
		if(!$settings)
			return $_SESSION['translate']->it("There is a problem in your settings")."<br />";
		if($settings['plugins']<>'none')
			$settings['plugins']	= unserialize($settings['plugins']);
		if($settings['themes']<>'none')
			$settings['themes']		= explode(";",$settings['themes']);
		$GLOBALS['xwysiwyg_settings'] = $settings;
	} else	$settings = $GLOBALS['xwysiwyg_settings'];

	return $settings;
}// END FUNC _read_config


function sniffer($browsers,$text=NULL){
	$supported = FALSE;
	if($browsers == 'none')	return $supported;
	require_once(PHPWS_SOURCE_DIR.'mod/xwysiwyg/class/phpSniff.class.php');//include the browser checking code
	//First thing, check browser version. Since I haven't tested, you'll have to add a better list of supported browsers
	$client =& new phpSniff();//$GET_VARS['UA']);
	//Now we want an inclusion list of browsers. These browsers are supposed to work, Galleon or aol versions
	//$browsers = array("FB.6+","NS7+","IE5.5+","MZ1.4+","FX.10+","FX1+");
	$browsers = explode(";",$browsers);
	foreach($browsers as $search) {
	    if($client->browser_is($search)) $supported = TRUE;
	}
	if($text){
		if($supported)	$supported = "<span style=\"color:green;\">".$_SESSION['translate']->it("Your browser IS supported")."</span>";
		else			$supported = "<span style=\"color:red;\">".$_SESSION['translate']->it("Your browser is NOT supported")."</span>";
	}
	return $supported;
}

function isSupported($section_name){
	$settings	= PHPWS_xwysiwyg::readConfig();
	$supported	= PHPWS_xwysiwyg::sniffer($settings['browsers']);
	$allowed	= FALSE;
	$answer		= array();
	if($_SESSION["OBJ_user"]->isDeity()) $allowed = TRUE;
	elseif((!$_SESSION["OBJ_user"]->isUser())AND(!$_SESSION["OBJ_user"]->isDeity())AND($settings['view_anon'])) $allowed = TRUE;
	elseif(($_SESSION["OBJ_user"]->isUser())AND(!$_SESSION["OBJ_user"]->isDeity())AND($settings['view_user'])) $allowed = TRUE;	

	if((!$supported)OR(!$allowed)OR(!is_array($settings))) {
		$answer["xwysiwyg"]		= FALSE;
		$answer["onRequest"]	= FALSE;
		$answer["xwButton"]		= FALSE;
	} elseif($settings['request_mode']) {
		$answer["xwysiwyg"]		= TRUE;
		$answer["onRequest"]	= TRUE;
		$answer["xwButton"]		= TRUE;
	} else {
		$answer["xwysiwyg"]		= PHPWS_xwysiwyg::isBox($section_name);
		$answer["onRequest"]	= FALSE;
		$answer["xwButton"]		= $_SESSION["OBJ_user"]->allow_access("xwysiwyg","settings");
	}
	return $answer;
}

function isBox($area){
	if($area == "xw_testarea")	return TRUE;
	$settings = PHPWS_xwysiwyg::readConfig();
	$query = "SELECT id FROM mod_xw_areas WHERE area = '$area'";
	$result = $GLOBALS['core']->quickFetch($query, TRUE);
	if(($result)AND(is_array($settings))) return TRUE;
	else return FALSE;
}

function setLanguage($settings){
	if($settings['lang_activ'])		$lang = $_SESSION['translate']->current_language;
	else							$lang = "en";
	return $lang;
}


function makeJS() {
	$code = "";
	if (isset($GLOBALS['xwysiwyg'])AND($GLOBALS['xwysiwyg'] >= 1)) {
		$areas = 		$GLOBALS['xwysiwyg_areas'];
		$settings =		$this->readConfig();
		if(!is_array($settings))	return FALSE;
		$lang =			$this->setLanguage($settings);	//get Language
		include_once(PHPWS_HOME_DIR.'files/xwysiwyg/editors/'.$settings['editor'].'.php');
		$code = PHPWS_xw_editor::getCode($settings,$areas,$lang);
		/*
		$loadplugs =	PHPWS_xw_editor::loadPlugins($settings);	//get Plugins from 'editor'.php
		$editors = "";
		$index = 1;
		foreach($areas as $area) {
			$regplugs	=  PHPWS_xw_editor::registerPlugins($settings,$index);	//register plugins from 'editor'.php
			$editors	.= PHPWS_xw_editor::makeEditors($settings,$index,$regplugs,$area,$lang); //make Editors-js from 'editor'.php
			$index++;
		}
		$main = PHPWS_xw_editor::makeMain($settings,$editors,$loadplugs,$lang);
		$main = "//]]> </script>\n".$main."<script type=\"text/javascript\"> //<![CDATA["; //ugly hack to work with $GLOBALS['core']->js_func[]
		*/
	}
	return $code;
}


function addArea() {
	if ($_SESSION["OBJ_user"]->allow_access("xwysiwyg","settings")) {//Administrativ condition
		if(isset($_REQUEST['area'])) {$data['area'] = PHPWS_Text::parseInput($_REQUEST['area']); }
		else {return FALSE;}
		if($GLOBALS['core']->sqlInsert($data, "mod_xw_areas", TRUE, FALSE)) {
			$this->content .= "<span style=\"color:green;font-weight:bold\">".$_SESSION['translate']->it("Saving was successful")."</span><br />"; }
		else { $this->content .= "<span style=\"color:red;\">".$_SESSION['translate']->it("There was a problem saving to the database")."</span><br />"; }
		$this->content .= "<br /><a href=\"./index.php\">".$_SESSION['translate']->it("Home")."</a>";
		//$this->content .= "<br /><a href=\"javascript:history.back()\">".$_SESSION['translate']->it("Back")."</a>";
	} else {
		$this->content .= $_SESSION['translate']->it("Access was denied due to lack of proper permissions.");
	} // End of ADMINISTRATOR condition
}


function delArea($area) {
	if ($_SESSION["OBJ_user"]->allow_access("xwysiwyg","settings")) {//Administrativ condition
		if($GLOBALS['core']->sqlDelete("mod_xw_areas","area",$area)) {
			$this->content .= "<span style=\"color:green;font-weight:bold\">".$_SESSION['translate']->it("Entry was successfully deleted from the database")."</span><br />"; }
		else { $this->content .= "<span style=\"color:red;\">".$_SESSION['translate']->it("There was a problem deleting the entry")."</span><br />"; }
		$this->content .= "<br /><a href=\"".$this->linkRef."&action=admin\">".$_SESSION['translate']->it("Back")."</a>";
	} else {
		$this->content .= $_SESSION['translate']->it("Access was denied due to lack of proper permissions.");
	} // End of ADMINISTRATOR condition
}



function action(){ //here we switch the actions
require_once(PHPWS_SOURCE_DIR."mod/xwysiwyg/class/xw_cms.php");
$this->content = "";
$action	= PHPWS_Text::parseInput($_REQUEST["action"]);
$this->linkRef	= "./index.php?module=xwysiwyg"; //i use this above, to shorten links...

switch($action) {

	case "toggle":
	if(isset($_REQUEST['editor']))
		PHPWS_xw_cms::toggleEditor_man(PHPWS_Text::parseInput($_REQUEST["editor"]));

	case "admin": //this for action=admin
 	$settings = PHPWS_xwysiwyg::readConfig();
	if(!is_array($settings)) {
		$this->content .= $settings;
		PHPWS_xw_cms::manageEditors();
		break;
	}
	PHPWS_xw_cms::showOptions_man($settings);
	break;

	case "testEditor":
	if(isset($_REQUEST['xw_testarea']))
		PHPWS_xw_cms::testEditors(PHPWS_Text::parseInput($_REQUEST['xw_testarea']));
	else {
		$text = "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.";
		PHPWS_xw_cms::testEditors($text);
	}
	break;

	case "manageEditor":
	PHPWS_xw_cms::manageEditors();
	break;

	case "addEditor":
	PHPWS_xw_cms::addEditor();
	PHPWS_xw_cms::manageEditors();
	break;

	case "delEditor":
	PHPWS_xw_cms::delEditor();
	PHPWS_xw_cms::manageEditors();
	break;

	case "cpyEditor":
	PHPWS_xw_cms::cpyEditor();
	PHPWS_xw_cms::manageEditors();
	break;

	case "tarEditor":
	PHPWS_xw_cms::tarEditor();
	PHPWS_xw_cms::manageEditors();
	break;

	case "delFile":
	PHPWS_xw_cms::delFile();
	PHPWS_xw_cms::manageEditors();
	break;

	case "addArea":
	$this->addArea();
	break;

	case "delArea":
	if(isset($_REQUEST['area']))
		$this->delArea(PHPWS_Text::parseInput($_REQUEST["area"]));
	break;

	default:
	$this->content .= $_SESSION['translate']->it("no action given...");
	break;
}

// send content to layout-module and forget the rest...
$GLOBALS["CNT_xwysiwyg"]["title"] = $_SESSION['translate']->it("xwysiwyg Manager");
$GLOBALS["CNT_xwysiwyg"]["content"] = $this->content;
}//END action


}//END class
?>