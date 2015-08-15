<div>
<?php
require_once('../assets_backend/be_includes/check_login_easy.php');
//show the accordion sub menus if this user has permission to access them...
if (isset($_SESSION['sec_mod']))
{
$sql_submodule = "SELECT idsyssubmodule,sysmodule_idsysmodule,submodule FROM syssubmodule 
INNER JOIN systemprofileaccess ON syssubmodule.idsyssubmodule=systemprofileaccess.syssubmodule_idsyssubmodule 
WHERE permview=1 AND syssubmodule.submod_status=1 AND sysmodule_idsysmodule=".$_SESSION['sec_mod']." AND systemprofileaccess.sysprofiles_idsysprofiles=".$_SESSION['MVGitHub_iduserprofile']."  ORDER BY listorder ASC ";
$res_submodule = mysql_query($sql_submodule);
$num_submodule = mysql_num_rows($res_submodule);
$fet_submodule = mysql_fetch_array($res_submodule);
//echo $sql_submodule;
}


if ( $_SESSION['sec_mod']==$fet_submodule['sysmodule_idsysmodule'])
	{ 

	if ($num_submodule > 0) //($fet_submodule['idsubmodule']!=$_SESSION['sec_mod']) &&
		{
//echo $sql_submodule;
			do {
			echo "<div ";
		
				if ($_SESSION['sec_submod']==$fet_submodule['idsyssubmodule']) { 
				echo " class=\"leftmenu_on\" style=\"color:#1D4F81\" "; 
				} else { 
				echo " class=\"leftmenu\"  style=\"color:#1D4F81\" ";
				}
			
			echo ">\r\n";
		//	echo "<a href=".$_SERVER['PHP_SELF']."?view=".$fet_submodule['idsubmodule']."&amp;page=".$_SESSION['CFCMS_page']."&amp;mod=".$_SESSION['sec_mod']."&amp;submod=".$fet_submodule['idsubmodule']."&uction=view_submod";
			echo "<a href=\"".$_SERVER['PHP_SELF']."?view=".$fet_submodule['idsyssubmodule']."&amp;page=&amp;mod=".$fet_submodule['sysmodule_idsysmodule']."&amp;submod=".$fet_submodule['idsyssubmodule']."&uction=view_submod";
			echo "\"><div>".$fet_submodule['submodule']."";
				
				//if the submodule is tasks in, then display any new tasks for this user
				if ($fet_submodule['idsyssubmodule']==2)
					{
		/*			$sql_countin="SELECT count(*) as newtsks FROM wftasks WHERE usrrole_idusrrole=".$_SESSION['MVGitHub_iduserrole']." AND wftskstatustypes_idwftskstatustypes=0";
					$res_countin=mysql_query($sql_countin);
					$fet_countin=mysql_fetch_array($res_countin);
					
					if ($fet_countin['newtsks']) 
						{
						$tsksin_counted=1;
						
	*/

//CREATE EXCEPTION FOR BATCHES TO AVOID JS ERROR
if ($_SESSION['sec_submod']!="299999999")
	{
	
	echo "<script type=\"text/javascript\">
		refreshdiv_1_1();
		</script>";
		echo "<span name=\"div_1_1\" id=\"div_1_1\"></span>";
	}
		//				echo "<span class=\"box_count\" >".$fet_countin['newtsks']."</span>";
		//				}
					}
				
			echo "</div></a>";//DISPLAY THE MAIN SUB MODULE LINK
			echo "</div>\r\n";
			//echo "dsdfsfd";
			} while ($fet_submodule = mysql_fetch_array($res_submodule)); //loop them sub modules
		} //end if
	}


//display random system usage tips when there is nothing on the left menu / submodule
if ($num_submodule < 2) //($fet_submodule['idsubmodule']!=$_SESSION['sec_mod']) &&
		{
		echo "<div style=\"padding:10px 30px 10px 10px \">";
		echo "<div class=\"text_body\" style=\"padding:10px 10px 0px 8px;\">".$lbl_tip."</div>";
		$sql_tip="SELECT tip FROM tips ORDER BY RAND() LIMIT 1";
		$res_tip=mysql_query($sql_tip);
		$fet_tip=mysql_fetch_array($res_tip);
		echo "<div class=\"tips_area\">".$fet_tip['tip']."</div>";
		echo "</div>";
		}

?>
<!--<div style="padding:40px 0px 40px 0px">
<a href="#" id="button_submittest" onclick="tb_open_new('pop_testfeedback.php?title=Feedback&amp;&amp;tabview=1&amp;keepThis=true&amp;TB_iframe=true&amp;height=460&amp;width=800')"></a>
</div>-->
</div>