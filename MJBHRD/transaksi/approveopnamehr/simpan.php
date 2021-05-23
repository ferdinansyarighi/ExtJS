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
// exit;
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
$arrKetMgr=array();

function xlsBOF() {
echo pack("ssssss", 0x809, 0x8, 0x0, 0x10, 0x0, 0x0);
return;
}

// Function penanda akhir file (End Of File) Excel

function xlsEOF() {
echo pack("ss", 0x0A, 0x00);
return;
}

// Function untuk menulis data (angka) ke cell excel

function xlsWriteNumber($Row, $Col, $Value) {
echo pack("sssss", 0x203, 14, $Row, $Col, 0x0);
echo pack("d", $Value);
return;
}

// Function untuk menulis data (text) ke cell excel

function xlsWriteLabel($Row, $Col, $Value ) {
$L = strlen($Value);
echo pack("ssssss", 0x204, 8 + $L, $Row, $Col, 0x0, $L);
echo $Value;
return;
}

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
		$arrKetMgr = json_decode($_POST['arrKetMgr']);
		$arrKetMgrHR = json_decode($_POST['arrKetMgrHR']);
		$arrKetMgrHR = str_replace("'", "`", $arrKetMgrHR);		
		$status2 = $_POST['status'];
		// echo $status2;exit;
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
		$length = count($arrHDID);
		for ($i=0; $i < $length ; $i++) { 	
		// echo $status2;exit;		

			$resultSeqAPP = oci_parse($con,"SELECT MJ.MJ_T_APPROVAL_SEQ.nextval FROM dual"); 
			oci_execute($resultSeqAPP);
			$rowApp = oci_fetch_row($resultSeqAPP);
			$IdTransAPP = $rowApp[0];
			
			$sqlQueryApp = "INSERT INTO MJ.MJ_T_APPROVAL (ID, APP_ID, EMP_ID, TRANSAKSI_ID, TRANSAKSI_KODE, TINGKAT, STATUS, KETERANGAN, CREATED_BY, CREATED_DATE) 
				VALUES ( $IdTransAPP, " . APPCODE . ", '$emp_id', $arrHDID[$i], 'OPNAME_KAR', 3, '$status2', '$arrKetMgr[$i]', $emp_id, SYSDATE)";
			$resultApp = oci_parse($con,$sqlQueryApp);
			oci_execute($resultApp);

			if ($status2 == 'Approved') {							
				$sqlQueryA = "UPDATE MJ.MJ_OPNAME_KAR SET TINGKAT = 3, STATUS = '$status2', LAST_UPDATED_BY = $emp_id, LAST_UPDATED_DATE = SYSDATE,KETERANGAN_HR = '$arrKetMgrHR[$i]' WHERE ID = $arrHDID[$i]";	
				// echo $sqlQueryA;exit;			
				$resultA = oci_parse($con,$sqlQueryA);
				oci_execute($resultA);	
			}else{
				$sqlQueryA = "UPDATE MJ.MJ_OPNAME_KAR SET TINGKAT = 0, STATUS = '$status2', LAST_UPDATED_BY = $emp_id, LAST_UPDATED_DATE = SYSDATE, KETERANGAN_HR = '$arrKetMgrHR[$i]' WHERE ID = $arrHDID[$i]";
				// echo $sqlQueryA;exit;
				$resultA = oci_parse($con,$sqlQueryA);
				oci_execute($resultA);
			}
		}


		//Excel
		$countibase = 0;
		$namaFile ="EXCEL/Data_Opname_Karyawan_$IdTransAPP.xls";
		// header file excel
		header("Status: 200");
			header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
			header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
			header("Pragma: hack");
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
			header("Cache-Control: private", false);
			header("Content-Description: File Transfer");
			header("Content-Type: application/force-download");
			header("Content-Type: application/download");
			header("Content-Disposition: attachment; filename=\"".$namaFile."\"");
			header("Content-Transfer-Encoding: binary");

		$isiExcel = "<table border=\"0\">
		<tr>
		    <td colspan=\"15\"><div align=\"left\">List Opname Data Karyawan</div></td>
		</tr>

		 <tr>
			<td colspan=\"15\"><div align=\"left\"></div></td>
		 </tr>
		  <tr>
			<td colspan=\"15\"><div align=\"left\"><table border=\"1\">
				<tr>
					<td><div align=\"center\">No</div></td>
					<td><div align=\"center\">Nama Karyawan</div></td>
					<td><div align=\"center\">Organization Units</div></td>
					<td><div align=\"center\">Organization Units Pengganti</div></td>
					<td><div align=\"center\">Departemen</div></td>
					<td><div align=\"center\">Departemen Pengganti</div></td>
					<td><div align=\"center\">Plant</div></td>
					<td><div align=\"center\">Plant Pengganti</div></td>
					<td><div align=\"center\">Jabatan</div></td>
					<td><div align=\"center\">Jabatan Pengganti</div></td>
					<td><div align=\"center\">Grade</div></td>
					<td><div align=\"center\">Grade Pengganti</div></td>			
					<td><div align=\"center\">Atasan Langung</div></td>
					<td><div align=\"center\">Atasan Langsung Pengganti</div></td>			
					<td><div align=\"center\">Atasan Tidak Langung</div></td>
					<td><div align=\"center\">Atasan Tidak Langsung Pengganti</div></td>			
					<td><div align=\"center\">Keterangan</div></td>	
					<td><div align=\"center\">Keterangan Manager</div></td>	
					<td><div align=\"center\">Keterangan Manager HRD</div></td>	
					<td><div align=\"center\">Status</div></td>	
					<td><div align=\"center\">Approval Terakhir</div></td>	
				</tr>
			</table></div></td>
		 </tr>


		 ";

		$result=oci_parse($con,"
			SELECT DISTINCT MOK.ID, MOK.NAMA, 
			HOU.NAME OU_ASLI, MOK.OU OU_PENGGANTI,
			CASE WHEN REGEXP_SUBSTR(PJ.NAME, '[^.]+', 1, 3) IS NULL THEN REGEXP_SUBSTR(PJ.NAME, '[^.]+', 1, 2) ELSE REGEXP_SUBSTR(PJ.NAME, '[^.]+', 1, 3) END DEPART_ASLI , MOK.DEPART DEPART_PENGGANTI, 
			HL.LOCATION_CODE PLANT_ASLI, MOK.PLANT PLANT_PENGGANTI, 
			PP.NAME JABATAN_ASLI, MOK.JABATAN JABATAN_PENGGANTI, 
			PG.NAME GRADE_ASLI, MOK.GRADE GRADE_PENGGANTI, 
			PPF3.FULL_NAME ATASAN_LSG_ASLI, MOK.ATASAN_LSG ATASAN_LSG_PENGGANTI, 
			PPF4.FULL_NAME ATASAN_TDK_LSG_ASLI, MOK.ATASAN_TDK_LSG ATASAN_TDK_LSG_PENGGANTI, 
			MOK.KETERANGAN,
			MOK.KETERANGAN_MGR,
			MOK.KETERANGAN_HR,
			MOK.STATUS,
			PPF2.FULL_NAME
			FROM MJ.MJ_OPNAME_KAR MOK
			INNER JOIN PER_PEOPLE_F PPF ON MOK.PERSON_ID = PPF.PERSON_ID	
			INNER JOIN PER_ASSIGNMENTS_F PAF ON PPF.PERSON_ID = PAF.PERSON_ID
			INNER JOIN PER_JOBS PJ ON PAF.JOB_ID = PJ.JOB_ID
			INNER JOIN PER_GRADES PG ON PAF.GRADE_ID = PG.GRADE_ID
			INNER JOIN PER_POSITIONS PP ON PAF.POSITION_ID = PP.POSITION_ID
			INNER JOIN HR_ORGANIZATION_UNITS HOU ON PP.ORGANIZATION_ID = HOU.ORGANIZATION_ID 
			INNER JOIN HR_LOCATIONS HL ON PAF.LOCATION_ID = HL.LOCATION_ID
			LEFT JOIN PER_PEOPLE_F PPF2 ON MOK.LAST_UPDATED_BY = PPF2.PERSON_ID
			LEFT JOIN PER_PEOPLE_F PPF3 ON PAF.ASS_ATTRIBUTE1 = PPF3.PERSON_ID
        	LEFT JOIN PER_PEOPLE_F PPF4 ON PAF.ASS_ATTRIBUTE2 = PPF4.PERSON_ID
			WHERE MOK.PERSON_ID in ($lop)
			");

			oci_execute($result);
			while($row = oci_fetch_row($result))
			{
				$hdid 					=$row[0];
				$nama 		 			=$row[1];
				$ou_asli 				=$row[2];
				$ou_ganti 				=$row[3];
				$dept_asli  			=$row[4];
				$dept_ganti  			=$row[5];
				$plant_asli 			=$row[6];
				$plant_ganti 			=$row[7];
				$jabatan_asli 			=$row[8];
				$jabatan_ganti 	 		=$row[9];
				$grade_asli 	 		=$row[10];
				$grade_ganti 	 		=$row[11];
				$atasan_lsg_asli 		=$row[12];
				$atasan_lsg_ganti 		=$row[13];
				$atasan_tdk_lsg_asli 	=$row[14];
				$atasan_tdk_lsg_ganti 	=$row[15];
				$keterangan 			=$row[16];
				$keterangan_mgr 		=$row[17];
				$keterangan_hr 			=$row[18];
				$status 				=$row[19];
				$last_appr 				=$row[20];
				$countibase++;
				
				$isiExcel .= "
				 <tr>
					<td colspan=\"15\"><div align=\"left\"><table border=\"1\">
						<tr>
							<td><div align=\"center\">$countibase</div></td>
							<td><div align=\"center\">$nama</div></td>
							<td><div align=\"center\">$ou_asli</div></td>
							<td><div align=\"left\">$ou_ganti</div></td>
							<td><div align=\"left\">$dept_asli</div></td>
							<td><div align=\"left\">$dept_ganti</div></td>
							<td><div align=\"left\">$plant_asli</div></td>
							<td><div align=\"left\">$plant_ganti</div></td>
							<td><div align=\"left\">$jabatan_asli</div></td>
							<td><div align=\"left\">$jabatan_ganti</div></td>		
							<td><div align=\"left\">$grade_asli</div></td>		
							<td><div align=\"left\">$grade_ganti</div></td>		
							<td><div align=\"left\">$atasan_lsg_asli</div></td>		
							<td><div align=\"left\">$atasan_lsg_ganti</div></td>		
							<td><div align=\"left\">$atasan_tdk_lsg_asli</div></td>		
							<td><div align=\"left\">$atasan_tdk_lsg_ganti</div></td>		
							<td><div align=\"left\">$keterangan</div></td>		
							<td><div align=\"left\">$keterangan_mgr</div></td>		
							<td><div align=\"left\">$keterangan_hr</div></td>		
							<td><div align=\"left\">$status</div></td>		
							<td><div align=\"left\">$last_appr</div></td>		
						</tr>
					</table></div></td>
				 </tr>
				";
				 
			}
			
		$isiExcel .= " </table>";
		//echo $isiExcel;
		$fp = fopen($namaFile, "w");
		fwrite($fp, $isiExcel);

		fclose($fp);

		//kirim email		


		if ($status2 == 'Approved') {

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
		$bodyEmail = "Dear HR DB , 
Mohon untuk melakukan cek data opname yang Telah Di-approve oleh Manager HRD

Data Terlampir 

Terima kasih";
		$mail->Body = $bodyEmail;
		
		// $mail->AddAddress($email_spv);	
		$mail->addCC('allifiando.d@merakjaya.co.id');
		$mail->addCC('fajar.setiawan@merakjaya.co.id');			
		$mail->addAttachment('EXCEL/Data_Opname_Karyawan_'.$IdTransAPP.'.xls');			
		$success = $mail->Send();
	}
	else{
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
		$bodyEmail = "Dear HR DB , 
Mohon untuk melakukan cek data opname yang Telah Di-disapprove oleh Manager HRD

Data Terlampir 

Terima kasih";
		$mail->Body = $bodyEmail;
		
		// $mail->AddAddress($email_spv);	
		$mail->addCC('allifiando.d@merakjaya.co.id');
		$mail->addCC('fajar.setiawan@merakjaya.co.id');			
		$mail->addAttachment('EXCEL/Data_Opname_Karyawan_'.$IdTransAPP.'.xls');			
		$success = $mail->Send();
	}
	$data="sukses";
	$msgError="sukses";
}
}
$result=array('success'=>true,'results'=>$msgError,'rows'=>$data);
echo json_encode($result);

?>