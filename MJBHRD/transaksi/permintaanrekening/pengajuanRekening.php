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

var store_popuptransaksi=new Ext.data.JsonStore({
		id:'store_popuptransaksi_id',
		pageSize: 100,
		fields:[{
			name:'HD_ID'
		},{
			name:'DATA_NOREQ'
		},{
			name:'DATA_PEMBUAT'
		},{
			name:'DATA_NAMAKARYAWAN'
		},{
			name:'DATA_DEPT'
		},{
			name:'DATA_POS'
		},{
			name:'DATA_LOKASI'
		},{
			name:'DATA_STATUS'
		},{
			name:'DATA_ALASAN'
		},{
			name:'DATA_STATUSREQUEST'
		}],
		 proxy:{
			type:'ajax',
			url:'isi_grid_permintaan.php', 
			reader: {
				root: 'rows',
			},
		},
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
		width:45
	},{
		dataIndex:'HD_ID',
		header:'id',
		width:100,
		hidden:true
	},{
		dataIndex:'DATA_NOREQ',
		header:'No Request',
		width:150,
		//hidden:true
	},{
		dataIndex:'DATA_PEMBUAT',
		header:'Pembuat',
		width:220,
		//hidden:true
	},{
		dataIndex:'DATA_NAMAKARYAWAN',
		header:'Nama Karyawan',
		width:220,
		//hidden:true
	},{
		dataIndex:'DATA_DEPT',
		header:'Departemen',
		width:100,
		//hidden:true
	},{
		dataIndex:'DATA_POS',
		header:'Posisi',
		width:100,
		//hidden:true
	},{
		dataIndex:'DATA_LOKASI',
		header:'Lokasi',
		width:100,
		//hidden:true
	},{
		dataIndex:'DATA_STATUS',
		header:'Aktif',
		width:50,
		//hidden:true
	},{
		dataIndex:'DATA_ALASAN',
		header:'Alasan',
		width:300,
		hidden:true
	},{
		dataIndex:'DATA_STATUSREQUEST',
		header:'Status Request',
		width:75,
		hidden:true
	}],
listeners: {
		dblclick: {
			element: 'body', //bind to the underlying body property on the panel
			fn: function(){
				Ext.getCmp('hd_id_user').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('HD_ID'));
				Ext.getCmp('tf_no_req').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_NOREQ'));
				Ext.getCmp('tf_namaUser').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_PEMBUAT'));
				Ext.getCmp('cb_nama_kar').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_NAMAKARYAWAN'));
				Ext.getCmp('tf_dept').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_DEPT'));
				Ext.getCmp('tf_pos').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_POS'));
				Ext.getCmp('tf_loc').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_LOKASI'));
				Ext.getCmp('tf_alasan').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_ALASAN'));
				var vstatus=grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_STATUS');
				if(vstatus == 'Y'){
					Ext.getCmp('cb_status').setValue(true);
				} else {
					Ext.getCmp('cb_status').setValue(false);
				}
				Ext.getCmp('tf_typeform').setValue('edit');
				Ext.getCmp('tf_no_req').setReadOnly(true);
				Ext.getCmp('tf_namaUser').setReadOnly(true);
				Ext.getCmp('cb_nama_kar').setReadOnly(true);
				Ext.getCmp('tf_dept').setReadOnly(true);
				Ext.getCmp('tf_pos').setReadOnly(true);
				Ext.getCmp('tf_loc').setReadOnly(true);
				Ext.getCmp('tf_alasan').setDisabled(false);
				Ext.getCmp('cb_status').setDisabled(false);
				PopupSIK.hide();
			}
		}
	}
});


