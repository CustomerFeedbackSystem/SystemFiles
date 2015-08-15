<?php
session_start();
unset($_SESSION['wtitle']);
unset($_SESSION['wtaskid']);
unset($_SESSION['thiscat']);
unset($_SESSION['tkttskmsg1']);
unset($_SESSION['tkttskmsg2']);
unset($_SESSION['idtktintrans']);
unset($_SESSION['wtaskid']);

header('location:pop_newticket.php');
exit;
?>