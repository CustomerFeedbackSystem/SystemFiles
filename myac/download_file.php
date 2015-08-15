<?php
require_once('../assets_backend/be_includes/config.php');
require_once('../assets_backend/be_includes/check_login.php');

//session_start();
$timenowis = date("Y-m-d H:i:s",time());

$geti = trim(mysql_escape_string($_REQUEST['i']));
$getf = trim(mysql_escape_string($_REQUEST['f']));
###############################################################
# File Download 1.3
###############################################################
# Visit http://www.zubrag.com/scripts/ for updates
###############################################################
# Sample call:
#    download.php?f=phptutorial.zip
#
# Sample call (browser will try to save with new file name):
#    download.php?f=phptutorial.zip&fc=php123tutorial.zip
###############################################################

//first, validate the links
if ( (abs($getf)>0) && (strlen($geti)==10) )
	{

	//then check if this user is permitted to even download this file - if not, warn them
	$res_perm=mysql_query("SELECT value_path,perm_read,sysprofiles_idsysprofiles,wfassetsdata.wfprocassets_idwfprocassets as assets,
	(SELECT perm_read FROM wfprocassetsaccess WHERE wfprocassets_idwfprocassets=assets AND sysprofiles_idsysprofiles=".$_SESSION['MVGitHub_iduserprofile'].") as perm
	 FROM wfassetsdata 
	INNER JOIN wfprocassetsaccess ON wfassetsdata.wfprocassetsaccess_idwfprocassetsaccess=wfprocassetsaccess.idwfprocassetsaccess 
	WHERE idwfassetsdata=".$getf." AND date(wfassetsdata.createdon)='".$geti."'");
	
	$num_perm=mysql_num_rows($res_perm);
	$fet_perm=mysql_fetch_array($res_perm);
	
	if ($num_perm<1) //then there is no record so give warning and exit
		{
		echo "<div style=\"color:#ff0000;font-family:arial\">Document Link Not Found</div>";
		
		//log that failure
		$sql_download="INSERT INTO audit_docdownlds ( doc_name, doc_ext, doc_size, tktin_idtktin, createdon, createdby, usersess, usrip, usrresult) 
				VALUES ('".$fet_perm['value_path']."', '0', '0', '0', '".$timenowis."', '".$_SESSION['MVGitHub_idacname']."', '".session_id()."', '".$_SERVER["REMOTE_ADDR"]."', 'NOLK')";
				
				mysql_query($sql_download);
		
		exit();
		} else { //however if the document link is found, then find if profile of user is allowed to view
		
		
			//if any of the above is false, then stop the process and log it
			if ($fet_perm['perm']!=1)
				{
				echo "<div style=\"color:#ff0000;font-family:arial\">Your Profile does not have permissions to download this document. </div>";
				
				//log that failure
				$sql_download="INSERT INTO audit_docdownlds ( doc_name, doc_ext, doc_size, tktin_idtktin, createdon, createdby, usersess, usrip, usrresult) 
				VALUES ('".$fet_perm['value_path']."', '0', '0', '0', '".$timenowis."', '".$_SESSION['MVGitHub_idacname']."', '".session_id()."', '".$_SERVER["REMOTE_ADDR"]."', 'NOPM')";
				
				mysql_query($sql_download);
				
				exit();
				
				} else {
				
					//check if the physical file esists first
					if (file_exists('../documents/task_docs/'.$geti.'/'.$fet_perm['value_path'])!=1)
						{
						echo "<div style=\"color:#ff0000;font-family:arial\">Oops! We could not locate the file </div>";
				
						//log that failure
						$sql_download="INSERT INTO audit_docdownlds ( doc_name, doc_ext, doc_size, tktin_idtktin, createdon, createdby, usersess, usrip, usrresult) 
						VALUES ('".$fet_perm['value_path']."', '0', '0', '0', '".$timenowis."', '".$_SESSION['MVGitHub_idacname']."', '".session_id()."', '".$_SERVER["REMOTE_ADDR"]."', 'NFIL')";
						
						mysql_query($sql_download);
						
						exit();
						}
				// Allow direct file download (hotlinking)?
				// Empty - allow hotlinking
				// If set to nonempty value (Example: example.com) will only allow downloads when referrer contains this text
				define('ALLOWED_REFERRER', '');
				
				// Download folder, i.e. folder where you keep all files for download.
				// MUST end with slash (i.e. "/" )
				define('BASE_DIR','../documents/task_docs/'.$geti.'/');
				
				// log downloads?  true/false
				define('LOG_DOWNLOADS',true);
				
				// log file name
				define('LOG_FILE','downloads.log');
				
				// Allowed extensions list in format 'extension' => 'mime type'
				// If myme type is set to empty string then script will try to detect mime type 
				// itself, which would only work if you have Mimetype or Fileinfo extensions
				// installed on server.
				$allowed_ext = array (
				
				  // archives
				  'zip' => 'application/zip',
				
				  // documents
				  'pdf' => 'application/pdf',
				  'doc' => 'application/msword',
				  'docx' => 'application/msword',
				  'xls' => 'application/vnd.ms-excel',
				  'xlsx' => 'application/vnd.ms-excel',
				  'ppt' => 'application/vnd.ms-powerpoint',
				  'pptx' => 'application/vnd.ms-powerpoint',
				  
				  // executables
				//  'exe' => 'application/octet-stream',
				
				  // images
				  'gif' => 'image/gif',
				  'png' => 'image/png',
				  'jpg' => 'image/jpeg',
				  'jpeg' => 'image/jpeg',
				
				  // audio
				  'mp3' => 'audio/mpeg',
				  'wav' => 'audio/x-wav',
				
				  // video
				  'mpeg' => 'video/mpeg',
				  'mpg' => 'video/mpeg',
				  'mpe' => 'video/mpeg',
				  'mov' => 'video/quicktime',
				  'avi' => 'video/x-msvideo'
				);
				
				
				
				####################################################################
				###  DO NOT CHANGE BELOW
				####################################################################
				
				// If hotlinking not allowed then make hackers think there are some server problems
				if (ALLOWED_REFERRER !== ''
				&& (!isset($_SERVER['HTTP_REFERER']) || strpos(strtoupper($_SERVER['HTTP_REFERER']),strtoupper(ALLOWED_REFERRER)) === false)
				) {
				  die("Internal server error. Please contact system administrator.");
				}
				
				// Make sure program execution doesn't time out
				// Set maximum script execution time in seconds (0 means no limit)
				set_time_limit(0);
				
				if (!isset($fet_perm['value_path']) || empty($fet_perm['value_path'])) {
				  die("Please specify file name for download.");
				}
				
				// Get real file name.
				// Remove any path info to avoid hacking by adding relative path, etc.
//				$fname = basename($_GET['f']);
				$fname = $fet_perm['value_path'];
				
				// Check if the file exists
				// Check in subfolders too
				function find_file ($dirname, $fname, &$file_path) {
				
				  $dir = opendir($dirname);
				
				  while ($file = readdir($dir)) {
					if (empty($file_path) && $file != '.' && $file != '..') {
					  if (is_dir($dirname.'/'.$file)) {
						find_file($dirname.'/'.$file, $fname, $file_path);
					  }
					  else {
						if (file_exists($dirname.'/'.$fname)) {
						  $file_path = $dirname.'/'.$fname;
						  return;
						}
					  }
					}
				  }
				
				} // find_file
				
				// get full file path (including subfolders)
				$file_path = '';
				find_file(BASE_DIR, $fname, $file_path);
				
				if (!is_file($file_path)) {
				  die("File does not exist. Make sure you specified correct file name."); 
				}
				
				// file size in bytes
				$fsize = filesize($file_path); 
				
				// file extension
				$fext = strtolower(substr(strrchr($fname,"."),1));
				
				// check if allowed extension
				if (!array_key_exists($fext, $allowed_ext)) {
				  die("Not allowed file type."); 
				}
				
				// get mime type
				if ($allowed_ext[$fext] == '') {
				  $mtype = '';
				  // mime type is not set, get from server settings
				  if (function_exists('mime_content_type')) {
					$mtype = mime_content_type($file_path);
				  }
				  else if (function_exists('finfo_file')) {
					$finfo = finfo_open(FILEINFO_MIME); // return mime type
					$mtype = finfo_file($finfo, $file_path);
					finfo_close($finfo);  
				  }
				  if ($mtype == '') {
					$mtype = "application/force-download";
				  }
				}
				else {
				  // get mime type defined by admin
				  $mtype = $allowed_ext[$fext];
				}
				
				// Browser will try to save file with this filename, regardless original filename.
				// You can override it if needed.
				
				
				$fdownload_raw=explode('_',$fet_perm['value_path']);
				$fdownload_pref=(strlen($fdownload_raw[0])+1); //length of the suffix + 1 for the underscore_
				$fdownload=substr($fet_perm['value_path'],$fdownload_pref,250); //full name including the extension
				
		/*		if (!isset($_GET['fc']) || empty($_GET['fc'])) {
				  $asfname = $fname;
				}
				else {
				  // remove some bad chars
				  $asfname = str_replace(array('"',"'",'\\','/'), '', $_GET['fc']);
				  if ($asfname === '') $asfname = 'NoName';
				}
		*/		
				//echo "<div style=\"background-color:#009900;color:#ffffff;font-weight:bold;font-family:arial\">File Downloading Successfully...</div>";
		
				// set headers
				header("Pragma: public");
				header("Expires: 0");
				header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
				header("Cache-Control: public");
				header("Content-Description: File Transfer");
				header("Content-Type: $mtype");
				header("Content-Disposition: attachment; filename=\"$fdownload\"");
				header("Content-Transfer-Encoding: binary");
				header("Content-Length: " . $fsize);
				
				// download
				// @readfile($file_path);
				$file = @fopen($file_path,"rb");
				if ($file) {
				  while(!feof($file)) {
					print(fread($file, 1024*8));
					flush();
					if (connection_status()!=0) {
					  @fclose($file);
					  die();
					}
				  }
				  @fclose($file);
				}
				
				// log downloads
				if (!LOG_DOWNLOADS) die();
				
				$f = @fopen(LOG_FILE, 'a+');
				if ($f) {
				  @fputs($f, date("m.d.Y g:ia")."  ".$_SERVER['REMOTE_ADDR']."  ".$fname."\n");
				  @fclose($f);
				}
				
				// log this doenload
				$sql_download="INSERT INTO audit_docdownlds ( doc_name, doc_ext, doc_size, tktin_idtktin, createdon, createdby, usersess, usrip, usrresult) 
				VALUES ('".$fname."', '0', '0', '0', '".$timenowis."', '".$_SESSION['MVGitHub_idacname']."', '".session_id()."', '".$_SERVER["REMOTE_ADDR"]."', 'OK')";
				
				mysql_query($sql_download);
							
				
				} //if (($fet_perm['perm_read']!=1) || ($fet_perm['sysprofiles_idsysprofiles']!=$_SESSION['MVGitHub_iduserprofile']) )
	
			} //if ($num_perm<1)

		} else { //if ( (abs($getf)>0) && (strlen($geti)==10) )
	
	//log the invalid / illegal attempt
	
	} //if ( (abs($getf)>0) && (strlen($geti)==10) ) 
?>