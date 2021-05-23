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
	var comboPemohon = Ext.create('Ext.data.Store', {
		fields: [{
			name:'DATA_VALUE'
		},{
			name:'DATA_NAME'
		}],
		proxy:{
			type:'ajax',
			url:'<?PHP echo PATHAPP ?>/combobox/combobox_userall.php', 
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
	var store_detil=new Ext.data.JsonStore({
		id:'store_cari_id',
		pageSize: 100,
		model: 'Plant',
		fields:[{
			name:'HD_ID'
		},{
			name:'DATA_JENIS'
		},{
			name:'DATA_PINJAMAN'
		},{
			name:'DATA_CICILAN'
		},{
			name:'DATA_JUMLAH_CICILAN'
		},{
			name:'DATA_OUTSTANDING'
		},{
			name:'OUTSTANDING_ASLI'
		}],
		proxy:{
			type:'ajax',
			url:'isi_grid_potongan.php', 
			reader: {
				root: 'rows',  
			},
		}
	});
	var cellEditing = Ext.create('Ext.grid.plugin.CellEditing', {
        clicksToEdit: 1
    });
	//var sm = Ext.create('Ext.selection.CheckboxModel');
    var grid_detil = Ext.create('Ext.grid.Panel', {
		id:'grid_detil_id',
		region:'center',
		store:store_detil,
        columnLines: true,
		height:75,
		width:755,
		autoScroll:true,
        columns: [{
			dataIndex:'HD_ID',
			header:'No.',
			hidden:true,
			width:50,
		},{
			dataIndex:'OUTSTANDING_ASLI',
			header:'No.',
			hidden:true,
			width:50,
		},{
			dataIndex:'DATA_JENIS',
			header:'Jenis Pinjaman',
			//hidden:true,
			width:150,
		},{
			dataIndex:'DATA_PINJAMAN',
			header:'Jumlah Pinjaman',
			//hidden:true,
			width:150,
            field: {
				xtype: 'numberfield',
				allowBlank: false,
				id:'tf_pinjaman',
				minValue: 0,
				maxValue: 1000000000,
				listeners: {
					change:function(field,newValue,oldValue){
						var nCicilan=0;
						var vPinjaman=newValue;
						var vCicilan=grid_detil.getSelectionModel().getSelection()[0].get('DATA_CICILAN');
						var vOutstanding=grid_detil.getSelectionModel().getSelection()[0].get('OUTSTANDING_ASLI');
						vOutstanding = parseFloat(vOutstanding) + parseFloat(vPinjaman);
						var vID=grid_detil.getSelectionModel().getSelection()[0].get('HD_ID');
						sme= grid_detil.getSelectionModel().getSelection();							
						sma= grid_detil.store.indexOf(sme[0]);
						if(vID==0){
							nCicilan = (parseFloat(vPinjaman) / parseFloat(vCicilan));
						} else {
							nCicilan = (parseFloat(vOutstanding) / parseFloat(vCicilan));
						}
						var sm1=grid_detil.getStore().getAt(sma).set('DATA_OUTSTANDING', vOutstanding);
						var sm1=grid_detil.getStore().getAt(sma).set('DATA_JUMLAH_CICILAN', nCicilan);
					}
				}
            }
		},{
			dataIndex:'DATA_CICILAN',
			header:'Jumlah Cicilan',
			//hidden:true,
			width:150,
            field: {
				xtype: 'numberfield',
				allowBlank: false,
				id:'tf_cicilan',
				minValue: 0,
				maxValue: 100,
				listeners: {
					change:function(field,newValue,oldValue){
						var nCicilan=0;
						var vCicilan=newValue;
						var vPinjaman=grid_detil.getSelectionModel().getSelection()[0].get('DATA_PINJAMAN');
						var vOutstanding=grid_detil.getSelectionModel().getSelection()[0].get('OUTSTANDING_ASLI');
						vOutstanding = parseFloat(vOutstanding) + parseFloat(vPinjaman);
						var vID=grid_detil.getSelectionModel().getSelection()[0].get('HD_ID');
						sme= grid_detil.getSelectionModel().getSelection();							
						sma= grid_detil.store.indexOf(sme[0]);
						if(vID==0){
							nCicilan = (parseFloat(vPinjaman) / parseFloat(vCicilan));
						} else {
							nCicilan = (parseFloat(vOutstanding) / parseFloat(vCicilan));
						}
						var sm1=grid_detil.getStore().getAt(sma).set('DATA_OUTSTANDING', vOutstanding);
						var sm1=grid_detil.getStore().getAt(sma).set('DATA_JUMLAH_CICILAN', nCicilan);
					}
				}
            }
		},{
			dataIndex:'DATA_JUMLAH_CICILAN',
			header:'Cicilan per Periode',
			//hidden:true,
			width:150,
            field: {
				xtype: 'numberfield',
				allowBlank: false,
				id:'tf_jum_cicilan',
				minValue: 0,
				maxValue: 1000000000,
				readOnly:true,
            }
		},{
			dataIndex:'DATA_OUTSTANDING',
			header:'Outstanding',
			//hidden:true,
			width:150,
            // field: {
				// xtype: 'numberfield',
				// allowBlank: false,
				// id:'tf_outstanding',
				// minValue: 0,
				// maxValue: 1000000000,
            // }
		}],
        selModel: {
            selType: 'cellmodel'
        },
        plugins: [cellEditing],
    });
	var kategoriForm = "All";
	var contentPanel = Ext.create('Ext.panel.Panel',{
			//title:'Form Input Tanda Terima',
			//region:'center',
			bodyStyle: 'spacing: 10px;border:none',
			items:[{
				xtype:'label',
				html:'<div align="center"><font size="5"><b>Potongan Pinjaman dan BS</b></font></div>',
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
					columnWidth:.6,
					border:false,
					layout: 'anchor',
					defaultType: 'combobox',
					items:[{		
						xtype:'combobox',
						fieldLabel:'Nama User',
						store: comboPemohon,
						displayField: 'DATA_NAME',
						valueField: 'DATA_VALUE',
						width:450,
						labelWidth:100,
						id:'cb_user',
						value:'',
						emptyText : '- Pilih -',
						//editable: false 
						enableKeyEvents : true,
						minChars:1,
						listeners: {
							select:function(f,r,i){
								var nama_pem=r[0].data.DATA_VALUE;
								Ext.Ajax.request({
									url:'<?php echo 'isi_dept.php'; ?>',
										params:{
											nama_pem:nama_pem,
										},
										success:function(response){
											var json=Ext.decode(response.responseText);
											var deskripsiasli = json.results;
											var deskripsisplit = deskripsiasli.split('.');
											var nama_dept = deskripsisplit[2];
											Ext.getCmp('tf_dept').setValue(nama_dept);
										},
									method:'POST',
								});
								Ext.Ajax.request({
									url:'<?php echo 'isi_perusahaan.php'; ?>',
										params:{
											nama_pem:nama_pem,
										},
										success:function(response){
											var json=Ext.decode(response.responseText);
											var sisaCuti = json.results;
											Ext.getCmp('tf_perusahaan').setValue(sisaCuti);
										},
									method:'POST',
								});
								Ext.Ajax.request({
									url:'<?php echo 'isi_plant.php'; ?>',
										params:{
											nama_pem:nama_pem,
										},
										success:function(response){
											var json=Ext.decode(response.responseText);
											var deskripsiasli = json.results;
											Ext.getCmp('tf_plant').setValue(deskripsiasli);
										},
									method:'POST',
								});
								store_detil.setProxy({
									type:'ajax',
									url:'isi_grid_potongan.php?nama_pem=' + nama_pem,
									reader: {
										type: 'json',
										root: 'data', 
										totalProperty:'total'   
									}
								});
								store_detil.load();
							}
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
					columnWidth:.6,
					border:false,
					layout: 'anchor',
					defaultType: 'combobox',
					items:[{		
						xtype:'textfield',
						fieldLabel:'Perusahaan',
						width:450,
						labelWidth:100,
						id:'tf_perusahaan',
						readOnly:true,
						fieldStyle:'background:#B8B8B8 ;font-weight:bold;'
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
					columnWidth:.6,
					border:false,
					layout: 'anchor',
					defaultType: 'combobox',
					items:[{		
						xtype:'textfield',
						fieldLabel:'Departemen',
						width:450,
						labelWidth:100,
						id:'tf_dept',
						readOnly:true,
						fieldStyle:'background:#B8B8B8 ;font-weight:bold;'
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
					columnWidth:.6,
					border:false,
					layout: 'anchor',
					defaultType: 'combobox',
					items:[{		
						xtype:'textfield',
						fieldLabel:'Plant',
						width:450,
						labelWidth:100,
						id:'tf_plant',
						readOnly:true,
						fieldStyle:'background:#B8B8B8 ;font-weight:bold;'
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
					width:50,
					handler:function(){
						if (Ext.getCmp('cb_user').getValue()!=''){
							var arrPinjamanP = 0;
							var arrCicilanP = 0;
							var arrJumCicilanP = 0;
							var arrOutstandingP = 0;
							var arrPinjamanB = 0;
							var arrCicilanB = 0;
							var arrJumCicilanB = 0;
							var arrOutstandingB = 0;
							var countPot = 0;
							store_detil.each(
								function(record)  {
									if(countPot == 0){
										arrPinjamanP = record.get('DATA_PINJAMAN');
										arrCicilanP = record.get('DATA_CICILAN');
										arrJumCicilanP = record.get('DATA_JUMLAH_CICILAN');
										arrOutstandingP = record.get('DATA_OUTSTANDING');
									} else {
										arrPinjamanB = record.get('DATA_PINJAMAN');
										arrCicilanB = record.get('DATA_CICILAN');
										arrJumCicilanB = record.get('DATA_JUMLAH_CICILAN');
										arrOutstandingB = record.get('DATA_OUTSTANDING');
									}
									countPot++;
								}
							);
							Ext.Ajax.request({
								url:'<?php echo 'simpan_potongan.php'; ?>',
								params:{
									nama_user:Ext.getCmp('cb_user').getValue(),
									arrPinjamanP:arrPinjamanP,
									arrCicilanP:arrCicilanP,
									arrJumCicilanP:arrJumCicilanP,
									arrOutstandingP:arrOutstandingP,
									arrPinjamanB:arrPinjamanB,
									arrCicilanB:arrCicilanB,
									arrJumCicilanB:arrJumCicilanB,
									arrOutstandingB:arrOutstandingB,
								},
								method:'POST',
								success:function(response){
									var json=Ext.decode(response.responseText);
									var jsonresults = json.results;
									// var jsonsplit = jsonresults.split('|');
									// var transId = jsonsplit[0];
									// var transNo = jsonsplit[1];
									if (json.rows == "sukses"){
										alertDialog('Sukses', "Data tersimpan dengan nama : " + jsonresults + ".");
										Ext.getCmp('cb_user').setValue('');
										Ext.getCmp('tf_perusahaan').setValue('');
										Ext.getCmp('tf_dept').setValue('');
										Ext.getCmp('tf_plant').setValue('');
										store_detil.removeAll();
									} else {
										alertDialog('Kesalahan', "Data gagal disimpan. ");
									} 
								},
								failure:function(error){
									alertDialog('Kesalahan','Data gagal disimpan');
								}
							}); 
						} else {
							alertDialog('Kesalahan','Nama User belum dipilih.');
						}
					}
				},{
					xtype:'label',
					html:'&nbsp',
				}]
			}],
		});	
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