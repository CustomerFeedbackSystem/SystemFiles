<?php
require_once('../assets_backend/be_includes/config.php');
require_once('../assets_backend/be_includes/check_login_easy.php');

$delegate_to=intval($_GET['recepientId']);

if ($delegate_to > 0)
	{
//query the db see if this users level is higher or equal to the recepient
$sql_lvl="SELECT idusrac,usrname,idusrrole,usrrolename,fname,lname,joblevel,utitle FROM usrac 
		INNER JOIN usrrole ON usrac.usrrole_idusrrole=usrrole.idusrrole 
		WHERE usrrole.idusrrole=".$delegate_to." LIMIT 1";	
$res_lvl=mysql_query($sql_lvl);		
$fet_lvl=mysql_fetch_array($res_lvl);

if ($fet_lvl['joblevel'] <=$_SESSION['MVGitHub_joblevel']) //only if the Job Level am passing to is Higher or Equal to mine
	{
		echo "<table border=\"0\">
			<tr>
				<td width=\"226px\" style=\"padding:0px 0px 0px 2px; margin:0px\" class=\"tbl_data\">
				<strong>Delegation Key <a href=\"#\" 
				style=\"text-decoration:none;\" class=\"tooltip\">
				<img src=\"../assets_backend/icons/help.gif\" border=\"0\" 
				align=\"absmiddle\" />
				<span>You need to request ".$fet_lvl['utitle']." ".$fet_lvl['fname']." ".$fet_lvl['lname']." for this Key</span></a> :</strong>
				</td>
				<td style=\"padding:0px 0px 0px 2px; margin:0px; \"  bgcolor=#ffffcc>
				
				 <div class=\"border_thin\" style=\"padding:3px\">
					   <div>
					   <input type=\"text\" maxlength=\"6\" onkeyup=\"this.value=this.value.toUpperCase();\"  value=\"\" tabindex=\"3\" name=\"delegation_key\" size=\"10\" />
					   </div>
					   <div class=\"text_small\">
					   <strong>
					   Please request ".$fet_lvl['utitle']." ".$fet_lvl['fname']." ".$fet_lvl['lname']." for this Key
					   </strong>
					   </div>
				  </div>			
				</td>
			</tr>
		</table>";
		}
	}
?>