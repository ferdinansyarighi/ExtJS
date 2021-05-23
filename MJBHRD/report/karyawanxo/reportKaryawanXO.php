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
	var comboPemohon = Ext.create('Ext.data.Store', {
		fields: [{
			name:'DATA_VALUE'
		},{
			name:'DATA_NAME'
		}],
		proxy:{
			type:'ajax',
			url:'<?PHP echo PATHAPP ?>/combobox/combobox_pemohon_dept_id.php', 
			reader: {
				type: 'json',
				root: 'data', 
				totalProperty:'total'   
			},
		}
	});

	var store_grid_absen = new Ext.data.JsonStore({
		id: 'store_grid_pp',
		pageSize: 50,
		timeout: 99999999999999999999,
		fields: [{
			name: 'PERSON_ID'
		},{
			name: 'NAMA_KARYAWAN'
		},{
			name: 'TANGGAL_ABSEN'
		},{
			name: 'JAM_MASUK'
		},{
			name: 'JAM_KELUAR'
		},{
			name: 'KETERANGAN'
		},{
			name: 'SIK_SPL'
		},{
			name: 'IJIN_KHUSUS'
		},{
			name: 'TGL_APPROVED'
		},{
			name: 'DEPT'
		}],
		proxy: {
			type: 'ajax',
			url: 'grid_monitor.php',
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
        title: 'Report Absen Karyawan Oracle',
        frame: false,
        columns: [{
			xtype:'rownumberer',
			id:'row_id',
			header:'No',
			width:40
		},{
			dataIndex:'PERSON_ID',
			header:'Person ID',
			width:100,
			hidden:true,
		},{
			dataIndex:'NAMA_KARYAWAN',
			header:'Nama Karyawan',
			width:180,
			//hidden:true
		},{
			dataIndex:'TANGGAL_ABSEN',
			header:'Tanggal Absen',
			width:140,
		},{
			dataIndex:'JAM_MASUK',
			header:'Jam Masuk',
			width:140,
		},{
			dataIndex:'JAM_KELUAR',
			header:'Jam Keluar',
			width:150,
		},{
			dataIndex:'KETERANGAN',
			header:'Keterangan',
			width:100,
		},{
			dataIndex:'SIK_SPL',
			header:'Surat Ijin/Lembur',
			width:100,
		},{
			dataIndex:'IJIN_KHUSUS',
			header:'Ijin Khusus',
			width:100,
		},{
			dataIndex:'TGL_APPROVED',
			header:'Tanggal Approved',
			width:100,
		},{
			dataIndex:'DEPT',
			header:'Departemen',
			width:100,
			hidden:true,
		}],
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
	var comboDept = Ext.create('Ext.data.Store', {
		fields: [{
			name:'DATA_VALUE'
		},{
			name:'DATA_NAME'
		}],
		proxy:{
			type:'ajax',
			url:'<?PHP echo PATHAPP ?>/combobox/combobox_dept.php', 
			reader: {
				type: 'json',
				root: 'data', 
				totalProperty:'total'   
			},
		}
	});
	var currentTime = new Date();
	var contentPanel = Ext.create('Ext.panel.Panel',{
		bodyStyle: 'spacing: 10px;border:none',
		items:[{
			xtype:'label',
			html:'<div align="center"><font size="5"><b>Report Monitoring Absen Karyawan Oracle</b></font></div>',
		},{
			xtype:'label',
			html:'&nbsp',
		},{		
			xtype:'combobox',
			fieldLabel:'Nama',
			store: comboPemohon,
			displayField: 'DATA_NAME',
			valueField: 'DATA_VALUE',
			width:400,
			labelWidth:75,
			id:'cb_nama',
			value:'',
			emptyText : '- Pilih -',
			// queryMode : 'local',
			//editable: false 
			enableKeyEvents : true,
			minChars:1,
			listeners: {
				keypress : {
					element: 'body',
					fn: function(){
						var nama_pemohon=Ext.getCmp('cb_nama').getValue();
						comboPemohon.load({
							params : {
								nama_pemohon:nama_pemohon,
							}
						});
					}
				}
			}
		},{		
			xtype:'combobox',
			fieldLabel:'Department',
			store: comboDept,
			displayField: 'DATA_NAME',
			valueField: 'DATA_VALUE',
			width:300,
			labelWidth:75,
			id:'cb_dept',
			value:'',
			emptyText : '- Pilih -',
			//editable: false 
			enableKeyEvents : true,
			minChars:1,
			listeners: {
				select:function(f,r,i){
					var nama_ijin=r[0].data.DATA_VALUE;
					comboPemohon.setProxy({
						type:'ajax',
						url:'<?PHP echo PATHAPP ?>/combobox/combobox_pemohon_dept_id.php?jobid=' + nama_ijin,
						reader: {
							type: 'json',
							root: 'data', 
							totalProperty:'total'   
						}
					});
					comboPemohon.load();
				},
			}
		},{		
			xtype:'combobox',
			fieldLabel:'Periode',
			store: comboPeriode,
			displayField: 'DATA_NAME',
			valueField: 'DATA_VALUE',
			width:300,
			labelWidth:75,
			id:'cb_periode',
			value:'- Pilih -',	
			//emptyText : '- Pilih -',
			editable: false,
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
					var nama=Ext.getCmp('cb_nama').getRawValue();
					var periode=Ext.getCmp('cb_periode').getValue();
					var dept=Ext.getCmp('cb_dept').getValue();
					if (Ext.getCmp('cb_nama').getValue()!='' || Ext.getCmp('cb_dept').getValue()!='') {
						store_grid_absen.setProxy({
							type:'ajax',
							url:'<?PHP echo PATHAPP ?>/report/karyawanxo/grid_monitor.php?nama='+nama+'&periode='+periode+'&dept='+dept,
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
					Ext.getCmp('cb_dept').setValue('');
					Ext.getCmp('cb_periode').setValue('');
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
					var nama=Ext.getCmp('cb_nama').getValue();
					var periode=Ext.getCmp('cb_periode').getValue();
					var dept=Ext.getCmp('cb_dept').getValue();
					window.open("isi_excel_karyawan.php?nama=" + nama + "&periode=" + periode + "&dept=" + dept + "");
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
	contentPanel.render('page');
	comboPemohon.load();
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