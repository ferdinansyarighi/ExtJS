<?php
  
//koneksi Oracle
$conn = oci_connect('apps','merak1234jaya','(DESCRIPTION = (ADDRESS = (PROTOCOL = TCP)(HOST = 192.168.0.18)(PORT = 1521)) (CONNECT_DATA = (SERVER = DEDICATED) (SID = MJBDB)))');
	if (!$conn) {
		$e = oci_error();
		trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
	}

//koneksi SQLite
class MyDB extends SQLite3{
  function __construct(){
	 //file database SQLite Plant porong
     $this->open('S:\DATABASE\AP3500_PLUS.SQLite');
	 
  }
}
$db = new MyDB();
if(!$db){
  echo $db->lastErrorMsg();
} else {
  echo "Opened database MySQLITE successfully\n";
}

//Tes ambil data SQLite
$sql = "SELECT name FROM 
   (SELECT * FROM sqlite_master UNION ALL
    SELECT * FROM sqlite_temp_master)
WHERE type='table'
ORDER BY name";
$ret = $db->query($sql);
while($row = $ret->fetchArray(SQLITE3_ASSOC) ){
  echo "ID = ". $row['name'] . "\n";
}

?>