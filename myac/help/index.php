<?php
require_once('../../assets_backend/be_includes/config.php');

if (isset($_GET['help']))
	{
	$_SESSION['help']=mysql_escape_string(trim($_GET['help']));
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="../../assets_backend/css/style.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div class="tbl_sh" style="position:fixed; margin:0px; padding:0px ; top:0px; width:100%">
        	<table border="0" cellpadding="0" cellspacing="0" width="100%">
            	<tr>
                	<td width="100%">
					Quick Help File
                    </td>
                    <td>
                   <a href="#" onClick="parent.tb_remove();" id="button_closewin"></a>
                    </td>
				</tr>
			</table>
		</div>
<div style="padding:30px 5px">
<table border="0" width="100%">
	<tr>
    	<td valign="top" width="20%" bgcolor="#EFEFEF">
        TOPICS COMING SOON
        </td>
        <td valign="top" width="80%">
<?php
if (isset($_SESSION['help']))
	{
	if ($_SESSION['help']==1)
		{
		require_once('ticket_glossary.php');
		exit;
		}
	}
?>
		</td>
	</tr>
</table>    
</div>
</body>
</html>