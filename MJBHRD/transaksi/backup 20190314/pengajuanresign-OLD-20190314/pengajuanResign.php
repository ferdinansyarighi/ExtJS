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

var comboManager = Ext.create('Ext.data.Store', {
	fields: [{
		name:'DATA_VALUE'
	},{
		name:'DATA_NAME'
	}],
	proxy:{
		type:'ajax',
		url:'<?PHP echo PATHAPP ?>/combobox/combobox_manager.php',
		reader: {
			type: 'json',
			root: 'data', 
			totalProperty:'total'   
		}
	}
});

var store_popuptransaksi=new Ext.data.JsonStore({
		id:'store_popuptransaksi_id',
		pageSize: 100,
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
			name:'DATA_TGL_PENGAJUAN'
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
		}],
		 proxy:{
			type:'ajax',
			url:'isi_grid_existing_resign.php', 
			reader: {
				root: 'rows',
			},
		},
	});

var grid_popuptransaksi=Ext.create('Ext.grid.Panel',{
	id:'grid_popuptransaksi_id',
	region:'center',
	store:store_popuptransaksi,
    columnLines: true,
    loadMask: true,
	columns:[{
		xtype:'rownumberer',
		id:'row_id',
		header:'No',
		width:45
	},{
		dataIndex:'HD_ID',
		header:'id',
		width:100,
		hidden:true
	},{
		dataIndex:'DATA_NO_PENGAJUAN',
		header:'No Pengajuan',
		width:160,
	},{
		dataIndex:'DATA_NAMA_KARYAWAN',
		header:'Nama Karyawan',
		width:150,
	},{
		dataIndex:'DATA_COMPANY',
		header:'Company',
		width:150,
		hidden:true
	},{
		dataIndex:'DATA_DEPT',
		header:'Department',
		width:150,
		hidden:true
	},{
		dataIndex:'DATA_POS',
		header:'Posisi',
		width:150,
		hidden:true
	},{
		dataIndex:'DATA_GRADE',
		header:'Grade',
		width:150,
		hidden:true
	},{
		dataIndex:'DATA_LOCATION',
		header:'Location',
		width:150,
		hidden:true
	},{
		dataIndex:'DATA_TGL_MASUK',
		header:'Tanggal Masuk',
		width:100,
		hidden:true
	},{
		dataIndex:'DATA_TGL_PENGAJUAN',
		header:'Tanggal Pengajuan',
		width:100,
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
		width:100,
		hidden:true
	},{
		dataIndex:'DATA_TGL_RESIGN',
		header:'Tanggal Resign',
		width:100,
		//hidden:true
	},{
		dataIndex:'DATA_MANAGER',
		header:'Manager',
		width:150,
		//hidden:true
	},{
		dataIndex:'DATA_KETERANGAN',
		header:'Keterangan',
		width:100,
		//hidden:true
	},{
		dataIndex:'DATA_STATUS',
		header:'Flag Aktif',
		width:75,
		//hidden:true
	},{
		dataIndex:'DATA_TGL_APP_TERAKHIR',
		header:'Tanggal Approve Terakhir',
		width:150,
		hidden:true
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
	}],
listeners: {
		dblclick: {
			element: 'body', //bind to the underlying body property on the panel
			fn: function(){
				Ext.getCmp('hd_id_user').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('HD_ID'));
				Ext.getCmp('tf_no_pengajuan').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_NO_PENGAJUAN'));
				Ext.getCmp('cb_nama_kar').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_NAMA_KARYAWAN'));
				Ext.getCmp('tf_company').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_COMPANY'));
				Ext.getCmp('tf_dept').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_DEPT'));
				Ext.getCmp('tf_pos').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_POS'));	
				Ext.getCmp('tf_grade').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_GRADE'));
				Ext.getCmp('tf_loc').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_LOCATION'));
				Ext.getCmp('tf_tgl_masuk').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_TGL_MASUK'));
				Ext.getCmp('tf_tgl_resign').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_TGL_RESIGN'));
				Ext.getCmp('tf_tgl_pengajuan').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_TGL_PENGAJUAN'));
				Ext.getCmp('cb_manager').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_MANAGER'));
				Ext.getCmp('tf_keterangan').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_KETERANGAN'));
				var hdid=grid_popuptransaksi.getSelectionModel().getSelection()[0].get('HD_ID');
				var vtahunkerja=grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_TAHUN_LAMA_KERJA');
				var vbulankerja=grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_BULAN_LAMA_KERJA');
				var vharikerja=grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_HARI_LAMA_KERJA');
				Ext.getCmp('tf_lama_kerja').setValue(vtahunkerja + " Tahun" + vbulankerja + " Bulan" + vharikerja + " SHari");
				Ext.getCmp('hd_id_user').setValue(hdid);
				var vPemohon = grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_NAMA_KARYAWAN');
				Ext.getCmp('cb_nama_kar').setValue(vPemohon);
				comboManager.setProxy({
					type:'ajax',
					url:'<?PHP echo PATHAPP ?>/combobox/combobox_manager.php?pemohon=' + vPemohon,
					reader: {
						type: 'json',
						root: 'data', 
						totalProperty:'total'   
					}
				});

				var vstatus=grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_STATUS');
				if(vstatus == '1'){
					Ext.getCmp('cb_status').setValue(true);
				} else {
					Ext.getCmp('cb_status').setValue(false);
				}

				Ext.Ajax.request({
					url:'<?PHP echo PATHAPP ?>/transaksi/pengajuanresign/insert_grid_upload.php',
					params:{
						idtrans:hdid,
					},
					method:'POST',
					success:function(response){
						store_upload.load();
					},
					failure:function(error){
						alertDialog('Warning','Save failed.');
					}
				});

				Ext.getCmp('tf_typeform').setValue('edit');
				Ext.getCmp('tf_no_pengajuan').setReadOnly(true);
				Ext.getCmp('cb_nama_kar').setReadOnly(false);
				Ext.getCmp('tf_company').setReadOnly(true);
				Ext.getCmp('tf_dept').setReadOnly(true);
				Ext.getCmp('tf_pos').setReadOnly(true);
				Ext.getCmp('tf_grade').setReadOnly(true);
				Ext.getCmp('tf_loc').setReadOnly(true);
				Ext.getCmp('tf_tgl_masuk').setReadOnly(true);
				Ext.getCmp('tf_tgl_resign').setReadOnly(false);
				Ext.getCmp('cb_manager').setReadOnly(false);
				Ext.getCmp('tf_lama_kerja').setReadOnly(true);
				Ext.getCmp('tf_keterangan').setReadOnly(false);
				Ext.getCmp('cb_status').setDisabled(false);
				PopupSIK.hide();
			}
		}
	}
});

