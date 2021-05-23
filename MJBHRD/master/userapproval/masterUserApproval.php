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
			name:'DATA_NAMA_USER'
		},{
			name:'DATA_EMAIL'
		},{
			name:'DATA_TINGKAT'
		},{
			name:'DATA_AREA'
		},{
			name:'DATA_STATUS'
		}],
		 proxy:{
			type:'ajax',
			url:'isi_grid_user.php', 
			reader: {
				type: 'json',
				root: 'data',
				totalProperty:'total'        
			},
		}
	});
	var statusUser = Ext.create('Ext.data.Store', {
		fields: ['DATA_NAME', 'DATA_VALUE'],
		data : [
			{"DATA_NAME":"ACTIVE", "DATA_VALUE":"ACTIVE"},
			{"DATA_NAME":"INACTIVE", "DATA_VALUE":"INACTIVE"}
		]
	});
	var tingkatUser = Ext.create('Ext.data.Store', {
		fields: ['DATA_NAME', 'DATA_VALUE'],
		data : [
			{"DATA_NAME":"1", "DATA_VALUE":"1"},
			{"DATA_NAME":"2", "DATA_VALUE":"2"}
		]
	});
	var comboPemohon = Ext.create('Ext.data.Store', {
		fields: [{
			name:'DATA_VALUE'
		},{
			name:'DATA_NAME'
		}],
		proxy:{
			type:'ajax',
			url:'<?PHP echo PATHAPP ?>/combobox/combobox_userall.php', 
			reader: {
				type: 'json',
				root: 'data', 
				totalProperty:'total'   
			},
		}
	});
	var comboArea = Ext.create('Ext.data.Store', {
		fields: [{
			name:'DATA_VALUE'
		},{
			name:'DATA_NAME'
		}],
		proxy:{
			type:'ajax',
			url:'<?PHP echo PATHAPP ?>/combobox/combobox_area.php', 
			reader: {
				type: 'json',
				root: 'data', 
				totalProperty:'total'   
			},
		}
	});
	var wind=Ext.create('Ext.Window', {
		title: 'Form Input User',
		width: 400,
		height: 220,							
		layout: 'fit',
		closeAction:'hide',
		items: [{
			xtype:'panel',
			bodyStyle: 'padding-left: 5px;padding-top: 5px;padding-bottom: 10px;border:none',
			items:[{		
				xtype:'textfield',
				fieldLabel:'userid',
				width:350,
				labelWidth:75,
				id:'hd_id_user',
				hidden:true
			},{		
				xtype:'textfield',
				fieldLabel:'formtype',
				width:350,
				labelWidth:75,
				id:'tf_formtype',
				hidden:true
			},{		
				xtype:'combobox',
				fieldLabel:'Nama User',
				store: comboPemohon,
				displayField: 'DATA_NAME',
				valueField: 'DATA_VALUE',
				width:350,
				labelWidth:75,
				id:'cb_nama_user',
				//value:'- Pilih -',
				emptyText : '- Pilih -',
				//editable: false,
				enableKeyEvents : true,
				minChars:1,
				listeners: {
					select:function(f,r,i){
						var nama_man=r[0].data.DATA_VALUE;
						//console.log(nama_man);
						// Ext.Ajax.request({
							// url:'<?php echo 'isi_email.php'; ?>',
								// params:{
									// nama_man:nama_man,
								// },
								// success:function(response){
									// var json=Ext.decode(response.responseText);
									// var deskripsiasli = json.results;
									// Ext.getCmp('tf_email').setValue(deskripsiasli);
								// },
							// method:'POST',
						// });
					},
					keypress : {
						element: 'body',
						fn: function(){
							var pjname=Ext.getCmp('cb_nama_user').getValue();
							combopj.load({
								params : {
									pjname:pjname,
								}
							});
						}
					}
				}
			// },{
				// xtype:'textfield',
				// fieldLabel:'Email',
				// width:350,
				// labelWidth:75,
				// id:'tf_email',
				// readOnly:true,
				// fieldStyle:'background:#B8B8B8 ;font-weight:bold;'
				// /* listeners: {
					 // change: function(field,newValue,oldValue){
							// field.setValue(newValue.toUpperCase());
					// }
				// } */
			},{		
				xtype:'combobox',
				fieldLabel:'Tingkat',
				store: tingkatUser,
				displayField: 'DATA_NAME',
				valueField: 'DATA_VALUE',
				width:200,
				labelWidth:75,
				id:'cb_tingkat',
				value:'- Pilih -',
				editable: false
			},{		
				xtype:'combobox',
				fieldLabel:'Area',
				store: comboArea,
				displayField: 'DATA_NAME',
				valueField: 'DATA_VALUE',
				width:200,
				labelWidth:75,
				id:'cb_area',
				value:'- Pilih -',
				editable: false
			},{		
				xtype:'combobox',
				fieldLabel:'Status User',
				store: statusUser,
				displayField: 'DATA_NAME',
				valueField: 'DATA_VALUE',
				width:200,
				labelWidth:75,
				id:'cb_status_user',
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
						if(Ext.getCmp('cb_nama_user').getValue()!='- Pilih -'){
							//if(Ext.getCmp('tf_email').getValue()!=''){
								if(Ext.getCmp('cb_tingkat').getValue()!='- Pilih -'){
									Ext.Ajax.request({
										url:'<?php echo 'simpan_user.php'; ?>',
										params:{
											hdid:Ext.getCmp('hd_id_user').getValue(),
											typeform:Ext.getCmp('tf_formtype').getValue(),
											namaemp:Ext.getCmp('cb_nama_user').getValue(),
											//email:Ext.getCmp('tf_email').getValue(),
											tingkat:Ext.getCmp('cb_tingkat').getValue(),
											area:Ext.getCmp('cb_area').getValue(),
											status:Ext.getCmp('cb_status_user').getValue(),
										},
										method:'POST',
										success:function(response){
											var json=Ext.decode(response.responseText);
											if (json.rows == "sukses"){
												alertDialog('Sukses','Data berhasil disimpan');
												//Ext.getCmp('tf_email').setValue('');
												Ext.getCmp('cb_status_user').setValue('ACTIVE');
												Ext.getCmp('cb_tingkat').setValue('- Pilih -');
												Ext.getCmp('cb_nama_user').setValue('- Pilih -');
												Ext.getCmp('cb_area').setValue('- Pilih -');
												Ext.getCmp('cb_nama_user').setDisabled(false);
												wind.close();
												store_user.load();
											} else {
												alertDialog('Kesalahan', "Data gagal disimpan. ");
											}
										},
										failure:function(result,action){
											alertDialog('Kesalahan','Data gagal disimpan');
										}
									});
								} else {
									alertDialog('Kesalahan','Tingkat belum diisi.');	
								}
							// }
							// else {
								// alertDialog('Kesalahan','Email belum diisi.');
							// }
						} else {
							alertDialog('Kesalahan','Nama user belum dipilih.');
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
						//Ext.getCmp('tf_email').setValue('');
						Ext.getCmp('cb_status_user').setValue('ACTIVE');
						Ext.getCmp('cb_tingkat').setValue('- Pilih -');
						Ext.getCmp('cb_area').setValue('- Pilih -');
						Ext.getCmp('cb_nama_user').setValue('- Pilih -');
						Ext.getCmp('cb_nama_user').setDisabled(false);
						wind.close();
					}
				}]
			}]
		}]
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
			dataIndex:'DATA_NAMA_USER',
			header:'Nama User',
			width:350,
			
		// },{
			// dataIndex:'DATA_EMAIL',
			// header:'Email',
			// width:200,
			
		},{
			dataIndex:'DATA_TINGKAT',
			header:'Tingkat',
			width:100,
			
		},{
			dataIndex:'DATA_AREA',
			header:'Area',
			width:250,
			//hidden:true
		},{
			dataIndex:'DATA_STATUS',
			header:'Status User',
			width:160
		}],
		listeners: {
			dblclick: {
				element: 'body', //bind to the underlying body property on the panel
				fn: function(){ 
					var hdid=grid_user.getSelectionModel().getSelection()[0].get('HD_ID');
					var statusUser = grid_user.getSelectionModel().getSelection()[0].get('DATA_STATUS');
					if (statusUser == 'A') {
						statusUser = 'ACTIVE';
					} else {
						statusUser = 'INACTIVE';
					}
					Ext.getCmp('hd_id_user').setValue(hdid);
					Ext.getCmp('tf_formtype').setValue('edit');
					//Ext.getCmp('tf_email').setValue(grid_user.getSelectionModel().getSelection()[0].get('DATA_EMAIL'));
					Ext.getCmp('cb_status_user').setValue(statusUser);
					Ext.getCmp('cb_area').setValue(grid_user.getSelectionModel().getSelection()[0].get('DATA_AREA'));
					Ext.getCmp('cb_tingkat').setValue(grid_user.getSelectionModel().getSelection()[0].get('DATA_TINGKAT'));
					Ext.getCmp('cb_nama_user').setValue(grid_user.getSelectionModel().getSelection()[0].get('DATA_NAMA_USER'));
					Ext.getCmp('cb_nama_user').setDisabled(true);
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
				html:'<div align="center"><font size="5"><b>Form Master HRD Approval</b></font></div>',
			},{
				xtype:'label',
				html:'&nbsp',
			}, {
			xtype:'panel',
			bodyStyle: 'padding-bottom: 5px;border:none',
				items:[{
					xtype:'button',
					text:'Tambah User',
					width:100,
					handler:function(){
						Ext.getCmp('tf_formtype').setValue('tambah');
						Ext.getCmp('cb_nama_user').setDisabled(false);
						Ext.getCmp('cb_status_user').setValue('ACTIVE');
						wind.show(); 
					}
				}]
			}, grid_user],
		});
	store_user.load();
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