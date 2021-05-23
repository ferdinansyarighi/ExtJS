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
	
	var comboKaryawan = Ext.create('Ext.data.Store', {
		fields: [{
			name:'DATA_VALUE'
		},{
			name:'DATA_NAME'
		}],
		proxy:{
			type:'ajax',
			url:'<?PHP echo PATHAPP ?>/combobox/combobox_mutasi_nama_karyawan_mon.php',
			reader: {
				type: 'json',
				root: 'data',
				totalProperty:'total'
			},
		}
	});
	
	var comboTipe = Ext.create('Ext.data.Store', {
		fields: [{
			name:'DATA_VALUE'
		},{
			name:'DATA_NAME'
		}],
		proxy:{
			type:'ajax',
			url:'<?PHP echo PATHAPP ?>/combobox/combobox_mutasi_tipe_trans.php',
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
			url:'<?PHP echo PATHAPP ?>/combobox/combobox_dept_mon.php', 
			reader: {
				type: 'json',
				root: 'data', 
				totalProperty:'total'   
			},
		}
	});
	
	var comboNoReq = Ext.create('Ext.data.Store', {
		fields: [{
			name:'DATA_VALUE'
		},{
			name:'DATA_NAME'
		}],
		proxy:{
			type:'ajax',
			url:'<?PHP echo PATHAPP ?>/combobox/combobox_mutasi_noreq_mon.php', 
			reader: {
				type: 'json',
				root: 'data', 
				totalProperty:'total'   
			},
		}
	});
	
	var comboStatus = Ext.create('Ext.data.Store', {
		fields: [{
			name:'DATA_VALUE'
		},{
			name:'DATA_NAME'
		}],
		proxy:{
			type:'ajax',
			url:'<?PHP echo PATHAPP ?>/combobox/combobox_mutasi_status.php',
			reader: {
				type: 'json',
				root: 'data',
				totalProperty:'total'
			},
		}
	});
	
	var store_monitoring=new Ext.data.JsonStore({
		id:'store_monitoring_id',
		pageSize: 50,
		fields:[
		{
			name:'NO_REQUEST'
		},{
			name:'TIPE'
		},{
			name:'STATUS_KARYAWAN'
		},{
			name:'SIFAT_PERUBAHAN'
		},{
			name:'NAMA_KARYAWAN'
		},{
			name:'DEPARTEMEN'
		},{
			name:'MGR'
		},{
			name:'GRADE'
		},{
			name:'POSITION'
		},{
			name:'LOCATION'
		},{
			name:'DEPT_BARU'
		},{
			name:'MGR_BARU'
		},{
			name:'GRADE_BARU'
		},{
			name:'POSITION_BARU'
		},{
			name:'LOCATION_BARU'
		},{
			name:'ALASAN'
		},{
			name:'KETERANGAN'
		},{
			name:'TGL_EFEKTIF'
		},{
			name:'STATUS_DOK'
		},{
			name:'LAST_APPROVER'
		},{
			name:'NEXT_APPROVER'
		},{
			name:'PIC_DISAPPROVE'
		},{
			name:'NOTE_DISAPPROVE'
		},{
			name:'DATA_ATTACHMENT'
		}		
		],
		proxy:{
			type:'ajax',
			url:'isi_grid_monitoring.php', 
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
	
	var grid_monitoring=Ext.create('Ext.grid.Panel',{
		id:'grid_monitoring_id',
		region:'center',
		store:store_monitoring,
        columnLines: true,
		height:400,
		autoScroll:true,
		loadMask: true,
		columns:[
		{
			xtype:'rownumberer',
			id:'row_id',
			header:'No',
			width:30
		}, {
			dataIndex:'NO_REQUEST',
			header:'No Request',
			width:180,
		}, {
			dataIndex:'TIPE',
			header:'Tipe',
			width:120,
		}, {
			dataIndex:'STATUS_KARYAWAN',
			header:'Status Karyawan',
			width:100
		}
		,{
			dataIndex:'SIFAT_PERUBAHAN',
			header:'Sifat Perubahan',
			width:100
		},{
			dataIndex:'NAMA_KARYAWAN',
			header:'Nama Karyawan',
			width:200
		},{
			dataIndex:'DEPARTEMEN',
			header:'Departemen',
			width:200
		},{
			dataIndex:'MGR',
			header:'Manager',
			width:150
		},{
			dataIndex:'GRADE',
			header:'Grade',
			width:120
		},{
			dataIndex:'POSITION',
			header:'Posisi',
			align: 'left',
			width:150
		},{
			dataIndex:'LOCATION',
			header:'Lokasi',
			align: 'left',
			width:150
		},{
			dataIndex:'DEPT_BARU',
			header:'Departemen Baru',
			align: 'left',
			width:200
		},{
			dataIndex:'MGR_BARU',
			header:'MGR Baru',
			width:150,
		},{
			dataIndex:'GRADE_BARU',
			header:'Grade Baru',
			width:120,
		},{
			dataIndex:'POSITION_BARU',
			header:'Posisi Baru',
			width:150,
		},{
			dataIndex:'LOCATION_BARU',
			header:'Lokasi Baru',
			width:150,
		},{
			dataIndex:'ALASAN',
			header:'Alasan',
			width:150,
		},{
			dataIndex:'KETERANGAN',
			header:'Keterangan',
			width:150,
		},{
			dataIndex:'TGL_EFEKTIF',
			header:'Tgl Efektif',
			width:80,
		},{
			dataIndex:'STATUS_DOK',
			header:'Status',
			width:80,
		},{
			dataIndex:'LAST_APPROVER',
			header:'Approval Terakhir',
			width:150,
		},{
			dataIndex:'NEXT_APPROVER',
			header:'Approval Selanjutnya',
			width:150,
		},{
			dataIndex:'PIC_DISAPPROVE',
			header:'PIC Disapprove',
			width:150,
		},{
			dataIndex:'NOTE_DISAPPROVE',
			header:'Keterangan Disapprove',
			width:150,
		},{
			dataIndex:'DATA_ATTACHMENT',
			header:'Attachment',
			width:150,
		}
		
		
		]
	});
	
	var currentTime = new Date();
	
	var contentPanel = Ext.create('Ext.panel.Panel',{
			bodyStyle: 'spacing: 10px;border:none',
			items:[{
				xtype:'label',
				html:'<div align="center"><font size="5"><b>Report Monitoring Mutasi Promosi Demosi Karyawan</b></font></div>',
			},{
				xtype:'label',
				html:'&nbsp',
			},{
				layout:'column',
				border:false,
				items:[{
					columnWidth:.25,
					border:false,
					layout: 'anchor',
					defaultType: 'datefield',
					items:[{
						name: 'date1',
						fieldLabel: 'Tanggal Efektif',
						width:175,
						labelWidth:95,
						//editable: false,
						id:'tf_tgl_from'
					}]
				},{
					columnWidth:.2,
					border:false,
					layout: 'anchor',
					defaultType: 'datefield',
					items:[{
						name: 'date2',
						fieldLabel: 'To',
						width:130,
						labelWidth:30,
						//editable: false,
						id:'tf_tgl_to'
					}]
				}]	
			},{	
				xtype:'combobox',
				fieldLabel:'No. Request',
				// store: statusSIK,
				store: comboNoReq,
				displayField: 'DATA_NAME',
				valueField: 'DATA_VALUE',
				width:300,
				labelWidth:95,
				// id:'cb_status',
				emptyText : '- All -',
				id:'cb_noreq',
				// value:'All',
				value:'',
				// editable: false,
				enableKeyEvents : true,
				minChars:1,
			},{
				xtype:'combobox',
				fieldLabel:'Departemen',
				store: comboDept,
				displayField: 'DATA_NAME',
				valueField: 'DATA_VALUE',
				width:300,
				labelWidth:95,
				id:'cb_dept',
				value:'',
				// emptyText : '- Pilih -',
				emptyText : '- All -',
				// editable: false
				enableKeyEvents : true,
				minChars:1,
			},{
				xtype:'combobox',
				// fieldLabel:'Plant',
				fieldLabel:'Nama Karyawan',
				// store: comboPlant,
				store: comboKaryawan,
				displayField: 'DATA_NAME',
				valueField: 'DATA_VALUE',
				width:400,
				labelWidth:95,
				id:'cb_nama',
				value:'',
				// emptyText : '- Pilih -',
				emptyText : '- All -',
				// editable: false 
				enableKeyEvents : true,
				minChars:1,
			},{
				xtype:'combobox',
				// fieldLabel:'No Dok',
				fieldLabel:'Tipe',
				// store: comboIjin,
				store: comboTipe,
				displayField: 'DATA_NAME',
				valueField: 'DATA_VALUE',
				width:300,
				labelWidth:95,
				// id:'cb_nodok',
				id:'cb_tipe',
				value:'',
				// emptyText : '- Pilih -',
				emptyText : '- All -',
				// editable: false 
				enableKeyEvents : true,
				minChars:1,
			},{
				xtype:'combobox',
				fieldLabel:'Status',
				// store: comboIjin,
				store: comboStatus,
				displayField: 'DATA_NAME',
				valueField: 'DATA_VALUE',
				width:300,
				labelWidth:95,
				id:'cb_status',
				value:'',
				// emptyText : '- Pilih -',
				emptyText : '- All -',
				// editable: false 
				enableKeyEvents : true,
				minChars:1,
			},{
				xtype:'label',
				html:'&nbsp',
			},{
				xtype:'panel',
				bodyStyle: 'padding-left: 0px;padding-bottom: 25px;border:none',
				items:[{
					xtype:'button',
					// text:'Filter',
					text:'View',
					width:100,
					handler:function(){
						maskgrid = new Ext.LoadMask(Ext.getCmp('grid_monitoring_id'), {msg: "Memuat . . ."});
						maskgrid.show();
						store_monitoring.load({
							params:{
								param_tglfrom : Ext.getCmp('tf_tgl_from').getRawValue(),
								param_tglto : Ext.getCmp('tf_tgl_to').getRawValue(),
								param_cb_noreq : Ext.getCmp('cb_noreq').getValue(),
								param_dept : Ext.getCmp('cb_dept').getValue(),
								param_nama : Ext.getCmp('cb_nama').getValue(),
								param_tipe : Ext.getCmp('cb_tipe').getValue(),
								param_status : Ext.getCmp('cb_status').getValue()																
							}
						});
					}
				},{
				xtype:'label',
				html:'&nbsp',
			},{
				xtype:'label',
				html:'&nbsp',
			},{
					xtype:'button',
					text:'Reset',
					width:100,
					handler:function(){
						
						Ext.getCmp('tf_tgl_from').setValue( currentTime.getDate() ),
						Ext.getCmp('tf_tgl_to').setValue( currentTime.getDate() ),
						Ext.getCmp('cb_noreq').setValue(''),
						Ext.getCmp('cb_dept').setValue(''),
						Ext.getCmp('cb_nama').setValue(''),
						Ext.getCmp('cb_tipe').setValue(''),
						Ext.getCmp('cb_status').setValue('')
						
					}
				},{
				xtype:'label',
				html:'&nbsp',
			},{
				xtype:'label',
				html:'&nbsp',
			},{
				xtype:'button',
				text:'To Excel',
				width:100,
				handler:function() {
					
					if(Ext.getCmp('tf_tgl_from').getValue() != '' && Ext.getCmp('tf_tgl_to').getValue() != '') {
						
						var param_tglfrom = Ext.getCmp('tf_tgl_from').getRawValue();
						var param_tglto = Ext.getCmp('tf_tgl_to').getRawValue();
						var param_cb_noreq = Ext.getCmp('cb_noreq').getValue();
						var param_dept = Ext.getCmp('cb_dept').getValue();
						var param_nama = Ext.getCmp('cb_nama').getValue();
						var param_tipe = Ext.getCmp('cb_tipe').getValue();
						var param_status = Ext.getCmp('cb_status').getValue();
						
						window.open("isi_excel_monitoring.php?param_tglfrom=" + param_tglfrom + "&param_tglto=" + param_tglto + "&param_cb_noreq=" + param_cb_noreq + "&param_dept=" + param_dept + "&param_nama=" + param_nama + "&param_tipe=" + param_tipe + "&param_status=" + param_status + "");
						
					} else {
						alertDialog('Warning','Periode belum dipilih.');
					}
				}
			}]
			}, grid_monitoring,{
				xtype:'label',
				html:'&nbsp',
			}],
		});	
	Ext.getCmp('tf_tgl_from').setValue(currentTime);
	Ext.getCmp('tf_tgl_to').setValue(currentTime);
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