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
								url: '<?PHP echo PATHAPP ?>/transaksi/potonganaccdir/uploadattachment.php', 
								method:'POST', 
								waitTitle:'Connecting', 
								waitMsg:'Sending data...',
								
								success:function(fp, o){ 											
									var sembarang=Ext.decode(o.response.responseText);		
									//console.log(sembarang);
									if(sembarang.results=='sukses'){
										store_upload_dir.load();
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
			name:'DATA_FILE'
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
	
	
	var store_upload_view=new Ext.data.JsonStore({
		id:'store_upload_view_id',
		pageSize: 50,
		fields:[{
			name:'HD_ID'
		},{
			name:'DATA_FILE'
		},{
			name:'DATA_ATTACHMENT'
		}],
		 proxy:{
			type:'ajax',
			url:'isi_grid_upload_view.php', 
			reader: {
				type: 'json',
				root: 'data',
				totalProperty:'total'        
			},
		}
	});
	
	var grid_upload_view=Ext.create('Ext.grid.Panel',{
		id:'grid_upload_view_id',
		region:'center',
		store:store_upload_view,
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
	
	
	var store_upload_dir = new Ext.data.JsonStore({
		id:'store_upload_dir_id',
		pageSize: 50,
		fields:[{
			name:'HD_ID'
		},{
			name:'DATA_FILE'
		},{
			name:'DATA_ATTACHMENT'
		}],
		 proxy:{
			type:'ajax',
			url:'isi_grid_upload_dir.php', 
			reader: {
				type: 'json',
				root: 'data',
				totalProperty:'total'        
			},
		}
	});
	
	var grid_upload_dir = Ext.create('Ext.grid.Panel',{
		id:'grid_upload_dir_id',
		region:'center',
		store:store_upload_dir,
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
	Ext.clearForm=function(){
		Ext.getCmp('tf_hdid').setValue('')
		Ext.getCmp('tf_typeform').setValue('tambah')
		Ext.getCmp('tf_np').setValue('')
		Ext.getCmp('cb_user').setValue('')
		Ext.getCmp('tf_mgr').setValue('')
		Ext.getCmp('tf_tipe').setValue('')
		Ext.getCmp('cb_user_ID').setValue('')
		Ext.getCmp('tf_perusahaan').setValue('')
		Ext.getCmp('tf_dept').setValue('')
		Ext.getCmp('tf_posisi').setValue('')
		Ext.getCmp('tf_plant').setValue('')
		Ext.getCmp('tf_mk').setValue('')
		Ext.getCmp('tf_gg').setValue('')
		store_detil.removeAll();
		Ext.getCmp('tf_ss').setValue(0)
		Ext.getCmp('tf_ss_hide').setValue(0)
		Ext.getCmp('tf_tb').setValue('')
		Ext.getCmp('tf_tp').setValue('')
		Ext.getCmp('tf_nominal').setValue(0)
		Ext.getCmp('tf_nominal_hide').setValue(0)
		Ext.getCmp('tf_jc').setValue(1)
		Ext.getCmp('tf_nc').setValue(0)
		Ext.getCmp('tf_tpi').setValue('')
		Ext.getCmp('tf_ket_mgr').setValue('')
		Ext.getCmp('tf_ket_hrd').setValue('')
		Ext.getCmp('tf_ket').setValue('')
		Ext.getCmp('tf_total').setValue(0)
		Ext.getCmp('tf_totalrp').setValue(0)
		Ext.getCmp('cb_bulan').setValue('')
		Ext.getCmp('cb_tahun').setValue('')
		
		Ext.getCmp('cb_tipe_pencairan').setValue('')
		Ext.getCmp('tf_no_rek').setValue('')
		
		Ext.Ajax.request({
			url:'delete_upload.php',
			method:'POST',
			success:function(response){
			},
			failure:function(error){
				alertDialog('Warning','Save failed.');
			}
		});
		
		Ext.Ajax.request({
			url:'delete_upload_view.php',
			method:'POST',
			success:function(response){
			},
			failure:function(error){
				alertDialog('Warning','Save failed.');
			}
		});
		
		Ext.Ajax.request({
			url:'delete_upload_dir.php',
			method:'POST',
			success:function(response){
			},
			failure:function(error){
				alertDialog('Warning','Save failed.');
			}
		});
		
		store_upload.removeAll();
		store_upload_view.removeAll();
		store_upload_dir.removeAll();
	};
	
	var store_detil=new Ext.data.JsonStore({
		id:'store_cari_id',
		pageSize: 100,
		model: 'Plant',
		fields:[{
			name:'HD_ID'
		},{
			name:'DATA_NO_PINJAM'
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
		},{
			name:'DATA_STATUS'
		},{
			name:'DATA_APP_TERAKHIR'
		},{
			name:'DATA_START_POTONGAN'
		}],
		proxy:{
			type:'ajax',
			url:'isi_grid_potongan.php', 
			reader: {
				root: 'rows',  
			},
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
			name:'DATA_TIPE'
		},{
			name:'DATA_MGR'
		},{
			name:'DATA_PERSON_ID'
		},{
			name:'DATA_PERSON'
		},{
			name:'DATA_TGL'
		},{
			name:'DATA_NOMINAL'
		},{
			name:'DATA_NOMINAL_HIDE'
		},{
			name:'DATA_JML_C'
		},{
			name:'DATA_TUJUAN'
		},{
			name:'DATA_KET_MGR'
		},{
			name:'DATA_KET_HRD'
		},{
			name:'DATA_TGL_BUAT'
		},{
			name:'DATA_STATUS'
		},{
			name:'DATA_NC'
		},{
			name:'START_POTONGAN_BULAN'
		},{
			name:'START_POTONGAN_TAHUN'
		},{
			name:'DATA_START_POTONGAN'
		},{
			name:'DATA_TIPE_PENCAIRAN'
		},{
			name:'DATA_NOMOR_REKENING'
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
			dataIndex:'DATA_MGR',
			header:'Manager',
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
			dataIndex:'DATA_NC',
			header:'Nominal Cicilan',
			width:100,
			hidden:true
		},{
			dataIndex:'DATA_PINJAMAN',
			header:'No Pinjaman',
			width:180,
			// hidden:true
		},{
			dataIndex:'DATA_PERSON',
			header:'Nama Peminjam',
			width:180,
			// hidden:true
		},{
			dataIndex:'DATA_TIPE',
			header:'Tipe Pinjaman',
			width:150,
			//hidden:true
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
			dataIndex:'DATA_NOMINAL_HIDE',
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
			dataIndex:'DATA_KET_MGR',
			header:'Keterangan Mgr',
			width:200,
			//hidden:true
		},{
			dataIndex:'DATA_KET_HRD',
			header:'Keterangan Hrd',
			width:200,
			//hidden:true
		},{
			dataIndex:'DATA_STATUS',
			header:'Status',
			width:50,
			//hidden:true
		},{
			dataIndex:'START_POTONGAN_BULAN',
			header:'Start Potongan (Bulan)',
			width:50,
			hidden:true
		},{
			dataIndex:'START_POTONGAN_TAHUN',
			header:'Start Potongan (Tahun)',
			width:50,
			hidden:true
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
		}],
		listeners: {
			dblclick: {
				element: 'body', //bind to the underlying body property on the panel
				fn: function(){
					var hdid=grid_popuptransaksi.getSelectionModel().getSelection()[0].get('HD_ID');
					Ext.getCmp('tf_hdid').setValue(hdid);
					Ext.getCmp('tf_np').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_PINJAMAN'));
					comboPemohon.load();
					Ext.getCmp('cb_user').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_PERSON'));
					Ext.getCmp('tf_mgr').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_MGR'));
					Ext.getCmp('tf_tipe').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_TIPE'));
					Ext.getCmp('cb_user_ID').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_PERSON_ID'));
					Ext.getCmp('tf_tb').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_TGL_BUAT'));
					Ext.getCmp('tf_tp').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_TGL'));
					Ext.getCmp('tf_nominal').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_NOMINAL'));
					
					Ext.getCmp('tf_nominal_hide').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_NOMINAL_HIDE'));
					
					Ext.getCmp('tf_jc').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_JML_C'));
					Ext.getCmp('tf_nc').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_NC'));
					Ext.getCmp('tf_tpi').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_TUJUAN'));
					Ext.getCmp('tf_ket_mgr').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_KET_MGR'));
					Ext.getCmp('tf_ket_hrd').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_KET_HRD'));
					
					// Ext.getCmp('tf_startpot').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_START_POTONGAN'));
					
					Ext.getCmp('cb_bulan').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('START_POTONGAN_BULAN'));
					Ext.getCmp('cb_tahun').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('START_POTONGAN_TAHUN'));
					
					Ext.getCmp('cb_tipe_pencairan').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_TIPE_PENCAIRAN'));
					Ext.getCmp('tf_no_rek').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_NOMOR_REKENING'));
					
					store_detil.setProxy({
						type:'ajax',
						timeout: 500000,
						url:'isi_grid_potongan.php?nama_pem=' + Ext.getCmp('cb_user_ID').getValue(),
						reader: {
							type: 'json',
							root: 'data', 
							totalProperty:'total'   
						}
					});
					store_detil.load();
					
					
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
					
					
					store_upload_view.setProxy({
						type:'ajax',
						timeout: 500000,
						url:'isi_grid_upload_view.php?hd_id=' + hdid,
						reader: {
							type: 'json',
							root: 'data', 
							totalProperty:'total'   
						}
					});
					store_upload_view.load();
					
					
					store_upload_dir.setProxy({
						type:'ajax',
						timeout: 500000,
						url:'isi_grid_upload_dir.php?hd_id=' + hdid,
						reader: {
							type: 'json',
							root: 'data', 
							totalProperty:'total'   
						}
					});
					store_upload_dir.load();
					
					
					Ext.Ajax.request({
						url:'<?PHP echo PATHAPP ?>/transaksi/potonganaccdir/insert_grid_upload_dir.php',
						params:{
							idtrans:hdid,
						},
						method:'POST',
						success:function(response){
							store_upload_dir.load();
						},
						failure:function(error){
							alertDialog('Warning','Save failed.');
						}
					});
					
					
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
								var tglmasuk = deskripsisplit[4];
								var gajirp = deskripsisplit[5];
								var gaji = deskripsisplit[6];
								
								var total = deskripsisplit[7];
								var totalrp = deskripsisplit[8];
								
								Ext.getCmp('tf_ss').setValue((parseFloat(gaji) - parseFloat(Ext.getCmp('tf_total').getValue())).formatMoney(2, ',', '.'));
								Ext.getCmp('tf_ss_hide').setValue((parseFloat(gaji) - parseFloat(Ext.getCmp('tf_total').getValue())));
								
								Ext.getCmp('tf_perusahaan').setValue(perusahaan);
								Ext.getCmp('tf_dept').setValue(dept);
								Ext.getCmp('tf_posisi').setValue(jabatan);
								Ext.getCmp('tf_plant').setValue(plant);
								Ext.getCmp('tf_mk').setValue(tglmasuk);
								Ext.getCmp('tf_gg').setValue(gajirp);
								Ext.getCmp('tf_gg_hide').setValue(gaji);
								
								Ext.getCmp('tf_totalrp').setValue( totalrp );
								Ext.getCmp('tf_total').setValue( total );								
								Ext.getCmp('tf_ss').setValue((parseFloat(gaji) - parseFloat( total )).formatMoney(2, ',', '.'));
								Ext.getCmp('tf_ss_hide').setValue((parseFloat(gaji) - parseFloat( total )));
								
								var max = parseFloat(gaji) - parseFloat( total );
								Ext.getCmp('tf_maxnominal').setValue(max);
								
							},
						method:'POST',
					});
					
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
			dataIndex:'DATA_NO_PINJAM',
			header:'No Pinjaman',
			width:180,
		},{
			dataIndex:'DATA_JENIS',
			header:'Jenis Pinjaman',
			width:150,
		},{
			dataIndex:'DATA_TGL',
			header:'Tgl Pinjam',
			//hidden:true,
			width:90,
		},{
			dataIndex:'DATA_START_POTONGAN',
			header:'Start Potongan',
			//hidden:true,
			width:100,
		},{
			dataIndex:'DATA_JML_P',
			header:'Jml Pinjaman',
			//hidden:true,
			width:85,
			align:'right'
		},{
			dataIndex:'DATA_JML_C',
			header:'Jml Cicilan',
			width:85,
			align:'right'
		},{
			dataIndex:'DATA_CICILAN',
			header:'Cicilan per Periode',
			width:110,
			align:'right'
		},{
			dataIndex:'DATA_OUTSTANDING_RP',
			header:'Outstanding (Rp)',
			width:150,
			align:'right'
		},{
			dataIndex:'DATA_OUTSTANDING_BLN',
			header:'Outstanding (Bln)',
			width:100,
			align:'right'
		},{
			dataIndex:'DATA_CICILAN_BLN',
			header:'Cicilan Terakhir (Bln)',
			width:120,
		},{
			dataIndex:'DATA_STATUS',
			header:'Status',
			width:100,
		},{
			dataIndex:'DATA_APP_TERAKHIR',
			header:'Approval Terakhir',
			width:150,
		}],
        selModel: {
            selType: 'cellmodel'
        },
        plugins: [cellEditing],
    });
	
	var comboBulan = Ext.create('Ext.data.Store', {
		fields: ['DATA_NAME', 'DATA_VALUE'],
		data : [
			{"DATA_NAME":"Januari", "DATA_VALUE":"1"},
			{"DATA_NAME":"Februari", "DATA_VALUE":"2"},
			{"DATA_NAME":"Maret", "DATA_VALUE":"3"},
			{"DATA_NAME":"April", "DATA_VALUE":"4"},
			{"DATA_NAME":"Mei", "DATA_VALUE":"5"},
			{"DATA_NAME":"Juni", "DATA_VALUE":"6"},
			{"DATA_NAME":"Juli", "DATA_VALUE":"7"},
			{"DATA_NAME":"Agustus", "DATA_VALUE":"8"},
			{"DATA_NAME":"September", "DATA_VALUE":"9"},
			{"DATA_NAME":"Oktober", "DATA_VALUE":"10"},
			{"DATA_NAME":"November", "DATA_VALUE":"11"},
			{"DATA_NAME":"Desember", "DATA_VALUE":"12"}
		]
	});
	
	var kategoriForm = "All";
	var contentPanel = Ext.create('Ext.panel.Panel',{
			//title:'Form Input Tanda Terima',
			//region:'center',
			bodyStyle: 'spacing: 10px;border:none',
			items:[{
				xtype:'label',
				html:'<div align="center"><font size="5"><b>Approval Pinjaman Karyawan Direksi</b></font></div>',
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
				xtype:'textfield',
				fieldLabel:'max nominal',
				width:350,
				labelWidth:150,
				id:'tf_maxnominal',
				value:0,
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
						labelWidth:100,
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
							
							Ext.getCmp('tf_ket').setValue('');
							
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
						labelWidth:100,
						id:'cb_user',
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
					columnWidth:.6,
					border:false,
					layout: 'anchor',
					defaultType: 'combobox',
					items:[{		
						xtype:'textfield',
						fieldLabel:'Manager',
						width:450,
						labelWidth:100,
						id:'tf_mgr',
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
					columnWidth:.6,
					border:false,
					layout: 'anchor',
					defaultType: 'combobox',
					items:[{		
						xtype:'textfield',
						fieldLabel:'Tipe Pinjaman',
						width:450,
						labelWidth:100,
						id:'tf_tipe',
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
						labelWidth:100,
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
						labelWidth:100,
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
						labelWidth:100,
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
						labelWidth:100,
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
						labelWidth:100,
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
						fieldLabel:'Masuk Kerja',
						width:450,
						labelWidth:100,
						id:'tf_mk',
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
						fieldLabel:'Gaji Gross',
						width:450,
						labelWidth:100,
						id:'tf_gg',
						readOnly:true,
						fieldStyle:'background:#B8B8B8 ;font-weight:bold;'
					}]
				}
				,{
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
						fieldLabel:'Gaji Gross Hide',
						width:450,
						labelWidth:100,
						id:'tf_gg_hide',
						hidden:true,
					}]
				}
				]
			},{
				xtype:'panel',
				bodyStyle: 'padding-left: 300px;padding-bottom: 10px;border:none',
				items:[{
					xtype:'label',
					html:'&nbsp',
				}]
			},{
				xtype: 'fieldset',
				flex: 1,
				title: 'History Pinjaman',
				defaultType: 'panel', // each item will be a radio button
				layout: 'anchor',
				items: [{
					xtype:'panel',
					bodyStyle: 'padding-left: 0px;padding-bottom: 5px;border:none',
					items:[grid_detil,{
						xtype:'label',
						html:'&nbsp',
					},{
						layout:'column',
						border:false,
						items:[
						{
							columnWidth:.02,
							border:false,
							layout: 'anchor',
							defaultType: 'label',
							items:[{
								xtype:'label',
								html:'',
							}]
						},
						{
							columnWidth:.6,
							border:false,
							layout: 'anchor',
							defaultType: 'combobox',
							items:[{		
								xtype:'textfield',
								fieldLabel:'Total',
								width:250,
								labelWidth:100,
								id:'tf_totalrp',
								readOnly:true,
								value:0,
								fieldStyle:'background:#B8B8B8 ;font-weight:bold;'
							}]
						},{
							columnWidth:.6,
							border:false,
							layout: 'anchor',
							defaultType: 'combobox',
							items:[{		
								xtype:'textfield',
								hidden:true,
								fieldLabel:'Total',
								width:380,
								labelWidth:90,
								id:'tf_total',
								value:0,
								readOnly:true,
								fieldStyle:'background:#B8B8B8 ;font-weight:bold;'
							}]
						}]	
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
						xtype:'panel',
						bodyStyle: 'padding-left: 0px;padding-bottom: 5px;border:none',
						items:[{
							layout:'column',
							border:false,
							items:[{
								columnWidth:.04,
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
									fieldLabel:'Sisa Saldo',
									width:400,
									labelWidth:110,
									id:'tf_ss',
									value:0,
									readOnly:true,
									fieldStyle:'background:#B8B8B8 ;font-weight:bold;'
								}]
							},{
								columnWidth:.98,
								border:false,
								layout: 'anchor',
								defaultType: 'combobox',
								hidden:true,
								items:[{		
									xtype:'textfield',
									fieldLabel:'Sisa Saldo hide',
									width:400,
									labelWidth:110,
									id:'tf_ss_hide',
									value:0,
									readOnly:true,
									fieldStyle:'background:#B8B8B8 ;font-weight:bold;'
								}]
							}]	
						},{
							layout:'column',
							border:false,
							items:[{
								columnWidth:.04,
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
									fieldLabel:'Tgl Pembuatan',
									width:400,
									labelWidth:110,
									id:'tf_tb',
									readOnly:true,
									fieldStyle:'background:#B8B8B8 ;font-weight:bold;'
								}]
							}]	
						},{
							layout:'column',
							border:false,
							items:[,{
								columnWidth:.04,
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
									fieldLabel:'Tgl Pengajuan',
									width:400,
									labelWidth:110,
									id:'tf_tp',
									readOnly:true,
									fieldStyle:'background:#B8B8B8 ;font-weight:bold;'
								}]
							}]	
						},{
							layout:'column',
							border:false,
							items:[,{
								columnWidth:.04,
								border:false,
								layout: 'anchor',
								defaultType: 'label',
								items:[{
									xtype:'label',
									html:'<div style="color:#FF0000">*</div>',
								}]
							},{
								columnWidth:.56,
								border:false,
								layout: 'anchor',
								defaultType: 'combobox',
								items:[{		
									xtype:'combobox',
									fieldLabel:'Start Potongan',
									width:220,
									labelWidth:110,
									id:'cb_bulan',
									store: comboBulan,
									displayField: 'DATA_NAME',
									valueField: 'DATA_VALUE'
								}]
							},{
								columnWidth:.3,
								border:false,
								layout: 'anchor',
								defaultType: 'numberfield',
								items:[{
									xtype:'numberfield',
									width:60,
									id:'cb_tahun'
								}]
							}]
						}
						
						
						, {
							layout:'column',
							border:false,
							items:[,{
								columnWidth:.04,
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
									fieldLabel:'Tipe Pencairan',
									width:300,
									labelWidth:110,
									id:'cb_tipe_pencairan',
									readOnly:true,
									fieldStyle:'background:#B8B8B8 ;font-weight:bold;'
								}]
							}]	
						}, {
							layout:'column',
							border:false,
							items:[,{
								columnWidth:.04,
								border:false,
								layout: 'anchor',
								defaultType: 'label',
								items:[{
									xtype:'label',
									// id:'lbl_no_rek',
									html:'',
								}]
							},{
								columnWidth:.27,
								border:false,
								//border:true,
								layout: 'anchor',
								defaultType: 'label',
								items:[{
									xtype:'label',
									// id:'lbl_no_rek',
									html:'<div>No Rekening: </div>',
								}]
							},{
								columnWidth:0.65,
								border:false,
								layout: 'anchor',
								defaultType: 'textfield',
								items:[{		
									xtype:'textfield',
									// fieldLabel:'No Rekening',
									width:450,
									// labelWidth:110,
									id:'tf_no_rek',
									readOnly:true,
									fieldStyle:'background:#B8B8B8 ;font-weight:bold;'
								}]
							}]	
						}, 
						
						
						
						{
							layout:'column',
							border:false,
							items:[,{
								columnWidth:.04,
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
								defaultType: 'numberfield',
								items:[{		
									xtype:'numberfield',
									fieldLabel:'Nominal',
									width:300,
									labelWidth:110,
									id:'tf_nominal',
									value: 0,
									// editable: false,
									minValue: 0,
									maxValue: 1000000000000,
									listeners: {
										'change':function(){
											
											
											if ( Ext.getCmp('tf_gg_hide').getValue() == 0 ) {
												
													var hasil = Ext.getCmp('tf_nominal').getValue() / Ext.getCmp('tf_jc').getValue();
													Ext.getCmp('tf_nc').setValue(hasil.formatMoney(2, ',', '.'));
													Ext.getCmp('tf_nominal_hide').setValue( Ext.getCmp('tf_nominal').getValue() );
												
											} else {
												
												
												if(Ext.getCmp('tf_maxnominal').getValue() != 0){
													if(Ext.getCmp('tf_tipe').getValue() == 'PINJAMAN PERSONAL'){ //alert('melbu');
														if(Ext.getCmp('tf_nominal').getValue()>Ext.getCmp('tf_maxnominal').getValue()){
															var nom = Ext.getCmp('tf_maxnominal').getValue();
															Ext.getCmp('tf_nominal').setValue(nom);
															Ext.getCmp('tf_nominal_hide').setValue(nom);
														}
													}
													var hasil = Ext.getCmp('tf_nominal').getValue() / Ext.getCmp('tf_jc').getValue();
													Ext.getCmp('tf_nc').setValue(hasil.formatMoney(2, ',', '.'));
													Ext.getCmp('tf_nominal_hide').setValue( Ext.getCmp('tf_nominal').getValue() );
												}
												
												
											}
											
										}
									}
								}]
							},{
								columnWidth:.9,
								border:false,
								layout: 'anchor',
								defaultType: 'numberfield',
								hidden:true,
								items:[{		
									xtype:'textfield',
									fieldLabel:'Nominal hide',
									width:300,
									labelWidth:110,
									id:'tf_nominal_hide',
									readOnly:true,
									fieldStyle:'background:#B8B8B8 ;font-weight:bold;'
								}]
							}]
						},{
							layout:'column',
							border:false,
							items:[,{
								columnWidth:.04,
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
								defaultType: 'numberfield',
								items:[{		
									xtype:'numberfield',
									fieldLabel:'Jumlah Cicilan',
									width:180,
									labelWidth:110,
									id:'tf_jc',
									value: 1,
									editable: false,
									minValue: 1,
									maxValue: 24,
									listeners: {
										 change: function(field,newValue,oldValue){
											var hasil = Ext.getCmp('tf_nominal').getValue() / Ext.getCmp('tf_jc').getValue();
											Ext.getCmp('tf_nc').setValue(hasil.formatMoney(2, '.', ','));
										}
									}
								}]
							}]	
						},{
							layout:'column',
							border:false,
							items:[,{
								columnWidth:.04,
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
									xtype:'textfield',
									fieldLabel:'Nominal Cicilan',
									width:300,
									labelWidth:110,
									id:'tf_nc',
									readOnly:true,
									fieldStyle:'background:#B8B8B8 ;font-weight:bold;'
								}]
							}]	
						},{
							layout:'column',
							border:false,
							items:[{
								columnWidth:.98,
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
											columnWidth:0.63,
											border:false,
											layout: 'anchor',
											defaultType: 'textfield',
											items:[grid_upload_view]
										}]	
									}]
								}]
							}]	
						},{
							layout:'column',
							border:false,
							items:[{
								columnWidth:.98,
								border:false,
								layout: 'anchor',
								defaultType: 'combobox',
								items:[{
									xtype: 'fieldset',
									flex: 1,
									title: 'Attachment HRD',
									layout: 'anchor',
									items: [{
										layout:'column',
										border:false,
										items:[{
											columnWidth:0.7,
											border:false,
											layout: 'anchor',
											defaultType: 'textfield',
											items:[grid_upload]
										}]	
									}]
								}]
							}]	
						}
						, {
							layout:'column',
							border:false,
							items:[{
								// columnWidth:.98,
								columnWidth:1.1,
								border:false,
								layout: 'anchor',
								defaultType: 'combobox',
								items:[{
									xtype: 'fieldset',
									flex: 1,
									title: 'Attachment Direksi',
									//defaultType: 'radio', // each item will be a radio button
									layout: 'anchor',
									items: [
										{
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
												html:'<div style="color:#FF0000">*</div>',
											}]
										},
										{
											columnWidth:0.63,
											border:false,
											layout: 'anchor',
											defaultType: 'textfield',
											items:[grid_upload_dir]
										},{
											columnWidth:.14,
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
											columnWidth:.14,
											border:false,
											layout: 'anchor',
											defaultType: 'button',
											items:[{xtype:'button',
												text:'Delete',
												width:50,
												handler:function(){
													var hdselec = grid_upload_dir.getSelectionModel().getSelection();
													if (hdselec != ''){
														var hdid=grid_upload_dir.getSelectionModel().getSelection()[0].get('HD_ID');
														if(hdid!=''){
															Ext.Ajax.request({
																url:'<?php echo 'delete_grid_upload_dir.php'; ?>',
																params:{
																	idupload:hdid,
																},
																method:'POST',
																success:function(response){
																	store_upload_dir.load();
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
						} 
						
						]
					}]
				},{
					columnWidth:.5,
					border:false,
					layout: 'anchor',
					defaultType: 'textareafield',
					items:[{
						xtype:'panel',
						bodyStyle: 'padding-left: 0px;padding-bottom: 5px;border:none',
						items:[{
							layout:'column',
							border:false,
							items:[,{
								columnWidth:.04,
								border:false,
								layout: 'anchor',
								defaultType: 'label',
								items:[{
									xtype:'label',
									html:'',
								}]
							},{
								columnWidth:.95,
								border:false,
								layout: 'anchor',
								defaultType: 'combobox',
								items:[{
									xtype:'textareafield',
									fieldLabel:'Tujuan pinjaman',
									width:400,
									height:80,
									labelWidth:90,
									id:'tf_tpi',
									readOnly:true,
									fieldStyle:'background:#B8B8B8 ;font-weight:bold;'
								}]
							},{
								columnWidth:.04,
								border:false,
								layout: 'anchor',
								defaultType: 'label',
								items:[{
									xtype:'label',
									html:'',
								}]
							},{
								columnWidth:.95,
								border:false,
								layout: 'anchor',
								defaultType: 'combobox',
								items:[{
									xtype:'textareafield',
									fieldLabel:'Keterangan Manager',
									width:400,
									height:80,
									labelWidth:90,
									id:'tf_ket_mgr',
									readOnly:true,
									fieldStyle:'background:#B8B8B8 ;font-weight:bold;'
								}]
							},{
								columnWidth:.04,
								border:false,
								layout: 'anchor',
								defaultType: 'label',
								items:[{
									xtype:'label',
									html:'',
								}]
							},{
								columnWidth:.95,
								border:false,
								layout: 'anchor',
								defaultType: 'combobox',
								items:[{
									xtype:'textareafield',
									fieldLabel:'Keterangan HRD',
									width:400,
									height:80,
									labelWidth:90,
									id:'tf_ket_hrd',
									readOnly:true,
									fieldStyle:'background:#B8B8B8 ;font-weight:bold;'
								}]
							},{
								columnWidth:.04,
								border:false,
								layout: 'anchor',
								defaultType: 'label',
								items:[{
									xtype:'label',
									html:'<div style="color:#FF0000">*</div>',
								}]
							},{
								columnWidth:.95,
								border:false,
								layout: 'anchor',
								defaultType: 'combobox',
								items:[{
									xtype:'textareafield',
									fieldLabel:'Keterangan Direksi',
									width:400,
									height:80,
									labelWidth:90,
									id:'tf_ket'
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
					text:'Approve',
					width:100,
					handler:function()
					{
						
						Ext.MessageBox.wait('Loading ...');
						
						if ( Ext.getCmp('tf_nominal').getValue() != 0 
								&& Ext.getCmp('tf_nominal').getValue() != ''
								&& Ext.getCmp('tf_nominal').getValue() != null
								&& Ext.getCmp('tf_jc').getValue() != 0
								&& Ext.getCmp('cb_bulan').getValue() != ''
								&& Ext.getCmp('cb_bulan').getValue() != null
								&& Ext.getCmp('cb_tahun').getValue() != ''
								&& Ext.getCmp('cb_tahun').getValue() != null
							)
						{
							
							if ( Ext.getCmp('tf_ket').getValue().length <= 500 ) 
							{
														

								Ext.Ajax.request({
									url:'<?php echo 'cek_start_potongan.php'; ?>',
										timeout: 500000,
										params:{
											param_bulan:Ext.getCmp('cb_bulan').getValue(),
											param_tahun:Ext.getCmp('cb_tahun').getValue(),
										},
										success:function(response) {
											
											var json=Ext.decode(response.responseText);
											
											if( json.vhasil_cek == '0' ) {
												
												Ext.MessageBox.hide();
												alertDialog('Kesalahan', 'Start Potongan paling cepat pada bulan berikutnya.');
												
											} else 
											{


																	
												if ( ( parseInt(Ext.getCmp('tf_ss_hide').getValue()) < parseInt(Ext.getCmp('tf_nominal_hide').getValue())
														|| parseInt(Ext.getCmp('tf_ss_hide').getValue()) < parseInt(Ext.getCmp('tf_nominal').getValue())
													) 

													&& ( Ext.getCmp('tf_tipe').getValue() == 'PINJAMAN PERSONAL' )
													&& ( Ext.getCmp('tf_gg_hide').getValue() != 0 )
													
													)
												
												{
													Ext.MessageBox.hide();
													alertDialog('Kesalahan', 'Nominal pinjaman tidak boleh melebihi sisa saldo.');
												}
												else {
													
													
													Ext.Ajax.request({
														url:'<?php echo 'check_double_attachment.php'; ?>',
														params:{
															hdid:Ext.getCmp('tf_hdid').getValue(),
														},
														method:'POST',
														success:function(response){
															
															var json = Ext.decode(response.responseText);
															var jsonresults = json.results;
															
															
															// Jika ada dua file attachment dengan nama sama dalam satu ID yang diproses
															
															if ( jsonresults == 2 ) {
																
																Ext.MessageBox.hide();
																alertDialog('Kesalahan', "Data tidak dapat disimpan karena ada nama file attachment yang sama.");
																
															} else {
																
																
																// Cek apakah sudah ada file yang di-upload. Jika belum ada, data tidak dapat disimpan.
																
																Ext.Ajax.request({
																	url:'<?php echo 'check_uploaded_file.php'; ?>',
																	method:'POST',
																	success:function(response){
																		
																		var json = Ext.decode(response.responseText);
																		var jsonresults = json.results;
																		
																		// console.log( jsonresults );
																		
																		if ( jsonresults == 'Kosong' ){
																			
																			Ext.MessageBox.hide();
																			alertDialog('Kesalahan', "Data tidak dapat disimpan karena belum ada file attachment yang di-upload.");
																
																		} else {
																			
																			// alertDialog('Kesalahan', "Data OK untuk disimpan.");
																			
																			/* DIPAKAI */
																			
																			Ext.Ajax.request({
																				url:'<?php echo 'simpan_potongan.php'; ?>',
																				params:{
																					hdid:Ext.getCmp('tf_hdid').getValue(),
																					typeform:'Approved',
																					keterangan:Ext.getCmp('tf_ket').getValue(),
																					nominal:Ext.getCmp('tf_nominal').getValue(),
																					jml:Ext.getCmp('tf_jc').getValue(),
																					bulan_pot:Ext.getCmp('cb_bulan').getValue(),
																					tahun_pot:Ext.getCmp('cb_tahun').getValue(),
																				},
																				method:'POST',
																				success:function(response){
																					var json=Ext.decode(response.responseText);
																					var jsonresults = json.results;
																					var jsonsplit = jsonresults.split('|');
																					var transId = jsonsplit[0];
																					var transNo = jsonsplit[1];
																					if (json.rows == "sukses"){
																						
																						// alertDialog('Sukses', "Data tersimpan dengan nomor : " + transNo + ".");
																						
																						Ext.MessageBox.hide();
																						alertDialog('Sukses', "Data nomor " + transNo + " telah di-approve.");
																						
																						
																						// Autoemail approval oleh direksi
																						
																						Ext.Ajax.request({
																							url:'<?PHP echo PATHAPP ?>/transaksi/potonganaccdir/autoemail_potonganaccdir.php',
																							method:'POST',
																							params:{
																								hdid : transId,
																								param_transNo : transNo,
																								param_tipe : Ext.getCmp('tf_tipe').getValue(),
																								param_approval:'Approved',
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
																			
																			/* DIPAKAI */
																			
																		}
																		
																	},
																	failure:function(error){
																		Ext.MessageBox.hide();
																		alertDialog('Kesalahan','Data gagal disimpan.');
																	}
																});
															
															}
															
														},
														failure:function(error){
															// alertDialog('Kesalahan','Data gagal disimpan.');
														}
													});
																						
													
												}

												
												
											}
															
										},
									method:'POST',
								});
								
								
								
									
							} else {
								Ext.MessageBox.hide();
								alertDialog( 'Kesalahan', 'Panjang isian Keterangan Direksi maksimal 500 karakter.' );
							}
												
						} else {
							Ext.MessageBox.hide();
							alertDialog( 'Peringatan', 'Nominal, Jumlah Cicilan, dan Start Potongan wajib diisi.' );
						}
					}
				},{
					xtype:'label',
					html:'&nbsp',
				},{
					xtype:'button',
					text:'Reject',
					width:100,
					handler:function(){
						
						Ext.MessageBox.wait('Loading ...');
						
						if (Ext.getCmp('tf_nominal').getValue()!=0 && Ext.getCmp('tf_jc').getValue()!=0 && Ext.getCmp('tf_ket').getValue()!=''){
							
							
							if ( Ext.getCmp('tf_ket').getValue().length <= 500 ) 
							{
								
								
								Ext.Ajax.request({
									url:'<?php echo 'simpan_potongan.php'; ?>',
									params:{
										hdid:Ext.getCmp('tf_hdid').getValue(),
										typeform:'Disapproved',
										keterangan:Ext.getCmp('tf_ket').getValue(),
										nominal:Ext.getCmp('tf_nominal').getValue(),
										jml:Ext.getCmp('tf_jc').getValue(),
										bulan_pot:Ext.getCmp('cb_bulan').getValue(),
										tahun_pot:Ext.getCmp('cb_tahun').getValue(),									
									},
									method:'POST',
									success:function(response){
										var json=Ext.decode(response.responseText);
										var jsonresults = json.results;
										var jsonsplit = jsonresults.split('|');
										var transId = jsonsplit[0];
										var transNo = jsonsplit[1];
										if (json.rows == "sukses"){
											
											// alertDialog('Sukses', "Data tersimpan dengan nama : " + transNo + ".");
											
											Ext.MessageBox.hide();
											alertDialog('Sukses', "Data nomor " + transNo + " berhasil di-reject.");
											
											Ext.Ajax.request({
												url:'<?PHP echo PATHAPP ?>/transaksi/potonganaccdir/autoemail_potonganaccdir.php',
												method:'POST',
												params:{
													hdid : transId,
													param_transNo : transNo,
													param_tipe : Ext.getCmp('tf_tipe').getValue(),
													param_approval:'Disapproved',
													param_keterangan: Ext.getCmp('tf_ket').getValue(),
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
								alertDialog( 'Kesalahan', 'Panjang isian Keterangan Direksi maksimal 500 karakter.' );
							}
							
							
						} else {
							Ext.MessageBox.hide();
							alertDialog('Peringatan','Nominal, Jumlah Cicilan, dan Keterangan Direksi wajib diisi.');
						}
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