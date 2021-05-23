<?php
require('smtpemailattachment.php');
include '../../main/koneksi.php';
date_default_timezone_set("Asia/Jakarta");
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
  
$status = "";
$data = "";
$namaTujuan = "";
$emailTujuan = "";

if (isset($_POST['hdid']))
{	
	$hdid = $_POST['hdid'];
	$vparam_tipe = $_POST['param_tipe'];
	$vparam_karyawan = $_POST['param_karyawan'];
	
	// $vparam_setuju = $_POST['param_setuju'];
	
	/*
	$query = "SELECT PPF2.FULL_NAME, EMAIL.EMAIL_ADDRESS, PPF1.EMAIL_ADDRESS PEMOHON, PPF2.EMAIL_ADDRESS PEMBUAT, MTM.NO_REQUEST
	FROM MJ.MJ_T_MUTASI MTM
	LEFT JOIN 
	(
		SELECT DISTINCT PAF.JOB_ID, PPF.EMAIL_ADDRESS, PPF.FULL_NAME
		FROM APPS.PER_PEOPLE_F PPF
		INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON PAF.PERSON_ID=PPF.PERSON_ID
		INNER JOIN APPS.PER_POSITIONS PP ON PP.POSITION_ID=PAF.POSITION_ID
		WHERE PPF.EFFECTIVE_END_DATE > SYSDATE AND PAF.EFFECTIVE_END_DATE > SYSDATE 
		AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y' AND PP.NAME LIKE '%MGR%'
	) EMAIL ON EMAIL.JOB_ID = MTM.DEPT_BARU_ID
	LEFT JOIN APPS.PER_PEOPLE_F PPF1 ON PPF1.PERSON_ID = MTM.KARYAWAN_ID AND PPF1.EFFECTIVE_END_DATE > SYSDATE AND PPF1.CURRENT_EMPLOYEE_FLAG = 'Y'
	LEFT JOIN APPS.PER_PEOPLE_F PPF2 ON PPF2.PERSON_ID = MTM.CREATED_BY AND PPF2.EFFECTIVE_END_DATE > SYSDATE AND PPF2.CURRENT_EMPLOYEE_FLAG = 'Y'
	WHERE 1=1 AND MTM.ID = $hdid";
	
    // echo $query;
	
	$result = oci_parse($con,$query);
	oci_execute($result);
	$row = oci_fetch_row($result);
	
	$deptManager = $row[0];		
	$deptManagerEmail = $row[1];		
	$deptPemohon = $row[2];		
	$deptPembuat = $row[3];
	$noRequest = $row[4];
	*/
	
	
	$query = "SELECT  
				A.ID
				, A.PEMOHON
				, A.NAMA_PEMBUAT
				, A.EMAIL_PEMBUAT
				, A.NO_REQUEST
				, B.EMAIL_ADDRESS_MGR_LAMA
				, B.FULL_NAME_MGR_LAMA
				, C.EMAIL_ADDRESS_MGR_BARU
				, C.FULL_NAME_MGR_BARU
				FROM
				(
				SELECT 
				MTM.ID
				, PPF1.EMAIL_ADDRESS PEMOHON
				, PPF2.FULL_NAME NAMA_PEMBUAT
				, PPF2.EMAIL_ADDRESS EMAIL_PEMBUAT
				, MTM.NO_REQUEST
				, MTM.DEPT_LAMA_ID
				, MTM.MGR_LAMA
				, MTM.MGR_BARU
				FROM    MJ.MJ_T_MUTASI MTM
				LEFT JOIN APPS.PER_PEOPLE_F PPF1 ON PPF1.PERSON_ID = MTM.KARYAWAN_ID AND PPF1.EFFECTIVE_END_DATE > SYSDATE AND PPF1.CURRENT_EMPLOYEE_FLAG = 'Y'
				LEFT JOIN APPS.PER_PEOPLE_F PPF2 ON PPF2.PERSON_ID = MTM.CREATED_BY AND PPF2.EFFECTIVE_END_DATE > SYSDATE AND PPF2.CURRENT_EMPLOYEE_FLAG = 'Y'
				) A,
				(
				SELECT  DISTINCT PPF.PERSON_ID PERSON_ID_MGR_LAMA
				, PPF.EMAIL_ADDRESS EMAIL_ADDRESS_MGR_LAMA
				, PPF.FULL_NAME FULL_NAME_MGR_LAMA
				FROM    APPS.PER_PEOPLE_F PPF
				INNER   JOIN APPS.PER_ASSIGNMENTS_F PAF ON PAF.PERSON_ID = PPF.PERSON_ID
				INNER   JOIN APPS.PER_POSITIONS PP ON PP.POSITION_ID = PAF.POSITION_ID
				WHERE   PPF.EFFECTIVE_END_DATE > SYSDATE AND PAF.EFFECTIVE_END_DATE > SYSDATE 
				AND     PPF.CURRENT_EMPLOYEE_FLAG = 'Y' AND PP.NAME LIKE '%MGR%'
				) B,
				(
				SELECT  DISTINCT PPF.PERSON_ID PERSON_ID_MGR_BARU
				, PPF.EMAIL_ADDRESS EMAIL_ADDRESS_MGR_BARU
				, PPF.FULL_NAME FULL_NAME_MGR_BARU
				FROM    APPS.PER_PEOPLE_F PPF
				INNER   JOIN APPS.PER_ASSIGNMENTS_F PAF ON PAF.PERSON_ID = PPF.PERSON_ID
				INNER   JOIN APPS.PER_POSITIONS PP ON PP.POSITION_ID = PAF.POSITION_ID
				WHERE   PPF.EFFECTIVE_END_DATE > SYSDATE AND PAF.EFFECTIVE_END_DATE > SYSDATE 
				AND     PPF.CURRENT_EMPLOYEE_FLAG = 'Y' AND PP.NAME LIKE '%MGR%'
				) C
				WHERE   A.MGR_LAMA = B.PERSON_ID_MGR_LAMA 
				AND     A.MGR_BARU = C.PERSON_ID_MGR_BARU 
				AND     A.ID = $hdid";
	
	
    // echo $query;
	
	$result = oci_parse($con,$query);
	oci_execute($result);
	$row = oci_fetch_row($result);

    $deptPemohon = $row[1];
	$namaPembuat = $row[2];
    $deptPembuat = $row[3];
    $noRequest = $row[4];
    $deptManagerEmail = $row[5];
    $deptManager = $row[6];
    $deptMgrBaruEmail = $row[7];
    $deptMgrBaru = $row[8];
	
	



	sleep(5);


	// $bodyEmail = "Dear $deptManager,


	$emailJam = date("H");

	$subjectEmail = "[Autoemail] Pengajuan $vparam_tipe $noRequest";
	$bodyEmail = "
Dear Sulianti, Mrs. SLI,

Telah dibuat pengajuan $vparam_tipe nomor $noRequest atas nama $vparam_karyawan.
Terima Kasih.


[Form: Approval Mutasi Karyawan HRD]


To: sulianti@merakjaya.co.id
CC: $deptPembuat, $deptPemohon, $deptManagerEmail, $deptMgrBaruEmail
";



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
	$mail->Body = $bodyEmail;
	
	
	
	$query_cp_plant = oci_parse( $con,"
		SELECT  COUNT( * )
		FROM    APPS.PER_PEOPLE_F PPF
		INNER JOIN  MJ_T_MUTASI MTM ON KARYAWAN_ID = PPF.PERSON_ID
		INNER JOIN  APPS.PER_ASSIGNMENTS_F PAF ON PAF.PERSON_ID = PPF.PERSON_ID
		LEFT JOIN   APPS.PER_JOBS PJ ON PJ.JOB_ID = PAF.JOB_ID AND PJ.JOB_ID = MTM.DEPT_LAMA_ID
		LEFT JOIN   APPS.PER_POSITIONS PP ON PP.POSITION_ID = PAF.POSITION_ID AND PP.POSITION_ID = MTM.POSISI_LAMA_ID
		LEFT JOIN   APPS.HR_LOCATIONS HL ON HL.LOCATION_ID = PAF.LOCATION_ID
		LEFT JOIN   APPS.PER_VALID_GRADES_V PVG  ON PVG.POSITION_ID = PP.POSITION_ID AND PVG.GRADE_ID = MTM.GRADE_LAMA_ID
		INNER JOIN  APPS.PER_PEOPLE_F PPF1 ON PPF1.PERSON_ID = MTM.MGR_LAMA AND PPF1.EFFECTIVE_END_DATE > SYSDATE
		LEFT JOIN   APPS.PER_JOBS PJ1 ON PJ1.JOB_ID = MTM.DEPT_BARU_ID
		INNER JOIN  APPS.PER_PEOPLE_F PPF2 ON PPF2.PERSON_ID = MTM.MGR_BARU AND PPF2.EFFECTIVE_END_DATE > SYSDATE
		LEFT JOIN   APPS.PER_POSITIONS PP2 ON PP2.POSITION_ID = MTM.POSISI_BARU_ID
		LEFT JOIN   APPS.HR_LOCATIONS HL2 ON HL2.LOCATION_ID = MTM.LOKASI_BARU_ID
		INNER JOIN  APPS.PAY_PEOPLE_GROUPS PPG ON PPG.PEOPLE_GROUP_ID = PAF.PEOPLE_GROUP_ID 
						AND (   UPPER( GROUP_NAME ) LIKE 'CP%'
								OR UPPER( GROUP_NAME ) LIKE 'PLANT%' )
		WHERE   PPF.EFFECTIVE_END_DATE > SYSDATE
		AND     PAF.PRIMARY_FLAG = 'Y'
		AND MTM.ID = $hdid
	");
	
	oci_execute( $query_cp_plant );
	$row_cp_plant = oci_fetch_row( $query_cp_plant );
	$result_cp_plant = $row_cp_plant[0];
	
	
	
	// Gaji HO
	
	if ( $result_cp_plant == "0" ) {
		
		/* DIPAKAI */
		
		$mail->AddAddress('sulianti@merakjaya.co.id');
		
		$mail->addCC($deptPemohon);
		$mail->addCC($deptManagerEmail);
		
		$mail->addCC('freddy.anggo@merakjaya.co.id');
		$mail->addCC('teguh@merakjaya.co.id');		
		$mail->addCC('yuke.indarto@merakjaya.co.id'); 
		$mail->addCC('maria.natalia@merakjaya.co.id'); 
		$mail->addCC('dwatra@merakjaya.co.id');
	
		/* DIPAKAI */
		
		$mail->addCC('yuke.indarto@merakjaya.co.id'); 
		$mail->addCC('maria.natalia@merakjaya.co.id'); 
		$mail->addCC('dwatra@merakjaya.co.id');
		
	// Gaji Plant
	
	} else {
		
		/* DIPAKAI */
		
		$mail->AddAddress('hrd.payroll@merakjaya.co.id');
		
		$mail->addCC($deptPemohon);
		$mail->addCC($deptManagerEmail);

		$mail->addCC('freddy.anggo@merakjaya.co.id');
		$mail->addCC('teguh@merakjaya.co.id');
		
		$mail->addCC('yuke.indarto@merakjaya.co.id'); 
		$mail->addCC('maria.natalia@merakjaya.co.id'); 
		$mail->addCC('dwatra@merakjaya.co.id');
		
		/* DIPAKAI */
		
		// $mail->addCC('yuke.indarto@merakjaya.co.id'); 
		// $mail->addCC('maria.natalia@merakjaya.co.id'); 
		// $mail->addCC('dwatra@merakjaya.co.id');
		
	}
	
	
	
	// $mail->addCC('yuke.indarto@merakjaya.co.id'); 
	// $mail->addCC('maria.natalia@merakjaya.co.id'); 
	// $mail->addCC('dwatra@merakjaya.co.id');
	
	// $mail->addCC($deptPembuat);
	// $mail->addCC($deptMgrBaruEmail);
	
	// $mail->addCC($deptPembuat);
	// $mail->addCC($deptMgrBaruEmail);
	
	// $mail->AddAddress($deptManagerEmail);
	
	// freddy.anggo@merakjaya.co.id
	// sonia.adina@merakjaya.co.id
	// hrd.complain@merakjaya.co.id
	// sulianti@merakjaya.co.id

	// $mail->AddAddress('sulianti@merakjaya.co.id');
	
	// freddy.anggo@merakjaya.co.id
	// sonia.adina@merakjaya.co.id
	// hrd.complain@merakjaya.co.id
	// sulianti@merakjaya.co.id

	// $mail->addCC($deptPembuat);
	// $mail->addCC($deptPemohon);
	// $mail->addCC($deptManagerEmail);
	// $mail->addCC($deptMgrBaruEmail);
	
	// $mail->addCC('freddy.anggo@merakjaya.co.id');
	// $mail->addCC('sonia.adina@merakjaya.co.id');
	
	
	// $mail->addAttachment( 'Mutasi_F-03_Payroll.pdf' );

	$query_noreq = "
		SELECT  '_' || REPLACE( NO_REQUEST, '/', '_' ) NO_REQUEST_FILENAME
		FROM    MJ_T_MUTASI
		WHERE   ID = $hdid
	";
	
	$result_noreq = oci_parse( $con, $query_noreq );
	oci_execute( $result_noreq );
	$row_noreq = oci_fetch_row( $result_noreq );
	
    $vNoReq = $row_noreq[0];
	
	$mail->addAttachment( 'Mutasi_F-03_Payroll' . $vNoReq . '.pdf' );
	
	$success = $mail->Send();

} 

?>