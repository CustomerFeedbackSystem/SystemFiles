<?php
require_once('../assets_backend/be_includes/config.php');
require_once('../assets_backend/be_includes/check_login_easy.php');
?>

<form method="post" action="" name="send_msg">
<table border="0" width="100%" cellpadding="2" cellspacing="0">
<tr>
    	<td width="28%" height="40" align="right"  class="tbl_data" style="padding:0px 10px 0px 15px">From :</td>
    <td width="72%" height="40"  class="tbl_data">
      <input readonly="readonly" style="background-color:#CCCCCC" type="text" name="cegsubject" maxlength="250" size="40" value="<?php echo $_SESSION['WASReBSyS_userrole']; ?>">
      </td>
  </tr>
<tr>
    	<td height="40" align="right" class="tbl_sh"  style="padding:0px 10px 0px 15px">To : <a class="tooltip" href="#">[?]<span>You can only send the message to someone in the region concerned</span></a></td>
  <td height="40" class="tbl_sh">
      	<input onclick="hidetxt()" onblur="showtxt()" type="text" name="recepient_alt" id="recepient_alt" maxlength="45" value="Search a User by Name or Role" size="55" />
        </td>
  </tr>
  <tr>
    	<td width="28%" height="40" align="right"  class="tbl_data"  style="padding:0px 10px 0px 15px">Subject :</td>
      <td width="72%" height="40"  class="tbl_data">
      <input type="text" name="cegsubject" maxlength="250" size="70" value="<?php echo $fet_cat['tktcategoryname'];?> tickets - <?php echo $_SESSION['odue'];?> Days overdue - <?php echo $fet_reg['userteamzonename'];?>">
      </td>
  </tr>
    
<tr>
    	<td align="right" valign="top" class="tbl_sh"  style="padding:0px 10px 0px 15px">Message :</td>
        <td   valign="top" class="tbl_sh">
        <textarea cols="40" rows="6" class="cegmsg"></textarea>
        </td>
</tr>
    <tr>
    	<td  valign="top"></td>
        <td height="60"  valign="middle">
        <input type="submit" value="Send Message!">
        <input type="hidden" name="formaction" value="send">        </td>
  </tr>
</table>
</form>