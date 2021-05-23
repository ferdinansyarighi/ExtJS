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
	var comboPerusahaan = Ext.create('Ext.data.Store', {
		fields: [{
			name:'DATA_VALUE'
		},{
			name:'DATA_NAME'
		}],
		proxy:{
			type:'ajax',
			url:'<?PHP echo PATHAPP ?>/combobox/combobox_perusahaan.php', 
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
			name:'DATA_TRP'
		},{
			name:'DATA_UANGCUTI'
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
			dataIndex:'DATA_TAHUNAN',
			header:'Saldo Cuti',
			width:150
		},{
			dataIndex:'DATA_TRP',
			header:'TRP',
			width:150
		},{
			dataIndex:'DATA_UANGCUTI',
			header:'Total Uang Cuti',
			width:150
		}]
	});
	var currentTime = new Date();
	var contentPanel = Ext.create('Ext.panel.Panel',{
		bodyStyle: 'spacing: 10px;border:none',
		items:[{
			xtype:'label',
			html:'<div align="center"><font size="5"><b>Report Penguangan Cuti Karyawan</b></font></div>',
		},{
			xtype:'label',
			html:'&nbsp',
		},{		
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
			// editable: false 
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
			fieldLabel:'Perusahaan',
			store: comboPerusahaan,
			displayField: 'DATA_NAME',
			valueField: 'DATA_VALUE',
			width:300,
			labelWidth:75,
			id:'cb_perusahaan',
			value:'',
			emptyText : '- Pilih -',
			//editable: false 
			enableKeyEvents : true,
			minChars:1,
			listeners: {
				keypress : {
					element: 'body',
					fn: function(){
						var nama_plant=Ext.getCmp('cb_perusahaan').getValue();
						comboPerusahaan.load({
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
				keypress : {
					element: 'body',
					fn: function(){
						var pjname=Ext.getCmp('cb_dept').getValue();
						comboDept.load({
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
					maskgrid = new Ext.LoadMask(Ext.getCmp('grid_cuti_id'), {msg: "Memuat . . ."});
					maskgrid.show();
					store_cuti.load({
						params:{
							nama:Ext.getCmp('cb_nama').getValue(),
							perusahaan:Ext.getCmp('cb_perusahaan').getValue(),
							dept:Ext.getCmp('cb_dept').getValue(),
						}
					});
				}
			},{
				xtype:'label',
				html:'&nbsp',
			},{
				xtype:'button',
				text:'Excel',
				width:100,
				handler:function(){
					var nama=Ext.getCmp('cb_nama').getValue();
					var perusahaan=Ext.getCmp('cb_perusahaan').getValue();
					var dept=Ext.getCmp('cb_dept').getValue();
					window.open("isi_excel_cuti.php?nama=" + nama + "&perusahaan=" + perusahaan + "&dept=" + dept + "");
				}
			},{
				xtype:'label',
				html:'&nbsp',
			},{
				xtype:'button',
				text:'CSV',
				width:100,
				handler:function(){
					var nama=Ext.getCmp('cb_nama').getValue();
					var perusahaan=Ext.getCmp('cb_perusahaan').getValue();
					var dept=Ext.getCmp('cb_dept').getValue();
					window.open("isi_csv_cuti.php?nama=" + nama + "&perusahaan=" + perusahaan + "&dept=" + dept + "");
				}
			}]
		}, grid_cuti,{
			xtype:'label',
			html:'&nbsp',
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