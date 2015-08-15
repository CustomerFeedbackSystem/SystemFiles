<table border="0" cellpadding="0" cellspacing="0">
                	<tr>
                    	<td class="text_body" align="right">
						<?php //echo $lbl_searchtasks;?>
                        <a href="#" style="text-decoration:none;" class="tooltip"><img src="../assets_backend/icons/help.gif" border="0" align="absmiddle" /><span><?php echo $msg_tip_tasksearch;?></span></a>
                        </td>
                        <td style="padding:0px; margin:0px; text-align:left">
                        <input value="<?php if (isset($_SESSION['searchbox'])) { echo $_SESSION['searchbox']; } ?>" type="text" maxlength="50" size="30" name="searchbox" class="small_field">
                        </td>
                        <td>
                        <?php
                        $res_region=mysql_query('SELECT idusrteamzone,userteamzonename FROM usrteamzone WHERE usrteam_idusrteam='.$_SESSION['MVGitHub_idacteam'].'');
						$fet_region=mysql_fetch_array($res_region);
						$num_region=mysql_num_rows($res_region);
						?>
                        <select name="search_region">
                        <option value="0">Any / All Regions</option>
                        <?php
						if ($num_region > 0)
							{
								do {
								?>
                                <option <?php if ( (isset($_SESSION['search_region'])) && ($_SESSION['search_region']==$fet_region['idusrteamzone']) ) { echo "selected=\"selected\""; } ?> value="<?php echo $fet_region['idusrteamzone'];?>"><?php echo $fet_region['userteamzonename'];?></option>
                                <?php
								} while ($fet_region=mysql_fetch_array($res_region));
							}
						?>
                        </select>
                        </td>
                        <td style="padding:2px; margin:0px; text-align:left">
                        <input type="hidden" value="5" name="parentviewtab" />
                        <a href="#" id="button_search"  onclick="document.forms['search_tasks'].submit()"></a>
                        </td>
                        <td>
                        
                        </td>
                    </tr>
                </table>