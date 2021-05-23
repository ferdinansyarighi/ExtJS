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
								url: '<?PHP echo PATHAPP ?>/transaksi/potonganacc/uploadattachment.php', 
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
	var comboPemohon = Ext.create('Ext.data.Store', {
		fields: [{
			name:'DATA_VALUE'
		},{
			name:'DATA_NAME'
		}],
		proxy:{
			type:'ajax',
			url:'<?PHP echo PATHAPP ?>/combobox/combobox_user_pelunasan.php', 
			reader: {
				type: 'json',
				root: 'data', 
				totalProperty:'total'   
			},
		}
	});
	var comboManager = Ext.create('Ext.data.Store', {
		fields: [{
			name:'DATA_VALUE_ID'
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
		Ext.getCmp('cb_user').show()
		Ext.getCmp('cb_user').setValue('')
		Ext.getCmp('tf_perusahaan').setValue('')
		Ext.getCmp('tf_dept').setValue('')
		Ext.getCmp('tf_posisi').setValue('')
		Ext.getCmp('tf_plant').setValue('')
		Ext.getCmp('tf_grade').setValue('')
		Ext.getCmp('tf_gg').setValue(0)
		Ext.getCmp('tf_gaji_asli').setValue(0)
		Ext.getCmp('tf_outstanding').setValue(0)
		Ext.getCmp('cb_tipe').setValue('CASH')
		store_detil.removeAll();
		Ext.getCmp('tf_tp').setValue('')
		
		Ext.getCmp('tf_nominal').setValue(0)
		Ext.getCmp('tf_nominal_hide').setValue(0)
		
		Ext.getCmp('cb_user').setDisabled(false)
		Ext.getCmp('btn_save').setDisabled(false)
		Ext.getCmp('btn_pdf').setDisabled(true)
		Ext.getCmp('cb_status').setValue(true)
		
		Ext.getCmp('tf_nama_emp_existing').hide()
		
		
	};

	Ext.uncheckGrid=function(){
		store_detil.pruneRemoved( false );
	};
	
	Ext.checkGrid=function(){
		store_detil.pruneRemoved( true );
	};
	
	var cellEditing = Ext.create('Ext.grid.plugin.CellEditing', {
        clicksToEdit: 1
    });
	var store_popuptransaksi=new Ext.data.JsonStore({
		id:'store_popuptransaksi_id',
		pageSize: 100,
		fields:[{
			name:'HD_ID'
		},{
			name:'DATA_NOMOR'
		},{
			name:'DATA_PERSON_ID'
		},{
			name:'DATA_NOMINAL'
		},{
			name:'DATA_TIPE'
		},{
			name:'DATA_TGL'
		},{
			name:'DATA_STATUS'
		},{
			name:'DATA_NAMA'
		},{
			name:'DATA_NOMINAL_RP'
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
			dataIndex:'DATA_NOMOR',
			header:'No Pelunasan',
			width:200,
			//hidden:true
		},{
			dataIndex:'DATA_NAMA',
			header:'Nama',
			width:200,
			//hidden:true
		},{
			dataIndex:'DATA_NOMINAL',
			header:'Nominal',
			width:110,
			//hidden:true
		},{
			dataIndex:'DATA_NOMINAL_RP',
			header:'Nominal',
			width:110,
			hidden:true
		},{
			dataIndex:'DATA_TIPE',
			header:'Tipe Pelunasan',
			width:120,
			//hidden:true
		},{
			dataIndex:'DATA_TGL',
			header:'Tgl Pelunasan',
			width:100,
			//hidden:true
		},{
			dataIndex:'DATA_STATUS',
			header:'Status',
			width:100,
			//hidden:true
		}],
		listeners: {
			dblclick: {
				element: 'body', //bind to the underlying body property on the panel
				fn: function(){
					var hdid=grid_popuptransaksi.getSelectionModel().getSelection()[0].get('HD_ID');
					var statusA=grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_STATUS');
					if(statusA == 'AKTIF'){
						Ext.getCmp('cb_status').setValue(true);
					} else {
						Ext.getCmp('cb_status').setValue(false);
					}
					Ext.getCmp('btn_save').setDisabled(true);
					
					// Ext.getCmp('btn_pdf').setDisabled(false);
					
					Ext.getCmp('tf_hdid').setValue(hdid);
					Ext.getCmp('tf_typeform').setValue('edit');
					Ext.getCmp('tf_np').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_NOMOR'));
					
					Ext.getCmp('cb_user').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_PERSON_ID'));
					Ext.getCmp('cb_user').setDisabled(true);
					
					comboPemohon.load();
					
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
								var grade = deskripsisplit[4];
								var gajirp = deskripsisplit[5];
								var gaji = deskripsisplit[6];
								var outstanding = deskripsisplit[7];
								var nama_existing = deskripsisplit[8];
								
								Ext.getCmp('tf_perusahaan').setValue(perusahaan);
								Ext.getCmp('tf_dept').setValue(dept);
								Ext.getCmp('tf_posisi').setValue(jabatan);
								Ext.getCmp('tf_plant').setValue(plant);
								Ext.getCmp('tf_grade').setValue(grade);
								Ext.getCmp('tf_gg').setValue(gajirp);
								Ext.getCmp('tf_gaji_asli').setValue(gaji);
								Ext.getCmp('tf_outstanding').setValue(parseInt(outstanding).formatMoney(2, ',', '.'));
								
								Ext.getCmp('cb_user').hide();
								Ext.getCmp('tf_nama_emp_existing').show();
								
								Ext.getCmp('tf_nama_emp_existing').setValue( nama_existing );
								// Ext.getCmp('cb_user').setDisabled( true );
								
								// Ext.getCmp('tf_nominal').setValue(parseInt(outstanding).formatMoney(2, ',', '.'));
								
							},
						method:'POST',
					});
					
					comboPemohon.load();
					Ext.getCmp('cb_tipe').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_TIPE'));
					comboTipe.load();
					Ext.getCmp('tf_tp').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_TGL'));
					
					Ext.getCmp('tf_nominal_hide').setValue( grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_NOMINAL') );
					
					Ext.getCmp('tf_nominal').setValue( grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_NOMINAL_RP') );
					
					// Ext.getCmp('tf_nominal').setValue( grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_NOMINAL') );
					// var  nominal_view = grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_NOMINAL');
					// console.log( 'nominal_view: ' + nominal_view );
					// Ext.getCmp('tf_nominal').setValue( nominal_view.formatMoney(2, ',', '.') );
					
					
					store_detil.setProxy({
						type:'ajax',
						timeout: 500000,
						url:'isi_grid_potongan.php?hdid=' + hdid,
						reader: {
							type: 'json',
							root: 'data', 
							totalProperty:'total'   
						}
					});
					
					store_detil.load();
					
					//grid_detil.getSelectionModel().getSelection().select(1,true); 
					// var sm=thisObj.getSelectionModel();
					
					
					
					console.log( 'di dalam grid_popuptransaksi' );
					
					PopupTransaksi.hide();
					
					// Ext.getCmp('grid_detil_id').getSelectionModel().selectAll();
					
					// var grid = Ext.getCmp("grid_detil_id"); 
					// grid.getSelectionModel().selectAll();
					
					// var viewCmp = Ext.getCmp('grid_detil_id');
					// viewCmp.fireEvent('select');

					/*
					var data = grid_detil.getSelectionModel().getSelection();
					var i = 0;
					
					for( var i in data ) {
						
						grid_detil.getSelectionModel().selectRow( i );
						
					}
					*/
					
					
				}
			}
		}
	});
	
	var PopupTransaksi=Ext.create('Ext.Window', {
		title: 'Cari No Pelunasan Pinjaman Karyawan',
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
			fieldLabel:'No Pelunasan',
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
							person_id:<?php echo $emp_id; ?>,
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
			name:'DATA_START_POTONGAN'
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
		}],
		proxy:{
			type:'ajax',
			url:'isi_grid_potongan.php', 
			reader: {
				root: 'rows',  
			},
		}
	});
    var sm = Ext.create('Ext.selection.CheckboxModel');
	
	// var outstanding = 0;
	
    var grid_detil = Ext.create('Ext.grid.Panel', {
		id:'grid_detil_id',
		region:'center',
		store:store_detil,
		selModel: {
			selType: 'checkboxmodel',
			mode: 'MULTI',
			checkOnly: true,
			allowDeselect: true,
			enableKeyNav : false,
			// showHeaderCheckbox: false,
			// deselectAll: true,
			// pruneRemoved: false,
			listeners: {	
				
				select: function (model, record, index, eOpts) {
					
					
					hd_id =  record.get('HD_ID');
					
					no_pinjaman =  record.get('DATA_NO_PINJAM');
					
					// console.log( 'select: ' + parseInt(record.get('DATA_OUTSTANDING_ASLI')) + ' hd_id: ' + hd_id + ' no_pinjaman: ' + no_pinjaman );
					
					
					
					// Tambahkan nominal pelunasan
					
					// Ext.getCmp('tf_nominal').setValue( Ext.getCmp('tf_nominal').getValue() + parseInt(record.get('DATA_OUTSTANDING_ASLI')).formatMoney(2, ',', '.') );
					// Ext.getCmp('tf_nominal').setValue( Ext.getCmp('tf_nominal_hide').getValue() + parseInt(record.get('DATA_OUTSTANDING_ASLI')) );
					
					Ext.getCmp('tf_nominal').setValue( ( Ext.getCmp('tf_nominal_hide').getValue() + parseInt(record.get('DATA_OUTSTANDING_ASLI')) ).formatMoney(2, ',', '.') );
					
					Ext.getCmp('tf_nominal_hide').setValue( Ext.getCmp('tf_nominal_hide').getValue() + parseInt(record.get('DATA_OUTSTANDING_ASLI')) );
					
					
					
					// var nominal_awal = Ext.getCmp('tf_nominal_hide').getValue();
					// var outstanding = parseInt(record.get('DATA_OUTSTANDING_ASLI'));
					// var nominal_akhir = nominal_awal + outstanding;
					
					// console.log( 'nominal_awal: ' + nominal_awal + ' outstanding: ' + outstanding + ' nominal_akhir: ' + nominal_akhir + ' nominal_akhir.formatMoney: ' + nominal_akhir.formatMoney(2, ',', '.') );
					// console.log( nominal_akhir.formatMoney(2, ',', '.') + ' ' +  nominal_akhir.formatMoney( 2 ) );
					// Ext.getCmp('tf_nominal').setValue( nominal_akhir.formatMoney(2, ',', '.') );
					
					
					
					
					/* CONTOH BERHASIL
					
					Ext.getCmp('tf_nominal').setValue( Ext.getCmp('tf_nominal').getValue() + parseInt(record.get('DATA_OUTSTANDING_ASLI')) );

					if ( parseInt(record.get('DATA_OUTSTANDING_ASLI')) > 1000000 ) {
						
						alertDialog( 'Peringatan', 'Lebih dari satu juta.' );
						
						Ext.getCmp('grid_detil_id').getSelectionModel().deselect( index );
						
					}
					
					END CONTOH BERHASIL */
					
					
					
					/* DIPAKAI */
					
					Ext.Ajax.request({
						url:'<?php echo 'cek_no_pinjaman_valid.php'; ?>',
							timeout: 500000,
							params:{
								
								hd_id : hd_id,
								
								// outstanding:outstanding,
								// process_type:'select',
								
							},
							success:function(response){
								
								var json = Ext.decode(response.responseText);
								var jsonresults = json.results;
								
								if ( json.success == true ) {
									
									if ( jsonresults != true )
									{  
										
										var json_no_pinjaman = json.no_pinjaman_param;
										
										Ext.getCmp('grid_detil_id').getSelectionModel().deselect( index );
										
										// alertDialog( 'Peringatan', 'Pelunasan atas nomor pinjaman ' + no_pinjaman + ' sudah pernah dilakukan.' );
										
										alertDialog( 'Peringatan', 'Pelunasan atas nomor pinjaman ' + json_no_pinjaman + ' sudah pernah dilakukan.' );
										
									}
									
								} else {
									
									Ext.getCmp('grid_detil_id').getSelectionModel().deselect( index );
									
									alertDialog( 'Kesalahan', 'Pengecekan pelunasan atas nomor pinjaman di detail gagal.' );
									
								}
								
							},
						method:'POST',
					});
					
					/* END DIPAKAI */
					
					
				},
				deselect: function (model, record, index, eOpts) {
					
					
					hd_id =  record.get('HD_ID');
					
					// console.log( 'deselect: ' + parseInt(record.get('DATA_OUTSTANDING_ASLI')) + ' hd_id: ' + hd_id );
					
					
					
					// Kurangkan nominal pelunasan
					
					// Ext.getCmp('tf_nominal').setValue( Ext.getCmp('tf_nominal').getValue() - parseInt(record.get('DATA_OUTSTANDING_ASLI')).formatMoney(10, ',', '.') );
					
					// Ext.getCmp('tf_nominal').setValue( Ext.getCmp('tf_nominal_hide').getValue() - parseInt(record.get('DATA_OUTSTANDING_ASLI')) );
					
					Ext.getCmp('tf_nominal').setValue( ( Ext.getCmp('tf_nominal_hide').getValue() - parseInt(record.get('DATA_OUTSTANDING_ASLI')) ).formatMoney(2, ',', '.') );
					
					Ext.getCmp('tf_nominal_hide').setValue( Ext.getCmp('tf_nominal_hide').getValue() - parseInt(record.get('DATA_OUTSTANDING_ASLI')) );
					
					
					
				}
				
			}
		},
		height:150,
		width:830,
		autoScroll:true,
        columnLines: true,
        frame: false,
        columns: [{
			dataIndex:'HD_ID',
			header:'No.',
			hidden:true,
			width:50,
		},{
			dataIndex:'DATA_OUTSTANDING_ASLI',
			header:'Outstanding asli',
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
			dataIndex:'DATA_OUTSTANDING_RP',
			header:'Outstanding (Rp)',
			width:150,
			align:'right'
		},{
			dataIndex:'DATA_START_POTONGAN',
			header:'Start Potongan',
			//hidden:true,
			width:100,
		},{
			dataIndex:'DATA_TGL',
			header:'Tgl Pinjam',
			//hidden:true,
			width:90,
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
		}]
    });
	
	var comboTipe = Ext.create('Ext.data.Store', {
		fields: ['DATA_NAME', 'DATA_VALUE'],
		data : [
			{"DATA_NAME":"Cash", "DATA_VALUE":"CASH"},
			{"DATA_NAME":"Potong Gaji", "DATA_VALUE":"POTONG GAJI"},
		]
	});
	
	var kategoriForm = "All";
	var contentPanel = Ext.create('Ext.panel.Panel',{
			//title:'Form Input Tanda Terima',
			//region:'center',
			bodyStyle: 'spacing: 10px;border:none',
			items:[{
				xtype:'label',
				html:'<div align="center"><font size="5"><b>Pelunasan Pinjaman Karyawan</b></font></div>',
			},{
				xtype:'label',
				html:'&nbsp',
			},{		
				xtype:'textfield',
				fieldLabel:'Hdid',
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
						fieldLabel: 'No Pelunasan',
						width:450,
						labelWidth:130,
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
							Ext.clearForm();
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
						labelWidth:130,
						id:'cb_user',
						value:'',
						emptyText : '- Pilih -',
						//editable: false 
						enableKeyEvents : true,
						minChars:1,
						listeners: {
							select:function(f,r,i){
								var nama_pem=r[0].data.DATA_VALUE;
								
								Ext.getCmp('tf_nominal').setValue( 0 );
								Ext.getCmp('tf_nominal_hide').setValue( 0 );
								
								store_detil.removeAll();
								
								// Ext.getCmp('grid_detil_id').getSelectionModel().deselectAll();
								
								Ext.getCmp('grid_detil_id').getSelectionModel().clearSelections();
								
								// Ext.getCmp('grid_detil_id').getSelectionModel().pruneRemoved( false );
								
								/*
								Ext.Ajax.request({
									url:'<?php echo 'delete_outstanding.php'; ?>',
										timeout: 500000,
										params:{
											
										},
										success:function(response){
											
											var json = Ext.decode(response.responseText);
											var jsonresults = json.results;
											
										},
									method:'POST',
								});
								*/
								
								// Ext.uncheckGrid();
								
								store_detil.setProxy({
									type:'ajax',
									timeout: 500000,
									url:'isi_grid_potongan.php?nama_pem=' + nama_pem,
									reader: {
										type: 'json',
										root: 'data', 
										totalProperty:'total'   
									}
								});
								
								store_detil.load();
								
								// Ext.checkGrid();
								
								// Ext.getCmp('grid_detil_id').getSelectionModel().deselectAll();
								
								// Ext.getCmp('grid_detil_id').getSelectionModel().selectRow( 0 );
								
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
											var grade = deskripsisplit[4];
											var gajirp = deskripsisplit[5];
											var gaji = deskripsisplit[6];
											var outstanding = deskripsisplit[7];
											Ext.getCmp('tf_perusahaan').setValue(perusahaan);
											Ext.getCmp('tf_dept').setValue(dept);
											Ext.getCmp('tf_posisi').setValue(jabatan);
											Ext.getCmp('tf_plant').setValue(plant);
											Ext.getCmp('tf_grade').setValue(grade);
											Ext.getCmp('tf_gg').setValue(gajirp);
											Ext.getCmp('tf_gaji_asli').setValue(gaji);
											Ext.getCmp('tf_outstanding').setValue(parseInt(outstanding).formatMoney(2, ',', '.'));
										},
									method:'POST',
								});
							}
						}
					}]
				}]	
			}, {
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
					defaultType: 'textfield',
					items:[{		
						fieldLabel:'Nama Karyawan',
						displayField: 'DATA_NAME',
						valueField: 'DATA_VALUE',
						width:450,
						labelWidth:130,
						id:'tf_nama_emp_existing',
						value:'',
						editable: false,
                        readOnly:true,
                        fieldStyle:'background:#B8B8B8 ;font-weight:bold;'
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
						labelWidth:130,
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
						labelWidth:130,
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
						labelWidth:130,
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
						fieldLabel:'Grade',
						width:450,
						labelWidth:130,
						id:'tf_grade',
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
						labelWidth:130,
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
						fieldLabel:'Gaji Gross',
						width:450,
						labelWidth:130,
						id:'tf_gg',
						readOnly:true,
						value: 0,
						fieldStyle:'background:#B8B8B8 ;font-weight:bold;'
					}]
				},{
					columnWidth:.6,
					border:false,
					layout: 'anchor',
					defaultType: 'combobox',
					hidden:true,
					items:[{		
						xtype:'textfield',
						fieldLabel:'Gaji Gross Asli',
						width:450,
						labelWidth:130,
						id:'tf_gaji_asli',
						readOnly:true,
						value: 0,
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
						fieldLabel:'Outstanding Pinjaman',
						width:450,
						labelWidth:130,
						id:'tf_outstanding',
						readOnly:true,
						value:0,
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
					hidden:true,
					items:[{		
						xtype:'numberfield',
						fieldLabel:'Nominal Pelunasan (Hide)',
						width:450,
						labelWidth:130,
						id:'tf_nominal_hide',
						readOnly:true,
						value: 0,
						minValue: 0,
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
						xtype:'textfield',
						fieldLabel:'Nominal Pelunasan',
						width:350,
						labelWidth:130,
						id:'tf_nominal',
						readOnly:true,
						value: 0,
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
					columnWidth:.6,
					border:false,
					layout: 'anchor',
					defaultType: 'combobox',
					items:[{		
						xtype:'combobox',
						fieldLabel:'Tipe Pelunasan ',
						width:350,
						labelWidth:130,
						id:'cb_tipe',
						store: comboTipe,
						displayField: 'DATA_NAME',
						valueField: 'DATA_VALUE',
						editable: false,
						value: 'CASH',
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
						fieldLabel:'Tgl Pelunasan',
						width:240,
						labelWidth:130,
						id:'tf_tp',
						format: 'Y-m-d',
						minValue: currentTime
					}]
				}]	
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
						items:[{
							columnWidth:.02,
							border:false,
							layout: 'anchor',
							defaultType: 'label',
							items:[{
								xtype:'label',
								html:'',
							}]
						}, {
							columnWidth:.02,
							border:false,
							layout: 'anchor',
							defaultType: 'label',
							items:[{
								xtype:'label',
								html:'',
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
					text:'Save',
					id:'btn_save',
					width:50,
					handler:function(){
						if ( Ext.getCmp('cb_user').getValue() != '' 
								&& Ext.getCmp('tf_tp').getValue() != '' 
								&& Ext.getCmp('tf_tp').getValue() != null
								&& Ext.getCmp('tf_nominal_hide').getValue() != 0 
							)
						{
							
							var data = grid_detil.getSelectionModel().getSelection();
							var i = 0;
							var arrTransID = new Array();
							
							// var cek_valid_number = 'Y';
							
							for( var i in data ) {
								
								arrTransID[i] = data[i].get('HD_ID');
								
								console.log( arrTransID[i] + '   ' + data[i].get('DATA_NO_PINJAM') );
								
								/*
								// console.log( 'arrTransID[' + i + '] :' + arrTransID[i] + '   data[' + i + '].get('DATA_NO_PINJAM') : ' + data[i].get('DATA_NO_PINJAM') );
								
								
								// Validasi apakah nomor pinjaman telah dibuatkan pelunasan
								
								Ext.Ajax.request({
									url:'<?php echo 'cek_no_pinjaman_valid_save.php'; ?>',
										timeout: 500000,
										params:{
											
											hd_id : arrTransID[i],
											
										},
										success:function(response){
											
											var json = Ext.decode(response.responseText);
											var jsonresults = json.results;
											
											if ( json.success == true ) {
												
												if ( jsonresults == false )
												{	
													
													cek_valid_number = 'N';
													
													alertDialog( 'Peringatan', 'Pelunasan atas nomor pinjaman ' + data[i].get('DATA_NO_PINJAM') + ' sudah pernah dilakukan. Data tidak dapat disimpan.' );
													
													return false;
													
													// break 1;
													// exit;
													
												} else {
													
													cek_valid_number = 'Y';
													
												}
												
												
											} else {
												
												cek_valid_number = 'N';
												
												alertDialog( 'Kesalahan', 'Pengecekan pelunasan atas nomor pinjaman di detail gagal.' );
												
											}
											
										},
									method:'POST',
								});
								*/

								
							}
							
							// console.log(arrTransID);
							// console.log( 'Masuk proses simpan.' );
							
							var statusString = 'N';
							
							if (Ext.getCmp('cb_status').getValue()) {
								statusString = 'Y';
							} else {
								statusString = 'N';
							}
							
							
							Ext.Ajax.request({
								url:'<?php echo 'simpan.php'; ?>',
								params:{
									arrTransID:Ext.encode(arrTransID),
									hdid:Ext.getCmp('tf_hdid').getValue(),
									typeform:Ext.getCmp('tf_typeform').getValue(),
									nama_user:Ext.getCmp('cb_user').getValue(),
									// nominal:Ext.getCmp('tf_nominal').getValue(),
									nominal:Ext.getCmp('tf_nominal_hide').getValue(),
									gajigross:Ext.getCmp('tf_gaji_asli').getValue(),
									tipe:Ext.getCmp('cb_tipe').getValue(),
									tgl:Ext.getCmp('tf_tp').getRawValue(),
									status:statusString,
								},
								method:'POST',
								success:function(response){
									var json=Ext.decode(response.responseText);
									var jsonresults = json.results;
									var jsonsplit = jsonresults.split('|');
									var transId = jsonsplit[0];
									var transNo = jsonsplit[1];
									
									if (json.rows == "sukses") {
										
										alertDialog('Sukses', "Data tersimpan dengan Nomor Pelunasan : <br/>" + transNo + ".");
										
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
										
										if (json.rows == "gagal_validasi") {
										
											alertDialog( 'Peringatan', 'Pelunasan atas nomor pinjaman ' + transNo + ' sudah pernah dilakukan. Data tidak dapat disimpan.' );
										
										} else {
											
											alertDialog('Kesalahan', "Data gagal disimpan. ");
											
										}
									}
									
								},
								failure:function(error){
									alertDialog('Kesalahan','Data gagal disimpan');
								}
							});
							
							
						} else {
							alertDialog('Peringatan','Nama User, Nominal, dan Tgl Pelunasan wajib diisi.');
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
				},{
					xtype:'label',
					html:'&nbsp',
				},{
					xtype:'button',
					text:'Print PDF',
					id:'btn_pdf',
					enabled:false,
					width:75,
					handler:function(){
						
						/*
						Ext.Ajax.request({
							//url:'autoemailPinjaman.php',
							method:'POST',
							params:{
								hdid:Ext.getCmp('tf_hdid').getValue(),
							},
							success:function(response){
							},
							failure:function(error){
								alertDialog('Warning','Save failed.');
							}
						});
						*/
						
					}
				}]
			}],
		});	
   contentPanel.render('page');
   Ext.clearForm();
   // comboPemohon.setProxy({
		// type:'ajax',
		// timeout: 500000,
		// url:'<?PHP echo PATHAPP ?>/combobox/combobox_userall_id.php?bs_nama_karyawan=<?PHP echo $emp_name; ?>', 
		// reader: {
			// type: 'json',
			// root: 'data', 
			// totalProperty:'total'   
		// }
	// });	
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