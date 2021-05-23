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
			url:'<?PHP echo PATHAPP ?>/combobox/combobox_pemohon.php', 
			reader: {
				type: 'json',
				root: 'data', 
				totalProperty:'total'   
			},
		}
	});

	var comboPengajuan = Ext.create('Ext.data.Store', {
		fields: [{
			name:'DATA_VALUE'
		},{
			name:'DATA_NAME'
		}],
		proxy:{
			type:'ajax',
			url:'<?PHP echo PATHAPP ?>/combobox/combobox_noresign_report.php', 
			reader: {
				type: 'json',
				root: 'data', 
				totalProperty:'total'   
			},
		}
	});

	var comboCompany = Ext.create('Ext.data.Store', {
		fields: [{
			name:'DATA_VALUE'
		},{
			name:'DATA_NAME'
		}],
		proxy:{
			type:'ajax',
			url:'<?PHP echo PATHAPP ?>/combobox/combobox_company.php', 
			reader: {
				type: 'json',
				root: 'data', 
				totalProperty:'total'   
			},
		}
	});
		
	var comboDepartemen = Ext.create('Ext.data.Store', {
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

	var comboLokasi = Ext.create('Ext.data.Store', {
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

	var comboManager = Ext.create('Ext.data.Store', {
		fields: [{
			name:'DATA_VALUE'
		},{
			name:'DATA_NAME'
		}],
		proxy:{
			type:'ajax',
			url:'<?PHP echo PATHAPP ?>/combobox/combobox_manager_advance_by_fed.php', 
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
		fields:[{
			name:'HD_ID'
		},{
			name:'DATA_NO_PENGAJUAN'
		},{
			name:'DATA_NAMA_KARYAWAN'
		},{
			name:'DATA_COMPANY'
		},{
			name:'DATA_DEPT'
		},{
			name:'DATA_POS'
		},{
			name:'DATA_GRADE'
		},{
			name:'DATA_LOCATION'
		},{
			name:'DATA_TGL_MASUK'
		},{
			name:'DATA_TGL_RESIGN'
		},{
			name:'DATA_TAHUN_LAMA_KERJA'
		},{
			name:'DATA_BULAN_LAMA_KERJA'
		},{
			name:'DATA_HARI_LAMA_KERJA'
		},{
			name:'DATA_MANAGER'
		},{
			name:'DATA_KETERANGAN'
		},{
			name:'DATA_STATUS'
		},{
			name:'DATA_APPROVAL_MANAGER'
		},{
			name:'DATA_APPROVAL_MANAGER_HRD'
		},{
			name:'DATA_TGL_APP_TERAKHIR'
		},{
			name:'DATA_CREATED_BY'
		},{
			name:'DATA_CREATED_DATE'
		},{
			name:'DATA_LAMA_KERJA'
		},{
			name:'DATA_KET_DISAPP'
		},{
			name:'DATA_TGL_PENGAJUAN'
		}],
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
		height:500,
		autoScroll:true,
		loadMask: true,
		columns:[{
			xtype:'rownumberer',
			id:'row_id',
			header:'No',
			width:30
		},{
			dataIndex:'HD_ID',
			header:'hdid',
			hidden:true,
			width:50,
		},{
			dataIndex:'DATA_NO_PENGAJUAN',
			header:'No Pengajuan',
			width:200,
		},{
			dataIndex:'DATA_NAMA_KARYAWAN',
			header:'Pemohon',
			width:200,
		},{
			dataIndex:'DATA_COMPANY',
			header:'Perusahaan',
			width:120,
		},{
			dataIndex:'DATA_DEPT',
			header:'Departemen',
			width:120
		},{
			dataIndex:'DATA_POS',
			header:'Jabatan',
			width:120
		},{
			dataIndex:'DATA_GRADE',
			header:'Grade',
			width:100
		},{
			dataIndex:'DATA_LOCATION',
			header:'Lokasi',
			width:75
		},{
			dataIndex:'DATA_TGL_PENGAJUAN',
			header:'Tanggal Pengajuan',
			width:100,
			//hidden:true
		},{
			dataIndex:'DATA_TGL_MASUK',
			header:'Tanggal Masuk',
			width:100,
			//hidden:true
		},{
			dataIndex:'DATA_TGL_RESIGN',
			header:'Tanggal Resign',
			width:100,
			//hidden:true
		},{
			dataIndex:'DATA_LAMA_KERJA',
			header:'Lama Kerja',
			width:175,
			//hidden:true
		},{
			dataIndex:'DATA_TAHUN_LAMA_KERJA',
			header:'Tahun Lama Kerja',
			width:100,
			hidden:true
		},{
			dataIndex:'DATA_BULAN_LAMA_KERJA',
			header:'Bulan Lama Kerja',
			width:100,
			hidden:true
		},{
			dataIndex:'DATA_HARI_LAMA_KERJA',
			header:'Hari Lama Kerja',
			width:175,
			hidden:true
		},{
			dataIndex:'DATA_MANAGER',
			header:'Manager',
			width:150,
			hidden:true
		},{
			dataIndex:'DATA_KETERANGAN',
			header:'Keterangan',
			width:100,
			//hidden:true
		},{
			dataIndex:'DATA_APPROVAL_MANAGER',
			header:'Approval Manager',
			width:125,
			//hidden:true
		},{
			dataIndex:'DATA_APPROVAL_MANAGER_HRD',
			header:'Approval HRD',
			width:100,
			//hidden:true
		},{
			dataIndex:'DATA_STATUS',
			header:'Flag Aktif',
			width:75,
			hidden:true
		},{
			dataIndex:'DATA_TGL_APP_TERAKHIR',
			header:'Tanggal Approve Terakhir',
			width:150,
			//hidden:true
		},{
			dataIndex:'DATA_CREATED_BY',
			header:'Pembuat',
			width:300,
			hidden:true
		},{
			dataIndex:'DATA_CREATED_DATE',
			header:'Tanggal Pembuatan',
			width:75,
			hidden:true
		},{
			dataIndex:'DATA_KET_DISAPP',
			header:'Keterangan Disapprove',
			width:200,
			//hidden:true
		}]
	});

	var currentTime = new Date();

	var contentPanel = Ext.create('Ext.panel.Panel',{
		bodyStyle: 'spacing: 10px;border:none',
		items:[{
			xtype:'label',
			html:'<div align="center"><font size="5"><b>Report Monitoring Resign</b></font></div>',
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
					fieldLabel: 'Periode Resign',
					width:200,
					labelWidth:100,
					id:'tf_tgl_from'
				}]
			},{
				columnWidth:.2,
				border:false,
				layout: 'anchor',
				defaultType: 'datefield',
				items:[{
					name: 'date2',
					fieldLabel: 's/d',
					width:130,
					labelWidth:30,
					id:'tf_tgl_to'
				}]
			}]	
		},{		
				xtype:'combobox',
				fieldLabel:'Pemohon',
				store: comboPemohon,
				displayField: 'DATA_NAME',
				valueField: 'DATA_VALUE',
				width:300,
				labelWidth:100,
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
			xtype:'combobox',
			fieldLabel:'No. Pengajuan',
			store: comboPengajuan,
			displayField: 'DATA_NAME',
			valueField: 'DATA_VALUE',
			width:300,
			labelWidth:100,
			id:'cb_nopengajuan',
			value:'',
			emptyText : '- Pilih -',
			enableKeyEvents : true,
			minChars:1,
		},{		
			xtype:'combobox',
			fieldLabel:'Company',
			store: comboCompany,
			displayField: 'DATA_NAME',
			valueField: 'DATA_VALUE',
			width:300,
			labelWidth:100,
			id:'cb_company',
			value:'',
			emptyText : '- Pilih -',
			enableKeyEvents : true,
			minChars:1,
		},{		
			xtype:'combobox',
			fieldLabel:'Departemen',
			store: comboDepartemen,
			displayField: 'DATA_NAME',
			valueField: 'DATA_VALUE',
			width:300,
			labelWidth:100,
			id:'cb_dept',
			value:'',
			emptyText : '- Pilih -',
			enableKeyEvents : true,
			minChars:1,
		},{		
			xtype:'combobox',
			fieldLabel:'Location',
			store: comboLokasi,
			displayField: 'DATA_NAME',
			valueField: 'DATA_VALUE',
			width:300,
			labelWidth:100,
			id:'cb_location',
			value:'',
			emptyText : '- Pilih -',
			enableKeyEvents : true,
			minChars:1,
		},{		
			xtype:'combobox',
			fieldLabel:'Manager',
			store: comboManager,
			displayField: 'DATA_NAME',
			valueField: 'DATA_VALUE',
			width:300,
			labelWidth:100,
			id:'cb_manager',
			value:'',
			emptyText : '- Pilih -',
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
				text:'Filter',
				width:100,
				handler:function(){
					maskgrid = new Ext.LoadMask(Ext.getCmp('grid_monitoring_id'), {msg: "Memuat . . ."});
					maskgrid.show();
					store_monitoring.load({
						params:{
							tglfrom:Ext.getCmp('tf_tgl_from').getRawValue(),
							tglto:Ext.getCmp('tf_tgl_to').getRawValue(),
							pemohon:Ext.getCmp('cb_pemohon').getRawValue(),
							nopengajuan:Ext.getCmp('cb_nopengajuan').getRawValue(),
							company:Ext.getCmp('cb_company').getRawValue(),
							dept:Ext.getCmp('cb_dept').getRawValue(),
							location:Ext.getCmp('cb_location').getRawValue(),
							manager:Ext.getCmp('cb_manager').getRawValue(),
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
				text:'Export to Excel',
				width:100,
				handler:function(){
					if(Ext.getCmp('tf_tgl_from').getValue() != '' && Ext.getCmp('tf_tgl_to').getValue() != ''){
						var periodedari=Ext.Date.dateFormat(Ext.getCmp('tf_tgl_from').getValue(), 'Y-m-d');
						var periodesampai=Ext.Date.dateFormat(Ext.getCmp('tf_tgl_to').getValue(), 'Y-m-d');
						var pemohon=Ext.getCmp('cb_pemohon').getValue();
						var nopengajuan=Ext.getCmp('cb_nopengajuan').getValue();
						var company=Ext.getCmp('cb_company').getValue();
						var dept=Ext.getCmp('cb_dept').getValue();
						var location=Ext.getCmp('cb_location').getValue();
						var manager=Ext.getCmp('cb_manager').getValue();
						
						window.open("isi_excel_resign.php?periodedar=" + periodedari + "&periodesamp=" + periodesampai + "&pemohon=" + pemohon +  "&nopengajuan=" + nopengajuan 
						+ "&company=" + company + "&dept=" + dept + "&location=" + location + "&manager=" + manager + "");
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