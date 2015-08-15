<?php
require_once('../assets_backend/be_includes/config.php');

require_once('../assets_backend/be_includes/check_login_easy.php');

if ( (isset($_POST['formaction'])) && ($_POST['formaction']=="uploaddoc") )
	{
	//clean up the values
	//echo "test";
	$doc_name=preg_replace('/[^a-z\-_0-9\.:@\/\s]/i','',mysql_escape_string(trim($_POST['docname'])));
	$doc_cat=preg_replace('/[^a-z\-_0-9\.:@\/\s]/i','',mysql_escape_string(trim($_POST['doccat'])));
	$doc_comment=preg_replace('/[^a-z\-_0-9\.:@\/\s]/i','',mysql_escape_string(trim($_POST['doccomment'])));
	$doc_file=preg_replace('/[^a-z\-_0-9\.:@\/\s]/i','',mysql_escape_string(trim($_FILES['imagefile']['name'])));
	//validate it
	if (!isset($_FILES['imagefile']['name']))
		{
		$error_1="<div class=\"msg_warning\">".$msg_warning_attmis."</div>";
		}
	if (strlen($doc_name)<1)
		{
		$error_2="<div class=\"msg_warning\">".$msg_warning_docname."</div>";
		}
	
	if (strlen($doc_cat)<1)
		{
		$error_3="<div class=\"msg_warning\">".$msg_warning_nocategory."</div>";
		}
	
	//upload directory
	$idir = "uploads/";   // Path To Images Directory
	
	//process it
		if ( (!isset($error_1)) &&  (!isset($error_2)) && (!isset($error_3)) )
			{
			$imagepath = $_FILES['imagefile']['name'];
			//first, upload the document physically tot he folder
			if ( ($_FILES['imagefile']['type'] == "image/jpg") || ($_FILES['imagefile']['type'] == "image/jpeg") || ($_FILES['imagefile']['type'] == "image/pjpeg") || ($_FILES['imagefile']['type'] == "image/gif") || ($_FILES['imagefile']['type'] == "image/png") || ($_FILES['imagefile']['type'] == "application/pdf") || ($_FILES['imagefile']['type'] == "application/doc") || ($_FILES['imagefile']['type'] == "application/x-download") )
					{
					$file_ext = strrchr($_FILES['imagefile']['name'],'.');   // Get The File Extention In The Format Of , For Instance, .jpg, .gif or .php
					$file_extention = substr($_FILES['imagefile']['name'],-4,4);
					$copy = copy($_FILES['imagefile']['tmp_name'], "$idir/" . $_FILES['imagefile']['name']);   // Move Image From Temporary Location To Permanent Location
					
					$success="1";
					 // print '<font color="#0066CC"  face="Arial, Helvetica, sans-serif">Image thumbnail created successfully.</font>';   // Resize successful
				  } else {
					  print '<font color="#FF0000" face="Arial, Helvetica, sans-serif">ERROR: Unable to Upload File.</font>';   // Error Message If Upload Failed
				  }
			//if success, then add onto the database
				if (isset($success))
					{
					//insert to db
					$sql="INSERT INTO docattach ( doccats_iddoccat, tbl_name, tbl_fk_id, owner_usrteamid, docname, doccomments, docpath, docexten, enteredby, enteredon) 
					VALUES ('".$doc_cat."', '".$_SESSION['s_uploadtbl']."', '".$_SESSION['idreporting']."', '".$_SESSION['MVGitHub_idacteam']."','".$doc_name."', '".$doc_comment."', '".$imagepath."', NULL, '".$_SESSION['MVGitHub_idacname']."','".$timenowis."');";
					
					mysql_query($sql);
					
					$msg="<div class=\"msg_success\">".$msg_changes_saved."</div>";
					}	  
				 
			}
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Task Details</title>
<link href="../assets_backend/css/style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../scripts/jquery-1.7.2.min.js"></script>
<script language="javascript" type="text/javascript" src="../scripts/datetimepicker.js"></script>
</head>
<body>
<table border="0" width="100%" cellpadding="2" cellspacing="0" >
	<tr>
    	<td class="tbl_sh">
        	<table border="0" cellpadding="0" cellspacing="0" width="100%">
            	<tr>
               	  <td width="700">
                  <?php
				  echo $lbl_uploaddoc;
				  ?>
                  </td>
                   
                    <td>
                   <a href="#" onClick="parent.tb_remove();parent.location.reload(1)" id="button_closewin"></a>
                    </td>
				</tr>
			</table>
            </td>
    </tr>
    <tr>
    	<td>
        <div style="padding:10px 0px 20px 0px">
        <span class="msg_instructions">
        <?php if (!isset($_POST['formaction'])) { echo $ins_fillform; 
		} else {
			if (isset($error_1)) { echo $error_1; }
			if (isset($error_2)) { echo $error_2; }
			if (isset($error_3)) { echo $error_3; }
			if (isset($msg)) { echo $msg; }
		}?>
        </span>
        </div>
        <form method="post" action="" enctype="multipart/form-data" name="uploaddoc" id="uploaddoc">
        <table border="0" cellpadding="2" cellspacing="0">
        	<tr>
            	<td class="tbl_data">
                <?php
				echo $lbl_docname;
				?>
                </td>
                <td class="tbl_data">
                <input type="text" maxlength="100" size="40" name="docname" />
                </td>
            </tr>
            <tr>
            	<td class="tbl_data">
                 <?php echo $lbl_doccat;?>
                </td>
                <td class="tbl_data">
                <select name="doccat">
                <option value="">---</option>
                <?php
				$sql_doccat="SELECT iddoccat,doccat FROM doccats WHERE tbl_name='".$_SESSION['s_uploadtbl']."' ORDER BY doccat ASC";
				$res_doccat=mysql_query($sql_doccat);
				$fet_doccat=mysql_fetch_array($res_doccat);
				do {
				echo "<option value=\"".$fet_doccat['iddoccat']."\">".$fet_doccat['doccat']."</option>";
				} while ($fet_doccat=mysql_fetch_array($res_doccat));
				?>
                </select>
                </td>
            </tr>
            <tr>
            	<td class="tbl_data">
                <?php echo $lbl_selectdoc;?>
                </td>
                <td class="tbl_data">
                <input type="file" name="imagefile" value="" id="imagefile">
                </td>
            </tr>
            <tr>
            	<td class="tbl_data">
                <?php echo $lbl_comments;?>
                </td>
                <td class="tbl_data">
                	<textarea cols="30" rows="3" name="doccomment"></textarea>
                </td>
            </tr>
            <tr>
            	<td></td>
              <td>
                <input type="hidden" value="uploaddoc" name="formaction" />
                <a href="#" onclick="document.forms['uploaddoc'].submit()" id="button_submit"></a>                </td>
            </tr>
        </table>
        
        </form>
        </td>
  </tr>
    
    </table>
</body>
</html>
