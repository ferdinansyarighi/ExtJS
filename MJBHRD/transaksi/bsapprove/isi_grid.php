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
  //echo $org_id;exit;
$kategori = "";
$tglfrom = "";
$tglto = "";
$plant = "";
$noDok = "";
$Dept = "";
$tingkat = 0;
$count = 0;
$countSpv = 0;
$countMan = 0;
$rangeDate = 0;

$tglnow=date('Y-m-d'); 
$bulannow=substr($tglnow, 5, 2);
$bulanName="";
$tahunnow=substr($tglnow, 0, 4);
if($bulannow == '01'){
	$bulanName = 'January';
} elseif ($bulannow == '02'){
	$bulanName = 'February';
} elseif ($bulannow == '03'){
	$bulanName = 'March';
} elseif ($bulannow == '04'){
	$bulanName = 'April';
} elseif ($bulannow == '05'){
	$bulanName = 'May';
} elseif ($bulannow == '06'){
	$bulanName = 'June';
} elseif ($bulannow == '07'){
	$bulanName = 'July';
} elseif ($bulannow == '08'){
	$bulanName = 'August';
} elseif ($bulannow == '09'){
	$bulanName = 'September';
} elseif ($bulannow == '10'){
	$bulanName = 'October';
} elseif ($bulannow == '11'){
	$bulanName = 'November';
} else {
	$bulanName = 'December';
}

$queryfilter=""; 

if (isset($_GET['tglfrom']) || isset($_GET['tglto']))
{	
	$hari="";
	$bulan="";
	$tahun=""; 
	$queryfilter=""; 
	$queryfilterRange= "";
	$tglskr=date('Y-m-d'); 
	$tahunbaru=substr($tglskr, 0, 2);
	$tglfrom = $_GET['tglfrom'];
	$hari=substr($tglfrom, 0, 2);
	$bulan=substr($tglfrom, 3, 2);
	$tahun=substr($tglfrom, 6, 2);
	if (strlen($tahun)==2)
	{
		$tahun = $tahunbaru . "" . $tahun;
	}
	$tglfrom = $tahun . "-" . $bulan . "-" . $hari;
	$tglto = $_GET['tglto'];
	$namaKaryawan = $_GET['pemohon'];
	$dept = $_GET['dept'];
	$noDok = $_GET['noBS'];
	$tipeBS = $_GET['tipeBS'];
	$hari=substr($tglto, 0, 2);
	$bulan=substr($tglto, 3, 2);
	$tahun=substr($tglto, 6, 2);
	if (strlen($tahun)==2)
	{
		$tahun = $tahunbaru . "" . $tahun;
	}
	$tglto = $tahun . "-" . $bulan . "-" . $hari;
	$queryfilter .=" AND TO_CHAR(BS.CREATED_DATE, 'YYYY-MM-DD') >= '$tglfrom' AND TO_CHAR(BS.CREATED_DATE, 'YYYY-MM-DD') <= '$tglto' ";
	
	
	if ($namaKaryawan!=''){
		$queryAssign = "SELECT ASSIGNMENT_ID 
		FROM APPS.PER_ASSIGNMENTS_F
		WHERE PERSON_ID = $namaKaryawan AND EFFECTIVE_END_DATE > SYSDATE AND PRIMARY_FLAG='Y'";
		$resultAssign = oci_parse($con,$queryAssign);
		oci_execute($resultAssign);
		$rowAssign = oci_fetch_row($resultAssign);
		$assignment_id = $rowAssign[0]; 
		$queryfilter .=" AND BS.ASSIGNMENT_ID = $assignment_id ";
	}
	if ($noDok!=''){
		$queryfilter .=" AND BS.NO_BS LIKE '%$noDok%' ";
	}
	if ($tipeBS!=''){
		$queryfilter .=" AND BS.TIPE LIKE '%$tipeBS%' ";
	}
	if ($dept!=''){
		$queryfilter .=" AND PAF.JOB_ID = $dept ";
	}
} 


//  Tampilkan data di grid untuk di-approve manager sebagai penanggung jawab

$queryAssignLogin = "SELECT ASSIGNMENT_ID 
FROM APPS.PER_ASSIGNMENTS_F
WHERE PERSON_ID = $emp_id AND EFFECTIVE_END_DATE > SYSDATE AND PRIMARY_FLAG='Y'";
$resultAssignLogin = oci_parse($con,$queryAssignLogin);
oci_execute($resultAssignLogin);
$rowAssignLogin = oci_fetch_row($resultAssignLogin);
$assignment_id_login = $rowAssignLogin[0]; 



//  Tampilkan isi grid untuk approval oleh Mgr Finance (dari tingkat 4 ke tingkat 5)
	
$queryCountMgrFin = "
	SELECT  COUNT( * )
	FROM    APPS.PER_ASSIGNMENTS_F PAF, APPS.PER_JOBS PJ, APPS.PER_POSITIONS PP,
			APPS.PER_PEOPLE_F PPF, APPS.PER_GRADES PG
	WHERE PAF.JOB_ID=PJ.JOB_ID
	AND PAF.POSITION_ID=PP.POSITION_ID
	AND PAF.EFFECTIVE_END_DATE > SYSDATE
	AND PRIMARY_FLAG = 'Y'
	AND PAF.PERSON_ID = PPF.PERSON_ID 
	AND PAF.GRADE_ID = PG.GRADE_ID
	AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y' AND PPF.EFFECTIVE_END_DATE > SYSDATE
	AND PJ.NAME LIKE '%FIN%' AND UPPER( PG.NAME ) LIKE '%MANAGER%'
	AND PPF.PERSON_ID = $emp_id
";

$resultCountMgrFin = oci_parse( $con, $queryCountMgrFin );
oci_execute( $resultCountMgrFin );
$rowCountMgrFin = oci_fetch_row( $resultCountMgrFin );
$countMgrFin = $rowCountMgrFin[0]; 



//  Cek apakah ada data untuk ditampilkan di grid untuk di-approve manager sebagai penanggung jawab

$queryCountApprMgr = "
	SELECT COUNT( * )
	FROM MJ.MJ_T_BS BS
	INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON BS.ASSIGNMENT_ID =PAF.ASSIGNMENT_ID 
	AND PAF.EFFECTIVE_END_DATE > SYSDATE AND PAF.PRIMARY_FLAG='Y'
	INNER JOIN APPS.PER_PEOPLE_F PPF ON PAF.PERSON_ID = PPF.PERSON_ID 
	AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y' AND PPF.EFFECTIVE_END_DATE > SYSDATE
	INNER JOIN APPS.PER_PEOPLE_F PPF2 ON BS.PENANGGUNG_JAWAB = PPF2.PERSON_ID 
	AND PPF2.CURRENT_EMPLOYEE_FLAG = 'Y' AND PPF2.EFFECTIVE_END_DATE > SYSDATE
	INNER JOIN APPS.HR_ORGANIZATION_UNITS HOU ON PAF.ORGANIZATION_ID = HOU.ORGANIZATION_ID
	INNER JOIN APPS.HR_ORGANIZATION_UNITS HOU2 ON BS.PERUSAHAAN_BS = HOU2.ORGANIZATION_ID
	INNER JOIN APPS.PER_JOBS PJ ON PAF.JOB_ID = PJ.JOB_ID
	INNER JOIN APPS.PER_POSITIONS POS ON PAF.POSITION_ID = POS.POSITION_ID
	INNER JOIN APPS.HR_LOCATIONS HL ON PAF.LOCATION_ID = HL.LOCATION_ID
	INNER JOIN APPS.per_grades PG ON PAF.GRADE_ID = PG.GRADE_ID
	WHERE 1=1 
	AND BS.PENANGGUNG_JAWAB = $emp_id 
	AND BS.TINGKAT = 0
	AND BS.STATUS = 'PROCESS' $queryfilter
	ORDER BY BS.ID
";

$resultCountApprMgr = oci_parse( $con, $queryCountApprMgr );
oci_execute( $resultCountApprMgr );
$rowCountApprMgr = oci_fetch_row( $resultCountApprMgr );
$ountApprMgr = $rowCountApprMgr[0]; 


