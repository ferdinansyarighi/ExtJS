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

function setupApp(){
	Ext.QuickTips.init();	
	var store_user=new Ext.data.JsonStore({
		id:'store_user_id',
		pageSize: 50,
		fields:[{
			name:'HD_ID'
		},{
			name:'DATA_PERUSAHAANID'
		},{
			name:'DATA_HRDID'
		},{
			name:'DATA_ACCID'
		},{
			name:'DATA_KSRID'
		},{
			name:'DATA_PERUSAHAANNAME'
		},{
			name:'DATA_HRDNAME'
		},{
			name:'DATA_ACCNAME'
		},{
			name:'DATA_KSRNAME'
		},{
			name:'DATA_STATUS'
		},{
			name:'DATA_CHECKER'
		},{
			name:'DATA_TIPE'
		},{
			name:'DATA_CHECKER_ID'
		}
		],
		 proxy:{
			type:'ajax',
			url:'isi_grid_approval.php', 
			reader: {
				type: 'json',
				root: 'data',
				totalProperty:'total'        
			},
		}
	});
	
	var grid_user=Ext.create('Ext.grid.Panel',{
		id:'grid_user_id',
		region:'center',
		store:store_user,
        columnLines: true,
		height:500,
		autoScroll:true,
		columns:[{
			dataIndex:'HD_ID',
			header:'id',
			width:100,
			hidden:true
		},{
			dataIndex:'DATA_PERUSAHAANID',
			header:'Perusahaan',
			width:225,
			hidden:true
		},{
			dataIndex:'DATA_HRDID',
			header:'HRD',
			width:250,
			hidden:true
		},{
			dataIndex:'DATA_ACCID',
			header:'ACC',
			width:250,
			hidden:true
		},{
			dataIndex:'DATA_KSRID',
			header:'KSR',
			width:250,
			hidden:true
		},{
			dataIndex:'DATA_CHECKER_ID',
			header:'DATA_CHECKER_ID',
			width:250,
			hidden:true
		},{
			dataIndex:'DATA_PERUSAHAANNAME',
			header:'Perusahaan',
			width:160,
			
		},{
			dataIndex:'DATA_TIPE',
			header:'Tipe Transaksi',
			width:100,
			
		},{
			dataIndex:'DATA_HRDNAME',
			header:'Manager HRD',
			width:200,
			
		},{
			dataIndex:'DATA_ACCNAME',
			header:'Manager Finance',
			width:200,
			
		},{
			dataIndex:'DATA_CHECKER',
			header:'Checker',
			width:200,
		},{
			dataIndex:'DATA_KSRNAME',
			header:'Validasi Kasir',
			width:200,
		},{
			dataIndex:'DATA_STATUS',
			header:'Status User',
			width:100
		}],
		listeners: {
			dblclick: {
				element: 'body', //bind to the underlying body property on the panel
				fn: function(){ 
					var hdid=grid_user.getSelectionModel().getSelection()[0].get('HD_ID');
					var statusUser = grid_user.getSelectionModel().getSelection()[0].get('DATA_STATUS');
					Ext.getCmp('hd_id_user').setValue(hdid);
					Ext.getCmp('cb_status').setValue(statusUser);
					Ext.getCmp('tf_formtype').setValue('edit');
					
					// Ext.getCmp('cb_perusahaan').setDisabled(true);
					
					Ext.getCmp('cb_perusahaan').setValue(String(grid_user.getSelectionModel().getSelection()[0].get('DATA_PERUSAHAANID')));
					Ext.getCmp('cb_tipe').setValue((grid_user.getSelectionModel().getSelection()[0].get('DATA_TIPE')));
					
					//Ext.getCmp('cb_hrd').setDisabled(true);
					
					Ext.getCmp('cb_hrd').setValue((grid_user.getSelectionModel().getSelection()[0].get('DATA_HRDID')));
					
					// Ext.getCmp('cb_acc').setDisabled(true);
					
					Ext.getCmp('cb_acc').setValue((grid_user.getSelectionModel().getSelection()[0].get('DATA_ACCID')));
					
					// Ext.getCmp('cb_kasir').setDisabled(true);
					
					Ext.getCmp('cb_kasir').setValue((grid_user.getSelectionModel().getSelection()[0].get('DATA_KSRID')));
					
					
					// Ext.getCmp('cb_tipe').setDisabled(true);
					
					
					
					
					
					if ( Ext.getCmp('cb_tipe').getValue() == 'Pinjaman' ) 
					{
						
						Ext.getCmp('cb_checker').setDisabled(true);
						Ext.getCmp('cb_checker').setValue('');
						
					} else {
						
							if ( Ext.getCmp('cb_tipe').getValue() == 'BS' ) 
							{
								
								Ext.getCmp('cb_checker').setDisabled( false );
								Ext.getCmp('cb_checker').setValue((grid_user.getSelectionModel().getSelection()[0].get('DATA_CHECKER_ID')));
								comboChecker.load();
							}
						
					}
						
					
						
					wind.show();
				}
			}
		}
	});
	
	var statusUser = Ext.create('Ext.data.Store', {
		fields: ['DATA_NAME', 'DATA_VALUE'],
		data : [
			{"DATA_NAME":"ACTIVE", "DATA_VALUE":"ACTIVE"},
			{"DATA_NAME":"INACTIVE", "DATA_VALUE":"INACTIVE"}
		]
	});
	
	var comboTipe = Ext.create('Ext.data.Store', {
		fields: ['DATA_NAME', 'DATA_VALUE'],
		data : [
			{"DATA_NAME":"BS", "DATA_VALUE":"BS"},
			{"DATA_NAME":"Pinjaman", "DATA_VALUE":"Pinjaman"}
		]
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
			}
		}
	});
	
	var comboManagerHRD = Ext.create('Ext.data.Store', {
		fields: [{
			name:'DATA_VALUE'
		},{
			name:'DATA_NAME'
		}],
		proxy:{
			type:'ajax',
			url:'<?PHP echo PATHAPP ?>/combobox/combobox_managerid_hrd.php',
			reader: {
				type: 'json',
				root: 'data', 
				totalProperty:'total'   
			}
		}
	});
	
	var comboManagerACC = Ext.create('Ext.data.Store', {
		fields: [{
			name:'DATA_VALUE'
		},{
			name:'DATA_NAME'
		}],
		proxy:{
			type:'ajax',
			url:'<?PHP echo PATHAPP ?>/combobox/combobox_managerid_acc.php', 
			reader: {
				type: 'json',
				root: 'data', 
				totalProperty:'total'   
			},
		},
		//autoLoad:true
	});
	
	var comboKasir = Ext.create('Ext.data.Store', {
		fields: [{
			name:'DATA_VALUE'
		},{
			name:'DATA_NAME'
		}],
		proxy:{
			type:'ajax',
			url:'<?PHP echo PATHAPP ?>/combobox/combobox_managerid_kasir.php', 
			reader: {
				type: 'json',
				root: 'data', 
				totalProperty:'total'   
			},
		},
		//autoLoad:true
	});

	var comboChecker = Ext.create('Ext.data.Store', {
		fields: [{
			name:'DATA_VALUE'
		},{
			name:'DATA_NAME'
		}],
		proxy:{
			type:'ajax',
			url:'<?PHP echo PATHAPP ?>/combobox/combobox_managerid_kasir.php', 
			reader: {
				type: 'json',
				root: 'data', 
				totalProperty:'total'   
			},
		},
		//autoLoad:true
	});
	
	var wind=Ext.create('Ext.Window', {
		title: 'Form Approval Pinjaman dan BS',
		width: 550,
		// height: 250,
		height: 270,
		layout: 'fit',
		closeAction:'hide',
		items: [{
			xtype:'panel',
			bodyStyle: 'padding-left: 5px;padding-top: 5px;padding-bottom: 10px;border:none',
			items:[{		
				xtype:'textfield',
				fieldLabel:'userid',
				width:350,
				labelWidth:125,
				id:'hd_id_user',
				hidden:true
			}, {		
				xtype:'textfield',
				fieldLabel:'formtype',
				width:350,
				labelWidth:125,
				id:'tf_formtype',
				hidden:true
			}, {		
				xtype:'combobox',
				fieldLabel:'Perusahaan',
				store: comboPerusahaan,
				displayField: 'DATA_NAME',
				valueField: 'DATA_VALUE',
				width:400,
				labelWidth:125,
				id:'cb_perusahaan',
				value : '',
				emptyText : '- Pilih -',
				minChars:1,
				enableKeyEvents : true,
			}
			, {
				xtype:'combobox',
				fieldLabel:'Tipe Transaksi',
				store: comboTipe,
				displayField: 'DATA_NAME',
				valueField: 'DATA_VALUE',
				width:250,
				labelWidth:125,
				id:'cb_tipe',
				value:'BS',
				editable: false,
				value : '',
				emptyText : '- Pilih -',
				listeners: {
					'change':function(){
						
						Ext.getCmp('cb_checker').setValue('');
						
						if ( Ext.getCmp('cb_tipe').getValue() == 'Pinjaman' ) {
							
							Ext.getCmp('cb_checker').setDisabled( true );
							
						} else {
							
							Ext.getCmp('cb_checker').setDisabled( false );
							
						}
						
					}
				}
			}, {		
				xtype:'combobox',
				fieldLabel:'Manager HRD',
				store: comboManagerHRD,
				displayField: 'DATA_NAME',
				valueField: 'DATA_VALUE',
				width:500,
				labelWidth:125,
				id:'cb_hrd',
				//editable: false,
				emptyText : '- Pilih -',
				minChars:1,
				enableKeyEvents : true,
			}, {		
				xtype:'combobox',
				fieldLabel:'Manager Finance',
				store: comboManagerACC,
				displayField: 'DATA_NAME',
				valueField: 'DATA_VALUE',
				width:500,
				labelWidth:125,
				id:'cb_acc',
				//editable: false,
				emptyText : '- Pilih -',
				minChars:1,
				enableKeyEvents : true,
			}, {		
				xtype:'combobox',
				fieldLabel:'Checker',
				store: comboChecker,
				displayField: 'DATA_NAME',
				valueField: 'DATA_VALUE',
				width:500,
				labelWidth:125,
				id:'cb_checker',
				//editable: false,
				emptyText : '- Pilih -',
				minChars:1,
				enableKeyEvents : true,
			}, {		
				xtype:'combobox',
				fieldLabel:'Validasi Kasir',
				store: comboKasir,
				displayField: 'DATA_NAME',
				valueField: 'DATA_VALUE',
				width:500,
				labelWidth:125,
				id:'cb_kasir',
				//editable: false,
				emptyText : '- Pilih -',
				minChars:1,
				enableKeyEvents : true,
			}, {
				xtype:'combobox',
				fieldLabel:'Status User',
				store: statusUser,
				displayField: 'DATA_NAME',
				valueField: 'DATA_VALUE',
				width:250,
				labelWidth:125,
				id:'cb_status',
				value:'ACTIVE',
				editable: false
			}, {
				xtype:'label',
				html:'&nbsp',
			}, {
				xtype:'panel',
				bodyStyle: 'padding-left: 130px;padding-bottom: 50px;border:none',
				items:[{
					xtype:'button',
					text:'Simpan',
					width:50,
					handler:function(){
						if ( Ext.getCmp('cb_tipe').getValue() == '' ){
							alertDialog('Kesalahan','Tipe Transaksi harus diisi.');
						}else{
							
						if ( Ext.getCmp('cb_tipe').getValue() == 'Pinjaman' ) 
						{
							
							if	(Ext.getCmp('cb_perusahaan').getValue()!='')
							{
								
								Ext.Ajax.request({
									url:'<?php echo 'simpan_approval.php'; ?>',
									params:{
										
										hdid:Ext.getCmp('hd_id_user').getValue(),
										typeform:Ext.getCmp('tf_formtype').getValue(),
										perusahaan:Ext.getCmp('cb_perusahaan').getValue(),
										hrd:Ext.getCmp('cb_hrd').getValue(),
										acc:Ext.getCmp('cb_acc').getValue(),
										kasir:Ext.getCmp('cb_kasir').getValue(),
										status:Ext.getCmp('cb_status').getValue(),
										tipe_trans:Ext.getCmp('cb_tipe').getValue(),
										
										// checker:Ext.getCmp('cb_checker').getValue(),
										
									},
									method:'POST',
									success:function(response){
										var json=Ext.decode(response.responseText);
										if (json.rows == "sukses"){
											alertDialog('Sukses','Data berhasil disimpan');
											Ext.clearPage();
											wind.close();
										} else {
											alertDialog('Kesalahan', "Data gagal disimpan. " + json.rows);
										}
									},
									failure:function(result,action){
										alertDialog('Kesalahan','Data gagal disimpan');
									}
								});
							} else {
								
								// alertDialog('Peringatan','Perusahaan, Tipe Transaksi, Manager HRD, Manager Accounting, Checker dan Validasi Kasir harus diisi!');
								
								alertDialog('Peringatan','Perusahaan harus diisi!');
															
							}
							
						} else {
							
							if ( Ext.getCmp('cb_tipe').getValue() == 'BS' ) 
							{
								
								
								if	(	Ext.getCmp('cb_perusahaan').getValue()!='' )
								{
									
									Ext.Ajax.request({
										url:'<?php echo 'simpan_approval.php'; ?>',
										params:{
											
											hdid:Ext.getCmp('hd_id_user').getValue(),
											typeform:Ext.getCmp('tf_formtype').getValue(),
											perusahaan:Ext.getCmp('cb_perusahaan').getValue(),
											hrd:Ext.getCmp('cb_hrd').getValue(),
											acc:Ext.getCmp('cb_acc').getValue(),
											kasir:Ext.getCmp('cb_kasir').getValue(),
											status:Ext.getCmp('cb_status').getValue(),
											tipe_trans:Ext.getCmp('cb_tipe').getValue(),
											checker:Ext.getCmp('cb_checker').getValue(),
											
										},
										method:'POST',
										success:function(response){
											var json=Ext.decode(response.responseText);
											if (json.rows == "sukses"){
												alertDialog('Sukses','Data berhasil disimpan');
												Ext.clearPage();
												wind.close();
											} else {
												alertDialog('Kesalahan', "Data gagal disimpan. " + json.rows);
											}
										},
										failure:function(result,action){
											alertDialog('Kesalahan','Data gagal disimpan');
										}
									});
								} else {
									
									alertDialog('Peringatan','Perusahaan harus diisi!');
									
									// alertDialog('Peringatan','Perusahaan, Tipe Transaksi, Manager HRD, Manager Accounting dan Validasi Kasir harus diisi!');
																
								}

							
							}
							
							
						}
						}
						
					}
				},{
					xtype:'label',
					html:'&nbsp',
				},{
					xtype:'button',
					text:'Batal',
					width:50,
					handler:function(){
						Ext.clearPage();
						wind.close();
					}
				}]
			}]
		}]
	});
	var currentTime = new Date();
	
	Ext.clearPage=function(){
		
		Ext.getCmp('tf_formtype').setValue('tambah');
		Ext.getCmp('hd_id_user').setValue('');
		Ext.getCmp('cb_perusahaan').setValue('');
		Ext.getCmp('cb_hrd').setValue('');
		Ext.getCmp('cb_acc').setValue('');
		Ext.getCmp('cb_checker').setValue('');
		Ext.getCmp('cb_kasir').setValue('');
		Ext.getCmp('cb_tipe').setValue('');
		
		Ext.getCmp('cb_status').setValue('ACTIVE');
		Ext.getCmp('cb_perusahaan').setDisabled(false);
		Ext.getCmp('cb_hrd').setDisabled(false);
		Ext.getCmp('cb_acc').setDisabled(false);
		Ext.getCmp('cb_kasir').setDisabled(false);
		Ext.getCmp('cb_tipe').setDisabled(false);
		
		store_user.load();
		
	}	
	
	var contentPanel = Ext.create('Ext.panel.Panel',{
			bodyStyle: 'spacing: 10px;border:none',
			items:[{
				xtype:'label',
				html:'<div align="center"><font size="5"><b>Form Master Approval Pinjaman dan BS</b></font></div>',
			},{
				xtype:'label',
				html:'&nbsp',
			}, {
			xtype:'panel',
			bodyStyle: 'padding-bottom: 5px;border:none',
				items:[{
					xtype:'button',
					text:'Tambah Approval',
					width:120,
					handler:function(){
						Ext.clearPage();
						wind.show(); 
					}
				}]
			}, grid_user],
		});
	store_user.load();
	comboPerusahaan.load();
	comboManagerACC.load();
	comboManagerHRD.load();
	comboKasir.load();
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