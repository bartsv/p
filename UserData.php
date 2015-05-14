<?php
header('Content-type: text/html;charset=utf-8');
require_once 'util/funzioni1.php';
$user=addslashes(trim($_POST['user']));
$psw=addslashes(trim($_POST['psw']));
selectMyData($user,$psw);
?>