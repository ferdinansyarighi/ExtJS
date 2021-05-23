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
  $vCount = 0;
  if($user_id!=""){
		  $queryHariIjin = "SELECT COUNT(-1)
				FROM APPS.PER_PEOPLE_F PPF
				INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON PPF.PERSON_ID = PAF.PERSON_ID
				INNER JOIN APPS.PER_JOBS PJ ON PJ.JOB_ID = PAF.JOB_ID
				WHERE PPF.EFFECTIVE_END_DATE > SYSDATE
				AND PAF.EFFECTIVE_END_DATE > SYSDATE
				AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y'
				AND PPF.PERSON_ID =$emp_id
				AND (PJ.NAME LIKE '%HRD%' OR PJ.NAME LIKE '%HRG%')
			";
			// echo $queryHariIjin;
			$resultHariIjin = oci_parse($con,$queryHariIjin);
			oci_execute($resultHariIjin);
			while($rowHariIjin = oci_fetch_row($resultHariIjin))
			{
				$vCount = $rowHariIjin[0]; 
			}
			
			$queryAdmin = "SELECT count(-1) FROM MJ.MJ_M_USER mmu
				inner join mj.mj_sys_user_rule ur on mmu.id = ur.id_user
				inner join  mj.mj_sys_rule sr on ur.id_rule = sr.id_rule
				inner join mj.mj_sys_app sa on sr.app_id = sa.app_id
				WHERE sa.app_id = 1 and sr.id_rule = 1
				and mmu.id = $user_id
			";
			// echo $queryHariIjin;
			$resultAdmin = oci_parse($con,$queryAdmin);
			oci_execute($resultAdmin);
			while($rowAdmin = oci_fetch_row($resultAdmin))
			{
				$vCountAdmin = $rowAdmin[0]; 
			}
  
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
		var recordIndex = store_detil.find('DATA_ID_DETAIL', userID);
		//console.log("RowIndex" + recordIndex);
		if (recordIndex > -1) {
			return store_detil.getAt(recordIndex);
		} else {
			return null;
		}
	}
	var comboRitasiKe = Ext.create('Ext.data.Store', {
		fields: ['DATA_NAME', 'DATA_VALUE'],
		data : [
			{"DATA_NAME":"1", "DATA_VALUE":"1"},
			{"DATA_NAME":"2", "DATA_VALUE":"2"},
			{"DATA_NAME":"3", "DATA_VALUE":"3"},
			{"DATA_NAME":"4", "DATA_VALUE":"4"},
			{"DATA_NAME":"5", "DATA_VALUE":"5"},
			{"DATA_NAME":"6", "DATA_VALUE":"6"},
			{"DATA_NAME":"7", "DATA_VALUE":"7"},
			{"DATA_NAME":"8", "DATA_VALUE":"8"},
			{"DATA_NAME":"9", "DATA_VALUE":"9"},
			{"DATA_NAME":"10", "DATA_VALUE":"10"},
			{"DATA_NAME":"11", "DATA_VALUE":"11"},
			{"DATA_NAME":"12", "DATA_VALUE":"12"},
			{"DATA_NAME":"13", "DATA_VALUE":"13"},
			{"DATA_NAME":"14", "DATA_VALUE":"14"},
			{"DATA_NAME":"15", "DATA_VALUE":"15"},
			{"DATA_NAME":"16", "DATA_VALUE":"16"},
			{"DATA_NAME":"17", "DATA_VALUE":"17"},
			{"DATA_NAME":"18", "DATA_VALUE":"18"},
			{"DATA_NAME":"19", "DATA_VALUE":"19"},
			{"DATA_NAME":"20", "DATA_VALUE":"20"},
		]
	});
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
		Ext.getCmp('tf_periode').setDisabled(false);
		Ext.getCmp('cb_nama').setDisabled(false);
		comboDriver.load();
		store_detil.removeAll()
	};
	
	function Total(xAwal, xAkhir, xSolar, xRitasi){
		sme= grid_detil.getSelectionModel().getSelection();							
		sma= grid_detil.store.indexOf(sme[0]);
		var xNoSJ=grid_detil.getStore().getAt(sma).get('DATA_SJ');
		
		if(xNoSJ != 'STANDBY'){
			var xTipe=grid_detil.getStore().getAt(sma).get('DATA_TIPE');
			var xNominal=grid_detil.getStore().getAt(sma).get('DATA_NOMINAL');
			var xNominalID=grid_detil.getStore().getAt(sma).get('DATA_NOMINAL_ID');
			var xGroup=grid_detil.getStore().getAt(sma).get('DATA_GROUP_ID');
			var xTgl=grid_detil.getStore().getAt(sma).get('DATA_TGL');
			var xVariabel = 0;
			var xTotal = 0;
			var xJarak = xAkhir - xAwal;
			// console.log(sm5);
			if(xAkhir != 0){
				if (xTipe == 'BP'){
					if(xSolar != 0){
						if(xRitasi != ''){
							Ext.Ajax.request({
								url:'<?php echo 'isi_nominal_ritasi.php'; ?>',
									params:{
										ritasi:xRitasi,
										group:xGroup,
										tglSj:xTgl,
									},
									success:function(response){
										var json=Ext.decode(response.responseText);
										var xNominalID = json.results;
										var xNominal = json.rows;
										xVariabel = (xJarak) / xSolar;
										xVariabel = Math.round(xVariabel * 100) / 100;
										if (xVariabel >= 3.5){
											xVariabel = 3.5;
										}
										xTotal = xVariabel * xNominal;
										xTotal = Math.round(xTotal * 100) / 100;
										if(xTotal <= 4000){
											xTotal = 4000;
										}
										var sm11=grid_detil.getStore().getAt(sma).set('DATA_VARIABEL', xVariabel);
										var sm12=grid_detil.getStore().getAt(sma).set('DATA_NOMINAL_ID', xNominalID);
										var sm13=grid_detil.getStore().getAt(sma).set('DATA_NOMINAL', xNominal);
										var sm14=grid_detil.getStore().getAt(sma).set('DATA_TOTAL', xTotal);
									},
								method:'POST',
							});
						}
					}
				} else {
					Ext.Ajax.request({
						url:'<?php echo 'isi_nominal_jarak.php'; ?>',
							params:{
								jarak:xJarak,
								group:xGroup,
								tglSj:xTgl,
							},
							success:function(response){
								var json=Ext.decode(response.responseText);
								var vnominalID = json.results;
								var vnominal = json.rows;
								// var deskripsisearch = deskripsiasl
								var sm11=grid_detil.getStore().getAt(sma).set('DATA_NOMINAL_ID', vnominalID);
								var sm12=grid_detil.getStore().getAt(sma).set('DATA_NOMINAL', vnominal);
								var sm13=grid_detil.getStore().getAt(sma).set('DATA_TOTAL', vnominal);
							},
						method:'POST',
					});
				}
			}
		}
	}	
	
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
	var comboPeriodePop = Ext.create('Ext.data.Store', {
		fields: [{
			name:'DATA_VALUE'
		},{
			name:'DATA_NAME'
		}],
		proxy:{
			type:'ajax',
			url:'<?PHP echo PATHAPP ?>/combobox/combobox_perioderitasipop.php', 
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
			url:'<?PHP echo PATHAPP ?>/combobox/combobox_plant_ritasidriver.php', 
			reader: {
				type: 'json',
				root: 'data', 
				totalProperty:'total'   
			},
		}
	});
	var comboDriver = Ext.create('Ext.data.Store', {
		fields: [{
			name:'DATA_VALUE'
		},{
			name:'DATA_NAME'
		}],
		proxy:{
			type:'ajax',
			url:'<?PHP echo PATHAPP ?>/combobox/combobox_namadriver.php', 
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
			name:'DATA_ID_DETAIL'
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
			name:'DATA_TIPE'
		},{
			name:'DATA_LHO'
		},{
			name:'DATA_KETERANGAN'
		},{
			name:'DATA_KM_AWAL'
		},{
			name:'DATA_KM_AKHIR'
		},{
			name:'DATA_SOLAR'
		},{
			name:'DATA_VARIABEL'
		},{
			name:'DATA_RITASI_KE'
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
	
	var sm = Ext.create('Ext.selection.CheckboxModel', {
        checkOnly: true,
    });
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
			dataIndex:'DATA_ID_DETAIL',
			header:'id detail',
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
			dataIndex:'DATA_TIPE',
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
			width:75,
            field: {
				xtype:'textfield',
				id:'tf_lho',
            }
        }, {
            header: 'Keterangan HRD',
            dataIndex: 'DATA_KETERANGAN',
			width:95,
            field: {
				xtype:'textfield',
				id:'tf_ket',
            }
        }, {
            header: 'KM Awal',
            dataIndex: 'DATA_KM_AWAL',
			width:100,
            field: {
				xtype:'numberfield',
				id:'tf_awal',
				listeners: {
					change:function(editor, e, eOpts){
						// console.log(e);
						sme= grid_detil.getSelectionModel().getSelection();							
						sma= grid_detil.store.indexOf(sme[0]);
						var xAwal=e;
						var xAkhir=grid_detil.getStore().getAt(sma).get('DATA_KM_AKHIR');
						var xSolar=grid_detil.getStore().getAt(sma).get('DATA_SOLAR');
						var xRitasi=grid_detil.getStore().getAt(sma).get('DATA_RITASI_KE');
						Total(xAwal, xAkhir, xSolar, xRitasi);
					}
				}
            }
        }, {
            header: 'KM Akhir',
            dataIndex: 'DATA_KM_AKHIR',
			width:100,
            field: {
				xtype:'numberfield',
				id:'tf_akhir',
				listeners: {
					change:function(editor, e, eOpts){
						sme= grid_detil.getSelectionModel().getSelection();							
						sma= grid_detil.store.indexOf(sme[0]);
						var xAwal=grid_detil.getStore().getAt(sma).get('DATA_KM_AWAL');
						var xAkhir=e;
						var xSolar=grid_detil.getStore().getAt(sma).get('DATA_SOLAR');
						var xRitasi=grid_detil.getStore().getAt(sma).get('DATA_RITASI_KE');
						Total(xAwal, xAkhir, xSolar, xRitasi);
					}
				}
            }
        }, {
            header: 'Solar',
            dataIndex: 'DATA_SOLAR',
			width:50,
            field: {
				xtype:'numberfield',
				id:'tf_solar',
				listeners: {
					change:function(editor, e, eOpts){
						sme= grid_detil.getSelectionModel().getSelection();							
						sma= grid_detil.store.indexOf(sme[0]);
						var xAwal=grid_detil.getStore().getAt(sma).get('DATA_KM_AWAL');
						var xAkhir=grid_detil.getStore().getAt(sma).get('DATA_KM_AKHIR');
						var xSolar=e;
						var xRitasi=grid_detil.getStore().getAt(sma).get('DATA_RITASI_KE');
						Total(xAwal, xAkhir, xSolar, xRitasi);
					}
				}
            }
        }, {
            header: 'Variabel',
            dataIndex: 'DATA_VARIABEL',
			width:75,
            field: {
				xtype:'textfield',
				id:'tf_variabel',
				readOnly: true,
            }
        }, {
            header: 'Ritasi Ke',
            dataIndex: 'DATA_RITASI_KE',
			width:50,
            field: {
				xtype:'combobox',
				id:'cb_ritasike',
                typeAhead: true,
                triggerAction: 'all',
                selectOnTab: true,
                store:comboRitasiKe,
				displayField: 'DATA_NAME',
				valueField: 'DATA_NAME',
                lazyRender: true,
                listClass: 'x-combo-list-small',
				editable: false,
				enableKeyEvents : true,
				minChars:1,
				listeners: {
					select:function(f,r,i){
						sme= grid_detil.getSelectionModel().getSelection();							
						sma= grid_detil.store.indexOf(sme[0]);
						var xAwal=grid_detil.getStore().getAt(sma).get('DATA_KM_AWAL');
						var xAkhir=grid_detil.getStore().getAt(sma).get('DATA_KM_AKHIR');
						var xSolar=grid_detil.getStore().getAt(sma).get('DATA_SOLAR');
						var xRitasi=r[0].data.DATA_VALUE;
						if (xAkhir >= xAwal){
							Total(xAwal, xAkhir, xSolar, xRitasi);
						} else {
							alertDialog('Peringatan','KM Akhir tidak boleh lebih kecil dari KM Awal.');
						}
					}
				}
            }
        }, {
            header: 'Nominal',
            dataIndex: 'DATA_NOMINAL',
			align: 'right',
			width:100,
            field: {
				xtype:'textfield',
				id:'tf_nominal',
				fieldStyle: 'text-align: right;',
				readOnly: true,
            }
        }, {
            header: 'Total',
            dataIndex: 'DATA_TOTAL',
			align: 'right',
			width:100,
            field: {
				xtype:'textfield',
				id:'tf_total',
				fieldStyle: 'text-align: right;',
				readOnly: true,
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
				//alert(col);
				if (col==29) {
					var userID=grid_detil.getSelectionModel().getSelection()[0].get('DATA_ID_DETAIL');
					//alert (userID);
					var userRecord = GetUserRecord(userID);
					//console.log(userRecord);
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
			name:'DATA_LHO'
		},{
			name:'DATA_DRIVER'
		},{
			name:'DATA_TOTAL_SJ'
		},{
			name:'DATA_DETIL'
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
		selModel:sm,
        loadMask: true,
		columns:[{
			xtype:'rownumberer',
			id:'row_idxx',
			header:'No',
			width:50
		},{
			dataIndex:'HD_ID',
			header:'id',
			width:100,
			hidden:true
		},{
			dataIndex:'DATA_LHO',
			header:'No LHO',
			width:200,
			//hidden:true
		},{
			dataIndex:'DATA_TOTAL_SJ',
			header:'Total SJ',
			width:100,
			//hidden:true
		},{
			dataIndex:'DATA_DRIVER',
			header:'Driver',
			width:300,
			hidden:true
		},{
			dataIndex:'DATA_DETIL',
			header:'No LHO2',
			width:200,
			hidden:true
		}],
		// listeners: {
			// dblclick: {
				// element: 'body', //bind to the underlying body property on the panel
				// fn: function(){ 
					// var hdid=grid_popuptransaksi.getSelectionModel().getSelection()[0].get('HD_ID');
					// var driver=grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_DRIVER');
					// Ext.getCmp('cb_nama').setValue(driver);
					// Ext.getCmp('cb_nama').setDisabled(true);
					// store_detil.load({
						// params:{
							// hd_id:hdid,
						// }
					// });
					// PopupSJ.hide();
				// }
			// }
		// }
		//multiSelect:true,
	});
	var store_searchSJ=new Ext.data.JsonStore({
		id:'store_searchSJ_id',
		pageSize: 100,
		fields:[{
			name:'DATA_NOSJ'
		},{
			name:'DATA_NAMA'
		},{
			name:'DATA_NOTE'
		},{
			name:'DATA_LHO'
		},{
			name:'DATA_TGL'
		},{
			name:'DATA_STATUS'
		},{
			name:'DATA_PERIODE'
		}],
		 proxy:{
			type:'ajax',
			url:'isi_grid_searchSJ.php', 
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
	var grid_searchSJ=Ext.create('Ext.grid.Panel',{
		id:'grid_searchSJ_id',
		region:'center',
		store:store_searchSJ,
        columnLines: true,
        loadMask: true,
		columns:[{
			xtype:'rownumberer',
			id:'row_id',
			header:'No',
			width:50
		},{
			dataIndex:'DATA_PERIODE',
			header:'Periode',
			width:150,
		},{
			dataIndex:'DATA_NOSJ',
			header:'No SJ',
			width:150,
		},{
			dataIndex:'DATA_NAMA',
			header:'Nama Driver',
			width:250
		},{
			dataIndex:'DATA_NOTE',
			header:'Note',
			width:100
		},{
			dataIndex:'DATA_LHO',
			header:'LHO',
			width:100
		},{
			dataIndex:'DATA_STATUS',
			header:'Status',
			width:100
		}],
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
			width:240
		},{
			dataIndex:'DATA_PERIOD_AKHIR',
			header:'Periode Akhir',
			width:240
		},{
			dataIndex:'DATA_NAMA_QC',
			header:'Driver Name',
			width:500
		}],
		listeners: {
			dblclick: {
				element: 'body', //bind to the underlying body property on the panel
				fn: function(){ 
					var hdid=grid_popupheader.getSelectionModel().getSelection()[0].get('HD_ID');
					Ext.getCmp('tf_hdid').setValue(hdid);
					Ext.getCmp('tf_periode').setDisabled(true);
					Ext.getCmp('cb_nama').setDisabled(true);
					// Ext.getCmp('tf_tgl_from').setValue(grid_popupheader.getSelectionModel().getSelection()[0].get('DATA_PERIOD_AWAL'));
					// Ext.getCmp('tf_tgl_to').setValue(grid_popupheader.getSelectionModel().getSelection()[0].get('DATA_PERIOD_AKHIR'));
					Ext.getCmp('tf_periode').setValue(grid_popupheader.getSelectionModel().getSelection()[0].get('DATA_PERIOD_AWAL') + ' - ' + grid_popupheader.getSelectionModel().getSelection()[0].get('DATA_PERIOD_AKHIR'));
					comboDriver.load();
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
		title: 'Cari Transaksi Ritasi Driver',
		width: 1000,
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
			fieldLabel:'Driver Name',
			maxLengthText:5,
			id:'tf_filter',
			width:170,
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
					checked   : false,
				}
			]
		}, {
			xtype: 'datefield',
			name: 'date1',
			fieldLabel: 'Tgl Pembuatan',
			width:170,
			labelWidth:80,
			id:'tf_filter_from'
		}, {
			xtype: 'datefield',
			name: 'date1',
			fieldLabel: 'to',
			width:110,
			labelWidth:20,
			id:'tf_filter_to'
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
					id        : 'cb_filter3',
					checked   : false,
				}
			]
		}, {
			xtype:'combobox',
			fieldLabel:'Plant',
			//maxLengthText:5,
			store:comboPlantRitasi,
			displayField: 'DATA_NAME',
			valueField: 'DATA_VALUE',
			id:'cb_plant_existing',
			width:170,
			labelWidth:35,
			minChars:3,
			// listeners: {
				 // change: function(field,newValue,oldValue){
						// field.setValue(newValue.toUpperCase());
				// }
			// }
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
					id        : 'cb_filter4',
					checked   : false,
				}
			]
		}, {
			xtype: 'combobox',
			//name: 'date1',
			fieldLabel: 'Periode',
			width:215,
			labelWidth:40,
			id:'cb_periode',
			store:comboPeriode,
			displayField: 'DATA_NAME',
			valueField: 'DATA_VALUE',
			editable: false,
			value:'- Pilih -',
			enableKeyEvents : true,
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
							plant:Ext.getCmp('cb_plant_existing').getValue(),
							tglfrom:Ext.getCmp('tf_filter_from').getRawValue(),
							tglto:Ext.getCmp('tf_filter_to').getRawValue(),
							periode:Ext.getCmp('cb_periode').getValue(),
							cb1:Ext.getCmp('cb_filter1').getValue(),
							cb2:Ext.getCmp('cb_filter2').getValue(),
							cb3:Ext.getCmp('cb_filter3').getValue(),
							cb4:Ext.getCmp('cb_filter4').getValue(),
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
	var PopupSearchSJ=Ext.create('Ext.Window', {
		title: 'Cari No Surat Jalan',
		width: 800,
		height: 450,							
		layout: 'fit',
		closeAction:'hide',
		tbar:[,{
			xtype:'label',
			html:'&nbsp',
		}, {		
			xtype:'combobox',
			fieldLabel:'Periode',
			store: comboPeriodePop,
			displayField: 'DATA_NAME',
			valueField: 'DATA_VALUE',
			width:230,
			labelWidth:50,
			id:'cb_filter_s',
			value:'',
			emptyText : '- Pilih -',
			//editable: false 
			enableKeyEvents : true,
			minChars:1,
		}, {
			xtype:'label',
			html:'&nbsp',
		}, {
			xtype:'textfield',
			fieldLabel:'No SJ',
			width:200,
			// maxLengthText:5,
			id:'tf_cari_s',
			labelWidth:45,
			listeners: {
				 change: function(field,newValue,oldValue){
						field.setValue(newValue.toUpperCase());
				}
			}
		}, {
			xtype:'label',
			html:'&nbsp',
		}, {
			xtype:'combobox',
			fieldLabel:'Plant',
			width:220,
			// maxLengthText:5,
			id:'cb_plant_search',
			store: comboPlantRitasi,
			displayField: 'DATA_NAME',
			valueField: 'DATA_VALUE',
			value:'',
			labelWidth:45,
			listeners: {
				 change: function(field,newValue,oldValue){
						field.setValue(newValue.toUpperCase());
				}
			}
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
				maskgrid = new Ext.LoadMask(Ext.getCmp('grid_searchSJ_id'), {msg: "Memuat . . ."});
				maskgrid.show();
				store_searchSJ.load({
					params:{
						tffilter_awal:Ext.getCmp('cb_filter_s').getValue().substring(0, 10),
						tffilter_akhir:Ext.getCmp('cb_filter_s').getValue().substring(13, 23),
						tfcari:Ext.getCmp('tf_cari_s').getValue(),
						cbplant:Ext.getCmp('cb_plant_search').getValue(),
					}
				});
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
				Ext.getCmp('cb_filter_s').setValue('');
				Ext.getCmp('tf_cari_s').setValue('');
				Ext.getCmp('cb_plant_search').setValue('');
				store_searchSJ.removeAll();
			}
		}],
		items: grid_searchSJ
	});
	var PopupSJ=Ext.create('Ext.Window', {
		title: 'Cari No LHO',
		width: 380,
		height: 450,							
		layout: 'fit',
		closeAction:'hide',
		tbar:[,{
			xtype:'label',
			html:'&nbsp',
		}, {
			xtype:'textfield',
			fieldLabel:'Cari',
			width:210,
			// maxLengthText:5,
			id:'tf_cari_pop',
			labelWidth:35,
			listeners: {
				 change: function(field,newValue,oldValue){
						field.setValue(newValue.toUpperCase());
				}
			}
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
				maskgrid = new Ext.LoadMask(Ext.getCmp('grid_popuptransaksi_id'), {msg: "Memuat . . ."});
				maskgrid.show();
				// var a = 0;
				// var kumpulanLHO = '';
				// store_detil.each(
					// function(record)  {
						// LHO = record.get('DATA_LHO');
						// if(LHO == null){
							// LHO = 0;
						// }
						// if(a == 0){
							// kumpulanLHO = kumpulanLHO + LHO;
						// } else {
							// kumpulanLHO = kumpulanLHO + ", " + LHO;
						// }
						// a++;
					// }
				// ); 
				//console.log(kumpulanLHO);
				store_popuptransaksi.load({
					params:{
						//hdid:Ext.getCmp('tf_hdid').getValue(),
						tfcari:Ext.getCmp('tf_cari_pop').getValue(),
						id_driver:Ext.getCmp('cb_nama').getValue(),
						//kumpulanLho:kumpulanLHO,
					}
				});
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
				Ext.getCmp('tf_cari_pop').setValue('');
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
			// var hdid=grid_popuptransaksi.getSelectionModel().getSelection()[0].get('HD_ID');
			// var driver=grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_DRIVER');
			// Ext.getCmp('cb_nama').setValue(driver);
			// Ext.getCmp('cb_nama').setDisabled(true);
			// var data=sm.getSelection();
			// //var z = 0;
			// //var kumpulanID = "";
			// var arrTransID = new Array();
			// for(var i in data) {
				// arrTransID[i]=data[i].get('DATA_LHO');
			// }
			// store_detil.load({
				// params:{
					// hd_id:hdid,
					// lho:Ext.encode(arrTransID)
				// }
			// });
			Ext.each(grid_popuptransaksi.getSelectionModel().getSelection(), function(record, index, allRecords) {
				// var array = $.map(record.get('DATA_DETIL'), function(value, index) {
					// return [value];
				// });
				var array = Object.keys(record.get('DATA_DETIL')).map(function (key) { return record.get('DATA_DETIL')[key]; }); 
				//console.log(arr[0]['DATA_SJ']);
				//console.log(Object.keys(array).length);
				//Object.keys(a).length;
				for($x=0;$x<Object.keys(array).length;$x++){
				  store_detil.add({'HD_ID':record.get('HD_ID'),'DATA_SJ':array[$x]['DATA_SJ'],'DATA_TGL':array[$x]['DATA_TGL'],'DATA_JAM':array[$x]['DATA_JAM']
								,'DATA_PLANT':array[$x]['DATA_PLANT'],'DATA_CUST':array[$x]['DATA_CUST'],'DATA_PROYEK':array[$x]['DATA_PROYEK'],'DATA_VOL':array[$x]['DATA_VOL']
								,'DATA_VOL_RETUR':array[$x]['DATA_VOL_RETUR'],'DATA_SOPIR':array[$x]['DATA_SOPIR'],'DATA_TRUK':array[$x]['DATA_TRUK']
								,'DATA_PLANT_CODE':array[$x]['DATA_PLANT_CODE'],'DATA_NOMINAL':array[$x]['DATA_NOMINAL'],'DATA_TOTAL':array[$x]['DATA_TOTAL']
								,'DATA_NOMINAL_ID':array[$x]['DATA_NOMINAL_ID'],'DATA_GROUP_ID':array[$x]['DATA_GROUP_ID'],'DATA_NOTE':array[$x]['DATA_NOTE']
								,'DATA_KM_AWAL':array[$x]['DATA_KM_AWAL'],'DATA_KM_AKHIR':array[$x]['DATA_KM_AKHIR'],'DATA_SOLAR':array[$x]['DATA_SOLAR']
								,'DATA_VARIABEL':array[$x]['DATA_VARIABEL'],'DATA_TIPE':array[$x]['DATA_TIPE']
								,'DATA_LHO':array[$x]['DATA_LHO2'],'DATA_RITASI_KE':array[$x]['DATA_RITASI'],'DATA_ID_DETAIL':array[$x]['DATA_ID_DETAIL']
				   }); 
				}
			}); 
			//Ext.getCmp('tf_kumpulanLho').setValue(kumpulanLHO);
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
				html:'<div align="center"><font size="5"><b>Ritasi Driver</b></font></div>',
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
						fieldLabel:'Driver',
						store: comboDriver,
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
					columnWidth:.09,
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
				},{
					columnWidth:.2,
					border:false,
					layout: 'anchor',
					defaultType: 'button',
					items:[{
						xtype:'button',
						text:'Search SJ',
						width:75,
						handler:function(){
							Ext.getCmp('cb_filter_s').setValue('');
							Ext.getCmp('tf_cari_s').setValue('');
							store_searchSJ.removeAll();
							PopupSearchSJ.show();
						}
					}]
				}
				]	
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
					if(Ext.getCmp('cb_nama').getValue() != ''){
						Ext.getCmp('tf_cari_pop').setValue('');
						store_popuptransaksi.removeAll();
						PopupSJ.show();
					} else {
						alertDialog('Peringatan','Harap untuk memilih Driver lebih dahulu.');
					}
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
						<?PHP if($user_id!=""){ ?>
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
										idSJ = record.get('DATA_ID_DETAIL');
										ritKe = record.get('DATA_RITASI_KE');
										xTgl = record.get('DATA_TGL');
										xNoSJ = record.get('DATA_SJ');
										if(z == 0){
											kumpulanID = kumpulanID + idSJ;
										} else {
											kumpulanID = kumpulanID + ", " + idSJ;
										}
										z++;
										jumlah = 0;
										for(var x=0; x<isiGrid; x++){
											// console.log(store_detil.data.items[x].data['HD_ID']);
											if(xNoSJ != 'STANDBY'){
												if(record.get('DATA_TIPE') == 'BP'){
													if(idSJ == store_detil.data.items[x].data['HD_ID'] || (ritKe == store_detil.data.items[x].data['DATA_RITASI_KE'] && xTgl == store_detil.data.items[x].data['DATA_TGL'])){
														jumlah++;
													}
												}else{
													if(idSJ == store_detil.data.items[x].data['HD_ID']){
														jumlah++;
													}
												}
											}
										}
										// console.log("ID SJ : "+idSJ+" , Jumlah : "+jumlah);
										// console.log(store_detil.find('HD_ID', idSJ));
										// console.log(store_detil.find('HD_ID', 1));
										if(record.get('DATA_TIPE') == 'BP'){
											if((record.get('DATA_LHO')=='' || record.get('DATA_KM_AKHIR')==0 || record.get('DATA_SOLAR')==0 || parseFloat(record.get('DATA_KM_AKHIR')) < parseFloat(record.get('DATA_KM_AWAL'))) && xNoSJ != 'STANDBY'){
												countGrid++;
											}
										}else if(record.get('DATA_TIPE') == 'SP'){
											if((record.get('DATA_LHO')=='' || record.get('DATA_KM_AKHIR')==0 || parseFloat(record.get('DATA_KM_AKHIR')) < parseFloat(record.get('DATA_KM_AWAL'))) && xNoSJ != 'STANDBY'){
												countGrid++;
											}
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
											var arrIDdt = new Array();
											var arrSJ = new Array();
											var arrTglSJ = new Array();
											var arrLHO = new Array();
											var arrKet = new Array();
											var arrKMAwal = new Array();
											var arrKMAkhir = new Array();
											var arrSolar = new Array();
											var arrVariabel = new Array();
											var arrRitasi = new Array();
											var arrNominal = new Array();
											var arrNominalID = new Array();
											var arrGroupID = new Array();
											var arrTipe = new Array();
											store_detil.each(
												function(record)  {
													arrID[i]=record.get('HD_ID');
													arrIDdt[i]=record.get('DATA_ID_DETAIL');
													arrSJ[i]=record.get('DATA_SJ');
													arrTglSJ[i]=record.get('DATA_TGL');
													arrLHO[i]=record.get('DATA_LHO');
													arrKet[i]=record.get('DATA_KETERANGAN');
													arrKMAwal[i]=record.get('DATA_KM_AWAL');
													arrKMAkhir[i]=record.get('DATA_KM_AKHIR');
													arrSolar[i]=record.get('DATA_SOLAR');
													arrVariabel[i]=record.get('DATA_VARIABEL');
													arrRitasi[i]=record.get('DATA_RITASI_KE');
													arrNominal[i]=record.get('DATA_NOMINAL');
													arrNominalID[i]=record.get('DATA_NOMINAL_ID');
													arrGroupID[i]=record.get('DATA_GROUP_ID');
													arrTipe[i]=record.get('DATA_TIPE');
													i=i+1;
												}
											);
											Ext.Ajax.request({
												url:'<?php echo 'cek_role.php'; ?>',
												method:'POST',
												success:function(response){
													var json=Ext.decode(response.responseText);
													if (json.rows == "sukses"){
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
																arrIDdt:Ext.encode(arrIDdt),
																arrSJ:Ext.encode(arrSJ),
																arrTglSJ:Ext.encode(arrTglSJ),
																arrLHO:Ext.encode(arrLHO),
																arrKet:Ext.encode(arrKet),
																arrKMAwal:Ext.encode(arrKMAwal),
																arrKMAkhir:Ext.encode(arrKMAkhir),
																arrSolar:Ext.encode(arrSolar),
																arrVariabel:Ext.encode(arrVariabel),
																arrRitasi:Ext.encode(arrRitasi),
																arrNominal:Ext.encode(arrNominal),
																arrNominalID:Ext.encode(arrNominalID),
																arrGroupID:Ext.encode(arrGroupID),
																arrTipe:Ext.encode(arrTipe),
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
													} else {
														alertDialog('Kesalahan', json.rows);
														Ext.getCmp('btn_save').setDisabled(false);
													}
												},
												failure:function(result,action){
													alertDialog('Kesalahan','Data gagal disimpan');
													Ext.getCmp('btn_save').setDisabled(false);
												}
											});
										} else {
											alertDialog('Peringatan','Surat Jalan dan Ritasi Ke- tidak boleh sama.');
										}
									} else {
										alertDialog('Peringatan','Nomor LHO atau Solar atau KM Akhir belum diisi Atau KM Awal tidak boleh lebih besar dari KM Akhir.');
									}
								} else {
									alertDialog('Peringatan','Surat Jalan belum dipilih.');
								}
							// } else {
								// alertDialog('Peringatan','Active date awal tidak boleh lebih besar dari active date akhir.');
							// }
						}
						else {
							alertDialog('Peringatan','Periode atau Driver Name belum diisi.');
						}
						<?PHP } else {
								header("location: " . PATHAPP . "/index.php");
							} ?>
					}
				},{
					xtype:'label',
					html:'&nbsp',
				}
				<?PHP
				if($vCount != 0 || $vCountAdmin != 0){
					?>
					,{
						xtype:'button',
						text:'Submit',
						id:'btn_submit',
						width:50,
						handler:function(){
							// console.log(Ext.Date.dateFormat(Ext.getCmp('tf_tgl_from').getValue(), 'Ymd'));
							<?PHP if($user_id!=""){ ?>
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
											idSJ = record.get('DATA_ID_DETAIL');
											ritKe = record.get('DATA_RITASI_KE');
											xTgl = record.get('DATA_TGL');
											xNoSJ = record.get('DATA_SJ');
											if(z == 0){
												kumpulanID = kumpulanID + idSJ;
											} else {
												kumpulanID = kumpulanID + ", " + idSJ;
											}
											z++;
											jumlah = 0;
											for(var x=0; x<isiGrid; x++){
												// console.log(store_detil.data.items[x].data['HD_ID']);
												//backup
												// if(idSJ == store_detil.data.items[x].data['HD_ID'] || (ritKe == store_detil.data.items[x].data['DATA_RITASI_KE'] && xTgl == store_detil.data.items[x].data['DATA_TGL'])){
													// jumlah++;
												// }
												if(xNoSJ != 'STANDBY'){
													if(record.get('DATA_TIPE') == 'BP'){
														if(idSJ == store_detil.data.items[x].data['HD_ID'] || (ritKe == store_detil.data.items[x].data['DATA_RITASI_KE'] && xTgl == store_detil.data.items[x].data['DATA_TGL'])){
															jumlah++;
														}
													}else{
														if(idSJ == store_detil.data.items[x].data['HD_ID']){
															jumlah++;
														}
													}
												}
											}
											// console.log("ID SJ : "+idSJ+" , Jumlah : "+jumlah);
											// console.log(store_detil.find('HD_ID', idSJ));
											
											// console.log(store_detil.find('HD_ID', 1));
											if(record.get('DATA_TIPE') == 'BP'){
												if((record.get('DATA_LHO')=='' || record.get('DATA_KM_AKHIR')==0 || record.get('DATA_SOLAR')==0 || parseFloat(record.get('DATA_KM_AKHIR')) < parseFloat(record.get('DATA_KM_AWAL'))) && xNoSJ != 'STANDBY'){
													countGrid++;
												}
											}else if(record.get('DATA_TIPE') == 'SP'){
												if((record.get('DATA_LHO')=='' || record.get('DATA_KM_AKHIR')==0 || parseFloat(record.get('DATA_KM_AKHIR')) < parseFloat(record.get('DATA_KM_AWAL'))) && xNoSJ != 'STANDBY'){
													countGrid++;
												}
											}
											/* backup 4/1/18
											if((record.get('DATA_LHO')=='' || record.get('DATA_KM_AKHIR')==0 || record.get('DATA_SOLAR')==0 || parseFloat(record.get('DATA_KM_AKHIR')) < parseFloat(record.get('DATA_KM_AWAL'))) && xNoSJ != 'STANDBY'){
												 countGrid++;
											 }*/
											 
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
												var arrIDdt = new Array();
												var arrSJ = new Array();
												var arrTglSJ = new Array();
												var arrLHO = new Array();
												var arrKet = new Array();
												var arrKMAwal = new Array();
												var arrKMAkhir = new Array();
												var arrSolar = new Array();
												var arrVariabel = new Array();
												var arrRitasi = new Array();
												var arrNominal = new Array();
												var arrNominalID = new Array();
												var arrGroupID = new Array();
												var arrTipe = new Array();
												store_detil.each(
													function(record)  {
														arrID[i]=record.get('HD_ID');
														arrIDdt[i]=record.get('DATA_ID_DETAIL');
														arrSJ[i]=record.get('DATA_SJ');
														arrTglSJ[i]=record.get('DATA_TGL');
														arrLHO[i]=record.get('DATA_LHO');
														arrKet[i]=record.get('DATA_KETERANGAN');
														arrKMAwal[i]=record.get('DATA_KM_AWAL');
														arrKMAkhir[i]=record.get('DATA_KM_AKHIR');
														arrSolar[i]=record.get('DATA_SOLAR');
														arrVariabel[i]=record.get('DATA_VARIABEL');
														arrRitasi[i]=record.get('DATA_RITASI_KE');
														arrNominal[i]=record.get('DATA_NOMINAL');
														arrNominalID[i]=record.get('DATA_NOMINAL_ID');
														arrGroupID[i]=record.get('DATA_GROUP_ID');
														arrTipe[i]=record.get('DATA_TIPE');
														i=i+1;
													}
												);
												Ext.Ajax.request({
													url:'<?php echo 'cek_ritasi.php'; ?>',
													params:{
														nama:Ext.getCmp('cb_nama').getValue(),
														arrRitasi:Ext.encode(arrRitasi),
														arrTglSJ:Ext.encode(arrTglSJ),
													},
													method:'POST',
													success:function(response){
														var json=Ext.decode(response.responseText);
														if (json.rows == "sukses"){
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
																	arrIDdt:Ext.encode(arrIDdt),
																	arrSJ:Ext.encode(arrSJ),
																	arrTglSJ:Ext.encode(arrTglSJ),
																	arrLHO:Ext.encode(arrLHO),
																	arrKet:Ext.encode(arrKet),
																	arrKMAwal:Ext.encode(arrKMAwal),
																	arrKMAkhir:Ext.encode(arrKMAkhir),
																	arrSolar:Ext.encode(arrSolar),
																	arrVariabel:Ext.encode(arrVariabel),
																	arrRitasi:Ext.encode(arrRitasi),
																	arrNominal:Ext.encode(arrNominal),
																	arrNominalID:Ext.encode(arrNominalID),
																	arrGroupID:Ext.encode(arrGroupID),
																	arrTipe:Ext.encode(arrTipe),
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
														} else {
															alertDialog('Kesalahan', json.rows);
															Ext.getCmp('btn_submit').setDisabled(false);
														}
													},
													failure:function(result,action){
														alertDialog('Kesalahan','Data gagal disimpan');
														Ext.getCmp('btn_submit').setDisabled(false);
													}
												});
											} else {
												alertDialog('Peringatan','Surat Jalan tidak boleh sama.');
											}
										} else {
											alertDialog('Peringatan','Nomor LHO atau Solar atau KM Akhir belum diisi Atau KM Awal tidak boleh lebih besar dari KM Akhir.');
										}
									} else {
										alertDialog('Peringatan','Surat Jalan belum dipilih.');
									}
								// } else {
									// alertDialog('Peringatan','Active date awal tidak boleh lebih besar dari active date akhir.');
								// }
							}
							else {
								alertDialog('Peringatan','Periode atau Driver belum diisi.');
							}
							<?PHP } else {
									header("location: " . PATHAPP . "/index.php");
								} ?>
						}
					}
					<?PHP
				}
				?>
				,{
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