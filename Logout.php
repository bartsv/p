<?php
require_once 'util/funzioni1.php';
$user=addslashes(trim($_POST['user']));
$psw=addslashes(trim($_POST['psw']));
CalcolaLike($user);
Logout($user,$psw);
?>
