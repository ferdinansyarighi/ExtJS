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
$attachment="";
$typeform="";
$Keputusan="";
$arrTransID=array();
$arrAlasan=array();
$emailSblmPengepprove='';
$idBs500 = 0;

 if(isset($_POST['typeform']))
  {
	  
	$typeform=$_POST['typeform'];
	if ($typeform=='Setuju') {
		$Keputusan = 'Approved';
	} else {
		$Keputusan = 'Disapproved';
	}
	$arrTransID=json_decode($_POST['arrTransID']);
	$arrAlasan=json_decode($_POST['arrAlasan']);
	$arrTglJTarr=json_decode($_POST['arrTglJT']);
	
  }
  
$countID = count($arrTransID);
//echo $countID;exit;
for ($x=0; $x<$countID; $x++){
	$TransID = $arrTransID[$x];
	$AlasanApp = $arrAlasan[$x];
	
	$arrTglJT = $arrTglJTarr[$x];
	$arrTglJT=substr($arrTglJT,0,10);
	
	$AlasanApp=str_replace("'", "`", $AlasanApp);
	
	$queryTingkat = "SELECT TINGKAT, NOMINAL, BYHRD FROM MJ.MJ_T_BS WHERE ID=$TransID";
	// echo $queryTingkat;
	$resultTingkat = oci_parse($con,$queryTingkat);
	oci_execute($resultTingkat);
	$rowTingkat = oci_fetch_row($resultTingkat);
	$tingkat = $rowTingkat[0];
	$nominal = $rowTingkat[1];
	$byhrd = $rowTingkat[2];
	$tingkat++;
	
	if ($typeform=='Setuju') 
	{
		if($tingkat == 1){
			$tingkat++;
		}
		
		if($byhrd == 'Y'){
			$tingkat = 5;
		}

		$resultSeq = oci_parse($con,"SELECT MJ.MJ_T_APPROVAL_SEQ.nextval FROM DUAL");
		oci_execute($resultSeq);
		$rowHSeq = oci_fetch_row($resultSeq);
		$hdid = $rowHSeq[0];
		
		$query = "INSERT INTO MJ.MJ_T_APPROVAL (ID, APP_ID, EMP_ID, TRANSAKSI_ID, TRANSAKSI_KODE, TINGKAT, STATUS, KETERANGAN, CREATED_BY, CREATED_DATE) 
		VALUES ($hdid, " . APPCODE . ", '$emp_id', $TransID, 'BON', $tingkat, '$Keputusan', '$AlasanApp', '$emp_name', SYSDATE)";
		//echo $query;
		$result = oci_parse($con,$query);
		oci_execute($result);
		
		$queryUpdate = "
		UPDATE MJ.MJ_T_BS 
		SET TINGKAT = $tingkat, 
			STATUS = '$Keputusan', 
			LAST_UPDATED_BY = '$emp_id', 
			LAST_UPDATED_DATE = SYSDATE,
			TGL_JT = TO_DATE( '$arrTglJT', 'YYYY-MM-DD' )
		WHERE ID=$TransID";
		
		// TGL_JT = TO_DATE( '$arrTglJT', 'DD-MON-YYYY' )
		
		// echo $queryUpdate; exit;
		
		$result = oci_parse($con, $queryUpdate );
		oci_execute($result);
		
		/* if($tingkat == 2 && $nominal > 500000){
			if($idBs500 == 0){
				$idBs500 = $TransID;
			}else{
				$idBs500 = $idBs500.", ".$TransID;
			}
		} */
		
		$resultNo = oci_parse($con,"SELECT DISTINCT BS.NO_BS, BS.TIPE, PJ.NAME DEPT_PEM, PP.NAME POS_PEM, PG.NAME
				, INITCAP(PPF2.TITLE)||PPF2.FIRST_NAME||' '||PPF2.LAST_NAME PJ, 'Rp '||TRIM( TO_CHAR( BS.NOMINAL, '999,999,999.99' ) ) NOMINAL, BS.KETERANGAN
				, PPF.EMAIL_ADDRESS, PPF2.EMAIL_ADDRESS, PPF.FULL_NAME, HOU.NAME, HOU2.NAME, TO_CHAR(BS.TGL_JT, 'DD-MON-YYYY')
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
				WHERE BS.ID = $TransID");
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
		$perusahaanBS = $rowNo[12];
		$tglJt = $rowNo[13];
		
		
		
		/* DIPAKAI */
		
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
		
		if($tingkat == 2){
			$resultCount = oci_parse($con,"SELECT COUNT(CHECKER_ID) X FROM MJ.MJ_M_APPROVAL_PINJAMAN WHERE TIPE = 'BS' AND STATUS = 'A'
				AND PERUSAHAAN_ID = (
				SELECT BS.PERUSAHAAN_BS FROM MJ.MJ_T_BS BS
							INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON BS.ASSIGNMENT_ID =PAF.ASSIGNMENT_ID AND PAF.EFFECTIVE_END_DATE > SYSDATE AND PAF.PRIMARY_FLAG='Y'
							INNER JOIN APPS.HR_ORGANIZATION_UNITS HOU ON PAF.ORGANIZATION_ID = HOU.ORGANIZATION_ID
							WHERE BS.ID = $TransID
				)");
			oci_execute($resultCount);
			$rowCount = oci_fetch_row($resultCount);
			$count = $rowCount[0];
			
			if($count == 0){
				$emailFin = "";
				
				$bodyEmail = "Dear All Tim Checker, 
	
Telah disetujui penanggung jawab pengajuan BS $tipeBS nomor $noBS dengan informasi berikut :

Nama Karyawan :   $namaPem
Perusahaan :   $companyPem
Department :   $deptPem
Posisi / Grade :   $posPem / $gradePem
Perusahaan BS :   $perusahaanBS
Penanggung Jawab :   $pj
Nominal :   $nominal
Tgl Jatuh Tempo :   $tglJt
Keterangan :   $keterangan
Note Approval :   $AlasanApp

Mohon untuk melakukan approval pengajuan BS $tipeBS diatas.
Terima Kasih.
";
				$mail->Body = $bodyEmail;
				
				
				/* DIPAKAI LIVE */
				
				$mail->addCC($emailPem);	
				$mail->addCC($emailPj);	
				$mail->addCC('greta.silviana@merakjaya.co.id');
				// $mail->addCC('fitri.ambarwati@merakjaya.co.id');
				$mail->addCC('maria.natalia@merakjaya.co.id');
				$mail->addCC('dwatra@merakjaya.co.id');
				
				$resultEmail = oci_parse($con,"SELECT DISTINCT PPF.EMAIL_ADDRESS
					FROM APPS.PER_PEOPLE_F PPF
					INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON PAF.PERSON_ID=PPF.PERSON_ID
					INNER JOIN APPS.PER_POSITIONS PP ON PP.POSITION_ID=PAF.POSITION_ID
					INNER JOIN APPS.PER_JOBS PJ ON PAF.JOB_ID = PJ.JOB_ID
					INNER JOIN APPS.PER_GRADES PG ON PAF.GRADE_ID = PG.GRADE_ID
					WHERE PPF.EFFECTIVE_END_DATE > SYSDATE
					AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y'
					AND PAF.PRIMARY_FLAG = 'Y'
					AND PJ.name like '%FIN%' and ppf.middle_names = 'FAM' ");
				oci_execute($resultEmail);
				while($rowEmail = oci_fetch_row($resultEmail))
				{
					//$emailFin .= $rowEmail[0].', ';
					$mail->AddAddress($rowEmail[0]);
				}
				
				/* DIPAKAI LIVE */
				
				
				
				/* DIPAKAI TESTING
				
				$mail->addCC('maria.natalia@merakjaya.co.id');
				$mail->addCC('dwatra@merakjaya.co.id');
				$mail->addCC( 'yuke.indarto@merakjaya.co.id' );
				
				DIPAKAI TESTING */
				
			
			} else {
				
				$resultEmail = oci_parse($con,"SELECT EMAIL_ADDRESS, INITCAP(TITLE)||REGEXP_SUBSTR(previous_last_name,'[^-]+', 2, 3)
					FROM PER_PEOPLE_F WHERE PERSON_ID = (SELECT CHECKER_ID FROM MJ.MJ_M_APPROVAL_PINJAMAN WHERE TIPE = 'BS' AND STATUS = 'A'
					AND PERUSAHAAN_ID = (
					SELECT BS.PERUSAHAAN_BS FROM MJ.MJ_T_BS BS
								INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON BS.ASSIGNMENT_ID =PAF.ASSIGNMENT_ID AND PAF.EFFECTIVE_END_DATE > SYSDATE AND PAF.PRIMARY_FLAG='Y'
								INNER JOIN APPS.HR_ORGANIZATION_UNITS HOU ON PAF.ORGANIZATION_ID = HOU.ORGANIZATION_ID
								WHERE BS.ID = $TransID
					)) AND EFFECTIVE_END_DATE > SYSDATE AND CURRENT_EMPLOYEE_FLAG = 'Y' ");
				oci_execute($resultEmail);
				$rowEmail = oci_fetch_row($resultEmail);
				$emailFin = $rowEmail[0];
				$namaFin = $rowEmail[1];
				$bodyEmail = "Dear $namaFin, 
	
Telah disetujui penanggung jawab pengajuan BS $tipeBS nomor $noBS dengan informasi berikut :

Nama Karyawan :   $namaPem
Perusahaan :   $companyPem
Department :   $deptPem
Posisi / Grade :   $posPem / $gradePem
Perusahaan BS :   $perusahaanBS
Penanggung Jawab :   $pj
Nominal :   $nominal
Tgl Jatuh Tempo :   $tglJt
Keterangan :   $keterangan
Note Approval :   $AlasanApp

Mohon untuk melakukan approval pengajuan BS $tipeBS diatas.
Terima Kasih.
";
				$mail->Body = $bodyEmail;
				
				
				
				/* DIPAKAI LIVE */
				
				$mail->addCC($emailPem);	
				$mail->addCC($emailPj);	
				$mail->addCC('greta.silviana@merakjaya.co.id');
				$mail->addCC('fitri.ambarwati@merakjaya.co.id');
				$mail->addCC('maria.natalia@merakjaya.co.id');
				$mail->addCC('dwatra@merakjaya.co.id');
				$mail->AddAddress($emailFin);
				
				/* DIPAKAI LIVE */
				
				
				
				/* DIPAKAI TESTING
				
				$mail->addCC('maria.natalia@merakjaya.co.id');
				$mail->addCC('dwatra@merakjaya.co.id');
				$mail->addCC( 'yuke.indarto@merakjaya.co.id' );
				
				DIPAKAI TESTING */
				
			}	

			$success = $mail->Send();
			
		} else if ( $tingkat == 3 ) {
			$resultCount = oci_parse($con,"SELECT COUNT(HRD_ID) X FROM MJ.MJ_M_APPROVAL_PINJAMAN WHERE TIPE = 'BS' AND STATUS = 'A'
				AND PERUSAHAAN_ID = (
				SELECT BS.PERUSAHAAN_BS FROM MJ.MJ_T_BS BS
							INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON BS.ASSIGNMENT_ID =PAF.ASSIGNMENT_ID AND PAF.EFFECTIVE_END_DATE > SYSDATE AND PAF.PRIMARY_FLAG='Y'
							INNER JOIN APPS.HR_ORGANIZATION_UNITS HOU ON PAF.ORGANIZATION_ID = HOU.ORGANIZATION_ID
							WHERE BS.ID = $TransID
				)");
			oci_execute($resultCount);
			$rowCount = oci_fetch_row($resultCount);
			$count = $rowCount[0];
			
			if($count == 0){
				$emailHrd = "";
				
				$bodyEmail = "Dear All Manager HRD, 
	
Telah disetujui checker pengajuan BS $tipeBS nomor $noBS dengan informasi berikut :

Nama Karyawan :   $namaPem
Perusahaan :   $companyPem
Department :   $deptPem
Posisi / Grade :   $posPem / $gradePem
Perusahaan BS :   $perusahaanBS
Penanggung Jawab :   $pj
Nominal :   $nominal
Tgl Jatuh Tempo :   $tglJt
Keterangan :   $keterangan
Note Approval :   $AlasanApp

Mohon untuk melakukan approval pengajuan BS $tipeBS diatas.
Terima Kasih.
";
			
				$mail->Body = $bodyEmail;
				
				
				
				/* DIPAKAI LIVE */
				
				$mail->addCC($emailPem);	
				$mail->addCC($emailPj);	
				$mail->addCC('greta.silviana@merakjaya.co.id');
				$mail->addCC('fitri.ambarwati@merakjaya.co.id');
				$mail->addCC('maria.natalia@merakjaya.co.id');
				$mail->addCC('dwatra@merakjaya.co.id');
				
				$resultEmail = oci_parse($con,"SELECT DISTINCT PPF.EMAIL_ADDRESS
				FROM APPS.PER_PEOPLE_F PPF
				INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON PAF.PERSON_ID=PPF.PERSON_ID
				INNER JOIN APPS.PER_POSITIONS PP ON PP.POSITION_ID=PAF.POSITION_ID
				INNER JOIN APPS.PER_JOBS PJ ON PAF.JOB_ID = PJ.JOB_ID
				INNER JOIN APPS.PER_GRADES PG ON PAF.GRADE_ID = PG.GRADE_ID
				WHERE PPF.EFFECTIVE_END_DATE > SYSDATE
				AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y'
				AND PAF.PRIMARY_FLAG = 'Y'
				AND PJ.name like '%HRD%' AND PG.name like '%Manager%' ");
				oci_execute($resultEmail);
				
				while($rowEmail = oci_fetch_row($resultEmail))
				{
					//$emailHrd .= $rowEmail[0].', ';
					$mail->AddAddress($rowEmail[0]);
				}
				
				/* DIPAKAI LIVE */
				
				
				
				/* DIPAKAI TESTING
				
				$mail->addCC('maria.natalia@merakjaya.co.id');
				$mail->addCC('dwatra@merakjaya.co.id');
				$mail->addCC( 'yuke.indarto@merakjaya.co.id' );
				
				DIPAKAI TESTING */
				
				
				
			} else {
				
				$resultEmail = oci_parse($con,"SELECT EMAIL_ADDRESS, INITCAP(TITLE)||REGEXP_SUBSTR(previous_last_name,'[^-]+', 2, 3)
					FROM PER_PEOPLE_F WHERE PERSON_ID = (SELECT HRD_ID FROM MJ.MJ_M_APPROVAL_PINJAMAN WHERE TIPE = 'BS' AND STATUS = 'A'
					AND PERUSAHAAN_ID = (
					SELECT BS.PERUSAHAAN_BS FROM MJ.MJ_T_BS BS
								INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON BS.ASSIGNMENT_ID =PAF.ASSIGNMENT_ID AND PAF.EFFECTIVE_END_DATE > SYSDATE AND PAF.PRIMARY_FLAG='Y'
								INNER JOIN APPS.HR_ORGANIZATION_UNITS HOU ON PAF.ORGANIZATION_ID = HOU.ORGANIZATION_ID
								WHERE BS.ID = $TransID
					)) AND EFFECTIVE_END_DATE > SYSDATE AND CURRENT_EMPLOYEE_FLAG = 'Y' ");
				oci_execute($resultEmail);
				$rowEmail = oci_fetch_row($resultEmail);
				$emailHrd = $rowEmail[0];
				$namaHrd = $rowEmail[1];
				$bodyEmail = "Dear $namaHrd, 
	
Telah disetujui checker pengajuan BS $tipeBS nomor $noBS dengan informasi berikut :

Nama Karyawan :   $namaPem
Perusahaan :   $companyPem
Department :   $deptPem
Posisi / Grade :   $posPem / $gradePem
Perusahaan BS :   $perusahaanBS
Penanggung Jawab :   $pj
Nominal :   $nominal
Tgl Jatuh Tempo :   $tglJt
Keterangan :   $keterangan
Note Approval :   $AlasanApp

Mohon untuk melakukan approval pengajuan BS $tipeBS diatas.
Terima Kasih.
";
				$mail->Body = $bodyEmail;
				
				
				
				/* DIPAKAI LIVE */
				
				$mail->addCC($emailPem);	
				$mail->addCC($emailPj);	
				$mail->addCC('greta.silviana@merakjaya.co.id');
				$mail->addCC('fitri.ambarwati@merakjaya.co.id');
				$mail->addCC('maria.natalia@merakjaya.co.id');
				$mail->addCC('dwatra@merakjaya.co.id');
				$mail->AddAddress($emailHrd);
				
				/* DIPAKAI LIVE */
				
				
				
				/* DIPAKAI TESTING
				
				$mail->addCC('maria.natalia@merakjaya.co.id');
				$mail->addCC('dwatra@merakjaya.co.id');
				$mail->addCC( 'yuke.indarto@merakjaya.co.id' );
				
				DIPAKAI TESTING */
				
				
			}	

			$success = $mail->Send();
			
		} else if($tingkat == 5){
			
			$resultCount = oci_parse($con,"SELECT COUNT(KASIR_ID) X FROM MJ.MJ_M_APPROVAL_PINJAMAN WHERE TIPE = 'BS' AND STATUS = 'A'
				AND PERUSAHAAN_ID = (
				SELECT BS.PERUSAHAAN_BS FROM MJ.MJ_T_BS BS
							INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON BS.ASSIGNMENT_ID =PAF.ASSIGNMENT_ID AND PAF.EFFECTIVE_END_DATE > SYSDATE AND PAF.PRIMARY_FLAG='Y'
							INNER JOIN APPS.HR_ORGANIZATION_UNITS HOU ON PAF.ORGANIZATION_ID = HOU.ORGANIZATION_ID
							WHERE BS.ID = $TransID
				)");
			oci_execute($resultCount);
			$rowCount = oci_fetch_row($resultCount);
			$count = $rowCount[0];
			
			if($count == 0){
				$emailFin = "";
				
				$bodyEmail = "Dear All Tim Kasir, 
	
Telah disetujui finance pengajuan BS $tipeBS nomor $noBS dengan informasi berikut :

Nama Karyawan :   $namaPem
Perusahaan :   $companyPem
Department :   $deptPem
Posisi / Grade :   $posPem / $gradePem
Perusahaan BS :   $perusahaanBS
Penanggung Jawab :   $pj
Nominal :   $nominal
Tgl Jatuh Tempo :   $tglJt
Keterangan :   $keterangan
Note Approval :   $AlasanApp

Mohon untuk melakukan approval pengajuan BS $tipeBS diatas.
Terima Kasih.
";
				$mail->Body = $bodyEmail;
				
				
				
				/* DIPAKAI LIVE */
				
				$mail->addCC($emailPem);	
				$mail->addCC($emailPj);	
				$mail->addCC('greta.silviana@merakjaya.co.id');
				$mail->addCC('fitri.ambarwati@merakjaya.co.id');
				$mail->addCC('maria.natalia@merakjaya.co.id');
				$mail->addCC('dwatra@merakjaya.co.id');
				
				$resultEmail = oci_parse($con,"SELECT DISTINCT PPF.EMAIL_ADDRESS
					FROM APPS.PER_PEOPLE_F PPF
					INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON PAF.PERSON_ID=PPF.PERSON_ID
					INNER JOIN APPS.PER_POSITIONS PP ON PP.POSITION_ID=PAF.POSITION_ID
					INNER JOIN APPS.PER_JOBS PJ ON PAF.JOB_ID = PJ.JOB_ID
					INNER JOIN APPS.PER_GRADES PG ON PAF.GRADE_ID = PG.GRADE_ID
					WHERE PPF.EFFECTIVE_END_DATE > SYSDATE
					AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y'
					AND PAF.PRIMARY_FLAG = 'Y'
					AND PJ.name like '%FIN%' and ppf.middle_names = 'MYT'  ");
					
				oci_execute($resultEmail);
				while($rowEmail = oci_fetch_row($resultEmail))
				{
					//$emailFin .= $rowEmail[0].', ';'
					$mail->AddAddress($rowEmail[0]);
				}
				
				/* DIPAKAI LIVE */
				
				
				
				/* DIPAKAI TESTING
				
				$mail->addCC('maria.natalia@merakjaya.co.id');
				$mail->addCC('dwatra@merakjaya.co.id');
				$mail->addCC( 'yuke.indarto@merakjaya.co.id' );
				
				DIPAKAI TESTING */
				
				
				
			} else {
				
				$resultEmail = oci_parse($con,"SELECT EMAIL_ADDRESS, INITCAP(TITLE)||REGEXP_SUBSTR(previous_last_name,'[^-]+', 2, 3)
					FROM PER_PEOPLE_F WHERE PERSON_ID = (SELECT KASIR_ID FROM MJ.MJ_M_APPROVAL_PINJAMAN WHERE TIPE = 'BS' AND STATUS = 'A'
					AND PERUSAHAAN_ID = (
					SELECT BS.PERUSAHAAN_BS FROM MJ.MJ_T_BS BS
								INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON BS.ASSIGNMENT_ID =PAF.ASSIGNMENT_ID AND PAF.EFFECTIVE_END_DATE > SYSDATE AND PAF.PRIMARY_FLAG='Y'
								INNER JOIN APPS.HR_ORGANIZATION_UNITS HOU ON PAF.ORGANIZATION_ID = HOU.ORGANIZATION_ID
								WHERE BS.ID = $TransID
					)) AND EFFECTIVE_END_DATE > SYSDATE AND CURRENT_EMPLOYEE_FLAG = 'Y' ");
				oci_execute($resultEmail);
				$rowEmail = oci_fetch_row($resultEmail);
				$emailFin = $rowEmail[0];
				$namaFin = $rowEmail[1];
				$bodyEmail = "Dear $namaFin, 
	
Telah disetujui finance pengajuan BS $tipeBS nomor $noBS dengan informasi berikut :

Nama Karyawan :   $namaPem
Perusahaan :   $companyPem
Department :   $deptPem
Posisi / Grade :   $posPem / $gradePem
Perusahaan BS :   $perusahaanBS
Penanggung Jawab :   $pj
Nominal :   $nominal
Tgl Jatuh Tempo :   $tglJt
Keterangan :   $keterangan
Note Approval :   $AlasanApp

Mohon untuk melakukan approval pengajuan BS $tipeBS diatas.
Terima Kasih.
";
				$mail->Body = $bodyEmail;
				
				
				
				/* DIPAKAI LIVE */
				
				$mail->addCC($emailPem);	
				$mail->addCC($emailPj);	
				$mail->addCC('greta.silviana@merakjaya.co.id');
				$mail->addCC('fitri.ambarwati@merakjaya.co.id');
				$mail->addCC('maria.natalia@merakjaya.co.id');
				$mail->addCC('dwatra@merakjaya.co.id');
				$mail->AddAddress($emailFin);
				
				/* DIPAKAI LIVE */
				
				
				
				/* DIPAKAI TESTING
				
				$mail->addCC('maria.natalia@merakjaya.co.id');
				$mail->addCC('dwatra@merakjaya.co.id');
				$mail->addCC( 'yuke.indarto@merakjaya.co.id' );
				
				DIPAKAI TESTING */
				
				
			}	

			$success = $mail->Send();
		}
		
		/* DIPAKAI */
		
		
	} else
	{ //Disapproved
		if($tingkat == 1){
			$tingkat++;
		}
		
		if($byhrd == 'Y'){
			$tingkat = 5;
		}
		
		$resultSeq = oci_parse($con,"SELECT MJ.MJ_T_APPROVAL_SEQ.nextval FROM DUAL");
		oci_execute($resultSeq);
		$rowHSeq = oci_fetch_row($resultSeq);
		$hdid = $rowHSeq[0];

		$query = "INSERT INTO MJ.MJ_T_APPROVAL (ID, APP_ID, EMP_ID, TRANSAKSI_ID, TRANSAKSI_KODE, TINGKAT, STATUS, KETERANGAN, CREATED_BY, CREATED_DATE) VALUES ($hdid, " . APPCODE . ", '$emp_id', $TransID, 'BON', $tingkat, '$Keputusan', '$AlasanApp', '$emp_name', SYSDATE)";
		//echo $query;
		$result = oci_parse($con,$query);
		oci_execute($result);
		
		$resultNo = oci_parse($con,"SELECT DISTINCT BS.NO_BS, BS.TIPE, PJ.NAME DEPT_PEM, PP.NAME POS_PEM, PG.NAME
				, INITCAP(PPF2.TITLE)||PPF2.FIRST_NAME||' '||PPF2.LAST_NAME PJ, 'Rp '||TRIM( TO_CHAR( BS.NOMINAL, '999,999,999.99' ) ) NOMINAL, BS.KETERANGAN
				, PPF.EMAIL_ADDRESS, PPF2.EMAIL_ADDRESS, PPF.FULL_NAME, HOU.NAME, HOU2.NAME, TO_CHAR(BS.TGL_JT, 'DD-MON-YYYY')
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
				WHERE BS.ID = $TransID");
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
		$perusahaanBS = $rowNo[12];
		$tglJt = $rowNo[13];
		
		
		
		
		/* DIPAKAI */
		
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
			
		if($tingkat == 2){
			$bodyEmail = "Dear $namaPem, 
	
Telah direject pengajuan BS $tipeBS nomor $noBS oleh $emp_name pada tingkat Manager/Penanggung Jawab dengan informasi berikut :

Nama Karyawan :   $namaPem
Perusahaan :   $companyPem
Department :   $deptPem
Posisi / Grade :   $posPem / $gradePem
Perusahaan BS :   $perusahaanBS
Penanggung Jawab :   $pj
Nominal :   $nominal
Tgl Jatuh Tempo :   $tglJt
Keterangan :   $keterangan
Ket. Reject :   $AlasanApp

Mohon dilakukan pengecekan atas BS $tipeBS diatas.
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
			
			/* DIPAKAI LIVE */
			
			
			
			/* DIPAKAI TESTING
			
			$mail->addCC('maria.natalia@merakjaya.co.id');
			$mail->addCC('dwatra@merakjaya.co.id');
			$mail->addCC( 'yuke.indarto@merakjaya.co.id' );
			
			DIPAKAI TESTING */
			
			
			$success = $mail->Send();
			
		} else if($tingkat == 3){
			
			$bodyEmail = "Dear $namaPem, 
	
Telah direject pengajuan BS $tipeBS nomor $noBS oleh $emp_name pada tingkat checker dengan informasi berikut :

Nama Karyawan :   $namaPem
Perusahaan :   $companyPem
Department :   $deptPem
Posisi / Grade :   $posPem / $gradePem
Penanggung Jawab :   $pj
Perusahaan BS :   $perusahaanBS
Penanggung Jawab :   $pj
Nominal :   $nominal
Tgl Jatuh Tempo :   $tglJt
Ket. Reject :   $AlasanApp

Mohon dilakukan pengecekan atas BS $tipeBS diatas.
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
			
			/* DIPAKAI LIVE */
			
			
			
			/* DIPAKAI TESTING
			
			$mail->addCC('maria.natalia@merakjaya.co.id');
			$mail->addCC('dwatra@merakjaya.co.id');
			$mail->addCC( 'yuke.indarto@merakjaya.co.id' );
			
			DIPAKAI TESTING */
			
			
			$success = $mail->Send();
			
		} else if($tingkat == 5){
			
			$bodyEmail = "Dear $namaPem, 
	
Telah direject pengajuan BS $tipeBS nomor $noBS oleh $emp_name pada tingkat finance dengan informasi berikut :

Nama Karyawan :   $namaPem
Perusahaan :   $companyPem
Department :   $deptPem
Posisi / Grade :   $posPem / $gradePem
Penanggung Jawab :   $pj
Perusahaan BS :   $perusahaanBS
Penanggung Jawab :   $pj
Nominal :   $nominal
Tgl Jatuh Tempo :   $tglJt
Ket. Reject :   $AlasanApp

Mohon dilakukan pengecekan atas BS $tipeBS diatas.
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
			
			/* DIPAKAI LIVE */
			
			
			
			/* DIPAKAI TESTING
			
			$mail->addCC('maria.natalia@merakjaya.co.id');
			$mail->addCC('dwatra@merakjaya.co.id');
			$mail->addCC( 'yuke.indarto@merakjaya.co.id' );
			
			DIPAKAI TESTING */
			
			
			$success = $mail->Send();
			
		}
		
		
		/* DIPAKAI */
		
		
		$queryPembuat = "SELECT BYHRD FROM MJ.MJ_T_BS WHERE ID=$TransID";
		// echo $queryTingkat;
		$resultPembuat = oci_parse($con,$queryPembuat);
		oci_execute($resultPembuat);
		$rowPembuat = oci_fetch_row($resultPembuat);
		$pembuat = $rowPembuat[0]; 
		
		/* $queryPosisi = "select COUNT(-1) from mj.mj_m_user
		WHERE ID IN (select ID_USER from mj.mj_sys_USER_rule WHERE ID_RULE IN (15,16))
		AND EMP_ID = $pembuat"; */
		
		/* $queryPosisi = "SELECT COUNT(PPF.PERSON_ID)
					FROM APPS.PER_PEOPLE_F PPF
					INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON PAF.PERSON_ID=PPF.PERSON_ID
					INNER JOIN APPS.PER_POSITIONS PP ON PP.POSITION_ID=PAF.POSITION_ID
					INNER JOIN APPS.PER_JOBS PJ ON PAF.JOB_ID = PJ.JOB_ID
					INNER JOIN APPS.PER_GRADES PG ON PAF.GRADE_ID = PG.GRADE_ID
					WHERE PPF.EFFECTIVE_END_DATE > SYSDATE
					AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y'
					AND PAF.PRIMARY_FLAG = 'Y'
					AND PJ.name like '%HRD%'
					AND PPF.PERSON_ID = $pembuat";
		$resultPosisi = oci_parse($con,$queryPosisi);
		oci_execute($resultPosisi);
		$rowPosisi = oci_fetch_row($resultPosisi);
		$jumPosisi = $rowPosisi[0];  */
		
		if ($pembuat == 'Y'){
			$tingkat = 1;
		} else {
			$tingkat = 0;
		}
		
		$queryUpdate = "
		UPDATE MJ.MJ_T_BS 
		SET TINGKAT=$tingkat, 
			STATUS = '$Keputusan', 
			LAST_UPDATED_BY='$emp_id', 
			LAST_UPDATED_DATE=SYSDATE,
			TGL_JT = TO_DATE( '$arrTglJT', 'YYYY-MM-DD' )
		WHERE ID=$TransID";
		$result = oci_parse($con, $queryUpdate );
		oci_execute($result);
		
		
		
	}
}
	
$data="sukses";

$result = array('success' => true,
				'results' => $hdid,
				'rows' => $data,
				'bs500' => $idBs500,
			);
echo json_encode($result);

?>