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
	// echo 'io_id: ' . $io_id . ' - io_name: ' . $io_name . ' - org_id: ' . $org_id . ' - org_name: ' . $org_name; 
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
	var currentTimeNow = new Date();
	var month = currentTime.getMonth() + 1;
	console.log(month.length);
	if (month.toString().length == 1){
		month = "0"+month;
	}
	var day = currentTime.getDate();
	var year = currentTime.getFullYear();
	var tanggalskr = year + "-" + month + "-" + day;
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
	Number.prototype.formatMoney = function(c, d, t){
	var n = this, 
		c = isNaN(c = Math.abs(c)) ? 2 : c, 
		d = d == undefined ? "." : d, 
		t = t == undefined ? "," : t, 
		s = n < 0 ? "-" : "", 
		i = String(parseInt(n = Math.abs(Number(n) || 0).toFixed(c))), 
		j = (j = i.length) > 3 ? j % 3 : 0;
	   return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
	 };
	function formatDate(value){
        return value ? Ext.Date.dateFormat(value, 'Y-m-d') : '';
        //return value;
    }
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
					url			: 'cekupload.php',
					method		: 'POST',
					waitTitle	: 'Connecting',
					waitMsg		: 'Sending data...',
					params:{
						file	:Ext.getCmp('arsipfile').getValue(), 
					},
					success: function (response) {
						var json=Ext.decode(response.responseText);
						if(json.jumlah==1){
							Ext.MessageBox.alert('Failed', 'Anda tidak diperbolehkan meng-upload file jenis TXT, PHP, atau EXE.');
						}
						else{
							frmfile1.getForm().submit({
								url: '<?PHP echo PATHAPP ?>/transaksi/potonganacckasir/uploadattachment.php', 
								method:'POST', 
								waitTitle:'Connecting', 
								waitMsg:'Sending data...',
								
								success:function(fp, o){ 											
									var sembarang=Ext.decode(o.response.responseText);		
									//console.log(sembarang);
									if(sembarang.results=='sukses'){
										store_upload.load();
										upload_panel.hide();
									} else if(sembarang.results=='gagal2'){
										Ext.MessageBox.alert('Peringatan', 'File tidak boleh sama.');
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
		width:275,
		columns:[{
			dataIndex:'HD_ID',
			header:'id',
			width:100,
			hidden:true
		},{
			dataIndex:'DATA_ATTACHMENT',
			header:'Nama File',
			width:275,
			
		}]
	});
	
	var store_upload_hrd=new Ext.data.JsonStore({
		id:'store_upload_hrd_id',
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
	
	var grid_upload_hrd=Ext.create('Ext.grid.Panel',{
		id:'grid_upload_hrd_id',
		region:'center',
		store:store_upload_hrd,
        columnLines: true,
		autoScroll:true,
		width:275,
		columns:[{
			dataIndex:'HD_ID',
			header:'id',
			width:100,
			hidden:true
		},{
			dataIndex:'DATA_FILE',
			header:'Nama File',
			width:275,
			
		}]
	});
	
	var comboPemohon = Ext.create('Ext.data.Store', {
		fields: [{
			name:'DATA_VALUE'
		},{
			name:'DATA_NAME'
		}],
		proxy:{
			type:'ajax',
			url:'<?PHP echo PATHAPP ?>/combobox/combobox_userall_id.php', 
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
	
	var comboJenis = Ext.create('Ext.data.Store', {
		fields: ['DATA_NAME', 'DATA_VALUE'],
		data : [
			{"DATA_NAME":"Cash", "DATA_VALUE":"CASH"},
			{"DATA_NAME":"Transfer", "DATA_VALUE":"TRANSFER"},
		]
	});

	var comboRekBank = Ext.create('Ext.data.Store', {
		fields: ['DATA_NAME', 'DATA_VALUE'],
		data : [
			{"DATA_NAME":"Cash", "DATA_VALUE":"CASH"},
			{"DATA_NAME":"Transfer", "DATA_VALUE":"TRANSFER"},
		]
	});
	
	Ext.clearForm=function(){
		Ext.getCmp('tf_hdid').setValue('')
		Ext.getCmp('tf_typeform').setValue('tambah')
		Ext.getCmp('tf_np').setValue('')
		Ext.getCmp('cb_user').setValue('')
		Ext.getCmp('cb_user_ID').setValue('')
		Ext.getCmp('tf_perusahaan').setValue('')
		Ext.getCmp('tf_dept').setValue('')
		Ext.getCmp('tf_posisi').setValue('')
		Ext.getCmp('tf_plant').setValue('')
		Ext.getCmp('tf_pinjaman').setValue('')
		Ext.getCmp('tf_tp').setValue('')
		
		Ext.getCmp('tf_nominal').setValue(0)
		Ext.getCmp('tf_nominal_tf').setValue(0)
		
		Ext.getCmp('cb_tipe').setValue('')
		Ext.getCmp('tf_no_rek').setValue('')
		
		// Ext.getCmp('tf_nr').setValue('')
		// Ext.getCmp('tf_bank').setValue('')
		// Ext.getCmp('tf_an').setValue('')
		
		Ext.getCmp('tf_startpot').setValue('')
		Ext.getCmp('cb_rek_transfer').setValue('')
		Ext.getCmp('cb_rek_transfer_nama').setValue('')
		Ext.getCmp('cb_rek_transfer_nomor').setValue('')
		Ext.getCmp('cb_rek_transfer_id').setValue('')
		
		Ext.getCmp('btn_validasi').setDisabled( true );
		Ext.getCmp('btn_browse').setDisabled( true );
		Ext.getCmp('btn_delete').setDisabled( true );
		
		// Ext.getCmp('btn_pilih').setDisabled(true)
		// Ext.getCmp('tf_tp').setDisabled(true)
		// Ext.getCmp('cb_tipe').setValue('CASH')
		
		Ext.Ajax.request({
			url:'delete_upload.php',
			method:'POST',
			success:function(response){
			},
			failure:function(error){
				alertDialog('Warning','Save failed.');
			}
		});
		store_upload.removeAll();
	};

	var store_detil=new Ext.data.JsonStore({
		id:'store_cari_id',
		pageSize: 100,
		model: 'Plant',
		fields:[{
			name:'HD_ID'
		},{
			name:'DATA_JENIS'
		},{
			name:'DATA_TGL'
		},{
			name:'DATA_JML_P'
		},{
			name:'DATA_JML_C'
		},{
			name:'DATA_CICILAN'
		},{
			name:'DATA_OUTSTANDING_RP'
		},{
			name:'DATA_OUTSTANDING_BLN'
		},{
			name:'DATA_OUTSTANDING_ASLI'
		},{
			name:'DATA_CICILAN_BLN'
		}],
		proxy:{
			type:'ajax',
			url:'isi_grid_potongan.php', 
			reader: {
				root: 'rows',  
			},
		}
	});
	
	var store_rek = new Ext.data.JsonStore({
		id:'store_cari_rek',
		pageSize: 100,
		model: 'Plant',
		fields:[{
			name:'BANK_ACCOUNT_NAME'
		},{
			name:'BANK_NAME'
		},{
			name:'BANK_ACCOUNT_NUM'
		},{
			name:'BANK_ACCOUNT_ID'
		}],
		proxy:{
			type:'ajax',
			url:'isi_grid_rek.php', 
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
	
	var cellEditing = Ext.create('Ext.grid.plugin.CellEditing', {
        clicksToEdit: 1
    });
	var store_popuptransaksi=new Ext.data.JsonStore({
		id:'store_popuptransaksi_id',
		pageSize: 100,
		fields:[{
			name:'HD_ID'
		},{
			name:'DATA_PINJAMAN'
		},{
			name:'DATA_PERSON_ID'
		},{
			name:'DATA_PERSON'
		},{
			name:'DATA_TGL'
		},{
			name:'DATA_NOMINAL'
		},{
			name:'DATA_NOMINAL_TF'
		},{
			name:'DATA_JML_C'
		},{
			name:'DATA_TUJUAN'
		},{
			name:'DATA_TGL_BUAT'
		},{
			name:'DATA_STATUS'
		},{
			name:'DATA_TIPE'
		},{
			name:'DATA_START_POTONGAN'
		},{
			name:'DATA_TIPE_PENCAIRAN'
		},{
			name:'DATA_NOMOR_REKENING'
		},{
			name:'DATA_STATUS_DOKUMEN'
		},{
			name:'DATA_REK_BANK_TRANSFER'
		},{
			name:'DATA_NAME_BANK_TRANSFER'
		},{
			name:'DATA_BANK_ACCOUNT_ID'
		},{
			name:'DATA_BANK_NAME'
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
			dataIndex:'DATA_PERSON_ID',
			header:'Pembuat',
			width:100,
			hidden:true
		},{
			dataIndex:'DATA_TGL_BUAT',
			header:'Pembuat',
			width:100,
			hidden:true
		},{
			dataIndex:'DATA_JML_C',
			header:'Pembuat',
			width:100,
			hidden:true
		},{
			dataIndex:'DATA_PINJAMAN',
			header:'No Pinjaman',
			width:180,
			//hidden:true
		},{
			dataIndex:'DATA_PERSON',
			header:'Nama Peminjam',
			width:180,
			// hidden:true
		},{
			dataIndex:'DATA_TIPE',
			header:'Tipe Pinjaman',
			width:150,
			// hidden:true
		},{
			dataIndex:'DATA_TGL',
			header:'Tgl Pinjam',
			width:100,
			//hidden:true
		},{
			dataIndex:'DATA_NOMINAL',
			header:'Nominal',
			width:150,
			//hidden:true
		},{
			dataIndex:'DATA_NOMINAL_TF',
			header:'Nominal',
			width:150,
			hidden:true
		},{
			dataIndex:'DATA_TUJUAN',
			header:'Tujuan Pinjaman',
			width:200,
			//hidden:true
		},{
			dataIndex:'DATA_START_POTONGAN',
			header:'Start Potongan',
			width:150,
			//hidden:true
		},{
			dataIndex:'DATA_STATUS',
			header:'Status',
			width:50,
			//hidden:true
		},{
			dataIndex:'DATA_TIPE_PENCAIRAN',
			header:'Tipe Pencairan',
			width:50,
			hidden:true
		},{
			dataIndex:'DATA_NOMOR_REKENING',
			header:'No Rekening',
			width:50,
			hidden:true
		},{
			dataIndex:'DATA_STATUS_DOKUMEN',
			header:'Status Dokumen',
			width:50,
			hidden:true
		},{
			dataIndex:'DATA_REK_BANK_TRANSFER',
			header:'Status Dokumen',
			width:50,
			hidden:true
		},{
			dataIndex:'DATA_NAME_BANK_TRANSFER',
			header:'Status Dokumen',
			width:50,
			hidden:true
		},{
			dataIndex:'DATA_BANK_ACCOUNT_ID',
			header:'Status Dokumen',
			width:50,
			hidden:true
		},{
			dataIndex:'DATA_BANK_NAME',
			header:'Status Dokumen',
			width:50,
			hidden:true
		}],
		listeners: {
			dblclick: {
				element: 'body', //bind to the underlying body property on the panel
				fn: function(){
					
					Ext.Ajax.request({
						url:'delete_upload.php',
						method:'POST',
						success:function(response){
						},
						failure:function(error){
							alertDialog('Warning','Save failed.');
						}
					});
					store_upload.removeAll();
					
					// Ext.getCmp('cb_tipe').setValue('CASH')
					
					// Ext.getCmp('btn_pilih').setDisabled(true);

					
					var status_dok = grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_STATUS_DOKUMEN');
					
					if ( status_dok == 'Validate' ) {
						
						Ext.getCmp('btn_validasi').setDisabled( true );
						Ext.getCmp('btn_browse').setDisabled( true );
						Ext.getCmp('btn_delete').setDisabled( true );
						
						Ext.getCmp('cb_rek_transfer').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_REK_BANK_TRANSFER'));
						Ext.getCmp('cb_rek_transfer_nama').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_NAME_BANK_TRANSFER'));
						Ext.getCmp('cb_rek_transfer_nomor').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_BANK_NAME'));
						Ext.getCmp('cb_rek_transfer_id').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_BANK_ACCOUNT_ID'));
					
					} else {

						Ext.getCmp('cb_rek_transfer').setValue('')
						Ext.getCmp('cb_rek_transfer_nama').setValue('')
						Ext.getCmp('cb_rek_transfer_nomor').setValue('')
						Ext.getCmp('cb_rek_transfer_id').setValue('')
					
						Ext.getCmp('btn_validasi').setDisabled( false );
						Ext.getCmp('btn_browse').setDisabled( false );
						Ext.getCmp('btn_delete').setDisabled( false );
						
					}					
					
					var hdid = grid_popuptransaksi.getSelectionModel().getSelection()[0].get('HD_ID');
					
					Ext.getCmp('tf_hdid').setValue(hdid);
					
					Ext.getCmp('tf_np').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_PINJAMAN'));
					comboPemohon.load();
					
					Ext.getCmp('cb_user').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_PERSON'));
					Ext.getCmp('cb_user_ID').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_PERSON_ID'));
					Ext.getCmp('tf_tp').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_TGL'));
					Ext.getCmp('tf_pinjaman').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_NOMINAL'));
					Ext.getCmp('tf_nominal').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_NOMINAL'));
					Ext.getCmp('tf_nominal').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_NOMINAL'));
					Ext.getCmp('tf_nominal_tf').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_NOMINAL_TF'));
					
					Ext.getCmp('tf_startpot').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_START_POTONGAN'));
					
					Ext.getCmp('cb_tipe').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_TIPE_PENCAIRAN'));
					Ext.getCmp('tf_no_rek').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_NOMOR_REKENING'));
					
					
					Ext.Ajax.request({
					url:'<?php echo 'isi_nama_header.php'; ?>',
							timeout: 500000,
							params:{
								nama_pem:Ext.getCmp('cb_user_ID').getValue(),
							},
							success:function(response){
								var json=Ext.decode(response.responseText);
								var deskripsiasli = json.results;
								var deskripsisplit = deskripsiasli.split('|');
								var perusahaan = deskripsisplit[0];
								var dept = deskripsisplit[1];
								var jabatan = deskripsisplit[2];
								var plant = deskripsisplit[3];
								var bank = deskripsisplit[4];
								var noreg = deskripsisplit[5];
								var an = deskripsisplit[6];
								Ext.getCmp('tf_perusahaan').setValue(perusahaan);
								Ext.getCmp('tf_dept').setValue(dept);
								Ext.getCmp('tf_posisi').setValue(jabatan);
								Ext.getCmp('tf_plant').setValue(plant);
								
								// Ext.getCmp('tf_bank').setValue(bank);
								// Ext.getCmp('tf_nr').setValue(noreg);
								// Ext.getCmp('tf_an').setValue(an);
								
							},
						method:'POST',
					});
					
					
					Ext.Ajax.request({
						url:'<?PHP echo PATHAPP ?>/transaksi/potonganacckasir/insert_grid_upload.php',
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
					
					
					store_upload.setProxy({
						type:'ajax',
						timeout: 500000,
						url:'isi_grid_upload.php?hd_id=' + hdid,
						reader: {
							type: 'json',
							root: 'data', 
							totalProperty:'total'   
						}
					});
					
					store_upload.load();
					
					
					PopupTransaksi.hide();
					
				}
			}
		}
	});
	
	var PopupTransaksi=Ext.create('Ext.Window', {
		title: 'Cari No Pinjaman Karyawan',
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
			fieldLabel:'No Pinjaman',
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


	var grid_popuprek=Ext.create('Ext.grid.Panel',{
		id:'grid_popuprek_id',
		region:'center',
		store:store_rek,
        columnLines: true,
        columnLines: true,
        loadMask: true,
		columns:[{
			xtype:'rownumberer',
			id:'row_id_rek',
			header:'No',
			width:50
		},{
			dataIndex:'BANK_ACCOUNT_NAME',
			header:'Bank Account Name',
			width:140,
			// hidden:true
		},{
			dataIndex:'BANK_NAME',
			header:'Bank Name',
			width:100,
			// hidden:true
		},{
			dataIndex:'BANK_ACCOUNT_NUM',
			header:'Bank Account Number',
			width:140,
			// hidden:true
		},{
			dataIndex:'BANK_ACCOUNT_ID',
			header:'Bank Account ID',
			width:100,
			hidden:true
		}]
		,
		listeners: {
			dblclick: {
				element: 'body', //bind to the underlying body property on the panel
				fn: function(){
					
					Ext.getCmp('cb_rek_transfer').setValue(grid_popuprek.getSelectionModel().getSelection()[0].get('BANK_ACCOUNT_NAME'));
					Ext.getCmp('cb_rek_transfer_nama').setValue(grid_popuprek.getSelectionModel().getSelection()[0].get('BANK_NAME'));
					Ext.getCmp('cb_rek_transfer_nomor').setValue(grid_popuprek.getSelectionModel().getSelection()[0].get('BANK_ACCOUNT_NUM'));
					Ext.getCmp('cb_rek_transfer_id').setValue(grid_popuprek.getSelectionModel().getSelection()[0].get('BANK_ACCOUNT_ID'));
					
					PopupRek.hide();
					
				}
			}
		}
		
		
	});
	
	var PopupRek=Ext.create('Ext.Window', {
		title: 'Cari Bank Account Transfer',
		width: 700,
		height: 350,							
		layout: 'fit',
		closeAction:'hide',
		tbar:[,{
			xtype:'label',
			html:'&nbsp',
		}
		/*
		,{
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
		}
		*/
		, {
			xtype:'textfield',
			fieldLabel:'No Rek Bank',
			maxLengthText:5,
			id:'tf_filter_rek',
			labelWidth:75,
			listeners: {
				 change: function(field,newValue,oldValue){
						field.setValue(newValue.toUpperCase());
				}
			}
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
				
				// if(Ext.getCmp('tf_filter_from_pop').getValue()<=Ext.getCmp('tf_filter_to_pop').getValue()){
					
					maskgrid = new Ext.LoadMask(Ext.getCmp('grid_popuprek_id'), {msg: "Memuat . . ."});
					
					maskgrid.show();
					store_rek.load({
						params:{
							
							tffilter:Ext.getCmp('tf_filter_rek').getValue(),
							
							/*
							tffilter:Ext.getCmp('tf_filter_pop').getValue(),
							tglfrom:Ext.getCmp('tf_filter_from_pop').getRawValue(),
							tglto:Ext.getCmp('tf_filter_to_pop').getRawValue(),
							cb1:Ext.getCmp('cb_filter1_pop').getValue(),
							cb2:Ext.getCmp('cb_filter2_pop').getValue(),
							*/
						}
					});
					
				// }
				// else {
				// 	alertDialog('Kesalahan','Tannggal from lebih besar dari tanggal to.');
				// }
				
			}
		}],
		items: grid_popuprek
	});


	
	//var sm = Ext.create('Ext.selection.CheckboxModel');
    var grid_detil = Ext.create('Ext.grid.Panel', {
		id:'grid_detil_id',
		region:'center',
		store:store_detil,
        columnLines: true,
		height:150,
		width:830,
		autoScroll:true,
        columns: [{
			dataIndex:'HD_ID',
			header:'No.',
			hidden:true,
			width:50,
		},{
			dataIndex:'OUTSTANDING_ASLI',
			header:'No.',
			hidden:true,
			width:50,
		},{
			dataIndex:'DATA_JENIS',
			header:'Jenis Pinjaman',
			width:85,
		},{
			dataIndex:'DATA_PINJAMAN',
			header:'Tgl Pinjam',
			//hidden:true,
			width:90,
		},{
			dataIndex:'DATA_JML_P',
			header:'Jml Pinjaman',
			//hidden:true,
			width:85,
		},{
			dataIndex:'DATA_JML_C',
			header:'Jml Cicilan',
			width:85,
		},{
			dataIndex:'DATA_CICILAN',
			header:'Cicilan per Periode',
			width:110,
		},{
			dataIndex:'DATA_OUTSTANDING_RP',
			header:'Outstanding (Rp)',
			width:150,
		},{
			dataIndex:'DATA_OUTSTANDING_BLN',
			header:'Outstanding (Bln)',
			width:100,
		},{
			dataIndex:'DATA_CICILAN_BLN',
			header:'Cicilan Terakhir (Bln)',
			width:120,
		}],
        selModel: {
            selType: 'cellmodel'
        },
        plugins: [cellEditing],
    });
	var kategoriForm = "All";
	var contentPanel = Ext.create('Ext.panel.Panel',{
			//title:'Form Input Tanda Terima',
			//region:'center',
			bodyStyle: 'spacing: 10px;border:none',
			items:[{
				xtype:'label',
				html:'<div align="center"><font size="5"><b>Validasi Pinjaman Karyawan (KASIR)</b></font></div>',
			},{
				xtype:'label',
				html:'&nbsp',
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
					columnWidth:.53,
					border:false,
					layout: 'anchor',
					defaultType: 'textfield',
					items:[{
						name: 'tf_np',
						fieldLabel: 'No Pinjaman',
						width:450,
						labelWidth:120,
						id:'tf_np',
						editable: false,
						readOnly:true,
						fieldStyle:'background:#B8B8B8 ;font-weight:bold;'
					}]
				},{
					columnWidth:.09,
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
							PopupTransaksi.show();
							

							
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
					columnWidth:.6,
					border:false,
					layout: 'anchor',
					defaultType: 'combobox',
					items:[{		
						xtype:'textfield',
						fieldLabel:'Nama Karyawan',
						width:450,
						labelWidth:120,
						id:'cb_user',
						readOnly:true,
						fieldStyle:'background:#B8B8B8 ;font-weight:bold;'
					}]
				}]	
			},{
				layout:'column',
				hidden:true,
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
					columnWidth:.6,
					border:false,
					layout: 'anchor',
					defaultType: 'combobox',
					items:[{		
						xtype:'textfield',
						fieldLabel:'Nama Karyawan',
						width:450,
						labelWidth:120,
						id:'cb_user_ID',
						readOnly:true,
						fieldStyle:'background:#B8B8B8 ;font-weight:bold;'
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
						xtype:'textfield',
						fieldLabel:'Perusahaan',
						width:450,
						labelWidth:120,
						id:'tf_perusahaan',
						readOnly:true,
						fieldStyle:'background:#B8B8B8 ;font-weight:bold;'
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
						xtype:'textfield',
						fieldLabel:'Departemen',
						width:450,
						labelWidth:120,
						id:'tf_dept',
						readOnly:true,
						fieldStyle:'background:#B8B8B8 ;font-weight:bold;'
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
						xtype:'textfield',
						fieldLabel:'Posisi',
						width:450,
						labelWidth:120,
						id:'tf_posisi',
						readOnly:true,
						fieldStyle:'background:#B8B8B8 ;font-weight:bold;'
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
						xtype:'textfield',
						fieldLabel:'Plant',
						width:450,
						labelWidth:120,
						id:'tf_plant',
						readOnly:true,
						fieldStyle:'background:#B8B8B8 ;font-weight:bold;'
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
						xtype:'textfield',
						fieldLabel:'Nominal Pinjaman',
						width:450,
						labelWidth:120,
						id:'tf_pinjaman',
						readOnly:true,
						fieldStyle:'background:#B8B8B8 ;font-weight:bold;'
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
					defaultType: 'datefield',
					items:[{		
						xtype:'textfield',
						fieldLabel:'Start Potongan',
						width:450,
						labelWidth:120,
						id:'tf_startpot',
						readOnly:true,
						fieldStyle:'background:#B8B8B8 ;font-weight:bold;'
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
						// html:'<div style="color:#FF0000">*</div>',
						html:'',
					}]
				},{
					columnWidth:.9,
					border:false,
					layout: 'anchor',
					defaultType: 'combobox',
					items:[{		
						xtype:'combobox',
						fieldLabel:'Tipe Pencairan',
						width:250,
						labelWidth:120,
						id:'cb_tipe',
						readOnly: true,
						editable: false,
						fieldStyle:'background:#B8B8B8 ;font-weight:bold;'
						/*
						store: comboJenis,
						value:'CASH',
						displayField: 'DATA_NAME',
						valueField: 'DATA_VALUE',
						listeners: {
							
							select:function(f,r,i) {
								
								var val=r[0].data.DATA_VALUE;
								
								// console.log(nama_ijin);
								// Ext.getCmp('tf_tp').setValue('');
								
								if( val == 'TRANSFER' ) {
									
									Ext.getCmp('btn_pilih').setDisabled(false);
									
								} else {
									
									Ext.getCmp('btn_pilih').setDisabled(true);
									
									Ext.getCmp('cb_rek_transfer').setValue('')
									Ext.getCmp('cb_rek_transfer_nama').setValue('')
									Ext.getCmp('cb_rek_transfer_nomor').setValue('')
									Ext.getCmp('cb_rek_transfer_id').setValue('')
									
								}
								
							},
						}
						*/
					}]	
				}]
			}, {
				layout:'column',
				border:false,
				items:[,{
					columnWidth:.02,
					border:false,
					layout: 'anchor',
					defaultType: 'label',
					items:[{
						xtype:'label',
						// id:'lbl_no_rek',
						html:'',
					}]
				},{
					columnWidth:.6,
					border:false,
					layout: 'anchor',
					defaultType: 'combobox',
					items:[{		
						xtype:'textfield',
						fieldLabel:'No Rekening',
						width:450,
						labelWidth:120,
						id:'tf_no_rek',
						fieldStyle:'background:#B8B8B8 ;font-weight:bold;'
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
					columnWidth:.36,
					border:false,
					layout: 'anchor',
					defaultType: 'combobox',
					items:[{		
						xtype:'textfield',
						fieldLabel:'Rek Bank Transfer',
						width:300,
						labelWidth:120,
						id:'cb_rek_transfer',
						editable: false,
						store: comboRekBank,
						displayField: 'DATA_NAME',
						valueField: 'DATA_VALUE',
						readOnly:true,
						fieldStyle:'background:#B8B8B8 ;font-weight:bold;'
					}]
					},{
						columnWidth:.2,
						border:false,
						layout: 'anchor',
						defaultType: 'button',
						items:[{
							name: 'cariRek',
							text: 'Pilih',
							width:75,
							id:'btn_pilih',
							handler:function(){
								/*
								Ext.getCmp('tf_filter_pop').setValue('');
								Ext.getCmp('tf_filter_from_pop').setValue(currentTimeNow);
								Ext.getCmp('tf_filter_to_pop').setValue(currentTimeNow);
								Ext.getCmp('cb_filter1_pop').setValue('true');
								Ext.getCmp('cb_filter2_pop').setValue('true');
								*/
								store_rek.removeAll();
								PopupRek.show();
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
					columnWidth:.36,
					border:false,
					layout: 'anchor',
					defaultType: 'combobox',
					items:[{		
						xtype:'textfield',
						fieldLabel:'Nama Bank',
						width:300,
						labelWidth:120,
						id:'cb_rek_transfer_nama',
						editable: false,
						displayField: 'DATA_NAME',
						valueField: 'DATA_VALUE',
						readOnly:true,
						fieldStyle:'background:#B8B8B8 ;font-weight:bold;'
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
					columnWidth:.36,
					border:false,
					layout: 'anchor',
					defaultType: 'combobox',
					items:[{		
						xtype:'textfield',
						fieldLabel:'Nomor Rek Bank',
						width:300,
						labelWidth:120,
						id:'cb_rek_transfer_nomor',
						editable: false,
						displayField: 'DATA_NAME',
						valueField: 'DATA_VALUE',
						readOnly:true,
						fieldStyle:'background:#B8B8B8 ;font-weight:bold;'
					}]
					}]
			},{
				layout:'column',
				border:false,
				hidden:true,
				// hidden:false,
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
					columnWidth:.36,
					border:false,
					layout: 'anchor',
					defaultType: 'combobox',
					items:[{		
						xtype:'textfield',
						fieldLabel:'Bank Account ID',
						width:300,
						labelWidth:120,
						id:'cb_rek_transfer_id',
						editable: false,
						displayField: 'DATA_NAME',
						valueField: 'DATA_VALUE',
						readOnly:true,
						fieldStyle:'background:#B8B8B8 ;font-weight:bold;'
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
					columnWidth:.9,
					border:false,
					layout: 'anchor',
					defaultType: 'datefield',
					items:[{
						xtype:'datefield',
						fieldLabel:'Tgl Pencairan',
						width:220,
						labelWidth:120,
						id:'tf_tp',
						format: 'Y-m-d',
						//disabled:true,
						// readOnly:true,
						// fieldStyle:'background:#B8B8B8 ;font-weight:bold;'
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
					columnWidth:.9,
					border:false,
					layout: 'anchor',
					defaultType: 'numberfield',
					items:[{		
						xtype:'textfield',
						fieldLabel:'Nominal',
						width:250,
						labelWidth:120,
						id:'tf_nominal',
						value: 0,
						readOnly: true,
						editable: false,
						minValue: 0,
						maxValue: 1000000000000
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
					columnWidth:.9,
					border:false,
					hidden:true,
					layout: 'anchor',
					defaultType: 'numberfield',
					items:[{		
						xtype:'numberfield',
						fieldLabel:'Nominal',
						width:250,
						labelWidth:120,
						id:'tf_nominal_tf',
						value: 0,
						readOnly: true,
						editable: false,
						minValue: 0,
						maxValue: 1000000000000
					}]
				}]	
			},
			
			/*
			{
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
					columnWidth:.9,
					border:false,
					layout: 'anchor',
					defaultType: 'combobox',
					items:[{		
						xtype:'textfield',
						fieldLabel:'Bank',
						width:450,
						labelWidth:120,
						id:'tf_bank',
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
					columnWidth:.6,
					border:false,
					layout: 'anchor',
					defaultType: 'combobox',
					items:[{		
						xtype:'textfield',
						fieldLabel:'Nomor Rekening',
						width:450,
						labelWidth:120,
						id:'tf_nr',
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
					columnWidth:.6,
					border:false,
					layout: 'anchor',
					defaultType: 'combobox',
					items:[{		
						xtype:'textfield',
						fieldLabel:'Atas Nama',
						width:450,
						labelWidth:120,
						id:'tf_an',
					}]
				}]	
			},
			*/
			
			{
				layout:'column',
				border:false,
				items:[{
					columnWidth:.54,
					border:false,
					layout: 'anchor',
					defaultType: 'combobox',
					items:[{
						xtype: 'fieldset',
						flex: 1,
						title: 'Attachment Bukti Transfer',
						//defaultType: 'radio', // each item will be a radio button
						layout: 'anchor',
						items: [{
							layout:'column',
							border:false,
							items:[
							{
								columnWidth:.03,
								border:false,
								layout: 'anchor',
								defaultType: 'label',
								items:[{
									xtype:'label',
									// html:'<div style="color:#FF0000">*</div>',
									html:'',
								}]
							},
							{
								columnWidth:0.63,
								border:false,
								layout: 'anchor',
								defaultType: 'textfield',
								items:[grid_upload]
							},{
								columnWidth:.14,
								border:false,
								layout: 'anchor',
								defaultType: 'button',
								items:[{
									name: 'cari',
									text: 'Browse',
									id:'btn_browse',
									width:50,
									handler:function(){
										upload_panel.show();
									}
								}]
							},{
								columnWidth:.14,
								border:false,
								layout: 'anchor',
								defaultType: 'button',
								items:[{xtype:'button',
									text:'Delete',
									id:'btn_delete',
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
					text:'Validasi',
					id:'btn_validasi',
					width:100,
					handler:function() { 
						
						Ext.MessageBox.wait('Loading ...');
						
						var vcek = '';
						
						if ( Ext.getCmp('cb_tipe').getValue() == 'TRANSFER' ) 
						{
							
							if (
								Ext.getCmp('cb_rek_transfer').getValue() == ''
								|| Ext.getCmp('cb_rek_transfer_nama').getValue() == ''
								|| Ext.getCmp('cb_rek_transfer_nomor').getValue() == ''
								|| Ext.getCmp('cb_rek_transfer_id').getValue() == ''
								|| Ext.getCmp('cb_rek_transfer').getValue() == null
								|| Ext.getCmp('cb_rek_transfer_nama').getValue() == null
								|| Ext.getCmp('cb_rek_transfer_nomor').getValue() == null
								|| Ext.getCmp('cb_rek_transfer_id').getValue() == null
								) 
							{
								Ext.MessageBox.hide();
								alertDialog('Peringatan', 'Rek Bank Transfer, Nama Bank, dan Nomor Rek Bank wajib diisi untuk Jenis Pencairan Transfer.');
								
							} else {
								
								vcek = 'y';
								
							}
						} else {
							
							vcek = 'y';
							
						}

					
						if ( vcek == 'y' ) 
						{
							
							
							if ( Ext.getCmp('cb_tipe').getValue() == 'TRANSFER' ) 
							{
								
								if (
										Ext.getCmp('tf_nominal_tf').getValue() != 0 
										&& Ext.getCmp('tf_tp').getValue() != '' 
										&& Ext.getCmp('tf_tp').getValue() != null 
										&& Ext.getCmp('tf_no_rek').getValue() != '' 
										&& Ext.getCmp('tf_no_rek').getValue() != null
										
										// && Ext.getCmp('tf_nr').getValue() != '' 
										// && Ext.getCmp('tf_nr').getValue() != null
										// && Ext.getCmp('tf_bank').getValue() != '' 
										// && Ext.getCmp('tf_an').getValue() != ''
										
									) 
								{
								
									// Cek apakah ada perubahan di approver di master pinjaman untuk OU tersebut
									
									Ext.Ajax.request({
										url:'<?php echo 'cek_valid_kasir_id.php'; ?>',
										params:{
											hdid:Ext.getCmp('tf_hdid').getValue(),
										},
										method:'POST',
										success:function(response){
											var json=Ext.decode(response.responseText);
											
											var jsonresults = json.hasil;
											
											if ( jsonresults == "sukses") {														
												
												Ext.Ajax.request({
													url:'<?php echo 'simpan_potongan.php'; ?>',
													params:{
														hdid:Ext.getCmp('tf_hdid').getValue(),
														typeform:'Approved',
														tgltf:Ext.Date.dateFormat(Ext.getCmp('tf_tp').getValue(), 'Y-m-d'),
														nominal:Ext.getCmp('tf_nominal_tf').getValue(),
														
														norek:Ext.getCmp('tf_no_rek').getValue(),
														tipe:Ext.getCmp('cb_tipe').getValue(),
														
														// norek:Ext.getCmp('tf_nr').getValue(),
														// bank:Ext.getCmp('tf_bank').getValue(),
														// an:Ext.getCmp('tf_an').getValue(),
														
														param_cb_rek_transfer:Ext.getCmp('cb_rek_transfer').getValue(),
														param_cb_rek_transfer_nama:Ext.getCmp('cb_rek_transfer_nama').getValue(),
														param_cb_rek_transfer_nomor:Ext.getCmp('cb_rek_transfer_nomor').getValue(),
														param_cb_rek_transfer_id:Ext.getCmp('cb_rek_transfer_id').getValue(),
													},
													method:'POST',
													success:function(response){
														var json=Ext.decode(response.responseText);
														var jsonresults = json.results;
														
														var jsonsplit = jsonresults.split('|');
														var transId = jsonsplit[0];
														var transNo = jsonsplit[1];
														
														if (json.rows == "sukses"){
															
															// alertDialog('Sukses', "Data tersimpan dengan nomor : " + jsonresults + ".");
															
															Ext.MessageBox.hide();
															alertDialog('Sukses', "Data nomor " + transNo + " telah di-validasi.");
															
															
															// Autoemail approval oleh Kasir
															
															Ext.Ajax.request({
																url:'<?PHP echo PATHAPP ?>/transaksi/potonganacckasir/autoemail_potonganacckasir.php',
																method:'POST',
																params:{
																	hdid : transId,
																	param_transNo : transNo,
																	// param_tipe : Ext.getCmp('tf_tipe').getValue(),
																	
																},
																success:function(response){
																},
																failure:function(error){
																	Ext.MessageBox.hide();
																	alertDialog('Warning','Save failed.');
																}
															});
															
															Ext.clearForm();
															
														} else {
															Ext.MessageBox.hide();
															alertDialog('Kesalahan', "Data gagal disimpan. ");
														} 
													},
													failure:function(error){
														Ext.MessageBox.hide();
														alertDialog('Kesalahan','Data gagal disimpan');
													}
												});
												
												
											} else {
												Ext.MessageBox.hide();
												alertDialog('Kesalahan', "Terjadi perubahan pada approver Kasir. Data tidak dapat disimpan. ");
											} 
										},
										failure:function(error){
											Ext.MessageBox.hide();
											alertDialog('Kesalahan','Data gagal disimpan');
										}
									});
									
								} else {
									
									// alertDialog('Peringatan', 'Tgl Pencairan, Nominal, Bank, Nomor Rekening, dan Atas Nama wajib diisi.');
									
									Ext.MessageBox.hide();
									alertDialog( 'Peringatan', 'Nomor Rekening wajib diisi.' );
									
								}
							
							
							
							// Jika tipe Cash
							
							} else {
								
								
								if ( Ext.getCmp('tf_nominal_tf').getValue() != 0 
										&& Ext.getCmp('tf_tp').getValue() != '' 
										&& Ext.getCmp('tf_tp').getValue() != null 
										
									) 
								{
									
									// Cek apakah ada perubahan di approver di master pinjaman untuk OU tersebut
									
									Ext.Ajax.request({
										url:'<?php echo 'cek_valid_kasir_id.php'; ?>',
										params:{
											hdid:Ext.getCmp('tf_hdid').getValue(),
										},
										method:'POST',
										success:function(response){
											var json=Ext.decode(response.responseText);
											
											var jsonresults = json.hasil;
											
											if ( jsonresults == "sukses") {														
												
												Ext.Ajax.request({
													url:'<?php echo 'simpan_potongan.php'; ?>',
													params:{
														hdid:Ext.getCmp('tf_hdid').getValue(),
														typeform:'Approved',
														tgltf:Ext.Date.dateFormat(Ext.getCmp('tf_tp').getValue(), 'Y-m-d'),
														nominal:Ext.getCmp('tf_nominal_tf').getValue(),
														
														norek:Ext.getCmp('tf_no_rek').getValue(),
														tipe:Ext.getCmp('cb_tipe').getValue(),
														
														// norek:Ext.getCmp('tf_nr').getValue(),
														// bank:Ext.getCmp('tf_bank').getValue(),
														// an:Ext.getCmp('tf_an').getValue(),
														
														param_cb_rek_transfer:Ext.getCmp('cb_rek_transfer').getValue(),
														param_cb_rek_transfer_nama:Ext.getCmp('cb_rek_transfer_nama').getValue(),
														param_cb_rek_transfer_nomor:Ext.getCmp('cb_rek_transfer_nomor').getValue(),
														param_cb_rek_transfer_id:Ext.getCmp('cb_rek_transfer_id').getValue(),
													},
													method:'POST',
													success:function(response){
														var json=Ext.decode(response.responseText);
														var jsonresults = json.results;
														
														var jsonsplit = jsonresults.split('|');
														var transId = jsonsplit[0];
														var transNo = jsonsplit[1];
														
														if (json.rows == "sukses"){
															
															// alertDialog('Sukses', "Data tersimpan dengan nomor : " + jsonresults + ".");
															
															Ext.MessageBox.hide();
															alertDialog('Sukses', "Data nomor " + transNo + " telah di-validasi.");
															
															
															// Autoemail approval oleh Kasir
															
															Ext.Ajax.request({
																url:'<?PHP echo PATHAPP ?>/transaksi/potonganacckasir/autoemail_potonganacckasir.php',
																method:'POST',
																params:{
																	hdid : transId,
																	param_transNo : transNo,
																	// param_tipe : Ext.getCmp('tf_tipe').getValue(),
																	
																},
																success:function(response){
																},
																failure:function(error){
																	Ext.MessageBox.hide();
																	alertDialog('Warning','Save failed.');
																}
															});
															
															Ext.clearForm();
															
														} else {
															Ext.MessageBox.hide();
															alertDialog('Kesalahan', "Data gagal disimpan. ");
														} 
													},
													failure:function(error){
														Ext.MessageBox.hide();
														alertDialog('Kesalahan','Data gagal disimpan');
													}
												});
												
												
											} else {
												Ext.MessageBox.hide();
												alertDialog('Kesalahan', "Terjadi perubahan pada approver Kasir. Data tidak dapat disimpan. ");
											} 
										},
										failure:function(error){
											Ext.MessageBox.hide();
											alertDialog('Kesalahan','Data gagal disimpan');
										}
									});
									
								
								} else {
									Ext.MessageBox.hide();
									alertDialog('Peringatan', 'Tgl Pencairan dan Nominal wajib diisi.');
								}
								
								
							}
							
						} else {
							
							// alertDialog('Peringatan', 'Rek Bank Transfer, Nama Bank, Nomor Rek Bank, Tgl Pencairan, Nominal, Bank, Nomor Rekening, dan Atas Nama wajib diisi.');			
							
							Ext.MessageBox.hide();
							alertDialog( 'Peringatan', 'Rek Bank Transfer, Nama Bank, Nomor Rek Bank, Tgl Pencairan, Nominal, dan No Rekening wajib diisi.');			
							
						}
					}
						
				
				},{
					xtype:'label',
					html:'&nbsp',
				},{
					xtype:'button',
					text:'Clear',
					width:100,
					handler:function(){
						Ext.clearForm();
					}
				},{
					xtype:'button',
					text:'Print',
					id:'btn_print',
					width:100,
					handler:function(){
						
						var transId = Ext.getCmp('tf_hdid').getValue();
						
						window.open("isi_pdf_potongan.php?hdid=" + transId + "");
						
					}
				}]
			}],
		});	
   contentPanel.render('page');
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