<?PHP
 include 'koneksi.php'; //Koneksi ke database

$tglskr=date('Y-m-d'); 
$username="";
$userpass="";
$passuser="";
$data="Username or Password doesn't exist.";

if(isset($_POST['username']) || isset($_POST['userpass'])){
	$username=$_POST['username'];
	$userpass=$_POST['userpass'];
	$username=str_replace("'","--","$username");
	$userpass=str_replace("'","--","$userpass");
	strpos($username, ';')?die('username tidak boleh mengandung ";"'):'';
	strpos($userpass, ';')?die('password tidak boleh mengandung ";"'):'';
	//$username = '';
	if($username!='')
	{
		$query = "SELECT DISTINCT 
		D.ORGANIZATION_ID IO_ID
		FROM MJ.MJ_M_USER A 
		INNER JOIN APPS.PER_PEOPLE_F B ON A.EMP_ID = B.PERSON_ID
		INNER JOIN APPS.PER_ASSIGNMENTS_F D ON A.EMP_ID = D.PERSON_ID
		WHERE UPPER(A.USERNAME) = '$username'
		AND UPPER(A.USERPASS)='$userpass'
		AND A.STATUS = 'A' 
		AND D.PRIMARY_FLAG='Y'
		AND B.EFFECTIVE_END_DATE > SYSDATE
		AND D.EFFECTIVE_END_DATE > SYSDATE";
		$result = oci_parse($con, $query);
		oci_execute($result);
		$row = oci_fetch_row($result);
		$jumlah = $row[0];
		if ($jumlah > 0)
		{	
			$query = "SELECT COUNT(-1)
			FROM MJ.MJ_M_USER A 
			INNER JOIN APPS.PER_PEOPLE_F B ON A.EMP_ID = B.PERSON_ID
			INNER JOIN APPS.PER_ASSIGNMENTS_F D ON A.EMP_ID = D.PERSON_ID
			INNER JOIN APPS.HR_ORGANIZATION_UNITS E ON D.LOCATION_ID = E.LOCATION_ID AND D.ORGANIZATION_ID<>E.ORGANIZATION_ID
			INNER JOIN APPS.HR_ORGANIZATION_INFORMATION F ON E.ORGANIZATION_ID=F.ORGANIZATION_ID AND D.ORGANIZATION_ID = F.ORG_INFORMATION3
			INNER JOIN APPS.HR_ORGANIZATION_UNITS G ON D.ORGANIZATION_ID = G.ORGANIZATION_ID
			INNER JOIN APPS.HR_LOCATIONS H ON D.LOCATION_ID = H.LOCATION_ID
			WHERE UPPER(A.USERNAME) = '$username'
			AND UPPER(A.USERPASS)='$userpass'
			AND A.STATUS = 'A' 
			AND D.PRIMARY_FLAG='Y'
			AND B.EFFECTIVE_END_DATE > SYSDATE
			AND D.EFFECTIVE_END_DATE > SYSDATE
			AND F.ORG_INFORMATION_CONTEXT='Accounting Information'";
			$result = oci_parse($con, $query);
			oci_execute($result);
			$row = oci_fetch_row($result);
			$jumlah = $row[0];
			if ($jumlah > 0)
			{
				$result = oci_parse($con, "SELECT DISTINCT A.ID USER_ID
				, A.USERNAME
				, A.USERPASS
				, A.EMP_ID
				, B.FULL_NAME EMP_NAME
				, E.ORGANIZATION_ID IO_ID
				, E.NAME IO_NAME
				, D.LOCATION_ID LOC_ID
				, H.LOCATION_CODE
				, D.ORGANIZATION_ID ORG_ID
				, G.NAME ORG_NAME
                , REGEXP_SUBSTR(J.NAME, '[^.]+', 1, 2) AS DEPT
                , POS.NAME AS JABATAN
                , PG.NAME
				FROM MJ.MJ_M_USER A 
				INNER JOIN APPS.PER_PEOPLE_F B ON A.EMP_ID = B.PERSON_ID
				INNER JOIN APPS.PER_ASSIGNMENTS_F D ON A.EMP_ID = D.PERSON_ID
				INNER JOIN APPS.HR_ORGANIZATION_UNITS E ON D.LOCATION_ID = E.LOCATION_ID AND D.ORGANIZATION_ID<>E.ORGANIZATION_ID
				INNER JOIN APPS.HR_ORGANIZATION_INFORMATION F ON E.ORGANIZATION_ID=F.ORGANIZATION_ID AND D.ORGANIZATION_ID = F.ORG_INFORMATION3
				INNER JOIN APPS.HR_ORGANIZATION_UNITS G ON D.ORGANIZATION_ID = G.ORGANIZATION_ID
				INNER JOIN APPS.HR_LOCATIONS H ON D.LOCATION_ID = H.LOCATION_ID
                INNER JOIN APPS.PER_JOBS J ON D.JOB_ID=J.JOB_ID
                INNER JOIN APPS.PER_POSITIONS POS ON D.POSITION_ID=POS.POSITION_ID
                INNER JOIN APPS.per_grades PG ON D.GRADE_ID = PG.GRADE_ID
				WHERE UPPER(A.USERNAME) = '$username'
				AND UPPER(A.USERPASS)='$userpass'
				AND A.STATUS = 'A' 
				AND D.PRIMARY_FLAG='Y'
				AND B.EFFECTIVE_END_DATE > SYSDATE
				AND D.EFFECTIVE_END_DATE > SYSDATE
				--AND D.PRIMARY_FLAG='Y'
				AND F.ORG_INFORMATION_CONTEXT='Accounting Information'");
				oci_execute($result);
				$row = oci_fetch_row($result);
				session_start();
				$_SESSION[APP]['user_id']=$row[0];
				$_SESSION[APP]['username']=$row[1];
				$_SESSION[APP]['emp_id']=$row[3];
				$_SESSION[APP]['emp_name']=$row[4];
				$_SESSION[APP]['io_id']=$row[5];
				$_SESSION[APP]['io_name']=$row[6];
				$_SESSION[APP]['loc_id']=$row[7];
				$_SESSION[APP]['loc_name']=$row[8];
				$_SESSION[APP]['org_id']=$row[9];
				$_SESSION[APP]['org_name']=$row[10];
				$_SESSION[APP]['dept_name']=$row[11];
				$_SESSION[APP]['pos_name']=$row[12];
				$_SESSION[APP]['grade']=$row[13];
				$data = "sukses";
			} else {
				$query = "SELECT COUNT(-1)
				FROM MJ.MJ_M_USER A 
				INNER JOIN APPS.PER_PEOPLE_F B ON A.EMP_ID = B.PERSON_ID
				INNER JOIN APPS.PER_ASSIGNMENTS_F D ON A.EMP_ID = D.PERSON_ID
				INNER JOIN APPS.HR_ORGANIZATION_UNITS E ON D.LOCATION_ID = E.LOCATION_ID AND D.ORGANIZATION_ID<>E.ORGANIZATION_ID
				INNER JOIN APPS.HR_ORGANIZATION_UNITS G ON D.ORGANIZATION_ID = G.ORGANIZATION_ID
				INNER JOIN APPS.HR_LOCATIONS H ON D.LOCATION_ID = H.LOCATION_ID
				WHERE UPPER(A.USERNAME) = '$username'
				AND UPPER(A.USERPASS)='$userpass'
				AND A.STATUS = 'A' 
				AND D.PRIMARY_FLAG='Y'
				AND B.EFFECTIVE_END_DATE > SYSDATE
				AND D.EFFECTIVE_END_DATE > SYSDATE";
				$result = oci_parse($con, $query);
				oci_execute($result);
				$row = oci_fetch_row($result);
				$jumlah = $row[0];
				if ($jumlah > 0)
				{
					$result = oci_parse($con, "SELECT DISTINCT A.ID USER_ID
					, A.USERNAME
					, A.USERPASS
					, A.EMP_ID
					, B.FULL_NAME EMP_NAME
					, 0 IO_ID
					, 'MJG' IO_NAME
					, D.LOCATION_ID LOC_ID
					, H.LOCATION_CODE
					, D.ORGANIZATION_ID ORG_ID
					, G.NAME ORG_NAME
					, REGEXP_SUBSTR(J.NAME, '[^.]+', 1, 2) AS DEPT
					, POS.NAME AS JABATAN
					, PG.NAME
					FROM MJ.MJ_M_USER A 
					INNER JOIN APPS.PER_PEOPLE_F B ON A.EMP_ID = B.PERSON_ID
					INNER JOIN APPS.PER_ASSIGNMENTS_F D ON A.EMP_ID = D.PERSON_ID
					INNER JOIN APPS.HR_ORGANIZATION_UNITS E ON D.LOCATION_ID = E.LOCATION_ID AND D.ORGANIZATION_ID<>E.ORGANIZATION_ID
					INNER JOIN APPS.HR_ORGANIZATION_UNITS G ON D.ORGANIZATION_ID = G.ORGANIZATION_ID
					INNER JOIN APPS.HR_LOCATIONS H ON D.LOCATION_ID = H.LOCATION_ID
					INNER JOIN APPS.PER_JOBS J ON D.JOB_ID=J.JOB_ID
					INNER JOIN APPS.PER_POSITIONS POS ON D.POSITION_ID=POS.POSITION_ID
					INNER JOIN APPS.per_grades PG ON D.GRADE_ID = PG.GRADE_ID
					WHERE UPPER(A.USERNAME) = '$username'
					AND UPPER(A.USERPASS)='$userpass'
					AND A.STATUS = 'A' 
					AND D.PRIMARY_FLAG='Y'
					--AND D.PRIMARY_FLAG='Y'
					AND B.EFFECTIVE_END_DATE > SYSDATE
					AND D.EFFECTIVE_END_DATE > SYSDATE");
					oci_execute($result);
					$row = oci_fetch_row($result);
					session_start();
					$_SESSION[APP]['user_id']=$row[0];
					$_SESSION[APP]['username']=$row[1];
					$_SESSION[APP]['emp_id']=$row[3];
					$_SESSION[APP]['emp_name']=$row[4];
					$_SESSION[APP]['io_id']=$row[5];
					$_SESSION[APP]['io_name']=$row[6];
					$_SESSION[APP]['loc_id']=$row[7];
					$_SESSION[APP]['loc_name']=$row[8];
					$_SESSION[APP]['org_id']=$row[9];
					$_SESSION[APP]['org_name']=$row[10];
					$_SESSION[APP]['dept_name']=$row[11];
					$_SESSION[APP]['pos_name']=$row[12];
					$_SESSION[APP]['grade']=$row[13];
					$data = "sukses";
				}
			}
		} else {
			$query = "SELECT COUNT(-1)
			FROM MJ.MJ_M_USER A 
			INNER JOIN APPS.PER_PEOPLE_F B ON A.EMP_ID = B.PERSON_ID
			INNER JOIN APPS.PER_ASSIGNMENTS_F D ON A.EMP_ID = D.PERSON_ID
			--INNER JOIN APPS.HR_ORGANIZATION_UNITS E ON D.LOCATION_ID = E.LOCATION_ID AND D.ORGANIZATION_ID<>E.ORGANIZATION_ID
			--INNER JOIN APPS.HR_ORGANIZATION_UNITS G ON D.ORGANIZATION_ID = G.ORGANIZATION_ID
			INNER JOIN APPS.HR_LOCATIONS H ON D.LOCATION_ID = H.LOCATION_ID
			WHERE UPPER(A.USERNAME) = '$username'
			AND UPPER(A.USERPASS)='$userpass'
			AND A.STATUS = 'A' 
			AND D.PRIMARY_FLAG='Y'
			AND B.EFFECTIVE_END_DATE > SYSDATE
			AND D.EFFECTIVE_END_DATE > SYSDATE";
			$result = oci_parse($con, $query);
			oci_execute($result);
			$row = oci_fetch_row($result);
			$jumlah = $row[0];
			if ($jumlah > 0)
			{
				$result = oci_parse($con, "SELECT DISTINCT A.ID USER_ID
				, A.USERNAME
				, A.USERPASS
				, A.EMP_ID
				, B.FULL_NAME EMP_NAME
				, 0 IO_ID
				, 'MJG' IO_NAME
				, D.LOCATION_ID LOC_ID
				, H.LOCATION_CODE
				, D.ORGANIZATION_ID ORG_ID
				, 'Merak Jaya Group' ORG_NAME
				, REGEXP_SUBSTR(J.NAME, '[^.]+', 1, 2) AS DEPT
				, POS.NAME AS JABATAN
				, PG.NAME
				FROM MJ.MJ_M_USER A 
				INNER JOIN APPS.PER_PEOPLE_F B ON A.EMP_ID = B.PERSON_ID
				INNER JOIN APPS.PER_ASSIGNMENTS_F D ON A.EMP_ID = D.PERSON_ID
				--INNER JOIN APPS.HR_ORGANIZATION_UNITS E ON D.LOCATION_ID = E.LOCATION_ID AND D.ORGANIZATION_ID<>E.ORGANIZATION_ID
				--INNER JOIN APPS.HR_ORGANIZATION_UNITS G ON D.ORGANIZATION_ID = G.ORGANIZATION_ID
				INNER JOIN APPS.HR_LOCATIONS H ON D.LOCATION_ID = H.LOCATION_ID
				INNER JOIN APPS.PER_JOBS J ON D.JOB_ID=J.JOB_ID
				INNER JOIN APPS.PER_POSITIONS POS ON D.POSITION_ID=POS.POSITION_ID
				INNER JOIN APPS.per_grades PG ON D.GRADE_ID = PG.GRADE_ID
				WHERE UPPER(A.USERNAME) = '$username'
				AND UPPER(A.USERPASS)='$userpass'
				AND A.STATUS = 'A' 
				AND D.PRIMARY_FLAG='Y'
				--AND D.PRIMARY_FLAG='Y'
				AND B.EFFECTIVE_END_DATE > SYSDATE
				AND D.EFFECTIVE_END_DATE > SYSDATE");
				oci_execute($result);
				$row = oci_fetch_row($result);
				session_start();
				$_SESSION[APP]['user_id']=$row[0];
				$_SESSION[APP]['username']=$row[1];
				$_SESSION[APP]['emp_id']=$row[3];
				$_SESSION[APP]['emp_name']=$row[4];
				$_SESSION[APP]['io_id']=$row[5];
				$_SESSION[APP]['io_name']=$row[6];
				$_SESSION[APP]['loc_id']=$row[7];
				$_SESSION[APP]['loc_name']=$row[8];
				$_SESSION[APP]['org_id']=$row[9];
				$_SESSION[APP]['org_name']=$row[10];
				$_SESSION[APP]['dept_name']=$row[11];
				$_SESSION[APP]['pos_name']=$row[12];
				$_SESSION[APP]['grade']=$row[13];
				$data = "sukses";
			}
		}
		
		
	} else {
		//$data = 'Untuk sementara program SIK / SPL belum bisa digunakan. Karena sedang digunakan HRD untuk kepentingan penggajian 1 februari. <BR>Silahkan login kembali pada pukul 10:00. <BR>Atas perhatiannya terima kasih.';
		//$data = 'Untuk sementara program SIK / SPL belum bisa digunakan. Karena sedang MAINTENANCE. <BR>Silahkan login kembali pada pukul 13:00 WIB. <BR>Atas perhatiannya terima kasih.';
		$data = "Username or Password doesn't exist.";
	}
	
	//echo $data;
	$result = array('success' => true,
					'results' => 0,
					'rows' => $data
				);
	echo json_encode($result);
}

?>