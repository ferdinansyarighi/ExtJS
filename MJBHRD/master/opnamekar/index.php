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

	var storeHRArea=new Ext.data.JsonStore({
		id:'store_hr_area',
		pageSize: 50,
		fields:[{
			name:'HD_ID'
		},{
			name:'DATA_NAMA_HR_AREA'
		},{
			name:'DATA_NAMA_HR_AREA_ID'
		},{
			name:'DATA_STATUS'
		},{
			name:'DATA_PLANT'
		},{
			name:'DATA_PLANT_ID'
		}],
		 proxy:{
			type:'ajax',
			url:'isi_grid.php', 
			reader: {
				type: 'json',
				root: 'data',
				totalProperty:'total'        
			},
		}
	});

	var combobox_hr_area = Ext.create('Ext.data.Store', {
		fields: ['DATA_NAME', 'DATA_VALUE'],
		proxy: {
			type: 'ajax',
			url: '<?PHP echo PATHAPP ?>/combobox/combobox_karyawan_hr.php',
			reader: {
				type: 'json',
				root: 'data',
				totalProperty: 'total'
			},
		}
	});

	var combobox_hr_area2 = Ext.create('Ext.data.Store', {
		fields: ['DATA_NAME', 'DATA_VALUE'],
		proxy: {
			type: 'ajax',
			url: '<?PHP echo PATHAPP ?>/combobox/combobox_karyawan_hr.php',
			reader: {
				type: 'json',
				root: 'data',
				totalProperty: 'total'
			},
		}
	});

	var comboStatusKaryawan = Ext.create('Ext.data.Store', {
		fields: ['DATA_NAME', 'DATA_VALUE'],
		data : [
			{"DATA_NAME":"ACTIVE", "DATA_VALUE":"ACTIVE"},
			{"DATA_NAME":"INACTIVE", "DATA_VALUE":"INACTIVE"}
		]
	});
	
	var comboPlant = Ext.create('Ext.data.Store', {
		fields: [{
			name:'DATA_VALUE'
		},{
			name:'DATA_NAME'
		}],
		proxy:{
			type:'ajax',
			url:'<?PHP echo PATHAPP ?>/combobox/combobox_plant_area.php', 
			reader: {
				type: 'json',
				root: 'data', 
				totalProperty:'total'   
			},
		}
	});

	var comboPlant2 = Ext.create('Ext.data.Store', {
		fields: [{
			name:'DATA_VALUE'
		},{
			name:'DATA_NAME'
		}],
		proxy:{
			type:'ajax',
			url:'<?PHP echo PATHAPP ?>/combobox/combobox_plant_area.php', 
			reader: {
				type: 'json',
				root: 'data', 
				totalProperty:'total'   
			},
		}
	});

	//Pop Up
	var wind=Ext.create('Ext.Window', {
		title: 'Form Edit Karyawan',
		width: 500,
		height: 270,
		layout: 'fit',
		closeAction:'hide',
		items: [{
			xtype:'panel',
			bodyStyle: 'padding-left: 5px;padding-top: 5px;padding-bottom: 10px;border:none',
			items:[{
				xtype:'label',
				html:'<div align="left"><font size="2"><b>Data Karyawan:</b></font></div>',
			},{
				xtype:'label',
				html:'&nbsp',
			},{
				xtype:'textfield',
				fieldLabel:'karyawanid',
				width:350,
				labelWidth:150,
				id:'hd_id_karyawan',
				hidden:true
			},{
				xtype:'combobox',
				fieldLabel:'Nama Admin',
				store: combobox_hr_area2,
				displayField: 'DATA_NAME',
				valueField: 'DATA_VALUE',
				width:450,
				labelWidth:150,
				id:'cb_nama_hr_area2',
				value:'- Pilih -',
				editable: true,
				enableKeyEvents : true,
				minChars:3,
			},{
				xtype:'combobox',
				fieldLabel:'Plant',
				store: comboPlant2,
				displayField: 'DATA_NAME',
				valueField: 'DATA_VALUE',
				width:350,
				labelWidth:150,
				id:'cb_plant2',
				value:'- Pilih -',
				editable: true,
				enableKeyEvents : true,
				minChars:3,
			},{
				xtype:'combobox',
				fieldLabel:'Status',
				store: comboStatusKaryawan,
				displayField: 'DATA_NAME',
				valueField: 'DATA_VALUE',
				width:280,
				labelWidth:150,
				id:'cb_status',
				value:'ACTIVE',
				editable: false
			},{
				xtype:'label',
				html:'&nbsp',
			} ,{
				xtype:'panel',
				bodyStyle: 'padding-left: 155px;padding-bottom: 50px;border:none',
				items:[{
					xtype:'button',
					text:'Simpan',
					width:50,
					handler:function(){
						if(Ext.getCmp('cb_nama_hr_area').getValue()!=''){
							if(Ext.getCmp('cb_plant').getValue()!=''){
								if(Ext.getCmp('cb_status').getValue()!=''){
									Ext.Ajax.request({
												url:'<?php echo 'simpan_karyawan.php'; ?>',
												params:{
													hdid:Ext.getCmp('hd_id_karyawan').getValue(),
													typeform:'edit',
													karyawan:Ext.getCmp('cb_nama_hr_area2').getValue(),
													plant:Ext.getCmp('cb_plant2').getValue(),
													status:Ext.getCmp('cb_status').getValue(),
												},
												method:'POST',
												success:function(response){
													var json=Ext.decode(response.responseText);
													if (json.rows == 'sukses') {
													alertDialog('Sukses','Data berhasil disimpan');
													Ext.getCmp('cb_nama_hr_area2').setValue('');					
													Ext.getCmp('hd_id_karyawan').setValue('');											
													Ext.getCmp('cb_plant2').setValue('- Pilih -');											
													wind.close();
													storeHRArea.load();
													}else {
														alertDialog('Kesalahan', json.rows);
													}
												},
												failure:function(result,action){
													alertDialog('Kesalahan','Data gagal disimpan');
												}
											});
								}else {
									alertDialog('Kesalahan','Status belum diisi.');
								}
							}else {
								alertDialog('Kesalahan','Plant belum diisi.');
							}
						}else {
								alertDialog('Kesalahan','Nama Admin belum diisi.');
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
						Ext.getCmp('cb_nama_hr_area').setValue('');																		
						Ext.getCmp('cb_plant').setValue('');
						Ext.getCmp('cb_status').setValue('');																		
						wind.close();
					}
				},{
					xtype:'label',
					html:'&nbsp',
				},{
					xtype:'button',
					text:'Hapus',
					width:50,
					handler:function(){
						Ext.MessageBox.confirm('Konfirmasi', 'Apakah anda yakin untuk menghapus Admin ini ?', confirmFunction);
                    function confirmFunction (btn)
                    {
                        if (btn == 'yes')
						Ext.Ajax.request({
							url:'delete.php',
							method:'POST',
							params:{
								hdid:Ext.getCmp('hd_id_karyawan').getValue(),
							},
							success:function(response){
								var json=Ext.decode(response.responseText);
								var transId=json.results;
								if (json.rows == "sukses"){
									alertDialog('Sukses', "Data Admin berhasil ter-Hapus.");
									wind.close();
									Ext.getCmp('cb_nama_hr_area').setValue('');									
									storeHRArea.setProxy({
									type:'ajax',
									url:'<?PHP echo PATHAPP ?>/master/admin/isi_grid.php?',
									reader: {
										type: 'json',
										root: 'data',
										totalProperty:'total'
									}
									});
									storeHRArea.load();
									Ext.getCmp('cb_nama_hr_area').setDisabled(false);

								} else {
									alertDialog('Kesalahan', "Data gagal disimpan. ");
								}
							},
							failure:function(error){
								alertDialog('Warning','Save failed.');
							}
						});
					  }
					}
				}]
			}]
		}]
	});
		
	//View Data
	var grid_hr_area=Ext.create('Ext.grid.Panel',{
		id:'grid_hr_area_id',
		region:'center',
		store:storeHRArea,
        columnLines: true,
		height:400,
		autoScroll:true,
		columns:[{
			dataIndex:'HD_ID',
			header:'id',
			width:100,
			hidden:true
		},{
			dataIndex:'DATA_NAMA_HR_AREA',
			header:'Nama HR Area',
			width:450,
			align:'center',			
		},{
			dataIndex:'DATA_PLANT',
			header:'Plant',
			width:250,
			align:'center',			
		},{
			dataIndex:'DATA_STATUS',
			header:'Status',
			width:158,
			align:'center',
		},{
			dataIndex:'DATA_NAMA_HR_AREA_ID',
			header:'ID HR AREA',
			width:140,
			hidden:true,
		},{
			dataIndex:'DATA_PLANT_ID',
			header:'ID PLANT',
			width:140,
			hidden:true,
		}],
		listeners: {
			dblclick: {
				element: 'body', 
				fn: function(){ 
					var hdid=grid_hr_area.getSelectionModel().getSelection()[0].get('HD_ID');					
					Ext.getCmp('hd_id_karyawan').setValue(hdid);					
					Ext.getCmp('cb_nama_hr_area2').setValue(grid_hr_area.getSelectionModel().getSelection()[0].get('DATA_NAMA_HR_AREA_ID'));
					combobox_hr_area2.load();
					Ext.getCmp('cb_plant2').setValue(grid_hr_area.getSelectionModel().getSelection()[0].get('DATA_PLANT_ID'));
					comboPlant2.load();
					Ext.getCmp('cb_nama_hr_area2').setDisabled(true);					
					var statusUser = grid_hr_area.getSelectionModel().getSelection()[0].get('DATA_STATUS');
					if (statusUser == 'A') {
						statusUser = 'ACTIVE';
					} else {
						statusUser = 'INACTIVE';
					}
					Ext.getCmp('cb_status').setValue(statusUser);
					// store_plant.removeAll();					
					wind.show();
				}
			}
		}
	});
	
	var currentTime = new Date();
	var contentPanel = Ext.create('Ext.panel.Panel',{
		bodyStyle: 'spacing: 10px;border:none',
		items:[{
			xtype:'label',
			html:'<div align="center"><font size="5"><b>Master HR Area</b></font></div>',
		},{
			xtype:'label',
			html:'&nbsp',
		}, {
			xtype:'panel',
			bodyStyle: 'padding-bottom: 5px;border:none',
			items:[{											
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
						columnWidth:.50,
						border:false,
						layout: 'anchor',
						defaultType: 'combobox',
						items:[{
							id:'cb_nama_hr_area',
							name: 'cb_nama_hr_area',
							fieldLabel: 'Nama HR Area',
							store: combobox_hr_area,
							width:380,
							labelWidth:125,
							minChars:3,
							displayField: 'DATA_NAME',
							valueField: 'DATA_VALUE',
							editable: true,
							readOnly: false,
							emptyText: "- Masukkan nama -",
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
						columnWidth:.50,
						border:false,
						layout: 'anchor',
						defaultType: 'combobox',
						items:[{
							id:'cb_plant',
							name: 'cb_plant',
							fieldLabel: 'Plant',
							store: comboPlant,
							width:380,
							labelWidth:125,
							minChars:3,
							displayField: 'DATA_NAME',
							valueField: 'DATA_VALUE',
							editable: true,
							readOnly: false,
							emptyText: "- Masukkan Plant -",
						}]
					}]	
				},{
					xtype:'label',
					html:'<br>',
				},{
					layout:'column',
					border:false,
					items:[{
						columnWidth:.05,
						border:false,
						layout: 'anchor',
						defaultType: 'label',
						items:[{
							xtype:'label',
							html:'',
						}]
					},{
						columnWidth:.11,
						border:false,
						layout: 'anchor',
						defaultType: 'button',
						items:[{
							name: 'btnview',
							text: 'View',
							width: 80,
							handler:function(){
								Ext.MessageBox.wait('Sedang proses ...');
								storeHRArea.setProxy({
									type:'ajax',
									url:'<?PHP echo PATHAPP ?>/master/opnamekar/isi_grid.php?karyawan='+Ext.getCmp('cb_nama_hr_area').getValue()+'&plant='+Ext.getCmp('cb_plant').getValue(),
									reader: {
										type: 'json',
										root: 'data',
										totalProperty:'total'        
									}
								});
								storeHRArea.load();
								Ext.MessageBox.hide();
							} 
						}]
					},{
						columnWidth:.11,
						border:false,
						layout: 'anchor',
						defaultType: 'button',
						items:[{
							name: 'btnsimpan',
							text: 'Save',
							width: 80,
							handler:function(){
								if(Ext.getCmp('cb_nama_hr_area').getValue()!=''){
									if(Ext.getCmp('cb_plant').getValue()!=''){
										Ext.Ajax.request({
											url:'<?php echo 'simpan_karyawan.php'; ?>',
											params:{												
												typeform:'tambah',
												karyawan:Ext.getCmp('cb_nama_hr_area').getValue(),
												plant:Ext.getCmp('cb_plant').getValue(),
												status:'A',
											},
											method:'POST',
											success:function(response){
												var json=Ext.decode(response.responseText);
												if (json.rows == 'sukses') {
												alertDialog('Sukses','Data berhasil disimpan');
												Ext.getCmp('cb_nama_hr_area').setValue('');
												Ext.getCmp('cb_plant').setValue('');
												storeHRArea.load();
												}else {
													alertDialog('Kesalahan', json.rows);
												}
											},
											failure:function(result,action){
												alertDialog('Kesalahan','Data gagal disimpan');
											}
										});
									}
									else {
										alertDialog('Kesalahan','Plant belum diisi.');
									}
								}else {
									alertDialog('Kesalahan','Nama Karyawan belum diisi.');
								}
							} 
						}]
					},{
						columnWidth:.11,
						border:false,
						layout: 'anchor',
						defaultType: 'button',
						items:[{
							name: 'btnclear',
							text: 'Clear',
							width: 80,
							handler:function(){
								Ext.getCmp('cb_nama_hr_area').setValue('');
								Ext.getCmp('cb_plant').setValue('');
							} 
						}]
					},{
						columnWidth: .11,
						border:false,
						layout:'anchor',
						defaultType:'button',
						items:[{
							name: 'btnExit',
							text: 'Exit',
							width: 80,
							handler:function(){
								document.location.href = "<?PHP echo PATHAPP ?>/main/indexUtama.php";
							}
						}]
					}]	
				}]
			},{
				xtype:'label',
				html:'<br>',
			}, grid_hr_area],
		});
	storeHRArea.load();	
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
	header("location: " . PATHAPP . "/Index.php");
}
?>