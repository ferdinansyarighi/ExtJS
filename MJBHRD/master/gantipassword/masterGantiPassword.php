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
  if($user_id!=""){
  
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
<title>PT. Merak Jaya Beton</title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href= <?PHP echo PATHAPP . "/media/css/cssphpbaru.css"?> />
<script type="text/javascript" src= <?PHP echo PATHAPP . "/media/js/jquery.min.js"?> ></script>
<script type="text/javascript" src= <?PHP echo PATHAPP . "/media/js/jquery.dropotron-1.0.js"?> ></script>
<script type="text/javascript" src= <?PHP echo PATHAPP . "/media/js/jquery.slidertron-1.1.js"?> ></script>

<link rel="stylesheet" href= <?PHP echo PATHAPP . "/media/css/ext-all.css"?> />
<link rel="stylesheet" href= <?PHP echo PATHAPP . "/media/css/icons.css"?> />
<link rel="stylesheet" href= <?PHP echo PATHAPP . "/media/css/app.css"?> />	
<script type="text/javascript" src= <?PHP echo PATHAPP . "/media/js/ext-all.js"?> ></script>
<script type="text/javascript" src= <?PHP echo PATHAPP . "/media/js/exporter/Exporter-all.js"?> ></script>
<script type="text/javascript" src= <?PHP echo PATHAPP . "/media/js/exporter/Base64.js"?> ></script>
<script type="text/javascript" src= <?PHP echo PATHAPP . "/media/js/locale/ext-lang-id.js"?> ></script>
<script type="text/javascript" src= <?PHP echo PATHAPP . "/media/js/ux/GroupSummary.js"?> ></script>
<script type="text/javascript" src= <?PHP echo PATHAPP . "/media/js/app.js"?> ></script>


<script type="text/javascript">
	$(function() {
		$('#menu > ul').dropotron({
			mode: 'fade',
			globalOffsetY: 11,
			offsetY: -15
		});
	});
</script>


<script type="text/javascript">
Ext.Loader.setConfig({enabled: true});

Ext.Loader.setPath('<?PHP echo PATHAPP ?>/media/js/ux/');

Ext.require([
    'Ext.grid.*',
    'Ext.panel.*',
	'Ext.data.*',
	'Ext.util.*',
    'Ext.tip.QuickTipManager',
	'Ext.form.field.Number',
	'Ext.selection.CheckboxModel',
	'Ext.toolbar.Paging',
    'Ext.ModelManager',
	'Ext.form.*',
    'Ext.layout.container.Column',
    'Ext.state.*',
]);

Ext.onReady(function () {
		Ext.tip.QuickTipManager.init();
		Ext.Loader.injectScriptElement(
			'<?PHP echo PATHAPP ?>/media/js/locale/ext-lang-id.js',
			setupApp,
			Ext.emptyFn,
			this,
			'utf-8'
		);
});

function setupApp(){
	Ext.QuickTips.init();	
	var currentTime = new Date();
	var contentPanel = Ext.create('Ext.panel.Panel',{
			bodyStyle: 'spacing: 10px;border:none',
			items:[{
				xtype:'label',
				html:'<div align="center"><font size="5"><b>Form Master Ganti Password</b></font></div>',
			},{
				xtype:'label',
				html:'&nbsp',
			},{		
				xtype:'textfield',
				fieldLabel:'userid',
				width:350,
				labelWidth:75,
				id:'hd_id_user',
				hidden:true
			},{		
				xtype:'textfield',
				fieldLabel:'Nama User',
				width:500,
				labelWidth:125,
				id:'tf_namaUser',
				value:"<?PHP echo $emp_name ?>",
				editable: false,
				readOnly:true,
				fieldStyle:'background:#B8B8B8 ;font-weight:bold;'
			},{		
				xtype:'textfield',
				fieldLabel:'Username',
				width:350,
				labelWidth:125,
				id:'tf_username',
				value:"<?PHP echo $username ?>",
				editable: false,
				readOnly:true,
				fieldStyle:'background:#B8B8B8 ;font-weight:bold;'
			},{		
				xtype:'textfield',
				fieldLabel:'Password Lama',
				width:350,
				labelWidth:125,
				id:'tf_passLama',
				inputType: 'password',
				listeners: {
					 change: function(field,newValue,oldValue){
							field.setValue(newValue.toUpperCase());
					}
				}
			},{		
				xtype:'textfield',
				fieldLabel:'Password Baru',
				width:350,
				labelWidth:125,
				id:'tf_passBaru',
				inputType: 'password',
				listeners: {
					 change: function(field,newValue,oldValue){
							field.setValue(newValue.toUpperCase());
					}
				}
			},{		
				xtype:'textfield',
				fieldLabel:'Ulangi Password Baru',
				width:350,
				labelWidth:125,
				id:'tf_passBaru2',
				inputType: 'password',
				listeners: {
					 change: function(field,newValue,oldValue){
							field.setValue(newValue.toUpperCase());
					}
				}
			},{
				xtype:'label',
				html:'&nbsp',
			},{
				xtype:'panel',
				bodyStyle: 'padding-left: 100px;padding-bottom: 50px;border:none',
				items:[{
					xtype:'button',
					text:'Simpan',
					width:50,
					handler:function(){
						if(Ext.getCmp('tf_passLama').getValue()!='' && Ext.getCmp('tf_passBaru').getValue()!='' && Ext.getCmp('tf_passBaru2').getValue()!=''){
							if(Ext.getCmp('tf_passBaru').getValue() == Ext.getCmp('tf_passBaru2').getValue()){
								Ext.Ajax.request({
									url:'<?php echo 'cekdatalama.php'; ?>',
									params:{
										passwordLama:Ext.getCmp('tf_passLama').getValue(),
									},
									method:'POST',
									success:function(response){
										var json=Ext.decode(response.responseText);
										if (json.rows == "sukses"){
											Ext.Ajax.request({
											url:'<?php echo 'simpan_gantiPassword.php'; ?>',
											params:{
												passwordLama:Ext.getCmp('tf_passLama').getValue(),
												passwordBaru:Ext.getCmp('tf_passBaru').getValue(),
												passwordBaru2:Ext.getCmp('tf_passBaru2').getValue(),
											},
											method:'POST',
											success:function(response){
												var json=Ext.decode(response.responseText);
												if (json.rows == "sukses"){
													alertDialog('Sukses','Data berhasil disimpan');
													Ext.getCmp('tf_passLama').setValue('');
													Ext.getCmp('tf_passBaru').setValue('');
													Ext.getCmp('tf_passBaru2').setValue('');
												} else {
													alertDialog('Kesalahan', "Password Lama tidak sesuai. ");
												}
											},
											failure:function(result,action){
												alertDialog('Kesalahan','Data gagal disimpan');
											}
										});
										} else {
											alertDialog('Kesalahan', "Password Lama tidak sesuai. ");
										}
									},
									failure:function(result,action){
										alertDialog('Kesalahan','Data gagal disimpan');
									}
								});
							} else {
								alertDialog('Kesalahan','Password Baru dan Ulangi Password Baru tidak sama.');
							}
						} else {
							alertDialog('Kesalahan','Password lama atau password baru atau ulangi password baru belum diisi.');
						}
					}
				},{
					xtype:'label',
					html:'&nbsp',
				},{
					xtype:'button',
					text:'Batal',
					width:50,
					handler:function(){
						Ext.getCmp('tf_passLama').setValue('');
						Ext.getCmp('tf_passBaru').setValue('');
						Ext.getCmp('tf_passBaru2').setValue('');
					}
				}]
			}],
		});
   contentPanel.render('page');
}
</script>


<style type="text/css">
<!--
.style3 {
	font-size: 12mm;
	color: #FFFFFF;
}
.style9 {font-size: 10mm}
.style10 {font-size: 12mm}
.style11 {font-size: medium}
-->
</style>
</head>
<body>
<div id="wrapper">
	<div id="headerlama">
		<div>
		<table width="891" border="0">
  			<tr>
    			<td width="121" rowspan="2"><img src= <?PHP echo PATHAPP . "/images/header.png" ?> alt="" /></td>
				<td width="596"><h1 style="font-size:400%">&nbsp;&nbsp;PT. MERAK JAYA GROUP</h1></td>
    			<td width="160"><table width="160" border="0" align="right">
				  <tr>				  
					<td width="25"><div align="right" style="font-size:100%">Nama</div></td>
					<td width="10">:</td>
					<td width="125"><div align="left" style="font-size:100%"><?PHP echo $emp_name; ?></td>
				  </tr>
				  <tr>
					<td width="25"><div align="right" style="font-size:100%">OU</div></td>
					<td width="10">:</td>
					<td width="125"><div align="left" style="font-size:100%"><?PHP echo $org_name; ?></td>
				  </tr>
				  <tr>
					<td width="25"><div align="right" style="font-size:100%">Plant</div></td>
					<td width="10">:</td>
					<td width="125"><div align="left" style="font-size:100%"><?PHP echo $loc_name; ?></td>
				  </tr>
              </table></td>
  			</tr>
		</table>
		</div>
	</div>
	
	<?PHP include '../../main/menu.php'; ?>
	
	
	<div id="page">
	

	</div>	
</div>
<div id="footerlama">
	<?PHP include '../../main/footer.php'; ?>
</div>
</body>
</html>
<?PHP
} else {
	header("location: " . PATHAPP . "/index.php");
}
?>