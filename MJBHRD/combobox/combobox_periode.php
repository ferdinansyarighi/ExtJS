<?php
include '../main/koneksi.php';

$tglskr=date('Y-m-d'); 
$tahunskr=date('Y'); 
$bulanskr=date('m'); 
$hariskr=date('d'); 

$tahunsebelum=$tahunskr - 1; 
$bulansebelum=$bulanskr - 1; 
$harisebelum=$hariskr - 1; 

$tahunsesudah=$tahunskr + 1; 
$bulansesudah=$bulanskr + 1; 
$harisesudah=$hariskr + 1; 

$tahundipakai=0; 
$bulandipakai=0; 
$haridipakai=0; 

$tahundipakai2=0; 
$bulandipakai2=0; 
$haridipakai2=0; 
	
if($hariskr >= 21){
	if(strlen($bulansesudah)==1)
		$bulansesudah="0" . $bulansesudah;
	$periode1 = $tahunskr . "-" . $bulanskr . "-21";
	$tahundipakai=$tahunskr; 
	$bulandipakai=$bulanskr; 
	$haridipakai=21; 
	if($bulanskr=='12'){
		$periode2 = $tahunsesudah . "-01-20";
	} else {
		$periode2 = $tahunskr . "-" . $bulansesudah . "-20";
	}
} else {
	if(strlen($bulansebelum)==1)
		$bulansebelum="0" . $bulansebelum;
	if($bulanskr=='01'){
		$periode1 = $tahunsebelum . "-12-21";
		$tahundipakai=$tahunsebelum; 
		$bulandipakai=12; 
		$haridipakai=21;
	} else {
		$periode1 = $tahunskr . "-" . $bulansebelum . "-21";
		$tahundipakai=$tahunskr; 
		$bulandipakai=$bulansebelum; 
		$haridipakai=21;
	}
	$periode2 = $tahunskr . "-" . $bulanskr . "-20";
}
$record = array();
$record['DATA_VALUE']=$periode1 . ' s/d ' . $periode2;
$record['DATA_NAME']=$periode1 . ' s/d ' . $periode2;
$data[]=$record;

if($bulandipakai=='01'){
	$tahundipakai2 = $tahundipakai - 1;
	$bulandipakai2 = '12'; 
	$periode1 = $tahundipakai2 . "-12-21";
} else {
	$tahundipakai2 = $tahundipakai;
	$bulandipakai2 = $bulandipakai - 1; 
	if(strlen($bulandipakai2)==1)
		$bulandipakai2="0" . $bulandipakai2;
	$periode1 = $tahundipakai2 . "-" . $bulandipakai2 . "-21";
}
$periode2 = $tahundipakai . "-" . $bulandipakai . "-20";

$record = array();
$record['DATA_VALUE']=$periode1 . ' s/d ' . $periode2;
$record['DATA_NAME']=$periode1 . ' s/d ' . $periode2;
$data[]=$record;

if($bulandipakai2=='01'){
	$tahundipakai3 = $tahundipakai2 - 1;
	$bulandipakai3 = '12'; 
	$periode1 = $tahundipakai3 . "-" . $bulandipakai3 . "-21";
} else {
	$tahundipakai3 = $tahundipakai2;
	$bulandipakai3 = $bulandipakai2 - 1; 
	if(strlen($bulandipakai3)==1)
		$bulandipakai3="0" . $bulandipakai3;
	$periode1 = $tahundipakai3 . "-" . $bulandipakai3 . "-21";
}
$periode2 = $tahundipakai2 . "-" . $bulandipakai2 . "-20";

$record = array();
$record['DATA_VALUE']=$periode1 . ' s/d ' . $periode2;
$record['DATA_NAME']=$periode1 . ' s/d ' . $periode2;
$data[]=$record;

if($bulandipakai3=='01'){
	$tahundipakai4 = $tahundipakai3 - 1;
	$bulandipakai4 = '12'; 
	$periode1 = $tahundipakai4 . "-" . $bulandipakai4 . "-21";
} else {
	$tahundipakai4 = $tahundipakai3;
	$bulandipakai4 = $bulandipakai3 - 1; 
	if(strlen($bulandipakai4)==1)
		$bulandipakai4="0" . $bulandipakai4;
	$periode1 = $tahundipakai4 . "-" . $bulandipakai4 . "-21";
}
$periode2 = $tahundipakai3 . "-" . $bulandipakai3 . "-20";

$record = array();
$record['DATA_VALUE']=$periode1 . ' s/d ' . $periode2;
$record['DATA_NAME']=$periode1 . ' s/d ' . $periode2;
$data[]=$record;

