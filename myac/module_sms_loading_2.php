<?php
require_once('../assets_backend/be_includes/check_login_easy.php');
?>
<link href="../assets_backend/css/style.css" rel="stylesheet" type="text/css">
<div>
<div class="tbl_sh">
To Top Up your SMS Account, please submit your receipt using the form below for verification &amp; activation
</div>
<div style="padding:15px 5px">
<form method="post" action="">
<table border="0" cellpadding="2" cellspacing="0">
<tr>
    	<td class="tbl_data">Batch No. </td>
        <td class="tbl_data"><strong>&lt;auto&gt;</strong></td>
      </tr>
	<tr>
    	<td class="tbl_data">Supplier </td>
        <td class="tbl_data">
        <select name="">
        <option value="">---</option>
        <option value="">Safaricom Limited</option>
        </select>
        </td>
        </tr>
        <tr>
    	<td class="tbl_data">Amount Paid (Ksh)</td>
        <td class="tbl_data"><input type="text" maxlength="20" size="20"></td>
        </tr>
        <tr>
    	<td class="tbl_data"> Total #SMS </td>
        <td class="tbl_data"><input type="text" maxlength="10" size="10"></td>
        </tr>
        <tr>
    	<td class="tbl_data">Receipt No. </td>
        <td class="tbl_data"><input type="text" maxlength="20" size="20"></td>
        </tr>
        <tr>
        	<td class="tbl_data">
            Submitted by
            </td>
            <td class="tbl_data">
          <?php
		  echo 
		  $_SESSION['MVGitHub_acname']."<br>".$_SESSION['MVGitHub_usrtitle']." ".$_SESSION['MVGitHub_usrfname']." 
		  ".$_SESSION['MVGitHub_usrlname']."<br>(".$_SESSION['MVGitHub_usremail'].")";?>
            </td>
        </tr>
        <tr>
        	<td class="tbl_data">
            Submitted On</td>
            <td class="tbl_data">
          <?php
		  echo date("D, M d, Y H:i",strtotime($timenowis));?>
            </td>
        </tr>
        <tr>
        	<td colspan="2">
            
            </td>
        </tr>
        <tr>
        	<td height="47"></td>
            <td>
            <a href="#" id="button_submit"></a>
            </td>
        </tr>
        
</table>
</form>
</div>