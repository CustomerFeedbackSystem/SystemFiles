<?php
require_once('../assets_backend/be_includes/check_login_easy.php');


//if the selected zone to expand
if  ( (isset($_GET['list_action'])) && (isset($_GET['expand_list'])) )
	{
	$_SESSION['list_action']=mysql_escape_string(trim($_GET['list_action']));
	$_SESSION['expand_list']=mysql_escape_string(trim($_GET['expand_list']));
	}
	
################ASSIGN ACTORS TO A STEP##################
if ((isset($_POST['formaction'])) && ($_POST['formaction']=="assign") ) //form action
	{

	if ( (isset($_POST['role'])) && ($_POST['role']!=""))  //not empty
		{
		$count=count($_POST['role']);
		
			if ($count>0)
				{
				//if greater than 0
					for ($i=0; $i<$count; $i++) //then pick and store the variables
						{
						
						$roleid=$_POST['role'][$i];
						
						$sql_actor="INSERT INTO wfactors (usrrole_idusrrole,usrgroup_idusrgroup,wftskflow_idwftskflow,usrteamzone_idusrteamzone,createdby,createdon) 
						VALUES ('".$roleid."','0','".$_SESSION['idflow']."','".$_SESSION['expand_list']."','".$_SESSION['MVGitHub_idacname']."','".$timenowis."')";
						mysql_query($sql_actor);
						
						if ($_SESSION['group_task_share']==1)
							{
							//get the id 
							$sql_taskshare_groupname="SELECT idwfactorsgroupname FROM wfactorsgroupname WHERE wftskflow_idwftskflow=".$_SESSION['idflow']." LIMIT 1";
							$res_taskshare_groupname=mysql_query($sql_taskshare_groupname);
							$fet_taskshare_groupname=mysql_fetch_array($res_taskshare_groupname);
							
							//insert the new record
							$sql_insert_role="INSERT INTO wfactorsgroup (wfactorsgroupname_idwfactorsgroupname,usrrole_idusrrole,wftskflow_idwftskflow,createdby,createdon) 
							VALUES ('".$fet_taskshare_groupname['wfactorsgroupname_idwfactorsgroupname']."','".$roleid."','".$_SESSION['idflow']."','".$_SESSION['MVGitHub_idacname']."','".$timenowis."')";
							mysql_query($sql_insert_role);
							} //if group task share
						
						} //loop
				} //count >0
			
		} //not empty
	
	}	//form action
##END ASSIGN ACTORS#############33
	
################REMOVE ACTORS TO A STEP##################
if ((isset($_POST['formaction'])) && ($_POST['formaction']=="remove") ) //form action
	{	
	if ( (isset($_POST['role'])) && ($_POST['role']!=""))  //not empty
		{
		$count=count($_POST['role']);
		
			if ($count>0)
				{
				//if greater than 0
					for ($i=0; $i<$count; $i++) //then pick and store the variables
						{
						$roleid=$_POST['role'][$i];
						
						$sql_actor="DELETE FROM wfactors WHERE usrrole_idusrrole=".$roleid." AND wftskflow_idwftskflow=".$_SESSION['idflow']."  LIMIT 1";
						mysql_query($sql_actor);
						
						if ($_SESSION['group_task_share']==1) //delete if so
							{					
							//delete the roles themselves in that group
							$sql_deleteroles="DELETE FROM wfactorsgroup WHERE usrrole_idusrrole=".$roleid." AND wftskflow_idwftskflow=".$_SESSION['idflow']." LIMIT 1 ";
							mysql_query($sql_deleteroles);					
							}
						
						} //lop
				} //end count >0
		} //role not empty
	} //formaction
	

//check the exception
if (isset($_GET['chkaction']))
	{
	$record=mysql_escape_string(trim($_GET['record']));
	$caction=mysql_escape_string(trim($_GET['chkaction']));
	//update that record
	$sql_update="UPDATE wfactors SET allow_tskflow_jump='".$caction."' WHERE idwfactors=".$record." LIMIT 1";
	mysql_query($sql_update);
	
	}

