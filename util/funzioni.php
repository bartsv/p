<?php

header('Content-type: text/html;charset=utf-8');
require_once 'cn_db.php';
function Cerca($cercatonome,$cercatocogn){
$db=conect();
	if(strcmp("",$cercatocogn)!=0 && strcmp("",$cercatonome)!=0)
		$query="SELECT id_utenti,nome,cognome FROM Utenti where nome like '$cercatonome%' OR cognome like '$cercatocogn%' LIMIT 10;";
if(strcmp("",$cercatocogn)==0 && strcmp("",$cercatonome)!=0)
		$query="SELECT id_utenti,nome,cognome FROM Utenti where nome like '$cercatonome%' LIMIT 10;";
		$res=mysql_query($query);
	
			while($e=mysql_fetch_assoc($res))
	        	$output[]=$e;
			print(json_encode($output));	
mysql_close($db);}

function Login($nome,$psw)
{
	$db=conect();
	$query="UPDATE `Utenti` SET `status`='YES' WHERE password like '$psw' AND  mail like '$nome';";
                        $res=mysql_query($query);
		$query="SELECT * FROM Utenti where password like '$psw' AND mail like '$nome';";
		$res=mysql_query($query);
		$num=mysql_num_rows($res);
		if ($num==1) {
			while($e=mysql_fetch_assoc($res))
	        	$output[]=$e;
			print(json_encode($output));
                        
		} else {
			print('[{"status":"NO","code_err":"2","error":"utente non esiste"}]');
		}mysql_close($db);
	
}
function Logout($nome,$psw){
	$db=conect();
	$query="UPDATE `Utenti` SET `status`='NO' WHERE password like '$psw' AND (nick like '$nome' or mail like '$nome');";
	$res=mysql_query($query);
	$num=mysql_affected_rows();
	if ($num==1) {
		print('{"status":"YES","user":"'.$nome.'"}');
		}else {
		print('{"status":"NO"}');
	}mysql_close($db);
	
}
function Registrazione($nome,$cognome,$nick,$mail,$psw,$citta,$dataN){
	$db=conect();
	$query="INSERT INTO `Utenti`( `nome`, `cognome`, `nick`, `mail`, `password`, `citta`, `dataN`) VALUES ('$nome','$cognome','$nick','$mail','$psw','$citta','$dataN');";
	
	$res=mysql_query($query);
	$num=mysql_affected_rows();
	if ($num==1) {
		print('{"code_err":"0","error":"Registrazione andata a buon fine"}');
	} else {
		print('{"code_err":"1","error":"utente esiste"}');
	}mysql_close($db);
}
function RegistrazioneFB($id,$nome,$cognome,$nick,$mail,$psw,$citta,$dataN){
	$db=conect();
	$query="INSERT INTO `Utenti`( `id_utenti`,`nome`, `cognome`, `nick`, `mail`, `password`, `citta`, `dataN`) VALUES ($id,'$nome','$cognome','$nick','$mail','$psw','$citta','$dataN');";
	echo $query;
	$res=mysql_query($query);
	$num=mysql_affected_rows();
	if ($num==1) {
		print('{"code_err":"0","error":"Registrazione andata a buon fine"}');
	} else {
		print('{"code_err":"1","error":"utente esiste"}');
	}mysql_close($db);
}
function insertCommento($mail,$psw,$mex,$idpost)
{
        $db=conect();
	$query="SELECT id_utenti FROM Utenti where mail like '$mail' and password like '$psw';";
	$res=mysql_query($query);
	$num=mysql_num_rows($res);
	$row=mysql_fetch_row($res);
	$id_utente=$row[0];
        $text="[";
	$query="INSERT INTO `Commenti`(`id_post_com`, `id_utente_com`, `messaggio`) VALUES ('$idpost','$id_utente','$mex');";
	$res=mysql_query($query);
	$num=mysql_affected_rows();
	if ($num==1) {
		$query="SELECT id_utente_com,messaggio,data,id_commento,id_post_com FROM Commenti where id_post_com='$idpost';";
		$res=mysql_query($query);
		$i=0;
                $num=mysql_num_rows($res);
        while($row=mysql_fetch_row($res)){
          if($num-1==$i)
          $text=$text.'{"utente":'.getUtente($row[0]).',"messaggio":"'.$row[1].'","data":"'.$row[2].'","id_commento":"'.$row[3].'","id_post_com":'.getPost($row[4]).'}';
          else
          $text=$text.'{"utente":'.getUtente($row[0]).',"messaggio":"'.$row[1].'","data":"'.$row[2].'","id_commento":"'.$row[3].'","id_post_com":'.getPost($row[4]).'},';
          $i++;
        }
      $text=$text."]";
      print($text);
	} else {
		print('{"code_err":"1","error":"utente esiste"}');
	}
	mysql_close($db);
}
function deleteCommento($mail,$psw,$idcom,$idpost){
        $db=conect();
$text="[";
	$query="SELECT id_utenti FROM Utenti where mail like '$mail' and password like '$psw';";
	$res=mysql_query($query);
        $num=mysql_num_rows($res);
	$row=mysql_fetch_row($res);
	$id_utente=$row[0];
	$query="DELETE FROM `Commenti` where `id_post_com`='$idpost' AND `id_utente_com`='$id_utente' AND `id_commento`='$idcom';";
	$res=mysql_query($query);
	$num=mysql_affected_rows();
	if ($num==1) {
		$query="SELECT id_utente_com,messaggio,data,id_commento,id_post_com FROM Commenti where id_post_com=$idpost;";
		$res=mysql_query($query);
		$i=0;
                $num=mysql_num_rows($res);
        while($row=mysql_fetch_row($res)){
          if($num-1==$i)
          $text=$text.'{"utente":'.getUtente($row[0]).',"messaggio":"'.$row[1].'","data":"'.$row[2].'","id_commento":"'.$row[3].'","id_post_com":"'.$row[4].'"}';
          else
          $text=$text.'{"utente":'.getUtente($row[0]).',"messaggio":"'.$row[1].'","data":"'.$row[2].'","id_commento":"'.$row[3].'","id_post_com":"'.$row[4].'"},';
          $i++;
        }
      $text=$text."]";
      print($text);
	} 
	mysql_close($db);
}
function insertPost($mail,$messaggio,$psw,$pathImages,$immThumb,$id_utente){
	
        $db=conect();
	$pathImages=str_replace('./','', $pathImages);
        $immThumb=str_replace('./','',$immThumb);
	$query="INSERT INTO `Post`(`id_utente`, `messaggio`, `thumb_imm`, `immagine`) VALUES ('$id_utente','$messaggio','$immThumb','$pathImages')";
	$res=mysql_query($query);
	$num=mysql_affected_rows();
	if ($num==1) {
print '{"code":"0","result":[';
		$query="SELECT id_post,id_utente,messaggio,thumb_imm,immagine,data,numLike FROM Post ORDER by id_post DESC Limit 50";
	
	$res=mysql_query($query);
        $num=mysql_num_rows($res);
        $i=0;
        while($row=mysql_fetch_row($res)){
          if($num-1==$i)
          print '{"id_post":"'.$row[0].'","utente_post":'.getUtente($row[1]).',"messaggio_post":"'.$row[2].'","num_Like":"'.$row[6].'","thumb_imm_post":"'.$row[3].'","immagine":"'.$row[4].'","data_post":"'.$row[5].'","commenti":'.getCommenti($row[0]).'}';
          else
          print '{"id_post":"'.$row[0].'","utente_post":'.getUtente($row[1]).',"messaggio_post":"'.$row[2].'","num_Like":"'.$row[6].'","thumb_imm_post":"'.$row[3].'","immagine":"'.$row[4].'","data_post":"'.$row[5].'","commenti":'.getCommenti($row[0]).'},';
        $i++;
        
	}
	print "]}";
	} else {
		print('{"code":"1"}');
	}
	mysql_close($db);
}


