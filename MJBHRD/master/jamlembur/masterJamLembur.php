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
			name:'DATA_PLANTID'
		},{
			name:'DATA_POSISIID'
		},{
			name:'DATA_JUMLAHSHIFT'
		},{
			name:'DATA_JUMLAHLEMBUR'
		},{
			name:'DATA_NAMAPOSISI'
		},{
			name:'DATA_NAMAPLANT'
		},{
			name:'DATA_STATUS'
		},{
			name:'DATA_OVER'
		}],
		 proxy:{
			type:'ajax',
			url:'isi_grid_lembur.php', 
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
			dataIndex:'DATA_PLANTID',
			header:'Plant',
			width:225,
			hidden:true
		},{
			dataIndex:'DATA_POSISIID',
			header:'Posisi',
			width:250,
			hidden:true
		},{
			dataIndex:'DATA_NAMAPLANT',
			header:'Plant',
			width:200,			
		},{
			dataIndex:'DATA_NAMAPOSISI',
			header:'Posisi',
			width:250,
			
		},{
			dataIndex:'DATA_JUMLAHSHIFT',
			header:'Jumlah Shift',
			width:80,
			
		},{
			dataIndex:'DATA_JUMLAHLEMBUR',
			header:'Jumlah Jam Lembur',
			width:115,
			
		},{
			dataIndex:'DATA_OVER',
			header:'Over Jam Lembur',
			width:113,
			//hidden:true
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
					var vPosisi = grid_user.getSelectionModel().getSelection()[0].get('DATA_POSISIID');
					// if (statusUser == 'A') {
						// statusUser = 'ACTIVE';
					// } else {
						// statusUser = 'INACTIVE';
					// }
					Ext.getCmp('hd_id_user').setValue(hdid);
					Ext.getCmp('tf_formtype').setValue('edit');
					Ext.getCmp('cb_plant').setDisabled(true);
					Ext.getCmp('cb_plant').setValue(String(grid_user.getSelectionModel().getSelection()[0].get('DATA_PLANTID')));
					Ext.getCmp('cb_posisi').setDisabled(true);
					if(vPosisi == 0){
						Ext.getCmp('cb_posisi').setValue('');
					} else {
						Ext.getCmp('cb_posisi').setValue(String(grid_user.getSelectionModel().getSelection()[0].get('DATA_POSISIID')));
					}
					Ext.getCmp('cb_over').setValue(grid_user.getSelectionModel().getSelection()[0].get('DATA_OVER'));
					Ext.getCmp('cb_status').setValue(statusUser);
					Ext.getCmp('tf_js').setValue(grid_user.getSelectionModel().getSelection()[0].get('DATA_JUMLAHSHIFT'));
					Ext.getCmp('tf_jl').setValue(grid_user.getSelectionModel().getSelection()[0].get('DATA_JUMLAHLEMBUR'));
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
	var statusOver = Ext.create('Ext.data.Store', {
		fields: ['DATA_NAME', 'DATA_VALUE'],
		data : [
			{"DATA_NAME":"Ya", "DATA_VALUE":"Ya"},
			{"DATA_NAME":"Tidak", "DATA_VALUE":"Tidak"}
		]
	});
	var cbKategori = Ext.create('Ext.data.Store', {
		fields: ['DATA_NAME', 'DATA_VALUE'],
		data : [
			{"DATA_NAME":"SPV", "DATA_VALUE":"SPV"},
			{"DATA_NAME":"MANAGER", "DATA_VALUE":"MANAGER"}
		]
	});
	var comboManager = Ext.create('Ext.data.Store', {
		fields: [{
			name:'DATA_VALUE'
		},{
			name:'DATA_NAME'
		}],
		proxy:{
			type:'ajax',
			url:'<?PHP echo PATHAPP ?>/combobox/combobox_managerid.php',
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
	var plant = Ext.create('Ext.data.Store', {
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
		},
		//autoLoad:true
	});
	var wind=Ext.create('Ext.Window', {
		title: 'Form Approval SPL Beton',
		width: 550,
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
			},{		
				xtype:'textfield',
				fieldLabel:'formtype',
				width:350,
				labelWidth:125,
				id:'tf_formtype',
				hidden:true
			},{		
				xtype:'combobox',
				fieldLabel:'Plant',
				store: plant,
				displayField: 'DATA_NAME',
				valueField: 'DATA_VALUE',
				width:250,
				labelWidth:125,
				id:'cb_plant',
				editable: false,
				value : '- Pilih -',
				minChars:1,
				enableKeyEvents : true,
				listeners: {
					keypress : {
						element: 'body',
						fn: function(){
							var vplant=Ext.getCmp('cb_plant').getValue();
							plant.load({
								params : {
									vplant:vplant,
								}
							});
						}
					}
				}
			},{		
				xtype:'combobox',
				fieldLabel:'Posisi',
				store: comboPosisi,
				displayField: 'DATA_NAME',
				valueField: 'DATA_VALUE',
				width:400,
				labelWidth:125,
				id:'cb_posisi',
				//editable: false,
				emptyText : '- Pilih -',
				minChars:1,
				enableKeyEvents : true,
			},{
				xtype:'numberfield',
				fieldLabel:'Jumlah Shift',
				width:180,
				labelWidth:125,
				id:'tf_js',
				value: 0,
				minValue: 0,
				maxValue: 100,
			},{
				xtype:'numberfield',
				fieldLabel:'Jumlah Jam Lembur',
				width:180,
				labelWidth:125,
				id:'tf_jl',
				value: 0,
				minValue: 0,
				maxValue: 1000,
			},{		
				xtype:'combobox',
				fieldLabel:'Over Jam Lembur',
				store: statusOver,
				displayField: 'DATA_NAME',
				valueField: 'DATA_VALUE',
				width:250,
				labelWidth:125,
				id:'cb_over',
				value:'Tidak',
				editable: false
			},{		
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
			},{
				xtype:'label',
				html:'&nbsp',
			},{
				xtype:'panel',
				bodyStyle: 'padding-left: 100px;padding-bottom: 50px;border:none',
				items:[{
					xtype:'button',
					text:'Simpan',
					width:50,
					handler:function(){
						if(Ext.getCmp('cb_plant').getValue()!='- Pilih -' && Ext.getCmp('tf_js').getValue()!=0 && Ext.getCmp('tf_jl').getValue()!=0){
							// console.log(Ext.getCmp('cb_plant').getValue());
							// console.log(Ext.getCmp('cb_posisi').getValue());
							// console.log(Ext.getCmp('tf_js').getValue());
							// console.log(Ext.getCmp('tf_jl').getValue());
							Ext.Ajax.request({
								url:'<?php echo 'simpan_lembur.php'; ?>',
								params:{
									hdid:Ext.getCmp('hd_id_user').getValue(),
									typeform:Ext.getCmp('tf_formtype').getValue(),
									plant:Ext.getCmp('cb_plant').getValue(),
									posisi:Ext.getCmp('cb_posisi').getValue(),
									jumShift:Ext.getCmp('tf_js').getValue(),
									jumLembur:Ext.getCmp('tf_jl').getValue(),
									over:Ext.getCmp('cb_over').getValue(),
									status:Ext.getCmp('cb_status').getValue(),
								},
								method:'POST',
								success:function(response){
									var json=Ext.decode(response.responseText);
									if (json.rows == "sukses"){
										alertDialog('Sukses','Data berhasil disimpan');
										Ext.getCmp('cb_over').setValue('Tidak');
										Ext.getCmp('cb_status').setValue('ACTIVE');
										Ext.getCmp('cb_plant').setValue('- Pilih -');
										Ext.getCmp('cb_posisi').setValue('');
										Ext.getCmp('tf_js').setValue(0);
										Ext.getCmp('tf_jl').setValue(0);
										wind.close();
										store_user.load();
									} else {
										alertDialog('Kesalahan', "Data gagal disimpan. " + json.rows);
									}
								},
								failure:function(result,action){
									alertDialog('Kesalahan','Data gagal disimpan');
								}
							});
						} else {
							alertDialog('Kesalahan','Plant, Posisi, Jumlah Shift, dan Jumlah Jam Lembur harus diisi!');
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
						Ext.getCmp('cb_over').setValue('Tidak');
						Ext.getCmp('cb_status').setValue('ACTIVE');
						Ext.getCmp('cb_plant').setValue('- Pilih -');
						Ext.getCmp('cb_posisi').setValue('');
						Ext.getCmp('tf_js').setValue(0);
						Ext.getCmp('tf_jl').setValue(0);
						wind.close();
					}
				}]
			}]
		}]
	});
	var currentTime = new Date();
	var contentPanel = Ext.create('Ext.panel.Panel',{
			bodyStyle: 'spacing: 10px;border:none',
			items:[{
				xtype:'label',
				html:'<div align="center"><font size="5"><b>Form Master Jam Lembur</b></font></div>',
			},{
				xtype:'label',
				html:'&nbsp',
			}, {
			xtype:'panel',
			bodyStyle: 'padding-bottom: 5px;border:none',
				items:[{
					xtype:'button',
					text:'Tambah Jam Lembur',
					width:120,
					handler:function(){
						Ext.getCmp('tf_formtype').setValue('tambah');
						Ext.getCmp('cb_plant').setValue('- Pilih -');
						Ext.getCmp('cb_posisi').setValue('');
						Ext.getCmp('tf_js').setValue(0);
						Ext.getCmp('tf_jl').setValue(0);
						Ext.getCmp('cb_over').setValue('Tidak');
						Ext.getCmp('cb_status').setValue('ACTIVE');
						Ext.getCmp('cb_plant').setDisabled(false);
						Ext.getCmp('cb_posisi').setDisabled(false);
						wind.show(); 
					}
				}]
			}, grid_user],
		});
	store_user.load();
	plant.load();
	comboPosisi.load();
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