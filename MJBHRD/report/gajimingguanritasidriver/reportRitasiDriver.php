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
	var currentTime = new Date();
	var comboPlantRitasi = Ext.create('Ext.data.Store', {
		fields: [{
			name:'DATA_VALUE'
		},{
			name:'DATA_NAME'
		}],
		proxy:{
			type:'ajax',
			url:'<?PHP echo PATHAPP ?>/combobox/combobox_plant_ritasi.php', 
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
			url:'<?PHP echo PATHAPP ?>/combobox/combobox_perioderitasi.php', 
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
	var comboNamaDrive = Ext.create('Ext.data.Store', {
		fields: [{
			name:'DATA_VALUE'
		},{
			name:'DATA_NAME'
		}],
		proxy:{
			type:'ajax',
			url:'<?PHP echo PATHAPP ?>/combobox/combobox_namadriver.php', 
			reader: {
				type: 'json',
				root: 'data', 
				totalProperty:'total'   
			},
		}
	});
	var store_excel=new Ext.data.JsonStore({
		id:'store_excel_id',
		pageSize: 50,
		fields:[{
			name:'DATA_NAMA'
		},{
			name:'DATA_RITASI_KE'
		},{
			name:'DATA_LEMBUR'
		},{
			name:'DATA_NOMINAL'
		},{
			name:'DATA_KEKURANGAN'
		},{
			name:'DATA_UM'
		},{
			name:'DATA_TOTAL'
		}],
		proxy:{
			type:'ajax',
			url:'isi_grid_excel.php', 
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
	var grid_excel=Ext.create('Ext.grid.Panel',{
		id:'grid_excel_id',
		region:'center',
		store:store_excel,
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
			text: 'Values',
			columns:[{
				dataIndex:'DATA_NAMA',
				header:'Row Labels',
				width:200,
			},{
				text: 'Count of',
				columns:[{
					dataIndex:'DATA_RITASI_KE',
					header:'Ritasi Ke-',
					align:'center',
					width:100,
				}]
			},{
				text: 'Sum of',
				columns:[{
					dataIndex:'DATA_NOMINAL',
					header:'Nominal Ritasi',
					align:'right',
					width:100,
				}]
			},{
				text: 'Sum of',
				columns:[{
					dataIndex:'DATA_LEMBUR',
					header:'Uang Lembur',
					align:'right',
					width:130,
				}]
			},{
				text: 'Sum of',
				columns:[{
					dataIndex:'DATA_UM',
					header:'Uang Makan',
					align:'right',
					width:120,
				}]
			},{
				text: 'Sum of Kekurangan',
				columns:[{
					dataIndex:'DATA_KEKURANGAN',
					header:'atau Kelebihan',
					align:'right',
					width:170,
				}]
			},{
				text: 'Sum of Jumlah',
				columns:[{
					dataIndex:'DATA_TOTAL',
					header:'Jumlah Nominal',
					align:'right',
					width:170,
				}]
			}]
		}]
	});
	var store_slip=new Ext.data.JsonStore({
		id:'store_slip_id',
		pageSize: 50,
		fields:[{
			name:'DATA_NAMA'
		},{
			name:'DATA_RITASI_KE'
		},{
			name:'DATA_TGL'
		},{
			name:'DATA_LHO'
		},{
			name:'DATA_VARIABEL'
		},{
			name:'DATA_JAM'
		},{
			name:'DATA_LEMBUR'
		},{
			name:'DATA_UM'
		},{
			name:'DATA_NOMINAL'
		},{
			name:'DATA_TOTAL'
		}],
		proxy:{
			type:'ajax',
			url:'isi_grid_slip.php', 
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
	var grid_slip=Ext.create('Ext.grid.Panel',{
		id:'grid_slip_id',
		region:'center',
		store:store_slip,
        columnLines: true,
		height:500,
		autoScroll:true,
		loadMask: true,
		columns:[{
			xtype:'rownumberer',
			id:'row_idslip',
			header:'No',
			width:40
		},{
			dataIndex:'DATA_NAMA',
			header:'Nama Driver',
			width:150,
		},{
			dataIndex:'DATA_TGL',
			header:'Tanggal SJ',
			align:'center',
			width:100,
		},{
			dataIndex:'DATA_RITASI_KE',
			header:'Ritasi Ke-',
			align:'center',
			width:75,
		},{
			dataIndex:'DATA_LHO',
			header:'No. LHO',
			align:'center',
			width:130,
		},{
			text: 'Variabel',
			columns:[{
				dataIndex:'DATA_VARIABEL',
				header:'Solar',
				align:'center',
				width:100,
			}]
		},{
			text: 'Sum of',
			columns:[{
				dataIndex:'DATA_NOMINAL',
				header:'Nominal Ritasi',
				align:'right',
				width:170,
			}]
		},{
			text: 'Sum of',
			columns:[{
				dataIndex:'DATA_JAM',
				header:'Total Jam Kerja',
				align:'center',
				width:100,
			}]
		},{
			text: 'Sum of',
			columns:[{
				dataIndex:'DATA_LEMBUR',
				header:'Uang Lembur',
				align:'right',
				width:170,
			}]
		},{
			text: 'Sum of',
			columns:[{
				dataIndex:'DATA_UM',
				header:'Uang Makan',
				align:'right',
				width:170,
			}]
		},{
			dataIndex:'DATA_TOTAL',
			header:'Jumlah',
			align:'right',
			width:130,
		}]
	});
	var store_rekap=new Ext.data.JsonStore({
		id:'store_rekap_id',
		pageSize: 100,
		fields:[{
			name:'DATA_NAMA'
		},{
			name:'DATA_SJ'
		},{
			name:'DATA_TGL'
		},{
			name:'DATA_JAM'
		},{
			name:'DATA_PLANT'
		},{
			name:'DATA_CUST'
		},{
			name:'DATA_PROYEK'
		},{
			name:'DATA_VOL'
		},{
			name:'DATA_VOL_RETUR'
		},{
			name:'DATA_SOPIR'
		},{
			name:'DATA_TRUK'
		},{
			name:'DATA_PLANT_CODE'
		},{
			name:'DATA_LHO'
		},{
			name:'DATA_KM_AWAL'
		},{
			name:'DATA_KM_AKHIR'
		},{
			name:'DATA_SOLAR'
		},{
			name:'DATA_VARIABEL'
		},{
			name:'DATA_RITASI_KE'
		},{
			name:'DATA_PLANT_CODE'
		},{
			name:'DATA_NOMINAL'
		},{
			name:'DATA_TOTAL'
		}],
		 proxy:{
			type:'ajax',
			url:'isi_grid_rekap.php', 
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
	var grid_rekap=Ext.create('Ext.grid.Panel',{
		id:'grid_rekap_id',
		region:'center',
		store:store_rekap,
        columnLines: true,
		height:500,
        loadMask: true,
		columns:[{
			xtype:'rownumberer',
			id:'row_idRekap',
			header:'No',
			width:50
		},{
			dataIndex:'DATA_NAMA',
			header:'Nama Driver',
			width:130,
		},{
			dataIndex:'DATA_SJ',
			header:'No Surat Jalan',
			width:150,
			//hidden:true
		},{
			dataIndex:'DATA_LHO',
			header:'Nomor LHO',
			width:70,
			// hidden:true
		},{
			dataIndex:'DATA_KM_AWAL',
			header:'KM Awal',
			width:70,
			// hidden:true
		},{
			dataIndex:'DATA_KM_AKHIR',
			header:'KM Akhir',
			width:70,
			// hidden:true
		},{
			dataIndex:'DATA_SOLAR',
			header:'Solar',
			width:70,
			// hidden:true
		},{
			dataIndex:'DATA_VARIABEL',
			header:'Variabel',
			width:70,
			// hidden:true
		},{
			dataIndex:'DATA_RITASI_KE',
			header:'Ritasi Ke',
			width:70,
			// hidden:true
		},{
			dataIndex:'DATA_NOMINAL',
			header:'Nominal',
			align:'right',
			width:100,
		},{
			dataIndex:'DATA_TOTAL',
			header:'Total',
			align:'right',
			width:100,
		},{
			dataIndex:'DATA_TGL',
			header:'SJ Date',
			width:90,
			//hidden:true
		},{
			text: 'Jam',
			columns:[{
				dataIndex:'DATA_JAM',
				header:'Berangkat',
				align:'center',
				width:70,
			}]
		},{
			text: 'Plant',
			columns:[{
				dataIndex:'DATA_PLANT_CODE',
				header:'Code',
				align:'center',
				width:70,
			}]
		},{
			dataIndex:'DATA_PLANT',
			header:'Plant',
			width:120,
			//hidden:true
		},{
			dataIndex:'DATA_CUST',
			header:'Customer',
			width:130,
			// hidden:true
		},{
			dataIndex:'DATA_PROYEK',
			header:'Project Location',
			width:130,
			// hidden:true
		},{
			dataIndex:'DATA_VOL',
			header:'Vol SJ',
			align:'right',
			width:60,
			// hidden:true
		},{
			dataIndex:'DATA_VOL_RETUR',
			header:'Vol Retur',
			align:'right',
			width:60,
			// hidden:true
		},{
			dataIndex:'DATA_SOPIR',
			header:'Driver',
			width:100,
			// hidden:true
		},{
			dataIndex:'DATA_TRUK',
			header:'TM',
			width:50,
			// hidden:true
		}],
		// multiSelect:true,
	});
	var kategoriForm = "Excel";
	var contentPanel = Ext.create('Ext.panel.Panel',{
		bodyStyle: 'spacing: 10px;border:none',
		id: "bodi",
		items:[{
			xtype:'label',
			html:'<div align="center"><font size="5"><b>Report Gaji Mingguan Ritasi Driver</b></font></div>',
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
				columnWidth:0.59,
				border:false,
				layout: 'anchor',
				defaultType: 'combobox',
				items:[{
					xtype: 'fieldset',
					flex: 1,
					title: 'Kategori',
					defaultType: 'radio', // each item will be a radio button
					layout: 'anchor',
					items: [{
						xtype: 'radiogroup',
						labelWidth:20,
						items: [
							{boxLabel: 'List Gaji to Excel', id : 'rb_excel', name: 'rb_kategori', inputValue: 1, checked: true},
							{boxLabel: 'List to CSV', id : 'rb_csv', name: 'rb_kategori', inputValue: 2},
							{boxLabel: 'Slip Gaji', id : 'rb_slip', name: 'rb_kategori', inputValue: 3},
							{boxLabel: 'Rekap SJ', id : 'rb_rekap', name: 'rb_kategori', inputValue: 4},
						],
						listeners: {
							change:function(){
								if (Ext.getCmp('rb_excel').getValue()){
									kategoriForm = "Excel";
									Ext.getCmp('gridExcel').show();
									Ext.getCmp('gridSlip').hide();
									Ext.getCmp('gridRekap').hide();
									Ext.getCmp('colRek').hide();
									Ext.getCmp('btn_pdf').hide();
								} else if (Ext.getCmp('rb_csv').getValue()){
									kategoriForm = "CSV";
									Ext.getCmp('gridExcel').show();
									Ext.getCmp('gridSlip').hide();
									Ext.getCmp('gridRekap').hide();
									Ext.getCmp('colRek').show();
									Ext.getCmp('btn_pdf').hide();
								} else if (Ext.getCmp('rb_slip').getValue()){
									kategoriForm = "Slip";
									Ext.getCmp('gridExcel').hide();
									Ext.getCmp('gridSlip').show();
									Ext.getCmp('gridRekap').hide();
									Ext.getCmp('colRek').hide();
									Ext.getCmp('btn_pdf').show();
								} else {
									kategoriForm = "Rekap";
									Ext.getCmp('gridExcel').hide();
									Ext.getCmp('gridSlip').hide();
									Ext.getCmp('gridRekap').show();
									Ext.getCmp('colRek').hide();
									Ext.getCmp('btn_pdf').show();
								}
							}
						}
					}]
				}]
			}]	
		}
		// ,{
			// layout:'column',
			// border:false,
			// items:[{
				// columnWidth:.02,
				// border:false,
				// layout: 'anchor',
				// defaultType: 'label',
				// items:[{
					// xtype:'label',
					// html:'',
				// }]
			// },{
				// columnWidth:.24,
				// border:false,
				// layout: 'anchor',
				// defaultType: 'combobox',
				// items:[{
					// xtype:'datefield',
					// fieldLabel:'Periode',
					// width:200,
					// labelWidth:100,
					// id:'tf_tgl_from',
					// editable: false
				// }]
			// },{
				// columnWidth:.17,
				// border:false,
				// layout: 'anchor',
				// defaultType: 'combobox',
				// items:[{
					// xtype:'datefield',
					// fieldLabel:'s/d',
					// width:135,
					// labelWidth:25,
					// id:'tf_tgl_to',
					// editable: false
				// }]
			// }]	
		// }
		,{
			layout:'column',
			border:false,
			id:'colPeriode',
			hidden:false,
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
					fieldLabel:'Periode',
					store: comboPeriode,
					displayField: 'DATA_NAME',
					valueField: 'DATA_VALUE',
					width:400,
					labelWidth:100,
					id:'tf_periode',
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
			id:'colRek',
			hidden:true,
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
					fieldLabel:'No Rekening',
					store: comboRekening,
					displayField: 'DATA_NAME',
					valueField: 'DATA_VALUE',
					width:400,
					labelWidth:100,
					id:'tf_rekening',
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
					store: comboPlantRitasi,
					displayField: 'DATA_NAME',
					valueField: 'DATA_VALUE',
					width:400,
					labelWidth:100,
					id:'cb_plant',
					value:'',
					emptyText : '- Pilih -',
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
					fieldLabel:'Driver Name',
					store: comboNamaDrive,
					displayField: 'DATA_NAME',
					valueField: 'DATA_VALUE',
					width:400,
					labelWidth:100,
					id:'cb_nama',
					value:'',
					emptyText : '- Pilih -',
					enableKeyEvents : true,
					minChars:1,
				}]
			}]	
		},{
			xtype:'label',
			html:'&nbsp',
		},{
			xtype:'panel',
			bodyStyle: 'padding-left: 0px;padding-bottom: 25px;border:none',
			items:[{
				xtype:'button',
				text:'Show',
				width:100,
				handler:function(){
					if(Ext.getCmp('tf_periode').getValue() != '- Pilih -'){
						if (Ext.getCmp('rb_excel').getValue()){
							maskgrid = new Ext.LoadMask(Ext.getCmp('grid_excel_id'), {msg: "Memuat . . ."});
							maskgrid.show();
							store_excel.load({
								params:{
									tgl_awal:Ext.getCmp('tf_periode').getValue().substring(0, 10),//Ext.Date.dateFormat(Ext.getCmp('tf_tgl_from').getValue(), 'Y-m-d'),
									tgl_akhir:Ext.getCmp('tf_periode').getValue().substring(13, 23),
									plant:Ext.getCmp('cb_plant').getValue(),
									nama:Ext.getCmp('cb_nama').getValue(),
								}
							});
						} else if (Ext.getCmp('rb_csv').getValue()){
							maskgrid = new Ext.LoadMask(Ext.getCmp('grid_excel_id'), {msg: "Memuat . . ."});
							maskgrid.show();
							store_excel.load({
								params:{
									tgl_awal:Ext.getCmp('tf_periode').getValue().substring(0, 10),//Ext.Date.dateFormat(Ext.getCmp('tf_tgl_from').getValue(), 'Y-m-d'),
									tgl_akhir:Ext.getCmp('tf_periode').getValue().substring(13, 23),
									plant:Ext.getCmp('cb_plant').getValue(),
									nama:Ext.getCmp('cb_nama').getValue(),
								}
							});
						} else if (Ext.getCmp('rb_slip').getValue()){
							maskgrid = new Ext.LoadMask(Ext.getCmp('grid_slip_id'), {msg: "Memuat . . ."});
							maskgrid.show();
							store_slip.load({
								params:{
									tgl_awal:Ext.getCmp('tf_periode').getValue().substring(0, 10),//Ext.Date.dateFormat(Ext.getCmp('tf_tgl_from').getValue(), 'Y-m-d'),
									tgl_akhir:Ext.getCmp('tf_periode').getValue().substring(13, 23),
									plant:Ext.getCmp('cb_plant').getValue(),
									nama:Ext.getCmp('cb_nama').getValue(),
								}
							});
						} else {
							maskgrid = new Ext.LoadMask(Ext.getCmp('grid_rekap_id'), {msg: "Memuat . . ."});
							maskgrid.show();
							store_rekap.load({
								params:{
									tgl_awal:Ext.getCmp('tf_periode').getValue().substring(0, 10),//Ext.Date.dateFormat(Ext.getCmp('tf_tgl_from').getValue(), 'Y-m-d'),
									tgl_akhir:Ext.getCmp('tf_periode').getValue().substring(13, 23),
									plant:Ext.getCmp('cb_plant').getValue(),
									nama:Ext.getCmp('cb_nama').getValue(),
								}
							});
						}
					}
					else {
						alertDialog('Peringatan','Periode belum dipilih.');
					}
				}
			},{
				xtype:'label',
				html:'&nbsp',
			},{
				xtype:'button',
				text:'Export',
				width:100,
				handler:function(){
					var tgl_awal=Ext.getCmp('tf_periode').getValue().substring(0, 10);
					var tgl_akhir=Ext.getCmp('tf_periode').getValue().substring(13, 23);
					var plant=Ext.getCmp('cb_plant').getValue();
					var nama=Ext.getCmp('cb_nama').getValue();
					if (Ext.getCmp('rb_excel').getValue()){
						window.open("isi_excel_ritasi.php?tgl_awal=" + tgl_awal + "&tgl_akhir=" + tgl_akhir + "&plant=" + plant + "&nama=" + nama);
					} else if (Ext.getCmp('rb_csv').getValue()){
						var rekening=Ext.getCmp('tf_rekening').getValue();
						window.open("isi_csv_ritasi.php?tgl_awal=" + tgl_awal + "&tgl_akhir=" + tgl_akhir + "&plant=" + plant + "&nama=" + nama + "&rekening=" + rekening);
					} else if (Ext.getCmp('rb_slip').getValue()){
						window.open("isi_slip_ritasi.php?tgl_awal=" + tgl_awal + "&tgl_akhir=" + tgl_akhir + "&plant=" + plant + "&nama=" + nama);
					} else {
						window.open("isi_rekap_ritasi.php?tgl_awal=" + tgl_awal + "&tgl_akhir=" + tgl_akhir + "&plant=" + plant + "&nama=" + nama);
					}
				}
			},{
				xtype:'label',
				html:'&nbsp',
			},{
				xtype:'button',
				text:'PDF',
				width:100,
				id:'btn_pdf',
				hidden:true,
				handler:function(){
					var tgl_awal=Ext.getCmp('tf_periode').getValue().substring(0, 10);
					var tgl_akhir=Ext.getCmp('tf_periode').getValue().substring(13, 23);
					var plant=Ext.getCmp('cb_plant').getValue();
					var nama=Ext.getCmp('cb_nama').getValue();
					if (Ext.getCmp('rb_excel').getValue()){
						// window.open("isi_excel_ritasi.php?tgl_awal=" + tgl_awal + "&tgl_akhir=" + tgl_akhir + "&plant=" + plant + "&nama=" + nama);
					} else if (Ext.getCmp('rb_csv').getValue()){
						// var rekening=Ext.getCmp('tf_rekening').getValue();
						// window.open("isi_csv_ritasi.php?tgl_awal=" + tgl_awal + "&tgl_akhir=" + tgl_akhir + "&plant=" + plant + "&nama=" + nama + "&rekening=" + rekening);
					} else if (Ext.getCmp('rb_slip').getValue()){
						window.open("isi_slip_pdf.php?tgl_awal=" + tgl_awal + "&tgl_akhir=" + tgl_akhir + "&plant=" + plant + "&nama=" + nama);
					} else {
						window.open("isi_rekap_pdf.php?tgl_awal=" + tgl_awal + "&tgl_akhir=" + tgl_akhir + "&plant=" + plant + "&nama=" + nama);
					}
				}
			}]
		},{
			xtype:'label',
			html:'&nbsp',
		},{
			layout:'column',
			border:false,
			id:'gridExcel',
			hidden:false,
			items:[{
				columnWidth:.99,
				border:false,
				layout: 'anchor',
				defaultType: 'combobox',
				items:[
					grid_excel
				]
			}]	
		},{
			layout:'column',
			border:false,
			id:'gridSlip',
			hidden:true,
			items:[{
				columnWidth:.99,
				border:false,
				layout: 'anchor',
				defaultType: 'combobox',
				items:[
					grid_slip
				]
			}]	
		},{
			layout:'column',
			border:false,
			id:'gridRekap',
			hidden:true,
			items:[{
				columnWidth:.99,
				border:false,
				layout: 'anchor',
				defaultType: 'combobox',
				items:[
					grid_rekap
				]
			}]	
		},{
			xtype:'label',
			html:'&nbsp',
		}],
	});	
	// Ext.getCmp('tf_tgl_from').setValue(currentTime)
	// Ext.getCmp('tf_tgl_to').setValue(currentTime)
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