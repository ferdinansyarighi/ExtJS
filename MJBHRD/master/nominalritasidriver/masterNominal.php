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
	var rowGridRitasi = 0;
	var rowGridJarak = 0;
	var tipePlant1 = Ext.create('Ext.data.Store', {
		fields: ['DATA_NAME', 'DATA_VALUE'],
		data : [
			{"DATA_NAME":"BP", "DATA_VALUE":"BP"},
			{"DATA_NAME":"SP", "DATA_VALUE":"SP"}
		]
	});
	function GetUserRecord(userID) {
		var recordIndex = store_detail_ritasi.find('HD_ID', userID);
		if (recordIndex > -1) {
			return store_detail_ritasi.getAt(recordIndex);
		} else {
			return null;
		}
	}
	function GetUserRecordJarak(userID) {
		var recordIndex = store_detail_jarak.find('HD_ID', userID);
		if (recordIndex > -1) {
			return store_detail_jarak.getAt(recordIndex);
		} else {
			return null;
		}
	}
	var tipePlant = Ext.create('Ext.data.Store', {
		fields: [{
			name:'DATA_VALUE'
		},{
			name:'DATA_NAME'
		}],
		proxy:{
			type:'ajax',
			url:'<?PHP echo PATHAPP ?>/combobox/combobox_tipe_plant.php', 
			reader: {
				type: 'json',
				root: 'data', 
				totalProperty:'total'   
			},
		}
	});
	Ext.clearForm=function(){
		Ext.getCmp('hd_id').setValue('')
		Ext.getCmp('tf_formtype').setValue('tambah')
		Ext.getCmp('cb_tipe').setValue('')
		Ext.getCmp('tf_pa').setValue('')
		// Ext.getCmp('tf_ritasike').setValue(0)
		// Ext.getCmp('tf_nominal').setValue(0)
		// Ext.getCmp('tf_jarak_from').setValue(0)
		// Ext.getCmp('tf_jarak_to').setValue(0)
		Ext.getCmp('tf_tgl_awal').setValue(currentTime)
		Ext.getCmp('tf_tgl_akhir').setValue('')
		Ext.getCmp('tf_ritasike').show()
		Ext.getCmp('tfJarak').hide()
		Ext.getCmp('gridRitasi').show()
		Ext.getCmp('gridJarak').hide()
		store_detail_jarak.removeAll()
		store_detail_ritasi.removeAll()
		grid_store.load()
		Ext.clearFormDet()
	};
	Ext.clearFormDet=function(){
		Ext.getCmp('tf_ritasike').setValue(0)
		Ext.getCmp('tf_nominal').setValue(0)
		Ext.getCmp('tf_jarak_from').setValue(0)
		Ext.getCmp('tf_jarak_to').setValue(0)
	};
	var grid_store=new Ext.data.JsonStore({
		id:'grid_store_id',
		pageSize: 50,
		fields:[{
			name:'HD_ID'
		},{
			name:'DATA_TIPE'
		},{
			name:'DATA_PLANT_AREA'
		},{
			name:'DATA_START_DATE'
		},{
			name:'DATA_END_DATE'
		},{
			name:'DATA_UPDATE_BY'
		},{
			name:'DATA_UPDATE_DATE'
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
			dataIndex:'DATA_JARAK_FROM',
			header:'Jarak From',
			width:100,
			hidden:true
		},{
			dataIndex:'DATA_JARAK_TO',
			header:'Jarak To',
			width:100,
			hidden:true
		},{
			dataIndex:'DATA_TIPE',
			header:'Tipe Plant',
			width:125,
			
		},{
			dataIndex:'DATA_PLANT_AREA',
			header:'Plant Area',
			width:200,
			
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
			width:200,
			
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
					Ext.getCmp('hd_id').setValue(hdid);
					Ext.getCmp('tf_formtype').setValue('edit');
					Ext.getCmp('cb_tipe').setValue(grid_view.getSelectionModel().getSelection()[0].get('DATA_TIPE'));
					if(Ext.getCmp('cb_tipe').getValue() == 'BP'){
						Ext.getCmp('tf_ritasike').show();
						Ext.getCmp('tfJarak').hide();
						Ext.getCmp('gridRitasi').show();
						Ext.getCmp('gridJarak').hide();
						store_detail_ritasi.load({
							params:{
								headerid:hdid,
							}
						});
					} else {
						Ext.getCmp('tf_ritasike').hide();
						Ext.getCmp('tfJarak').show();
						Ext.getCmp('gridRitasi').hide();
						Ext.getCmp('gridJarak').show();
						store_detail_jarak.load({
							params:{
								headerid:hdid,
							}
						});
					}
					Ext.getCmp('tf_pa').setValue(grid_view.getSelectionModel().getSelection()[0].get('DATA_PLANT_AREA'));
					Ext.getCmp('tf_tgl_awal').setValue(grid_view.getSelectionModel().getSelection()[0].get('DATA_START_DATE'));
					Ext.getCmp('tf_tgl_akhir').setValue(grid_view.getSelectionModel().getSelection()[0].get('DATA_END_DATE'));
					wind.show();
				}
			}
		}
	});
	var store_detail_ritasi=new Ext.data.JsonStore({
		id:'store_detail_ritasi_id',
		pageSize: 50,
		fields:[{
			name:'HD_ID'
		},{
			name:'DATA_RITASI_KE'
		},{
			name:'DATA_NOMINAL'
		}],
		 proxy:{
			type:'ajax',
			url:'isi_grid_detail.php', 
			reader: {
				type: 'json',
				root: 'data',
				totalProperty:'total'        
			},
		}
	});
	var sumidtxtritasi='';
	var grid_detail_ritasi=Ext.create('Ext.grid.Panel',{
		id:'grid_detail_ritasi_id',
		region:'center',
		store:store_detail_ritasi,
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
			dataIndex:'DATA_RITASI_KE',
			header:'Ritasi Ke-',
			width:80,
		},{
			dataIndex:'DATA_NOMINAL',
			header:'Nominal',
			width:150,
			align:'right',
		},{
			dataIndex:'DATA_ACTION',
			header:'Hapus',
			width:50,
			hideable:false,
			sortable:false,
			align:'center',
			renderer:function(){
				return "<img style='cursor:pointer;'src='../../media/css/images/main-icon/toolbar/hapus1.png' />"; 
			}
		}],
		listeners:{
			cellclick:function(grid,row,col){
				// alert(col);
				if (col==3) {
					var userID=grid_detail_ritasi.getSelectionModel().getSelection()[0].get('HD_ID');
					var userRecord = GetUserRecord(userID);
					store_detail_ritasi.remove(userRecord);
				}
			}
		}
	});
	var store_detail_jarak=new Ext.data.JsonStore({
		id:'store_detail_jarak_id',
		pageSize: 50,
		fields:[{
			name:'HD_ID'
		},{
			name:'DATA_JARAK_AWAL'
		},{
			name:'DATA_JARAK_AKHIR'
		},{
			name:'DATA_NOMINAL'
		}],
		 proxy:{
			type:'ajax',
			url:'isi_grid_detail.php', 
			reader: {
				type: 'json',
				root: 'data',
				totalProperty:'total'        
			},
		}
	});
	var sumidtxtjarak='';
	var grid_detail_jarak=Ext.create('Ext.grid.Panel',{
		id:'grid_detail_jarak_id',
		region:'center',
		store:store_detail_jarak,
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
			dataIndex:'DATA_JARAK_AWAL',
			header:'Jarak Awal',
			width:75,
			align:'right',
		},{
			dataIndex:'DATA_JARAK_AKHIR',
			header:'Jarak Akhir',
			width:75,
			align:'right',
		},{
			dataIndex:'DATA_NOMINAL',
			header:'Nominal',
			width:100,
			align:'right',
		},{
			dataIndex:'DATA_ACTION',
			header:'Hapus',
			width:50,
			hideable:false,
			sortable:false,
			align:'center',
			renderer:function(){
				return "<img style='cursor:pointer;'src='../../media/css/images/main-icon/toolbar/hapus1.png' />"; 
			}
		}],
		listeners:{
			cellclick:function(grid,row,col){
				// alert(col);
				if (col==4) {
					var userID=grid_detail_jarak.getSelectionModel().getSelection()[0].get('HD_ID');
					var userRecord = GetUserRecordJarak(userID);
					store_detail_jarak.remove(userRecord);
				}
			}
		}
	});
	var wind=Ext.create('Ext.Window', {
		title: 'Form Input Nominal Ritasi Driver',
		width: 500,
		height: 600,							
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
				xtype:'label',
				html:'<div align="left"><font size="2"><b>Data Header:</b></font></div>',
			},{
				xtype:'label',
				html:'&nbsp',
			},{		
				xtype:'combobox',
				fieldLabel:'Tipe Plant',
				store: tipePlant,
				displayField: 'DATA_NAME',
				valueField: 'DATA_VALUE',
				width:175,
				labelWidth:100,
				id:'cb_tipe',
				emptyText : '- Pilih -',
				//editable: false 
				enableKeyEvents : true,
				minChars:1,
				listeners: {
					select:function(f,r,i){
						var nama_pem=r[0].data.DATA_VALUE;
						if(nama_pem == 'BP'){
							Ext.getCmp('tf_ritasike').show();
							Ext.getCmp('tfJarak').hide();
							Ext.getCmp('gridRitasi').show();
							Ext.getCmp('gridJarak').hide();
							store_detail_jarak.removeAll();
						} else {
							Ext.getCmp('tf_ritasike').hide();
							Ext.getCmp('tfJarak').show();
							Ext.getCmp('gridRitasi').hide();
							Ext.getCmp('gridJarak').show();
							store_detail_ritasi.removeAll();
						}
					}
				}
			},{
				xtype:'textfield',
				fieldLabel:'Plant Area',
				width:450,
				labelWidth:100,
				id:'tf_pa',
				maxLength : 100,
				listeners: {
					 change: function(field,newValue,oldValue){
							field.setValue(newValue.toUpperCase());
					}
				}
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
				xtype:'panel',
				bodyStyle: 'padding-left: 100px;padding-bottom: 10px;border:none',
				items:[{
					xtype:'label',
					html:'&nbsp',
				}]
			},{
				xtype:'label',
				html:'<div align="left"><font size="2"><b>Data Detail:</b></font></div>',
			},{
				xtype:'panel',
				bodyStyle: 'padding-left: 100px;padding-bottom: 0px;border:none',
				items:[{
					xtype:'label',
					html:'&nbsp',
				}]
			},{
				xtype:'numberfield',
				fieldLabel:'Ritasi Ke-',
				width:200,
				labelWidth:100,
				id:'tf_ritasike',
				editable:false,
				value: 0,
				minValue: 0,
				maxValue: 20,
			},{
				layout:'column',
				border:false,
				id:'tfJarak',
				items:[{
					columnWidth:.45,
					border:false,
					layout: 'anchor',
					defaultType: 'numberfield',
					items:[{
						xtype:'numberfield',
						fieldLabel:'Jarak',
						width:200,
						labelWidth:100,
						id:'tf_jarak_from',
						value: 0,
						minValue: 0,
						maxValue: 10000000000,
					}]
				},{
					columnWidth:.4,
					border:false,
					layout: 'anchor',
					defaultType: 'combobox',
					items:[{
						xtype:'numberfield',
						fieldLabel:'s/d',
						width:125,
						labelWidth:25,
						id:'tf_jarak_to',
						value: 0,
						minValue: 0,
						maxValue: 10000000000,
					}]
				}]	
			},{
				xtype:'numberfield',
				fieldLabel:'Nominal',
				width:200,
				labelWidth:100,
				id:'tf_nominal',
				value: 0,
				minValue: 0,
				maxValue: 10000000000,
			},{
				xtype:'panel',
				bodyStyle: 'padding-left: 105px;border:none',
				items:[{xtype:'button',
					text:'Tambah',
					width:70,
					handler:function(){
						if (Ext.getCmp('cb_tipe').getValue()!='' && Ext.getCmp('tf_pa').getValue()!=''){
							if(Ext.getCmp('cb_tipe').getValue()=='BP'){
								if(Ext.getCmp('tf_nominal').getValue()!=null && Ext.getCmp('tf_nominal').getValue()!=0 && Ext.getCmp('tf_ritasike').getValue()!=null && Ext.getCmp('tf_ritasike').getValue()!=0){
									var isiGrid = 0;
									var jumlah = 0;
									isiGrid = store_detail_ritasi.count();
									store_detail_ritasi.each(
										function(record)  {
											if(Ext.getCmp('tf_ritasike').getValue() == record.get('DATA_RITASI_KE')){
												jumlah++;
											}
										}
									); 
									if(jumlah == 0){
										store_detail_ritasi.add({'HD_ID': rowGridRitasi
											,'DATA_RITASI_KE': Ext.getCmp('tf_ritasike').getValue()
											, 'DATA_NOMINAL': Ext.getCmp('tf_nominal').getValue()});
										rowGridRitasi++;
										Ext.clearFormDet();
									} else {
										alertDialog('Kesalahan','Ritasi sudah pernah ditambah.');
									} 
								}
								else {
									alertDialog('Kesalahan','Ritasi atau Nominal belum diisi.');
								}
							} else {
								if(Ext.getCmp('tf_nominal').getValue()!=null && Ext.getCmp('tf_nominal').getValue()!=0 && Ext.getCmp('tf_jarak_from').getValue()!=null && Ext.getCmp('tf_jarak_to').getValue()!=null && Ext.getCmp('tf_jarak_to').getValue()!=0){
									if(Ext.getCmp('tf_jarak_to').getValue() >= Ext.getCmp('tf_jarak_from').getValue()){
										var isiGrid = 0;
										var jumlah = 0;
										isiGrid = store_detail_jarak.count();
										store_detail_jarak.each(
											function(record)  {
												if((Ext.getCmp('tf_jarak_from').getValue() >= record.get('DATA_JARAK_AWAL') && Ext.getCmp('tf_jarak_from').getValue() <= record.get('DATA_JARAK_AKHIR')) || (Ext.getCmp('tf_jarak_to').getValue() >= record.get('DATA_JARAK_AWAL') && Ext.getCmp('tf_jarak_to').getValue() <= record.get('DATA_JARAK_AKHIR')) || (Ext.getCmp('tf_jarak_from').getValue() <= record.get('DATA_JARAK_AWAL') && Ext.getCmp('tf_jarak_to').getValue() >= record.get('DATA_JARAK_AKHIR'))){
													jumlah++;
												}
											}
										); 
										if(jumlah == 0){
											store_detail_jarak.add({'HD_ID': rowGridJarak
												,'DATA_JARAK_AWAL': Ext.getCmp('tf_jarak_from').getValue()
												,'DATA_JARAK_AKHIR': Ext.getCmp('tf_jarak_to').getValue()
												, 'DATA_NOMINAL': Ext.getCmp('tf_nominal').getValue()});
											rowGridJarak++;
											Ext.clearFormDet();
										} else {
											alertDialog('Kesalahan','Jarak sudah pernah ditambah.');
										}
									} else {
										alertDialog('Kesalahan','Jarak awal tidak boleh lebih besar dari jarak akhir.');
									} 
								}
								else {
									alertDialog('Kesalahan','Jarak atau Nominal belum diisi.');
								}
							}
						} else {
							alertDialog('Kesalahan','Tipe atau Plant Area belum diisi.');
						}
					}
				}]
			},{
				xtype:'label',
				html:'&nbsp',
			},{
				xtype:'panel',
				id:'gridRitasi',
				hidden:false,
				bodyStyle: 'padding-left: 105px;border:none',
				items:[grid_detail_ritasi]
			},{
				xtype:'panel',
				id:'gridJarak',
				hidden:true,
				bodyStyle: 'padding-left: 105px;border:none',
				items:[grid_detail_jarak]
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
						var cekLeng = Ext.getCmp('tf_pa').getValue().length;
						var isiGrid = 0;
						var i = 0;
						// console.log(UbahTglFrom + "|" + UbahTglTo);
						if(Ext.getCmp('cb_tipe').getValue() != '' && Ext.getCmp('tf_pa').getValue() != ''){
							if(Ext.getCmp('cb_tipe').getValue() == 'BP'){
								isiGrid = store_detail_ritasi.count();
								if(isiGrid!=0){
									if(UbahTglFrom <= UbahTglTo){
										if(cekLeng < 100){
											var arrRitasi = new Array();
											var arrJarakAwal = new Array();
											var arrJarakAkhir = new Array();
											var arrNominal = new Array();
											store_detail_ritasi.each(
												function(record)  {
													arrRitasi[i]=record.get('DATA_RITASI_KE');
													arrJarakAwal[i]=0;
													arrJarakAkhir[i]=0;
													arrNominal[i]=record.get('DATA_NOMINAL');
													i=i+1;
												}
											);
											Ext.Ajax.request({
												url:'<?php echo 'simpan_nominal.php'; ?>',
												params:{
													hdid:Ext.getCmp('hd_id').getValue(),
													typeform:Ext.getCmp('tf_formtype').getValue(),
													tipePlant:Ext.getCmp('cb_tipe').getValue().trim(),
													plantArea:Ext.getCmp('tf_pa').getValue().trim(),
													ritasiKe:Ext.getCmp('tf_ritasike').getValue(),
													jarakAwal:0,
													jarakAkhir:0,
													nominal:Ext.getCmp('tf_nominal').getValue(),
													tgl_awal:Ext.getCmp('tf_tgl_awal').getRawValue(),
													tgl_akhir:Ext.getCmp('tf_tgl_akhir').getRawValue(),
													arrRitasi:Ext.encode(arrRitasi),
													arrJarakAwal:Ext.encode(arrJarakAwal),
													arrJarakAkhir:Ext.encode(arrJarakAkhir),
													arrNominal:Ext.encode(arrNominal),
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
											alertDialog('Peringatan','Plant Area maksimal 100 karakter.');
										}
									} else {
										alertDialog('Peringatan','Active date awal tidak boleh lebih besar dari active date akhir.');
									}
								}
								else {
									alertDialog('Peringatan','Tidak ada data detail.');
								}
							} else {
								isiGrid = store_detail_jarak.count();
								if(isiGrid!=0){
									if(UbahTglFrom <= UbahTglTo){
										if(cekLeng < 100){
											var arrRitasi = new Array();
											var arrJarakAwal = new Array();
											var arrJarakAkhir = new Array();
											var arrNominal = new Array();
											store_detail_jarak.each(
												function(record)  {
													arrRitasi[i]=0;
													arrJarakAwal[i]=record.get('DATA_JARAK_AWAL');
													arrJarakAkhir[i]=record.get('DATA_JARAK_AKHIR');
													arrNominal[i]=record.get('DATA_NOMINAL');
													i=i+1;
												}
											);
											Ext.Ajax.request({
												url:'<?php echo 'simpan_nominal.php'; ?>',
												params:{
													hdid:Ext.getCmp('hd_id').getValue(),
													typeform:Ext.getCmp('tf_formtype').getValue(),
													tipePlant:Ext.getCmp('cb_tipe').getValue().trim(),
													plantArea:Ext.getCmp('tf_pa').getValue().trim(),
													ritasiKe:0,
													jarakAwal:Ext.getCmp('tf_jarak_from').getValue(),
													jarakAkhir:Ext.getCmp('tf_jarak_to').getValue(),
													nominal:Ext.getCmp('tf_nominal').getValue(),
													tgl_awal:Ext.getCmp('tf_tgl_awal').getRawValue(),
													tgl_akhir:Ext.getCmp('tf_tgl_akhir').getRawValue(),
													arrRitasi:Ext.encode(arrRitasi),
													arrJarakAwal:Ext.encode(arrJarakAwal),
													arrJarakAkhir:Ext.encode(arrJarakAkhir),
													arrNominal:Ext.encode(arrNominal),
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
											alertDialog('Peringatan','Plant Area maksimal 100 karakter.');
										}
									} else {
										alertDialog('Peringatan','Active date awal tidak boleh lebih besar dari active date akhir.');
									}
								}
								else {
									alertDialog('Peringatan','Tidak ada data detail.');
								}
							}
						}
						else {
							alertDialog('Peringatan','Tipe atau Plant Area belum diisi.');
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
				html:'<div align="center"><font size="5"><b>Form Master Nominal Ritasi Driver</b></font></div>',
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