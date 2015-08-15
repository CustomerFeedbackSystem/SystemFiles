<?php
require_once('../assets_backend/be_includes/config.php'); //remove|comment this on production

//get the logo from user login session

//get the first zone which represents the HQ
$res_address=mysql_query("SELECT teamzonephone,teamzoneemail,physicaladdress,postaladdress FROM usrteamzone WHERE usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." ORDER BY idusrteamzone ASC LIMIT 1");
$fet_address=mysql_fetch_array($res_address);
?>

<table border="0" width="100%">
    <tr>
        <td>
        <?php
		if ( (isset($_SESSION['MVGitHub_logo'])) && (strlen($_SESSION['MVGitHub_logo'])>4) )
			{
			echo "<img src=\"../".$_SESSION['MVGitHub_logo']."\" border=\"0\" align=\"absmiddle\">";
			}
		?>
        </td>
        <td align="center">
        <div class="text_body_vlarge"><strong><?php echo $_SESSION['MVGitHub_acteam'];?></strong></div>
        <div class="text_body_large" ><?php echo $fet_address['postaladdress'];?> </div>
        <div class="text_body_large" ><?php echo $fet_address['physicaladdress'];?> </div>
        <div class="text_body_large" >TEL : <?php echo $fet_address['teamzonephone'];?> </div>
        <div class="text_body_large" >EMAIL : <?php echo $fet_address['teamzoneemail'];?> </div>
        <div class="text_body_vlarge"><strong>CUSTOMER COMPLAINT FORM</strong></div></td>
      <td>
       
        </td>
    </tr>
</table>
