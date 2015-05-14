<?php
header('Content-type: text/html;charset=utf-8');
require 'provadb.php';
function Cerca($cercatonome,$cercatocogn){
$db=dbconn();
	if(strcmp("",$cercatocogn)!=0 && strcmp("",$cercatonome)!=0)
		$query="SELECT id_utenti,nome,cognome FROM Utenti where nome like '$cercatonome%' OR cognome like '$cercatocogn%' LIMIT 10;";
if(strcmp("",$cercatocogn)==0 && strcmp("",$cercatonome)!=0)
		$query="SELECT id_utenti,nome,cognome FROM Utenti where nome like '$cercatonome%' LIMIT 10;";

$sql = $db->prepare($query);
$sql->execute();  
while($res = $sql->fetch(PDO::FETCH_ASSOC))
$out[]=$res;
print(json_encode($out)); 
$db=null;
}
function Login($nome,$psw)
{
        try{
	$db=dbconn();
	$query="UPDATE `Utenti` SET `status`='YES' WHERE password like '$psw' AND  mail like '$nome';";
    $sql = $db->prepare($query);
	   		 $sql->execute();
		$query="SELECT * FROM Utenti where password like '$psw' AND mail like '$nome';";
		$sql = $db->prepare($query);
		$sql->execute(); 
		$num=$sql->rowCount();
		if ($num==1) {
			while($res = $sql->fetch(PDO::FETCH_ASSOC))
				$out[]=$res;
			print(json_encode($out));
                        
		} else {
			print('[{"status":"NO","code_err":"2","error":"utente non esiste"}]');
		}
      $db=null;
     }catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}
}
function Logout($nome,$psw){
	try{
	$db=dbconn();
	$query="UPDATE `Utenti` SET `status`='NO' WHERE password like '$psw' AND (nick like '$nome' or mail like '$nome');";
	$sql = $db->prepare($query);
	   		 $sql->execute();
	$num=$sql->rowCount();
	if ($num==1) {
		print('{"status":"YES","user":"'.$nome.'"}');
		}else {
		print('{"status":"NO"}');
	}
	$db=null;
	}catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
	}
}
function Registrazione($nome,$cognome,$nick,$mail,$psw,$citta,$dataN){
	try{
	$db=dbconn();
	$query="INSERT INTO `Utenti`( `nome`, `cognome`, `nick`, `mail`, `password`, `citta`, `dataN`) VALUES ('$nome','$cognome','$nick','$mail','$psw','$citta','$dataN');";
	$sql = $db->prepare($query);
	$sql->execute();
	$num=$sql->rowCount();
	if ($num==1) {
		print('{"code_err":"0","error":"Registrazione andata a buon fine"}');
	} else {
		print('{"code_err":"1","error":"utente esiste"}');
	}
	$db=null;
	}catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
	}
}
function insertCommento($mail,$psw,$mex,$idpost)
{
     try{
        $db=dbconn();
	$query="SELECT id_utenti FROM Utenti where mail like '$mail' and password like '$psw';";
$sql = $db->prepare($query);
$sql->execute();
$row=$sql->fetch();
	$id_utente=$row['id_utenti'];
        $text="[";
	$query="INSERT INTO `Commenti`(`id_post_com`, `id_utente_com`, `messaggio`) VALUES ('$idpost','$id_utente','$mex');";
	$sql = $db->prepare($query);
	   		 $sql->execute();
	$num=$sql->rowCount();
	if ($num==1) {
		$query="SELECT id_utente_com,messaggio,data,id_commento,id_post_com FROM Commenti where id_post_com='$idpost';";
		
		$sql = $db->prepare($query);
		$sql->execute();  
		$i=0;
                $num=$sql->rowCount();
        while($row=$sql->fetch()){
          if($num-1==$i)
          $text=$text.'{"utente":'.getUtente($row[0],$db).',"messaggio":"'.$row[1].'","data":"'.$row[2].'","id_commento":"'.$row[3].'","id_post_com":'.getPost($row[4],$db).'}';
          else
          $text=$text.'{"utente":'.getUtente($row[0],$db).',"messaggio":"'.$row[1].'","data":"'.$row[2].'","id_commento":"'.$row[3].'","id_post_com":'.getPost($row[4],$db).'},';
          $i++;
        }
      $text=$text."]";
      print($text);
	} else {
		print('{"code_err":"1","error":"utente esiste"}');
	}
	
      selectPosts($mail,$psw,$db);
	$db=null;
	}catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
	}
}
function deleteCommento($mail,$psw,$idcom,$idpost){
	try{
        $db=dbconn();
$text="[";
	$query="SELECT id_utenti FROM Utenti where mail like '$mail' and password like '$psw';";
	$sql = $db->prepare($query);
	$sql->execute();
	$row=$sql->fetch();
	$id_utente=$row[0];
	$query="DELETE FROM `Commenti` where `id_post_com`='$idpost' AND `id_utente_com`='$id_utente' AND `id_commento`='$idcom';";
	$sql = $db->prepare($query);
	   		 $sql->execute();
	$num=$sql->rowCount();
	if ($num==1) {
		$query="SELECT id_utente_com,messaggio,data,id_commento,id_post_com FROM Commenti where id_post_com=$idpost;";
		$sql = $db->prepare($query);
	    $sql->execute();
	
		$i=0;
        $num=$sql->rowCount();
        while($row=$sql->fetch()){
          if($num-1==$i)
          $text=$text.'{"utente":'.getUtente($row[0],$db).',"messaggio":"'.$row[1].'","data":"'.$row[2].'","id_commento":"'.$row[3].'","id_post_com":"'.$row[4].'"}';
          else
          $text=$text.'{"utente":'.getUtente($row[0],$db).',"messaggio":"'.$row[1].'","data":"'.$row[2].'","id_commento":"'.$row[3].'","id_post_com":"'.$row[4].'"},';
          $i++;
        }
      $text=$text."]";
      print($text);
      selectPosts($mail,$psw,$db);
	} 
	$db=null;
	}catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
	}
}
function insertPost($mail,$messaggio,$psw,$pathImages,$immThumb,$id_utente){
	try{
        $db=dbconn();
        $p="[";
	$pathImages=str_replace('./','', $pathImages);
        $immThumb=str_replace('./','',$immThumb);
	
	$query="INSERT INTO `Post`(`id_utente`, `messaggio`, `thumb_imm`, `immagine`) VALUES ('$id_utente','$messaggio','$immThumb','$pathImages')";
	$sql = $db->prepare($query);
	   		 $sql->execute();
	$num=$sql->rowCount();
	if ($num==1) {
		print '{"code":"0","result":';
		$query="SELECT id_post,id_utente,messaggio,thumb_imm,immagine,data,numLike FROM Post ORDER by id_post DESC Limit 50";
		$sql = $db->prepare($query);
	    $sql->execute();
	    $num=$sql->rowCount();
        $i=0;
        while($row=$sql->fetch()){
          if($num-1==$i)
          $p=$p.'{"id_post":"'.$row[0].'","utente_post":'.getUtente($row[1],$db).',"messaggio_post":"'.$row[2].'","num_Like":"'.$row[6].'","thumb_imm_post":"'.$row[3].'","immagine":"'.$row[4].'","data_post":"'.$row[5].'","commenti":'.getCommenti($row[0],$db).'}]';
          else
          $p=$p.'{"id_post":"'.$row[0].'","utente_post":'.getUtente($row[1],$db).',"messaggio_post":"'.$row[2].'","num_Like":"'.$row[6].'","thumb_imm_post":"'.$row[3].'","immagine":"'.$row[4].'","data_post":"'.$row[5].'","commenti":'.getCommenti($row[0],$db).'},';
        $i++;
        
	}
	print $p."}";

	file_put_contents(dirname(__FILE__)."/../post.json", $p);	
	} else {
		print('{"code":"1"}');
	}
	$db=null;
	}catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
	}
}