if($bulandipakai4=='01'){
	$tahundipakai5 = $tahundipakai4 - 1;
	$bulandipakai5 = '12'; 
	$periode1 = $tahundipakai5 . "-" . $bulandipakai5 . "-21";
} else {
	$tahundipakai5 = $tahundipakai4;
	$bulandipakai5 = $bulandipakai4 - 1; 
	if(strlen($bulandipakai5)==1)
		$bulandipakai5="0" . $bulandipakai5;
	$periode1 = $tahundipakai5 . "-" . $bulandipakai5 . "-21";
}
$periode2 = $tahundipakai4 . "-" . $bulandipakai4 . "-20";

$record = array();
$record['DATA_VALUE']=$periode1 . ' s/d ' . $periode2;
$record['DATA_NAME']=$periode1 . ' s/d ' . $periode2;
$data[]=$record;

if($bulandipakai5=='01'){
	$tahundipakai6 = $tahundipakai5 - 1;
	$bulandipakai6 = '12'; 
	$periode1 = $tahundipakai6 . "-" . $bulandipakai6 . "-21";
} else {
	$tahundipakai6 = $tahundipakai5;
	$bulandipakai6 = $bulandipakai5 - 1; 
	if(strlen($bulandipakai6)==1)
		$bulandipakai6="0" . $bulandipakai6;
	$periode1 = $tahundipakai6 . "-" . $bulandipakai6 . "-21";
}
$periode2 = $tahundipakai5 . "-" . $bulandipakai5 . "-20";

$record = array();
$record['DATA_VALUE']=$periode1 . ' s/d ' . $periode2;
$record['DATA_NAME']=$periode1 . ' s/d ' . $periode2;
$data[]=$record;

if($bulandipakai6=='01'){
	$tahundipakai7 = $tahundipakai6 - 1;
	$bulandipakai7 = '12'; 
	$periode1 = $tahundipakai7 . "-" . $bulandipakai7 . "-21";
} else {
	$tahundipakai7 = $tahundipakai6;
	$bulandipakai7 = $bulandipakai6 - 1; 
	if(strlen($bulandipakai7)==1)
		$bulandipakai7="0" . $bulandipakai7;
	$periode1 = $tahundipakai7 . "-" . $bulandipakai7 . "-21";
}
$periode2 = $tahundipakai6 . "-" . $bulandipakai6 . "-20";

$record = array();
$record['DATA_VALUE']=$periode1 . ' s/d ' . $periode2;
$record['DATA_NAME']=$periode1 . ' s/d ' . $periode2;
$data[]=$record;

if($bulandipakai7=='01'){
	$tahundipakai8 = $tahundipakai7 - 1;
	$bulandipakai8 = '12'; 
	$periode1 = $tahundipakai8 . "-" . $bulandipakai8 . "-21";
} else {
	$tahundipakai8 = $tahundipakai7;
	$bulandipakai8 = $bulandipakai7 - 1; 
	if(strlen($bulandipakai8)==1)
		$bulandipakai8="0" . $bulandipakai8;
	$periode1 = $tahundipakai8 . "-" . $bulandipakai8 . "-21";
}
$periode2 = $tahundipakai7 . "-" . $bulandipakai7 . "-20";

$record = array();
$record['DATA_VALUE']=$periode1 . ' s/d ' . $periode2;
$record['DATA_NAME']=$periode1 . ' s/d ' . $periode2;
$data[]=$record;

if($bulandipakai8=='01'){
	$tahundipakai9 = $tahundipakai8 - 1;
	$bulandipakai9 = '12'; 
	$periode1 = $tahundipakai9 . "-" . $bulandipakai9 . "-21";
} else {
	$tahundipakai9 = $tahundipakai8;
	$bulandipakai9 = $bulandipakai8 - 1; 
	if(strlen($bulandipakai9)==1)
		$bulandipakai9="0" . $bulandipakai9;
	$periode1 = $tahundipakai9 . "-" . $bulandipakai9 . "-21";
}
$periode2 = $tahundipakai8 . "-" . $bulandipakai8 . "-20";

$record = array();
$record['DATA_VALUE']=$periode1 . ' s/d ' . $periode2;
$record['DATA_NAME']=$periode1 . ' s/d ' . $periode2;
$data[]=$record;

if($bulandipakai9=='01'){
	$tahundipakai10 = $tahundipakai9 - 1;
	$bulandipakai10 = '12'; 
	$periode1 = $tahundipakai10 . "-" . $bulandipakai10 . "-21";
} else {
	$tahundipakai10 = $tahundipakai9;
	$bulandipakai10 = $bulandipakai9 - 1; 
	if(strlen($bulandipakai10)==1)
		$bulandipakai10="0" . $bulandipakai10;
	$periode1 = $tahundipakai10 . "-" . $bulandipakai10 . "-21";
}
$periode2 = $tahundipakai9 . "-" . $bulandipakai9 . "-20";

