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
  // $conn = odbc_connect('bioFinger', 'absen', 'absen123');
  $conn = odbc_connect('bioFinger', 'sa', 'merakjaya1234');
	if (!$conn) {
		header('Location: HTML/index.html');
		echo ("Koneksi ke Biofinger Gagal !");
		exit;
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
	var currentTime = new Date();
	var awalbulan = new Date(currentTime.getFullYear(), currentTime.getMonth(), 1);
	var month = currentTime.getMonth() + 1;
	var day = currentTime.getDate();
	var year = currentTime.getFullYear();
	var tanggalskr = day + "-" + month + "-" + year;
	var sumidtxt='';
	var rowGrid = 1;
	function GetUserRecord(userID) {
		var recordIndex = store_grid_absen.find('HD_ID', userID);
		//console.log("RowIndex" + recordIndex);
		if (recordIndex > -1) {
			return store_grid_absen.getAt(recordIndex);
		} else {
			return null;
		}
	}
	function formatDate(value){
        return value ? Ext.Date.dateFormat(value, 'Y-m-d') : '';
        //return value;
    }
	var comboPlantSql = Ext.create('Ext.data.Store', {
		fields: [{
			name:'DATA_VALUE'
		},{
			name:'DATA_NAME'
		}],
		proxy:{
			type:'ajax',
			url:'<?PHP echo PATHAPP ?>/combobox/combobox_plant_sql.php', 
			reader: {
				type: 'json',
				root: 'data', 
				totalProperty:'total'   
			},
		}
	});
	var comboPeriode = Ext.create('Ext.data.Store', {
		fields: [{
			name:'DATA_VALUE'
		},{
			name:'DATA_NAME'
		}],
		proxy:{
			type:'ajax',
			url:'<?PHP echo PATHAPP ?>/combobox/combobox_periode.php', 
			reader: {
				type: 'json',
				root: 'data', 
				totalProperty:'total'   
			},
		}
	});
	var comboPemohon = Ext.create('Ext.data.Store', {
		fields: [{
			name:'DATA_VALUE'
		},{
			name:'DATA_NAME'
		}],
		proxy:{
			type:'ajax',
			url:'<?PHP echo PATHAPP ?>/combobox/combobox_karyawan.php', 
			reader: {
				type: 'json',
				root: 'data', 
				totalProperty:'total'   
			},
		}
	});

	var store_grid_absen = new Ext.data.JsonStore({
		id: 'store_grid_pp',
		pageSize: 999999,
		timeout: 99999999999999999999999999999,
		fields: [{
			name: 'HD_ID'
		}, {
			name: 'DATA_NAMA'
		}, {
			name: 'DATA_PLANT'
		}, {
			name: 'DATA_ID_FINGER'
		}, {
			name: 'DATA_CHECKLOCK'
		}, {
			name: 'DATA_IN_OUT'
		}, {
			name: 'DATA_IMPORT'
		}],
		proxy: {
			type: 'ajax',
			url: 'grid.php',
			reader: {
				type: 'json',
				root: 'data',
				totalProperty: 'total'
			},
		},
		listeners: {
			load: {
				fn: function() {
					Ext.MessageBox.hide();
				}
			},
			scope: this
		}
	});

	var grid_absen = Ext.create('Ext.grid.Panel', {
        store: store_grid_absen,
		autoScroll:true,
        columnLines: true,
        width: 850,
        height: 300,
        title: 'Detail Presensi Karyawan Biofinger',
        frame: false,
        columns: [{
			dataIndex:'HD_ID',
			header:'Header id',
			width:100,
			hidden:true,
		},{
			xtype:'rownumberer',
			id:'row_id',
			header:'No',
			width:40
		},{
			dataIndex:'DATA_NAMA',
			header:'Employee Name',
			width:180,
			//hidden:true
		},{
			dataIndex:'DATA_PLANT',
			header:'Plant',
			width:140,
		},{
			dataIndex:'DATA_ID_FINGER',
			header:'ID Finger',
			width:140,
		},{
			dataIndex:'DATA_CHECKLOCK',
			header:'Checklog',
			width:150,
		},{
			dataIndex:'DATA_IN_OUT',
			header:'IN/OUT',
			width:100,
		},{
			dataIndex:'DATA_IMPORT',
			header:'Import Date',
			width:138,
		}],
       
    });
	var currentTime = new Date();

	var contentPanel = Ext.create('Ext.panel.Panel',{
		bodyStyle: 'spacing: 10px;border:none',
		items:[{
			xtype:'label',
			html:'<div align="center"><font size="5"><b>Report Monitoring Detail Absensi Karyawan Biofinger</b></font></div>',
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
					html:'',
				}]
			},{
				columnWidth:.8,
				border:false,
				layout: 'anchor',
				defaultType: 'combobox',
				items:[{		
					xtype:'combobox',
					fieldLabel:'Nama',
					store: comboPemohon,
					displayField: 'DATA_NAME',
					valueField: 'DATA_VALUE',
					width:300,
					labelWidth:75,
					id:'cb_nama',
					value:'',
					emptyText : '- Pilih -',
					//editable: false 
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
					html:'',
				}]
			},{
				columnWidth:.8,
				border:false,
				layout: 'anchor',
				defaultType: 'combobox',
				items:[{		
					xtype:'combobox',
					fieldLabel:'Plant',
					store: comboPlantSql,
					displayField: 'DATA_NAME',
					valueField: 'DATA_VALUE',
					width:300,
					labelWidth:75,
					id:'cb_plant',
					value:'',
					emptyText : '- Pilih -',
					//editable: false 
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
				columnWidth:.28,
				border:false,
				layout: 'anchor',
				defaultType: 'datefield',
				items:[{
					name: 'date1',
					fieldLabel: 'Periode',
					width:225,
					labelWidth:75,
					id:'tf_tgl_dari',
					editable:false,
					value: awalbulan
				}]
			},{
				columnWidth:.22,
				border:false,
				layout: 'anchor',
				defaultType: 'datefield',
				items:[{
					name: 'date2',
					fieldLabel: 's/d',
					width:175,
					labelWidth:30,
					id:'tf_tgl_sampai',
					editable:false,
					value: currentTime
				}]
			}]	
		},{
			xtype:'label',
			html:'&nbsp',
		},{
			layout:'column',
			border:false,
			items:[{
				columnWidth:.05,
				border:false,
				layout: 'anchor',
				defaultType: 'label',
				items:[{
					xtype:'label',
					html:'',
				}]
				},{
					columnWidth:.1,
					border:false,
					layout: 'anchor',
					defaultType: 'button',
					items:[{
						name: 'btnview',
						text: 'View',
						width: 80,
						handler:function(){
							Ext.MessageBox.wait('Sedang proses ...');
							tgldari = formatDate(Ext.getCmp('tf_tgl_dari').getValue());
							tglke = formatDate(Ext.getCmp('tf_tgl_sampai').getValue());
								if (Ext.getCmp('cb_nama').getValue()!='' || Ext.getCmp('cb_plant').getValue()!='') {
									store_grid_absen.setProxy({
										timeout: 50000000,
										type:'ajax',
										url:'<?PHP echo PATHAPP ?>/report/absen/grid.php?nama='+Ext.getCmp('cb_nama').getValue()+'&tgldari='+tgldari+'&tglke='+tglke+'&plant='+Ext.getCmp('cb_plant').getValue(),
										reader: {
											type: 'json',
											root: 'data',
											totalProperty:'total'        
										}
									});
									store_grid_absen.load();		
								}else{
									alertDialog('Kesalahan','Nama atau Plant Tidak boleh kosong.');
								}

							
						} 
					}]
				},{
					columnWidth:.1,
					border:false,
					layout: 'anchor',
					defaultType: 'button',
					items:[{
						name: 'btnclear',
						text: 'Reset',
						width: 80,
						handler:function(){
							Ext.getCmp('cb_nama').setValue('');
							Ext.getCmp('cb_plant').setValue('');
							Ext.getCmp('tf_tgl_dari').setValue(awalbulan);
							Ext.getCmp('tf_tgl_sampai').setValue(currentTime);
							store_grid_absen.removeAll();
						} 
					}]
				},{
					columnWidth:.15,
					border:false,
					layout: 'anchor',
					defaultType: 'button',
					items:[{
						name: 'btnexport',
						text: 'Export Excel',
						width: 80,
						handler:function(){
							tgldari = formatDate(Ext.getCmp('tf_tgl_dari').getValue());
							tglke = formatDate(Ext.getCmp('tf_tgl_sampai').getValue());
						// if(Ext.getCmp('cb_periode').getValue() != '- Pilih -'){
						// 	var nama=Ext.getCmp('cb_nama').getValue();
						// 	var periode=Ext.getCmp('cb_periode').getValue();
						// 	var plant=Ext.getCmp('cb_plant').getValue();
							// var tanngalFrom=Ext.getCmp('tf_tgl_from').getRawValue();
							// var tanggalTo=Ext.getCmp('tf_tgl_to').getRawValue();
							// window.open("isi_excel_absen.php?nama=" + nama + "&periode=" + periode + "&plant=" + plant + "&tanngalFrom=" + tanngalFrom + "&tanggalTo=" + tanggalTo + "");
							// window.open("isi_excel_absen.php?nama=" + nama + "&periode=" + periode + "&plant=" + plant + "");
							window.open('<?php echo PATHAPP ?>/report/absen/isi_excel_absenLFN.php?nama='+Ext.getCmp('cb_nama').getValue()+'&plant='+Ext.getCmp('cb_plant').getValue()+'&tgldari='+tgldari+'&tglke='+tglke+'')
							// } else {
							// 	alertDialog('Warning','Periode belum dipilih.');
							// }
						}	
					}]
		  		}
		  	]},{
			xtype:'label',
			html:'&nbsp',
		},grid_absen,{
			xtype:'label',
			html:'&nbsp',
		}],
	});	
	// Ext.getCmp('tf_tgl_from').setValue(currentTime);
	// Ext.getCmp('tf_tgl_to').setValue(currentTime);
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