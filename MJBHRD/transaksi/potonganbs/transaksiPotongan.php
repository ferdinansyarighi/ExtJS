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
							Ext.MessageBox.alert('Failed', 'Anda tidak diperbolehkan menambah file jenis TXT atau PHP');
						}
						else{
							frmfile1.getForm().submit({
								url: '<?PHP echo PATHAPP ?>/transaksi/potonganbs/uploadattachment.php', 
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
	Ext.clearForm=function(){
		Ext.getCmp('tf_hdid').setValue('')
		Ext.getCmp('tf_typeform').setValue('tambah')
		Ext.getCmp('tf_nb').setValue('')
		Ext.getCmp('cb_user').setDisabled(false)
		Ext.getCmp('cb_user').setValue('')
		// Ext.getCmp('cb_user_ID').setValue('')
		Ext.getCmp('tf_perusahaan').setValue('')
		Ext.getCmp('tf_dept').setValue('')
		Ext.getCmp('tf_posisi').setValue('')
		Ext.getCmp('tf_plant').setValue('')
		// Ext.getCmp('tf_pinjaman').setValue('')
		Ext.getCmp('tf_tp').setValue('')
		Ext.getCmp('tf_ket').setValue('')
		Ext.getCmp('tf_nominal').setValue(0)
		Ext.getCmp('tf_jc').setValue(1)
		Ext.getCmp('cb_status').setValue(true);
		  
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
			dataIndex:'DATA_PERSON',
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
			dataIndex:'DATA_STATUS',
			header:'Status',
			width:50,
			//hidden:true
		}],
		listeners: {
			dblclick: {
				element: 'body', //bind to the underlying body property on the panel
				fn: function(){
					var hdid=grid_popuptransaksi.getSelectionModel().getSelection()[0].get('HD_ID');
					Ext.getCmp('tf_hdid').setValue(hdid);
					Ext.getCmp('tf_typeform').setValue('edit');
					Ext.getCmp('cb_user').setDisabled(true);
					var statusA=grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_STATUS');
					if(statusA == 'AKTIF'){
						Ext.getCmp('cb_status').setValue(true);
					} else {
						Ext.getCmp('cb_status').setValue(false);
					}
					comboPemohon.load();
					Ext.getCmp('cb_user').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_PERSON_ID'));
					Ext.getCmp('tf_nb').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_PINJAMAN'));
					Ext.getCmp('tf_tp').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_TGL'));
					Ext.getCmp('tf_nominal').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_NOMINAL'));
					Ext.getCmp('tf_jc').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_JML_C'));
					Ext.getCmp('tf_nc').setValue((Ext.getCmp('tf_nominal').getValue() / Ext.getCmp('tf_jc').getValue()).formatMoney(2, ',', '.'));
					Ext.getCmp('tf_ket').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_TUJUAN'));
					Ext.Ajax.request({
					url:'<?php echo 'isi_nama_header.php'; ?>',
							timeout: 500000,
							params:{
								nama_pem:Ext.getCmp('cb_user').getValue(),
							},
							success:function(response){
								var json=Ext.decode(response.responseText);
								var deskripsiasli = json.results;
								var deskripsisplit = deskripsiasli.split('|');
								var perusahaan = deskripsisplit[0];
								var dept = deskripsisplit[1];
								var jabatan = deskripsisplit[2];
								var plant = deskripsisplit[3];
								Ext.getCmp('tf_perusahaan').setValue(perusahaan);
								Ext.getCmp('tf_dept').setValue(dept);
								Ext.getCmp('tf_posisi').setValue(jabatan);
								Ext.getCmp('tf_plant').setValue(plant);
							},
						method:'POST',
					});
					Ext.Ajax.request({
						url:'<?PHP echo PATHAPP ?>/transaksi/potonganbs/insert_grid_upload.php',
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
				html:'<div align="center"><font size="5"><b>Form BS Karyawan (KASIR)</b></font></div>',
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
						name: 'tf_nb',
						fieldLabel: 'No BS',
						width:450,
						labelWidth:100,
						id:'tf_nb',
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
							Ext.clearForm();
							PopupTransaksi.show();
						} 
					}]
				},{
					columnWidth:.1,
					border:false,
					layout: 'anchor',
					defaultType: 'button',
					items:[{
						xtype: 'checkboxgroup',
						labelWidth:50,
						items: [
							{boxLabel: 'Aktif', id: 'cb_status', name: 'cb_status', inputValue: 1, checked: true},
						]
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
						xtype:'combobox',
						fieldLabel:'Nama Karyawan',
						store: comboPemohon,
						displayField: 'DATA_NAME',
						valueField: 'DATA_VALUE',
						width:450,
						labelWidth:100,
						id:'cb_user',
						value:'',
						emptyText : '- Pilih -',
						//editable: false 
						enableKeyEvents : true,
						minChars:1,
						listeners: {
							select:function(f,r,i){
								var nama_pem=r[0].data.DATA_VALUE;
								Ext.Ajax.request({
									url:'<?php echo 'isi_nama_header.php'; ?>',
										timeout: 500000,
										params:{
											nama_pem:nama_pem,
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
											Ext.getCmp('tf_perusahaan').setValue(perusahaan);
											Ext.getCmp('tf_dept').setValue(dept);
											Ext.getCmp('tf_posisi').setValue(jabatan);
											Ext.getCmp('tf_plant').setValue(plant);
										},
									method:'POST',
								});
							}
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
					columnWidth:.9,
					border:false,
					layout: 'anchor',
					defaultType: 'numberfield',
					items:[{		
						xtype:'numberfield',
						fieldLabel:'Nominal BS',
						width:300,
						labelWidth:100,
						id:'tf_nominal',
						value: 0,
						// editable: false,
						minValue: 0,
						maxValue: 1000000000000,
						listeners: {
							 change: function(field,newValue,oldValue){
								var hasil = Ext.getCmp('tf_nominal').getValue() / Ext.getCmp('tf_jc').getValue();
								// console.log(hasil);
								Ext.getCmp('tf_nc').setValue(hasil.formatMoney(2, ',', '.'));
							}
						}
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
						html:'',
					}]
				},{
					columnWidth:.9,
					border:false,
					layout: 'anchor',
					defaultType: 'datefield',
					items:[{		
						xtype:'datefield',
						fieldLabel:'Tgl BS',
						width:200,
						labelWidth:100,
						id:'tf_tp',
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
						html:'',
					}]
				},{
					columnWidth:.9,
					border:false,
					layout: 'anchor',
					defaultType: 'numberfield',
					items:[{		
						xtype:'numberfield',
						fieldLabel:'Jumlah Cicilan',
						width:200,
						labelWidth:100,
						id:'tf_jc',
						value: 1,
						editable: false,
						minValue: 1,
						maxValue: 24,
						listeners: {
							 change: function(field,newValue,oldValue){
								var hasil = Ext.getCmp('tf_nominal').getValue() / Ext.getCmp('tf_jc').getValue();
								// console.log(hasil);
								// console.log(hasil.formatMoney(2, ',', '.'));
								Ext.getCmp('tf_nc').setValue(hasil.formatMoney(2, '.', ','));
							}
						}
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
						html:'',
					}]
				},{
					columnWidth:.9,
					border:false,
					layout: 'anchor',
					defaultType: 'numberfield',
					items:[{		
						xtype:'textfield',
						fieldLabel:'Nominal Cicilan',
						width:300,
						labelWidth:100,
						id:'tf_nc',
						readOnly:true,
						fieldStyle:'background:#B8B8B8 ;font-weight:bold;'
					}]
				}]	
			},{
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
						title: 'Attachment',
						//defaultType: 'radio', // each item will be a radio button
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
					columnWidth:.98,
					border:false,
					layout: 'anchor',
					defaultType: 'combobox',
					items:[{
						xtype:'textareafield',
						fieldLabel:'Keterangan',
						width:450,
						height:150,
						labelWidth:100,
						id:'tf_ket'
					}]
				}]	
			},{
				xtype:'label',
				html:'&nbsp',
			},{
				xtype:'panel',
				bodyStyle: 'padding-left: 300px;padding-bottom: 50px;border:none',
				items:[{
					xtype:'button',
					text:'Save',
					width:100,
					handler:function(){
						if (Ext.getCmp('cb_user').getValue()!='' && Ext.getCmp('tf_nominal').getValue()!=0 && Ext.getCmp('tf_nominal').getValue()!='' && Ext.getCmp('tf_nominal').getValue()!=null && Ext.getCmp('tf_tp').getValue()!= null && Ext.getCmp('tf_ket').getValue()!=''){
							var statusString = 0;
							if (Ext.getCmp('cb_status').getValue()) {
								statusString = 1;
							} else {
								statusString = 0;
							}
							Ext.Ajax.request({
								url:'<?php echo 'simpan_potongan.php'; ?>',
								params:{
									hdid:Ext.getCmp('tf_hdid').getValue(),
									typeform:Ext.getCmp('tf_typeform').getValue(),
									tgl:Ext.Date.dateFormat(Ext.getCmp('tf_tp').getValue(), 'Y-m-d'),
									nominal:Ext.getCmp('tf_nominal').getValue(),
									nama_user:Ext.getCmp('cb_user').getValue(),
									jml:Ext.getCmp('tf_jc').getValue(),
									ket:Ext.getCmp('tf_ket').getValue(),
									status:statusString,
								},
								method:'POST',
								success:function(response){
									var json=Ext.decode(response.responseText);
									var jsonresults = json.results;
									var jsonsplit = jsonresults.split('|');
									var transId = jsonsplit[0];
									var transNo = jsonsplit[1];
									if (json.rows == "sukses"){
										alertDialog('Sukses', "Data tersimpan dengan nomor : " + transNo + ".");
										// Ext.Ajax.request({
											// url:'autoemailPinjaman.php',
											// method:'POST',
											// params:{
												// hdid:jsonresults,
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
							alertDialog('Peringatan','Nama Karyawan, Tgl BS, Nominal BS, Jumlah Cicilan, dan Keterangan belum diisi.');
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