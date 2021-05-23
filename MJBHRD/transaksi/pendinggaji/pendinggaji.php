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
		Ext.getCmp('tf_hdid').setValue('')
		Ext.getCmp('tf_typeform').setValue('tambah')
		//Ext.getCmp('tf_maxnominal').setValue(0)
		Ext.getCmp('cb_nama').setValue('')
		Ext.getCmp('tf_dept').setValue('')
		Ext.getCmp('tf_pos').setValue('')
		Ext.getCmp('tf_loc').setValue('')
		Ext.getCmp('cb_periode').setValue('')
		//Ext.getCmp('cb_periode').setValue('BULANAN')
		Ext.getCmp('cb_satuan').setValue('RP')
		Ext.getCmp('tf_nominal').setValue(0)
		Ext.getCmp('tf_periode').setValue(currentTime)
		Ext.getCmp('tf_periode2').setValue('')
		Ext.getCmp('tf_keterangan').setValue('')
		store_detil.load()
		Ext.getCmp('cb_nama').setDisabled(false)
		Ext.getCmp('tf_periode').setDisabled(false)
		Ext.getCmp('tf_periode').setMinValue(currentTime)
		Ext.getCmp('tf_periode2').setDisabled(false)
	};
	var comboPeriode = Ext.create('Ext.data.Store', {
		fields: ['DATA_NAME', 'DATA_VALUE'],
		data : [
			{"DATA_NAME":"Bulanan", "DATA_VALUE":"BULANAN"},
			{"DATA_NAME":"Mingguan", "DATA_VALUE":"MINGGUAN"}
		]
	});
	var comboSatuan = Ext.create('Ext.data.Store', {
		fields: ['DATA_NAME', 'DATA_VALUE'],
		data : [
			{"DATA_NAME":"Rp", "DATA_VALUE":"RP"},
			{"DATA_NAME":"%", "DATA_VALUE":"%"}
		]
	});
	var comboKaryawan = Ext.create('Ext.data.Store', {
		fields: [{
			name:'DATA_VALUE'
		},{
			name:'DATA_NAME'
		},{
			name:'DATA_LOCATION'
		},{
			name:'DATA_GAJIAN'
		}],
		proxy:{
			type:'ajax',
			url:'<?PHP echo PATHAPP ?>/combobox/combobox_kar_linkgroup.php', 
			reader: {
				type: 'json',
				root: 'data', 
				totalProperty:'total'   
			},
		}
	});
	var store_detil=new Ext.data.JsonStore({
		id:'store_cari_id',
		pageSize: 100,
		model: 'Plant',
		fields:[{
			name:'HD_ID'
		},{
			name:'DATA_NAMA'
		},{
			name:'DATA_DEPT'
		},{
			name:'DATA_POS'
		},{
			name:'DATA_LOC'
		},{
			name:'DATA_PERIODE_GAJI'
		},{
			name:'DATA_SATUAN'
		},{
			name:'DATA_NOMINAL'
		},{
			name:'DATA_PENDING'
		},{
			name:'DATA_PERIODE_AWAL'
		},{
			name:'DATA_PERIODE_AKHIR'
		},{
			name:'DATA_PERIODE'
		},{
			name:'DATA_KET'
		},{
			name:'DATA_STATUS'
		},{
			name:'DATA_PERSON_ID'
		},{
			name:'DATA_CREATED_DATE'
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
	//var sm = Ext.create('Ext.selection.CheckboxModel');
    var grid_detil = Ext.create('Ext.grid.Panel', {
		id:'grid_detil_id',
        store: store_detil,
		autoScroll:true,
        columnLines: true,
        width: 850,
        height: 300,
        title: 'Data Pending Gaji',
        frame: false,
		//selModel:sm,
        loadMask: true,
        columns:[{
			xtype:'rownumberer',
			id:'row_id',
			header:'No',
			width:30
		},{
			dataIndex:'HD_ID',
			header:'id',
			width:100,
			hidden:true
		},{
			dataIndex:'DATA_SATUAN',
			header:'satuan',
			width:100,
			hidden:true
		},{
			dataIndex:'DATA_NOMINAL',
			header:'nominal',
			width:100,
			hidden:true
		},{
			dataIndex:'DATA_PERIODE_AWAL',
			header:'periode awal',
			width:100,
			hidden:true
		},{
			dataIndex:'DATA_PERIODE_AKHIR',
			header:'periode akhir',
			width:100,
			hidden:true
		},{
			dataIndex:'DATA_PERSON_ID',
			header:'person id',
			width:100,
			hidden:true
		},{
			dataIndex:'DATA_CREATED_DATE',
			header:'cek periode',
			width:100,
			hidden:true
		},{
			dataIndex:'DATA_NAMA',
			header:'Nama Karyawan',
			width:200,
		},{
			dataIndex:'DATA_DEPT',
			header:'Departemen',
			width:140,
		},{
			dataIndex:'DATA_POS',
			header:'Posisi',
			width:100,
		},{
			dataIndex:'DATA_LOC',
			header:'Lokasi',
			width:100,
		},{
			dataIndex:'DATA_PERIODE_GAJI',
			header:'Periode Gaji',
			width:100,
		},{
			dataIndex:'DATA_PENDING',
			header:'Pending Gaji',
			width:100,
			align:'right',
		},{
			dataIndex:'DATA_PERIODE',
			header:'Periode',
			//align:'right',
			width:180,
			//align:'center',
		},{
			dataIndex:'DATA_KET',
			header:'Keterangan',
			width:120,
		},{
			dataIndex:'DATA_STATUS',
			header:'Status',
			width:100,
		}],
        //plugins: [cellEditing],
		listeners:{
			// cellclick:function(grid,row,col){
				// // alert(col);
				// if (col==3) {
					// var bool=grid_detil.getSelectionModel().getSelection()[0].get('DATA_NAMA');
					// console.log(bool);
					// if(bool){
						// Ext.getCmp('tf_value').setDisabled(true)
					// } else {
						// Ext.getCmp('tf_value').setDisabled(false)
					// }
				// } 
			// }
			dblclick: {
				element: 'body', //bind to the underlying body property on the panel
				fn: function(){ 
					var hdid=grid_detil.getSelectionModel().getSelection()[0].get('HD_ID');
					Ext.getCmp('tf_hdid').setValue(hdid);
					Ext.getCmp('tf_typeform').setValue('edit');
					//Ext.getCmp('tf_maxnominal').setValue(grid_detil.getSelectionModel().getSelection()[0].get('DATA_MAX_NOMINAL'));
					Ext.getCmp('cb_nama').setValue(grid_detil.getSelectionModel().getSelection()[0].get('DATA_PERSON_ID'));
					comboKaryawan.load();
					Ext.getCmp('cb_nama').setDisabled(true);
					Ext.getCmp('tf_dept').setValue(grid_detil.getSelectionModel().getSelection()[0].get('DATA_DEPT'));
					Ext.getCmp('tf_pos').setValue(grid_detil.getSelectionModel().getSelection()[0].get('DATA_POS'));
					Ext.getCmp('tf_loc').setValue(grid_detil.getSelectionModel().getSelection()[0].get('DATA_LOC'));
					Ext.getCmp('cb_periode').setValue(grid_detil.getSelectionModel().getSelection()[0].get('DATA_PERIODE_GAJI'));
					comboPeriode.load();
					Ext.getCmp('cb_satuan').setValue(grid_detil.getSelectionModel().getSelection()[0].get('DATA_SATUAN'));
					comboSatuan.load();
					Ext.getCmp('tf_nominal').setValue(grid_detil.getSelectionModel().getSelection()[0].get('DATA_NOMINAL'));
					var status=grid_detil.getSelectionModel().getSelection()[0].get('DATA_STATUS');
					if(status != 'WAITING'){
						Ext.getCmp('tf_periode').setDisabled(true);
					}
					Ext.getCmp('tf_periode').setMinValue(grid_detil.getSelectionModel().getSelection()[0].get('DATA_CREATED_DATE'));
					Ext.getCmp('tf_periode').setValue(grid_detil.getSelectionModel().getSelection()[0].get('DATA_PERIODE_AWAL'));
					var bool=grid_detil.getSelectionModel().getSelection()[0].get('DATA_PERIODE_AKHIR');
					Ext.getCmp('tf_periode2').setValue(bool);
					//alert(Ext.getCmp('tf_periode2').getValue());
					if(Ext.getCmp('tf_periode2').getValue() != null){
						Ext.getCmp('tf_periode2').setDisabled(true);
					}else{
						Ext.getCmp('tf_periode2').setDisabled(false);
					}
					Ext.getCmp('tf_keterangan').setValue(grid_detil.getSelectionModel().getSelection()[0].get('DATA_KET'));
				}
			}
		} 
    });
	
	var contentPanel = Ext.create('Ext.panel.Panel',{
			//title:'Form Input Tanda Terima',
			//region:'center',
			bodyStyle: 'spacing: 10px;border:none',
			items:[{
				xtype:'label',
				html:'<div align="center"><font size="5"><b>Pending Gaji</b></font></div>',
			},{
				xtype:'label',
				html:'&nbsp',
			},{		
				xtype:'textfield',
				fieldLabel:'id',
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
			}
			// ,{		
				// xtype:'textfield',
				// fieldLabel:'max nominal',
				// width:350,
				// labelWidth:75,
				// id:'tf_maxnominal',
				// value:0,
				// hidden:true
			// }
			,{
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
					columnWidth:.6,
					border:false,
					layout: 'anchor',
					defaultType: 'combobox',
					items:[{		
						xtype:'combobox',
						fieldLabel:'Nama Karyawan',
						store: comboKaryawan,
						displayField: 'DATA_NAME',
						valueField: 'DATA_VALUE',
						width:450,
						labelWidth:130,
						id:'cb_nama',
						//value:'All',
						//editable: false 
						enableKeyEvents : true,
						minChars:2,
						listeners: {
							select:function(f,r,i){
								var nama_kar=r[0].data.DATA_NAME;
								var person_id=r[0].data.DATA_VALUE;
								var location=r[0].data.DATA_LOCATION;
								var gajian=r[0].data.DATA_GAJIAN;
								Ext.Ajax.request({
									url:'<?php echo 'isi_dept.php'; ?>',
										params:{
											nama_pem:nama_kar,
										},
										success:function(response){
											var json=Ext.decode(response.responseText);
											var deskripsiasli = json.results;
											var deskripsisplit = deskripsiasli.split('.');
											var nama_dept = deskripsisplit[1];
											Ext.getCmp('tf_dept').setValue(deskripsiasli);
										},
									method:'POST',
								});
								Ext.Ajax.request({
									url:'<?php echo 'isi_position.php'; ?>',
										params:{
											nama_pem:nama_kar,
										},
										success:function(response){
											var json=Ext.decode(response.responseText);
											var deskripsiasli = json.results;
											//var deskripsisplit = deskripsiasli.split('.');
											//var nama_dept = deskripsisplit[1];
											Ext.getCmp('tf_pos').setValue(deskripsiasli);
										},
									method:'POST',
								});
								Ext.getCmp('tf_loc').setValue(location);
								Ext.getCmp('cb_periode').setValue(gajian);
								Ext.getCmp('tf_nominal').setValue(0);
								//untuk set max nominal
								/* Ext.Ajax.request({
									url:'<?php echo 'max_nominal.php'; ?>',
										params:{
											personid:person_id,
										},
										success:function(response){
											var json=Ext.decode(response.responseText);
											var nominal = json.results;
											//var deskripsisplit = deskripsiasli.split('.');
											//var nama_dept = deskripsisplit[1];
											Ext.getCmp('tf_maxnominal').setValue(nominal);
										},
									method:'POST',
								}); */	
							},
							
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
					defaultType: 'textfield',
					items:[{		
						xtype:'textfield',
						fieldLabel:'Departemen',
						width:340,
						labelWidth:130,
						id:'tf_dept',
						fieldStyle:'background:#B8B8B8 ;font-weight:bold;',
						readOnly: 'true',
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
					columnWidth:.8,
					border:false,
					layout: 'anchor',
					defaultType: 'textfield',
					items:[{		
						xtype:'textfield',
						fieldLabel:'Position / Jabatan',
						width:340,
						labelWidth:130,
						id:'tf_pos',
						fieldStyle:'background:#B8B8B8 ;font-weight:bold;',
						readOnly: 'true',
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
					columnWidth:.8,
					border:false,
					layout: 'anchor',
					defaultType: 'textfield',
					items:[{		
						xtype:'textfield',
						fieldLabel:'Location',
						width:340,
						labelWidth:130,
						id:'tf_loc',
						fieldStyle:'background:#B8B8B8 ;font-weight:bold;',
						readOnly: 'true',
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
					columnWidth:.8,
					border:false,
					layout: 'anchor',
					defaultType: 'textfield',
					items:[{		
						xtype:'textfield',
						fieldLabel:'Periode Gaji',
						width:340,
						labelWidth:130,
						id:'cb_periode',
						fieldStyle:'background:#B8B8B8 ;font-weight:bold;',
						readOnly: 'true',
					}]
				}
				/* {
					columnWidth:.4,
					border:false,
					layout: 'anchor',
					defaultType: 'combobox',
					items:[{		
						xtype:'combobox',
						fieldLabel:'Periode Gaji',
						store: comboPeriode,
						displayField: 'DATA_NAME',
						valueField: 'DATA_VALUE',
						width:280,
						labelWidth:130,
						id:'cb_periode',
						value:'BULANAN',
						queryMode:'local',
						editable: false,
						//enableKeyEvents : true,
					}]
				} */
				]	
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
						html:'<div style="color:#FF0000">*</div>',
					}]
				},{
					columnWidth:.22,
					border:false,
					layout: 'anchor',
					defaultType: 'combobox',
					items:[{		
						xtype:'combobox',
						fieldLabel:'Pending Gaji',
						store: comboSatuan,
						displayField: 'DATA_NAME',
						valueField: 'DATA_VALUE',
						width:180,
						labelWidth:130,
						id:'cb_satuan',
						value:'RP',
						editable: false,
						enableKeyEvents : true,
						minChars:1,
						queryMode:'local',
					}]
				},{
					columnWidth:.3,
					border:false,
					layout: 'anchor',
					defaultType: 'numberfield',
					items:[{		
						xtype:'numberfield',
						//fieldLabel:'Position / Jabatan',
						width:120,
						//labelWidth:130,
						id:'tf_nominal',
						//align:'right',
						value:0,
						fieldStyle:'text-align:right;',
						//set batas max nominal
						/* listeners: {
							'change':function(){
								if(Ext.getCmp('tf_nominal').getValue()>Ext.getCmp('tf_maxnominal').getValue()){
									//alert('asd');
									var nom = Ext.getCmp('tf_maxnominal').getValue();
									Ext.getCmp('tf_nominal').setValue(nom);
								}
							}
						} */
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
					columnWidth:.28,
					border:false,
					layout: 'anchor',
					defaultType: 'datefield',
					items:[{		
						xtype:'datefield',
						fieldLabel:'Periode',
						width:230,
						labelWidth:130,
						id:'tf_periode',
						editable: false,
						enableKeyEvents : true,
						value: currentTime,
						minValue: currentTime,
						renderer: formatDate,
					}]
				},{
					columnWidth:.3,
					border:false,
					layout: 'anchor',
					defaultType: 'datefield',
					items:[{		
						xtype:'datefield',
						fieldLabel:'s/d',
						width:120,
						labelWidth:30,
						id:'tf_periode2',
						editable: false,
						minValue: currentTime,
						renderer: formatDate,
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
						html:'<div style="color:#FF0000">*</div>',
					}]
				},{
					columnWidth:.8,
					border:false,
					layout: 'anchor',
					defaultType: 'textarea',
					items:[{		
						xtype:'textarea',
						fieldLabel:'Keterangan',
						width:540,
						labelWidth:130,
						id:'tf_keterangan',
						maxLength: 255,
						//fieldStyle:'background:#B8B8B8 ;font-weight:bold;',
						//readOnly: 'true',
					}]
				}]	
			},{
				xtype:'label',
				html:'&nbsp',
			},{
				xtype:'panel',
				bodyStyle: 'padding-left: 150px;padding-bottom: 30px;border:none',
				items:[{
					xtype:'button',
					text:'Simpan',
					width:80,
					handler:function(){
						if(Ext.getCmp('cb_nama').getValue() != null){
							if(Ext.getCmp('tf_keterangan').getValue() != ''){
								//console.log(Ext.getCmp('cb_nama').getValue());
								if(Ext.getCmp('tf_nominal').getValue()!=0){
									if(Ext.getCmp('cb_satuan').getValue() != '%' || Ext.getCmp('tf_nominal').getValue()<=100){
										//if (j==countGrid){
											maskgrid = new Ext.LoadMask(Ext.getCmp('grid_detil_id'), {msg: "Harap Tunggu. . .Sedang Proses . . ."});
											maskgrid.show();
											Ext.Ajax.request({
												url:'<?php echo 'simpan.php'; ?>',
												timeout: 500000,
												params:{
													hdid:Ext.getCmp('tf_hdid').getValue(),
													typeform:Ext.getCmp('tf_typeform').getValue(),
													nama:Ext.getCmp('cb_nama').getValue(),
													periodegaji:Ext.getCmp('cb_periode').getValue(),
													satuan:Ext.getCmp('cb_satuan').getValue(),
													nominal:Ext.getCmp('tf_nominal').getValue(),
													periode:Ext.getCmp('tf_periode').getValue(),
													periode2:Ext.getCmp('tf_periode2').getValue(),
													keterangan:Ext.getCmp('tf_keterangan').getValue(),
												},
												method:'POST',
												success:function(response){
													maskgrid.hide();
													var json=Ext.decode(response.responseText);
													if (json.rows == "sukses"){
														alertDialog('Sukses', "Data tersimpan.");
														rowGrid = 0;
														//store_detil.removeAll();
														maskgrid = new Ext.LoadMask(Ext.getCmp('grid_detil_id'), {msg: "Memuat . . ."});
														maskgrid.show();
														Ext.clearForm();
														store_detil.load();
														maskgrid.hide();
													} else {
														alertDialog('Kesalahan', "Data gagal disimpan. <br/>"+json.rows);
													} 
												},
												failure:function(error){
													maskgrid.hide();
													alertDialog('Kesalahan','Data gagal disimpan');
												}
											});
										// } else {
											// alertDialog('Kesalahan','Data Value pada grid element tidak boleh kosong.');
										// }
									} else {
										alertDialog('Kesalahan','Nominal tidak boleh >100%.');
									}
								} else {
									alertDialog('Kesalahan','Nominal pending gaji tidak boleh kosong.');
								} 
							} else {
								alertDialog('Kesalahan','Keterangan tidak boleh kosong.');
							} 								
						} else {
							alertDialog('Kesalahan','Nama Karyawan tidak boleh kosong.');
						} 
					}
				},{
					xtype:'label',
					html:'&nbsp',
				},{
					xtype:'button',
					text:'Cancel',
					width:80,
					handler:function(){
						Ext.clearForm();
					}
				}]
			}, grid_detil ,{
				xtype:'label',
				html:'&nbsp',
			},{
				layout:'column',
				border:false,
				items:[{
					columnWidth:.24,
					border:false,
					layout: 'anchor',
					defaultType: 'label',
					items:[{
						xtype:'label',
						html:'Catatan : Untuk field yang bertanda (',
					}]
				},{
					columnWidth:.01,
					border:false,
					layout: 'anchor',
					defaultType: 'label',
					items:[{
						xtype:'label',
						html:'<div style="color:#FF0000">*</div>',
					}]
				},{
					columnWidth:.39,
					border:false,
					layout: 'anchor',
					defaultType: 'combobox',
					items:[{
						xtype:'label',
						html:') harus diisi.',
					}]
				}]	
			}],
		});	
   contentPanel.render('page');
   //comboPemohon.load();
   store_detil.load();
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