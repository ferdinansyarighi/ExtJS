<?PHP
	$file 	= $_POST['file'];
	$split 	= explode('.',$file);

	if($split[1]=='txt' || $split[1]=='TXT' || $split[1]=='php' || $split[1]=='PHP' || $split[1]=='EXE'){
		$jml=1;
	}
	else{
		$jml=0;
	}

	$a['jumlah']=$jml;
	echo json_encode($a);
?>