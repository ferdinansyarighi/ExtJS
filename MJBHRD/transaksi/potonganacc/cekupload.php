<?PHP
include '../../main/koneksi.php';

$file 	= $_POST['file'];
$split 	= explode('.',$file);

// echo $split[0] . ' ' . $split[1];
// console.log( $split[0] + ' ' + $split[1] );

if ( $split[1] == 'txt' || $split[1] == 'TXT' 
		|| $split[1]=='php' || $split[1]=='PHP' 
		|| $split[1] == 'exe' || $split[1] == 'EXE' ) {
	$jml = 1;
}
else{
	$jml=0;
}
$a['jumlah']=$jml;
echo json_encode($a);

?>