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

$status = "";
$tglfrom = "";
$tglto = "";
$plant = "";
$noDok = "";
$tingkat = 0;
$queryfilter = ""; 


$hari="";
$bulan="";
$tahun=""; 


if ( isset( $_GET['param_cb_noreq'] ) )
{
	$v_cb_noreq = $_GET[ 'param_cb_noreq' ];
	
	if( $v_cb_noreq != '' ){
		
		$queryfilter .= " AND MTM.NO_REQUEST = '$v_cb_noreq'" ;
		
	}
}


if ( isset( $_GET['param_dept'] ) )
{
	$v_dept = $_GET[ 'param_dept' ];
	
	if( $v_dept != '' ) {
		
		$queryfilter .= " AND PJ.NAME like '%$v_dept%'";
		
	}
}


if ( isset( $_GET['param_nama'] ) )
{
	$v_nama = $_GET[ 'param_nama' ];
	
	if( $v_nama != '' ) {
		
		$queryfilter .= " AND PPF.PERSON_ID like '%$v_nama%'";
		
	}
}


if ( isset( $_GET['param_tipe'] ) )
{
	$v_tipe = $_GET[ 'param_tipe' ];
	
	if( $v_tipe != '' ) {
		
		$queryfilter .= " AND MTM.TIPE = '$v_tipe'";
		
	}
}


if ( isset( $_GET['param_status'] ) )
{
	$status = $_GET[ 'param_status' ];
	
	if( $status != '' ) {
		
		$queryfilter .= " AND MTM.STATUS_DOK = '$status'";
		
	}
}


if ( isset( $_GET['param_tglfrom'] ) && isset( $_GET['param_tglto'] ) )
{
	$v_tglfrom = $_GET[ 'param_tglfrom' ];
	$v_tglto = $_GET[ 'param_tglto' ];
	
	if( $v_tglfrom != '' && $v_tglto != '' ) {
		
		$queryfilter .= " AND MTM.TGL_EFFECTIVE BETWEEN TO_DATE( '$v_tglfrom', 'DD/MM/YY' ) AND TO_DATE( '$v_tglto', 'DD/MM/YY' )";
		
	}
}


$query_role = "
SELECT  R.NAMA_RULE, U.EMP_ID, J.JOB_ID, A.APP_NAME, U.USERNAME AKUN, E.FULL_NAME VC_NAME, U.ID, E.EMAIL_ADDRESS VC_EMAIL, ID_USER_RULE, 
		UR.UPDATE_DATE, DECODE(UR.AKTIF, 'Y','Active','Inactive') ACTIVE_DESC, UR.ID_RULE, UR.AKTIF, J.NAME AS VC_DEPT_NAME
FROM    MJ.MJ_SYS_USER_RULE UR, MJ.MJ_SYS_RULE R,
        MJ.MJ_M_USER U, APPS.PER_PEOPLE_F E, MJ.MJ_SYS_APP A, APPS.PER_ALL_ASSIGNMENTS_F ASS, APPS.PER_JOBS J
WHERE   UR.ID_RULE = R.ID_RULE AND UR.ID_USER = U.ID AND U.EMP_ID = E.PERSON_ID AND R.APP_ID = A.APP_ID 
AND     E.PERSON_ID = ASS.PERSON_ID AND SYSDATE BETWEEN ASS.EFFECTIVE_START_DATE AND ASS.EFFECTIVE_END_DATE
AND     ASS.JOB_ID = J.JOB_ID(+) AND SYSDATE BETWEEN E.EFFECTIVE_START_DATE AND E.EFFECTIVE_END_DATE AND ASS.PRIMARY_FLAG = 'Y'
AND     A.APP_NAME = 'MJBHRD'
AND     U.EMP_ID = $emp_id
";

// AND     U.EMP_ID = 38170
//WHERE 1=1 AND (MTS.PEMBUAT='$emp_name' OR MTS.PERSON_ID='$emp_id') $queryfilter";


$result = oci_parse( $con, $query_role );
oci_execute( $result );
$row = oci_fetch_row( $result );

$vNamaRule = $row[0];
$vDeptId = $row[2];

// $vEmpId = $row[1];


if ( $vNamaRule == 'Administrator' || $vDeptId == 26066 ) {

	$queryfilter .= "";

} else {
	
	$queryfilter .= " AND ( PPF.PERSON_ID = $emp_id OR MTM.CREATED_BY = $emp_id ) ";
	
}