var frmfile1 = new Ext.FormPanel({
	fileUpload	: true,
	bodyStyle	: 'padding: 5px;border:none',
	defaultType	: 'textfield',
	//region		: 'center',
	frame		: true,
	items		:[{
		xtype		:'fileuploadfield',
		fieldLabel	: 'File Arsip',
		name		: 'arsipfile',
		id			: 'arsipfile',
		width		: 375,
		labelWidth	: 75,
	}],
});

var store_upload=new Ext.data.JsonStore({
	id:'store_upload_id',
	pageSize: 50,
	fields:[{
		name:'HD_ID'
	},{
		name:'DATA_ATTACHMENT'
	}],
	 proxy:{
		type:'ajax',
		url:'isi_grid_upload.php', 
		reader: {
			type: 'json',
			root: 'data',
			totalProperty:'total'        
		},
	}
});
var grid_upload=Ext.create('Ext.grid.Panel',{
	id:'grid_upload_id',
	region:'center',
	store:store_upload,
    columnLines: true,
	autoScroll:true,
	width:350,
	columns:[{
		dataIndex:'HD_ID',
		header:'id',
		width:100,
		hidden:true
	},{
		dataIndex:'DATA_ATTACHMENT',
		header:'Nama File',
		width:350,
		
	}]
});

