<?php
require_once('../assets_backend/be_includes/config.php');
require_once('../assets_backend/be_includes/check_login_easy.php');
	if (isset($_GET['tktcat']))
	{
	$tktcat=intval($_GET['tktcat']);
	} else if (isset($_POST['tktcat'])) {
	$tktcat=intval($_POST['tktcat']);
	}
//echo $tktcat;
//exit;
//$tktcat=2;
if ($tktcat > 0)
//store this value in a session
$_SESSION['tktcat']=$tktcat;
	{
?>

<table border="0" width="100%" cellpadding="2" cellspacing="0">
			<tr>
            	<td colspan="2">
                <!-- START EXTRA FORMS LOAD HERE -->
                    <?php
                     //DISPLAY THE FORM FOR THIS TASK		 
					 //the dataform is determined by
					 //a) userprofileid
					 //b) category of task
					$sql_formdata="SELECT idwfprocassetsaccess, assetname, perm_read, perm_write, perm_required, wfprocassets.wfprocdtype_idwfprocdtype, idwfprocassets, wfprocassetsgrouplbl,sysprofiles_idsysprofiles,wfprocassetsaccess.wfprocforms_idwfprocforms
					FROM wfprocassetsaccess
					INNER JOIN wfprocassets ON wfprocassetsaccess.wfprocassets_idwfprocassets = wfprocassets.idwfprocassets
					INNER JOIN wfprocassetsgroup ON wfprocassets.wfprocassetsgroup_idwfprocassetsgroup = wfprocassetsgroup.idwfprocassetsgroup
					INNER JOIN wfprocforms_cats ON wfprocassetsaccess.wfprocforms_idwfprocforms = wfprocforms_cats.wfprocforms_idwfprocforms 
					WHERE sysprofiles_idsysprofiles=".$_SESSION['MVGitHub_iduserprofile']." AND wfprocforms_cats.tktcategory_idtktcategory=".$tktcat." AND wfprocassetsaccess.perm_read=1 ORDER BY wfprocassetsgrouplbl ASC,ordering ASC";
		/*			 $sql_formdata="SELECT idwfprocassetsaccess,assetname,perm_read,perm_write,perm_required,wfprocassets.wfprocdtype_idwfprocdtype,idwfprocassets,wfprocassetsgrouplbl FROM wfprocassetsaccess 
					 INNER JOIN wfprocassets ON wfprocassetsaccess.wfprocassets_idwfprocassets=wfprocassets.idwfprocassets
					 INNER JOIN wfprocassetsgroup ON wfprocassets.wfprocassetsgroup_idwfprocassetsgroup=wfprocassetsgroup.idwfprocassetsgroup
					 WHERE wftskflow_idwftskflow=".$wftskflow." AND wfprocassetsaccess.perm_read=1 ORDER BY wfprocassetsgrouplbl ASC,ordering ASC";
		*/			 //echo $sql_formdata;
					 $res_formdata=mysql_query($sql_formdata);
					 $num_formdata=mysql_num_rows($res_formdata);
					 $fet_formdata=mysql_fetch_array($res_formdata);
					//echo $sql_formdata;
					 $lastTFM_nest = ""; //for nesting
					//echo $sql_formdata;
//$fet_formdata['perm_write']
				 if ($num_formdata > 0)
					{ //[001]
					//process if form fields are required
			
				 echo "<input type=\"hidden\" name=\"formdata_available\" value=\"1\">";
				 
			$dmn=1;

			do 	{
				$TFM_nest = $fet_formdata['wfprocassetsgrouplbl'];
				
				if ($lastTFM_nest != $TFM_nest) 
					{ 
					$lastTFM_nest = $TFM_nest; 
					
					 if ($dmn>1)
						{
						echo "</div>";
						}
					?>	
            
                    <a href="#" style="text-decoration:none" rel="toggle[dataform<?php echo $dmn;?>]" data-openimage="../assets_backend/btns/btn_collapse.gif" data-closedimage="../assets_backend/btns/btn_expand.gif">
                    <div class="divcol">
                    <img src="../assets_backend/btns/btn_collapse.gif" border="0" align="absmiddle" /> <?php echo $fet_formdata['wfprocassetsgrouplbl'];?>                    </div>
                    </a>     
                    
                	<div id="dataform<?php echo $dmn;?>"> 
		            <?php 

					$dmn=$dmn+1;
					} //End of Basic-UltraDev Simulated Nested Repeat?>
                                
                    <table width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                        <td width="25%" class="tbl_data">
                        <?php
                        if ($fet_formdata['perm_required']==1) 
                        {
                        echo $lbl_asterik;
                        }
                        echo $fet_formdata['assetname'];
                        ?>                        </td>
                      <td class="tbl_data">
                        <?php
						

						//this is an insert only mode as there is no preceeding task at this point
						$transaction="INSERT";
    
                                                 
                         //this is a text box
                         if ($fet_formdata['wfprocdtype_idwfprocdtype']==1) 
                            {
                            //check permissions
                        //	echo $fet_formdata['perm_write'];
                       
                            
                            echo "<input type=\"text\"  ";
                            //check if it is a post
                                if (isset($_POST['item_'.$fet_formdata['idwfprocassetsaccess'].'']))
                                    {
                                    echo " value=\"".$_POST['item_'.$fet_formdata['idwfprocassetsaccess'].'']."\" ";
                                    } 
                                //highlight document show if error
                            //	if (isset($error."_".$fet_formdata['idwfprocassetsaccess']))
                                if ( (isset($_POST['required_'.$fet_formdata['idwfprocassetsaccess'].''])) && ($_POST['required_'.$fet_formdata['idwfprocassetsaccess'].'']==1) &&  ($_POST['item_'.$fet_formdata['idwfprocassetsaccess'].'']=="") )
                                    {
                                    echo " style=\"border:1px solid #ff0000\" ";
                                    }
                                                        
                            echo " name=\"item_".$fet_formdata['idwfprocassetsaccess']."\" maxlength=\"50\">";
                            
                            echo "<input type=\"hidden\" name=\"required_".$fet_formdata['idwfprocassetsaccess']."\" value=\"".$fet_formdata['perm_required']."\">\r\n
                            <input type=\"hidden\" name=\"transtype_".$fet_formdata['idwfprocassetsaccess']."\" value=\"".$transaction."\" > \r\n
                            <input type=\"hidden\" name=\"itemtype_".$fet_formdata['idwfprocassetsaccess']."\" value=\"".$fet_formdata['wfprocdtype_idwfprocdtype']."\">
                          
                            ";
                            }
                            
                        //select menu	
                        if ($fet_formdata['wfprocdtype_idwfprocdtype']==2) 
                            {
							
							                                                   
                            $sql_choices="SELECT idwfprocassetschoice,assetchoice FROM wfprocassetschoice WHERE wfprocassets_idwfprocassets=".$fet_formdata['idwfprocassets']."";
                            $res_choices=mysql_query($sql_choices);
                            $fet_choices=mysql_fetch_array($res_choices);
                        //echo $sql_choices;	
                            echo "<select name=\"item_".$fet_formdata['idwfprocassetsaccess']."\" ";
                                
                                if ( (isset($_POST['required_'.$fet_formdata['idwfprocassetsaccess'].''])) && ($_POST['required_'.$fet_formdata['idwfprocassetsaccess'].'']==1) &&  ($_POST['item_'.$fet_formdata['idwfprocassetsaccess'].'']=="") )
                                    {
                                    echo " style=\"border:1px solid #ff0000\" ";
                                    }
                            
                            echo "  >";
                            //get the select options data
                            echo "<option value=\"\" >---</option>";
                                do {
                                    
                                    echo "<option ";
                                        //select the default if there is a value
                                        if ( (isset($fet_data['idwfassetsdata'])) && ($fet_data['wfprocassetschoice_idwfprocassetschoice']==$fet_choices['idwfprocassetschoice']) )
                                            {
                                            echo " selected=\"selected\" ";
                                            } 
                                    echo " value=\"".$fet_choices['idwfprocassetschoice']."\">".$fet_choices['assetchoice']."</option>";
                                } while ($fet_choices=mysql_fetch_array($res_choices));
                            echo "</select>";						
                            
                                                                             
                                echo "<input type=\"hidden\" name=\"required_".$fet_formdata['idwfprocassetsaccess']."\" value=\"".$fet_formdata['perm_required']."\">\r\n
                                <input type=\"hidden\" name=\"transtype_".$fet_formdata['idwfprocassetsaccess']."\" value=\"".$transaction."\" > \r\n
                                <input type=\"hidden\" name=\"itemtype_".$fet_formdata['idwfprocassetsaccess']."\" value=\"".$fet_formdata['wfprocdtype_idwfprocdtype']."\">
                              
                                ";
                            }
                        
                        //file upload
                        if ($fet_formdata['wfprocdtype_idwfprocdtype']==3) 
                            {
                           
							 //show the following only if you have write permissions otherwise disable	
                            echo "<input type=\"file\"   ";
                            
                            if ( (isset($_POST['required_'.$fet_formdata['idwfprocassetsaccess'].''])) && ($_POST['required_'.$fet_formdata['idwfprocassetsaccess'].'']==1) &&  ($_POST['item_'.$fet_formdata['idwfprocassetsaccess'].'']=="") )
                                    {
                                    echo " style=\"border:1px solid #ff0000\" ";
                                    }
                            
                            echo " name=\"item_".$fet_formdata['idwfprocassetsaccess']."\" size=\"10\"  >";
                            echo "<span style=\"cursor:pointer;color:red;\" onclick=\"document.task.item_".$fet_formdata['idwfprocassetsaccess'].".value=''\">unselect</span>";
                            
                            echo "<input type=\"hidden\" name=\"required_".$fet_formdata['idwfprocassetsaccess']."\" value=\"".$fet_formdata['perm_required']."\">\r\n
                                <input type=\"hidden\" name=\"transtype_".$fet_formdata['idwfprocassetsaccess']."\" value=\"".$transaction."\" > \r\n
                                <input type=\"hidden\" name=\"itemtype_".$fet_formdata['idwfprocassetsaccess']."\" value=\"".$fet_formdata['wfprocdtype_idwfprocdtype']."\">
                                ";
                            
                            
                            }
                            
                        //checkbox
                        if ($fet_formdata['wfprocdtype_idwfprocdtype']==4) 
                            {
							
						
							echo "<label for=\"".$fet_formdata['idwfprocassetsaccess']."\">";
							
							//check if the persion has permission to edit the checkbox to know what to show 
						                           		 
								 echo "<input type=\"checkbox\" ";
                                echo " name=\"item_".$fet_formdata['idwfprocassetsaccess']."\" id=\"".$fet_formdata['idwfprocassetsaccess']."\" value=\"1\"> <small>( click to select )</small>";
                            
					
							
                            
                            echo "<input type=\"hidden\" name=\"required_".$fet_formdata['idwfprocassetsaccess']."\" value=\"".$fet_formdata['perm_required']."\">\r\n
                                <input type=\"hidden\" name=\"transtype_".$fet_formdata['idwfprocassetsaccess']."\" value=\"".$transaction."\" > \r\n
                                <input type=\"hidden\" name=\"itemtype_".$fet_formdata['idwfprocassetsaccess']."\" value=\"".$fet_formdata['wfprocdtype_idwfprocdtype']."\">
                       
                                ";
                            echo "</label>";
                            }
                        
                        
                        //yes no questions
                        if ($fet_formdata['wfprocdtype_idwfprocdtype']==5) 
                            {	
    						
								
                            	echo "<label for=\"radio_1\"><input id=\"radio_1\" ";
                          		 echo " type=\"radio\" value=\"YES\" name=\"item_".$fet_formdata['idwfprocassetsaccess']."\"><strong> YES </strong></label>";
                           		 echo "<span style=\"padding:0px 15px 0px 15px\"></span>";
								                           
							
								echo "<label for=\"radio_2\"><input id=\"radio_2\" type=\"radio\" ";
    	                     
                    	        echo " value=\"NO\" name=\"item_".$fet_formdata['idwfprocassetsaccess']."\"><strong> NO </strong></label>";
								
    
                                echo "<input type=\"hidden\" name=\"required_".$fet_formdata['idwfprocassetsaccess']."\" value=\"".$fet_formdata['perm_required']."\">\r\n
                                <input type=\"hidden\" name=\"transtype_".$fet_formdata['idwfprocassetsaccess']."\" value=\"".$transaction."\" > \r\n
                                <input type=\"hidden\" name=\"itemtype_".$fet_formdata['idwfprocassetsaccess']."\" value=\"".$fet_formdata['wfprocdtype_idwfprocdtype']."\">
                                ";
                            
                            }
                        
                        //date only
                        if ($fet_formdata['wfprocdtype_idwfprocdtype']==6) 
                            {
							
						
                                $readonly_off="<script language=\"javascript\">
                                $('#item_".$fet_formdata['idwfprocassetsaccess']."').datepicker({
                                    controlType: 'select',
                                    dateFormat: 'dd/mm/yy'
                                });
                                </script>";
								$readonly_click="onClick=\"datetimepicker('item_".$fet_formdata['idwfprocassetsaccess']."');\"";
								$readonly_style=" ";
                                
							
							
                            echo "<input size=\"10\" ";
                           echo " value=\"".$today."\" ";
                              
                            
                            //display if value missing
                            if ( (isset($_POST['required_'.$fet_formdata['idwfprocassetsaccess'].''])) && ($_POST['required_'.$fet_formdata['idwfprocassetsaccess'].'']==1) &&  ($_POST['item_'.$fet_formdata['idwfprocassetsaccess'].'']=="") )
                                    {
                                    echo " style=\"border:1px solid #ff0000\" ";
                                    }
                            
                            echo $readonly_click." ".$readonly_style."  name=\"item_".$fet_formdata['idwfprocassetsaccess']."\" type=\"text\" id=\"item_".$fet_formdata['idwfprocassetsaccess']."\"  >" ;					
                            echo $readonly_off;
                            echo "<input type=\"hidden\" name=\"required_".$fet_formdata['idwfprocassetsaccess']."\" value=\"".$fet_formdata['perm_required']."\">\r\n
                                <input type=\"hidden\" name=\"transtype_".$fet_formdata['idwfprocassetsaccess']."\" value=\"".$transaction."\" > \r\n
                                <input type=\"hidden\" name=\"itemtype_".$fet_formdata['idwfprocassetsaccess']."\" value=\"".$fet_formdata['wfprocdtype_idwfprocdtype']."\">
                                ";	
                                
                            }
                        
                        
                        //date & time 
                        if ($fet_formdata['wfprocdtype_idwfprocdtype']==7) 
                            {
							
							
                                $readonly_off="<script language=\"javascript\">
                                $('#item_".$fet_formdata['idwfprocassetsaccess']."').datetimepicker({
                                        controlType: 'select',
                                        timeFormat: 'hh:mm tt',
                                        dateFormat: 'dd/mm/yy'
                                });
                                </script>";	
								$readonly_style="";
								$readonly_click=" onClick=\"datetimepicker('item_".$fet_formdata['idwfprocassetsaccess']."');\" ";
                            
							
                            echo "<input size=\"25\" ";
                          
                           echo " value=\"\" ";
                               
                            
                            //show if required value missing
                            if ( (isset($_POST['required_'.$fet_formdata['idwfprocassetsaccess'].''])) && ($_POST['required_'.$fet_formdata['idwfprocassetsaccess'].'']==1) &&  ($_POST['item_'.$fet_formdata['idwfprocassetsaccess'].'']=="") )
                                    {
                                    echo " style=\"border:1px solid #ff0000\" ";
                                    }
                                    
                            echo $readonly_click." ".$readonly_style." name=\"item_".$fet_formdata['idwfprocassetsaccess']."\" type=\"text\" id=\"item_".$fet_formdata['idwfprocassetsaccess']."\"  >" ;
                                
                                echo $readonly_off;	

                            echo "<input type=\"hidden\" name=\"required_".$fet_formdata['idwfprocassetsaccess']."\" value=\"".$fet_formdata['perm_required']."\">\r\n
                                <input type=\"hidden\" name=\"transtype_".$fet_formdata['idwfprocassetsaccess']."\" value=\"".$transaction."\" > \r\n
                                <input type=\"hidden\" name=\"itemtype_".$fet_formdata['idwfprocassetsaccess']."\" value=\"".$fet_formdata['wfprocdtype_idwfprocdtype']."\">
                                ";	
                                        
                            }
                        
                        
                         //this is a text box (numbers only)
                         if ($fet_formdata['wfprocdtype_idwfprocdtype']==8) 
                            {                            
                            echo "<input onKeyUp=\"res(this,numb);\" type=\"text\"  ";
                            
                            if (isset($_POST['item_'.$fet_formdata['idwfprocassetsaccess'].'']))
                                {
                                echo " value=\"".$_POST['item_'.$fet_formdata['idwfprocassetsaccess'].'']."\" ";
                                } 
                            //show if value is missing
                            if ( (isset($_POST['required_'.$fet_formdata['idwfprocassetsaccess'].''])) && ($_POST['required_'.$fet_formdata['idwfprocassetsaccess'].'']==1) &&  ($_POST['item_'.$fet_formdata['idwfprocassetsaccess'].'']=="") )
                                    {
                                    echo " style=\"border:1px solid #ff0000\" ";
                                    }	
                                
                            echo " name=\"item_".$fet_formdata['idwfprocassetsaccess']."\" maxlength=\"50\">";
                            echo "<input type=\"hidden\" name=\"required_".$fet_formdata['idwfprocassetsaccess']."\" value=\"".$fet_formdata['perm_required']."\">\r\n
                            <input type=\"hidden\" name=\"transtype_".$fet_formdata['idwfprocassetsaccess']."\" value=\"".$transaction."\" > \r\n
                            <input type=\"hidden\" name=\"itemtype_".$fet_formdata['idwfprocassetsaccess']."\" value=\"".$fet_formdata['wfprocdtype_idwfprocdtype']."\">
                            ";
                            }
                            
                            
                            //this are approvals values
                      if ($fet_formdata['wfprocdtype_idwfprocdtype']==9) 
					 	{
						//check permissions
					//	echo $fet_formdata['perm_write'];
						
						
						$sql_choicesapprovals="SELECT idwfprocdtype_approvals,wfprocdtype_approvalslbl FROM wfprocdtype_approvals ";
						$res_choicesapprovals=mysql_query($sql_choicesapprovals);
						$fet_choicesapprovals=mysql_fetch_array($res_choicesapprovals);
						//echo $sql_choices;	
						
						echo "<select name=\"item_".$fet_formdata['idwfprocassetsaccess']."\" ";
						
						//show if required value is missing
						if ( (isset($_POST['required_'.$fet_formdata['idwfprocassetsaccess'].''])) && ($_POST['required_'.$fet_formdata['idwfprocassetsaccess'].'']==1) &&  ($_POST['item_'.$fet_formdata['idwfprocassetsaccess'].'']=="") )
								{
								echo " style=\"border:1px solid #ff0000\" ";
								}
						
						echo " >";
							
							do {
								
								echo "<option ";
									//select the default if there is a value
									if ( (isset($_POST['item_'.$fet_formdata['idwfprocassetsaccess'].''])) && ($_POST['item_'.$fet_formdata['idwfprocassetsaccess'].'']==$fet_choicesapprovals['idwfprocdtype_approvals'])  )
										{
										echo " selected=\"selected\" ";
									//	} else {
									//	echo " disabled=\"disabled\" ";
										}	
										
									if ($fet_formdata['perm_write']==0)  
										{	
										echo "disabled=\"disabled\" ";
										} 
																		
								echo " value=\"".$fet_choicesapprovals['idwfprocdtype_approvals']."\">".$fet_choicesapprovals['wfprocdtype_approvalslbl']."</option>";
							} while ($fet_choicesapprovals=mysql_fetch_array($res_choicesapprovals));
						echo "</select>";	
						
						echo "<input type=\"hidden\" name=\"required_".$fet_formdata['idwfprocassetsaccess']."\" value=\"".$fet_formdata['perm_required']."\">\r\n
						<input type=\"hidden\" name=\"transtype_".$fet_formdata['idwfprocassetsaccess']."\" value=\"".$transaction."\" > \r\n
						<input type=\"hidden\" name=\"itemtype_".$fet_formdata['idwfprocassetsaccess']."\" value=\"".$fet_formdata['wfprocdtype_idwfprocdtype']."\">
						";
						}
                        
						
						
						 //this is a text box
                         if ($fet_formdata['wfprocdtype_idwfprocdtype']==10) 
                            {
                            //check permissions
                        //	echo $fet_formdata['perm_write'];
                       
                            
                            echo "<textarea   name=\"item_".$fet_formdata['idwfprocassetsaccess']."\" maxlength=\"450\" ";
                                //highlight document show if error
                            //	if (isset($error."_".$fet_formdata['idwfprocassetsaccess']))
                                if ( (isset($_POST['required_'.$fet_formdata['idwfprocassetsaccess'].''])) && ($_POST['required_'.$fet_formdata['idwfprocassetsaccess'].'']==1) &&  ($_POST['item_'.$fet_formdata['idwfprocassetsaccess'].'']=="") )
                                    {
                                    echo " style=\"border:1px solid #ff0000\" ";
                                    }
							echo ">";
							if (isset($_POST['item_'.$fet_formdata['idwfprocassetsaccess'].'']))
                                    {
                                    echo $_POST['item_'.$fet_formdata['idwfprocassetsaccess'].''];
                                    }                  
                            echo "</textarea>";
                            
                            echo "<input type=\"hidden\" name=\"required_".$fet_formdata['idwfprocassetsaccess']."\" value=\"".$fet_formdata['perm_required']."\">\r\n
                            <input type=\"hidden\" name=\"transtype_".$fet_formdata['idwfprocassetsaccess']."\" value=\"".$transaction."\" > \r\n
                            <input type=\"hidden\" name=\"itemtype_".$fet_formdata['idwfprocassetsaccess']."\" value=\"".$fet_formdata['wfprocdtype_idwfprocdtype']."\">
                            ";
                            }
						
						
						
                        ?>
                        </td>
                        </tr>
                        </table>
                
             		<?php			
				 	} while ($fet_formdata=mysql_fetch_array($res_formdata));
				} //[001] close if num > 0
			//close after checking
				?>

                    <!-- END EXTRA FORM  -->
                </td>
            </tr>
            <tr>
            	<td colspan="2">
<!--- Extra Form -->

<!-- Extra Form End-->
                </td>
            </tr>
          	<tr>
            	<td colspan="2" class="table_header">
                <?php echo $lbl_taskalloc;?>
                </td>
            </tr>
   	  			<tr>
                    	<td width="25%" class="tbl_data">
                        <strong><?php echo $lbl_from;?></strong>
                        </td>
						<td width="75%" class="tbl_data">
                        <?php echo $_SESSION['MVGitHub_userrole'];?>, <small><?php echo $_SESSION['MVGitHub_usrtitle']." ".$_SESSION['MVGitHub_usrlname'];?></small>
                        </td>
                </tr>
                  <tr>
                    	<td width="25%" class="tbl_data" >
                        <?php echo $lbl_asterik;?><strong><?php echo $lbl_action;?></strong></td>
                        <td class="tbl_data">
		<?php
			$sql_listactions="SELECT  wftskstatustype,idwftskstatustypes,wftskstatustypedesc,idwftskstatus FROM wftskstatustypes
			INNER JOIN wftskstatus ON wftskstatustypes.idwftskstatustypes=wftskstatus.wftskstatustypes_idwftskstatustypes 
			WHERE idwftskstatustypes=2 GROUP BY idwftskstatustypes ORDER BY wftskstatustypes.listorder ASC";
			$res_listactions=mysql_query($sql_listactions);
			$fet_listactions=mysql_fetch_array($res_listactions);
			$num_listactions=mysql_num_rows($res_listactions);
			?>
			<!--<select name="action_to"  id="action_msg" onchange="showstuff(this.value);" > -->
            <select name="action_to"  id="action_msg" >
            	<!-- <option value="0">---</option> -->
                <?php 
				do { 
				?>
                <option value="<?php echo $fet_listactions['idwftskstatustypes'];?>"><?php echo $fet_listactions['wftskstatustype'];?></option>
                <?php } while ($fet_listactions=mysql_fetch_array($res_listactions)) ;?>
            </select>
			</td>
		</tr>
		
	
	<!--<div id="2" style="margin:0; padding:0;display:none;">-->
    <div id="2" style="margin:0; padding:0;">
    <table border="0" width="100%" cellpadding="0" cellspacing="0" >
    	<tr>
            <td width="25%" valign="top" class="tbl_data" align="left" >
            <?php echo $lbl_asterik;?><strong><?php echo $lbl_youraction_msg?></strong>            </td>
            <td class="tbl_data" >
            <textarea cols="25" rows="4" name="task_msg_2"><?php if (isset($tkttskmsg2)) { echo $tkttskmsg2; }  if (isset($_SESSION['tkttskmsg2'])) { echo $_SESSION['tkttskmsg2']; }  ?></textarea>
            <?php
             /*               $sBasePath = $_SERVER['PHP_SELF'] ;
                            $sBasePath = substr( $sBasePath, 0, strpos( $sBasePath, "_samples" ) ) ;
                            $oFCKeditor = new FCKeditor('task_msg_2') ;
                            $oFCKeditor->Height = '150' ;
                            $oFCKeditor->BasePath	= "fckeditor/".$sBasePath ;
							if (isset($tkttskmsg2)) 
							{
							$oFCKeditor->Value =$tkttskmsg2;
							} else {
                            $oFCKeditor->Value = '-';
							}                            $oFCKeditor->ToolbarSet = 'Basic' ;
                            $oFCKeditor->Create() ;*/
                            ?>            </td>
		</tr>
    
         <?php
		   if (($tktcat==5) || ($tktcat==18) )
		   	{
			//echo "<input type=\"hidden\" value=\"0\" name=\"subcat_exists\">";
			echo "<input type=\"hidden\" value=\"0\" name=\"subcat\">";
		   ?>           
           <tr>
        	<td class="tbl_data">
			<strong>Bill Number</strong>
            </td>
        	<td class="tbl_data">
            <input type="text" name="billno" value="<?php if (isset($_SESSION['num_rec_rcm'])) { echo $_SESSION['num_rec_rcm']; } ?>" maxlength="20" size="20" onKeyUp="res(this,numb);" />
            </td>
          </tr>            
           <?php
		   }
		   ?>
        <tr>
        	<td class="tbl_data">
           <?php echo $lbl_asterik;?> <strong><?php echo $lbl_sendto;?></strong>
           </td>
           <td class="tbl_data">
           <div style="padding:0px 0px 5px 0px;">
               <div class="text_small">To which Region?</div>
               <div>
               <?php
			   //loop the regions for this company
			   $sql_regions="SELECT idusrteamzone,userteamzonename FROM usrteamzone WHERE usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." LIMIT 10";
			   $res_regions=mysql_query($sql_regions);
			   $fet_regions=mysql_fetch_array($res_regions);
			   //echo $sql_regions;
			   ?>
               <select name="assign_to_region" id="assign_to_region" onChange="getrecepients(this.value)">
               <?php
			   do {
			   	
				echo "<option ";
					//check this users region and select it by default
					if ($_SESSION['MVGitHub_userteamzoneid']==$fet_regions['idusrteamzone'])
						{
						echo " selected=\"selected\" ";
						}
				echo " value=\"".$fet_regions['idusrteamzone']."\">".$fet_regions['userteamzonename']."</option>";
			   
			   } while ($fet_regions=mysql_fetch_array($res_regions));
			   ?>
               </select>
               </div>
           </div>
           <div>
           <div class="text_small">Select Recepient:</div>
           <div id="recepientdiv">
            <?php
			//before listing the roles, please confirm that
			//1. the Category in use has a valid workflow
			$sql_wf="SELECT wfproc_idwfproc FROM link_tskcategory_wfproc 
			INNER JOIN wfproc ON link_tskcategory_wfproc.wfproc_idwfproc=wfproc.idwfproc
			WHERE link_tskcategory_wfproc.usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." AND link_tskcategory_wfproc.tktcategory_idtktcategory=".$tktcat." AND wfproc.wfstatus=1 LIMIT 1";
			$res_wf=mysql_query($sql_wf);
			$num_wf=mysql_num_rows($res_wf);
			$fet_wf=mysql_fetch_array($res_wf);
			
			if ($num_wf>0)
				{
		//2. get the workflow
			//ensure that if the person creating this ticket is the Customer Care agent /or the first one on the workflow, 
			//then they should not send the ticket to themselves and so it should go to the next step
						$sql_precheck="SELECT idwftskflow,wfsymbol_idwfsymbol,wfproc_idwfproc FROM wftskflow WHERE wfproc_idwfproc=".$fet_wf['wfproc_idwfproc']." AND listorder>'0.00'  ORDER BY listorder ASC LIMIT 1"; //precheck to get the idtskflow for this process
						$res_precheck=mysql_query($sql_precheck);
						$fet_precheck=mysql_fetch_array($res_precheck);
						$num_precheck=mysql_num_rows($res_precheck);
						//echo $sql_precheck;
						//check if the actors for this step
						if ($num_precheck > 0) //is that steps actors ROLE BASED or GROUP BASED
							{
							$sql_preactors="SELECT usrrole_idusrrole,usrgroup_idusrgroup,wftskflow_idwftskflow FROM wfactors WHERE wftskflow_idwftskflow=".$fet_precheck['idwftskflow']." AND usrteamzone_idusrteamzone=".$_SESSION['MVGitHub_userteamzoneid']." LIMIT 1 ";
							$res_preactors=mysql_query($sql_preactors);
							$fet_preactors=mysql_fetch_array($res_preactors);
							$num_preactors=mysql_num_rows($res_preactors);
							//echo $sql_preactors."<br><br>";
								if ($num_preactors > 0)
									{
										if ($fet_preactors['usrrole_idusrrole'] > 0)
											{
											//echo "sdfd";
											//check if this user's account and if so, lets skip to the next taskworkflow id
											$sql_thisusr="SELECT usrrole_idusrrole FROM wfactors WHERE usrrole_idusrrole=".$_SESSION['MVGitHub_iduserrole']." AND wftskflow_idwftskflow=".$fet_preactors['wftskflow_idwftskflow']." LIMIT 1";
											$res_thisusr=mysql_query($sql_thisusr);
											$fet_thisusr=mysql_fetch_array($res_thisusr);
											$num_thisusr=mysql_num_rows($res_thisusr);
											//echo $sql_thisusr;
												if ($num_thisusr > 0)
													{
													$skip_to_step2=1; //set variable to skip to next workflow
													}
											}
										
										if ($fet_preactors['usrgroup_idusrgroup'] > 0)
											{
											$sql_thisusr="SELECT usrrole_idusrrole FROM link_userrole_usergroup WHERE usrrole_idusrrole=".$fet_preactors['usrgroup_idusrgroup']." AND usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." LIMIT 1";
											$res_thisusr=mysql_query($sql_thisusr);
											$fet_thisusr=mysql_fetch_array($res_thisusr);
											
												if ($fet_thisusr['usrrole_idusrrole']==$_SESSION['MVGitHub_iduserrole'])
													{
													$skip_to_step2=1; //set variable to skip to next workflow
													}
											
											}
									
									}
							}
					
						if (isset($skip_to_step2))
							{						
							$sql_nextwf="SELECT idwftskflow,wfsymbol_idwfsymbol,wfproc_idwfproc FROM wftskflow WHERE wfproc_idwfproc=".$fet_wf['wfproc_idwfproc']." AND listorder>'0.00'  ORDER BY listorder ASC LIMIT 1,1";
							} else {
							$sql_nextwf="SELECT idwftskflow,wfsymbol_idwfsymbol,wfproc_idwfproc FROM wftskflow WHERE wfproc_idwfproc=".$fet_wf['wfproc_idwfproc']." AND listorder>'0.00'  ORDER BY listorder ASC LIMIT 1";
							}
						//echo $sql_nextwf;	
						$res_nextwf=mysql_query($sql_nextwf);
						$fet_nextwf=mysql_fetch_array($res_nextwf);
						$num_nextwf=mysql_num_rows($res_nextwf);
						
						//echo $sql_nextwf."<br>";
						if ($num_nextwf > 0)//if there is a record
							{ 
							
							if ($fet_nextwf['wfsymbol_idwfsymbol']==10)//if it is the end of the process
								{
								
								$next_step="last_step";
								
								} else { //else if not end of the process, continue
								
									//confirm whether the actors are a group or individual role
									$sql_actors="SELECT usrrole_idusrrole,usrgroup_idusrgroup FROM wfactors WHERE wftskflow_idwftskflow=".$fet_nextwf['idwftskflow']." AND usrteamzone_idusrteamzone=".$_SESSION['MVGitHub_userteamzoneid']."  LIMIT 1 ";
									$res_actors=mysql_query($sql_actors);
									$fet_actors=mysql_fetch_array($res_actors);
									$num_actors=mysql_num_rows($res_actors);
									//echo $sql_actors;
									if ($fet_actors['usrrole_idusrrole'] >0 ) //if more than 0, then it is a allocated to a role
										{
										//find out the actual account assigned this role
										$sql_userac="SELECT idusrac,usrrolename,idusrrole,usrac.utitle,usrac.lname,usrac.usrname,usrac.fname FROM wfactors
										INNER JOIN usrrole ON wfactors.usrrole_idusrrole=usrrole.idusrrole
										INNER JOIN usrac ON usrrole.idusrrole=usrac.usrrole_idusrrole
										WHERE wfactors.wftskflow_idwftskflow=".$fet_nextwf['idwftskflow']." AND usrac.usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." AND acstatus=1 AND wfactors.usrteamzone_idusrteamzone=".$_SESSION['MVGitHub_userteamzoneid']." ";
										$res_userac=mysql_query($sql_userac);
										$fet_userac=mysql_fetch_array($res_userac);
										$num_userac=mysql_num_rows($res_userac);
										//echo $sql_userac."<br>";

										if ($num_userac > 0)
											{
											
											$menu_item="";
												do {
												
													$usrman=substr($fet_userac['usrname'],3,10);
													
													if ($fet_userac['idusrrole']!=$_SESSION['MVGitHub_iduserrole']) //list only if not current user
														{
														//get the man number from the account name
														$menu_item.="<option title=\"".$fet_userac['utitle']." ".$fet_userac['fname']." ".$fet_userac['lname']." (".$usrman.")\" value=\"".$fet_userac['idusrrole']."\">".$fet_userac['usrrolename']."</option>";
														} else {
														$menu_item.="<option title=\"".$fet_userac['utitle']." ".$fet_userac['fname']." ".$fet_userac['lname']." (".$usrman.")\" value=\"".$fet_userac['idusrrole']."\">*** [ To My TasksIN ]</option>";
														} //end //list only if not current user
													} while ($fet_userac=mysql_fetch_array($res_userac));
									
											} else {
											
											echo "<div class=\"msg_warning\">--No Active User--<br><small>(Please Contact Admin)</small></div>";
											
											} //user exists
										} //close usrrole
								
									if ($fet_actors['usrgroup_idusrgroup'] > 0 ) //if allocated to a group, then do the following
										{ 
										//if group, check only those roles that do actually have users (active status) mapped to them
										//check who has had most work in the last 7 days (one week) in terms of hours
										//last 7 days
										//$timenow = ; //capture current time. You can adjust based on server settings
										$sevendaysago = date("Y-m-d H:i:s",strtotime(date("Y-m-d H:i:s",time())) - (7*86400)); //7 days ago
										
										//echo $sevendaysago."<br>-----";
											
										$sql_workdistr="SELECT SUM(TIMESTAMPDIFF(MINUTE,timetatstart,timedeadline)) AS minutes, usrac.idusrac, usrac.usrrole_idusrrole,usrac.utitle,usrac.lname,usrrole.usrrolename FROM wftasks 
										INNER JOIN usrrole ON wftasks.usrrole_idusrrole=usrrole.idusrrole 
										INNER JOIN link_userrole_usergroup ON usrrole.idusrrole = link_userrole_usergroup.usrrole_idusrrole
										INNER JOIN usrac ON link_userrole_usergroup.usrrole_idusrrole = usrac.usrrole_idusrrole
										WHERE wftasks.createdon>='".$sevendaysago."' AND wftasks.createdon<='".$timenowis."' AND usrac.usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." AND link_userrole_usergroup.usrgroup_idusrgroup=".$fet_actors['usrgroup_idusrgroup']." AND acstatus=1 AND usrrole.usrteamzone_idusrteamzone=".$_SESSION['MVGitHub_userteamzoneid']." GROUP BY wftasks.usrac_idusrac ORDER BY minutes ASC";
										//echo "test";
										//echo $sql_workdistr."<br>";
										$res_workdistr=mysql_query($sql_workdistr);
										$num_workdistr=mysql_num_rows($res_workdistr);
										$fet_workdistr=mysql_fetch_array($res_workdistr);
											
											
										//check in case the group has not received anything in the last 7 days
										$sql_workdistolder7="SELECT SUM(TIMESTAMPDIFF(MINUTE,timetatstart,timedeadline)) AS minutes, usrac.idusrac, usrac.usrrole_idusrrole,usrac.utitle,usrac.lname,usrrole.usrrolename FROM wftasks 
										INNER JOIN usrrole ON wftasks.usrrole_idusrrole=usrrole.idusrrole 
										INNER JOIN link_userrole_usergroup ON usrrole.idusrrole = link_userrole_usergroup.usrrole_idusrrole
										INNER JOIN usrac ON link_userrole_usergroup.usrrole_idusrrole = usrac.usrrole_idusrrole
										WHERE wftasks.createdon<='".$timenowis."' AND link_userrole_usergroup.usrgroup_idusrgroup=".$fet_actors['usrgroup_idusrgroup']." AND usrac.usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." AND acstatus=1 AND usrrole.usrteamzone_idusrteamzone=".$_SESSION['MVGitHub_userteamzoneid']." GROUP BY wftasks.usrac_idusrac ORDER BY minutes ASC";
										//echo "test";
										//echo $sql_workdistolder7."<br>";
										$res_workdistolder7=mysql_query($sql_workdistolder7);
										$num_workdistolder7=mysql_num_rows($res_workdistolder7);
										$fet_workdistolder7=mysql_fetch_array($res_workdistolder7);	
										
											
										//check also for any new user who perhaps has never received a task - new user
										$sql_newuser="SELECT idusrac, usrac.usrrole_idusrrole, usrrole.usrrolename,usrac.utitle,usrac.lname
										FROM link_userrole_usergroup
										INNER JOIN usrrole ON link_userrole_usergroup.usrrole_idusrrole = usrrole.idusrrole
										INNER JOIN usrac ON usrrole.idusrrole=usrac.usrrole_idusrrole
										WHERE link_userrole_usergroup.usrrole_idusrrole NOT
										IN (
										
										SELECT usrrole_idusrrole
										FROM wftasks
										)
										AND link_userrole_usergroup.usrgroup_idusrgroup=".$fet_actors['usrgroup_idusrgroup']." 
										AND usrrole.usrteamzone_idusrteamzone=".$_SESSION['MVGitHub_userteamzoneid']."
										AND acstatus=1";
										$res_newuser=mysql_query($sql_newuser);
										$num_newuser=mysql_num_rows($res_newuser);
										$fet_newuser=mysql_fetch_array($res_newuser);
								
							//	echo $sql_newuser;
										//if record exists, then pick 
										
										if ($num_newuser>0) //if there is a new user and user exists in the workflow
											{
											
											$menu_item3="";
												do {
													if ($fet_newuser['usrrole_idusrrole']!=$_SESSION['MVGitHub_iduserrole']) //list only if not current user
														{
														$menu_item3.="<option title=\"".$fet_newuser['utitle']." ".$fet_newuser['lname']."\" value=\"".$fet_newuser['usrrole_idusrrole']."\">".$fet_newuser['usrrolename']."</option>";
														} //end //list only if not current user
													} while ($fet_newuser=mysql_fetch_array($res_newuser));	
											//$menu_item="<option selected=\"selected\" title=\"".$fet_newuser['utitle']." ".$fet_newuser['lname']."\" value=\"".$fet_newuser['usrrole_idusrrole']."\">".$fet_newuser['usrrolename']."[2]</option>";
												
												
											} else if ($num_newuser==0) { //else if no one is new 
												
													if ($num_workdistr > 0 ) //if there are already users in the tasks
														{
														$menu_item2="";
															
															do {
															//don't list the current logged in user on the menu
															if ($fet_workdistr['usrrole_idusrrole']!=$_SESSION['MVGitHub_iduserrole'])
																	{
																	$menu_item2.="<option title=\"".$fet_workdistr['utitle']." ".$fet_workdistr['lname']."\" value=\"".$fet_workdistr['usrrole_idusrrole']."\">".$fet_workdistr['usrrolename']."</option>";
																	}
																} while ($fet_workdistr=mysql_fetch_array($res_workdistr));
														
														} else { //else create a task for the admin
														
															//check if older than 7 days
															if ($num_workdistolder7 > 0)
																{
																$menu_item2="";
																do {
																	//don't list the current logged in user on the menu
																	if ($fet_workdistolder7['usrrole_idusrrole']!=$_SESSION['MVGitHub_iduserrole'])
																			{
																			$menu_item2.="<option title=\"".$fet_workdistolder7['utitle']." ".$fet_workdistolder7['lname']."\" value=\"".$fet_workdistolder7['usrrole_idusrrole']."\">".$fet_workdistolder7['usrrolename']."</option>";
																			}
																		} while ($fet_workdistolder7=mysql_fetch_array($res_workdistolder7));
																
																} else {
																//create new task for the admin
																echo "<div class=\"msg_warning\">--No Active User--<br><small>(Please Contact Admin)</small></div>";
																}
											
														} //user exists
												
												} //close new user

											} //close user group

								} //not last step
							
							} else { //if no record
							
							$next_step="end_of_road";
							} //close if there is a record


													
							
						if ( (isset($menu_item)) || (isset($menu_item2)) || (isset($menu_item3)) )
							{
							//retain the selected option with the earlier action
							if (isset($_POST['assign_to_2']))
								{
								//get the details
								$sql_retainto="SELECT idusrac, usrac.usrrole_idusrrole, usrrole.usrrolename,usrac.utitle,usrac.lname
								FROM link_userrole_usergroup
								INNER JOIN usrrole ON link_userrole_usergroup.usrrole_idusrrole = usrrole.idusrrole
								INNER JOIN usrac ON usrrole.idusrrole=usrac.usrrole_idusrrole
								WHERE idusrrole=".$tktasito2." LIMIT 1";
								$res_retainto=mysql_query($sql_retainto);
								$fet_retainto=mysql_fetch_array($res_retainto);
								
								$default_menu="<option title=\"".$fet_retainto['utitle']." ".$fet_retainto['fname']." ".$fet_retainto['lname']." \" value=\"".$fet_retainto['usrrole_idusrrole']."\">".$fet_retainto['usrrolename']."</option>";
								} else {
								$default_menu="<option value=\"\">---</option>";
								}
						echo "<select name=\"assign_to_2\" id=\"assign_to_2\">";
						echo $default_menu;
						if(isset($menu_item)) { echo $menu_item; }
						if(isset($menu_item2)) { echo $menu_item2; }
						if(isset($menu_item3)) { echo $menu_item3; }
						echo "</select>";	
										
							}
						
						}
						?>
                        </div>
                        </div>
                        </td>
        </tr>
        <tr>
        	<td></td>
            <td height="50">
            <?php
			if ((isset($skip_to_step2)) && ($skip_to_step2==1))
				{
				$skip_step=1;
				} else {
				$skip_step=0;
				}
			//echo $skip_step;
			?>
            <input type="hidden" value="2" name="step" />
            <input type="hidden" name="skip_to_step2"  value="<?php echo $skip_step;?>" />
            <input type="hidden" value="process_task" name="formaction" />
            <a href="javascript: submitform()" id="button_passiton"></a>
                        </td>
        </tr>
	</table>
	</div>
    
	
</td>
		</tr>
</table>
<?php
}
?>