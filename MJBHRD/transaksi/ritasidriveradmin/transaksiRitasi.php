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
	function formatDate(value){
        return value ? Ext.Date.dateFormat(value, 'Y-m-d') : '';
        //return value;
    }
	Ext.clearForm=function(){
		Ext.getCmp('tf_hdid').setValue('')
		Ext.getCmp('tf_typeform').setValue('tambah')
		// Ext.getCmp('tf_tgl_from').setValue(currentTime)
		// Ext.getCmp('tf_tgl_to').setValue(currentTime)
		Ext.getCmp('cb_nama').setValue('')
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
			url:'<?PHP echo PATHAPP ?>/combobox/combobox_plant_ritasi.php', 
			reader: {
				type: 'json',
				root: 'data', 
				totalProperty:'total'   
			},
		}
	});
	var comboPlantRitasiDriver = Ext.create('Ext.data.Store', {
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
	
	//var sm = Ext.create('Ext.selection.CheckboxModel');
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
				// alert(col);
				if (col==29) {
					var userID=grid_detil.getSelectionModel().getSelection()[0].get('DATA_ID_DETAIL');
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
			dataIndex:'DATA_LHO',
			header:'LHO',
			width:100,
			hidden:true
		},{
			dataIndex:'DATA_KETERANGAN',
			header:'Keterangan',
			width:100,
			hidden:true
		},{
			dataIndex:'DATA_KM_AWAL',
			header:'KM Awal',
			width:100,
			hidden:true
		},{
			dataIndex:'DATA_KM_AKHIR',
			header:'KM Akhir',
			width:100,
			hidden:true
		},{
			dataIndex:'DATA_SOLAR',
			header:'Solar',
			width:100,
			hidden:true
		},{
			dataIndex:'DATA_VARIABEL',
			header:'Variabel',
			width:100,
			hidden:true
		},{
			dataIndex:'DATA_TIPE',
			header:'Tipe',
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
			width:140
		},{
			dataIndex:'DATA_PERIOD_AKHIR',
			header:'Periode Akhir',
			width:140
		},{
			dataIndex:'DATA_NAMA_QC',
			header:'Driver Name',
			width:400
		}],
		listeners: {
			dblclick: {
				element: 'body', //bind to the underlying body property on the panel
				fn: function(){ 
					var hdid=grid_popupheader.getSelectionModel().getSelection()[0].get('HD_ID');
					Ext.getCmp('tf_hdid').setValue(hdid);
					Ext.getCmp('cb_nama').setDisabled(true);
					// Ext.getCmp('tf_tgl_from').setValue(grid_popupheader.getSelectionModel().getSelection()[0].get('DATA_PERIOD_AWAL'));
					// Ext.getCmp('tf_tgl_to').setValue(grid_popupheader.getSelectionModel().getSelection()[0].get('DATA_PERIOD_AKHIR'));
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
					//checked   : false,
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
			store:comboPlantRitasiDriver,
			displayField: 'DATA_NAME',
			valueField: 'DATA_VALUE',
			//maxLengthText:5,
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
							cb1:Ext.getCmp('cb_filter1').getValue(),
							cb2:Ext.getCmp('cb_filter2').getValue(),
							cb3:Ext.getCmp('cb_filter3').getValue(),
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
		title: 'Cari No Surat Jalan',
		width: 1100,
		height: 450,							
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
			width:200,
			labelWidth:50,
			id:'cb_filter_pop',
			value:'',
			emptyText : '- Pilih -',
			//editable: false 
			enableKeyEvents : true,
			minChars:1,
		}, {
			xtype:'textfield',
			fieldLabel:'No LHO',
			width:125,
			// maxLengthText:5,
			id:'tf_lho_pop',
			labelWidth:50,
			listeners: {
				 change: function(field,newValue,oldValue){
						field.setValue(newValue.toUpperCase());
				}
			}
		}, {
			xtype:'textfield',
			fieldLabel:'Cari',
			width:275,
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
				if(Ext.getCmp('tf_filter_from_pop').getValue()<=Ext.getCmp('tf_filter_to_pop').getValue()){
					maskgrid = new Ext.LoadMask(Ext.getCmp('grid_popuptransaksi_id'), {msg: "Memuat . . ."});
					maskgrid.show();
					store_popuptransaksi.load({
						params:{
							hdid:Ext.getCmp('tf_hdid').getValue(),
							tffilter:Ext.getCmp('cb_filter_pop').getValue(),
							tglfrom:Ext.getCmp('tf_filter_from_pop').getRawValue(),
							tglto:Ext.getCmp('tf_filter_to_pop').getRawValue(),
							tfcari:Ext.getCmp('tf_cari_pop').getValue(),
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
				Ext.getCmp('tf_lho_pop').setValue('');
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
				Ext.each(grid_popuptransaksi.getSelectionModel().getSelection(), function(record, index, allRecords) {
				  store_detil.add({'HD_ID':record.get('HD_ID'),'DATA_SJ':record.get('DATA_SJ'),'DATA_TGL':record.get('DATA_TGL'),'DATA_JAM':record.get('DATA_JAM')
								,'DATA_PLANT':record.get('DATA_PLANT'),'DATA_CUST':record.get('DATA_CUST'),'DATA_PROYEK':record.get('DATA_PROYEK'),'DATA_VOL':record.get('DATA_VOL')
								,'DATA_VOL_RETUR':record.get('DATA_VOL_RETUR'),'DATA_SOPIR':record.get('DATA_SOPIR'),'DATA_TRUK':record.get('DATA_TRUK')
								,'DATA_PLANT_CODE':record.get('DATA_PLANT_CODE'),'DATA_NOMINAL':record.get('DATA_NOMINAL'),'DATA_TOTAL':record.get('DATA_TOTAL')
								,'DATA_NOMINAL_ID':record.get('DATA_NOMINAL_ID'),'DATA_GROUP_ID':record.get('DATA_GROUP_ID'),'DATA_NOTE':record.get('DATA_NOTE')
								,'DATA_KM_AWAL':record.get('DATA_KM_AWAL'),'DATA_KM_AKHIR':record.get('DATA_KM_AKHIR'),'DATA_SOLAR':record.get('DATA_SOLAR')
								,'DATA_VARIABEL':record.get('DATA_VARIABEL'),'DATA_TIPE':record.get('DATA_TIPE')
								,'DATA_LHO':Ext.getCmp('tf_lho_pop').getValue(),'DATA_ID_DETAIL':record.get('DATA_ID_DETAIL')
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
				html:'<div align="center"><font size="5"><b>Ritasi Driver Admin</b></font></div>',
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
					columnWidth:.47,
					border:false,
					layout: 'anchor',
					defaultType: 'combobox',
					items:[{		
						xtype:'combobox',
						fieldLabel:'Driver',
						store: comboDriver,
						displayField: 'DATA_NAME',
						valueField: 'DATA_VALUE',
						width:400,
						labelWidth:50,
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
							//Ext.getCmp('tf_filter_from').setValue(currentTime);
							//Ext.getCmp('tf_filter_to').setValue(currentTime);
							Ext.getCmp('cb_filter1').setValue('true');
							//Ext.getCmp('cb_filter2').setValue('true');
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
					// if(Ext.getCmp('cb_nama').getValue() != ''){
						Ext.getCmp('cb_filter_pop').setValue('');
						Ext.getCmp('tf_filter_from_pop').setValue(currentTime);
						Ext.getCmp('tf_filter_to_pop').setValue(currentTime);
						Ext.getCmp('cb_filter1_pop').setValue('true');
						Ext.getCmp('cb_filter2_pop').setValue('true');
						Ext.getCmp('tf_lho_pop').setValue('');
						store_popuptransaksi.removeAll();
						PopupSJ.show();
					// } else {
						// alertDialog('Peringatan','Harap untuk memilih Driver lebih dahulu.');
					// }
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
						if(Ext.getCmp('cb_nama').getValue()!=''){
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
																// tgl_awal:Ext.getCmp('tf_periode').getValue().substring(0, 10),
																// tgl_akhir:Ext.getCmp('tf_periode').getValue().substring(13, 23),
																nama:Ext.getCmp('cb_nama').getValue(),
																kumpulanID:kumpulanID,
																arrID:Ext.encode(arrID),
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
							alertDialog('Peringatan','Nama Driver belum diisi.');
						}
						<?PHP } else {
								header("location: " . PATHAPP . "/index.php");
							} ?>
					}
				},{
					xtype:'label',
					html:'&nbsp',
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