var frmfile1 = new Ext.FormPanel({
		fileUpload	: true,
		bodyStyle	: 'padding: 5px;border:none',
		defaultType	: 'textfield',
		//region		: 'center',
		frame		: true,
		items		:[{
			xtype		:'fileuploadfield',
			fieldLabel	: 'File Arsip',
			name		: 'arsipfile',
			id			: 'arsipfile',
			width		: 375,
			labelWidth	: 75,
		}],
	});

var upload_panel = Ext.create('Ext.Window', {//new Ext.Window ({	
	//bodyStyle	: 'border:none;padding: 10px',
	layout		: 'form',
	height		: 150,
	width		: 400,
	closeAction	: 'hide',
	buttonAlign	: 'right',
	items		:[frmfile1],
	title		:'Pilih File',
	buttons: [{
		text     : 'Ok',
		handler: function () {
			Ext.Ajax.request({
				url			: '<?PHP echo PATHAPP ?>/transaksi/pengajuanresign/cekupload.php',
				method		: 'POST',
				waitTitle	: 'Connecting',
				waitMsg		: 'Sending data...',
				params:{
					file	:Ext.getCmp('arsipfile').getValue(), 
				},
				success: function (response) {
					var json=Ext.decode(response.responseText);
					if(json.jumlah==1){
						Ext.MessageBox.alert('Failed', 'Anda tidak diperbolehkan menambah file jenis TXT atau PHP');
					}
					else{
						frmfile1.getForm().submit({
							url: '<?PHP echo PATHAPP ?>/transaksi/pengajuanresign/uploadattachment.php', 
							method:'POST', 
							waitTitle:'Connecting', 
							waitMsg:'Sending data...',
							
							success:function(fp, o){ 											
								var sembarang=Ext.decode(o.response.responseText);		
								//console.log(sembarang);
								if(sembarang.results=='sukses'){
									store_upload.load();
									upload_panel.hide();
								} else {
									Ext.MessageBox.alert('Peringatan', 'File upload melebihi 512kb.');
								}
							},
							failure:function(form, action){ 
								if(action.failureType == 'server'){ 
									obj = Ext.util.JSON.decode(action.response.responseText); 						
									Ext.Msg.alert('Data gagal disimpan!', 'Login Gagal!'); 
								}else{ 
									Ext.Msg.alert('Warning!', 'Authentication server is unreachable : ' + action.response.responseText); 
								} 					
							} 
						});
					}
				},
				failure: function ( result, request) {
					Ext.MessageBox.alert('Failed', 'Data gagal disimpan');
				}
			}); 
		}
	},{
		text     : 'Batal',
		handler  : function(){
			Ext.getCmp('arsipfile').setValue('');
			//Ext.getCmp('fileid').setValue('');
			upload_panel.hide();
		}
	}]
});

var comboNamakar = Ext.create('Ext.data.Store', {
		fields: [{
			name:'DATA_VALUE'
		},{
			name:'DATA_NAME'
		}],
		proxy:{
			type:'ajax',
			url:'<?PHP echo PATHAPP ?>/combobox/combobox_bawahan.php', 
			reader: {
				type: 'json',
				root: 'data', 
				totalProperty:'total'   
			},
		}
	});

var PopupSIK=Ext.create('Ext.Window', {
title: 'Cari Pengajuan Resign',
width: 700,
height: 350,							
layout: 'fit',
closeAction:'hide',
items: grid_popuptransaksi
});

