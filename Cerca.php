<?php
header('Content-type: text/html;charset=utf-8');
require_once 'util/funzioni1.php';
$nome=$_POST['nome'];
$pieces = explode(" ", $nome);
$nome=$pieces[0];
$cogn="";
for($i=1;$i<count($pieces);$i++)
$cogn=$cogn.$temp[$i];
Cerca($nome,$cogn);
?>