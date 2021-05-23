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
$arrID=array();
$arrSJ=array();
$arrLHO=array();
$arrKet=array();
$arrKMAwal=array();
$arrKMAkhir=array();
$arrSolar=array();
$arrVariabel=array();
$arrRitasi=array();
$arrNominal=array();
$arrNominalID=array();
$arrGroupID=array();
$arrTipe=array();

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
	$arrTglSJ=json_decode($_POST['arrTglSJ']);
	$arrLHO=json_decode($_POST['arrLHO']);
	$arrKet=json_decode($_POST['arrKet']);
	$arrKMAwal=json_decode($_POST['arrKMAwal']);
	$arrKMAkhir=json_decode($_POST['arrKMAkhir']);
	$arrSolar=json_decode($_POST['arrSolar']);
	$arrVariabel=json_decode($_POST['arrVariabel']);
	$arrRitasi=json_decode($_POST['arrRitasi']);
	$arrNominal=json_decode($_POST['arrNominal']);
	$arrNominalID=json_decode($_POST['arrNominalID']);
	$arrGroupID=json_decode($_POST['arrGroupID']);
	$arrTipe=json_decode($_POST['arrTipe']);
	
	if($hdid == ''){
		$hdid = 0;
	} 
  }
$countID = count($arrID);
//echo "masuk";exit;
$result = oci_parse($con, "SELECT COUNT(-1) 
FROM MJ.MJ_T_RITASI_DRIVER 
WHERE NAMA_DRIVER='$nama'
AND ((TO_CHAR(EFFECTIVE_START_DATE, 'YYYY-MM-DD') <= '$tgl_awal' AND NVL(TO_CHAR(EFFECTIVE_END_DATE, 'YYYY-MM-DD'), '4712-12-31') >= '$tgl_awal') 
 OR (TO_CHAR(EFFECTIVE_START_DATE, 'YYYY-MM-DD') <= '$tgl_akhir' AND NVL(TO_CHAR(EFFECTIVE_END_DATE, 'YYYY-MM-DD'), '4712-12-31') >= '$tgl_akhir')  )
AND ID <> $hdid
");
oci_execute($result);
$rowJum = oci_fetch_row($result);
$jumlah = $rowJum[0]; 

if ($jumlah>0)
{
	$data = "Nama Driver dengan periode tersebut sudah pernah diinput.";
} else {
	if($typeform=="tambah"){
		//insert Header
		$resultSeq = oci_parse($con,"SELECT MJ.MJ_T_RITASI_DRIVER_SEQ.nextval FROM dual");
		oci_execute($resultSeq);
		$row = oci_fetch_row($resultSeq);
		$hdid = $row[0];
		$sqlQuery = "INSERT INTO MJ.MJ_T_RITASI_DRIVER (ID, EFFECTIVE_START_DATE, EFFECTIVE_END_DATE, NAMA_DRIVER, STATUS, CREATED_BY, CREATED_DATE) 
		VALUES ( $hdid, TO_DATE('$tgl_awal', 'YYYY-MM-DD'), TO_DATE('$tgl_akhir', 'YYYY-MM-DD'), $nama, '$typetrans', '$user_id', SYSDATE)";
		//echo $sqlQuery;
		$result = oci_parse($con,$sqlQuery);
		if(!oci_execute($result)){
			$data = "gagal";
		}else{
			//Insert Detail
			for ($x=0; $x<$countID; $x++){
				$TransID = $arrID[$x];
				$noSJ = $arrSJ[$x];
				$tglSJ = $arrTglSJ[$x];
				$noLHO = $arrLHO[$x];
				$ketHRD = $arrKet[$x];
				$kmAwal = $arrKMAwal[$x];
				$kmAkhir = $arrKMAkhir[$x];
				$solar = $arrSolar[$x];
				$variabel = $arrVariabel[$x];
				$ritasiKe = $arrRitasi[$x];
				$nominal = $arrNominal[$x];
				$nominalID = $arrNominalID[$x];
				$groupID = $arrGroupID[$x];
				$tipe = $arrTipe[$x];
				
				if($ritasiKe == ''){
					$ritasiKe = 0;
				}
				
				if($noSJ != 'STANDBY'){
					$tglSJ = '';
				}
				
				$resultSeq = oci_parse($con,"SELECT MJ.MJ_T_RITASI_DRIVER_DETAIL_SEQ.nextval FROM dual");
				oci_execute($resultSeq);
				$row = oci_fetch_row($resultSeq);
				$IdDetail = $row[0];
				$sqlQuery = "INSERT INTO MJ.MJ_T_RITASI_DRIVER_DETAIL (ID, MJ_T_RITASI_DRIVER_ID, SJ_ID, NOMOR_LHO, KETERANGAN, TIPE, KM_AWAL, KM_AKHIR, SOLAR, VARIABEL, RITASI_KE, NOMINAL, NOMINAL_ID, GROUP_ID, CREATED_BY, CREATED_DATE, TGL_STANDBY) 
				VALUES ( $IdDetail, $hdid, $TransID, '$noLHO', '$ketHRD', '$tipe', $kmAwal, $kmAkhir, $solar, $variabel, $ritasiKe, '$nominal', $nominalID, $groupID, '$user_id', SYSDATE, '$tglSJ')";
				//echo $sqlQuery;
				$result = oci_parse($con,$sqlQuery);
				oci_execute($result);
			}
			
			$data = "sukses";
		}
	} else {
		$sqlQueryHeader = "UPDATE MJ.MJ_T_RITASI_DRIVER SET STATUS = '$typetrans', LAST_UPDATED_BY='$user_id', LAST_UPDATED_DATE=SYSDATE WHERE ID = $hdid  ";
		//echo $sqlQuery;
		$resultHeader = oci_parse($con,$sqlQueryHeader);
		oci_execute($resultHeader);
		
		//Insert Detail
		for ($x=0; $x<$countID; $x++){
			$TransID = $arrID[$x];
			$noSJ = $arrSJ[$x];
			$tglSJ = $arrTglSJ[$x];
			$noLHO = $arrLHO[$x];
			$ketHRD = $arrKet[$x];
			$kmAwal = $arrKMAwal[$x];
			$kmAkhir = $arrKMAkhir[$x];
			$solar = $arrSolar[$x];
			$variabel = $arrVariabel[$x];
			$ritasiKe = $arrRitasi[$x];
			$nominal = $arrNominal[$x];
			$nominalID = $arrNominalID[$x];
			$groupID = $arrGroupID[$x];
			$tipe = $arrTipe[$x];
			
			if($ritasiKe == ''){
				$ritasiKe = 0;
			}
			
			if($noSJ != 'STANDBY'){
				$tglSJ = '';
			}
			
			$result = oci_parse($con, "SELECT COUNT(-1) 
			FROM MJ.MJ_T_RITASI_DRIVER_DETAIL 
			WHERE MJ_T_RITASI_DRIVER_ID='$hdid'
			AND SJ_ID = $TransID
			");
			oci_execute($result);
			$rowJum = oci_fetch_row($result);
			$jumlah = $rowJum[0]; 
			
			if ($jumlah > 0){
				$sqlQuery = "UPDATE MJ.MJ_T_RITASI_DRIVER_DETAIL SET NOMOR_LHO = '$noLHO', KETERANGAN = '$ketHRD', TIPE = '$tipe', KM_AWAL = '$kmAwal', KM_AKHIR = '$kmAkhir', SOLAR = '$solar', VARIABEL = '$variabel', NOMINAL = '$nominal', NOMINAL_ID = $nominalID, GROUP_ID = $groupID, RITASI_KE = $ritasiKe, TGL_STANDBY='$tglSJ', LAST_UPDATED_BY='$user_id', LAST_UPDATED_DATE=SYSDATE
				WHERE MJ_T_RITASI_DRIVER_ID = $hdid AND SJ_ID = $TransID ";
				//echo $sqlQuery;
				$result = oci_parse($con,$sqlQuery);
				oci_execute($result);
			} else {				
				$resultSeq = oci_parse($con,"SELECT MJ.MJ_T_RITASI_DRIVER_DETAIL_SEQ.nextval FROM dual");
				oci_execute($resultSeq);
				$row = oci_fetch_row($resultSeq);
				$IdDetail = $row[0];
				
				$sqlQuery = "INSERT INTO MJ.MJ_T_RITASI_DRIVER_DETAIL (ID, MJ_T_RITASI_DRIVER_ID, SJ_ID, NOMOR_LHO, KETERANGAN, TIPE, KM_AWAL, KM_AKHIR, SOLAR, VARIABEL, RITASI_KE, NOMINAL, NOMINAL_ID, GROUP_ID, CREATED_BY, CREATED_DATE, TGL_STANDBY) 
				VALUES ( $IdDetail, $hdid, $TransID, '$noLHO', '$ketHRD', '$tipe', $kmAwal, $kmAkhir, $solar, $variabel, $ritasiKe, '$nominal', $nominalID, $groupID, '$user_id', SYSDATE, '$tglSJ')";
				// echo $sqlQuery;
				$result = oci_parse($con,$sqlQuery);
				oci_execute($result);
			}
		}
		
		$sqlQuery = "DELETE FROM MJ.MJ_T_RITASI_DRIVER_DETAIL WHERE MJ_T_RITASI_DRIVER_ID = $hdid AND SJ_ID NOT IN ($kumpulanID) ";
		//echo $sqlQuery;
		$result = oci_parse($con,$sqlQuery);
		oci_execute($result);
		
		$data = "sukses";
	}	
	// echo $typetrans;
	if($typetrans == 'Submit'){
		$resultCek = oci_parse($con, "SELECT CASE WHEN REGEXP_SUBSTR( REPLACE(REPLACE(TRIM(MSB.JAM_BERANGKAT), ';', ':'), '.', ''), '[^:]+', 1, 1 ) < 7 
		THEN TO_CHAR(NVL(MSB.TANGGAL_SJ-1, TRDD.TGL_STANDBY), 'YYYYMMDD') 
		ELSE TO_CHAR(NVL(MSB.TANGGAL_SJ, TRDD.TGL_STANDBY), 'YYYYMMDD') END TANGGAL_SJ
		, NVL(HCA.PARTY_ID, 0) PARTY_ID
		, TRDD.NOMOR_LHO
		, TRDD.RITASI_KE
		, TRDD.VARIABEL
		, TRDD.NOMINAL
		, SUM((NVL(MSB.VOLUME_KIRIM, 0) - NVL(MRS.RETURN_QTY, 0))) VOLUM
		, CASE WHEN NVL(TRLD.ID, 0) = 0 THEN 'Y' ELSE 'N' END BISA
		, MTT.JAM_MASUK
		, MTT.JAM_KELUAR
		, NVL(TRDD.TIPE, 'BP') TIPE
		FROM MJ.MJ_T_RITASI_DRIVER TRD
		INNER JOIN MJ.MJ_T_RITASI_DRIVER_DETAIL TRDD ON TRD.ID = TRDD.MJ_T_RITASI_DRIVER_ID
		LEFT JOIN MJ.MJ_SJ_BETON MSB ON MSB.TRANSACTION_ID = TRDD.SJ_ID AND MSB.ORG_ID = 81
		LEFT JOIN MJ.MJ_RETURN_SJ MRS ON MSB.TRANSACTION_ID = MRS.SJ_ID
		LEFT JOIN APPS.OE_ORDER_HEADERS_ALL OEH ON OEH.HEADER_ID = MSB.HEADER_ID
		LEFT JOIN APPS.HZ_CUST_ACCOUNTS HCA ON HCA.CUST_ACCOUNt_ID = OEH.SOLD_TO_ORG_ID
		LEFT JOIN MJ.MJ_T_RITASI_LEMBUR_DRIVER TRLD ON TRLD.TGL = NVL(MSB.TANGGAL_SJ, TRDD.TGL_STANDBY) AND TRLD.STATUS = 'Y' 
		AND TRLD.PERSON_ID = TRD.NAMA_DRIVER AND TRDD.RITASI_KE = TRLD.RITASI_KE AND TRLD.NOMOR_LHO = TRDD.NOMOR_LHO 
		AND HCA.PARTY_ID = TRLD.CUSTOMER_ID AND TRLD.VARIABEL = TRDD.VARIABEL AND TRLD.TRANSAKSI_ID = TRD.ID
		LEFT JOIN MJ.MJ_T_TIMECARD MTT ON MTT.PERSON_ID = TRD.NAMA_DRIVER AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD') = TO_CHAR(NVL(MSB.TANGGAL_SJ, TRDD.TGL_STANDBY), 'YYYY-MM-DD')
		WHERE TRD.ID=$hdid
		GROUP BY CASE WHEN REGEXP_SUBSTR( REPLACE(REPLACE(TRIM(MSB.JAM_BERANGKAT), ';', ':'), '.', ''), '[^:]+', 1, 1 ) < 7 
		THEN TO_CHAR(NVL(MSB.TANGGAL_SJ-1, TRDD.TGL_STANDBY), 'YYYYMMDD') 
		ELSE TO_CHAR(NVL(MSB.TANGGAL_SJ, TRDD.TGL_STANDBY), 'YYYYMMDD') END, TRLD.ID, MTT.JAM_MASUK, MTT.JAM_KELUAR, TRDD.NOMINAL, HCA.PARTY_ID, TRDD.NOMOR_LHO
		, TRDD.RITASI_KE, TRDD.VARIABEL, TRDD.TIPE
		");
		oci_execute($resultCek);
		while($rowCek = oci_fetch_row($resultCek))
		{
			$tgSJ = $rowCek[0];
			$partyId = $rowCek[1];
			$noLHO = $rowCek[2];
			$ritasiKe = $rowCek[3];
			$variabel = $rowCek[4];
			$nominalTrans = $rowCek[5];
			$sumVol = $rowCek[6];
			$statusCek = $rowCek[7]; 
			$jamIn = $rowCek[8]; 
			$jamOut = $rowCek[9]; 
			$tipe = $rowCek[10]; 
			$jamInR=str_replace(":","", $jamIn);
			$jamOutR=str_replace(":","", $jamOut);
			$uangMakan = 0; 
			$nominalLembur = 0; 
			
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
				
				if($jamIn != '' && $jamOut != ''){
					if(($jamOutR - $jamInR) >= 800 && ($jamOutR - $jamInR) < 1600){
						$uangMakan = 2500; 
					} elseif(($jamOutR - $jamInR) >= 1600){
						$uangMakan = 5000; 
					}
					if(($jamInR) <= 600){
						$nominalLembur = $nominalLembur + 5000; 
					}
					if($HariIni == 'SUNDAY' || $HariLibur != 0){
						$nominalLembur = $nominalLembur + 10000; 
					}
				} else {
					$uangMakan = 0;
					$nominalLembur = 0;
				}
				
				$jumlahDetA = 0;
				$resultDetA = oci_parse($con, "SELECT COUNT(-1)
				FROM MJ.MJ_T_RITASI_LEMBUR_DRIVER TRLD
				WHERE TRLD.STATUS = 'Y'
				AND TO_CHAR(TRLD.TGL, 'YYYY-MM-DD') = '$tgSJ'
				AND TRLD.PERSON_ID = $nama
				AND TRLD.LEMBUR > 0
				");
				oci_execute($resultDetA);
				$rowJumDetA = oci_fetch_row($resultDetA);
				$jumlahDetA = $rowJumDetA[0]; 
				
				if($jumlahDetA != 0){
					$nominalLembur = 0;
					//$uangMakan = 0;
				}
				
				$jumlahDet = 0;
				$resultDet = oci_parse($con, "SELECT COUNT(-1)
				FROM MJ.MJ_T_RITASI_LEMBUR_DRIVER TRLD
				WHERE TRLD.STATUS = 'Y'
				AND TO_CHAR(TRLD.TGL, 'YYYY-MM-DD') = '$tgSJ'
				AND TRLD.PERSON_ID = $nama
				AND TRLD.UANG_MAKAN > 0
				");
				oci_execute($resultDet);
				$rowJumDet = oci_fetch_row($resultDet);
				$jumlahDet = $rowJumDet[0]; 
				
				if($jumlahDet != 0){
					//$nominalLembur = 0;
					$uangMakan = 0;
				}
				
				$sqlQuery = "INSERT INTO MJ.MJ_T_RITASI_LEMBUR_DRIVER (ID, PERSON_ID, TRANSAKSI_ID, CUSTOMER_ID, NOMOR_LHO, RITASI_KE, VARIABEL, TGL, SUM_VOLUM, NOMINAL, LEMBUR, UANG_MAKAN, JAM_IN, JAM_OUT, TIPE, STATUS, CREATED_BY, CREATED_DATE) 
				VALUES ( MJ.MJ_T_RITASI_LEMBUR_SEQ.NEXTVAL, $nama, $hdid, $partyId, '$noLHO', '$ritasiKe', '$variabel', TO_DATE('$tgSJ', 'YYYY-MM-DD'), '$sumVol', $nominalTrans, $nominalLembur, $uangMakan, '$jamIn', '$jamOut', '$tipe', 'Y', '$user_id', SYSDATE)";
				// echo $sqlQuery;
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