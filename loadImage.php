<?php
include "SmartImageclass.php"; 
include "util/funzioni1.php";
ini_set("open_basedir","On");

  
$messaggio=htmlentities(trim($_POST['m']));
  $data = base64_decode(substr($_POST['file'],22));
$formImage = imagecreatefromstring($data);
$name=$_POST['name'];
$user=$_POST['user'];
$t=explode("\\", $user);
$dir=dirname(__FILE__)."/../../../httpdocs/blog/immagini/".$t[0].$t[1].$t[2].$name;
$dirt=dirname(__FILE__)."/../../../httpdocs/blog/immagini/thumb/".$t[0].$t[1].$t[2].$name;
if( file_exists("./immagini/".$t[0].$t[1].$t[2].$name)){
	echo '1';
}
else{

imagepng($formImage,"./immagini/".$t[0].$t[1].$t[2].$name);
make_thumb("./immagini/".$t[0].$t[1].$t[2].$name,"./immagini/".$t[0].$t[1].$t[2].$name,320,320);
make_thumb("./immagini/".$t[0].$t[1].$t[2].$name,"./immagini/thumb/".$t[0].$t[1].$t[2].$name,158,158);
if (!copy("./immagini/".$t[0].$t[1].$t[2].$name, $dir)) {
    echo "failed to copy 1...\n";
}
if (!copy("./immagini/thumb/".$t[0].$t[1].$t[2].$name, $dirt)) {
    echo "failed to copy 2...\n";
}
//insertPosts($t[0],$messaggio,"immagini/".$t[0].$t[1].$t[2].$name,"immagini/thumb/".$t[0].$t[1].$t[2].$name);
}
function make_thumb($src, $dest, $desired_width,$desired_heigth) {

	$img = new SmartImage($src); 
// Ridimensionamento e salvataggio su file 
// il valore true dice di tagliare l'immagine 
$img->resize($desired_width, $desired_heigth, true); 
$img->saveImage($dest, 85);
}
?>