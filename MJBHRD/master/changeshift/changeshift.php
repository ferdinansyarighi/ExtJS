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
	var firstDay = new Date(currentTime.getFullYear(), currentTime.getMonth(), 1);
	var day = currentTime.getDate();
	var year = currentTime.getFullYear();
	var tanggalskr = day + "-" + month + "-" + year;
	var rowGrid = 1;

	function formatDate(value){
        return value ? Ext.Date.dateFormat(value, 'Y-m-d') : '';
        //return value;
    }
	Ext.clearForm=function(){
		Ext.getCmp('tf_hdid').setValue('')
		Ext.getCmp('tf_typeform').setValue('tambah')
		Ext.getCmp('cb_company').setValue('')
		Ext.getCmp('cb_nama').setValue('')
		Ext.getCmp('tf_dept').setValue('')
		Ext.getCmp('tf_pos').setValue('')
		Ext.getCmp('cb_grade').setValue('')
		Ext.getCmp('cb_nama_group').setValue('')
		store_detil.removeAll()
	};

	var comboKaryawan = Ext.create('Ext.data.Store', {
		fields: [{
			name:'DATA_VALUE'
		},{
			name:'DATA_NAME'
		},{
			name:'DATA_COMPANY'
		},{
			name:'DATA_GRADE'
		},{
			name:'DATA_LOCATION'
		}],
		proxy:{
			type:'ajax',
			url:'<?PHP echo PATHAPP ?>/combobox/combobox_changeshift.php', 
			reader: {
				type: 'json',
				root: 'data', 
				totalProperty:'total'   
			},
		}
	});

	var comboShift = Ext.create('Ext.data.Store', {
		fields: [{
			name:'DATA_VALUE'
		},{
			name:'DATA_NAME'
		}],
		proxy:{
			type:'ajax',
			url:'<?PHP echo PATHAPP ?>/combobox/combobox_shift.php', 
			reader: {
				type: 'json',
				root: 'data', 
				totalProperty:'total'   
			},
		}
	});

	var store_detil=new Ext.data.JsonStore({
		id:'store_cari_id',
		pageSize: 100,
		model: 'Plant',
		fields:[/*{
			name:'HD_ID'
		},*/{
			name:'DATA_DATE'
		},{
			name:'DATA_DAY'
		},{
			name:'DATA_SHIFT'
		},{
			name:'DATA_WORKSCHEDULE'
		},{
			name:'DATA_WORKHOUR'
		},{
			name:'DATA_SHIFT_ID'
		},{
			name:'DATA_ID'
		},{
			name:'DATA_DATEFROM'
		},{
			name:'DATA_DATETO'
		}],
		proxy:{
			type:'ajax',
			url:'isi_grid_bawah.php', 
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
	//var sm = Ext.create('Ext.selection.CheckboxModel');
    var grid_detil = Ext.create('Ext.grid.Panel', {
		id:'grid_detil_id',
        store: store_detil,
		autoScroll:true,
        columnLines: true,
        width: 800,
        height: 300,
        title: 'Employee Shift Data',
        frame: false,
		//selModel:sm,
        loadMask: true,
        columns:[{
			xtype:'rownumberer',
			id:'row_id',
			header:'No',
			align : 'center',
			width:50
		},{
			dataIndex:'DATA_SHIFT_ID',
			header:'Shift_id',
			width:100,
			hidden:true
		},{
			dataIndex:'DATA_ID',
			header:'id',
			width:100,
			hidden:true
		},{
			dataIndex:'DATA_DATE',
			header:'Date',
			width:90,
			align : 'center',			
		},{
			dataIndex:'DATA_DAY',
			header:'Day',
			width:140,
			
		},{
			dataIndex:'DATA_SHIFT',
			header:'Shift',
			width:170,
			//align:'center',
		},{
			dataIndex:'DATA_WORKSCHEDULE',
			header:'Work Schedule',
			width:165,
		},{
			dataIndex:'DATA_WORKHOUR',
			header:'Work Hour',
			width:165,
			
		},{
			dataIndex:'DATA_DATEFROM',
			header:'Date From',
			id: 'date_from_grid',
			width:100,
			hidden:true
		},{
			dataIndex:'DATA_DATETO',
			header:'Date To',
			id: 'date_to_grid',
			width:100,
			hidden:true
		}],
		listeners: {
			dblclick: {
				element: 'body', //bind to the underlying body property on the panel
				fn: function(){ 
					var hdid =grid_detil.getSelectionModel().getSelection()[0].get('DATA_ID');
					var nama = Ext.getCmp('cb_nama').getRawValue();
					var assid= Ext.getCmp('cb_nama').getValue();
					var date =grid_detil.getSelectionModel().getSelection()[0].get('DATA_DATE');
					Ext.getCmp('tf_formtype').setValue('edit');
					Ext.getCmp('data_id').setValue(hdid);
					Ext.getCmp('tf_nama').setRawValue(nama);
					Ext.getCmp('tf_tanggal').setValue(date);
					Ext.getCmp('tf_assid').setValue(assid);
					Ext.getCmp('cb_shift_id').setValue(grid_detil.getSelectionModel().getSelection()[0].get('DATA_SHIFT_ID'));
					comboShift.load();
					wind.show();
				}
			}
		}
	});

	var wind=Ext.create('Ext.Window', {
		title: 'Form Edit Shift',
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
				id:'data_id',
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
				fieldLabel:'Assignment',
				width:350,
				labelWidth:75,
				id:'tf_assid',
				hidden:true
			},{
				xtype:'textfield',
				fieldLabel:'Nama Karyawan',
				width:450,
				labelWidth:100,
				id:'tf_nama',
				maxLength : 100,
				readOnly : true,
			},{
				xtype:'textfield',
				fieldLabel:'Tanggal',
				width:450,
				labelWidth:100,
				id:'tf_tanggal',
				maxLength : 100,
				readOnly : true,
			},{
				xtype: 'combobox',
				id:'cb_shift_id',
				fieldLabel:'Shift',
				store:comboShift,
				displayField: 'DATA_NAME',
				valueField: 'DATA_VALUE',
				enableKeyEvents : true,
				minChars:1,
				listeners: {
					select:function(f,r,i){
						var id_kode=r[0].data.DATA_VALUE;
						var sme= grid_detil.getSelectionModel().getSelection();							
						var sma= grid_detil.store.indexOf(sme[0]);
						var sm5= grid_detil.getStore().getAt(sma).set('DATA_SHIFT_ID', id_kode);
					},
				}
			},{
				xtype: 'checkboxgroup',
				fieldLabel:'Aktif',
				labelWidth:100,
				hidden:true,
				items: [
					{id: 'cb_status', name: 'cb_status', inputValue: 1, checked: true},
				]
			},{
					xtype:'label',
					html:'&nbsp',
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
						if(Ext.getCmp('tf_nama').getValue()!=''){
							if(Ext.getCmp('data_id').getValue()!=''){
								Ext.Ajax.request({
									url:'<?php echo 'simpan_change.php'; ?>',
									params:{
										typeform:Ext.getCmp('tf_formtype').getValue(),
										assid   :Ext.getCmp('tf_assid').getValue(),
										tanggal :Ext.getCmp('tf_tanggal').getValue(),
										shiftid:Ext.getCmp('cb_shift_id').getValue(),
									},
									method:'POST',
									success:function(response){
										var json=Ext.decode(response.responseText);
										if (json.rows == "sukses"){
											alertDialog('Sukses','Data berhasil disimpan');
											
											store_detil.load({
												params:{
													tglfrom:Ext.getCmp('tf_tgl_from').getRawValue(),
													tglto:Ext.getCmp('tf_tgl_to').getRawValue(),
													assid:Ext.getCmp('cb_nama').getValue(),
												}
											});
											wind.close();
											Ext.clearForm();
										} else {
											alertDialog('Kesalahan', json.rows);
										}
									},
									failure:function(result,action){
										alertDialog('Kesalahan','Data gagal disimpan');
									}
								});
							}
							else {
								alertDialog('Peringatan','Shift Belum di Set.');
							}
							}
						else {
							alertDialog('Peringatan','Nama Karyawan Kosong.');
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
						//Ext.clearForm();
						wind.close();
					}
				}]
			}]
		}]
	});
	
	var contentPanel = Ext.create('Ext.panel.Panel',{
			//title:'Form Input Tanda Terima',
			//region:'center',
			bodyStyle: 'spacing: 10px;border:none',
			items:[{
				xtype:'label',
				html:'<div align="center"><font size="5"><b>Change Shift Karyawan</b></font></div>',
			},{
				xtype:'label',
				html:'&nbsp',
			},{		
				xtype:'textfield',
				fieldLabel:'id',
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
					columnWidth:.47,
					border:false,
					layout: 'anchor',
					defaultType: 'combobox',
					items:[{		
						xtype:'combobox',
						fieldLabel:'Nama Karyawan',
						store: comboKaryawan,
						displayField: 'DATA_NAME',
						valueField: 'DATA_VALUE',
						width:400,
						labelWidth:130,
						id:'cb_nama',
						//value:'All',
						//editable: false 
						enableKeyEvents : true,
						minChars:2,
						listeners: {
							select:function(f,r,i){
								var nama_kar=r[0].data.DATA_NAME;
								var company_kar=r[0].data.DATA_COMPANY;
								var grade_kar=r[0].data.DATA_GRADE;
								var location_kar=r[0].data.DATA_LOCATION;
								Ext.Ajax.request({
									url:'<?php echo 'isi_dept.php'; ?>',
										params:{
											nama_pem:nama_kar,
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
									url:'<?php echo 'isi_position.php'; ?>',
										params:{
											nama_pem:nama_kar,
										},
										success:function(response){
											var json=Ext.decode(response.responseText);
											var deskripsiasli = json.results;
											//var deskripsisplit = deskripsiasli.split('.');
											//var nama_dept = deskripsisplit[1];
											Ext.getCmp('tf_pos').setValue(deskripsiasli);
										},
									method:'POST',
								});
								Ext.getCmp('cb_company').setValue(company_kar);
								Ext.getCmp('cb_grade').setValue(grade_kar);
								Ext.getCmp('cb_nama_group').setValue(location_kar);
							},
							
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
						html:'',
					}]
				},{
					columnWidth:.47,
					border:false,
					layout: 'anchor',
					defaultType: 'textfield',
					items:[{		
						xtype:'textfield',
						fieldLabel:'Company',
						width:400,
						labelWidth:130,
						id:'cb_company',
						readOnly: 'true',
						fieldStyle:'background:#B8B8B8 ;font-weight:bold;',
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
					defaultType: 'textfield',
					items:[{		
						xtype:'textfield',
						fieldLabel:'Departemen',
						width:340,
						labelWidth:130,
						id:'tf_dept',
						fieldStyle:'background:#B8B8B8 ;font-weight:bold;',
						readOnly: 'true',
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
					columnWidth:.8,
					border:false,
					layout: 'anchor',
					defaultType: 'textfield',
					items:[{		
						xtype:'textfield',
						fieldLabel:'Position / Jabatan',
						width:340,
						labelWidth:130,
						id:'tf_pos',
						fieldStyle:'background:#B8B8B8 ;font-weight:bold;',
						readOnly: 'true',
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
					defaultType: 'textfield',
					items:[{		
						xtype:'textfield',
						fieldLabel:'Grade',
						width:340,
						labelWidth:130,
						id:'cb_grade',
						readOnly: 'true',
						fieldStyle:'background:#B8B8B8 ;font-weight:bold;',
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
					defaultType: 'textfield',
					items:[{		
						xtype:'textfield',
						fieldLabel:'Location',
						width:340,
						labelWidth:130,
						id:'cb_nama_group',
						readOnly: 'true',
						fieldStyle:'background:#B8B8B8 ;font-weight:bold;',
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
						columnWidth:.3,
						border:false,
						layout: 'anchor',
						defaultType: 'datefield',
						items:[{
							name: 'date1',
							fieldLabel: 'Period',
							width:250,
							labelWidth:130,
							//editable: false,
							id:'tf_tgl_from'
						}]
				},{
						columnWidth:.2,
						border:false,
						layout: 'anchor',
						defaultType: 'datefield',
						items:[{
							name: 'date2',
							fieldLabel: 's/d',
							width:150,
							labelWidth:30,
							//editable: false,
							id:'tf_tgl_to'
						}]
					}]	
			},{
				xtype:'label',
				html:'&nbsp',
			},{
					columnWidth:.2,
					border:false,
					layout: 'anchor',
					defaultType: 'button',
					items:[{
						name: 'cari',
						text: 'Filter',
						width:80,
						labelWidth:30,
						handler:function(){
							maskgrid = new Ext.LoadMask(Ext.getCmp('grid_detil_id'), {msg: "Memuat . . ."});
							maskgrid.show();
							store_detil.load({
								params:{
									tglfrom:Ext.getCmp('tf_tgl_from').getRawValue(),
									tglto:Ext.getCmp('tf_tgl_to').getRawValue(),
									assid:Ext.getCmp('cb_nama').getValue(),
								}
							});
						} 
					},{
						xtype:'label',
						html:'&nbsp',
					}, grid_detil ,{
						xtype:'label',
						html:'&nbsp',
					},{
						name: 'batal',
						text: 'Cancel',
						width:80,
						labelWidth:30,
						handler:function(){
								Ext.clearForm();
						} 
					}]
				},{
				xtype:'label',
				html:'&nbsp',
			}, grid_detil ,{
				xtype:'label',
				html:'&nbsp',
			}],
		});	

   contentPanel.render('page');
   Ext.getCmp('tf_tgl_from').setValue(firstDay);
   Ext.getCmp('tf_tgl_to').setValue(currentTime);

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