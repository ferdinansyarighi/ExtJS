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
	var currentTimeNow = new Date();
	currentTime.setDate(currentTime.getDate() + 7);
	var month = currentTime.getMonth() + 1;
	var day = currentTime.getDate();
	var year = currentTime.getFullYear();
	var tanggalskr = day + "-" + month + "-" + year;
	var rowGrid = 1;
	function GetUserRecord(userID) {
		var recordIndex = store_detil.find('DATA_ID', userID);
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
	var store_upload=new Ext.data.JsonStore({
		id:'store_upload_id',
		pageSize: 50,
		fields:[{
			name:'HD_ID'
		},{
			name:'DATA_FILE'
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
			dataIndex:'DATA_FILE',
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
					url			: '<?PHP echo PATHAPP ?>/transaksi/sik/cekupload.php',
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
								url: '<?PHP echo PATHAPP ?>/transaksi/sik/uploadattachment.php', 
								method:'POST', 
								waitTitle:'Connecting', 
								waitMsg:'Sending data...',
								
								success:function(){ 											
									store_upload.load();
									upload_panel.hide();
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
	var comboIjin = Ext.create('Ext.data.Store', {
		fields: [{
			name:'DATA_VALUE'
		},{
			name:'DATA_NAME'
		}],
		proxy:{
			type:'ajax',
			url:'<?PHP echo PATHAPP ?>/combobox/combobox_ijin.php', 
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
			url:'<?PHP echo PATHAPP ?>/combobox/combobox_pemohon.php', 
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
	var comboSPV = Ext.create('Ext.data.Store', {
		fields: [{
			name:'DATA_VALUE'
		},{
			name:'DATA_NAME'
		}],
		proxy:{
			type:'ajax',
			url:'<?PHP echo PATHAPP ?>/combobox/combobox_spv.php',
			reader: {
				type: 'json',
				root: 'data', 
				totalProperty:'total'   
			}
		}
	});
	Ext.clearForm=function(){
		Ext.getCmp('tf_hdid').setValue('')
		Ext.getCmp('tf_typeform').setValue('tambah')
		Ext.getCmp('tf_no_sik').setValue('')
		Ext.getCmp('cb_pemohon').setValue('')
		Ext.getCmp('tf_dept').setValue('')
		Ext.getCmp('cb_plant').setValue('')
		Ext.getCmp('cb_manager').setValue('- Pilih -')
		Ext.getCmp('cb_spv').setValue('- Pilih -')
		Ext.getCmp('tf_tgl_from').setValue(currentTime)
		Ext.getCmp('tf_tgl_to').setValue(currentTime)
		Ext.getCmp('tf_keterangan').setValue('')
		Ext.getCmp('tf_alamat').setValue('')
		Ext.getCmp('tf_no_telp').setValue('')
		Ext.getCmp('tf_no_hp').setValue('')
		Ext.getCmp('tf_email').setValue('')
		Ext.getCmp('tf_em').setValue('')
		Ext.getCmp('tf_emspv').setValue('')
		kategoriForm = "Cuti";
		Ext.getCmp('cb_ijin').setValue('')
		Ext.getCmp('tf_jam_from').setValue(0)
		Ext.getCmp('tf_menit_from').setValue(0)
		Ext.getCmp('tf_jam_to').setValue(0)
		Ext.getCmp('tf_menit_to').setValue(0)
		Ext.getCmp('tf_sisa_cuti').setValue(0)
		Ext.getCmp('btn_kirim').setDisabled(true)
		Ext.getCmp('rb_cuti').setDisabled(false)
		Ext.getCmp('rb_sakit').setDisabled(false)
		Ext.getCmp('rb_ijin').setDisabled(false)
		Ext.getCmp('rb_terlambat').setDisabled(false)
		Ext.getCmp('cb_status').setValue(true);
	};
	var kategoriForm = "Cuti";
	var store_popuptransaksi=new Ext.data.JsonStore({
		id:'store_popuptransaksi_id',
		pageSize: 100,
		fields:[{
			name:'HD_ID'
		},{
			name:'DATA_KATEGORI'
		},{
			name:'DATA_STATUS'
		},{
			name:'DATA_NOSIK'
		},{
			name:'DATA_IJIN'
		},{
			name:'DATA_PEMBUAT'
		},{
			name:'DATA_PEMOHON'
		},{
			name:'DATA_DEPT'
		},{
			name:'DATA_PLANT'
		},{
			name:'DATA_MANAGER'
		},{
			name:'DATA_EMAILMANAGER'
		},{
			name:'DATA_TGL_FROM'
		},{
			name:'DATA_TGL_TO'
		},{
			name:'DATA_JAM_FROM'
		},{
			name:'DATA_JAM_TO'
		},{
			name:'DATA_KETERANGAN'
		},{
			name:'DATA_ALAMAT'
		},{
			name:'DATA_NOTELP'
		},{
			name:'DATA_NOHP'
		},{
			name:'DATA_EMAIL'
		},{
			name:'DATA_SPV'
		},{
			name:'DATA_EMAILSPV'
		}],
		 proxy:{
			type:'ajax',
			url:'isi_grid_popuptransaksi.php', 
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
			width:50
		},{
			dataIndex:'HD_ID',
			header:'id',
			width:100,
			hidden:true
		},{
			dataIndex:'DATA_NOSIK',
			header:'No Surat Ijin',
			width:200,
			//hidden:true
		},{
			dataIndex:'DATA_PEMOHON',
			header:'Pemohon',
			width:200,
			//hidden:true
		},{
			dataIndex:'DATA_KATEGORI',
			header:'Kategori',
			width:100,
			//hidden:true
		},{
			dataIndex:'DATA_STATUS',
			header:'Status',
			width:100,
			//hidden:true
		},{
			dataIndex:'DATA_PEMBUAT',
			header:'Pembuat',
			width:100,
			hidden:true
		},{
			dataIndex:'DATA_DEPT',
			header:'Dept',
			width:100,
			hidden:true
		},{
			dataIndex:'DATA_PLANT',
			header:'Plant',
			width:75,
			hidden:true
		},{
			dataIndex:'DATA_MANAGER',
			header:'Manager',
			width:75,
			hidden:true
		},{
			dataIndex:'DATA_EMAILMANAGER',
			header:'Email Manager',
			width:150,
			hidden:true
		},{
			dataIndex:'DATA_TGL_FROM',
			header:'Tgl From',
			width:100,
			hidden:true
		},{
			dataIndex:'DATA_TGL_TO',
			header:'Tgl To',
			width:50,
			hidden:true
		},{
			dataIndex:'DATA_JAM_FROM',
			header:'Jam From',
			width:100,
			hidden:true
		},{
			dataIndex:'DATA_JAM_TO',
			header:'Jam To',
			width:100,
			hidden:true
		},{
			dataIndex:'DATA_KETERANGAN',
			header:'Keterangan',
			width:150,
			hidden:true
		},{
			dataIndex:'DATA_ALAMAT',
			header:'Alamat',
			width:150,
			hidden:true
		},{
			dataIndex:'DATA_NOTELP',
			header:'No Telp',
			width:150,
			hidden:true
		},{
			dataIndex:'DATA_NOHP',
			header:'No HP',
			width:100,
			hidden:true
		},{
			dataIndex:'DATA_EMAIL',
			header:'Email',
			width:100,
			hidden:true
		},{
			dataIndex:'DATA_SPV',
			header:'SPV',
			width:75,
			hidden:true
		},{
			dataIndex:'DATA_EMAILSPV',
			header:'Email SPV',
			width:150,
			hidden:true
		}],
		listeners: {
			dblclick: {
				element: 'body', //bind to the underlying body property on the panel
				fn: function(){ 
					var hdid=grid_popuptransaksi.getSelectionModel().getSelection()[0].get('HD_ID');
					var vkategori=grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_KATEGORI');
					if (vkategori == 'Cuti'){
						kategoriForm = "Cuti";
						Ext.getCmp('rb_cuti').setValue(true);
						Ext.getCmp('rb_sakit').setValue(false);
						Ext.getCmp('rb_ijin').setValue(false);
						Ext.getCmp('rb_terlambat').setValue(false);
						/* Ext.getCmp('tf_tgl_from').setMinValue(Ext.Date.add(new Date(), Ext.Date.DAY, 7));
						Ext.getCmp('tf_tgl_to').setMinValue(Ext.Date.add(new Date(), Ext.Date.DAY, 7));
						Ext.getCmp('tf_tgl_from').setMaxValue(Ext.Date.add(new Date(), Ext.Date.YEAR, 5));
						Ext.getCmp('tf_tgl_to').setMaxValue(Ext.Date.add(new Date(), Ext.Date.YEAR, 5)); */
						var currentTime = new Date();
						currentTime.setDate(currentTime.getDate() + 7);
						Ext.getCmp('tf_tgl_from').setValue(currentTime);
						Ext.getCmp('tf_tgl_to').setValue(currentTime);
					}
					if (vkategori == 'Sakit'){
						kategoriForm = "Sakit";
						Ext.getCmp('rb_cuti').setValue(false);
						Ext.getCmp('rb_sakit').setValue(true);
						Ext.getCmp('rb_ijin').setValue(false);
						Ext.getCmp('rb_terlambat').setValue(false);
						/* Ext.getCmp('tf_tgl_from').setMinValue(Ext.Date.add(new Date(), Ext.Date.DAY, -100));
						Ext.getCmp('tf_tgl_to').setMinValue(Ext.Date.add(new Date(), Ext.Date.DAY, -1));
						Ext.getCmp('tf_tgl_from').setMaxValue(Ext.Date.add(new Date(), Ext.Date.DAY, -1));
						Ext.getCmp('tf_tgl_to').setMaxValue(Ext.Date.add(new Date(), Ext.Date.DAY, -1)); */
						var currentTime = new Date();
						currentTime.setDate(currentTime.getDate() + -1);
						Ext.getCmp('tf_tgl_from').setValue(currentTime);
						Ext.getCmp('tf_tgl_to').setValue(currentTime);
					}
					if (vkategori == 'Ijin'){
						kategoriForm = "Ijin";
						Ext.getCmp('rb_cuti').setValue(false);
						Ext.getCmp('rb_sakit').setValue(false);
						Ext.getCmp('rb_ijin').setValue(true);
						Ext.getCmp('rb_terlambat').setValue(false);
						Ext.getCmp('cb_ijin').setDisabled(false);
						/* Ext.getCmp('tf_tgl_from').setMinValue(Ext.Date.add(new Date(), Ext.Date.DAY, 1));
						Ext.getCmp('tf_tgl_to').setMinValue(Ext.Date.add(new Date(), Ext.Date.DAY, 1));
						Ext.getCmp('tf_tgl_from').setMaxValue(Ext.Date.add(new Date(), Ext.Date.YEAR, 5));
						Ext.getCmp('tf_tgl_to').setMaxValue(Ext.Date.add(new Date(), Ext.Date.YEAR, 5)); */
						var currentTime = new Date();
						currentTime.setDate(currentTime.getDate() + 1);
						Ext.getCmp('tf_tgl_from').setValue(currentTime);
						Ext.getCmp('tf_tgl_to').setValue(currentTime);
					}
					if (vkategori == 'Terlambat'){
						kategoriForm = "Terlambat";
						Ext.getCmp('rb_cuti').setValue(false);
						Ext.getCmp('rb_sakit').setValue(false);
						Ext.getCmp('rb_ijin').setValue(false);
						Ext.getCmp('rb_terlambat').setValue(true);
						/* Ext.getCmp('tf_tgl_from').setMinValue(Ext.Date.add(new Date(), Ext.Date.DAY, 0));
						Ext.getCmp('tf_tgl_to').setMinValue(Ext.Date.add(new Date(), Ext.Date.DAY, 0));
						Ext.getCmp('tf_tgl_from').setMaxValue(Ext.Date.add(new Date(), Ext.Date.YEAR, 5));
						Ext.getCmp('tf_tgl_to').setMaxValue(Ext.Date.add(new Date(), Ext.Date.YEAR, 5)); */
						var currentTime = new Date();
						currentTime.setDate(currentTime.getDate() + 0);
						Ext.getCmp('tf_tgl_from').setValue(currentTime);
						Ext.getCmp('tf_tgl_to').setValue(currentTime);
					}
					var vstatus=grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_STATUS');
					if(vstatus == 'AKTIF'){
						Ext.getCmp('cb_status').setValue(true);
					} else {
						Ext.getCmp('cb_status').setValue(false);
					}
					Ext.getCmp('tf_hdid').setValue(hdid);
					Ext.getCmp('tf_no_sik').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_NOSIK'));
					//Ext.getCmp('tf_pembuat').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_PEMBUAT'));
					Ext.getCmp('cb_ijin').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_IJIN'));
					var vPemohon = grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_PEMOHON');
					Ext.getCmp('cb_pemohon').setValue(vPemohon);
					Ext.Ajax.request({
						url:'<?php echo 'isi_cuti.php'; ?>',
							params:{
								nama_pem:grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_PEMOHON'),
								kategoriForm:vkategori,
							},
							success:function(response){
								var json=Ext.decode(response.responseText);
								var sisaCuti = json.results;
								Ext.getCmp('tf_sisa_cuti').setValue(sisaCuti);
							},
						method:'POST',
					});
					Ext.getCmp('tf_dept').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_DEPT'));
					Ext.getCmp('cb_plant').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_PLANT'));
					Ext.getCmp('cb_manager').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_MANAGER'));
					Ext.getCmp('tf_em').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_EMAILMANAGER'));
					Ext.getCmp('cb_spv').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_SPV'));
					Ext.getCmp('tf_emspv').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_EMAILSPV'));
					Ext.getCmp('tf_tgl_from').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_TGL_FROM'));
					Ext.getCmp('tf_tgl_to').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_TGL_TO'));
					var vjamfrom=grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_JAM_FROM');
					var vjamfromsplit = vjamfrom.split(':');
					var xjamfrom = vjamfromsplit[0];
					var xmenitfrom = vjamfromsplit[1];
					Ext.getCmp('tf_jam_from').setValue(xjamfrom);
					Ext.getCmp('tf_menit_from').setValue(xmenitfrom);
					var vjamto=grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_JAM_TO');
					var vjamtosplit = vjamto.split(':');
					var xjamto = vjamtosplit[0];
					var xmenitto = vjamtosplit[1];
					Ext.getCmp('tf_jam_to').setValue(xjamto);
					Ext.getCmp('tf_menit_to').setValue(xmenitto);
					Ext.getCmp('tf_keterangan').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_KETERANGAN'));
					Ext.getCmp('tf_alamat').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_ALAMAT'));
					Ext.getCmp('tf_no_telp').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_NOTELP'));
					Ext.getCmp('tf_no_hp').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_NOHP'));
					Ext.getCmp('tf_email').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_EMAIL'));
					Ext.Ajax.request({
						url:'<?PHP echo PATHAPP ?>/transaksi/sik/insert_grid_upload.php',
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
					Ext.getCmp('btn_kirim').setDisabled(false);
					Ext.getCmp('rb_cuti').setDisabled(true);
					Ext.getCmp('rb_sakit').setDisabled(true);
					Ext.getCmp('rb_ijin').setDisabled(true);
					Ext.getCmp('rb_terlambat').setDisabled(true);
					comboManager.setProxy({
						type:'ajax',
						url:'<?PHP echo PATHAPP ?>/combobox/combobox_manager.php?pemohon=' + vPemohon,
						reader: {
							type: 'json',
							root: 'data', 
							totalProperty:'total'   
						}
					});
					comboManager.load();
					comboSPV.setProxy({
						type:'ajax',
						url:'<?PHP echo PATHAPP ?>/combobox/combobox_spv.php?pemohon=' + vPemohon,
						reader: {
							type: 'json',
							root: 'data', 
							totalProperty:'total'   
						}
					});
					comboSPV.load();
					PopupSIK.hide();
				}
			}
		}
	});
	var PopupSIK=Ext.create('Ext.Window', {
		title: 'Cari No Surat Ijin Karyawan',
		width: 700,
		height: 350,							
		layout: 'fit',
		closeAction:'hide',
		tbar:[,{
			xtype:'label',
			html:'&nbsp',
		},{
			xtype: 'fieldcontainer',
			defaultType: 'checkboxfield',
			items: [
				{
					name      : 'topping',
					inputValue: '1',
					id        : 'cb_filter1_pop',
					checked   : true,
				}
			]
		}, {
			xtype:'textfield',
			fieldLabel:'No SIK',
			maxLengthText:5,
			id:'tf_filter_pop',
			labelWidth:75,
			listeners: {
				 change: function(field,newValue,oldValue){
						field.setValue(newValue.toUpperCase());
				}
			}
		},{
			xtype:'label',
			html:'&nbsp',
		}, {
			xtype: 'fieldcontainer',
			defaultType: 'checkboxfield',
			items: [
				{
					name      : 'topping',
					inputValue: '1',
					id        : 'cb_filter2_pop',
					checked   : true,
				}
			]
		}, {
			xtype: 'datefield',
			name: 'date1',
			fieldLabel: 'Tanggal Pembuatan',
			width:200,
			labelWidth:100,
			id:'tf_filter_from_pop'
		}, {
			xtype: 'datefield',
			name: 'date1',
			fieldLabel: 'to',
			width:130,
			labelWidth:30,
			id:'tf_filter_to_pop'
		},{
			xtype:'label',
			html:'&nbsp',
		},{
			xtype:'button',
			text:'Cari',
			scale:'small',
			width: 45,
			border: 1,
			style: {
				borderColor: 'black',
				borderStyle: 'solid'
			},
			cls:'button-popup',
			handler:function(){
				if(Ext.getCmp('tf_filter_from_pop').getValue()<=Ext.getCmp('tf_filter_to_pop').getValue()){
					maskgrid = new Ext.LoadMask(Ext.getCmp('grid_popuptransaksi_id'), {msg: "Memuat . . ."});
					maskgrid.show();
					store_popuptransaksi.load({
						params:{
							tffilter:Ext.getCmp('tf_filter_pop').getValue(),
							tglfrom:Ext.getCmp('tf_filter_from_pop').getRawValue(),
							tglto:Ext.getCmp('tf_filter_to_pop').getRawValue(),
							cb1:Ext.getCmp('cb_filter1_pop').getValue(),
							cb2:Ext.getCmp('cb_filter2_pop').getValue(),
						}
					});
				}
				else {
					alertDialog('Kesalahan','Tannggal from lebih besar dari tanggal to.');
				}
				
			}
		}],
		items: grid_popuptransaksi
	});
	var contentPanel = Ext.create('Ext.panel.Panel',{
			//title:'Form Input Tanda Terima',
			//region:'center',
			bodyStyle: 'spacing: 10px;border:none',
			items:[{
				xtype:'label',
				html:'<div align="center"><font size="5"><b>Form Pengajuan Ijin Karyawan</b></font></div>',
			},{
				xtype:'label',
				html:'&nbsp',
			},{		
				xtype:'textfield',
				fieldLabel:'id sik',
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
						xtype: 'fieldset',
						flex: 1,
						title: 'Kategori',
						defaultType: 'radio', // each item will be a radio button
						layout: 'anchor',
						items: [{
							xtype: 'radiogroup',
							labelWidth:50,
							items: [
								{boxLabel: 'Cuti', id : 'rb_cuti', name: 'rb_kategori', inputValue: 1, checked: true},
								{boxLabel: 'Sakit', id : 'rb_sakit', name: 'rb_kategori', inputValue: 2},
								{boxLabel: 'Ijin', id : 'rb_ijin', name: 'rb_kategori', inputValue: 3},
								{boxLabel: 'Terlambat', id : 'rb_terlambat', name: 'rb_kategori', inputValue: 4},
							],
							listeners: {
								change:function(){
									if (Ext.getCmp('rb_cuti').getValue()){
										Ext.clearForm();
									    Ext.getCmp('tf_jam_from').setValue(8);
									    Ext.getCmp('tf_jam_to').setValue(16);
										kategoriForm = "Cuti";
										Ext.getCmp('cb_ijin').setDisabled(true);
										/* Ext.getCmp('tf_tgl_from').setMinValue(Ext.Date.add(new Date(), Ext.Date.YEAR, -7));
										Ext.getCmp('tf_tgl_to').setMinValue(Ext.Date.add(new Date(), Ext.Date.YEAR, -7));
										Ext.getCmp('tf_tgl_from').setMaxValue(Ext.Date.add(new Date(), Ext.Date.YEAR, 5));
										Ext.getCmp('tf_tgl_to').setMaxValue(Ext.Date.add(new Date(), Ext.Date.YEAR, 5));  */
										var currentTime = new Date();
										currentTime.setDate(currentTime.getDate() + 7);
										Ext.getCmp('tf_tgl_from').setValue(currentTime);
										Ext.getCmp('tf_tgl_to').setValue(currentTime);
										Ext.Ajax.request({
											url:'<?PHP echo PATHAPP ?>/transaksi/sik/delete_upload.php',
											method:'POST',
											success:function(response){
											},
											failure:function(error){
												alertDialog('Warning','Save failed.');
											}
										});
										store_upload.removeAll();
									} else if (Ext.getCmp('rb_sakit').getValue()) {
										Ext.clearForm();
										kategoriForm = "Sakit";
										Ext.getCmp('cb_ijin').setDisabled(true);
										/* Ext.getCmp('tf_tgl_from').setMinValue(Ext.Date.add(new Date(), Ext.Date.DAY, -100));
										Ext.getCmp('tf_tgl_to').setMinValue(Ext.Date.add(new Date(), Ext.Date.DAY, -1));
										Ext.getCmp('tf_tgl_from').setMaxValue(Ext.Date.add(new Date(), Ext.Date.DAY, -1));
										Ext.getCmp('tf_tgl_to').setMaxValue(Ext.Date.add(new Date(), Ext.Date.DAY, -1)); */
										var currentTime = new Date();
										currentTime.setDate(currentTime.getDate() + -1);
										Ext.getCmp('tf_tgl_from').setValue(currentTime);
										Ext.getCmp('tf_tgl_to').setValue(currentTime);
										Ext.Ajax.request({
											url:'<?PHP echo PATHAPP ?>/transaksi/sik/delete_upload.php',
											method:'POST',
											success:function(response){
											},
											failure:function(error){
												alertDialog('Warning','Save failed.');
											}
										});
										store_upload.removeAll();
									} else if (Ext.getCmp('rb_ijin').getValue()) {
										Ext.clearForm();
										kategoriForm = "Ijin";
										Ext.getCmp('cb_ijin').setDisabled(false);
										/* Ext.getCmp('tf_tgl_from').setMinValue(Ext.Date.add(new Date(), Ext.Date.DAY, 1));
										Ext.getCmp('tf_tgl_to').setMinValue(Ext.Date.add(new Date(), Ext.Date.DAY, 1));
										Ext.getCmp('tf_tgl_from').setMaxValue(Ext.Date.add(new Date(), Ext.Date.YEAR, 5));
										Ext.getCmp('tf_tgl_to').setMaxValue(Ext.Date.add(new Date(), Ext.Date.YEAR, 5)); */
										var currentTime = new Date();
										currentTime.setDate(currentTime.getDate() + 1);
										Ext.getCmp('tf_tgl_from').setValue(currentTime);
										Ext.getCmp('tf_tgl_to').setValue(currentTime);
										Ext.Ajax.request({
											url:'<?PHP echo PATHAPP ?>/transaksi/sik/delete_upload.php',
											method:'POST',
											success:function(response){
											},
											failure:function(error){
												alertDialog('Warning','Save failed.');
											}
										});
										store_upload.removeAll();
									} else {
										Ext.clearForm();
										kategoriForm = "Terlambat";
										Ext.getCmp('cb_ijin').setDisabled(true);
										/* Ext.getCmp('tf_tgl_from').setMinValue(Ext.Date.add(new Date(), Ext.Date.DAY, 0));
										Ext.getCmp('tf_tgl_to').setMinValue(Ext.Date.add(new Date(), Ext.Date.DAY, 0));
										Ext.getCmp('tf_tgl_from').setMaxValue(Ext.Date.add(new Date(), Ext.Date.YEAR, 5));
										Ext.getCmp('tf_tgl_to').setMaxValue(Ext.Date.add(new Date(), Ext.Date.YEAR, 5)); */
										var currentTime = new Date();
										currentTime.setDate(currentTime.getDate() + 0);
										Ext.getCmp('tf_tgl_from').setValue(currentTime);
										Ext.getCmp('tf_tgl_to').setValue(currentTime);
										Ext.Ajax.request({
											url:'<?PHP echo PATHAPP ?>/transaksi/sik/delete_upload.php',
											method:'POST',
											success:function(response){
											},
											failure:function(error){
												alertDialog('Warning','Save failed.');
											}
										});
										store_upload.removeAll();
										//console.log("terlambat");
									}
								}
							}
						}]
					}]
				},{
					columnWidth:.02,
					border:false,
					layout: 'anchor',
					defaultType: 'label',
					items:[{
						xtype:'label',
						html:'&nbsp',
					}]
				},{
					columnWidth:.15,
					border:false,
					layout: 'anchor',
					defaultType: 'combobox',
					items:[{
						xtype: 'fieldset',
						flex: 1,
						title: 'Status',
						defaultType: 'checkbox', // each item will be a radio button
						layout: 'anchor',
						items: [{
							xtype: 'checkboxgroup',
							labelWidth:50,
							items: [
								{boxLabel: 'Aktif', id: 'cb_status', name: 'cb_status', inputValue: 1, checked: true},
							]
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
						html:'',
					}]
				},{
					columnWidth:.48,
					border:false,
					layout: 'anchor',
					defaultType: 'textfield',
					items:[{
						name: 'tf_no_sik',
						fieldLabel: 'No',
						width:400,
						labelWidth:90,
						id:'tf_no_sik',
						editable: false,
						readOnly:true,
						fieldStyle:'background:#B8B8B8 ;font-weight:bold;'
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
							Ext.getCmp('tf_filter_pop').setValue('');
							Ext.getCmp('tf_filter_from_pop').setValue(currentTimeNow);
							Ext.getCmp('tf_filter_to_pop').setValue(currentTimeNow);
							Ext.getCmp('cb_filter1_pop').setValue('true');
							Ext.getCmp('cb_filter2_pop').setValue('true');
							store_popuptransaksi.removeAll();
							PopupSIK.show();
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
						xtype:'textfield',
						fieldLabel:'Pembuat',
						width:490,
						labelWidth:90,
						id:'tf_pembuat',
						value:"<?PHP echo $emp_name ?>",
						readOnly:true,
						fieldStyle:'background:#B8B8B8 ;font-weight:bold;'
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
						fieldLabel:'Ijin Khusus',
						store: comboIjin,
						displayField: 'DATA_NAME',
						valueField: 'DATA_VALUE',
						width:490,
						labelWidth:90,
						id:'cb_ijin',
						value:'',
						emptyText : '- Pilih -',
						editable: false,
						enableKeyEvents : true,
						minChars:1,
						listeners: {
							select:function(f,r,i){
								var nama_ijin=r[0].data.DATA_VALUE;
								//console.log(nama_man);
								Ext.Ajax.request({
									url:'<?php echo 'cek_ijin.php'; ?>',
										params:{
											nama_ijin:nama_ijin,
										},
										success:function(response){
											var json=Ext.decode(response.responseText);
											var jumHari = json.results;
											Ext.getCmp('tf_tgl_to').setMaxValue(Ext.Date.add(new Date(), Ext.Date.DAY, parseFloat(jumHari)));
										},
									method:'POST',
								});
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
					}]
				},{
					columnWidth:.47,
					border:false,
					layout: 'anchor',
					defaultType: 'combobox',
					items:[{		
						xtype:'combobox',
						fieldLabel:'Pemohon',
						store: comboPemohon,
						displayField: 'DATA_NAME',
						valueField: 'DATA_VALUE',
						width:400,
						labelWidth:90,
						id:'cb_pemohon',
						value:'',
						emptyText : '- Pilih -',
						//editable: false 
						enableKeyEvents : true,
						minChars:1,
						listeners: {
							select:function(f,r,i){
								var nama_pem=r[0].data.DATA_VALUE;
								//console.log(nama_pem);
								Ext.Ajax.request({
									url:'<?php echo 'isi_dept.php'; ?>',
										params:{
											nama_pem:nama_pem,
										},
										success:function(response){
											var json=Ext.decode(response.responseText);
											var deskripsiasli = json.results;
											var deskripsisplit = deskripsiasli.split('.');
											var nama_dept = deskripsisplit[2];
											Ext.getCmp('tf_dept').setValue(nama_dept);
										},
									method:'POST',
								});
								Ext.Ajax.request({
									url:'<?php echo 'isi_cuti.php'; ?>',
										params:{
											nama_pem:nama_pem,
											kategoriForm:kategoriForm,
										},
										success:function(response){
											var json=Ext.decode(response.responseText);
											var sisaCuti = json.results;
											Ext.getCmp('tf_sisa_cuti').setValue(sisaCuti);
										},
									method:'POST',
								});
								Ext.Ajax.request({
									url:'<?php echo 'isi_plant.php'; ?>',
										params:{
											nama_pem:nama_pem,
										},
										success:function(response){
											var json=Ext.decode(response.responseText);
											var deskripsiasli = json.results;
											Ext.getCmp('cb_plant').setValue(deskripsiasli);
										},
									method:'POST',
								});
								//var pemohon=Ext.getCmp('cb_pemohon').getValue();
								comboManager.setProxy({
									type:'ajax',
									url:'<?PHP echo PATHAPP ?>/combobox/combobox_manager.php?pemohon=' + nama_pem,
									reader: {
										type: 'json',
										root: 'data', 
										totalProperty:'total'   
									}
								});
								comboManager.load();
								comboSPV.setProxy({
									type:'ajax',
									url:'<?PHP echo PATHAPP ?>/combobox/combobox_spv.php?pemohon=' + nama_pem,
									reader: {
										type: 'json',
										root: 'data', 
										totalProperty:'total'   
									}
								});
								comboSPV.load();
							},
							keypress : {
								element: 'body',
								fn: function(){
									var pjname=Ext.getCmp('cb_pemohon').getValue();
									combopj.load({
										params : {
											pjname:pjname,
										}
									});
								}
							}
						}
					}]
				},{
					columnWidth:.1,
					border:false,
					layout: 'anchor',
					defaultType: 'textfield',
					items:[{
						xtype:'textfield',
						fieldLabel:'Sisa Cuti',
						width:100,
						labelWidth:50,
						id:'tf_sisa_cuti',
						value:'',
						readOnly:true,
						fieldStyle:'background:#B8B8B8 ;font-weight:bold;'
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
						xtype:'textfield',
						fieldLabel:'Dept.',
						width:490,
						labelWidth:90,
						id:'tf_dept',
						value:'',
						readOnly:true,
						fieldStyle:'background:#B8B8B8 ;font-weight:bold;'
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
						fieldLabel:'Plant',
						store: comboPlant,
						displayField: 'DATA_NAME',
						valueField: 'DATA_VALUE',
						width:490,
						labelWidth:90,
						id:'cb_plant',
						value:'',
						emptyText : '- Pilih -',
						//editable: false 
						enableKeyEvents : true,
						minChars:1,
						readOnly:true,
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
						fieldLabel:'SPV',
						store: comboSPV,
						displayField: 'DATA_NAME',
						valueField: 'DATA_VALUE',
						width:490,
						labelWidth:90,
						id:'cb_spv',
						value:'- Pilih -',
						//emptyText : '- Pilih -',
						editable: false ,
						enableKeyEvents : true,
						minChars:1,
						queryMode: 'local',
						listeners: {
							select:function(f,r,i){
								var nama_man=r[0].data.DATA_VALUE;
								//console.log(nama_man);
								Ext.Ajax.request({
									url:'<?php echo 'isi_email.php'; ?>',
										params:{
											nama_man:nama_man,
										},
										success:function(response){
											var json=Ext.decode(response.responseText);
											var deskripsiasli = json.results;
											Ext.getCmp('tf_emspv').setValue(deskripsiasli);
										},
									method:'POST',
								});
							},
							keypress : {
								element: 'body',
								fn: function(){
									var pemohon=Ext.getCmp('cb_pemohon').getValue();
									comboSPV.load({
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
						html:'',
					}]
				},{
					columnWidth:.8,
					border:false,
					layout: 'anchor',
					defaultType: 'textfield',
					items:[{
						xtype:'textfield',
						fieldLabel:'Email SPV',
						width:490,
						labelWidth:90,
						id:'tf_emspv',
						value:'',
						readOnly:true,
						fieldStyle:'background:#B8B8B8 ;font-weight:bold;'
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
						width:490,
						labelWidth:90,
						id:'cb_manager',
						value:'- Pilih -',
						//emptyText : '- Pilih -',
						editable: false,
						enableKeyEvents : true,
						minChars:1,
						queryMode: 'local',
						listeners: {
							/* beforequery: function(queryEvent, eOpts){
								//delete queryEvent.combo.lastQuery;
								var pemohon=Ext.getCmp('cb_pemohon').getValue();
								queryEvent.combo.store.proxy.extraParams = {
									pemohon:pemohon,
								}
							},  */
							select:function(f,r,i){
								var nama_man=r[0].data.DATA_VALUE;
								//console.log(nama_man);
								Ext.Ajax.request({
									url:'<?php echo 'isi_email.php'; ?>',
										params:{
											nama_man:nama_man,
										},
										success:function(response){
											var json=Ext.decode(response.responseText);
											var deskripsiasli = json.results;
											Ext.getCmp('tf_em').setValue(deskripsiasli);
										},
									method:'POST',
								});
							},
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
						html:'',
					}]
				},{
					columnWidth:.8,
					border:false,
					layout: 'anchor',
					defaultType: 'textfield',
					items:[{
						xtype:'textfield',
						fieldLabel:'Email Manager',
						width:490,
						labelWidth:90,
						id:'tf_em',
						value:'',
						readOnly:true,
						fieldStyle:'background:#B8B8B8 ;font-weight:bold;'
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
					columnWidth:.27,
					border:false,
					layout: 'anchor',
					defaultType: 'combobox',
					items:[{
						xtype:'datefield',
						fieldLabel:'Tanggal',
						width:225,
						labelWidth:90,
						id:'tf_tgl_from',
						editable: false
					}]
				},{
					columnWidth:.3,
					border:false,
					layout: 'anchor',
					defaultType: 'combobox',
					items:[{
						xtype:'datefield',
						fieldLabel:'s/d',
						width:165,
						labelWidth:30,
						id:'tf_tgl_to',
						editable: false
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
					columnWidth:.17,
					border:false,
					layout: 'anchor',
					defaultType: 'numberfield',
					items:[{
						xtype:'numberfield',
						fieldLabel:'Jam',
						width:140,
						labelWidth:90,
						id:'tf_jam_from',
						value: 0,
						minValue: 0,
						maxValue: 23,
					}]
				},{
					columnWidth:.1,
					border:false,
					layout: 'anchor',
					defaultType: 'numberfield',
					items:[{
						xtype:'numberfield',
						fieldLabel:'Menit',
						width:80,
						labelWidth:30,
						id:'tf_menit_from',
						value: 0,
						minValue: 0,
						maxValue: 59,
					}]
				},{
					columnWidth:.1,
					border:false,
					layout: 'anchor',
					defaultType: 'numberfield',
					items:[{
						xtype:'numberfield',
						fieldLabel:'s/d',
						width:80,
						labelWidth:30,
						id:'tf_jam_to',
						value: 0,
						minValue: 0,
						maxValue: 23,
					}]
				},{
					columnWidth:.127,
					border:false,
					layout: 'anchor',
					defaultType: 'numberfield',
					items:[{
						xtype:'numberfield',
						fieldLabel:'Menit',
						width:80,
						labelWidth:30,
						id:'tf_menit_to',
						value: 0,
						minValue: 0,
						maxValue: 59,
					}]
				}
				/* ,{
					columnWidth:.315,
					border:false,
					layout: 'anchor',
					defaultType: 'timefield',
					items:[{
						xtype:'timefield',
						fieldLabel:'Jam',
						width:255,
						labelWidth:90,
						id:'tf_jam_from',
						editable: false,
						minValue: '8:00 AM',
						maxValue: '4:00 PM'
					}]
				},{
					columnWidth:.3,
					border:false,
					layout: 'anchor',
					defaultType: 'timefield',
					items:[{
						xtype:'timefield',
						fieldLabel:'s/d',
						width:220,
						labelWidth:30,
						id:'tf_jam_to',
						editable: false,
						minValue: '8:00 AM',
						maxValue: '4:00 PM'
					}]
				} */
				]	
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
					defaultType: 'textareafield',
					items:[{
						xtype:'textareafield',
						fieldLabel:'Keterangan',
						width:490,
						labelWidth:90,
						id:'tf_keterangan'
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
						title: 'Data yang bisa dihubungi sewaktu ijin',
						//defaultType: 'radio', // each item will be a radio button
						layout: 'anchor',
						items: [{
							layout:'column',
							border:false,
							items:[{
								columnWidth:.03,
								border:false,
								layout: 'anchor',
								defaultType: 'label',
								items:[{
									xtype:'label',
									html:'',
								}]
							},{
								columnWidth:0.95,
								border:false,
								layout: 'anchor',
								defaultType: 'textfield',
								items:[{
									xtype:'textfield',
									fieldLabel:'Alamat',
									width:450,
									labelWidth:80,
									id:'tf_alamat',
									value:'',
									//readOnly:true,
									//fieldStyle:'background:#B8B8B8 ;font-weight:bold;'
								}]
							},{
								columnWidth:.03,
								border:false,
								layout: 'anchor',
								defaultType: 'label',
								items:[{
									xtype:'label',
									html:'',
								}]
							},{
								columnWidth:0.95,
								border:false,
								layout: 'anchor',
								defaultType: 'textfield',
								items:[{
									xtype:'textfield',
									fieldLabel:'No. Tlp',
									width:450,
									labelWidth:80,
									id:'tf_no_telp',
									value:'',
									//readOnly:true,
									//fieldStyle:'background:#B8B8B8 ;font-weight:bold;'
								}]
							},{
								columnWidth:.03,
								border:false,
								layout: 'anchor',
								defaultType: 'label',
								items:[{
									xtype:'label',
									html:'<div style="color:#FF0000">*</div>',
								}]
							},{
								columnWidth:0.95,
								border:false,
								layout: 'anchor',
								defaultType: 'textfield',
								items:[{
									xtype:'textfield',
									fieldLabel:'No. HP',
									width:450,
									labelWidth:80,
									id:'tf_no_hp',
									value:'',
									//readOnly:true,
									//fieldStyle:'background:#B8B8B8 ;font-weight:bold;'
								}]
							},{
								columnWidth:.03,
								border:false,
								layout: 'anchor',
								defaultType: 'label',
								items:[{
									xtype:'label',
									html:'',
								}]
							},{
								columnWidth:0.95,
								border:false,
								layout: 'anchor',
								defaultType: 'textfield',
								items:[{
									xtype:'textfield',
									fieldLabel:'Email',
									width:450,
									labelWidth:80,
									id:'tf_email',
									value:'',
									//readOnly:true,
									//fieldStyle:'background:#B8B8B8 ;font-weight:bold;'
								}]
							}]	
						}]
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
						title: 'Attachment',
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
											if(hdid!=''){
												Ext.Ajax.request({
													url:'<?php echo 'delete_grid_upload.php'; ?>',
													params:{
														idupload:hdid,
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
					columnWidth:.5,
					border:false,
					layout: 'anchor',
					defaultType: 'label',
					items:[{
						xtype:'label',
						html:'&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp- Untuk kategori sakit attachment harus diisi.',
					}]
				}]	
			},{
				xtype:'label',
				html:'&nbsp',
			},{
				xtype:'label',
				html:'&nbsp',
			},{
				xtype:'panel',
				bodyStyle: 'padding-left: 300px;padding-bottom: 50px;border:none',
				items:[{
					xtype:'button',
					text:'Simpan',
					width:75,
					id:'btn_simpan',
					handler:function(){
						var xSakit = "gagal";
						var xNoHP = "gagal";
						if (Ext.getCmp('cb_pemohon').getValue()!=''){
							if (Ext.getCmp('cb_plant').getValue()!='') {
								if(Ext.getCmp('cb_spv').getValue()!='- Pilih -' || Ext.getCmp('cb_manager').getValue()!='- Pilih -'){
									console.log(Ext.getCmp('tf_tgl_from').getRawValue());
									console.log(Ext.getCmp('tf_tgl_to').getRawValue());
									if(Ext.getCmp('tf_tgl_from').getValue() <= Ext.getCmp('tf_tgl_to').getValue()){
										if(Ext.getCmp('tf_jam_from').getValue()!='0' || Ext.getCmp('tf_menit_from').getValue()!='0' || Ext.getCmp('tf_jam_to').getValue()!='0' || Ext.getCmp('tf_menit_to').getValue()!='0'){
											if(Ext.getCmp('tf_keterangan').getValue()!=''){
												if(Ext.getCmp('rb_sakit').getValue()){
													var isiUpload = store_upload.count();
													if(isiUpload>0){
														xSakit = "sukses";
													}
												} else {
													xSakit = "sukses";
												}
												if(Ext.getCmp('rb_sakit').getValue() || Ext.getCmp('rb_terlambat').getValue()){
													xNoHP = "sukses";
												}
												if(xNoHP == "sukses"){
													if(xSakit == "sukses"){
														// Ext.Ajax.request({
															// url:'<?php echo 'cek_SIK.php'; ?>',
																// params:{
																	// pemohon:Ext.getCmp('cb_pemohon').getValue(),
																	// tglFrom:Ext.getCmp('tf_tgl_from').getRawValue(),
																	// tglTo:Ext.getCmp('tf_tgl_to').getRawValue(),
																// },
																// success:function(response){
																	// var json=Ext.decode(response.responseText);
																	// var jumSIK = json.results;
																	// if(jumSIK >= 1){
																		
																	// } else {
																		// alertDialog('Kesalahan', "Tanggal tersebut sudah pernah dbuatkan SIK.");
																	// }
																// },
															// method:'POST',
														// });
														var statusString = 0;
														if (Ext.getCmp('cb_status').getValue()) {
															statusString = 1;
														} else {
															statusString = 0;
														}
														Ext.getCmp('btn_simpan').setDisabled(true);
														// maskgrid = new Ext.LoadMask(Ext.getCmp('grid_popuptransaksi_id'), {msg: "Proses Menyimpan . . ."});
														// maskgrid.show();
														Ext.Ajax.request({
															url:'<?php echo 'simpan_sik.php'; ?>',
															params:{
																hdid:Ext.getCmp('tf_hdid').getValue(),
																typeform:Ext.getCmp('tf_typeform').getValue(),
																noSik:Ext.getCmp('tf_no_sik').getValue(),
																pembuat:Ext.getCmp('tf_pembuat').getValue(),
																pemohon:Ext.getCmp('cb_pemohon').getValue(),
																dept:Ext.getCmp('tf_dept').getValue(),
																plant:Ext.getCmp('cb_plant').getValue(),
																manager:Ext.getCmp('cb_manager').getValue(),
																spv:Ext.getCmp('cb_spv').getValue(),
																tglFrom:Ext.getCmp('tf_tgl_from').getRawValue(),
																tglTo:Ext.getCmp('tf_tgl_to').getRawValue(),
																jamFrom:Ext.getCmp('tf_jam_from').getValue() + ":" + Ext.getCmp('tf_menit_from').getValue(),
																jamTo:Ext.getCmp('tf_jam_to').getValue() + ":" + Ext.getCmp('tf_menit_to').getValue(),
																keterangan:Ext.getCmp('tf_keterangan').getValue(),
																alamat:Ext.getCmp('tf_alamat').getValue(),
																noTelp:Ext.getCmp('tf_no_telp').getValue(),
																noHP:Ext.getCmp('tf_no_hp').getValue(),
																email:Ext.getCmp('tf_email').getValue(),
																email_man:Ext.getCmp('tf_em').getValue(),
																email_spv:Ext.getCmp('tf_emspv').getValue(),
																status:statusString,
																kategoriForm:kategoriForm,
																ijinKhusus:Ext.getCmp('cb_ijin').getValue(),
															},
															method:'POST',
															success:function(response){
																Ext.getCmp('btn_simpan').setDisabled(false);
																var json=Ext.decode(response.responseText);
																var jsonresults = json.results;
																var jsonsplit = jsonresults.split('|');
																var transId = jsonsplit[0];
																var transNo = jsonsplit[1];
																if (json.rows == "sukses"){
																	//maskgrid.hide();
																	if (Ext.getCmp('tf_typeform').getValue()=='tambah'){
																		alertDialog('Sukses', "Data tersimpan dengan no : " + transNo + ".");
																	} else {
																		alertDialog('Sukses', "Data dengan no : " + transNo + " telah tersimpan.");
																	}
																	Ext.Ajax.request({
																		url:'<?PHP echo PATHAPP ?>/transaksi/sik/autoemailSIK.php',
																		method:'POST',
																		params:{
																			hdid:transId,
																		},
																		success:function(response){
																		},
																		failure:function(error){
																			alertDialog('Warning','Save failed.');
																		}
																	});
																	Ext.clearForm();
																	Ext.getCmp('cb_ijin').setDisabled(true);
																	Ext.getCmp('rb_cuti').setValue(true);
																	Ext.getCmp('rb_sakit').setValue(false);
																	Ext.getCmp('rb_ijin').setValue(false);
																	Ext.getCmp('rb_terlambat').setValue(false);
																	var currentTime = new Date();
																	currentTime.setDate(currentTime.getDate() + 7);
																	Ext.getCmp('tf_tgl_from').setValue(currentTime);
																	Ext.getCmp('tf_tgl_to').setValue(currentTime);
																	store_upload.removeAll();
																} else {
																	alertDialog('Kesalahan', "Data gagal disimpan. ");
																} 
															},
															failure:function(error){
																alertDialog('Kesalahan','Data gagal disimpan');
															}
														});
													} else {
														alertDialog('Kesalahan','Attachment belum diisi.');
													}
												} else {
													if (Ext.getCmp('tf_no_hp').getValue()!=''){
														if(xSakit == "sukses"){
															var statusString = 0;
															if (Ext.getCmp('cb_status').getValue()) {
																statusString = 1;
															} else {
																statusString = 0;
															}
															Ext.getCmp('btn_simpan').setDisabled(true);
															Ext.Ajax.request({
																url:'<?php echo 'simpan_sik.php'; ?>',
																params:{
																	hdid:Ext.getCmp('tf_hdid').getValue(),
																	typeform:Ext.getCmp('tf_typeform').getValue(),
																	noSik:Ext.getCmp('tf_no_sik').getValue(),
																	pembuat:Ext.getCmp('tf_pembuat').getValue(),
																	pemohon:Ext.getCmp('cb_pemohon').getValue(),
																	dept:Ext.getCmp('tf_dept').getValue(),
																	plant:Ext.getCmp('cb_plant').getValue(),
																	manager:Ext.getCmp('cb_manager').getValue(),
																	spv:Ext.getCmp('cb_spv').getValue(),
																	tglFrom:Ext.getCmp('tf_tgl_from').getRawValue(),
																	tglTo:Ext.getCmp('tf_tgl_to').getRawValue(),
																	jamFrom:Ext.getCmp('tf_jam_from').getValue() + ":" + Ext.getCmp('tf_menit_from').getValue(),
																	jamTo:Ext.getCmp('tf_jam_to').getValue() + ":" + Ext.getCmp('tf_menit_to').getValue(),
																	keterangan:Ext.getCmp('tf_keterangan').getValue(),
																	alamat:Ext.getCmp('tf_alamat').getValue(),
																	noTelp:Ext.getCmp('tf_no_telp').getValue(),
																	noHP:Ext.getCmp('tf_no_hp').getValue(),
																	email:Ext.getCmp('tf_email').getValue(),
																	email_man:Ext.getCmp('tf_em').getValue(),
																	email_spv:Ext.getCmp('tf_emspv').getValue(),
																	status:statusString,
																	kategoriForm:kategoriForm,
																	ijinKhusus:Ext.getCmp('cb_ijin').getValue(),
																},
																method:'POST',
																success:function(response){
																	Ext.getCmp('btn_simpan').setDisabled(false);
																	var json=Ext.decode(response.responseText);
																	var jsonresults = json.results;
																	var jsonsplit = jsonresults.split('|');
																	var transId = jsonsplit[0];
																	var transNo = jsonsplit[1];
																	if (json.rows == "sukses"){
																		if (Ext.getCmp('tf_typeform').getValue()=='tambah'){
																			alertDialog('Sukses', "Data tersimpan dengan no : " + transNo + ".");
																		} else {
																			alertDialog('Sukses', "Data dengan no : " + transNo + " telah tersimpan.");
																		}
																		Ext.Ajax.request({
																			url:'<?PHP echo PATHAPP ?>/transaksi/sik/autoemailSIK.php',
																			method:'POST',
																			params:{
																				hdid:transId,
																			},
																			success:function(response){
																			},
																			failure:function(error){
																				alertDialog('Warning','Save failed.');
																			}
																		});
																		Ext.clearForm();
																		Ext.getCmp('cb_ijin').setDisabled(true);
																		Ext.getCmp('rb_cuti').setValue(true);
																		Ext.getCmp('rb_sakit').setValue(false);
																		Ext.getCmp('rb_ijin').setValue(false);
																		Ext.getCmp('rb_terlambat').setValue(false);
																		var currentTime = new Date();
																		currentTime.setDate(currentTime.getDate() + 7);
																		Ext.getCmp('tf_tgl_from').setValue(currentTime);
																		Ext.getCmp('tf_tgl_to').setValue(currentTime);
																		store_upload.removeAll();
																	} else {
																		alertDialog('Kesalahan', "Data gagal disimpan. ");
																	} 
																},
																failure:function(error){
																	alertDialog('Kesalahan','Data gagal disimpan');
																}
															});
														} else {
															alertDialog('Kesalahan','Attachment belum diisi.');
														}
													} else {
														alertDialog('Kesalahan','No HP belum diisi.');
													}
												}
											} else {
												alertDialog('Kesalahan','Keterangan belum diisi.');
											}
										} else {
											alertDialog('Kesalahan','Jam belum diisi.');
										}
									} else {
										alertDialog('Kesalahan','Tanggal akhir tidak boleh lebih kecil dari tanggal awal.');
									}
								} else {
									alertDialog('Kesalahan','SPV atau Manager belum dipilih.');
								}
							} else {
								alertDialog('Kesalahan','Plant belum dipilih.');
							}
						} else {
							alertDialog('Kesalahan','Pemohon belum dipilih.');
						}
					}
				},{
					xtype:'label',
					html:'&nbsp',
				},{
					xtype:'button',
					text:'Clear',
					width:50,
					handler:function(){
						Ext.clearForm();
						Ext.getCmp('cb_ijin').setDisabled(true);
						Ext.getCmp('rb_cuti').setValue(true);
						Ext.getCmp('rb_sakit').setValue(false);
						Ext.getCmp('rb_ijin').setValue(false);
						Ext.getCmp('rb_terlambat').setValue(false);
						var currentTime = new Date();
						currentTime.setDate(currentTime.getDate() + 7);
						Ext.getCmp('tf_tgl_from').setValue(currentTime);
						Ext.getCmp('tf_tgl_to').setValue(currentTime);
						Ext.getCmp('tf_sisa_cuti').setValue(0);
						Ext.Ajax.request({
							url:'<?PHP echo PATHAPP ?>/transaksi/sik/delete_upload.php',
							method:'POST',
							success:function(response){
							},
							failure:function(error){
								alertDialog('Warning','Save failed.');
							}
						});
						store_upload.removeAll();
						//var jamFrom = Ext.getCmp('tf_jam_from').getValue() + ":" + Ext.getCmp('tf_menit_from').getValue();
						//console.log(Ext.getCmp('cb_status').getValue());
						
					}
				},{
					xtype:'label',
					html:'&nbsp',
				},{
					xtype:'button',
					text:'Kirim Email',
					width:100,
					id:'btn_kirim',
					disabled:true,
					handler:function(){
						Ext.Ajax.request({
							url:'<?PHP echo PATHAPP ?>/transaksi/sik/autoemailSIK.php',
							method:'POST',
							params:{
								hdid:Ext.getCmp('tf_hdid').getValue(),
							},
							success:function(response){
								alertDialog('Sukses', "Email telah dikirim.");
							},
							failure:function(error){
								alertDialog('Warning','Save failed.');
							}
						});
					}
				}]
			}],
		});	
   contentPanel.render('page');   
	Ext.Ajax.request({
		url:'<?PHP echo PATHAPP ?>/transaksi/sik/delete_upload.php',
		method:'POST',
		success:function(response){
		},
		failure:function(error){
			alertDialog('Warning','Save failed.');
		}
	});
	store_upload.removeAll();
   Ext.getCmp('cb_ijin').setDisabled(true);
   /* Ext.getCmp('tf_tgl_from').setMinValue(Ext.Date.add(new Date(), Ext.Date.DAY, 7));
   Ext.getCmp('tf_tgl_to').setMinValue(Ext.Date.add(new Date(), Ext.Date.DAY, 7));*/
   Ext.getCmp('tf_tgl_from').setValue(currentTime);
   Ext.getCmp('tf_tgl_to').setValue(currentTime); 
   Ext.getCmp('tf_sisa_cuti').setValue(0);
   Ext.getCmp('tf_jam_from').setValue(8);
   Ext.getCmp('tf_jam_to').setValue(16);
   //comboManager.
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
    			<td width="121" rowspan="2"><img src= <?PHP echo PATHAPP . "/images/header.jpg" ?> alt="" /></td>
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