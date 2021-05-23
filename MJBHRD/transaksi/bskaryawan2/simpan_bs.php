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

$tingkat=0;
$status_bs='PROCESS';

 if(isset($_POST['nama_user']))
  {
  	$hdid=$_POST['hdid'];
	$typeform=$_POST['typeform'];
	//$nama_user=$_POST['nama_user'];
	$person_id=$_POST['person_id'];
	$perusahaan_bs=$_POST['perusahaan_bs'];
  	$penanggungJawab=$_POST['pj'];
  	$tipe=$_POST['tipe'];
  	$nominal=$_POST['nominal'];
  	$tgl_jt=$_POST['tgl_jt'];
	$tgl_jt=substr($tgl_jt,0,10);
  	$ket=$_POST['ket'];
	$ket=str_replace("'", "`", $ket);
  	$syarat1=$_POST['syarat1'];
  	$syarat2=$_POST['syarat2'];
  	$syarat3=$_POST['syarat3'];
  	$syarat4=$_POST['syarat4'];
  	$syarat5=$_POST['syarat5'];
	$syarat1=str_replace("'", "`", $syarat1);
	$syarat2=str_replace("'", "`", $syarat2);
	$syarat3=str_replace("'", "`", $syarat3);
	$syarat4=str_replace("'", "`", $syarat4);
	$syarat5=str_replace("'", "`", $syarat5);
  	$status=$_POST['status'];
	
	$tipe_pencairan=$_POST['tipe_pencairan'];
	$no_rek=$_POST['no_rek'];
	$no_rek=str_replace("'", "`", $no_rek);
	
	$arrNamaFile=json_decode($_POST['arrDataFile']);
	//echo $tipe;exit;
	$countID = count($arrNamaFile);
	
	if($typeform == "tambah"){
		$queryAssignment = "SELECT PAF.ASSIGNMENT_ID
			,REGEXP_SUBSTR(PJ.NAME, '[^.]+', 1, 1) PERUSAHAAN
			,REGEXP_SUBSTR(PJ.NAME, '[^.]+', 1, 2) DEPT
			,CASE WHEN REGEXP_SUBSTR(PP.NAME, '[^.]+', 1, 1)='0' THEN 'MGR'
				ELSE REGEXP_SUBSTR(PP.NAME, '[^.]+', 1, 1) END AS DIV
		FROM APPS.PER_ASSIGNMENTS_F PAF, APPS.PER_JOBS PJ, APPS.PER_POSITIONS PP
		WHERE PAF.JOB_ID=PJ.JOB_ID
			AND PAF.POSITION_ID=PP.POSITION_ID
			AND PAF.EFFECTIVE_END_DATE > SYSDATE
			AND PRIMARY_FLAG = 'Y'
			AND PAF.PERSON_ID=$person_id";
		$resultAssignment = oci_parse($con,$queryAssignment);
		oci_execute($resultAssignment);
		$rowAssignment = oci_fetch_row($resultAssignment);
		$assignment_id = $rowAssignment[0];
		$namePer = $rowAssignment[1];
		//$nameDept = $rowAssignment[2];
		$nameDiv = $rowAssignment[3];
		
		$querycount = "SELECT COUNT(-1) 
		FROM MJ.MJ_M_GENERATENO 
		WHERE APPCODE='" . APPCODE . "' 
		AND TRANSAKSI_KODE='BON'";
		$resultcount = oci_parse($con,$querycount);
		oci_execute($resultcount);
		$rowcount = oci_fetch_row($resultcount);
		$jumgen = $rowcount[0]; 
		
		if ($jumgen>0){
			/* $query = "SELECT LASTNO 
			FROM MJ.MJ_M_GENERATENO 
			WHERE TEMP1='$namePer'
			AND TEMP3='$nameDiv' 
			AND APPCODE='" . APPCODE . "' 
			AND TRANSAKSI_KODE='BON'"; */
			$query = "SELECT LASTNO 
			FROM MJ.MJ_M_GENERATENO 
			WHERE APPCODE='" . APPCODE . "' 
			AND TRANSAKSI_KODE='BON'";
			$result = oci_parse($con,$query);
			oci_execute($result);
			$rowGLastno = oci_fetch_row($result);
			$lastNo = $rowGLastno[0];
		} else {
			$resultSeq = oci_parse($con,"SELECT MJ.MJ_M_GENERATENO_SEQ.nextval FROM DUAL");
			oci_execute($resultSeq);
			$rowHSeq = oci_fetch_row($resultSeq);
			$gencountseq = $rowHSeq[0];
		
			$query = "INSERT INTO MJ.MJ_M_GENERATENO (ID, TAHUN, LASTNO, APPCODE, TEMP1, TEMP2, TEMP3, TRANSAKSI_KODE) 
			VALUES ($gencountseq, '$tahunGenNo', '0', '" . APPCODE . "', '$namePer', '', '$nameDiv', 'BON')";
			$result = oci_parse($con,$query);
			oci_execute($result);
			$lastNo = 0;
		} 
		
		//RESET NOMOR PER TAHUN
		$querycount = "SELECT TAHUN 
		FROM MJ.MJ_M_GENERATENO 
		WHERE APPCODE='" . APPCODE . "' 
		AND TRANSAKSI_KODE='BON'";
		$resultcount = oci_parse($con,$querycount);
		oci_execute($resultcount);
		$rowcount = oci_fetch_row($resultcount);
		$thnGen = $rowcount[0]; 
		
		if($thnGen!=$tahunGenNo){
			$lastNo = 0;
			$lastNo=$lastNo+1;
			$queryLast = "UPDATE MJ.MJ_M_GENERATENO 
			SET LASTNO='$lastNo', TAHUN='$tahunGenNo' 
			WHERE APPCODE='" . APPCODE . "' 
			AND TRANSAKSI_KODE='BON'";
			$resultLast = oci_parse($con,$queryLast);
			oci_execute($resultLast);
		} else {
			$lastNo=$lastNo+1;
			$queryLast = "UPDATE MJ.MJ_M_GENERATENO 
			SET LASTNO='$lastNo' 
			WHERE APPCODE='" . APPCODE . "' 
			AND TRANSAKSI_KODE='BON'";
			$resultLast = oci_parse($con,$queryLast);
			oci_execute($resultLast);
		}
		
		$jumno=strlen($lastNo);
		if($jumno==1){
			$nourut = "00000".$lastNo;
		} else if ($jumno==2){
			$nourut = "0000".$lastNo;
		} else if ($jumno==3){
			$nourut = "000".$lastNo;
		} else if ($jumno==4){
			$nourut = "00".$lastNo;
		} else if ($jumno==5){
			$nourut = "0".$lastNo;
		} else {
			$nourut = $lastNo;
		}
		
		$noBS = "BS/" . $namePer . "/" . $nameDiv . "/" . $tahunGenNo. "/" . date('m') . "/" . $nourut;
		
		$resultSeq = oci_parse($con,"SELECT MJ.MJ_T_BS_SEQ.nextval FROM dual"); 
		oci_execute($resultSeq);
		$row = oci_fetch_row($resultSeq);
		$hdid = $row[0];
		
		if($person_id == $penanggungJawab){
			$tingkat = 2;
			$status_bs = 'Approved';
		}
		
	
		$sqlQuery = "INSERT INTO MJ.MJ_T_BS (ID, NO_BS, ASSIGNMENT_ID, PENANGGUNG_JAWAB, NOMINAL, KETERANGAN, TINGKAT, AKTIF, STATUS, CREATED_BY, CREATED_DATE, TIPE, BYHRD, PERSON_ID, PERUSAHAAN_BS, TGL_JT, SYARAT1, SYARAT2, SYARAT3, SYARAT4, SYARAT5,
						TIPE_PENCAIRAN, NO_REK ) 
		VALUES ( $hdid, '$noBS', $assignment_id, $penanggungJawab, $nominal, '$ket', $tingkat, '$status', '$status_bs', $emp_id, SYSDATE, '$tipe', 'N', $person_id, $perusahaan_bs, to_date('$tgl_jt','YYYY-MM-DD'), '$syarat1', '$syarat2', '$syarat3', '$syarat4', '$syarat5',
						'$tipe_pencairan', '$no_rek' )";
						
		//echo $sqlQuery;exit;
		
		$result = oci_parse($con,$sqlQuery);
		oci_execute($result);
		
		//insert upload PINJAMAN
		$query = "SELECT FILENAME, FILESIZE, FILETYPE, TRANSAKSI_KODE
		FROM MJ.MJ_TEMP_UPLOAD
		WHERE APP_ID=" . APPCODE . " AND USERNAME='$user_id'
		AND TRANSAKSI_KODE='BON'";
		//echo $query;
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
		AND TRANSAKSI_KODE='BON'";
		$result = oci_parse($con, $query);
		oci_execute($result);
		//end insert upload PINJAMAN
		
		// for ($x=0; $x<$countID; $x++){
			// $resultUpload = oci_parse($con,"UPDATE MJ.MJ_TEMP_UPLOAD SET TRANSAKSI_ID = $hdid WHERE TRANSAKSI_KODE = 'BON' AND FILENAME = '$arrNamaFile[$x]' AND TRANSAKSI_ID = 1");
			// oci_execute($resultUpload);
		// }
	
		$data="sukses";
	
	
	// Proses Edit
	
	} else {
		$resultCek = oci_parse($con,"SELECT CASE WHEN PERSON_ID = PENANGGUNG_JAWAB AND TINGKAT = 2 THEN 'PROCESS' ELSE STATUS END FROM MJ.MJ_T_BS WHERE ID=$hdid"); 
		oci_execute($resultCek);
		$rowCek = oci_fetch_row($resultCek);
		$statusBS = $rowCek[0];
		
		if ( $statusBS != 'Approved' ) 
		{
			
			$sqlQuery = "
				UPDATE MJ.MJ_T_BS 
				SET PERUSAHAAN_BS = $perusahaan_bs, 
				PENANGGUNG_JAWAB=$penanggungJawab, 
				TIPE='$tipe', 
				NOMINAL=$nominal, 
				tgl_jt = to_date('$tgl_jt','YYYY-MM-DD'), 
				KETERANGAN='$ket', 
				AKTIF='$status', 
				LAST_UPDATED_BY=$emp_id, 
				LAST_UPDATED_DATE=SYSDATE, 
				SYARAT1 = '$syarat1', 
				SYARAT2 = '$syarat2', 
				SYARAT3 = '$syarat3', 
				SYARAT4 = '$syarat4', 
				SYARAT5 = '$syarat5',
				TIPE_PENCAIRAN = '$tipe_pencairan', 
				NO_REK = '$no_rek',
				STATUS = 'PROCESS'
				WHERE 1=1 
				AND ID=$hdid ";
			//echo $sqlQuery;exit;
			$result = oci_parse($con,$sqlQuery);
			oci_execute($result);
			
			//delete  file attachment PINJAMAN sebelum dilakukan insert sesuai data di temp upload
			
			$queryUpload = "DELETE FROM MJ.MJ_M_UPLOAD WHERE APP_ID=" . APPCODE . " AND USERNAME='$user_id' AND TRANSAKSI_KODE='BON' AND TRANSAKSI_ID=$hdid";
			//echo $query;
			$resultUpload = oci_parse($con,$queryUpload);
			oci_execute($resultUpload);
				
			$query = "SELECT FILENAME, FILESIZE, FILETYPE, TRANSAKSI_KODE
			FROM MJ.MJ_TEMP_UPLOAD
			WHERE APP_ID=" . APPCODE . " AND USERNAME='$user_id'
			AND TRANSAKSI_KODE='BON'";
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
			AND TRANSAKSI_KODE='BON'";
			$result = oci_parse($con, $query);
			oci_execute($result);
			//end insert upload PINJAMAN
			
			/* backup cara upload old
			for ($x=0; $x<$countID; $x++){
				$querycountUpl = "SELECT count(-1)
				FROM MJ.MJ_TEMP_UPLOAD
				WHERE FILENAME = '$arrNamaFile[$x]'
				AND TRANSAKSI_ID=$hdid
				AND TRANSAKSI_KODE='BON'";
				$resultcountUpl = oci_parse($con,$querycountUpl);
				oci_execute($resultcountUpl);
				$rowcountUpl = oci_fetch_row($resultcountUpl);
				$countUpl = $rowcountUpl[0]; 
				
				if($countUpl == 0){
					//DELETE INSERT upload BS
					$queryUpload = "DELETE FROM MJ.MJ_TEMP_UPLOAD WHERE APP_ID=" . APPCODE . " AND USERNAME='$user_id' AND TRANSAKSI_KODE='BON' AND TRANSAKSI_ID=$hdid";
					//echo $query;
					$resultUpload = oci_parse($con,$queryUpload);
					oci_execute($resultUpload);
				
				
					$resultUpload = oci_parse($con,"UPDATE MJ.MJ_TEMP_UPLOAD SET TRANSAKSI_ID = $hdid WHERE TRANSAKSI_KODE = 'BON' AND FILENAME = '$arrNamaFile[$x]' AND TRANSAKSI_ID = 1");
					oci_execute($resultUpload);
				}
			} */
			// $query = "SELECT FILENAME, FILESIZE, FILETYPE, TRANSAKSI_KODE
			// FROM MJ.MJ_TEMP_UPLOAD
			// WHERE APP_ID=" . APPCODE . " AND USERNAME='$user_id'
			// AND TRANSAKSI_KODE='BS'";
			// $result = oci_parse($con, $query);
			// oci_execute($result);
			// while($row = oci_fetch_row($result))
			// {
				// $vFilename=$row[0];
				// $vFilesize=$row[1];
				// $vFiletype=$row[2];
				// $vFilekode=$row[3];
				
				// $resultDetSeq = oci_parse($con,"SELECT MJ.MJ_M_UPLOAD_SEQ.nextval FROM DUAL");
				// oci_execute($resultDetSeq);
				// $rowDSeq = oci_fetch_row($resultDetSeq);
				// $seqD = $rowDSeq[0];
				
				// $queryUpload = "INSERT INTO MJ.MJ_M_UPLOAD (ID, APP_ID, TRANSAKSI_ID, FILENAME, FILESIZE, FILETYPE, USERNAME, CREATEDDATE, TRANSAKSI_KODE) 
				// VALUES ($seqD, " . APPCODE . ", $hdid, '$vFilename', '$vFilesize', '$vFiletype', '$user_id', SYSDATE, '$vFilekode')";
				// //echo $query;
				// $resultUpload = oci_parse($con,$queryUpload);
				// oci_execute($resultUpload);
			// }
			
			// $query = "DELETE FROM MJ.MJ_TEMP_UPLOAD
			// WHERE APP_ID=" . APPCODE . " AND USERNAME='$user_id'
			// AND TRANSAKSI_KODE='BS'";
			// $result = oci_parse($con, $query);
			// oci_execute($result);
			// //end insert upload BS
			
			$data="sukses";
		}else{
			$data="sudahapprove";
		}
	}
	
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
	