function insertLike($post_id,$mail,$psw){
	$db=conect();
	$query="SELECT id_utenti FROM Utenti where mail like '$mail' AND password='$psw';";
	$res=mysql_query($query);
	$row=mysql_fetch_row($res);
        $num=mysql_num_rows($res);
        if ($num==1) {
	$id_utente=$row[0];
	$query="INSERT INTO `Like`(`Post_id`, `Utente_id`) VALUES ('$post_id','$id_utente');";
	$res=mysql_query($query);
	$num=mysql_affected_rows();
	if ($num==1) {
             print getLike($id_utente);
             $query="SELECT COUNT(*) FROM `Like` where Post_id='$post_id';";
             $res=mysql_query($query);
			 $row=mysql_fetch_row($res);
			 $numL=$row[0];
			 $query="UPDATE `Post` SET `numLike`='$numL' WHERE `id_post`='$post_id'";
			 $res=mysql_query($query);
	} else {
		print('{"code_err":"1","error":"utente esiste"}');
	}
        }
	mysql_close($db);
}
function deleteLike($post_id,$mail,$psw){
	$db=conect();
	$query="SELECT id_utenti FROM Utenti where mail like '$mail' and password like '$psw';";
	$res=mysql_query($query);
	$row=mysql_fetch_row($res);
	$id_utente=$row[0];
	$query="DELETE FROM `Like` where `Post_id`='$post_id' AND `Utente_id`='$id_utente';";
	$res=mysql_query($query);
	$num=mysql_affected_rows();
	if ($num==1) {
		$query="SELECT COUNT(*) FROM `Like` where Post_id='$post_id';";
             $res=mysql_query($query);
			 $row=mysql_fetch_row($res);
			 $numL=$row[0];
			 $query="UPDATE `Post` SET `numLike`='$numL' WHERE `id_post`='$post_id'";
			 $res=mysql_query($query);
		print('{"code":"0","error":"Like inserita"}');
	} else {
		print('{"code":"1","error":"utente esiste"}');
	}
	mysql_close($db);
}
function deleteUtente($mail,$psw){
	$db=conect();
	$query="SELECT id_utenti,imm_profilo FROM Utenti where mail like '$mail';";
	$res=mysql_query($query);
	$row=mysql_fetch_row($res);
	$id_utente=$row[0];
	$pathImmProfilo=$row[1];
	$query="DELETE FROM `Utenti` where `mail` LIKE '$mail' AND `id_utenti`='$id_utente' AND `password` LIKE '$psw';";
	$res=mysql_query($query);
	$num=mysql_affected_rows($res);
	$query="DELETE FROM `Post` where `id_utente`='$id_utente';";
	$res=mysql_query($query);
	$num=mysql_affected_rows($res);
	$query="DELETE FROM `Like` where `Utenti_id`='$id_utente';";
	$res=mysql_query($query);
	$num=mysql_affected_rows($res);
	if ($num==1) {
		print('{"code_ok":"0","error":"Profilo eliminato"}');
	} else {
		print('{"code_err":"1","error":"utente esiste"}');
	}
	unlink($pathImmProfilo);
	mysql_close($db);
}
function CalcolaLike($mail){
	$db=conect();
	$query="SELECT id_utenti FROM Utenti where mail like '$mail' or nick like '$mail';";
	$res=mysql_query($query);
	$row=mysql_fetch_row($res);
	$id_utente=$row[0];
	$query="SELECT COUNT(*) FROM `Like` where `Utente_id`='$id_utente';";
	$res=mysql_query($query);
	$num=mysql_num_rows($res);
	$row=mysql_fetch_row($res);
	$num_like=$row[0];
	if ($num==1) {
		$query="UPDATE `Utenti` SET `numero_Like`=$num_like WHERE `id_utenti`=$id_utente;";
		$res=mysql_query($query);
	}
	mysql_close($db);
}
function insertImmProfilo($mail,$psw,$path){
	$db=conect();
	$query="SELECT id_utenti,`imm_profilo` FROM Utenti where mail like '$mail' and password like '$psw';";
	$res=mysql_query($query);
	$row=mysql_fetch_row($res);
	$id_utente=$row[0];
	$pathVecchio=$row[1];
        $path=str_replace('./','', $path);
	$query="UPDATE `Utenti` SET `imm_profilo`='$path' WHERE `id_utenti`=$id_utente";
	$res=mysql_query($query);
	$num=mysql_affected_rows();
	if ($num==1) {
		if (strcmp($pathVecchio,'profilo_imm/default.jpg') != 0) {
			//unlink($pathVecchio);
		print('{"code":"0","error":"'.$path.'"}');	
		}
		else
		print('{"code":"0","error":"'.$path.'"}');
	} else {
		print('{"code":"0","error":"'.$pathVecchio.'"}');
	}
	mysql_close($db);
}
function selectPost($mail,$psw){
	$db=conect();
	$query="SELECT id_utenti FROM Utenti where mail like '$mail' and password like '$psw';";
	$res=mysql_query($query);
	$num=mysql_num_rows($res);
    $row=mysql_fetch_row($res);
    $id_utente=$row[0];
	if ($num==1) {
          $p='[';
		$query="SELECT id_post,id_utente,messaggio,thumb_imm,immagine,data,numLike FROM Post ORDER by id_post DESC Limit 50";
	$res=mysql_query($query);
        $num=mysql_num_rows($res);
        $i=0;
        while($row=mysql_fetch_row($res)){
          if($num-1==$i)
          $p.= '{"id_post":"'.$row[0].'","utente_post":'.getUtente($row[1]).',"messaggio_post":"'.$row[2].'","num_Like":"'.$row[6].'","thumb_imm_post":"'.$row[3].'","immagine":"'.$row[4].'","data_post":"'.$row[5].'","commenti":'.getCommenti($row[0]).'}';
          else
          $p.= '{"id_post":"'.$row[0].'","utente_post":'.getUtente($row[1]).',"messaggio_post":"'.$row[2].'","num_Like":"'.$row[6].'","thumb_imm_post":"'.$row[3].'","immagine":"'.$row[4].'","data_post":"'.$row[5].'","commenti":'.getCommenti($row[0]).'},';
        $i++;
        }
	$p.=']';
	}
	mysql_close($db);
file_put_contents("post.json", $p);
	return $id_utente;
}
function getCommenti($id){
     $text="[";
     $query="Select id_utente_com,messaggio,data,id_commento From Commenti where id_post_com=$id;";
     $res=mysql_query($query);
     $num=mysql_num_rows($res);
        $i=0;
        while($row=mysql_fetch_row($res)){
          if($num-1==$i)
          $text=$text.'{"utente":'.getUtente($row[0]).',"messaggio":"'.$row[1].'","data":"'.$row[2].'","id_commento":"'.$row[3].'"}';
          else
          $text=$text.'{"utente":'.getUtente($row[0]).',"messaggio":"'.$row[1].'","data":"'.$row[2].'","id_commento":"'.$row[3].'"},';
          $i++;
        }
      $text=$text."]";
return $text;
}
function getUtente($id){
$text="[";
     $query="Select nome,cognome,imm_profilo From Utenti where id_utenti=$id;";
     $res=mysql_query($query);
     $num=mysql_num_rows($res);
        $i=0;
        while($row=mysql_fetch_row($res)){
          if($num-1==$i)
          $text=$text.'{"nome_utente":"'.$row[0].'","cognome_utente":"'.$row[1].'","imm_profilo":"'.$row[2].'","id_utente":"'.$id.'"}';
          else
          $text=$text.'{"nome_utente":"'.$row[0].'","cognome_utente":"'.$row[1].'","imm_profilo":"'.$row[2].'","id_utente":"'.$id.'"},';
          $i++;
        }
      $text=$text."]";
return $text;
}
function getLike($idUtente){
	$text="[";
     $query="SELECT COUNT(*) FROM `Like` where `Utente_id`=$idUtente;";
     $res=mysql_query($query);
     $row=mysql_fetch_row($res);
     if($row[0]>0){
     $query="Select * FROM `Like` where `Utente_id`=$idUtente;";
     $res=mysql_query($query);
     $num=mysql_num_rows($res);
        $i=0;
        while($row=mysql_fetch_row($res)){
           if($num-1==$i)
          $text=$text.'{"utente":'.getPost($row[0]).',"id_post":"'.$row[0].'","data":"'.$row[2].'"}';
          else
          $text=$text.'{"utente":'.getPost($row[0]).',"id_post":"'.$row[0].'","data":"'.$row[2].'"},';
          $i++;
        }
      }
      $text=$text."]";
return $text;
}
function getPost($id){
     $query="Select id_utente,thumb_imm,numLike From Post where id_post=$id;";
     $res=mysql_query($query);
	 $row=mysql_fetch_row($res);
	 $id_utente=$row[0];
	 $imm=$row[1];
         $numlike=$row[2];
     $num=mysql_num_rows($res);
	 $text="{";
	 if($num==1){
        $query="Select nome,cognome,imm_profilo From Utenti where id_utenti=$id_utente;";
        
     $res=mysql_query($query);
      $row=mysql_fetch_row($res);
          $text=$text.'"id_post":"'.$id.'","nome":"'.$row[0].' '.$row[1].'","thumb_imm":"'.$imm.'","imm_profilo":"'.$row[2].'","num_Like":"'.$numlike.'"';
          
      }
      $text=$text."}";
return $text;
}
function getPrivateCommenti($id){
	$text="[";
     $query="Select id_post_com,data,id_commento From Commenti where id_utente_com=$id;";
     $res=mysql_query($query);
     $num=mysql_num_rows($res);
        $i=0;
        while($row=mysql_fetch_row($res)){
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
	$db=conect();
	$query="SELECT id_utenti FROM Utenti where mail like '$mail' and password like '$psw';";
	$res=mysql_query($query);
	$row=mysql_fetch_row($res);
	$id_utente=$row[0];
	print'{"id_utente":"'.$id_utente.'","myLike":'.getLike($id_utente).',"mycomment":'.getPrivateCommenti($id_utente).',"myPost":'.getAllPostFromUser($id_utente).'}';
	mysql_close($db);
}
function selectDataUser($id){
	$db=conect();
	$query="SELECT id_utenti,nome,cognome,imm_profilo FROM Utenti where id_utenti=$id ;";
	$res=mysql_query($query);
	$row=mysql_fetch_row($res);
	$id_utente=$row[0];
	print'{"id_utente":"'.$id_utente.'","nome":"'.$row[1].'","cognome":"'.$row[2].'","imm_profilo":"'.$row[3].'","Post":'.getAllPostFromUser($id_utente).'}';
	mysql_close($db);
}
function getAllPostFromUser($id){
        $text='[';
		$query="SELECT id_post,id_utente,messaggio,thumb_imm,immagine,data,numLike FROM Post where id_utente=$id order by id_post DESC";
	$res=mysql_query($query);
        $num=mysql_num_rows($res);
        $i=0;
        while($row=mysql_fetch_row($res)){
          if($num-1==$i)
          $text=$text.'{"id_post":"'.$row[0].'","utente_post":'.getUtente($row[1]).',"messaggio_post":"'.$row[2].'","num_Like":"'.$row[6].'","thumb_imm_post":"'.$row[3].'","immagine":"'.$row[4].'","data_post":"'.$row[5].'","commenti":'.getCommenti($row[0]).'}';
          else
          $text=$text.'{"id_post":"'.$row[0].'","utente_post":'.getUtente($row[1]).',"messaggio_post":"'.$row[2].'","num_Like":"'.$row[6].'","thumb_imm_post":"'.$row[3].'","immagine":"'.$row[4].'","data_post":"'.$row[5].'","commenti":'.getCommenti($row[0]).'},';
        $i++;
        }
	$text=$text.']';
return $text;
}
//SELECT count(Post_id) FROM `Post` inner join `Like` on `Post`.id_post=`Like`.Post_id where `Like`.id_utente=2
function CalcolaClassifica(){
	$db=conect();
	$query="SELECT * FROM Post where numLike>0 AND (data BETWEEN '2015-04-14 00:00:00' AND '2015-04-18 23:29:59') ORDER by numLike DESC Limit 5";
	print '[';
	$res=mysql_query($query);
        $num=mysql_num_rows($res);
	$i=0;
        while($row=mysql_fetch_row($res)){
          if($num-1==$i)
          print '{"id_post":"'.$row[0].'","utente_post":'.getUtente($row[1]).',"numLike":"'.$row[2].'","messaggio_post":"'.$row[3].'","thumb_imm_post":"'.$row[4].'","immagine":"'.$row[5].'","data_post":"'.$row[6].'","commenti":'.getCommenti($row[0]).'}';
          else
          print '{"id_post":"'.$row[0].'","utente_post":'.getUtente($row[1]).',"numLike":"'.$row[2].'","messaggio_post":"'.$row[3].'","thumb_imm_post":"'.$row[4].'","immagine":"'.$row[5].'","data_post":"'.$row[6].'","commenti":'.getCommenti($row[0]).'},';
        $i++;
        }
	print ']';
	mysql_close($db);
}

  ?>