//  Jika login adalah manager untuk approval dari tingkat 0 ke tingkat 1
if ( $countMgrFin != 0 )
{
	
	$queryLoginAcc = "
			SELECT PERUSAHAAN_ID 
			FROM MJ.MJ_M_APPROVAL_PINJAMAN 
			WHERE TIPE = 'BS' 
			AND STATUS = 'A' 
			AND ACCOUNTING_ID = $emp_id
		";
		
		$resultLoginAcc = oci_parse( $con, $queryLoginAcc );
		oci_execute( $resultLoginAcc );
		$rowLoginAcc = oci_fetch_row( $resultLoginAcc );
		$accOrgId = $rowLoginAcc[0]; 
		
		
		// Jika tidak dilakukan setting ACCOUNTING_ID 
		
		if ( $accOrgId == '' ) {
			
			$query = "
			SELECT BS.ID
					, BS.NO_BS
					, NVL( PPF.FULL_NAME, BS.NAMA_EXTERNAL ) PEMOHON
                    , NVL( HOU.NAME,
                            (   SELECT  NAME
                                FROM    APPS.HR_ORGANIZATION_UNITS
                                WHERE   ORGANIZATION_ID = BS.PERUSAHAAN_BS
                            )
                      ) PERUSAHAAN
					, REGEXP_SUBSTR(PJ.NAME, '[^.]+', 1, 2) AS DEPT
					, REGEXP_SUBSTR(POS.NAME, '[^.]+', 1, 3) AS JABATAN
					, HL.LOCATION_CODE PLANT
					, PG.NAME GRADE
					, BS.NOMINAL
					, BS.KETERANGAN
					, BS.TIPE
					, HOU2.NAME
					-- , TO_CHAR(BS.TGL_JT, 'DD-MON-YYYY')
					, TO_CHAR( BS.TGL_JT, 'YYYY-MM-DD' )
					, DECODE( BS.TINGKAT,
								0, 'Appr Mgr',
								1, 'Appr Mgr Fin',
								2, 'Checker',
								4, 'Appr Mgr Fin'
						) TINGKAT_DECODE
					, BS.TIPE_PENCAIRAN
			FROM MJ.MJ_T_BS BS
			LEFT JOIN APPS.PER_ASSIGNMENTS_F PAF ON BS.ASSIGNMENT_ID =PAF.ASSIGNMENT_ID 
				AND PAF.EFFECTIVE_END_DATE > SYSDATE AND PAF.PRIMARY_FLAG='Y'
			LEFT JOIN APPS.PER_PEOPLE_F PPF ON PAF.PERSON_ID = PPF.PERSON_ID 
				AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y' AND PPF.EFFECTIVE_END_DATE > SYSDATE
			LEFT JOIN APPS.PER_PEOPLE_F PPF2 ON BS.PENANGGUNG_JAWAB = PPF2.PERSON_ID 
				AND PPF2.CURRENT_EMPLOYEE_FLAG = 'Y' AND PPF2.EFFECTIVE_END_DATE > SYSDATE
			LEFT JOIN APPS.HR_ORGANIZATION_UNITS HOU ON PAF.ORGANIZATION_ID = HOU.ORGANIZATION_ID
			LEFT JOIN APPS.HR_ORGANIZATION_UNITS HOU2 ON BS.PERUSAHAAN_BS = HOU2.ORGANIZATION_ID
			LEFT JOIN APPS.PER_JOBS PJ ON PAF.JOB_ID = PJ.JOB_ID
			LEFT JOIN APPS.PER_POSITIONS POS ON PAF.POSITION_ID = POS.POSITION_ID
			LEFT JOIN APPS.HR_LOCATIONS HL ON PAF.LOCATION_ID = HL.LOCATION_ID
			LEFT JOIN APPS.per_grades PG ON PAF.GRADE_ID = PG.GRADE_ID
			WHERE 1 = 1 
			AND (
					-- BS.TINGKAT = 4 
                    (   BS.TINGKAT = 4
                        AND BS.ID IN (
                            SELECT  MTA.TRANSAKSI_ID
                            FROM    MJ.MJ_T_APPROVAL MTA
                            WHERE   1 = 1
                            AND     MTA.TRANSAKSI_KODE = 'BON'
                            AND     MTA.TINGKAT = 4
                            --AND     TO_CHAR( MTA.CREATED_DATE, 'YYYYMMDDHH24:MI' ) <= TO_CHAR( SYSDATE - 1, 'YYYYMMDD' ) || '09:00'
                            AND     MTA.ID = (
                                        SELECT  MAX( ID )
                                        FROM    MJ.MJ_T_APPROVAL
                                        WHERE   TRANSAKSI_KODE = 'BON'
                                        AND     TINGKAT = 4
                                        AND     TRANSAKSI_ID = MTA.TRANSAKSI_ID
                                    )
                        )  
						AND BS.PERUSAHAAN_BS not in 
							(	SELECT PERUSAHAAN_ID 
								FROM MJ.MJ_M_APPROVAL_PINJAMAN 
								WHERE TIPE = 'BS' 
								AND STATUS = 'A' 
								AND ACCOUNTING_ID is not null
							)
                    )
					or (
						BS.TINGKAT = 1 
						AND 	BS.STATUS = 'PROCESS'
						--AND     TO_CHAR( BS.CREATED_DATE, 'YYYYMMDDHH24:MI' ) <= TO_CHAR( SYSDATE - 1, 'YYYYMMDD' ) || '09:00'
						--AND     TO_CHAR( NVL( BS.LAST_UPDATED_DATE, BS.CREATED_DATE ), 'YYYYMMDDHH24:MI' ) <= TO_CHAR( SYSDATE - 1, 'YYYYMMDD' ) || '09:00'
						AND BS.PERUSAHAAN_BS not in 
							(	SELECT PERUSAHAAN_ID 
								FROM MJ.MJ_M_APPROVAL_PINJAMAN 
								WHERE TIPE = 'BS' 
								AND STATUS = 'A' 
								AND ACCOUNTING_ID is not null
							)
					)
					or (
						BS.TINGKAT = 0 AND BS.PENANGGUNG_JAWAB = $emp_id 
					)
				)
			--and HOU.ORGANIZATION_ID != $accOrgId 
			AND BS.STATUS != 'Disapproved'
			$queryfilter
			";

			//echo $query; exit;


			
		// Jika dilakukan setting ACCOUNTING_ID 
		
		} else 
		{
			
			$query = "
			SELECT BS.ID, BS.NO_BS
					, NVL( PPF.FULL_NAME, BS.NAMA_EXTERNAL ) PEMOHON
                    , NVL( HOU.NAME,
                            (   SELECT  NAME
                                FROM    APPS.HR_ORGANIZATION_UNITS
                                WHERE   ORGANIZATION_ID = BS.PERUSAHAAN_BS
                            )
                      ) PERUSAHAAN
					, REGEXP_SUBSTR(PJ.NAME, '[^.]+', 1, 2) AS DEPT
					, REGEXP_SUBSTR(POS.NAME, '[^.]+', 1, 3) AS JABATAN
					, HL.LOCATION_CODE PLANT
					, PG.NAME GRADE
					, BS.NOMINAL
					, BS.KETERANGAN
					, BS.TIPE
					, HOU2.NAME
					-- , TO_CHAR(BS.TGL_JT, 'DD-MON-YYYY')
					, TO_CHAR( BS.TGL_JT, 'YYYY-MM-DD' )
					, DECODE( BS.TINGKAT,
								0, 'Appr Mgr',
								1, 'Appr Mgr Fin',
								2, 'Checker',
								4, 'Appr Mgr Fin'
						) TINGKAT_DECODE
					, BS.TIPE_PENCAIRAN
			FROM MJ.MJ_T_BS BS
			LEFT JOIN APPS.PER_ASSIGNMENTS_F PAF ON BS.ASSIGNMENT_ID =PAF.ASSIGNMENT_ID 
				AND PAF.EFFECTIVE_END_DATE > SYSDATE AND PAF.PRIMARY_FLAG='Y'
			LEFT JOIN APPS.PER_PEOPLE_F PPF ON PAF.PERSON_ID = PPF.PERSON_ID 
				AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y' AND PPF.EFFECTIVE_END_DATE > SYSDATE
			LEFT JOIN APPS.PER_PEOPLE_F PPF2 ON BS.PENANGGUNG_JAWAB = PPF2.PERSON_ID 
				AND PPF2.CURRENT_EMPLOYEE_FLAG = 'Y' AND PPF2.EFFECTIVE_END_DATE > SYSDATE
			LEFT JOIN APPS.HR_ORGANIZATION_UNITS HOU ON PAF.ORGANIZATION_ID = HOU.ORGANIZATION_ID
			LEFT JOIN APPS.HR_ORGANIZATION_UNITS HOU2 ON BS.PERUSAHAAN_BS = HOU2.ORGANIZATION_ID
			LEFT JOIN APPS.PER_JOBS PJ ON PAF.JOB_ID = PJ.JOB_ID
			LEFT JOIN APPS.PER_POSITIONS POS ON PAF.POSITION_ID = POS.POSITION_ID
			LEFT JOIN APPS.HR_LOCATIONS HL ON PAF.LOCATION_ID = HL.LOCATION_ID
			LEFT JOIN APPS.per_grades PG ON PAF.GRADE_ID = PG.GRADE_ID
			WHERE 1 = 1 
			AND (
					-- BS.TINGKAT = 4 
                    (   BS.TINGKAT = 4
                        AND BS.ID IN (
                            SELECT  MTA.TRANSAKSI_ID
                            FROM    MJ.MJ_T_APPROVAL MTA
                            WHERE   1 = 1
                            AND     MTA.TRANSAKSI_KODE = 'BON'
                            AND     MTA.TINGKAT = 4
                            --AND     TO_CHAR( MTA.CREATED_DATE, 'YYYYMMDDHH24:MI' ) <= TO_CHAR( SYSDATE - 1, 'YYYYMMDD' ) || '09:00'
                            AND     MTA.ID = (
                                        SELECT  MAX( ID )
                                        FROM    MJ.MJ_T_APPROVAL
                                        WHERE   TRANSAKSI_KODE = 'BON'
                                        AND     TINGKAT = 4
                                        AND     TRANSAKSI_ID = MTA.TRANSAKSI_ID
                                    )
                        )                
                    )
					or (
						BS.TINGKAT = 1 
						AND 	BS.STATUS = 'PROCESS'
						--AND     TO_CHAR( BS.CREATED_DATE, 'YYYYMMDDHH24:MI' ) <= TO_CHAR( SYSDATE - 1, 'YYYYMMDD' ) || '09:00'
						--AND     TO_CHAR( NVL( BS.LAST_UPDATED_DATE, BS.CREATED_DATE ), 'YYYYMMDDHH24:MI' ) <= TO_CHAR( SYSDATE - 1, 'YYYYMMDD' ) || '09:00'
					)
					or (
						BS.TINGKAT = 0 AND BS.PENANGGUNG_JAWAB = $emp_id 
					)
				)
			AND (	BS.PERUSAHAAN_BS = $accOrgId or
					BS.PERUSAHAAN_BS in 
						(	SELECT PERUSAHAAN_ID FROM MJ.MJ_M_APPROVAL_PINJAMAN WHERE TIPE = 'BS' AND STATUS = 'A' 
							AND ACCOUNTING_ID is not null AND ACCOUNTING_ID = $emp_id
						) 
					or BS.PERUSAHAAN_BS not in 
					(	SELECT PERUSAHAAN_ID 
						FROM MJ.MJ_M_APPROVAL_PINJAMAN 
						WHERE TIPE = 'BS' 
						AND STATUS = 'A' 
						AND ACCOUNTING_ID is not null
					)
				)
			AND BS.STATUS != 'Disapproved'
			$queryfilter
			";
			//echo $query; exit;
		}
		
		// ORDER BY BS.ID
		
		
} elseif ( $ountApprMgr != 0 ) {


	// Query untuk menampilkan data dengan tingkat 0 untuk di-approve oleh Manager

	$query = "
	SELECT BS.ID, BS.NO_BS, PPF.FULL_NAME PEMOHON, HOU.NAME PERUSAHAAN
			, REGEXP_SUBSTR(PJ.NAME, '[^.]+', 1, 2) AS DEPT
			, POS.NAME AS JABATAN
			, HL.LOCATION_CODE PLANT
			, PG.NAME GRADE
			, BS.NOMINAL
			, BS.KETERANGAN
			, BS.TIPE
			, HOU2.NAME
			-- , TO_CHAR(BS.TGL_JT, 'DD-MON-YYYY')
			, TO_CHAR( BS.TGL_JT, 'YYYY-MM-DD' )
			, DECODE( BS.TINGKAT,
						0, 'Appr Mgr',
						1, 'Appr Mgr Fin',
						2, 'Checker',
						4, 'Appr Mgr Fin'
				) TINGKAT_DECODE
			, BS.TIPE_PENCAIRAN
	FROM MJ.MJ_T_BS BS
	INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON BS.ASSIGNMENT_ID =PAF.ASSIGNMENT_ID 
	AND PAF.EFFECTIVE_END_DATE > SYSDATE AND PAF.PRIMARY_FLAG='Y'
	INNER JOIN APPS.PER_PEOPLE_F PPF ON PAF.PERSON_ID = PPF.PERSON_ID 
	AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y' AND PPF.EFFECTIVE_END_DATE > SYSDATE
	INNER JOIN APPS.PER_PEOPLE_F PPF2 ON BS.PENANGGUNG_JAWAB = PPF2.PERSON_ID 
	AND PPF2.CURRENT_EMPLOYEE_FLAG = 'Y' AND PPF2.EFFECTIVE_END_DATE > SYSDATE
	INNER JOIN APPS.HR_ORGANIZATION_UNITS HOU ON PAF.ORGANIZATION_ID = HOU.ORGANIZATION_ID
	INNER JOIN APPS.HR_ORGANIZATION_UNITS HOU2 ON BS.PERUSAHAAN_BS = HOU2.ORGANIZATION_ID
	INNER JOIN APPS.PER_JOBS PJ ON PAF.JOB_ID = PJ.JOB_ID
	INNER JOIN APPS.PER_POSITIONS POS ON PAF.POSITION_ID = POS.POSITION_ID
	INNER JOIN APPS.HR_LOCATIONS HL ON PAF.LOCATION_ID = HL.LOCATION_ID
	INNER JOIN APPS.per_grades PG ON PAF.GRADE_ID = PG.GRADE_ID
	WHERE 1=1 
	AND BS.PENANGGUNG_JAWAB = $emp_id 
	AND BS.TINGKAT = 0
	AND BS.STATUS = 'PROCESS' $queryfilter
	";

// ORDER BY BS.ID




}


