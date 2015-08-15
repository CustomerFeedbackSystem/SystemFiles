<table border="0" cellpadding="0" cellspacing="0">
                	<tr>
                    	<td class="text_body" align="right">
						<?php //echo $lbl_searchtasks;?>
                        </td>
                        <td style="padding:0px; margin:0px; text-align:left">
                        <?php
						//tasks in my c/o active state
						$res_actors=mysql_query("SELECT idwftasks_co,co_status,validation_code,usrphone,fname,lname,validation_code_sent,usrrole_idusrrole FROM wftasks_co 
						INNER JOIN usrac ON wftasks_co.idusrrole_owner=usrac.usrrole_idusrrole WHERE idusrrole_acting=".$_SESSION['MVGitHub_iduserrole']." AND co_status<2");
						$fet_actors=mysql_fetch_array($res_actors);
						?>
                        <select name="search_actor">
                        <option value="">-- Task Owner --</option>
						<?php
                        do {
							echo "<option value=\"".$fet_actors['usrrole_idusrrole']."\">".$fet_actors['fname']." ".$fet_actors['lname']."</option>";
							} while ($fet_actors=mysql_fetch_array($res_actors));
						?>
                        </select>
                        </td>
                        <td>
                        <?php
                        $res_status=mysql_query('SELECT idwftskstatustypes,wftskstatustype,wftskstatuslbl FROM wftskstatustypes WHERE is_visible=1 ORDER BY listorder ASC LIMIT 4');
						$fet_status=mysql_fetch_array($res_status);
						$num_status=mysql_num_rows($res_status);
						?>
                        <select name="search_status">
                        <option value="0">-- Task Status --</option>
                        <?php
						if ($num_status > 0)
							{
								do {
								?>
                                <option <?php if ( (isset($_SESSION['search_status'])) && ($_SESSION['search_status']==$fet_status['idwftskstatustypes']) ) { echo "selected=\"selected\""; } ?> value="<?php echo $fet_status['idwftskstatustypes'];?>"><?php echo $fet_status['wftskstatuslbl'];?></option>
                                <?php
								} while ($fet_status=mysql_fetch_array($res_status));
							}
						?>
                        </select>
                        </td>
                        <td style="padding:2px; margin:0px; text-align:left">
                        <input type="hidden" value="5" name="parentviewtab" />
                        <a href="#" id="button_search"  onclick="document.forms['search_tasks_co'].submit()"></a>
                        </td>
                        <td>
                        
                        </td>
                    </tr>
                </table>