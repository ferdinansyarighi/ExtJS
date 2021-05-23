<?php
//echo date('m');exit;
  //session_start();
////phpinfo();
$conn = oci_connect('apps','merak1234jaya','(DESCRIPTION = (ADDRESS = (PROTOCOL = TCP)(HOST = 192.168.0.18)(PORT = 1521)) (CONNECT_DATA = (SERVER = DEDICATED) (SID = MJBDB)))');
	if (!$conn) {
		$e = oci_error();
		trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
	}

/* $sysConf = file_get_contents("S:\DATABASE\systemConfig.ini");
//$sysConf = file_get_contents("D:\dataSource\MBI\systemConfig.ini");
$rawMcode = stristr($sysConf, 'machinecode=');
$mCode = substr($rawMcode, 12,2);
echo $mCode;exit; */
	
class MyDB extends SQLite3{
  function __construct(){
     //$this->open('D:\APPROD\DATABASE\AP3500_PLUS.sqlite');
     //$this->open('W:\AP3500_PLUS.SQLite');
     //$this->open('\\40.17.1.4\approd\DATABASE\AP3500_PLUS.SQLite');
	 
     //$this->open('C:\Users\Administrator.MERAKJAYA\AppData\AP3500_PLUS.SQLiteRoaming\Microsoft\Windows\Network Shortcuts\DATABASEdwatra\AP3500_PLUS.SQLite');
     //$this->open('W:\DATABASE\AP3500_PLUS.SQLite');
     $this->open('R:\AP3500_PLUS.SQLite');
     //$this->open('D:\dataSource\AP3500_PLUS');
	 
  }
}
//echo 'asdas';exit;
$db = new MyDB();
//$db = new SQLite3('C:\Users\Administrator.MERAKJAYA\AppData\Roaming\Microsoft\Windows\Network Shortcuts\DATABASEdwatra\AP3500_PLUS.SQLite');
//$db = new SQLite3('S:\DATABASE\AP3500_PLUS.SQLite');
if(!$db){
  echo $db->lastErrorMsg();
} else {
  //echo "Opened database MySQLITE successfully\n";
}

//echo '<br/>'.realpath("C:\Users\Administrator.MERAKJAYA\AppData\Roaming\Microsoft\Windows\Network Shortcuts\DATABASEdwatra\AP3500_PLUS.SQLite").'<br/>';
//exit;
//$sysConf = file_get_contents("D:\Data Program\Formula MBI\mbi\systemConfig.ini");
/* $sysConf = file_get_contents("systemConfig.ini");
$rawMcode = stristr($sysConf, 'machinecode=');
$mCode = substr($rawMcode, 12,2);

echo 'dan Koneksi Oracle Berhasil. - OK - '.$mCode;exit;
$queryAction = "SELECT DISTINCT FORMULA_ID, BPO_ID, upper(SYNC_TYPE), FORMULA_CODE FROM MJDMI.MJ_FORMULA_ASP_V WHERE BPO_ID = $mCode ORDER BY FORMULA_ID ";
$resultAction = oci_parse($conn, $queryAction);
oci_execute($resultAction);
while($rowAction = oci_fetch_row($resultAction)){
	$formula_id = $rowAction[0];
	$bpo_id = $rowAction[1];
	$sync_type = $rowAction[2];
	$formula_code = $rowAction[3];
}
echo 'Koneksi Oracle Berhasil. - '.$formula_id.'OK';exit; */
//$sql = "SELECT sFOR_CODE, sFOR_NAME FROM TBL_FORMULA";
//sqlite_finalize(xxx);
//$sql = "SELECT * FROM sqlite_master";
$sql = "SELECT name FROM 
   (SELECT * FROM sqlite_master UNION ALL
    SELECT * FROM sqlite_temp_master)
WHERE type='table'
ORDER BY name";
//$sql = "INSERT INTO TBL_FORMULA VALUES('0073', 'TES', 111, 222, 333, 444, 555, 0,0,0,0, 1010, 0,0,0,0,0,0,0,0,0,0,0,0,0,0, '', 'Admin', date('now'), 0)";
//$sql = "UPDATE TBL_FORMULA SET sLAST_DT=datetime('now','localtime')  WHERE sFOR_CODE = '0073'"; 
$ret = $db->query($sql);
while($row = $ret->fetchArray(SQLITE3_ASSOC) ){
  echo "ID = ". $row['name'] . "\n";
}
echo "Operation done successfully\n";
$db->close();
	
?>



