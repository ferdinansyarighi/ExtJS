<?php
include '../../main/koneksi.php';
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
  
$nopengajuan = "";
$tglfrom = "";
$tglto = "";
$namakar = "";

if (isset($_GET['tglfrom']) || isset($_GET['tglto']))
{	
	$queryfilter="";
	$tglskr=date('Y-m-d');
	$tglfrom = $_GET['tglfrom'];
	$tglto = $_GET['tglto'];
	$namapemohon = $_GET['namakar'];
	$namapemohon = str_replace("'", "%", $namapemohon); //LFN
	$nopengajuan = $_GET['nopengajuan'];

	if ($namapemohon!=''){
		$queryfilter .=" AND NAMA_KARYAWAN LIKE '%$namapemohon%' ";
	}
	if ($tglfrom!=''){
		$queryfilter .=" AND TGL_RESIGN >= TO_DATE('$tglfrom', 'DD/MM/YY') ";
	}
	if ($tglto!=''){
		$queryfilter .=" AND TGL_RESIGN <= TO_DATE('$tglto', 'DD/MM/YY') ";
	}
	if ($nopengajuan!=''){
		$queryfilter .=" AND NO_PENGAJUAN LIKE '%$nopengajuan%' ";
	}
}

$querycount = "SELECT COUNT(-1)
FROM PER_PEOPLE_F PPF, PER_JOBS PJ, PER_ASSIGNMENTS_F PAF, PER_GRADES PG
WHERE PPF.PERSON_ID = PAF.PERSON_ID AND PAF.JOB_ID = PJ.JOB_ID AND PJ.JOB_ID = 26066 
AND PAF.GRADE_ID = PG.GRADE_ID AND PG.NAME LIKE '%Manager%' AND PPF.FULL_NAME LIKE '%$emp_name%'";
$resultcount = oci_parse($con,$querycount);
oci_execute($resultcount);
$rowcount = oci_fetch_row($resultcount);
$jumgen = $rowcount[0];

$querycountMan = "SELECT COUNT(-1) FROM PER_PEOPLE_F PPF, PER_POSITIONS PP, PER_ASSIGNMENTS_F PAF 
WHERE PPF.FULL_NAME LIKE '%$emp_name%' AND PAF.PERSON_ID = PPF.PERSON_ID AND PAF.POSITION_ID = PP.POSITION_ID AND PP.NAME LIKE '%MGR%' AND PAF.JOB_ID != 26066";
$resultcountman = oci_parse($con,$querycountMan);
oci_execute($resultcountman);
$rowcountman = oci_fetch_row($resultcountman);
$jumman = $rowcountman[0];

if($jumgen == 0)
{
	$query = "SELECT ID,NO_PENGAJUAN,NAMA_KARYAWAN,COMPANY,DEPARTMENT,POSITION,GRADE,LOCATION,TGL_MASUK,TGL_RESIGN,TAHUN_LAMA_KERJA,
	BULAN_LAMA_KERJA, HARI_LAMA_KERJA, MANAGER, KETERANGAN, STATUS, APPROVAL_MANAGER, APPROVAL_MANAGER_HRD, TGL_APP_TERAKHIR, CREATED_BY,CREATED_DATE, 
	TAHUN_LAMA_KERJA || ' Tahun ' || BULAN_LAMA_KERJA || ' Bulan ' || HARI_LAMA_KERJA || ' Hari' LAMA_KERJA, KETERANGAN_DISAPPROVE, TGL_PENGAJUAN
	FROM MJ.MJ_T_RESIGN WHERE MANAGER LIKE '%$emp_name%' AND APPROVAL_MANAGER LIKE 'In Proccess' AND VALIDASI IS NULL $queryfilter";
	//echo $query;exit;
}
else{
	$query = "SELECT ID,NO_PENGAJUAN,NAMA_KARYAWAN,COMPANY,DEPARTMENT,POSITION,GRADE,LOCATION,TGL_MASUK,TGL_RESIGN,TAHUN_LAMA_KERJA,
	BULAN_LAMA_KERJA, HARI_LAMA_KERJA, MANAGER, KETERANGAN, STATUS, APPROVAL_MANAGER, APPROVAL_MANAGER_HRD, TGL_APP_TERAKHIR, CREATED_BY,CREATED_DATE, 
	TAHUN_LAMA_KERJA || ' Tahun ' || BULAN_LAMA_KERJA || ' Bulan ' || HARI_LAMA_KERJA || ' Hari' LAMA_KERJA, KETERANGAN_DISAPPROVE, TGL_PENGAJUAN
	FROM MJ.MJ_T_RESIGN WHERE APPROVAL_MANAGER LIKE 'Approved' AND APPROVAL_MANAGER_HRD = 'In Proccess' AND VALIDASI IS NULL $queryfilter
	UNION
	SELECT ID,NO_PENGAJUAN,NAMA_KARYAWAN,COMPANY,DEPARTMENT,POSITION,GRADE,LOCATION,TGL_MASUK,TGL_RESIGN,TAHUN_LAMA_KERJA,
	BULAN_LAMA_KERJA, HARI_LAMA_KERJA, MANAGER, KETERANGAN, STATUS, APPROVAL_MANAGER, APPROVAL_MANAGER_HRD, TGL_APP_TERAKHIR, CREATED_BY,CREATED_DATE, 
	TAHUN_LAMA_KERJA || ' Tahun ' || BULAN_LAMA_KERJA || ' Bulan ' || HARI_LAMA_KERJA || ' Hari' LAMA_KERJA, KETERANGAN_DISAPPROVE, TGL_PENGAJUAN
	FROM MJ.MJ_T_RESIGN WHERE MANAGER LIKE '%$emp_name%' AND APPROVAL_MANAGER = 'In Proccess' AND APPROVAL_MANAGER_HRD = 'In Proccess' AND VALIDASI IS NULL $queryfilter";
}

