<?php
require_once('../assets_backend/be_includes/config.php');
require_once('../assets_backend/be_includes/check_login_easy.php');

if ( (isset($_GET['generate_key'])) && ($_GET['generate_key']=="on") )
	{

		function generateRandomString($length = 6) {
		$characters = '123456789ABCDEFGHJKLMNPQRSTUVWXYZ';
		$randomString = '';
		for ($i = 0; $i < $length; $i++) 
			{
			$randomString .= $characters[rand(0, strlen($characters) - 1)];
			}
		return $randomString;
		}
	
	$newkey=generateRandomString();
	
	//insert this key in the relevant table for authentication
	if (strlen($newkey)==6) //if the key is generated, then go ahead
		{
		$sql_rec="INSERT INTO wftasksdeleg_key (time_requested,requested_by_idusrac,requested_by_idrole,authkey) 
		VALUES ('".$timenowis."','".$_SESSION['MVGitHub_idacname']."','".$_SESSION['MVGitHub_iduserrole']."','".$newkey."')";
		mysql_query($sql_rec);
		
		}
	
//	echo generateRandomString();
	
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="../assets_backend/css/style.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div>
	<div class="tbl_sh" style="position:fixed; margin:0px; padding:0px; top:0px">
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
  		<tr>
        	<td width="660">Generate a Task Delegation Key</td>
            <td>&nbsp;</td>
        	<td align="right">
            	<a href="#" onClick="parent.tb_remove();" id="button_closewin"></a>
            </td>
      </tr>
    </table>
  </div>
  <div style="padding:30px;">
    <div class="msg_instructions">
    <strong>    Please generate a Key for your collegue to Delegate Tasks to you</strong></div>
  <div style="padding:20px; background-image:url(../assets_backend/images/bg_section.jpg); background-repeat:repeat-x">
        <?php
		if ( (!isset($_GET['generate_key'])) || ( (isset($_GET['generate_key'])) && ($_GET['generate_key']!="on") ) )
			{
		?>
        <a href="<?php echo $_SERVER['PHP_SELF'];?>?generate_key=on" style="text-decoration:none">
        <div class="border_thick" style="padding:5px">
        <center>
          <strong>GENERATE KEY</strong>
        </center>
        </div>
        </a>
        <?php } else { ?>
        <div class="border_thick" style="padding:5px">
        <center>
        <strong style="font-family:'Courier New', Courier, monospace; font-size:26px">
        <?php echo $newkey; ?>
        </strong>
        </center>
        </div>
        <div class="text_body">
        <center>
        <a href="<?php echo $_SERVER['PHP_SELF'];?>?generate_key=on">Generate another Key</a>
        </center>
        </div>
        <?php } ?>
        </div>
  </div>
</div>
</body>
</html>
