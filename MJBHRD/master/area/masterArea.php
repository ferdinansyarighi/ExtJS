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
	var store_area=new Ext.data.JsonStore({
		id:'store_area_id',
		pageSize: 50,
		fields:[{
			name:'HD_ID'
		},{
			name:'NAMA_AREA'
		},{
			name:'DATA_STATUS'
		}],
		 proxy:{
			type:'ajax',
			url:'isi_grid_area.php', 
			reader: {
				type: 'json',
				root: 'data',
				totalProperty:'total'        
			},
		}
	});
	var store_plant=new Ext.data.JsonStore({
		id:'store_plant_id',
		pageSize: 50,
		fields:[{
			name:'HD_ID'
		},{
			name:'DATA_PLANTNAME'
		}],
		 proxy:{
			type:'ajax',
			url:'isi_grid_plant.php', 
			reader: {
				type: 'json',
				root: 'data',
				totalProperty:'total'        
			},
		}
	});
	var statusArea = Ext.create('Ext.data.Store', {
		fields: ['DATA_NAME', 'DATA_VALUE'],
		data : [
			{"DATA_NAME":"ACTIVE", "DATA_VALUE":"ACTIVE"},
			{"DATA_NAME":"INACTIVE", "DATA_VALUE":"INACTIVE"}
		]
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
		}
	});
	var sumidtxt='';
	var grid_plant=Ext.create('Ext.grid.Panel',{
		id:'grid_plant_id',
		region:'center',
		store:store_plant,
        columnLines: true,
		autoScroll:true,
		height:200,
		width:300,
		columns:[{
			dataIndex:'HD_ID',
			header:'id',
			width:100,
			hidden:true
		},{
			dataIndex:'DATA_PLANTNAME',
			header:'Nama Plant',
			width:290,
			//aligment:'Center',
		}]
	});
	var wind=Ext.create('Ext.Window', {
		title: 'Form Input Area',
		width: 500,
		height: 520,							
		layout: 'fit',
		closeAction:'hide',
		items: [{
			xtype:'panel',
			bodyStyle: 'padding-left: 5px;padding-top: 5px;padding-bottom: 10px;border:none',
			items:[{
				xtype:'label',
				html:'<div align="left"><font size="2"><b>Data Header:</b></font></div>',
			},{
				xtype:'label',
				html:'&nbsp',
			},{		
				xtype:'textfield',
				fieldLabel:'areaid',
				width:350,
				labelWidth:150,
				id:'hd_id_area',
				hidden:true
			},{		
				xtype:'textfield',
				fieldLabel:'formtype',
				width:350,
				labelWidth:150,
				id:'tf_formtype',
				hidden:true
			},{		
				xtype:'textfield',
				fieldLabel:'sumid',
				width:350,
				labelWidth:150,
				id:'hd_sumid',
				hidden:true
			},{
				xtype:'textfield',
				fieldLabel:'Nama Area',
				width:350,
				labelWidth:150,
				id:'tf_nama_area',
				listeners: {
					 change: function(field,newValue,oldValue){
							field.setValue(newValue.toUpperCase());
					}
				}
			},{		
				xtype:'combobox',
				fieldLabel:'Status Area',
				store: statusArea,
				displayField: 'DATA_NAME',
				valueField: 'DATA_VALUE',
				width:280,
				labelWidth:150,
				id:'cb_status_area',
				value:'ACTIVE',
				editable: false
			},{
				xtype:'label',
				html:'&nbsp',
			},{
				xtype:'label',
				html:'<div align="left"><font size="2"><b>Data Detail:</b></font></div>',
			},{
				xtype:'label',
				html:'&nbsp',
			},{		
				xtype:'combobox',
				fieldLabel:'Plant',
				store: plant,
				displayField: 'DATA_NAME',
				valueField: 'DATA_VALUE',
				width:350,
				labelWidth:150,
				id:'cb_plant',
				value:'- Pilih -',
				editable: false
			},{
				xtype:'panel',
				bodyStyle: 'padding-left: 155px;border:none',
				items:[{xtype:'button',
					text:'Tambah',
					width:50,
					handler:function(){
						if(Ext.getCmp('cb_plant').getValue()!='- Pilih -'){
							var xsumid = sumidtxt.search(Ext.getCmp('cb_plant').getValue());
							if (xsumid=='-1'){
								if(sumidtxt!=''){
									sumidtxt += ', ' + Ext.getCmp('cb_plant').getValue()
								}
								else {
									sumidtxt = Ext.getCmp('cb_plant').getValue()
								}
								store_plant.load({
									params:{
										hd_id:sumidtxt,
									}
								})
							} else {
								alertDialog('Kesalahan','Menu sudah pernah ditambah.');
							} 
						}
						else {
							alertDialog('Kesalahan','Menu belum dipilih.');
						}
					}
				}, {xtype:'button',
					text:'Hapus',
					width:50,
					handler:function(){
						var hdid=grid_plant.getSelectionModel().getSelection()[0].get('HD_ID');
						if(hdid!=''){
							var hdidkoma = ', ' + hdid;
							var xsumidkoma = sumidtxt.search(hdidkoma);
							if (xsumidkoma=='-1'){
								var xsumid = sumidtxt.search(hdid);
								if (xsumid!='-1'){
									sumidtxt = sumidtxt.replace(hdid + ', ', '');
									store_plant.load({
										params:{
											hd_id:sumidtxt,
										}
									})
								}
							} else {
								sumidtxt = sumidtxt.replace(hdidkoma, '');
								store_plant.load({
									params:{
										hd_id:sumidtxt,
									}
								})
							} 
						}
						else {
							alertDialog('Kesalahan','Menu belum dipilih.');
						}
					}
				}]
			},{
				xtype:'label',
				html:'&nbsp',
			},{
				xtype:'panel',
				bodyStyle: 'padding-left: 155px;border:none',
				items:[grid_plant]
			} ,{
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
						if(Ext.getCmp('tf_nama_area').getValue()!=''){
							Ext.Ajax.request({
										url:'<?php echo 'simpan_area.php'; ?>',
										params:{
											hdid:Ext.getCmp('hd_id_area').getValue(),
											typeform:Ext.getCmp('tf_formtype').getValue(),
											namaarea:Ext.getCmp('tf_nama_area').getValue(),
											status:Ext.getCmp('cb_status_area').getValue(),
											hdidplant:sumidtxt,
										},
										method:'POST',
										success:function(response){
											alertDialog('Sukses','Data berhasil disimpan');
											Ext.getCmp('tf_nama_area').setValue('');
											sumidtxt='';
											Ext.getCmp('tf_formtype').setValue('');
											Ext.getCmp('hd_id_area').setValue('');
											Ext.getCmp('cb_plant').setValue('- Pilih -');
											store_plant.removeAll();
											wind.close();
											store_area.load();
										},
										failure:function(result,action){
											alertDialog('Kesalahan','Data gagal disimpan');
										}
									});
						}
						else {
							alertDialog('Kesalahan','Nama area belum diisi.');
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
						Ext.getCmp('tf_nama_area').setValue('');
						Ext.getCmp('tf_formtype').setValue('');
						Ext.getCmp('hd_id_area').setValue('');
						Ext.getCmp('cb_plant').setValue('- Pilih -');
						sumidtxt='';
						store_plant.removeAll();
						wind.close();
					}
				}]
			}]
		}]
	});
	//View Data
	var grid_area=Ext.create('Ext.grid.Panel',{
		id:'grid_area_id',
		region:'center',
		store:store_area,
        columnLines: true,
		height:400,
		autoScroll:true,
		columns:[{
			dataIndex:'HD_ID',
			header:'id',
			width:100,
			hidden:true
		},{
			dataIndex:'NAMA_AREA',
			header:'Nama Area',
			width:460,
			
		},{
			dataIndex:'DATA_STATUS',
			header:'Status Area',
			width:400
		}],
		listeners: {
			dblclick: {
				element: 'body', //bind to the underlying body property on the panel
				fn: function(){ 
					var hdid=grid_area.getSelectionModel().getSelection()[0].get('HD_ID');
					Ext.getCmp('hd_id_area').setValue(hdid);
					Ext.getCmp('tf_formtype').setValue('edit');
					Ext.getCmp('tf_nama_area').setValue(grid_area.getSelectionModel().getSelection()[0].get('NAMA_AREA'));
					var statusUser = grid_area.getSelectionModel().getSelection()[0].get('DATA_STATUS');
					if (statusUser == 'A') {
						statusUser = 'ACTIVE';
					} else {
						statusUser = 'INACTIVE';
					}
					Ext.getCmp('cb_status_area').setValue(statusUser);
					store_plant.removeAll();
					Ext.Ajax.request({
						url:'<?php echo 'isi_sumid.php'; ?>',
						params:{
							hd_id:hdid,
						},
						method:'POST',
						success:function(response){
							sumidtxt='';
							var json=Ext.decode(response.responseText);
							for(var i in json) {
								if(i==0){
									sumidtxt += json[i];
								} else {
									sumidtxt += ', ' + json[i];
								}
							}
							store_plant.load({
								params:{
									hd_id:sumidtxt,
								}
							})
						},
						failure:function(result,action){
							//myMask.hide();
							//alertDialog('Kesalahan','Data gagal disimpan');
						}
					});
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
				html:'<div align="center"><font size="5"><b>Form Master Area</b></font></div>',
			},{
				xtype:'label',
				html:'&nbsp',
			}, {
			xtype:'panel',
			bodyStyle: 'padding-bottom: 5px;border:none',
				items:[{
					xtype:'button',
					text:'Tambah Area',
					width:100,
					handler:function(){
						Ext.getCmp('tf_formtype').setValue('tambah');
						Ext.getCmp('tf_nama_area').setValue('');
						Ext.getCmp('hd_id_area').setValue('');
						sumidtxt='';
						store_plant.removeAll();
						wind.show(); 
					}
				}]
			}, grid_area],
		});
	store_area.load();
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