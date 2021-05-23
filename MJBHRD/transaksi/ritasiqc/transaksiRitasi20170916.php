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
		Ext.getCmp('tf_periode').setValue('- Pilih -')
		// Ext.getCmp('tf_tgl_from').setValue(currentTime)
		// Ext.getCmp('tf_tgl_to').setValue(currentTime)
		Ext.getCmp('cb_nama').setValue('')
		comboNamaQC.load();
		store_detil.removeAll()
	};
	var comboPeriode = Ext.create('Ext.data.Store', {
		fields: [{
			name:'DATA_VALUE'
		},{
			name:'DATA_NAME'
		}],
		proxy:{
			type:'ajax',
			url:'<?PHP echo PATHAPP ?>/combobox/combobox_perioderitasi.php', 
			reader: {
				type: 'json',
				root: 'data', 
				totalProperty:'total'   
			},
		}
	});
	var comboPlantRitasi = Ext.create('Ext.data.Store', {
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
	var comboNamaQC = Ext.create('Ext.data.Store', {
		fields: [{
			name:'DATA_VALUE'
		},{
			name:'DATA_NAME'
		}],
		proxy:{
			type:'ajax',
			url:'<?PHP echo PATHAPP ?>/combobox/combobox_namaqc.php', 
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
			name:'DATA_SJ'
		},{
			name:'DATA_TGL'
		},{
			name:'DATA_JAM'
		},{
			name:'DATA_PLANT'
		},{
			name:'DATA_CUST'
		},{
			name:'DATA_PROYEK'
		},{
			name:'DATA_VOL'
		},{
			name:'DATA_VOL_RETUR'
		},{
			name:'DATA_SOPIR'
		},{
			name:'DATA_TRUK'
		},{
			name:'DATA_PLANT_CODE'
		},{
			name:'DATA_LHO'
		},{
			name:'DATA_NOMINAL'
		},{
			name:'DATA_TOTAL'
		},{
			name:'DATA_NOMINAL_ID'
		},{
			name:'DATA_GROUP_ID'
		},{
			name:'DATA_NOTE'
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
	var sm = Ext.create('Ext.selection.CheckboxModel');
    var grid_detil = Ext.create('Ext.grid.Panel', {
		id:'grid_detil_id',
        store: store_detil,
		autoScroll:true,
        columnLines: true,
        width: 850,
        height: 300,
        title: 'List Surat Jalan',
        frame: false,
        loadMask: true,
        columns: [{
			xtype:'rownumberer',
			id:'row_id',
			header:'No',
			width:50
		},{
			dataIndex:'HD_ID',
			header:'id',
			width:100,
			hidden:true
		},{
			dataIndex:'DATA_JAM',
			header:'Jam Berangkat',
			width:100,
			hidden:true
		},{
			dataIndex:'DATA_NOMINAL',
			header:'Nominal',
			width:100,
			hidden:true
		},{
			dataIndex:'DATA_TOTAL',
			header:'Total',
			width:100,
			hidden:true
		},{
			dataIndex:'DATA_NOMINAL_ID',
			header:'Nominal ID',
			width:100,
			hidden:true
		},{
			dataIndex:'DATA_GROUP_ID',
			header:'Group ID',
			width:100,
			hidden:true
		},{
			dataIndex:'DATA_SJ',
			header:'No Surat Jalan',
			width:180,
			//hidden:true
		},{
			dataIndex:'DATA_NOTE',
			header:'Note',
			width:100,
			// hidden:true
		}, {
            header: 'No LHO',
            dataIndex: 'DATA_LHO',
			width:150,
            field: {
				xtype:'textfield',
				id:'tf_lho',
            }
        },{
			dataIndex:'DATA_TGL',
			header:'SJ Date',
			width:90,
			//hidden:true
		},{
			dataIndex:'DATA_PLANT',
			header:'Plant',
			width:100,
			//hidden:true
		},{
			dataIndex:'DATA_CUST',
			header:'Customer',
			width:100,
			// hidden:true
		},{
			dataIndex:'DATA_VOL',
			header:'Vol SJ',
			align:'right',
			width:60,
			// hidden:true
		},{
			dataIndex:'DATA_VOL_RETUR',
			header:'Vol Retur',
			align:'right',
			width:60,
			// hidden:true
		},{
			dataIndex:'DATA_TRUK',
			header:'TM',
			width:50,
			// hidden:true
		},{
			dataIndex:'DATA_SOPIR',
			header:'Driver',
			width:100,
			// hidden:true
		},{
			dataIndex:'DATA_PROYEK',
			header:'Project Location',
			width:100,
			// hidden:true
		},{
			dataIndex:'DATA_PLANT_CODE',
			header:'Plant Code',
			width:100,
			hidden:true
		},{
			dataIndex:'DATA_NOMINAL',
			header:'Nominal',
			width:100,
			// hidden:true
		},{
			dataIndex:'DATA_TOTAL',
			header:'Total',
			width:100,
			// hidden:true
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
        selModel: {
            selType: 'cellmodel'
        },
        plugins: [cellEditing],
		listeners:{
			cellclick:function(grid,row,col){
				// alert(col);
				if (col==21) {
					var userID=grid_detil.getSelectionModel().getSelection()[0].get('HD_ID');
					var userRecord = GetUserRecord(userID);
					store_detil.remove(userRecord);
				}
			}
		}
    });
	var kategoriForm = "All";
	var store_popuptransaksi=new Ext.data.JsonStore({
		id:'store_popuptransaksi_id',
		pageSize: 100,
		fields:[{
			name:'HD_ID'
		},{
			name:'DATA_SJ'
		},{
			name:'DATA_TGL'
		},{
			name:'DATA_JAM'
		},{
			name:'DATA_PLANT'
		},{
			name:'DATA_CUST'
		},{
			name:'DATA_PROYEK'
		},{
			name:'DATA_VOL'
		},{
			name:'DATA_VOL_RETUR'
		},{
			name:'DATA_SOPIR'
		},{
			name:'DATA_TRUK'
		},{
			name:'DATA_PLANT_CODE'
		},{
			name:'DATA_NOMINAL'
		},{
			name:'DATA_TOTAL'
		},{
			name:'DATA_NOMINAL_ID'
		},{
			name:'DATA_GROUP_ID'
		},{
			name:'DATA_NOTE'
		}],
		 proxy:{
			type:'ajax',
			url:'isi_grid_popuptransaksi.php', 
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
	var grid_popuptransaksi=Ext.create('Ext.grid.Panel',{
		id:'grid_popuptransaksi_id',
		region:'center',
		store:store_popuptransaksi,
        columnLines: true,
        loadMask: true,
		columns:[{
			xtype:'rownumberer',
			id:'row_id',
			header:'No',
			width:50
		},{
			dataIndex:'HD_ID',
			header:'id',
			width:100,
			hidden:true
		},{
			dataIndex:'DATA_JAM',
			header:'Jam Berangkat',
			width:100,
			hidden:true
		},{
			dataIndex:'DATA_NOMINAL',
			header:'Nominal',
			width:100,
			hidden:true
		},{
			dataIndex:'DATA_TOTAL',
			header:'Total',
			width:100,
			hidden:true
		},{
			dataIndex:'DATA_NOMINAL_ID',
			header:'Nominal ID',
			width:100,
			hidden:true
		},{
			dataIndex:'DATA_GROUP_ID',
			header:'Group ID',
			width:100,
			hidden:true
		},{
			dataIndex:'DATA_SJ',
			header:'No Surat Jalan',
			width:180,
			//hidden:true
		},{
			dataIndex:'DATA_NOTE',
			header:'Note',
			width:100,
			// hidden:true
		},{
			dataIndex:'DATA_TGL',
			header:'SJ Date',
			width:90,
			//hidden:true
		},{
			dataIndex:'DATA_PLANT',
			header:'Plant',
			width:100,
			//hidden:true
		},{
			dataIndex:'DATA_CUST',
			header:'Customer',
			width:100,
			// hidden:true
		},{
			dataIndex:'DATA_VOL',
			header:'Vol SJ',
			align:'right',
			width:60,
			// hidden:true
		},{
			dataIndex:'DATA_VOL_RETUR',
			header:'Vol Retur',
			align:'right',
			width:60,
			// hidden:true
		},{
			dataIndex:'DATA_TRUK',
			header:'TM',
			width:50,
			// hidden:true
		},{
			dataIndex:'DATA_SOPIR',
			header:'Driver',
			width:100,
			// hidden:true
		},{
			dataIndex:'DATA_PROYEK',
			header:'Project Location',
			width:100,
			// hidden:true
		},{
			dataIndex:'DATA_PLANT_CODE',
			header:'Plant Code',
			width:100,
			hidden:true
		}],
		multiSelect:true,
	});
	var store_popupheader=new Ext.data.JsonStore({
		id:'store_popupheader_id',
		pageSize: 100,
		fields:[{
			name:'HD_ID'
		},{
			name:'DATA_PERIOD_AWAL'
		},{
			name:'DATA_PERIOD_AKHIR'
		},{
			name:'DATA_NAMA_QC_ID'
		},{
			name:'DATA_NAMA_QC'
		}],
		 proxy:{
			type:'ajax',
			url:'isi_grid_popupheader.php', 
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
	var grid_popupheader=Ext.create('Ext.grid.Panel',{
		id:'grid_popupheader_id',
		region:'center',
		store:store_popupheader,
        columnLines: true,
        loadMask: true,
		columns:[{
			xtype:'rownumberer',
			id:'row_id',
			header:'No',
			width:50
		},{
			dataIndex:'HD_ID',
			header:'id',
			width:100,
			hidden:true
		},{
			dataIndex:'DATA_NAMA_QC_ID',
			header:'QC id',
			width:100,
			hidden:true
		},{
			dataIndex:'DATA_PERIOD_AWAL',
			header:'Periode Awal',
			width:140
		},{
			dataIndex:'DATA_PERIOD_AKHIR',
			header:'Periode Akhir',
			width:140
		},{
			dataIndex:'DATA_NAMA_QC',
			header:'QC Name',
			width:400
		}],
		listeners: {
			dblclick: {
				element: 'body', //bind to the underlying body property on the panel
				fn: function(){ 
					var hdid=grid_popupheader.getSelectionModel().getSelection()[0].get('HD_ID');
					Ext.getCmp('tf_hdid').setValue(hdid);
					// Ext.getCmp('tf_tgl_from').setValue(grid_popupheader.getSelectionModel().getSelection()[0].get('DATA_PERIOD_AWAL'));
					// Ext.getCmp('tf_tgl_to').setValue(grid_popupheader.getSelectionModel().getSelection()[0].get('DATA_PERIOD_AKHIR'));
					Ext.getCmp('tf_periode').setValue(grid_popupheader.getSelectionModel().getSelection()[0].get('DATA_PERIOD_AWAL') + ' - ' + grid_popupheader.getSelectionModel().getSelection()[0].get('DATA_PERIOD_AKHIR'));
					comboNamaQC.load();
					Ext.getCmp('cb_nama').setValue(grid_popupheader.getSelectionModel().getSelection()[0].get('DATA_NAMA_QC_ID'));
					Ext.getCmp('tf_typeform').setValue('edit');
					store_detil.load({
						params:{
							hd_id:hdid,
						}
					});
					wind.hide();
				}
			}
		}
	});
	var wind=Ext.create('Ext.Window', {
		title: 'Cari Transaksi Ritasi QC',
		width: 750,
		height: 350,							
		layout: 'fit',
		closeAction:'hide',
		tbar:[,{
			xtype:'label',
			html:'&nbsp',
		},{
			xtype: 'fieldcontainer',
			defaultType: 'checkboxfield',
			items: [
				{
					name      : 'topping',
					inputValue: '1',
					id        : 'cb_filter1',
					checked   : true,
				}
			]
		}, {
			xtype:'textfield',
			fieldLabel:'QC Name',
			maxLengthText:5,
			id:'tf_filter',
			labelWidth:70,
			listeners: {
				 change: function(field,newValue,oldValue){
						field.setValue(newValue.toUpperCase());
				}
			}
		},{
			xtype:'label',
			html:'&nbsp',
		}, {
			xtype: 'fieldcontainer',
			defaultType: 'checkboxfield',
			items: [
				{
					name      : 'topping',
					inputValue: '1',
					id        : 'cb_filter2',
					checked   : true,
				}
			]
		}, {
			xtype: 'datefield',
			name: 'date1',
			fieldLabel: 'Tanggal Pembuatan',
			width:225,
			labelWidth:125,
			id:'tf_filter_from'
		}, {
			xtype: 'datefield',
			name: 'date1',
			fieldLabel: 'to',
			width:130,
			labelWidth:30,
			id:'tf_filter_to'
		},{
			xtype:'label',
			html:'&nbsp',
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
				if(Ext.getCmp('tf_filter_from').getValue()<=Ext.getCmp('tf_filter_to').getValue()){
					maskgrid = new Ext.LoadMask(Ext.getCmp('grid_popupheader_id'), {msg: "Memuat . . ."});
					maskgrid.show();
					store_popupheader.load({
						params:{
							tffilter:Ext.getCmp('tf_filter').getValue(),
							tglfrom:Ext.getCmp('tf_filter_from').getRawValue(),
							tglto:Ext.getCmp('tf_filter_to').getRawValue(),
							cb1:Ext.getCmp('cb_filter1').getValue(),
							cb2:Ext.getCmp('cb_filter2').getValue(),
						}
					});
				}
				else {
					alertDialog('Kesalahan','Tannggal from lebih besar dari tanggal to.');
				}
				
			}
		}],
		items: grid_popupheader
	});
	var PopupSJ=Ext.create('Ext.Window', {
		title: 'Cari No Surat Jalan',
		width: 850,
		height: 350,							
		layout: 'fit',
		closeAction:'hide',
		tbar:[,{
			xtype:'label',
			html:'&nbsp',
		}, {
			xtype: 'fieldcontainer',
			defaultType: 'checkboxfield',
			items: [
				{
					name      : 'topping',
					inputValue: '1',
					id        : 'cb_filter2_pop',
					checked   : true,
				}
			]
		}, {
			xtype: 'datefield',
			name: 'date1',
			fieldLabel: 'SJ Date',
			width:150,
			labelWidth:50,
			id:'tf_filter_from_pop'
		}, {
			xtype: 'datefield',
			name: 'date1',
			fieldLabel: 'to',
			width:130,
			labelWidth:30,
			id:'tf_filter_to_pop'
		},{
			xtype:'label',
			html:'&nbsp',
		},{
			xtype: 'fieldcontainer',
			defaultType: 'checkboxfield',
			items: [
				{
					name      : 'topping',
					inputValue: '1',
					id        : 'cb_filter1_pop',
					checked   : true,
				}
			]
		}, {		
			xtype:'combobox',
			fieldLabel:'Plant',
			store: comboPlantRitasi,
			displayField: 'DATA_NAME',
			valueField: 'DATA_VALUE',
			width:350,
			labelWidth:50,
			id:'cb_filter_pop',
			value:'',
			emptyText : '- Pilih -',
			//editable: false 
			enableKeyEvents : true,
			minChars:1,
		},{
			xtype:'label',
			html:'&nbsp',
		},{
			xtype:'button',
			text:'View',
			scale:'small',
			width: 45,
			border: 1,
			style: {
				borderColor: 'black',
				borderStyle: 'solid'
			},
			cls:'button-popup',
			handler:function(){
				if(Ext.getCmp('tf_filter_from_pop').getValue()<=Ext.getCmp('tf_filter_to_pop').getValue()){
					maskgrid = new Ext.LoadMask(Ext.getCmp('grid_popuptransaksi_id'), {msg: "Memuat . . ."});
					maskgrid.show();
					store_popuptransaksi.load({
						params:{
							hdid:Ext.getCmp('tf_hdid').getValue(),
							tffilter:Ext.getCmp('cb_filter_pop').getValue(),
							tglfrom:Ext.getCmp('tf_filter_from_pop').getRawValue(),
							tglto:Ext.getCmp('tf_filter_to_pop').getRawValue(),
							cb1:Ext.getCmp('cb_filter1_pop').getValue(),
							cb2:Ext.getCmp('cb_filter2_pop').getValue(),
						}
					});
				}
				else {
					alertDialog('Kesalahan','Tannggal from lebih besar dari tanggal to.');
				}
				
			}
		},{
			xtype:'button',
			text:'Reset',
			scale:'small',
			width: 45,
			border: 1,
			style: {
				borderColor: 'black',
				borderStyle: 'solid'
			},
			cls:'button-popup',
			handler:function(){
				Ext.getCmp('cb_filter_pop').setValue('');
				Ext.getCmp('tf_filter_from_pop').setValue(currentTime);
				Ext.getCmp('tf_filter_to_pop').setValue(currentTime);
				Ext.getCmp('cb_filter1_pop').setValue(true);
				Ext.getCmp('cb_filter2_pop').setValue(true);
				store_popuptransaksi.removeAll();
			}
		},{
			xtype:'button',
			text:'Add',
			scale:'small',
			width: 45,
			border: 1,
			style: {
				borderColor: 'black',
				borderStyle: 'solid'
			},
			cls:'button-popup',
			handler:function(){
				Ext.each(grid_popuptransaksi.getSelectionModel().getSelection(), function(record, index, allRecords) {
				  store_detil.add({'HD_ID':record.get('HD_ID'),'DATA_SJ':record.get('DATA_SJ'),'DATA_TGL':record.get('DATA_TGL'),'DATA_JAM':record.get('DATA_JAM')
								,'DATA_PLANT':record.get('DATA_PLANT'),'DATA_CUST':record.get('DATA_CUST'),'DATA_PROYEK':record.get('DATA_PROYEK'),'DATA_VOL':record.get('DATA_VOL')
								,'DATA_VOL_RETUR':record.get('DATA_VOL_RETUR'),'DATA_SOPIR':record.get('DATA_SOPIR'),'DATA_TRUK':record.get('DATA_TRUK')
								,'DATA_PLANT_CODE':record.get('DATA_PLANT_CODE'),'DATA_NOMINAL':record.get('DATA_NOMINAL'),'DATA_TOTAL':record.get('DATA_TOTAL')
								,'DATA_NOMINAL_ID':record.get('DATA_NOMINAL_ID'),'DATA_GROUP_ID':record.get('DATA_GROUP_ID'),'DATA_NOTE':record.get('DATA_NOTE')
				   }); 
				}); 
				PopupSJ.hide();
			}
		}],
		items: grid_popuptransaksi
	});
	var contentPanel = Ext.create('Ext.panel.Panel',{
			//title:'Form Input Tanda Terima',
			//region:'center',
			bodyStyle: 'spacing: 10px;border:none',
			items:[{
				xtype:'label',
				html:'<div align="center"><font size="5"><b>Ritasi QC</b></font></div>',
			},{
				xtype:'label',
				html:'&nbsp',
			},{		
				xtype:'textfield',
				fieldLabel:'id pp',
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
				id:'colPeriode',
				hidden:false,
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
					columnWidth:.8,
					border:false,
					layout: 'anchor',
					defaultType: 'combobox',
					items:[{		
						xtype:'combobox',
						fieldLabel:'Periode',
						store: comboPeriode,
						displayField: 'DATA_NAME',
						valueField: 'DATA_VALUE',
						width:300,
						labelWidth:100,
						id:'tf_periode',
						value:'- Pilih -',
						//emptyText : '- Pilih -',
						editable: false,
						enableKeyEvents : true,
						minChars:1,
					}]
				}]	
			}
			// ,{
				// layout:'column',
				// border:false,
				// items:[{
					// columnWidth:.02,
					// border:false,
					// layout: 'anchor',
					// defaultType: 'label',
					// items:[{
						// xtype:'label',
						// html:'',
					// }]
				// },{
					// columnWidth:.24,
					// border:false,
					// layout: 'anchor',
					// defaultType: 'combobox',
					// items:[{
						// xtype:'datefield',
						// fieldLabel:'Period',
						// width:200,
						// labelWidth:100,
						// id:'tf_tgl_from',
						// editable: false
					// }]
				// },{
					// columnWidth:.17,
					// border:false,
					// layout: 'anchor',
					// defaultType: 'combobox',
					// items:[{
						// xtype:'datefield',
						// fieldLabel:'s/d',
						// width:135,
						// labelWidth:25,
						// id:'tf_tgl_to',
						// editable: false
					// }]
				// }]	
			// }
			,{
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
					columnWidth:.53,
					border:false,
					layout: 'anchor',
					defaultType: 'combobox',
					items:[{		
						xtype:'combobox',
						fieldLabel:'QC Name',
						store: comboNamaQC,
						displayField: 'DATA_NAME',
						valueField: 'DATA_VALUE',
						width:450,
						labelWidth:100,
						id:'cb_nama',
						value:'',
						emptyText : '- Pilih -',
						//editable: false 
						enableKeyEvents : true,
						minChars:1,
					}]
				},{
					columnWidth:.2,
					border:false,
					layout: 'anchor',
					defaultType: 'button',
					items:[{
						xtype:'button',
						text:'Existing',
						width:75,
						handler:function(){
							Ext.getCmp('tf_filter').setValue('');
							Ext.getCmp('tf_filter_from').setValue(currentTime);
							Ext.getCmp('tf_filter_to').setValue(currentTime);
							Ext.getCmp('cb_filter1').setValue('true');
							Ext.getCmp('cb_filter2').setValue('true');
							store_popupheader.removeAll();
							wind.show();
						}
					}]
				}]	
			},{
				xtype:'panel',
				bodyStyle: 'padding-left: 300px;padding-bottom: 10px;border:none',
				items:[{
					xtype:'label',
					html:'&nbsp',
				}]
			},{
				xtype:'button',
				name: 'cari',
				text: 'Add Surat Jalan',
				width:100,
				handler:function(){
					Ext.getCmp('cb_filter_pop').setValue('');
					Ext.getCmp('tf_filter_from_pop').setValue(currentTime);
					Ext.getCmp('tf_filter_to_pop').setValue(currentTime);
					Ext.getCmp('cb_filter1_pop').setValue('true');
					Ext.getCmp('cb_filter2_pop').setValue('true');
					store_popuptransaksi.removeAll();
					PopupSJ.show();
				} 
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
					text:'Save',
					id:'btn_save',
					width:50,
					handler:function(){
						// console.log(Ext.Date.dateFormat(Ext.getCmp('tf_tgl_from').getValue(), 'Ymd'));
						if(Ext.getCmp('cb_nama').getValue()!='' && Ext.getCmp('tf_periode').getValue()!='- Pilih -'){
							// if(Ext.Date.dateFormat(Ext.getCmp('tf_tgl_from').getValue(), 'Ymd') <= Ext.Date.dateFormat(Ext.getCmp('tf_tgl_to').getValue(), 'Ymd')){
								var isiGrid = 0;
								var countGrid = 0;
								var countRecord = 0;
								var idSJ = 0;
								var jumlah = 0;
								var i = 0;
								var z = 0;
								var kumpulanID = "";
								var typetrans = "Save";
								isiGrid = store_detil.count();
								store_detil.each(
									function(record)  {
										idSJ = record.get('HD_ID');
										if(z == 0){
											kumpulanID = kumpulanID + idSJ;
										} else {
											kumpulanID = kumpulanID + ", " + idSJ;
										}
										z++;
										jumlah = 0;
										for(var x=0; x<isiGrid; x++){
											// console.log(store_detil.data.items[x].data['HD_ID']);
											if(idSJ == store_detil.data.items[x].data['HD_ID']){
												jumlah++;
											}
										}
										// console.log("ID SJ : "+idSJ+" , Jumlah : "+jumlah);
										// console.log(store_detil.find('HD_ID', idSJ));
										// console.log(store_detil.find('HD_ID', 1));
										if(record.get('DATA_LHO')==''){
											countGrid++;
										}
										if(jumlah >= 2){
											countRecord++;
										}
									}
								); 
								if(isiGrid != 0){
									if(countGrid == 0){
										if(countRecord == 0){
											// console.log(kumpulanID);
											Ext.getCmp('btn_save').setDisabled(true);
											var arrID = new Array();
											var arrSJ = new Array();
											var arrTglSJ = new Array();
											var arrLHO = new Array();
											var arrNominal = new Array();
											var arrNominalID = new Array();
											var arrGroupID = new Array();
											store_detil.each(
												function(record)  {
													arrID[i]=record.get('HD_ID');
													arrSJ[i]=record.get('DATA_SJ');
													arrTglSJ[i]=record.get('DATA_TGL');
													arrLHO[i]=record.get('DATA_LHO');
													arrNominal[i]=record.get('DATA_NOMINAL');
													arrNominalID[i]=record.get('DATA_NOMINAL_ID');
													arrGroupID[i]=record.get('DATA_GROUP_ID');
													i=i+1;
												}
											);
											// Ext.Ajax.request({
												// url:'<?php echo 'cek_absen.php'; ?>',
												// params:{
													// tgl_awal:Ext.Date.dateFormat(Ext.getCmp('tf_tgl_from').getValue(), 'Y-m-d'),
													// tgl_akhir:Ext.Date.dateFormat(Ext.getCmp('tf_tgl_to').getValue(), 'Y-m-d'),
													// nama:Ext.getCmp('cb_nama').getValue(),
													// arrID:Ext.encode(arrID),
													// arrSJ:Ext.encode(arrSJ),
													// arrTglSJ:Ext.encode(arrTglSJ),
												// },
												// method:'POST',
												// success:function(response){
													// var json=Ext.decode(response.responseText);
													// if (json.rows == "sukses"){
														Ext.Ajax.request({
															url:'<?php echo 'simpan_ritasi.php'; ?>',
															params:{
																hdid:Ext.getCmp('tf_hdid').getValue(),
																typeform:Ext.getCmp('tf_typeform').getValue(),
																typetrans:typetrans,
																tgl_awal:Ext.getCmp('tf_periode').getValue().substring(0, 10),
																tgl_akhir:Ext.getCmp('tf_periode').getValue().substring(13, 23),
																nama:Ext.getCmp('cb_nama').getValue(),
																kumpulanID:kumpulanID,
																arrID:Ext.encode(arrID),
																arrSJ:Ext.encode(arrSJ),
																arrLHO:Ext.encode(arrLHO),
																arrNominal:Ext.encode(arrNominal),
																arrNominalID:Ext.encode(arrNominalID),
																arrGroupID:Ext.encode(arrGroupID),
															},
															method:'POST',
															success:function(response){
																Ext.getCmp('btn_save').setDisabled(false);
																var json=Ext.decode(response.responseText);
																if (json.rows == "sukses"){
																	alertDialog('Sukses','Data berhasil disimpan');
																	Ext.clearForm();
																} else {
																	alertDialog('Kesalahan', json.rows);
																}
															},
															failure:function(result,action){
																alertDialog('Kesalahan','Data gagal disimpan');
																Ext.getCmp('btn_save').setDisabled(false);
															}
														});
													// } else {
														// alertDialog('Kesalahan', json.rows);
														// Ext.getCmp('btn_save').setDisabled(false);
													// }
												// },
												// failure:function(result,action){
													// alertDialog('Kesalahan','Data gagal disimpan');
													// Ext.getCmp('btn_save').setDisabled(false);
												// }
											// });
										} else {
											alertDialog('Peringatan','Surat Jalan tidak boleh sama.');
										}
									} else {
										alertDialog('Peringatan','Nomor LHO belum diisi.');
									}
								} else {
									alertDialog('Peringatan','Surat Jalan belum dipilih.');
								}
							// } else {
								// alertDialog('Peringatan','Active date awal tidak boleh lebih besar dari active date akhir.');
							// }
						}
						else {
							alertDialog('Peringatan','Periode atau QC Name belum diisi.');
						}
					}
				},{
					xtype:'label',
					html:'&nbsp',
				},{
					xtype:'button',
					text:'Submit',
					id:'btn_submit',
					width:50,
					handler:function(){
						// console.log(Ext.Date.dateFormat(Ext.getCmp('tf_tgl_from').getValue(), 'Ymd'));
						if(Ext.getCmp('cb_nama').getValue()!='' && Ext.getCmp('tf_periode').getValue()!='- Pilih -'){
							// if(Ext.Date.dateFormat(Ext.getCmp('tf_tgl_from').getValue(), 'Ymd') <= Ext.Date.dateFormat(Ext.getCmp('tf_tgl_to').getValue(), 'Ymd')){
								var isiGrid = 0;
								var countGrid = 0;
								var countRecord = 0;
								var idSJ = 0;
								var jumlah = 0;
								var i = 0;
								var z = 0;
								var kumpulanID = "";
								var typetrans = "Submit";
								isiGrid = store_detil.count();
								store_detil.each(
									function(record)  {
										idSJ = record.get('HD_ID');
										if(z == 0){
											kumpulanID = kumpulanID + idSJ;
										} else {
											kumpulanID = kumpulanID + ", " + idSJ;
										}
										z++;
										jumlah = 0;
										for(var x=0; x<isiGrid; x++){
											// console.log(store_detil.data.items[x].data['HD_ID']);
											if(idSJ == store_detil.data.items[x].data['HD_ID']){
												jumlah++;
											}
										}
										// console.log("ID SJ : "+idSJ+" , Jumlah : "+jumlah);
										// console.log(store_detil.find('HD_ID', idSJ));
										// console.log(store_detil.find('HD_ID', 1));
										if(record.get('DATA_LHO')==''){
											countGrid++;
										}
										if(jumlah >= 2){
											countRecord++;
										}
									}
								); 
								if(isiGrid != 0){
									if(countGrid == 0){
										if(countRecord == 0){
											// console.log(kumpulanID);
											Ext.getCmp('btn_submit').setDisabled(true);
											var arrID = new Array();
											var arrSJ = new Array();
											var arrTglSJ = new Array();
											var arrLHO = new Array();
											var arrNominal = new Array();
											var arrNominalID = new Array();
											var arrGroupID = new Array();
											store_detil.each(
												function(record)  {
													arrID[i]=record.get('HD_ID');
													arrSJ[i]=record.get('DATA_SJ');
													arrTglSJ[i]=record.get('DATA_TGL');
													arrLHO[i]=record.get('DATA_LHO');
													arrNominal[i]=record.get('DATA_NOMINAL');
													arrNominalID[i]=record.get('DATA_NOMINAL_ID');
													arrGroupID[i]=record.get('DATA_GROUP_ID');
													i=i+1;
												}
											);
											// Ext.Ajax.request({
												// url:'<?php echo 'cek_absen.php'; ?>',
												// params:{
													// tgl_awal:Ext.Date.dateFormat(Ext.getCmp('tf_tgl_from').getValue(), 'Y-m-d'),
													// tgl_akhir:Ext.Date.dateFormat(Ext.getCmp('tf_tgl_to').getValue(), 'Y-m-d'),
													// nama:Ext.getCmp('cb_nama').getValue(),
													// arrID:Ext.encode(arrID),
													// arrSJ:Ext.encode(arrSJ),
													// arrTglSJ:Ext.encode(arrTglSJ),
												// },
												// method:'POST',
												// success:function(response){
													// var json=Ext.decode(response.responseText);
													// if (json.rows == "sukses"){
														Ext.Ajax.request({
															url:'<?php echo 'simpan_ritasi.php'; ?>',
															params:{
																hdid:Ext.getCmp('tf_hdid').getValue(),
																typeform:Ext.getCmp('tf_typeform').getValue(),
																typetrans:typetrans,
																tgl_awal:Ext.getCmp('tf_periode').getValue().substring(0, 10),
																tgl_akhir:Ext.getCmp('tf_periode').getValue().substring(13, 23),
																nama:Ext.getCmp('cb_nama').getValue(),
																kumpulanID:kumpulanID,
																arrID:Ext.encode(arrID),
																arrSJ:Ext.encode(arrSJ),
																arrLHO:Ext.encode(arrLHO),
																arrNominal:Ext.encode(arrNominal),
																arrNominalID:Ext.encode(arrNominalID),
																arrGroupID:Ext.encode(arrGroupID),
															},
															method:'POST',
															success:function(response){
																Ext.getCmp('btn_submit').setDisabled(false);
																var json=Ext.decode(response.responseText);
																if (json.rows == "sukses"){
																	alertDialog('Sukses','Data berhasil disimpan');
																	Ext.clearForm();
																} else {
																	alertDialog('Kesalahan', json.rows);
																}
															},
															failure:function(result,action){
																alertDialog('Kesalahan','Data gagal disimpan');
																Ext.getCmp('btn_submit').setDisabled(false);
															}
														});
													// } else {
														// alertDialog('Kesalahan', json.rows);
														// Ext.getCmp('btn_submit').setDisabled(false);
													// }
												// },
												// failure:function(result,action){
													// alertDialog('Kesalahan','Data gagal disimpan');
													// Ext.getCmp('btn_submit').setDisabled(false);
												// }
											// });
										} else {
											alertDialog('Peringatan','Surat Jalan tidak boleh sama.');
										}
									} else {
										alertDialog('Peringatan','Nomor LHO belum diisi.');
									}
								} else {
									alertDialog('Peringatan','Surat Jalan belum dipilih.');
								}
							// } else {
								// alertDialog('Peringatan','Active date awal tidak boleh lebih besar dari active date akhir.');
							// }
						}
						else {
							alertDialog('Peringatan','Periode atau QC Name belum diisi.');
						}
					}
				},{
					xtype:'label',
					html:'&nbsp',
				},{
					xtype:'button',
					text:'Cancel',
					width:50,
					handler:function(){
						Ext.clearForm();
					}
				}]
			}],
		});	
   contentPanel.render('page');
   Ext.clearForm();
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