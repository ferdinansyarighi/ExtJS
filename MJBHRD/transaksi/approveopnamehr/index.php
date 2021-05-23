<?php 
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
// $pos_name = "";
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
	// $pos_name = $_SESSION[APP]['pos_name'];
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
<link rel="stylesheet" type="text/css" href="extjs/ux/css/CheckHeader.css">
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
		var recordIndex = store_detil.find('DATA_ID', userID);
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

    var comboPlant = Ext.create('Ext.data.Store', {
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

	var comboManager = Ext.create('Ext.data.Store', {
        fields: [{
            name:'DATA_VALUE'
        },{
            name:'DATA_NAME'
        }],
        proxy:{
            type:'ajax',
            url:'<?PHP echo PATHAPP ?>/combobox/combobox_manager_all.php',
            reader: {
                type: 'json',
                root: 'data', 
                totalProperty:'total'   
            }
        }
    });

    var comboPeriode = Ext.create('Ext.data.Store', {
		fields: [{
			name:'DATA_VALUE'
		},{
			name:'DATA_NAME'
		}],
		proxy:{
			type:'ajax',
			url:'<?PHP echo PATHAPP ?>/combobox/combobox_periode.php', 
			reader: {
				type: 'json',
				root: 'data', 
				totalProperty:'total'   
			},
		}
	});
	
	Ext.define('Ext.ux.CheckColumn', {
		extend: 'Ext.grid.column.Column',
		alias: 'widget.checkcolumn',
		
		constructor: function() {
			this.addEvents(
				/**
				 * @event checkchange
				 * Fires when the checked state of a row changes
				 * @param {Ext.ux.CheckColumn} this
				 * @param {Number} rowIndex The row index
				 * @param {Boolean} checked True if the box is checked
				 */
				'checkchange'
			);
			this.callParent(arguments);
		},

		/**
		 * @private
		 * Process and refire events routed from the GridView's processEvent method.
		 */
		processEvent: function(type, view, cell, recordIndex, cellIndex, e) {
			if (type == 'mousedown' || (type == 'keydown' && (e.getKey() == e.ENTER || e.getKey() == e.SPACE))) {
				var record = view.panel.store.getAt(recordIndex),
					dataIndex = this.dataIndex,
					checked = !record.get(dataIndex);
					
				record.set(dataIndex, checked);
				this.fireEvent('checkchange', this, recordIndex, checked);
				// cancel selection.
				return false;
			} else {
				return this.callParent(arguments);
			}
		},

		// Note: class names are not placed on the prototype bc renderer scope
		// is not in the header.
		renderer : function(value){
			var cssPrefix = Ext.baseCSSPrefix,
				cls = [cssPrefix + 'grid-checkheader'];

			if (value) {
				cls.push(cssPrefix + 'grid-checkheader-checked');
			}
			return '<div class="' + cls.join(' ') + '">&#160;</div>';
		}
	});
	
	var store_grid_opname = new Ext.data.JsonStore({
		id: 'store_grid_opname',
		pageSize: 50,
		fields: [{
			name: 'HD_ID'
		},{
			name: 'DATA_KARYAWAN'
		},{
			name: 'DATA_KARYAWAN_ID'
		},{
			name: 'DATA_OU'
		},{
			name: 'DATA_OU_ID'
		},{
			name: 'DATA_OU_PENGGANTI'
		},{
			name: 'DATA_OU_ID_PENGGANTI'
		},{
			name: 'DATA_PLANT'
		},{
			name: 'DATA_PLANT_ID'
		},{
			name: 'DATA_PLANT_PENGGANTI'
		},{
			name: 'DATA_PLANT_ID_PENGGANTI'
		},{
			name: 'DATA_DEPT'
		},{
			name: 'DATA_DEPT_ID'
		},{
			name: 'DATA_DEPT_PENGGANTI'
		},{
			name: 'DATA_DEPT_ID_PENGGANTI'
		},{
			name: 'DATA_ATASAN_LSG'
		},{
			name: 'DATA_ATASAN_LSG_PENGGANTI'
		},{
			name: 'DATA_ATASAN_LSG_ID_PENGGANTI'
		},{
			name: 'DATA_ATASAN_TDK_LSG'
		},{
			name: 'DATA_ATASAN_TDK_LSG_PENGGANTI'
		},{
			name: 'DATA_ATASAN_TDK_LSG_ID_PENGGANTI'
		},{
			name: 'DATA_JABATAN'
		},{
			name: 'DATA_JABATAN_ID'
		},{
			name: 'DATA_JABATAN_PENGGANTI'
		},{
			name: 'DATA_JABATAN_ID_PENGGANTI'
		},{
			name: 'DATA_GRADE'
		},{
			name: 'DATA_GRADE_ID'
		},{
			name: 'DATA_GRADE_PENGGANTI'
		},{
			name: 'DATA_GRADE_ID_PENGGANTI'
		},{
			name: 'DATA_KETERANGAN'
		},{
			name: 'DATA_KETERANGAN_MGR'
		},{
			name: 'DATA_KETERANGAN_MGRHR'
		}],
		proxy: {
			type: 'ajax',
			url: 'grid.php',
			reader: {
				type: 'json',
				root: 'data',
				totalProperty: 'total'
			},
		},
		listeners: {
			load: {
				fn: function() {
					// Ext.MessageBox.hide();
					maskgrid.hide();
				}
			},
			scope: this
		}
	});
	
    var sm = Ext.create('Ext.selection.CheckboxModel', {
    	checkOnly: true,
    });
	var cellEditing = Ext.create('Ext.grid.plugin.CellEditing', {
		clicksToEdit: 1,
		pluginId: 'editing'
		//preventSelection: true,
	});
	
	var grid_opname = Ext.create('Ext.grid.Panel', {
        id: 'grid_opname',
		store: store_grid_opname,
		autoScroll:true,
        columnLines: true,
        width: 850,
        height: 300,
		selModel:sm,
		//colModel:sm,
        title: 'Detail Opname Karyawan',
        frame: false,
        columns: [{
			dataIndex:'HD_ID',
			header:'Header id',
			width:100,
			hidden:true,
		},{
			xtype:'rownumberer',
			id:'row_id',
			header:'No',
			width:40
		},{
			dataIndex:'DATA_KARYAWAN',
			header:'Nama Karyawan',
			width:150,
		},{
			dataIndex:'DATA_OU',
			header:'Organization Units',
			width:150,
			
		},{
			dataIndex:'DATA_OU_PENGGANTI',
			header:'Organization Units Pengganti',
			width:180,			
		},{
			dataIndex:'DATA_DEPT',
			header:'Department',
			width:150,
		},{
			dataIndex:'DATA_DEPT_PENGGANTI',
			header:'Department Pengganti',
			width:180,			
		},{
			dataIndex:'DATA_PLANT',
			header:'Plant',
			width:150,
		},{
			dataIndex:'DATA_PLANT_PENGGANTI',
			header:'Plant Pengganti',
			width:180,			
		},{
			dataIndex:'DATA_ATASAN_LSG',
			header:'Atasan Langsung',
			width:150,
		},{
			dataIndex:'DATA_ATASAN_LSG_PENGGANTI',
			header:'Atasan Langsung Pengganti',
			width:180,			
		},{
			dataIndex:'DATA_ATASAN_TDK_LSG',
			header:'Atasan Tidak Langsung',
			width:150,
		},{
			dataIndex:'DATA_ATASAN_TDK_LSG_PENGGANTI',
			header:'Atasan Tidak Langsung Pengganti',
			width:180,			
		},{
			dataIndex:'DATA_JABATAN',
			header:'Jabatan',
			width:150,
		},{
			dataIndex:'DATA_JABATAN_PENGGANTI',
			header:'Jabatan Pengganti',
			width:180,			
		},{
			dataIndex:'DATA_GRADE',
			header:'Grade',
			width:150,
		},{
			dataIndex:'DATA_GRADE_PENGGANTI',
			header:'Grade Pengganti',
			width:180,						
		},{
			dataIndex:'DATA_KETERANGAN',
			header:'Keterangan HR Area',
			width:150,						
		},{
			dataIndex:'DATA_KETERANGAN_MGR',
			header:'Keterangan Manager',
			width:150,			
		},{
			dataIndex:'DATA_KETERANGAN_MGRHR',
			header:'Keterangan Manager HR',
			width:150,
			field: {
				xtype:'textfield',
				id:'tf_keterangan_mgrhr',
				name:'tf_keterangan_mghrr',
            }
		}],
		bbar: Ext.create('Ext.PagingToolbar', {
			store: store_grid_opname,
			displayInfo: true,
			displayMsg: 'Displaying document {0} - {1} of {2} data',
			emptyMsg: "No data History to display",
		}),
		plugins: [cellEditing],	
		listeners: {
			beforedeselect: function(sm, record) {
				var plugin = this.getPlugin('editing');
				// In 4.2.0 there is apparently a bug that makes
				// plugin.editing always true
				return !plugin.getActiveEditor();
			}
			,beforeedit: function(editor, context) {
			   return context.colIdx !== 0;
			}
		},
    });
		 //console.log("sdasdas");
	 var contentPanel = Ext.create('Ext.panel.Panel',{
		//title:'Form Input Tanda Terima',
		//region:'center',
		bodyStyle: 'spacing: 10px;border:none',
		items:[{
			xtype:'label',
			html:'<div align="center"><font size="5"><b>Approve Opname Employee MGR</b></font></div>',
		},{
			xtype:'label',
			html:'<br><br>',
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
					id:'cb_plant',
					name: 'cb_plant',
					fieldLabel: 'Plant',
					store: comboPlant,
					width:365,
					labelWidth:140,
					minChars:3,
					displayField: 'DATA_NAME',
					valueField: 'DATA_VALUE',
					editable: true,
					readOnly: false,
					emptyText: "",
					//fieldStyle:'background:#B8B8B8 ;font-weight:bold;'
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
				xtype:'textfield',
				fieldLabel:'typeform',
				width:350,
				labelWidth:75,
				id:'tf_typeform',
				value:'tambah',
				hidden:true
			},{
				columnWidth:.6,
				border:false,
				layout: 'anchor',
				defaultType: 'combobox',
				items:[{
					name: 'cb_dept',
					fieldLabel: 'Department',
					store: comboDept,
					width:365,
					labelWidth:140,
					minChars:3,
					displayField: 'DATA_NAME',
					valueField: 'DATA_VALUE',
					id:'cb_dept',
					editable: true,
					readOnly: false,
					emptyText: "",
					//fieldStyle:'background:#B8B8B8 ;font-weight:bold;'
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
				}]
			},{
				columnWidth:.6,
				border:false,
				layout: 'anchor',
				defaultType: 'combobox',
				items:[{
					id:'cb_periode',
					name: 'cb_periode',
					fieldLabel: 'Periode',
					store: comboPeriode,
					width:365,
					labelWidth:140,
					minChars:3,
					displayField: 'DATA_NAME',
					valueField: 'DATA_VALUE',
					editable: true,
					readOnly: false,
					emptyText: "",
					//fieldStyle:'background:#B8B8B8 ;font-weight:bold;'
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
					name: 'cb_mgr',
					fieldLabel: 'Manager Employee',
					store: comboManager,
					width:365,
					labelWidth:140,
					minChars:3,
					displayField: 'DATA_NAME',
					valueField: 'DATA_NAME',
					id:'cb_mgr',
					editable: true,
					readOnly: false,
					emptyText: "",
					//fieldStyle:'background:#B8B8B8 ;font-weight:bold;'
				}]
			}]	
		},{
			xtype:'label',
			html:'&nbsp',
		},{
			layout:'column',
			border:false,
			items:[{
				columnWidth:.05,
				border:false,
				layout: 'anchor',
				defaultType: 'label',
				items:[{
					xtype:'label',
					html:'',
				}]
			},{
				columnWidth:.1,
				border:false,
				layout: 'anchor',
				defaultType: 'button',
				items:[{
					name: 'btnfilter',
					text: 'View',
					width: 80,
					handler:function(){
						
						maskgrid = new Ext.LoadMask(Ext.getCmp('grid_opname'), {msg: "Memuat . . ."});
						maskgrid.show();						
						
						store_grid_opname.setProxy({
							type:'ajax',
							timeout: 5000000,
							url:'<?PHP echo PATHAPP ?>/transaksi/approveopnamehr/grid.php?plant='+Ext.getCmp('cb_plant').getValue()+'&department='+Ext.getCmp('cb_dept').getValue()+'&periode='+Ext.getCmp('cb_periode').getValue()+'&mgr='+Ext.getCmp('cb_mgr').getValue(),
							reader: {
								type: 'json',
								root: 'data',
								totalProperty:'total'        
							}
						});
						store_grid_opname.load();
					} 
				}]
			},{
					columnWidth:.2,
					border:false,
					layout: 'anchor',
					defaultType: 'button',
					items:[{
						name: 'btnclear',
						text: 'Clear',
						width: 80,
						handler:function(){
							Ext.getCmp('cb_plant').setValue('');
							Ext.getCmp('cb_dept').setValue('');			
							Ext.getCmp('cb_mgr').setValue('');			
							Ext.getCmp('cb_periode').setValue('');
							store_grid_opname.removeAll();
						} 
					}]
				}]	
		},{
			xtype:'label',
			html:'&nbsp',
		},grid_opname,{
			xtype:'label',
			html:'&nbsp<br><br>',
		},{
			layout:'column',
			border:false,
			items:[{
				columnWidth:.05,
				border:false,
				layout: 'anchor',
				defaultType: 'label',
				items:[{
					xtype:'label',
					html:'',
				}]
			},{
				columnWidth:.1,
				border:false,
				layout: 'anchor',
				defaultType: 'button',
				items:[{
					name: 'btnapp',
					text: 'Approve',
					width: 80,
					handler:function(){
						var data=sm.getSelection();
						var countGrid = 0;
						var i=0;
						var arrHDID = new Array();
						var arrKaryawan = new Array();
						var arrKaryawanID = new Array();
						var arrOU = new Array();
						var arrDept = new Array();
						var arrPlant = new Array();
						var arrAtasanLsg = new Array();
						var arrAtasanTdkLsg = new Array();
						var arrJabatan = new Array();
						var arrGrade = new Array();
						var arrKet = new Array();
						var arrKetMgr = new Array();
						var arrKetMgrHR = new Array();
						for(var i in data) {
							arrHDID[i]=data[i].get('HD_ID');
							arrKaryawan[i]=data[i].get('DATA_KARYAWAN');
							arrKaryawanID[i]=data[i].get('DATA_KARYAWAN_ID');
							arrOU[i]=data[i].get('DATA_OU_PENGGANTI');
							arrDept[i]=data[i].get('DATA_DEPT_PENGGANTI');
							arrPlant[i]=data[i].get('DATA_PLANT_PENGGANTI');
							arrAtasanLsg[i]=data[i].get('DATA_ATASAN_LSG_PENGGANTI');
							arrAtasanTdkLsg[i]=data[i].get('DATA_ATASAN_TDK_LSG_PENGGANTI');
							arrJabatan[i]=data[i].get('DATA_JABATAN_PENGGANTI');
							arrGrade[i]=data[i].get('DATA_GRADE_PENGGANTI');
							arrKet[i]=data[i].get('DATA_KETERANGAN');
							arrKetMgr[i]=data[i].get('DATA_KETERANGAN_MGR');
							arrKetMgrHR[i]=data[i].get('DATA_KETERANGAN_MGRHR');
							countGrid++;							
						}						
						if(countGrid>0){						
							maskgrid = new Ext.LoadMask(Ext.getCmp('grid_opname'), {msg: "Memuat . . ."});
							maskgrid.show();
							Ext.Ajax.request({
								url:'<?php echo 'simpan.php'; ?>',
								timeout: 500000,
								params:{
									typeform:Ext.getCmp('tf_typeform').getValue(),
									arrHDID:Ext.encode(arrHDID),
									arrKaryawan:Ext.encode(arrKaryawan),
									arrKaryawanID:Ext.encode(arrKaryawanID),
									arrOU:Ext.encode(arrOU),
									arrDept:Ext.encode(arrDept),
									arrPlant:Ext.encode(arrPlant),
									arrAtasanLsg:Ext.encode(arrAtasanLsg),
									arrAtasanTdkLsg:Ext.encode(arrAtasanTdkLsg),
									arrJabatan:Ext.encode(arrJabatan),
									arrGrade:Ext.encode(arrGrade),
									arrKet:Ext.encode(arrKet),
									status:'Approved',
									arrKetMgr:Ext.encode(arrKetMgr),
									arrKetMgrHR:Ext.encode(arrKetMgrHR),
								},
								method:'POST',
								success:function(response){
																		 
									maskgrid.hide();
									var json=Ext.decode(response.responseText);
									if (json.rows == "sukses"){
										alertDialog('Sukses', "Data tersimpan.");									
										rowGrid = 0;
										store_grid_opname.removeAll();
										maskgrid = new Ext.LoadMask(Ext.getCmp('grid_opname'), {msg: "Memuat . . ."});
										maskgrid.show();						
										store_grid_opname.setProxy({
											type:'ajax',
											timeout: 5000000,
											url:'<?PHP echo PATHAPP ?>/transaksi/approveopnamehr/grid.php?plant='+Ext.getCmp('cb_plant').getValue()+'&department='+Ext.getCmp('cb_dept').getValue()+'&periode='+Ext.getCmp('cb_periode').getValue()+'&mgr='+Ext.getCmp('cb_mgr').getValue(),
											reader: {
												type: 'json',
												root: 'data',
												totalProperty:'total'        
											}
										});
										store_grid_opname.load();
										alertDialog('Sukses', "Data tersimpan.");
										// Ext.MessageBox.hide();
									} else {
										alertDialog('Kesalahan', "Data gagal disimpan.");
									} 
								},
								failure:function(error){
									maskgrid.hide();
									alertDialog('Kesalahan','Data gagal disimpan');
								}
							});
						} else {
							alertDialog('Kesalahan','Belum ada data yang dipilih untuk di approve.');
						} 
					} 
				}]
			},{
				columnWidth:.1,
				border:false,
				layout: 'anchor',
				defaultType: 'button',
				items:[{
					name: 'btndis',
					text: 'Disapprove',
					width: 80,
					handler:function(){
						var data=sm.getSelection();
						var countGrid = 0;
						var i=0;
						var validasi=0;
						var arrHDID = new Array();
						var arrKaryawan = new Array();
						var arrKaryawanID = new Array();
						var arrOU = new Array();
						var arrDept = new Array();
						var arrPlant = new Array();
						var arrAtasanLsg = new Array();
						var arrAtasanTdkLsg = new Array();
						var arrJabatan = new Array();
						var arrGrade = new Array();
						var arrKet = new Array();
						var arrKetMgr = new Array();
						var arrKetMgrHR = new Array();
						for(var i in data) {
							arrHDID[i]=data[i].get('HD_ID');
							arrKaryawan[i]=data[i].get('DATA_KARYAWAN');
							arrKaryawanID[i]=data[i].get('DATA_KARYAWAN_ID');
							arrOU[i]=data[i].get('DATA_OU_PENGGANTI');
							arrDept[i]=data[i].get('DATA_DEPT_PENGGANTI');
							arrPlant[i]=data[i].get('DATA_PLANT_PENGGANTI');
							arrAtasanLsg[i]=data[i].get('DATA_ATASAN_LSG_PENGGANTI');
							arrAtasanTdkLsg[i]=data[i].get('DATA_ATASAN_TDK_LSG_PENGGANTI');
							arrJabatan[i]=data[i].get('DATA_JABATAN_PENGGANTI');
							arrGrade[i]=data[i].get('DATA_GRADE_PENGGANTI');
							arrKet[i]=data[i].get('DATA_KETERANGAN');
							arrKetMgr[i]=data[i].get('DATA_KETERANGAN_MGR');
							arrKetMgrHR[i]=data[i].get('DATA_KETERANGAN_MGRHR');
							var ket_hr = data[i].get('DATA_KETERANGAN_MGRHR');
							if(ket_hr ==null || ket_hr == ''){
								validasi++;
							}							
							countGrid++;							
						}
						// console.log(validasi);
						if (validasi == 0) {												
							if(countGrid>0){
								// Ext.MessageBox.wait('Sedang proses ...');
								maskgrid = new Ext.LoadMask(Ext.getCmp('grid_opname'), {msg: "Harap Tunggu. . .Sedang Proses . . ."});
								maskgrid.show();
								Ext.Ajax.request({
									url:'<?php echo 'simpan.php'; ?>',
									timeout: 500000,
									params:{
										typeform:Ext.getCmp('tf_typeform').getValue(),
										arrHDID:Ext.encode(arrHDID),
										arrKaryawan:Ext.encode(arrKaryawan),
										arrKaryawanID:Ext.encode(arrKaryawanID),
										arrOU:Ext.encode(arrOU),
										arrDept:Ext.encode(arrDept),
										arrPlant:Ext.encode(arrPlant),
										arrAtasanLsg:Ext.encode(arrAtasanLsg),
										arrAtasanTdkLsg:Ext.encode(arrAtasanTdkLsg),
										arrJabatan:Ext.encode(arrJabatan),
										arrGrade:Ext.encode(arrGrade),
										arrKet:Ext.encode(arrKet),
										status:'Disapproved',
										arrKetMgr:Ext.encode(arrKetMgr),
										arrKetMgrHR:Ext.encode(arrKetMgrHR),
									},
									method:'POST',
									success:function(response){	
										maskgrid.hide();
										var json=Ext.decode(response.responseText);
										if (json.rows == "sukses"){
											alertDialog('Sukses', "Data tersimpan.");
											rowGrid = 0;
											store_grid_opname.removeAll();
											maskgrid = new Ext.LoadMask(Ext.getCmp('grid_opname'), {msg: "Memuat . . ."});
											maskgrid.show();
											store_grid_opname.setProxy({
												type:'ajax',
												timeout: 5000000,
												url:'<?PHP echo PATHAPP ?>/transaksi/approveopnamehr/grid.php?plant='+Ext.getCmp('cb_plant').getValue()+'&department='+Ext.getCmp('cb_dept').getValue()+'&periode='+Ext.getCmp('cb_periode').getValue()+'&mgr='+Ext.getCmp('cb_mgr').getValue(),
												reader: {
													type: 'json',
													root: 'data',
													totalProperty:'total'        
												}
											});
											store_grid_opname.load();
											alertDialog('Sukses', "Data tersimpan.");
										} else {
											alertDialog('Kesalahan', "Data gagal disimpan.");
										} 
									},
									failure:function(error){
										maskgrid.hide();
										alertDialog('Kesalahan','Data gagal disimpan');
									}
								});
							} else {
								alertDialog('Kesalahan','Belum ada data yang dipilih untuk di disapprove.');
							}
						} else {
							alertDialog('Kesalahan','Apabila ingin disapprove kolom Keterangan Manager HR wajib diisi.');
						} 
					} 
				}]
			},{
				columnWidth:.2,
				border:false,
				layout: 'anchor',
				defaultType: 'button',
				items:[{
					name: 'btnexit',
					text: 'Exit',
					width: 80,
					handler:function(){
						document.location.href = "<?PHP echo PATHAPP ?>/main/indexUtama.php";
					} 
				}]
			}]	
		}],
	});	
	
	//store_grid_opname.load();
	contentPanel.render('page');
    }
</script>
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