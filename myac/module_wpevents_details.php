<?php
require_once('../assets_backend/be_includes/check_login_easy.php');
//get the idreport
if (isset($_GET['idreport']))
	{
	$_SESSION['idreporting']=preg_replace('/[^a-z\-_0-9\.:@\/\s]/i','',mysql_escape_string(trim($_GET['idreport'])));
	}

//tabs navigation
if (!isset($_SESSION['tabview_con'])) //set default tab
	{
	$_SESSION['tabview_con'] = 1;
	}

if ( (isset($_SESSION['tabview_con'])) && ($_SESSION['tabview_con']!=1) && ($_SESSION['tabview_con']!=2) ) //set default tab
	{
	$_SESSION['tabview_con'] = 1;
	}

if (isset($_GET['tabview']))
	{
	$_SESSION['tabview_con'] = mysql_escape_string(trim($_GET['tabview']));
	}

?>
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
            <span class="tab<?php if ( (isset($_SESSION['tabview_con'])) && ($_SESSION['tabview_con']==2)){ echo "_on"; } ?>"><a href="<?php $_SERVER['PHP_SELF'];?>?tabview=2"><?php echo $lbl_attndlist;?></a></span>
            <span class="tab<?php if ( (isset($_SESSION['tabview_con'])) && ($_SESSION['tabview_con']==3)){ echo "_on"; } ?>"><a href="<?php $_SERVER['PHP_SELF'];?>?tabview=3"><?php echo $lbl_docs;?></a></span>
          <span class="tab<?php // if ( (isset($_SESSION['tabview_con'])) && ($_SESSION['tabview_con']==4)){ echo "_on"; } ?>"><a href="<?php $_SERVER['PHP_SELF'];?>?tabview=4"><?php echo $lbl_photovideo;?></a></span>
            <span class="tab<?php if ( (isset($_SESSION['tabview_con'])) && ($_SESSION['tabview_con']==5)){ echo "_on"; } ?>"><a href="<?php $_SERVER['PHP_SELF'];?>?tabview=5"><?php echo $lbl_notes;?></a></span>
        </div>
	</div>
    <div style="margin:10px 0px 0px 0px; padding:20px 10px 50px 10px">
    <?php
		if ($_SESSION['tabview_con']=="1") { require_once('module_wpevents_details_1.php'); }
		if ($_SESSION['tabview_con']=="2") { require_once('module_wpevents_details_2.php'); }
		if ($_SESSION['tabview_con']=="3") { require_once('module_wpevents_details_3.php'); }
		if ($_SESSION['tabview_con']=="4") { require_once('../myac/module_wpevents_details_4.php'); }
		if ($_SESSION['tabview_con']=="5") { require_once('module_wpevents_details_5.php'); }
	?>
    </div>
</div>    