<?php
header('Content-type: text/html;charset=utf-8');
require_once 'util/funzioni1.php';
$user=addslashes(trim($_POST['user']));
$psw=addslashes(trim($_POST['psw']));
$tipo=trim($_POST['tipo']);
if(strcmp($tipo,'selectAll')==0){
/*
$filename="dataagg.txt";
$fp = fopen($filename, "r");
$letto=fread($fp, filesize($filename));
fclose($fp);
if(strcmp($letto, "")==0){
		$fp=fopen($filename, "w");
		if (flock($fp, LOCK_EX)) { // Esegue un lock esclusivo
		    fwrite($fp, time().'');
		    flock($fp, LOCK_UN); // rilascia il lock
		} else {
		    echo "Non si riesce ad eseguire il lock del file !";
		}
		fclose($fp);
		$p=selectAllPost($user,$psw);
file_put_contents('post.json',$p);
}
else
	if ($letto+1800<=time()) {
		$fp=fopen($filename, "w");
		if (flock($fp, LOCK_EX)) { // Esegue un lock esclusivo
		    fwrite($fp, time().'');
		    flock($fp, LOCK_UN); // rilascia il lock
		} else {
		    echo "Non si riesce ad eseguire il lock del file !";
		}
		fclose($fp);
        $p=selectAllPost($user,$psw);
file_put_contents('post.json',$p);
}*/
print(file_get_contents('post.json'));


}

?>