<?PHP
	include '../../main/koneksi.php'; //Koneksi ke database
	require('smtpemailattachment.php');
 
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
  
	$data = "gagal";
	$tglskr=date('Y-m-d'); 
	$tahunGenNo=substr($tglskr, 0, 4);
	$bulanGenNo=substr($tglskr, 5, 2);
	if(isset($_POST['tglresign'])){
		$formtype      =$_POST['formtype'];
		$hdid          =$_POST['hd_id'];
		$no_pengajuan  =$_POST['no_pengajuan'];
		$nama_karyawan =$_POST['nama_karyawan'];
		$nama_karyawan = str_replace("'", "%", $nama_karyawan); //LFN
		$company       =$_POST['company'];
		$department    =$_POST['department'];
		$position      =$_POST['position'];
		$grade         =$_POST['grade'];
		$location      =$_POST['location'];
		$tglmasuk      =$_POST['tglmasuk'];
		$tglresign     =$_POST['tglresign'];
		$tglpengajuan  =$_POST['tglpengajuan'];
		$lamakerja     =$_POST['lamakerja'];
		$manager       =$_POST['manager'];
		$keterangan    =$_POST['keterangan'];

		$status=$_POST['status'];
		if ($status == 'false')
		{
			$status = '0';
		}
		else
		{
			$status = '1';
		}
	}

	$tahunkerja = substr($lamakerja, 0, 2);
	$bulankerja = substr($lamakerja, 9, 2);
	$harikerja  = substr($lamakerja, 18, 2);

	$keterangan = str_replace("'", "`", $keterangan);

	if ($formtype=='tambah')
	{
		$queryEmailman = "SELECT DISTINCT PPF.EMAIL_ADDRESS FROM PER_PEOPLE_F PPF, MJ.MJ_T_RESIGN RES
		WHERE PPF.FULL_NAME = TRIM(REGEXP_SUBSTR('$manager', '[^-]+', 1, 1))
		AND PPF.EMAIL_ADDRESS IS NOT NULL";
		$resultEmailman = oci_parse($con, $queryEmailman);
		oci_execute($resultEmailman);
		$rowEmailman = oci_fetch_row($resultEmailman);
		$emailMan=$rowEmailman[0];

		$querycount = "SELECT COUNT(-1) FROM MJ.MJ_M_GENERATENO WHERE APPCODE='MJBRESIGN' AND TEMP1='$company'";
		$resultcount = oci_parse($con,$querycount);
		oci_execute($resultcount);
		$rowcount = oci_fetch_row($resultcount);
		$jumgen = $rowcount[0];
		
		if ($jumgen>0){
			$query = "SELECT LASTNO FROM MJ.MJ_M_GENERATENO WHERE APPCODE='MJBRESIGN' AND TEMP1='$company'";
			$result = oci_parse($con,$query);
			oci_execute($result);
			$rowGLastno = oci_fetch_row($result);
			$lastNo = $rowGLastno[0];
		} else {
			$resultSeq = oci_parse($con,"SELECT MJ.MJ_M_GENERATENO_SEQ.nextval FROM DUAL");
			oci_execute($resultSeq);
			$rowHSeq = oci_fetch_row($resultSeq);
			$gencountseq = $rowHSeq[0];
		
			$query = "INSERT INTO MJ.MJ_M_GENERATENO (ID, LASTNO, APPCODE, TEMP1) VALUES ($gencountseq, '0', 'MJBRESIGN', '$company')";
			$result = oci_parse($con,$query);
			oci_execute($result);
			$lastNo = 0;
		} 
		
		$lastNo=$lastNo+1;
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

		$no_pengajuan = "HRD" . "/" . "RESIGN" . "/" . $bulanGenNo . $tahunGenNo . "/" . $nourut;

		$resultSeq = oci_parse($con,"SELECT MJ.MJ_T_RESIGN_SEQ.nextval FROM DUAL");
		oci_execute($resultSeq);
		$rowHSeq = oci_fetch_row($resultSeq);
		$gencountseq = $rowHSeq[0];

		$querycekeMan = "SELECT PERSON_ID FROM PER_PEOPLE_F WHERE FULL_NAME = TRIM(REGEXP_SUBSTR('$manager', '[^-]+', 1, 1))
							AND FULL_NAME NOT LIKE '%TRIAL%' AND EFFECTIVE_START_DATE <= SYSDATE AND EFFECTIVE_END_DATE >= SYSDATE";
		$resultcekeMan = oci_parse($con,$querycekeMan);
		oci_execute($resultcekeMan);
		$rowcekeMan = oci_fetch_row($resultcekeMan);
		$jumcekeMan = $rowcekeMan[0];

		$querycountMan = "SELECT COUNT(-1)
							FROM PER_PEOPLE_F PPF, PER_POSITIONS PP, PER_ASSIGNMENTS_F PAF 
							WHERE PPF.FULL_NAME LIKE '%$emp_name%' AND PAF.PERSON_ID = PPF.PERSON_ID AND PAF.POSITION_ID = PP.POSITION_ID
								AND PP.NAME LIKE '%MGR%'";
		$resultcountman = oci_parse($con,$querycountMan);
		oci_execute($resultcountman);
		$rowcountman = oci_fetch_row($resultcountman);
		$jumman = $rowcountman[0];

		$querycountEx = "SELECT COUNT(-1)
							FROM MJ.MJ_T_RESIGN
							WHERE NAMA_KARYAWAN LIKE '%$nama_karyawan%'
								AND (APPROVAL_MANAGER = 'In Proccess' OR APPROVAL_MANAGER_HRD = 'In Proccess') AND STATUS = 1";
		$resultcountEx = oci_parse($con,$querycountEx);
		oci_execute($resultcountEx);
		$rowcountEx = oci_fetch_row($resultcountEx);
		$jumEx = $rowcountEx[0];

		$queryemailPem = "SELECT EMAIL_ADDRESS FROM PER_PEOPLE_F WHERE FULL_NAME = '$nama_karyawan'";
		$resultemailPem = oci_parse($con,$queryemailPem);
		oci_execute($resultemailPem);
		$rowemailPem = oci_fetch_row($resultemailPem);
		$emailPemohon = $rowemailPem[0];

		if($jumEx == 0)
		{	
			$querycekerMan = "SELECT COUNT(-1)
								FROM PER_PEOPLE_F
								WHERE FULL_NAME = TRIM(REGEXP_SUBSTR('$manager', '[^-]+', 1, 1))
									AND FULL_NAME NOT LIKE '%TRIAL%' AND EFFECTIVE_START_DATE <= SYSDATE AND EFFECTIVE_END_DATE >= SYSDATE";
			$resultcekerMan = oci_parse($con,$querycekerMan);
			oci_execute($resultcekerMan);
			$rowcekerMan = oci_fetch_row($resultcekerMan);
			$jumcekerMan = $rowcekerMan[0];

			$querycrazyMan = "SELECT TRIM(REGEXP_SUBSTR('$manager', '[^-]+', 1, 2)) FROM DUAL";
			$resultcrazyMan = oci_parse($con,$querycrazyMan);
			oci_execute($resultcrazyMan);
			$rowcrazyMan = oci_fetch_row($resultcrazyMan);
			$jumcrazyMan = $rowcrazyMan[0];

			$querycrazyMani = "SELECT COUNT(-1) FROM PER_POSITIONS PP WHERE REGEXP_SUBSTR(PP.NAME, '[^.]+', 1, 3) = '$jumcrazyMan'";
			$resultcrazyMani = oci_parse($con,$querycrazyMani);
			oci_execute($resultcrazyMani);
			$rowcrazyMani = oci_fetch_row($resultcrazyMani);
			$jumcrazyMani = $rowcrazyMani[0];

			$querycrazycek = "SELECT LENGTH('$keterangan') FROM DUAL";
			$resultcrazycek = oci_parse($con,$querycrazycek);
			oci_execute($resultcrazycek);
			$rowcrazycek = oci_fetch_row($resultcrazycek);
			$jumcrazycek = $rowcrazycek[0];

		if($jumcrazycek > 200)
		{
			$data = "gagal desc";
			$hdid2 = "gagal";
		}
		else if($jumcrazyMani == 0){
			$data = "gagal jabatan manager";
			$hdid2 = "gagal";
		}
		else if ($jumcekerMan == 0)
		{
			$data = "gagal manager";
			$hdid2 = "gagal";
		}
		else 
		{
			$emailJam = date("H");
			$subjectEmail = "[Autoemail] Pengajuan Resign $no_pengajuan";
			
			$mail = new PHPMailer();
			$mail->Hostname = '192.168.0.35';
			$mail->Port = 25;
			$mail->Host = "192.168.0.35";
			$mail->SMTPAuth = true;
			$mail->Username = 'autoemail.it@merakjaya.co.id';
			$mail->Password = 'autoemail';
			if($emp_id == $jumcekeMan && $jumman >=1)
			{	$query = "SELECT FILENAME, FILESIZE, FILETYPE, TRANSAKSI_KODE
							FROM MJ.MJ_TEMP_UPLOAD
							WHERE APP_ID=" . APPCODE . " AND USERNAME='$user_id'
							AND TRANSAKSI_KODE='RESIGN'";
							
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
					$queryUpload = "INSERT INTO MJ.MJ_M_UPLOAD (ID, APP_ID, TRANSAKSI_ID, FILENAME, FILESIZE, FILETYPE, USERNAME, CREATEDDATE,
										TRANSAKSI_KODE) 
									VALUES ($seqD, " . APPCODE . ", $gencountseq, '$vFilename', '$vFilesize', '$vFiletype', '$user_id', SYSDATE,
										'$vFilekode')";
					//echo $query;
					$resultUpload = oci_parse($con,$queryUpload);
					oci_execute($resultUpload);
				}

				$query = "DELETE FROM MJ.MJ_TEMP_UPLOAD
							WHERE APP_ID=" . APPCODE . " AND USERNAME='$user_id' AND TRANSAKSI_KODE='RESIGN'";
				$result = oci_parse($con, $query);
				oci_execute($result);
				//end insert upload RESIGN

				$result = oci_parse($con,"INSERT INTO MJ.MJ_T_RESIGN (ID, NO_PENGAJUAN, NAMA_KARYAWAN, COMPANY, DEPARTMENT, POSITION, GRADE,
											LOCATION, TGL_MASUK, TGL_RESIGN, TGL_PENGAJUAN, TAHUN_LAMA_KERJA, BULAN_LAMA_KERJA, HARI_LAMA_KERJA,
											MANAGER, KETERANGAN, STATUS, APPROVAL_MANAGER, APPROVAL_MANAGER_HRD, TGL_APP_TERAKHIR, CREATED_BY,
											CREATED_DATE)
										VALUES ($gencountseq, '$no_pengajuan', '$nama_karyawan','$company', '$department', '$position', '$grade',
											'$location', TO_DATE('$tglmasuk', 'DD/MM/YYYY'), TO_DATE('$tglresign', 'DD/MM/YYYY'),
											TO_DATE('$tglpengajuan', 'DD/MM/YYYY'), '$tahunkerja', '$bulankerja', '$harikerja', '$manager',
											'$keterangan', '$status', 'Approved', 'In Proccess', sysdate, $emp_id, sysdate)");
				oci_execute($result); 

				$bodyEmail = "Dear All,
					
								Pengajuan resign seperti data dibawah telah dibuat oleh $emp_name

								Nama Karyawan 	: $nama_karyawan
								Perusahaan 		: $company
								Departemen		: $department
								Posisi			: $position
								Grade			: $grade
								Lokasi 			: $location
								Tgl Masuk		: $tglmasuk
								Tgl Resign		: $tglresign
								Dept Head		: $manager
								Keterangan 		: $keterangan

								Mohon untuk segera memproses pengajuan resign tersebut

								Terima Kasih,";

				$mail->Mailer = 'smtp';
				$mail->From = "autoemail.it@merakjaya.co.id";
				$mail->FromName = "Auto Email"; 
				$mail->Subject = $subjectEmail;
				$mail->Body = $bodyEmail;
				
				$mail->AddAddress('freddy.anggo@merakjaya.co.id'); 
				$mail->addCC('teguh@merakjaya.co.id');
				$mail->addCC('sulianti@merakjaya.co.id');
				$mail->addCC('$emailMan');
				$mail->addCC('veronika.putri@merakjaya.co.id');
				$mail->addCC($emailPemohon);
				//$mail->AddAddress('m.ferdinansyah@merakjaya.co.id');
				//$mail->addCC('maria.natalia@merakjaya.co.id');
			}
			else {
				$query = "DELETE FROM MJ.MJ_M_UPLOAD
							WHERE APP_ID=" . APPCODE . " AND USERNAME='$user_id'
							AND TRANSAKSI_KODE='RESIGN'";
				$result = oci_parse($con, $query);
				oci_execute($result);

				$query = "SELECT FILENAME, FILESIZE, FILETYPE, TRANSAKSI_KODE
							FROM MJ.MJ_TEMP_UPLOAD
							WHERE APP_ID=" . APPCODE . " AND USERNAME='$user_id'
							AND TRANSAKSI_KODE='RESIGN'";
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
					/*echo "INSERT INTO MJ.MJ_M_UPLOAD (ID, APP_ID, TRANSAKSI_ID, FILENAME, FILESIZE, FILETYPE, USERNAME, CREATEDDATE, TRANSAKSI_KODE) 
					VALUES ($seqD, " . APPCODE . ", $hdid, '$vFilename', '$vFilesize', '$vFiletype', '$user_id', SYSDATE, '$vFilekode')";exit;*/
					$queryUpload = "INSERT INTO MJ.MJ_M_UPLOAD (ID, APP_ID, TRANSAKSI_ID, FILENAME, FILESIZE, FILETYPE, USERNAME, CREATEDDATE,
										TRANSAKSI_KODE) 
									VALUES ($seqD, " . APPCODE . ", $gencountseq, '$vFilename', '$vFilesize', '$vFiletype', '$user_id', SYSDATE,
										'$vFilekode')";
					//echo $query;
					$resultUpload = oci_parse($con,$queryUpload);
					oci_execute($resultUpload);
				}

				$query = "DELETE FROM MJ.MJ_TEMP_UPLOAD
							WHERE APP_ID=" . APPCODE . " AND USERNAME='$user_id' AND TRANSAKSI_KODE='RESIGN'";
				$result = oci_parse($con, $query);
				oci_execute($result);
				//end insert upload RESIGN

				$result = oci_parse($con,"INSERT INTO MJ.MJ_T_RESIGN (ID, NO_PENGAJUAN, NAMA_KARYAWAN, COMPANY, DEPARTMENT, POSITION, GRADE,
											LOCATION, TGL_MASUK, TGL_RESIGN, TGL_PENGAJUAN, TAHUN_LAMA_KERJA, BULAN_LAMA_KERJA, HARI_LAMA_KERJA,
											MANAGER, KETERANGAN, STATUS, APPROVAL_MANAGER, APPROVAL_MANAGER_HRD, CREATED_BY, CREATED_DATE)
										VALUES ($gencountseq, '$no_pengajuan', '$nama_karyawan','$company', '$department', '$position', '$grade',
											'$location', TO_DATE('$tglmasuk', 'DD/MM/YYYY'), TO_DATE('$tglresign', 'DD/MM/YYYY'),
											TO_DATE('$tglpengajuan', 'DD/MM/YYYY'),'$tahunkerja', '$bulankerja', '$harikerja', '$manager',
											'$keterangan', '$status', 'In Proccess', 'In Proccess', $emp_id, sysdate)");
				oci_execute($result); 

				$bodyEmail = "Dear All,
				
								Pengajuan resign seperti data dibawah telah dibuat

								Nama Karyawan 	: $nama_karyawan
								Perusahaan 		: $company
								Departemen		: $department
								Posisi			: $position
								Grade			: $grade
								Lokasi 			: $location
								Tgl Masuk		: $tglmasuk
								Tgl Resign		: $tglresign
								Dept Head		: $manager
								Keterangan 		: $keterangan

								Mohon untuk segera memproses pengajuan resign tersebut

								Terima Kasih,";

				$mail->Mailer = 'smtp';
				$mail->From = "autoemail.it@merakjaya.co.id";
				$mail->FromName = "Auto Email"; 
				$mail->Subject = $subjectEmail;
				$mail->Body = $bodyEmail;
				
				$mail->AddAddress('freddy.anggo@merakjaya.co.id'); 
				$mail->addCC('teguh@merakjaya.co.id');
				$mail->addCC('sulianti@merakjaya.co.id');
				$mail->addCC('$emailMan');
				$mail->addCC('veronika.putri@merakjaya.co.id');
				$mail->addCC($emailPemohon);
				//$mail->AddAddress('m.ferdinansyah@merakjaya.co.id');
				//$mail->addCC('maria.natalia@merakjaya.co.id');
			}

			$resultid = oci_parse($con,"SELECT ID FROM MJ.MJ_T_RESIGN WHERE NO_PENGAJUAN = '$no_pengajuan'");
			oci_execute($resultid);
			$rowHid = oci_fetch_row($resultid);
			$hdid2 = $rowHid[0];

			$queryLast = "UPDATE MJ.MJ_M_GENERATENO SET LASTNO='$lastNo' WHERE APPCODE='MJBRESIGN' AND TEMP1='$company'";
			$resultLast = oci_parse($con,$queryLast);
			oci_execute($resultLast);
			$data = "sukses";

			$success = $mail->Send();
		}
		}
		else {
			$data = "gagalada";
			$hdid2 = "gagal";
		}

	} else {
		$querycekerMan = "SELECT COUNT(-1)
							FROM PER_PEOPLE_F
							WHERE FULL_NAME = TRIM(REGEXP_SUBSTR('$manager', '[^-]+', 1, 1)) AND FULL_NAME NOT LIKE '%TRIAL%'
								AND EFFECTIVE_START_DATE <= SYSDATE AND EFFECTIVE_END_DATE >= SYSDATE";
		$resultcekerMan = oci_parse($con,$querycekerMan);
		oci_execute($resultcekerMan);
		$rowcekerMan = oci_fetch_row($resultcekerMan);
		$jumcekerMan = $rowcekerMan[0];

		$querycrazyMan = "SELECT TRIM(REGEXP_SUBSTR('$manager', '[^-]+', 1, 2)) FROM DUAL";
		$resultcrazyMan = oci_parse($con,$querycrazyMan);
		oci_execute($resultcrazyMan);
		$rowcrazyMan = oci_fetch_row($resultcrazyMan);
		$jumcrazyMan = $rowcrazyMan[0];

		$querycrazyMani = "SELECT COUNT(-1) FROM PER_POSITIONS PP WHERE REGEXP_SUBSTR(PP.NAME, '[^.]+', 1, 3) = '$jumcrazyMan'";
		$resultcrazyMani = oci_parse($con,$querycrazyMani);
		oci_execute($resultcrazyMani);
		$rowcrazyMani = oci_fetch_row($resultcrazyMani);
		$jumcrazyMani = $rowcrazyMani[0];

		$querycrazycek = "SELECT LENGTH('$keterangan') FROM DUAL";
		$resultcrazycek = oci_parse($con,$querycrazycek);
		oci_execute($resultcrazycek);
		$rowcrazycek = oci_fetch_row($resultcrazycek);
		$jumcrazycek = $rowcrazycek[0];

		if($jumcrazycek > 200)
		{
			$data = "gagal desc";
			$hdid2 = "gagal";
		}
		else if($jumcrazyMani == 0){
			$data = "gagal jabatan manager";
			$hdid2 = "gagal";
		}
		else if ($jumcekerMan == 0)
		{
			$data = "gagal manager";
			$hdid2 = "gagal";
		}
		else 
		{
		$emailJam = date("H");
		$subjectEmail = "[Autoemail] Pengajuan Resign $no_pengajuan";
		
		$mail = new PHPMailer();
		$mail->Hostname = '192.168.0.35';
		$mail->Port = 25;
		$mail->Host = "192.168.0.35";
		$mail->SMTPAuth = true;
		$mail->Username = 'autoemail.it@merakjaya.co.id';
		$mail->Password = 'autoemail';

		$querycountDat = "SELECT DISTINCT COUNT(-1) FROM MJ.MJ_T_RESIGN MTR, PER_PEOPLE_F PPF, PER_ASSIGNMENTS_F PAF, PER_POSITIONS PP
		WHERE (MTR.ID = $hdid AND PPF.PERSON_ID = $emp_id AND PPF.PERSON_ID = PAF.PERSON_ID AND PAF.POSITION_ID = PP.POSITION_ID
		AND PP.NAME LIKE '%MGR%' AND MTR.APPROVAL_MANAGER = 'In Proccess' AND MTR.APPROVAL_MANAGER_HRD = 'In Proccess') 
		OR (MTR.ID = $hdid AND PPF.PERSON_ID = $emp_id AND PPF.PERSON_ID = PAF.PERSON_ID AND PAF.POSITION_ID = PP.POSITION_ID
		AND PP.NAME NOT LIKE '%MGR%' AND MTR.APPROVAL_MANAGER = 'In Proccess' AND MTR.APPROVAL_MANAGER_HRD = 'In Proccess')";
		$resultcountdat = oci_parse($con,$querycountDat);
		oci_execute($resultcountdat);
		$rowcountdat = oci_fetch_row($resultcountdat);
		$jumdat = $rowcountdat[0];

		if($jumdat == 0)
		{
			//count data sesuai hdid dan approval manager disapprove
			$queryhehe = "SELECT COUNT(-1) FROM MJ.MJ_T_RESIGN WHERE ID = $hdid AND (APPROVAL_MANAGER = 'Disapproved' OR APPROVAL_MANAGER_HRD = 'Disapproved')";
			$resulthehe = oci_parse($con,$queryhehe);
			oci_execute($resulthehe);
			$rowhehe = oci_fetch_row($resulthehe);
			$hehe = $rowhehe[0];
			if ($hehe >= 1)
			{
				$query = "DELETE FROM MJ.MJ_M_UPLOAD
				WHERE APP_ID=" . APPCODE . " AND USERNAME='$user_id'
				AND TRANSAKSI_KODE='RESIGN'";
				$result = oci_parse($con, $query);
				oci_execute($result);

				$query = "SELECT FILENAME, FILESIZE, FILETYPE, TRANSAKSI_KODE
				FROM MJ.MJ_TEMP_UPLOAD
				WHERE APP_ID=" . APPCODE . " AND USERNAME='$user_id'
				AND TRANSAKSI_KODE='RESIGN'";
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
				AND TRANSAKSI_KODE='RESIGN'";
				$result = oci_parse($con, $query);
				oci_execute($result);
				//end insert upload RESIGN

				$result = oci_parse($con,"UPDATE MJ.MJ_T_RESIGN SET TGL_RESIGN = TO_DATE('$tglresign', 'DD/MM/YY'), TGL_PENGAJUAN = TO_DATE('$tglpengajuan', 'DD/MM/YY'), TAHUN_LAMA_KERJA = '$tahunkerja', BULAN_LAMA_KERJA = '$bulankerja', HARI_LAMA_KERJA = '$harikerja', MANAGER = '$manager', KETERANGAN_DISAPPROVE = '', PIC_DISAPPROVE = '', STATUS = '$status', APPROVAL_MANAGER = 'In Proccess', APPROVAL_MANAGER_HRD = 'In Proccess', LAST_UPDATED_DATE = sysdate , LAST_UPDATED_BY = $emp_id WHERE ID = $hdid");
				oci_execute($result); 

				$bodyEmail = "Dear All,
					
	Pengajuan resign seperti data dibawah telah dibuat

	Nama Karyawan 	: $nama_karyawan
	Perusahaan 		: $company
	Departemen		: $department
	Posisi			: $position
	Grade			: $grade
	Lokasi 			: $location
	Tgl Masuk		: $tglmasuk
	Tgl Resign		: $tglresign
	Dept Head		: $manager
	Keterangan 		: $keterangan

	Mohon untuk segera memproses pengajuan resign tersebut

	Terima Kasih,";

			$mail->Mailer = 'smtp';
			$mail->From = "autoemail.it@merakjaya.co.id";
			$mail->FromName = "Auto Email"; 
			$mail->Subject = $subjectEmail;
			$mail->Body = $bodyEmail;
			
			$mail->AddAddress('freddy.anggo@merakjaya.co.id'); 
			$mail->addCC('teguh@merakjaya.co.id');
			$mail->addCC('sulianti@merakjaya.co.id');
			$mail->addCC('$emailMan');
			$mail->addCC('veronika.putri@merakjaya.co.id');
			$mail->addCC($emailPemohon);
			//$mail->AddAddress('m.ferdinansyah@merakjaya.co.id');
			//$mail->addCC('maria.natalia@merakjaya.co.id');

				$hdid2 = $hdid;
				$data = "sukses";
			}
			else {
				$data = "gagal";
				$hdid2 = $hdid;
			}
		}
		else {
			$query = "DELETE FROM MJ.MJ_M_UPLOAD
			WHERE APP_ID=" . APPCODE . " AND USERNAME='$user_id'
			AND TRANSAKSI_KODE='RESIGN' AND TRANSAKSI_ID = $hdid";
			$result = oci_parse($con, $query);
			oci_execute($result);

			$query = "SELECT FILENAME, FILESIZE, FILETYPE, TRANSAKSI_KODE
			FROM MJ.MJ_TEMP_UPLOAD
			WHERE APP_ID=" . APPCODE . " AND USERNAME='$user_id'
			AND TRANSAKSI_KODE='RESIGN'";
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
			AND TRANSAKSI_KODE='RESIGN'";
			$result = oci_parse($con, $query);
			oci_execute($result);
			//end insert upload RESIGN
			
			$result = oci_parse($con,"UPDATE MJ.MJ_T_RESIGN SET TGL_PENGAJUAN = TO_DATE('$tglpengajuan', 'DD/MM/YY'), TGL_RESIGN = TO_DATE('$tglresign', 'DD/MM/YY'), TAHUN_LAMA_KERJA = '$tahunkerja', BULAN_LAMA_KERJA = '$bulankerja', HARI_LAMA_KERJA = '$harikerja', MANAGER = '$manager', KETERANGAN = '$keterangan', STATUS = '$status', LAST_UPDATED_DATE = sysdate , LAST_UPDATED_BY = $emp_id WHERE ID = $hdid");
			oci_execute($result); 

			$bodyEmail = "Dear All,
					
	Pengajuan resign seperti data dibawah telah dibuat

	Nama Karyawan 	: $nama_karyawan
	Perusahaan 		: $company
	Departemen		: $department
	Posisi			: $position
	Grade			: $grade
	Lokasi 			: $location
	Tgl Masuk		: $tglmasuk
	Tgl Resign		: $tglresign
	Dept Head		: $manager
	Keterangan 		: $keterangan

	Mohon untuk segera memproses pengajuan resign tersebut

	Terima Kasih,";

			$mail->Mailer = 'smtp';
			$mail->From = "autoemail.it@merakjaya.co.id";
			$mail->FromName = "Auto Email"; 
			$mail->Subject = $subjectEmail;
			$mail->Body = $bodyEmail;
			
			$mail->AddAddress('freddy.anggo@merakjaya.co.id'); 
			$mail->addCC('teguh@merakjaya.co.id');
			$mail->addCC('sulianti@merakjaya.co.id');
			$mail->addCC('$emailMan');
			$mail->addCC('veronika.putri@merakjaya.co.id');
			$mail->addCC($emailPemohon);
			//$mail->AddAddress('m.ferdinansyah@merakjaya.co.id');
			//$mail->addCC('maria.natalia@merakjaya.co.id');
			$hdid2 = $hdid;
			$data = "sukses";
		}
		$success = $mail->Send();
		}
	}

	$result = array('success' => true,
					'results' => $user_id,
					'rows'    => $data,
					'hdid2'   => $hdid2,
				);
	echo json_encode($result);

?>