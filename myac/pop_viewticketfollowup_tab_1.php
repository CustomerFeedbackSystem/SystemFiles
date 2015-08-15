<?php
require_once('../assets_backend/be_includes/check_login_easy.php');

//query the first history with the WSP
$sql_history="SELECT wftasks.idwftasks,usrrole.usrrolename,usrteamzone.usrteam_idusrteam,usrac.utitle,usrac.lname,wftskstatustypes.wftskstatustype,wftskupdates.wftskupdate,wftskstatustypes.wftskstatuslbl,wftskupdates.createdon,wftasks.tktin_idtktin FROM wftskupdates
INNER JOIN usrrole ON wftskupdates.usrrole_idusrrole=usrrole.idusrrole
INNER JOIN usrteamzone ON usrrole.usrteamzone_idusrteamzone=usrteamzone.idusrteamzone 	
INNER JOIN usrac ON wftskupdates.usrac_idusrac=usrac.idusrac
INNER JOIN wftasks ON wftskupdates.wftasks_idwftasks=wftasks.idwftasks 	
INNER JOIN wftskstatustypes ON wftskupdates.wftskstatustypes_idwftskstatustypes=wftskstatustypes.idwftskstatustypes
WHERE wftasks.tktin_idtktin=".$_SESSION['tktid']." AND usrteamzone.usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." ORDER BY createdon DESC";
$res_history=mysql_query($sql_history);
$fet_history=mysql_fetch_array($res_history);
$num_history=mysql_num_rows($res_history);

//query also the first action by the customer
//get the ticket details and display them as the first history item at the bottom
$sql_tkt="SELECT senderphone,refnumber,tktdesc,timereported,tktchannelname,tktstatusname,tkttypename FROM tktin 
INNER JOIN tktchannel ON tktin.tktchannel_idtktchannel=tktchannel.idtktchannel
INNER JOIN tktstatus ON tktin.tktstatus_idtktstatus=tktstatus.idtktstatus
INNER JOIN tkttype ON tktin.tkttype_idtkttype=tkttype.idtkttype
WHERE idtktinPK=".$_SESSION['tktid']." ";
$res_tkt=mysql_query($sql_tkt);
$fet_tkt=mysql_fetch_array($res_tkt);
?>
<!--
<div class="title_header_blue">
<?php //echo $lbl_tkthistory;?>
</div>
-->
<div>
<table border="0" width="100%">

	<tr>
    	<td width="60%" valign="top" class="border_right ">
        <div class="table_header">
        <?php echo $lbl_ticketdetails;?>
        </div>
        <div style="padding:0px 0px 30px 0px">
        <table border="0" width="100%" cellpadding="2" cellspacing="0">
        	<tr>
            	<td width="30%" class="tbl_data">
				<?php echo $lbl_ticketno;?>
                </td>
              <td width="70%" class="tbl_data">
               <strong> <?php echo $fet_ov['refnumber'];?></strong>
               </td>
            </tr>
            <tr>
            	<td class="tbl_data">
				<?php echo $lbl_tktcat;?>
                </td>
                <td class="tbl_data">
                <strong> <?php echo $fet_ov['tktcategoryname'];?></strong>
                </td>
            </tr>
            <tr>
            	<td class="tbl_data">
				<?php echo $lbl_timereported;?>
                </td>
                <td class="tbl_data">
                <strong><?php echo date("D, M d, Y H:i",strtotime($fet_ov['timereported'])); ?></strong>
                </td>
            </tr>
            <tr>
            	<td class="tbl_data">
				<?php echo $lbl_location;?>
                </td>
                <td class="tbl_data">
                <strong><?php echo $fet_ov['locationname']; ?></strong>
                </td>
            </tr>
            <tr>
            	<td class="tbl_data">
				<?php echo $lbl_ticketnmsg;?>
                </td>
                <td class="tbl_data">
                <strong><?php echo $fet_ov['tktdesc']; ?></strong>
                </td>
            </tr>
        </table>
        </div>
        <div class="table_header">
        <?php echo $lbl_historywsp;?>
        </div>
        <div style="padding:5px 0px 10px 0px">
         <?php 
				if ($num_history>0)
					{
					do {
				?>
  <div class="bline" <?php 
// technocurve arc 3 php mv block2/3 start
echo " style=\"background-color:$mocolor\" onMouseOver=\"this.style.backgroundColor='$mocolor3'\" onMouseOut=\"this.style.backgroundColor='$mocolor'\"";
// technocurve arc 3 php mv block2/3 end
?>>
                	<table border="0" width="100%" cellpadding="1" cellspacing="0">
                    	<tr>
							<td width="160" align="left" bgcolor="#D4D0C8" class="text_small"><strong><?php echo date("D, M d, Y H:i",strtotime($fet_history['createdon'])); ?></strong></td>
							<td align="right" class="text_small">
							<?php echo $fet_history['wftskstatuslbl'];?>
							</td>
                      </tr>
                      <tr>
                      	<td class="text_small" title="<?php echo $fet_history['utitle']." ".$fet_history['lname'];?>">
                        <strong><?php echo $fet_history['usrrolename'];?></strong>
                        </td>
                      	<td class="text_small">
                        <?php
						//find the recepient of this task
						$sql_recepient="SELECT utitle,lname,usrrolename FROM wftasks
						INNER JOIN usrrole ON wftasks.usrrole_idusrrole=usrrole.idusrrole 
						INNER JOIN usrac ON usrrole.idusrrole=usrac.usrrole_idusrrole
						WHERE wftasks_idwftasks=".$fet_history['idwftasks']." LIMIT 1";
						$res_recepient=mysql_query($sql_recepient);
						$num_recepient=mysql_num_rows($res_recepient);
						$fet_recepient=mysql_fetch_array($res_recepient);
						
						if ($num_recepient >0)
							{
							echo "&raquo;&nbsp;&nbsp;&nbsp;<span style=\"font-weight:bold\" title=\"".$fet_recepient['utitle']." ".$fet_recepient['lname']."\">".$fet_recepient['usrrolename']."</span>";
							}
						?>
                        </td>
                      </tr>
                        <tr>
                        	<td colspan="4" class="text_body">
                            <?php echo $fet_history['wftskupdate'];?>
                            </td>
                        </tr>
                    </table>
                </div>               
                
                <?php 
// technocurve arc 3 php mv block3/3 start
if ($mocolor == $mocolor1) {
	$mocolor = $mocolor2;
} else {
	$mocolor = $mocolor1;
}
// technocurve arc 3 php mv block3/3 end
?>    
                <?php
					} while ($fet_history=mysql_fetch_array($res_history));					
				}
				?>
