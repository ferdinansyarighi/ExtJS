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
	var store_rekening=new Ext.data.JsonStore({
		id:'store_rekening_id',
		pageSize: 50,
		fields:[{
			name:'HD_ID'
		},{
			name:'DATA_NAMA_BANK'
		},{
			name:'DATA_ATAS_NAMA'
		},{
			name:'DATA_NO_REKENING'
		},{
			name:'DATA_STATUS'
		}],
		 proxy:{
			type:'ajax',
			url:'isi_grid_rekening.php', 
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
	var wind=Ext.create('Ext.Window', {
		title: 'Form Input Rekening',
		width: 400,
		height: 200,							
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
				id:'hd_id_rekening',
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
				fieldLabel:'Nama Bank',
				width:350,
				labelWidth:75,
				id:'tf_nb',
			},{
				xtype:'textfield',
				fieldLabel:'Atas Nama',
				width:350,
				labelWidth:75,
				id:'tf_an',
			},{
				xtype:'textfield',
				fieldLabel:'No Rekening',
				width:350,
				labelWidth:75,
				id:'tf_nr',
			},{		
				xtype:'combobox',
				fieldLabel:'Status',
				store: statusUser,
				displayField: 'DATA_NAME',
				valueField: 'DATA_VALUE',
				width:200,
				labelWidth:75,
				id:'cb_status_rekening',
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
						if(Ext.getCmp('tf_nb').getValue()!=''){
							if(Ext.getCmp('tf_an').getValue()!=''){
								if(Ext.getCmp('tf_an').getValue()!=''){
									Ext.Ajax.request({
										url:'<?php echo 'simpan_rekening.php'; ?>',
										params:{
											hdid:Ext.getCmp('hd_id_rekening').getValue(),
											typeform:Ext.getCmp('tf_formtype').getValue(),
											namaBank:Ext.getCmp('tf_nb').getValue(),
											atasNama:Ext.getCmp('tf_an').getValue(),
											noRekening:Ext.getCmp('tf_nr').getValue(),
											status:Ext.getCmp('cb_status_rekening').getValue(),
										},
										method:'POST',
										success:function(response){
											var json=Ext.decode(response.responseText);
											if (json.rows == "sukses"){
												alertDialog('Sukses','Data berhasil disimpan');
												Ext.getCmp('tf_nb').setValue('');
												Ext.getCmp('tf_an').setValue('');
												Ext.getCmp('tf_nr').setValue('');
												Ext.getCmp('tf_formtype').setValue('');
												Ext.getCmp('hd_id_rekening').setValue('');
												wind.close();
												store_rekening.load();
											} else {
												alertDialog('Kesalahan', "Data gagal disimpan. ");
											}
										},
										failure:function(result,action){
											alertDialog('Kesalahan','Data gagal disimpan');
										}
									});
								} else {
									alertDialog('Kesalahan','No Rekening belum diisi.');	
								}
							} else {
								alertDialog('Kesalahan','Atas Nama belum diisi.');	
							}
						}
						else {
							alertDialog('Kesalahan','Nama Bank belum diisi.');
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
						Ext.getCmp('tf_nb').setValue('');
						Ext.getCmp('tf_an').setValue('');
						Ext.getCmp('tf_nr').setValue('');
						Ext.getCmp('tf_formtype').setValue('');
						Ext.getCmp('hd_id_rekening').setValue('');
						Ext.getCmp('cb_status_rekening').setValue('ACTIVE');
						wind.close();
					}
				}]
			}]
		}]
	});
	var grid_rekening=Ext.create('Ext.grid.Panel',{
		id:'grid_rekening_id',
		region:'center',
		store:store_rekening,
        columnLines: true,
		height:500,
		autoScroll:true,
		columns:[{
			dataIndex:'HD_ID',
			header:'id',
			width:100,
			hidden:true
		},{
			dataIndex:'DATA_NAMA_BANK',
			header:'Nama Bank',
			width:240,
			
		},{
			dataIndex:'DATA_ATAS_NAMA',
			header:'Atas Nama',
			width:240,
			
		},{
			dataIndex:'DATA_NO_REKENING',
			header:'No Rekening',
			width:240,
			
		},{
			dataIndex:'DATA_STATUS',
			header:'Status',
			width:140
		}],
		listeners: {
			dblclick: {
				element: 'body', //bind to the underlying body property on the panel
				fn: function(){ 
					var hdid=grid_rekening.getSelectionModel().getSelection()[0].get('HD_ID');
					var statusUser = grid_rekening.getSelectionModel().getSelection()[0].get('DATA_STATUS');
					if (statusUser == 'A') {
						statusUser = 'ACTIVE';
					} else {
						statusUser = 'INACTIVE';
					}
					Ext.getCmp('hd_id_rekening').setValue(hdid);
					Ext.getCmp('tf_formtype').setValue('edit');
					Ext.getCmp('tf_nb').setValue(grid_rekening.getSelectionModel().getSelection()[0].get('DATA_NAMA_BANK'));
					Ext.getCmp('tf_an').setValue(grid_rekening.getSelectionModel().getSelection()[0].get('DATA_ATAS_NAMA'));
					Ext.getCmp('tf_nr').setValue(grid_rekening.getSelectionModel().getSelection()[0].get('DATA_NO_REKENING'));
					Ext.getCmp('cb_status_rekening').setValue(statusUser);
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
				html:'<div align="center"><font size="5"><b>Form Master Rekening</b></font></div>',
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
						Ext.getCmp('cb_status_rekening').setValue('ACTIVE');
						Ext.getCmp('tf_nb').setDisabled(false);
						wind.show(); 
					}
				}]
			}, grid_rekening],
		});
	store_rekening.load();
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