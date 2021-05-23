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
	$dept_name = $_SESSION[APP]['dept_name'];
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
	var month = currentTime.getMonth() + 1;
	var day = currentTime.getDate();
	var year = currentTime.getFullYear();
	var tanggalskr = day + "-" + month + "-" + year;
	var rowGrid = 1;
	function GetUserRecord(userID) {
		var recordIndex = store_detil.find('HD_ID', userID);
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
	
	Ext.clearForm=function(){
		Ext.getCmp('cb_namaKaryawan').setValue('')
		Ext.getCmp('cb_nodok').setValue('')
		Ext.getCmp('cb_tipe').setValue('- Pilih -')
		Ext.getCmp('cb_perusahaan').setValue('')
		Ext.getCmp('cb_dept').setValue('')
		Ext.getCmp('tf_tgl_from').setValue( currentTime.getDate() )
		Ext.getCmp('tf_tgl_to').setValue( currentTime.getDate() )
		store_detil.removeAll();
		store_detil.load({
			params:{
				perusahaan:Ext.getCmp('cb_perusahaan').getValue(),
				dept:Ext.getCmp('cb_dept').getValue(),
				tipe:Ext.getCmp('cb_tipe').getValue(),
				nomor:Ext.getCmp('cb_nodok').getRawValue(),
				nama:Ext.getCmp('cb_namaKaryawan').getValue(),
			}
		});
	};
	
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
	var comboPerusahaan = Ext.create('Ext.data.Store', {
		fields: [{
			name:'DATA_VALUE'
		},{
			name:'DATA_NAME'
		}],
		proxy:{
			type:'ajax',
			url:'<?PHP echo PATHAPP ?>/combobox/combobox_perusahaan_id.php', 
			reader: {
				type: 'json',
				root: 'data', 
				totalProperty:'total'   
			},
		}
	});
	var comboPlant = Ext.create('Ext.data.Store', {
		fields: [{
			name:'DATA_VALUE'
		},{
			name:'DATA_NAME'
		}],
		proxy:{
			type:'ajax',
			url:'<?PHP echo PATHAPP ?>/combobox/combobox_lokasi.php', 
			reader: {
				type: 'json',
				root: 'data', 
				totalProperty:'total'   
			},
		}
	});
	var comboNo = Ext.create('Ext.data.Store', {
		fields: [{
			name:'DATA_VALUE'
		},{
			name:'DATA_NAME'
		}],
		proxy:{
			type:'ajax',
			url:'<?PHP echo PATHAPP ?>/combobox/combobox_nopelunasan.php', 
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
			url:'<?PHP echo PATHAPP ?>/combobox/combobox_dept_mutasi.php', 
			reader: {
				type: 'json',
				root: 'data', 
				totalProperty:'total'   
			},
		}
	});
	var comboTipe = Ext.create('Ext.data.Store', {
		fields: ['DATA_NAME', 'DATA_VALUE'],
		data : [
			{"DATA_NAME":"Cash", "DATA_VALUE":"CASH"},
			{"DATA_NAME":"Potong Gaji", "DATA_VALUE":"POTONG GAJI"},
			//{"DATA_NAME":"Transfer", "DATA_VALUE":"TRANSFER"}
		]
	});
	var store_detil=new Ext.data.JsonStore({
		id:'store_cari_id',
		pageSize: 100,
		model: 'Plant',
		fields:[{
			name:'HD_ID'
		},{
			name:'DATA_NO_PELUNASAN'
		},{
			name:'DATA_TIPE_PELUNASAN'
		},{
			name:'DATA_TGL_PELUNASAN'
		},{
			name:'DATA_NO_PINJAMAN'
		},{
			name:'DATA_TIPE_PINJAMAN'
		},{
			name:'DATA_TGL_PINJAMAN'
		},{
			name:'DATA_TGL_VALIDASI'
		},{
			name:'DATA_JUMLAH_PINJAMAN'
		},{
			name:'DATA_JUMLAH_CICILAN'
		},{
			name:'DATA_NOMINAL_CICILAN'
		},{
			name:'DATA_OUTSTANDING'
		},{
			name:'DATA_CICILAN_TERAKHIR'
		}],
		proxy:{
			type:'ajax',
			url:'isi_grid.php', 
			reader: {
				root: 'rows',  
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
	
    var grid_detil = Ext.create('Ext.grid.Panel', {
		id:'grid_detil_id',
        store: store_detil,
		autoScroll:true,
        columnLines: true,
        width: 850,
        height: 300,
        title: 'Data Pelunasan Pinjaman',
        frame: false,
		//selModel:sm,
        loadMask: true,
        columns: [{
			dataIndex:'HD_ID',
			header:'No.',
			hidden:true,
			width:40,
		},{
			xtype:'rownumberer',
			id:'row_id',
			header:'No',
			width:40,
		},{
			dataIndex:'DATA_NO_PELUNASAN',
			header:'Nomor Pelunasan',
			//hidden:true,
			width:150,
		},{
			dataIndex:'DATA_TIPE_PELUNASAN',
			header:'Tipe Pelunasan',
			//hidden:true,
			width:150,
		},{
			dataIndex:'DATA_TGL_PELUNASAN',
			header:'Tgl Pelunasan',
			//hidden:true,
			width:150,
		},{
			dataIndex:'DATA_NO_PINJAMAN',
			header:'Nomor Pinjaman',
			//hidden:true,
			width:150,
		},{
			dataIndex:'DATA_TIPE_PINJAMAN',
			header:'Tipe Pinjaman',
			//hidden:true,
			width:150,
		},{
			dataIndex:'DATA_TGL_PINJAMAN',
			header:'Tgl Pinjaman',
			//hidden:true,
			width:150,
		},{
			dataIndex:'DATA_TGL_VALIDASI',
			header:'Tgl Validasi',
			//hidden:true,
			width:150,
		},{
			dataIndex:'DATA_JUMLAH_PINJAMAN',
			header:'Jumlah Pinjaman',
			//hidden:true,
			width:125,
		},{
			dataIndex:'DATA_JUMLAH_CICILAN',
			header:'Jumlah Cicilan',
			//hidden:true,
			width:75,
		},{
			dataIndex:'DATA_NOMINAL_CICILAN',
			header:'Nominal Cicilan',
			//hidden:true,
			width:150,
		},{
			dataIndex:'DATA_OUTSTANDING',
			header:'Outstanding',
			//hidden:true,
			width:150,
		},{
			dataIndex:'DATA_CICILAN_TERAKHIR',
			header:'Cicilan Terakhir',
			//hidden:true,
			width:150,
		}],
    });
	var contentPanel = Ext.create('Ext.panel.Panel',{
			//title:'Form Input Tanda Terima',
			//region:'center',
			bodyStyle: 'spacing: 10px;border:none',
			items:[{
				xtype:'label',
				html:'<div align="center"><font size="5"><b>Report Pelunasan Pinjaman Karyawan</b></font></div>',
			},{
				xtype:'label',
				html:'&nbsp<br/><br/>',
			},{		
				xtype:'textfield',
				fieldLabel:'id pp',
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
				items:[,{
					// columnWidth:.02,
					border:false,
					layout: 'anchor',
					defaultType: 'label',
					items:[{
						xtype:'label',
						html:'',
					}]
				},{
					columnWidth:.6,
					border:false,
					layout: 'anchor',
					defaultType: 'textfield',
					items:[{		
						xtype:'combobox',
						fieldLabel:'Company',
						width:340,
						labelWidth:120,
						id:'cb_perusahaan',
						store: comboPerusahaan,
						displayField: 'DATA_NAME',
						valueField: 'DATA_VALUE',
						minChars:2,
						emptyText : '- Pilih -',
					}]
				}]	
			},{
				layout:'column',
				border:false,
				items:[,{
					// columnWidth:.02,
					border:false,
					layout: 'anchor',
					defaultType: 'label',
					items:[{
						xtype:'label',
						html:'',
					}]
				},{
					columnWidth:.6,
					border:false,
					layout: 'anchor',
					defaultType: 'combobox',
					items:[{		
						xtype:'combobox',
						fieldLabel:'Departement',
						width:450,
						labelWidth:120,
						id:'cb_dept',
						store: comboDept,
						displayField: 'DATA_NAME',
						valueField: 'DATA_VALUE',
						minChars:2,
						emptyText : '- Pilih -',
						//readOnly:true,
						//fieldStyle:'background:#B8B8B8 ;font-weight:bold;'
					}]
				}]	
			},{
				layout:'column',
				border:false,
				items:[,{
					// columnWidth:.02,
					border:false,
					layout: 'anchor',
					defaultType: 'label',
					items:[{
						xtype:'label',
						html:'',
					}]
				},{
					columnWidth:.4,
					border:false,
					layout: 'anchor',
					defaultType: 'combobox',
					items:[{		
						xtype:'combobox',
						fieldLabel:'Tipe Pelunasan',
						id:'cb_tipe',
						store: comboTipe,
						displayField: 'DATA_NAME',
						valueField: 'DATA_VALUE',
						// value:'- Pilih -',
						emptyText : '- Pilih -',
						minChars:1,
						// editable:false,
						width:340,
						labelWidth:120,
					}]
				}]	
			},{
				layout:'column',
				border:false,
				items:[,{
					// columnWidth:.02,
					border:false,
					layout: 'anchor',
					defaultType: 'label',
					items:[{
						xtype:'label',
						html:'',
					}]
				},{
					columnWidth:.4,
					border:false,
					layout: 'anchor',
					defaultType: 'combobox',
					items:[{		
						xtype:'combobox',
						fieldLabel:'No Pelunasan',
						store: comboNo,
						displayField: 'DATA_NAME',
						valueField: 'DATA_VALUE',
						width:340,
						labelWidth:120,
						id:'cb_nodok',
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
				// border:true,
				items:[{
					// columnWidth:.02,
					// columnWidth:.4,
					
					// columnWidth:.01,
					
					border:false,
					layout: 'anchor',
					defaultType: 'label',
					items:[{
						xtype:'label',
						html:'',
					}]
				},{
					columnWidth:.4,
					border:false,
					layout: 'anchor',
					defaultType: 'combobox',
					items:[{		
						xtype:'combobox',
						fieldLabel:'Nama Karyawan',
						store: comboPemohon,
						displayField: 'DATA_NAME',
						valueField: 'DATA_VALUE',
						width:340,
						labelWidth:120,
						id:'cb_namaKaryawan',
						//value:'',
						emptyText : '- Pilih -',
						// queryMode : 'local',
						enableKeyEvents : true,
						minChars:1,
					}]
				}]
			},{
				layout:'column',
				border:false,
				// border:true,
				items:[{
					// columnWidth:.25,
					// columnWidth:.02,
					
					columnWidth:.3,
					
					border:false,
					// border:true,
					layout: 'anchor',
					defaultType: 'datefield',
					items:[{
						name: 'date1',
						fieldLabel: 'Tanggal Pelunasan',
						// width:175,
						// labelWidth:95,
						width:200,
						labelWidth:120,
						//editable: false,
						id:'tf_tgl_from'
					}]
				},{
					columnWidth:.2,
					border:false,
					// border:true,
					layout: 'anchor',
					defaultType: 'datefield',
					items:[{
						name: 'date2',
						fieldLabel: 'To',
						width:110,
						labelWidth:30,
						//editable: false,
						id:'tf_tgl_to'
					}]
				}]	
			},{
				xtype:'label',
				html:'<br/>',
			},{
				layout:'column',
				border:false,
				items:[,{
					columnWidth:.08,
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
						name: 'cari',
						text: 'View',
						width:75,
						handler:function(){
							maskgrid = new Ext.LoadMask(Ext.getCmp('grid_detil_id'), {msg: "Memuat . . ."});
							maskgrid.show();
							store_detil.load({
								params:{
									perusahaan:Ext.getCmp('cb_perusahaan').getValue(),
									dept:Ext.getCmp('cb_dept').getValue(),
									tipe:Ext.getCmp('cb_tipe').getValue(),
									nomor:Ext.getCmp('cb_nodok').getRawValue(),
									nama:Ext.getCmp('cb_namaKaryawan').getValue(),
									param_tglfrom : Ext.getCmp('tf_tgl_from').getRawValue(),
									param_tglto : Ext.getCmp('tf_tgl_to').getRawValue(),
								}
							});
						} 
					}]
				},{
					columnWidth:.1,
					border:false,
					layout: 'anchor',
					defaultType: 'button',
					items:[{
						name: 'clear',
						text: 'Reset',
						width:75,
						handler:function(){
							Ext.clearForm();
						} 
					}]
				},{
					columnWidth:.1,
					border:false,
					layout: 'anchor',
					defaultType: 'button',
					items:[{
						name: 'pdf',
						text: 'To Excel',
						width:75,
						handler:function(){
							var perusahaan = Ext.getCmp('cb_perusahaan').getValue();
							var dept = Ext.getCmp('cb_dept').getValue();
							var tipe = Ext.getCmp('cb_tipe').getValue();
							var nomor = Ext.getCmp('cb_nodok').getValue();
							var nama = Ext.getCmp('cb_namaKaryawan').getValue();
							window.open("isi_excel.php?perusahaan=" + perusahaan + "&dept=" + dept + "&tipe=" + tipe + "&nomor="+ nomor + "&nama="+ nama);
						} 
					}]
				}]	
			},{
				xtype:'label',
				html:'&nbsp',
			}, grid_detil ,{
				xtype:'label',
				html:'&nbsp',
			}],
		});	
   contentPanel.render('page');
   comboPemohon.load();
   store_detil.load({
		params:{
			perusahaan:Ext.getCmp('cb_perusahaan').getValue(),
			dept:Ext.getCmp('cb_dept').getValue(),
			tipe:Ext.getCmp('cb_tipe').getValue(),
			nomor:Ext.getCmp('cb_nodok').getRawValue(),
			nama:Ext.getCmp('cb_namaKaryawan').getValue(),
		}
	});
	Ext.clearForm();
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