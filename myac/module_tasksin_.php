<?php
require_once('../assets_backend/be_includes/config.php');

	if ($_SESSION['parenttabview']==1) //tasksin new
		{
		require_once('module_tasksin_new.php');
		}
	if ($_SESSION['parenttabview']==2) //tasksin new
		{
		require_once('module_tasksin_inprogress.php');
		}
	if ($_SESSION['parenttabview']==3) //tasksin new
		{
		require_once('module_tasksin_overdue.php');
		}
	if ($_SESSION['parenttabview']==4) //tasksin new
		{
		require_once('module_tasksin_closed.php');
		}
	if ($_SESSION['parenttabview']==5) //tasksin new
		{
		require_once('module_task_search.php');
		}
?>