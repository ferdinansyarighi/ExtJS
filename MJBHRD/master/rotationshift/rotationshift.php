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
	var firstDay = new Date(currentTime.getFullYear(), currentTime.getMonth(), 1);
	var month = currentTime.getMonth() + 1;
	var day = currentTime.getDate();
	var year = currentTime.getFullYear();
	var tanggalskr = day + "-" + month + "-" + year;
	var rowGrid = 1;
	Array.prototype.max = function() {
	  return Math.max.apply(null, this);
	};

	Array.prototype.min = function() {
	  return Math.min.apply(null, this);
	};
	function GetUserRecord(userID) {
		var recordIndex = store_detil.find('DATA_NO', userID);
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
	
	// function dateRangeOverlaps(a_start, a_end, b_start, b_end) {
		// if (a_start <= b_start && b_start <= a_end) return true; // b starts in a
		// if (a_start <= b_end   && b_end   <= a_end) return true; // b ends in a
		// if (b_start <  a_start && a_end   <  b_end) return true; // a in b
		// return false;
	// }
	// function dateRangeOverlaps(startDateA, endDateA, startDateB, endDateB) {

		// if ((endDateA < startDateB) || (startDateA > endDateB)) {
			// return null
		// }

		// var obj = {};
		// obj.startDate = startDateA <= startDateB ? startDateB : startDateA;
		// obj.endDate = endDateA <= endDateB ? endDateA : endDateB;

		// return obj;
	// }
	// function multipleDateRangeOverlaps() {
		// var i, j;
		// if (arguments.length % 2 !== 0)
			// throw new TypeError('Arguments length must be a multiple of 2');
		// for (i = 0; i < arguments.length - 2; i += 2) {
			// for (j = i + 2; j < arguments.length; j += 2) {
				// if (
					// dateRangeOverlaps(
						// arguments[i], arguments[i+1],
						// arguments[j], arguments[j+1]
					// )
				// ) return true;
			// }
		// }
		// return false;
	// }
	/* function formatDateNum(value){
        var val = value ? Ext.Date.dateFormat(value, 'Y-m-d') : '';
		var res = val.replace("-", "");
        return res;
    } */
	Ext.clearForm=function(){
		Ext.getCmp('tf_hdid').setValue('')
		Ext.getCmp('tf_typeform').setValue('tambah')
		Ext.getCmp('cb_company').setValue('')
		Ext.getCmp('cb_nama').setValue('')
		Ext.getCmp('tf_dept').setValue('')
		Ext.getCmp('tf_pos').setValue('')
		Ext.getCmp('tf_grade').setValue('')
		Ext.getCmp('tf_location').setValue('')
		store_detil.removeAll()
		Ext.getCmp('tf_periode1').setValue(firstDay)
		Ext.getCmp('tf_periode2').setValue(currentTime)
		store_group.removeAll()
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
			url:'<?PHP echo PATHAPP ?>/combobox/combobox_rotationshift.php', 
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
		fields:[{
			name:'DATA_NO'
		},{
			name:'DATA_ID'
		},{
			name:'DATA_SHIFT_ID'
		},{
			name:'DATA_NAMA'
		},{
			name:'DATA_DATE_FROM',type:'date'
		},{
			name:'DATA_DATE_TO',type:'date'
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
        width: 410,
        height: 300,
        title: 'Shift Karyawan',
        frame: false,
		//selModel:sm,
        loadMask: true,
        columns:[{
			xtype:'rownumberer',
			id:'row_id',
			header:'No',
			align:'center',
			width:35
		},{
			dataIndex:'DATA_NO',
			header:'NOMOR',
			width:100,
			hidden:true
		},{
			dataIndex:'DATA_CEK',
			header:'Cek',
			width:100,
			hidden:true
		},{
			dataIndex:'DATA_ID',
			header:'id',
			width:100,
			hidden:true
		},{
			dataIndex:'DATA_SHIFT_ID',
			header:'Shift id',
			width:100,
			hidden:true
		},{
			dataIndex:'DATA_NAMA',
			header:'Shift',
			width:137,
			//align:'center',
            field: {
                xtype: 'combobox',
				id:'cb_shift',
				store:comboShift,
				displayField: 'DATA_NAME',
				valueField: 'DATA_NAME',
				enableKeyEvents : true,
				minChars:1,
				listeners: {
					select:function(f,r,i){
						var id_kode=r[0].data.DATA_VALUE;
						var sme= grid_detil.getSelectionModel().getSelection();							
						var sma=grid_detil.store.indexOf(sme[0]);
						//console.log(id_kode);
						var sm5=grid_detil.getStore().getAt(sma).set('DATA_SHIFT_ID', id_kode);
					},
				}
            }
		},{
			dataIndex:'DATA_DATE_FROM',
			header:'Start Date',
			renderer: formatDate,
			width:100,
			align:'center',
            field: {
                xtype: 'datefield',
				id: 'tf_startshift',
                format: 'Y-m-d',
                //minValue: Ext.Date.add(new Date(), Ext.Date.DAY, 0),
            }
		},{
			dataIndex:'DATA_DATE_TO',
			header:'End Date',
			renderer: formatDate,
			width:100,
			align:'center',
            field: {
                xtype: 'datefield',
				id:'tf_endshift',
                format: 'Y-m-d',
				//editable:false,
                //minValue: Ext.Date.add(new Date(), Ext.Date.DAY, 0),
            }
		},{
			dataIndex:'DATA_ACTION',
			header:'Hapus',
			width:40,
			hideable:false,
			sortable:false,
			align:'center',
			renderer:function(){
				return "<img style='cursor:pointer;'src='../../media/css/images/main-icon/toolbar/hapus1.png' />"; 
			}
		}],
        selModel: {
            selType: 'cellmodel'
        },
        plugins: [cellEditing],
        tbar: [{
            text: 'Tambah',
			style: {
				borderColor: 'grey',
				borderStyle: 'solid'
			},
			cls:'button-popup',
            handler : function(){
                // Create a record instance through the ModelManager
                store_detil.add({'DATA_NO': rowGrid, 'DATA_NAMA': '', 'DATA_DATE_FROM': currentTime, 'DATA_CEK' : 0});
				rowGrid++;
            }
        }],
		listeners:{
			cellclick:function(grid,row,col){
				// console.log(col);
				if (col==8) {
					var bool=grid_detil.getSelectionModel().getSelection()[0].get('DATA_CEK');
					if(bool==0){
						var userID=grid_detil.getSelectionModel().getSelection()[0].get('DATA_NO');
						//console.log(userID);
						var userRecord = GetUserRecord(userID);
						store_detil.remove(userRecord);
					}else{
						alert('Data tidak dapat dihapus.');
					}
				}
				if(col==5){
					var bool=grid_detil.getSelectionModel().getSelection()[0].get('DATA_CEK');
					if(bool==0){
						Ext.getCmp('cb_shift').setDisabled(false);
					}else{
						Ext.getCmp('cb_shift').setDisabled(true);
					}
				}
				if(col==6){
					var bool=grid_detil.getSelectionModel().getSelection()[0].get('DATA_CEK');
					if(bool==0){
						Ext.getCmp('tf_startshift').setDisabled(false);
					}else{
						Ext.getCmp('tf_startshift').setDisabled(true);
					}
				}
				if(col==7){
					var bool=grid_detil.getSelectionModel().getSelection()[0].get('DATA_CEK');
					if(bool==0){;
						Ext.getCmp('tf_endshift').setDisabled(false);
					}else{
						Ext.getCmp('tf_endshift').setDisabled(true);
					}
				}
			}
		} 
    });
	var store_group=new Ext.data.JsonStore({
		id:'store_group',
		pageSize: 100,
		//model: 'Plant',
		fields:[{
			name:'DATA_DATE'
		},{
			name:'DATA_DAY'
		},{
			name:'DATA_SHIFT'
		},{
			name:'DATA_WS'
		},{
			name:'DATA_WH'
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
		title: 'Detail Shift Karyawan',
		width:410,
		height:480,
		x:10,
		y:30,
		autoScroll:true,
		columns:[{
			xtype:'rownumberer',
			id:'row_id',
			header:'No',
			width:30
		},{
			dataIndex:'DATA_DATE',
			header:'Date',
			//align:'right',
			width:80,
			
		},{
			dataIndex:'DATA_DAY',
			header:'Day',
			width:80,
			
		},{
			dataIndex:'DATA_SHIFT',
			header:'Shift',
			width:100,
			
		},{
			dataIndex:'DATA_WS',
			header:'Work Schedule',
			//align:'right',
			width:100,
			
		},{
			dataIndex:'DATA_WH',
			header:'Work Hour',
			width:80,
			
		}],
	});
	var contentPanel = Ext.create('Ext.panel.Panel',{
			//title:'Form Input Tanda Terima',
			//region:'center',
			bodyStyle: 'spacing: 10px;border:none',
			items:[{
				xtype:'label',
				html:'<div align="center"><font size="5"><b>Rotation Shift Karyawan</b></font></div>',
			},{
				xtype:'label',
				html:'<br><br>',
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
					columnWidth:.5,
					layout:'column',
					border:false,
					items:[{
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
											Ext.clearForm();
											var assignment_id=r[0].data.DATA_VALUE;
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
											Ext.getCmp('cb_nama').setValue(assignment_id);
											Ext.getCmp('cb_company').setValue(company_kar);
											Ext.getCmp('tf_location').setValue(grade_kar);
											Ext.getCmp('tf_grade').setValue(location_kar);
											
											
											store_detil.load({
												params:{
													assignment_id:assignment_id,
												}
											});
											
											Ext.Ajax.request({
												url:'<?php echo 'count_detail.php'; ?>',
												params:{
													assignment_id:assignment_id,
												},
												method:'POST',
												success:function(response){
													var json=Ext.decode(response.responseText);
													rowGrid = json.rows;
													rowGrid++;
												},
												failure:function(error){
													//alertDialog('Kesalahan','Data gagal disimpan');
												}
											});
										},
										
									}
								}]
							},
							
							
							// {
								// columnWidth:.2,
								// border:false,
								// layout: 'anchor',
								// defaultType: 'button',
								// items:[{
									// name: 'cari',
									// text: 'Search',
									// width:80,
									// handler:function(){
										// Ext.clearForm();
										// store_group.load();
										// wind.show(); 
									// }
								// }]
							// }
							]	
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
								border:false,
								layout: 'anchor',
								defaultType: 'textfield',
								items:[{		
									xtype:'textfield',
									fieldLabel:'Company',
									width:400,
									labelWidth:130,
									id:'cb_company',
									//value:'All',
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
								border:false,
								layout: 'anchor',
								defaultType: 'textfield',
								items:[{		
									xtype:'textfield',
									fieldLabel:'Grade',
									width:340,
									labelWidth:130,
									id:'tf_grade',
									readOnly: 'true',
									fieldStyle:'background:#B8B8B8 ;font-weight:bold;',
								}]
							}]	
						},{
							layout:'column',
							border:false,
							//zIndex:-1,
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
								border:false,
								layout: 'anchor',
								defaultType: 'textfield',
								items:[{		
									xtype:'textfield',
									fieldLabel:'Location',
									width:340,
									labelWidth:130,
									id:'tf_location',
									readOnly: 'true',
									fieldStyle:'background:#B8B8B8 ;font-weight:bold;',
								}]
							}]	
						},{
							layout:'column',
							border:false,
							items:[,{
								columnWidth:1,
								border:false,
								layout: 'anchor',
								defaultType: 'label',
								items:[{
									xtype:'label',
									html:'&nbsp',
								}]
							},{
								columnWidth:1,
								border:false,
								layout: 'anchor',
								defaultType: 'textfield',
								items:[{		
									xtype:'label',
									html:'&nbsp',
								}]
							}]	
						}, grid_detil ,{
							xtype:'label',
							html:'&nbsp',
						},{
							xtype:'panel',
							bodyStyle: 'padding-left: 120px;padding-top: 20px;padding-bottom: 50px;border:none',
							items:[{
								xtype:'button',
								text:'Save',
								width:80,
								handler:function(){
									var countGrid = 0;
									var z = 0;
									var kumpulanID = "";
									var i=0;
									var j=0;
									var k=0;
									var arrID = new Array();
									var arrShiftID = new Array();
									var arrDateFrom = new Array();
									var arrDateFromNum = new Array();
									var arrDateTo = new Array();
									var arrDateToNum = new Array();
									store_detil.each(
										function(record)  {
											var id = record.get('DATA_ID');
											if(id==''){
												id=0;
											}
											arrID[i]=record.get('DATA_ID');
											arrShiftID[i]=record.get('DATA_SHIFT_ID');
											arrDateFrom[i]=record.get('DATA_DATE_FROM');
											if(z == 0){
												kumpulanID = kumpulanID + id;
											} else {
												kumpulanID = kumpulanID + ", " + id;
											}
											z++;
											arrDateTo[i]=record.get('DATA_DATE_TO');
											arrDateFromNum[i]=record.get('DATA_DATE_FROM');
											if(record.get('DATA_DATE_TO') != null){
												arrDateToNum[i]=record.get('DATA_DATE_TO');
											}else{
												arrDateToNum[i]='32503654800000';
											}
											countGrid++;
											//console.log(record.get('DATA_DATE_TO'));
											if(arrShiftID[i]!=''){
												j=j+1;
											}
											i=i+1;
										}
									);
									//console.log(arrDateFromNum.max());
									// console.log(arrDateFrom.min());
									// console.log(arrDateToNum.max());
									//console.log(arrDateToNum.min());
									//console.log(dateRangeOverlaps(arrDateFromNum.min(), arrDateFromNum.max(), arrDateToNum.min(), arrDateToNum.max()));
									if(Ext.getCmp('cb_nama').getValue() != null){
										if (j==countGrid){
											maskgrid = new Ext.LoadMask(Ext.getCmp('grid_detil_id'), {msg: "Harap Tunggu. . .Sedang Proses . . ."});
											maskgrid.show();
											Ext.Ajax.request({
												url:'<?php echo 'simpan_shift.php'; ?>',
												timeout: 500000,
												params:{
													//hdid:Ext.getCmp('tf_hdid').getValue(),
													typeform:Ext.getCmp('tf_typeform').getValue(),
													kumpulanID:kumpulanID,
													nama:Ext.getCmp('cb_nama').getValue(),
													arrID:Ext.encode(arrID),
													arrShiftID:Ext.encode(arrShiftID),
													arrDateFrom:Ext.encode(arrDateFrom),
													arrDateTo:Ext.encode(arrDateTo),
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
														alertDialog('Kesalahan', json.rows);
													} 
												},
												failure:function(error){
													maskgrid.hide();
													alertDialog('Kesalahan','Data gagal disimpan');
												}
											});
										} else {
											alertDialog('Kesalahan','Shift tidak boleh kosong.');
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
								text:'Clear',
								width:80,
								handler:function(){
									Ext.clearForm();
								}
							}]
						}]
				},{
					columnWidth:.5,
					layout:'absolute',
					border:false,
					items:[{
						//columnWidth:1,
						layout:'column',
						border:false,
						x:0,
						y:0,
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
							columnWidth:.38,
							border:false,
							layout: 'anchor',
							defaultType: 'datefield',
							items:[{		
								xtype:'datefield',
								fieldLabel:'Periode',
								width:150,
								labelWidth:50,
								id:'tf_periode1',
								//value:'All',
								//readOnly: 'true',
								//fieldStyle:'background:#B8B8B8 ;font-weight:bold;',
							}]
						},{
							columnWidth:.33,
							border:false,
							layout: 'anchor',
							defaultType: 'datefield',
							items:[{		
								xtype:'datefield',
								fieldLabel:'s/d',
								width:130,
								labelWidth:30,
								id:'tf_periode2',
								//value:'All',
								//readOnly: 'true',
								//fieldStyle:'background:#B8B8B8 ;font-weight:bold;',
							}]
						},{
							columnWidth:.13,
							border:false,
							layout: 'anchor',
							defaultType: 'button',
							items:[{
								name: 'cari',
								text: 'View',
								width:50,
								handler:function(){
									if(Ext.getCmp('cb_nama').getValue() != null && Ext.getCmp('cb_nama').getValue() != ''){
										var assignment_id = Ext.getCmp('cb_nama').getValue();
										var periode1 = Ext.getCmp('tf_periode1').getValue();
										var periode2 = Ext.getCmp('tf_periode2').getValue();
										store_group.load({
											params:{
												assignment_id:assignment_id,
												periode1:periode1,
												periode2:periode2,
											}
										});
									}else{
										alertDialog('Kesalahan','Nama Karyawan belum diisi.');
									}
								}
							}]
						},]	
					},grid_group]
				}]
			}],//end
		});	
		Ext.getCmp('tf_periode1').setValue(firstDay);
		Ext.getCmp('tf_periode2').setValue(currentTime);
	//Ext.getCmp('cb_shift').setValue('');
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