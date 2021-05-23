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
	var comboSetuju = Ext.create('Ext.data.Store', {
		fields: ['DATA_NAME', 'DATA_VALUE'],
		data : [
			{"DATA_NAME":"Setuju", "DATA_VALUE":"Setuju"},
			{"DATA_NAME":"Tidak Setuju", "DATA_VALUE":"Tidak Setuju"}
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
			url:'<?PHP echo PATHAPP ?>/combobox/combobox_pemohon_id.php', 
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
	Ext.clearForm=function(){
		Ext.getCmp('tf_hdid').setValue('')
		Ext.getCmp('tf_typeform').setValue('tambah')
		Ext.getCmp('tf_no_req').setValue('')
		Ext.getCmp('tf_tipe').setValue('')
		Ext.getCmp('tf_status_kar').setValue('')
		Ext.getCmp('tf_sifat_perubahan').setValue('')
		Ext.getCmp('tf_lama').setValue('')
		Ext.getCmp('tf_pembuat').setValue('')
		Ext.getCmp('tf_karyawan').setValue('')
		Ext.getCmp('tf_dept').setValue('')
		Ext.getCmp('tf_mgr_lama').setValue('')
		Ext.getCmp('tf_grade').setValue('')
		Ext.getCmp('tf_posisi').setValue('')
		Ext.getCmp('tf_lokasi').setValue('')
		Ext.getCmp('tf_gaji_baru').setValue('0.00')
		Ext.getCmp('tf_gaji').setValue('0.00')
		Ext.getCmp('tf_dept_baru').setValue('')
		Ext.getCmp('tf_mgr_baru').setValue('')
		Ext.getCmp('tf_grade_baru').setValue('')
		Ext.getCmp('tf_posisi_baru').setValue('')
		Ext.getCmp('tf_lokasi_baru').setValue('')
		Ext.getCmp('tf_keterangan').setValue('')
		Ext.getCmp('tf_alasan').setValue('')
		Ext.getCmp('cb_setuju').setValue('')
		Ext.getCmp('tf_note').setValue('')
		Ext.getCmp('tf_tgl').setValue('')
		comboDept.load()
		comboPosisi.load()
		comboLokasi.load()
		comboKaryawan.load()
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
			name:'DATA_KARYAWAN'
		},{
			name:'DATA_PEMBUAT'
		},{
			name:'DATA_DEPT_LAMA'
		},{
			name:'DATA_POSISI_LAMA'
		},{
			name:'DATA_LOKASI_LAMA'
		},{
			name:'DATA_GAJI_LAMA'
		},{
			name:'DATA_DEPT'
		},{
			name:'DATA_POSISI'
		},{
			name:'DATA_LOKASI'
		},{
			name:'DATA_GAJI'
		},{
			name:'DATA_KETERANGAN'
		},{
			name:'DATA_ALASAN'
		},{
			name:'DATA_TGL'
		},{
			name:'DATA_GRADE'
		},{
			name:'DATA_GRADE_LAMA'
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
			dataIndex:'DATA_DEPT_LAMA',
			header:'Dept',
			width:100,
			hidden:true
		},{
			dataIndex:'DATA_POSISI_LAMA',
			header:'Posisi',
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
			dataIndex:'DATA_PEMBUAT',
			header:'Pembuat',
			width:150,
			hidden:true
		},{
			dataIndex:'DATA_TGL',
			header:'Tgl',
			width:150,
			hidden:true
		},{
			dataIndex:'DATA_GRADE_LAMA',
			header:'Grade',
			width:150,
			hidden:true
		},{
			dataIndex:'DATA_GRADE',
			header:'Grade',
			width:150,
			hidden:true
		},{
			dataIndex:'DATA_MGR_LAMA',
			header:'Mgr Lama',
			width:150,
			hidden:true
		},{
			dataIndex:'DATA_MGR_BARU',
			header:'Mgr Baru',
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
				if (col==24) {
					var hdid=grid_popuptransaksi.getSelectionModel().getSelection()[0].get('HD_ID');
					Ext.getCmp('tf_hdid').setValue(hdid);
					Ext.getCmp('tf_typeform').setValue('edit');
					Ext.getCmp('tf_no_req').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_NOREQ'));
					Ext.getCmp('tf_pembuat').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_PEMBUAT'));
					Ext.getCmp('tf_tipe').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_TIPE'));
					Ext.getCmp('tf_status_kar').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_STATUS_KARYAWAN'));
					Ext.getCmp('tf_sifat_perubahan').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_SIFAT_PERUBAHAN'));
					Ext.getCmp('tf_lama').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_JUMLAH_BULAN'));
					Ext.getCmp('tf_karyawan').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_KARYAWAN'));
					Ext.getCmp('tf_dept').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_DEPT_LAMA'));
					Ext.getCmp('tf_mgr_lama').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_MGR_LAMA'));
					Ext.getCmp('tf_grade').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_GRADE_LAMA'));
					Ext.getCmp('tf_posisi').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_POSISI_LAMA'));
					Ext.getCmp('tf_lokasi').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_LOKASI_LAMA'));
					Ext.getCmp('tf_dept_baru').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_DEPT'));
					Ext.getCmp('tf_mgr_baru').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_MGR_BARU'));
					Ext.getCmp('tf_grade_baru').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_GRADE'));
					Ext.getCmp('tf_posisi_baru').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_POSISI'));
					Ext.getCmp('tf_lokasi_baru').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_LOKASI'));
					Ext.getCmp('tf_gaji').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_GAJI_LAMA'));
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
					Ext.getCmp('tf_no_req').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_NOREQ'));
					Ext.getCmp('tf_pembuat').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_PEMBUAT'));
					Ext.getCmp('tf_tipe').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_TIPE'));
					Ext.getCmp('tf_status_kar').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_STATUS_KARYAWAN'));
					Ext.getCmp('tf_sifat_perubahan').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_SIFAT_PERUBAHAN'));
					Ext.getCmp('tf_lama').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_JUMLAH_BULAN'));
					Ext.getCmp('tf_karyawan').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_KARYAWAN'));
					Ext.getCmp('tf_dept').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_DEPT_LAMA'));
					Ext.getCmp('tf_mgr_lama').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_MGR_LAMA'));
					Ext.getCmp('tf_grade').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_GRADE_LAMA'));
					Ext.getCmp('tf_posisi').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_POSISI_LAMA'));
					Ext.getCmp('tf_lokasi').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_LOKASI_LAMA'));
					Ext.getCmp('tf_dept_baru').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_DEPT'));
					Ext.getCmp('tf_mgr_baru').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_MGR_BARU'));
					Ext.getCmp('tf_grade_baru').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_GRADE'));
					Ext.getCmp('tf_posisi_baru').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_POSISI'));
					Ext.getCmp('tf_lokasi_baru').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_LOKASI'));
					Ext.getCmp('tf_gaji').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_GAJI_LAMA'));
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
	var contentPanel = Ext.create('Ext.panel.Panel',{
			//title:'Form Input Tanda Terima',
			//region:'center',
			bodyStyle: 'spacing: 10px;border:none',
			items:[{
				xtype:'label',
				html:'<div align="center"><font size="5"><b>Form Persetujuan MGR Mutasi Karyawan</b></font></div>',
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
				},{
					columnWidth:.2,
					border:false,
					layout: 'anchor',
					defaultType: 'button',
					items:[{
						name: 'cari',
						text: 'Search',
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
						labelWidth:100,
						id:'tf_pembuat',
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
						fieldLabel:'Tipe',
						width:250,
						labelWidth:100,
						id:'tf_tipe',
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
						fieldLabel:'Status Karyawan',
						//store: comboStatusKar,
						//displayField: 'DATA_NAME',
						//valueField: 'DATA_VALUE',
						width:250,
						labelWidth:100,
						id:'tf_status_kar',
						//value:'TETAP',
						//emptyText : '- Pilih -',
						editable: false,
						enableKeyEvents : true,
						//minChars:1,
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
					columnWidth:.32,
					border:false,
					layout: 'anchor',
					defaultType: 'combobox',
					items:[{
						xtype:'textfield',
						fieldLabel:'Sifat Perubahan',
						width:250,
						labelWidth:100,
						id:'tf_sifat_perubahan',
						value:'',
						readOnly:true,
						fieldStyle:'background:#B8B8B8 ;font-weight:bold;'
					}]
				},{
					columnWidth:.13,
					border:false,
					layout: 'anchor',
					defaultType: 'combobox',
					items:[{
						xtype:'textfield',
						fieldLabel:'Lama',
						width:100,
						labelWidth:50,
						id:'tf_lama',
						value:'',
						readOnly:true,
						fieldStyle:'background:#B8B8B8 ;font-weight:bold;'
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
						html:'',
					}]
				},{
					columnWidth:.8,
					border:false,
					layout: 'anchor',
					defaultType: 'combobox',
					items:[{
						xtype:'textfield',
						fieldLabel:'Karyawan',
						width:490,
						labelWidth:100,
						id:'tf_karyawan',
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
						fieldLabel:'Manager',
						width:400,
						labelWidth:100,
						id:'tf_mgr_lama',
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
						fieldLabel:'Departemen Baru',
						width:400,
						labelWidth:100,
						id:'tf_dept_baru',
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
						fieldLabel:'Manager Baru',
						width:400,
						labelWidth:100,
						id:'tf_mgr_baru',
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
						fieldLabel:'Grade Baru',
						width:400,
						labelWidth:100,
						id:'tf_grade_baru',
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
						fieldLabel:'Posisi Baru',
						width:400,
						labelWidth:100,
						id:'tf_posisi_baru',
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
						fieldLabel:'Lokasi Baru',
						width:400,
						labelWidth:100,
						id:'tf_lokasi_baru',
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
					defaultType: 'numberfield',
					items:[{
						xtype:'textfield',
						fieldLabel:'Gaji Baru',
						width:300,
						labelWidth:100,
						id:'tf_gaji_baru',
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
					defaultType: 'textareafield',
					items:[{
						xtype:'textareafield',
						fieldLabel:'Alasan',
						width:490,
						labelWidth:100,
						id:'tf_alasan',
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
					defaultType: 'textareafield',
					items:[{
						xtype:'textareafield',
						fieldLabel:'Keterangan',
						width:490,
						labelWidth:100,
						id:'tf_keterangan',
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
					defaultType: 'textfield',
					items:[{
						xtype:'textfield',
						fieldLabel:'Tgl Efektif',
						width:250,
						labelWidth:100,
						id:'tf_tgl',
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
						fieldLabel:'Persetujuan',
						store: comboSetuju,
						displayField: 'DATA_NAME',
						valueField: 'DATA_VALUE',
						width:250,
						labelWidth:100,
						id:'cb_setuju',
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
					defaultType: 'textfield',
					items:[{
						xtype:'textfield',
						fieldLabel:'Note Persetujuan',
						width:490,
						labelWidth:100,
						id:'tf_note'
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
						if (Ext.getCmp('cb_setuju').getValue()!=''){
							if (Ext.getCmp('cb_setuju').getValue()=='Setuju' || Ext.getCmp('tf_note').getValue()!=''){
								Ext.Ajax.request({
									url:'<?php echo 'simpan_mutasi.php'; ?>',
									params:{
										hdid:Ext.getCmp('tf_hdid').getValue(),
										setuju:Ext.getCmp('cb_setuju').getValue(),
										note:Ext.getCmp('tf_note').getValue(),
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
											// Ext.Ajax.request({
												// url:'<?PHP echo PATHAPP ?>/transaksi/mutasi/autoemailMUTASI.php',
												// method:'POST',
												// params:{
													// hdid:transId,
												// },
												// success:function(response){
												// },
												// failure:function(error){
													// alertDialog('Warning','Save failed.');
												// }
											// });
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
								alertDialog('Kesalahan','Note Persetujuan harus diisi.');
							}
						} else {
							alertDialog('Kesalahan','Persetujuan belum dipilih.');
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