// echo $query; exit;


/* BAGIAN PIC CHECKER DITUTUP KARENA ADA FORM SENDIRI


//  Jika login adalah bukan manager, untuk login checker (dari tingkat 2 ke tingkat 3) dan Mgr Finance (dari tingkat 4 ke tingkat 5)

else {
	
	
	//  Tampilkan isi grid untuk approval oleh login checker (dari tingkat 2 ke tingkat 3)
	
	
	$queryLoginChecker = "
			SELECT PERUSAHAAN_ID 
			FROM MJ.MJ_M_APPROVAL_PINJAMAN 
			WHERE TIPE = 'BS' 
			AND STATUS = 'A' 
			AND CHECKER_ID = $emp_id
		";

	$resultLogin = oci_parse( $con, $queryLoginChecker );
	oci_execute( $resultLogin );
	$rowLogin = oci_fetch_row( $resultLogin );
	$checkerOrgId = $rowLogin[0];

	
	// Jika tidak dilakukan setting CHECKER_ID 
	
	if ( $checkerOrgId == '' ) 
	{
		
		$query = "
			SELECT BS.ID, BS.NO_BS, PPF.FULL_NAME PEMOHON, HOU.NAME PERUSAHAAN
					, REGEXP_SUBSTR(PJ.NAME, '[^.]+', 1, 2) AS DEPT
					, REGEXP_SUBSTR(POS.NAME, '[^.]+', 1, 3) AS JABATAN
					, HL.LOCATION_CODE PLANT
					, PG.NAME GRADE
					, BS.NOMINAL
					, BS.KETERANGAN
					, BS.TIPE, HOU2.NAME
					-- , TO_CHAR(BS.TGL_JT, 'DD-MON-YYYY')
					, TO_CHAR( BS.TGL_JT, 'YYYY-MM-DD' )
					, DECODE( BS.TINGKAT,
								0, 'Appr Mgr',
								1, 'Appr Mgr Fin',
								2, 'Checker',
								4, 'Appr Mgr Fin'
						) TINGKAT_DECODE
			FROM MJ.MJ_T_BS BS
			LEFT JOIN APPS.PER_ASSIGNMENTS_F PAF ON BS.ASSIGNMENT_ID =PAF.ASSIGNMENT_ID 
				AND PAF.EFFECTIVE_END_DATE > SYSDATE AND PAF.PRIMARY_FLAG='Y'
			LEFT JOIN APPS.PER_PEOPLE_F PPF ON PAF.PERSON_ID = PPF.PERSON_ID 
				AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y' AND PPF.EFFECTIVE_END_DATE > SYSDATE
			LEFT JOIN APPS.PER_PEOPLE_F PPF2 ON BS.PENANGGUNG_JAWAB = PPF2.PERSON_ID 
				AND PPF2.CURRENT_EMPLOYEE_FLAG = 'Y' AND PPF2.EFFECTIVE_END_DATE > SYSDATE
			LEFT JOIN APPS.HR_ORGANIZATION_UNITS HOU ON PAF.ORGANIZATION_ID = HOU.ORGANIZATION_ID
			LEFT JOIN APPS.HR_ORGANIZATION_UNITS HOU2 ON BS.PERUSAHAAN_BS = HOU2.ORGANIZATION_ID
			LEFT JOIN APPS.PER_JOBS PJ ON PAF.JOB_ID = PJ.JOB_ID
			LEFT JOIN APPS.PER_POSITIONS POS ON PAF.POSITION_ID = POS.POSITION_ID
			LEFT JOIN APPS.HR_LOCATIONS HL ON PAF.LOCATION_ID = HL.LOCATION_ID
			LEFT JOIN APPS.per_grades PG ON PAF.GRADE_ID = PG.GRADE_ID
			WHERE 1 = 1 
			AND BS.TINGKAT = 2 
			AND BS.PERUSAHAAN_BS not in 
				(	SELECT PERUSAHAAN_ID 
					FROM MJ.MJ_M_APPROVAL_PINJAMAN 
					WHERE TIPE = 'BS' 
					AND STATUS = 'A' 
					AND CHECKER_ID is not null
				)
			AND EXISTS
				(
					SELECT  1
					FROM    APPS.PER_ASSIGNMENTS_F PAF, APPS.PER_JOBS PJ, APPS.PER_POSITIONS PP,
							APPS.PER_PEOPLE_F PPF, APPS.PER_GRADES PG
					WHERE PAF.JOB_ID=PJ.JOB_ID
					AND PAF.POSITION_ID=PP.POSITION_ID
					AND PAF.EFFECTIVE_END_DATE > SYSDATE
					AND PRIMARY_FLAG = 'Y'
					AND PAF.PERSON_ID = PPF.PERSON_ID 
					AND PAF.GRADE_ID = PG.GRADE_ID
					AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y' AND PPF.EFFECTIVE_END_DATE > SYSDATE
					AND PJ.NAME LIKE '%FIN%' 
					AND PPF.PERSON_ID = $emp_id
				)
			$queryfilter
	";
	
	
	// Jika dilakukan setting CHECKER_ID 
	
	} else 
	{
		
		$query = "
			SELECT BS.ID, BS.NO_BS, PPF.FULL_NAME PEMOHON, HOU.NAME PERUSAHAAN
					, REGEXP_SUBSTR(PJ.NAME, '[^.]+', 1, 2) AS DEPT
					, REGEXP_SUBSTR(POS.NAME, '[^.]+', 1, 3) AS JABATAN
					, HL.LOCATION_CODE PLANT
					, PG.NAME GRADE
					, BS.NOMINAL
					, BS.KETERANGAN
					, BS.TIPE, HOU2.NAME
					--, TO_CHAR(BS.TGL_JT, 'DD-MON-YYYY')
					, TO_CHAR( BS.TGL_JT, 'YYYY-MM-DD' )
					, DECODE( BS.TINGKAT,
								0, 'Appr Mgr',
								1, 'Appr Mgr Fin',
								2, 'Checker',
								4, 'Appr Mgr Fin'
						) TINGKAT_DECODE
			FROM MJ.MJ_T_BS BS
			LEFT JOIN APPS.PER_ASSIGNMENTS_F PAF ON BS.ASSIGNMENT_ID =PAF.ASSIGNMENT_ID 
				AND PAF.EFFECTIVE_END_DATE > SYSDATE AND PAF.PRIMARY_FLAG='Y'
			LEFT JOIN APPS.PER_PEOPLE_F PPF ON PAF.PERSON_ID = PPF.PERSON_ID 
				AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y' AND PPF.EFFECTIVE_END_DATE > SYSDATE
			LEFT JOIN APPS.PER_PEOPLE_F PPF2 ON BS.PENANGGUNG_JAWAB = PPF2.PERSON_ID 
				AND PPF2.CURRENT_EMPLOYEE_FLAG = 'Y' AND PPF2.EFFECTIVE_END_DATE > SYSDATE
			LEFT JOIN APPS.HR_ORGANIZATION_UNITS HOU ON PAF.ORGANIZATION_ID = HOU.ORGANIZATION_ID
			LEFT JOIN APPS.HR_ORGANIZATION_UNITS HOU2 ON BS.PERUSAHAAN_BS = HOU2.ORGANIZATION_ID
			LEFT JOIN APPS.PER_JOBS PJ ON PAF.JOB_ID = PJ.JOB_ID
			LEFT JOIN APPS.PER_POSITIONS POS ON PAF.POSITION_ID = POS.POSITION_ID
			LEFT JOIN APPS.HR_LOCATIONS HL ON PAF.LOCATION_ID = HL.LOCATION_ID
			LEFT JOIN APPS.per_grades PG ON PAF.GRADE_ID = PG.GRADE_ID
			WHERE 1 = 1 
			AND BS.TINGKAT = 2 
			and (	BS.PERUSAHAAN_BS = NVL( $checkerOrgId, BS.PERUSAHAAN_BS )
					OR
					BS.PERUSAHAAN_BS in 
					(	SELECT PERUSAHAAN_ID 
						FROM MJ.MJ_M_APPROVAL_PINJAMAN 
						WHERE TIPE = 'BS' AND STATUS = 'A' AND CHECKER_ID is not null AND CHECKER_ID = $emp_id
					) 
				OR BS.PERUSAHAAN_BS not in 
					(	SELECT PERUSAHAAN_ID 
						FROM MJ.MJ_M_APPROVAL_PINJAMAN 
						WHERE TIPE = 'BS' 
						AND STATUS = 'A' 
						AND CHECKER_ID is not null
					)
			) 
			AND EXISTS
				(
					SELECT  1
					FROM    APPS.PER_ASSIGNMENTS_F PAF, APPS.PER_JOBS PJ, APPS.PER_POSITIONS PP,
							APPS.PER_PEOPLE_F PPF, APPS.PER_GRADES PG
					WHERE PAF.JOB_ID=PJ.JOB_ID
					AND PAF.POSITION_ID=PP.POSITION_ID
					AND PAF.EFFECTIVE_END_DATE > SYSDATE
					AND PRIMARY_FLAG = 'Y'
					AND PAF.PERSON_ID = PPF.PERSON_ID 
					AND PAF.GRADE_ID = PG.GRADE_ID
					AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y' AND PPF.EFFECTIVE_END_DATE > SYSDATE
					AND PJ.NAME LIKE '%FIN%' 
					AND PPF.PERSON_ID = $emp_id
				)
			$queryfilter
		";
	}
	
}

BAGIAN PIC CHECKER DITUTUP KARENA ADA FORM SENDIRI */



