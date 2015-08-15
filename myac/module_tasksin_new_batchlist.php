<?php
require_once('../assets_backend/be_includes/config.php');
require_once('../assets_backend/be_includes/check_login_easy.php');

if (isset($_GET['parentviewtab']))
	{
	$_SESSION['parenttabview']=preg_replace('/[^a-z\-_0-9\.:\/]/i','',mysql_escape_string(trim($_GET['parentviewtab'])));
	}

if (isset($_GET['batchformview']))
	{
	$_SESSION['batchformview']=mysql_escape_string(trim($_GET['batchformview']));
	}
?>

<div>
	<div style="padding:5px 0px 5px 600px">
    <a href="index.php?view=2&amp;page=&amp;mod=2&amp;submod=2&amp;batchview=<?php echo $_SESSION['link_batch'];?>" id="button_batch_on"></a>
    </div>
    <div >
    <form action="" method="get" name="search_tasks">
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
    	<tr>
			<td width="30%" class="bg_section">
           <a href="index.php?mod=2&amp;submod=0&amp;ua=view&amp;view=2&amp;uction=view_mod"> Batch List </a>&nbsp;&nbsp;&raquo;&nbsp;&nbsp;Task / Records List
			</td>
			<td width="70%" class="bg_section">
            
            </td>
        </tr>
    </table>
    </form>
    </div>
	<div style="padding:0px 0px 10px 0px">
	<?php	
//	echo "hhhh";
		if (isset($_SESSION['batchformview']))
			{
			require_once('../myac/module_tasksin_new_batchlist_'.$_SESSION['batchformview'].'.php');
			}
	?>  
	</div>
</div>