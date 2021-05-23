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
  	$hdid = $_POST['hdid'];
	$keputusan = $_POST['keputusan'];
	$tipe_bs = $_POST['tipe_bs'];
	
	$syarat1 = $_POST['syarat1'];
	$syarat2 = $_POST['syarat2'];
	$syarat3 = $_POST['syarat3'];
	$syarat4 = $_POST['syarat4'];
	$syarat5 = $_POST['syarat5'];
	
	
	$ket = $_POST['ket'];
	$ket = str_replace("'", "`", $ket);
	
	$tglJt = $_POST[ 'tgl_jt' ];
	$tglJt = substr( $tglJt, 0, 10 );
	
	//echo $tgl;exit;
	
	$queryLogin = "
		SELECT	PERUSAHAAN_ID 
		FROM 	MJ.MJ_M_APPROVAL_PINJAMAN 
		WHERE 	TIPE = 'BS' 
		AND 	STATUS = 'A' 
		AND 	CHECKER_ID = $emp_id
	";
	//echo $queryLogin;exit;
	$resultLogin = oci_parse($con,$queryLogin);
	oci_execute($resultLogin);
	$rowLogin = oci_fetch_row($resultLogin);
	$hrdOrgId = $rowLogin[0]; 
	
	if ( $hrdOrgId == '' ) {
		$hrdOrgId = '-1';
	}
	
	$query = "
		SELECT count(*)
		FROM MJ.MJ_T_BS BS
		LEFT JOIN APPS.PER_ASSIGNMENTS_F PAF ON BS.ASSIGNMENT_ID =PAF.ASSIGNMENT_ID 
			AND PAF.EFFECTIVE_END_DATE > SYSDATE AND PAF.PRIMARY_FLAG='Y'
		LEFT JOIN APPS.PER_PEOPLE_F PPF ON PAF.PERSON_ID = PPF.PERSON_ID 
			AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y' AND PPF.EFFECTIVE_END_DATE > SYSDATE
		LEFT JOIN APPS.PER_PEOPLE_F PPF2 ON BS.PENANGGUNG_JAWAB = PPF2.PERSON_ID 
			AND PPF2.CURRENT_EMPLOYEE_FLAG = 'Y' AND PPF2.EFFECTIVE_END_DATE > SYSDATE
		LEFT JOIN APPS.HR_ORGANIZATION_UNITS HOU ON PAF.ORGANIZATION_ID = HOU.ORGANIZATION_ID
		WHERE 1 = 1 
		AND BS.TINGKAT = 2
		AND (	BS.PERUSAHAAN_BS = $hrdOrgId 
				or BS.PERUSAHAAN_BS in 
					(	SELECT 	PERUSAHAAN_ID 
						FROM 	MJ.MJ_M_APPROVAL_PINJAMAN 
						WHERE 	TIPE = 'BS' 
						AND 	STATUS = 'A' 
						AND 	CHECKER_ID is not null 
						AND 	CHECKER_ID = $emp_id
					) 
				or BS.PERUSAHAAN_BS not in 
					(	SELECT 	PERUSAHAAN_ID 
						FROM 	MJ.MJ_M_APPROVAL_PINJAMAN 
						WHERE 	TIPE = 'BS' 
						AND 	STATUS = 'A' 
						AND 	CHECKER_ID is not null
					)
			) 
		  and bs.id in ($hdid)
		ORDER BY BS.ID
		";
	
	// echo $query;exit;
		
	$resultCount = oci_parse($con,$query);
	oci_execute($resultCount);
	$rowCount = oci_fetch_row($resultCount);
	$countData = $rowCount[0];
	
	//echo $countData;exit;
	
	if ( $countData == 0 ) {
		
		$data="tidakadahakakses";
		
	} else {
	
		if ( $keputusan == "Approved" )
		{
			
			if ( $tipe_bs == 'SPPD' || $tipe_bs == 'EXTERNAL' )
			{
				
				$tingkat = 3;
				
			} elseif ( $tipe_bs == 'OPERASIONAL' || $tipe_bs == 'OPERASIONAL KHUSUS' )
			{
				
				$tingkat = 4;
				
			}
			

			$resultSeq = oci_parse($con,"SELECT MJ.MJ_T_APPROVAL_SEQ.nextval FROM DUAL");
			oci_execute($resultSeq);
			$rowHSeq = oci_fetch_row($resultSeq);
			$seq_id = $rowHSeq[0];
			
			$query = "
			INSERT INTO MJ.MJ_T_APPROVAL (ID, APP_ID, EMP_ID, TRANSAKSI_ID, TRANSAKSI_KODE, TINGKAT, STATUS, KETERANGAN, CREATED_BY, CREATED_DATE) 
			VALUES ($seq_id, " . APPCODE . ", '$emp_id', $hdid, 'BON', $tingkat, '$keputusan', '$ket', '$emp_name', SYSDATE)
			";
			//echo $query;
			$result = oci_parse($con,$query);
			oci_execute($result);
			
			$queryUpdate = "
			UPDATE MJ.MJ_T_BS 
				SET TINGKAT = $tingkat, 
				STATUS = 'PROCESS', 
				TGL_JT = to_date( '$tglJt', 'YYYY-MM-DD' ),
				SYARAT1 = '$syarat1',
				SYARAT2 = '$syarat2',
				SYARAT3 = '$syarat3',
				SYARAT4 = '$syarat4',
				SYARAT5 = '$syarat5',
				LAST_UPDATED_BY = '$emp_id', 
				LAST_UPDATED_DATE = SYSDATE 
			WHERE ID = $hdid
			";			
			
			// echo $queryUpdate; exit;
			
			$result = oci_parse($con, $queryUpdate );
			oci_execute($result);



			$query = "
				DELETE FROM MJ.MJ_M_UPLOAD
				WHERE APP_ID=" . APPCODE . " 
				AND USERNAME='$user_id'
				AND TRANSAKSI_KODE='BONMGRHRD'
				AND TRANSAKSI_ID = $hdid
			";
			$result = oci_parse($con, $query);
			oci_execute($result);


			
			//insert upload PINJAMAN
			$query = "SELECT FILENAME, FILESIZE, FILETYPE, TRANSAKSI_KODE
			FROM MJ.MJ_TEMP_UPLOAD
			WHERE APP_ID=" . APPCODE . " AND USERNAME='$user_id'
			AND TRANSAKSI_KODE='BONMGRHRD'";
			//echo $query;
			$result = oci_parse($con, $query);
			oci_execute($result);
			
			while($row = oci_fetch_row($result))
			{
				$vFilename=$row[0];
				$vFilesize=$row[1];
				$vFiletype=$row[2];
				$vFilekode=$row[3];
				$vFiledate="(SELECT CREATEDDATE
					FROM MJ.MJ_TEMP_UPLOAD
					WHERE APP_ID=" . APPCODE . " AND USERNAME='$user_id'
					AND TRANSAKSI_KODE='BONMGRHRD'
					AND FILENAME = '$vFilename'
					AND FILETYPE = '$vFiletype'
					)";
				
				$resultDetSeq = oci_parse($con,"SELECT MJ.MJ_M_UPLOAD_SEQ.nextval FROM DUAL");
				oci_execute($resultDetSeq);
				$rowDSeq = oci_fetch_row($resultDetSeq);
				$seqD = $rowDSeq[0];
				
				
				$queryAttachmentApprove = oci_parse( $con, "
				SELECT  COUNT( * )
				FROM    MJ.MJ_M_UPLOAD
				WHERE   TRANSAKSI_KODE = '$vFilekode'
				AND     APP_ID = " . APPCODE . "
				AND     TRANSAKSI_ID = $hdid
				AND     FILENAME = '$vFilename'
				" );
				oci_execute( $queryAttachmentApprove );
				$rowqueryAttachmentApprove = oci_fetch_row( $queryAttachmentApprove );
				$countAttachmentApprove = $rowqueryAttachmentApprove[0];
				
				if ( $countAttachmentApprove == 0 ) {
				
					$queryUpload = "INSERT INTO MJ.MJ_M_UPLOAD (ID, APP_ID, TRANSAKSI_ID, FILENAME, FILESIZE, FILETYPE, USERNAME, CREATEDDATE, TRANSAKSI_KODE) 
					VALUES ($seqD, " . APPCODE . ", $hdid, '$vFilename', '$vFilesize', '$vFiletype', '$user_id', $vFiledate, '$vFilekode')";
					//echo $query;
					$resultUpload = oci_parse($con,$queryUpload);
					oci_execute($resultUpload);				
				
				}
				
			}
			
			$query = "
				DELETE FROM MJ.MJ_TEMP_UPLOAD
				WHERE APP_ID=" . APPCODE . " AND USERNAME='$user_id'
				AND TRANSAKSI_KODE='BONMGRHRD'
			";
			$result = oci_parse($con, $query);
			oci_execute($result);
			
			//end insert upload PINJAMAN
			
			$resultNo = oci_parse($con,"
			SELECT DISTINCT BS.NO_BS, BS.TIPE, PJ.NAME DEPT_PEM, PP.NAME POS_PEM, PG.NAME
					, DECODE( TRIM( INITCAP(PPF2.TITLE) || PPF2.FIRST_NAME || ' ' || PPF2.LAST_NAME ), NULL, BS.PENANGGUNGJAWAB_EXTERNAL, TRIM( INITCAP(PPF2.TITLE) || PPF2.FIRST_NAME || ' ' || PPF2.LAST_NAME ) ) PJ
					, 'Rp '||TRIM( TO_CHAR( BS.NOMINAL, '999,999,999.99' ) ) NOMINAL, BS.KETERANGAN
					, PPF.EMAIL_ADDRESS, PPF2.EMAIL_ADDRESS
					, NVL( PPF.FULL_NAME, BS.NAMA_EXTERNAL )
					, HOU.NAME, HOU2.NAME, TO_CHAR(BS.TGL_JT, 'DD-MON-YYYY')
					, BS.TINGKAT, PPF3.EMAIL_ADDRESS
					FROM MJ.MJ_T_BS BS
					LEFT JOIN APPS.PER_ASSIGNMENTS_F PAF ON BS.ASSIGNMENT_ID =PAF.ASSIGNMENT_ID AND PAF.EFFECTIVE_END_DATE > SYSDATE AND PAF.PRIMARY_FLAG='Y'
					LEFT JOIN APPS.PER_PEOPLE_F PPF ON PAF.PERSON_ID = PPF.PERSON_ID AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y' AND PPF.EFFECTIVE_END_DATE > SYSDATE
					LEFT JOIN APPS.PER_PEOPLE_F PPF2 ON BS.PENANGGUNG_JAWAB = PPF2.PERSON_ID AND PPF2.CURRENT_EMPLOYEE_FLAG = 'Y' AND PPF2.EFFECTIVE_END_DATE > SYSDATE
					LEFT JOIN APPS.PER_PEOPLE_F PPF3 ON BS.CREATED_BY = PPF3.PERSON_ID AND PPF3.CURRENT_EMPLOYEE_FLAG = 'Y' AND PPF3.EFFECTIVE_END_DATE > SYSDATE
					LEFT JOIN APPS.PER_ASSIGNMENTS_F PAF2 ON PPF2.PERSON_ID =PAF2.PERSON_ID AND PAF2.EFFECTIVE_END_DATE > SYSDATE AND PAF2.PRIMARY_FLAG='Y'
					LEFT JOIN APPS.HR_ORGANIZATION_UNITS HOU ON PAF.ORGANIZATION_ID = HOU.ORGANIZATION_ID
					LEFT JOIN APPS.HR_ORGANIZATION_UNITS HOU2 ON BS.PERUSAHAAN_BS = HOU2.ORGANIZATION_ID
					LEFT JOIN APPS.PER_JOBS PJ ON PJ.JOB_ID=PAF.JOB_ID
					LEFT JOIN APPS.PER_POSITIONS PP ON PAF.POSITION_ID=PP.POSITION_ID
					LEFT JOIN APPS.HR_LOCATIONS HL ON HL.LOCATION_ID=PAF.LOCATION_ID
					LEFT JOIN APPS.PER_JOBS PJ2 ON PJ2.JOB_ID=PAF2.JOB_ID
					LEFT JOIN APPS.PER_POSITIONS PP2 ON PAF2.POSITION_ID=PP2.POSITION_ID
					LEFT JOIN APPS.PER_GRADES PG ON PAF.GRADE_ID = PG.GRADE_ID
					WHERE BS.ID = $hdid
					");
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
			$tingkat_bs_existing = $rowNo[14];
			$emailPembuat = $rowNo[15];
			
			
			// , INITCAP(PPF2.TITLE)||PPF2.FIRST_NAME||' '||PPF2.LAST_NAME PJ
			// 
			
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
			
			if($tingkat_bs_existing == 4){
				$resultCount = oci_parse($con,"SELECT COUNT(ACCOUNTING_ID) X FROM MJ.MJ_M_APPROVAL_PINJAMAN WHERE TIPE = 'BS' AND STATUS = 'A'
					AND PERUSAHAAN_ID = (
						SELECT BS.PERUSAHAAN_BS FROM MJ.MJ_T_BS BS
						INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON BS.ASSIGNMENT_ID =PAF.ASSIGNMENT_ID AND PAF.EFFECTIVE_END_DATE > SYSDATE AND PAF.PRIMARY_FLAG='Y'
						INNER JOIN APPS.HR_ORGANIZATION_UNITS HOU ON PAF.ORGANIZATION_ID = HOU.ORGANIZATION_ID
						WHERE BS.ID = $hdid
					)");
				oci_execute($resultCount);
				$rowCount = oci_fetch_row($resultCount);
				$count = $rowCount[0];
				
				if($count == 0){
					$emailMgrFin = "";
					
					$bodyEmail = "Dear All Manager Finance, 
	
Telah disetujui oleh Checker pengajuan BS $tipeBS nomor $noBS dengan informasi berikut :

Nama Karyawan :   $namaPem
Perusahaan :   $companyPem
Department :   $deptPem
Posisi / Grade :   $posPem / $gradePem
Perusahaan BS :   $perusahaanBS
Penanggung Jawab :   $pj
Nominal :   $nominal
Tgl Jatuh Tempo :   $tglJt
Keterangan :   $keterangan
Note Approval :   $ket

Mohon untuk melakukan approval pengajuan BS $tipeBS diatas.
Terima Kasih.
";
					$mail->Body = $bodyEmail;
					
					
					/* DIPAKAI LIVE */
					
					$mail->addCC($emailPem);	
					$mail->addCC($emailPembuat);
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
						AND PJ.name like '%FIN%' AND PG.name like '%Manager%' ");
					oci_execute($resultEmail);
					while($rowEmail = oci_fetch_row($resultEmail))
					{
						//$emailMgrFin .= $rowEmail[0].', ';
						$mail->AddAddress($rowEmail[0]);
					}
					
					/* DIPAKAI LIVE */
					
					
					
					/* DIPAKAI TESTING */
					
					// $mail->AddAddress('maria.natalia@merakjaya.co.id');
					// $mail->addCC('dwatra@merakjaya.co.id');
					// $mail->addCC('yuke.indarto@merakjaya.co.id');
					
					/* DIPAKAI TESTING */
					
					
					
				} else {
					
					$resultEmail = oci_parse($con,"SELECT EMAIL_ADDRESS, TRIM( INITCAP(TITLE) || FIRST_NAME || ' ' || LAST_NAME )
						FROM PER_PEOPLE_F WHERE PERSON_ID = (SELECT ACCOUNTING_ID FROM MJ.MJ_M_APPROVAL_PINJAMAN WHERE TIPE = 'BS' AND STATUS = 'A'
						AND PERUSAHAAN_ID = (
						SELECT BS.PERUSAHAAN_BS FROM MJ.MJ_T_BS BS
									INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON BS.ASSIGNMENT_ID =PAF.ASSIGNMENT_ID AND PAF.EFFECTIVE_END_DATE > SYSDATE AND PAF.PRIMARY_FLAG='Y'
									INNER JOIN APPS.HR_ORGANIZATION_UNITS HOU ON PAF.ORGANIZATION_ID = HOU.ORGANIZATION_ID
									WHERE BS.ID = $hdid
						)) AND EFFECTIVE_END_DATE > SYSDATE AND CURRENT_EMPLOYEE_FLAG = 'Y' ");
					oci_execute($resultEmail);
					$rowEmail = oci_fetch_row($resultEmail);
					$emailMgrFin = $rowEmail[0];
					$namaMgrFin = $rowEmail[1];
			
					$bodyEmail = "Dear $namaMgrFin, 
	
Telah disetujui oleh Checker pengajuan BS $tipeBS nomor $noBS dengan informasi berikut :

Nama Karyawan :   $namaPem
Perusahaan :   $companyPem
Department :   $deptPem
Posisi / Grade :   $posPem / $gradePem
Perusahaan BS :   $perusahaanBS
Penanggung Jawab :   $pj
Nominal :   $nominal
Tgl Jatuh Tempo :   $tglJt
Keterangan :   $keterangan
Note Approval :   $ket

Mohon untuk melakukan approval pengajuan BS $tipeBS diatas.
Terima Kasih.
";
			
					$mail->Body = $bodyEmail;
					
					
					/* DIPAKAI LIVE */
					
					$mail->addCC($emailPem);	
					$mail->addCC($emailPembuat);
					$mail->addCC($emailPj);	
					$mail->addCC('greta.silviana@merakjaya.co.id');
					$mail->addCC('fitri.ambarwati@merakjaya.co.id');
					$mail->addCC('maria.natalia@merakjaya.co.id');
					$mail->addCC('dwatra@merakjaya.co.id');
					$mail->AddAddress($emailMgrFin);
					
					/* DIPAKAI LIVE */
					
					
					
					/* DIPAKAI TESTING */
					
					// $mail->AddAddress('maria.natalia@merakjaya.co.id');
					// $mail->addCC('dwatra@merakjaya.co.id');
					// $mail->addCC('yuke.indarto@merakjaya.co.id');
					
					/* DIPAKAI TESTING */
					
					
				}
			
			}else{
				$resultCount = oci_parse($con,"SELECT COUNT(HRD_ID) X FROM MJ.MJ_M_APPROVAL_PINJAMAN WHERE TIPE = 'BS' AND STATUS = 'A'
					AND PERUSAHAAN_ID = (
					SELECT BS.PERUSAHAAN_BS FROM MJ.MJ_T_BS BS
								INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON BS.ASSIGNMENT_ID =PAF.ASSIGNMENT_ID AND PAF.EFFECTIVE_END_DATE > SYSDATE AND PAF.PRIMARY_FLAG='Y'
								INNER JOIN APPS.HR_ORGANIZATION_UNITS HOU ON PAF.ORGANIZATION_ID = HOU.ORGANIZATION_ID
								WHERE BS.ID = $hdid
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
Note Approval :   $ket

Mohon untuk melakukan approval pengajuan BS $tipeBS diatas.
Terima Kasih.
";
				
					$mail->Body = $bodyEmail;
					
					
					
					/* DIPAKAI LIVE */
					
					$mail->addCC($emailPem);	
					$mail->addCC($emailPembuat);
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
					
					
					
					//DIPAKAI TESTING
					
					// $mail->AddAddress('maria.natalia@merakjaya.co.id');
					// $mail->addCC('dwatra@merakjaya.co.id');
					// $mail->addCC('yuke.indarto@merakjaya.co.id');
					
					//DIPAKAI TESTING 
					
					
					
				} else {
					
					$resultEmail = oci_parse($con,"SELECT EMAIL_ADDRESS, TRIM( INITCAP(TITLE) || FIRST_NAME || ' ' || LAST_NAME )
						FROM PER_PEOPLE_F WHERE PERSON_ID = (SELECT HRD_ID FROM MJ.MJ_M_APPROVAL_PINJAMAN WHERE TIPE = 'BS' AND STATUS = 'A'
						AND PERUSAHAAN_ID = (
						SELECT BS.PERUSAHAAN_BS FROM MJ.MJ_T_BS BS
									INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON BS.ASSIGNMENT_ID =PAF.ASSIGNMENT_ID AND PAF.EFFECTIVE_END_DATE > SYSDATE AND PAF.PRIMARY_FLAG='Y'
									INNER JOIN APPS.HR_ORGANIZATION_UNITS HOU ON PAF.ORGANIZATION_ID = HOU.ORGANIZATION_ID
									WHERE BS.ID = $hdid
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
Note Approval :   $ket

Mohon untuk melakukan approval pengajuan BS $tipeBS diatas.
Terima Kasih.
";
					//ECHO $bodyEmail;
					$mail->Body = $bodyEmail;
					
					
					
					/* DIPAKAI LIVE */
					
					$mail->addCC($emailPem);
					$mail->addCC($emailPembuat);	
					$mail->addCC($emailPj);	
					$mail->addCC('greta.silviana@merakjaya.co.id');
					$mail->addCC('fitri.ambarwati@merakjaya.co.id');
					$mail->addCC('maria.natalia@merakjaya.co.id');
					$mail->addCC('dwatra@merakjaya.co.id');
					$mail->AddAddress($emailHrd);
					
					/* DIPAKAI LIVE */
					
					
					
					//DIPAKAI TESTING
					
					// $mail->AddAddress('maria.natalia@merakjaya.co.id');
					// $mail->addCC('dwatra@merakjaya.co.id');
					// $mail->addCC('yuke.indarto@merakjaya.co.id');
					
					//DIPAKAI TESTING 
					
					
				}
			}

			$success = $mail->Send();
			
			
			/* DIPAKAI */
			
			
			
			
			$data="sukses";
			
		} else 
		{ //disapproved
		
			$queryTingkat = "SELECT TINGKAT FROM MJ.MJ_T_BS WHERE ID=$hdid";
			// echo $queryTingkat;
			$resultTingkat = oci_parse($con,$queryTingkat);
			oci_execute($resultTingkat);
			$rowTingkat = oci_fetch_row($resultTingkat);
			$tingkat = $rowTingkat[0];
			
			if($tingkat==1){
				$queryUpdate = "
				UPDATE MJ.MJ_T_BS 
				SET STATUS = '$keputusan',
				LAST_UPDATED_BY='$emp_id', 
				LAST_UPDATED_DATE=SYSDATE,
				TGL_JT = to_date( '$tglJt', 'YYYY-MM-DD' )
				WHERE ID=$hdid
				";
			} else {
				$queryUpdate = "
				UPDATE MJ.MJ_T_BS 
				SET TINGKAT=0, 
				STATUS = '$keputusan', 
				LAST_UPDATED_BY='$emp_id', 
				LAST_UPDATED_DATE=SYSDATE,
				TGL_JT = to_date( '$tglJt', 'YYYY-MM-DD' )
				WHERE ID=$hdid";
			}
			$result = oci_parse($con, $queryUpdate );
			oci_execute($result);

			$resultSeq = oci_parse($con,"SELECT MJ.MJ_T_APPROVAL_SEQ.nextval FROM DUAL");
			oci_execute($resultSeq);
			$rowHSeq = oci_fetch_row($resultSeq);
			$seq_id = $rowHSeq[0];
			
			$query = "INSERT INTO MJ.MJ_T_APPROVAL (ID, APP_ID, EMP_ID, TRANSAKSI_ID, TRANSAKSI_KODE, TINGKAT, STATUS, KETERANGAN, CREATED_BY, CREATED_DATE) VALUES ($seq_id, " . APPCODE . ", '$emp_id', $hdid, 'BON', 4, '$keputusan', '$ket', '$emp_name', SYSDATE)";
			//echo $query;
			$result = oci_parse($con,$query);
			oci_execute($result);
			
			//insert upload PINJAMAN
			$query = "SELECT FILENAME, FILESIZE, FILETYPE, TRANSAKSI_KODE
			FROM MJ.MJ_TEMP_UPLOAD
			WHERE APP_ID=" . APPCODE . " AND USERNAME='$user_id'
			AND TRANSAKSI_KODE='BONMGRHRD'";
			//echo $query;
			$result = oci_parse($con, $query);
			oci_execute($result);
			
			while($row = oci_fetch_row($result))
			{
				$vFilename=$row[0];
				$vFilesize=$row[1];
				$vFiletype=$row[2];
				$vFilekode=$row[3];
				$vFiledate="(SELECT CREATEDDATE
					FROM MJ.MJ_TEMP_UPLOAD
					WHERE APP_ID=" . APPCODE . " AND USERNAME='$user_id'
					AND TRANSAKSI_KODE='BONMGRHRD'
					AND FILENAME = '$vFilename'
					AND FILETYPE = '$vFiletype'
					)";
				
				$resultDetSeq = oci_parse($con,"SELECT MJ.MJ_M_UPLOAD_SEQ.nextval FROM DUAL");
				oci_execute($resultDetSeq);
				$rowDSeq = oci_fetch_row($resultDetSeq);
				$seqD = $rowDSeq[0];
				
				
				$queryAttachmentDisapprove = oci_parse( $con, "
				SELECT  COUNT( * )
				FROM    MJ.MJ_M_UPLOAD
				WHERE   TRANSAKSI_KODE = '$vFilekode'
				AND     APP_ID = " . APPCODE . "
				AND     TRANSAKSI_ID = $hdid
				AND     FILENAME = '$vFilename'
				" );
				oci_execute( $queryAttachmentDisapprove );
				$rowqueryAttachmentDisapprove = oci_fetch_row( $queryAttachmentDisapprove );
				$countAttachmentDisapprove = $rowqueryAttachmentDisapprove[0];
				
				if ( $countAttachmentDisapprove == 0 ) {
					
					$queryUpload = "INSERT INTO MJ.MJ_M_UPLOAD (ID, APP_ID, TRANSAKSI_ID, FILENAME, FILESIZE, FILETYPE, USERNAME, CREATEDDATE, TRANSAKSI_KODE) 
					VALUES ($seqD, " . APPCODE . ", $hdid, '$vFilename', '$vFilesize', '$vFiletype', '$user_id', $vFiledate, '$vFilekode')";
					//echo $query;
					$resultUpload = oci_parse($con,$queryUpload);
					oci_execute($resultUpload);					
					
				}
				
				
			}
			
			$query = "DELETE FROM MJ.MJ_TEMP_UPLOAD
			WHERE APP_ID=" . APPCODE . " AND USERNAME='$user_id'
			AND TRANSAKSI_KODE='BONMGRHRD'";
			$result = oci_parse($con, $query);
			oci_execute($result);
			//end insert upload PINJAMAN
			
			
			
			$resultNo = oci_parse($con,"
			SELECT DISTINCT BS.NO_BS, BS.TIPE, PJ.NAME DEPT_PEM, PP.NAME POS_PEM, PG.NAME
					, TRIM( INITCAP(PPF2.TITLE) || PPF2.FIRST_NAME || ' ' || PPF2.LAST_NAME )
					, 'Rp '||TRIM( TO_CHAR( BS.NOMINAL, '999,999,999.99' ) ) NOMINAL, BS.KETERANGAN
					, PPF.EMAIL_ADDRESS, PPF2.EMAIL_ADDRESS
					, NVL( PPF.FULL_NAME, BS.NAMA_EXTERNAL )
					, HOU.NAME, HOU2.NAME, TO_CHAR(BS.TGL_JT, 'DD-MON-YYYY'), PPF3.EMAIL_ADDRESS
					FROM MJ.MJ_T_BS BS
					LEFT JOIN APPS.PER_ASSIGNMENTS_F PAF ON BS.ASSIGNMENT_ID =PAF.ASSIGNMENT_ID AND PAF.EFFECTIVE_END_DATE > SYSDATE AND PAF.PRIMARY_FLAG='Y'
					LEFT JOIN PER_PEOPLE_F PPF ON PAF.PERSON_ID = PPF.PERSON_ID AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y' AND PPF.EFFECTIVE_END_DATE > SYSDATE
					LEFT JOIN PER_PEOPLE_F PPF2 ON BS.PENANGGUNG_JAWAB = PPF2.PERSON_ID AND PPF2.CURRENT_EMPLOYEE_FLAG = 'Y' AND PPF2.EFFECTIVE_END_DATE > SYSDATE
					LEFT JOIN APPS.PER_PEOPLE_F PPF3 ON BS.CREATED_BY = PPF3.PERSON_ID AND PPF3.CURRENT_EMPLOYEE_FLAG = 'Y' AND PPF3.EFFECTIVE_END_DATE > SYSDATE
					LEFT JOIN APPS.PER_ASSIGNMENTS_F PAF2 ON PPF2.PERSON_ID =PAF2.PERSON_ID AND PAF2.EFFECTIVE_END_DATE > SYSDATE AND PAF2.PRIMARY_FLAG='Y'
					LEFT JOIN APPS.HR_ORGANIZATION_UNITS HOU ON PAF.ORGANIZATION_ID = HOU.ORGANIZATION_ID
					LEFT JOIN APPS.HR_ORGANIZATION_UNITS HOU2 ON BS.PERUSAHAAN_BS = HOU2.ORGANIZATION_ID
					LEFT JOIN APPS.PER_JOBS PJ ON PJ.JOB_ID=PAF.JOB_ID
					LEFT JOIN APPS.PER_POSITIONS PP ON PAF.POSITION_ID=PP.POSITION_ID
					LEFT JOIN APPS.HR_LOCATIONS HL ON HL.LOCATION_ID=PAF.LOCATION_ID
					LEFT JOIN APPS.PER_JOBS PJ2 ON PJ2.JOB_ID=PAF2.JOB_ID
					LEFT JOIN APPS.PER_POSITIONS PP2 ON PAF2.POSITION_ID=PP2.POSITION_ID
					LEFT JOIN APPS.PER_GRADES PG ON PAF.GRADE_ID = PG.GRADE_ID
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
			$perusahaanBS = $rowNo[12];
			$tglJt = $rowNo[13];			
			$emailPembuat = $rowNo[14];			
			
			
			
			
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
				
			$bodyEmail = "Dear $namaPem, 
	
Telah direject pengajuan BS $tipeBS nomor $noBS oleh $emp_name pada tingkat Checker dengan informasi berikut :

Nama Karyawan :   $namaPem
Perusahaan :   $companyPem
Department :   $deptPem
Posisi / Grade :   $posPem / $gradePem
Perusahaan BS :   $perusahaanBS
Penanggung Jawab :   $pj
Nominal :   $nominal
Tgl Jatuh Tempo :   $tglJt
Keterangan :   $keterangan
Ket. Reject :   $ket

Mohon dilakukan pengecekan atas BS $tipeBS diatas.
Terima Kasih.
";
			$mail->Body = $bodyEmail;
			
			
			/* DIPAKAI LIVE */
			
			$mail->addCC($emailPj);	
			$mail->addCC($emailPembuat);	
			$mail->addCC('greta.silviana@merakjaya.co.id');
			$mail->addCC('fitri.ambarwati@merakjaya.co.id');
			$mail->addCC('maria.natalia@merakjaya.co.id');
			$mail->addCC('dwatra@merakjaya.co.id');
			$mail->AddAddress($emailPem);
			
			/* DIPAKAI LIVE */
			
			
			
			/* DIPAKAI TESTING */
			
			// $mail->AddAddress('maria.natalia@merakjaya.co.id');
			// $mail->addCC('dwatra@merakjaya.co.id');
			// $mail->addCC('yuke.indarto@merakjaya.co.id');
			
			/* DIPAKAI TESTING */
			
			
			
			$success = $mail->Send();
		
		
			/* DIPAKAI */
			
			
			
			$data="sukses";
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


} else {
	
	$data="gagal";

	$result = array('success' => true,
					'results' => '',
					'rows' => $data
				);
	echo json_encode($result);	
}




?>