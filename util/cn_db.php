<?php
function conect()
{
$db=mysql_connect("localhost","imoto_forum","100289ba]");
if (!$db) {
		die ('Non riesco a connettermi: ' . mysql_error());
	}
     	$db_selected = mysql_select_db("imotocro_forum",$db);
	if (!$db_selected) {
		die ("Errore nella selezione del database: " . mysql_error());
	}
	return $db;
}
?>