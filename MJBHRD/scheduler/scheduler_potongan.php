<?PHP
 include 'D:/dataSource/MJBHRD/main/koneksi.php'; //Koneksi ke database


$query = "SELECT ID
, PERSON_ID
, (OUTSTANDING_P - HARGA_CICILAN_P) AS OUTSTANDING_P
, (OUTSTANDING_B - HARGA_CICILAN_B) AS OUTSTANDING_B
, HARGA_CICILAN_P
, HARGA_CICILAN_B
, OUTSTANDING_P
, OUTSTANDING_B
FROM MJ_T_POTONGAN
WHERE STATUS='A'
--AND PERSON_ID=1130";
$result = oci_parse($con, $query);
oci_execute($result);
while($row = oci_fetch_row($result))
{
	$vId = $row[0];
	$vPersonId = $row[1];
	$vOutstandingP = $row[2];
	$vOutstandingB = $row[3];
	$vPotonganP = $row[4];
	$vPotonganB = $row[5];
	$vPotonganPx = $row[6];
	$vPotonganBx = $row[7];
	
	//Pinjaman
	if($vOutstandingP >= 0 && $vPotonganPx != 0){
		$resultCuti = oci_parse($con,"UPDATE MJ.MJ_T_POTONGAN SET OUTSTANDING_P=$vOutstandingP WHERE ID=$vId AND APP_ID=" . APPCODE . "");
		oci_execute($resultCuti);
		
		if($vOutstandingP == 0){
			$resultCuti = oci_parse($con,"UPDATE MJ.MJ_T_POTONGAN SET HARGA_CICILAN_P=0 WHERE ID=$vId AND APP_ID=" . APPCODE . "");
			oci_execute($resultCuti);
		}
				
		$resultSeq = oci_parse($con,"SELECT MJ.MJ_T_POTONGAN_DETAIL_SEQ.nextval FROM dual"); 
		oci_execute($resultSeq);
		$row = oci_fetch_row($resultSeq);
		$IdUser = $row[0];

		$sqlQuery = "INSERT INTO MJ.MJ_T_POTONGAN_DETAIL (ID, APP_ID, MJ_T_POTONGAN_ID, TIPE, JUMLAH_POTONGAN, STATUS, CREATED_BY, CREATED_DATE) VALUES ( $IdUser, " . APPCODE . ", $vId, 'POTONGAN', $vPotonganP, 'A', 1, SYSDATE)";
		$resultQuery = oci_parse($con,$sqlQuery);
		oci_execute($resultQuery);
	} 
	//BS
	if($vOutstandingB >= 0 && $vPotonganBx != 0){
		$resultCuti = oci_parse($con,"UPDATE MJ.MJ_T_POTONGAN SET OUTSTANDING_B=$vOutstandingB WHERE ID=$vId AND APP_ID=" . APPCODE . "");
		oci_execute($resultCuti);
		
		if($vOutstandingB == 0){
			$resultCuti = oci_parse($con,"UPDATE MJ.MJ_T_POTONGAN SET HARGA_CICILAN_B=0 WHERE ID=$vId AND APP_ID=" . APPCODE . "");
			oci_execute($resultCuti);
		}
				
		$resultSeq = oci_parse($con,"SELECT MJ.MJ_T_POTONGAN_DETAIL_SEQ.nextval FROM dual"); 
		oci_execute($resultSeq);
		$row = oci_fetch_row($resultSeq);
		$IdUser = $row[0];

		$sqlQuery = "INSERT INTO MJ.MJ_T_POTONGAN_DETAIL (ID, APP_ID, MJ_T_POTONGAN_ID, TIPE, JUMLAH_POTONGAN, STATUS, CREATED_BY, CREATED_DATE) VALUES ( $IdUser, " . APPCODE . ", $vId, 'BS', $vPotonganB, 'A', 1, SYSDATE)";
		$resultQuery = oci_parse($con,$sqlQuery);
		oci_execute($resultQuery);
	}
	
} 
?>