<?php
header('Content-type: text/html;charset=utf-8');
require_once 'util/funzioni1.php';

$nome=addslashes(trim($_POST['nome']));
$cognome=addslashes(trim($_POST['cognome']));
$nick=addslashes(trim($_POST['nick']));
$mail=addslashes(trim($_POST['mail']));
$psw=addslashes(trim($_POST['psw']));
$citta=addslashes(trim($_POST['citta']));
$data=addslashes(trim($_POST['dataN']));
Registrazione($nome,$cognome,$nick,$mail,$psw,$citta,$data);
?>