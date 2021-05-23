<?PHP
// require('smtpemailattachment.php');
include '../../main/koneksi.php';
date_default_timezone_set("Asia/Jakarta");
// deklarasi variable dan session
session_start();
$user_id = "";
$username = "";
$emp_id = "";
$emp_name = "";
$io_id = "";
$io_name = "";
$loc_id = "";
$loc_name = "";
$org_id = "";
$org_name = "";
 if(isset($_SESSION[APP]['user_id']))
  {
	$user_id = $_SESSION[APP]['user_id'];
	$username = $_SESSION[APP]['username'];
	$emp_id = $_SESSION[APP]['emp_id'];
	$emp_name = $_SESSION[APP]['emp_name'];
	$io_id = $_SESSION[APP]['io_id'];
	$io_name = $_SESSION[APP]['io_name'];
	$loc_id = $_SESSION[APP]['loc_id'];
	$loc_name = $_SESSION[APP]['loc_name'];
	$org_id = $_SESSION[APP]['org_id'];
	$org_name = $_SESSION[APP]['org_name'];
  }
  
$hari="";
$bulan="";
$tahun=""; 
$tglskr=date('Y-m-d'); 
$tahunbaru=substr($tglskr, 0, 2);
$data="gagal";
$tglSebelum="";
$namaSebelum="";
$arrID=array();
$arrSJ=array();
$arrLHO=array();
$arrNominal=array();
$arrNominalID=array();
$arrGroupID=array();

 if(isset($_POST['typeform']))
  {
	$hdid=$_POST['hdid'];
	$typeform=$_POST['typeform'];
	$typetrans=$_POST['typetrans'];
	$tgl_awal=$_POST['tgl_awal'];
	$tgl_akhir=$_POST['tgl_akhir'];
	$nama=$_POST['nama'];
	$kumpulanID=$_POST['kumpulanID'];
	$arrID=json_decode($_POST['arrID']);
	$arrSJ=json_decode($_POST['arrSJ']);
	$arrLHO=json_decode($_POST['arrLHO']);
	$arrNominal=json_decode($_POST['arrNominal']);
	$arrNominalID=json_decode($_POST['arrNominalID']);
	$arrGroupID=json_decode($_POST['arrGroupID']);
	
	if($hdid == ''){
		$hdid = 0;
	} 
  }
$countID = count($arrID);

