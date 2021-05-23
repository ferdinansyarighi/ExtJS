<?PHP
/* if ($db = ibase_connect('localhost/3051:E:\Accurate\MJB BETON BARU.gdb', 'guest',
  'guest')) {
    //ibase_close($db);
  } else {
    echo 'Connection failed.';
  } */
  
  /* $con=mysqli_connect("localhost","mjbtt","","mjbtt");
  if (mysqli_connect_errno()) {
  	echo "Failed to connect to MySQL: " . mysqli_connect_error();
  } else {
  	//mysqli_close($con);
  }   */
	$conn = odbc_connect('bioFinger', 'absen', 'absen123');
	if (!$conn) {
		echo "Connection MSSQL Black failed.";
		exit;
	}
  
  $con=oci_connect('apps','merak1234jaya','(DESCRIPTION = (ADDRESS = (PROTOCOL = TCP)(HOST = 192.168.0.18)(PORT = 1521)) (CONNECT_DATA = (SERVER = DEDICATED) (SID = MJBDB)))');
  if(!$con){
	echo "Tidak dapat terkoneksi ke database";
  } 
  
  $conHR=oci_connect('MJHR','MJHR1234MJHRJAYA','(DESCRIPTION = (ADDRESS = (PROTOCOL = TCP)(HOST = 192.168.0.18)(PORT = 1521)) (CONNECT_DATA = (SERVER = DEDICATED) (SID = MJBDB)))');
  if(!$conHR){
	echo "Tidak dapat terkoneksi ke database";
  } 
  
  
define('APP', 'TOMS');
define("APPCODE", 1);
define("PATHAPPD", "apps.merakjaya.co.id/MJBHRD");
define("PATHAPP", "/MJBHRD"); 
//define("PATHAPP", "http://192.168.0.58/MJBHRD"); 
  
?>