$result = oci_parse($con,$query);
oci_execute($result);

$count = 0;
while($row = oci_fetch_row($result))
{
	$TransID=$row[0];
	$record = array();
	$record['HD_ID']=$row[0];
	$record['DATA_NO_PENGAJUAN']=$row[1];
	$record['DATA_NAMA_KARYAWAN']=$row[2];
	$record['DATA_COMPANY']=$row[3];
	$record['DATA_DEPT']=$row[4];
	$record['DATA_POS']=$row[5];
	$record['DATA_GRADE']=$row[6];
	$record['DATA_LOCATION']=$row[7];
	$record['DATA_TGL_MASUK']=$row[8];
	$record['DATA_TGL_RESIGN']=$row[9];
	$record['DATA_TAHUN_LAMA_KERJA']=$row[10];
	$record['DATA_BULAN_LAMA_KERJA']=$row[11];
	$record['DATA_HARI_LAMA_KERJA']=$row[12];
	$record['DATA_MANAGER']=$row[13];
	$record['DATA_KETERANGAN']=$row[14];
	$record['DATA_STATUS']=$row[15];
	$record['DATA_APPROVAL_MANAGER']=$row[16];
	$record['DATA_APPROVAL_MANAGER_HRD']=$row[17];
	$record['DATA_TGL_APP_TERAKHIR']=$row[18];
	$record['DATA_CREATED_BY']=$row[19];
	$record['DATA_CREATED_DATE']=$row[20];
	$record['DATA_LAMA_KERJA']=$row[21];
	$record['DATA_KET_DISAPP']=$row[22];
	$record['DATA_TGL_PENGAJUAN']=$row[23];

	$queryAtt = "SELECT TRANSAKSI_ID, FILENAME, FILESIZE, FILETYPE, USERNAME, TO_CHAR(CREATEDDATE, 'YYYY-MM-DD')
	FROM MJ.MJ_M_UPLOAD
	WHERE APP_ID=" . APPCODE . " AND TRANSAKSI_ID=$TransID AND TRANSAKSI_KODE = 'RESIGN'";
	$resultAtt = oci_parse($con, $queryAtt);
	oci_execute($resultAtt);
	$doccount=0;
	$dataAtt='';
	while($rowAtt = oci_fetch_row($resultAtt))
	{
		$vTransID =$rowAtt[0];
		$vFilename=$rowAtt[1];
		$vFilesize=$rowAtt[2];
		$vFiletype=$rowAtt[3];
		$vFileuser=$rowAtt[4];
		$vFiledate=$rowAtt[5];
		$ekstensi	= end(explode(".", $vFilename));
		$docattachment = "<a href= " . PATHAPP . "/upload/" . $vFileuser.md5($vFiledate).$vFilesize.md5($vFilename).".".$ekstensi . " target=_blank>" . $vFilename . "</a>";
		//$docattachment = PATHAPP . "/upload/" . $vFileuser.md5($vFiledate).$vFilesize.md5($vFilename).".".$ekstensi;
		if($doccount==0){
			$dataAtt = $docattachment;
		} else {
			$dataAtt .= ", " . $docattachment;
		}
		//echo $docattachment;
		//$mail->addAttachment( $docattachment );
		$doccount++;
	}
	$record['DATA_ATTACHMENT']=$dataAtt;
	$data[]=$record;
	$count++;
}

 $totalrow = 0;

	$result = array('success' => true,
					'results' => $totalrow,
					'rows' 	  => $data,
				);
	echo json_encode($result);
?>