<?php
require_once('../assets_backend/be_includes/config.php');
require_once('../assets_backend/be_includes/check_login_easy.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css_report_print.css" rel="stylesheet" type="text/css" media="print" />
<link href="css_report.css" rel="stylesheet" type="text/css" />

<title>Form 1</title>
</head>
<body>
<?php
if (isset($_GET['ticketnumber']))
	{
//cleanup
$print_ticket=mysql_escape_string(trim($_GET['ticketnumber']));

//pull relevant data from the DB
$sql_ticketinfo="SELECT usrteamzone.userteamzonename,
tktin.waterac,
tktin.sendername,
tktin.senderemail,
tktin.senderphone,
tktin.refnumber,
tktin.tktdesc,
tktin.timereported,
tktcategory.tktcategoryname,
usrac.fname,
tktin.createdon
FROM tktin
INNER JOIN usrteamzone ON tktin.usrteamzone_idusrteamzone=usrteamzone.idusrteamzone
INNER JOIN tktcategory ON tktin.tktcategory_idtktcategory=tktcategory.idtktcategory
INNER JOIN usrac ON tktin.createdby=usrac.idusrac
WHERE idtktinPK=".$print_ticket." AND tktin.createdby!=2";
$res_ticketinfo=mysql_query($sql_ticketinfo);
$fet_ticketinfo=mysql_fetch_array($res_ticketinfo);
$num_ticketinfo=mysql_num_rows($res_ticketinfo);
//echo $sql_ticketinfo;
	if ($num_ticketinfo > 0 ) //if matching ticket found that is private, then go ahead
		{
?>
<div class="hidden" style="font-family:Arial, Helvetica, sans-serif;  width:800px; background-color:#FFFFFF;">
<table border="0" width="100%">
	<tr>
    	<td>
        <a href="#" onclick="window.print()"><img border="0" align="absmiddle" src="../assets_backend/btns/print_small.gif" /> Print this Page</a>
        </td>
        <td align="right">
        <a href="#" onClick="parent.tb_remove();">Close Window [X]</a>
		</td>
	</tr>
</table>
</div>
<div style=" width:650px;  padding:5px" class="border">
	<table border="0" cellpadding="4" cellspacing="0" width="650"> 
    	<tr>
        	<td height="30" colspan="2" align="center">
            <?php require_once('report_header.php'); ?>
            </td>
   	  </tr>
        <tr>
        	<td class="border_top_thick" >
            <div class="text_body_mod">1. CUSTOMER DETAILS</div>            </td>
            <td class="border_top_thick" align="right">
            <div class="text_body_mod"> TICKET NO : <span class="text_data"><?php echo $fet_ticketinfo['refnumber'];?></span></div>
            </td>
        </tr>
        <tr>
        	<td height="131" colspan="2" valign="top">
       	  <table border="0" width="100%" cellpadding="2" cellspacing="0">
<tr>
                    	<td width="33%" valign="top" class="border_top_right">
                        <div class="text_body_mod">Region</div>
                        <div class="text_data"><?php echo $fet_ticketinfo['userteamzonename'];?></div>                        </td>
                        <td width="27%" valign="top" class="border_top_right">
                        <div class="text_body_mod">Account No.</div>
                        <div class="text_data"><?php echo $fet_ticketinfo['waterac'];?></div>                      </td>
                     
                      <td width="40%" valign="top" class="border_top_right">
                        <div class="text_body_mod"> Ticket No.</div>
                        <div class="text_data"><?php echo $fet_ticketinfo['refnumber'];?></div>            </td>
            </tr>
                    <tr>
                    	<td colspan="2" valign="top" class="border_top_right">
                       <div class="text_body_mod">Name of Customer</div>
                        <div class="text_data"><?php echo $fet_ticketinfo['sendername'];?></div>                      </td>
                      <td valign="top" class="border_top_right">
                       	<div class="text_body_mod">Tel No.</div>
                        <div class="text_data"><?php echo $fet_ticketinfo['senderphone'];?></div>                      </td>
                     
            </tr>
                </table>          </td>
      </tr>
        
        <tr>
        	<td colspan="2" class="border_top_thick">
            <div class="text_body_large">2. PARTICULARS OF THE COMPLAINT</div>            </td>
        </tr>
        <tr>
        	<td height="80" colspan="2" class="border_top" valign="top">
            <div class="text_body_mod">Complaint Type : <span class="text_data"><?php echo $fet_ticketinfo['tktcategoryname'];?></span></div>
             <div class="text_body_mod">Complaint Details</div>
             <div class="text_data">
             <?php echo $fet_ticketinfo['tktdesc'];?>
             </div>
            </td>
      	</tr>
        <tr>
        	<td  colspan="2" class="border_top_thick">
            <div class="text_body_large"></div>            </td>
        </tr>
        <tr>
        	<td height="63" colspan="2" valign="top">
       		  <table border="0" width="100%" cellpadding="2" cellspacing="0">
           	  		<tr>
                    	<td class="border_top_right" valign="top">
                        <div class="text_body_small">You Were Served By </div>
                        <div class="text_data">
                        <?php echo $fet_ticketinfo['fname'];?>
                        </div>                      </td>
                        <td class="border_top_right" valign="top">
                        <div class="text_body_small">Date &amp; Time of Service</div>
                        <div class="text_data">
						<?php echo date("D, M d, Y H:i",strtotime($fet_ticketinfo['createdon'])); ?>
                        </div>
                        </td>
                    </tr>
                </table>          </td>
      </tr>
      <tr>
      	<td colspan="2" style="background-color:#E4E4E4">&nbsp;</td>
      </tr>
    </table>
</div>
<?php
	} else {
	echo "<div class=\"msg_warning\">Record Not Found!</div>";
	}
} else {
	echo "<div class=\"msg_warning\">Error! Link invalid</div>";
	}
?>
</body>
</html>
