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
	var currentTime = new Date();
	var statusUser = Ext.create('Ext.data.Store', {
		fields: ['DATA_NAME', 'DATA_VALUE'],
		data : [
			{"DATA_NAME":"ACTIVE", "DATA_VALUE":"ACTIVE"},
			{"DATA_NAME":"INACTIVE", "DATA_VALUE":"INACTIVE"}
		]
	});
	var storeKomponen = Ext.create('Ext.data.Store', {
		fields: ['DATA_NAME', 'DATA_VALUE'],
		data : [
			{"DATA_NAME":"+", "DATA_VALUE":"+"},
			{"DATA_NAME":"-", "DATA_VALUE":"-"}
		]
	});
	var storeType = Ext.create('Ext.data.Store', {
		fields: ['DATA_NAME', 'DATA_VALUE'],
		data : [
			{"DATA_NAME":"Recuring", "DATA_VALUE":"RECURING"},
			{"DATA_NAME":"Non Recuring", "DATA_VALUE":"NONRECURING"}
		]
	});
	Ext.clearForm=function(){
		Ext.getCmp('hd_id').setValue('')
		Ext.getCmp('tf_formtype').setValue('tambah')
		Ext.getCmp('tf_nama').setValue('')
		Ext.getCmp('cb_komponen').setValue('+')
		Ext.getCmp('cb_type').setValue('RECURING')
		Ext.getCmp('cb_status').setValue(true)
		grid_store.load()
	};
	var grid_store=new Ext.data.JsonStore({
		id:'grid_store_id',
		pageSize: 50,
		fields:[{
			name:'HD_ID'
		},{
			name:'DATA_NAMA'
		},{
			name:'DATA_KOMPONEN'
		},{
			name:'DATA_TYPE'
		},{
			name:'DATA_STATUS'
		},{
			name:'DATA_OLEH'
		},{
			name:'DATA_TGL'
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
	var grid_view=Ext.create('Ext.grid.Panel',{
		id:'grid_view_id',
		region:'center',
		store:grid_store,
        columnLines: true,
		height:500,
		autoScroll:true,
		columns:[{
			xtype:'rownumberer',
			id:'row_id',
			header:'No',
			width:30
		},{
			dataIndex:'HD_ID',
			header:'id',
			width:100,
			hidden:true
		},{
			dataIndex:'DATA_NAMA',
			header:'Nama Element',
			width:220,
			
		},{
			dataIndex:'DATA_KOMPONEN',
			header:'Komponen +/-',
			//align:'right',
			width:120,
			
		},{
			dataIndex:'DATA_TYPE',
			header:'Type Element',
			width:120,
			
		},{
			dataIndex:'DATA_STATUS',
			header:'Status',
			width:80,
			
		},{
			dataIndex:'DATA_OLEH',
			header:'Update By',
			width:160,
			
		},{
			dataIndex:'DATA_TGL',
			header:'Update Date',
			width:140,
			
		}],
		listeners: {
			dblclick: {
				element: 'body', //bind to the underlying body property on the panel
				fn: function(){ 
					var hdid=grid_view.getSelectionModel().getSelection()[0].get('HD_ID');
					Ext.getCmp('hd_id').setValue(hdid);
					Ext.getCmp('tf_formtype').setValue('edit');
					Ext.getCmp('tf_nama').setValue(grid_view.getSelectionModel().getSelection()[0].get('DATA_NAMA'));
					Ext.getCmp('cb_komponen').setValue(grid_view.getSelectionModel().getSelection()[0].get('DATA_KOMPONEN'));
					Ext.getCmp('cb_type').setValue(grid_view.getSelectionModel().getSelection()[0].get('DATA_TYPE'));
					if(grid_view.getSelectionModel().getSelection()[0].get('DATA_STATUS') == 'Active'){
						Ext.getCmp('cb_status').setValue(true);
					}else{
						Ext.getCmp('cb_status').setValue(false);
					}
					// Ext.getCmp('cb_status').setValue(statusUser);
					wind.show();
				}
			}
		}
	});
	var wind=Ext.create('Ext.Window', {
		title: 'Form Input Element Gaji',
		width: 480,
		height: 180,							
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
				id:'hd_id',
				hidden:true
			},{		
				xtype:'textfield',
				fieldLabel:'formtype',
				width:350,
				labelWidth:75,
				id:'tf_formtype',
				hidden:true
			},{
				xtype:'textfield',
				fieldLabel:'Nama Element',
				width:450,
				labelWidth:100,
				id:'tf_nama',
				maxLength : 100,
				listeners: {
					 change: function(field,newValue,oldValue){
							field.setValue(newValue.toUpperCase());
					}
				}
			},{
				xtype:'combobox',
				fieldLabel:'Komponen +/-',
				store: storeKomponen,
				displayField: 'DATA_NAME',
				valueField: 'DATA_VALUE',
				width:230,
				labelWidth:100,
				id:'cb_komponen',
				value:'+',
				editable: false,
			},{
				xtype:'combobox',
				fieldLabel:'Type Element',
				store: storeType,
				displayField: 'DATA_NAME',
				valueField: 'DATA_VALUE',
				width:230,
				labelWidth:100,
				id:'cb_type',
				value:'RECURING',
				editable: false,
			},{
				xtype: 'checkboxgroup',
				fieldLabel:'Aktif',
				labelWidth:100,
				items: [
					{id: 'cb_status', name: 'cb_status', inputValue: 1, checked: true},
				]
			},{
				xtype:'panel',
				bodyStyle: 'padding-left: 100px;padding-bottom: 50px;border:none',
				items:[{
					xtype:'button',
					text:'Simpan',
					width:50,
					handler:function(){
						var cekLeng = Ext.getCmp('tf_nama').getValue().length;
						if(Ext.getCmp('tf_nama').getValue()!=''){
							if(cekLeng < 100){
								Ext.Ajax.request({
									url:'<?php echo 'simpan_element.php'; ?>',
									params:{
										hdid:Ext.getCmp('hd_id').getValue(),
										typeform:Ext.getCmp('tf_formtype').getValue(),
										nama:Ext.getCmp('tf_nama').getValue().trim(),
										komponen:Ext.getCmp('cb_komponen').getValue(),
										type:Ext.getCmp('cb_type').getValue(),
										status:Ext.getCmp('cb_status').getValue(),
									},
									method:'POST',
									success:function(response){
										var json=Ext.decode(response.responseText);
										if (json.rows == "sukses"){
											alertDialog('Sukses','Data berhasil disimpan');
											Ext.clearForm();
											wind.close();
										} else {
											alertDialog('Kesalahan', json.rows);
										}
									},
									failure:function(result,action){
										alertDialog('Kesalahan','Data gagal disimpan');
									}
								});
							} else {
								alertDialog('Peringatan','Nama element maksimal 100 karakter.');
							}
						}
						else {
							alertDialog('Peringatan','Nama element belum diisi.');
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
						Ext.clearForm();
						wind.close();
					}
				}]
			}]
		}]
	});
	var contentPanel = Ext.create('Ext.panel.Panel',{
			bodyStyle: 'spacing: 10px;border:none',
			items:[{
				xtype:'label',
				html:'<div align="center"><font size="5"><b>Form Master Element Gaji</b></font></div>',
			},{
				xtype:'label',
				html:'&nbsp',
			}, {
			xtype:'panel',
			bodyStyle: 'padding-bottom: 5px;border:none',
				items:[{
					xtype:'button',
					text:'Tambah',
					width:100,
					handler:function(){
						Ext.getCmp('tf_formtype').setValue('tambah');
						Ext.clearForm();
						wind.show(); 
					}
				}]
			}, grid_view],
		});
	Ext.clearForm();
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