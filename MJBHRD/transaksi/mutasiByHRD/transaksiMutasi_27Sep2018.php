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
	var comboTipe = Ext.create('Ext.data.Store', {
		fields: ['DATA_NAME', 'DATA_VALUE'],
		data : [
			{"DATA_NAME":"Mutasi", "DATA_VALUE":"Mutasi"},
			{"DATA_NAME":"Promosi", "DATA_VALUE":"Promosi"},
			{"DATA_NAME":"Demosi", "DATA_VALUE":"Demosi"}
		]
	});
	var comboStatusKar = Ext.create('Ext.data.Store', {
		fields: ['DATA_NAME', 'DATA_VALUE'],
		data : [
			{"DATA_NAME":"Tetap", "DATA_VALUE":"TETAP"},
			{"DATA_NAME":"Percobaan", "DATA_VALUE":"PERCOBAAN"},
			{"DATA_NAME":"PJS", "DATA_VALUE":"PJS"},
			{"DATA_NAME":"Kontrak", "DATA_VALUE":"KONTRAK"},
			{"DATA_NAME":"Kontrak Khusus", "DATA_VALUE":"KONTRAK KHUSUS"}
		]
	});
	var comboSifatPer = Ext.create('Ext.data.Store', {
		fields: ['DATA_NAME', 'DATA_VALUE'],
		data : [
			{"DATA_NAME":"Tetap", "DATA_VALUE":"TETAP"},
			{"DATA_NAME":"Sementara", "DATA_VALUE":"SEMENTARA"},
		]
	});
	var comboKaryawan = Ext.create('Ext.data.Store', {
		fields: [{
			name:'DATA_VALUE'
		},{
			name:'DATA_NAME'
		}],
		proxy:{
			type:'ajax',
			url:'<?PHP echo PATHAPP ?>/combobox/combobox_pemohon_mutasi.php', 
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
	var comboManagerLama = Ext.create('Ext.data.Store', {
		fields: [{
			name:'DATA_VALUE'
		},{
			name:'DATA_NAME'
		}],
		proxy:{
			type:'ajax',
			url:'<?PHP echo PATHAPP ?>/combobox/combobox_manager_mutasi_lama.php',
			reader: {
				type: 'json',
				root: 'data', 
				totalProperty:'total'   
			}
		}
	});
	var comboManagerBaru = Ext.create('Ext.data.Store', {
		fields: [{
			name:'DATA_VALUE'
		},{
			name:'DATA_NAME'
		}],
		proxy:{
			type:'ajax',
			url:'<?PHP echo PATHAPP ?>/combobox/combobox_manager_mutasi_baru.php',
			reader: {
				type: 'json',
				root: 'data', 
				totalProperty:'total'   
			}
		}
	});
	var comboPosisi = Ext.create('Ext.data.Store', {
		fields: [{
			name:'DATA_VALUE'
		},{
			name:'DATA_NAME'
		}],
		proxy:{
			type:'ajax',
			url:'<?PHP echo PATHAPP ?>/combobox/combobox_posisi.php',
			reader: {
				type: 'json',
				root: 'data', 
				totalProperty:'total'   
			}
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
			url:'<?PHP echo PATHAPP ?>/combobox/combobox_grade.php',
			reader: {
				type: 'json',
				root: 'data', 
				totalProperty:'total'   
			}
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
			}
		}
	});
	
	var comboDireksi = Ext.create('Ext.data.Store', {
		fields: [{
			name:'DATA_VALUE'
		},{
			name:'DATA_NAME'
		}],
		proxy:{
			type:'ajax',
			url:'<?PHP echo PATHAPP ?>/combobox/combobox_direksi.php', 
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
		Ext.getCmp('tf_no_req').setValue('')
		Ext.getCmp('cb_tipe').setValue('')
		Ext.getCmp('cb_status_kar').setValue('TETAP')
		Ext.getCmp('cb_sifat_per').setValue('TETAP')
		Ext.getCmp('tf_lama').setValue(0)
		Ext.getCmp('cb_tipe').setDisabled(false)
		Ext.getCmp('cb_karyawan').setValue('')
		Ext.getCmp('cb_karyawan').setDisabled(false)
		Ext.getCmp('tf_grade').setValue('')
		Ext.getCmp('tf_dept').setValue('')
		Ext.getCmp('tf_posisi').setValue('')
		Ext.getCmp('tf_lokasi').setValue('')
		Ext.getCmp('tf_dept_id').setValue('')
		Ext.getCmp('tf_posisi_id').setValue('')
		Ext.getCmp('tf_lokasi_id').setValue('')
		Ext.getCmp('tf_gaji').setValue('0')
		Ext.getCmp('tf_gaji_baru').setValue('0')
		Ext.getCmp('tf_gaji_view').setValue('0.00')
		Ext.getCmp('cb_dept').setValue('')
		Ext.getCmp('cb_mgr_lama').setValue('')
		Ext.getCmp('cb_mgr_baru').setValue('')
		Ext.getCmp('cb_posisi').setValue('')
		Ext.getCmp('cb_grade').setValue('')
		Ext.getCmp('cb_direksi').setValue('')
		Ext.getCmp('cb_lokasi').setValue('')
		Ext.getCmp('cb_dept').setDisabled(true)
		Ext.getCmp('cb_mgr_lama').setDisabled(true)
		Ext.getCmp('cb_mgr_baru').setDisabled(true)
		Ext.getCmp('cb_grade').setDisabled(true)
		Ext.getCmp('cb_posisi').setDisabled(true)
		Ext.getCmp('cb_lokasi').setDisabled(true)
		Ext.getCmp('tf_keterangan').setValue('')
		Ext.getCmp('tf_alasan').setValue('')
		Ext.getCmp('tf_tgl').setValue('')
		Ext.getCmp('cb_aktif').setValue(true)
		Ext.getCmp('tf_tgl').setMinValue(Ext.Date.add(new Date(), Ext.Date.DAY, 0));
		comboDept.load()
		comboGrade.load()
		comboPosisi.load()
		comboLokasi.load()
		comboKaryawan.load()
		store_upload.removeAll();
	};
	var kategoriForm = "Cuti";
	var store_popuptransaksi=new Ext.data.JsonStore({
		id:'store_popuptransaksi_id',
		pageSize: 100,
		fields:[{
			name:'HD_ID'
		},{
			name:'DATA_NOREQ'
		},{
			name:'DATA_TIPE'
		},{
			name:'DATA_KARYAWAN_ID'
		},{
			name:'DATA_KARYAWAN'
		},{
			name:'DATA_DEPT_LAMA_ID'
		},{
			name:'DATA_DEPT_LAMA'
		},{
			name:'DATA_POSISI_LAMA_ID'
		},{
			name:'DATA_POSISI_LAMA'
		},{
			name:'DATA_GRADE_LAMA_ID'
		},{
			name:'DATA_GRADE_LAMA'
		},{
			name:'DATA_LOKASI_LAMA_ID'
		},{
			name:'DATA_LOKASI_LAMA'
		},{
			name:'DATA_GAJI_LAMA'
		},{
			name:'DATA_DEPT'
		},{
			name:'DATA_POSISI'
		},{
			name:'DATA_GRADE'
		},{
			name:'DATA_LOKASI'
		},{
			name:'DATA_GAJI'
		},{
			name:'DATA_KETERANGAN'
		},{
			name:'DATA_ALASAN'
		},{
			name:'DATA_AKTIF'
		},{
			name:'DATA_TGL'
		},{
			name:'DATA_MGR_LAMA'
		},{
			name:'DATA_MGR_BARU'
		},{
			name:'DATA_STATUS_KARYAWAN'
		},{
			name:'DATA_SIFAT_PERUBAHAN'
		},{
			name:'DATA_JUMLAH_BULAN'
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
			dataIndex:'DATA_NOREQ',
			header:'No Mutasi',
			width:200,
		},{
			dataIndex:'DATA_TIPE',
			header:'Tipe',
			width:100,
		},{
			dataIndex:'DATA_KARYAWAN',
			header:'Nama Karyawan',
			width:300,
		},{
			dataIndex:'DATA_STATUS',
			header:'Status',
			width:100,
			hidden:true
		},{
			dataIndex:'DATA_KARYAWAN_ID',
			header:'karyawan',
			width:100,
			hidden:true
		},{
			dataIndex:'DATA_DEPT_LAMA_ID',
			header:'Dept',
			width:100,
			hidden:true
		},{
			dataIndex:'DATA_DEPT_LAMA',
			header:'Dept',
			width:100,
			hidden:true
		},{
			dataIndex:'DATA_POSISI_LAMA_ID',
			header:'Posisi',
			width:75,
			hidden:true
		},{
			dataIndex:'DATA_POSISI_LAMA',
			header:'Posisi',
			width:75,
			hidden:true
		},{
			dataIndex:'DATA_LOKASI_LAMA_ID',
			header:'Lokasi',
			width:75,
			hidden:true
		},{
			dataIndex:'DATA_LOKASI_LAMA',
			header:'Lokasi',
			width:75,
			hidden:true
		},{
			dataIndex:'DATA_GAJI_LAMA',
			header:'Gaji',
			width:150,
			hidden:true
		},{
			dataIndex:'DATA_DEPT',
			header:'Dept',
			width:100,
			hidden:true
		},{
			dataIndex:'DATA_POSISI',
			header:'Posisi',
			width:50,
			hidden:true
		},{
			dataIndex:'DATA_LOKASI',
			header:'lokasi',
			width:100,
			hidden:true
		},{
			dataIndex:'DATA_GAJI',
			header:'Gaji',
			width:100,
			hidden:true
		},{
			dataIndex:'DATA_KETERANGAN',
			header:'Keterangan',
			width:150,
			hidden:true
		},{
			dataIndex:'DATA_ALASAN',
			header:'Alasan',
			width:150,
			hidden:true
		},{
			dataIndex:'DATA_AKTIF',
			header:'Status',
			width:150,
			hidden:true
		},{
			dataIndex:'DATA_TGL',
			header:'Tgl',
			width:150,
			hidden:true
		},{
			dataIndex:'DATA_GRADE',
			header:'Alasan',
			width:150,
			hidden:true
		},{
			dataIndex:'DATA_GRADE_LAMA',
			header:'Status',
			width:150,
			hidden:true
		},{
			dataIndex:'DATA_GRADE_LAMA_ID',
			header:'Tgl',
			width:150,
			hidden:true
		},{
			dataIndex:'DATA_MGR_LAMA',
			header:'Status',
			width:150,
			hidden:true
		},{
			dataIndex:'DATA_MGR_BARU',
			header:'Tgl',
			width:150,
			hidden:true
		},{
			dataIndex:'DATA_STATUS_KARYAWAN',
			header:'Status Karyawan',
			width:150,
			hidden:true
		},{
			dataIndex:'DATA_SIFAT_PERUBAHAN',
			header:'Sifat Perubahan',
			width:150,
			hidden:true
		},{
			dataIndex:'DATA_JUMLAH_BULAN',
			header:'Jumlah Bulan',
			width:150,
			hidden:true
		},{
			dataIndex:'DATA_ACTION',
			header:'Edit',
			width:40,
			hideable:false,
			sortable:false,
			align:'center',
			renderer:function(){
				return "<img style='cursor:pointer;'src='../../media/css/images/main-icon/toolbar/ubah1.png' />"; 
			}
		}],
		listeners: {
			cellclick:function(grid,row,col){
				//alert(col);
				if (col==30) {
					var hdid=grid_popuptransaksi.getSelectionModel().getSelection()[0].get('HD_ID');
					Ext.getCmp('tf_hdid').setValue(hdid);
					Ext.getCmp('tf_typeform').setValue('edit');
					var vstatus=grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_AKTIF');
					if(vstatus == 'Y'){
						Ext.getCmp('cb_aktif').setValue(true);
					} else {
						Ext.getCmp('cb_aktif').setValue(false);
					}
					Ext.getCmp('tf_no_req').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_NOREQ'));
					Ext.getCmp('cb_tipe').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_TIPE'));
					Ext.getCmp('cb_status_kar').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_STATUS_KARYAWAN'));
					Ext.getCmp('cb_sifat_per').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_SIFAT_PERUBAHAN'));
					Ext.getCmp('tf_lama').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_JUMLAH_BULAN'));
					var vPemohon = grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_KARYAWAN_ID');
					Ext.Ajax.request({
						url:'<?php echo 'isi_gaji.php'; ?>',
							params:{
								nama_pem:vPemohon,
							},
							success:function(response){
								var json=Ext.decode(response.responseText);
								var deskripsiasli = json.results;
								var deskripsisplit = deskripsiasli.split('|');
								Ext.getCmp('tf_gaji').setValue(deskripsisplit[0]);
								Ext.getCmp('tf_gaji_view').setValue(deskripsisplit[1]);
							},
						method:'POST',
					});
					
					comboStatusKar.load();
					comboSifatPer.load();
					
					//Ext.getCmp('cb_mgr_lama').setValue('');
					//Ext.getCmp('cb_mgr_baru').setValue('');
					Ext.getCmp('cb_mgr_lama').setDisabled(true);
					Ext.getCmp('cb_mgr_baru').setDisabled(true);
					
					comboManagerLama.setProxy({
						type:'ajax',
						url:'<?PHP echo PATHAPP ?>/combobox/combobox_manager_mutasi_lama.php?pemohon=' + vPemohon,
						reader: {
							type: 'json',
							root: 'data', 
							totalProperty:'total'   
						}
					});
					Ext.getCmp('cb_mgr_lama').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_MGR_LAMA'));
					comboManagerLama.load();
					Ext.getCmp('cb_karyawan').setValue(vPemohon);
					Ext.getCmp('cb_karyawan').setDisabled(true)
					Ext.getCmp('cb_tipe').setDisabled(true)
					Ext.getCmp('cb_status_kar').setDisabled(false)
					Ext.getCmp('cb_sifat_per').setDisabled(false)
					Ext.getCmp('tf_lama').setDisabled(true)
					if(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_SIFAT_PERUBAHAN') == 'SEMENTARA'){
						Ext.getCmp('tf_lama').setDisabled(false)
					}
					
					Ext.Ajax.request({
						url:'<?php echo 'isi_dept.php'; ?>',
							params:{
								nama_pem:vPemohon,
							},
							success:function(response){
								var json=Ext.decode(response.responseText);
								var deskripsiasli = json.results;
								var deskripsisplit = deskripsiasli.split('|');
								var nama_dept = deskripsisplit[0];
								Ext.getCmp('tf_dept_id').setValue(deskripsisplit[0]);
								Ext.getCmp('tf_dept').setValue(deskripsisplit[1]);
								Ext.getCmp('tf_posisi_id').setValue(deskripsisplit[2]);
								Ext.getCmp('tf_posisi').setValue(deskripsisplit[3]);
								Ext.getCmp('tf_lokasi_id').setValue(deskripsisplit[4]);
								Ext.getCmp('tf_lokasi').setValue(deskripsisplit[5]);
								Ext.getCmp('tf_grade_id').setValue(deskripsisplit[6]);
								Ext.getCmp('tf_grade').setValue(deskripsisplit[7]);
								if(Ext.getCmp('cb_tipe').getValue() != '' && deskripsisplit[7] != ''){
									// console.log('TES');
									comboGrade.setProxy({
										type:'ajax',
										url:'<?PHP echo PATHAPP ?>/combobox/combobox_grade.php?tipe=' + Ext.getCmp('cb_tipe').getValue() + '&grade=' + deskripsisplit[7],
										reader: {
											type: 'json',
											root: 'data', 
											totalProperty:'total'   
										}
									});
									comboGrade.load();
									Ext.getCmp('cb_grade').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_GRADE'));
								}
							},
						method:'POST',
					});
					var vJobId=grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_DEPT');
					Ext.getCmp('cb_dept').setValue(vJobId);
					comboPosisi.setProxy({
						type:'ajax',
						url:'<?PHP echo PATHAPP ?>/combobox/combobox_posisi.php?jobId=' + vJobId,
						reader: {
							type: 'json',
							root: 'data', 
							totalProperty:'total'   
						}
					});
					comboPosisi.load();
					comboManagerBaru.setProxy({
						type:'ajax',
						url:'<?PHP echo PATHAPP ?>/combobox/combobox_manager_mutasi_baru.php?dept=' + vJobId,
						reader: {
							type: 'json',
							root: 'data', 
							totalProperty:'total'   
						}
					});
					Ext.getCmp('cb_mgr_baru').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_MGR_BARU'));
					comboManagerBaru.load();
					Ext.getCmp('cb_posisi').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_POSISI'));
					Ext.getCmp('cb_lokasi').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_LOKASI'));
					Ext.getCmp('tf_gaji_baru').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_GAJI'));
					Ext.getCmp('tf_alasan').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_ALASAN'));
					Ext.getCmp('tf_keterangan').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_KETERANGAN'));
					Ext.getCmp('tf_tgl').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_TGL'));
					PopupSIK.hide();
				}
			},
			dblclick: {
				element: 'body', //bind to the underlying body property on the panel
				fn: function(){ 
					var hdid=grid_popuptransaksi.getSelectionModel().getSelection()[0].get('HD_ID');
					Ext.getCmp('tf_hdid').setValue(hdid);
					Ext.getCmp('tf_typeform').setValue('edit');
					var vstatus=grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_AKTIF');
					if(vstatus == 'Y'){
						Ext.getCmp('cb_aktif').setValue(true);
					} else {
						Ext.getCmp('cb_aktif').setValue(false);
					}
					Ext.getCmp('tf_no_req').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_NOREQ'));
					Ext.getCmp('cb_tipe').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_TIPE'));
					Ext.getCmp('cb_status_kar').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_STATUS_KARYAWAN'));
					Ext.getCmp('cb_sifat_per').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_SIFAT_PERUBAHAN'));
					Ext.getCmp('tf_lama').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_JUMLAH_BULAN'));
					var vPemohon = grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_KARYAWAN_ID');
					Ext.Ajax.request({
						url:'<?php echo 'isi_gaji.php'; ?>',
							params:{
								nama_pem:vPemohon,
							},
							success:function(response){
								var json=Ext.decode(response.responseText);
								var deskripsiasli = json.results;
								var deskripsisplit = deskripsiasli.split('|');
								Ext.getCmp('tf_gaji').setValue(deskripsisplit[0]);
								Ext.getCmp('tf_gaji_view').setValue(deskripsisplit[1]);
							},
						method:'POST',
					});
					
					//Ext.getCmp('cb_mgr_lama').setValue('');
					//Ext.getCmp('cb_mgr_baru').setValue('');
					Ext.getCmp('cb_mgr_lama').setDisabled(true);
					Ext.getCmp('cb_mgr_baru').setDisabled(true);
					
					comboStatusKar.load();
					comboSifatPer.load();
					
					comboManagerLama.setProxy({
						type:'ajax',
						url:'<?PHP echo PATHAPP ?>/combobox/combobox_manager_mutasi_lama.php?pemohon=' + vPemohon,
						reader: {
							type: 'json',
							root: 'data', 
							totalProperty:'total'   
						}
					});
					Ext.getCmp('cb_mgr_lama').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_MGR_LAMA'));
					comboManagerLama.load();
					Ext.getCmp('cb_karyawan').setValue(vPemohon);
					Ext.getCmp('cb_karyawan').setDisabled(true)
					Ext.getCmp('cb_tipe').setDisabled(true)
					Ext.getCmp('cb_status_kar').setDisabled(false)
					Ext.getCmp('cb_sifat_per').setDisabled(false)
					Ext.getCmp('tf_lama').setDisabled(true)
					if(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_SIFAT_PERUBAHAN') == 'SEMENTARA'){
						Ext.getCmp('tf_lama').setDisabled(false)
					}
					Ext.Ajax.request({
						url:'<?php echo 'isi_dept.php'; ?>',
							params:{
								nama_pem:vPemohon,
							},
							success:function(response){
								var json=Ext.decode(response.responseText);
								var deskripsiasli = json.results;
								var deskripsisplit = deskripsiasli.split('|');
								var nama_dept = deskripsisplit[0];
								Ext.getCmp('tf_dept_id').setValue(deskripsisplit[0]);
								Ext.getCmp('tf_dept').setValue(deskripsisplit[1]);
								Ext.getCmp('tf_posisi_id').setValue(deskripsisplit[2]);
								Ext.getCmp('tf_posisi').setValue(deskripsisplit[3]);
								Ext.getCmp('tf_lokasi_id').setValue(deskripsisplit[4]);
								Ext.getCmp('tf_lokasi').setValue(deskripsisplit[5]);
								Ext.getCmp('tf_grade_id').setValue(deskripsisplit[6]);
								Ext.getCmp('tf_grade').setValue(deskripsisplit[7]);
								if(Ext.getCmp('cb_tipe').getValue() != '' && deskripsisplit[7] != ''){
									// console.log('TES');
									comboGrade.setProxy({
										type:'ajax',
										url:'<?PHP echo PATHAPP ?>/combobox/combobox_grade.php?tipe=' + Ext.getCmp('cb_tipe').getValue() + '&grade=' + deskripsisplit[7],
										reader: {
											type: 'json',
											root: 'data', 
											totalProperty:'total'   
										}
									});
									comboGrade.load();
									Ext.getCmp('cb_grade').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_GRADE'));
								}
							},
						method:'POST',
					});
					var vJobId=grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_DEPT');
					Ext.getCmp('cb_dept').setValue(vJobId);
					comboPosisi.setProxy({
						type:'ajax',
						url:'<?PHP echo PATHAPP ?>/combobox/combobox_posisi.php?jobId=' + vJobId,
						reader: {
							type: 'json',
							root: 'data', 
							totalProperty:'total'   
						}
					});
					comboPosisi.load();
					comboManagerBaru.setProxy({
						type:'ajax',
						url:'<?PHP echo PATHAPP ?>/combobox/combobox_manager_mutasi_baru.php?dept=' + vJobId,
						reader: {
							type: 'json',
							root: 'data', 
							totalProperty:'total'   
						}
					});
					Ext.getCmp('cb_mgr_baru').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_MGR_BARU'));
					comboManagerBaru.load();
					Ext.getCmp('cb_posisi').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_POSISI'));
					Ext.getCmp('cb_lokasi').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_LOKASI'));
					Ext.getCmp('tf_gaji_baru').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_GAJI'));
					Ext.getCmp('tf_alasan').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_ALASAN'));
					Ext.getCmp('tf_keterangan').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_KETERANGAN'));
					Ext.getCmp('tf_tgl').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_TGL'));
					PopupSIK.hide();
				}
			}
		}
	});
	var PopupSIK=Ext.create('Ext.Window', {
		title: 'Cari Mutasi Karyawan',
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
			fieldLabel:'No Mutasi',
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
							Ext.MessageBox.alert('Failed', 'Anda tidak diperbolehkan menambah file jenis TXT atau PHP');
						}
						else{
							frmfile1.getForm().submit({
								url: '<?PHP echo PATHAPP ?>/transaksi/mutasiByHRD/uploadattachment.php', 
								method:'POST', 
								waitTitle:'Connecting', 
								waitMsg:'Sending data...',
								
								success:function(fp, o){ 											
									var sembarang=Ext.decode(o.response.responseText);		
									//console.log(sembarang);
									if(sembarang.results=='sukses'){
										store_upload.setProxy({
											type:'ajax',
											url:'<?PHP echo PATHAPP ?>/transaksi/mutasiByHRD/isi_grid_upload.php?id_temp='+sembarang.id_temp,
											reader: {
												type: 'json',
												root: 'data', 
												totalProperty:'total'   
											}
										});
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
	
	var contentPanel = Ext.create('Ext.panel.Panel',{
			//title:'Form Input Tanda Terima',
			//region:'center',
			bodyStyle: 'spacing: 10px;border:none',
			items:[{
				xtype:'label',
				html:'<div align="center"><font size="5"><b>Form Mutasi Karyawan</b></font></div>',
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
					columnWidth:.48,
					border:false,
					layout: 'anchor',
					defaultType: 'textfield',
					items:[{
						name: 'tf_no_req',
						fieldLabel: 'No. Request',
						width:400,
						labelWidth:100,
						id:'tf_no_req',
						editable: false,
						readOnly:true,
						fieldStyle:'background:#B8B8B8 ;font-weight:bold;'
					}]
				}
				// ,{
					// columnWidth:.2,
					// border:false,
					// layout: 'anchor',
					// defaultType: 'button',
					// items:[{
						// name: 'cari',
						// text: 'Existing',
						// width:75,
						// handler:function(){
							// Ext.getCmp('tf_filter_pop').setValue('');
							// Ext.getCmp('tf_filter_from_pop').setValue(currentTimeNow);
							// Ext.getCmp('tf_filter_to_pop').setValue(currentTimeNow);
							// Ext.getCmp('cb_filter1_pop').setValue('true');
							// Ext.getCmp('cb_filter2_pop').setValue('true');
							// store_popuptransaksi.removeAll();
							// PopupSIK.show();
						// } 
					// }]
				// }
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
						labelWidth:100,
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
						html:'<div style="color:#FF0000">*</div>',
					}]
				},{
					columnWidth:.8,
					border:false,
					layout: 'anchor',
					defaultType: 'combobox',
					items:[{		
						xtype:'combobox',
						fieldLabel:'Tipe',
						store: comboTipe,
						displayField: 'DATA_NAME',
						valueField: 'DATA_VALUE',
						width:250,
						labelWidth:100,
						id:'cb_tipe',
						value:'',
						emptyText : '- Pilih -',
						editable: false,
						enableKeyEvents : true,
						minChars:1,
						listeners: {
							select:function(f,r,i){
								var nama_pem=r[0].data.DATA_VALUE;
								Ext.getCmp('cb_grade').setValue('')
								
								if(Ext.getCmp('cb_tipe').getValue() != '' && Ext.getCmp('tf_grade').getValue() != ''){
									// console.log('TES');
									comboGrade.setProxy({
										type:'ajax',
										url:'<?PHP echo PATHAPP ?>/combobox/combobox_grade.php?tipe=' + Ext.getCmp('cb_tipe').getValue() + '&grade=' + Ext.getCmp('tf_grade').getValue(),
										reader: {
											type: 'json',
											root: 'data', 
											totalProperty:'total'   
										}
									});
									comboGrade.load();
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
						fieldLabel:'Status Karyawan',
						store: comboStatusKar,
						displayField: 'DATA_NAME',
						valueField: 'DATA_VALUE',
						width:250,
						labelWidth:100,
						id:'cb_status_kar',
						value:'TETAP',
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
					columnWidth:.32,
					border:false,
					layout: 'anchor',
					defaultType: 'combobox',
					items:[{		
						xtype:'combobox',
						fieldLabel:'Sifat Perubahan',
						store: comboSifatPer,
						displayField: 'DATA_NAME',
						valueField: 'DATA_VALUE',
						width:250,
						labelWidth:100,
						id:'cb_sifat_per',
						value:'TETAP',
						//emptyText : '- Pilih -',
						editable: false,
						enableKeyEvents : true,
						minChars:1,
						listeners: {
							select:function(f,r,i){
								var value=r[0].data.DATA_VALUE;
								if(value == 'SEMENTARA'){
									Ext.getCmp('tf_lama').setDisabled(false);
								}else{
									Ext.getCmp('tf_lama').setValue(0);
									Ext.getCmp('tf_lama').setDisabled(true);
								}
							}
						}
					}]
				},{
					columnWidth:.13,
					border:false,
					layout: 'anchor',
					defaultType: 'numberfield',
					items:[{		
						xtype:'numberfield',
						fieldLabel:'Lama',
						//store: comboSifatPer,
						//displayField: 'DATA_NAME',
						//valueField: 'DATA_VALUE',
						width:100,
						labelWidth:50,
						id:'tf_lama',
						value:0,
						minValue:0,
						maxValue:36,
						disabled : true,
						//editable: false,
						enableKeyEvents : true,
						//minChars:1,
					}]
				},{
					columnWidth:.1,
					border:false,
					layout: 'anchor',
					defaultType: 'label',
					items:[{		
						xtype:'label',
						html:'<b>Bulan<b/>',
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
						fieldLabel:'Nama Karyawan',
						store: comboKaryawan,
						displayField: 'DATA_NAME',
						valueField: 'DATA_VALUE',
						width:490,
						labelWidth:100,
						id:'cb_karyawan',
						value:'',
						emptyText : '- Pilih -',
						enableKeyEvents : true,
						minChars:1,
						listeners: {
							select:function(f,r,i){
								var nama_pem=r[0].data.DATA_VALUE;
								var nama_pem2=r[0].data.DATA_NAME;
								Ext.getCmp('cb_dept').setDisabled(false)
								Ext.getCmp('cb_mgr_lama').setDisabled(false)
								Ext.getCmp('cb_grade').setDisabled(false)
								Ext.getCmp('cb_grade').setValue('')
								Ext.getCmp('cb_posisi').setDisabled(false)
								Ext.getCmp('cb_lokasi').setDisabled(false)
								Ext.Ajax.request({
									url:'<?php echo 'isi_dept.php'; ?>',
										params:{
											nama_pem:nama_pem,
										},
										success:function(response){
											var json=Ext.decode(response.responseText);
											var deskripsiasli = json.results;
											var deskripsisplit = deskripsiasli.split('|');
											var nama_dept = deskripsisplit[0];
											Ext.getCmp('tf_dept_id').setValue(deskripsisplit[0]);
											Ext.getCmp('tf_dept').setValue(deskripsisplit[1]);
											Ext.getCmp('tf_posisi_id').setValue(deskripsisplit[2]);
											Ext.getCmp('tf_posisi').setValue(deskripsisplit[3]);
											Ext.getCmp('tf_lokasi_id').setValue(deskripsisplit[4]);
											Ext.getCmp('tf_lokasi').setValue(deskripsisplit[5]);
											Ext.getCmp('tf_grade_id').setValue(deskripsisplit[6]);
											Ext.getCmp('tf_grade').setValue(deskripsisplit[7]);
											if(Ext.getCmp('cb_tipe').getValue() != '' && deskripsisplit[7] != ''){
												// console.log('TES');
												comboGrade.setProxy({
													type:'ajax',
													url:'<?PHP echo PATHAPP ?>/combobox/combobox_grade.php?tipe=' + Ext.getCmp('cb_tipe').getValue() + '&grade=' + deskripsisplit[7],
													reader: {
														type: 'json',
														root: 'data', 
														totalProperty:'total'   
													}
												});
												comboGrade.load();
											}
										},
									method:'POST',
								});
								Ext.Ajax.request({
									url:'<?php echo 'isi_gaji.php'; ?>',
										params:{
											nama_pem:nama_pem,
										},
										success:function(response){
											var json=Ext.decode(response.responseText);
											var deskripsiasli = json.results;
											var deskripsisplit = deskripsiasli.split('|');
											Ext.getCmp('tf_gaji').setValue(deskripsisplit[0]);
											Ext.getCmp('tf_gaji_view').setValue(deskripsisplit[1]);
										},
									method:'POST',
								});
								Ext.getCmp('cb_mgr_lama').setValue('');
								comboManagerLama.setProxy({
									type:'ajax',
									url:'<?PHP echo PATHAPP ?>/combobox/combobox_manager_mutasi_lama.php?pemohon=' + nama_pem,
									reader: {
										type: 'json',
										root: 'data', 
										totalProperty:'total'   
									}
								});
								comboManagerLama.load();
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
					defaultType: 'combobox',
					items:[{
						xtype:'textfield',
						fieldLabel:'Departemen',
						width:400,
						labelWidth:100,
						id:'tf_dept',
						value:'',
						readOnly:true,
						fieldStyle:'background:#B8B8B8 ;font-weight:bold;'
					}]
				}]	
			},{
				layout:'column',
				border:false,
				hidden:true,
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
						fieldLabel:'Departemen',
						width:400,
						labelWidth:100,
						id:'tf_dept_id',
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
						fieldLabel:'Manager Lama',
						store: comboManagerLama,
						displayField: 'DATA_NAME',
						valueField: 'DATA_VALUE',
						width:400,
						labelWidth:100,
						id:'cb_mgr_lama',
						value:'',
						emptyText : '- Pilih -',
						editable: false,
						enableKeyEvents : true,
						minChars:1,
					}]
				}]	
			},{
				layout:'column',
				border:false,
				// hidden:true,
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
						fieldLabel:'Grade',
						width:400,
						labelWidth:100,
						id:'tf_grade',
						value:'',
						readOnly:true,
						fieldStyle:'background:#B8B8B8 ;font-weight:bold;'
					}]
				}]	
			},{
				layout:'column',
				border:false,
				hidden:true,
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
						fieldLabel:'Grade',
						width:400,
						labelWidth:100,
						id:'tf_grade_id',
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
						fieldLabel:'Posisi',
						width:400,
						labelWidth:100,
						id:'tf_posisi',
						value:'',
						readOnly:true,
						fieldStyle:'background:#B8B8B8 ;font-weight:bold;'
					}]
				}]	
			},{
				layout:'column',
				border:false,
				hidden:true,
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
						fieldLabel:'Posisi',
						width:400,
						labelWidth:100,
						id:'tf_posisi_id',
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
						fieldLabel:'Lokasi',
						width:400,
						labelWidth:100,
						id:'tf_lokasi',
						value:'',
						readOnly:true,
						fieldStyle:'background:#B8B8B8 ;font-weight:bold;'
					}]
				}]	
			},{
				layout:'column',
				border:false,
				hidden:true,
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
						fieldLabel:'Lokasi',
						width:400,
						labelWidth:100,
						id:'tf_lokasi_id',
						value:'',
						readOnly:true,
						fieldStyle:'background:#B8B8B8 ;font-weight:bold;'
					}]
				}]	
			},{
				layout:'column',
				border:false,
				hidden:true,
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
						fieldLabel:'Gaji',
						width:400,
						labelWidth:100,
						id:'tf_gaji',
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
						fieldLabel:'Gaji',
						width:400,
						labelWidth:100,
						id:'tf_gaji_view',
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
						fieldLabel:'Departemen Baru',
						store: comboDept,
						displayField: 'DATA_NAME',
						valueField: 'DATA_VALUE',
						width:400,
						labelWidth:100,
						id:'cb_dept',
						value:'',
						emptyText : '- Pilih -',
						editable: false,
						enableKeyEvents : true,
						minChars:1,
						listeners: {
							select:function(f,r,i){
								var nama_pem=r[0].data.DATA_VALUE;
								Ext.getCmp('cb_posisi').setValue('')
								comboPosisi.setProxy({
									type:'ajax',
									url:'<?PHP echo PATHAPP ?>/combobox/combobox_posisi.php?jobId=' + nama_pem,
									reader: {
										type: 'json',
										root: 'data', 
										totalProperty:'total'   
									}
								});
								comboPosisi.load();
								Ext.getCmp('cb_mgr_baru').setValue('');
								Ext.getCmp('cb_mgr_baru').setDisabled(false);
								comboManagerBaru.setProxy({
									type:'ajax',
									url:'<?PHP echo PATHAPP ?>/combobox/combobox_manager_mutasi_baru.php?dept=' + nama_pem,
									reader: {
										type: 'json',
										root: 'data', 
										totalProperty:'total'   
									}
								});
								comboManagerBaru.load();
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
						fieldLabel:'Manager Baru',
						store: comboManagerBaru,
						displayField: 'DATA_NAME',
						valueField: 'DATA_VALUE',
						width:400,
						labelWidth:100,
						id:'cb_mgr_baru',
						value:'',
						emptyText : '- Pilih -',
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
						fieldLabel:'Grade Baru',
						store: comboGrade,
						displayField: 'DATA_NAME',
						valueField: 'DATA_VALUE',
						width:400,
						labelWidth:100,
						id:'cb_grade',
						value:'',
						emptyText : '- Pilih -',
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
						fieldLabel:'Posisi Baru',
						store: comboPosisi,
						displayField: 'DATA_NAME',
						valueField: 'DATA_VALUE',
						width:400,
						labelWidth:100,
						id:'cb_posisi',
						value:'',
						emptyText : '- Pilih -',
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
						fieldLabel:'Lokasi Baru',
						store: comboLokasi,
						displayField: 'DATA_NAME',
						valueField: 'DATA_VALUE',
						width:400,
						labelWidth:100,
						id:'cb_lokasi',
						value:'',
						emptyText : '- Pilih -',
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
					defaultType: 'numberfield',
					items:[{
						xtype:'numberfield',
						fieldLabel:'Gaji Baru',
						width:300,
						labelWidth:100,
						id:'tf_gaji_baru',
						value: 0,
						minValue: 0,
						maxValue: 1000000000000,
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
					defaultType: 'textareafield',
					items:[{
						xtype:'textareafield',
						fieldLabel:'Alasan',
						width:490,
						labelWidth:100,
						id:'tf_alasan'
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
					defaultType: 'textareafield',
					items:[{
						xtype:'textareafield',
						fieldLabel:'Keterangan',
						width:490,
						labelWidth:100,
						id:'tf_keterangan'
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
					layout: 'column',
					//defaultType: 'combobox',
					items:[{
						columnWidth:0.7,
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
									columnWidth:0.65,
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
									columnWidth:.15,
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
					defaultType: 'checkbox',
					items:[{
						xtype:'datefield',
						fieldLabel:'Tanggal Efektif',
						width:200,
						labelWidth:100,
						id:'tf_tgl',
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
					columnWidth:.8,
					border:false,
					layout: 'anchor',
					defaultType: 'combobox',
					items:[{		
						xtype:'combobox',
						fieldLabel:'Direksi',
						store: comboDireksi,
						displayField: 'DATA_NAME',
						valueField: 'DATA_VALUE',
						width:400,
						labelWidth:100,
						id:'cb_direksi',
						value:'',
						emptyText : '- Pilih -',
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
					defaultType: 'checkbox',
					items:[{
						// xtype:'checkboxgroup',
						fieldLabel:'Aktif',
						width:490,
						labelWidth:100,
						id: 'cb_aktif', 
						name: 'cb_aktif', 
						inputValue: 'Y', 
						checked: true
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
						var isiUpload = store_upload.count();
						if(isiUpload>0){
							if (Ext.getCmp('cb_tipe').getValue()!='' && Ext.getCmp('cb_karyawan').getValue()!='' && Ext.getCmp('cb_dept').getValue()!='' && Ext.getCmp('cb_mgr_lama').getValue()!='' && Ext.getCmp('cb_mgr_baru').getValue()!='' && Ext.getCmp('cb_posisi').getValue()!='' && Ext.getCmp('cb_lokasi').getValue()!='' && Ext.getCmp('tf_gaji_baru').getValue()!='0' && Ext.getCmp('tf_tgl').getValue()!='' && Ext.getCmp('cb_direksi').getValue()!=''){
								// var posisiSplit = Ext.getCmp('cb_posisi').getValue().split('|');
								// var posisiId = posisiSplit[0];
								// var gradeId = posisiSplit[1];
								
								var i = 0;
								var arrDataFile = new Array();
								store_upload.each(
									function(record) {
										arrDataFile[i]=record.get('DATA_FILE');
										i++;
									}
								);
								
								Ext.Ajax.request({
									url:'<?php echo 'simpan_mutasi.php'; ?>',
									params:{
										hdid:Ext.getCmp('tf_hdid').getValue(),
										typeform:Ext.getCmp('tf_typeform').getValue(),
										noReq:Ext.getCmp('tf_no_req').getValue(),
										pembuat:Ext.getCmp('tf_pembuat').getValue(),
										tipe:Ext.getCmp('cb_tipe').getValue(),	
										statuskar:Ext.getCmp('cb_status_kar').getValue(),	
										sifatperubahan:Ext.getCmp('cb_sifat_per').getValue(),	
										lama:Ext.getCmp('tf_lama').getValue(),	
										karyawan:Ext.getCmp('cb_karyawan').getValue(),
										deptlama:Ext.getCmp('tf_dept_id').getValue(),
										posisilama:Ext.getCmp('tf_posisi_id').getValue(),
										lokasilama:Ext.getCmp('tf_lokasi_id').getValue(),
										gradelama:Ext.getCmp('tf_grade_id').getValue(),
										gajilama:Ext.getCmp('tf_gaji').getValue(),
										dept:Ext.getCmp('cb_dept').getValue(),
										mgrlama:Ext.getCmp('cb_mgr_lama').getValue(),
										mgrbaru:Ext.getCmp('cb_mgr_baru').getValue(),
										posisi:Ext.getCmp('cb_posisi').getValue(),
										grade:Ext.getCmp('cb_grade').getValue(),
										lokasi:Ext.getCmp('cb_lokasi').getValue(),
										gaji:Ext.getCmp('tf_gaji_baru').getValue(),
										alasan:Ext.getCmp('tf_alasan').getValue(),
										keterangan:Ext.getCmp('tf_keterangan').getValue(),
										tanggal:Ext.Date.dateFormat(Ext.getCmp('tf_tgl').getValue(), 'Y-m-d'),
										direksi:Ext.getCmp('cb_direksi').getValue(),
										aktif:Ext.getCmp('cb_aktif').getValue(),
										arrDataFile:Ext.encode(arrDataFile),
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
												url:'<?PHP echo PATHAPP ?>/transaksi/mutasi/autoemailMUTASI.php',
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
											window.open("isi_pdf_mutasi_dwp.php?hdid=" + transId + "");
											Ext.clearForm();
										} else {
											alertDialog('Kesalahan', "Data gagal disimpan. ");
										} 
									},
									failure:function(error){
										alertDialog('Kesalahan','Data gagal disimpan');
									}
								});
							} else {
								alertDialog('Kesalahan','Tipe, Nama Karyawan, Departemen baru, Posisi baru, Lokasi baru, Gaji baru, Tanggal belum dipilih.');
							}
						}else{
							alertDialog('Kesalahan','Blm ada file yg di attachment.');
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