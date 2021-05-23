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
	var day = currentTime.getDate();
	var year = currentTime.getFullYear();
	var tanggalskr = day + "-" + month + "-" + year;
	var rowGrid = 1;
	function GetUserRecord(userID) {
		var recordIndex = store_detil.find('HD_ID', userID);
		//console.log("RowIndex" + recordIndex);
		if (recordIndex > -1) {
			return store_detil.getAt(recordIndex);
		} else {
			return null;
		}
	}
	function formatDate(value){
        return value ? Ext.Date.dateFormat(value, 'Y-m-d') : '';
        //return value;
    }
	Ext.clearForm=function(){
		Ext.getCmp('tf_hdid').setValue('')
		Ext.getCmp('tf_typeform').setValue('tambah')
		Ext.getCmp('cb_company').setValue('All')
		Ext.getCmp('cb_nama').setValue('')
		Ext.getCmp('tf_dept').setValue('')
		Ext.getCmp('tf_pos').setValue('')
		Ext.getCmp('tf_grade').setValue('')
		Ext.getCmp('cb_periode').setValue('BULANAN')
		Ext.getCmp('cb_nama_group').setValue('')
		Ext.getCmp('cb_company').setDisabled(false);
					Ext.getCmp('cb_nama').setDisabled(false);
		store_detil.removeAll()
	};
	var comboPeriode = Ext.create('Ext.data.Store', {
		fields: ['DATA_NAME', 'DATA_VALUE'],
		data : [
			{"DATA_NAME":"Bulanan", "DATA_VALUE":"BULANAN"},
			{"DATA_NAME":"Mingguan", "DATA_VALUE":"MINGGUAN"}
		]
	});
	var comboCompany = Ext.create('Ext.data.Store', {
		fields: [{
			name:'DATA_VALUE'
		},{
			name:'DATA_NAME'
		}],
		proxy:{
			type:'ajax',
			url:'<?PHP echo PATHAPP ?>/combobox/combobox_company_linkgroup.php', 
			reader: {
				type: 'json',
				root: 'data', 
				totalProperty:'total'   
			},
		}
	});
	var comboKaryawan = Ext.create('Ext.data.Store', {
		fields: [{
			name:'DATA_VALUE'
		},{
			name:'DATA_NAME'
		},{
			name:'DATA_GRADE'
		}],
		proxy:{
			type:'ajax',
			url:'<?PHP echo PATHAPP ?>/combobox/combobox_kar_linkgroup.php', 
			reader: {
				type: 'json',
				root: 'data', 
				totalProperty:'total'   
			},
		}
	});
	var comboGroup = Ext.create('Ext.data.Store', {
		fields: [{
			name:'DATA_VALUE'
		},{
			name:'DATA_NAME'
		}],
		proxy:{
			type:'ajax',
			url:'<?PHP echo PATHAPP ?>/combobox/combobox_group.php', 
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
		fields:[{
			name:'HD_ID'
		},{
			name:'DATA_NAMA'
		},{
			name:'DATA_KOMPONEN'
		},{
			name:'DATA_TYPE'
		},{
			name:'DATA_VALUE'
		},{
			name:'DATA_DEFAULT_VALUE'
		},{
			name:'DATA_SATUAN'
		},{
			name:'DATA_OLEH'
		},{
			name:'DATA_TGL'
		},{
			name:'DATA_DEFAULT'
		},{
			name:'DATA_SATUAN'
		},{
			name:'DATA_ID_GROUP_DETAIL'
		},{
			name:'DATA_CEK'
		}],
		proxy:{
			type:'ajax',
			url:'isi_grid.php', 
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
        width: 850,
        height: 300,
        title: 'Data Group Element',
        frame: false,
		//selModel:sm,
        loadMask: true,
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
			dataIndex:'DATA_ID_GROUP_DETAIL',
			header:'id group detail',
			width:100,
			hidden:true
		},{
			dataIndex:'DATA_DEFAULT_VALUE',
			header:'Default Value',
			width:100,
			hidden:true
		},{
			dataIndex:'DATA_CEK',
			header:'Readonly',
			width:100,
			hidden:true
		},{
			dataIndex:'DATA_NAMA',
			header:'Nama Element',
			width:200,
			
		},{
			dataIndex:'DATA_VALUE',
			header:'Value',
			width:100,
			getEditor:function(record){
				//console.log(record.get('DATA_VALUE'));
				if(record.get('DATA_CEK') == 1){
					return Ext.create('Ext.grid.CellEditor',{
						field:{
							xtype:'numberfield',
							// id:'tf_value',
							readOnly:true,
						}
					});
				}
				else{
					return Ext.create('Ext.grid.CellEditor',{
						field:{
							xtype:'numberfield',
							// id:'tf_value',
							readOnly:false,
						}
					});
				}
				
			}
		},{
			dataIndex:'DATA_KOMPONEN',
			header:'+/-',
			//align:'right',
			width:50,
			align:'center',
		},{
			dataIndex:'DATA_TYPE',
			header:'Element',
			width:120,
			
		},{
			dataIndex:'DATA_SATUAN',
			header:'Satuan',
			width:60,
		},{
			dataIndex:'DATA_OLEH',
			header:'Update By',
			width:160,
			
		},{
			dataIndex:'DATA_TGL',
			header:'Update Date',
			width:140,
		}],
        plugins: [cellEditing],
		// listeners:{
			// cellclick:function(grid,row,col){
				// // alert(col);
				// if (col==3) {
					// var bool=grid_detil.getSelectionModel().getSelection()[0].get('DATA_NAMA');
					// console.log(bool);
					// if(bool){
						// Ext.getCmp('tf_value').setDisabled(true)
					// } else {
						// Ext.getCmp('tf_value').setDisabled(false)
					// }
				// } 
			// }
		// } 
    });
	
	var store_group=new Ext.data.JsonStore({
		id:'store_group',
		pageSize: 100,
		//model: 'Plant',
		fields:[{
			name:'HD_ID'
		},{
			name:'DATA_COMPANY_ID'
		},{
			name:'DATA_COMPANY'
		},{
			name:'DATA_PERSON_ID'
		},{
			name:'DATA_PERSON'
		},{
			name:'DATA_ID_GROUP'
		},{
			name:'DATA_GROUP'
		},{
			name:'DATA_OLEH'
		},{
			name:'DATA_TGL'
		},{
			name:'DATA_PERIODE'
		},{
			name:'DATA_GRADE'
		}],
		proxy:{
			type:'ajax',
			url:'isi_grid_group.php', 
			reader: {
				root: 'rows',  
			},
		},
	});
	var grid_group=Ext.create('Ext.grid.Panel',{
		id:'grid_group',
		region:'center',
		store:store_group,
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
			dataIndex:'DATA_GRADE',
			header:'Grade',
			width:100,
			hidden:true
		},{
			dataIndex:'DATA_COMPANY',
			header:'Company',
			//align:'right',
			width:120,
			
		},{
			dataIndex:'DATA_PERSON',
			header:'Nama Karyawan',
			width:120,
			
		},{
			dataIndex:'DATA_PERIODE',
			header:'Periode',
			width:120,
			
		},{
			dataIndex:'DATA_GROUP',
			header:'Group Element',
			//align:'right',
			width:120,
			
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
					Ext.getCmp('cb_company').setDisabled(true);
					Ext.getCmp('cb_nama').setDisabled(true);
					store_detil.removeAll();
					var hdid=grid_group.getSelectionModel().getSelection()[0].get('HD_ID');
					store_detil.load({
						params:{
							hdid:hdid,
						}
					});
					Ext.getCmp('tf_hdid').setValue(hdid);
					Ext.getCmp('tf_typeform').setValue('edit');
					Ext.getCmp('cb_company').setValue(grid_group.getSelectionModel().getSelection()[0].get('DATA_COMPANY_ID'));
					comboCompany.load();
					Ext.getCmp('cb_nama').setValue(grid_group.getSelectionModel().getSelection()[0].get('DATA_PERSON_ID'));
					comboKaryawan.load();
					Ext.getCmp('tf_grade').setValue(grid_group.getSelectionModel().getSelection()[0].get('DATA_GRADE'));
					Ext.getCmp('cb_periode').setValue(grid_group.getSelectionModel().getSelection()[0].get('DATA_PERIODE'));
					var id_group = grid_group.getSelectionModel().getSelection()[0].get('DATA_ID_GROUP');
					var nama_kar = grid_group.getSelectionModel().getSelection()[0].get('DATA_PERSON');
					Ext.getCmp('cb_nama_group').setValue(id_group);
					comboGroup.load();
					
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
					//console.log(rowGrid+'wqa');
					
					// Ext.getCmp('cb_status').setValue(statusUser);
					var org_id = Ext.getCmp('cb_company').getValue();
					var person_id = Ext.getCmp('cb_nama').getValue();
					var periode = Ext.getCmp('cb_periode').getValue();
					comboGroup.setProxy({
						type:'ajax',
						url:'<?PHP echo PATHAPP ?>/combobox/combobox_group.php?company_id=' + org_id +'&person_id='+person_id+'&periode='+periode,
						reader: {
							type: 'json',
							root: 'data', 
							totalProperty:'total'   
						}
					});
					comboGroup.load();
					wind.hide();
				},
				
			},
			
		}
	});
	var wind = Ext.create('Ext.Window', {
            width: 665,
            height: 350,
			title: 'List Group element',
            layout: 'fit',
            closeAction: 'hide',
			tbar:[{
				xtype:'textfield',
				fieldLabel:'Nama Karyawan',
				maxLengthText:5,
				id:'tf_filter',
				labelWidth:100,
				listeners: {
					 change: function(field,newValue,oldValue){
							field.setValue(newValue.toUpperCase());
					}
				}
			},{
				xtype:'button',
				text:'Cari',
				scale:'small',
				width: 45,
				border: 1,
				style: {
					borderColor: 'black',
					borderStyle: 'solid'
				},
				cls:'button-popup',
				handler:function(){
					//if(Ext.getCmp('tf_filter').getValue()){
						//maskgrid = new Ext.LoadMask(Ext.getCmp('grid_popupheader_id'), {msg: "Memuat . . ."});
						//maskgrid.show();
						store_group.load({
							params:{
								tffilter:Ext.getCmp('tf_filter').getValue(),
							}
						});
					// }
					// else {
						// alertDialog('Kesalahan','Filter blm diisi.');
					// }
					
				}
			}],
            items: grid_group
        });
	var contentPanel = Ext.create('Ext.panel.Panel',{
			//title:'Form Input Tanda Terima',
			//region:'center',
			bodyStyle: 'spacing: 10px;border:none',
			items:[{
				xtype:'label',
				html:'<div align="center"><font size="5"><b>Link Group</b></font></div>',
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
						fieldLabel:'Company',
						store: comboCompany,
						displayField: 'DATA_NAME',
						valueField: 'DATA_VALUE',
						width:400,
						labelWidth:130,
						id:'cb_company',
						value:'All',
						editable: false,
						enableKeyEvents : true,
						minChars:1,
						listeners: {
							select:function(f,r,i){
								var org_id=r[0].data.DATA_VALUE;
								Ext.getCmp('cb_nama').setValue('');
								var person_id = Ext.getCmp('cb_nama').getValue();
								var periode = Ext.getCmp('cb_periode').getValue();
								comboGroup.setProxy({
									type:'ajax',
									url:'<?PHP echo PATHAPP ?>/combobox/combobox_group.php?company_id=' + org_id +'&person_id='+person_id+'&periode='+periode,
									reader: {
										type: 'json',
										root: 'data', 
										totalProperty:'total'   
									}
								});
								comboGroup.load();
								comboKaryawan.setProxy({
									type:'ajax',
									url:'<?PHP echo PATHAPP ?>/combobox/combobox_kar_linkgroup.php?org_id='+org_id,
									reader: {
										type: 'json',
										root: 'data', 
										totalProperty:'total'   
									}
								});
								comboKaryawan.load();
							},
						}
					}]
				},{
					columnWidth:.2,
					border:false,
					layout: 'anchor',
					defaultType: 'button',
					items:[{
						name: 'cari',
						text: 'Search',
						width:80,
						handler:function(){
							Ext.clearForm();
							store_group.load();
							wind.show(); 
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
					columnWidth:.6,
					border:false,
					layout: 'anchor',
					defaultType: 'combobox',
					items:[{		
						xtype:'combobox',
						fieldLabel:'Nama Karyawan',
						store: comboKaryawan,
						displayField: 'DATA_NAME',
						valueField: 'DATA_VALUE',
						width:450,
						labelWidth:130,
						id:'cb_nama',
						//value:'All',
						//editable: false 
						enableKeyEvents : true,
						minChars:2,
						listeners: {
							select:function(f,r,i){
								var nama_kar=r[0].data.DATA_NAME;
								var person_id=r[0].data.DATA_VALUE;
								var grade=r[0].data.DATA_GRADE;
								Ext.getCmp('tf_grade').setValue(grade);
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
								var company_id = Ext.getCmp('cb_company').getValue();
								var periode = Ext.getCmp('cb_periode').getValue();
								comboGroup.setProxy({
									type:'ajax',
									url:'<?PHP echo PATHAPP ?>/combobox/combobox_group.php?company_id='+company_id+'&person_id=' + person_id+'&periode='+periode,
									reader: {
										type: 'json',
										root: 'data', 
										totalProperty:'total'   
									}
								});
								comboGroup.load();
								// store_detil.load({
									// params:{
										// person_id:person_id,
									// }
								// });		
							},
							
						}
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
					columnWidth:.8,
					border:false,
					layout: 'anchor',
					defaultType: 'textfield',
					items:[{		
						xtype:'textfield',
						fieldLabel:'Grade',
						width:340,
						labelWidth:130,
						id:'tf_grade',
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
					defaultType: 'combobox',
					items:[{		
						xtype:'combobox',
						fieldLabel:'Periode Gaji',
						store: comboPeriode,
						displayField: 'DATA_NAME',
						valueField: 'DATA_VALUE',
						width:340,
						labelWidth:130,
						id:'cb_periode',
						value:'BULANAN',
						queryMode:'local',
						editable: false,
						enableKeyEvents : true,
						minChars:1,
						listeners: {
							select:function(f,r,i){
								Ext.getCmp('cb_nama_group').setValue('');
								var periode=r[0].data.DATA_VALUE;
								var company_id = Ext.getCmp('cb_company').getValue();
								var person_id = Ext.getCmp('cb_nama').getValue();
								comboGroup.setProxy({
									type:'ajax',
									url:'<?PHP echo PATHAPP ?>/combobox/combobox_group.php?company_id='+company_id+'&periode=' + periode+'&person_id='+person_id,
									reader: {
										type: 'json',
										root: 'data', 
										totalProperty:'total'   
									}
								});
								comboGroup.load();
							},
						}
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
					defaultType: 'combobox',
					items:[{		
						xtype:'combobox',
						fieldLabel:'Nama Group Element',
						store: comboGroup,
						displayField: 'DATA_NAME',
						valueField: 'DATA_VALUE',
						width:340,
						labelWidth:130,
						id:'cb_nama_group',
						//value:'All',
						//editable: false 
						enableKeyEvents : true,
						minChars:1,
						listeners: {
							select:function(f,r,i){
								var id_group=r[0].data.DATA_VALUE;
								store_detil.load({
									params:{
										dtid:id_group,
									}
								});
								// store_detil.setProxy({
									// type:'ajax',
									// url:'<?PHP echo PATHAPP ?>/master/linkgroup/isi_grid.php?dtid=' + id_group,
									// reader: {
										// type: 'json',
										// root: 'data', 
										// totalProperty:'total'   
									// }
								// });
								// store_detil.load();
							},
							
						}
					}]
				}]	
			},{
				xtype:'label',
				html:'&nbsp',
			}, grid_detil ,{
				xtype:'label',
				html:'&nbsp',
			},{
				xtype:'panel',
				bodyStyle: 'padding-left: 300px;padding-bottom: 50px;border:none',
				items:[{
					xtype:'button',
					text:'Simpan',
					width:80,
					handler:function(){
						var countGrid = 0;
						var i=0;
						var j=0;
						var arrTransID = new Array();
						var arrDefault = new Array();
						var arrIdGroupDetail = new Array();
						store_detil.each(
							function(record)  {
								arrTransID[i]=record.get('HD_ID');
								arrDefault[i]=record.get('DATA_VALUE');
								arrIdGroupDetail[i]=record.get('DATA_ID_GROUP_DETAIL');
								countGrid++;
								if(arrDefault[i]!=''){
									j=j+1;
								}
								i=i+1;
							}
						);
						if(Ext.getCmp('cb_nama').getValue() != null){
							//console.log(Ext.getCmp('cb_nama').getValue());
							if(Ext.getCmp('cb_nama_group').getValue() != null){
								//if (j==countGrid){
									maskgrid = new Ext.LoadMask(Ext.getCmp('grid_detil_id'), {msg: "Harap Tunggu. . .Sedang Proses . . ."});
									maskgrid.show();
									Ext.Ajax.request({
										url:'<?php echo 'simpan_group.php'; ?>',
										timeout: 500000,
										params:{
											hdid:Ext.getCmp('tf_hdid').getValue(),
											typeform:Ext.getCmp('tf_typeform').getValue(),
											company:Ext.getCmp('cb_company').getValue(),
											nama:Ext.getCmp('cb_nama').getValue(),
											periode:Ext.getCmp('cb_periode').getValue(),
											group:Ext.getCmp('cb_nama_group').getValue(),
											arrTransID:Ext.encode(arrTransID),
											arrDefault:Ext.encode(arrDefault),
											arrIdGroupDetail:Ext.encode(arrIdGroupDetail),
										},
										method:'POST',
										success:function(response){
											maskgrid.hide();
											var json=Ext.decode(response.responseText);
											if (json.rows == "sukses"){
												alertDialog('Sukses', "Data tersimpan.");
												rowGrid = 0;
												//store_detil.removeAll();
												maskgrid = new Ext.LoadMask(Ext.getCmp('grid_detil_id'), {msg: "Memuat . . ."});
												maskgrid.show();
												Ext.clearForm();
												maskgrid.hide();
											} else {
												alertDialog('Kesalahan', "Data gagal disimpan. ");
											} 
										},
										failure:function(error){
											maskgrid.hide();
											alertDialog('Kesalahan','Data gagal disimpan');
										}
									});
								// } else {
									// alertDialog('Kesalahan','Data Value pada grid element tidak boleh kosong.');
								// }
							} else {
								alertDialog('Kesalahan','Nama Group tidak boleh kosong.');
							}						
						} else {
							alertDialog('Kesalahan','Nama Karyawan tidak boleh kosong.');
						} 
					}
				},{
					xtype:'label',
					html:'&nbsp',
				},{
					xtype:'button',
					text:'Cancel',
					width:80,
					handler:function(){
						Ext.clearForm();
					}
				}]
			}],
		});	
		comboGroup.setProxy({
			type:'ajax',
			url:'<?PHP echo PATHAPP ?>/combobox/combobox_group.php?periode=BULANAN',
			reader: {
				type: 'json',
				root: 'data', 
				totalProperty:'total'   
			}
		});
		comboGroup.load();
   contentPanel.render('page');
   //comboPemohon.load();
   //store_detil.load();
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