<?PHP
require('smtpemailattachment.php');
// require('../../pdf/pdfmctable.php');
include '../../main/koneksi.php';
date_default_timezone_set("Asia/Jakarta");
session_start();
// echo "asd";exit;
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
// $pos_name = "";
// $pos_id = "";
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
	// $pos_name = $_SESSION[APP]['pos_name'];
	// $pos_id = $_SESSION[APP]['pos_id'];
  }
// echo $emp_id;exit;
if ($emp_id == 0) {
	header('Location: ../index.html');
	exit;
}
$cek = "";
$hari="";
$bulan="";
$tahun=""; 
$tglskr=date('Y-m-d'); 
$tahunGenNo=substr($tglskr, 0, 4);
$tahunbaru=substr($tglskr, 0, 2);
$tglFrom=date('Y-m-d'); 
$tglTo=date('Y-m-d'); 
$jamFrom="";
$jamTo="";
$emailTo="";
$lop2=array(); //Untuk menampung email mgr yang dipilih

$arrHDID=array();
$arrKaryawan=array();
$arrKaryawanID=array();
$arrOU=array();
$arrDept=array();
$arrPlant=array();
$arrAtasanLsg=array();
$arrAtasanTdkLsg=array();
$arrJabatan=array();
$arrGrade=array();
$arrKet=array();


if(isset($_POST['typeform'])) {

 	if($emp_id == 0){
		$data="gagal";
		$cek="Silahkan login ulang.";
	}
	else {
		$typeform = $_POST['typeform'];
		$arrHDID = json_decode($_POST['arrHDID']);
		$arrKaryawan = json_decode($_POST['arrKaryawan']);
		$arrKaryawanID = json_decode($_POST['arrKaryawanID']);
		$arrOU = json_decode($_POST['arrOU']);
		$arrDept = json_decode($_POST['arrDept']);
		$arrPlant = json_decode($_POST['arrPlant']);
		$arrJabatan = json_decode($_POST['arrJabatan']);	
		$arrGrade = json_decode($_POST['arrGrade']);
		$arrAtasanLsg = json_decode($_POST['arrAtasanLsg']);	
		$arrAtasanTdkLsg = json_decode($_POST['arrAtasanTdkLsg']);
		$arrKet = json_decode($_POST['arrKet']);
		$arrKet = str_replace("'", "`", $arrKet);
		$periode = $_POST['periode'];
		$periode1 = substr($periode, 0, 10);
		$periode2 = substr($periode, 15, 10);		
  	}
}

$Trans="";
$lengthy = end($arrKaryawanID);
for ($i=0; $i<$lengthy; $i++){
	$Trans = $arrKaryawanID[0];
	// echo $arrIDSJ[$i];exit;
}