$record = array();
$record['DATA_VALUE']=$periode1 . ' s/d ' . $periode2;
$record['DATA_NAME']=$periode1 . ' s/d ' . $periode2;
$data[]=$record;

if($bulandipakai10=='01'){
	$tahundipakai11 = $tahundipakai10 - 1;
	$bulandipakai11= '12'; 
	$periode1 = $tahundipakai11 . "-" . $bulandipakai11 . "-21";
} else {
	$tahundipakai11 = $tahundipakai10;
	$bulandipakai11 = $bulandipakai10 - 1; 
	if(strlen($bulandipakai11)==1)
		$bulandipakai11="0" . $bulandipakai11;
	$periode1 = $tahundipakai11 . "-" . $bulandipakai11 . "-21";
}
$periode2 = $tahundipakai10 . "-" . $bulandipakai10 . "-20";

$record = array();
$record['DATA_VALUE']=$periode1 . ' s/d ' . $periode2;
$record['DATA_NAME']=$periode1 . ' s/d ' . $periode2;
$data[]=$record;

if($bulandipakai11=='01'){
	$tahundipakai12 = $tahundipakai11 - 1;
	$bulandipakai12= '12'; 
	$periode1 = $tahundipakai12 . "-" . $bulandipakai12 . "-21";
} else {
	$tahundipakai12 = $tahundipakai11;
	$bulandipakai12 = $bulandipakai11 - 1; 
	if(strlen($bulandipakai12)==1)
		$bulandipakai12="0" . $bulandipakai12;
	$periode1 = $tahundipakai12 . "-" . $bulandipakai12 . "-21";
}
$periode2 = $tahundipakai11 . "-" . $bulandipakai11 . "-20";

$record = array();
$record['DATA_VALUE']=$periode1 . ' s/d ' . $periode2;
$record['DATA_NAME']=$periode1 . ' s/d ' . $periode2;
$data[]=$record;

if($bulandipakai12=='01'){
	$tahundipakai13 = $tahundipakai12 - 1;
	$bulandipakai13= '12'; 
	$periode1 = $tahundipakai13 . "-" . $bulandipakai13 . "-21";
} else {
	$tahundipakai13 = $tahundipakai12;
	$bulandipakai13 = $bulandipakai12 - 1; 
	if(strlen($bulandipakai13)==1)
		$bulandipakai13="0" . $bulandipakai13;
	$periode1 = $tahundipakai13 . "-" . $bulandipakai13 . "-21";
}
$periode2 = $tahundipakai12 . "-" . $bulandipakai12 . "-20";

$record = array();
$record['DATA_VALUE']=$periode1 . ' s/d ' . $periode2;
$record['DATA_NAME']=$periode1 . ' s/d ' . $periode2;
$data[]=$record;

if($bulandipakai13=='01'){
	$tahundipakai14 = $tahundipakai13 - 1;
	$bulandipakai14= '12'; 
	$periode1 = $tahundipakai14 . "-" . $bulandipakai14 . "-21";
} else {
	$tahundipakai14 = $tahundipakai13;
	$bulandipakai14 = $bulandipakai13 - 1; 
	if(strlen($bulandipakai14)==1)
		$bulandipakai14="0" . $bulandipakai14;
	$periode1 = $tahundipakai14 . "-" . $bulandipakai14 . "-21";
}
$periode2 = $tahundipakai13 . "-" . $bulandipakai13 . "-20";

$record = array();
$record['DATA_VALUE']=$periode1 . ' s/d ' . $periode2;
$record['DATA_NAME']=$periode1 . ' s/d ' . $periode2;
$data[]=$record;

if($bulandipakai14=='01'){
	$tahundipakai15 = $tahundipakai14 - 1;
	$bulandipakai15= '12'; 
	$periode1 = $tahundipakai15 . "-" . $bulandipakai15 . "-21";
} else {
	$tahundipakai15 = $tahundipakai14;
	$bulandipakai15 = $bulandipakai14 - 1; 
	if(strlen($bulandipakai15)==1)
		$bulandipakai15="0" . $bulandipakai15;
	$periode1 = $tahundipakai15 . "-" . $bulandipakai15 . "-21";
}
$periode2 = $tahundipakai14 . "-" . $bulandipakai14 . "-20";

$record = array();
$record['DATA_VALUE']=$periode1 . ' s/d ' . $periode2;
$record['DATA_NAME']=$periode1 . ' s/d ' . $periode2;
$data[]=$record;

// $record = array();
// $record['DATA_VALUE']='2015-12-21 s/d 2016-01-20';
// $record['DATA_NAME']='2015-12-21 s/d 2016-01-20';
// $data[]=$record;

















	
echo json_encode($data);
?>