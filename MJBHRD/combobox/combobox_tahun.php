<?php
	$tglskr=date('Y'); 
	
	$record = array();
	$record['DATA_VALUE']=$tglskr;
	$record['DATA_NAME']=$tglskr;
	$data[]=$record;

	$record = array();
	$record['DATA_VALUE']=$tglskr - 1;
	$record['DATA_NAME']=$tglskr - 1;
	$data[]=$record;
	
	$record = array();
	$record['DATA_VALUE']=$tglskr - 2;
	$record['DATA_NAME']=$tglskr - 2;
	$data[]=$record;
	
	echo json_encode($data);
?>