############################3

//check if the administriator makingn this changes has global perm
if ($_SESSION['globalaccess_workflow']==1)
	{
	$filter_role="";
	} else {
	$filter_role=" AND usrteamzone.idusrteamzone=".$_SESSION['MVGitHub_userteamzoneid']." ";
	}

?>

<table border="0" width="800" cellpadding="1" cellspacing="0">
	<tr>
    	<td valign="top" class="hline" width="400px">
        <form method="post" action="" name="assign">
        	<?php
			//first, loop and list the region to Save the time and bandwidth
			$sql_regions="SELECT idusrteamzone,userteamzonename FROM usrteamzone WHERE usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']."";
			$res_regions=mysql_query($sql_regions);
			$fet_regions=mysql_fetch_array($res_regions);
			$num_regions=mysql_num_rows($res_regions);
			
			if ($num_regions > 0 ) //[001] there are regions in the db 
				{
				do {
			?>
            <div style="margin:5px 0px">
           	<table border="0" cellpadding="2" cellspacing="0" width="100%" >
            	<tr>
                	<td <?php if ((isset($_SESSION['expand_list'])) && ($fet_regions['idusrteamzone']==$_SESSION['expand_list'])) { echo "class=\"divcol\" style=\"font-weight:bold\" "; } else { echo "class=\"tbl_sh\" style=\"font-size:14px\" "; } ?> >                   
                    <a style="text-decoration:none; height:35px" href="<?php echo $_SERVER['PHP_SELF'];?>?list_action=expand&amp;expand_list=<?php echo $fet_regions['idusrteamzone'];?>" >
                    <div><?php echo $fet_regions['userteamzonename'];?></div>
                    </a>                   
                    </td >
                    <td width="30" <?php if ( (isset($_SESSION['expand_list'])) && ($fet_regions['idusrteamzone']==$_SESSION['expand_list'])) { echo "class=\"divcol\" style=\"font-weight:bold\" "; } else { echo "class=\"tbl_sh\""; } ?> align="right">
                    <?php
					 if ( (isset($_SESSION['expand_list'])) && ($fet_regions['idusrteamzone']==$_SESSION['expand_list']) ) {
					 ?>
                    <a href="#" onclick="document.forms['assign'].submit()" id="button_send_right"></a>
                     <?php
					}
					?>                    </td>
              </tr>
            </table>
            </div>
            <?php
	if ( (isset($_SESSION['list_action'])) && ($_SESSION['list_action']=="expand") && (isset($_SESSION['expand_list'])) && ($_SESSION['expand_list']==$fet_regions['idusrteamzone']) )
		{ //[002] //expand list
		//if this teamzone session is active, then list this users
		$query_roles = "SELECT idusrrole, usrrolename, usrroledesc, usrname, utitle, fname, lname, acstatus,userteamzonename,idusrteamzone, (SELECT idwfactors FROM wfactors WHERE usrrole.idusrrole = wfactors.usrrole_idusrrole AND wftskflow_idwftskflow=".$_SESSION['idflow'].") AS dis_user FROM usrrole INNER JOIN usrac ON usrrole.idusrrole = usrac.usrrole_idusrrole INNER JOIN usrteamzone ON usrrole.usrteamzone_idusrteamzone = usrteamzone.idusrteamzone WHERE usrteamzone.usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." AND usrteamzone.idusrteamzone=".$_SESSION['expand_list']." ORDER BY usrrolename ASC";
		$roles = mysql_query($query_roles, $connSystem) or die(mysql_error());
		$row_roles = mysql_fetch_assoc($roles);
		$totalRows_roles = mysql_num_rows($roles);
	
		if ($totalRows_roles > 0)
			{ //[003] if there is a record
			?>
		<div>
		<table border="0" cellpadding="2" cellspacing="0" width="400" >
        <?php
		do {
		?>
			<tr  <?php 
// technocurve arc 3 php mv block2/3 start
echo " style=\"background-color:$mocolor\" onMouseOver=\"this.style.backgroundColor='$mocolor3'\" onMouseOut=\"this.style.backgroundColor='$mocolor'\"";
// technocurve arc 3 php mv block2/3 end
?>>
            	<td width="10" class="tbl_data">
                <?php
				if ((!isset($fet_cact['usrgroup_idusrgroup'])) || ($fet_cact['usrgroup_idusrgroup'] < 1)) //if not set to group
					{
					if ($row_roles['dis_user']=="") { ?>
                 <label for="<?php echo $row_roles['idusrrole'];?>">
                <input type="checkbox" name="role[]" id="<?php echo $row_roles['idusrrole'];?>" value="<?php echo $row_roles['idusrrole'];?>" />
                </label>
                	<?php } else { ?>
                    <div style="background-color:#cccccc; width:20px; height:20px"></div>
                    <?php } 
					} //if not set to group
					?>
                </td>
                <td class="tbl_data" width="290">
				<span title="<?php echo $row_roles['usrroledesc'];?>"><?php echo $row_roles['usrrolename'];?></span>
				<div style="color:<?php if ($row_roles['acstatus']==1) { echo "#009900"; } else { echo "#ff0000"; } ?>"><small><?php echo $row_roles['utitle']." ".$row_roles['lname']." , ".$row_roles['fname'];?></small></div>
              </td>
              	<td class="tbl_data" width="100">
                <?php
					if (strlen($row_roles['usrname'])>0) { ?>
                    <span title="<?php echo $row_roles['utitle']." ".$row_roles['lname']." , ".$row_roles['fname'];?>"><?php echo $row_roles['usrname'];?></span>
                    <?php } else { echo "---"; } ?>
              </td>
			</tr>
         <?php
		 } while ($row_roles = mysql_fetch_assoc($roles));
        
		 // technocurve arc 3 php mv block3/3 start
			if ($mocolor == $mocolor1) {
				$mocolor = $mocolor2;
			} else {
				$mocolor = $mocolor1;
			}
		}  //[003] if there is a record
// technocurve arc 3 php mv block3/3 end
		 ?>
		</table>
		</div>
            <?php	
			} // [002] expand list
		 } while ($fet_regions=mysql_fetch_array($res_regions));
	 } //[001]
			?>
            <input type="hidden" name="formaction" value="assign" />
        </form>
        </td>
        
      <td valign="top"  width="400">
		<form method="post" action="" name="remove">
      <?php
			//first, loop and list the region to Save the time and bandwidth
			$sql_toregions="SELECT idusrteamzone,userteamzonename FROM usrteamzone WHERE usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']."";
			$res_toregions=mysql_query($sql_toregions);
			$fet_toregions=mysql_fetch_array($res_toregions);
			$num_toregions=mysql_num_rows($res_toregions);
			
			if ($num_toregions > 0 ) //[001] there are regions in the db 
				{
				do {
			?>
            <div  style="margin:5px 0px">
           	<table border="0" cellpadding="2" cellspacing="0" width="100%" >
            	<tr>
                	<td width="30" <?php if ( (isset($_SESSION['expand_list'])) && ($fet_toregions['idusrteamzone']==$_SESSION['expand_list'])) { echo "class=\"divcol\" style=\"font-weight:bold\"  "; } else { echo "class=\"tbl_sh\""; } ?> align="right">
                    <?php
					 if ( (isset($_SESSION['expand_list'])) && ($fet_toregions['idusrteamzone']==$_SESSION['expand_list']) ) {
					 ?>
                    <a href="#" onclick="document.forms['remove'].submit()" id="button_send_left"></a>
                    <?php
					}
					?>                     
                    </td>
                	<td height="20px" <?php if ((isset($_SESSION['expand_list'])) && ($fet_toregions['idusrteamzone']==$_SESSION['expand_list'])) { echo "class=\"divcol\" style=\"font-weight:bold\"  "; } else { echo "class=\"tbl_sh\" style=\"font-size:14px\""; } ?> >                
			                    <a style="text-decoration:none;" href="<?php echo $_SERVER['PHP_SELF'];?>?list_action=expand&amp;expand_list=<?php echo $fet_toregions['idusrteamzone'];?>" >
            			        <div><?php echo $fet_toregions['userteamzonename'];?></div>
			                    </a>                   
            			        </td >                    
                </tr>
            </table>
            </div>
            <?php
	if ( (isset($_SESSION['list_action'])) && ($_SESSION['list_action']=="expand") && (isset($_SESSION['expand_list'])) && ($_SESSION['expand_list']==$fet_toregions['idusrteamzone']) )
		{ //[002] //expand list
			mysql_select_db($database_connSystem, $connSystem);
			$query_sasi = "SELECT idusrrole,usrrolename,usrroledesc,usrname,utitle,fname,lname,acstatus,userteamzonename,idwfactors,allow_tskflow_jump FROM usrrole LEFT JOIN usrac ON usrrole.idusrrole=usrac.usrrole_idusrrole  INNER JOIN wfactors ON usrrole.idusrrole=wfactors.usrrole_idusrrole INNER JOIN usrteamzone ON usrrole.usrteamzone_idusrteamzone=usrteamzone.idusrteamzone WHERE usrteamzone.usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." AND wftskflow_idwftskflow=".$_SESSION['idflow']." AND usrteamzone.idusrteamzone=".$_SESSION['expand_list']." ORDER BY userteamzonename ASC,usrrolename ASC";
			$sasi = mysql_query($query_sasi, $connSystem) or die(mysql_error());
			$row_sasi = mysql_fetch_assoc($sasi);
			$totalRows_sasi = mysql_num_rows($sasi);
			
			if ($totalRows_sasi >0)
				{
				 
			?>
            <div>
            <table border="0" cellpadding="2" cellspacing="0" width="400" >
            <?php
			do {
			?>
                <tr>
                    <td width="30" class="tbl_data">
                    <input type="checkbox" value="<?php echo $row_sasi['idusrrole'];?>" name="role[]" />
                    </td>
                  <td class="tbl_data">
                    <?php echo $row_sasi['usrrolename']?>
                    <div style="color:<?php if ($row_sasi['acstatus']==1) { echo "#009900"; } else { echo "#ff0000"; } ?>">
                    <small><?php echo $row_sasi['utitle']." ".$row_sasi['lname']." , ".$row_sasi['fname'];?></small>
                    </div>
                    </td>
                    <td class="tbl_data">
                    <span title="<?php echo $row_sasi['utitle']." ".$row_sasi['lname']." , ".$row_sasi['fname'];?>"> <?php echo $row_sasi['usrname']?> </span> 
                    </td>
                    <td class="tbl_data">
                    <a href="<?php echo $_SERVER['PHP_SELF'];?>?record=<?php echo $row_sasi['idwfactors'];?>&amp;chkaction=<?php if ($row_sasi['allow_tskflow_jump']==1) { echo "0"; } else { echo "1"; }?>" id="button_check<?php if ($row_sasi['allow_tskflow_jump']==1) { echo "_on"; }?>"></a>
                    </td>
                </tr>
               <?php
			   	} while ($row_sasi = mysql_fetch_assoc($sasi));
			   ?>
            </table>
            </div>
            <?php
				} else {
				echo "<div class=\"msg_warning_small\">You have not added roles for this region</div>";
				}
			}//002
		} while ($fet_toregions=mysql_fetch_array($res_toregions));
	}
			?>
           <input type="hidden" name="formaction" value="remove" />
        </form>
      </td>
    </tr>
</table>