<?PHP

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
	$pos_name = "";
	
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
		$pos_name = $_SESSION[APP]['pos_name'];
  }
?>