$query = "
SELECT  MTM.NO_REQUEST, MTM.TIPE, MTM.STATUS_KARYAWAN, MTM.SIFAT_PERUBAHAN
        , PPF.FULL_NAME EMP_NAME, PJ.NAME DEPT_NAME_OLD
        , PPF1.FIRST_NAME || ' ' || PPF1.LAST_NAME NAMA_MGR_LAMA
        , PVG.NAME GRADE, PP.NAME POSITION_OLD, HL.LOCATION_CODE LOCATION
        , PJ1.NAME DEPT_BARU
        , PPF2.FIRST_NAME || ' ' || PPF2.LAST_NAME NAMA_MGR_BARU
        ,   (
                SELECT  DISTINCT NAME
                FROM    APPS.PER_GRADES
                WHERE   GRADE_ID = MTM.GRADE_BARU_ID
            ) GRADE_BARU
        , PP2.NAME POSITION_BARU
        , HL2.LOCATION_CODE LOCATION_BARU
        , MTM.ALASAN, MTM.KETERANGAN, MTM.TGL_EFFECTIVE, MTM.STATUS_DOK
        , MTM.ID
       ,   CASE
            WHEN MTM.TINGKAT = 0 THEN NULL
            WHEN MTM.TINGKAT = 1 THEN PPF1.FIRST_NAME || ' ' || PPF1.LAST_NAME
            WHEN MTM.TINGKAT = 2 THEN PPF2.FIRST_NAME || ' ' || PPF2.LAST_NAME
            WHEN MTM.TINGKAT = 3 THEN 
                (   SELECT  FIRST_NAME || ' ' || LAST_NAME
                    FROM    APPS.PER_PEOPLE_F
                    WHERE   PERSON_ID = 
                    (   SELECT  DISTINCT EMP_ID
                        FROM    MJ_T_APPROVAL
                        WHERE   TRANSAKSI_KODE = 'MPD'
                        AND     STATUS <> 'Disapproved'
                        AND     TRANSAKSI_ID = MTM.ID
                        AND     TINGKAT = 3
                    )
                )
            END APPROVAL_TERAKHIR
        ,   CASE
            WHEN MTM.TINGKAT = 0 THEN PPF1.FIRST_NAME || ' ' || PPF1.LAST_NAME
            WHEN MTM.TINGKAT = 1 THEN PPF2.FIRST_NAME || ' ' || PPF2.LAST_NAME
            WHEN MTM.TINGKAT = 2 THEN 'HRD Mgr'
            WHEN MTM.TINGKAT = 3 THEN NULL
            END APPROVAL_SELANJUTNYA
        , DECODE(
            (   SELECT  MAX( TINGKAT )
                FROM    MJ_T_APPROVAL
                WHERE   TRANSAKSI_KODE = 'MPD'
                AND     STATUS = 'Disapproved'
                AND     TRANSAKSI_ID = MTM.ID
                AND     MTM.STATUS_DOK = 'Disapproved'
                GROUP   BY TRANSAKSI_ID
            ), 1, PPF1.FIRST_NAME || ' ' || PPF1.LAST_NAME
             , 2, PPF2.FIRST_NAME || ' ' || PPF2.LAST_NAME
             , 3, ( SELECT  FIRST_NAME || ' ' || LAST_NAME
                    FROM    APPS.PER_PEOPLE_F
                    WHERE   PERSON_ID = 
                            (   SELECT  DISTINCT EMP_ID
                                FROM    MJ_T_APPROVAL
                                WHERE   TRANSAKSI_KODE = 'MPD'
                                AND     STATUS = 'Disapproved'
                                AND     TRANSAKSI_ID = MTM.ID
                                AND     TINGKAT = 3
                            )
                  )
            ) PIC_DISAPPROVE
       ,   (    SELECT  KETERANGAN
                FROM    MJ_T_APPROVAL
                WHERE   TRANSAKSI_KODE = 'MPD'
                AND     STATUS = 'Disapproved'
                AND     TRANSAKSI_ID = MTM.ID
                AND     MTM.STATUS_DOK = 'Disapproved'
                AND     ID =
                        (   SELECT  MAX( ID )
                            FROM    MJ_T_APPROVAL
                            WHERE   TRANSAKSI_KODE = 'MPD'
                            AND     STATUS = 'Disapproved'
                            AND     TRANSAKSI_ID = MTM.ID
                        )
            ) KETERANGAN_DISAPPROVE
        , MTM.CREATED_BY
        , PPF.EMAIL_ADDRESS, PPF.PERSON_ID
        , PJ.JOB_ID, PP.POSITION_ID, HL.LOCATION_ID, PVG.GRADE_ID
        , MTM.TINGKAT
        , ( SELECT  MAX( TINGKAT )
            FROM    MJ_T_APPROVAL
            WHERE   TRANSAKSI_KODE = 'MPD'
            AND     STATUS = 'Disapproved'
            AND     TRANSAKSI_ID = MTM.ID
            AND     MTM.STATUS_DOK = 'Disapproved'
            GROUP   BY TRANSAKSI_ID, STATUS
          ) TINGKAT_DISAPPROVE
