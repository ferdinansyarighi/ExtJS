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
	var maskgrid;
	var sumidtxt='';
	var comboUser = Ext.create('Ext.data.Store', {
		fields: [{
			name:'DATA_VALUE'
		},{
			name:'DATA_NAME'
		}],
		proxy:{
			type:'ajax',
			url:'<?PHP echo PATHAPP ?>/combobox/combobox_usergaji.php', 
			reader: {
				type: 'json',
				root: 'data', 
				totalProperty:'total'   
			},
		}
	});
	var store_cuti=new Ext.data.JsonStore({
		id:'store_cuti_id',
		pageSize: 50,
		fields:[{
			name:'HD_ID'
		},{
			name:'DATA_NAMA'
		},{
			name:'DATA_PERUSAHAAN'
		},{
			name:'DATA_DEPARTMENT'
		},{
			name:'DATA_JABATAN'
		},{
			name:'DATA_TAHUNAN'
		},{
			name:'DATA_SAKIT'
		}],
		proxy:{
			type:'ajax',
			url:'isi_grid_cuti.php', 
			reader: {
				type: 'json',
				root: 'data',
				totalProperty:'total'        
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
	var grid_cuti=Ext.create('Ext.grid.Panel',{
		id:'grid_cuti_id',
		region:'center',
		store:store_cuti,
        columnLines: true,
		height:500,
		autoScroll:true,
		loadMask: true,
		columns:[{
			xtype:'rownumberer',
			id:'row_id',
			header:'No',
			width:30
		},{
			dataIndex:'DATA_NAMA',
			header:'Nama',
			width:200,
		},{
			dataIndex:'DATA_PERUSAHAAN',
			header:'Perusahaan',
			width:200,
		},{
			dataIndex:'DATA_DEPARTMENT',
			header:'Department',
			width:200
		},{
			dataIndex:'DATA_JABATAN',
			header:'Jabatan',
			width:100
		},{
			text: 'Saldo Cuti',
			columns:[{
				dataIndex:'DATA_TAHUNAN',
				header:'Tahunan',
				width:75
			},{
				dataIndex:'DATA_SAKIT',
				header:'Sakit',
				width:75
			}]
		}]
	});
	var currentTime = new Date();
	var kategoriForm = "Transfer";
	var contentPanel = Ext.create('Ext.panel.Panel',{
		bodyStyle: 'spacing: 10px;border:none',
		id: "bodi",
		items:[{
			xtype:'label',
			html:'<div align="center"><font size="5"><b>Report Preview Elemen Gaji</b></font></div>',
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
					html:'',
				}]
			},{
				columnWidth:.8,
				border:false,
				layout: 'anchor',
				defaultType: 'combobox',
				items:[{		
					xtype:'combobox',
					fieldLabel:'Nama Karyawan',
					store: comboUser,
					displayField: 'DATA_NAME',
					valueField: 'DATA_VALUE',
					width:500,
					labelWidth:125,
					id:'cb_karyawan',
					value:'',
					emptyText : '- Pilih -',
					//editable: false 
					enableKeyEvents : true,
					minChars:1,
					listeners: {
						select:function(f,r,i){
							var person_id=r[0].data.DATA_VALUE;
							//console.log(nama_pem);
							Ext.Ajax.request({
								url:'<?php echo 'isi_detil.php'; ?>',
									params:{
										person_id:person_id,
									},
									success:function(response){
										var json=Ext.decode(response.responseText);
										var deskripsiasli = json.results;
										var deskripsisplit = deskripsiasli.split('|');
										Ext.getCmp('tf_nik').setValue(deskripsisplit[0]);
										Ext.getCmp('tf_fa').setValue(deskripsisplit[1]);
										Ext.getCmp('tf_dept').setValue(deskripsisplit[2]);
										Ext.getCmp('tf_jab').setValue(deskripsisplit[3]);
										Ext.getCmp('tf_plant').setValue(deskripsisplit[4]);
										Ext.getCmp('tf_gp').setValue(deskripsisplit[5]);
										Ext.getCmp('tf_tj').setValue(deskripsisplit[6]);
										Ext.getCmp('tf_tl').setValue(deskripsisplit[7]);
										Ext.getCmp('tf_tg').setValue(deskripsisplit[8]);
										Ext.getCmp('tf_um').setValue(deskripsisplit[9]);
										Ext.getCmp('tf_ut').setValue(deskripsisplit[10]);
										Ext.getCmp('tf_ph').setValue(deskripsisplit[11]);
										Ext.getCmp('tf_pph').setValue(deskripsisplit[12]);
										Ext.getCmp('tf_bk').setValue(deskripsisplit[13]);
										Ext.getCmp('tf_bt').setValue(deskripsisplit[14]);
										Ext.getCmp('tf_pin').setValue(deskripsisplit[15]);
										Ext.getCmp('tf_bs').setValue(deskripsisplit[16]);
									},
								method:'POST',
							});
						},
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
				columnWidth:.8,
				border:false,
				layout: 'anchor',
				defaultType: 'combobox',
				items:[{
					xtype:'textfield',
					fieldLabel:'NIK',
					width:300,
					labelWidth:125,
					id:'tf_nik',
					value:'',
					readOnly:true,
					fieldStyle:'background:#B8B8B8 ;font-weight:bold;'
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
				columnWidth:.8,
				border:false,
				layout: 'anchor',
				defaultType: 'combobox',
				items:[{
					xtype:'textfield',
					fieldLabel:'No. Face Attd',
					width:300,
					labelWidth:125,
					id:'tf_fa',
					value:'',
					readOnly:true,
					fieldStyle:'background:#B8B8B8 ;font-weight:bold;'
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
				columnWidth:.8,
				border:false,
				layout: 'anchor',
				defaultType: 'combobox',
				items:[{
					xtype:'textfield',
					fieldLabel:'Departement',
					width:500,
					labelWidth:125,
					id:'tf_dept',
					value:'',
					readOnly:true,
					fieldStyle:'background:#B8B8B8 ;font-weight:bold;'
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
				columnWidth:.8,
				border:false,
				layout: 'anchor',
				defaultType: 'combobox',
				items:[{
					xtype:'textfield',
					fieldLabel:'Jabatan',
					width:500,
					labelWidth:125,
					id:'tf_jab',
					value:'',
					readOnly:true,
					fieldStyle:'background:#B8B8B8 ;font-weight:bold;'
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
				columnWidth:.8,
				border:false,
				layout: 'anchor',
				defaultType: 'combobox',
				items:[{
					xtype:'textfield',
					fieldLabel:'Location / Plant',
					width:300,
					labelWidth:125,
					id:'tf_plant',
					value:'',
					readOnly:true,
					fieldStyle:'background:#B8B8B8 ;font-weight:bold;'
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
				columnWidth:.8,
				border:false,
				layout: 'anchor',
				defaultType: 'combobox',
				items:[{
					xtype:'textfield',
					fieldLabel:'Gaji Pokok',
					width:300,
					labelWidth:125,
					id:'tf_gp',
					value:'0',
					readOnly:true,
					fieldStyle:'background:#B8B8B8 ;font-weight:bold;text-align: right;'
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
				columnWidth:.8,
				border:false,
				layout: 'anchor',
				defaultType: 'combobox',
				items:[{
					xtype:'textfield',
					fieldLabel:'Tunjangan Jabatan',
					width:300,
					labelWidth:125,
					id:'tf_tj',
					value:'0',
					readOnly:true,
					fieldStyle:'background:#B8B8B8 ;font-weight:bold;text-align: right;'
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
				columnWidth:.8,
				border:false,
				layout: 'anchor',
				defaultType: 'combobox',
				items:[{
					xtype:'textfield',
					fieldLabel:'Tunjangan Lokasi',
					width:300,
					labelWidth:125,
					id:'tf_tl',
					value:'0',
					readOnly:true,
					fieldStyle:'background:#B8B8B8 ;font-weight:bold;text-align: right;'
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
				columnWidth:.8,
				border:false,
				layout: 'anchor',
				defaultType: 'combobox',
				items:[{
					xtype:'textfield',
					fieldLabel:'Tunjangan Grade',
					width:300,
					labelWidth:125,
					id:'tf_tg',
					value:'0',
					readOnly:true,
					fieldStyle:'background:#B8B8B8 ;font-weight:bold;text-align: right;'
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
				columnWidth:.8,
				border:false,
				layout: 'anchor',
				defaultType: 'combobox',
				items:[{
					xtype:'textfield',
					fieldLabel:'Uang Makan',
					width:300,
					labelWidth:125,
					id:'tf_um',
					value:'0',
					readOnly:true,
					fieldStyle:'background:#B8B8B8 ;font-weight:bold;text-align: right;'
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
				columnWidth:.8,
				border:false,
				layout: 'anchor',
				defaultType: 'combobox',
				items:[{
					xtype:'textfield',
					fieldLabel:'Uang Transport',
					width:300,
					labelWidth:125,
					id:'tf_ut',
					value:'0',
					readOnly:true,
					fieldStyle:'background:#B8B8B8 ;font-weight:bold;text-align: right;'
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
				columnWidth:.8,
				border:false,
				layout: 'anchor',
				defaultType: 'combobox',
				items:[{
					xtype:'textfield',
					fieldLabel:'Premi Hadir',
					width:300,
					labelWidth:125,
					id:'tf_ph',
					value:'0',
					readOnly:true,
					fieldStyle:'background:#B8B8B8 ;font-weight:bold;text-align: right;'
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
				columnWidth:.8,
				border:false,
				layout: 'anchor',
				defaultType: 'combobox',
				items:[{
					xtype:'textfield',
					fieldLabel:'PPH21',
					width:300,
					labelWidth:125,
					id:'tf_pph',
					value:'0',
					readOnly:true,
					fieldStyle:'background:#B8B8B8 ;font-weight:bold;text-align: right;'
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
				columnWidth:.8,
				border:false,
				layout: 'anchor',
				defaultType: 'combobox',
				items:[{
					xtype:'textfield',
					fieldLabel:'BPJS Kesehatan',
					width:300,
					labelWidth:125,
					id:'tf_bk',
					value:'0',
					readOnly:true,
					fieldStyle:'background:#B8B8B8 ;font-weight:bold;text-align: right;'
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
				columnWidth:.8,
				border:false,
				layout: 'anchor',
				defaultType: 'combobox',
				items:[{
					xtype:'textfield',
					fieldLabel:'BPJS TK',
					width:300,
					labelWidth:125,
					id:'tf_bt',
					value:'0',
					readOnly:true,
					fieldStyle:'background:#B8B8B8 ;font-weight:bold;text-align: right;'
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
				columnWidth:.8,
				border:false,
				layout: 'anchor',
				defaultType: 'combobox',
				items:[{
					xtype:'textfield',
					fieldLabel:'Pinjaman',
					width:300,
					labelWidth:125,
					id:'tf_pin',
					value:'0',
					readOnly:true,
					fieldStyle:'background:#B8B8B8 ;font-weight:bold;text-align: right;'
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
				columnWidth:.8,
				border:false,
				layout: 'anchor',
				defaultType: 'combobox',
				items:[{
					xtype:'textfield',
					fieldLabel:'BS',
					width:300,
					labelWidth:125,
					id:'tf_bs',
					value:'0',
					readOnly:true,
					fieldStyle:'background:#B8B8B8 ;font-weight:bold;text-align: right;'
				}]
			}]	
		},{
			xtype:'label',
			html:'&nbsp',
		},{
			xtype:'panel',
			bodyStyle: 'padding-left: 0px;padding-bottom: 25px;border:none',
			items:[{
				xtype:'button',
				text:'Clear',
				id:'btn_clear',
				width:100,
				handler:function(){
					Ext.getCmp('cb_karyawan').setValue("");
					Ext.getCmp('tf_nik').setValue("");
					Ext.getCmp('tf_fa').setValue("");
					Ext.getCmp('tf_dept').setValue("");
					Ext.getCmp('tf_jab').setValue("");
					Ext.getCmp('tf_plant').setValue("");
					Ext.getCmp('tf_gp').setValue(0);
					Ext.getCmp('tf_tj').setValue(0);
					Ext.getCmp('tf_tl').setValue(0);
					Ext.getCmp('tf_tg').setValue(0);
					Ext.getCmp('tf_um').setValue(0);
					Ext.getCmp('tf_ut').setValue(0);
					Ext.getCmp('tf_ph').setValue(0);
					Ext.getCmp('tf_pph').setValue(0);
					Ext.getCmp('tf_bk').setValue(0);
					Ext.getCmp('tf_bt').setValue(0);
					Ext.getCmp('tf_pin').setValue(0);
					Ext.getCmp('tf_bs').setValue(0);
				}
			}]
		},{
			xtype:'label',
			html:'&nbsp',
		},{
			xtype:'label',
			html:'&nbsp',
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