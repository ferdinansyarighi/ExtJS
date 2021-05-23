<?PHP
include 'koneksi.php';
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
//echo '<pre>' . print_r($_SESSION, TRUE) . '</pre>';
//echo $_SESSION[APP]['user_id'];
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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--
Design by TEMPLATED
http://templated.co
Released for free under the Creative Commons Attribution License

Name       : Big Business 2.0
Description: A two-column, fixed-width design with a bright color scheme.
Version    : 1.0
Released   : 20120624
-->
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="description" content="" />
<meta name="keywords" content="" />
<title>PT. Merak Jaya Group</title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<?PHP 
if ($user_id!=''){
?>
<link rel="stylesheet" type="text/css" href="../media/css/cssphplama.css" />
<script type="text/javascript" src="../media/js/jquery.min.js"></script>
<script type="text/javascript" src="../media/js/jquery.dropotron-1.0.js"></script>
<script type="text/javascript" src="../media/js/jquery.slidertron-1.1.js"></script>

<script type="text/javascript">
	$(function() {
		$('#menu > ul').dropotron({
			mode: 'fade',
			globalOffsetY: 11,
			offsetY: -15
		});
	});
</script>
<?PHP 
}
?>
</head>
<body>
<?PHP 
if ($user_id!=''){
?>
	<div id="wrapper">
	<div id="headerlama">
		<div>
		<table width="891" border="0">
  			<tr>
    			<td width="121" rowspan="2"><img src="../images/header.png" alt="" /></td>
				<td width="596"><h1 style="font-size:300%">&nbsp;&nbsp;PT. MERAK JAYA GROUP</h1></td>
    			<td width="160"><table width="160" border="0" align="right">
				  <tr>
					<td width="25"><div align="right" style="font-size:75%">Nama</div></td>
					<td width="10">:</td>
					<td width="125"><div align="left" style="font-size:75%"><?PHP echo $emp_name; ?></td>
				  </tr>
				  <tr>
					<td width="25"><div align="right" style="font-size:75%">OU</div></td>
					<td width="10">:</td>
					<td width="125"><div align="left" style="font-size:75%"><?PHP echo $org_name; ?></td>
				  </tr>
				  <tr>
					<td width="25"><div align="right" style="font-size:75%">Plant</div></td>
					<td width="10">:</td>
					<td width="125"><div align="left" style="font-size:75%"><?PHP echo $loc_name; ?></td>
				  </tr>
              </table></td>
  			</tr>
		</table>
		</div>
	</div>
	
	<?PHP include '../main/menu.php'; ?>
	
	</div>
	<div id="footerlama">
		<?PHP include '../main/footer.php'; ?>
	</div>
<?PHP	
} else {
	header("location: " . PATHAPP . "/index.php");
}
?>

</body>
</html>