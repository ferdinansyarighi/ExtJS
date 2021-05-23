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
	Ext.clearForm=function(){
		Ext.getCmp('hd_id').setValue('')
		Ext.getCmp('tf_formtype').setValue('tambah')
		Ext.getCmp('cb_pa').setValue('')
		Ext.getCmp('cb_plant').setValue('')
		Ext.getCmp('tf_tgl_awal').setValue(currentTime)
		Ext.getCmp('tf_tgl_akhir').setValue('')
		grid_store.load()
	};
	var grid_store=new Ext.data.JsonStore({
		id:'grid_store_id',
		pageSize: 50,
		fields:[{
			name:'HD_ID'
		},{
			name:'DATA_PLANT_AREA_ID'
		},{
			name:'DATA_PLANT_AREA'
		},{
			name:'DATA_PLANT_ID'
		},{
			name:'DATA_PLANT'
		},{
			name:'DATA_UPDATE_BY'
		},{
			name:'DATA_UPDATE_DATE'
		},{
			name:'DATA_STATUS'
		},{
			name:'DATA_START_DATE'
		},{
			name:'DATA_END_DATE'
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
			dataIndex:'DATA_START_DATE',
			header:'id',
			width:100,
			hidden:true
		},{
			dataIndex:'DATA_END_DATE',
			header:'id',
			width:100,
			hidden:true
		},{
			dataIndex:'DATA_PLANT_AREA_ID',
			header:'id',
			width:100,
			hidden:true
		},{
			dataIndex:'DATA_PLANT_ID',
			header:'id',
			width:100,
			hidden:true
		},{
			dataIndex:'DATA_PLANT_AREA',
			header:'Plant Area',
			width:200,
			
		},{
			dataIndex:'DATA_PLANT',
			header:'Plant',
			align:'left',
			width:170,
			
		},{
			dataIndex:'DATA_START_DATE',
			header:'Start Date',
			width:80,
			
		},{
			dataIndex:'DATA_END_DATE',
			header:'End Date',
			width:80,
			
		},{
			dataIndex:'DATA_UPDATE_BY',
			header:'Update By',
			width:160,
			
		},{
			dataIndex:'DATA_UPDATE_DATE',
			header:'Update Date',
			width:140,
			
		}],
		listeners: {
			dblclick: {
				element: 'body', //bind to the underlying body property on the panel
				fn: function(){ 
					var hdid=grid_view.getSelectionModel().getSelection()[0].get('HD_ID');
					var idlg=grid_view.getSelectionModel().getSelection()[0].get('DATA_PLANT_AREA_ID');
					Ext.getCmp('hd_id').setValue(hdid);
					Ext.getCmp('tf_formtype').setValue('edit');
					comboPA.load({
						params:{
							hd_id:idlg,
						}
					});
					comboPlant.load({
						params : {
							tipe:idlg,
						}
					});
					Ext.getCmp('cb_pa').setValue(idlg);
					Ext.getCmp('cb_plant').setValue(grid_view.getSelectionModel().getSelection()[0].get('DATA_PLANT_ID'));
					Ext.getCmp('tf_tgl_awal').setValue(grid_view.getSelectionModel().getSelection()[0].get('DATA_START_DATE'));
					Ext.getCmp('tf_tgl_akhir').setValue(grid_view.getSelectionModel().getSelection()[0].get('DATA_END_DATE'));
					wind.show();
				}
			}
		}
	});
	var comboPA = Ext.create('Ext.data.Store', {
		fields: [{
			name:'DATA_VALUE'
		},{
			name:'DATA_NAME'
		}],
		proxy:{
			type:'ajax',
			url:'<?PHP echo PATHAPP ?>/combobox/combobox_plant_area_driver.php', 
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
			url:'<?PHP echo PATHAPP ?>/combobox/combobox_plant_ritasi.php', 
			reader: {
				type: 'json',
				root: 'data', 
				totalProperty:'total'   
			},
		}
	});
	var wind=Ext.create('Ext.Window', {
		title: 'Form Input Plant Area Driver',
		width: 500,
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
				xtype:'combobox',
				fieldLabel:'Plant Area',
				store: comboPA,
				displayField: 'DATA_NAME',
				valueField: 'DATA_VALUE',
				width:450,
				labelWidth:100,
				id:'cb_pa',
				value:'',
				emptyText : '- Pilih -',
				//editable: false 
				enableKeyEvents : true,
				minChars:1,
				listeners: {
					select:function(f,r,i){
						var nama_pem=r[0].data.DATA_VALUE;
						Ext.getCmp('cb_plant').setValue('');
						comboPlant.load({
							params : {
								tipe:nama_pem,
							}
						});
					}
				}
			},{		
				xtype:'combobox',
				fieldLabel:'Plant',
				store: comboPlant,
				displayField: 'DATA_NAME',
				valueField: 'DATA_VALUE',
				width:450,
				labelWidth:100,
				id:'cb_plant',
				value:'',
				emptyText : '- Pilih -',
				//editable: false 
				enableKeyEvents : true,
				minChars:1,
				queryMode: 'local',
			},{
				layout:'column',
				border:false,
				items:[{
					columnWidth:.45,
					border:false,
					layout: 'anchor',
					defaultType: 'combobox',
					items:[{		
						xtype:'datefield',
						fieldLabel:'Active Date',
						width:200,
						labelWidth:100,
						id:'tf_tgl_awal',
						editable:false,
						// readOnly:true,
						// fieldStyle:'background:#B8B8B8 ;font-weight:bold;'
					}]
				},{
					columnWidth:.01,
					border:false,
					layout: 'anchor',
					defaultType: 'label',
					items:[{
						xtype:'label',
						html:'',
					}]
				},{
					columnWidth:.27,
					border:false,
					layout: 'anchor',
					defaultType: 'combobox',
					items:[{		
						xtype:'datefield',
						fieldLabel:'s/d',
						width:125,
						labelWidth:25,
						id:'tf_tgl_akhir',
						editable:false,
						// readOnly:true,
						// fieldStyle:'background:#B8B8B8 ;font-weight:bold;'
					}]
				},{
					columnWidth:.2,
					border:false,
					layout: 'anchor',
					defaultType: 'button',
					items:[{
						xtype:'button',
						text:'X',
						width:20,
						handler:function(){
							Ext.getCmp('tf_tgl_akhir').setValue('');
						}
					}]
				}]	
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
						var ArrUbahTglFrom = Ext.getCmp('tf_tgl_awal').getRawValue().split('/');
						var UbahTglFrom = ArrUbahTglFrom[2]+ArrUbahTglFrom[1]+ArrUbahTglFrom[0];
						var ArrUbahTglTo = Ext.getCmp('tf_tgl_akhir').getRawValue().split('/');
						var UbahTglTo = ArrUbahTglTo[2]+ArrUbahTglTo[1]+ArrUbahTglTo[0];				
						if(Ext.getCmp('cb_pa').getValue()!='' && Ext.getCmp('cb_plant').getValue()!=0){
							if(UbahTglFrom <= UbahTglTo){
								Ext.Ajax.request({
									url:'<?php echo 'simpan_plantarea.php'; ?>',
									params:{
										hdid:Ext.getCmp('hd_id').getValue(),
										typeform:Ext.getCmp('tf_formtype').getValue(),
										plantArea:Ext.getCmp('cb_pa').getValue(),
										plant:Ext.getCmp('cb_plant').getValue(),
										tgl_awal:Ext.getCmp('tf_tgl_awal').getRawValue(),
										tgl_akhir:Ext.getCmp('tf_tgl_akhir').getRawValue(),
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
								alertDialog('Peringatan','Active date awal tidak boleh lebih besar dari active date akhir.');
							}
						}
						else {
							alertDialog('Peringatan','Plant Area atau Plant belum diisi.');
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
				html:'<div align="center"><font size="5"><b>Form Master Plant Area Driver</b></font></div>',
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