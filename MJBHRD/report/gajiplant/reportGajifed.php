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
	var comboPeriodeGaji = Ext.create('Ext.data.Store', {
		fields: ['DATA_NAME', 'DATA_VALUE'],
		data : [
			{"DATA_NAME":"Bulanan", "DATA_VALUE":"BULANAN"},
			{"DATA_NAME":"Mingguan", "DATA_VALUE":"MINGGUAN"}
		]
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
	var comboPlant = Ext.create('Ext.data.Store', {
		fields: [{
			name:'DATA_VALUE'
		},{
			name:'DATA_NAME'
		}],
		proxy:{
			type:'ajax',
			url:'<?PHP echo PATHAPP ?>/combobox/combobox_plant_element.php', 
			reader: {
				type: 'json',
				root: 'data', 
				totalProperty:'total'   
			},
		}
	});
	var comboGrade = Ext.create('Ext.data.Store', {
		fields: [{
			name:'DATA_VALUE'
		},{
			name:'DATA_NAME'
		}],
		proxy:{
			type:'ajax',
			url:'<?PHP echo PATHAPP ?>/combobox/combobox_grade2.php', 
			reader: {
				type: 'json',
				root: 'data', 
				totalProperty:'total'   
			},
		}
	});
	/* var comboPlant = Ext.create('Ext.data.Store', {
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
	}); */
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
	var comboDept = Ext.create('Ext.data.Store', {
		fields: [{
			name:'DATA_VALUE'
		},{
			name:'DATA_NAME'
		}],
		proxy:{
			type:'ajax',
			url:'<?PHP echo PATHAPP ?>/combobox/combobox_dept_id2.php', 
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
			html:'<div align="center"><font size="5"><b>Report Gaji Karyawan Plant</b></font></div>',
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
				columnWidth:0.99,
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
						labelWidth:50,
						items: [
							{boxLabel: 'Perhitungan Gaji', id : 'rb_perhitungan', name: 'rb_kategori', inputValue: 1, checked: true},
							{boxLabel: 'List Gaji', id : 'rb_list', name: 'rb_kategori', inputValue: 2},
							{boxLabel: 'Compare Gaji', id : 'rb_compare', name: 'rb_kategori', inputValue: 3},
							{boxLabel: 'CSV Transfer', id : 'rb_csv', name: 'rb_kategori', inputValue: 4},
							{boxLabel: 'Slip Gaji Excel', id : 'rb_slip', name: 'rb_kategori', inputValue: 5},
							{boxLabel: 'Slip Gaji PDF', id : 'rb_slip_pdf', name: 'rb_kategori', inputValue: 6},
						],
						listeners: {
							change:function(){
								//Ext.getCmp('btn_revisi').setDisabled(true);
								//Ext.getCmp('btn_simpan').setDisabled(true);
								Ext.getCmp('tf_revisi').setDisabled(false);
								Ext.getCmp('cb_dept').setDisabled(true);
								Ext.getCmp('cb_dept').setValue('');
								Ext.getCmp('tf_revisi').setValue(0);
								if (Ext.getCmp('rb_perhitungan').getValue()){
									kategoriForm = "Slip";
									//Ext.getCmp('btn_revisi').setDisabled(false);
									//Ext.getCmp('btn_simpan').setDisabled(true);
									Ext.getCmp('tf_revisi').setDisabled(true);
								} else if (Ext.getCmp('rb_list').getValue()){
									kategoriForm = "Revisi";
									Ext.getCmp('tf_revisi').setDisabled(false);
								} else if (Ext.getCmp('rb_compare').getValue()){
									kategoriForm = "Compare";
									Ext.getCmp('tf_revisi').setDisabled(true);
								} else if (Ext.getCmp('rb_csv').getValue()){
									kategoriForm = "CSV";
								} else if (Ext.getCmp('rb_slip').getValue()){
									Ext.getCmp('cb_dept').setDisabled(false);
									kategoriForm = "Excel";
								} else {
									Ext.getCmp('cb_dept').setDisabled(false);
									kategoriForm = "PDF";
								}
							}
						}
					}]
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
					fieldLabel:'No Rekening',
					store: comboRekening,
					displayField: 'DATA_NAME',
					valueField: 'DATA_VALUE',
					width:300,
					labelWidth:80,
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
					html:'<div style="color:#FF0000">*</div>',
				}]
			},{
				columnWidth:.8,
				border:false,
				layout: 'anchor',
				defaultType: 'combobox',
				items:[{		
					xtype:'combobox',
					fieldLabel:'Periode Gaji',
					store: comboPeriodeGaji,
					displayField: 'DATA_NAME',
					valueField: 'DATA_VALUE',
					width:300,
					labelWidth:80,
					id:'cb_periode_gaji',
					value:'BULANAN',
					queryMode:'local',
					editable: false,
					enableKeyEvents : true,
					minChars:1,
					listeners: {
						select:function(f,r,i){
							var periode=r[0].data.DATA_VALUE;
							if(periode == 'BULANAN'){
								Ext.getCmp('cb_periode').hide();
								Ext.getCmp('lbl_periode').hide();
								Ext.getCmp('cb_bulan').show();
								Ext.getCmp('lbl_bulan').show();
								Ext.getCmp('cb_tahun').show();
								Ext.getCmp('lbl_tahun').show();
							}else{
								Ext.getCmp('cb_bulan').hide();
								Ext.getCmp('lbl_bulan').hide();
								Ext.getCmp('cb_tahun').hide();
								Ext.getCmp('lbl_tahun').hide();
								Ext.getCmp('cb_periode').show();
								Ext.getCmp('lbl_periode').show();
							}
						},
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
					html:'<div style="color:#FF0000">*</div>',
					id:'lbl_periode',
					hidden:true
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
					width:300,
					labelWidth:80,
					id:'cb_periode',
					value:'- Pilih -',
					//emptyText : '- Pilih -',
					editable: false,
					enableKeyEvents : true,
					minChars:1,
					hidden:true
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
					id:'lbl_bulan',
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
					width:250,
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
					id:'lbl_tahun',
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
					width:180,
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
					html:'<div style="color:#FF0000"></div>',
				}]
			},{
				columnWidth:.8,
				border:false,
				layout: 'anchor',
				defaultType: 'combobox',
				items:[{		
					xtype:'combobox',
					fieldLabel:'Company',
					store: comboCompany,
					displayField: 'DATA_NAME',
					valueField: 'DATA_VALUE',
					width:320,
					labelWidth:80,
					id:'cb_company',
					value:'All',
					//emptyText : '- Pilih -',
					editable: false,
					enableKeyEvents : true,
					minChars:1,
					listeners: {
						select:function(f,r,i){
							var org_id=r[0].data.DATA_VALUE;
							comboPlant.setProxy({
								type:'ajax',
								url:'<?PHP echo PATHAPP ?>/combobox/combobox_plant_element.php?orgid=' + org_id,
								reader: {
									type: 'json',
									root: 'data', 
									totalProperty:'total'   
								}
							});
							comboPlant.load();
						},
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
				columnWidth:.8,
				border:false,
				layout: 'anchor',
				defaultType: 'combobox',
				items:[{
					xtype:'combobox',
					fieldLabel:'Plant',
					store: comboPlant,
					displayField: 'DATA_NAME',
					valueField: 'DATA_VALUE',
					width:300,
					labelWidth:80,
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
					fieldLabel:'Department',
					store: comboDept,
					displayField: 'DATA_NAME',
					valueField: 'DATA_VALUE',
					width:300,
					labelWidth:80,
					id:'cb_dept',
					value:'',
					disabled: true,
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
					fieldLabel:'Grade',
					store: comboGrade,
					displayField: 'DATA_NAME',
					valueField: 'DATA_VALUE',
					width:300,
					labelWidth:80,
					id:'cb_grade',
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
					width:150,
					labelWidth:80,
					id:'tf_revisi',
					value: 0,
					minValue: 0,
					maxValue: 1,
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
				text:'Export',
				width:100,
				handler:function(){
					if(Ext.getCmp('tf_rekening').getValue() != '- Pilih -' || ((Ext.getCmp('cb_bulan').getValue() != '- Pilih -' && Ext.getCmp('cb_tahun').getValue() != '- Pilih -') || Ext.getCmp('cb_periode_gaji').getValue() != 'BULANAN')){
						if(Ext.getCmp('tf_rekening').getValue() != '- Pilih -' && (Ext.getCmp('cb_periode').getValue() != '- Pilih -' || Ext.getCmp('cb_periode_gaji').getValue() != 'MINGGUAN')){
							Ext.Ajax.request({
							url:'<?PHP echo PATHAPP ?>/report/gajiplant/cek_data_double.php',
								timeout: 500000,
								params:{
									periodegaji:Ext.getCmp('cb_periode_gaji').getValue(),
									periode_start:Ext.getCmp('cb_periode').getValue().substring(0, 10),
									periode_end:Ext.getCmp('cb_periode').getValue().substring(13, 23),
									bulan:Ext.getCmp('cb_bulan').getValue(),
									tahun:Ext.getCmp('cb_tahun').getValue(),
									company:Ext.getCmp('cb_company').getValue(),
									plant:Ext.getCmp('cb_plant').getValue(),
									//dept:Ext.getCmp('cb_dept').getValue(),
									grade:Ext.getCmp('cb_grade').getValue(),
									revisi:Ext.getCmp('tf_revisi').getValue(),
								},
								method:'POST',
								success:function(response){
									var json=Ext.decode(response.responseText);
									if (json.results >= 1){
										alertDialog('Peringatan', "Berikut ini adalah karyawan yang namanya muncul lebih dari sekali. Silahkan cek elemen gaji di oracle.<BR> " + json.rows);
										//console.log(Ext.getCmp('rb_perhitungan').getValue());
									} 
									if (Ext.getCmp('rb_perhitungan').getValue()){
										if((Ext.getCmp('cb_bulan').getValue() != '- Pilih -' && Ext.getCmp('cb_tahun').getValue() != '- Pilih -') || Ext.getCmp('cb_periode_gaji').getValue() != 'BULANAN'){
											if(Ext.getCmp('cb_periode').getValue() != '- Pilih -' || Ext.getCmp('cb_periode_gaji').getValue() != 'MINGGUAN'){
												//var rekening=Ext.getCmp('tf_rekening').getValue();
												var periodegaji=Ext.getCmp('cb_periode_gaji').getValue();
												var periode_start=Ext.getCmp('cb_periode').getValue().substring(0, 10);
												var periode_end=Ext.getCmp('cb_periode').getValue().substring(13, 23);
												var bulan=Ext.getCmp('cb_bulan').getValue();
												var tahun=Ext.getCmp('cb_tahun').getValue();
												var company=Ext.getCmp('cb_company').getValue();
												var plant=Ext.getCmp('cb_plant').getValue();
												//var dept=Ext.getCmp('cb_dept').getValue();
												var grade=Ext.getCmp('cb_grade').getValue();
												var revisi=Ext.getCmp('tf_revisi').getValue();
												window.open("isi_slip_gaji.php?periodegaji=" + periodegaji + "&periode_start=" + periode_start + "&periode_end=" + periode_end + "&bulan=" + bulan + "&tahun=" + tahun + "&company=" + company + "&plant=" + plant + "&grade=" + grade);
											}else{
												alertDialog('Warning','Periode harus diisi.');
											}
										} else {
											alertDialog('Warning','Bulan dan Tahun harus diisi.');
										}
									} else if (Ext.getCmp('rb_list').getValue()){
										if((Ext.getCmp('cb_bulan').getValue() != '- Pilih -' && Ext.getCmp('cb_tahun').getValue() != '- Pilih -') || Ext.getCmp('cb_periode_gaji').getValue() != 'BULANAN'){
											if(Ext.getCmp('cb_periode').getValue() != '- Pilih -' || Ext.getCmp('cb_periode_gaji').getValue() != 'MINGGUAN'){
												Ext.Ajax.request({
												url:'<?PHP echo PATHAPP ?>/report/gajiplant/cek_data_revisi.php',
													timeout: 500000,
													params:{
														periodegaji:Ext.getCmp('cb_periode_gaji').getValue(),
														//periode:Ext.getCmp('cb_periode').getValue(),
														periode_start:Ext.getCmp('cb_periode').getValue().substring(0, 10),
														periode_end:Ext.getCmp('cb_periode').getValue().substring(13, 23),
														bulan:Ext.getCmp('cb_bulan').getValue(),
														tahun:Ext.getCmp('cb_tahun').getValue(),
														company:Ext.getCmp('cb_company').getValue(),
														plant:Ext.getCmp('cb_plant').getValue(),
														revisi:Ext.getCmp('tf_revisi').getValue(),
													},
													method:'POST',
													success:function(response){
														var json=Ext.decode(response.responseText);
														if (json.rows == "sukses"){
															var periodegaji=Ext.getCmp('cb_periode_gaji').getValue();
															var periode_start=Ext.getCmp('cb_periode').getValue().substring(0, 10);
															var periode_end=Ext.getCmp('cb_periode').getValue().substring(13, 23);
															var bulan=Ext.getCmp('cb_bulan').getValue();
															var tahun=Ext.getCmp('cb_tahun').getValue();
															var company=Ext.getCmp('cb_company').getValue();
															var plant=Ext.getCmp('cb_plant').getValue();
															//var dept=Ext.getCmp('cb_dept').getValue();
															var grade=Ext.getCmp('cb_grade').getValue();
															var revisi=Ext.getCmp('tf_revisi').getValue();
															window.open("isi_list_gaji.php?periodegaji=" + periodegaji + "&periode_start=" + periode_start + "&periode_end=" + periode_end + "&bulan=" + bulan + "&tahun=" + tahun + "&company=" + company + "&plant=" + plant + "&grade=" + grade + "&revisi=" + revisi);
														} else {
															alertDialog('Kesalahan', "Data tidak ditemukan. ");
														} 
													},
													failure:function(error){
														alertDialog('Kesalahan','Data gagal disimpan');
													}
												});
											} else {
												alertDialog('Warning','Periode harus diisi.');
											}
										} else {
											alertDialog('Warning','Bulan dan Tahun harus diisi.');
										}
									} else if (Ext.getCmp('rb_compare').getValue()){
										if((Ext.getCmp('cb_bulan').getValue() != '- Pilih -' && Ext.getCmp('cb_tahun').getValue() != '- Pilih -') || Ext.getCmp('cb_periode_gaji').getValue() != 'BULANAN'){
											if(Ext.getCmp('cb_periode').getValue() != '- Pilih -' || Ext.getCmp('cb_periode_gaji').getValue() != 'MINGGUAN'){
												Ext.Ajax.request({
												url:'<?PHP echo PATHAPP ?>/report/gajiplant/cek_data_compare.php',
													timeout: 500000,
													params:{
														periodegaji:Ext.getCmp('cb_periode_gaji').getValue(),
														//periode:Ext.getCmp('cb_periode').getValue(),
														periode_start:Ext.getCmp('cb_periode').getValue().substring(0, 10),
														periode_end:Ext.getCmp('cb_periode').getValue().substring(13, 23),
														bulan:Ext.getCmp('cb_bulan').getValue(),
														tahun:Ext.getCmp('cb_tahun').getValue(),
														company:Ext.getCmp('cb_company').getValue(),
														plant:Ext.getCmp('cb_plant').getValue(),
														//revisi:Ext.getCmp('tf_revisi').getValue(),
													},
													method:'POST',
													success:function(response){
														var json=Ext.decode(response.responseText);
														if (json.rows == "sukses"){
															var periodegaji=Ext.getCmp('cb_periode_gaji').getValue();
															var periode_start=Ext.getCmp('cb_periode').getValue().substring(0, 10);
															var periode_end=Ext.getCmp('cb_periode').getValue().substring(13, 23);
															var bulan=Ext.getCmp('cb_bulan').getValue();
															var tahun=Ext.getCmp('cb_tahun').getValue();
															var company=Ext.getCmp('cb_company').getValue();
															var plant=Ext.getCmp('cb_plant').getValue();
															//var dept=Ext.getCmp('cb_dept').getValue();
															var grade=Ext.getCmp('cb_grade').getValue();
															//var revisi=Ext.getCmp('tf_revisi').getValue();
															window.open("isi_compare_gaji_dwp.php?periodegaji=" + periodegaji + "&periode_start=" + periode_start + "&periode_end=" + periode_end + "&bulan=" + bulan + "&tahun=" + tahun + "&company=" + company + "&plant=" + plant + "&grade=" + grade);
														} else {
															alertDialog('Kesalahan', json.results);
														} 
													},
													failure:function(error){
														alertDialog('Kesalahan','Data gagal disimpan');
													}
												});
											} else {
												alertDialog('Warning','Periode harus diisi.');
											}
										} else {
											alertDialog('Warning','Bulan dan Tahun harus diisi.');
										}
									} else if (Ext.getCmp('rb_csv').getValue()){
										if(Ext.getCmp('tf_rekening').getValue() != '- Pilih -' || ((Ext.getCmp('cb_bulan').getValue() != '- Pilih -' && Ext.getCmp('cb_tahun').getValue() != '- Pilih -') || Ext.getCmp('cb_periode_gaji').getValue() != 'BULANAN')){
											if(Ext.getCmp('tf_rekening').getValue() != '- Pilih -' && (Ext.getCmp('cb_periode').getValue() != '- Pilih -' || Ext.getCmp('cb_periode_gaji').getValue() != 'MINGGUAN')){
												Ext.Ajax.request({
												url:'<?PHP echo PATHAPP ?>/report/gajiplant/cek_data_revisi.php',
													timeout: 500000,
													params:{
														periodegaji:Ext.getCmp('cb_periode_gaji').getValue(),
														//periode:Ext.getCmp('cb_periode').getValue(),
														periode_start:Ext.getCmp('cb_periode').getValue().substring(0, 10),
														periode_end:Ext.getCmp('cb_periode').getValue().substring(13, 23),
														bulan:Ext.getCmp('cb_bulan').getValue(),
														tahun:Ext.getCmp('cb_tahun').getValue(),
														company:Ext.getCmp('cb_company').getValue(),
														plant:Ext.getCmp('cb_plant').getValue(),
														revisi:Ext.getCmp('tf_revisi').getValue(),
													},
													method:'POST',
													success:function(response){
														var json=Ext.decode(response.responseText);
														if (json.rows == "sukses"){
															var rekening=Ext.getCmp('tf_rekening').getValue();
															var periodegaji=Ext.getCmp('cb_periode_gaji').getValue();
															var periode_start=Ext.getCmp('cb_periode').getValue().substring(0, 10);
															var periode_end=Ext.getCmp('cb_periode').getValue().substring(13, 23);
															var bulan=Ext.getCmp('cb_bulan').getValue();
															var tahun=Ext.getCmp('cb_tahun').getValue();
															var company=Ext.getCmp('cb_company').getValue();
															var plant=Ext.getCmp('cb_plant').getValue();
															//var dept=Ext.getCmp('cb_dept').getValue();
															var grade=Ext.getCmp('cb_grade').getValue();
															var revisi=Ext.getCmp('tf_revisi').getValue();
															window.open("isi_csv_gaji.php?rekening="+ rekening +"&periodegaji=" + periodegaji + "&periode_start=" + periode_start + "&periode_end=" + periode_end + "&bulan=" + bulan + "&tahun=" + tahun + "&company=" + company + "&plant=" + plant + "&grade=" + grade + "&revisi=" + revisi);
														} else {
															alertDialog('Kesalahan', "Data tidak ditemukan. ");
														} 
													},
													failure:function(error){
														alertDialog('Kesalahan','Data gagal disimpan');
													}
												});
											} else {
												alertDialog('Warning','Periode harus diisi.');
											}
										} else {
											alertDialog('Warning','No Rekening, Bulan, dan Tahun harus diisi.');
										}
									} else if (Ext.getCmp('rb_slip').getValue()){
										if(Ext.getCmp('tf_rekening').getValue() != '- Pilih -' || ((Ext.getCmp('cb_bulan').getValue() != '- Pilih -' && Ext.getCmp('cb_tahun').getValue() != '- Pilih -') || Ext.getCmp('cb_periode_gaji').getValue() != 'BULANAN')){
											if(Ext.getCmp('tf_rekening').getValue() != '- Pilih -' && (Ext.getCmp('cb_periode').getValue() != '- Pilih -' || Ext.getCmp('cb_periode_gaji').getValue() != 'MINGGUAN')){
												Ext.Ajax.request({
												url:'<?PHP echo PATHAPP ?>/report/gajiplant/cek_data_revisi.php',
													timeout: 500000,
													params:{
														periodegaji:Ext.getCmp('cb_periode_gaji').getValue(),
														//periode:Ext.getCmp('cb_periode').getValue(),
														periode_start:Ext.getCmp('cb_periode').getValue().substring(0, 10),
														periode_end:Ext.getCmp('cb_periode').getValue().substring(13, 23),
														bulan:Ext.getCmp('cb_bulan').getValue(),
														tahun:Ext.getCmp('cb_tahun').getValue(),
														company:Ext.getCmp('cb_company').getValue(),
														plant:Ext.getCmp('cb_plant').getValue(),
														revisi:Ext.getCmp('tf_revisi').getValue(),
													},
													method:'POST',
													success:function(response){
														var json=Ext.decode(response.responseText);
														if (json.rows == "sukses"){
															var periodegaji=Ext.getCmp('cb_periode_gaji').getValue();
															var periode_start=Ext.getCmp('cb_periode').getValue().substring(0, 10);
															var periode_end=Ext.getCmp('cb_periode').getValue().substring(13, 23);
															var bulan=Ext.getCmp('cb_bulan').getValue();
															var tahun=Ext.getCmp('cb_tahun').getValue();
															var company=Ext.getCmp('cb_company').getValue();
															var plant=Ext.getCmp('cb_plant').getValue();
															var dept=Ext.getCmp('cb_dept').getValue();
															var grade=Ext.getCmp('cb_grade').getValue();
															var revisi=Ext.getCmp('tf_revisi').getValue();
															window.open("isi_slip_gaji_header_dwp.php?periodegaji=" + periodegaji + "&periode_start=" + periode_start + "&periode_end=" + periode_end + "&bulan=" + bulan + "&tahun=" + tahun + "&company=" + company + "&plant=" + plant + "&dept=" + dept + "&grade=" + grade + "&revisi=" + revisi);
														} else {
															alertDialog('Kesalahan', "Data tidak ditemukan. ");
														} 
													},
													failure:function(error){
														alertDialog('Kesalahan','Data gagal disimpan');
													}
												});
											} else {
												alertDialog('Warning','Periode harus diisi.');
											}
										} else {
											alertDialog('Warning','No Rekening, Bulan, dan Tahun harus diisi.');
										}
									} else {
										if(Ext.getCmp('tf_rekening').getValue() != '- Pilih -' || ((Ext.getCmp('cb_bulan').getValue() != '- Pilih -' && Ext.getCmp('cb_tahun').getValue() != '- Pilih -') || Ext.getCmp('cb_periode_gaji').getValue() != 'BULANAN')){
											if(Ext.getCmp('tf_rekening').getValue() != '- Pilih -' && (Ext.getCmp('cb_periode').getValue() != '- Pilih -' || Ext.getCmp('cb_periode_gaji').getValue() != 'MINGGUAN')){
												Ext.Ajax.request({
												url:'<?PHP echo PATHAPP ?>/report/gajiplant/cek_data_revisi.php',
													timeout: 500000,
													params:{
														periodegaji:Ext.getCmp('cb_periode_gaji').getValue(),
														//periode:Ext.getCmp('cb_periode').getValue(),
														periode_start:Ext.getCmp('cb_periode').getValue().substring(0, 10),
														periode_end:Ext.getCmp('cb_periode').getValue().substring(13, 23),
														bulan:Ext.getCmp('cb_bulan').getValue(),
														tahun:Ext.getCmp('cb_tahun').getValue(),
														company:Ext.getCmp('cb_company').getValue(),
														plant:Ext.getCmp('cb_plant').getValue(),
														revisi:Ext.getCmp('tf_revisi').getValue(),
													},
													method:'POST',
													success:function(response){
														var json=Ext.decode(response.responseText);
														if (json.rows == "sukses"){
															var periodegaji=Ext.getCmp('cb_periode_gaji').getValue();
															var periode_start=Ext.getCmp('cb_periode').getValue().substring(0, 10);
															var periode_end=Ext.getCmp('cb_periode').getValue().substring(13, 23);
															var bulan=Ext.getCmp('cb_bulan').getValue();
															var tahun=Ext.getCmp('cb_tahun').getValue();
															var company=Ext.getCmp('cb_company').getValue();
															var plant=Ext.getCmp('cb_plant').getValue();
															var dept=Ext.getCmp('cb_dept').getValue();
															var grade=Ext.getCmp('cb_grade').getValue();
															var revisi=Ext.getCmp('tf_revisi').getValue();
															window.open("isi_pdf_gaji_dwp.php?periodegaji=" + periodegaji + "&periode_start=" + periode_start + "&periode_end=" + periode_end + "&bulan=" + bulan + "&tahun=" + tahun + "&company=" + company + "&plant=" + plant + "&dept=" + dept + "&grade=" + grade + "&revisi=" + revisi);
														} else {
															alertDialog('Kesalahan', "Data tidak ditemukan. ");
														} 
													},
													failure:function(error){
														alertDialog('Kesalahan','Data gagal disimpan');
													}
												});
											} else {
												alertDialog('Warning','Periode harus diisi.');
											}
										} else {
											alertDialog('Warning','No Rekening, Bulan, dan Tahun harus diisi.');
										}
									}
								},
								failure:function(error){
									alertDialog('Kesalahan','Data gagal disimpan');
								}
							});
						} else {
							alertDialog('Warning','No Rekening dan Periode harus diisi.');
						}
					} else {
						alertDialog('Warning','No Rekening, Bulan, dan Tahun harus diisi.');
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
	//Ext.getCmp('btn_simpan').setDisabled(true);
	Ext.getCmp('tf_revisi').setDisabled(true);
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