var comboNamakar = Ext.create('Ext.data.Store', {
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

var PopupSIK=Ext.create('Ext.Window', {
title: 'Cari Permintaan Rekening',
width: 700,
height: 350,							
layout: 'fit',
closeAction:'hide',
items: grid_popuptransaksi
});

function setupApp(){
	Ext.QuickTips.init();	
	var currentTime = new Date();
	var contentPanel = Ext.create('Ext.panel.Panel',{
			bodyStyle: 'spacing: 10px;border:none',
			items:[{
				xtype:'label',
				html:'<div align="center"><font size="5"><b>Form Permintaan Pembuatan Rekening</b></font></div>',
			},{
				xtype:'label',
				html:'&nbsp',
			},{		
				xtype:'textfield',
				fieldLabel:'userid',
				width:350,
				labelWidth:75,
				id:'hd_id_user',
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
						html:'<div style="color:#FF0000"></div>',
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
						labelWidth:125,
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
						text: 'Existing',
						width:75,
						handler:function(){
						    store_popuptransaksi.load();
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
						html:'<div style="color:#FF0000"></div>',
					}]
				},{
					columnWidth:.48,
					border:false,
					layout: 'anchor',
					defaultType: 'combobox',
					items:[{		
						xtype:'textfield',
						fieldLabel:'Pembuat',
						width:500,
						labelWidth:125,
						id:'tf_namaUser',
						value:"<?PHP echo $emp_name ?>",
						editable: false,
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
					columnWidth:.47,
					border:false,
					layout: 'anchor',
					defaultType: 'combobox',
					items:[{		
						xtype:'combobox',
						editable:true,
						fieldLabel:'Nama Karyawan',
						store: comboNamakar,
						displayField: 'DATA_NAME',
						valueField: 'DATA_VALUE',
						width:400,
						labelWidth:125,
						id:'cb_nama_kar',
						value:'',
						emptyText : '- Pilih -',
						//editable: false 
						enableKeyEvents : true,
						minChars:1,
						listeners: {
							select:function(f,r,i){
								var nama_pem=r[0].data.DATA_NAME;
									// console.log('MASUK');
								Ext.Ajax.request({
									url:'<?php echo 'isi_departemen.php'; ?>',
										params:{
											nama_pem:nama_pem,
										},
										success:function(response){
											var json=Ext.decode(response.responseText);
											var deskripsiasli = json.results;
											var deskripsisplit = deskripsiasli.split('.');
											var nama_dept = deskripsisplit[1];
											Ext.getCmp('tf_dept').setValue(deskripsiasli);
										},
									method:'POST',
								});
								Ext.Ajax.request({
									url:'<?php echo 'isi_posisi.php'; ?>',
										params:{
											nama_pem:nama_pem,
										},
										success:function(response){
											var json=Ext.decode(response.responseText);
											var deskripsiasli = json.results;
											var deskripsisplit = deskripsiasli.split('.');
											var nama_dept = deskripsisplit[1];
											Ext.getCmp('tf_pos').setValue(deskripsiasli);
										},
									method:'POST',
								});
								Ext.Ajax.request({
									url:'<?php echo 'isi_lokasi.php'; ?>',
										params:{
											nama_pem:nama_pem,
										},
										success:function(response){
											var json=Ext.decode(response.responseText);
											var deskripsiasli = json.results;
											var deskripsisplit = deskripsiasli.split('.');
											var nama_dept = deskripsisplit[1];
											Ext.getCmp('tf_loc').setValue(deskripsiasli);
										},
									method:'POST',
								});
							},
							keypress : {
								element: 'body',
								fn: function(){
									// console.log('MASUK');
									var pjname=Ext.getCmp('cb_nama_kar').getValue();
									combopj.load({
										params : {
											pjname:pjname,
										}
									});
								}
							},
							change: function(field,newValue,oldValue){
									Ext.getCmp('tf_dept').setValue('');
									Ext.getCmp('tf_pos').setValue('');
									Ext.getCmp('tf_loc').setValue('');
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
						html:'<div style="color:#FF0000"></div>',
					}]
				},{
					columnWidth:.48,
					border:false,
					layout: 'anchor',
					defaultType: 'combobox',
					items:[{	
						xtype:'textfield',
						fieldLabel:'Departemen',
						width:350,
						labelWidth:125,
						id:'tf_dept',
						editable: false,
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
						html:'<div style="color:#FF0000"></div>',
					}]
				},{
					columnWidth:.48,
					border:false,
					layout: 'anchor',
					defaultType: 'combobox',
					items:[{	
						xtype:'textfield',
						fieldLabel:'Posisi',
						width:350,
						labelWidth:125,
						id:'tf_pos',
						editable: false,
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
						html:'<div style="color:#FF0000"></div>',
					}]
				},{
					columnWidth:.48,
					border:false,
					layout: 'anchor',
					defaultType: 'combobox',
					items:[{	
						xtype:'textfield',
						fieldLabel:'Lokasi',
						width:350,
						labelWidth:125,
						id:'tf_loc',
						editable: false,
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
					columnWidth:.6,
					border:false,
					layout: 'anchor',
					defaultType: 'combobox',
					items:[{	
						xtype:'textareafield',
						fieldLabel:'Alasan',
						width:500,
						labelWidth:125,
						id:'tf_alasan',
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
					columnWidth:.48,
					border:false,
					layout: 'anchor',
					defaultType: 'combobox',
					items:[{	
						xtype:'checkbox',
						fieldLabel:'Aktif',
						width:100,
						labelWidth:125,
						id:'cb_status',
						inputValue: 'Y',
						checked: true,
					}]
				}]
			},{
				xtype:'label',
				html:'&nbsp',
			},{
				xtype:'panel',
				bodyStyle: 'padding-left: 145px;padding-bottom: 50px;border:none',
				items:[{
					xtype:'button',
					text:'Simpan',
					width:50,
					handler:function(){
						if(Ext.getCmp('cb_nama_kar').getValue() != ''){
							if(Ext.getCmp('tf_alasan').getValue() != ''){
							Ext.Ajax.request({
								url:'<?php echo 'simpan_perminta.php'; ?>',
								params:{
									hd_id:Ext.getCmp('hd_id_user').getValue(),
									formtype:Ext.getCmp('tf_typeform').getValue(),
									emp_id:Ext.getCmp('cb_nama_kar').getValue(),
									alasan:Ext.getCmp('tf_alasan').getValue(),
									aktif:Ext.getCmp('cb_status').getValue(),
								},
								method:'POST',
								success:function(response){
									var json=Ext.decode(response.responseText);
									if (json.rows == "sukses"){
											alertDialog('Sukses','Data berhasil disimpan');
											var hd_id =json.hdid2;
											if(Ext.getCmp('cb_status').getValue() == 'false'){
												window.open("isi_pdf_form_permintaan.php?hdid=" + hd_id + "");
											}
												Ext.getCmp('tf_no_req').setValue('');
												Ext.getCmp('cb_nama_kar').setValue('');
												Ext.getCmp('tf_dept').setValue('');
												Ext.getCmp('tf_pos').setValue('');
												Ext.getCmp('tf_loc').setValue('');
												Ext.getCmp('tf_alasan').setValue('');
												Ext.getCmp('hd_id_user').setValue('');
												Ext.getCmp('tf_typeform').setValue('tambah');
									 }else {
										alertDialog('Kesalahan', "Data gagal disimpan");
									}
								},
								failure:function(result,action){
									alertDialog('Kesalahan','Data gagal disimpan');
								}
							});
						}
							else {
								alertDialog('Kesalahan','Alasan Wajib Diisi');
							}
						}
						else {
							alertDialog('Kesalahan','Nama Karyawan Wajib Diisi');
						}
					}
				},{
					xtype:'label',
					html:'&nbsp',
				},{
					xtype:'button',
					text:'Cancel',
					width:50,
					handler:function(){
						Ext.getCmp('tf_no_req').setValue('');
						Ext.getCmp('cb_nama_kar').setValue('');
						Ext.getCmp('cb_nama_kar').setReadOnly(false);
						Ext.getCmp('tf_dept').setValue('');
						Ext.getCmp('tf_pos').setValue('');
						Ext.getCmp('tf_loc').setValue('');
						Ext.getCmp('tf_alasan').setValue('');
						//Ext.getCmp('cb_status').setValue('');
						Ext.getCmp('hd_id_user').setValue('');
						Ext.getCmp('tf_typeform').setValue('tambah');
					}
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
			}],
		});
   contentPanel.render('page');
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