<div class="bline" <?php 
// technocurve arc 3 php mv block2/3 start
echo " style=\"background-color:$mocolor\" onMouseOver=\"this.style.backgroundColor='$mocolor3'\" onMouseOut=\"this.style.backgroundColor='$mocolor'\"";
// technocurve arc 3 php mv block2/3 end
?>>
<table border="0" width="100%" cellpadding="1" cellspacing="0">
                    	<tr>
                        	<td width="160" align="left" bgcolor="#D4D0C8" class="text_small"><strong><?php echo date("D, M d, Y H:i",strtotime($fet_tkt['timereported'])); ?></strong></td>
                            <td align="left" class="text_small">
                              <strong><?php echo "Customer - via ".$fet_tkt['tktchannelname'];?></strong> </td>
                          <td align="right" class="text_small">
                            <?php echo "New Ticket";?>                            </td>
                </tr>
                        <tr>
                        	<td colspan="4" class="text_body">
                            <?php echo "[".$fet_tkt['refnumber']."]&nbsp;".$fet_tkt['tktdesc'];?>
                            </td>
                        </tr>
          </table>
</div>
</div>
      </td>
        <td width="40%" valign="top">
        <div class="table_header">
        <?php echo $lbl_customerdetails;?>
        </div>
       <div>
       	<table border="0" width="100%" cellpadding="1" cellspacing="0">
        	<tr>
            	<td  class="tbl_data">
                <?php echo $lbl_fname;?>
                </td>
                <td  class="tbl_data">
                  <strong><?php echo $fet_ov['sendername'];?></strong> </td>
            </tr>
            	<tr>
            	<td  class="tbl_data">
              <?php echo $lbl_mobile;?>
                </td>
                <td  class="tbl_data">
                  <strong>0<?php echo substr($fet_ov['senderphone'],3,6);?>***</strong> </td>
            </tr>
        </table>
       </div>
       </td>
    </tr>
</table>
</div>