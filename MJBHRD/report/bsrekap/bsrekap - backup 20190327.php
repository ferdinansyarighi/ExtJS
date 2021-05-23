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
		Ext.getCmp('tf_tgl_from').setValue(currentTime)
		Ext.getCmp('tf_tgl_to').setValue(currentTime)
		Ext.getCmp('tf_tgl_jt_from').setValue('')
		Ext.getCmp('tf_tgl_jt_to').setValue('')
		Ext.getCmp('cb_namaKaryawan').setValue('')
		Ext.getCmp('cb_nodok').setValue('')
		Ext.getCmp('cb_tipe').setValue('- Pilih -')
		Ext.getCmp('cb_pos_doc').setValue('- Pilih -')
		Ext.getCmp('tf_perusahaan').setValue('')
		Ext.getCmp('tf_dept').setValue('')
		Ext.getCmp('tf_plant').setValue('')
		Ext.getCmp('cb_status').setValue('- Pilih -')
		Ext.getCmp('rb_bs').setValue(true)
		Ext.getCmp('rb_nol').setValue(false)
		store_detil.removeAll();
		store_detil_nol.removeAll();
		store_detil.load({
			params:{
				tglfrom:Ext.getCmp('tf_tgl_from').getRawValue(),
				tglto:Ext.getCmp('tf_tgl_to').getRawValue(),
				tgljtfrom:Ext.getCmp('tf_tgl_jt_from').getRawValue(),
				tgljtto:Ext.getCmp('tf_tgl_jt_to').getRawValue(),
				pemohon:Ext.getCmp('cb_namaKaryawan').getValue(),
				noBS:Ext.getCmp('cb_nodok').getRawValue(),
				tipeBS:Ext.getCmp('cb_tipe').getValue(),
				posDoc:Ext.getCmp('cb_pos_doc').getValue(),
				perusahaan:Ext.getCmp('tf_perusahaan').getValue(),
				dept:Ext.getCmp('tf_dept').getValue(),
				plant:Ext.getCmp('tf_plant').getValue(),
				status:Ext.getCmp('cb_status').getValue(),
			}
		});
		comboIjin.removeAll();
		comboIjin.load();
		grid_detil.show();
		grid_detil2.hide();
	};
	
	var comboPemohon = Ext.create('Ext.data.Store', {
		fields: [{
			name:'DATA_VALUE'
		},{
			name:'DATA_NAME'
		}],
		proxy:{
			type:'ajax',
			url:'<?PHP echo PATHAPP ?>/combobox/combobox_pemohon_dept_id.php', 
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
			url:'<?PHP echo PATHAPP ?>/combobox/combobox_perusahaan_id.php', 
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
			url:'<?PHP echo PATHAPP ?>/combobox/combobox_lokasi.php', 
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
			url:'<?PHP echo PATHAPP ?>/combobox/combobox_nobs.php', 
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
	var comboTipeBS = Ext.create('Ext.data.Store', {
		fields: ['DATA_NAME', 'DATA_VALUE'],
		data : [
			{"DATA_NAME":"- Pilih -", "DATA_VALUE":"- Pilih -"},
			{"DATA_NAME":"SPPD", "DATA_VALUE":"SPPD"},
			{"DATA_NAME":"OPERASIONAL", "DATA_VALUE":"OPERASIONAL"},
			{"DATA_NAME":"OPERASIONAL KHUSUS", "DATA_VALUE":"OPERASIONAL KHUSUS"}
		]
	});
	var comboPosDoc = Ext.create('Ext.data.Store', {
		fields: ['DATA_NAME', 'DATA_VALUE'],
		data : [
			{"DATA_NAME":"- Pilih -", "DATA_VALUE":"- Pilih -"},
			{"DATA_NAME":"Pengajuan Karyawan", "DATA_VALUE":"0"},
			{"DATA_NAME":"Pengajuan HRD", "DATA_VALUE":"1"},
			{"DATA_NAME":"APP Penanggung Jawab / MGR Department", "DATA_VALUE":"2"},
			{"DATA_NAME":"App Checker", "DATA_VALUE":"3"},
			{"DATA_NAME":"App MGR HRD", "DATA_VALUE":"4"},
			{"DATA_NAME":"App MGR FIN", "DATA_VALUE":"5"},
			{"DATA_NAME":"Validasi Kasir", "DATA_VALUE":"6"}
		]
	});
	var comboStatus = Ext.create('Ext.data.Store', {
		fields: ['DATA_NAME', 'DATA_VALUE'],
		data : [
			{"DATA_NAME":"- Pilih -", "DATA_VALUE":"- Pilih -"},
			{"DATA_NAME":"APPROVED", "DATA_VALUE":"APPROVED"},
			{"DATA_NAME":"DISAPPROVED", "DATA_VALUE":"DISAPPROVED"},
			{"DATA_NAME":"PROCESS", "DATA_VALUE":"PROCESS"},
			{"DATA_NAME":"VALIDATED", "DATA_VALUE":"VALIDATED"},
			{"DATA_NAME":"CLOSE", "DATA_VALUE":"CLOSE"}
		]
	});
	var store_detil=new Ext.data.JsonStore({
		id:'store_cari_id',
		pageSize: 100,
		model: 'Plant',
		fields:[{
			name:'HD_ID'
		},{
			name:'DATA_NO_BS'
		},{
			name:'DATA_PEMOHON'
		},{
			name:'DATA_PERUSAHAAN'
		},{
			name:'DATA_DEPT'
		},{
			name:'DATA_JABATAN'
		},{
			name:'DATA_PLANT'
		},{
			name:'DATA_GRADE'
		},{
			name:'DATA_NOMINAL'
		},{
			name:'DATA_KETERANGAN'
		},{
			name:'DATA_ATTACHMENT'
		},{
			name:'DATA_STATUS'
		},{
			name:'DATA_APP_TERAKHIR'
		},{
			name:'DATA_TGL_TERAKHIR'
		},{
			name:'DATA_NEXT_APP'
		},{
			name:'DATA_KET_DISAPP'
		},{
			name:'DATA_TIPE_BS'
		},{
			name:'DATA_TANGGAL_PENCAIRAN'
		},{
			name:'DATA_TANGGAL_CLOSE'
		},{
			name:'DATA_NOMINAL_OUTSTANDING'
		},{
			name:'DATA_TANGGAL_JT'
		},{
			name:'DATA_PERUSAHAAN_BS'
		},{
			name:'DATA_ATTACHMENT_HR'
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
	
	var store_detil_nol=new Ext.data.JsonStore({
		id:'store_cari_id',
		pageSize: 100,
		model: 'Plant',
		fields:[{
			name:'HD_ID'
		},{
			name:'DATA_NO_BS'
		},{
			name:'DATA_PEMOHON'
		},{
			name:'DATA_PERUSAHAAN'
		},{
			name:'DATA_DEPT'
		},{
			name:'DATA_JABATAN'
		},{
			name:'DATA_PLANT'
		},{
			name:'DATA_GRADE'
		},{
			name:'DATA_NOMINAL'
		},{
			name:'DATA_KETERANGAN'
		},{
			name:'DATA_ATTACHMENT'
		},{
			name:'DATA_STATUS'
		},{
			name:'DATA_APP_TERAKHIR'
		},{
			name:'DATA_TGL_TERAKHIR'
		},{
			name:'DATA_NEXT_APP'
		},{
			name:'DATA_KET_DISAPP'
		},{
			name:'DATA_TIPE_BS'
		},{
			name:'DATA_TANGGAL_PENCAIRAN'
		},{
			name:'DATA_TANGGAL_CLOSE'
		},{
			name:'DATA_NOMINAL_OUTSTANDING'
		},{
			name:'DATA_TANGGAL_JT'
		},{
			name:'DATA_PERUSAHAAN_BS'
		},{
			name:'DATA_ATTACHMENT_HR'
		},{
			name:'DATA_NO_CM'
		},{
			name:'DATA_KET_NOL'
		},{
			name:'DATA_TIPE_NOL'
		},{
			name:'DATA_NOMINAL_NOL'
		},{
			name:'DATA_BANK_PENERIMA'
		},{
			name:'DATA_TGL_NOL'
		},{
			name:'DATA_TGL_ACTION_NOL'
		}],
		proxy:{
			type:'ajax',
			url:'isi_grid_pengenolan.php', 
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
        title: 'Data Bon Sementaara (BS)',
        frame: false,
		//selModel:sm,
        loadMask: true,
        columns: [{
			dataIndex:'HD_ID',
			header:'No.',
			hidden:true,
			width:40,
		},{
			xtype:'rownumberer',
			id:'row_id',
			header:'No',
			width:40,
		},{
			dataIndex:'DATA_NO_BS',
			header:'Nomor BS',
			//hidden:true,
			width:180,
		},{
			dataIndex:'DATA_PEMOHON',
			header:'Pemohon',
			//hidden:true,
			width:150,
		},{
			dataIndex:'DATA_PERUSAHAAN',
			header:'Perusahaan',
			//hidden:true,
			width:150,
		},{
			dataIndex:'DATA_PERUSAHAAN_BS',
			header:'Perusahaan BS',
			//hidden:true,
			width:150,
		},{
			dataIndex:'DATA_DEPT',
			header:'Departemen',
			//hidden:true,
			width:150,
		},{
			dataIndex:'DATA_JABATAN',
			header:'Jabatan',
			//hidden:true,
			width:75,
		},{
			dataIndex:'DATA_GRADE',
			header:'Grade',
			//hidden:true,
			width:75,
		},{
			dataIndex:'DATA_TIPE_BS',
			header:'Tipe BS',
			//hidden:true,
			width:75,
		},{
			dataIndex:'DATA_PLANT',
			header:'Location',
			//hidden:true,
			width:75,
		},{
			dataIndex:'DATA_NOMINAL',
			header:'Nominal',
			align:'right',
			//hidden:true,
			width:125,
		},{
			dataIndex:'DATA_NOMINAL_OUTSTANDING',
			header:'Nominal Outstanding',
			align:'right',
			//hidden:true,
			width:125,
		},{
			dataIndex:'DATA_TANGGAL_JT',
			header:'Tanggal Jatuh Tempo',
			//hidden:true,
			width:150,
		},{
			dataIndex:'DATA_TANGGAL_PENCAIRAN',
			header:'Tanggal Pencairan',
			//hidden:true,
			width:100,
		},{
			dataIndex:'DATA_TANGGAL_CLOSE',
			header:'Tanggal Close',
			//hidden:true,
			width:100,
		},{
			dataIndex:'DATA_KETERANGAN',
			header:'Keterangan',
			//hidden:true,
			width:150,
		},{
			dataIndex:'DATA_ATTACHMENT',
			header:'Attachment',
			//hidden:true,
			width:150,
		},{
			dataIndex:'DATA_ATTACHMENT_HR',
			header:'Attachment HRD',
			//hidden:true,
			width:150,
		},{
			dataIndex:'DATA_STATUS',
			header:'Status',
			//hidden:true,
			width:75,
		},{
			dataIndex:'DATA_APP_TERAKHIR',
			header:'Approval Terakhir',
			//hidden:true,
			width:150,
		},{
			dataIndex:'DATA_TGL_TERAKHIR',
			header:'Update Date',
			//hidden:true,
			width:150,
		},{
			dataIndex:'DATA_NEXT_APP',
			header:'Approval Selanjutnya',
			//hidden:true,
			width:150,
		},{
			dataIndex:'DATA_KET_DISAPP',
			header:'Ket. Appr/Disappr',
			//hidden:true,
			width:150,
		}], 
		listeners: {
			dblclick: {
				element: 'body', //bind to the underlying body property on the panel
				fn: function(){
					var hdidPinjam=grid_detil.getSelectionModel().getSelection()[0].get('HD_ID');
					window.open("../../transaksi/bsvalidasi/isi_pdf_bs.php?hdid=" + hdidPinjam + '&hrd_id=' + '');
				}
			}
		}
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
	
	var grid_detil2 = Ext.create('Ext.grid.Panel', {
		id:'grid_detil_pengenolan_id',
        store: store_detil_nol,
		autoScroll:true,
        columnLines: true,
        width: 850,
        height: 300,
        title: 'Data Pengenolan Bon Sementaara (BS)',
        frame: false,
		//selModel:sm,
        loadMask: true,
        columns: [{
			dataIndex:'HD_ID',
			header:'No.',
			hidden:true,
			width:40,
		},{
			xtype:'rownumberer',
			id:'row_id',
			header:'No',
			width:40,
		},{
			dataIndex:'DATA_NO_BS',
			header:'Nomor BS',
			//hidden:true,
			width:180,
		},{
			dataIndex:'DATA_PEMOHON',
			header:'Pemohon',
			//hidden:true,
			width:150,
		},{
			dataIndex:'DATA_PERUSAHAAN',
			header:'Perusahaan',
			//hidden:true,
			width:150,
		},{
			dataIndex:'DATA_PERUSAHAAN_BS',
			header:'Perusahaan BS',
			//hidden:true,
			width:150,
		},{
			dataIndex:'DATA_DEPT',
			header:'Departemen',
			//hidden:true,
			width:150,
		},{
			dataIndex:'DATA_JABATAN',
			header:'Jabatan',
			//hidden:true,
			width:75,
		},{
			dataIndex:'DATA_GRADE',
			header:'Grade',
			//hidden:true,
			width:75,
		},{
			dataIndex:'DATA_PLANT',
			header:'Location',
			//hidden:true,
			width:75,
		},{
			dataIndex:'DATA_TIPE_BS',
			header:'Tipe BS',
			//hidden:true,
			width:75,
		},{
			dataIndex:'DATA_TANGGAL_JT',
			header:'Tanggal Jatuh Tempo',
			align:'center',
			//hidden:true,
			width:150,
		},{
			dataIndex:'DATA_TANGGAL_PENCAIRAN',
			header:'Tanggal Pencairan',
			align:'center',
			//hidden:true,
			width:100,
		},{
			dataIndex:'DATA_TANGGAL_CLOSE',
			header:'Tanggal Close',
			align:'center',
			//hidden:true,
			width:100,
		},{
			dataIndex:'DATA_STATUS',
			header:'Status',
			//hidden:true,
			width:75,
		},{
			dataIndex:'DATA_KETERANGAN',
			header:'Keterangan',
			//hidden:true,
			width:150,
		},{
			dataIndex:'DATA_NOMINAL',
			header:'Nominal BS',
			align:'right',
			//hidden:true,
			width:125,
		},{
			dataIndex:'DATA_NOMINAL_OUTSTANDING',
			header:'Nominal Outstanding',
			align:'right',
			//hidden:true,
			width:125,
		},{
			dataIndex:'DATA_NO_CM',
			header:'Nomor CM',
			align:'right',
			//hidden:true,
			width:120,
		},{
			dataIndex:'DATA_KET_NOL',
			header:'Keterangan Pengenolan',
			//hidden:true,
			width:150,
		},{
			dataIndex:'DATA_TIPE_NOL',
			header:'Tipe Pengenolan',
			//hidden:true,
			width:120,
		},{
			dataIndex:'DATA_NOMINAL_NOL',
			header:'Nominal Pengenolan',
			align:'right',
			//hidden:true,
			width:125,
		},{
			dataIndex:'DATA_BANK_PENERIMA',
			header:'Account Bank Penerima',
			//hidden:true,
			width:150,
		},{
			dataIndex:'DATA_TGL_NOL',
			header:'Tgl Pengenolan',
			align:'center',
			//hidden:true,
			width:125,
		},{
			dataIndex:'DATA_TGL_ACTION_NOL',
			header:'Tgl Action',
			align:'center',
			//hidden:true,
			width:125,
		}], 
		/* listeners: {
			dblclick: {
				element: 'body', //bind to the underlying body property on the panel
				fn: function(){
					var hdidPinjam=grid_detil.getSelectionModel().getSelection()[0].get('HD_ID');
					window.open("../../transaksi/bsvalidasi/isi_pdf_bs.php?hdid=" + hdidPinjam + '&hrd_id=' + '');
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
				html:'<div align="center"><font size="5"><b>Rekap Bon Sementara (BS)</b></font></div>',
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
								{boxLabel: 'Rekap Bon Sementara', id : 'rb_bs', name: 'rb_kategori', inputValue: 1, checked: true},
								{boxLabel: 'Rekap Pengenolan BS', id : 'rb_nol', name: 'rb_kategori', inputValue: 2},
							],
							listeners: {
								change:function(){
									if (Ext.getCmp('rb_bs').getValue()){
										//kategoriForm = "Slip";
										Ext.getCmp('btn_pdf').show();
										grid_detil.show();
										grid_detil2.hide();
										maskgrid = new Ext.LoadMask(Ext.getCmp('grid_detil_id'), {msg: "Memuat . . ."});
										maskgrid.show();
										store_detil.load({
											params:{
												tglfrom:Ext.getCmp('tf_tgl_from').getRawValue(),
												tglto:Ext.getCmp('tf_tgl_to').getRawValue(),
												tgljtfrom:Ext.getCmp('tf_tgl_jt_from').getRawValue(),
												tgljtto:Ext.getCmp('tf_tgl_jt_to').getRawValue(),
												pemohon:Ext.getCmp('cb_namaKaryawan').getValue(),
												noBS:Ext.getCmp('cb_nodok').getRawValue(),
												tipeBS:Ext.getCmp('cb_tipe').getValue(),
												posDoc:Ext.getCmp('cb_pos_doc').getValue(),
												perusahaan:Ext.getCmp('tf_perusahaan').getValue(),
												dept:Ext.getCmp('tf_dept').getValue(),
												plant:Ext.getCmp('tf_plant').getValue(),
												status:Ext.getCmp('cb_status').getValue(),
											}
										});
									} else {
										Ext.getCmp('btn_pdf').hide();
										grid_detil.hide();
										grid_detil2.show();
										maskgrid = new Ext.LoadMask(Ext.getCmp('grid_detil_pengenolan_id'), {msg: "Memuat . . ."});
										maskgrid.show();
										store_detil_nol.load({
											params:{
												tglfrom:Ext.getCmp('tf_tgl_from').getRawValue(),
												tglto:Ext.getCmp('tf_tgl_to').getRawValue(),
												tgljtfrom:Ext.getCmp('tf_tgl_jt_from').getRawValue(),
												tgljtto:Ext.getCmp('tf_tgl_jt_to').getRawValue(),
												pemohon:Ext.getCmp('cb_namaKaryawan').getValue(),
												noBS:Ext.getCmp('cb_nodok').getRawValue(),
												tipeBS:Ext.getCmp('cb_tipe').getValue(),
												posDoc:Ext.getCmp('cb_pos_doc').getValue(),
												perusahaan:Ext.getCmp('tf_perusahaan').getValue(),
												dept:Ext.getCmp('tf_dept').getValue(),
												plant:Ext.getCmp('tf_plant').getValue(),
												status:Ext.getCmp('cb_status').getValue(),
											}
										});
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
						html:'',
					}]
				},{
					columnWidth:.24,
					border:false,
					layout: 'anchor',
					defaultType: 'combobox',
					items:[{
						xtype:'datefield',
						fieldLabel:'Periode',
						width:200,
						labelWidth:100,
						id:'tf_tgl_from',
						editable: false
					}]
				},{
					columnWidth:.25,
					border:false,
					layout: 'anchor',
					defaultType: 'combobox',
					items:[{
						xtype:'datefield',
						fieldLabel:'s/d',
						width:135,
						labelWidth:25,
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
						html:'',
					}]
				},{
					columnWidth:.24,
					border:false,
					layout: 'anchor',
					defaultType: 'combobox',
					items:[{
						xtype:'datefield',
						fieldLabel:'Tgl Jatuh Tempo',
						width:200,
						labelWidth:100,
						id:'tf_tgl_jt_from',
						editable: true
					}]
				},{
					columnWidth:.25,
					border:false,
					layout: 'anchor',
					defaultType: 'combobox',
					items:[{
						xtype:'datefield',
						fieldLabel:'s/d',
						width:135,
						labelWidth:25,
						id:'tf_tgl_jt_to',
						editable: true
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
						fieldLabel:'Pemohon',
						store: comboPemohon,
						displayField: 'DATA_NAME',
						valueField: 'DATA_VALUE',
						width:340,
						labelWidth:100,
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
						fieldLabel:'No BS',
						store: comboIjin,
						displayField: 'DATA_NAME',
						valueField: 'DATA_VALUE',
						width:340,
						labelWidth:100,
						id:'cb_nodok',
						value:'',
						emptyText : '- Pilih -',
						//editable: false 
						enableKeyEvents : true,
						minChars:1,
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
						fieldLabel:'Tipe BS',
						id:'cb_tipe',
						store: comboTipeBS,
						displayField: 'DATA_NAME',
						valueField: 'DATA_VALUE',
						value:'- Pilih -',
						minChars:1,
						editable:false,
						width:340,
						labelWidth:100,
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
					defaultType: 'textfield',
					items:[{		
						xtype:'combobox',
						fieldLabel:'Perusahaan BS',
						width:340,
						labelWidth:100,
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
						labelWidth:100,
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
						fieldLabel:'Plant',
						width:340,
						labelWidth:100,
						id:'tf_plant',
						store: comboPlant,
						displayField: 'DATA_NAME',
						valueField: 'DATA_VALUE',
						minChars:2,
						emptyText : '- Pilih -',
						//value:'<?PHP echo $loc_name?>',
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
						store: comboStatus,
						displayField: 'DATA_NAME',
						valueField: 'DATA_VALUE',
						value:'- Pilih -',
						minChars:1,
						editable:false,
						width:340,
						labelWidth:100,
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
						fieldLabel:'Posisi Document',
						id:'cb_pos_doc',
						store: comboPosDoc,
						displayField: 'DATA_NAME',
						valueField: 'DATA_VALUE',
						value:'- Pilih -',
						minChars:1,
						editable:false,
						width:340,
						labelWidth:100,
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
						text: 'Filter',
						width:75,
						handler:function(){
							if (Ext.getCmp('rb_bs').getValue()){
								maskgrid = new Ext.LoadMask(Ext.getCmp('grid_detil_id'), {msg: "Memuat . . ."});
								maskgrid.show();
								store_detil.load({
									params:{
										tglfrom:Ext.getCmp('tf_tgl_from').getRawValue(),
										tglto:Ext.getCmp('tf_tgl_to').getRawValue(),
										tgljtfrom:Ext.getCmp('tf_tgl_jt_from').getRawValue(),
										tgljtto:Ext.getCmp('tf_tgl_jt_to').getRawValue(),
										pemohon:Ext.getCmp('cb_namaKaryawan').getValue(),
										noBS:Ext.getCmp('cb_nodok').getRawValue(),
										tipeBS:Ext.getCmp('cb_tipe').getValue(),
										posDoc:Ext.getCmp('cb_pos_doc').getValue(),
										perusahaan:Ext.getCmp('tf_perusahaan').getValue(),
										dept:Ext.getCmp('tf_dept').getValue(),
										plant:Ext.getCmp('tf_plant').getValue(),
										status:Ext.getCmp('cb_status').getValue(),
									}
								});
							}else{
								maskgrid = new Ext.LoadMask(Ext.getCmp('grid_detil_pengenolan_id'), {msg: "Memuat . . ."});
								maskgrid.show();
								store_detil_nol.load({
									params:{
										tglfrom:Ext.getCmp('tf_tgl_from').getRawValue(),
										tglto:Ext.getCmp('tf_tgl_to').getRawValue(),
										tgljtfrom:Ext.getCmp('tf_tgl_jt_from').getRawValue(),
										tgljtto:Ext.getCmp('tf_tgl_jt_to').getRawValue(),
										pemohon:Ext.getCmp('cb_namaKaryawan').getValue(),
										noBS:Ext.getCmp('cb_nodok').getRawValue(),
										tipeBS:Ext.getCmp('cb_tipe').getValue(),
										posDoc:Ext.getCmp('cb_pos_doc').getValue(),
										perusahaan:Ext.getCmp('tf_perusahaan').getValue(),
										dept:Ext.getCmp('tf_dept').getValue(),
										plant:Ext.getCmp('tf_plant').getValue(),
										status:Ext.getCmp('cb_status').getValue(),
									}
								});
							}
						} 
					}]
				},{
					columnWidth:.1,
					border:false,
					layout: 'anchor',
					defaultType: 'button',
					items:[{
						name: 'pdf',
						id: 'btn_pdf',
						text: 'To PDF',
						width:75,
						handler:function(){
							var nobs = Ext.getCmp('cb_nodok').getValue()
							if(nobs != ''){
								window.open("../../transaksi/bsvalidasi/isi_pdf_bs.php?hdid=" + nobs + '&hrd_id=' + '');
							}else{
								alertDialog('Kesalahan', 'Nomor BS belum diisi.');
							}
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
							var tgl_from = Ext.getCmp('tf_tgl_from').getRawValue();
							var tgl_to = Ext.getCmp('tf_tgl_to').getRawValue();
							var tgl_jt_from = Ext.getCmp('tf_tgl_jt_from').getRawValue();
							var tgl_jt_to = Ext.getCmp('tf_tgl_jt_to').getRawValue();
							var pemohon = Ext.getCmp('cb_namaKaryawan').getValue();
							var nobs = Ext.getCmp('cb_nodok').getRawValue();
							var tipebs = Ext.getCmp('cb_tipe').getValue();
							var posDoc = Ext.getCmp('cb_pos_doc').getValue();
							var perusahaan = Ext.getCmp('tf_perusahaan').getValue();
							var dept = Ext.getCmp('tf_dept').getValue();
							var plant = Ext.getCmp('tf_plant').getValue();
							var status = Ext.getCmp('cb_status').getValue();
							if (Ext.getCmp('rb_bs').getValue()){
								window.open("isi_excel_rekapbs.php?tglfrom=" + tgl_from + "&tglto=" + tgl_to + "&tgljtfrom=" + tgl_jt_from + "&tgljtto=" + tgl_jt_to + "&pemohon=" + pemohon + "&noBS=" + nobs + "&tipeBS=" + tipebs + "&posDoc=" + posDoc + "&perusahaan="+ perusahaan + "&dept="+ dept + "&plant="+ plant + "&status="+ status);
							}else{
								window.open("isi_excel_rekapbs_nol.php?tglfrom=" + tgl_from + "&tglto=" + tgl_to + "&tgljtfrom=" + tgl_jt_from + "&tgljtto=" + tgl_jt_to + "&pemohon=" + pemohon + "&noBS=" + nobs + "&tipeBS=" + tipebs + "&posDoc=" + posDoc + "&perusahaan="+ perusahaan + "&dept="+ dept + "&plant="+ plant + "&status="+ status);
							}
						} 
					}]
				},{
					columnWidth:.2,
					border:false,
					layout: 'anchor',
					defaultType: 'button',
					items:[{
						name: 'clear',
						text: 'Clear',
						width:75,
						handler:function(){
							Ext.clearForm();
						} 
					}]
				}]	
			},{
				xtype:'label',
				html:'&nbsp',
			}, grid_detil
			, grid_detil2 
			,{
				xtype:'label',
				html:'&nbsp',
			}],
		});	
   contentPanel.render('page');
   Ext.getCmp('tf_tgl_from').setValue(currentTime);
   Ext.getCmp('tf_tgl_to').setValue(currentTime);
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