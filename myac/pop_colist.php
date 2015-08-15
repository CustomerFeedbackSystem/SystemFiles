<?php
require_once('../assets_backend/be_includes/config.php');
require_once('../assets_backend/be_includes/check_login.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="../assets_backend/css/style.css" rel="stylesheet" type="text/css" />
</head>
<body style="background-color:#ffffff">
	<div class="tbl_sh" style="position:fixed; margin:0px 0px 35px 0px; padding:0px; top:0px; width:100%">
    <table border="0" cellpadding="0" cellspacing="0" width="100%" >
  		<tr>
        	<td width="60%">
            <div>
            C / O Tasks
            </div>
       		</td>
          	<td align="right" width="40%">
            	<table border="0" cellpadding="0" cellspacing="0">
                	<tr>
                    	
                    	<td>
						<a href="#" onClick="parent.tb_remove();" id="button_closewin"></a>
                        </td>
					</tr>
				</table>
            </td>
      </tr>
    </table>
</div>
<div style="padding:33px 10px">
<?php
if ((isset($_GET['msg_del'])) && ($_GET['msg_del']==1) )
	{
?>
	<div class="msg_success_small">
    Tasks released Successfully!
    </div>
<?php
	} else {
?>    
	<div class="msg_instructions_small">
    Take care of Tasks on behalf of someone else
    </div>
<?php } ?>   
	<div style="background-color:#f7f7f7">
    <form method="post" name="search_usr">
    <table border="0">
    	<tr>
        	<td>
            Find User
            </td>
            <td>
            <input type="text" name="account_usr" size="50" maxlength="30" />
            </td>
            <td>
            <input type="hidden" value="search" name="faction" />
            <a href="#" id="button_search"  onclick="document.forms['search_usr'].submit()"></a>
            </td>
        </tr>
    </table>
    </form>
    </div>
    <div>
    <?php
	if ((isset($_POST['faction'])) && ($_POST['faction']=="search") )
		{
	$usr_val=trim(mysql_escape_string($_POST['account_usr']));
	
	if (strlen($usr_val) < 1)
		{
		echo "<div class=\"text_small\">
        <a href=\"pop_colist.php?faction=all\">&laquo; Back to My C / O List</a></div>
      <div>";
		echo "<div class=\"msg_warning\">User search name was too short. Please try again!</div>";
		
		exit;
		}	
	$sql="SELECT  fname, lname, usrrolename,idusrrole, usrdptname, 
	(SELECT idwftasks_co FROM wftasks_co WHERE idusrrole_owner=idusrrole AND co_status=0 AND wftasks_co.idusrrole_acting=".$_SESSION['MVGitHub_iduserrole']." AND wftasks_co.idusrrole_owner!=".$_SESSION['MVGitHub_iduserrole'].") as pending,
	(SELECT idwftasks_co FROM wftasks_co WHERE idusrrole_owner=idusrrole AND co_status=1 AND wftasks_co.idusrrole_acting=".$_SESSION['MVGitHub_iduserrole']." AND wftasks_co.idusrrole_owner!=".$_SESSION['MVGitHub_iduserrole'].") as active
	FROM usrac
	INNER JOIN usrrole ON usrac.usrrole_idusrrole = usrrole.idusrrole
	LEFT JOIN usrdpts ON usrrole.usrdpts_idusrdpts = usrdpts.idusrdpts
	INNER JOIN usrteamzone ON usrrole.usrteamzone_idusrteamzone=usrteamzone.idusrteamzone
	WHERE ( fname LIKE '%".$usr_val."%' OR lname LIKE '%".$usr_val."%' OR usrrolename LIKE '%".$usr_val."%' OR usrname LIKE '%".$usr_val."%') 
	AND usrteamzone.usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']."";
	//echo $sql;
	$res_mycos=mysql_query($sql);	
	$fet_mycos=mysql_fetch_array($res_mycos);
	$num_mycos=mysql_num_rows($res_mycos);
	
	if ($num_mycos > 0 )
		{
	?>
      <div class="text_small">
        <a href="pop_colist.php?faction=all">&laquo; Back to My C / O List</a></div>
      <div>
     <table border="0" width="100%" cellpadding="2" cellspacing="0">
    	<tr>
        <td height="30" class="tbl_h2" width="25"></td>
        <td height="30" class="tbl_h2">Name</td>
          <td class="tbl_h2">Role</td>
          <td class="tbl_h2">Department</td>
        </tr>
        <?php
		if ($num_mycos > 0)
			{
			do {
		?>
        <tr>
        	<td width="20" class="tbl_data">
            <?php if ($fet_mycos['pending']>0)
				{
				$url="pop_colist_action.php?do_ths=validate&amp;idrole=".$fet_mycos['idusrrole']."&amp;idwfco=".$fet_mycos['active'].$fet_mycos['pending'];
			?>
            <a title="Process Incomplete"   href="<?php echo $url;?>">
            <img src="../assets_backend/icons/warning.gif" width="20" height="15" border="0" align="absmiddle" />
            </a>
            <?php 
			} else if ($fet_mycos['active']>0) { 
			$url="pop_colist_action.php?do_ths=disable&amp;idwfco=".$fet_mycos['active']."&amp;idrole=".$fet_mycos['idusrrole']."";
			?>
            <a   href="<?php echo $url;?>" id="button_check_on" /></a>
            <?php } else if ( ($fet_mycos['pending']<1) && ($fet_mycos['active']<1) ) { 
			$url="pop_colist_action.php?do_ths=enable&amp;idrole=".$fet_mycos['idusrrole']."";
			?>
            <a   href="<?php echo $url;?>" id="button_check" /></a>
			<?php }?>
            </td>
            <td class="tbl_data">
            <a  href="<?php echo $url;?>">
            <?php echo $fet_mycos['fname'];?> <?php echo $fet_mycos['lname'];?>
            </a>
            </td>
          	<td class="tbl_data"><?php echo $fet_mycos['usrrolename'];?></td>
            <td class="tbl_data"><?php echo $fet_mycos['usrdptname'];?></td>
        </tr>
       <?php
	   			} while ($fet_mycos=mysql_fetch_array($res_mycos));
		   } //if num
	   ?>
    </table>
    </div>
    <?php } else { ?>
    <div class="text_small">
        <a href="pop_colist.php?faction=all">&laquo; Back to My C / O List</a></div>
      <div>
    <div class="msg_warning_small">
    Not Found a User by the name or term <?php echo $usr_val;?>
    </div>
    <?php } ?>
    <?php
	} else {
	$sql="SELECT idwftasks_co, fname, lname, usrrolename, usrdptname, co_status,idusrrole
	FROM wftasks_co
	INNER JOIN usrrole ON wftasks_co.idusrrole_owner = usrrole.idusrrole
	INNER JOIN usrac ON usrrole.idusrrole = usrac.usrrole_idusrrole
	LEFT JOIN usrdpts ON usrrole.usrdpts_idusrdpts = usrdpts.idusrdpts
	WHERE wftasks_co.idusrrole_acting=".$_SESSION['MVGitHub_iduserrole']."
	AND co_status <2";
	//echo $sql;
	$res_mycos=mysql_query($sql);	
	$fet_mycos=mysql_fetch_array($res_mycos);
	$num_mycos=mysql_num_rows($res_mycos);
	
	?>
    <div>
     <table border="0" width="100%" cellpadding="2" cellspacing="0">
    	<tr>
        <td height="30" class="tbl_h2" width="25"></td>
        <td height="30" class="tbl_h2">Name</td>
          <td class="tbl_h2">Role</td>
          <td class="tbl_h2">Department</td>
          <td class="tbl_h2"></td>
        </tr>
        <?php
		if ($num_mycos > 0)
			{
			do {
		?>
        <tr>
        	<td width="20" class="tbl_data">
            <?php 
			if ($fet_mycos['idusrrole']==$_SESSION['MVGitHub_iduserrole'])
				{
				echo "-";
				} else {
					if ($fet_mycos['co_status']==0)
						{
						$url="pop_colist_action.php?do_ths=validate&amp;idwfco=".$fet_mycos['idwftasks_co']."&amp;idrole=".$fet_mycos['idusrrole']."";
					?>
					<a href="<?php echo $url;?>">
					<img src="../assets_backend/icons/warning.gif" width="20" height="15" border="0" align="absmiddle" />
					</a>
					<?php 
					} else if ($fet_mycos['co_status']==1) { 
					$url="pop_colist_action.php?do_ths=disable&amp;idwfco=".$fet_mycos['idwftasks_co']."&amp;idrole=".$fet_mycos['idusrrole']."";
					?>
					<a href="<?php echo $url;?>" id="button_check_on" /></a>
					<?php } else { 
					$url="#";
					echo "-"; }
				}
				?>
            </td>
            <td class="tbl_data">
            <a   href="<?php echo $url;?>">
            <?php echo $fet_mycos['fname'];?> <?php echo $fet_mycos['lname'];?>
            </a>
            </td>
          	<td class="tbl_data"><?php echo $fet_mycos['usrrolename'];?></td>
            <td class="tbl_data"><?php echo $fet_mycos['usrdptname'];?></td>
            <td class="tbl_data">
            <?php
			if ($fet_mycos['co_status']<2)
				{
			?>
            <a href="pop_colist_action.php?action=del&amp;del=<?php echo $fet_mycos['idwftasks_co'];?>" id="button_delete_small" title="Delete C/O"></a>
            <?php
			}
			?>
            </td>
        </tr>
       <?php
	   			} while ($fet_mycos=mysql_fetch_array($res_mycos));
		   } //if num
	   ?>
    </table>
    </div>
    <?php
	} //if search
	?>
    </div>
</div>
</div>
</body>
</html>
