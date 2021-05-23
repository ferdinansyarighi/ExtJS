<?PHP
require('smtpemailattachment.php');
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
	$emp_name = str_replace("'", "''", $_SESSION[APP]['emp_name']);
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
$tahunGenNo=substr($tglskr, 0, 4);
$data="gagal";
$arrNamaFile=array();
$countID=0;

 if(isset($_POST['hdid']))
  {
  	$hdid=$_POST['hdid'];
	$typeform=$_POST['typeform'];
	//$nama_user=$_POST['nama_user'];
	$person_id=$_POST['person_id'];
  	$tipe=$_POST['tipe'];
	
  	$no_rek=$_POST['no_rek'];
	$no_rek=str_replace("'", "`", $no_rek);
	
  	$tgl=$_POST['tgl'];
  	$tgl=substr($tgl,0,10);
  	$tgl_jt=$_POST['tgl_jt'];
	$tgl_jt=substr($tgl_jt,0,10);
	
	
	
	$param_cb_rek_transfer = $_POST['param_cb_rek_transfer'];
	$param_cb_rek_transfer_nama = $_POST['param_cb_rek_transfer_nama'];
	$param_cb_rek_transfer_nomor = $_POST['param_cb_rek_transfer_nomor'];
	$param_cb_rek_transfer_id = $_POST['param_cb_rek_transfer_id'];
	
	
	if($typeform == "edit"){
		$queryLogin = "SELECT PERUSAHAAN_ID FROM MJ.MJ_M_APPROVAL_PINJAMAN WHERE TIPE = 'BS' AND STATUS = 'A' AND KASIR_ID = $emp_id";
		//echo $queryLogin;exit;
		$resultLogin = oci_parse($con,$queryLogin);
		oci_execute($resultLogin);
		$rowLogin = oci_fetch_row($resultLogin);
		$hrdOrgId = $rowLogin[0]; 
		if($hrdOrgId == ''){
			$hrdOrgId = '-1';
		}
		
		$query = "SELECT count(*)
			FROM MJ.MJ_T_BS BS
			INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON BS.ASSIGNMENT_ID =PAF.ASSIGNMENT_ID 
			AND PAF.EFFECTIVE_END_DATE > SYSDATE AND PAF.PRIMARY_FLAG='Y'
			INNER JOIN PER_PEOPLE_F PPF ON PAF.PERSON_ID = PPF.PERSON_ID 
			AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y' AND PPF.EFFECTIVE_END_DATE > SYSDATE
			INNER JOIN PER_PEOPLE_F PPF2 ON BS.PENANGGUNG_JAWAB = PPF2.PERSON_ID 
			AND PPF2.CURRENT_EMPLOYEE_FLAG = 'Y' AND PPF2.EFFECTIVE_END_DATE > SYSDATE
			INNER JOIN APPS.HR_ORGANIZATION_UNITS HOU ON PAF.ORGANIZATION_ID = HOU.ORGANIZATION_ID
			WHERE 1=1 AND BS.TINGKAT = 5 AND BS.STATUS = 'Approved' 
			--and BS.TGL_PENCAIRAN IS NULL
			 and (BS.PERUSAHAAN_BS = $hrdOrgId or
				BS.PERUSAHAAN_BS in (SELECT PERUSAHAAN_ID FROM MJ.MJ_M_APPROVAL_PINJAMAN WHERE TIPE = 'BS' AND STATUS = 'A' AND KASIR_ID is not null AND KASIR_ID = $emp_id) or BS.PERUSAHAAN_BS not in (SELECT PERUSAHAAN_ID FROM MJ.MJ_M_APPROVAL_PINJAMAN WHERE TIPE = 'BS' AND STATUS = 'A' AND KASIR_ID is not null)) 
			  and bs.id in ($hdid)
			ORDER BY BS.ID
			";
			//echo $query;exit;
		$resultCount = oci_parse($con,$query);
		oci_execute($resultCount);
		$rowCount = oci_fetch_row($resultCount);
		$countData = $rowCount[0];
		//echo $countData;exit;
		
		if($countData == 0){
			
			$data="tidakadahakakses";
			
		} else {
			
			$resultCek = oci_parse($con,"SELECT STATUS FROM MJ.MJ_T_BS WHERE ID=$hdid"); 
			oci_execute($resultCek);
			$rowCek = oci_fetch_row($resultCek);
			$statusBS = $rowCek[0];
			
			if ( $statusBS == 'Approved' ) {
				
				$sqlQuery = "
				UPDATE MJ.MJ_T_BS 
				SET TINGKAT = 6, 
					STATUS = 'Validated',
					TIPE_PENCAIRAN = '$tipe', 
					NO_REK = '$no_rek', 
					TGL_PENCAIRAN = to_date('$tgl','YYYY-MM-DD'), 
					TGL_JT = to_date('$tgl_jt','YYYY-MM-DD'),
					REK_BANK_TRANSFER = '$param_cb_rek_transfer',
					NAME_BANK_TRANSFER = '$param_cb_rek_transfer_nama',
					BANK_ACCOUNT_ID = '$param_cb_rek_transfer_nomor',
					BANK_NAME = '$param_cb_rek_transfer_id'
				WHERE 1 = 1 
				AND ID = $hdid ";
				
				$result = oci_parse($con,$sqlQuery);
				oci_execute($result);
				
				//delete  file attachment PINJAMAN sebelum dilakukan insert sesuai data di temp upload
				$queryUpload = "DELETE FROM MJ.MJ_M_UPLOAD WHERE APP_ID=" . APPCODE . " AND USERNAME='$user_id' AND TRANSAKSI_KODE='BONKASIR' AND TRANSAKSI_ID=$hdid";
				//echo $query;
				$resultUpload = oci_parse($con,$queryUpload);
				oci_execute($resultUpload);
					
				$query = "SELECT FILENAME, FILESIZE, FILETYPE, TRANSAKSI_KODE
				FROM MJ.MJ_TEMP_UPLOAD
				WHERE APP_ID=" . APPCODE . " AND USERNAME='$user_id'
				AND TRANSAKSI_KODE='BONKASIR'";
				$result = oci_parse($con, $query);
				oci_execute($result);
				while($row = oci_fetch_row($result))
				{
					$vFilename=$row[0];
					$vFilesize=$row[1];
					$vFiletype=$row[2];
					$vFilekode=$row[3];
					
					$resultDetSeq = oci_parse($con,"SELECT MJ.MJ_M_UPLOAD_SEQ.nextval FROM DUAL");
					oci_execute($resultDetSeq);
					$rowDSeq = oci_fetch_row($resultDetSeq);
					$seqD = $rowDSeq[0];
					
					
					$queryUpload = "INSERT INTO MJ.MJ_M_UPLOAD (ID, APP_ID, TRANSAKSI_ID, FILENAME, FILESIZE, FILETYPE, USERNAME, CREATEDDATE, TRANSAKSI_KODE) 
					VALUES ($seqD, " . APPCODE . ", $hdid, '$vFilename', '$vFilesize', '$vFiletype', '$user_id', SYSDATE, '$vFilekode')";
					//echo $query;
					$resultUpload = oci_parse($con,$queryUpload);
					oci_execute($resultUpload);
				}
				
				$query = "DELETE FROM MJ.MJ_TEMP_UPLOAD
				WHERE APP_ID=" . APPCODE . " AND USERNAME='$user_id'
				AND TRANSAKSI_KODE='BONKASIR'";
				$result = oci_parse($con, $query);
				oci_execute($result);
				//end insert upload PINJAMAN
				
				
				/* DIPAKAI */
				
				$resultNo = oci_parse($con,"SELECT DISTINCT BS.NO_BS, BS.TIPE, PJ.NAME DEPT_PEM, PP.NAME POS_PEM, PG.NAME
						, INITCAP(PPF2.TITLE)||PPF2.FIRST_NAME||' '||PPF2.LAST_NAME PJ, 'Rp '||TRIM( TO_CHAR( BS.NOMINAL, '999,999,999.99' ) ) NOMINAL, BS.KETERANGAN
						, PPF.EMAIL_ADDRESS, PPF2.EMAIL_ADDRESS, PPF.FULL_NAME, HOU.NAME
						, BS.TIPE_PENCAIRAN, DECODE(BS.TIPE_PENCAIRAN, 'TRANSFER', BS.NO_REK, ' - '), TO_CHAR(BS.TGL_PENCAIRAN, 'DD-Mon-YYYY')
						, HOU2.NAME, TO_CHAR(BS.TGL_JT, 'DD-MON-YYYY')
						FROM MJ.MJ_T_BS BS
						INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON BS.ASSIGNMENT_ID =PAF.ASSIGNMENT_ID AND PAF.EFFECTIVE_END_DATE > SYSDATE AND PAF.PRIMARY_FLAG='Y'
						INNER JOIN PER_PEOPLE_F PPF ON PAF.PERSON_ID = PPF.PERSON_ID AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y' AND PPF.EFFECTIVE_END_DATE > SYSDATE
						INNER JOIN PER_PEOPLE_F PPF2 ON BS.PENANGGUNG_JAWAB = PPF2.PERSON_ID AND PPF2.CURRENT_EMPLOYEE_FLAG = 'Y' AND PPF2.EFFECTIVE_END_DATE > SYSDATE
						INNER JOIN APPS.PER_ASSIGNMENTS_F PAF2 ON PPF2.PERSON_ID =PAF2.PERSON_ID AND PAF2.EFFECTIVE_END_DATE > SYSDATE AND PAF2.PRIMARY_FLAG='Y'
						INNER JOIN APPS.HR_ORGANIZATION_UNITS HOU ON PAF.ORGANIZATION_ID = HOU.ORGANIZATION_ID
						INNER JOIN APPS.HR_ORGANIZATION_UNITS HOU2 ON BS.PERUSAHAAN_BS = HOU2.ORGANIZATION_ID
						INNER JOIN APPS.PER_JOBS PJ ON PJ.JOB_ID=PAF.JOB_ID
						INNER JOIN APPS.PER_POSITIONS PP ON PAF.POSITION_ID=PP.POSITION_ID
						INNER JOIN APPS.HR_LOCATIONS HL ON HL.LOCATION_ID=PAF.LOCATION_ID
						INNER JOIN APPS.PER_JOBS PJ2 ON PJ2.JOB_ID=PAF2.JOB_ID
						INNER JOIN APPS.PER_POSITIONS PP2 ON PAF2.POSITION_ID=PP2.POSITION_ID
						INNER JOIN APPS.PER_GRADES PG ON PAF.GRADE_ID = PG.GRADE_ID
						WHERE BS.ID = $hdid");
				oci_execute($resultNo);
				$rowNo = oci_fetch_row($resultNo);
				$noBS = $rowNo[0];
				$tipeBS = $rowNo[1];
				$deptPem = $rowNo[2];
				$posPem = $rowNo[3];
				$gradePem = $rowNo[4];
				$pj = $rowNo[5];
				$nominal = $rowNo[6];
				$keterangan = $rowNo[7];
				$emailPem = $rowNo[8];
				$emailPj = $rowNo[9];
				$namaPem = $rowNo[10];
				$companyPem = $rowNo[11];
				$tipePencairan = $rowNo[12];
				$nomorRekening= $rowNo[13];
				$tglPencairan = $rowNo[14];
				$perusahaanBS = $rowNo[15];
				$tglJt = $rowNo[16];
				
				//autoemail
				$emailJam = date("H");
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
				$subjectEmail = "[Autoemail] Pengajuan BS No. $noBS";
				$mail->Subject = $subjectEmail;
				$bodyEmail = "Dear $namaPem, 
	
Telah divalidasi oleh kasir pengajuan BS $tipeBS nomor $noBS dengan informasi berikut :

Nama Karyawan :   $namaPem
Perusahaan :   $companyPem
Department :   $deptPem
Posisi / Grade :   $posPem / $gradePem
Perusahaan BS :   $perusahaanBS
Penanggung Jawab :   $pj
Nominal :   $nominal
Tgl Jatuh Tempo :   $tglJt
Keterangan :   $keterangan
Tipe Pencairan :   $tipePencairan
No. Rekening :   $nomorRekening
Tgl Pencairan :   $tglPencairan

Mohon dilakukan pengecekan atas pencairan BS $tipeBS diatas.
Terima Kasih.
";
				$mail->Body = $bodyEmail;
				
				
				/* DIPAKAI LIVE */
				
				$mail->addCC($emailPj);	
				$mail->addCC('greta.silviana@merakjaya.co.id');
				$mail->addCC('fitri.ambarwati@merakjaya.co.id');
				$mail->addCC('maria.natalia@merakjaya.co.id');
				$mail->addCC('dwatra@merakjaya.co.id');
				$mail->AddAddress($emailPem);

				$success = $mail->Send();
				
				/* DIPAKAI LIVE */
				
				
				
				/* DIPAKAI TESTING
				
				$mail->addCC('maria.natalia@merakjaya.co.id');
				$mail->addCC('dwatra@merakjaya.co.id');
				$mail->addCC( 'yuke.indarto@merakjaya.co.id' );
				
				DIPAKAI TESTING */
				
				
				
				/* DIPAKAI */
				
				$data="sukses";
			}
		}
	}
	
	$resultNo = oci_parse($con,"SELECT NO_BS FROM MJ.MJ_T_BS WHERE ID = $hdid");
	oci_execute($resultNo);
	$rowNo = oci_fetch_row($resultNo);
	$noBS = $rowNo[0];
	
	
	$result = array('success' => true,
					'results' => $hdid .'|'. $noBS,
					'rows' => $data
				);
	echo json_encode($result);
  }


?>