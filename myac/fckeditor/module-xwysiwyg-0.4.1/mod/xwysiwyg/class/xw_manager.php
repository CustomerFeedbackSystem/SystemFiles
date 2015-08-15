<?php
require_once(PHPWS_SOURCE_DIR . "core/Text.php"); //this we use to parse input and for ezTable
require_once("../../../../../../myac/fckeditor/module-xwysiwyg-0.4.1/mod/xwysiwyg/class/Archive/Tar.php");
/**
 * Class file for xwysiwyg module 
 * $Id: xw_cms.php,v 1.16 2005/11/18 00:02:32 ykuendig Exp $ *
 * @author Yves Kuendig <phpws@NOSPAM.firebird.ch>
 * @module xwysiwyg
 * @moduletype mixed module / hack
 * @package phpwebsite = 0.9.3-4 ++
 */

class PHPWS_xw_cms {

function requests_man($settings){
	if(isset($_GET['height'])&&($_GET['height']>0))	$settings['height']	= PHPWS_Text::parseInput($_GET['height']);
	else													$settings['height']	= "auto";
	if(isset($_GET['width'])&&($_GET['width']>0))	$settings['width']	= PHPWS_Text::parseInput($_GET['width']);
	else													$settings['width']	= "auto";
	if(isset($_GET['lang_activ']))			$settings['lang_activ']		= 1;
	else										$settings['lang_activ']		= 0;
	if(isset($_GET['view_anon']))			$settings['view_anon']		= 1;
	else										$settings['view_anon']		= 0;
	if(isset($_GET['view_user']))			$settings['view_user']		= 1;
	else										$settings['view_user']		= 0;
	if(isset($_GET['request_mode']))		$settings['request_mode']	= 1;
	else										$settings['request_mode']	= 0;
	if(isset($_GET['enable_css']))			$settings['enable_css']		= 1;
	else										$settings['enable_css']		= 0;
	if(isset($_GET['theme']))				$settings['theme']			= PHPWS_Text::parseInput($_GET['theme']);
	else										$settings['theme']			= "none";
	if(isset($_GET['path']))				$settings['path']			= PHPWS_Text::parseInput($_GET['path']);
	else										$settings['path']			= "";
	if(isset($_GET['browsers']))			$settings['browsers']		= PHPWS_Text::parseInput($_GET['browsers']);
	else										$settings['browsers']		= "none";

	if($settings['plugins']<>'none') {
		$i = 1;
		foreach($settings['plugins'] as $key => $value) {
			if(isset($_GET["plug_$i"]))		$settings['plugins'][$key]	= 1;
			else								$settings['plugins'][$key]	= 0;
			$i++;
		}
	}

	return $settings;
}


function showOptions_man($settings) {
 if ($_SESSION["OBJ_user"]->allow_access("xwysiwyg", "settings")) {
    if (isset($_GET['func'])AND($_GET['func'] == "update")) {
		$settings = PHPWS_xw_cms::requests_man($settings);
	}

	$form = new EZform("xw_options");
    $form->add("module", "hidden", "xwysiwyg");
    $form->add("action", "hidden", "admin");
    $form->add("func", "hidden", "update");

    $form->add("width", "text", $settings['width']);
	$form->setWidth("width", "12");
    $form->add("height", "text", $settings['height']);
	$form->setWidth("height", "12");
    $form->add("path", "text", $settings['path']);
	$form->setWidth("path", "60");
    $form->add("browsers", "text", $settings['browsers']);
	$form->setWidth("browsers", "60");
    $form->add("lang_activ", "checkbox");
    $form->setMatch("lang_activ", $settings['lang_activ']);
    $form->add("view_anon", "checkbox");
    $form->setMatch("view_anon", $settings['view_anon']);
    $form->add("view_user", "checkbox");
    $form->setMatch("view_user", $settings['view_user']);
    $form->add("request_mode", "checkbox");
    $form->setMatch("request_mode", $settings['request_mode']);
    $form->add("enable_css", "checkbox");
    $form->setMatch("enable_css", $settings['enable_css']);

	if($settings['plugins']<>'none') {
		$i = 1;
		foreach($settings['plugins'] as $key => $value) {
			$form->add("plug_$i", "checkbox");
			$form->setMatch("plug_$i", $value); //$settings['plugins'][$key]);
			$i++;
		}
	}

	if($settings['themes']<>'none') {
		$form->add("theme", "dropbox");
		$form->setValue("theme", $settings['themes']);
		$form->reindexValue("theme");
		$form->setMatch("theme", $settings['theme']);
	}

    $form->add("SUBMIT", "submit", $_SESSION['translate']->it("Save"));

	$formTags = $form->getTemplate();
    $formTags['TITLE']				= $_SESSION['translate']->it("Settings"); //Version???
    $formTags['WIDTH_TEXT']			= $_SESSION['translate']->it("Set width of editor (eg.600)");
    $formTags['HEIGHT_TEXT']		= $_SESSION['translate']->it("Set height of editor (eg.300)");
    $formTags['LANG_ACTIV_TEXT']	= $_SESSION['translate']->it("Enable translation of [var1]", $settings['editor']);
    $formTags['VIEW_ANON_TEXT']		= $_SESSION['translate']->it("Allow anonymous to use [var1]", $settings['editor']);
    $formTags['VIEW_USER_TEXT']		= $_SESSION['translate']->it("Allow users to use [var1]", $settings['editor']);
    $formTags['REQUEST_MODE_TEXT']	= $_SESSION['translate']->it("Enable Request Mode");
    $formTags['ENABLE_CSS_TEXT']	= $_SESSION['translate']->it("Enable CSS @import");
    $formTags['PATH_TEXT']			= $_SESSION['translate']->it("Path");
    $formTags['BROWSERS_TEXT']		= $_SESSION['translate']->it("Browsers");
	if(isset($formTags['THEME']))
		$formTags['THEME_TEXT']		= $_SESSION['translate']->it("Theme");

	$formTags['MESSAGE']	= PHPWS_xw_cms::updateOptions_man($settings);
    $formTags['TOGGLE']		= PHPWS_xw_cms::toggleButton_man($settings['editor']);
    $formTags['MANAGE']		= PHPWS_xw_cms::manageButton_man();
    $formTags['TEST']		= PHPWS_xw_cms::testButton_man();
    $formTags['BROWSER']	= PHPWS_xwysiwyg::sniffer($settings['browsers'],"text");
    $formTags['EDITOR']		= $_SESSION['translate']->it("You are using [var1] now.", "<strong>".$settings['editor']."</strong>");

	if($settings['plugins']<>'none') {
		$i = 1;
		foreach($settings['plugins'] as $key => $value) {
			$formTags["PLUG_".$i."_TEXT"]	= $_SESSION['translate']->it("Enable [var1] Plugin",$key);
			$i++;
		}
	}
	$this->content .= PHPWS_Template::processTemplate($formTags, "xwysiwyg", "options.tpl");
	$this->content .= PHPWS_xw_cms::showArea_man();
 } else {
	$this->content .= $_SESSION['translate']->it("Access was denied due to lack of proper permissions.");
 } // End of ADMINISTRATOR condition
}// END FUNC




function updateOptions_man($settings) {
	if(isset($_GET['func'])AND($_GET['func'] == "update")) {
		unset($settings['themes']);
		if($GLOBALS['core']->sqlUpdate($settings, 'mod_xw_config', 'editor', $settings['editor'])) {
			$message = "<span style=\"color:green;\">".$_SESSION['translate']->it("Settings saved successfully")."</span><br />"; }
		else { $message = "<span style=\"color:red;\">".$_SESSION['translate']->it("There was a problem saving to the database")."</span><br />"; }
	} else { $message = "<span style=\"color:blue;\">".$_SESSION['translate']->it("Make your selections")."</span><br />";	}
	return $message;
}

function toggleButton_man($editor) {
	$editors = $GLOBALS['core']->getCol("SELECT editor FROM mod_xw_config ORDER BY 1",TRUE);
	if(is_array($editors)) {
		$form = new EZform("xw_toggle");
	    $form->add("module", "hidden", "xwysiwyg");
    	$form->add("action", "hidden", "toggle");
		$form->add("editor", "dropbox");
		$form->setValue("editor", $editors);
		$form->reindexValue("editor");
		$form->setMatch("editor", $editor);
		$form->add("SUBMIT", "submit", $_SESSION['translate']->it("Change"));
	}
	$formTags = $form->getTemplate();
	return PHPWS_Template::processTemplate($formTags, "xwysiwyg", "toggle.tpl");
}

function manageButton_man() {
	$form = new EZform("xw_manage");
    $form->add("module", "hidden", "xwysiwyg");
   	$form->add("action", "hidden", "manageEditor");
	$form->add("SUBMIT", "submit", $_SESSION['translate']->it("Manage"));
	$formTags = $form->getTemplate();
	return PHPWS_Template::processTemplate($formTags, "xwysiwyg", "manage.tpl");
}

function testButton_man() {
	$form = new EZform("xw_test");
    $form->add("module", "hidden", "xwysiwyg");
   	$form->add("action", "hidden", "testEditor");
	$form->add("SUBMIT", "submit", $_SESSION['translate']->it("Test"));
	$formTags = $form->getTemplate();
	return PHPWS_Template::processTemplate($formTags, "xwysiwyg", "test.tpl");
}

function toggleEditor_man($editor) {
	$data['editor'] = $editor;
	if(!$GLOBALS['core']->quickFetch("SELECT editor FROM mod_xw_editor", TRUE, TRUE))
		return $GLOBALS['core']->sqlInsert($data, "mod_xw_editor");
	else
		return $GLOBALS['core']->sqlUpdate($data, "mod_xw_editor");
}


function showArea_man() {
	$query = "SELECT id,area FROM mod_xw_areas ORDER BY 1";
	$result = $GLOBALS['core']->query($query, TRUE, TRUE); 
	$table[] = array("<b>ID</b>","<b>".$_SESSION['translate']->it("Text Area Name")."</b>","<b>".$_SESSION['translate']->it("Action")."</b>");
    if($result) {
		while($row = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
			$id			= $row['id'];
			$area		= $row['area'];
			$delelink	= $this->linkRef."&action=delArea&area=$area";
			$deletext	= $_SESSION['translate']->it("Delete");
			$table[]	= array($id,$area,"<a href=\"$delelink\">$deletext</a>");
		}
	}
	$content = PHPWS_Text::ezTable($table,2,2,1,"",NULL,1,"top");
	return $content;
}

function testEditors($text) {
	if ($_SESSION["OBJ_user"]->allow_access("xwysiwyg", "settings")) {
		$this->content .= "<a href=\"".$this->linkRef."&amp;action=admin"."\">".$_SESSION['translate']->it("Back to administration")."</a>";
	    $tags = array();
	    $tags["TESTAREA"] = PHPWS_WizardBag::js_insert("wysiwyg", "xw_test_form", "xw_testarea") . PHPWS_Form::formTextArea("xw_testarea", $text, 10, 70);
		$tags["SUBMIT_BUTTON"] = PHPWS_Form::formSubmit($_SESSION["translate"]->it("Save"));
	    $elements[0] = PHPWS_Form::formHidden("module", "xwysiwyg");
	    $elements[0] .= PHPWS_Form::formHidden("action", "testEditor");
	    $elements[0] .= PHPWS_Template::processTemplate($tags, "xwysiwyg", "test_editor.tpl");
	    $this->content .= PHPWS_Form::makeForm("xw_test_form", "index.php", $elements, "post", FALSE, TRUE);
		$this->content .= "<hr /><br />".PHPWS_Text::parseOutput($text);
	} else {
		$this->content .= $_SESSION['translate']->it("Access was denied due to lack of proper permissions.");
	} // End of ADMINISTRATOR condition
}

function manageEditors() {
	if ($_SESSION["OBJ_user"]->allow_access("xwysiwyg", "settings")) {
		$this->content .= "<a href=\"".$this->linkRef."&amp;action=admin"."\">".$_SESSION['translate']->it("Back to administration")."</a>";
		if(isset($_GET['updList']))	//to update, simply delete the existing file
			@unlink(PHPWS_HOME_DIR."files/xwysiwyg/editors/list.txt");

		$editors	= array();
		$formTags	= array();
		$table = array();

		$this->content .= "<br /><br /><b>".$_SESSION['translate']->it("Available editors")."</b>";
		$table[]	= array("<b>".$_SESSION['translate']->it("Location")."</b>","<b>".$_SESSION['translate']->it("Editor")."</b>","<b>".$_SESSION['translate']->it("Status")."</b>","<b>".$_SESSION['translate']->it("Action")."</b>");
		$dir = PHPWS_File::readDirectory(PHPWS_HOME_DIR."files/xwysiwyg/editors/",FALSE,TRUE,FALSE,array('conf'));
		if (is_array($dir)){
			$location = $_SESSION['translate']->it("local");
			foreach($dir as $file){
				include(PHPWS_HOME_DIR."files/xwysiwyg/editors/".$file);
				$editors[] = $cfg_editor;
				$result = $GLOBALS['core']->quickFetch("SELECT editor FROM mod_xw_config WHERE editor = '$cfg_editor'", TRUE, TRUE);
				if($result) {
					$status			= $_SESSION['translate']->it("installed");
					$action_link	= $this->linkRef."&amp;action=delEditor&amp;delEditor=$cfg_editor";
					$action_label	= $_SESSION['translate']->it("Uninstall");
					$action			= "<a href=\"$action_link\">$action_label</a>";
					$select			= $GLOBALS['core']->quickFetch("SELECT editor FROM mod_xw_editor", TRUE, TRUE);
					if($select['editor'] == $cfg_editor) {
						$editor			= "<strong>$cfg_editor</strong>";
					} else {
						$action_link	= $this->linkRef."&amp;action=toggle&amp;editor=$cfg_editor";
						$action_label	= $_SESSION['translate']->it("Select");
						$action			.= " || <a href=\"$action_link\">$action_label</a>";
						$editor			= $cfg_editor;
					}
				} else {
					$status			= $_SESSION['translate']->it("not installed");
					$action_link	= $this->linkRef."&amp;action=addEditor&amp;addEditor=$cfg_editor&amp;fn=$file";
					$action_label	= $_SESSION['translate']->it("Install");
					$action			= "<a href=\"$action_link\">$action_label</a>";
					$action_link	= $this->linkRef."&amp;action=delFile&amp;delFile=$cfg_editor&amp;filetyp=ext";
					$action_label	= $_SESSION['translate']->it("Remove");
					$action			.= " || <a href=\"$action_link\">$action_label</a>";
					$editor			= $cfg_editor;
				}
				$table[]	= array($location,$editor,$status,$action);
			}
		} else {
			$this->content .= "<br />".$_SESSION['translate']->it("No local editors found");
		}

		if(!$GLOBALS["core"]->isHub) {
		$table[]	= array("<b>".$_SESSION['translate']->it("Location")."</b>","<b>".$_SESSION['translate']->it("Editor")."</b>","<b>".$_SESSION['translate']->it("Status")."</b>","<b>".$_SESSION['translate']->it("Action")."</b>");
			if(!is_dir("{$GLOBALS['core']->home_dir}files/xwysiwyg/"))
				PHPWS_File::makeDir($GLOBALS['core']->home_dir . "files/xwysiwyg/");
			if(!is_dir("{$GLOBALS['core']->home_dir}files/xwysiwyg/editors/"))
				PHPWS_File::makeDir($GLOBALS['core']->home_dir . "files/xwysiwyg/editors/");
			$dir = PHPWS_File::readDirectory(PHPWS_SOURCE_DIR."files/xwysiwyg/editors/",FALSE,TRUE,FALSE,array('conf'));
			if (is_array($dir)){
				$location = $_SESSION['translate']->it("hub");
				foreach($dir as $file){
					include_once(PHPWS_SOURCE_DIR."files/xwysiwyg/editors/".$file);
					if(!in_array($cfg_editor, $editors)) {
						$status			= $_SESSION['translate']->it("not copied");
						$action_link	= $this->linkRef."&amp;action=cpyEditor&amp;cpyEditor=$cfg_editor";
						$action_label	= $_SESSION['translate']->it("Copy");
						$action			= "<a href=\"$action_link\">$action_label</a>";
						$editor			= $cfg_editor;
					} else {
						$status			= $_SESSION['translate']->it("copied");
						$action_link	= $this->linkRef."&amp;action=remEditor&amp;remEditor=$cfg_editor";
						$action_label	= "";
						$action			= "na";
						$editor			= $cfg_editor;
					}
					$table[]	= array($location,$editor,$status,$action);
				}
			} else {
				$this->content .= "<br />".$_SESSION['translate']->it("No editors found on hub");
			}
		}
		$this->content .= PHPWS_Text::ezTable($table,2,2,1,"",NULL,1,"top");

		$this->content .= "<br /><br /><b>".$_SESSION['translate']->it("Available editor-packages")."</b>";
		$this->content .= "&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"".$this->linkRef."&amp;action=manageEditor&amp;updList=1"."\">".$_SESSION['translate']->it("Update List")."</a>";
		$table = array();
		$table[]	= array("<b>".$_SESSION['translate']->it("Location")."</b>","<b>".$_SESSION['translate']->it("Editor")."</b>","<b>".$_SESSION['translate']->it("Description")."</b>","<b>".$_SESSION['translate']->it("Action")."</b>");
		$dir = PHPWS_File::readDirectory(PHPWS_HOME_DIR."files/xwysiwyg/editors/",FALSE,TRUE,FALSE,array('tgz'));
		if (is_array($dir)){
			$location = $_SESSION['translate']->it("local");
			foreach($dir as $file){
					$status			= $_SESSION['translate']->it("Archive package");
					$action_link	= $this->linkRef."&amp;action=tarEditor&amp;tarEditor=$file&amp;local=1";
					$action_label	= $_SESSION['translate']->it("Extract");
					$action			= "<a href=\"$action_link\">$action_label</a>";
					$action_link	= $this->linkRef."&amp;action=delFile&amp;delFile=$file&amp;filetyp=tgz";
					$action_label	= $_SESSION['translate']->it("Delete");
					$action			.= " || <a href=\"$action_link\">$action_label</a>";
					$editor			= $file;
					$table[]		= array($location,$editor,$status,$action);
			}
		}
		// fixme: remeber to update this entry !
		$file_loc = PHPWS_HOME_DIR."files/xwysiwyg/editors/list.txt";
		$file_onl = "http://www.firebird.ch/xwysiwyg/editors/list41.txt";
		$location = $_SESSION['translate']->it("remote");
		if(!is_file($file_loc))
			PHPWS_File::fileCopy($file_onl, PHPWS_HOME_DIR."files/xwysiwyg/editors/", "list.txt", TRUE, TRUE);

		if ($rows = file($file_loc)){
			PHPWS_Array::dropNulls($rows);
			foreach ($rows as $items){
		    $item = explode("::", $items);
			$editor			= $item[0];
			if(in_array($editor, $editors)) continue;
			$status			= $item[1];
			$action_label	= $_SESSION['translate']->it("Direct install ([var1]kb)",$item[3]);
			$action_link	= $this->linkRef."&amp;action=tarEditor&amp;tarEditor=".$item[2];
			$action			= "<a href=\"$action_link\">$action_label</a>";
			$action_label	= $_SESSION['translate']->it("Download");
			$action_link	= "http://www.firebird.ch/xwysiwyg/editors/".$item[2];
			$action			.= " || <a href=\"$action_link\" target=\"_blank\">$action_label</a>";
			$table[]		= array($location,$editor,$status,$action);
		 }
	}
	$this->content .= PHPWS_Text::ezTable($table,2,2,1,"",NULL,1,"top");
	$this->content .= $_SESSION['translate']->it("Depending on your server's security settings, direct install may not work.")."<br />";
	$this->content .= $_SESSION['translate']->it("If everything fails, or if you want to modify the editor,")."<br />";
	$this->content .= $_SESSION['translate']->it("download the file and copy it to /_phpws_/files/xwysiwyg/editors/ manually")."<br />";
	} else {
		$this->content .= $_SESSION['translate']->it("Access was denied due to lack of proper permissions.");
	} // End of ADMINISTRATOR condition
}

function addEditor() {
	if ($_SESSION["OBJ_user"]->allow_access("xwysiwyg","settings")) {//Administrativ condition
		if(isset($_GET['addEditor']))	$data['editor'] = PHPWS_Text::parseInput($_GET['addEditor']);
		else	return FALSE;
		if(isset($_GET['fn']))			$file = PHPWS_Text::parseInput($_GET['fn']);
		else	return FALSE;
		require(PHPWS_HOME_DIR."files/xwysiwyg/editors/".$file);
		if($data['editor'] = $cfg_editor) {
			if(isset($cfg_path))		$data['path']		= $cfg_path;
			if(isset($cfg_browsers))	$data['browsers']	= $cfg_browsers;
			if(isset($cfg_height))		$data['height']		= $cfg_height;
			if(isset($cfg_width))		$data['width']		= $cfg_width;
			if((isset($cfg_plugins))&&($cfg_plugins<>'none')) {
				$data['plugins']	= array_flip(explode(";",$cfg_plugins));
				foreach($data['plugins'] as $key => $value)	$data['plugins'][$key] = 0;
				$data['plugins']	= serialize($data['plugins']);
			} else $data['plugins'] = "none";
			if(isset($cfg_themes))	$data['themes']	= $cfg_themes;
			else	$data['themes']	= "none";
			if(isset($cfg_def_theme))	$data['theme']	= $cfg_def_theme;
		} else	return FALSE;
		if($GLOBALS['core']->sqlInsert($data, "mod_xw_config", TRUE, FALSE)) {
			$this->content .= "<span style=\"color:green;font-weight:bold\">".$_SESSION['translate']->it("Saving was successful")."</span><br />";
			if(!$GLOBALS['core']->quickFetch("SELECT editor FROM mod_xw_editor", TRUE, TRUE))
				$result = PHPWS_xw_cms::toggleEditor_man($cfg_editor);
		} else { $this->content .= "<span style=\"color:red;\">".$_SESSION['translate']->it("There was a problem saving to the database")."</span><br />"; }
	} else {
		$this->content .= $_SESSION['translate']->it("Access was denied due to lack of proper permissions.");
	} // End of ADMINISTRATOR condition
}


function delEditor() {
	if ($_SESSION["OBJ_user"]->allow_access("xwysiwyg","settings")) {//Administrativ condition
		if(isset($_GET['delEditor']))	$editor = PHPWS_Text::parseInput($_GET['delEditor']);
		else	return FALSE;
		if($GLOBALS['core']->sqlDelete("mod_xw_config","editor",$editor)) {
			$this->content .= "<span style=\"color:green;font-weight:bold\">".$_SESSION['translate']->it("Entry was successfully deleted from the database")."</span><br />";
				if($GLOBALS['core']->quickFetch("SELECT editor FROM mod_xw_editor WHERE editor = '$editor'", TRUE, TRUE))
					$GLOBALS['core']->sqlDelete("mod_xw_editor","editor",$editor);
		}
		else { $this->content .= "<span style=\"color:red;\">".$_SESSION['translate']->it("There was a problem deleting the entry")."</span><br />"; }
	} else {
		$this->content .= $_SESSION['translate']->it("Access was denied due to lack of proper permissions.");
	} // End of ADMINISTRATOR condition
}

function cpyEditor() {
	if ($_SESSION["OBJ_user"]->allow_access("xwysiwyg","settings")) {//Administrativ condition
		if(isset($_GET['cpyEditor']))	$editor = PHPWS_Text::parseInput($_GET['cpyEditor']);
		else	return FALSE;
		if(is_file(PHPWS_SOURCE_DIR."files/xwysiwyg/editors/".$editor.".php")) {
			PHPWS_File::recursiveFileCopy(PHPWS_SOURCE_DIR . "files/xwysiwyg/editors/$editor/", PHPWS_HOME_DIR . "files/xwysiwyg/editors/$editor/");
			PHPWS_File::fileCopy(PHPWS_SOURCE_DIR . "files/xwysiwyg/editors/$editor.php", PHPWS_HOME_DIR . "files/xwysiwyg/editors/", "$editor.php", TRUE, TRUE);
			PHPWS_File::fileCopy(PHPWS_SOURCE_DIR . "files/xwysiwyg/editors/$editor.conf", PHPWS_HOME_DIR . "files/xwysiwyg/editors/", "$editor.conf", TRUE, TRUE);
		}
	} else {
		$this->content .= $_SESSION['translate']->it("Access was denied due to lack of proper permissions.");
	} // End of ADMINISTRATOR condition
}

function delFile() {
	if ($_SESSION["OBJ_user"]->allow_access("xwysiwyg","settings")) {//Administrativ condition
		if(isset($_GET['delFile']))	$filename = PHPWS_Text::parseInput($_GET['delFile']);
		else	return FALSE;
		if(isset($_GET['filetyp']))	$filetyp = PHPWS_Text::parseInput($_GET['filetyp']);
		else	return FALSE;
		if($filename) {
			if ($filetyp=="tgz") {
				unlink(PHPWS_HOME_DIR . "files/xwysiwyg/editors/$filename");
			} elseif($filetyp=="ext") {
				PHPWS_File::rmdir(PHPWS_HOME_DIR . "files/xwysiwyg/editors/$filename");
				unlink(PHPWS_HOME_DIR . "files/xwysiwyg/editors/$filename.conf");
				unlink(PHPWS_HOME_DIR . "files/xwysiwyg/editors/$filename.php");
				@unlink(PHPWS_HOME_DIR . "files/xwysiwyg/editors/".$filename."_readme.txt");
			}
		}
	} else {
		$this->content .= $_SESSION['translate']->it("Access was denied due to lack of proper permissions.");
	} // End of ADMINISTRATOR condition
}

function tarEditor() {
	if ($_SESSION["OBJ_user"]->allow_access("xwysiwyg","settings")) {//Administrativ condition
		if(isset($_GET['tarEditor']))	$package = PHPWS_Text::parseInput($_GET['tarEditor']);
		else	return FALSE;
		$file_loc = PHPWS_HOME_DIR."files/xwysiwyg/editors/".$package;
		$file_onl = "http://www.firebird.ch/xwysiwyg/editors/".$package;
		//Last check, if the target is writeable
		if(!is_writeable(PHPWS_HOME_DIR."files/xwysiwyg/editors")) {
			$this->content .= $_SESSION['translate']->it("[var1] does NOT exist or is NOT server writable!","files/xwysiwyg/editors")."<br />";
			$this->content .= $_SESSION['translate']->it("Try to create it manually (This may be a SafeMode problem)")."<br />";
			return FALSE;
 		}
		if(!isset($_GET['local']))	//if not local, copy first
			PHPWS_File::fileCopy($file_onl, PHPWS_HOME_DIR . "files/xwysiwyg/editors/", "$package", TRUE, TRUE);
		//Now we try to extract the files
		$tar = new Archive_Tar($file_loc);
		$result = $tar->extract(PHPWS_HOME_DIR."files/xwysiwyg/editors/");
		if(!$result)	$this->content .= $_SESSION['translate']->it("Direct install failed!")."<br />";
	} else {
		$this->content .= $_SESSION['translate']->it("Access was denied due to lack of proper permissions.");
	} // End of ADMINISTRATOR condition
}

}//END class
?>