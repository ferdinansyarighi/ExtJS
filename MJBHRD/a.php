<?php
// echo md5('ricky');
// echo '<BR>';
// echo md5('56ea8b83122449e814e0fd7bfb5f220a');

$tglskr=date('Y-m-d'); 
$tahunSkr=substr($tglskr, 0, 4);
$bulanSkr=substr($tglskr, 5, 2);
$hariSkr=substr($tglskr, 8, 2);

echo $hariSkr . " - " . $bulanSkr . " - " . $tahunSkr;
?>