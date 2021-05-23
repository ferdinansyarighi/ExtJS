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
    }

	Ext.clearForm=function(){
		Ext.getCmp('cb_shift').setValue('')
		Ext.getCmp('tf_typeform').setValue('tambah')
		Ext.getCmp('tf_early_in').setValue('')
		Ext.getCmp('tf_early_out').setValue('')
		Ext.getCmp('tf_late_in').setValue('')
		Ext.getCmp('tf_late_out').setValue('')
		Ext.getCmp('cb_status').setValue(true)
		store_workschedule.removeAll();
	};

	var comboShift = Ext.create('Ext.data.Store',{
		fields: [{
			name:'DATA_VALUE'
		},{
			name:'DATA_NAME'
		},{
			name:'DATA_WORK_SCHEDULE'
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
		id:'store_detil',
		pageSize: 100,
		model: 'Plant',
		fields:[{
			name:'DATA_ID'
		},{
			name:'DATA_SHIFT_ID'
		},{
			name:'DATA_SHIFT'
		},{
			name:'DATA_WORK_SCHEDULE'
		},{
			name:'DATA_EARLY_IN'
		},{
			name:'DATA_LATE_IN'
		},{
			name:'DATA_EARLY_OUT'
		},{
			name:'DATA_LATE_OUT'
		},{
			name:'DATA_STATUS'
		},{
			name:'DATA_CREATED_BY'
		},{
			name:'DATA_CREATED_DATE'
		},{
			name:'DATA_UPDATE_BY'
		},{
			name:'DATA_UPDATE_DATE'
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

    var grid_detil = Ext.create('Ext.grid.Panel', {
		id:'grid_detil_id',
        store: store_detil,
		autoScroll:true,
        columnLines: true,
        width: 800,
        height: 300,
        title: 'Employee Shift Data',
        frame: false,
        loadMask: true,
        columns:[{
			xtype:'rownumberer',
			id:'row_id',
			header:'No',
			align : 'center',
			width:50
		},{
			dataIndex:'DATA_ID',
			header:'ID_Range',
			width:100,
			hidden:true
		},{
			dataIndex:'DATA_SHIFT_ID',
			header:'Shift_ID',
			width:100,
			hidden:true
		},{
			dataIndex:'DATA_SHIFT',
			header:'Shift',
			width:150
		},{
			dataIndex:'DATA_WORK_SCHEDULE',
			header:'Work Schedule',
			width:108,
			hideable:false,
			sortable:false,
			align:'center',
			renderer:function(){
				return "<img style='cursor:pointer;'src='../../media/css/images/main-icon/toolbar/detail.png' /> "
			}
		},{
			dataIndex:'DATA_EARLY_IN',
			header:'Range Early In',
			width:90		
		},{
			dataIndex:'DATA_LATE_IN',
			header:'Range Late In',
			width:90		
		},{
			dataIndex:'DATA_EARLY_OUT',
			header:'Range Early Out',
			width:90		
		},{
			dataIndex:'DATA_LATE_OUT',
			header:'Range Late Out',
			width:90	
		},{
			dataIndex:'DATA_STATUS',
			header:'Active',
			width:90
		},{
			dataIndex:'DATA_CREATED_BY',
			header:'Update By',
			width:150,
			hidden:true
		},{
			dataIndex:'DATA_CREATED_DATE',
			header:'Update Date',
			width:150,
			hidden:true	
		},{
			dataIndex:'DATA_UPDATE_BY',
			header:'Update By',
			width:150
		},{
			dataIndex:'DATA_UPDATE_DATE',
			header:'Update Date',
			width:150	
		}],
		listeners:{
		cellclick:function(grid,row,col){
				if (col == 4) {		
					var shifting=grid_detil.getSelectionModel().getSelection()[0].get('DATA_SHIFT_ID');
					store_workschedule_detil.setProxy({
                        type:'ajax',
                        url:'<?PHP echo PATHAPP ?>/master/range/isi_grid_workschedule_detil.php?shift=' +shifting,
                        reader: {
                            type: 'json',
                            root: 'data', 
                            totalProperty:'total'   
                        }
                    });
                    store_workschedule_detil.load(); 
					windwork.show();
				}
			},dblclick:{
        		element:'body',
        		fn:function(){
					var shifts     =grid_detil.getSelectionModel().getSelection()[0].get('DATA_SHIFT');
					var shifts_id  =grid_detil.getSelectionModel().getSelection()[0].get('DATA_SHIFT_ID');
					var earlyins   =grid_detil.getSelectionModel().getSelection()[0].get('DATA_EARLY_IN');
					var earlyouts  =grid_detil.getSelectionModel().getSelection()[0].get('DATA_EARLY_OUT');
					var lateins    =grid_detil.getSelectionModel().getSelection()[0].get('DATA_LATE_IN');
					var lateouts   =grid_detil.getSelectionModel().getSelection()[0].get('DATA_LATE_OUT');
					var id         =grid_detil.getSelectionModel().getSelection()[0].get('DATA_ID');
					Ext.getCmp('tf_typeform').setValue('edit');
					Ext.getCmp('cb_shift').setValue(shifts_id);
					Ext.getCmp('tf_early_in').setValue(earlyins);
					Ext.getCmp('tf_early_out').setValue(earlyouts);
					Ext.getCmp('tf_late_in').setValue(lateins);
					Ext.getCmp('tf_late_out').setValue(lateouts);
					Ext.getCmp('tf_hdid').setValue(id);

					var vstatus=grid_detil.getSelectionModel().getSelection()[0].get('DATA_STATUS');
					if(vstatus == 'Y'){
						Ext.getCmp('cb_status').setValue(true);
					} else {
						Ext.getCmp('cb_status').setValue(false);
					}

					comboShift.setProxy({
						type:'ajax',
						url:'<?PHP echo PATHAPP ?>/combobox/combobox_shift.php?',
						reader: {
							type: 'json',
							root: 'data',
							totalProperty:'total'        
						}
					});

					store_workschedule.setProxy({
						type:'ajax',
						url:'<?PHP echo PATHAPP ?>/master/range/isi_grid_workschedule.php?shift='+shifts_id,
						reader: {
							type: 'json',
							root: 'data',
							totalProperty:'total'        
						}
					});
					store_workschedule.load();

					comboShift.load();
        		}
    		}	
		}
    });

	var store_workschedule=new Ext.data.JsonStore({
		id:'store_workschedule',
		pageSize: 100,
		model: 'Plant',
		fields:[{
			name:'DATA_SEQ_NO'
		},{
			name:'DATA_NAMA_SHIFT'
		},{
			name:'DATA_HARI'
		},{
			name:'DATA_WORKSCHEDULE'
		}],
		proxy:{
			type:'ajax',
			url:'isi_grid_workschedule.php', 
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

    var grid_workschedule = Ext.create('Ext.grid.Panel', {
		id:'grid_workschedule',
        store: store_workschedule,
		autoScroll:true,
        columnLines: true,
        width: 200,
        height: 200,
        columnWidth:.47,
        title: 'Shift Detail',
        columns:[{
			xtype:'rownumberer',
			id:'row_id',
			header:'No',
			align : 'center',
			width:50,
			hidden:true
		},{
			dataIndex:'DATA_SEQ_NO',
			header:'Row_ID',
			width:100,
			hidden:true
		},{
			dataIndex:'DATA_NAMA_SHIFT',
			header:'Shift',
			align : 'center',
			width:150
		},{
			dataIndex:'DATA_HARI',
			header:'Hari',
			align : 'center',
			width:100	
		},{
			dataIndex:'DATA_WORKSCHEDULE',
			header:'Work Schedule',
			align : 'center',
			width:150  
		}],
	});

	var store_workschedule_detil=new Ext.data.JsonStore({
		id:'store_workschedule_detil',
		pageSize: 100,
		model: 'Plant',
		fields:[{
			name:'DATA_SEQ_NO'
		},{
			name:'DATA_NAMA_SHIFT'
		},{
			name:'DATA_HARI'
		},{
			name:'DATA_WORKSCHEDULE'
		}],
		proxy:{
			type:'ajax',
			url:'isi_grid_workschedule_detil.php', 
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

    var grid_workschedule_detil = Ext.create('Ext.grid.Panel', {
		id:'grid_workschedule_detil',
        store: store_workschedule_detil,
		autoScroll:true,
        columnLines: true,
        width: 400,
        height: 220,
        columnWidth:.47,
        title: 'Shift Detail',
        columns:[{
			xtype:'rownumberer',
			id:'row_id',
			header:'No',
			align : 'center',
			width:50,
		},{
			dataIndex:'DATA_SEQ_NO',
			header:'Row_ID',
			width:100,
			align:'center',
			hidden:true
		},{
			dataIndex:'DATA_NAMA_SHIFT',
			header:'Shift',
			align:'center',
			width:100
		},{
			dataIndex:'DATA_HARI',
			header:'Hari',
			align:'center',
			width:100	
		},{
			dataIndex:'DATA_WORKSCHEDULE',
			header:'Work Schedule',
			align:'center',
			width:150  
		}],
	});

	var windwork= Ext.create('Ext.Window',{
		title:'Data Work Schedule',
		width:450,
		height:320,
		layout:'fit',
		closeAction:'hide',
		autoScroll:true,
		items:[{
			xtype:'panel',
			bodyStyle: 'padding-left: 5px;padding-top: 5px;padding-bottom: 10px;border:none',
			items:[{
				xtype:'label',
				html:'<br>',
			},grid_workschedule_detil,{
				xtype:'label',
				html:'<br>',
			},{
				xtype:'label',
				html:'&nbsp',
			},{
				xtype:'button',
				text:'Close',
				width:50,
				handler:function(){
					windwork.close();
				}
			}]
		}]
	});
	
	var contentPanel = Ext.create('Ext.panel.Panel',{
			bodyStyle: 'spacing: 10px;border:none',
			items:[{
				xtype:'label',
				html:'<div align="center"><font size="5"><b>Master Range Karyawan</b></font></div>',
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
						html:'<div style="color:#FF0000">*</div>',
					}]
				},{
					columnWidth:.47,
					border:false,
					layout: 'anchor',
					defaultType: 'combobox',
					items:[{		
						xtype:'combobox',
						fieldLabel:'Shift',
						store: comboShift,
						displayField: 'DATA_NAME',
						valueField: 'DATA_VALUE',
						width:400,
						labelWidth:130,
						id:'cb_shift',
						enableKeyEvents : true,
						minChars:2,
						listeners: {
							select:function(){		
							    maskgrid = new Ext.LoadMask(Ext.getCmp('grid_workschedule'), {msg: "Memuat . . ."});
							    maskgrid.show();	
								var shift=Ext.getCmp('cb_shift').getValue();
								if (Ext.getCmp('cb_shift').getValue()!='') {
									store_workschedule.setProxy({
										type:'ajax',
										url:'<?PHP echo PATHAPP ?>/master/range/isi_grid_workschedule.php?shift='+shift,
										reader: {
											type: 'json',
											root: 'data',
											totalProperty:'total'        
										}
									});
									store_workschedule.load();		
								}else{
									alertDialog('Kesalahan','Shift tidak ada');
								}				
							}
						}
					}]
				}]	
			},{
				layout:'column',
				border:false,
				items:[{
					columnWidth:.177,
					border:false,
					layout: 'anchor',
					defaultType: 'label',
					items:[{
						xtype:'label',
						html:'&nbsp&nbsp&nbsp&nbspWork Schedule:',
					}]
				},grid_workschedule]	
			},{
				xtype:'label',
				html:'&nbsp',
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
					defaultType: 'numberfield',
					items:[{		
						xtype:'numberfield',
						fieldLabel:'Range Early In',
						width:250,
						labelWidth:130,
						id:'tf_early_in',
						minValue:0,
					}]
				},{
					columnWidth:.06,
					border:false,
					layout: 'anchor',
					defaultType: 'label',
					items:[{
						xtype:'label',
						html:'Hour',
						width:10,
						labelWidth:10,
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
						html:'<div style="color:#FF0000">*</div>',
					}]
				},{
					columnWidth:.3,
					border:false,
					layout: 'anchor',
					defaultType: 'numberfield',
					items:[{		
						xtype:'numberfield',
						fieldLabel:'Range Late In',
						width:250,
						labelWidth:130,
						id:'tf_late_in',
						minValue:0,
					}]
				},{
					columnWidth:.06,
					border:false,
					layout: 'anchor',
					defaultType: 'label',
					items:[{
						xtype:'label',
						html:'Hour',
						width:10,
						labelWidth:10,
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
						html:'<div style="color:#FF0000">*</div>',
					}]
				},{
					columnWidth:.3,
					border:false,
					layout: 'anchor',
					defaultType: 'numberfield',
					items:[{		
						xtype:'numberfield',
						fieldLabel:'Range Early Out',
						width:250,
						labelWidth:130,
						id:'tf_early_out',
						minValue:0,
					}]
				},{
					columnWidth:.06,
					border:false,
					layout: 'anchor',
					defaultType: 'label',
					items:[{
						xtype:'label',
						html:'Hour',
						width:10,
						labelWidth:10,
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
					defaultType: 'numberfield',
					items:[{		
						xtype:'numberfield',
						fieldLabel:'Range Late Out',
						width:250,
						labelWidth:130,
						id:'tf_late_out',
						minValue:0,
					}]
				},{
					columnWidth:.06,
					border:false,
					layout: 'anchor',
					defaultType: 'label',
					items:[{
						xtype:'label',
						html:'Hour',
						width:10,
						labelWidth:10,
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
						labelWidth:130,
						id:'cb_status',
						inputValue: 'Y',
						checked: true,
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
					name: 'simpan',
					text: 'Save',
					width:80,
					labelWidth:30,
					handler:function(){
						if(Ext.getCmp('cb_shift').getValue() != ''){
							if(Ext.getCmp('tf_late_in').getValue() != null && Ext.getCmp('tf_late_out').getValue() != null && Ext.getCmp('tf_early_in').getValue() != null && Ext.getCmp('tf_early_out').getValue() != null){
										Ext.Ajax.request({
											url:'<?php echo 'simpan_range.php'; ?>',
											params:{
												shiftid:Ext.getCmp('cb_shift').getValue(),
												shiftname:Ext.getCmp('cb_shift').getRawValue(),
												formtype:Ext.getCmp('tf_typeform').getValue(),
												earlyin:Ext.getCmp('tf_early_in').getRawValue(),
												latein:Ext.getCmp('tf_late_in').getRawValue(),
												earlyout:Ext.getCmp('tf_early_out').getRawValue(),
												lateout:Ext.getCmp('tf_late_out').getRawValue(),
												status:Ext.getCmp('cb_status').getValue(),
												id:Ext.getCmp('tf_hdid').getValue(),
											},
											method:'POST',
											success:function(response){
												var json=Ext.decode(response.responseText);
												if (json.rows == "sukses"){
														alertDialog('Sukses','Data berhasil disimpan');
														Ext.getCmp('cb_shift').setValue('');
														Ext.getCmp('tf_typeform').setValue('tambah');
														Ext.getCmp('tf_early_in').setValue('');
														Ext.getCmp('tf_early_out').setValue('');
														Ext.getCmp('tf_late_in').setValue('');
														Ext.getCmp('tf_late_out').setValue('');
														Ext.getCmp('cb_status').setValue(true);
														store_workschedule.removeAll();
														store_detil.load();
												 }else {
													alertDialog('Kesalahan', "Masih ada range yang aktif pada shift tersebut");
												}
											},
											failure:function(result,action){
												alertDialog('Kesalahan','Data gagal disimpan');
											}
										});
									}
									else {
										alertDialog('Kesalahan','Data range wajib diisi');
									}
								}
								else {
									alertDialog('Kesalahan','Shift wajib diisi');
								}
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
				},{
					xtype:'label',
					html:'&nbsp',
				}, grid_detil ,{
					xtype:'label',
					html:'&nbsp',
				},{
					xtype:'button',
					text:'Export to Excel',
					width:100,
					handler:function(){					
						window.open("isi_excel_range.php");
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
   store_detil.load();
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