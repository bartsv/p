<?php
function dbconn(){
// collegamento al database
$col = 'mysql:host=localhost;dbname=imotocro_forum';

// blocco try per il lancio dell'istruzione
try {
  // connessione tramite creazione di un oggetto PDO
  $db = new PDO($col , 'imoto_forum', '100289ba]');
return $db;
}
// blocco catch per la gestione delle eccezioni
catch(PDOException $e) {
  // notifica in caso di errorre
  echo 'Attenzione: '.$e->getMessage();
return null;
}
}
?>