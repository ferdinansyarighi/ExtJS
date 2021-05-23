<?PHP
	include 'D:/dataSource/MJBHRD/main/koneksi.php';

	$queryCekAbsen = "SELECT  COUNT(-1)
	FROM    MJ.MJ_T_TIMECARD MTT 
	WHERE   TO_CHAR( MTT.TANGGAL, 'YYYY-MM-DD') >= '2019-01-01'
	AND     TO_CHAR( MTT.TANGGAL, 'YYYY-MM-DD') <= '2019-03-04'
	--AND     MTT.PERSON_ID = 43262
	AND     MTT.STATUS IN ( 248, 253, 247 )
	GROUP   BY MTT.PERSON_ID, MTT.TANGGAL, MTT.STATUS
	HAVING   COUNT( * ) > 1
	ORDER   BY TANGGAL";
	$resultCekAbsen = oci_parse($con,$queryCekAbsen);
	oci_execute($resultCekAbsen);
	$rowAbsen = oci_fetch_row($resultCekAbsen);
	$countAbsen = $rowAbsen[0]; 
	//echo $countData;exit;
	//echo $newformat; exit;
	if($countAbsen >= 1){
		$query = "SELECT  MAX(ID),MTT.PERSON_ID, MTT.TANGGAL, MTT.STATUS, COUNT( * ) B
		FROM    MJ.MJ_T_TIMECARD MTT 
		WHERE   TO_CHAR( MTT.TANGGAL, 'YYYY-MM-DD') >= '2019-01-01'
		AND     TO_CHAR( MTT.TANGGAL, 'YYYY-MM-DD') <= '2019-03-04'
		--AND     MTT.PERSON_ID = 43262
		AND     MTT.STATUS IN ( 248, 253, 247 )
		GROUP   BY MTT.PERSON_ID, MTT.TANGGAL, MTT.STATUS
		HAVING   COUNT( * ) > 1
		ORDER   BY TANGGAL";
		$result = oci_parse($con, $query);
		oci_execute($result);
		while($row = oci_fetch_row($result)){
			$idHapus  =$row[0];
			$personID =$row[1];
			$tanggal  =$row[2];
			$status   =$row[3];

			$queryTimeCard = "DELETE MJ.MJ_T_TIMECARD WHERE ID = $idHapus";
			$hapus = oci_parse($con,$queryTimeCard);
			oci_execute($hapus);
		}
	}
?>