$result = oci_parse($con, "SELECT COUNT(-1) 
FROM MJ.MJ_T_RITASI_QC 
WHERE NAMA_QC='$nama'
AND ((TO_CHAR(EFFECTIVE_START_DATE, 'YYYY-MM-DD') <= '$tgl_awal' AND NVL(TO_CHAR(EFFECTIVE_END_DATE, 'YYYY-MM-DD'), '4712-12-31') >= '$tgl_awal') 
 OR (TO_CHAR(EFFECTIVE_START_DATE, 'YYYY-MM-DD') <= '$tgl_akhir' AND NVL(TO_CHAR(EFFECTIVE_END_DATE, 'YYYY-MM-DD'), '4712-12-31') >= '$tgl_akhir')  )
AND ID <> $hdid
");
oci_execute($result);
$rowJum = oci_fetch_row($result);
$jumlah = $rowJum[0]; 

if ($jumlah>0)
{
	$data = "Nama QC dengan periode tersebut sudah pernah diinput.";
} else {
	if($typeform=="tambah"){
		//insert Header
		$resultSeq = oci_parse($con,"SELECT MJ.MJ_T_RITASI_QC_SEQ.nextval FROM dual");
		oci_execute($resultSeq);
		$row = oci_fetch_row($resultSeq);
		$hdid = $row[0];
		$sqlQuery = "INSERT INTO MJ.MJ_T_RITASI_QC (ID, EFFECTIVE_START_DATE, EFFECTIVE_END_DATE, NAMA_QC, STATUS, CREATED_BY, CREATED_DATE) 
		VALUES ( $hdid, TO_DATE('$tgl_awal', 'YYYY-MM-DD'), TO_DATE('$tgl_akhir', 'YYYY-MM-DD'), $nama, '$typetrans', '$user_id', SYSDATE)";
		// echo $sqlQuery;
		$result = oci_parse($con,$sqlQuery);
		$oe = oci_execute($result);
		
		if($oe){
			//Insert Detail
			for ($x=0; $x<$countID; $x++){
				$TransID = $arrID[$x];
				$noSJ = $arrSJ[$x];
				$noLHO = $arrLHO[$x];
				$nominal = $arrNominal[$x];
				$nominalID = $arrNominalID[$x];
				$groupID = $arrGroupID[$x];
				
				$resultSeq = oci_parse($con,"SELECT MJ.MJ_T_RITASI_QC_DETAIL_SEQ.nextval FROM dual");
				oci_execute($resultSeq);
				$row = oci_fetch_row($resultSeq);
				$IdDetail = $row[0];
				$sqlQuery = "INSERT INTO MJ.MJ_T_RITASI_QC_DETAIL (ID, MJ_T_RITASI_QC_ID, SJ_ID, NOMOR_LHO, NOMINAL, NOMINAL_ID, GROUP_ID, CREATED_BY, CREATED_DATE) 
				VALUES ( $IdDetail, $hdid, $TransID, '$noLHO', '$nominal', $nominalID, $groupID, '$user_id', SYSDATE)";
				//echo $sqlQuery;
				$result = oci_parse($con,$sqlQuery);
				oci_execute($result);
			}
		}
		
		
		$data = "sukses";
	} else {
		$sqlQueryHeader = "UPDATE MJ.MJ_T_RITASI_QC SET STATUS = '$typetrans', LAST_UPDATED_BY='$user_id', LAST_UPDATED_DATE=SYSDATE WHERE ID = $hdid  ";
		//echo $sqlQuery;
		$resultHeader = oci_parse($con,$sqlQueryHeader);
		oci_execute($resultHeader);
		
		//Insert Detail
		for ($x=0; $x<$countID; $x++){
			$TransID = $arrID[$x];
			$noSJ = $arrSJ[$x];
			$noLHO = $arrLHO[$x];
			$nominal = $arrNominal[$x];
			$nominalID = $arrNominalID[$x];
			$groupID = $arrGroupID[$x];
			
			$result = oci_parse($con, "SELECT COUNT(-1) 
			FROM MJ.MJ_T_RITASI_QC_DETAIL 
			WHERE MJ_T_RITASI_QC_ID='$hdid'
			AND SJ_ID = $TransID
			");
			oci_execute($result);
			$rowJum = oci_fetch_row($result);
			$jumlah = $rowJum[0]; 
			
			if ($jumlah > 0){
				$sqlQuery = "UPDATE MJ.MJ_T_RITASI_QC_DETAIL SET NOMOR_LHO = '$noLHO', NOMINAL = '$nominal', NOMINAL_ID = $nominalID, GROUP_ID = $groupID, LAST_UPDATED_BY='$user_id', LAST_UPDATED_DATE=SYSDATE
				WHERE MJ_T_RITASI_QC_ID = $hdid AND SJ_ID = $TransID ";
				//echo $sqlQuery;
				$result = oci_parse($con,$sqlQuery);
				oci_execute($result);
			} else {				
				$resultSeq = oci_parse($con,"SELECT MJ.MJ_T_RITASI_QC_DETAIL_SEQ.nextval FROM dual");
				oci_execute($resultSeq);
				$row = oci_fetch_row($resultSeq);
				$IdDetail = $row[0];
				
				$sqlQuery = "INSERT INTO MJ.MJ_T_RITASI_QC_DETAIL (ID, MJ_T_RITASI_QC_ID, SJ_ID, NOMOR_LHO, NOMINAL, NOMINAL_ID, GROUP_ID, CREATED_BY, CREATED_DATE) 
				VALUES ( $IdDetail, $hdid, $TransID, '$noLHO', '$nominal', $nominalID, $groupID, '$user_id', SYSDATE)";
				//echo $sqlQuery;
				$result = oci_parse($con,$sqlQuery);
				oci_execute($result);
			}
		}
		
		$sqlQuery = "DELETE FROM MJ.MJ_T_RITASI_QC_DETAIL WHERE MJ_T_RITASI_QC_ID = $hdid AND SJ_ID NOT IN ($kumpulanID) ";
		//echo $sqlQuery;
		$result = oci_parse($con,$sqlQuery);
		oci_execute($result);
		
		$data = "sukses";
	}	
	//ini Ketika tekan tombol submit, menginsertkan ke tabel MJ_T_RITASI_LEMBUR
	if($typetrans == 'Submit'){
		$resultCek = oci_parse($con, "SELECT CASE WHEN REGEXP_SUBSTR( REPLACE(REPLACE(TRIM(MSB.JAM_BERANGKAT), ';', ':'), '.', ''), '[^:]+', 1, 1 ) < 7 
		THEN TO_CHAR(MSB.TANGGAL_SJ-1, 'YYYYMMDD') 
		ELSE TO_CHAR(MSB.TANGGAL_SJ, 'YYYYMMDD') END TANGGAL_SJ
		, HCA.PARTY_ID
		, TRQD.NOMOR_LHO
		, TRQD.NOMINAL
		, SUM((MSB.VOLUME_KIRIM - NVL(MRS.RETURN_QTY, 0))) VOLUM
		, CASE WHEN NVL(TRL.ID, 0) = 0 THEN 'Y' ELSE 'N' END BISA
		, MTT.JAM_MASUK
		, MTT.JAM_KELUAR
		FROM MJ.MJ_T_RITASI_QC TRQ
		INNER JOIN MJ.MJ_T_RITASI_QC_DETAIL TRQD ON TRQ.ID = TRQD.MJ_T_RITASI_QC_ID
		INNER JOIN MJ.MJ_SJ_BETON MSB ON MSB.TRANSACTION_ID = TRQD.SJ_ID
		INNER JOIN APPS.OE_ORDER_HEADERS_ALL OEH ON OEH.HEADER_ID = MSB.HEADER_ID
		INNER JOIN APPS.HZ_CUST_ACCOUNTS HCA ON HCA.CUST_ACCOUNt_ID = OEH.SOLD_TO_ORG_ID
		LEFT JOIN MJ.MJ_RETURN_SJ MRS ON MSB.TRANSACTION_ID = MRS.SJ_ID
		LEFT JOIN MJ.MJ_T_RITASI_LEMBUR TRL ON TRL.TGL = MSB.TANGGAL_SJ AND TRL.STATUS = 'Y' 
		AND TRL.PERSON_ID = TRQ.NAMA_QC AND HCA.PARTY_ID = TRL.CUSTOMER_ID AND TRL.NOMOR_LHO = TRQD.NOMOR_LHO
		AND TRL.TRANSAKSI_ID = TRQ.ID
		LEFT JOIN MJ.MJ_T_TIMECARD MTT ON MTT.PERSON_ID = TRQ.NAMA_QC AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD') = TO_CHAR(MSB.TANGGAL_SJ, 'YYYY-MM-DD')
		WHERE MSB.ORG_ID = 81
		AND TRQ.ID=$hdid
		GROUP BY CASE WHEN REGEXP_SUBSTR( REPLACE(REPLACE(TRIM(MSB.JAM_BERANGKAT), ';', ':'), '.', ''), '[^:]+', 1, 1 ) < 7 
		THEN TO_CHAR(MSB.TANGGAL_SJ-1, 'YYYYMMDD') 
		ELSE TO_CHAR(MSB.TANGGAL_SJ, 'YYYYMMDD') END, TRL.ID, MTT.JAM_MASUK, MTT.JAM_KELUAR, TRQD.NOMINAL, HCA.PARTY_ID, TRQD.NOMOR_LHO
		");
		oci_execute($resultCek);
		while($rowCek = oci_fetch_row($resultCek))
		{
			$tgSJ = $rowCek[0];
			$partyId = $rowCek[1];
			$noLHO = $rowCek[2];
			$nominalTrans = $rowCek[3];
			$sumVol = $rowCek[4];
			$statusCek = $rowCek[5]; 
			$jamIn = $rowCek[6]; 
			$jamOut = $rowCek[7]; 
			$jamInR=str_replace(":","", $jamIn);
			$jamOutR=str_replace(":","", $jamOut);
			$nominalLembur = 0; 
			
			//pengecekan bahwa sudah pernah diinsertkan ke tabel lembur apa belum
			if($statusCek == 'Y'){
									
				$queryHariIni = "SELECT TO_CHAR(TO_DATE('$tgSJ', 'YYYY-MM-DD'), 'DAY') FROM DUAL";
				$resultHariIni = oci_parse($con,$queryHariIni);
				oci_execute($resultHariIni);
				$rowHariIni = oci_fetch_row($resultHariIni);
				$HariIni = $rowHariIni[0]; 
				$HariIni = trim($HariIni);

				$queryHariLibur = "SELECT COUNT(-1)
				FROM APPS.HXT_HOLIDAY_DAYS A
				, APPS.HXT_HOLIDAY_CALENDARS B
				WHERE A.HCL_ID=B.ID 
				AND B.EFFECTIVE_END_DATE>SYSDATE 
				AND TO_CHAR(A.HOLIDAY_DATE, 'YYYY-MM-DD')='$tgSJ'";
				$resultHariLibur = oci_parse($con,$queryHariLibur);
				oci_execute($resultHariLibur);
				$rowHariLibur = oci_fetch_row($resultHariLibur);
				$HariLibur = $rowHariLibur[0]; 
				//cek absensi, kalau ada dia kemungkinan dapat uang lembur
				if($jamIn != '' && $jamOut != ''){
					if($HariIni == 'SUNDAY' || $HariLibur != 0){
						if(($jamOutR - $jamInR) >= 1200){
							$nominalLembur = 35000; 
						} else {
							$nominalLembur = 25000; 
						}
						// $nominalTrans = $nominalTrans * 2; 
					} else {
						$nominalLembur = 0;
					}
				} else {
					// if($HariIni == 'SUNDAY' && $HariLibur != 0){
						// $nominalTrans = $nominalTrans * 2; 
					// }
					$nominalLembur = 0;
				}
				
				$jumlahDet = 0;
				$resultDet = oci_parse($con, "SELECT COUNT(-1)
				FROM MJ.MJ_T_RITASI_LEMBUR TRL
				WHERE TRL.STATUS = 'Y'
				AND TO_CHAR(TRL.TGL, 'YYYY-MM-DD') = '$tgSJ'
				AND TRL.PERSON_ID = $nama
				AND TRL.LEMBUR > 0
				");
				oci_execute($resultDet);
				$rowJumDet = oci_fetch_row($resultDet);
				$jumlahDet = $rowJumDet[0]; 
				
				if($jumlahDet != 0){
					$nominalLembur = 0;
				}
				
				$sqlQuery = "INSERT INTO MJ.MJ_T_RITASI_LEMBUR (ID, PERSON_ID, TRANSAKSI_ID, CUSTOMER_ID, NOMOR_LHO, TGL, SUM_VOLUM, NOMINAL, LEMBUR, JAM_IN, JAM_OUT, TIPE, STATUS, CREATED_BY, CREATED_DATE) 
				VALUES ( MJ.MJ_T_RITASI_LEMBUR_SEQ.NEXTVAL, $nama, $hdid, $partyId, '$noLHO', TO_DATE('$tgSJ', 'YYYY-MM-DD'), '$sumVol', $nominalTrans, $nominalLembur, '$jamIn', '$jamOut', 'QC', 'Y', '$user_id', SYSDATE)";
				//echo $sqlQuery;
				$result = oci_parse($con, $sqlQuery);
				oci_execute($result);
			}
		}
	}
	
}	

$result = array('success' => true,
				'results' => $hdid,
				'rows' => $data
			);
echo json_encode($result);

?>