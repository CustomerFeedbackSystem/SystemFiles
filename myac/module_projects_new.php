<?php
require_once('../assets_backend/be_includes/check_login_easy.php');
?>

   <script src="http://api.mygeoposition.com/api/geopicker/api.js" type="text/javascript"></script>
    
    <script type="text/javascript">
        function lookupGeoData() {            
            myGeoPositionGeoPicker({
                startAddress     : 'Kenya, Nairobi',
                returnFieldMap   : {
                                     'lat' : '<LAT>',
                                     'lng' : '<LNG>',
                                     'projcity' : '<CITY>',   /* ...or <COUNTRY>, <STATE>, <DISTRICT>,
                                                                           <CITY>, <SUBURB>, <ZIP>, <STREET>, <STREETNUMBER> */
                                     'projlocation' : '<ADDRESS>'
                                   }
            });
        }
    </script>
<div>
	<div class="bg_section">
	<?php echo $fet_heading['modulename']; ?> &raquo; <?php echo $fet_heading['submodule']; ?>
	</div>
    <div>
    <?php
	if (isset($error)) { echo $error; }
	?>
    </div>
    <div>
    	<div class="tab_area">
            <span class="tab<?php if ( (isset($_SESSION['tabview_con'])) && ($_SESSION['tabview_con']==1)){ echo "_on"; } ?>"><a href="<?php $_SERVER['PHP_SELF'];?>?tabview=1"><?php echo $lbl_ovdetails;?></a></span>
            <span class="tab_off"><a href="#" style="color:#666666; text-decoration:none"><?php echo $lbl_projectmile;?></a></span>
            <span class="tab_off"><a href="#" style="color:#666666; text-decoration:none"><?php echo $lbl_docs;?></a></span>
           <!-- <span class="tab_off"><a href="#" style="color:#666666; text-decoration:none"><?php //echo $lbl_photovideo;?></a></span>-->
            <span class="tab_off"><a href="#" style="color:#666666; text-decoration:none"><?php echo $lbl_notes;?></a></span>
        </div>
	</div>
    <div>
    <form method="post" name="new_project" action="">
    	<table border="0" width="80%" cellpadding="3" cellspacing="0">
        	<tr>
            	<td class="tbl_data"><?php echo $lbl_projectname;?></td>
                <td class="tbl_data"><input type="text" name="projname" maxlength="100" size="35"></td>
            </tr>
            <tr>
            	<td class="tbl_data"><?php echo $lbl_projectcat;?></td>
                <td class="tbl_data">
                <select name="projcat">
                <option value="">----</option>
                <?php
				$sql_projcat="SELECT  idprojecttype,ptype FROM projecttype";
				$res_projcat=mysql_query($sql_projcat);
				$fet_projcat=mysql_fetch_array($res_projcat);
					do {
					echo "<option value=\"".$fet_projcat['idprojtype']."\">".$fet_projcat['ptype']."</option>";
					} while ($fet_projcat=mysql_fetch_array($res_projcat));
				?>
                </select>
                </td>
            </tr>
            <tr>
            	<td class="tbl_data" valign="top"><?php echo $lbl_description;?></td>
                <td class="tbl_data">
                <textarea cols="40" rows="4" name="projdesc"></textarea>
                </td>
            </tr>
            <tr>
            	<td class="tbl_data" valign="top"><?php echo $lbl_status;?></td>
                <td class="tbl_data">
                <select name="projstatus">
                	<option value="">----</option>
                    <?php
					$sql_projstatus="SELECT idprojectstatus,pstatus FROM projectstatus";
					$res_projstatus=mysql_query($sql_projstatus);
					$fet_projstatus=mysql_fetch_array($res_projstatus);
						do {
						echo "<option value=\"".$fet_projstatus['idprojectstatus']."\">".$fet_projstatus['pstatus']."</option>";
						} while ($fet_projstatus=mysql_fetch_array($res_projstatus));
					?>
                </select>
                </td>
            </tr>
            <tr>
                    	<td height="40" class="tbl_data">
                          <?php
						echo $lbl_datestart;
						?>
                         </td>
              <td height="40" class="tbl_data">
                       <input size="15" onClick="displayDatePicker('datestart');" name="datestart" style="background-color:#F5F5F5" type="text" id="datestart" value="" readonly="readonly"> 
                <img src="../assets_backend/btns/cal.gif" alt="Pick a date" width="30" height="30" border="0" align="absmiddle" onClick="displayDatePicker('datestart');">
&nbsp;&nbsp;                </td>
                  </tr>
                  <tr>
                    	<td height="40" class="tbl_data">
                          <?php
						echo $lbl_dateend;
						?>
                         </td>
                    <td height="40" class="tbl_data">
                       <input size="15" onClick="displayDatePicker('dateend');" name="dateend" style="background-color:#F5F5F5" type="text" id="dateend" value="" readonly="readonly"> 
                      <img src="../assets_backend/btns/cal.gif" alt="Pick a date" width="30" height="30" border="0" align="absmiddle" onClick="displayDatePicker('dateend');">
&nbsp;&nbsp;&nbsp;                      </td>
                  </tr>
                  <tr>
            	<td class="tbl_data" valign="top"><?php echo $lbl_location;?></td>
                <td class="tbl_data">
                <input type="text" value="" name="projlocation" id="projlocation" maxlength="100" size="40"> : <?php echo $lbl_town_city;?> <input type="text" value="" name="projcity" id="projcity" maxlength="100" size="20">
                <div class="tbl_sh">
                <?php echo $lbl_mapcoord;?>
                </div>
                <div>
                <table border="0" cellpadding="0" cellspacing="0">
                	<tr>
                    	<td class="text_small">
						<?php echo $lbl_latitude;?>
                        </td>
                        <td>
                        <input type="text" value="" name="lat" id="lat">
                        </td><td><button type="button" onclick="lookupGeoData();">GeoPicker</button></td>
                    </tr>
                    <tr>
                    	<td class="text_small">
						<?php echo $lbl_longitude;?>
                        </td>
                        <td>
                        <input type="text" value="" name="lng" id="lng">
                        </td>
                        <td>
						
                        </td>
                    </tr>
                    
                </table>
                </div>
                </td>
            </tr>
             <tr>
                    	<td height="40" class="tbl_data">
                          <?php
						echo $lbl_updatefreq;
						?>
                         </td>
                    <td height="40" class="tbl_data">
                      <input onkeyup="res(this,numb);" type="text" value="" size="4" maxlength="2" name="tat" /> 
                    <select name="tat_cat">
                    <option  value="">---</option>
                    <option  value="Hours">Hours</option>
                    <option  value="Days">Days</option>
                    </select></td>
                  </tr>
                  <tr>
                  	<td>
                    </td>
                    <td height="50">
                    <table border="0" style="margin:15px 10px 5px 0px" cellpadding="0" cellspacing="0">
                        	<tr>
                            	<td>
                                <a href="#" onclick="document.forms['project_new'].submit()" id="button_save"></a>                                </td>
                                <td style="padding:0px 0px 0px 10px">
                                <input type="hidden" value="save" name="formaction" />
                               <a href="<?php echo $_SERVER['PHP_SELF'];?>" id="button_cancel" onclick="return confirm('<?php echo $msg_prompt_sure_cancel;?>');"></a>                                </td>
                            </tr>
                        </table>
                    </td>
                  </tr>
        </table>
    </form>
    </div>
</div>
    