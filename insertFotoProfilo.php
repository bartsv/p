<?php
header('Content-type: text/html;charset=utf-8');
require_once 'util/funzioni1.php';
include "SmartImageclass.php"; 

$user=addslashes(trim($_POST['pippo']));
$psw=addslashes(trim($_POST['password']));
$id_utente=addslashes(trim($_POST['idU']));
$nome=addslashes(trim($_POST['nome']));
$cogn=addslashes(trim($_POST['cognome']));
$target_path  = "./profilo_imm/";
$target_path = $target_path .$id_utente.$nome.$cogn.$_FILES['uploadedfile']['name'];
if( file_exists($target_path))
unlink($target_path);
	if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)) 
	{
         make_thumb($target_path, $target_path, 100);
        insertImmProfilo($user,$psw,$target_path);
	} 
	else
	{
	    echo '{"code":"1"}';
	}

function make_thumb($src, $dest, $desired_width) {

	$img = new SmartImage($src); 
// Ridimensionamento e salvataggio su file 
// il valore true dice di tagliare l'immagine 
$img->resize(130, 130, true); 
$img->saveImage($dest, 85);
}
?>