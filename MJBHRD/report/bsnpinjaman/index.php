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
		Ext.getCmp('tf_perusahaan').setValue('')
		Ext.getCmp('tf_dept').setValue('')
		Ext.getCmp('cb_namaKaryawan').setValue('')
		Ext.getCmp('tf_tgl_from').setValue('')
		Ext.getCmp('tf_tgl_to').setValue('')
		Ext.getCmp('cb_bulan_from').setValue('')
		Ext.getCmp('cb_bulan_to').setValue('')
		//Ext.getCmp('cb_tahun_from').setValue(currentTime.getFullYear())
		Ext.getCmp('cb_tahun_from').setValue('')
		//Ext.getCmp('cb_tahun_to').setValue(currentTime.getFullYear())
		Ext.getCmp('cb_tahun_to').setValue('')
		Ext.getCmp('cb_status').setValue('ALL')
		store_detil.removeAll();
		store_detil.load({
			params:{
				tglfrom:Ext.getCmp('tf_tgl_from').getRawValue(),
				tglto:Ext.getCmp('tf_tgl_to').getRawValue(),
				bulanfrom:Ext.getCmp('cb_bulan_from').getValue(),
				bulanto:Ext.getCmp('cb_bulan_to').getValue(),
				tahunfrom:Ext.getCmp('cb_tahun_from').getRawValue(),
				tahunto:Ext.getCmp('cb_tahun_to').getRawValue(),
				perusahaan:Ext.getCmp('tf_perusahaan').getValue(),
				dept:Ext.getCmp('tf_dept').getValue(),
				pemohon:Ext.getCmp('cb_namaKaryawan').getValue(),
				status:Ext.getCmp('cb_status').getValue(),
			}
		});
	};
	
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
	var comboPemohon = Ext.create('Ext.data.Store', {
		fields: [{
			name:'DATA_VALUE'
		},{
			name:'DATA_NAME'
		}],
		proxy:{
			type:'ajax',
			url:'<?PHP echo PATHAPP ?>/combobox/combobox_pemohon_hrd_id.php', 
			reader: {
				type: 'json',
				root: 'data', 
				totalProperty:'total'   
			},
		}
	});
	var comboBulan = Ext.create('Ext.data.Store', {
		fields: ['DATA_NAME', 'DATA_VALUE'],
		data : [
			{"DATA_NAME":"January", "DATA_VALUE":"01"},
			{"DATA_NAME":"February", "DATA_VALUE":"02"},
			{"DATA_NAME":"March", "DATA_VALUE":"03"},
			{"DATA_NAME":"April", "DATA_VALUE":"04"},
			{"DATA_NAME":"May", "DATA_VALUE":"05"},
			{"DATA_NAME":"June", "DATA_VALUE":"06"},
			{"DATA_NAME":"July", "DATA_VALUE":"07"},
			{"DATA_NAME":"August", "DATA_VALUE":"08"},
			{"DATA_NAME":"September", "DATA_VALUE":"09"},
			{"DATA_NAME":"October", "DATA_VALUE":"10"},
			{"DATA_NAME":"November", "DATA_VALUE":"11"},
			{"DATA_NAME":"December", "DATA_VALUE":"12"},
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
	var comboTipeBS = Ext.create('Ext.data.Store', {
		fields: ['DATA_NAME', 'DATA_VALUE'],
		data : [
			{"DATA_NAME":"All", "DATA_VALUE":"ALL"},
			{"DATA_NAME":"Active", "DATA_VALUE":"A"},
			{"DATA_NAME":"Inactive", "DATA_VALUE":"I"}
		]
	});
	var store_detil=new Ext.data.JsonStore({
		id:'store_cari_id',
		pageSize: 100,
		model: 'Plant',
		fields:[{
			name:'HD_ID'
		},{
			name:'DATA_PERSON_ID'
		},{
			name:'DATA_PERUSAHAAN'
		},{
			name:'DATA_DEPT'
		},{
			name:'DATA_NAMA'
		},{
			name:'DATA_NAMA_PINJAMAN'
		},{
			name:'DATA_NOMINAL'
		},{
			name:'DATA_CICILAN'
		},{
			name:'DATA_NOMINAL_CICILAN'
		},{
			name:'DATA_OUTSTANDING'
		},{
			name:'DATA_STATUS'
		},{
			name:'DATA_TGL_INPUT_PINJAMAN'
		},{
			name:'DATA_TGL_POTONGAN'
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
        title: 'Data BS dan Pinjaman',
        frame: false,
		//selModel:sm,
        loadMask: true,
        columns: [{
			dataIndex:'DATA_PERSON_ID',
			header:'No.',
			hidden:true,
			width:40,
		},{
			xtype:'rownumberer',
			id:'row_id',
			header:'No',
			width:40,
		},{
			dataIndex:'HD_ID',
			header:'No. BS',
			//hidden:true,
			width:40,
		},{
			dataIndex:'DATA_PERUSAHAAN',
			header:'Perusahaan',
			//hidden:true,
			width:150,
		},{
			dataIndex:'DATA_DEPT',
			header:'Departemen',
			//hidden:true,
			width:150,
		},{
			dataIndex:'DATA_NAMA',
			header:'Nama Karyawan',
			//hidden:true,
			width:175,
		},{
			dataIndex:'DATA_NAMA_PINJAMAN',
			header:'Pinjaman/BS',
			//hidden:true,
			width:100,
		},{
			dataIndex:'DATA_NOMINAL',
			header:'Nominal',
			//hidden:true,
			width:100,
		},{
			dataIndex:'DATA_CICILAN',
			header:'Cicilan',
			//hidden:true,
			width:75,
		},{
			dataIndex:'DATA_NOMINAL_CICILAN',
			header:'Nominal Cicilan',
			//hidden:true,
			width:100,
		},{
			dataIndex:'DATA_OUTSTANDING',
			header:'Outstanding',
			//hidden:true,
			width:100,
		},{
			dataIndex:'DATA_STATUS',
			header:'Status',
			//hidden:true,
			width:75,
		},{
			dataIndex:'DATA_TGL_INPUT_PINJAMAN',
			header:'Tgl Input Pinjaman',
			//hidden:true,
			width:120,
		},{
			dataIndex:'DATA_TGL_POTONGAN',
			header:'Tgl Potongan',
			//hidden:true,
			width:120,
		}],
        //plugins: [cellEditing],
		/* listeners:{
			cellclick:function(grid,row,col){
				//alert(col);
				if (col==11) {
					//var boo=Ext.getCmp('cb_urgent').getValue();
					var bool=grid_detil.getSelectionModel().getSelection()[0].get('DATA_URGENT');
					console.log(bool);
					if(bool){
						Ext.getCmp('tgl_keb').setMinValue(Ext.Date.add(new Date(), Ext.Date.DAY, 1));
					} else {
						Ext.getCmp('tgl_keb').setMinValue(Ext.Date.add(new Date(), Ext.Date.DAY, 3));
					}
				} else if (col==13) {
					var userID=grid_detil.getSelectionModel().getSelection()[0].get('HD_ID');
					var userRecord = GetUserRecord(userID);
					store_detil.remove(userRecord);
					//rowGrid--;
				}
			}
		} */
    });
	var contentPanel = Ext.create('Ext.panel.Panel',{
			//title:'Form Input Tanda Terima',
			//region:'center',
			bodyStyle: 'spacing: 10px;border:none',
			items:[{
				xtype:'label',
				html:'<div align="center"><font size="5"><b>Report BS dan Pinjaman</b></font></div>',
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
					columnWidth:.02,
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
						fieldLabel:'Perusahaan',
						width:340,
						labelWidth:140,
						id:'tf_perusahaan',
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
					columnWidth:.02,
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
						fieldLabel:'Departemen',
						width:450,
						labelWidth:140,
						id:'tf_dept',
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
						labelWidth:140,
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
					columnWidth:.3,
					border:false,
					layout: 'anchor',
					defaultType: 'combobox',
					items:[{
						xtype:'datefield',
						fieldLabel:'Periode Pinjaman',
						width:250,
						labelWidth:140,
						id:'tf_tgl_from',
						editable: false,
						format: 'd-m-Y'
					}]
				},{
					columnWidth:.17,
					border:false,
					layout: 'anchor',
					defaultType: 'combobox',
					items:[{
						xtype:'datefield',
						fieldLabel:'s/d',
						width:135,
						labelWidth:25,
						id:'tf_tgl_to',
						editable: false,
						format: 'd-m-Y'
					}]
				},{
					columnWidth:.25,
					border:false,
					layout: 'anchor',
					defaultType: 'combobox',
					items:[{
						xtype:'button',
						text:'X',
						width:35,
						id:'btn_x',
						handler:function(){
							Ext.getCmp('tf_tgl_from').setValue('');
							Ext.getCmp('tf_tgl_to').setValue('');
						} 
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
					columnWidth:.3,
					border:false,
					layout: 'anchor',
					defaultType: 'combobox',
					items:[{
						xtype:'combobox',
						fieldLabel:'Periode Pemotongan',
						width:250,
						labelWidth:140,
						id:'cb_bulan_from',
						editable: false,
						store: comboBulan,
						displayField: 'DATA_NAME',
						valueField: 'DATA_VALUE',
						//value: '01'
					}]
				},{
					columnWidth:.1,
					border:false,
					layout: 'anchor',
					defaultType: 'combobox',
					items:[{
						xtype:'combobox',
						//fieldLabel:'Periode Pemotongan',
						width:70,
						labelWidth:140,
						id:'cb_tahun_from',
						editable: false,
						store: comboTahun,
						displayField: 'DATA_NAME',
						valueField: 'DATA_VALUE',
						//value: currentTime.getFullYear()
					}]
				},{
					columnWidth:.168,
					border:false,
					layout: 'anchor',
					defaultType: 'combobox',
					items:[{
						xtype:'combobox',
						fieldLabel:'s/d',
						width:135,
						labelWidth:30,
						id:'cb_bulan_to',
						editable: false,
						store: comboBulan,
						displayField: 'DATA_NAME',
						valueField: 'DATA_VALUE',
						//value: '01'
					}]
				},{
					columnWidth:.1,
					border:false,
					layout: 'anchor',
					defaultType: 'combobox',
					items:[{
						xtype:'combobox',
						//fieldLabel:'Periode Pemotongan',
						width:70,
						labelWidth:140,
						id:'cb_tahun_to',
						editable: false,
						store: comboTahun,
						displayField: 'DATA_NAME',
						valueField: 'DATA_VALUE',
						//value: currentTime.getFullYear()
					}]
				},{
					columnWidth:.25,
					border:false,
					layout: 'anchor',
					defaultType: 'combobox',
					items:[{
						xtype:'button',
						text:'X',
						width:35,
						id:'btn_x2',
						handler:function(){
							Ext.getCmp('cb_bulan_from').setValue('');
							Ext.getCmp('cb_tahun_from').setValue('');
							Ext.getCmp('cb_bulan_to').setValue('');
							Ext.getCmp('cb_tahun_to').setValue('');
						} 
					}]
				}]	
			},{
				layout:'column',
				border:false,
				items:[,{
					columnWidth:.02,
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
						fieldLabel:'Status',
						id:'cb_status',
						store: comboTipeBS,
						displayField: 'DATA_NAME',
						valueField: 'DATA_VALUE',
						value:'ALL',
						minChars:1,
						editable:false,
						width:340,
						labelWidth:140,
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
									tglfrom:Ext.getCmp('tf_tgl_from').getRawValue(),
									tglto:Ext.getCmp('tf_tgl_to').getRawValue(),
									bulanfrom:Ext.getCmp('cb_bulan_from').getValue(),
									bulanto:Ext.getCmp('cb_bulan_to').getValue(),
									tahunfrom:Ext.getCmp('cb_tahun_from').getRawValue(),
									tahunto:Ext.getCmp('cb_tahun_to').getRawValue(),
									perusahaan:Ext.getCmp('tf_perusahaan').getValue(),
									dept:Ext.getCmp('tf_dept').getValue(),
									pemohon:Ext.getCmp('cb_namaKaryawan').getValue(),
									status:Ext.getCmp('cb_status').getValue(),
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
							var tglfrom = Ext.getCmp('tf_tgl_from').getRawValue();
							var tglto = Ext.getCmp('tf_tgl_to').getRawValue();
							var bulanfrom = Ext.getCmp('cb_bulan_from').getValue();
							var bulanfromRaw = Ext.getCmp('cb_bulan_from').getRawValue();
							var bulanto = Ext.getCmp('cb_bulan_to').getValue();
							var bulantoRaw = Ext.getCmp('cb_bulan_to').getRawValue();
							var tahunfrom = Ext.getCmp('cb_tahun_from').getRawValue();
							var tahunto = Ext.getCmp('cb_tahun_to').getRawValue();
							var perusahaan = Ext.getCmp('tf_perusahaan').getValue();
							var perusahaanRaw = Ext.getCmp('tf_perusahaan').getRawValue();
							var dept = Ext.getCmp('tf_dept').getValue();
							var deptRaw = Ext.getCmp('tf_dept').getRawValue();
							var pemohon = Ext.getCmp('cb_namaKaryawan').getValue();
							var pemohonRaw = Ext.getCmp('cb_namaKaryawan').getRawValue();
							var status = Ext.getCmp('cb_status').getValue();
							var statusRaw = Ext.getCmp('cb_status').getRawValue();
							
							window.open("isi_excel_rekapbs.php?tglfrom=" + tglfrom + "&tglto=" + tglto + "&bulanfrom=" + bulanfrom + "&bulanto=" + bulanto + "&tahunfrom=" + tahunfrom + "&tahunto=" + tahunto + "&perusahaan=" + perusahaan + "&dept=" + dept + "&pemohon=" + pemohon + "&status=" + status + "&perusahaanRaw=" + perusahaanRaw+ "&deptRaw=" + deptRaw + "&pemohonRaw=" + pemohonRaw + "&bulanfromRaw=" + bulanfromRaw + "&bulantoRaw=" + bulantoRaw + "&statusRaw=" + statusRaw);
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