function setupApp(){
	Ext.QuickTips.init();	
	var currentTime = new Date();
	var contentPanel = Ext.create('Ext.panel.Panel',{
			bodyStyle: 'spacing: 10px;border:none',
			items:[{
				xtype:'label',
				html:'<div align="center"><font size="5"><b>Form Resignation</b></font></div>',
			},{
				xtype:'label',
				html:'&nbsp',
			},{		
				xtype:'textfield',
				fieldLabel:'userid',
				width:350,
				labelWidth:75,
				id:'hd_id_user',
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
					columnWidth:.48,
					border:false,
					layout: 'anchor',
					defaultType: 'textfield',
					items:[{
						name: 'tf_no_req',
						fieldLabel: 'No. Pengajuan',
						width:400,
						labelWidth:125,
						id:'tf_no_pengajuan',
						editable: false,
						readOnly:true,
						fieldStyle:'background:#B0C4DE ;font-weight:bold;'
					}]
				},{
					columnWidth:.2,
					border:false,
					layout: 'anchor',
					defaultType: 'button',
					items:[{
						name: 'cari',
						text: 'Existing',
						width:75,
						handler:function(){
						    store_popuptransaksi.load();
							PopupSIK.show();

							Ext.Ajax.request({
								url:'<?PHP echo PATHAPP ?>/transaksi/pengajuanresign/delete_upload.php',
								method:'POST',
								success:function(response){
								},
								failure:function(error){
									alertDialog('Warning','Save failed.');
								}
							});
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
					}]
				},{
					columnWidth:.47,
					border:false,
					layout: 'anchor',
					defaultType: 'combobox',
					items:[{		
						xtype:'combobox',
						editable:true,
						fieldLabel:'Nama Karyawan',
						store: comboNamakar,
						fieldStyle:'background:#FAEBD7 ;font-weight:bold;',
						displayField: 'DATA_NAME',
						valueField: 'DATA_VALUE',
						width:400,
						labelWidth:125,
						id:'cb_nama_kar',
						value:'',
						emptyText : '- Pilih -',
						enableKeyEvents : true,
						minChars:1,
						listeners: {
							select:function(f,r,i){
								var nama_pem=r[0].data.DATA_NAME;
									// console.log('MASUK');
								Ext.Ajax.request({
									url:'<?php echo 'isi_departemen.php'; ?>',
										params:{
											nama_pem:nama_pem,
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
									url:'<?php echo 'isi_posisi.php'; ?>',
										params:{
											nama_pem:nama_pem,
										},
										success:function(response){
											var json=Ext.decode(response.responseText);
											var deskripsiasli = json.results;
											var deskripsisplit = deskripsiasli.split('.');
											var nama_dept = deskripsisplit[1];
											Ext.getCmp('tf_pos').setValue(deskripsiasli);
										},
									method:'POST',
								});
								comboManager.setProxy({
									type:'ajax',
									url:'<?PHP echo PATHAPP ?>/combobox/combobox_manager.php?pemohon=' + nama_pem,
									reader: {
										type: 'json',
										root: 'data', 
										totalProperty:'total'   
									}
								});
								Ext.Ajax.request({
									url:'<?php echo 'isi_perusahaan.php'; ?>',
										params:{
											nama_pem:nama_pem,
										},
										success:function(response){
											var json=Ext.decode(response.responseText);
											var deskripsiasli = json.results;
											var deskripsisplit = deskripsiasli.split('.');
											var nama_dept = deskripsisplit[1];
											Ext.getCmp('tf_company').setValue(deskripsiasli);
										},
									method:'POST',
								});
								Ext.Ajax.request({
									url:'<?php echo 'isi_tanggal_masuk.php'; ?>',
										params:{
											nama_pem:nama_pem,
										},
										success:function(response){
											var json=Ext.decode(response.responseText);
											var deskripsiasli = json.results;
											var deskripsisplit = deskripsiasli.split('.');
											var nama_dept = deskripsisplit[1];
											Ext.getCmp('tf_tgl_masuk').setValue(deskripsiasli);
										},
									method:'POST',
								});
								Ext.Ajax.request({
									url:'<?php echo 'isi_grade.php'; ?>',
										params:{
											nama_pem:nama_pem,
										},
										success:function(response){
											var json=Ext.decode(response.responseText);
											var deskripsiasli = json.results;
											var deskripsisplit = deskripsiasli.split('.');
											var nama_dept = deskripsisplit[1];
											Ext.getCmp('tf_grade').setValue(deskripsiasli);
										},
									method:'POST',
								});
								Ext.Ajax.request({
									url:'<?php echo 'isi_lokasi.php'; ?>',
										params:{
											nama_pem:nama_pem,
										},
										success:function(response){
											var json=Ext.decode(response.responseText);
											var deskripsiasli = json.results;
											var deskripsisplit = deskripsiasli.split('.');
											var nama_dept = deskripsisplit[1];
											Ext.getCmp('tf_loc').setValue(deskripsiasli);
										},
									method:'POST',
								});
							},
							keypress : {
								element: 'body',
								fn: function(){
									// console.log('MASUK');
									var pjname=Ext.getCmp('cb_nama_kar').getValue();
									combopj.load({
										params : {
											pjname:pjname,
										}
									});
								}
							},
							change: function(field,newValue,oldValue){
									Ext.getCmp('tf_dept').setValue('');
									Ext.getCmp('tf_pos').setValue('');
									Ext.getCmp('tf_loc').setValue('');
									Ext.getCmp('tf_company').setValue('');
									Ext.getCmp('tf_tgl_masuk').setValue('');
									Ext.getCmp('tf_grade').setValue('');
									Ext.getCmp('tf_tgl_resign').setValue('');
									Ext.getCmp('cb_manager').setValue('');
									Ext.getCmp('tf_keterangan').setValue('');
									Ext.getCmp('tf_lama_kerja').setValue('');
							}
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
						html:'<div style="color:#FF0000"></div>',
					}]
				},{
					columnWidth:.48,
					border:false,
					layout: 'anchor',
					defaultType: 'combobox',
					items:[{	
						xtype:'textfield',
						fieldLabel:'Company',
						width:350,
						labelWidth:125,
						id:'tf_company',
						editable: false,
						readOnly:true,
						fieldStyle:'background:#B0C4DE ;font-weight:bold;'
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
					columnWidth:.48,
					border:false,
					layout: 'anchor',
					defaultType: 'combobox',
					items:[{	
						xtype:'textfield',
						fieldLabel:'Department',
						width:350,
						labelWidth:125,
						id:'tf_dept',
						editable: false,
						readOnly:true,
						fieldStyle:'background:#B0C4DE ;font-weight:bold;'
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
					columnWidth:.48,
					border:false,
					layout: 'anchor',
					defaultType: 'combobox',
					items:[{	
						xtype:'textfield',
						fieldLabel:'Position/Jabatan',
						width:350,
						labelWidth:125,
						id:'tf_pos',
						editable: false,
						readOnly:true,
						fieldStyle:'background:#B0C4DE ;font-weight:bold;'
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
					columnWidth:.48,
					border:false,
					layout: 'anchor',
					defaultType: 'combobox',
					items:[{	
						xtype:'textfield',
						fieldLabel:'Grade',
						width:275,
						labelWidth:125,
						id:'tf_grade',
						editable: false,
						readOnly:true,
						fieldStyle:'background:#B0C4DE ;font-weight:bold;'
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
					columnWidth:.48,
					border:false,
					layout: 'anchor',
					defaultType: 'combobox',
					items:[{	
						xtype:'textfield',
						fieldLabel:'Location',
						width:350,
						labelWidth:125,
						id:'tf_loc',
						editable: false,
						readOnly:true,
						fieldStyle:'background:#B0C4DE ;font-weight:bold;'
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
					columnWidth:.48,
					border:false,
					layout: 'anchor',
					defaultType: 'datefield',
					items:[{	
						xtype:'datefield',
						fieldLabel:'Tanggal Pengajuan',
						width:270,
						labelWidth:125,
						id:'tf_tgl_pengajuan',
						fieldStyle:'background:#FAF0E6 ;font-weight:bold;'
						//format: 'd-M-Y',
						//value:currentTime
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
					columnWidth:.48,
					border:false,
					layout: 'anchor',
					defaultType: 'datefield',
					items:[{	
						xtype:'datefield',
						fieldLabel:'Tanggal Masuk',
						width:270,
						labelWidth:125,
						id:'tf_tgl_masuk',
						editable: false,
						readOnly:true,
						fieldStyle:'background:#B0C4DE ;font-weight:bold;'
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
					columnWidth:.48,
					border:false,
					layout: 'anchor',
					defaultType: 'datefield',
					items:[{	
						xtype:'datefield',
						fieldLabel:'Tanggal Resign',
						width:270,
						labelWidth:125,
						id:'tf_tgl_resign',
						fieldStyle:'background:#FAEBD7 ;font-weight:bold;',
						minValue : currentTime,
						listeners: {
                     		change: function(f,r,i){
								Ext.Ajax.request({
									url:'<?php echo 'isi_lamakerja.php'; ?>',
									params:{
										tanggalmasuk :Ext.getCmp('tf_tgl_masuk').getValue(),
										tanggalresign:Ext.getCmp('tf_tgl_resign').getValue(),
									},
									method:'POST',
									success:function(response){
											var json=Ext.decode(response.responseText);
											var deskripsiasli = json.results;
											Ext.getCmp('tf_lama_kerja').setValue(deskripsiasli);
									},
							});
                    	}
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
						html:'<div style="color:#FF0000"></div>',
					}]
				},{
					columnWidth:.48,
					border:false,
					layout: 'anchor',
					defaultType: 'combobox',
					items:[{	
						xtype:'textfield',
						fieldLabel:'Lama Kerja',
						width:350,
						labelWidth:125,
						id:'tf_lama_kerja',
						editable: false,
						readOnly:true,
						fieldStyle:'background:#B0C4DE ;font-weight:bold;'
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
						fieldLabel:'Manager',
						store: comboManager,
						displayField: 'DATA_NAME',
						valueField: 'DATA_VALUE',
						width:450,
						labelWidth:125,
						id:'cb_manager',
						emptyText:'- Pilih -',
						editable: true,
						fieldStyle:'background:#FAEBD7 ;font-weight:bold;',
						enableKeyEvents : true,
						minChars:1,
						listeners: {
							keypress : {
								element: 'body',
								fn: function(){
									var pemohon=Ext.getCmp('cb_pemohon').getValue();
									comboManager.load({
										params : {
											pemohon:pemohon,
										}
									});
								}
							}
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
					}]
				},{
					columnWidth:.6,
					border:false,
					layout: 'anchor',
					defaultType: 'combobox',
					items:[{	
						xtype:'textareafield',
						fieldLabel:'Keterangan',
						width:500,
						labelWidth:125,
						id:'tf_keterangan',
						fieldStyle:'background:#FAF0E6 ;font-weight:bold;'
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
					columnWidth:.48,
					border:false,
					layout: 'anchor',
					defaultType: 'combobox',
					items:[{	
						xtype:'checkbox',
						fieldLabel:'Aktif',
						width:100,
						labelWidth:125,
						id:'cb_status',
						inputValue: 'Y',
						checked: true,
					}]
				}]
			},{
				layout:'column',
				border:false,
				items:[{
					columnWidth:.59,
					border:false,
					layout: 'anchor',
					defaultType: 'combobox',
					items:[{
						xtype: 'fieldset',
						flex: 1,
						title: '<div style="color:#CD5C5C">* Attachment</div>',
						//defaultType: 'radio', // each item will be a radio button
						layout: 'anchor',
						items: [{
							layout:'column',
							border:false,
							items:[{
								columnWidth:0.75,
								border:false,
								layout: 'anchor',
								defaultType: 'textfield',
								items:[grid_upload]
							},{
								columnWidth:.12,
								border:false,
								layout: 'anchor',
								defaultType: 'button',
								items:[{
									name: 'cari',
									text: 'Browse',
									width:50,
									handler:function(){
										upload_panel.show();
									}
								}]
							},{
								columnWidth:.12,
								border:false,
								layout: 'anchor',
								defaultType: 'button',
								items:[{xtype:'button',
									text:'Delete',
									width:50,
									handler:function(){
										var hdselec = grid_upload.getSelectionModel().getSelection();
										if (hdselec != ''){
											var hdid=grid_upload.getSelectionModel().getSelection()[0].get('HD_ID');
											var file=grid_upload.getSelectionModel().getSelection()[0].get('DATA_FILE');
											if(hdid!=''){
												Ext.Ajax.request({
													url:'<?php echo 'delete_grid_upload.php'; ?>',
													params:{
														idupload:hdid,
														fiupload:file,
													},
													method:'POST',
													success:function(response){
														store_upload.load();
													},
													failure:function(result,action){
														alertDialog('Warning','Save failed');
													}
												});
											}
											else {
												alertDialog('Warning','Attachment not selected.');
											}
										}
										else {
											alertDialog('Warning','Attachment not selected.');
										}
									}
								}]
							}]	
						}]
					}]
				}]	
			},{
				xtype:'label',
				html:'&nbsp',
			},{
				xtype:'panel',
				bodyStyle: 'padding-left: 145px;padding-bottom: 50px;border:none',
				items:[{
					xtype:'button',
					text:'Simpan',
					width:50,
					handler:function(){
						var isiUpload = store_upload.count();
						if(Ext.getCmp('cb_nama_kar').getValue() != '' && Ext.getCmp('tf_tgl_resign').getValue() != ''){
							if(Ext.getCmp('cb_manager').getValue() != '' && Ext.getCmp('tf_keterangan').getValue() != ''){
								if(Ext.getCmp('tf_tgl_pengajuan').getRawValue() != ''){
									if(isiUpload > 0){
										Ext.Ajax.request({
											url:'<?php echo 'simpan_resign.php'; ?>',
											params:{
												hd_id:Ext.getCmp('hd_id_user').getValue(),
												formtype:Ext.getCmp('tf_typeform').getValue(),
												no_pengajuan:Ext.getCmp('tf_no_pengajuan').getRawValue(),
												nama_karyawan:Ext.getCmp('cb_nama_kar').getRawValue(),
												company:Ext.getCmp('tf_company').getRawValue(),
												department:Ext.getCmp('tf_dept').getRawValue(),
												position:Ext.getCmp('tf_pos').getRawValue(),
												grade:Ext.getCmp('tf_grade').getRawValue(),
												location:Ext.getCmp('tf_loc').getRawValue(),
												tglmasuk:Ext.getCmp('tf_tgl_masuk').getRawValue(),
												tglresign:Ext.getCmp('tf_tgl_resign').getRawValue(),
												tglpengajuan:Ext.getCmp('tf_tgl_pengajuan').getRawValue(),
												lamakerja:Ext.getCmp('tf_lama_kerja').getRawValue(),
												manager:Ext.getCmp('cb_manager').getRawValue(),
												keterangan:Ext.getCmp('tf_keterangan').getRawValue(),
												status:Ext.getCmp('cb_status').getValue(),
											},
											method:'POST',
											success:function(response){
												var json=Ext.decode(response.responseText);
												if (json.rows == "sukses"){
														alertDialog('Sukses','Data berhasil disimpan');
														var hd_id =json.hdid2;
														Ext.getCmp('tf_no_pengajuan').setValue('');
														Ext.getCmp('cb_nama_kar').setValue('');
														Ext.getCmp('tf_company').setValue('');
														Ext.getCmp('tf_dept').setValue('');
														Ext.getCmp('tf_pos').setValue('');
														Ext.getCmp('tf_grade').setValue('');
														Ext.getCmp('tf_loc').setValue('');
														Ext.getCmp('tf_tgl_masuk').setValue('');
														Ext.getCmp('tf_tgl_resign').setValue('');
														Ext.getCmp('tf_lama_kerja').setValue('');
														Ext.getCmp('cb_manager').setValue('');
														Ext.getCmp('tf_keterangan').setValue('');
														Ext.getCmp('cb_status').setValue(true);
														Ext.getCmp('hd_id_user').setValue('');
														Ext.Ajax.request({
															url:'<?PHP echo PATHAPP ?>/transaksi/pengajuanresign/delete_upload.php',
															method:'POST',
															success:function(response){
															},
															failure:function(error){
																alertDialog('Warning','Save failed.');
															}
														});
														store_upload.removeAll();
														Ext.getCmp('tf_typeform').setValue('tambah');
														Ext.getCmp('tf_tgl_pengajuan').setValue('');
												 }else if (json.rows == "gagal"){
													alertDialog('Kesalahan', "Data sudah di approve, Mohon buat BA");
												}
												else {
													alertDialog('Kesalahan', "Anda sudah melakukan pengajuan ");
												}
											},
											failure:function(result,action){
												alertDialog('Kesalahan','Data gagal disimpan');
											}
										});
									}
									else {
										alertDialog('Kesalahan','Attachment Wajib Diisi');
									}
								} else {
									alertDialog('Kesalahan','Tanggal Pengajuan Wajib Diisi');
								}
							}
							else {
								alertDialog('Kesalahan','Manager dan Keterangan Wajib Diisi');
							}
						}
						else {
							alertDialog('Kesalahan','Nama Karyawan dan Tanggal Resign Wajib Diisi');
						}
					}
				},{
					xtype:'label',
					html:'&nbsp',
				},{
					xtype:'button',
					text:'Cancel',
					width:50,
					handler:function(){
						Ext.getCmp('tf_no_pengajuan').setValue('');
						Ext.getCmp('cb_nama_kar').setValue('');
						Ext.getCmp('tf_company').setValue('');
						Ext.getCmp('tf_dept').setValue('');
						Ext.getCmp('tf_pos').setValue('');
						Ext.getCmp('tf_grade').setValue('');
						Ext.getCmp('tf_loc').setValue('');
						Ext.getCmp('tf_tgl_masuk').setValue('');
						Ext.getCmp('tf_tgl_resign').setValue('');
						Ext.getCmp('tf_tgl_pengajuan').setValue('');
						Ext.getCmp('tf_lama_kerja').setValue('');
						Ext.getCmp('cb_manager').setValue('');
						Ext.getCmp('tf_keterangan').setValue('');
						Ext.getCmp('cb_status').setValue(true);
						Ext.getCmp('hd_id_user').setValue('');
						Ext.getCmp('tf_typeform').setValue('tambah');
						Ext.Ajax.request({
							url:'<?PHP echo PATHAPP ?>/transaksi/pengajuanresign/delete_upload.php',
							method:'POST',
							success:function(response){
							},
							failure:function(error){
								alertDialog('Warning','Save failed.');
							}
						});
						store_upload.removeAll();
					}
				},{
					xtype:'label',
					html:'&nbsp',
				},{
					xtype:'button',
					text:'Cetak Form',
					width:100,
					handler:function(){
						var no_pengajuan = Ext.getCmp('tf_no_pengajuan').getRawValue();
						if (no_pengajuan != '')
						{
							window.open("pdf_resign.php?hdid=" + no_pengajuan + "");
						}
						else {
							alertDialog('Kesalahan', "Pilih nomor pengajuan yang ingin dicetak");
						}
					}
				}]
			},{
				layout:'column',
				border:false,
				items:[{
					columnWidth:.25,
					border:false,
					layout: 'anchor',
					defaultType: 'label',
					items:[{
						xtype:'label',
						html:'Catatan : - Untuk field yang bertanda  ',
					}]
				},{
					columnWidth:.02,
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
						html:' harus diisi.',
					}]
				}]	
			},{
				layout:'column',
				border:false,
				items:[{
					columnWidth:.9,
					border:false,
					layout: 'anchor',
					defaultType: 'label',
					items:[{
						xtype:'label',
						html:'Form ini mensupport penggunaan web browser Mozilla Firefox, mohon untuk menggunakan Mozilla Firefox.',
					}]
				}]	
			}],
		});
    contentPanel.render('page');
	Ext.Ajax.request({
		url:'<?PHP echo PATHAPP ?>/transaksi/pengajuanresign/delete_upload.php',
		method:'POST',
		success:function(response){
		},
		failure:function(error){
			alertDialog('Warning','Save failed.');
		}
	});
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