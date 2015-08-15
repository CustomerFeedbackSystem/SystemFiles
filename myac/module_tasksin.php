<?php
require_once('../assets_backend/be_includes/config.php');
require_once('../assets_backend/be_includes/check_login_easy.php');


if (!isset($_SESSION['parenttabview']))
	{
	$_SESSION['parenttabview']=1;
	}
	
if ( ($_SESSION['sec_submod']==2) && ($_SESSION['sec_mod']==2) && ($_SESSION['parenttabview']==5) ) //fail safe to prevent invalid tab request
	{
	require_once('module_task_search.php');
	exit;
	}

if (($_SESSION['parenttabview'] > 4) &&  (!isset($_GET['parentviewtab']))  ) //fail safe to prevent invalid tab request
	{
	$_SESSION['parenttabview']=1;
	}

if (isset($_GET['parentviewtab']))
	{
	$_SESSION['parenttabview']=mysql_escape_string(trim($_GET['parentviewtab']));
	}
	
//CHECK IF I HAVE DELEGATED OUT
$sql_deleg="SELECT usrrolename,wftasksdeleg_meta.idusrrole_from,wftasksdeleg_meta.idusrrole_to,utitle,fname,lname FROM wftasksdeleg_meta
INNER JOIN usrrole ON wftasksdeleg_meta.idusrrole_to=usrrole.idusrrole	
INNER JOIN usrac ON usrrole.idusrrole=usrac.usrrole_idusrrole	
WHERE wftasksdeleg_meta.idusrrole_from=".$_SESSION['MVGitHub_iduserrole']." AND wftasksdeleg_meta.deleg_status=1 LIMIT 1";
$res_deleg=mysql_query($sql_deleg);
$num_deleg=mysql_num_rows($res_deleg);
$fet_deleg=mysql_fetch_array($res_deleg);

if ($num_deleg > 0)
	{
	$_SESSION['delegated']=1;
	$_SESSION['delegated_to']=$fet_deleg['usrrolename'];
	} else {
	$_SESSION['delegated']=0;
	}

//STORE THIS IN A SESSION TO BE USED ACROSS ALL PAGES
$_SESSION['delegated_tasks_to'] = "(".$fet_deleg['usrrolename'].") ".$fet_deleg['utitle']." ".$fet_deleg['fname']." ".$fet_deleg['lname'];;

//CHECK IF I HAVE BEEN DELEGATED IN
$sql_delegin="SELECT usrrolename,wftasksdeleg_meta.idusrrole_from,wftasksdeleg_meta.idusrrole_to,utitle,fname,lname FROM wftasksdeleg_meta
INNER JOIN usrrole ON wftasksdeleg_meta.idusrrole_from=usrrole.idusrrole
INNER JOIN usrac ON usrrole.idusrrole=usrac.usrrole_idusrrole	
WHERE wftasksdeleg_meta.idusrrole_to=".$_SESSION['MVGitHub_iduserrole']." AND wftasksdeleg_meta.deleg_status=1 LIMIT 1";
$res_delegin=mysql_query($sql_delegin);
$num_delegin=mysql_num_rows($res_delegin);
$fet_delegin=mysql_fetch_array($res_delegin);
//echo $sql_delegin;
?>
<script type="text/javascript">
var reqPageUrl = new Array();			
reqPageUrl[1] = "module_tasksin_new_<?php echo $_SESSION['batchview'];?>.php?parentviewtab=1";
reqPageUrl[2] = "module_tasksin_inprogress.php?parentviewtab=2";
reqPageUrl[3] = "module_tasksin_closed.php?parentviewtab=3";
reqPageUrl[4] = "module_tasksin_overdue.php?parentviewtab=4";
reqPageUrl[5] = "module_tasksin_co.php?parentviewtab=5";

function loadTab(id)
{
	if (reqPageUrl[id].length > 0)
	{
		$("#preloader").show();
		$.ajax(
		{
			url: reqPageUrl[id], 
			cache: false,
			error: function(XMLHttpRequest, textStatus, errorThrown)
			{
				$('#tabmenu a').removeClass('active'); //remove all class='active' for all anchors
				$("#content_tab"+id).toggleClass('active'); //add class to the current one
				$("#content").empty().append('Error in loading content');//if there is any error in the request
				$("#preloader").hide();//hide the preloader
			},
			success: function(message) 
			{
				$('#tabmenu a').removeClass('active'); //remove all class='active' for all anchors
				$("#content_tab"+id).toggleClass('active'); //add class to the current one
				$("#content").empty().append(message);//first empty the content, then append content
				$("#preloader").hide();//hide the preloader
				tb_init('a.thickbox, area.thickbox, input.thickbox'); //call tb_init function to initiate ThichBox into your respective tab panels
			}
			
		});			        
	}
}

$(document).ready(function()
{
	loadTab(<?php echo $_SESSION['parenttabview'];?>); //After page loading, active tab 1
	//loadTab(1); //After page loading, active tab 1
	$("#preloader").hide();
	$("#content_tab1").click(function(e){e.preventDefault(); loadTab(1);}); //Here e.preventDefault(); is to prevent the respective href from going the user off the link, that is the href url '#' which is appending to the URL will going off 
	$("#content_tab2").click(function(e){e.preventDefault(); loadTab(2);});
	$("#content_tab3").click(function(e){e.preventDefault(); loadTab(3);});
	$("#content_tab4").click(function(e){e.preventDefault(); loadTab(4);});
	$("#content_tab5").click(function(e){e.preventDefault(); loadTab(5);});
});
</script>
</head>

