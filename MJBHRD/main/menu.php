<?PHP
//include 'koneksi.php';
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
  
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
</head>
<body>
	<div id="menu">
		<ul>
			<li class="first">
				<a href = <?PHP echo PATHAPP . "/main/indexUtama.php"?> >Home</a>
			</li>
			<?PHP
				$RuleApp=0;
				$CountRuleApp=0;
				$resultCount = oci_parse($con, "SELECT NVL(MSRM.ALL_MODUL, 0)
				FROM MJ.MJ_M_USER MMU 
				INNER JOIN MJ.MJ_SYS_USER_RULE MUR ON MUR.ID_USER=MMU.ID
				INNER JOIN MJ.MJ_SYS_RULE MSR ON MSR.ID_RULE=MUR.ID_RULE AND APP_ID = " . APPCODE . "
				INNER JOIN MJ.MJ_SYS_RULE_MODUL MSRM ON MSRM.ID_RULE=MSR.ID_RULE
				WHERE USERNAME='$username'");
				oci_execute($resultCount);
				$rowCount = oci_fetch_row($resultCount);
				$RuleApp = $rowCount[0];
				
				if ($RuleApp==0) {
					$resultCount = oci_parse($con, "SELECT COUNT(-1)
					FROM MJ.MJ_M_USER MMU 
					INNER JOIN MJ.MJ_SYS_USER_RULE MUR ON MUR.ID_USER=MMU.ID
					INNER JOIN MJ.MJ_SYS_RULE MSR ON MSR.ID_RULE=MUR.ID_RULE AND MSR.APP_ID = " . APPCODE . "
					INNER JOIN MJ.MJ_SYS_RULE_MODUL MSRM ON MSRM.ID_RULE=MSR.ID_RULE
					INNER JOIN MJ.MJ_SYS_MODUL MSM ON MSM.ID_MODUL=MSRM.ID_MODUL AND MSM.APP_ID = " . APPCODE . "
					WHERE USERNAME='$username' AND MSM.AKTIF='Y' AND NAMA_MODUL='Master'");
					oci_execute($resultCount);
					$rowCount = oci_fetch_row($resultCount);
					$jumlah = $rowCount[0];
					if ($jumlah>0){
						$result = oci_parse($con, "SELECT MSM.TARGET, MSM.NAMA_MODUL
						FROM MJ.MJ_M_USER MMU 
						INNER JOIN MJ.MJ_SYS_USER_RULE MUR ON MUR.ID_USER=MMU.ID
						INNER JOIN MJ.MJ_SYS_RULE MSR ON MSR.ID_RULE=MUR.ID_RULE AND MSR.APP_ID = " . APPCODE . "
						INNER JOIN MJ.MJ_SYS_RULE_MODUL MSRM ON MSRM.ID_RULE=MSR.ID_RULE
						INNER JOIN MJ.MJ_SYS_MODUL MSM ON MSM.ID_MODUL=MSRM.ID_MODUL AND MSM.APP_ID = " . APPCODE . "
						WHERE USERNAME='$username' AND MSM.AKTIF='Y' AND PARENT=(SELECT ID_MODUL FROM MJ.MJ_SYS_MODUL WHERE NAMA_MODUL='Master' AND APP_ID = " . APPCODE . ")
						ORDER BY MSM.URUTAN");
						oci_execute($result);
			?>
						<li>
							<span class="opener">Master<b></b></span>
							<ul>
			<?PHP
							while($row = oci_fetch_row($result))
							{
				?>
								<li><a href="<?PHP echo PATHAPP . $row[0]; ?>"><?PHP echo $row[1]; ?></a></li>
			<?PHP
							}
			?>
							</ul>
						</li>
			<?PHP
					
					}
					$resultCount = oci_parse($con, "SELECT COUNT(-1)
					FROM MJ.MJ_M_USER MMU 
					INNER JOIN MJ.MJ_SYS_USER_RULE MUR ON MUR.ID_USER=MMU.ID
					INNER JOIN MJ.MJ_SYS_RULE MSR ON MSR.ID_RULE=MUR.ID_RULE AND MSR.APP_ID = " . APPCODE . "
					INNER JOIN MJ.MJ_SYS_RULE_MODUL MSRM ON MSRM.ID_RULE=MSR.ID_RULE
					INNER JOIN MJ.MJ_SYS_MODUL MSM ON MSM.ID_MODUL=MSRM.ID_MODUL AND MSM.APP_ID = " . APPCODE . "
					WHERE USERNAME='$username' AND MSM.AKTIF='Y' AND NAMA_MODUL='Transaksi'");
					oci_execute($resultCount);
					$rowCount = oci_fetch_row($resultCount);
					$jumlah = $rowCount[0];
					if ($jumlah>0){
						$result = oci_parse($con, "SELECT MSM.TARGET, MSM.NAMA_MODUL
						FROM MJ.MJ_M_USER MMU 
						INNER JOIN MJ.MJ_SYS_USER_RULE MUR ON MUR.ID_USER=MMU.ID
						INNER JOIN MJ.MJ_SYS_RULE MSR ON MSR.ID_RULE=MUR.ID_RULE AND MSR.APP_ID = " . APPCODE . "
						INNER JOIN MJ.MJ_SYS_RULE_MODUL MSRM ON MSRM.ID_RULE=MSR.ID_RULE
						INNER JOIN MJ.MJ_SYS_MODUL MSM ON MSM.ID_MODUL=MSRM.ID_MODUL AND MSM.APP_ID = " . APPCODE . "
						WHERE USERNAME='$username' AND MSM.AKTIF='Y' AND PARENT=(SELECT ID_MODUL FROM MJ.MJ_SYS_MODUL WHERE NAMA_MODUL='Transaksi' AND APP_ID = " . APPCODE . ")
						ORDER BY MSM.URUTAN");
						oci_execute($result);
			?>
						<li>
							<span class="opener">Transaksi<b></b></span>
							<ul>
			<?PHP
						while($row = oci_fetch_row($result))
						{
				?>
							<li><a href="<?PHP echo PATHAPP . $row[0]; ?>"><?PHP echo $row[1]; ?></a></li>
			<?PHP
						}
			?>
							</ul>
						</li>
			<?PHP
					}
					$resultCount = oci_parse($con, "SELECT COUNT(-1)
					FROM MJ.MJ_M_USER MMU 
					INNER JOIN MJ.MJ_SYS_USER_RULE MUR ON MUR.ID_USER=MMU.ID
					INNER JOIN MJ.MJ_SYS_RULE MSR ON MSR.ID_RULE=MUR.ID_RULE AND MSR.APP_ID = " . APPCODE . "
					INNER JOIN MJ.MJ_SYS_RULE_MODUL MSRM ON MSRM.ID_RULE=MSR.ID_RULE
					INNER JOIN MJ.MJ_SYS_MODUL MSM ON MSM.ID_MODUL=MSRM.ID_MODUL AND MSM.APP_ID = " . APPCODE . "
					WHERE USERNAME='$username' AND MSM.AKTIF='Y' AND NAMA_MODUL='Report'");
					oci_execute($resultCount);
					$rowCount = oci_fetch_row($resultCount);
					$jumlah = $rowCount[0];
					if ($jumlah>0){
						$result = oci_parse($con, "SELECT MSM.TARGET, MSM.NAMA_MODUL
						FROM MJ.MJ_M_USER MMU 
						INNER JOIN MJ.MJ_SYS_USER_RULE MUR ON MUR.ID_USER=MMU.ID
						INNER JOIN MJ.MJ_SYS_RULE MSR ON MSR.ID_RULE=MUR.ID_RULE AND MSR.APP_ID = " . APPCODE . "
						INNER JOIN MJ.MJ_SYS_RULE_MODUL MSRM ON MSRM.ID_RULE=MSR.ID_RULE
						INNER JOIN MJ.MJ_SYS_MODUL MSM ON MSM.ID_MODUL=MSRM.ID_MODUL AND MSM.APP_ID = " . APPCODE . "
						WHERE USERNAME='$username' AND MSM.AKTIF='Y' AND PARENT=(SELECT ID_MODUL FROM MJ.MJ_SYS_MODUL WHERE NAMA_MODUL='Report' AND APP_ID = " . APPCODE . ")
						ORDER BY MSM.URUTAN");
						oci_execute($result);
			?>
						<li>
							<span class="opener">Report<b></b></span>
							<ul>
			<?PHP
							while($row = oci_fetch_row($result))
							{
				?>
								<li><a href="<?PHP echo PATHAPP . $row[0]; ?>"><?PHP echo $row[1]; ?></a></li>
			<?PHP
							}
			?>
							</ul>
						</li>
			<?PHP
					}
				} else {
					$resultCount = oci_parse($con, "SELECT COUNT(-1)
					FROM MJ.MJ_SYS_MODUL
					WHERE AKTIF='Y' AND NAMA_MODUL='Master'");
					oci_execute($resultCount);
					$rowCount = oci_fetch_row($resultCount);
					$jumlah = $rowCount[0];
					if ($jumlah>0){
						$result = oci_parse($con, "SELECT TARGET, NAMA_MODUL
						FROM MJ.MJ_SYS_MODUL
						WHERE AKTIF='Y' AND PARENT=(SELECT ID_MODUL FROM MJ.MJ_SYS_MODUL WHERE NAMA_MODUL='Master' AND APP_ID = " . APPCODE . ")
						ORDER BY URUTAN");
						oci_execute($result);
			?>
						<li>
							<span class="opener">Master<b></b></span>
							<ul>
			<?PHP
							while($row = oci_fetch_row($result))
							{
				?>
								<li><a href="<?PHP echo PATHAPP . $row[0]; ?>"><?PHP echo $row[1]; ?></a></li>
			<?PHP
							}
			?>
							</ul>
						</li>
			<?PHP
					
					}
					$resultCount = oci_parse($con, "SELECT COUNT(-1)
					FROM MJ.MJ_SYS_MODUL
					WHERE AKTIF='Y' AND NAMA_MODUL='Transaksi'");
					oci_execute($resultCount);
					$rowCount = oci_fetch_row($resultCount);
					$jumlah = $rowCount[0];
					if ($jumlah>0){
						$result = oci_parse($con, "SELECT TARGET, NAMA_MODUL
						FROM MJ.MJ_SYS_MODUL
						WHERE AKTIF='Y' AND PARENT=(SELECT ID_MODUL FROM MJ.MJ_SYS_MODUL WHERE NAMA_MODUL='Transaksi' AND APP_ID = " . APPCODE . ")
						ORDER BY URUTAN");
						oci_execute($result);
			?>
						<li>
							<span class="opener">Transaksi<b></b></span>
							<ul>
			<?PHP
						while($row = oci_fetch_row($result))
						{
				?>
							<li><a href="<?PHP echo PATHAPP . $row[0]; ?>"><?PHP echo $row[1]; ?></a></li>
			<?PHP
						}
			?>
							</ul>
						</li>
			<?PHP
					}
					$resultCount = oci_parse($con, "SELECT COUNT(-1)
					FROM MJ.MJ_SYS_MODUL
					WHERE AKTIF='Y' AND NAMA_MODUL='Report'");
					oci_execute($resultCount);
					$rowCount = oci_fetch_row($resultCount);
					$jumlah = $rowCount[0];
					if ($jumlah>0){
						$result = oci_parse($con, "SELECT TARGET, NAMA_MODUL
						FROM MJ.MJ_SYS_MODUL
						WHERE AKTIF='Y' AND PARENT=(SELECT ID_MODUL FROM MJ.MJ_SYS_MODUL WHERE NAMA_MODUL='Report' AND APP_ID = " . APPCODE . ")
						ORDER BY URUTAN");
						oci_execute($result);
			?>
						<li>
							<span class="opener">Report<b></b></span>
							<ul>
			<?PHP
							while($row = oci_fetch_row($result))
							{
				?>
								<li><a href="<?PHP echo PATHAPP . $row[0]; ?>"><?PHP echo $row[1]; ?></a></li>
			<?PHP
							}
			?>
							</ul>
						</li>
			<?PHP
					}
				}
			?>
			<li class="last"><a href= <?PHP echo PATHAPP . "/Index.php"?> >Logout</a></li>
		</ul>
		<br class="clearfix" />
	</div>
</body>
</html>
