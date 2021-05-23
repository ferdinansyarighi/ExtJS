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
    //'Ext.ux.PreviewPlugin',
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
	var maskgrid;
	var currentTime = new Date();
	var currentTimeNow = new Date();
	currentTime.setDate(currentTime.getDate() + 7);
	var month = currentTime.getMonth() + 1;
	var day = currentTime.getDate();
	var year = currentTime.getFullYear();
	var tanggalskr = day + "-" + month + "-" + year;
	var rowGrid = 1;
	function GetUserRecord(userID) {
		var recordIndex = store_detil.find('DATA_ID', userID);
		//console.log("RowIndex" + recordIndex);
		if (recordIndex > -1) {
			return store_detil.getAt(recordIndex);
		} else {
			return null;
		}
	}
	function formatDate(value){
        return value ? Ext.Date.dateFormat(value, 'Y-m-d') : '';
        //return value;
    }
	var frmfile1 = new Ext.FormPanel({
		fileUpload	: true,
		bodyStyle	: 'padding: 5px;border:none',
		defaultType	: 'textfield',
		//region		: 'center',
		frame		: true,
		items		:[{
			xtype		:'fileuploadfield',
			fieldLabel	: 'File Arsip',
			name		: 'arsipfile',
			id			: 'arsipfile',
			width		: 375,
			labelWidth	: 75,
		}],
	});
	var upload_panel = Ext.create('Ext.Window', {//new Ext.Window ({	
		//bodyStyle	: 'border:none;padding: 10px',
		layout		: 'form',
		height		: 150,
		width		: 400,
		closeAction	: 'hide',
		buttonAlign	: 'right',
		items		:[frmfile1],
		title		:'Pilih File',
		buttons: [{
			text     : 'Ok',
			handler: function () {
				Ext.Ajax.request({
					url			: '<?PHP echo PATHAPP ?>/transaksi/importplant/cekupload.php',
					method		: 'POST',
					waitTitle	: 'Connecting',
					waitMsg		: 'Sending data...',
					params:{
						file	:Ext.getCmp('arsipfile').getValue(), 
					},
					success: function (response) {
						var json=Ext.decode(response.responseText);
						if(json.jumlah==1){
							Ext.MessageBox.alert('Failed', 'Anda tidak diperbolehkan mengimport file selain excel (.xls).');
						}
						else{
							Ext.getCmp('tf_namaFile').setValue(Ext.getCmp('arsipfile').getValue());
							frmfile1.getForm().submit({
								url: '<?PHP echo PATHAPP ?>/transaksi/importplant/uploadattachment.php', 
								method:'POST', 
								waitTitle:'Connecting', 
								waitMsg:'Sending data...',
								
								success:function(){
									upload_panel.hide();
								},
								failure:function(form, action){ 
									if(action.failureType == 'server'){ 
										obj = Ext.util.JSON.decode(action.response.responseText); 						
										Ext.Msg.alert('Data gagal disimpan!', 'Login Gagal!'); 
									}else{ 
										Ext.Msg.alert('Warning!', 'Authentication server is unreachable : ' + action.response.responseText); 
									} 					
								} 
							});
						}
					},
					failure: function ( result, request) {
						Ext.MessageBox.alert('Failed', 'Data gagal disimpan');
					}
				}); 
			}
		},{
			text     : 'Batal',
			handler  : function(){
				Ext.getCmp('arsipfile').setValue('');
				//Ext.getCmp('fileid').setValue('');
				upload_panel.hide();
			}
		}]
	});
	var contentPanel = Ext.create('Ext.panel.Panel',{
			//title:'Form Input Tanda Terima',
			//region:'center',
			id: "bodi",
			bodyStyle: 'spacing: 10px;border:none',
			items:[{
				xtype:'label',
				html:'<div align="center"><font size="5"><b>Form Import Absensi</b></font></div>',
			},{
				xtype:'label',
				html:'&nbsp',
			},{		
				xtype:'textfield',
				fieldLabel:'id sik',
				width:350,
				labelWidth:150,
				id:'tf_hdid',
				value:0,
				hidden:true
			},{		
				xtype:'textfield',
				fieldLabel:'typeform',
				width:350,
				labelWidth:75,
				id:'tf_typeform',
				value:'tambah',
				hidden:true
			},{
				layout:'column',
				border:false,
				items:[{
					columnWidth:.59,
					border:false,
					layout: 'anchor',
					defaultType: 'combobox',
					items:[{
						xtype: 'fieldset',
						flex: 1,
						title: 'Import Excel',
						//defaultType: 'radio', // each item will be a radio button
						layout: 'anchor',
						items: [{
							layout:'column',
							border:false,
							items:[{
								columnWidth:0.75,
								border:false,
								layout: 'anchor',
								defaultType: 'textfield',
								items:[{		
									xtype:'textfield',
									fieldLabel:'',
									width:350,
									labelWidth:75,
									id:'tf_namaFile',
									value:'',
								}]
							},{
								columnWidth:.12,
								border:false,
								layout: 'anchor',
								defaultType: 'button',
								items:[{
									name: 'cari',
									text: 'Browse',
									width:50,
									handler:function(){
										upload_panel.show();
									}
								}]
							}]	
						}]
					}]
				}]	
			},{
				xtype:'label',
				html:'&nbsp',
			},{
				xtype:'label',
				html:'&nbsp',
			},{
				xtype:'panel',
				bodyStyle: 'padding-left: 300px;padding-bottom: 50px;border:none',
				items:[{
					xtype:'button',
					text:'Import Bio Finger',
					id:'btn_simpan',
					width:150,
					handler:function(){
						if(Ext.getCmp('tf_namaFile').getValue() != ''){
							Ext.getCmp('btn_simpan').setDisabled(true);
							maskgrid = new Ext.LoadMask(Ext.getCmp('bodi'), {msg: "Proses menyimpan data . . ."});
							maskgrid.show();
							Ext.Ajax.request({
								url:'<?php echo 'simpan_import.php'; ?>',
								timeout: 500000,
								params:{
									namaFile:Ext.getCmp('tf_namaFile').getValue(),
								},
								method:'POST',
								success:function(response){
									maskgrid.hide();
									alertDialog('Sukses','Data berhasil diimport');
									Ext.getCmp('tf_namaFile').setValue('');
									Ext.getCmp('btn_simpan').setDisabled(false);
								},
								failure:function(result,action){
									alertDialog('Kesalahan','Data gagal diimport');
								}
							});
						} else {
							alertDialog('Kesalahan','File belum dipilih.');
						}
					}
				},{
					xtype:'label',
					html:'&nbsp',
				},{
					xtype:'button',
					text:'Import Finger Spot',
					id:'btn_simpan_spot',
					width:150,
					handler:function(){
						// console.log(Ext.getCmp('tf_namaFile').getValue());
						if(Ext.getCmp('tf_namaFile').getValue() != ''){
							Ext.getCmp('btn_simpan').setDisabled(true);
							maskgrid = new Ext.LoadMask(Ext.getCmp('bodi'), {msg: "Proses menyimpan data . . ."});
							maskgrid.show();
							Ext.Ajax.request({
								url:'<?php echo 'simpan_import_spot.php'; ?>',
								timeout: 5000000,
								params:{
									namaFile:Ext.getCmp('tf_namaFile').getValue(),
								},
								method:'POST',
								success:function(response){
									maskgrid.hide();
									var json=Ext.decode(response.responseText);
									if (json.rows == "sukses"){
										alertDialog('Kesalahan', "Data berhasil diimport. ");
									} else {
										alertDialog('Kesalahan', "Data gagal diimport. ");
									} 
									Ext.getCmp('tf_namaFile').setValue('');
									Ext.getCmp('btn_simpan').setDisabled(false);
								},
								failure:function(result,action){
									alertDialog('Kesalahan','Data gagal diimport');
								}
							});
						} else {
							alertDialog('Kesalahan','File belum dipilih.');
						}
					}
				},{
					xtype:'label',
					html:'&nbsp',
				},{
					xtype:'button',
					text:'Clear',
					width:50,
					handler:function(){
						Ext.getCmp('tf_namaFile').setValue('');
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