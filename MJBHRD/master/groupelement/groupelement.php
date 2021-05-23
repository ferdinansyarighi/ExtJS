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
		Ext.getCmp('tf_namaGroup').setValue('')
		Ext.getCmp('cb_group').setValue('HO')
		Ext.getCmp('cb_company').setValue('All')
		Ext.getCmp('cb_plant').setValue('All')
		Ext.getCmp('cb_grade').setValue('All')
		Ext.getCmp('cb_periode').setValue('BULANAN')
		Ext.getCmp('cb_tipe_lembur').setValue('All')
		Ext.getCmp('cb_status').setValue(true)
		Ext.getCmp('cb_grade').setDisabled(false)
		store_detil.removeAll()
		store_detil.load()
	};
	var comboSatuan = Ext.create('Ext.data.Store', {
		fields: ['DATA_NAME', 'DATA_VALUE'],
		data : [
			{"DATA_NAME":"IDR", "DATA_VALUE":"IDR"},
			{"DATA_NAME":"%", "DATA_VALUE":"%"}
		]
	});
	var comboPeriode = Ext.create('Ext.data.Store', {
		fields: ['DATA_NAME', 'DATA_VALUE'],
		data : [
			{"DATA_NAME":"Bulanan", "DATA_VALUE":"BULANAN"},
			{"DATA_NAME":"Mingguan", "DATA_VALUE":"MINGGUAN"}
		]
	});
	var comboGroup = Ext.create('Ext.data.Store', {
		fields: ['DATA_NAME', 'DATA_VALUE'],
		data : [
			{"DATA_NAME":"HO", "DATA_VALUE":"HO"},
			{"DATA_NAME":"Plant", "DATA_VALUE":"PLANT"}
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
			url:'<?PHP echo PATHAPP ?>/combobox/combobox_company.php', 
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
			url:'<?PHP echo PATHAPP ?>/combobox/combobox_plant_element.php', 
			reader: {
				type: 'json',
				root: 'data', 
				totalProperty:'total'   
			},
		}
	});
	var comboGrade = Ext.create('Ext.data.Store', {
		fields: [{
			name:'DATA_VALUE'
		},{
			name:'DATA_NAME'
		}],
		proxy:{
			type:'ajax',
			url:'<?PHP echo PATHAPP ?>/combobox/combobox_grade2.php', 
			reader: {
				type: 'json',
				root: 'data', 
				totalProperty:'total'   
			},
		}
	});
	var comboIjin = Ext.create('Ext.data.Store', {
		fields: [{
			name:'DATA_VALUE'
		},{
			name:'DATA_NAME'
		}],
		proxy:{
			type:'ajax',
			url:'<?PHP echo PATHAPP ?>/combobox/combobox_nospl.php', 
			reader: {
				type: 'json',
				root: 'data', 
				totalProperty:'total'   
			},
		}
	});
	var comboDept = Ext.create('Ext.data.Store', {
		fields: [{
			name:'DATA_VALUE'
		},{
			name:'DATA_NAME'
		}],
		proxy:{
			type:'ajax',
			url:'<?PHP echo PATHAPP ?>/combobox/combobox_dept.php', 
			reader: {
				type: 'json',
				root: 'data', 
				totalProperty:'total'   
			},
		}
	});
	
	var comboTipeLembur = Ext.create('Ext.data.Store', {
		fields: ['DATA_NAME', 'DATA_VALUE'],
		data : [
			{"DATA_NAME":"All", "DATA_VALUE":"All"},
			{"DATA_NAME":"OT Hidup", "DATA_VALUE":"OT HIDUP"},
			{"DATA_NAME":"OT Mati", "DATA_VALUE":"OT MATI"}
		]
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
			name:'DATA_DEFAULT'
		},{
			name:'DATA_SATUAN'
		},{
			name:'DATA_STATUS'
		},{
			name:'DATA_OLEH'
		},{
			name:'DATA_TGL'
		},{
			name:'DATA_DEFAULT'
		},{
			name:'DATA_SATUAN'
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
	var sm = Ext.create('Ext.selection.CheckboxModel', {
        checkOnly: true,
        // listeners: {
            // selectionchange: function(model, records) {
                // if (records[0] && !editor.editing) {
                    // editor.startEdit(records[0], 1);    // 2 is the number of column you want to edit
                // }
            // }
        // }
    });
    var grid_detil = Ext.create('Ext.grid.Panel', {
		id:'grid_detil_id',
        store: store_detil,
		autoScroll:true,
        columnLines: true,
        width: 850,
        height: 300,
        title: 'Data Element',
        frame: false,
		selModel:sm,
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
			dataIndex:'DATA_NAMA',
			header:'Nama Element',
			width:200,
			
		},{
			dataIndex:'DATA_KOMPONEN',
			header:'Komponen +/-',
			//align:'right',
			width:100,
			align:'center',
		},{
			dataIndex:'DATA_TYPE',
			header:'Type Element',
			width:120,
			
		},{
			dataIndex:'DATA_DEFAULT',
			header:'Default Value',
			width:100,
            field: {
				xtype:'numberfield',
				id:'tf_default',
				allowBlank: false,
				//readOnly: true,
            }
		},{
			dataIndex:'DATA_SATUAN',
			header:'Satuan',
			width:100,
            field: {
				xtype:'combobox',
				id:'cb_satuan',
				store:comboSatuan,
				displayField: 'DATA_NAME',
				valueField: 'DATA_VALUE',
                //lazyRender: true,
                listClass: 'x-combo-list-small',
				queryMode: 'local',
				editable: false,
				//enableKeyEvents : true,
				//readOnly: true,
            }
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
        plugins: [cellEditing],
		listeners:{
			 checkchange : function( CheckColumn, rowIndex, checked, eOpts ){

				 //me.getPlugin('cellplugin').startEdit(record,1);     
				 me.getPlugin('cellplugin').startEditByPosition({row: rowIndex, column: 1});        

		   }
			
			/* cellclick:function(grid,row,col){
				//alert(col);
				if (col==11) {x
					//var boo=Ext.getCmp('cb_urgent').getValue();
					var bool=grid_detil.getSelectionModel().getSelection()[0].get('DATA_URGENT');
					console.log(bool);
					if(bool){
						Ext.getCmp('tgl_keb').setMinValue(Ext.Date.add(new Date(), Ext.Date.DAY, 1));
					} else {
						Ext.getCmp('tgl_keb').setMinValue(Ext.Date.add(new Date(), Ext.Date.DAY, 3));
					}
				} else if (col==13) {
					var userID=grid_detil.getSelectionModel().getSelection()[0].get('HD_ID');
					var userRecord = GetUserRecord(userID);
					store_detil.remove(userRecord);
					//rowGrid--;
				}
			} */
		}
    });
	
	var store_group=new Ext.data.JsonStore({
		id:'store_group',
		pageSize: 100,
		//model: 'Plant',
		fields:[{
			name:'HD_ID'
		},{
			name:'DATA_NAMA'
		},{
			name:'DATA_GROUP'
		},{
			name:'DATA_COMPANY_ID'
		},{
			name:'DATA_COMPANY'
		},{
			name:'DATA_PLANT_ID'
		},{
			name:'DATA_PLANT'
		},{
			name:'DATA_GRADE_ID'
		},{
			name:'DATA_GRADE'
		},{
			name:'DATA_PERIODE'
		},{
			name:'DATA_DEFAULT'
		},{
			name:'DATA_SATUAN'
		},{
			name:'DATA_TIPE_LEMBUR'
		},{
			name:'DATA_STATUS'
		},{
			name:'DATA_OLEH'
		},{
			name:'DATA_TGL'
		},{
			name:'DATA_ID_ELEMENT'
		},{
			name:'DATA_ID_LINK'
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
			dataIndex:'DATA_ID_ELEMENT',
			header:'id element',
			width:100,
			hidden:true
		},{
			dataIndex:'DATA_ID_LINK',
			header:'id link',
			width:100,
			hidden:true
		},{
			dataIndex:'DATA_NAMA',
			header:'Nama Group',
			width:150,
			
		},{
			dataIndex:'DATA_GROUP',
			header:'Group',
			width:80,
			
		},{
			dataIndex:'DATA_COMPANY',
			header:'Company',
			//align:'right',
			width:120,
			
		},{
			dataIndex:'DATA_PLANT',
			header:'Plant',
			width:120,
			
		},{
			dataIndex:'DATA_GRADE',
			header:'Grade',
			//align:'right',
			width:120,
			
		},{
			dataIndex:'DATA_PERIODE',
			header:'Periode',
			width:120,
			
		},{
			dataIndex:'DATA_TIPE_LEMBUR',
			header:'Status',
			width:80,	
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
					var dtid=grid_group.getSelectionModel().getSelection()[0].get('HD_ID');
					store_detil.load({
						params:{
							dtid:dtid,
						},
						success:function(response){
							
						},
					});
					var hdid=grid_group.getSelectionModel().getSelection()[0].get('HD_ID');
					Ext.getCmp('tf_hdid').setValue(hdid);
					Ext.getCmp('tf_typeform').setValue('edit');
					Ext.getCmp('tf_namaGroup').setValue(grid_group.getSelectionModel().getSelection()[0].get('DATA_NAMA'));
					Ext.getCmp('cb_group').setValue(grid_group.getSelectionModel().getSelection()[0].get('DATA_GROUP'));
					comboGroup.load();
					Ext.getCmp('cb_company').setValue(grid_group.getSelectionModel().getSelection()[0].get('DATA_COMPANY_ID'));
					comboCompany.load();
					Ext.getCmp('cb_plant').setValue(grid_group.getSelectionModel().getSelection()[0].get('DATA_PLANT_ID'));
					comboPlant.load();
					Ext.getCmp('cb_grade').setValue(grid_group.getSelectionModel().getSelection()[0].get('DATA_GRADE_ID'));
					comboGrade.load();
					Ext.getCmp('cb_tipe_lembur').setValue(grid_group.getSelectionModel().getSelection()[0].get('DATA_TIPE_LEMBUR'));
					comboTipeLembur.load();
					Ext.getCmp('cb_periode').setValue(grid_group.getSelectionModel().getSelection()[0].get('DATA_PERIODE'));
					if(grid_group.getSelectionModel().getSelection()[0].get('DATA_STATUS') == 'Active'){
						Ext.getCmp('cb_status').setValue(true);
					}else{
						Ext.getCmp('cb_status').setValue(false);
					}
					Ext.Ajax.request({
						url:'<?php echo 'isi_grid.php'; ?>',
						params:{
							dtid:dtid,
						},
						method:'GET',
						success:function(response){
							Ext.Ajax.request({
								url:'<?php echo 'count_detail.php'; ?>',
								params:{
									dtid:dtid,
								},
								method:'POST',
								success:function(response){
									var json=Ext.decode(response.responseText);
									var rowGrid = json.rows;
									//console.log(rowGrid+'wq');
									for (var i=0 ; i<rowGrid ; i++){
										//console.log(i);
										grid_detil.getSelectionModel().select(i, true); 
									}
								},
								failure:function(error){
									//alertDialog('Kesalahan','Data gagal disimpan');
								}
							});
						},
						failure:function(error){
							//alertDialog('Kesalahan','Data gagal disimpan');
						}
					});
					
					//Ext.getCmp('cb_grade').setDisabled(true);
					//console.log(rowGrid+'wqa');
					if(grid_group.getSelectionModel().getSelection()[0].get('DATA_ID_LINK') != 0){
						Ext.getCmp('cb_group').setDisabled(true);
						Ext.getCmp('cb_company').setDisabled(true);
						Ext.getCmp('cb_plant').setDisabled(true);
						Ext.getCmp('cb_grade').setDisabled(true);
						Ext.getCmp('cb_periode').setDisabled(true);
						Ext.getCmp('cb_tipe_lembur').setDisabled(true);
						Ext.getCmp('cb_status').setDisabled(true);
					}else{
						Ext.getCmp('cb_group').setDisabled(false);
						Ext.getCmp('cb_company').setDisabled(false);
						Ext.getCmp('cb_plant').setDisabled(false);
						Ext.getCmp('cb_grade').setDisabled(false);
						Ext.getCmp('cb_periode').setDisabled(false);
						Ext.getCmp('cb_tipe_lembur').setDisabled(false);
						Ext.getCmp('cb_status').setDisabled(false);
					}
					// Ext.getCmp('cb_status').setValue(statusUser);
					wind.hide();
				},
			}
			
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
				fieldLabel:'Nama Group',
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
				html:'<div align="center"><font size="5"><b>Group Element</b></font></div>',
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
					columnWidth:.5,
					border:false,
					layout: 'anchor',
					defaultType: 'textfield',
					items:[{		
						xtype:'textfield',
						fieldLabel:'Nama Group Element',
						width:420,
						labelWidth:130,
						id:'tf_namaGroup',
						editable: false,
						readOnly:true,
						fieldStyle:'background:#B8B8B8 ;font-weight:bold;'
					}]
				},{
					columnWidth:.2,
					border:false,
					layout: 'anchor',
					defaultType: 'button',
					items:[{
						name: 'cari',
						text: 'Search Group',
						width:100,
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
					columnWidth:.5,
					border:false,
					layout: 'anchor',
					defaultType: 'combobox',
					items:[{		
						xtype:'combobox',
						fieldLabel:'Group',
						store: comboGroup,
						displayField: 'DATA_NAME',
						valueField: 'DATA_VALUE',
						width:400,
						labelWidth:130,
						id:'cb_group',
						value:'HO',
						editable: false,
						//enableKeyEvents : true,
						//minChars:1,
						/* listeners: {
							select:function(f,r,i){
								var org_id=r[0].data.DATA_VALUE;
								comboPlant.setProxy({
									type:'ajax',
									url:'<?PHP echo PATHAPP ?>/combobox/combobox_plant_element.php?orgid=' + org_id,
									reader: {
										type: 'json',
										root: 'data', 
										totalProperty:'total'   
									}
								});
								comboPlant.load();
							},
							
						} */
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
					columnWidth:.5,
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
						//editable: false 
						enableKeyEvents : true,
						minChars:1,
						/* listeners: {
							select:function(f,r,i){
								var org_id=r[0].data.DATA_VALUE;
								comboPlant.setProxy({
									type:'ajax',
									url:'<?PHP echo PATHAPP ?>/combobox/combobox_plant_element.php?orgid=' + org_id,
									reader: {
										type: 'json',
										root: 'data', 
										totalProperty:'total'   
									}
								});
								comboPlant.load();
							},
							
						} */
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
						fieldLabel:'Plant',
						store: comboPlant,
						displayField: 'DATA_NAME',
						valueField: 'DATA_VALUE',
						width:340,
						labelWidth:130,
						id:'cb_plant',
						value:'All',
						//editable: false 
						enableKeyEvents : true,
						minChars:1,
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
					defaultType: 'combobox',
					items:[{		
						xtype:'combobox',
						fieldLabel:'Grade',
						store: comboGrade,
						displayField: 'DATA_NAME',
						valueField: 'DATA_VALUE',
						width:550,
						labelWidth:130,
						id:'cb_grade',
						value:'All',
						emptyText : '- Pilih -',
						//editable: false 
						enableKeyEvents : true,
						minChars:1,
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
						fieldLabel:'Tipe Lembur',
						store: comboTipeLembur,
						displayField: 'DATA_NAME',
						valueField: 'DATA_VALUE',
						width:340,
						labelWidth:130,
						id:'cb_tipe_lembur',
						value:'All',
						queryMode:'local',
						editable: false,
						enableKeyEvents : true,
						minChars:1,
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
					defaultType: 'checkboxgroup',
					items:[{		
						xtype: 'checkboxgroup',
						fieldLabel:'Active',
						labelWidth:130,
						items: [
							{id: 'cb_status', name: 'cb_status', inputValue: 1, checked: true},
						]
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
						var data=sm.getSelection();
						var countGrid = 0;
						var i=0;
						var j=0;
						var arrTransID = new Array();
						var arrDefault = new Array();
						var arrSatuan = new Array();
						for(var i in data) {
							arrTransID[i]=data[i].get('HD_ID');
							arrDefault[i]=data[i].get('DATA_DEFAULT');
							arrSatuan[i]=data[i].get('DATA_SATUAN');
							countGrid++;
							console.log(arrSatuan[i]);
							if(arrSatuan[i] != null){
								console.log('masuk');
								j=j+1;
							}
							console.log(j+':j');
							console.log(countGrid);
						}
						if(Ext.getCmp('cb_grade').getValue()!=''){
							if (j==countGrid){
								if(countGrid>0){
									maskgrid = new Ext.LoadMask(Ext.getCmp('grid_detil_id'), {msg: "Harap Tunggu. . .Sedang Proses . . ."});
									maskgrid.show();
									Ext.Ajax.request({
										url:'<?php echo 'simpan_group.php'; ?>',
										timeout: 500000,
										params:{
											hdid:Ext.getCmp('tf_hdid').getValue(),
											typeform:Ext.getCmp('tf_typeform').getValue(),
											//nama:Ext.getCmp('tf_namaGroup').getValue(),
											group:Ext.getCmp('cb_group').getValue(),
											company:Ext.getCmp('cb_company').getValue(),
											plant:Ext.getCmp('cb_plant').getValue(),
											grade:Ext.getCmp('cb_grade').getValue(),
											periode:Ext.getCmp('cb_periode').getValue(),
											tipe_lembur:Ext.getCmp('cb_tipe_lembur').getValue(),
											status:Ext.getCmp('cb_status').getValue(),
											arrTransID:Ext.encode(arrTransID),
											arrDefault:Ext.encode(arrDefault),
											arrSatuan:Ext.encode(arrSatuan),
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
											} else if(json.rows == "tidakBisaDel"){
												alertDialog('Kesalahan', "Data tidak bisa di Update. Karena Data element "+json.element+" sudah ada di Link Group.");
											}else {
												alertDialog('Kesalahan', "Data gagal disimpan. ");
											} 
										},
										failure:function(error){
											maskgrid.hide();
											alertDialog('Kesalahan','Data gagal disimpan');
										}
									});
								} else {
									alertDialog('Kesalahan','Data Element belum dicentang.');
								} 
							} else {
								alertDialog('Kesalahan','Data Satuan pada grid element tidak boleh kosong.');
							}
						} else {
							alertDialog('Kesalahan','Grade tidak boleh kosong.');
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
   contentPanel.render('page');
   //comboPemohon.load();
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