// echo $query; exit;




/*

// CODING LAMA OLEH DWP

//Checker (Tingkat 2)

$queryLogin = "SELECT COUNT(*) X FROM MJ.MJ_M_APPROVAL_PINJAMAN WHERE TIPE = 'BS' AND STATUS = 'A' AND CHECKER_ID = $emp_id";
//echo $queryLogin;exit;
$resultLogin = oci_parse($con,$queryLogin);
oci_execute($resultLogin);
$rowLogin = oci_fetch_row($resultLogin);
$countChecker = $rowLogin[0]; 

$queryLogin = "SELECT PERUSAHAAN_ID FROM MJ.MJ_M_APPROVAL_PINJAMAN WHERE TIPE = 'BS' AND STATUS = 'A' AND CHECKER_ID = $emp_id";
//echo $queryLogin;exit;
$resultLogin = oci_parse($con,$queryLogin);
oci_execute($resultLogin);
$rowLogin = oci_fetch_row($resultLogin);
$checkerOrgId = $rowLogin[0]; 

if ( $countChecker == 0 ) {
	
	// print_r( '$countChecker == 0' );
	
	$queryLogin = "SELECT CASE WHEN PJ.name like '%FIN%' THEN '2'
					ELSE '0' END AS TINGKAT
				, PPF.FULL_NAME, PP.NAME
			FROM APPS.PER_ASSIGNMENTS_F PAF, APPS.PER_JOBS PJ, APPS.PER_POSITIONS PP,
			PER_PEOPLE_F PPF, APPS.PER_GRADES PG
			WHERE PAF.JOB_ID=PJ.JOB_ID
				AND PAF.POSITION_ID=PP.POSITION_ID
				AND PAF.EFFECTIVE_END_DATE > SYSDATE
				AND PRIMARY_FLAG = 'Y'
				AND PAF.PERSON_ID = PPF.PERSON_ID 
				AND PAF.GRADE_ID = PG.GRADE_ID
				AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y' AND PPF.EFFECTIVE_END_DATE > SYSDATE
				AND PAF.ASSIGNMENT_ID = $assignment_id_login";
	$resultLogin = oci_parse($con,$queryLogin);
	oci_execute($resultLogin);
	$rowLogin = oci_fetch_row($resultLogin);
	$tingkat = $rowLogin[0]; 
	
	if($tingkat == 2){
		
		$query = "
		SELECT BS.ID, BS.NO_BS, PPF.FULL_NAME PEMOHON, HOU.NAME PERUSAHAAN
				, REGEXP_SUBSTR(PJ.NAME, '[^.]+', 1, 2) AS DEPT
				, REGEXP_SUBSTR(POS.NAME, '[^.]+', 1, 3) AS JABATAN
				, HL.LOCATION_CODE PLANT
				, PG.NAME GRADE
				, BS.NOMINAL
				, BS.KETERANGAN
				, BS.TIPE, HOU2.NAME
				-- , TO_CHAR(BS.TGL_JT, 'DD-MON-YYYY')
				, TO_CHAR( BS.TGL_JT, 'YYYY-MM-DD' )
		FROM MJ.MJ_T_BS BS
		LEFT JOIN APPS.PER_ASSIGNMENTS_F PAF ON BS.ASSIGNMENT_ID =PAF.ASSIGNMENT_ID 
			AND PAF.EFFECTIVE_END_DATE > SYSDATE AND PAF.PRIMARY_FLAG='Y'
		LEFT JOIN APPS.PER_PEOPLE_F PPF ON PAF.PERSON_ID = PPF.PERSON_ID 
			AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y' AND PPF.EFFECTIVE_END_DATE > SYSDATE
		LEFT JOIN APPS.PER_PEOPLE_F PPF2 ON BS.PENANGGUNG_JAWAB = PPF2.PERSON_ID 
			AND PPF2.CURRENT_EMPLOYEE_FLAG = 'Y' AND PPF2.EFFECTIVE_END_DATE > SYSDATE
		LEFT JOIN APPS.HR_ORGANIZATION_UNITS HOU ON PAF.ORGANIZATION_ID = HOU.ORGANIZATION_ID
		LEFT JOIN APPS.HR_ORGANIZATION_UNITS HOU2 ON BS.PERUSAHAAN_BS = HOU2.ORGANIZATION_ID
		LEFT JOIN APPS.PER_JOBS PJ ON PAF.JOB_ID = PJ.JOB_ID
		LEFT JOIN APPS.PER_POSITIONS POS ON PAF.POSITION_ID = POS.POSITION_ID
		LEFT JOIN APPS.HR_LOCATIONS HL ON PAF.LOCATION_ID = HL.LOCATION_ID
		LEFT JOIN APPS.per_grades PG ON PAF.GRADE_ID = PG.GRADE_ID
		WHERE 1=1 AND BS.TINGKAT = 2 and BS.PERUSAHAAN_BS not in 
			(SELECT PERUSAHAAN_ID FROM MJ.MJ_M_APPROVAL_PINJAMAN WHERE TIPE = 'BS' AND STATUS = 'A' AND CHECKER_ID is not null)
		$queryfilter
		";
	}
	
} else 
{
	// $countChecker != 0
	
	// print_r( '$countChecker != 0' );
	
	$query = "
	SELECT BS.ID, BS.NO_BS, PPF.FULL_NAME PEMOHON, HOU.NAME PERUSAHAAN
			, REGEXP_SUBSTR(PJ.NAME, '[^.]+', 1, 2) AS DEPT
			, REGEXP_SUBSTR(POS.NAME, '[^.]+', 1, 3) AS JABATAN
			, HL.LOCATION_CODE PLANT
			, PG.NAME GRADE
			, BS.NOMINAL
			, BS.KETERANGAN
			, BS.TIPE, HOU2.NAME
			--, TO_CHAR(BS.TGL_JT, 'DD-MON-YYYY')
			, TO_CHAR( BS.TGL_JT, 'YYYY-MM-DD' )
    FROM MJ.MJ_T_BS BS
    LEFT JOIN APPS.PER_ASSIGNMENTS_F PAF ON BS.ASSIGNMENT_ID =PAF.ASSIGNMENT_ID 
		AND PAF.EFFECTIVE_END_DATE > SYSDATE AND PAF.PRIMARY_FLAG='Y'
    LEFT JOIN APPS.PER_PEOPLE_F PPF ON PAF.PERSON_ID = PPF.PERSON_ID 
		AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y' AND PPF.EFFECTIVE_END_DATE > SYSDATE
    LEFT JOIN APPS.PER_PEOPLE_F PPF2 ON BS.PENANGGUNG_JAWAB = PPF2.PERSON_ID 
		AND PPF2.CURRENT_EMPLOYEE_FLAG = 'Y' AND PPF2.EFFECTIVE_END_DATE > SYSDATE
    LEFT JOIN APPS.HR_ORGANIZATION_UNITS HOU ON PAF.ORGANIZATION_ID = HOU.ORGANIZATION_ID
	LEFT JOIN APPS.HR_ORGANIZATION_UNITS HOU2 ON BS.PERUSAHAAN_BS = HOU2.ORGANIZATION_ID
    LEFT JOIN APPS.PER_JOBS PJ ON PAF.JOB_ID = PJ.JOB_ID
    LEFT JOIN APPS.PER_POSITIONS POS ON PAF.POSITION_ID = POS.POSITION_ID
    LEFT JOIN APPS.HR_LOCATIONS HL ON PAF.LOCATION_ID = HL.LOCATION_ID
    LEFT JOIN APPS.per_grades PG ON PAF.GRADE_ID = PG.GRADE_ID
    WHERE 1=1 
	AND BS.TINGKAT = 2 
	and (	BS.PERUSAHAAN_BS = $checkerOrgId or
			BS.PERUSAHAAN_BS in 
			(	SELECT PERUSAHAAN_ID 
				FROM MJ.MJ_M_APPROVAL_PINJAMAN 
				WHERE TIPE = 'BS' AND STATUS = 'A' AND CHECKER_ID is not null AND CHECKER_ID = $emp_id
			) 
	or BS.PERUSAHAAN_BS not in (SELECT PERUSAHAAN_ID FROM MJ.MJ_M_APPROVAL_PINJAMAN WHERE TIPE = 'BS' AND STATUS = 'A' AND CHECKER_ID is not null)) 
	$queryfilter
	";
}

// echo $query; exit;


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//Accounting (Tingkat 4)

$queryLogin = "SELECT COUNT(*) X FROM MJ.MJ_M_APPROVAL_PINJAMAN WHERE TIPE = 'BS' AND STATUS = 'A' AND ACCOUNTING_ID = $emp_id";
//echo $queryLogin;exit;
$resultLogin = oci_parse($con,$queryLogin);
oci_execute($resultLogin);
$rowLogin = oci_fetch_row($resultLogin);
$countAcc = $rowLogin[0]; 

$queryLogin = "SELECT PERUSAHAAN_ID FROM MJ.MJ_M_APPROVAL_PINJAMAN WHERE TIPE = 'BS' AND STATUS = 'A' AND ACCOUNTING_ID = $emp_id";
//echo $queryLogin;exit;
$resultLogin = oci_parse($con,$queryLogin);
oci_execute($resultLogin);
$rowLogin = oci_fetch_row($resultLogin);
$accOrgId = $rowLogin[0]; 



if ( $countAcc == 0 ) {
	
	// echo '$countAcc == 0';
	
	$queryLogin = "SELECT CASE WHEN PJ.name like '%FIN%' AND PG.name like '%Manager%'  THEN '4'
					ELSE '0' END AS TINGKAT
				, PPF.FULL_NAME, PP.NAME
			FROM APPS.PER_ASSIGNMENTS_F PAF, APPS.PER_JOBS PJ, APPS.PER_POSITIONS PP,
			PER_PEOPLE_F PPF, APPS.PER_GRADES PG
			WHERE PAF.JOB_ID=PJ.JOB_ID
				AND PAF.POSITION_ID=PP.POSITION_ID
				AND PAF.EFFECTIVE_END_DATE > SYSDATE
				AND PRIMARY_FLAG = 'Y'
				AND PAF.PERSON_ID = PPF.PERSON_ID 
				AND PAF.GRADE_ID = PG.GRADE_ID
				AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y' AND PPF.EFFECTIVE_END_DATE > SYSDATE
				AND PAF.ASSIGNMENT_ID = $assignment_id_login";
	$resultLogin = oci_parse($con,$queryLogin);
	oci_execute($resultLogin);
	$rowLogin = oci_fetch_row($resultLogin);
	$tingkat = $rowLogin[0]; 
	
	if ( $tingkat == 4 ) {
		
		if( $query != '' ) {
			
			$query .= "
			UNION 
			SELECT BS.ID
			, BS.NO_BS
			, NVL( PPF.FULL_NAME, BS.NAMA_EXTERNAL ) PEMOHON
			, HOU.NAME PERUSAHAAN
			, REGEXP_SUBSTR(PJ.NAME, '[^.]+', 1, 2) AS DEPT
			, REGEXP_SUBSTR(POS.NAME, '[^.]+', 1, 3) AS JABATAN
			, HL.LOCATION_CODE PLANT
			, PG.NAME GRADE
			, BS.NOMINAL
			, BS.KETERANGAN
			, BS.TIPE
			, HOU2.NAME
			-- , TO_CHAR(BS.TGL_JT, 'DD-MON-YYYY')
			, TO_CHAR( BS.TGL_JT, 'YYYY-MM-DD' )
			FROM MJ.MJ_T_BS BS
			LEFT JOIN APPS.PER_ASSIGNMENTS_F PAF ON BS.ASSIGNMENT_ID =PAF.ASSIGNMENT_ID 
				AND PAF.EFFECTIVE_END_DATE > SYSDATE AND PAF.PRIMARY_FLAG='Y'
			LEFT JOIN APPS.PER_PEOPLE_F PPF ON PAF.PERSON_ID = PPF.PERSON_ID 
				AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y' AND PPF.EFFECTIVE_END_DATE > SYSDATE
			LEFT JOIN APPS.PER_PEOPLE_F PPF2 ON BS.PENANGGUNG_JAWAB = PPF2.PERSON_ID 
				AND PPF2.CURRENT_EMPLOYEE_FLAG = 'Y' AND PPF2.EFFECTIVE_END_DATE > SYSDATE
			LEFT JOIN APPS.HR_ORGANIZATION_UNITS HOU ON PAF.ORGANIZATION_ID = HOU.ORGANIZATION_ID
			LEFT JOIN APPS.HR_ORGANIZATION_UNITS HOU2 ON BS.PERUSAHAAN_BS = HOU2.ORGANIZATION_ID
			LEFT JOIN APPS.PER_JOBS PJ ON PAF.JOB_ID = PJ.JOB_ID
			LEFT JOIN APPS.PER_POSITIONS POS ON PAF.POSITION_ID = POS.POSITION_ID
			LEFT JOIN APPS.HR_LOCATIONS HL ON PAF.LOCATION_ID = HL.LOCATION_ID
			LEFT JOIN APPS.per_grades PG ON PAF.GRADE_ID = PG.GRADE_ID
			WHERE 1=1 AND (
				-- BS.TINGKAT = 4 
				(   BS.TINGKAT = 4
					AND BS.ID IN (
						SELECT  TRANSAKSI_ID
						FROM    MJ.MJ_T_APPROVAL
						WHERE   1 = 1
						AND     TRANSAKSI_KODE = 'BON'
						AND     TINGKAT = 4
						AND     TO_CHAR( CREATED_DATE, 'YYYYMMDDHH24:MI' ) <= TO_CHAR( SYSDATE - 1, 'YYYYMMDD' ) || '09:00'
						)                
				)
				or (
					BS.TINGKAT = 1 
					AND 	BS.STATUS = 'PROCESS'
					AND     TO_CHAR( BS.CREATED_DATE, 'YYYYMMDDHH24:MI' ) <= TO_CHAR( SYSDATE - 1, 'YYYYMMDD' ) || '09:00'
					AND     TO_CHAR( NVL( BS.LAST_UPDATED_DATE, BS.CREATED_DATE ), 'YYYYMMDDHH24:MI' ) <= TO_CHAR( SYSDATE - 1, 'YYYYMMDD' ) || '09:00'
				)
			)
			--and HOU.ORGANIZATION_ID != $accOrgId 
			and BS.PERUSAHAAN_BS not in (SELECT PERUSAHAAN_ID FROM MJ.MJ_M_APPROVAL_PINJAMAN WHERE TIPE = 'BS' AND STATUS = 'A' AND ACCOUNTING_ID is not null)
			$queryfilter
			";
			
		} else {
			
			$query = "SELECT BS.ID
				, BS.NO_BS
				, NVL( PPF.FULL_NAME, BS.NAMA_EXTERNAL ) PEMOHON
				, HOU.NAME PERUSAHAAN
				, REGEXP_SUBSTR(PJ.NAME, '[^.]+', 1, 2) AS DEPT
				, REGEXP_SUBSTR(POS.NAME, '[^.]+', 1, 3) AS JABATAN
				, HL.LOCATION_CODE PLANT
				, PG.NAME GRADE
				, BS.NOMINAL
				, BS.KETERANGAN
				, BS.TIPE
				, HOU2.NAME
				-- , TO_CHAR(BS.TGL_JT, 'DD-MON-YYYY')
				, TO_CHAR( BS.TGL_JT, 'YYYY-MM-DD' )
			FROM MJ.MJ_T_BS BS
			LEFT  JOIN APPS.PER_ASSIGNMENTS_F PAF ON BS.ASSIGNMENT_ID =PAF.ASSIGNMENT_ID 
				AND PAF.EFFECTIVE_END_DATE > SYSDATE AND PAF.PRIMARY_FLAG='Y'
			LEFT  JOIN APPS.PER_PEOPLE_F PPF ON PAF.PERSON_ID = PPF.PERSON_ID 
				AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y' AND PPF.EFFECTIVE_END_DATE > SYSDATE
			LEFT  JOIN APPS.PER_PEOPLE_F PPF2 ON BS.PENANGGUNG_JAWAB = PPF2.PERSON_ID 
				AND PPF2.CURRENT_EMPLOYEE_FLAG = 'Y' AND PPF2.EFFECTIVE_END_DATE > SYSDATE
			LEFT  JOIN APPS.HR_ORGANIZATION_UNITS HOU ON PAF.ORGANIZATION_ID = HOU.ORGANIZATION_ID
			LEFT  JOIN APPS.HR_ORGANIZATION_UNITS HOU2 ON BS.PERUSAHAAN_BS = HOU2.ORGANIZATION_ID
			LEFT  JOIN APPS.PER_JOBS PJ ON PAF.JOB_ID = PJ.JOB_ID
			LEFT  JOIN APPS.PER_POSITIONS POS ON PAF.POSITION_ID = POS.POSITION_ID
			LEFT  JOIN APPS.HR_LOCATIONS HL ON PAF.LOCATION_ID = HL.LOCATION_ID
			LEFT  JOIN APPS.per_grades PG ON PAF.GRADE_ID = PG.GRADE_ID
			WHERE 1=1 AND (
				-- BS.TINGKAT = 4 
				(   BS.TINGKAT = 4
					AND BS.ID IN (
						SELECT  TRANSAKSI_ID
						FROM    MJ.MJ_T_APPROVAL
						WHERE   1 = 1
						AND     TRANSAKSI_KODE = 'BON'
						AND     TINGKAT = 4
						AND     TO_CHAR( CREATED_DATE, 'YYYYMMDDHH24:MI' ) <= TO_CHAR( SYSDATE - 1, 'YYYYMMDD' ) || '09:00'
						)                
				)
				or (
					BS.TINGKAT = 1 
					AND 	BS.STATUS = 'PROCESS'
					AND     TO_CHAR( BS.CREATED_DATE, 'YYYYMMDDHH24:MI' ) <= TO_CHAR( SYSDATE - 1, 'YYYYMMDD' ) || '09:00'
					AND     TO_CHAR( NVL( BS.LAST_UPDATED_DATE, BS.CREATED_DATE ), 'YYYYMMDDHH24:MI' ) <= TO_CHAR( SYSDATE - 1, 'YYYYMMDD' ) || '09:00'
				)
			) --and HOU.ORGANIZATION_ID != $accOrgId 
			and BS.PERUSAHAAN_BS not in (SELECT PERUSAHAAN_ID FROM MJ.MJ_M_APPROVAL_PINJAMAN WHERE TIPE = 'BS' AND STATUS = 'A' AND ACCOUNTING_ID is not null)
			$queryfilter
			ORDER BY BS.ID";
			
		}
		
	}
	
} else 
{
	// $countAcc != 0
	
	// echo '$countAcc == 0';
	
	if ( $query != '' ) {
		
		$query .= "
		union 
		SELECT BS.ID
				, BS.NO_BS
				, NVL( PPF.FULL_NAME, BS.NAMA_EXTERNAL ) PEMOHON
				, HOU.NAME PERUSAHAAN
				, REGEXP_SUBSTR(PJ.NAME, '[^.]+', 1, 2) AS DEPT
				, REGEXP_SUBSTR(POS.NAME, '[^.]+', 1, 3) AS JABATAN
				, HL.LOCATION_CODE PLANT
				, PG.NAME GRADE
				, BS.NOMINAL
				, BS.KETERANGAN
				, BS.TIPE
				, HOU2.NAME
				-- , TO_CHAR(BS.TGL_JT, 'DD-MON-YYYY')
				, TO_CHAR( BS.TGL_JT, 'YYYY-MM-DD' )
		FROM MJ.MJ_T_BS BS
		LEFT JOIN APPS.PER_ASSIGNMENTS_F PAF ON BS.ASSIGNMENT_ID =PAF.ASSIGNMENT_ID 
			AND PAF.EFFECTIVE_END_DATE > SYSDATE AND PAF.PRIMARY_FLAG='Y'
		LEFT JOIN APPS.PER_PEOPLE_F PPF ON PAF.PERSON_ID = PPF.PERSON_ID 
			AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y' AND PPF.EFFECTIVE_END_DATE > SYSDATE
		LEFT JOIN APPS.PER_PEOPLE_F PPF2 ON BS.PENANGGUNG_JAWAB = PPF2.PERSON_ID 
			AND PPF2.CURRENT_EMPLOYEE_FLAG = 'Y' AND PPF2.EFFECTIVE_END_DATE > SYSDATE
		LEFT JOIN APPS.HR_ORGANIZATION_UNITS HOU ON PAF.ORGANIZATION_ID = HOU.ORGANIZATION_ID
		LEFT JOIN APPS.HR_ORGANIZATION_UNITS HOU2 ON BS.PERUSAHAAN_BS = HOU2.ORGANIZATION_ID
		LEFT JOIN APPS.PER_JOBS PJ ON PAF.JOB_ID = PJ.JOB_ID
		LEFT JOIN APPS.PER_POSITIONS POS ON PAF.POSITION_ID = POS.POSITION_ID
		LEFT JOIN APPS.HR_LOCATIONS HL ON PAF.LOCATION_ID = HL.LOCATION_ID
		LEFT JOIN APPS.per_grades PG ON PAF.GRADE_ID = PG.GRADE_ID
		WHERE 1=1 AND (
			-- BS.TINGKAT = 4 
			(   BS.TINGKAT = 4
				AND BS.ID IN (
					SELECT  TRANSAKSI_ID
					FROM    MJ.MJ_T_APPROVAL
					WHERE   1 = 1
					AND     TRANSAKSI_KODE = 'BON'
					AND     TINGKAT = 4
					AND     TO_CHAR( CREATED_DATE, 'YYYYMMDDHH24:MI' ) <= TO_CHAR( SYSDATE - 1, 'YYYYMMDD' ) || '09:00'
					)                
			)
			or (
				BS.TINGKAT = 1 
				AND 	BS.STATUS = 'PROCESS'
				AND     TO_CHAR( BS.CREATED_DATE, 'YYYYMMDDHH24:MI' ) <= TO_CHAR( SYSDATE - 1, 'YYYYMMDD' ) || '09:00'
				AND     TO_CHAR( NVL( BS.LAST_UPDATED_DATE, BS.CREATED_DATE ), 'YYYYMMDDHH24:MI' ) <= TO_CHAR( SYSDATE - 1, 'YYYYMMDD' ) || '09:00'
			)
		) --and HOU.ORGANIZATION_ID = $accOrgId 
		and (BS.PERUSAHAAN_BS = $accOrgId or
		BS.PERUSAHAAN_BS in 
			(SELECT PERUSAHAAN_ID FROM MJ.MJ_M_APPROVAL_PINJAMAN WHERE TIPE = 'BS' AND STATUS = 'A' 
				AND ACCOUNTING_ID is not null AND ACCOUNTING_ID = $emp_id) 
				or BS.PERUSAHAAN_BS not in 
					(SELECT PERUSAHAAN_ID FROM MJ.MJ_M_APPROVAL_PINJAMAN WHERE TIPE = 'BS' AND STATUS = 'A' AND ACCOUNTING_ID is not null)) 
		$queryfilter
		";
		
	} else {
		
		$query = "
		SELECT BS.ID, BS.NO_BS
				, NVL( PPF.FULL_NAME, BS.NAMA_EXTERNAL ) PEMOHON
				, HOU.NAME PERUSAHAAN
				, REGEXP_SUBSTR(PJ.NAME, '[^.]+', 1, 2) AS DEPT
				, REGEXP_SUBSTR(POS.NAME, '[^.]+', 1, 3) AS JABATAN
				, HL.LOCATION_CODE PLANT
				, PG.NAME GRADE
				, BS.NOMINAL
				, BS.KETERANGAN
				, BS.TIPE
				, HOU2.NAME
				-- , TO_CHAR(BS.TGL_JT, 'DD-MON-YYYY')
				, TO_CHAR( BS.TGL_JT, 'YYYY-MM-DD' )
		FROM MJ.MJ_T_BS BS
		LEFT JOIN APPS.PER_ASSIGNMENTS_F PAF ON BS.ASSIGNMENT_ID =PAF.ASSIGNMENT_ID 
			AND PAF.EFFECTIVE_END_DATE > SYSDATE AND PAF.PRIMARY_FLAG='Y'
		LEFT JOIN APPS.PER_PEOPLE_F PPF ON PAF.PERSON_ID = PPF.PERSON_ID 
			AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y' AND PPF.EFFECTIVE_END_DATE > SYSDATE
		LEFT JOIN APPS.PER_PEOPLE_F PPF2 ON BS.PENANGGUNG_JAWAB = PPF2.PERSON_ID 
			AND PPF2.CURRENT_EMPLOYEE_FLAG = 'Y' AND PPF2.EFFECTIVE_END_DATE > SYSDATE
		LEFT JOIN APPS.HR_ORGANIZATION_UNITS HOU ON PAF.ORGANIZATION_ID = HOU.ORGANIZATION_ID
		LEFT JOIN APPS.HR_ORGANIZATION_UNITS HOU2 ON BS.PERUSAHAAN_BS = HOU2.ORGANIZATION_ID
		LEFT JOIN APPS.PER_JOBS PJ ON PAF.JOB_ID = PJ.JOB_ID
		LEFT JOIN APPS.PER_POSITIONS POS ON PAF.POSITION_ID = POS.POSITION_ID
		LEFT JOIN APPS.HR_LOCATIONS HL ON PAF.LOCATION_ID = HL.LOCATION_ID
		LEFT JOIN APPS.per_grades PG ON PAF.GRADE_ID = PG.GRADE_ID
		WHERE 1=1 AND (
			-- BS.TINGKAT = 4 
			(   BS.TINGKAT = 4
				AND BS.ID IN (
					SELECT  TRANSAKSI_ID
					FROM    MJ.MJ_T_APPROVAL
					WHERE   1 = 1
					AND     TRANSAKSI_KODE = 'BON'
					AND     TINGKAT = 4
					AND     TO_CHAR( CREATED_DATE, 'YYYYMMDDHH24:MI' ) <= TO_CHAR( SYSDATE - 1, 'YYYYMMDD' ) || '09:00'
					)                
			)
			or (
				BS.TINGKAT = 1 
				AND 	BS.STATUS = 'PROCESS'
				AND     TO_CHAR( BS.CREATED_DATE, 'YYYYMMDDHH24:MI' ) <= TO_CHAR( SYSDATE - 1, 'YYYYMMDD' ) || '09:00'
				AND     TO_CHAR( NVL( BS.LAST_UPDATED_DATE, BS.CREATED_DATE ), 'YYYYMMDDHH24:MI' ) <= TO_CHAR( SYSDATE - 1, 'YYYYMMDD' ) || '09:00'
			)
		) --and HOU.ORGANIZATION_ID = $accOrgId 
		and (BS.PERUSAHAAN_BS = $accOrgId or
		BS.PERUSAHAAN_BS in 
			(SELECT PERUSAHAAN_ID FROM MJ.MJ_M_APPROVAL_PINJAMAN WHERE TIPE = 'BS' AND STATUS = 'A' 
			AND ACCOUNTING_ID is not null AND ACCOUNTING_ID = $emp_id) 
			or BS.PERUSAHAAN_BS not in 
				(SELECT PERUSAHAAN_ID FROM MJ.MJ_M_APPROVAL_PINJAMAN WHERE TIPE = 'BS' AND STATUS = 'A' AND ACCOUNTING_ID is not null)) 
		$queryfilter
		ORDER BY BS.ID";
		
	}
	
}

// echo $query; exit;


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//Manager (Tingkat 0)
$queryLogin = "SELECT CASE WHEN PJ.name like '%FIN%' THEN '2'
				WHEN PJ.name like '%FIN%' AND PG.name like '%Manager%'  THEN '4'
				ELSE '0' END AS TINGKAT
			, PPF.FULL_NAME, PP.NAME
		FROM APPS.PER_ASSIGNMENTS_F PAF, APPS.PER_JOBS PJ, APPS.PER_POSITIONS PP,
		PER_PEOPLE_F PPF, APPS.PER_GRADES PG
		WHERE PAF.JOB_ID=PJ.JOB_ID
			AND PAF.POSITION_ID=PP.POSITION_ID
			AND PAF.EFFECTIVE_END_DATE > SYSDATE
			AND PRIMARY_FLAG = 'Y'
			AND PAF.PERSON_ID = PPF.PERSON_ID 
			AND PAF.GRADE_ID = PG.GRADE_ID
			AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y' AND PPF.EFFECTIVE_END_DATE > SYSDATE
			AND PAF.ASSIGNMENT_ID = $assignment_id_login";
$resultLogin = oci_parse($con,$queryLogin);
oci_execute($resultLogin);
$rowLogin = oci_fetch_row($resultLogin);
$tingkat = $rowLogin[0]; 
//$tingkat = 4; 
//echo $tingkat;exit;

if ( $tingkat == 0 ) 
{
	
	// echo '$tingkat == 0';
	
	$query = "
	SELECT BS.ID, BS.NO_BS, PPF.FULL_NAME PEMOHON, HOU.NAME PERUSAHAAN
			, REGEXP_SUBSTR(PJ.NAME, '[^.]+', 1, 2) AS DEPT
			, POS.NAME AS JABATAN
			, HL.LOCATION_CODE PLANT
			, PG.NAME GRADE
			, BS.NOMINAL
			, BS.KETERANGAN
			, BS.TIPE
			, HOU2.NAME
			-- , TO_CHAR(BS.TGL_JT, 'DD-MON-YYYY')
			, TO_CHAR( BS.TGL_JT, 'YYYY-MM-DD' )
	FROM MJ.MJ_T_BS BS
	INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON BS.ASSIGNMENT_ID =PAF.ASSIGNMENT_ID 
	AND PAF.EFFECTIVE_END_DATE > SYSDATE AND PAF.PRIMARY_FLAG='Y'
	INNER JOIN PER_PEOPLE_F PPF ON PAF.PERSON_ID = PPF.PERSON_ID 
	AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y' AND PPF.EFFECTIVE_END_DATE > SYSDATE
	INNER JOIN PER_PEOPLE_F PPF2 ON BS.PENANGGUNG_JAWAB = PPF2.PERSON_ID 
	AND PPF2.CURRENT_EMPLOYEE_FLAG = 'Y' AND PPF2.EFFECTIVE_END_DATE > SYSDATE
	INNER JOIN APPS.HR_ORGANIZATION_UNITS HOU ON PAF.ORGANIZATION_ID = HOU.ORGANIZATION_ID
	INNER JOIN APPS.HR_ORGANIZATION_UNITS HOU2 ON BS.PERUSAHAAN_BS = HOU2.ORGANIZATION_ID
	INNER JOIN APPS.PER_JOBS PJ ON PAF.JOB_ID = PJ.JOB_ID
	INNER JOIN APPS.PER_POSITIONS POS ON PAF.POSITION_ID = POS.POSITION_ID
	INNER JOIN APPS.HR_LOCATIONS HL ON PAF.LOCATION_ID = HL.LOCATION_ID
	INNER JOIN APPS.per_grades PG ON PAF.GRADE_ID = PG.GRADE_ID
	WHERE 1=1 and BS.PENANGGUNG_JAWAB = $emp_id 
	AND BS.TINGKAT = 0
	AND BS.STATUS = 'PROCESS' $queryfilter
	ORDER BY BS.ID
	";
	
	// AND BS.TINGKAT IN ( 0, 1 ) 
	
} else 
{
	
	// $tingkat != 0
	
	// echo '$tingkat != 0';
	
	if ( $query != '') {
		
		$query .= "
		union 
		SELECT BS.ID, BS.NO_BS, PPF.FULL_NAME PEMOHON, HOU.NAME PERUSAHAAN
				, REGEXP_SUBSTR(PJ.NAME, '[^.]+', 1, 2) AS DEPT
				, POS.NAME AS JABATAN
				, HL.LOCATION_CODE PLANT
				, PG.NAME GRADE
				, BS.NOMINAL
				, BS.KETERANGAN
				, BS.TIPE
				, HOU2.NAME
				-- , TO_CHAR(BS.TGL_JT, 'DD-MON-YYYY')
				, TO_CHAR( BS.TGL_JT, 'YYYY-MM-DD' )
		FROM MJ.MJ_T_BS BS
		INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON BS.ASSIGNMENT_ID =PAF.ASSIGNMENT_ID 
		AND PAF.EFFECTIVE_END_DATE > SYSDATE AND PAF.PRIMARY_FLAG='Y'
		INNER JOIN APPS.PER_PEOPLE_F PPF ON PAF.PERSON_ID = PPF.PERSON_ID 
		AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y' AND PPF.EFFECTIVE_END_DATE > SYSDATE
		INNER JOIN APPS.PER_PEOPLE_F PPF2 ON BS.PENANGGUNG_JAWAB = PPF2.PERSON_ID 
		AND PPF2.CURRENT_EMPLOYEE_FLAG = 'Y' AND PPF2.EFFECTIVE_END_DATE > SYSDATE
		INNER JOIN APPS.HR_ORGANIZATION_UNITS HOU ON PAF.ORGANIZATION_ID = HOU.ORGANIZATION_ID
		INNER JOIN APPS.HR_ORGANIZATION_UNITS HOU2 ON BS.PERUSAHAAN_BS = HOU2.ORGANIZATION_ID
		INNER JOIN APPS.PER_JOBS PJ ON PAF.JOB_ID = PJ.JOB_ID
		INNER JOIN APPS.PER_POSITIONS POS ON PAF.POSITION_ID = POS.POSITION_ID
		INNER JOIN APPS.HR_LOCATIONS HL ON PAF.LOCATION_ID = HL.LOCATION_ID
		INNER JOIN APPS.per_grades PG ON PAF.GRADE_ID = PG.GRADE_ID
		WHERE 1=1 and BS.PENANGGUNG_JAWAB = $emp_id 
		AND BS.TINGKAT = 0
		AND BS.STATUS = 'PROCESS' $queryfilter
		ORDER BY ID";
		
		// and BS.TINGKAT IN (0,1) 
		
	} else {
		
		$query = "
		SELECT BS.ID, BS.NO_BS, PPF.FULL_NAME PEMOHON, HOU.NAME PERUSAHAAN
				, REGEXP_SUBSTR(PJ.NAME, '[^.]+', 1, 2) AS DEPT
				, POS.NAME AS JABATAN
				, HL.LOCATION_CODE PLANT
				, PG.NAME GRADE
				, BS.NOMINAL
				, BS.KETERANGAN
				, BS.TIPE
				, HOU2.NAME
				-- , TO_CHAR(BS.TGL_JT, 'DD-MON-YYYY')
				, TO_CHAR( BS.TGL_JT, 'YYYY-MM-DD' )
		FROM MJ.MJ_T_BS BS
		INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON BS.ASSIGNMENT_ID =PAF.ASSIGNMENT_ID 
		AND PAF.EFFECTIVE_END_DATE > SYSDATE AND PAF.PRIMARY_FLAG='Y'
		INNER JOIN APPS.PER_PEOPLE_F PPF ON PAF.PERSON_ID = PPF.PERSON_ID 
		AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y' AND PPF.EFFECTIVE_END_DATE > SYSDATE
		INNER JOIN APPS.PER_PEOPLE_F PPF2 ON BS.PENANGGUNG_JAWAB = PPF2.PERSON_ID 
		AND PPF2.CURRENT_EMPLOYEE_FLAG = 'Y' AND PPF2.EFFECTIVE_END_DATE > SYSDATE
		INNER JOIN APPS.HR_ORGANIZATION_UNITS HOU ON PAF.ORGANIZATION_ID = HOU.ORGANIZATION_ID
		INNER JOIN APPS.HR_ORGANIZATION_UNITS HOU2 ON BS.PERUSAHAAN_BS = HOU2.ORGANIZATION_ID
		INNER JOIN APPS.PER_JOBS PJ ON PAF.JOB_ID = PJ.JOB_ID
		INNER JOIN APPS.PER_POSITIONS POS ON PAF.POSITION_ID = POS.POSITION_ID
		INNER JOIN APPS.HR_LOCATIONS HL ON PAF.LOCATION_ID = HL.LOCATION_ID
		INNER JOIN APPS.per_grades PG ON PAF.GRADE_ID = PG.GRADE_ID
		WHERE 1=1 and BS.PENANGGUNG_JAWAB = $emp_id 
		and BS.TINGKAT = 0
		AND BS.STATUS = 'PROCESS' $queryfilter
		ORDER BY BS.ID";
		
		// and BS.TINGKAT IN (0,1) 
		
	}
}

*/