<body>
	<!-- Container [Begin] -->
    <div class="container">
    <table border="0" cellpadding="0" cellspacing="0" width="100%" >
    	<tr>
        	<td>
<?php
//only display this if not batch view
if ($_SESSION['batchview']=="nobatch")
	{
?>            
<div class="tab_area">
        <ul id="tabmenu">
            <li><a id="content_tab1" href="#"><?php echo $lbl_new;?>
			<?php
            //CREATE EXCEPTION FOR BATCHES TO AVOID JS ERROR
            if ($_SESSION['sec_submod']!="299999999")
                {
            ?>            
			<script type="text/javascript">
            refreshdiv_2();
            </script>
            <span name="div_2" id="div_2"></span>
			<?php
            }
            ?>
			</a>
            </li>
            
            <li><a id="content_tab2" href="#"><?php echo $lbl_inprogress;?>
			<?php
            //CREATE EXCEPTION FOR BATCHES TO AVOID JS ERROR
            if ($_SESSION['sec_submod']!="299999999")
                {
            ?>            
            <script type="text/javascript">
            refreshdiv_3();
            </script>
            
            <span name="div_3" id="div_3"></span>
            <?php
            }
            ?>          
            </a>
            </li>
            
            <li><a id="content_tab3" href="#"><?php echo $lbl_completed;?></a></li>
            <li><a id="content_tab4" href="#"><?php echo $lbl_overdue;?>
			<?php
            //CREATE EXCEPTION FOR BATCHES TO AVOID JS ERROR
            if ($_SESSION['sec_submod']!="299999999")
                {
            ?>             
            <script type="text/javascript">
            refreshdiv_4();
            </script>
            
            <span name="div_4" id="div_4"></span>
            <?php
            }
            ?>
             </a>
             </li>
              <li><a id="content_tab5" href="#">C / o</a></li>
              
              </ul>
	</div>
    <?php
	} else { //else if batch view, then use the menu below
	?>

<div class="tab_area">
        <ul id="tabmenu">
            <li><a id="content_tab1" href="#">Batched Tasks</a></li>
           <!-- <li><a id="content_tab3" href="#"><?php echo $lbl_completed;?></a></li>-->
             <li>
             <a id="content_tab4" href="#"><?php echo $lbl_overdue;?>
             <?php
            //CREATE EXCEPTION FOR BATCHES TO AVOID JS ERROR
           
            ?>             
            <script type="text/javascript">
            refreshdiv_4();
            </script>
            
            <span name="div_4" id="div_4"></span>
            <?php
            
            ?>
             </a>
             </li>
        </ul>
	</div>

<?php	
	}
	?>
    </td>
    <td>&nbsp;</td>
    <td align="right">
<?php
if ($fet_delegin['idusrrole_to']!=$_SESSION['MVGitHub_iduserrole']) //if am receiving delegated tasks, I cannot delegate when in that state
	{
		if ($num_deleg > 0)
		{	
		?>
    	<a href="pop_delegate.php?tabview=1&tabview=1&keepThis=true&TB_iframe=true&height=450&width=750&inlineId=hiddenModalContent&modal=true" class="thickbox" id="button_delegate_on" title="You have delegated your tasks to <?php echo $_SESSION['delegated_tasks_to'];?>"></a>
    	<?php
    	} else {
		?>
    	<a href="pop_delegate.php?tabview=1&tabview=1&keepThis=true&TB_iframe=true&height=450&width=750&inlineId=hiddenModalContent&modal=true" class="thickbox" id="button_delegate_off" title="You can delegate your tasks to your colleague"></a>
    	<?php
		}
	}
//	echo $fet_delegin['idusrrole_to'];
	if ($fet_delegin['idusrrole_to']==$_SESSION['MVGitHub_iduserrole']) //delegated tasks in to me, so warn
		{
		echo "<img title=\"You are handling ".$fet_delegin['usrrolename']."'s (".$fet_delegin['utitle']." ".$fet_delegin['fname']." ".$fet_delegin['lname'].") tasks and therefore you cannot delegate your tasks at the moment\" src=\"../assets_backend/btns/btn_delegate_disabled.png\" border=\"0\">";
		}
	?>
    </td>
    <td>
    <?php
	if ( ($fet_delegin['idusrrole_to']!=$_SESSION['MVGitHub_iduserrole']) || ($fet_delegin['idusrrole_to']==$_SESSION['MVGitHub_iduserrole']) ) //if am receiving delegated tasks, I cannot delegate when in that state
	{
		if ($num_deleg > 0)
		{
	?>
    <img src="../assets_backend/btns/button_delegate_key_off.gif">
    <?php } else { ?>
    <a href="pop_delegate_generatekey.php?tabview=1&tabview=1&keepThis=true&TB_iframe=true&height=300&width=650&inlineId=hiddenModalContent&modal=true" class="thickbox" id="button_delegate_key" title="Generate a KEY to receive delegated tasks"></a>
    <?php } 
		}
	?>
    </td>
    </tr>
    </table>
        <div id="preloader">
        	<div><img src="../thickbox/loadingAnimation.gif" align="absmiddle"></div>
            <div style="font-size:10px; font-weight:bold"> Loading... Please Wait... </div>
        </div>
        
        <div id="content"></div>
    </div>    
    <!-- Container [End] -->