$lop="";
$lengthx = count($arrKaryawanID);
for ($i=0; $i<$lengthx; $i++){
	$IDTrans = $arrKaryawanID[$i];			
	if($i == $lengthx-1){
		$lop .= $IDTrans;			
	}else{
		$lop .= $IDTrans.',';

	}
}
// echo $lop;exit;
if ($typeform=='tambah')
{
	if($emp_id == 0){
		$data="gagal";
		$msgError="Silahkan login ulang.";
	}else {		
		$length = count($arrKaryawan);
		for ($i=0; $i < $length ; $i++) { 		


			$queryCekPeriode = "SELECT COUNT(-1) FROM DUAL WHERE TO_CHAR(SYSDATE,'YYYY-MM-DD')  between '$periode1' and '$periode2'";
			$resultCekPeriode = oci_parse($con, $queryCekPeriode);
			oci_execute($resultCekPeriode);
			$rowCekPeriode = oci_fetch_row($resultCekPeriode);
			$counterPeriode = $rowCekPeriode [0];

			// echo $counterPeriode;exit;

			$queryCek = "SELECT count(-1) FROM MJ.MJ_OPNAME_KAR WHERE NAMA = '$arrKaryawan[$i]' AND TO_CHAR(CREATED_DATE,'YYYY-MM-DD') between '$periode1' and '$periode2' ";			
			$resultCek = oci_parse($con, $queryCek);
			oci_execute($resultCek);
			$rowCek = oci_fetch_row($resultCek);
			$counter = $rowCek [0];
			// echo $queryCek;exit;

			// echo $counter;exit;

			$resultSeqAPP = oci_parse($con,"SELECT MJ.MJ_OPNAME_KAR_SEQ.nextval FROM dual"); 
			oci_execute($resultSeqAPP);
			$rowApp = oci_fetch_row($resultSeqAPP);
			$IdTransAPP = $rowApp[0];

			if ($counterPeriode == 0) {
				$data="gagal";
				$msgError="Telah melewati periode sekarang.";
			}else{

				if ($counter == 0) {				

					$sqlQueryApp = "INSERT INTO MJ.MJ_OPNAME_KAR (ID, PERSON_ID, NAMA, OU, DEPART, PLANT, JABATAN, GRADE, ATASAN_LSG, ATASAN_TDK_LSG, KETERANGAN, CREATED_BY, CREATED_DATE, STATUS, TINGKAT, PERIODE1, PERIODE2) 
					VALUES ( $IdTransAPP, $arrKaryawanID[$i], '$arrKaryawan[$i]', '$arrOU[$i]', '$arrDept[$i]', '$arrPlant[$i]', '$arrJabatan[$i]', '$arrGrade[$i]', '$arrAtasanLsg[$i]', '$arrAtasanTdkLsg[$i]', '$arrKet[$i]', $emp_id, SYSDATE,'In Process', 1, '$periode1', '$periode2')";
					// echo $sqlQueryApp;exit;
					$resultApp = oci_parse($con,$sqlQueryApp);
					oci_execute($resultApp);
				}
				else{
					$queryUpdte = "UPDATE MJ.MJ_OPNAME_KAR SET OU = '$arrOU[$i]', DEPART = '$arrDept[$i]', PLANT = '$arrPlant[$i]', JABATAN = '$arrJabatan[$i]', GRADE = '$arrGrade[$i]', ATASAN_LSG = '$arrAtasanLsg[$i]', ATASAN_TDK_LSG = '$arrAtasanTdkLsg[$i]', KETERANGAN = '$arrKet[$i]', LAST_UPDATED_BY = $emp_id, LAST_UPDATED_DATE = SYSDATE, STATUS = 'In Process', TINGKAT = 1
						WHERE PERSON_ID = $arrKaryawanID[$i]";

					$resultUpdte = oci_parse($con, $queryUpdte);
					oci_execute($resultUpdte);
				}
				$data="sukses";
				$msgError="sukses";				
			}


		//kirim email
		$querycountMan1 = "SELECT COUNT(-1) FROM PER_PEOPLE_F PPF, PER_POSITIONS PP, PER_ASSIGNMENTS_F PAF 
			WHERE PPF.FULL_NAME = '$arrAtasanLsg[$i]' AND PAF.PERSON_ID = PPF.PERSON_ID AND PAF.POSITION_ID = PP.POSITION_ID AND PP.NAME LIKE '%MGR%'";
		$resultcountman1 = oci_parse($con,$querycountMan1);
		oci_execute($resultcountman1);
		$rowcountman1 = oci_fetch_row($resultcountman1);
		$jumman1 = $rowcountman1[0];

		$querycountMan2 = "SELECT COUNT(-1) FROM PER_PEOPLE_F PPF, PER_POSITIONS PP, PER_ASSIGNMENTS_F PAF 
		WHERE PPF.FULL_NAME = '$arrAtasanLsg[$i]' AND PAF.PERSON_ID = PPF.PERSON_ID AND PAF.POSITION_ID = PP.POSITION_ID AND PP.NAME LIKE '%MGR%'";
		$resultcountman2 = oci_parse($con,$querycountMan2);
		oci_execute($resultcountman2);
		$rowcountman2 = oci_fetch_row($resultcountman2);
		$jumman2 = $rowcountman2[0];

		// echo $jumman1

		if($jumman1 != 0){ //Jika atasan lsg mgr
			$queryEmailMan1 = "SELECT DISTINCT PPF.EMAIL_ADDRESS, PPF2.FULL_NAME FROM PER_PEOPLE_F PPF, PER_PEOPLE_F PPF2, MJ.MJ_OPNAME_KAR MOK 
			WHERE MOK.ATASAN_LSG = PPF.FULL_NAME
			AND MOK.CREATED_BY = PPF2.PERSON_ID
			AND PPF.FULL_NAME = '$arrAtasanLsg[$i]'";
			$resultEmailMan1 = oci_parse($con,$queryEmailMan1);
			oci_execute($resultEmailMan1);
			$rowEmailMan1= oci_fetch_row($resultEmailMan1);
			$emailTo 	= $rowEmailMan1[0];
			$pembuat 	= $rowEmailMan1[1];
			$lop2[$i]	= $emailTo;
		}else{  
			$queryEmailMan2 = "SELECT PPF.EMAIL_ADDRESS, PPF2.FULL_NAME FROM PER_PEOPLE_F PPF, PER_PEOPLE_F PPF2, MJ.MJ_OPNAME_KAR MOK 
			WHERE MOK.ATASAN_TDK_LSG = PPF.FULL_NAME
			AND MOK.CREATED_BY = PPF2.PERSON_ID
			AND PPF.FULL_NAME = '$arrAtasanTdkLsg[$i]'";
			$resultEmailMan2 = oci_parse($con,$queryEmailMan2);
			oci_execute($resultEmailMan2);
			$rowEmailMan2= oci_fetch_row($resultEmailMan2);
			$emailTo 	 = $rowEmailMan2[0];
			$pembuat 	 = $rowEmailMan2[1];
			$lop2[$i]	 = $emailTo;
		}
}

$unique1=array_unique($lop2); //Untuk distinct array pada email mgr

$new = array_values($unique1); //Untuk buat array baru yang didapat dari uniq agar array dimulai dari 0

$length1 = count($new);

	for ($i=0; $i < $length1; $i++) { 	
	
		$emailJam = date("H");
		$subjectEmail = "[Autoemail] Opname HR Area";
		
		$mail = new PHPMailer();
		$mail->Hostname = '192.168.0.35';
		$mail->Port = 25;
		$mail->Host = "192.168.0.35";
		$mail->SMTPAuth = true;
		$mail->Username = 'autoemail.it@merakjaya.co.id';
		$mail->Password = 'autoemail';

		$mail->Mailer = 'smtp';
		$mail->From = "autoemail.it@merakjaya.co.id";
		$mail->FromName = "Auto Email"; 
		$mail->Subject = $subjectEmail;
		$bodyEmail = "Dear hmm , 
Mohon untuk melakukan cek data opname yang dibuat oleh $pembuat

Email_To $new[$i]

Terima kasih";
		$mail->Body = $bodyEmail;
		
		// $mail->AddAddress($email_spv);	
		$mail->addCC('allifiando.d@merakjaya.co.id');
		$mail->addCC('fajar.setiawan@merakjaya.co.id');					
		$success = $mail->Send();
	}
}
}
$result=array('success'=>true,'results'=>$msgError,'rows'=>$data);
echo json_encode($result);

?>