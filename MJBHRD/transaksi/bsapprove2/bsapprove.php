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
			url:'<?PHP echo PATHAPP ?>/combobox/combobox_dept_id2.php', 
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
			{"DATA_NAME":"SPPD", "DATA_VALUE":"SPPD"},
			{"DATA_NAME":"OPERASIONAL", "DATA_VALUE":"OPERASIONAL"},
			{"DATA_NAME":"PERSONAL", "DATA_VALUE":"PERSONAL"}
		]
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
								url: '<?PHP echo PATHAPP ?>/transaksi/bskaryawan/uploadattachment.php', 
								method:'POST', 
								waitTitle:'Connecting', 
								waitMsg:'Sending data...',
								
								success:function(fp, o){ 											
									var sembarang=Ext.decode(o.response.responseText);		
									//console.log(sembarang);
									if(sembarang.results=='sukses'){
										store_upload.setProxy({
											type:'ajax',
											url:'<?PHP echo PATHAPP ?>/transaksi/bskaryawan/isi_grid_upload.php?id_temp='+sembarang.id_temp,
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
			name:'DATA_ALASAN'
		},{
			name:'DATA_ATTACHMENT'
		},{
			name:'DATA_ATTACHMENT_HRD'
		},{
			name:'DATA_ATTACH'
		},{
			name:'DATA_TIPE_BS'
		},{
			name:'DATA_PERUSAHAAN_BS'
		},{
			name:'DATA_TGL_JT', type:'date'
		}
		,{
			name:'DATA_TINGKAT_DECODE'
		}],
		proxy:{
			type:'ajax',
			
			url:'isi_grid.php', 
			
			// url:'isi_grid_v2.php',
			
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
	var sm = Ext.create('Ext.selection.CheckboxModel', {
		checkOnly: true,
		//injectCheckbox:'last',
		//pruneRemoved: false,
		listeners: {
	        select: function (sm, idx, rec) {
	            //alert(sm);
	        },
    	},
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
		selModel:sm,
        loadMask: true,
        columns: [{
			dataIndex:'HD_ID',
			header:'No.',
			hidden:true,
			width:50,
		},{
			dataIndex:'DATA_NO_BS',
			header:'Nomor BS',
			//hidden:true,
			width:200,
		},{
			dataIndex:'DATA_TINGKAT_DECODE',
			header:'Posisi Appr',
			//hidden:true,
			width:120,
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
			header:'Lokasi',
			//hidden:true,
			width:75,
		},{
			dataIndex:'DATA_PERUSAHAAN_BS',
			header:'Perusahaan BS',
			//hidden:true,
			width:125,
		},{
			dataIndex:'DATA_TIPE_BS',
			header:'Tipe BS',
			//hidden:true,
			width:100,
		},{
			dataIndex:'DATA_NOMINAL',
			header:'Nominal',
			//hidden:true,
			width:75,
		},{
			dataIndex:'DATA_TGL_JT',
			header:'Tgl Jatuh tempo',
			renderer: formatDate,
			// hidden:true,
			// selectOnFocus: true,
			// renderer: Ext.util.Format.dateRenderer('g:i A')
			width:100,
            field: {
				// xtype:'textfield',
				xtype:'datefield',
				id:'tf_tgl_jt_grid',
				format: 'Y-m-d',
				minValue: currentTime,
				// value: currentTime,
				// value: DATA_TGL_JT,
            }
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
			dataIndex:'DATA_ATTACHMENT_HRD',
			header:'Attachment HRD',
			//hidden:true,
			width:150,
		}, {
            header: 'Ket Disapprove',
            dataIndex: 'DATA_ALASAN',
			width:250,
            field: {
				xtype:'textfield',
				id:'tf_alasan',
            }
        }
		// , {
            // header: 'Upload',
            // dataIndex: 'DATA_ATTACH',
			// width:50,
            // renderer: function(value, m, record) { 
                // return "<img style='cursor:pointer;'src='../../media/css/images/main-icon/toolbar/upload.png' />"; 
            // } 
        // }
		],
        plugins: [cellEditing],
		// listeners:{
			// cellclick:function(grid,row,col){
				// //alert(col);
				// if (col==13) {
					// upload_panel.show();
				// }
			// }
		// } 
    });
	var contentPanel = Ext.create('Ext.panel.Panel',{
			//title:'Form Input Tanda Terima',
			//region:'center',
			bodyStyle: 'spacing: 10px;border:none',
			items:[{
				xtype:'label',
				html:'<div align="center"><font size="5"><b>Approval Bon Sementara (BS)</b></font></div>',
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
						value:'',
						emptyText : '- Pilih -',
						// queryMode : 'local',
						//editable: false 
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
					columnWidth:.4,
					border:false,
					layout: 'anchor',
					defaultType: 'combobox',
					items:[{		
						xtype:'combobox',
						fieldLabel:'Departemen',
						store: comboDept,
						displayField: 'DATA_NAME',
						valueField: 'DATA_VALUE',
						width:340,
						labelWidth:100,
						id:'cb_dept',
						value:'',
						emptyText : '- Pilih -',
						// queryMode : 'local',
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
						width:340,
						labelWidth:100,
						id:'cb_tipe',
						store: comboTipeBS,
						displayField: 'DATA_NAME',
						valueField: 'DATA_VALUE',
						//value:'- Pilih -',
						emptyText : '- Pilih -',
						minChars:1,
						//editable:false,
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
							maskgrid = new Ext.LoadMask(Ext.getCmp('grid_detil_id'), {msg: "Memuat . . ."});
							maskgrid.show();
							store_detil.load({
								params:{
									tglfrom:Ext.getCmp('tf_tgl_from').getRawValue(),
									tglto:Ext.getCmp('tf_tgl_to').getRawValue(),
									pemohon:Ext.getCmp('cb_namaKaryawan').getValue(),
									dept:Ext.getCmp('cb_dept').getValue(),
									noBS:Ext.getCmp('cb_nodok').getRawValue(),
									tipeBS:Ext.getCmp('cb_tipe').getValue(),
								}
							});
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
							Ext.getCmp('tf_tgl_from').setValue(currentTime);
							Ext.getCmp('tf_tgl_to').setValue(currentTime);
							Ext.getCmp('cb_namaKaryawan').setValue(''),
							Ext.getCmp('cb_dept').setValue(''),
							Ext.getCmp('cb_nodok').setValue(''),
							Ext.getCmp('cb_tipe').setValue(''),
							store_detil.load({
								params:{
									tglfrom:Ext.getCmp('tf_tgl_from').getRawValue(),
									tglto:Ext.getCmp('tf_tgl_to').getRawValue(),
									pemohon:Ext.getCmp('cb_namaKaryawan').getValue(),
									dept:Ext.getCmp('cb_dept').getValue(),
									noBS:Ext.getCmp('cb_nodok').getRawValue(),
									tipeBS:Ext.getCmp('cb_tipe').getValue(),
								}
							});
						} 
					}]
				}]	
			},{
				xtype:'label',
				html:'&nbsp',
			}, grid_detil ,{
				xtype:'label',
				html:'&nbsp',
			},{
				xtype:'panel',
				bodyStyle: 'padding-left: 300px;padding-bottom: 50px;border:none',
				items:[{
					xtype:'button',
					text:'Approve',
					width:80,
					handler:function(){
						var data=sm.getSelection();
						
						var arrTransID = new Array();
						var arrAlasan = new Array();
						var arrTglJT = new Array();
						
						var i=0;
						var countGrid = 0;
						
						for(var i in data) {
							arrTransID[i]=data[i].get('HD_ID');
							arrAlasan[i]=data[i].get('DATA_ALASAN');
							countGrid++;
						}
						
						
						var j = 0;
						var validasiTglJT = 0;
						
						for(var j in data) {
							
							arrTransID[j]=data[j].get('HD_ID');
							arrTglJT[j]=data[j].get('DATA_TGL_JT');
							countGrid++;
							
							if( data[j].get('DATA_TGL_JT') == '' || data[j].get('DATA_TGL_JT') == null ) {
								validasiTglJT++;
							}
							
						}
						
						// console.log( 'validasiTglJT: ' + validasiTglJT );
						
						if ( countGrid > 0 ) {
							
							if ( validasiTglJT == 0 ) {
								
								// console.log( 'validasiTglJT == 0 ' );
								
								maskgrid = new Ext.LoadMask(Ext.getCmp('grid_detil_id'), {msg: "Harap Tunggu. . .Sedang Proses Approval . . ."});
								maskgrid.show();
								Ext.Ajax.request({
									
									url:'<?php echo 'simpan_approvebs.php'; ?>',
									
									// url:'<?php echo 'simpan_approvebs_v2.php'; ?>',
									
									timeout: 500000,
									params:{
										typeform:'Setuju',
										arrTransID:Ext.encode(arrTransID),
										arrAlasan:Ext.encode(arrAlasan),
										arrTglJT:Ext.encode(arrTglJT),
									},
									method:'POST',
									success:function(response){
										maskgrid.hide();
										var json=Ext.decode(response.responseText);
										if (json.rows == "sukses"){
											alertDialog('Sukses', "Data tersimpan.");
											rowGrid = 0;
											store_detil.removeAll();
											maskgrid = new Ext.LoadMask(Ext.getCmp('grid_detil_id'), {msg: "Memuat . . ."});
											maskgrid.show();
											store_detil.load({
												params:{
													tglfrom:Ext.getCmp('tf_tgl_from').getRawValue(),
													tglto:Ext.getCmp('tf_tgl_to').getRawValue(),
													pemohon:Ext.getCmp('cb_namaKaryawan').getValue(),
													dept:Ext.getCmp('cb_dept').getValue(),
													noBS:Ext.getCmp('cb_nodok').getRawValue(),
													tipeBS:Ext.getCmp('cb_tipe').getValue(),
												}
											});
											
											if(json.bs500 != 0){
												window.open("isi_pdf_bsapprove.php?hdid=" + json.bs500);
											}
											
										} else if(json.rows == "tidakadahakakses"){
											alertDialog('Kesalahan', "Anda tidak memiliki hak akses untuk melakukan approval atas BS tersebut. ");
										}else {
											alertDialog('Kesalahan', "Data gagal disimpan. ");
										} 
									},
									failure:function(error){
										maskgrid.hide();
										alertDialog('Kesalahan','Data gagal disimpan');
									}
								});
								
							} else {
								alertDialog('Kesalahan','Tanggal Jatuh Tempo belum diisi.');
							}
							
						} else {
							alertDialog('Kesalahan','Data BS belum dicentang.');
						} 
					}
				},{
					xtype:'label',
					html:'&nbsp&nbsp&nbsp',
				},{
					xtype:'button',
					text:'Disapprove',
					width:80,
					handler:function(){
						var data=sm.getSelection();
						var countGrid = 0;
						
						
						var arrTransID = new Array();
						var arrAlasan = new Array();
						var arrTglJT = new Array();
						
						var i=0;
						var validasi=0;
						
						for(var i in data) {
							arrTransID[i]=data[i].get('HD_ID');
							arrAlasan[i]=data[i].get('DATA_ALASAN');
							countGrid++;
							if(data[i].get('DATA_ALASAN')==''){
								validasi++;
							}
						}

						var j = 0;
						var validasiTglJT = 0;
						
						for(var j in data) {
							
							arrTransID[j]=data[j].get('HD_ID');
							arrTglJT[j]=data[j].get('DATA_TGL_JT');
							countGrid++;
							
							if( data[j].get('DATA_TGL_JT') == '' || data[j].get('DATA_TGL_JT') == null ) {
								validasiTglJT++;
							}
						}
						
						// console.log( 'validasiTglJT: ' + validasiTglJT );
						
						if ( validasiTglJT == 0 ) {
							
							// console.log( 'validasiTglJT == 0' );
							
							if ( validasi == 0 ) {
								
								if ( countGrid > 0 ) {
									maskgrid = new Ext.LoadMask(Ext.getCmp('grid_detil_id'), {msg: "Harap Tunggu. . .Sedang Proses Approval . . ."});
									maskgrid.show();
									Ext.Ajax.request({
										
										url:'<?php echo 'simpan_approvebs.php'; ?>',
										
										// url:'<?php echo 'simpan_approvebs_v2.php'; ?>',
										
										params:{
											typeform:'Tolak',
											arrTransID:Ext.encode(arrTransID),
											arrAlasan:Ext.encode(arrAlasan),
											arrTglJT:Ext.encode(arrTglJT),
										},
										timeout: 500000,
										method:'POST',
										success:function(response){
											maskgrid.hide();
											var json=Ext.decode(response.responseText);
											if (json.rows == "sukses"){
												alertDialog('Sukses', "Data tersimpan.");
												rowGrid = 0;
												store_detil.removeAll();
												maskgrid = new Ext.LoadMask(Ext.getCmp('grid_detil_id'), {msg: "Memuat . . ."});
												maskgrid.show();
												store_detil.load({
													params:{
														tglfrom:Ext.getCmp('tf_tgl_from').getRawValue(),
														tglto:Ext.getCmp('tf_tgl_to').getRawValue(),
														pemohon:Ext.getCmp('cb_namaKaryawan').getValue(),
														dept:Ext.getCmp('cb_dept').getValue(),
														noBS:Ext.getCmp('cb_nodok').getRawValue(),
														tipeBS:Ext.getCmp('cb_tipe').getValue(),
													}
												});
											} else if(json.rows == "tidakadahakakses"){
												alertDialog('Kesalahan', "Anda tidak memiliki hak akses untuk melakukan approval atas BS tersebut. ");
											} else {
												alertDialog('Kesalahan', "Data gagal disimpan. ");
											} 
										},
										failure:function(error){
											maskgrid.hide();
											alertDialog('Kesalahan','Data gagal disimpan');
										}
									});
								} else {
									alertDialog('Kesalahan','Data BS belum dicentang.');
								}
								
							} else {
								alertDialog('Kesalahan','Keterangan Disapprove belum diisi.');
							}
							
						} else {
							alertDialog('Kesalahan','Tanggal Jatuh Tempo belum diisi.');
						}
						
						
					}
				}]
			}],
		});	
   contentPanel.render('page');
   Ext.getCmp('tf_tgl_from').setValue(currentTime);
   Ext.getCmp('tf_tgl_to').setValue(currentTime);
   comboPemohon.load();
   store_detil.load({
		params:{
			tglfrom:Ext.getCmp('tf_tgl_from').getRawValue(),
			tglto:Ext.getCmp('tf_tgl_to').getRawValue(),
			pemohon:Ext.getCmp('cb_namaKaryawan').getValue(),
			dept:Ext.getCmp('cb_dept').getValue(),
			noBS:Ext.getCmp('cb_nodok').getRawValue(),
			tipeBS:Ext.getCmp('cb_tipe').getValue(),
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