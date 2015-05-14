<?php
header('Content-type: text/html;charset=utf-8');
include "SmartImageclass.php"; 
require_once 'util/funzioni1.php';
$mex=addslashes(trim($_POST['mex']));
$user=addslashes(trim($_POST['pippo']));
$psw=addslashes(trim($_POST['password']));
htmlentities($mex);
$db=dbconn();
$query="SELECT id_utenti,nick FROM Utenti where mail like '$user' and password like '$psw';";
	$sql=$db->prepare($query);
	$sql->execute();
	$row=$sql->fetch();
	$id_utente=$row[0];
        $nick=$row[1];
        $num=$sql->rowCount();

$db=null;
if($num==1){
$target_path  = "./immagini/";
$target_path = $target_path .$id_utente.$nick.$_FILES['uploadedfile']['name'];
$target_path_imm="./immagini/thumb/".$id_utente.$nick.$_FILES['uploadedfile']['name'];
if( file_exists($target_path)){
	echo '{"code":"1"}';
}
else{
	if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)) 
	{
	make_thumb($target_path, $target_path_imm, 158);
	insertPost($user,$mex,$psw,$target_path,$target_path_imm,$id_utente);
	} 
	else
	{
	    echo '{"code":"1"}';
	}
}
}
function make_thumb($src, $dest, $desired_width) {

	$img = new SmartImage($src); 
// Ridimensionamento e salvataggio su file 
// il valore true dice di tagliare l'immagine 
$img->resize(160, 160, true); 
$img->saveImage($dest, 85);
}
?>