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

	function formatDate(value){
        return value ? Ext.Date.dateFormat(value, 'Y-m-d') : '';
    }
	var comboPemohon = Ext.create('Ext.data.Store', {
		fields: [{
			name:'DATA_VALUE'
		},{
			name:'DATA_NAME'
		}],
		proxy:{
			type:'ajax',
			url:'<?PHP echo PATHAPP ?>/combobox/combobox_val_resign.php', 
			reader: {
				type: 'json',
				root: 'data', 
				totalProperty:'total'   
			},
		}
	});

	var comboPengajuan = Ext.create('Ext.data.Store', {
		fields: [{
			name:'DATA_VALUE'
		},{
			name:'DATA_NAME'
		}],
		proxy:{
			type:'ajax',
			url:'<?PHP echo PATHAPP ?>/combobox/combobox_noresignval.php', 
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
		fields:[{
			name:'HD_ID'
		},{
			name:'DATA_NO_PENGAJUAN'
		},{
			name:'DATA_NAMA_KARYAWAN'
		},{
			name:'DATA_COMPANY'
		},{
			name:'DATA_DEPT'
		},{
			name:'DATA_POS'
		},{
			name:'DATA_GRADE'
		},{
			name:'DATA_LOCATION'
		},{
			name:'DATA_TGL_MASUK'
		},{
			name:'DATA_TGL_RESIGN'
		},{
			name:'DATA_TAHUN_LAMA_KERJA'
		},{
			name:'DATA_BULAN_LAMA_KERJA'
		},{
			name:'DATA_HARI_LAMA_KERJA'
		},{
			name:'DATA_MANAGER'
		},{
			name:'DATA_KETERANGAN'
		},{
			name:'DATA_STATUS'
		},{
			name:'DATA_APPROVAL_MANAGER'
		},{
			name:'DATA_APPROVAL_MANAGER_HRD'
		},{
			name:'DATA_TGL_APP_TERAKHIR'
		},{
			name:'DATA_CREATED_BY'
		},{
			name:'DATA_CREATED_DATE'
		},{
			name:'DATA_LAMA_KERJA'
		},{
			name:'DATA_KET_VALIDATION'
		},{
			name:'DATA_ATTACHMENT'
		},{
			name:'DATA_TGL_PENGAJUAN'
		}],
		proxy:{
			type:'ajax',
			url:'grid_val_resign.php', 
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
        title: 'List Form Employee Resign',
        frame: false,
		selModel:sm,
        loadMask: true,
        columns: [{
			dataIndex:'HD_ID',
			header:'No.',
			hidden:true,
			width:50,
		},{
			dataIndex:'HD_ID',
			header:'id',
			width:100,
			hidden:true
		},{
			dataIndex:'DATA_NO_PENGAJUAN',
			header:'Nomor Pengajuan',
			width:160,
			//hidden:true
		},{
			dataIndex:'DATA_NAMA_KARYAWAN',
			header:'Pemohon',
			width:150,
			//hidden:true
		},{
			dataIndex:'DATA_COMPANY',
			header:'Perusahaan',
			width:150,
			//hidden:true
		},{
			dataIndex:'DATA_DEPT',
			header:'Departemen',
			width:150,
			//hidden:true
		},{
			dataIndex:'DATA_POS',
			header:'Jabatan',
			width:100,
			//hidden:true
		},{
			dataIndex:'DATA_GRADE',
			header:'Grade',
			width:100,
			//hidden:true
		},{
			dataIndex:'DATA_LOCATION',
			header:'Location',
			width:80,
			//hidden:true
		},{
			dataIndex:'DATA_TGL_PENGAJUAN',
			header:'Tanggal Pengajuan',
			width:100,
			//hidden:true
		},{
			dataIndex:'DATA_TGL_MASUK',
			header:'Tanggal Masuk',
			width:100,
			//hidden:true
		},{
			dataIndex:'DATA_TGL_RESIGN',
			header:'Tanggal Resign',
			width:100,
			//hidden:true
		},{
			dataIndex:'DATA_LAMA_KERJA',
			header:'Lama Kerja',
			width:175,
			//hidden:true
		},{
			dataIndex:'DATA_TAHUN_LAMA_KERJA',
			header:'Tahun Lama Kerja',
			width:100,
			hidden:true
		},{
			dataIndex:'DATA_BULAN_LAMA_KERJA',
			header:'Bulan Lama Kerja',
			width:100,
			hidden:true
		},{
			dataIndex:'DATA_HARI_LAMA_KERJA',
			header:'Hari Lama Kerja',
			width:175,
			hidden:true
		},{
			dataIndex:'DATA_MANAGER',
			header:'Manager',
			width:150,
			hidden:true
		},{
			dataIndex:'DATA_KETERANGAN',
			header:'Keterangan',
			width:100,
			//hidden:true
		},{
			dataIndex:'DATA_STATUS',
			header:'Flag Aktif',
			width:75,
			hidden:true
		},{
			dataIndex:'DATA_TGL_APP_TERAKHIR',
			header:'Tanggal Approve Terakhir',
			width:150,
			hidden:true
		},{
			dataIndex:'DATA_CREATED_BY',
			header:'Pembuat',
			width:300,
			hidden:true
		},{
			dataIndex:'DATA_CREATED_DATE',
			header:'Tanggal Pembuatan',
			width:75,
			hidden:true
		},{
			dataIndex:'DATA_ATTACHMENT',
			header:'Attachment',
			//hidden:true,
			width:150,
		},{
            header: 'Keterangan Validasi',
            dataIndex: 'DATA_KET_VALIDATION',
			width:250,
            field:{
				xtype:'textfield',
				id:'tf_ket_disapp',
            }
        }],
        plugins: [cellEditing],
    });

	var contentPanel = Ext.create('Ext.panel.Panel',{

			bodyStyle: 'spacing: 10px;border:none',
			items:[{
				xtype:'label',
				html:'<div align="center"><font size="5"><b>Validate Resignation</b></font></div>',
			},{
				xtype:'label',
				html:'&nbsp',
			},{		
				xtype:'textfield',
				fieldLabel:'id resign',
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
					columnWidth:.24,
					border:false,
					layout: 'anchor',
					defaultType: 'combobox',
					items:[{
						xtype:'datefield',
						fieldLabel:'Periode Resign',
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
						fieldLabel:'Pemohon',
						store: comboPemohon,
						displayField: 'DATA_NAME',
						valueField: 'DATA_VALUE',
						width:340,
						labelWidth:100,
						id:'cb_nama_pemohon',
						value:'',
						emptyText : '- Pilih -',
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
						fieldLabel:'No. Pengajuan',
						store: comboPengajuan,
						displayField: 'DATA_NAME',
						valueField: 'DATA_VALUE',
						width:340,
						labelWidth:100,
						id:'cb_no_pengajuan',
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
									tglfrom:Ext.getCmp('tf_tgl_from').getRawValue(),
									tglto:Ext.getCmp('tf_tgl_to').getRawValue(),
									namakar:Ext.getCmp('cb_nama_pemohon').getValue(),
									nopengajuan:Ext.getCmp('cb_no_pengajuan').getValue(),
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
					text:'Validate',
					width:50,
					handler:function(){
						var data=sm.getSelection();
						var countGrid = 0;
						var i=0;
						var arrTransID = new Array();
						var arrAlasan = new Array();
						for(var i in data) {
							arrTransID[i]=data[i].get('HD_ID');
							arrAlasan[i]=data[i].get('DATA_KET_VALIDATION');
							countGrid++;
						}
						console.log(countGrid);
						console.log(arrTransID);

						if(countGrid>0){
							maskgrid = new Ext.LoadMask(Ext.getCmp('grid_detil_id'), {msg: "Harap Tunggu. . .Sedang Proses Validasi . . ."});
							maskgrid.show();
							Ext.Ajax.request({
								url:'<?php echo 'simpan_validateresign.php'; ?>',
								timeout: 500000,
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
										rowGrid = 0;
										store_detil.removeAll();
										maskgrid = new Ext.LoadMask(Ext.getCmp('grid_detil_id'), {msg: "Memuat . . ."});
										maskgrid.show();
										store_detil.load({
											params:{
												tglfrom:Ext.getCmp('tf_tgl_from').getRawValue(),
												tglto:Ext.getCmp('tf_tgl_to').getRawValue(),
												namakar:Ext.getCmp('cb_nama_pemohon').getValue(),
												nopengajuan:Ext.getCmp('cb_no_pengajuan').getValue(),
											}
										});
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
							alertDialog('Kesalahan','Data Pengajuan Resign belum dicentang.');
						} 
					}
				},{
					xtype:'label',
					html:'&nbsp',
				},{
					xtype:'button',
					text:'Clear',
					width:50,
					handler:function(){
						store_detil.removeAll();
					}
				}]
			}],
		});	
   contentPanel.render('page');
   Ext.getCmp('tf_tgl_from').setValue(currentTime);
   Ext.getCmp('tf_tgl_to').setValue(currentTime);
   comboPemohon.load();
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