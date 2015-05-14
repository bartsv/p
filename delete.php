<?php
header('Content-type: text/html;charset=utf-8');
require_once 'util/funzioni1.php';
$user=addslashes(trim($_POST['user']));
$psw=addslashes(trim($_POST['psw']));
$idc=addslashes(trim($_POST['idC']));
$idpost=addslashes(trim($_POST['idP']));
$tipo=trim($_POST['tipo']);
if(strcmp($tipo,'deleteC')==0)
deleteCommento($user,$psw,$idc,$idpost);
if(strcmp($tipo,'deleteL')==0)
deleteLike($idpost,$user,$psw);
?>