FROM    APPS.PER_PEOPLE_F PPF
INNER JOIN  MJ_T_MUTASI MTM ON KARYAWAN_ID = PPF.PERSON_ID
LEFT JOIN  APPS.PER_ASSIGNMENTS_F PAF ON PAF.PERSON_ID = PPF.PERSON_ID
LEFT JOIN   APPS.PER_JOBS PJ ON PJ.JOB_ID = MTM.DEPT_LAMA_ID
LEFT JOIN   APPS.PER_POSITIONS PP ON PP.POSITION_ID = MTM.POSISI_LAMA_ID
LEFT JOIN   APPS.HR_LOCATIONS HL ON HL.LOCATION_ID = MTM.LOKASI_LAMA_ID
LEFT JOIN   APPS.PER_GRADES PVG ON PVG.GRADE_ID = MTM.GRADE_LAMA_ID
INNER JOIN  APPS.PER_PEOPLE_F PPF1 ON PPF1.PERSON_ID = MTM.MGR_LAMA AND PPF1.EFFECTIVE_END_DATE > SYSDATE
LEFT JOIN   APPS.PER_JOBS PJ1 ON PJ1.JOB_ID = MTM.DEPT_BARU_ID
INNER JOIN  APPS.PER_PEOPLE_F PPF2 ON PPF2.PERSON_ID = MTM.MGR_BARU AND PPF2.EFFECTIVE_END_DATE > SYSDATE
LEFT JOIN   APPS.PER_POSITIONS PP2 ON PP2.POSITION_ID = MTM.POSISI_BARU_ID
LEFT JOIN   APPS.HR_LOCATIONS HL2 ON HL2.LOCATION_ID = MTM.LOKASI_BARU_ID
WHERE   PPF.EFFECTIVE_END_DATE > SYSDATE
AND     PAF.PRIMARY_FLAG = 'Y'
AND 	PAF.EFFECTIVE_END_DATE > SYSDATE
$queryfilter
ORDER BY MTM.NO_REQUEST
";


// WHERE 1=1 AND (MTS.PEMBUAT='$emp_name' OR MTSD.PERSON_ID='$emp_id') $queryfilter";

// echo $query; exit;

$result = oci_parse($con,$query);
oci_execute($result);
$count = 0;
while($row = oci_fetch_row($result))
{
	$record = array();
	$record['NO_REQUEST']=$row[0];
	$record['TIPE']=$row[1];
	$record['STATUS_KARYAWAN']=$row[2];
	$record['SIFAT_PERUBAHAN']=$row[3];
	$record['NAMA_KARYAWAN']=$row[4];
	$record['DEPARTEMEN']=$row[5];
	$record['MGR']=$row[6];
	$record['GRADE']=$row[7];
	$record['POSITION']=$row[8];
	$record['LOCATION']=$row[9];
	
	$record['DEPT_BARU']=$row[10];
	$record['MGR_BARU']=$row[11];
	$record['GRADE_BARU']=$row[12];
	$record['POSITION_BARU']=$row[13];
	$record['LOCATION_BARU']=$row[14];
	$record['ALASAN']=$row[15];
	$record['KETERANGAN']=$row[16];
	$record['TGL_EFEKTIF']=$row[17];
	$record['STATUS_DOK']=$row[18];
	
	$TransID = $row[19];
	
	$record['LAST_APPROVER']=$row[20];
	$record['NEXT_APPROVER']=$row[21];
	$record['PIC_DISAPPROVE']=$row[22];
	$record['NOTE_DISAPPROVE']=$row[23];
	
	$queryAtt = "SELECT TRANSAKSI_ID, FILENAME, FILESIZE, FILETYPE, USERNAME, TO_CHAR(CREATEDDATE, 'YYYY-MM-DD')
	FROM MJ.MJ_TEMP_UPLOAD
	WHERE APP_ID=" . APPCODE . " AND TRANSAKSI_KODE = 'MPD' AND TRANSAKSI_ID = $TransID";
	
	
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
		$docattachment = "<a href= " . PATHAPP . "/upload/mutasi/" . $vFileuser.md5($vFiledate).$vFilesize.md5($vFilename).".".$ekstensi . ">" . $vFilename . "</a>";
		
		if($doccount==0){
			$dataAtt = $docattachment;
		} else {
			$dataAtt .= ", " . $docattachment;
		}
		
		//echo $docattachment;
		
		$doccount++;
	}
	$record['DATA_ATTACHMENT']=$dataAtt;
	
	
	$data[]=$record;
	$count++;
	
}

if ($count==0)
{
	$data="";
}
 echo json_encode($data);
?>