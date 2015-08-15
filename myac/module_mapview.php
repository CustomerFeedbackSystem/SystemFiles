<div>
	<div class="bg_section">
    <?php echo $fet_heading['modulename']; ?> &raquo; <?php echo $fet_heading['submodule']; ?>
    </div>
    <div>
    <div style="margin:10px">
    <div style="background-color:#E8E8E8; margin:10px 0px 10px 0px">
                    <form method="post" name="filter" action="<?php $_SERVER['PHP_SELF'];?>" >
                 <table border="0" cellpadding="0" cellspacing="0">
                                    	<tr>
                                        	<td class="text_body">
                                            <?php echo $lbl_ticketfilter;?>:
                                            </td>
                                          <td>
                                           
                                             <select  class="small_field" name="map_filter_cat">
                                            <option value="">--<?php echo $lbl_tktcat;?>--</option>
                                            <option value="-1">>> All Categories << </option>
                                            <?php
											$sql_cats="SELECT idtktcategory,tktcategory_idtktcategory,tktcategoryname FROM tktcategory WHERE tktcategory_idtktcategory=0 ORDER BY tktcategoryname ASC";
											$res_cats=mysql_query($sql_cats);
											$num_cats=mysql_num_rows($res_cats);
											$fet_cats=mysql_fetch_array($res_cats);
												do {
												echo "<option ";
												
												echo " value=\"".$fet_cats['idtktcategory']."\">".$fet_cats['tktcategoryname']."</option>";
												} while ($fet_cats=mysql_fetch_array($res_cats));
											?>
                                            </select>
                                             <select class="small_field" name="map_filter_chn">
                                            <option value="">--Channel--</option>
                                            <option value="-1"> >> All Channels << </option>
                                            <?php
											$sql_channel="SELECT idtktchannel,tktchannelname FROM tktchannel ";
											$res_channel=mysql_query($sql_channel);
											$num_channel=mysql_num_rows($res_channel);
											$fet_channel=mysql_fetch_array($res_channel);
												do {
												echo "<option value=\"".$fet_channel['idtktchannel']."\">".$fet_channel['tktchannelname']."</option>";
												} while ($fet_channel=mysql_fetch_array($res_channel));
											?>
                                            </select>
                                            </td>
                                            <td>
                                            <a href="#" onclick="document.forms['filter'].submit()" id="button_go_2"></a>
                                            <input type="hidden" value="2" name="parentviewtab" />
                                            <input type="hidden" value="4" name="mod" />
                                            <input type="hidden" value="6" name="submod" />
                                            <input type="hidden" value="view_submod" name="uction" />

                                            </td>
                                        </tr>
                                    </table>
                 </form>

    </div>
	<div id="map" style="width: 800px; height: 700px"></div>     
    </div>
    </div>
</div>    