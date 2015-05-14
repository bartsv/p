<?php
header('Content-type: text/html;charset=utf-8');
require_once 'util/funzioni1.php';
$user=addslashes(trim($_POST['id']));
selectDataUser($user);
?>