if($data=="sukses")
{

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

if ( $person_id == $penanggungJawab )
{
	
	$resultCount = oci_parse($con,"SELECT COUNT(CHECKER_ID) X FROM MJ.MJ_M_APPROVAL_PINJAMAN WHERE TIPE = 'BS' AND STATUS = 'A'
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

Mohon untuk melakukan approval pengajuan BS $tipeBS diatas.
Terima Kasih.
";
		$mail->Body = $bodyEmail;
		
		
		/* DIPAKAI LIVE */
		
		$mail->addCC($emailPem);	
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
			
			$mail->AddAddress( 'maria.natalia@merakjaya.co.id' );
			$mail->addCC( 'dwatra@merakjaya.co.id' );
			$mail->addCC( 'yuke.indarto@merakjaya.co.id' );
			
			DIPAKAI TESTING */
			
			
		} else {
			
			$resultEmail = oci_parse($con,"SELECT EMAIL_ADDRESS, INITCAP(TITLE)||REGEXP_SUBSTR(previous_last_name,'[^-]+', 2, 3)
				FROM PER_PEOPLE_F WHERE PERSON_ID = (SELECT CHECKER_ID FROM MJ.MJ_M_APPROVAL_PINJAMAN WHERE TIPE = 'BS' AND STATUS = 'A'
				AND PERUSAHAAN_ID = (
				SELECT BS.PERUSAHAAN_BS FROM MJ.MJ_T_BS BS
							INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON BS.ASSIGNMENT_ID =PAF.ASSIGNMENT_ID AND PAF.EFFECTIVE_END_DATE > SYSDATE AND PAF.PRIMARY_FLAG='Y'
							INNER JOIN APPS.HR_ORGANIZATION_UNITS HOU ON PAF.ORGANIZATION_ID = HOU.ORGANIZATION_ID
							WHERE BS.ID = $hdid
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

Mohon untuk melakukan approval pengajuan BS $tipeBS diatas.
Terima Kasih.
";
		
			$mail->Body = $bodyEmail;
			
			
			/* DIPAKAI LIVE */
			
			$mail->addCC($emailPem);	
			$mail->addCC('greta.silviana@merakjaya.co.id');
			$mail->addCC('fitri.ambarwati@merakjaya.co.id');
			$mail->addCC('maria.natalia@merakjaya.co.id');
			$mail->addCC('dwatra@merakjaya.co.id');
			$mail->AddAddress($emailFin);
			
			/* DIPAKAI LIVE */

			
			
			/* DIPAKAI TESTING
			
			$mail->AddAddress( 'maria.natalia@merakjaya.co.id' );
			$mail->addCC( 'dwatra@merakjaya.co.id' );
			$mail->addCC( 'yuke.indarto@merakjaya.co.id' );
			
			DIPAKAI TESTING */
			
		}	

} else 
{
	
	$bodyEmail = "Dear $pj, 
	
Telah dibuat pengajuan BS $tipeBS nomor $noBS dengan informasi berikut :

Nama Karyawan :   $namaPem
Perusahaan :   $companyPem
Department :   $deptPem
Posisi / Grade :   $posPem / $gradePem
Perusahaan BS :   $perusahaanBS
Penanggung Jawab :   $pj
Nominal :   $nominal
Tgl Jatuh Tempo :   $tglJt
Keterangan :   $keterangan

Mohon untuk melakukan approval pengajuan BS $tipeBS diatas.
Terima Kasih.
";
	$mail->Body = $bodyEmail;
	
	
	/* DIPAKAI LIVE */
	
	$mail->addCC($emailPem);	
	$mail->addCC('greta.silviana@merakjaya.co.id');
	$mail->addCC('fitri.ambarwati@merakjaya.co.id');
	$mail->addCC('maria.natalia@merakjaya.co.id');
	$mail->addCC('dwatra@merakjaya.co.id');
	$mail->AddAddress($emailPj);
	
	/* DIPAKAI LIVE */


	/* DIPAKAI TESTING
	
	$mail->AddAddress( 'maria.natalia@merakjaya.co.id' );
	$mail->addCC( 'dwatra@merakjaya.co.id' );
	$mail->addCC( 'yuke.indarto@merakjaya.co.id' );
	
	DIPAKAI TESTING */	

}

	$success = $mail->Send();
	
	/* DIPAKAI */
	
}
	
	
	$result = array('success' => true,
					'results' => $hdid .'|'. $noBS,
					'rows' => $data
				);
	echo json_encode($result);
  }


?>