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
	$pos_name = $_SESSION[APP]['pos_name'];
	$grade = $_SESSION[APP]['grade'];
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
								url: '<?PHP echo PATHAPP ?>/transaksi/bsvalidasi/uploadattachment.php', 
								method:'POST', 
								waitTitle:'Connecting', 
								waitMsg:'Sending data...',
								
								success:function(fp, o){ 											
									var sembarang=Ext.decode(o.response.responseText);		
									//console.log(sembarang);
									if(sembarang.results=='sukses'){
										store_upload.setProxy({
											type:'ajax',
											url:'<?PHP echo PATHAPP ?>/transaksi/bsvalidasi/isi_grid_upload.php?id_temp='+sembarang.id_temp,
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
	var store_upload_view_hrd=new Ext.data.JsonStore({
		id:'store_upload_hrd_id',
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
	var grid_upload_view_hrd=Ext.create('Ext.grid.Panel',{
		id:'grid_upload_hrd_id',
		region:'center',
		store:store_upload_view_hrd,
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
	var comboTipePencairan = Ext.create('Ext.data.Store', {
		fields: ['DATA_NAME', 'DATA_VALUE'],
		data : [
			{"DATA_NAME":"CASH", "DATA_VALUE":"CASH"},
			{"DATA_NAME":"TRANSFER", "DATA_VALUE":"TRANSFER"},
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
		Ext.getCmp('tf_nb').setValue('')
		Ext.getCmp('cb_user').setDisabled(false)
		Ext.getCmp('cb_user').setValue('')
		Ext.getCmp('tf_emp_id').setValue('')
		Ext.getCmp('cb_pj').setValue('')
		Ext.getCmp('cb_tipe').setValue('')
		Ext.getCmp('cb_user_id').setValue('')
		Ext.getCmp('tf_perusahaan').setValue('')
		Ext.getCmp('tf_dept').setValue('')
		Ext.getCmp('tf_posisi').setValue('')
		Ext.getCmp('tf_grade').setValue('')
		Ext.getCmp('tf_plant').setValue('')
		Ext.getCmp('cb_perusahaan').setValue('')
		// Ext.getCmp('tf_pinjaman').setValue('')
		//Ext.getCmp('tf_tp').setValue('')
		Ext.getCmp('tf_ket').setValue('')
		Ext.getCmp('tf_syarat1').setValue('')
		Ext.getCmp('tf_syarat2').setValue('')
		Ext.getCmp('tf_syarat3').setValue('')
		Ext.getCmp('tf_syarat4').setValue('')
		Ext.getCmp('tf_syarat5').setValue('')
		Ext.getCmp('tf_tgl_jt').setValue('')
		Ext.getCmp('tf_nominal').setValue(0)
		Ext.getCmp('tf_maxnominal').setValue(0)
		//Ext.getCmp('tf_jc').setValue(1)
		Ext.getCmp('cb_status').setValue(true);
		store_upload_view.removeAll();
		store_upload_view_hrd.removeAll();
		store_popuptransaksi.removeAll();
		Ext.getCmp('cb_tipe_pencairan').setValue('- Pilih -');
		Ext.getCmp('tf_no_rek').setValue('');
		Ext.getCmp('tf_tgl_pencairan').setValue('');
		Ext.getCmp('lbl_no_rek').hide();
		Ext.getCmp('tf_no_rek').hide();
		
		Ext.getCmp('cb_rek_transfer').setValue('')
		Ext.getCmp('cb_rek_transfer_nama').setValue('')
		Ext.getCmp('cb_rek_transfer_nomor').setValue('')
		Ext.getCmp('cb_rek_transfer_id').setValue('')
		
		Ext.getCmp('btn_pilih').setDisabled(true);

		Ext.getCmp('tf_nama_external').setValue('')
		Ext.getCmp('tf_png_jwb_external').setValue('')
		
		Ext.getCmp('tf_nama_external').show();
		Ext.getCmp('tf_png_jwb_external').show();
		
		Ext.getCmp('save_btn').setDisabled( true );
		
		store_upload.removeAll();
		
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
	var cellEditing = Ext.create('Ext.grid.plugin.CellEditing', {
        clicksToEdit: 1
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

	var store_popuptransaksi=new Ext.data.JsonStore({
		id:'store_popuptransaksi_id',
		pageSize: 100,
		fields:[{
			name:'HD_ID'
		},{
			name:'DATA_NO_BS'
		},{
			name:'DATA_ASSIGNMENT_ID'
		},{
			name:'DATA_PEMOHON'
		},{
			name:'DATA_PERSON_ID_PJ'
		},{
			name:'DATA_PJ'
		},{
			name:'DATA_NOMINAL'
		},{
			name:'DATA_KET'
		},{
			name:'DATA_AKTIF'
		},{
			name:'DATA_ID_UPL'
		},{
			name:'DATA_PERSON_ID'
		},{
			name:'DATA_TIPE'
		},{
			name:'DATA_PERUSAHAAN_BS'
		},{
			name:'DATA_TGL_JT'
		},{
			name:'DATA_SYARAT1'
		},{
			name:'DATA_SYARAT2'
		},{
			name:'DATA_SYARAT3'
		},{
			name:'DATA_SYARAT4'
		},{
			name:'DATA_SYARAT5'
		},{
			name:'TIPE_PENCAIRAN'
		},{
			name:'NO_REK'
		},{
			name:'TGL_PENCAIRAN'
		},{
			name:'REK_BANK_TRANSFER'
		},{
			name:'NAME_BANK_TRANSFER'
		},{
			name:'BANK_ACCOUNT_ID'
		},{
			name:'BANK_NAME'
		},{
			name:'NAMA_EXTERNAL'
		},{
			name:'PENANGGUNGJAWAB_EXTERNAL'
		},{
			name:'STATUS'
		},{
			name:'PEMOHON_DECODE'
		},{
			name:'PENANGGUNGJAWAB_DECODE'
		}
		],
		 proxy:{
			type:'ajax',
			
			url:'isi_grid_popuptransaksi.php', 
			
			// url:'isi_grid_popuptransaksi_v2.php', 
			
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
			dataIndex:'DATA_ASSIGNMENT_ID',
			header:'Pembuat',
			width:100,
			hidden:true
		},{
			dataIndex:'DATA_PERSON_ID_PJ',
			header:'Person id PJ',
			width:100,
			hidden:true
		},{
			dataIndex:'DATA_ID_UPL',
			header:'id Upload',
			width:100,
			hidden:true
		},{
			dataIndex:'DATA_PERSON_ID',
			header:'Person id Pemohon',
			width:100,
			hidden:true
		},{
			dataIndex:'DATA_NO_BS',
			header:'No BS',
			width:200,
			//hidden:true
		},{
			dataIndex:'PEMOHON_DECODE',
			header:'Pemohon',
			width:120,
			// hidden:true
		},{
			dataIndex:'DATA_PEMOHON',
			header:'Pemohon',
			width:150,
			hidden:true
		},{
			dataIndex:'DATA_PERUSAHAAN_BS',
			header:'Perusahaan BS',
			width:120,
			hidden:true
		},{
			dataIndex:'PENANGGUNGJAWAB_DECODE',
			header:'Penanggung Jawab',
			width:120,
			// hidden:true
		},{
			dataIndex:'DATA_PJ',
			header:'Penanggung Jawab',
			width:150,
			hidden:true
		},{
			dataIndex:'DATA_TIPE',
			header:'Tipe BS',
			width:150,
			//hidden:true
		},{
			dataIndex:'DATA_TGL_JT',
			header:'Tgl Jatuh Tempo',
			width:100,
		},{
			dataIndex:'DATA_NOMINAL',
			header:'Nominal',
			width:100,
		},{
			dataIndex:'DATA_KET',
			header:'Keterangan',
			width:200,
			//hidden:true
		},{
			dataIndex:'DATA_AKTIF',
			header:'Aktif',
			width:50,
			hidden:true
		},{
			dataIndex:'DATA_SYARAT1',
			header:'Syarat 1',
			width:120,
			hidden:true
		},{
			dataIndex:'DATA_SYARAT2',
			header:'Syarat 2',
			width:120,
			hidden:true
		},{
			dataIndex:'DATA_SYARAT3',
			header:'Syarat 3',
			width:120,
			hidden:true
		},{
			dataIndex:'DATA_SYARAT4',
			header:'Syarat 4',
			width:120,
			hidden:true
		},{
			dataIndex:'DATA_SYARAT5',
			header:'Syarat 5',
			width:120,
			hidden:true
		},{
			dataIndex:'TIPE_PENCAIRAN',
			header:'Tipe Pencairan',
			width:120,
			hidden:true
		},{
			dataIndex:'NO_REK',
			header:'No Rek',
			width:120,
			hidden:true
		}, {
			dataIndex:'TGL_PENCAIRAN',
			header:'Tgl Pencairan',
			width:120,
			hidden:true
		},{
			dataIndex:'REK_BANK_TRANSFER',
			header:'Rek Bank Transfer',
			width:120,
			hidden:true
		},{
			dataIndex:'NAME_BANK_TRANSFER',
			header:'Nama Bank Transfer',
			width:120,
			hidden:true
		},{
			dataIndex:'BANK_ACCOUNT_ID',
			header:'Bank Account ID',
			width:120,
			hidden:true
		},{
			dataIndex:'BANK_NAME',
			header:'Bank Name',
			width:120,
			hidden:true
		},{
			dataIndex:'NAMA_EXTERNAL',
			header:'Nama External',
			width:120,
			hidden:true
		},{
			dataIndex:'PENANGGUNGJAWAB_EXTERNAL',
			header:'Penanggungjawab External',
			width:120,
			hidden:true
		},{
			dataIndex:'STATUS',
			header:'Status',
			width:120,
			hidden:true
		}
		
		],
		listeners: {
			dblclick: {
				element: 'body', //bind to the underlying body property on the panel
				fn: function(){
					var hdid=grid_popuptransaksi.getSelectionModel().getSelection()[0].get('HD_ID');
					Ext.getCmp('tf_hdid').setValue(hdid);
					Ext.getCmp('tf_typeform').setValue('edit');
					//Ext.getCmp('cb_user').setDisabled(true);
					
					Ext.getCmp('tf_nb').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_NO_BS'));
					var statusA=grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_AKTIF');
					if(statusA == 'Y'){
						Ext.getCmp('cb_status').setValue(true);
					} else {
						Ext.getCmp('cb_status').setValue(false);
					}
					
					var person_id = grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_PERSON_ID');
					var pemohon = grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_PEMOHON');
					Ext.getCmp('cb_user_id').setValue(person_id);
					Ext.getCmp('cb_user').setValue(pemohon);
					
					if ( person_id != '' && person_id != null ) {
						
						console.log( 'person_id: ' + person_id );
						
						Ext.Ajax.request({
							url:'<?php echo 'isi_nama_header.php'; ?>',
								timeout: 500000,
								params:{
									nama_pem:person_id,
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
									var grade = deskripsisplit[7];
									Ext.getCmp('tf_perusahaan').setValue(perusahaan);
									Ext.getCmp('tf_dept').setValue(dept);
									Ext.getCmp('tf_posisi').setValue(jabatan);
									Ext.getCmp('tf_grade').setValue(grade);
									Ext.getCmp('tf_plant').setValue(plant);
								},
							method:'POST',
						});
					
					}
					
					
					Ext.getCmp('tf_emp_id').setValue(person_id);
					Ext.getCmp('cb_perusahaan').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_PERUSAHAAN_BS'));
					Ext.getCmp('cb_pj').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_PJ'));
					Ext.getCmp('cb_tipe').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_TIPE'));
					
					Ext.getCmp('tf_nominal').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_NOMINAL'));
					Ext.getCmp('tf_tgl_jt').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_TGL_JT'));
					Ext.getCmp('tf_ket').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_KET'));
					Ext.getCmp('tf_syarat1').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_SYARAT1'));
					Ext.getCmp('tf_syarat2').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_SYARAT2'));
					Ext.getCmp('tf_syarat3').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_SYARAT3'));
					Ext.getCmp('tf_syarat4').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_SYARAT4'));
					Ext.getCmp('tf_syarat5').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_SYARAT5'));
					
					Ext.getCmp('cb_tipe_pencairan').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('TIPE_PENCAIRAN'));
					Ext.getCmp('tf_tgl_pencairan').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('TGL_PENCAIRAN'));
					
					Ext.getCmp('cb_rek_transfer').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('REK_BANK_TRANSFER'));
					Ext.getCmp('cb_rek_transfer_nama').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('NAME_BANK_TRANSFER'));
					Ext.getCmp('cb_rek_transfer_nomor').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('BANK_ACCOUNT_ID'));
					Ext.getCmp('cb_rek_transfer_id').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('BANK_NAME'));
					
					
					var pencairan = grid_popuptransaksi.getSelectionModel().getSelection()[0].get('TIPE_PENCAIRAN');
					
					console.log( 'pencairan: ' + pencairan );
					
					if ( pencairan == 'TRANSFER' ) {
						
						console.log( 'Masuk di transfer' );
						
						Ext.getCmp('lbl_no_rek').show();
						Ext.getCmp('tf_no_rek').show();
						Ext.getCmp('tf_no_rek').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('NO_REK'));
						
						Ext.getCmp('btn_pilih').setDisabled(false);
						
					}
					
					
					var status = grid_popuptransaksi.getSelectionModel().getSelection()[0].get('STATUS');
					
					console.log( 'status: ' + status );
					
					if ( status == 'Validated' ) {
						
						Ext.getCmp('save_btn').setDisabled( true );
						
					} else {
						
						Ext.getCmp('save_btn').setDisabled( false );
						
					}
					
					
					if ( grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_TIPE') == 'EXTERNAL') {
						
						Ext.getCmp('tf_nama_external').show();
						Ext.getCmp('tf_png_jwb_external').show();
						// Ext.getCmp('lbl_nama_external').show();
						// Ext.getCmp('lbl_png_jwb_external').show();
						
						Ext.getCmp('tf_nama_external').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('NAMA_EXTERNAL'));
						Ext.getCmp('tf_png_jwb_external').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('PENANGGUNGJAWAB_EXTERNAL'));
						
					} else {
						
						Ext.getCmp('tf_nama_external').setValue('');
						Ext.getCmp('tf_nama_external').setValue('');
						
						Ext.getCmp('tf_nama_external').hide();
						Ext.getCmp('tf_png_jwb_external').hide();						
						
					}
					
					Ext.Ajax.request({
						url:'<?PHP echo PATHAPP ?>/transaksi/bsvalidasi/insert_grid_upload.php',
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
					
					store_upload_view_hrd.setProxy({
						type:'ajax',
						timeout: 500000,
						url:'isi_grid_upload_view_hrd.php?hd_id=' + hdid,
						reader: {
							type: 'json',
							root: 'data', 
							totalProperty:'total'   
						}
					});
					store_upload_view_hrd.load();
					
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
	
	var contentPanel = Ext.create('Ext.panel.Panel',{
			//title:'Form Input Tanda Terima',
			//region:'center',
			bodyStyle: 'spacing: 10px;border:none',
			items:[{
				xtype:'label',
				html:'<div align="center"><font size="5"><b>Validasi Bon Sementara (BS) Kasir</b></font></div>',
			},{
				xtype:'label',
				html:'<br/><br/>',
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
				fieldLabel:'id pemohon',
				width:350,
				labelWidth:150,
				id:'cb_user_id',
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
				labelWidth:75,
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
						html:'<div style="color:#FF0000">*</div>',
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
						labelWidth:120,
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
						text: 'Search',
						width:75,
						handler:function(){
							Ext.getCmp('tf_filter_pop').setValue('');
							Ext.getCmp('tf_filter_from_pop').setValue(currentTimeNow);
							Ext.getCmp('tf_filter_to_pop').setValue(currentTimeNow);
							Ext.getCmp('cb_filter1_pop').setValue('true');
							Ext.getCmp('cb_filter2_pop').setValue('true');
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
							{boxLabel: 'Aktif', id: 'cb_status', name: 'cb_status', inputValue: 1, checked: true, readOnly: true,},
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
						html:'<div style="color:#FF0000"></div>',
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
						//value:'<?PHP echo $emp_name; ?>',
						//emptyText : '- Pilih -',
						fieldStyle:'background:#B8B8B8 ;font-weight:bold;',
						readOnly: true,
						editable: false 
						//enableKeyEvents : true,
					}]
				}]	
			},{
				layout:'column',
				border:false,
				hidden: true,
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
						xtype:'textfield',
						fieldLabel:'Emp id',
						width:450,
						labelWidth:120,
						id:'tf_emp_id',
						readOnly:true,
						//value:'<?PHP echo $org_name?>',
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
					defaultType: 'textfield',
					items:[{		
						xtype:'textfield',
						fieldLabel:'Perusahaan',
						width:450,
						labelWidth:120,
						id:'tf_perusahaan',
						readOnly:true,
						//value:'<?PHP echo $org_name?>',
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
						//value:'<?PHP echo $io_name?>',
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
						fieldLabel:'Grade',
						width:450,
						labelWidth:120,
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
						fieldLabel:'Lokasi',
						width:450,
						labelWidth:120,
						id:'tf_plant',
						readOnly:true,
						fieldStyle:'background:#B8B8B8 ;font-weight:bold;',
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
						html:'<div style="color:#FF0000"></div>',
					}]
				},{
					columnWidth:.6,
					border:false,
					layout: 'anchor',
					defaultType: 'combobox',
					items:[{		
						xtype:'combobox',
						fieldLabel:'Perusahaan BS',
						width:450,
						labelWidth:120,
						id:'cb_perusahaan',
						editable:false,
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
						html:'<div style="color:#FF0000"></div>',
					}]
				},{
					columnWidth:.6,
					border:false,
					layout: 'anchor',
					defaultType: 'combobox',
					items:[{		
						xtype:'textfield',
						fieldLabel:'Penanggung jawab',
						width:450,
						labelWidth:120,
						id:'cb_pj',
						minChars:1,
						editable:false,
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
						html:'<div style="color:#FF0000"></div>',
					}]
				},{
					columnWidth:.6,
					border:false,
					layout: 'anchor',
					defaultType: 'combobox',
					items:[{		
						xtype:'textfield',
						fieldLabel:'Tipe BS',
						width:300,
						labelWidth:120,
						id:'cb_tipe',
						editable:false,
						readOnly:true,
						fieldStyle:'background:#B8B8B8 ;font-weight:bold;'
					}]
				}]	
			}
			
			
			, {
				layout:'column',
				border:false,
				items:[{
					columnWidth:.02,
					border:false,
					layout: 'anchor',
					defaultType: 'label',
					items:[{
						xtype:'label',
						id:'lbl_nama_external',
						html:'<div style="color:#FF0000"></div>',
					}]
				},{
					columnWidth:.6,
					border:false,
					layout: 'anchor',
					defaultType: 'combobox',
					items:[{		
						xtype:'textfield',
						fieldLabel:'Nama External',
						displayField: 'DATA_NAME',
						valueField: 'DATA_VALUE',
						width:450,
						labelWidth:120,
						id:'tf_nama_external',
						fieldStyle:'background:#B8B8B8 ;font-weight:bold;'
					}]
				}]	
			}
			
			, {
				layout:'column',
				border:false,
				items:[{
					columnWidth:.02,
					border:false,
					layout: 'anchor',
					defaultType: 'label',
					items:[{
						xtype:'label',
						id:'lbl_png_jwb_external',
						html:'<div style="color:#FF0000"></div>',
					}]
				},{
					columnWidth:.6,
					border:false,
					layout: 'anchor',
					defaultType: 'combobox',
					items:[{		
						xtype:'textfield',
						fieldLabel:'Penanggung Jawab External',
						displayField: 'DATA_NAME',
						valueField: 'DATA_VALUE',
						width:450,
						labelWidth:120,
						id:'tf_png_jwb_external',
						fieldStyle:'background:#B8B8B8 ;font-weight:bold;'
					}]
				}]	
			}
			
			,{
				layout:'column',
				border:false,
				items:[,{
					columnWidth:.02,
					border:false,
					layout: 'anchor',
					defaultType: 'label',
					items:[{
						xtype:'label',
						html:'<div style="color:#FF0000"></div>',
					}]
				},{
					columnWidth:.9,
					border:false,
					layout: 'anchor',
					defaultType: 'numberfield',
					items:[{		
						xtype:'numberfield',
						fieldLabel:'Nominal BS',
						width:230,
						labelWidth:120,
						id:'tf_nominal',
						value: 0,
						fieldStyle:'text-align:right;',
						// editable: false,
						minValue: 0,
						maxValue: 1000000000000,
						allowBlank: false,
						editable:false,
						readOnly:true,
						fieldStyle:'background:#B8B8B8 ;font-weight:bold;'
						// listeners: {
							 // change: function(field,newValue,oldValue){
								// //var hasil = Ext.getCmp('tf_nominal').getValue() / Ext.getCmp('tf_jc').getValue();
								// // console.log(hasil);
								// //Ext.getCmp('tf_nc').setValue(hasil.formatMoney(2, ',', '.'));
								// if(Ext.getCmp('tf_nominal').getValue()>Ext.getCmp('tf_maxnominal').getValue()){
									// var nom = Ext.getCmp('tf_maxnominal').getValue();
									// Ext.getCmp('tf_nominal').setValue(nom);
								// }
							// }
						// }
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
						// html:'<div style="color:#FF0000"></div>',
						html:'<div style="color:#FF0000">*</div>',
					}]
				},{
					columnWidth:.6,
					border:false,
					layout: 'anchor',
					defaultType: 'combobox',
					items:[{		
						xtype:'datefield',
						fieldLabel:'Tgl Jatuh Tempo',
						width:220,
						labelWidth:120,
						id:'tf_tgl_jt',
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
						html:'<div style="color:#FF0000"></div>',
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
						//height:150,
						labelWidth:120,
						id:'tf_ket',
						editable:false,
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
						html:'<div style="color:#FF0000"></div>',
					}]
				},{
					columnWidth:.98,
					border:false,
					layout: 'anchor',
					defaultType: 'combobox',
					items:[{
						xtype:'textfield',
						fieldLabel:'Syarat A',
						width:450,
						//height:150,
						labelWidth:120,
						id:'tf_syarat1',
						//maxValue: 5,
						editable:false,
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
						html:'<div style="color:#FF0000"></div>',
					}]
				},{
					columnWidth:.98,
					border:false,
					layout: 'anchor',
					defaultType: 'combobox',
					items:[{
						xtype:'textfield',
						fieldLabel:'Syarat B',
						width:450,
						//height:150,
						labelWidth:120,
						id:'tf_syarat2',
						//maxValue: 5,
						editable:false,
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
						html:'<div style="color:#FF0000"></div>',
					}]
				},{
					columnWidth:.98,
					border:false,
					layout: 'anchor',
					defaultType: 'combobox',
					items:[{
						xtype:'textfield',
						fieldLabel:'Syarat C',
						width:450,
						//height:150,
						labelWidth:120,
						id:'tf_syarat3',
						//maxValue: 5,
						editable:false,
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
						html:'<div style="color:#FF0000"></div>',
					}]
				},{
					columnWidth:.98,
					border:false,
					layout: 'anchor',
					defaultType: 'combobox',
					items:[{
						xtype:'textfield',
						fieldLabel:'Syarat D',
						width:450,
						//height:150,
						labelWidth:120,
						id:'tf_syarat4',
						//maxValue: 5,
						editable:false,
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
						html:'<div style="color:#FF0000"></div>',
					}]
				},{
					columnWidth:.98,
					border:false,
					layout: 'anchor',
					defaultType: 'combobox',
					items:[{
						xtype:'textfield',
						fieldLabel:'Syarat E',
						width:450,
						//height:150,
						labelWidth:120,
						id:'tf_syarat5',
						//maxValue: 5,
						editable:false,
						readOnly:true,
						fieldStyle:'background:#B8B8B8 ;font-weight:bold;'
					}]
				}]	
			},{
				xtype:'label',
				html:'&nbsp',
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
								items:[grid_upload_view]
							}]	
						}]
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
						title: 'Attachment Direksi',
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
								items:[grid_upload_view_hrd]
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
						html:'<div style="color:#FF0000">*</div>',
					}]
				},{
					columnWidth:.6,
					border:false,
					layout: 'anchor',
					defaultType: 'combobox',
					items:[{		
						xtype:'combobox',
						fieldLabel:'Tipe Pencairan',
						width:300,
						labelWidth:120,
						id:'cb_tipe_pencairan',
						store: comboTipePencairan,
						displayField: 'DATA_NAME',
						valueField: 'DATA_VALUE',
						value:'- Pilih -',
						minChars:1,
						editable:false,
						queryMode: 'Local',
						listeners: {
							select:function(f,r,i){
								var tipePencairan=r[0].data.DATA_VALUE;
								
								if( tipePencairan == 'CASH' ) {
									
									Ext.getCmp('lbl_no_rek').hide();
									Ext.getCmp('tf_no_rek').hide();
									Ext.getCmp('tf_no_rek').setValue('');
									
									Ext.getCmp('btn_pilih').setDisabled(true);
									
									Ext.getCmp('cb_rek_transfer').setValue('')
									Ext.getCmp('cb_rek_transfer_nama').setValue('')
									Ext.getCmp('cb_rek_transfer_nomor').setValue('')
									Ext.getCmp('cb_rek_transfer_id').setValue('')
									
								} else {
									
									Ext.getCmp('lbl_no_rek').show();
									Ext.getCmp('tf_no_rek').show();
									var person_id = Ext.getCmp('cb_user_id').getValue();
									
									Ext.getCmp('btn_pilih').setDisabled(false);
									
									Ext.Ajax.request({
										url:'<?php echo 'isi_nama_header.php'; ?>',
											timeout: 500000,
											params:{
												nama_pem:person_id,
											},
											success:function(response){
												var json=Ext.decode(response.responseText);
												var deskripsiasli = json.results;
												var deskripsisplit = deskripsiasli.split('|');
												var rek = deskripsisplit[8];
												Ext.getCmp('tf_no_rek').setValue(rek);
											},
										method:'POST',
									});
								}
								
							}
						},
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
						id:'lbl_no_rek',
						html:'<div style="color:#FF0000">*</div>',
					}]
				},{
					columnWidth:.6,
					border:false,
					layout: 'anchor',
					defaultType: 'combobox',
					items:[{		
						xtype:'textfield',
						fieldLabel:'No Rekening',
						width:400,
						labelWidth:120,
						id:'tf_no_rek',
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
						xtype:'datefield',
						fieldLabel:'Tgl Pencairan',
						width:220,
						labelWidth:120,
						id:'tf_tgl_pencairan',
						value: currentTime,
						//minValue: currentTime,
						renderer: formatDate,
					}]
				}]	
			},
			
			
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
			},
			
			
			
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
						html:'<div style="color:#FF0000"></div>',
					}]
				},{
					columnWidth:.54,
					border:false,
					layout: 'anchor',
					defaultType: 'combobox',
					items:[{
						xtype: 'fieldset',
						flex: 1,
						title: 'Attachment Kasir',
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
				xtype:'panel',
				bodyStyle: 'padding-left: 180px;padding-bottom: 50px;border:none',
				items:[{
					xtype:'button',
					text:'Save',
					id:'save_btn',
					width:100,
					handler:function() {
						
						if ( Ext.getCmp('tf_no_rek').getValue().length <= 100 )
						{
							
							var isiUpload = store_upload.count();
							
							
							// Jika tipe BS adalah EXTERNAL 
							
							if ( Ext.getCmp('cb_tipe').getValue() == 'EXTERNAL' ) {

								if (	Ext.getCmp('tf_tgl_pencairan').getValue() != null
										&& Ext.getCmp('tf_tgl_pencairan').getValue() != '' 
										&& Ext.getCmp('cb_tipe_pencairan').getValue() != '' 
										&& Ext.getCmp('cb_tipe_pencairan').getValue() != '- Pilih -' 
										&& ( Ext.getCmp('cb_tipe_pencairan').getValue () == 'CASH' || Ext.getCmp('tf_no_rek').getValue() != '' ) 
									)
								{
									
									// Jika tipe pencairan transfer maka nomor rekening perusahaan harus diisi 
									
									if ( Ext.getCmp('cb_tipe_pencairan').getValue () == 'TRANSFER' 
											&& Ext.getCmp('cb_rek_transfer').getValue() != ''
											&& Ext.getCmp('cb_rek_transfer').getValue() != null
										) 
									{

									
										var check_tipe = 'N';
										
										if ( Ext.getCmp('cb_tipe').getValue() == 'OPERASIONAL KHUSUS' ) {
											
											check_tipe = 'Y';
											
										} else {
											
											if ( Ext.getCmp('tf_tgl_jt').getValue() == null || Ext.getCmp('tf_tgl_jt').getValue() == ''  ) {
												
												check_tipe = 'N';
												
											} else {
												
												check_tipe = 'Y';
												
											}
										
										}
										
										if ( check_tipe == 'N' ) {
											
											alertDialog('Peringatan','Kolom bertanda bintang (*) harus diisi.');
											
										} else {
										
										
										
											// console.log( 'Masuk transfer' );
											
											// var i = 0;
											// var arrDataFile = new Array();
											// store_upload.each(
												// function(record) {
													// arrDataFile[i]=record.get('HD_ID');
													// i++;
												// }
											// );
											
											// if ( isiUpload > 0 ) {
												
												Ext.Ajax.request ( {
													
													// url:'<?php echo 'simpan_bs_v2.php'; ?>',
													
													url:'<?php echo 'simpan_bs.php'; ?>',
													
													params:{
														hdid:Ext.getCmp('tf_hdid').getValue(),
														typeform:Ext.getCmp('tf_typeform').getValue(),
														//tgl:Ext.Date.dateFormat(Ext.getCmp('tf_tp').getValue(), 'Y-m-d'),
														person_id:Ext.getCmp('tf_emp_id').getValue(),
														tipe:Ext.getCmp('cb_tipe_pencairan').getValue(),
														no_rek:Ext.getCmp('tf_no_rek').getValue(),
														tgl:Ext.getCmp('tf_tgl_pencairan').getValue(),
														tgl_jt:Ext.getCmp('tf_tgl_jt').getValue(),
														
														// arrDataFile:Ext.encode(arrDataFile),
														
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
															
															alertDialog('Sukses', "Data tersimpan dengan nomor : " + transNo + ".");
															
															Ext.clearForm();
														} else if(json.rows == "tidakadahakakses") {
															alertDialog('Kesalahan', "Anda tidak mempunyai hak akses untuk melakukan approval.");
														} else {
															alertDialog('Kesalahan', "Data gagal disimpan. ");
														} 
													},
													failure:function(error){
														alertDialog('Kesalahan','Data gagal disimpan');
													}
												}); 
											
											// }else {
											//	alertDialog('Peringatan','Attachment harus diisi.');
											// } 
											
										}
										
										
										
									} else {
										
										if ( Ext.getCmp('cb_tipe_pencairan').getValue () == 'CASH'
												&& Ext.getCmp('tf_tgl_pencairan').getValue() != null
												&& Ext.getCmp('tf_tgl_pencairan').getValue() != '' 
											) 
										{
											
											// console.log( 'Masuk cash' +  Ext.getCmp('tf_tgl_pencairan').getValue() );
											
											// var i = 0;
											// var arrDataFile = new Array();
											// store_upload.each(
												// function(record) {
													// arrDataFile[i]=record.get('HD_ID');
													// i++;
												// }
											// );
											
											// if ( isiUpload > 0 ) {


											var check_tipe = 'N';
											
											if ( Ext.getCmp('cb_tipe').getValue() == 'OPERASIONAL KHUSUS' ) {
												
												check_tipe = 'Y';
												
											} else {
												
												if ( Ext.getCmp('tf_tgl_jt').getValue() == null || Ext.getCmp('tf_tgl_jt').getValue() == ''  ) {
													
													check_tipe = 'N';
													
												} else {
													
													check_tipe = 'Y';
													
												}
											
											}
											
											if ( check_tipe == 'N' ) {
												
												alertDialog('Peringatan','Kolom bertanda bintang (*) harus diisi.');
												
											} else {
											
												Ext.Ajax.request({
													
													// url:'<?php echo 'simpan_bs_v2.php'; ?>',
													
													url:'<?php echo 'simpan_bs.php'; ?>',
													
													params:{
														hdid:Ext.getCmp('tf_hdid').getValue(),
														typeform:Ext.getCmp('tf_typeform').getValue(),
														//tgl:Ext.Date.dateFormat(Ext.getCmp('tf_tp').getValue(), 'Y-m-d'),
														person_id:Ext.getCmp('tf_emp_id').getValue(),
														tipe:Ext.getCmp('cb_tipe_pencairan').getValue(),
														no_rek:Ext.getCmp('tf_no_rek').getValue(),
														tgl:Ext.getCmp('tf_tgl_pencairan').getValue(),
														tgl_jt:Ext.getCmp('tf_tgl_jt').getValue(),
														
														// arrDataFile:Ext.encode(arrDataFile),
														
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
															
															alertDialog('Sukses', "Data tersimpan dengan nomor : " + transNo + ".");
															
															Ext.clearForm();
														} else if(json.rows == "tidakadahakakses") {
															alertDialog('Kesalahan', "Anda tidak mempunyai hak akses untuk melakukan approval.");
														} else {
															alertDialog('Kesalahan', "Data gagal disimpan. ");
														} 
													},
													failure:function(error){
														alertDialog('Kesalahan','Data gagal disimpan');
													}
												}); 
											
											}
											
											// }else {
											//	alertDialog('Peringatan','Attachment harus diisi.');
											// } 
											
											
											
										} else {
											
											alertDialog('Peringatan','Mandatory belum diisi.');
											
										}
										
									}
									
								} else {
									alertDialog('Peringatan','Mandatory belum diisi.');
								}
								
							
							// Jika tipe BS adalah selain EXTERNAL 
							
							} else {
								
							
								if (	Ext.getCmp('cb_user').getValue() != '' 
										&& Ext.getCmp('tf_tgl_pencairan').getValue() != null
										&& Ext.getCmp('tf_tgl_pencairan').getValue() != '' 
										&& Ext.getCmp('cb_tipe_pencairan').getValue() != '' 
										&& Ext.getCmp('cb_tipe_pencairan').getValue() != '- Pilih -' 
										&& ( Ext.getCmp('cb_tipe_pencairan').getValue () == 'CASH' || Ext.getCmp('tf_no_rek').getValue() != '' ) 
									)
								{
									
									// Jika tipe pencairan transfer maka nomor rekening perusahaan harus diisi 
									
									if ( Ext.getCmp('cb_tipe_pencairan').getValue () == 'TRANSFER' 
											&& Ext.getCmp('cb_rek_transfer').getValue() != ''
											&& Ext.getCmp('cb_rek_transfer').getValue() != null
										) 
									{

									
										var check_tipe = 'N';
										
										if ( Ext.getCmp('cb_tipe').getValue() == 'OPERASIONAL KHUSUS' ) {
											
											check_tipe = 'Y';
											
										} else {
											
											if ( Ext.getCmp('tf_tgl_jt').getValue() == null || Ext.getCmp('tf_tgl_jt').getValue() == ''  ) {
												
												check_tipe = 'N';
												
											} else {
												
												check_tipe = 'Y';
												
											}
										
										}
										
										if ( check_tipe == 'N' ) {
											
											alertDialog('Peringatan','Kolom bertanda bintang (*) harus diisi.');
											
										} else {
										
										
										
											// console.log( 'Masuk transfer' );
											
											// var i = 0;
											// var arrDataFile = new Array();
											// store_upload.each(
												// function(record) {
													// arrDataFile[i]=record.get('HD_ID');
													// i++;
												// }
											// );
											
											// if ( isiUpload > 0 ) {
												
												Ext.Ajax.request ( {
													
													// url:'<?php echo 'simpan_bs_v2.php'; ?>',
													
													url:'<?php echo 'simpan_bs.php'; ?>',
													
													params:{
														hdid:Ext.getCmp('tf_hdid').getValue(),
														typeform:Ext.getCmp('tf_typeform').getValue(),
														//tgl:Ext.Date.dateFormat(Ext.getCmp('tf_tp').getValue(), 'Y-m-d'),
														person_id:Ext.getCmp('tf_emp_id').getValue(),
														tipe:Ext.getCmp('cb_tipe_pencairan').getValue(),
														no_rek:Ext.getCmp('tf_no_rek').getValue(),
														tgl:Ext.getCmp('tf_tgl_pencairan').getValue(),
														tgl_jt:Ext.getCmp('tf_tgl_jt').getValue(),
														
														// arrDataFile:Ext.encode(arrDataFile),
														
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
															
															alertDialog('Sukses', "Data tersimpan dengan nomor : " + transNo + ".");
															
															Ext.clearForm();
														} else if(json.rows == "tidakadahakakses") {
															alertDialog('Kesalahan', "Anda tidak mempunyai hak akses untuk melakukan approval.");
														} else {
															alertDialog('Kesalahan', "Data gagal disimpan. ");
														} 
													},
													failure:function(error){
														alertDialog('Kesalahan','Data gagal disimpan');
													}
												}); 
											
											// }else {
											//	alertDialog('Peringatan','Attachment harus diisi.');
											// } 
											
										}
										
										
										
									} else {
										
										if ( Ext.getCmp('cb_tipe_pencairan').getValue () == 'CASH'
												&& Ext.getCmp('tf_tgl_pencairan').getValue() != null
												&& Ext.getCmp('tf_tgl_pencairan').getValue() != '' 
											) 
										{
											
											// console.log( 'Masuk cash' +  Ext.getCmp('tf_tgl_pencairan').getValue() );
											
											// var i = 0;
											// var arrDataFile = new Array();
											// store_upload.each(
												// function(record) {
													// arrDataFile[i]=record.get('HD_ID');
													// i++;
												// }
											// );
											
											// if ( isiUpload > 0 ) {


											var check_tipe = 'N';
											
											if ( Ext.getCmp('cb_tipe').getValue() == 'OPERASIONAL KHUSUS' ) {
												
												check_tipe = 'Y';
												
											} else {
												
												if ( Ext.getCmp('tf_tgl_jt').getValue() == null || Ext.getCmp('tf_tgl_jt').getValue() == ''  ) {
													
													check_tipe = 'N';
													
												} else {
													
													check_tipe = 'Y';
													
												}
											
											}
											
											if ( check_tipe == 'N' ) {
												
												alertDialog('Peringatan','Kolom bertanda bintang (*) harus diisi.');
												
											} else {
											
												Ext.Ajax.request({
													
													// url:'<?php echo 'simpan_bs_v2.php'; ?>',
													
													url:'<?php echo 'simpan_bs.php'; ?>',
													
													params:{
														hdid:Ext.getCmp('tf_hdid').getValue(),
														typeform:Ext.getCmp('tf_typeform').getValue(),
														//tgl:Ext.Date.dateFormat(Ext.getCmp('tf_tp').getValue(), 'Y-m-d'),
														person_id:Ext.getCmp('tf_emp_id').getValue(),
														tipe:Ext.getCmp('cb_tipe_pencairan').getValue(),
														no_rek:Ext.getCmp('tf_no_rek').getValue(),
														tgl:Ext.getCmp('tf_tgl_pencairan').getValue(),
														tgl_jt:Ext.getCmp('tf_tgl_jt').getValue(),
														
														// arrDataFile:Ext.encode(arrDataFile),
														
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
															alertDialog('Sukses', "Data tersimpan dengan nomor : " + transNo + ".");
															
															Ext.clearForm();
														} else if(json.rows == "tidakadahakakses") {
															alertDialog('Kesalahan', "Anda tidak mempunyai hak akses untuk melakukan approval.");
														} else {
															alertDialog('Kesalahan', "Data gagal disimpan. ");
														} 
													},
													failure:function(error){
														alertDialog('Kesalahan','Data gagal disimpan');
													}
												}); 
											
											}
											
											// }else {
											//	alertDialog('Peringatan','Attachment harus diisi.');
											// } 
											
											
											
										} else {
											
											alertDialog('Peringatan','Mandatory belum diisi.');
											
										}
										
									}
									
								} else {
									alertDialog('Peringatan','Mandatory belum diisi.');
								}
								
							}
							
							
						} else {
							
							alertDialog( 'Kesalahan', 'Panjang isian No Rekening maksimal 100 karakter.' );	
							
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
					xtype:'label',
					html:'&nbsp',
				},{
					xtype:'button',
					text:'Print Lembar BS',
					width:100,
					handler:function(){
						var id_print = Ext.getCmp('tf_hdid').getValue();
						if(id_print > 0){
							
							window.open("isi_pdf_bs.php?hdid=" + id_print + "&hrd_id="+<?php echo $emp_id; ?>);
							
							// window.open("isi_pdf_bs_v2.php?hdid=" + id_print + "&hrd_id="+<?php echo $emp_id; ?>);
							
						}else{
							alertDialog('Peringatan','Blm ada data yang dipilih.');
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