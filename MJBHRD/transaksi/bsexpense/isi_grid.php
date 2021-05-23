<?PHP
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
  
  $querywhere="";
  $id = 0;
  
  if(isset($_GET['id_bs']))
  {
	$id = $_GET['id_bs'];
	$querywhere .= " AND BS_HD_ID = $id";  
  }
//$hdid=$_GET['hd_id']; 
	$count=0;
	$result = oci_parse($con, "SELECT ID, BS_HD_ID, TIPE_EXPENSE, NOMINAL, TO_CHAR(TGL_EXPENSE, 'YYYY-MM-DD'), KETERANGAN, TO_CHAR(CREATED_DATE, 'YYYY-MM-DD'), NO_BBK, BANK_ACCOUNT_ID FROM MJ.MJ_T_BS_DT WHERE 1=1 $querywhere");
	oci_execute($result);
	while($row = oci_fetch_row($result))
	{
		$record = array();
		//$TransID=$row[0];
		$record['DATA_ID']=$row[0];
		$record['DATA_HD_ID']=$row[1];
		$record['DATA_TIPE']=$row[2];
		$record['DATA_NOMINAL']=$row[3];
		$record['DATA_TGL_EXPENSE']=$row[4];
		$record['DATA_KETERANGAN']=$row[5];
		$record['DATA_CREATED']=$row[6];
		$record['DATA_NO_BBK']=$row[7];
		$querycount = "SELECT CBA.BANK_ACCOUNT_NUM|| ' - ' ||BB.BANK_NAME|| ' a/n ' ||CBA.BANK_ACCOUNT_NAME||' #'||CBA.BANK_ACCOUNT_ID 
			FROM    APPS.CE_BANK_ACCOUNTS CBA,
					APPS.CE_BANK_ACCT_USES_ALL BAU,
					APPS.CEFV_BANK_BRANCHES BB
			WHERE   CBA.BANK_ACCOUNT_ID = BAU.BANK_ACCOUNT_ID
			AND     CBA.BANK_BRANCH_ID = BB.BANK_BRANCH_ID
			AND     ( CBA.END_DATE IS NULL OR CBA.END_DATE > TRUNC (SYSDATE) )
			AND     CBA.BANK_ACCOUNT_ID
			IN (
			53008, 72007, 57007, 56007, 58007, 98008, 53010, 73007, 25002, 57009, 42002, 12001, 45002, 54008,31002,98007
			)
			AND CBA.BANK_ACCOUNT_ID = '$row[8]'
			";
		$resultcount = oci_parse($con,$querycount);
		oci_execute($resultcount);
		$rowcount = oci_fetch_row($resultcount);
		$jumAcc = $rowcount[0]; 
		$record['DATA_ACC_BANK']=$jumAcc;
		
		$record['DATA_CEK']=1;
		
		$data[]=$record;
		$count++;
	}	
if($count==0)
{
	$data='';
}
echo json_encode($data); 
?>