// ECHO $query;EXIT;


$result = oci_parse($con,$query);
oci_execute($result);
while($row = oci_fetch_row($result))
{
	$TransID=$row[0];
	
	// echo $row[0]; exit;
	
	$record = array();
	$record['HD_ID']=$row[0];
	$record['DATA_NO_BS']=$row[1];
	$record['DATA_PEMOHON']=$row[2];
	$record['DATA_PERUSAHAAN']=$row[3];
	$record['DATA_DEPT']=$row[4];
	$record['DATA_JABATAN']=$row[5];
	$record['DATA_PLANT']=$row[6];
	$record['DATA_GRADE']=$row[7];
	$record['DATA_NOMINAL']=$row[8];
	$record['DATA_KETERANGAN']=$row[9];
	$record['DATA_TIPE_BS']=$row[10];
	$record['DATA_PERUSAHAAN_BS']=$row[11];
	$record['DATA_TGL_JT']=$row[12];
	
	$record['DATA_TINGKAT_DECODE']=$row[13];
	$record['DATA_TIPE_PENCAIRAN']=$row[14];
	
	$record['DATA_ALASAN']='';
	
	$queryAtt = "SELECT TRANSAKSI_ID, FILENAME, FILESIZE, FILETYPE, USERNAME, TO_CHAR(CREATEDDATE, 'YYYY-MM-DD')
	FROM MJ.MJ_M_UPLOAD
	WHERE APP_ID=" . APPCODE . " AND TRANSAKSI_ID=$TransID AND TRANSAKSI_KODE IN ('BON', 'BONADMIN')";
	$resultAtt = oci_parse($con, $queryAtt);
	oci_execute($resultAtt);
	$doccount=0;
	$dataAtt='';
	while($rowAtt = oci_fetch_row($resultAtt))
	{
		$vTransID=$rowAtt[0];
		$vFilename=$rowAtt[1];
		$vFilesize=$rowAtt[2];
		$vFiletype=$rowAtt[3];
		$vFileuser=$rowAtt[4];
		$vFiledate=$rowAtt[5];
		$ekstensi	= end(explode(".", $vFilename));
		$docattachment = "<a href= " . PATHAPP . "/upload/BS/" . $vFileuser.md5($vFiledate).$vFilesize.md5($vFilename).".".$ekstensi . " target=_blank>" . $vFilename . "</a>";
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
	
	$queryAtt = "SELECT TRANSAKSI_ID, FILENAME, FILESIZE, FILETYPE, USERNAME, TO_CHAR(CREATEDDATE, 'YYYY-MM-DD')
	FROM MJ.MJ_M_UPLOAD
	WHERE APP_ID=" . APPCODE . " AND TRANSAKSI_ID=$TransID AND TRANSAKSI_KODE = 'BONMGRHRD'";
	//echo $queryAtt.'-';
	$resultAtt = oci_parse($con, $queryAtt);
	oci_execute($resultAtt);
	$doccount=0;
	$dataAtt='';
	while($rowAtt = oci_fetch_row($resultAtt))
	{
		$vTransID=$rowAtt[0];
		$vFilename=$rowAtt[1];
		$vFilesize=$rowAtt[2];
		$vFiletype=$rowAtt[3];
		$vFileuser=$rowAtt[4];
		$vFiledate=$rowAtt[5];
		$ekstensi	= end(explode(".", $vFilename));
		if ($vFiledate < '2019-02-02') {
			$docattachment = "<a href= " . PATHAPP . "/upload/BS/" . $vFileuser.md5($vFiledate).$vFilesize.md5($vFilename).".".$ekstensi . " target=_blank>" . $vFilename . "</a>";
		}else{
			$docattachment = "<a href= " . PATHAPP . "/upload/BS/" . ''.md5($vFiledate).$vFilesize.md5($vFilename).".".$ekstensi . " target=_blank>" . $vFilename . "</a>";
		}
		
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
	$record['DATA_ATTACHMENT_HRD']=$dataAtt;
	$data[]=$record;
	$count++;
}

if ($count==0)
{
	$data="";
}
 $totalrow = 0;

	$result = array('success' => true,
					'results' => $totalrow,
					'rows' => $data
				);
	echo json_encode($result);
?>