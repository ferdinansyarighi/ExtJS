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
	var comboPlant = Ext.create('Ext.data.Store', {
		fields: [{
			name:'DATA_VALUE'
		},{
			name:'DATA_NAME'
		}],
		proxy:{
			type:'ajax',
			url:'<?PHP echo PATHAPP ?>/combobox/combobox_plant.php', 
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
			url:'<?PHP echo PATHAPP ?>/combobox/combobox_nosik.php', 
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
	var store_detil=new Ext.data.JsonStore({
		id:'store_cari_id',
		pageSize: 100,
		model: 'Plant',
		fields:[{
			name:'HD_ID'
		},{
			name:'DATA_NO_SIK'
		},{
			name:'DATA_PEMOHON'
		},{
			name:'DATA_DEPARTEMEN'
		},{
			name:'DATA_PLANT'
		},{
			name:'DATA_KATEGORI'
		},{
			name:'DATA_TGL_FROM'
		},{
			name:'DATA_TGL_TO'
		},{
			name:'DATA_KETERANGAN'
		},{
			name:'DATA_JAM_FROM'
		},{
			name:'DATA_JAM_TO'
		},{
			name:'DATA_ALASAN'
		},{
			name:'DATA_ATTACHMENT'
		},{
			name:'DATA_KHUSUS'
		}],
		proxy:{
			type:'ajax',
			url:'isi_grid_approve.php', 
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
        title: 'Data Surat Ijin Karyawan',
        frame: false,
		selModel:sm,
        loadMask: true,
        columns: [{
			dataIndex:'HD_ID',
			header:'No.',
			hidden:true,
			width:50,
		},{
			dataIndex:'DATA_NO_SIK',
			header:'Nomor Dokumen',
			//hidden:true,
			width:150,
		},{
			dataIndex:'DATA_PEMOHON',
			header:'Nama Pemohon',
			//hidden:true,
			width:150,
		},{
			dataIndex:'DATA_DEPARTEMEN',
			header:'Departemen',
			//hidden:true,
			width:150,
		},{
			dataIndex:'DATA_PLANT',
			header:'Plant',
			//hidden:true,
			width:75,
		},{
			dataIndex:'DATA_KATEGORI',
			header:'Kategori',
			//hidden:true,
			width:75,
		},{
			dataIndex:'DATA_KHUSUS',
			header:'Kategori Ijin',
			//hidden:true,
			width:75,
		},{
			dataIndex:'DATA_TGL_FROM',
			header:'Tanggal Awal',
			//hidden:true,
			width:75,
		},{
			dataIndex:'DATA_TGL_TO',
			header:'Tanggal Akhir',
			//hidden:true,
			width:75,
		},{
			dataIndex:'DATA_JAM_FROM',
			header:'Jam Awal',
			//hidden:true,
			width:75,
		},{
			dataIndex:'DATA_JAM_TO',
			header:'Jam Akhir',
			//hidden:true,
			width:75,
		},{
			dataIndex:'DATA_KETERANGAN',
			header:'Keterangan',
			//hidden:true,
			width:250,
		}, {
            header: 'Alasan',
            dataIndex: 'DATA_ALASAN',
			width:250,
            field: {
				xtype:'textfield',
				id:'tf_alasan',
            }
        },{
			dataIndex:'DATA_ATTACHMENT',
			header:'Attachment',
			//hidden:true,
			width:150,
		}],
        plugins: [cellEditing],
		/* listeners:{
			cellclick:function(grid,row,col){
				//alert(col);
				if (col==11) {
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
			}
		} */
    });
	var kategoriForm = "All";
	var contentPanel = Ext.create('Ext.panel.Panel',{
			//title:'Form Input Tanda Terima',
			//region:'center',
			bodyStyle: 'spacing: 10px;border:none',
			items:[{
				xtype:'label',
				html:'<div align="center"><font size="5"><b>Approval Ijin Karyawan</b></font></div>',
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
						xtype: 'fieldset',
						flex: 1,
						title: 'Kategori',
						defaultType: 'radio', // each item will be a radio button
						layout: 'anchor',
						items: [{
							xtype: 'radiogroup',
							labelWidth:50,
							items: [
								{boxLabel: 'All', id : 'rb_all', name: 'rb_kategori', inputValue: 1, checked: true},
								{boxLabel: 'Cuti', id : 'rb_cuti', name: 'rb_kategori', inputValue: 2},
								{boxLabel: 'Sakit', id : 'rb_sakit', name: 'rb_kategori', inputValue: 3},
								{boxLabel: 'Ijin', id : 'rb_ijin', name: 'rb_kategori', inputValue: 4},
								{boxLabel: 'Terlambat', id : 'rb_terlambat', name: 'rb_kategori', inputValue: 5},
							],
							listeners: {
								change:function(){
									if (Ext.getCmp('rb_all').getValue()){
										kategoriForm = "All";
									} else if (Ext.getCmp('rb_cuti').getValue()) {
										kategoriForm = "Cuti";
									} else if (Ext.getCmp('rb_sakit').getValue()) {
										kategoriForm = "Sakit";
									} else if (Ext.getCmp('rb_ijin').getValue()) {
										kategoriForm = "Ijin";
									} else {
										kategoriForm = "Terlambat";
									}
								}
							}
						}]
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
						labelWidth:100,
						id:'cb_plant',
						value:'',
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
						fieldLabel:'No Dok',
						store: comboIjin,
						displayField: 'DATA_NAME',
						valueField: 'DATA_VALUE',
						width:340,
						labelWidth:100,
						id:'cb_nodok',
						value:'',
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
						fieldLabel:'Departemen',
						store: comboDept,
						displayField: 'DATA_NAME',
						valueField: 'DATA_VALUE',
						width:340,
						labelWidth:100,
						id:'cb_dept',
						value:'',
						emptyText : '- Pilih -',
						//editable: false 
						enableKeyEvents : true,
						minChars:1,
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
					columnWidth:.24,
					border:false,
					layout: 'anchor',
					defaultType: 'combobox',
					items:[{
						xtype:'datefield',
						fieldLabel:'Tgl Pembuatan',
						width:200,
						labelWidth:100,
						id:'tf_tgl_from',
						editable: false
					}]
				},{
					columnWidth:.25,
					border:false,
					layout: 'anchor',
					defaultType: 'combobox',
					items:[{
						xtype:'datefield',
						fieldLabel:'s/d',
						width:135,
						labelWidth:25,
						id:'tf_tgl_to',
						editable: false
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
					columnWidth:.2,
					border:false,
					layout: 'anchor',
					defaultType: 'button',
					items:[{
						name: 'cari',
						text: 'Filter',
						width:75,
						handler:function(){
							maskgrid = new Ext.LoadMask(Ext.getCmp('grid_detil_id'), {msg: "Memuat . . ."});
							maskgrid.show();
							store_detil.load({
								params:{
									kategori:kategoriForm,
									tglfrom:Ext.getCmp('tf_tgl_from').getRawValue(),
									tglto:Ext.getCmp('tf_tgl_to').getRawValue(),
									plant:Ext.getCmp('cb_plant').getValue(),
									noDok:Ext.getCmp('cb_nodok').getValue(),
									Dept:Ext.getCmp('cb_dept').getValue(),
								}
							});
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
					text:'Setuju',
					width:50,
					handler:function(){
						var data=sm.getSelection();
						var countGrid = 0;
						var i=0;
						var arrTransID = new Array();
						var arrAlasan = new Array();
						for(var i in data) {
							arrTransID[i]=data[i].get('HD_ID');
							arrAlasan[i]=data[i].get('DATA_ALASAN');
							countGrid++;
						}
						console.log(countGrid);
						console.log(arrTransID);
						if(countGrid>0){
							maskgrid = new Ext.LoadMask(Ext.getCmp('grid_detil_id'), {msg: "Harap Tunggu. . .Sedang Proses Approval . . ."});
							maskgrid.show();
							Ext.Ajax.request({
								url:'<?php echo 'simpan_approvesik.php'; ?>',
								timeout: 100000,
								params:{
									typeform:'Setuju',
									arrTransID:Ext.encode(arrTransID),
									arrAlasan:Ext.encode(arrAlasan),
								},
								method:'POST',
								success:function(response){
									maskgrid.hide();
									var json=Ext.decode(response.responseText);
									if (json.rows == "sukses"){
										alertDialog('Sukses', "Data tersimpan.");
										//var transId=json.results;
										//window.open("isi_pdf_pp.php?hdid=" + transId + "");
										Ext.getCmp('tf_tgl_from').setValue(currentTime);
										Ext.getCmp('tf_tgl_to').setValue(currentTime);
										Ext.getCmp('rb_all').setValue(true);
										Ext.getCmp('rb_cuti').setValue(false);
										Ext.getCmp('rb_sakit').setValue(false);
										Ext.getCmp('rb_ijin').setValue(false);
										Ext.getCmp('rb_terlambat').setValue(false);
										Ext.getCmp('cb_plant').setValue('');
										Ext.getCmp('cb_nodok').setValue('');
										rowGrid = 0;
										store_detil.removeAll();
									} else {
										alertDialog('Kesalahan', "Data gagal disimpan. ");
									} 
								},
								failure:function(error){
									maskgrid.hide();
									alertDialog('Kesalahan','Data gagal disimpan');
								}
							});
						} else {
							alertDialog('Kesalahan','Data Surat Ijin Karyawan belum dicentang.');
						} 
					}
				},{
					xtype:'label',
					html:'&nbsp',
				},{
					xtype:'button',
					text:'Tolak',
					width:50,
					handler:function(){
						var data=sm.getSelection();
						var countGrid = 0;
						var i=0;
						var validasi=0;
						var arrTransID = new Array();
						var arrAlasan = new Array();
						for(var i in data) {
							arrTransID[i]=data[i].get('HD_ID');
							arrAlasan[i]=data[i].get('DATA_ALASAN');
							countGrid++;
							if(data[i].get('DATA_ALASAN')==''){
								validasi++;
							}
						}
						if(validasi==0){
							if(countGrid>0){
								maskgrid = new Ext.LoadMask(Ext.getCmp('grid_detil_id'), {msg: "Harap Tunggu. . .Sedang Proses Approval . . ."});
								maskgrid.show();
								Ext.Ajax.request({
									url:'<?php echo 'simpan_approvesik.php'; ?>',
									params:{
										typeform:'Tolak',
										arrTransID:Ext.encode(arrTransID),
										arrAlasan:Ext.encode(arrAlasan),
									},
									timeout: 100000,
									method:'POST',
									success:function(response){
										maskgrid.hide();
										var json=Ext.decode(response.responseText);
										if (json.rows == "sukses"){
											alertDialog('Sukses', "Data tersimpan.");
											//var transId=json.results;
											//window.open("isi_pdf_pp.php?hdid=" + transId + "");
											Ext.getCmp('tf_tgl_from').setValue(currentTime);
											Ext.getCmp('tf_tgl_to').setValue(currentTime);
											Ext.getCmp('rb_all').setValue(true);
											Ext.getCmp('rb_cuti').setValue(false);
											Ext.getCmp('rb_sakit').setValue(false);
											Ext.getCmp('rb_ijin').setValue(false);
											Ext.getCmp('rb_terlambat').setValue(false);
											Ext.getCmp('cb_plant').setValue('');
											Ext.getCmp('cb_nodok').setValue('');
											rowGrid = 0;
											store_detil.removeAll();
										} else {
											alertDialog('Kesalahan', "Data gagal disimpan. ");
										} 
									},
									failure:function(error){
										maskgrid.hide();
										alertDialog('Kesalahan','Data gagal disimpan');
									}
								});
							} else {
								alertDialog('Kesalahan','Data Surat Ijin Karyawan belum dicentang.');
							}
						} else {
							alertDialog('Kesalahan','Data alasan belum diisi.');
						}
					}
				}]
			}],
		});	
   contentPanel.render('page');
   Ext.getCmp('tf_tgl_from').setValue(currentTime);
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
    			<td width="121" rowspan="2"><img src= <?PHP echo PATHAPP . "/images/header.jpg" ?> alt="" /></td>
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