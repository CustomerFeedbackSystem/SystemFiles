<?php
require_once('../assets_backend/be_includes/check_login.php');

if (!isset($_GET['tactor']))
	{
//determine this actor is whether a role or a group
$sql_whoact="SELECT usrrole_idusrrole,usrgroup_idusrgroup FROM wfactors WHERE  wftskflow_idwftskflow=".$_SESSION['idflow']." LIMIT 1";
$res_whoact=mysql_query($sql_whoact);
$fet_whoact=mysql_fetch_array($res_whoact);
$num_whoact=mysql_num_rows($res_whoact);
//echo $sql_whoact;
	if ($num_whoact >0)
		{
			if ($fet_whoact['usrrole_idusrrole'] > 0)
				{
				$_SESSION['thiswfactor']="role";
				}
			if ($fet_whoact['usrgroup_idusrgroup'] > 0)
				{
				$_SESSION['thiswfactor']="group";
				}	
		}
	}

if (!isset($_SESSION['thiswfactor']))
	{
	$_SESSION['thiswfactor']="role";
	}

if (isset($_GET['tactor']))
	{
	$_SESSION['thiswfactor']=mysql_escape_string(trim($_GET['tactor']));
	}


//check if the actors are currently user roles or user groups
$sql_cact="SELECT usrrole_idusrrole,usrgroup_idusrgroup FROM wfactors WHERE wftskflow_idwftskflow=".$_SESSION['idflow']." LIMIT 1";
$res_cact=mysql_query($sql_cact);
$num_cact=mysql_num_rows($res_cact);
$fet_cact=mysql_fetch_array($res_cact);
/*echo $fet_cact['usrrole_idusrrole'];
echo $fet_cact['usrgroup_idusrgroup'];*/
?>
<div>
	<div>
    <table border="0" cellpadding="0" cellspacing="0">
    	<tr>
        	<td>
            <a href="<?php echo $_SERVER['PHP_SELF'];?>?tactor=role" id="button_radio<?php if ($_SESSION['thiswfactor']=="role"){ echo "_on"; }?>"></a>
            </td>
            <td class="text_body" style="padding:0px 50px 0px 0px">
            <?php echo $lbl_role;?>
            </td>
            <td>
            <a href="<?php echo $_SERVER['PHP_SELF'];?>?tactor=group" id="button_radio<?php if ($_SESSION['thiswfactor']=="group"){ echo "_on"; }?>"></a>
			</td>
			<td class="text_body">
			<?php echo $lbl_groups;?>
            </td>
        </tr>
    </table>
    </div>
    <div>
    <?php
	 if ($_SESSION['thiswfactor']=="role")
	 	{
		require_once('tabview_responsible_role_v2.php');
		}
	if ($_SESSION['thiswfactor']=="group")
	 	{
		require_once('tabview_responsible_group.php');
		}
	?>
    </div>
</div>