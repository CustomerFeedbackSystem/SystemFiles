<?php
require_once('../assets_backend/be_includes/config.php');

//require_once('../Connections/connSystem.php');
//mysql_select_db($database_connSystem, $connSystem);
require_once('../assets_backend/be_includes/check_login_easy.php');
?>
<div style="background-color:#CCCCCC">
<?php
include_once 'php-ofc-library/open_flash_chart_object.php';
open_flash_chart_object( '100%', '100%', 'chart_pie_1.php' );
?>
</div>