function insertLike($post_id,$mail,$psw){
	try{
	$db=dbconn();
	$query="SELECT id_utenti FROM Utenti where mail like '$mail' AND password='$psw';";
	$sql = $db->prepare($query);
	    $sql->execute();
	    $num=$sql->rowCount();
        $row=$sql->fetch();
	$id_utente=$row[0];
        if ($num==1) {
	$query="INSERT INTO `Like`(`Post_id`, `Utente_id`) VALUES ('$post_id','$id_utente');";
	$sql = $db->prepare($query);
	   		 $sql->execute();
	$num=$sql->rowCount();
	if ($num==1) {
             $query="SELECT COUNT(*) FROM `Like` where Post_id='$post_id';";
             $sql = $db->prepare($query);
	    	 $sql->execute();
	    	 $row=$sql->fetch();
			 $numL=$row[0];
	         $query="UPDATE `Post` SET `numLike`='$numL' WHERE `id_post`='$post_id'";
			 $sql = $db->prepare($query);
	   		 $sql->execute();
             print getLike($id_utente,$db,$numL);
	   		 selectPosts($mail,$psw,$db);
	} else {
		print('{"code_err":"1","error":"utente esiste"}');
	}
        }
	$db=null;
	}catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
	}
}
function deleteLike($post_id,$mail,$psw){
	try{
	$db=dbconn();
	$query="SELECT id_utenti FROM Utenti where mail like '$mail' and password like '$psw';";
	$sql = $db->prepare($query);
	    $sql->execute();
	$row=$sql->fetch();
	$id_utente=$row[0];
	$query="DELETE FROM `Like` where `Post_id`='$post_id' AND `Utente_id`='$id_utente';";
	$sql = $db->prepare($query);
	   		 $sql->execute();
	$num=$sql->rowCount();
	if ($num==1) {
		$query="SELECT COUNT(*) FROM `Like` where Post_id='$post_id';";
             $sql = $db->prepare($query);
	   		 $sql->execute();
			$row=$sql->fetch();
			 $numL=$row[0];
			 $db->beginTransaction();
			 $query="UPDATE `Post` SET `numLike`='$numL' WHERE `id_post`='$post_id'";
			 $sql = $db->exec($query);
			 $db->commit();
		print('{"code":"0","error":"Like inserita","numL":"'.$numL.'"}');
		
	   		 selectPosts($mail,$psw,$db);
	} else {
	$query="SELECT COUNT(*) FROM `Like` where Post_id='$post_id';";
             $sql = $db->prepare($query);
	   		 $sql->execute();
			$row=$sql->fetch();
			 $numL=$row[0];
		print('{"code":"1","error":"utente esiste","numL":"'.$numL.'"}');
	}
	$db=null;
	}catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
	}
}
function CalcolaLike($mail){
	try{
	$db=dbconn();
	$query="SELECT id_utenti FROM Utenti where mail like '$mail' or nick like '$mail';";
	$sql = $db->prepare($query);
	    $sql->execute();
	$row=$sql->fetch();
	$id_utente=$row[0];
	$query="SELECT COUNT(*) FROM `Like` where `Utente_id`='$id_utente';";
	$sql = $db->prepare($query);
	    $sql->execute();
	$row=$sql->fetch();
	$num=$sql->rowCount();
	$num_like=$row[0];
	if ($num==1) {
	    $db->beginTransaction();
		$query="UPDATE `Utenti` SET `numero_Like`=$num_like WHERE `id_utenti`=$id_utente;";
		$sql = $db->exec($query);
			 $db->commit();
	}
	$db=null;
	}catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
	}
}
function insertImmProfilo($mail,$psw,$path){
	try{
	$db=dbconn();
	$query="SELECT id_utenti,`imm_profilo` FROM Utenti where mail like '$mail' and password like '$psw';";
	$sql = $db->prepare($query);
	    $sql->execute();
	$row=$sql->fetch();
	$id_utente=$row[0];
	$pathVecchio=$row[1];
        $path=str_replace('./','', $path);
    
	$query="UPDATE `Utenti` SET `imm_profilo`='$path' WHERE `id_utenti`=$id_utente";
	$sql = $db->prepare($query);
	   		 $sql->execute();
	$num=$sql->rowCount();
	if ($num==1) {
		if (strcmp($pathVecchio,'profilo_imm/default.jpg') != 0) {
			unlink($pathVecchio);
		print('{"code":"0","error":"'.$path.'"}');	
		}
		else
		print('{"code":"0","error":"'.$path.'"}');
	} else {
		print('{"code":"0","error":"'.$pathVecchio.'"}');
	}
	$db=null;
	}catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
	}
}
function selectPost($mail,$psw){
	$db=dbconn();
	$query="SELECT id_utenti FROM Utenti where mail like '$mail' and password like '$psw';";
	$sql = $db->prepare($query);
	    $sql->execute();
	$row=$sql->fetch();
    $id_utente=$row[0];
	$num=$sql->rowCount();
	if ($num==1) {
          $p='[';
		$query="SELECT id_post,id_utente,messaggio,thumb_imm,immagine,data,numLike FROM Post ORDER by id_post DESC Limit 50";
		$sql = $db->prepare($query);
	    $sql->execute();
        $num=$sql->rowCount();
        $i=0;
        while($row=$sql->fetch()){
          if($num-1==$i)
          $p.= '{"id_post":"'.$row[0].'","utente_post":'.getUtente($row[1],$db).',"messaggio_post":"'.$row[2].'","num_Like":"'.$row[6].'","thumb_imm_post":"'.$row[3].'","immagine":"'.$row[4].'","data_post":"'.$row[5].'","commenti":'.getCommenti($row[0],$db).'}';
          else
          $p.= '{"id_post":"'.$row[0].'","utente_post":'.getUtente($row[1],$db).',"messaggio_post":"'.$row[2].'","num_Like":"'.$row[6].'","thumb_imm_post":"'.$row[3].'","immagine":"'.$row[4].'","data_post":"'.$row[5].'","commenti":'.getCommenti($row[0],$db).'},';
        $i++;
        }
	$p.=']';
	}
	$db=null;
	file_put_contents(dirname(__FILE__)."/../post.json", $p);
	return $id_utente;
}
function selectPosts($mail,$psw,$db){
	$query="SELECT id_utenti FROM Utenti where mail like '$mail' and password like '$psw';";
	$sql = $db->prepare($query);
	    $sql->execute();
	$row=$sql->fetch();
    $id_utente=$row[0];
	$num=$sql->rowCount();
	if ($num==1) {
          $p='[';
		$query="SELECT id_post,id_utente,messaggio,thumb_imm,immagine,data,numLike FROM Post ORDER by id_post DESC Limit 50";
		$sql = $db->prepare($query);
	    $sql->execute();
        $num=$sql->rowCount();
        $i=0;
        while($row=$sql->fetch()){
          if($num-1==$i)
          $p.= '{"id_post":"'.$row[0].'","utente_post":'.getUtente($row[1],$db).',"messaggio_post":"'.$row[2].'","num_Like":"'.$row[6].'","thumb_imm_post":"'.$row[3].'","immagine":"'.$row[4].'","data_post":"'.$row[5].'","commenti":'.getCommenti($row[0],$db).'}';
          else
          $p.= '{"id_post":"'.$row[0].'","utente_post":'.getUtente($row[1],$db).',"messaggio_post":"'.$row[2].'","num_Like":"'.$row[6].'","thumb_imm_post":"'.$row[3].'","immagine":"'.$row[4].'","data_post":"'.$row[5].'","commenti":'.getCommenti($row[0],$db).'},';
        $i++;
        }
	$p.=']';
	}
	
	file_put_contents(dirname(__FILE__)."/../post.json", $p);
	return $id_utente;
}
function selectAllPost($mail,$psw){
	$db=dbconn();
	$query="SELECT id_utenti FROM Utenti where mail like '$mail' and password like '$psw';";
	$sql = $db->prepare($query);
	    $sql->execute();
	$row=$sql->fetch();
    $id_utente=$row[0];
	$num=$sql->rowCount();
	if ($num==1) {
          $p='[';
		$query="SELECT id_post,id_utente,messaggio,thumb_imm,immagine,data,numLike FROM Post ORDER by id_post DESC Limit 50";
		$sql = $db->prepare($query);
	    $sql->execute();
        $num=$sql->rowCount();
        $i=0;
        while($row=$sql->fetch()){
          if($num-1==$i)
          $p.= '{"id_post":"'.$row[0].'","utente_post":'.getUtente($row[1],$db).',"messaggio_post":"'.$row[2].'","num_Like":"'.$row[6].'","thumb_imm_post":"'.$row[3].'","immagine":"'.$row[4].'","data_post":"'.$row[5].'","commenti":'.getCommenti($row[0],$db).'}';
          else
          $p.= '{"id_post":"'.$row[0].'","utente_post":'.getUtente($row[1],$db).',"messaggio_post":"'.$row[2].'","num_Like":"'.$row[6].'","thumb_imm_post":"'.$row[3].'","immagine":"'.$row[4].'","data_post":"'.$row[5].'","commenti":'.getCommenti($row[0],$db).'},';
        $i++;
        }
	$p.=']';
	}
	$db=null;
	return $p;
}
function getCommenti($id,$db){
     $text="[";
     $query="Select id_utente_com,messaggio,data,id_commento From Commenti where id_post_com=$id;";
     $sql = $db->prepare($query);
	    $sql->execute();
        $num=$sql->rowCount();
        $i=0;
        while($row=$sql->fetch()){
          if($num-1==$i)
          $text=$text.'{"utente":'.getUtente($row[0],$db).',"messaggio":"'.$row[1].'","data":"'.$row[2].'","id_commento":"'.$row[3].'"}';
          else
          $text=$text.'{"utente":'.getUtente($row[0],$db).',"messaggio":"'.$row[1].'","data":"'.$row[2].'","id_commento":"'.$row[3].'"},';
          $i++;
        }
      $text=$text."]";
return $text;
}
function getUtente($id,$db){
$text="[";
     $query="Select nome,cognome,imm_profilo From Utenti where id_utenti=$id;";
     $sql = $db->prepare($query);
	    $sql->execute();
        $num=$sql->rowCount();
        $i=0;
        while($row=$sql->fetch()){
          if($num-1==$i)
          $text=$text.'{"nome_utente":"'.$row[0].'","cognome_utente":"'.$row[1].'","imm_profilo":"'.$row[2].'","id_utente":"'.$id.'"}';
          else
          $text=$text.'{"nome_utente":"'.$row[0].'","cognome_utente":"'.$row[1].'","imm_profilo":"'.$row[2].'","id_utente":"'.$id.'"},';
          $i++;
        }
      $text=$text."]";
return $text;
}
function getLike($idUtente,$db,$numL){
	$text="[";
     $query="SELECT COUNT(*) FROM `Like` where `Utente_id`=$idUtente;";
     $sql = $db->prepare($query);
	    $sql->execute();
        $row=$sql->fetch();
     if($row[0]>0){
     $query="Select * FROM `Like` where `Utente_id`=$idUtente;";
     $sql = $db->prepare($query);
	    $sql->execute();
        $num=$sql->rowCount();
        $i=0;
        while($row=$sql->fetch()){
           if($num-1==$i)
          $text=$text.'{"utente":'.getPost($row[0],$db).',"id_post":"'.$row[0].'","data":"'.$row[2].'","numL":"'.$numL.'"}';
          else
          $text=$text.'{"utente":'.getPost($row[0],$db).',"id_post":"'.$row[0].'","data":"'.$row[2].'","numL":"'.$numL.'"},';
          $i++;
        }
      }
      $text=$text."]";
return $text;
}
function getPost($id,$db){
     $query="Select id_utente,thumb_imm,numLike From Post where id_post=$id;";
     $sql = $db->prepare($query);
	    $sql->execute();
	    
	 $row=$sql->fetch();
	 $id_utente=$row[0];
	 $imm=$row[1];
         $numlike=$row[2];
     $num=$sql->rowCount();
	 $text="{";
	 if($num==1){
        $query="Select nome,cognome,imm_profilo From Utenti where id_utenti=$id_utente;";
        
     $sql = $db->prepare($query);
	    $sql->execute();
	    
	 $row=$sql->fetch();
          $text=$text.'"id_post":"'.$id.'","nome":"'.$row[0].' '.$row[1].'","thumb_imm":"'.$imm.'","imm_profilo":"'.$row[2].'","num_Like":"'.$numlike.'"';
          
      }
      $text=$text."}";
return $text;
}
function getPrivateCommenti($id,$db){
	$text="[";
     $query="Select id_post_com,data,id_commento From Commenti where id_utente_com=$id;";
     $sql = $db->prepare($query);
	    $sql->execute();
	    
	 $num=$sql->rowCount();
        $i=0;
        while($row=$sql->fetch()){
          if($num-1==$i)
          $text=$text.'{"post":'.getPost($row[0]).',"data":"'.$row[1].'","id_comm":"'.$row[2].'"}';
          else
          $text=$text.'{"post":'.getPost($row[0]).',"data":"'.$row[1].'","id_comm":"'.$row[2].'"},';
          $i++;
        }
      $text=$text."]";
return $text;
}
function selectMyData($mail,$psw){
	try{
	$db=dbconn();
	$query="SELECT id_utenti FROM Utenti where mail like '$mail' and password like '$psw';";
	$sql = $db->prepare($query);
	    $sql->execute();
	    
	 $row=$sql->fetch();
	$id_utente=$row[0];
	print'{"id_utente":"'.$id_utente.'","myLike":'.getLike($id_utente,$db).',"mycomment":'.getPrivateCommenti($id_utente,$db).',"myPost":'.getAllPostFromUser($id_utente,$db).'}';
	
	$db=null;
	}catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
	}
}
function selectDataUser($id){
    try{
	$db=dbconn();
	$query="SELECT id_utenti,nome,cognome,imm_profilo FROM Utenti where id_utenti=$id ;";
	$sql = $db->prepare($query);
	    $sql->execute();
	    
	 $row=$sql->fetch();
	$id_utente=$row[0];
	print'{"id_utente":"'.$id_utente.'","nome":"'.$row[1].'","cognome":"'.$row[2].'","imm_profilo":"'.$row[3].'","Post":'.getAllPostFromUser($id_utente,$db).'}';

	$db=null;
	}catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
	}
}
function getAllPostFromUser($id,$db){
        $text='[';
		$query="SELECT id_post,id_utente,messaggio,thumb_imm,immagine,data,numLike FROM Post where id_utente=$id order by id_post DESC";
	$sql = $db->prepare($query);
	    $sql->execute();
	    
	 $num=$sql->rowCount();
        $i=0;
        while($row=$sql->fetch()){
          if($num-1==$i)
          $text=$text.'{"id_post":"'.$row[0].'","utente_post":'.getUtente($row[1],$db).',"messaggio_post":"'.$row[2].'","num_Like":"'.$row[6].'","thumb_imm_post":"'.$row[3].'","immagine":"'.$row[4].'","data_post":"'.$row[5].'","commenti":'.getCommenti($row[0],$db).'}';
          else
          $text=$text.'{"id_post":"'.$row[0].'","utente_post":'.getUtente($row[1],$db).',"messaggio_post":"'.$row[2].'","num_Like":"'.$row[6].'","thumb_imm_post":"'.$row[3].'","immagine":"'.$row[4].'","data_post":"'.$row[5].'","commenti":'.getCommenti($row[0],$db).'},';
        $i++;
        }
	$text=$text.']';
return $text;
}
//SELECT count(Post_id) FROM `Post` inner join `Like` on `Post`.id_post=`Like`.Post_id where `Like`.id_utente=2
//AND (data BETWEEN '2015-04-14 00:00:00' AND '2015-04-18 23:29:59')
function CalcolaClassifica(){
	try{
	$db=dbconn();
	$query="SELECT * FROM Post where numLike>0  ORDER by numLike DESC Limit 5";
	print '[';
	$sql = $db->prepare($query);
	    $sql->execute();
	    
	 $num=$sql->rowCount();
	$i=0;
        while($row=$sql->fetch()){
          if($num-1==$i)
          print '{"id_post":"'.$row[0].'","utente_post":'.getUtente($row[1],$db).',"numLike":"'.$row[2].'","messaggio_post":"'.$row[3].'","thumb_imm_post":"'.$row[4].'","immagine":"'.$row[5].'","data_post":"'.$row[6].'","commenti":'.getCommenti($row[0],$db).'}';
          else
          print '{"id_post":"'.$row[0].'","utente_post":'.getUtente($row[1],$db).',"numLike":"'.$row[2].'","messaggio_post":"'.$row[3].'","thumb_imm_post":"'.$row[4].'","immagine":"'.$row[5].'","data_post":"'.$row[6].'","commenti":'.getCommenti($row[0],$db).'},';
        $i++;
        }
	print ']';
	
	$db=null;
	}catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
	}
}

  ?>