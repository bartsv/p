<?php
header('Content-type: text/html;charset=utf-8');
require_once 'util/funzioni1.php';
$user=addslashes(trim($_POST['user']));
$psw=addslashes(trim($_POST['psw']));
$mex=addslashes(trim($_POST['mex']));
$idpost=addslashes(trim($_POST['idP']));
$tipo=trim($_POST['tipo']);
if(strcmp($tipo,'insertC')==0)
insertCommento($user,$psw,$mex,$idpost);
if(strcmp($tipo,'insertL')==0)
insertLike($idpost,$user,$psw);
?>