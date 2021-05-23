<?PHP
include '../../main/koneksi.php';

$file 	= $_POST['file'];
$split 	= explode('.',$file);
if($split[1]=='XLS' || $split[1]=='xls'){
	$jml=0;
}
else{
	$jml=1;
}
$a['jumlah']=$jml;
echo json_encode($a);

?>