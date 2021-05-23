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
	var comboPlant = Ext.create('Ext.data.Store', {
		fields: [{
			name:'DATA_VALUE'
		},{
			name:'DATA_NAME'
		}],
		proxy:{
			type:'ajax',
			url:'<?PHP echo PATHAPP ?>/combobox/combobox_plant.php', 
			reader: {
				type: 'json',
				root: 'data', 
				totalProperty:'total'   
			},
		}
	});
	var comboIjin = Ext.create('Ext.data.Store', {
		fields: [{
			name:'DATA_VALUE'
		},{
			name:'DATA_NAME'
		}],
		proxy:{
			type:'ajax',
			url:'<?PHP echo PATHAPP ?>/combobox/combobox_nospl.php', 
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
	var statusAwal = Ext.create('Ext.data.Store', {
		fields: ['DATA_NAME', 'DATA_VALUE'],
		data : [
			{"DATA_NAME":"Semua Status", "DATA_VALUE":"Semua Status"},
			{"DATA_NAME":"INV", "DATA_VALUE":"INV"},
			{"DATA_NAME":"TT", "DATA_VALUE":"TT"}
		]
	});
	var store_monitoring=new Ext.data.JsonStore({
		id:'store_monitoring_id',
		pageSize: 50,
		fields:[{
			name:'HD_ID'
		},{
			name:'DATA_NO_SPL'
		},{
			name:'DATA_NAMA'
		},{
			name:'DATA_PLANT'
		},{
			name:'DATA_TGL_SPL'
		},{
			name:'DATA_JAM_FROM'
		},{
			name:'DATA_JAM_TO'
		},{
			name:'DATA_PEKERJAAN'
		},{
			name:'DATA_STATUS'
		},{
			name:'DATA_TANGGAL'
		},{
			name:'DATA_USER_APP'
		},{
			name:'DATA_PIC_APPROVED'
		},{
			name:'DATA_PIC_DISAPPROVED'
		},{
			name:'DATA_ALASAN'
		},{
			name:'DATA_DEPT'
		}],
		proxy:{
			timeout: 500000,
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
		height:500,
		autoScroll:true,
		loadMask: true,
		columns:[{
			xtype:'rownumberer',
			id:'row_id',
			header:'No',
			width:30
		},{
			dataIndex:'DATA_NO_SPL',
			header:'No Dokumen',
			width:200,
		},{
			dataIndex:'DATA_NAMA',
			header:'Nama',
			width:200,
		},{
			dataIndex:'DATA_DEPT',
			header:'Department',
			width:120
		},{
			dataIndex:'DATA_PLANT',
			header:'Plant',
			width:120
		},{
			dataIndex:'DATA_TGL_SPL',
			header:'Tanggal SPL',
			width:75
		},{
			dataIndex:'DATA_JAM_FROM',
			header:'Jam Awal',
			width:75
		},{
			dataIndex:'DATA_JAM_TO',
			header:'Jam Akhir',
			width:75
		},{
			dataIndex:'DATA_PEKERJAAN',
			header:'Pekerjaan',
			width:100
		},{
			dataIndex:'DATA_STATUS',
			header:'Status',
			align: 'left',
			width:100
		},{
			dataIndex:'DATA_TANGGAL',
			header:'Tanggal Approved Terakhir',
			align: 'left',
			width:100
		},{
			dataIndex:'DATA_USER_APP',
			header:'User Approved Terakhir',
			align: 'left',
			width:150
		},{
			dataIndex:'DATA_PIC_APPROVED',
			header:'Approval Selanjutnya',
			width:150,
			//flex:1
		},{
			dataIndex:'DATA_PIC_DISAPPROVED',
			header:'PIC Disapprove',
			width:150,
			//flex:1
		},{
			dataIndex:'DATA_ALASAN',
			header:'Alasan',
			width:150,
			//flex:1
		}]
	});
	var currentTime = new Date();
	var statusSIK = Ext.create('Ext.data.Store', {
		fields: ['DATA_NAME', 'DATA_VALUE'],
		data : [
			{"DATA_NAME":"All", "DATA_VALUE":"All"},
			{"DATA_NAME":"Approved", "DATA_VALUE":"Approved"},
			{"DATA_NAME":"Disapproved", "DATA_VALUE":"Disapproved"},
			{"DATA_NAME":"In process", "DATA_VALUE":"In process"},
			{"DATA_NAME":"Inactive", "DATA_VALUE":"Inactive"},
		]
	});
	var contentPanel = Ext.create('Ext.panel.Panel',{
			bodyStyle: 'spacing: 10px;border:none',
			items:[{
				xtype:'label',
				html:'<div align="center"><font size="5"><b>Report Monitoring SPL</b></font></div>',
			},{
				xtype:'label',
				html:'&nbsp',
			},{
				layout:'column',
				border:false,
				items:[{
					columnWidth:.2,
					border:false,
					layout: 'anchor',
					defaultType: 'datefield',
					items:[{
						name: 'date1',
						fieldLabel: 'Tanggal SPL',
						width:165,
						labelWidth:75,
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
				fieldLabel:'Status',
				store: statusSIK,
				displayField: 'DATA_NAME',
				valueField: 'DATA_VALUE',
				width:300,
				labelWidth:75,
				id:'cb_status',
				value:'All',
				editable: false,
			},{		
				xtype:'combobox',
				fieldLabel:'Plant',
				store: comboPlant,
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
				listeners: {
					keypress : {
						element: 'body',
						fn: function(){
							var nama_plant=Ext.getCmp('cb_plant').getValue();
							comboPlant.load({
								params : {
									nama_plant:nama_plant,
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
				fieldLabel:'No Dok',
				store: comboIjin,
				displayField: 'DATA_NAME',
				valueField: 'DATA_VALUE',
				width:300,
				labelWidth:75,
				id:'cb_nodok',
				value:'',
				emptyText : '- Pilih -',
				//editable: false 
				enableKeyEvents : true,
				minChars:1,
				listeners: {
					keypress : {
						element: 'body',
						fn: function(){
							var pjname=Ext.getCmp('cb_nodok').getValue();
							comboIjin.load({
								params : {
									pjname:pjname,
								}
							});
						}
					}
				}
			},{		
				xtype:'combobox',
				fieldLabel:'Pemohon',
				store: comboPemohon,
				displayField: 'DATA_NAME',
				valueField: 'DATA_VALUE',
				width:300,
				labelWidth:75,
				id:'cb_pemohon',
				value:'',
				emptyText : '- Pilih -',
				// queryMode : 'local',
				//editable: false,
				enableKeyEvents : true,
				minChars:1,
				listeners: {
					keypress : {
						element: 'body',
						fn: function(){
							var pjname=Ext.getCmp('cb_pemohon').getValue();
							comboPemohon.load({
								params : {
									pjname:pjname,
								}
							});
						}
					}
				}
			},{
				xtype:'label',
				html:'&nbsp',
			},{
				xtype:'panel',
				bodyStyle: 'padding-left: 0px;padding-bottom: 25px;border:none',
				items:[{
					xtype:'button',
					text:'Filter',
					width:100,
					handler:function(){
						maskgrid = new Ext.LoadMask(Ext.getCmp('grid_monitoring_id'), {msg: "Memuat . . ."});
						maskgrid.show();
						store_monitoring.load({
							params:{
								status:Ext.getCmp('cb_status').getValue(),
								tglfrom:Ext.getCmp('tf_tgl_from').getRawValue(),
								tglto:Ext.getCmp('tf_tgl_to').getRawValue(),
								plant:Ext.getCmp('cb_plant').getValue(),
								noDok:Ext.getCmp('cb_nodok').getValue(),
								pemohon:Ext.getCmp('cb_pemohon').getValue(),
								dept:Ext.getCmp('cb_dept').getValue(),
							}
						});
					}
				}]
			}, grid_monitoring,{
				xtype:'label',
				html:'&nbsp',
			}],
		});	
	Ext.getCmp('tf_tgl_from').setValue(currentTime);
	Ext.getCmp('tf_tgl_to').setValue(currentTime);
	comboPemohon.load();
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