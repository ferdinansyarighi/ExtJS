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
	$dept_name = $_SESSION[APP]['dept_name'];
	$pos_name = $_SESSION[APP]['pos_name'];
	$grade = $_SESSION[APP]['grade'];
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
	var currentTimeNow = new Date();
	var month = currentTime.getMonth() + 1;
	console.log(month.length);
	if (month.toString().length == 1){
		month = "0"+month;
	}
	var day = currentTime.getDate();
	var year = currentTime.getFullYear();
	var tanggalskr = year + "-" + month + "-" + day;
	var rowGrid = 1;
	Number.prototype.formatMoney = function(c, d, t){
	var n = this, 
		c = isNaN(c = Math.abs(c)) ? 2 : c, 
		d = d == undefined ? "." : d, 
		t = t == undefined ? "," : t, 
		s = n < 0 ? "-" : "", 
		i = String(parseInt(n = Math.abs(Number(n) || 0).toFixed(c))), 
		j = (j = i.length) > 3 ? j % 3 : 0;
	   return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
	 };
	function formatDate(value){
        return value ? Ext.Date.dateFormat(value, 'Y-m-d') : '';
        //return value;
    }
	
	var store_upload=new Ext.data.JsonStore({
		id:'store_upload_id',
		pageSize: 50,
		fields:[{
			name:'HD_ID'
		},{
			name:'DATA_FILE'
		},{
			name:'DATA_ATTACHMENT'
		}],
		 proxy:{
			type:'ajax',
			url:'isi_grid_upload_view.php', 
			reader: {
				type: 'json',
				root: 'data',
				totalProperty:'total'        
			},
		}
	});
	var grid_upload=Ext.create('Ext.grid.Panel',{
		id:'grid_upload_id',
		region:'center',
		store:store_upload,
        columnLines: true,
		autoScroll:true,
		width:275,
		columns:[{
			dataIndex:'HD_ID',
			header:'id',
			width:100,
			hidden:true
		},{
			dataIndex:'DATA_ATTACHMENT',
			header:'Nama File',
			width:275,
			
		}]
	});
	var comboTipeExpense = Ext.create('Ext.data.Store', {
		fields: ['DATA_NAME', 'DATA_VALUE'],
		data : [
			{"DATA_NAME":"CLOSE", "DATA_VALUE":"CLOSE"},
			{"DATA_NAME":"POTONGAN", "DATA_VALUE":"POTONGAN"},
			{"DATA_NAME":"EXPENSE CLAIM", "DATA_VALUE":"EXPENSE CLAIM"},
		]
	});
	Ext.clearForm=function(){
		Ext.getCmp('tf_hdid').setValue('')
		Ext.getCmp('tf_status').setValue('')
		Ext.getCmp('tf_nb').setValue('')
		Ext.getCmp('cb_user').setDisabled(false)
		Ext.getCmp('cb_user').setValue('')
		Ext.getCmp('tf_emp_id').setValue('')
		Ext.getCmp('cb_pj').setValue('')
		Ext.getCmp('cb_tipe').setValue('')
		Ext.getCmp('cb_user_id').setValue('')
		Ext.getCmp('tf_perusahaan').setValue('')
		Ext.getCmp('tf_dept').setValue('')
		Ext.getCmp('tf_posisi').setValue('')
		Ext.getCmp('tf_grade').setValue('')
		Ext.getCmp('tf_plant').setValue('')
		// Ext.getCmp('tf_pinjaman').setValue('')
		//Ext.getCmp('tf_tp').setValue('')
		Ext.getCmp('tf_ket').setValue('')
		Ext.getCmp('tf_nominal').setValue(0)
		Ext.getCmp('tf_maxnominal').setValue(0)
		//Ext.getCmp('tf_jc').setValue(1)
		Ext.getCmp('cb_status').setValue(true);
		store_upload.removeAll();
		store_expense.removeAll();
		store_popuptransaksi.removeAll();
		Ext.getCmp('cb_tipe_pencairan').setValue('');
		Ext.getCmp('tf_no_rek').setValue('');
		Ext.getCmp('tf_tgl_pencairan').setValue('');
	};
	var cellEditing = Ext.create('Ext.grid.plugin.CellEditing', {
        clicksToEdit: 1
    });
	var store_popuptransaksi=new Ext.data.JsonStore({
		id:'store_popuptransaksi_id',
		pageSize: 100,
		fields:[{
			name:'HD_ID'
		},{
			name:'DATA_NO_BS'
		},{
			name:'DATA_ASSIGNMENT_ID'
		},{
			name:'DATA_PEMOHON'
		},{
			name:'DATA_PERSON_ID_PJ'
		},{
			name:'DATA_PJ'
		},{
			name:'DATA_NOMINAL'
		},{
			name:'DATA_KET'
		},{
			name:'DATA_AKTIF'
		},{
			name:'DATA_ID_UPL'
		},{
			name:'DATA_PERSON_ID'
		},{
			name:'DATA_TIPE'
		},{
			name:'DATA_TIPE_PENCAIRAN'
		},{
			name:'DATA_NO_REK'
		},{
			name:'DATA_TGL_PENCAIRAN'
		},{
			name:'DATA_STATUS'
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
			dataIndex:'DATA_STATUS',
			header:'Status',
			width:100,
			hidden:true
		},{
			dataIndex:'DATA_ASSIGNMENT_ID',
			header:'Pembuat',
			width:100,
			hidden:true
		},{
			dataIndex:'DATA_PERSON_ID_PJ',
			header:'Person id PJ',
			width:100,
			hidden:true
		},{
			dataIndex:'DATA_ID_UPL',
			header:'id Upload',
			width:100,
			hidden:true
		},{
			dataIndex:'DATA_PERSON_ID',
			header:'Person id Pemohon',
			width:100,
			hidden:true
		},{
			dataIndex:'DATA_NO_BS',
			header:'No BS',
			width:150,
			//hidden:true
		},{
			dataIndex:'DATA_PEMOHON',
			header:'Pemohon',
			width:150,
			//hidden:true
		},{
			dataIndex:'DATA_PJ',
			header:'Penanggung Jawab',
			width:150,
			//hidden:true
		},{
			dataIndex:'DATA_TIPE',
			header:'Tipe BS',
			width:150,
			//hidden:true
		},{
			dataIndex:'DATA_NOMINAL',
			header:'Nominal',
			width:100,
		},{
			dataIndex:'DATA_KET',
			header:'Keterangan',
			width:200,
			//hidden:true
		},{
			dataIndex:'DATA_TIPE_PENCAIRAN',
			header:'Tipe Pencairan',
			width:100,
			//hidden:true
		},{
			dataIndex:'DATA_NO_REK',
			header:'No Rekening',
			width:150,
			//hidden:true
		},{
			dataIndex:'DATA_TGL_PENCAIRAN',
			header:'Tgl Pencairan',
			width:100,
			//hidden:true
		},{
			dataIndex:'DATA_AKTIF',
			header:'Aktif',
			width:50,
			hidden:true
		}],
		listeners: {
			dblclick: {
				element: 'body', //bind to the underlying body property on the panel
				fn: function(){
					var hdid=grid_popuptransaksi.getSelectionModel().getSelection()[0].get('HD_ID');
					Ext.getCmp('tf_hdid').setValue(hdid);
					Ext.getCmp('tf_status').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_STATUS'));
					//Ext.getCmp('cb_user').setDisabled(true);
					
					Ext.getCmp('tf_nb').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_NO_BS'));
					var statusA=grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_AKTIF');
					if(statusA == 'Y'){
						Ext.getCmp('cb_status').setValue(true);
					} else {
						Ext.getCmp('cb_status').setValue(false);
					}
					
					var person_id = grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_PERSON_ID');
					var pemohon = grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_PEMOHON');
					Ext.getCmp('cb_user_id').setValue(person_id);
					Ext.getCmp('cb_user').setValue(pemohon);
					Ext.Ajax.request({
						url:'<?php echo 'isi_nama_header.php'; ?>',
							timeout: 500000,
							params:{
								nama_pem:person_id,
							},
							success:function(response){
								var json=Ext.decode(response.responseText);
								var deskripsiasli = json.results;
								var deskripsisplit = deskripsiasli.split('|');
								var perusahaan = deskripsisplit[0];
								var dept = deskripsisplit[1];
								var jabatan = deskripsisplit[2];
								var plant = deskripsisplit[3];
								var tglmasuk = deskripsisplit[4];
								var gajirp = deskripsisplit[5];
								var gaji = deskripsisplit[6];
								var grade = deskripsisplit[7];
								Ext.getCmp('tf_perusahaan').setValue(perusahaan);
								Ext.getCmp('tf_dept').setValue(dept);
								Ext.getCmp('tf_posisi').setValue(jabatan);
								Ext.getCmp('tf_grade').setValue(grade);
								Ext.getCmp('tf_plant').setValue(plant);
							},
						method:'POST',
					});
					Ext.getCmp('tf_emp_id').setValue(person_id);
					Ext.getCmp('cb_pj').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_PJ'));
					Ext.getCmp('cb_tipe').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_TIPE'));
					
					Ext.getCmp('tf_nominal').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_NOMINAL'));
					Ext.getCmp('tf_ket').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_KET'));
					Ext.getCmp('cb_tipe_pencairan').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_TIPE_PENCAIRAN'));
					Ext.getCmp('tf_no_rek').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_NO_REK'));
					Ext.getCmp('tf_tgl_pencairan').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_TGL_PENCAIRAN'));
					//var id_upload = grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_ID_UPL');
					// Ext.Ajax.request({
						// url:'<?PHP echo PATHAPP ?>/transaksi/potonganbs/insert_grid_upload.php',
						// params:{
							// idtrans:hdid,
						// },
						// method:'POST',
						// success:function(response){
							// store_upload.load();
						// },
						// failure:function(error){
							// alertDialog('Warning','Save failed.');
						// }
					// });
					
					
					// if(id_upload != 'null'){
						// store_upload.setProxy({
							// type:'ajax',
							// url:'<?PHP echo PATHAPP ?>/transaksi/bsvalidasi/isi_grid_upload.php?id_temp='+id_upload,
							// reader: {
								// type: 'json',
								// root: 'data', 
								// totalProperty:'total'   
							// }
						// });
						// store_upload.load();
					// }
					
					store_expense.setProxy({
						type:'ajax',
						url:'<?PHP echo PATHAPP ?>/transaksi/bsexpense/isi_grid.php?id_bs='+hdid,
						reader: {
							type: 'json',
							root: 'data', 
							totalProperty:'total'   
						}
					});
					store_expense.load();
					
					store_upload.setProxy({
						type:'ajax',
						timeout: 500000,
						url:'isi_grid_upload_view.php?hd_id=' + hdid,
						reader: {
							type: 'json',
							root: 'data', 
							totalProperty:'total'   
						}
					});
					store_upload.load();
					
					
					PopupTransaksi.hide();
				}
			}
		}
	});
	var PopupTransaksi=Ext.create('Ext.Window', {
		title: 'Cari No Pinjaman Karyawan',
		width: 700,
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
					id        : 'cb_filter1_pop',
					checked   : true,
				}
			]
		}, {
			xtype:'textfield',
			fieldLabel:'No Pinjaman',
			maxLengthText:5,
			id:'tf_filter_pop',
			labelWidth:75,
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
					id        : 'cb_filter2_pop',
					checked   : true,
				}
			]
		}, {
			xtype: 'datefield',
			name: 'date1',
			fieldLabel: 'Tanggal Pembuatan',
			width:200,
			labelWidth:100,
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
				if(Ext.getCmp('tf_filter_from_pop').getValue()<=Ext.getCmp('tf_filter_to_pop').getValue()){
					maskgrid = new Ext.LoadMask(Ext.getCmp('grid_popuptransaksi_id'), {msg: "Memuat . . ."});
					maskgrid.show();
					store_popuptransaksi.load({
						params:{
							tffilter:Ext.getCmp('tf_filter_pop').getValue(),
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
		}],
		items: grid_popuptransaksi
	});
	
	var store_expense=new Ext.data.JsonStore({
		id:'store_cari_id',
		pageSize: 100,
		model: 'Plant',
		fields:[{
			name:'DATA_ID'
		},{
			name:'DATA_HD_ID'
		},{
			name:'DATA_TIPE'
		},{
			name:'DATA_NOMINAL'
		},{
			name:'DATA_TGL_EXPENSE',type:'date'
		},{
			name:'DATA_KETERANGAN'
		},{
			name:'DATA_CREATED',type:'date'
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
    var grid_expense = Ext.create('Ext.grid.Panel', {
		id:'grid_detil_id',
        store: store_expense,
		autoScroll:true,
        columnLines: true,
        width: 800,
        height: 300,
        title: 'Expense BS',
        frame: false,
		//selModel:sm,
        loadMask: true,
        columns:[{
			dataIndex:'DATA_CEK',
			header:'Cek',
			width:100,
			hidden:true
		},{
			xtype:'rownumberer',
			id:'row_id',
			header:'No',
			align:'center',
			width:35
		},{
			dataIndex:'DATA_KETERANGAN',
			header:'Keterangan',
			width:260,
			field: {
                xtype: 'textfield',
				id: 'tf_keterangan',
            }
		},{
			dataIndex:'DATA_TIPE',
			header:'Tipe Expense',
			width:130,
			align:'center',
            field: {
                xtype: 'combobox',
				id:'cb_tipe_expense',
				store:comboTipeExpense,
				displayField: 'DATA_NAME',
				valueField: 'DATA_VALUE',
				enableKeyEvents : true,
				minChars:1,
				editable:false,
            }
		},{
			dataIndex:'DATA_NOMINAL',
			header:'Nominal',
			width:150,
			align:'right',
            field: {
                xtype: 'numberfield',
				id: 'tf_nominal_exp',
                //minValue: 0,
				allowBlank: false,
            }
		},{
			dataIndex:'DATA_TGL_EXPENSE',
			header:'Tgl Expense',
			renderer: formatDate,
			width:110,
			align:'center',
            field: {
                xtype: 'datefield',
				id:'tf_tgl_exp',
                format: 'Y-m-d',
				editable:false,
                //minValue: Ext.Date.add(new Date(), Ext.Date.DAY, 0),
            }
		},{
			dataIndex:'DATA_CREATED',
			header:'Tgl Action',
			renderer: formatDate,
			width:110,
			align:'center',
            field: {
                xtype: 'datefield',
				id:'tf_tgl_act',
                format: 'Y-m-d',
				editable:false,
				readOnly:true,
                minValue: Ext.Date.add(new Date(), Ext.Date.DAY, 0),
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
                store_expense.add({'DATA_NO': rowGrid, 'DATA_KETERANGAN': '', 'DATA_NOMINAL': 0, 'DATA_TGL_EXPENSE': tanggalskr, 'DATA_CREATED': tanggalskr, 'DATA_CEK': 0 });
				rowGrid++;
            }
        }],
		listeners:{
			cellclick:function(grid,row,col){
				//alert(col);
				if(col==2){
					var bool=grid_expense.getSelectionModel().getSelection()[0].get('DATA_CEK');
					if(bool==0){
						Ext.getCmp('tf_keterangan').setDisabled(false);
					}else{
						Ext.getCmp('tf_keterangan').setDisabled(true);
					}
				}
				if(col==3){
					var bool=grid_expense.getSelectionModel().getSelection()[0].get('DATA_CEK');
					if(bool==0){
						Ext.getCmp('cb_tipe_expense').setDisabled(false);
					}else{
						Ext.getCmp('cb_tipe_expense').setDisabled(true);
					}
				}
				if(col==4){
					var bool=grid_expense.getSelectionModel().getSelection()[0].get('DATA_CEK');
					if(bool==0){;
						Ext.getCmp('tf_nominal_exp').setDisabled(false);
					}else{
						Ext.getCmp('tf_nominal_exp').setDisabled(true);
					}
				}
				if(col==5){
					var bool=grid_expense.getSelectionModel().getSelection()[0].get('DATA_CEK');
					if(bool==0){;
						Ext.getCmp('tf_tgl_exp').setDisabled(false);
					}else{
						Ext.getCmp('tf_tgl_exp').setDisabled(true);
					}
				}
			}
		} 
    });
	
	var contentPanel = Ext.create('Ext.panel.Panel',{
			//title:'Form Input Tanda Terima',
			//region:'center',
			bodyStyle: 'spacing: 10px;border:none',
			items:[{
				xtype:'label',
				html:'<div align="center"><font size="5"><b>Expense Bon Sementara (BS)</b></font></div>',
			},{
				xtype:'label',
				html:'<br/><br/>',
			},{		
				xtype:'textfield',
				fieldLabel:'id bs',
				width:350,
				labelWidth:150,
				id:'tf_hdid',
				value:0,
				hidden:true
			},{		
				xtype:'textfield',
				fieldLabel:'id pemohon',
				width:350,
				labelWidth:150,
				id:'cb_user_id',
				value:0,
				hidden:true
			},{		
				xtype:'textfield',
				fieldLabel:'Status',
				width:350,
				labelWidth:75,
				id:'tf_status',
				hidden:true
			},{		
				xtype:'textfield',
				fieldLabel:'max nominal',
				width:350,
				labelWidth:75,
				id:'tf_maxnominal',
				value:0,
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
						html:'<div style="color:#FF0000">*</div>',
					}]
				},{
					columnWidth:.53,
					border:false,
					layout: 'anchor',
					defaultType: 'textfield',
					items:[{
						name: 'tf_nb',
						fieldLabel: 'No BS',
						width:450,
						labelWidth:120,
						id:'tf_nb',
						editable: false,
						readOnly:true,
						fieldStyle:'background:#B8B8B8 ;font-weight:bold;'
					}]
				},{
					columnWidth:.09,
					border:false,
					layout: 'anchor',
					defaultType: 'button',
					items:[{
						name: 'cari',
						text: 'Existing',
						width:75,
						handler:function(){
							Ext.getCmp('tf_filter_pop').setValue('');
							Ext.getCmp('tf_filter_from_pop').setValue(currentTimeNow);
							Ext.getCmp('tf_filter_to_pop').setValue(currentTimeNow);
							Ext.getCmp('cb_filter1_pop').setValue('true');
							Ext.getCmp('cb_filter2_pop').setValue('true');
							Ext.clearForm();
							PopupTransaksi.show();
						} 
					}]
				},{
					columnWidth:.1,
					border:false,
					layout: 'anchor',
					defaultType: 'button',
					items:[{
						xtype: 'checkboxgroup',
						labelWidth:50,
						items: [
							{boxLabel: 'Aktif', id: 'cb_status', name: 'cb_status', inputValue: 1, checked: true, readOnly: true,},
						]
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
						html:'<div style="color:#FF0000"></div>',
					}]
				},{
					columnWidth:.6,
					border:false,
					layout: 'anchor',
					defaultType: 'combobox',
					items:[{		
						xtype:'textfield',
						fieldLabel:'Nama Karyawan',
						width:450,
						labelWidth:120,
						id:'cb_user',
						//value:'<?PHP echo $emp_name; ?>',
						//emptyText : '- Pilih -',
						fieldStyle:'background:#B8B8B8 ;font-weight:bold;',
						readOnly: true,
						editable: false 
						//enableKeyEvents : true,
					}]
				}]	
			},{
				layout:'column',
				border:false,
				hidden: true,
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
					defaultType: 'textfield',
					items:[{		
						xtype:'textfield',
						fieldLabel:'Emp id',
						width:450,
						labelWidth:120,
						id:'tf_emp_id',
						readOnly:true,
						//value:'<?PHP echo $org_name?>',
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
					defaultType: 'textfield',
					items:[{		
						xtype:'textfield',
						fieldLabel:'Perusahaan',
						width:450,
						labelWidth:120,
						id:'tf_perusahaan',
						readOnly:true,
						//value:'<?PHP echo $org_name?>',
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
						labelWidth:120,
						id:'tf_dept',
						readOnly:true,
						//value:'<?PHP echo $io_name?>',
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
						fieldLabel:'Posisi',
						width:450,
						labelWidth:120,
						id:'tf_posisi',
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
						fieldLabel:'Grade',
						width:450,
						labelWidth:120,
						id:'tf_grade',
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
						fieldLabel:'Lokasi',
						width:450,
						labelWidth:120,
						id:'tf_plant',
						readOnly:true,
						fieldStyle:'background:#B8B8B8 ;font-weight:bold;',
						//value:'<?PHP echo $loc_name?>',
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
						html:'<div style="color:#FF0000"></div>',
					}]
				},{
					columnWidth:.6,
					border:false,
					layout: 'anchor',
					defaultType: 'combobox',
					items:[{		
						xtype:'textfield',
						fieldLabel:'Penanggung jawab',
						width:450,
						labelWidth:120,
						id:'cb_pj',
						minChars:1,
						editable:false,
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
						html:'<div style="color:#FF0000"></div>',
					}]
				},{
					columnWidth:.6,
					border:false,
					layout: 'anchor',
					defaultType: 'combobox',
					items:[{		
						xtype:'textfield',
						fieldLabel:'Tipe BS',
						width:300,
						labelWidth:120,
						id:'cb_tipe',
						editable:false,
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
						html:'<div style="color:#FF0000"></div>',
					}]
				},{
					columnWidth:.9,
					border:false,
					layout: 'anchor',
					defaultType: 'numberfield',
					items:[{		
						xtype:'numberfield',
						fieldLabel:'Nominal BS',
						width:230,
						labelWidth:120,
						id:'tf_nominal',
						value: 0,
						fieldStyle:'text-align:right;',
						// editable: false,
						minValue: 0,
						maxValue: 1000000000000,
						allowBlank: false,
						editable:false,
						readOnly:true,
						fieldStyle:'background:#B8B8B8 ;font-weight:bold;'
						// listeners: {
							 // change: function(field,newValue,oldValue){
								// //var hasil = Ext.getCmp('tf_nominal').getValue() / Ext.getCmp('tf_jc').getValue();
								// // console.log(hasil);
								// //Ext.getCmp('tf_nc').setValue(hasil.formatMoney(2, ',', '.'));
								// if(Ext.getCmp('tf_nominal').getValue()>Ext.getCmp('tf_maxnominal').getValue()){
									// var nom = Ext.getCmp('tf_maxnominal').getValue();
									// Ext.getCmp('tf_nominal').setValue(nom);
								// }
							// }
						// }
						// readOnly:true,
						// fieldStyle:'background:#B8B8B8 ;font-weight:bold;'
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
						html:'<div style="color:#FF0000"></div>',
					}]
				},{
					columnWidth:.98,
					border:false,
					layout: 'anchor',
					defaultType: 'combobox',
					items:[{
						xtype:'textareafield',
						fieldLabel:'Keterangan',
						width:450,
						//height:150,
						labelWidth:120,
						id:'tf_ket',
						editable:false,
						readOnly:true,
						fieldStyle:'background:#B8B8B8 ;font-weight:bold;'
					}]
				}]	
			},{
				xtype:'label',
				html:'&nbsp',
			},{
				layout:'column',
				border:false,
				items:[{
					columnWidth:.54,
					border:false,
					layout: 'anchor',
					defaultType: 'combobox',
					items:[{
						xtype: 'fieldset',
						flex: 1,
						title: 'Attachment',
						//defaultType: 'radio', // each item will be a radio button
						layout: 'anchor',
						items: [{
							layout:'column',
							border:false,
							items:[{
								columnWidth:0.7,
								border:false,
								layout: 'anchor',
								defaultType: 'textfield',
								items:[grid_upload]
							}]	
						}]
					}]
				}]	
			},{
				xtype:'label',
				html:'&nbsp',
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
						html:'<div style="color:#FF0000"></div>',
					}]
				},{
					columnWidth:.6,
					border:false,
					layout: 'anchor',
					defaultType: 'combobox',
					items:[{		
						xtype:'textfield',
						fieldLabel:'Tipe Pencairan',
						width:300,
						labelWidth:120,
						id:'cb_tipe_pencairan',
						editable:false,
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
						id:'lbl_no_rek',
						html:'<div style="color:#FF0000"></div>',
					}]
				},{
					columnWidth:.6,
					border:false,
					layout: 'anchor',
					defaultType: 'combobox',
					items:[{		
						xtype:'textfield',
						fieldLabel:'No Rekening',
						width:600,
						labelWidth:120,
						id:'tf_no_rek',
						editable:false,
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
						html:'<div style="color:#FF0000"></div>',
					}]
				},{
					columnWidth:.6,
					border:false,
					layout: 'anchor',
					defaultType: 'combobox',
					items:[{		
						xtype:'textfield',
						fieldLabel:'Tgl Pencairan',
						width:220,
						labelWidth:120,
						id:'tf_tgl_pencairan',
						editable:false,
						readOnly:true,
						fieldStyle:'background:#B8B8B8 ;font-weight:bold;'
					}]
				}]	
			},{
				xtype:'label',
				html:'&nbsp',
			}, grid_expense,{
				xtype:'label',
				html:'&nbsp',
			},{
				xtype:'panel',
				bodyStyle: 'padding-left: 180px;padding-bottom: 50px;border:none',
				items:[{
					xtype:'button',
					text:'Save',
					width:100,
					handler:function(){
						if (Ext.getCmp('tf_hdid').getValue()!=''){
							if (Ext.getCmp('tf_status').getValue()=='CLOSE'){
								var noBS = Ext.getCmp('tf_nb').getValue();
								alertDialog('Peringatan',"BS "+noBS+" tidak dapat disimpan karena sudah CLOSE.");
							} else {
								var isiGrid = store_expense.count();
								if(isiGrid>0){
									
									var i = 0;
									store_expense.each(
										function(record)  {
											if(record.get('DATA_TIPE') == '' || (record.get('DATA_TIPE') == 'EXPENSE CLAIM' && record.get('DATA_NOMINAL') == 0) ){
												i=i+1;
											}
										}
									); 
									
									if (i>0){
										alertDialog('Peringatan',"Tipe Expense harus diisi dan Apabila Tipe Expense 'EXPENSE CLAIM' maka Nominal Expense tidak boleh 0.");
									}else{
										var i=0;
										var arrKet = new Array();
										var arrTipe = new Array();
										var arrNominal = new Array();
										var arrTglExp = new Array();
										var arrTglAct = new Array();
										var arrCek = new Array();
										store_expense.each(
											function(record)  {
												arrKet[i]=record.get('DATA_KETERANGAN');
												arrTipe[i]=record.get('DATA_TIPE');
												arrNominal[i]=record.get('DATA_NOMINAL');
												arrTglExp[i]=record.get('DATA_TGL_EXPENSE');
												arrTglAct[i]=record.get('DATA_CREATED');
												arrCek[i]=record.get('DATA_CEK');
												i=i+1;
											}
										);
										
										Ext.Ajax.request({
											url:'<?php echo 'simpan_bs.php'; ?>',
											params:{
												hdid:Ext.getCmp('tf_hdid').getValue(),
												//tgl:Ext.Date.dateFormat(Ext.getCmp('tf_tp').getValue(), 'Y-m-d'),
												arrKet:Ext.encode(arrKet),
												arrTipe:Ext.encode(arrTipe),
												arrNominal:Ext.encode(arrNominal),
												arrTglExp:Ext.encode(arrTglExp),
												arrTglAct:Ext.encode(arrTglAct),
												arrCek:Ext.encode(arrCek),
											},
											method:'POST',
											success:function(response){
												var json=Ext.decode(response.responseText);
												var jsonresults = json.results;
												var jsonsplit = jsonresults.split('|');
												var transId = jsonsplit[0];
												var transNo = jsonsplit[1];
												if (json.rows == "sukses"){
													alertDialog('Sukses', "Data tersimpan dengan nomor : " + transNo + ".");
													// Ext.Ajax.request({
														// url:'autoemailPinjaman.php',
														// method:'POST',
														// params:{
															// hdid:jsonresults,
														// },
														// success:function(response){
														// },
														// failure:function(error){
															// alertDialog('Warning','Save failed.');
														// }
													// });
													Ext.clearForm();
												} else {
													alertDialog('Kesalahan', "Data gagal disimpan. ");
												} 
											},
											failure:function(error){
												alertDialog('Kesalahan','Data gagal disimpan');
											}
										}); 
									}
								} else{
									alertDialog('Peringatan','Tambah BS Expense dulu.');
								}
							}
						} else {
							alertDialog('Peringatan','BS belum di pilih.');
						}
					}
				},{
					xtype:'label',
					html:'&nbsp',
				},{
					xtype:'button',
					text:'Clear',
					width:100,
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