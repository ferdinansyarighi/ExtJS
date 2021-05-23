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
	var maskgrid;
	var sumidtxt='';
	var comboBulan = Ext.create('Ext.data.Store', {
		fields: ['DATA_NAME', 'DATA_VALUE'],
		data : [
			{"DATA_NAME":"January", "DATA_VALUE":"January"},
			{"DATA_NAME":"February", "DATA_VALUE":"February"},
			{"DATA_NAME":"March", "DATA_VALUE":"March"},
			{"DATA_NAME":"April", "DATA_VALUE":"April"},
			{"DATA_NAME":"May", "DATA_VALUE":"May"},
			{"DATA_NAME":"June", "DATA_VALUE":"June"},
			{"DATA_NAME":"July", "DATA_VALUE":"July"},
			{"DATA_NAME":"August", "DATA_VALUE":"August"},
			{"DATA_NAME":"September", "DATA_VALUE":"September"},
			{"DATA_NAME":"October", "DATA_VALUE":"October"},
			{"DATA_NAME":"November", "DATA_VALUE":"November"},
			{"DATA_NAME":"December", "DATA_VALUE":"December"},
		]
	});
	var comboTahun = Ext.create('Ext.data.Store', {
		fields: [{
			name:'DATA_VALUE'
		},{
			name:'DATA_NAME'
		}],
		proxy:{
			type:'ajax',
			url:'<?PHP echo PATHAPP ?>/combobox/combobox_tahun.php', 
			reader: {
				type: 'json',
				root: 'data', 
				totalProperty:'total'   
			},
		}
	});
	var comboRekening = Ext.create('Ext.data.Store', {
		fields: [{
			name:'DATA_VALUE'
		},{
			name:'DATA_NAME'
		}],
		proxy:{
			type:'ajax',
			url:'<?PHP echo PATHAPP ?>/combobox/combobox_rekening.php', 
			reader: {
				type: 'json',
				root: 'data', 
				totalProperty:'total'   
			},
		}
	});
	var store_cuti=new Ext.data.JsonStore({
		id:'store_cuti_id',
		pageSize: 50,
		fields:[{
			name:'HD_ID'
		},{
			name:'DATA_NAMA'
		},{
			name:'DATA_PERUSAHAAN'
		},{
			name:'DATA_DEPARTMENT'
		},{
			name:'DATA_JABATAN'
		},{
			name:'DATA_TAHUNAN'
		},{
			name:'DATA_SAKIT'
		}],
		proxy:{
			type:'ajax',
			url:'isi_grid_cuti.php', 
			reader: {
				type: 'json',
				root: 'data',
				totalProperty:'total'        
			},
		},
		listeners:{
			load :{
				fn:function(){
					maskgrid.hide();
				}
			},
			scope:this
		}
	});
	var grid_cuti=Ext.create('Ext.grid.Panel',{
		id:'grid_cuti_id',
		region:'center',
		store:store_cuti,
        columnLines: true,
		height:500,
		autoScroll:true,
		loadMask: true,
		columns:[{
			xtype:'rownumberer',
			id:'row_id',
			header:'No',
			width:30
		},{
			dataIndex:'DATA_NAMA',
			header:'Nama',
			width:200,
		},{
			dataIndex:'DATA_PERUSAHAAN',
			header:'Perusahaan',
			width:200,
		},{
			dataIndex:'DATA_DEPARTMENT',
			header:'Department',
			width:200
		},{
			dataIndex:'DATA_JABATAN',
			header:'Jabatan',
			width:100
		},{
			text: 'Saldo Cuti',
			columns:[{
				dataIndex:'DATA_TAHUNAN',
				header:'Tahunan',
				width:75
			},{
				dataIndex:'DATA_SAKIT',
				header:'Sakit',
				width:75
			}]
		}]
	});
	var currentTime = new Date();
	var kategoriForm = "Transfer";
	var contentPanel = Ext.create('Ext.panel.Panel',{
		bodyStyle: 'spacing: 10px;border:none',
		id: "bodi",
		items:[{
			xtype:'label',
			html:'<div align="center"><font size="5"><b>Transaksi Revisi Gaji</b></font></div>',
		},{
			xtype:'label',
			html:'&nbsp',
		},{
			layout:'column',
			border:false,
			items:[{
				columnWidth:.02,
				border:false,
				layout: 'anchor',
				defaultType: 'label',
				items:[{
					xtype:'label',
					html:'<div style="color:#FF0000">*</div>',
				}]
			},{
				columnWidth:.8,
				border:false,
				layout: 'anchor',
				defaultType: 'combobox',
				items:[{		
					xtype:'combobox',
					fieldLabel:'Bulan',
					store: comboBulan,
					displayField: 'DATA_NAME',
					valueField: 'DATA_VALUE',
					width:300,
					labelWidth:80,
					id:'cb_bulan',
					value:'- Pilih -',
					//emptyText : '- Pilih -',
					editable: false,
					enableKeyEvents : true,
					minChars:1,
				}]
			}]	
		},{
			layout:'column',
			border:false,
			items:[{
				columnWidth:.02,
				border:false,
				layout: 'anchor',
				defaultType: 'label',
				items:[{
					xtype:'label',
					html:'<div style="color:#FF0000">*</div>',
				}]
			},{
				columnWidth:.8,
				border:false,
				layout: 'anchor',
				defaultType: 'combobox',
				items:[{		
					xtype:'combobox',
					fieldLabel:'Tahun',
					store: comboTahun,
					displayField: 'DATA_NAME',
					valueField: 'DATA_VALUE',
					width:300,
					labelWidth:80,
					id:'cb_tahun',
					value:'- Pilih -',	
					//emptyText : '- Pilih -',
					editable: false,
					enableKeyEvents : true,
					minChars:1,
				}]
			}]	
		},{
			layout:'column',
			border:false,
			items:[{
				columnWidth:.02,
				border:false,
				layout: 'anchor',
				defaultType: 'label',
				items:[{
					xtype:'label',
					html:'<div style="color:#FF0000">*</div>',
				}]
			},{
				columnWidth:.2,
				border:false,
				layout: 'anchor',
				defaultType: 'combobox',
				items:[{
					xtype:'numberfield',
					fieldLabel:'Revisi',
						editable: false,
					width:150,
					labelWidth:80,
					id:'tf_revisi',
					value: 0,
					minValue: 0,
					maxValue: 1,
				}]
			},{
				xtype:'label',
				html:'&nbsp',
			}]	
		},{
			xtype:'label',
			html:'&nbsp',
		},{
			xtype:'panel',
			bodyStyle: 'padding-left: 0px;padding-bottom: 25px;border:none',
			items:[{
				xtype:'button',
				text:'Simpan',
				id:'btn_simpan',
				width:100,
				handler:function(){
					if(Ext.getCmp('cb_bulan').getValue() != '- Pilih -' && Ext.getCmp('cb_tahun').getValue() != '- Pilih -'){
						Ext.Ajax.request({
						url:'<?PHP echo PATHAPP ?>/transaksi/revisigaji/cek_data_revisi.php',
							timeout: 1500000,
							params:{
								bulan:Ext.getCmp('cb_bulan').getValue(),
								tahun:Ext.getCmp('cb_tahun').getValue(),
								revisi:Ext.getCmp('tf_revisi').getValue(),
							},
							method:'POST',
							success:function(response){
								var json=Ext.decode(response.responseText);
								if (json.rows == "sukses"){
									// Ext.Msg.show({
										// title:'Konfirmasi Menyimpan Data',
										// msg:'Data revisi ' + Ext.getCmp('tf_revisi').getValue() + ' sudah ada pada database. Apakah anda mau mengganti data dengan yang baru?',
										// buttons:Ext.Msg.YESNO,
										// fn:function(btn){
											// if(btn=='yes'){
												// maskgrid = new Ext.LoadMask(Ext.getCmp('bodi'), {msg: "Proses menyimpan data . . ."});
												// maskgrid.show();
												// //Ext.getCmp('btn_simpan').setDisabled(true);
												// Ext.Ajax.request({
													// url:'<?PHP echo PATHAPP ?>/transaksi/revisigaji/simpan_gaji.php',
													// timeout: 500000,
													// params:{
														// bulan:Ext.getCmp('cb_bulan').getValue(),
														// tahun:Ext.getCmp('cb_tahun').getValue(),
														// revisi:Ext.getCmp('tf_revisi').getValue(),
													// },
													// method:'POST',
													// success:function(response){
														// maskgrid.hide();
														// var json=Ext.decode(response.responseText);
														// if (json.rows == "sukses"){
															// alertDialog('Kesalahan', "Data berhasil disimpan. ");
														// } else {
															// alertDialog('Kesalahan', "Data gagal disimpan. ");
														// } 
													// },
													// failure:function(error){
														// alertDialog('Kesalahan','Data gagal disimpan');
													// }
												// });
											// }
										// },
										// animEl:'elId',
										// icon:Ext.MessageBox.QUESTION
									// });
									alertDialog('Informasi','Data gaji revisi ' + Ext.getCmp('tf_revisi').getValue() + ' sudah pernah disimpan. <BR>User tidak diperbolehkan untuk mengupdate data.');
								} else {
									Ext.getCmp('btn_simpan').setDisabled(true);
									maskgrid = new Ext.LoadMask(Ext.getCmp('bodi'), {msg: "Proses menyimpan data . . ."});
									maskgrid.show();
									Ext.Ajax.request({
									url:'<?PHP echo PATHAPP ?>/transaksi/revisigaji/cek_data_double.php',
										timeout: 1500000,
										params:{
											bulan:Ext.getCmp('cb_bulan').getValue(),
											tahun:Ext.getCmp('cb_tahun').getValue(),
										},
										method:'POST',
										success:function(response){
											var json=Ext.decode(response.responseText);
											if (json.results >= 1){
												alertDialog('Peringatan', "Berikut ini adalah karyawan yang namanya muncul lebih dari sekali. Silahkan cek elemen gaji di oracle.<BR> " + json.rows);
												//console.log(Ext.getCmp('rb_slip').getValue());
											} 
											Ext.Ajax.request({
											url:'<?PHP echo PATHAPP ?>/transaksi/revisigaji/simpan_gaji_resign.php',
											//url:'<?PHP echo PATHAPP ?>/transaksi/revisigaji/simpan_gaji_pending_per_person_id.php',
											timeout: 1500000,
											params:{
												bulan:Ext.getCmp('cb_bulan').getValue(),
												tahun:Ext.getCmp('cb_tahun').getValue(),
												revisi:Ext.getCmp('tf_revisi').getValue(),
											},
											method:'POST',
											success:function(response){
												maskgrid.hide();
												var json=Ext.decode(response.responseText);
												if (json.rows == "sukses"){
													alertDialog('Kesalahan', "Data berhasil disimpan. ");
												} else {
													alertDialog('Kesalahan', "Data gagal disimpan. ");
												} 
											},
											failure:function(error){
												maskgrid.hide();
												alertDialog('Kesalahan','Data gagal disimpan');
											}
										});
										},
										failure:function(error){
											maskgrid.hide();
											alertDialog('Kesalahan','Data gagal disimpan');
										}
									});
								} 
							},
							failure:function(error){
								maskgrid.hide();
								alertDialog('Kesalahan','Data gagal disimpan');
							}
						});
					} else {
						alertDialog('Warning','Bulan dan Tahun harus diisi.');
					}
				}
			}]
		},{
			xtype:'label',
			html:'&nbsp',
		},{
			xtype:'label',
			html:'&nbsp',
		}],
	});	
	contentPanel.render('page');
	// Ext.getCmp('btn_simpan').setDisabled(true);
	// Ext.getCmp('tf_revisi').setDisabled(true);
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