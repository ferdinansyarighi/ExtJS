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

Ext.define('Teller.ext.CurrencyField', {    extend: 'Ext.form.field.Number',    alias: 'widget.currencyfield',      hideTrigger: true,   setValue: function (v) {     this.callParent(arguments);         if (!Ext.isEmpty(this.getValue())) {            this.setRawValue(Ext.util.Format.currency(this.getValue()));        }   },  removeFormat: function (v) {        if (Ext.isEmpty(v)) {           return '';          } else {            v = v.toString().replace(Ext.util.Format.currencySign, '').replace(Ext.util.Format.thousandSeparator, '');              
if (v % 1 === 0) {              
// Return value formatted with no precision since there are no digits after the decimal                 
return Ext.util.Format.number(v, '0');          
} else {                
// Return value formatted with precision of two digits since there are digits after the decimal             
return Ext.util.Format.number(v, '0.00');           }       
}   
},  // Override parseValue to remove the currency format      
parseValue: function (v) {        
return this.callParent([this.removeFormat(v)]); 
},  // Remove the format before validating the value    
getErrors: function (v) {       return this.callParent([this.removeFormat(v)]); },  
/* Override getSubmitData to remove the currency format on the value    that will be passed out from the getValues method of the form */    
getSubmitData: function () {        var returnObject = {};      returnObject[this.name] = this.removeFormat(this.callParent(arguments)[this.name]);         
return returnObject;    },  
// Override preFocus to remove the format during edit   
preFocus: function () {         this.setRawValue(this.removeFormat(this.getRawValue()));        this.callParent(arguments); } });

Ext.define('CurrencyField', {
    extend: 'Ext.form.field.Number',
    alias: ['widget.currencyfield'],
    currency: '', //change to the symbol you would like to display.
    listeners: {
        render: function(cmp) {
            cmp.showCurrency(cmp);
        },
        blur: function(cmp) {
            cmp.showCurrency(cmp);
        },
        focus: function(cmp) {
            cmp.setRawValue(cmp.valueToRaw(cmp.getValue()));
        } 
    },
    showCurrency: function(cmp) {
        cmp.setRawValue(Ext.util.Format.currency(cmp.valueToRaw(cmp.getValue()), cmp.currency, 2, false));
    },
    valueToRaw: function(value) {
        return value.toString().replace(/[^0-9.]/g, '');
    },
    rawToValue: function(value) {
        return Ext.util.Format.round(this.valueToRaw(value), 2);
    }
});
// END Currency Component

Ext.define('ProjectComfort.overrides.MyNumberField', {
    //Simple override by Arno Voerman, Februari 2018.
    //Extenting the use of currency- and thousand seperator
    override: 'Ext.form.field.Number',

    initComponent: function () {
        var me = this;

        //Modification. The decimal seperator is always different from the thousand seperator
        if (me.thousandSeparator) me.decimalSeparator = me.thousandSeparator == ',' ? '.' : ',';

        //Get focus to remove formatting on editing. Using directly the onFocus will result in a disfunction of the Mousewheeel...
        me.mon(me, 'focus', me._onFocus, me);

        this.callParent();
    },

    valueToRaw: function (value) {
        // Extend current routine with formatting the output
        var me = this,
            decimalSeparator = me.decimalSeparator,
            thousandSeparator = me.thousandSeparator;
        //value = me.parseValue(value);
        value = me.fixPrecision(value);
        value = Ext.isNumber(value) ? value : parseFloat(String(value).replace(decimalSeparator, '.'));
        value = isNaN(value) ? '' : String(value).replace('.', decimalSeparator);

        //Add formatting
        if (this.thousandSeparator) {
            var regX = /(\d+)(\d{3})/,
                value = String(value).replace(/^\d+/, function (val) {
                    while (regX.test(val)) {
                        val = val.replace(regX, '$1' + thousandSeparator + '$2');
                    }
                    return val;
                });
        }

        //Add currency symbol
        if (this.currencySymbol) value = this.currencySymbol + ' ' + value;

        return value;
    },

    parseValue: function (value) {
        //Modification. Strip formatting (currency and/or thousandseperator) and eturn 'clean' number
        if (this.currencySymbol || this.thousandSeparator) value = String(value).replace(this.currencySymbol, '').replace(this.thousandSeparator, '').trim();

        value = parseFloat(String(value).replace(this.decimalSeparator, '.'));

        return isNaN(value) ? null : value;
    },

    _onFocus: function () {
        //On focus, remove the formatting to editing (if NOT readOnly)
        if (this.readOnly) return;

        //Get the actual value which is shown in the field
        var value = this.getRawValue();

        //Remove extra formatting including leading and trailing spaces
        if (this.currencySymbol || this.thousandSeparator) value = String(value).replace(this.currencySymbol, '').replace(this.thousandSeparator, '').trim();    //.replace('.', this.decimalSeparator);

        //show Rawvalue in field
        this.setRawValue(Ext.isEmpty(value) ? null : value);
    }
});

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
	var frmfile1 = new Ext.FormPanel({
		fileUpload	: true,
		bodyStyle	: 'padding: 5px;border:none',
		defaultType	: 'textfield',
		//region		: 'center',
		frame		: true,
		items		:[{
			xtype		:'fileuploadfield',
			fieldLabel	: 'File Arsip',
			name		: 'arsipfile',
			id			: 'arsipfile',
			width		: 375,
			labelWidth	: 75,
		}],
	});
	var upload_panel = Ext.create('Ext.Window', {//new Ext.Window ({	
		//bodyStyle	: 'border:none;padding: 10px',
		layout		: 'form',
		height		: 150,
		width		: 400,
		closeAction	: 'hide',
		buttonAlign	: 'right',
		items		:[frmfile1],
		title		:'Pilih File',
		buttons: [{
			text     : 'Ok',
			handler: function () {
				Ext.Ajax.request({
					url			: 'cekupload.php',
					method		: 'POST',
					waitTitle	: 'Connecting',
					waitMsg		: 'Sending data...',
					params:{
						file	:Ext.getCmp('arsipfile').getValue(), 
					},
					success: function (response) {
						var json=Ext.decode(response.responseText);
						if(json.jumlah==1){
							Ext.MessageBox.alert('Failed', 'Anda tidak diperbolehkan menambah file jenis TXT atau PHP');
						}
						else{
							frmfile1.getForm().submit({
								url: '<?PHP echo PATHAPP ?>/transaksi/bskaryawan/uploadattachment.php', 
								method:'POST', 
								waitTitle:'Connecting', 
								waitMsg:'Sending data...',
								
								success:function(fp, o){ 											
									var sembarang=Ext.decode(o.response.responseText);		
									//console.log(sembarang);
									if(sembarang.results=='sukses'){
										store_upload.setProxy({
											type:'ajax',
											url:'<?PHP echo PATHAPP ?>/transaksi/bskaryawan/isi_grid_upload.php?id_temp='+sembarang.id_temp,
											reader: {
												type: 'json',
												root: 'data', 
												totalProperty:'total'   
											}
										});
										store_upload.load();
										upload_panel.hide();
									} else if(sembarang.results=='gagal2'){
										Ext.MessageBox.alert('Peringatan', 'File tidak boleh sama.');
									} else {
										Ext.MessageBox.alert('Peringatan', 'File upload melebihi 512kb.');
									}
								},
								failure:function(form, action){ 
									if(action.failureType == 'server'){ 
										obj = Ext.util.JSON.decode(action.response.responseText); 						
										Ext.Msg.alert('Data gagal disimpan!', 'Login Gagal!'); 
									}else{ 
										Ext.Msg.alert('Warning!', 'Authentication server is unreachable : ' + action.response.responseText); 
									} 					
								} 
							});
						}
					},
					failure: function ( result, request) {
						Ext.MessageBox.alert('Failed', 'Data gagal disimpan');
					}
				}); 
			}
		},{
			text     : 'Batal',
			handler  : function(){
				Ext.getCmp('arsipfile').setValue('');
				//Ext.getCmp('fileid').setValue('');
				upload_panel.hide();
			}
		}]
	});
	var store_upload=new Ext.data.JsonStore({
		id:'store_upload_id',
		pageSize: 50,
		fields:[{
			name:'HD_ID'
		},{
			name:'DATA_ATTACHMENT'
		}],
		 proxy:{
			type:'ajax',
			url:'isi_grid_upload.php', 
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
	var store_upload_hrd=new Ext.data.JsonStore({
		id:'store_upload_hrd_id',
		pageSize: 50,
		fields:[{
			name:'HD_ID'
		},{
			name:'DATA_FILE'
		}],
		 proxy:{
			type:'ajax',
			url:'isi_grid_upload.php', 
			reader: {
				type: 'json',
				root: 'data',
				totalProperty:'total'        
			},
		}
	});
	var comboManager = Ext.create('Ext.data.Store', {
		fields: [{
			name:'DATA_VALUE'
		},{
			name:'DATA_NAME'
		}],
		proxy:{
			type:'ajax',
			
			// url:'<?PHP echo PATHAPP ?>/combobox/combobox_manager_id.php',
			
			url:'<?PHP echo PATHAPP ?>/combobox/combobox_manager_id_v2.php',
			
			reader: {
				type: 'json',
				root: 'data', 
				totalProperty:'total'   
			}
		}
	});
	var grid_upload_hrd=Ext.create('Ext.grid.Panel',{
		id:'grid_upload_hrd_id',
		region:'center',
		store:store_upload_hrd,
        columnLines: true,
		autoScroll:true,
		width:275,
		columns:[{
			dataIndex:'HD_ID',
			header:'id',
			width:100,
			hidden:true
		},{
			dataIndex:'DATA_FILE',
			header:'Nama File',
			width:275,
			
		}]
	});
	var comboPerusahaan = Ext.create('Ext.data.Store', {
		fields: [{
			name:'DATA_VALUE'
		},{
			name:'DATA_NAME'
		}],
		proxy:{
			type:'ajax',
			url:'<?PHP echo PATHAPP ?>/combobox/combobox_perusahaan_id.php',
			reader: {
				type: 'json',
				root: 'data', 
				totalProperty:'total'   
			}
		}
	});
	var comboTipeBS = Ext.create('Ext.data.Store', {
		fields: ['DATA_NAME', 'DATA_VALUE'],
		data : [
			{"DATA_NAME":"SPPD", "DATA_VALUE":"SPPD"},
			{"DATA_NAME":"OPERASIONAL", "DATA_VALUE":"OPERASIONAL"},
			{"DATA_NAME":"OPERASIONAL KHUSUS", "DATA_VALUE":"OPERASIONAL KHUSUS"},
		]
	});
	var comboPemohon = Ext.create('Ext.data.Store', {
		fields: [{
			name:'DATA_VALUE'
		},{
			name:'DATA_NAME'
		}],
		proxy:{
			type:'ajax',
			url:'<?PHP echo PATHAPP ?>/combobox/combobox_userall_id.php?bs_nama_karyawan=<?PHP echo $emp_name ?>', 
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
	
	var comboTipePencairan = Ext.create('Ext.data.Store', {
		fields: ['DATA_NAME', 'DATA_VALUE'],
		data : [
			{"DATA_NAME":"CASH", "DATA_VALUE":"CASH"},
			{"DATA_NAME":"TRANSFER", "DATA_VALUE":"TRANSFER"},
		]
	});
	
	Ext.clearForm=function(){
		Ext.getCmp('tf_hdid').setValue('')
		Ext.getCmp('tf_typeform').setValue('tambah')
		Ext.getCmp('tf_nb').setValue('')
		Ext.getCmp('cb_user').setDisabled(false)
		Ext.getCmp('cb_user').setValue('<?PHP echo $emp_name; ?>')
		Ext.getCmp('tf_emp_id').setValue('<?PHP echo $emp_id; ?>')
		Ext.getCmp('cb_pj').setValue('')
		Ext.getCmp('cb_tipe').setValue('- Pilih -')
		// Ext.getCmp('cb_user_ID').setValue('')
		Ext.getCmp('tf_perusahaan').setValue('<?PHP echo $org_name; ?>')
		Ext.getCmp('tf_dept').setValue('<?PHP echo $dept_name; ?>')
		Ext.getCmp('tf_posisi').setValue('<?PHP echo $pos_name; ?>')
		Ext.getCmp('tf_grade').setValue('<?PHP echo $grade; ?>')
		Ext.getCmp('tf_plant').setValue('<?PHP echo $loc_name; ?>')
		// Ext.getCmp('tf_pinjaman').setValue('')
		//Ext.getCmp('tf_tp').setValue('')
		Ext.getCmp('tf_ket').setValue('')
		Ext.getCmp('tf_syarat1').setValue('')
		Ext.getCmp('tf_syarat2').setValue('')
		Ext.getCmp('tf_syarat3').setValue('')
		Ext.getCmp('tf_syarat4').setValue('')
		Ext.getCmp('tf_syarat5').setValue('')
		Ext.getCmp('tf_nominal').setValue(0)
		Ext.getCmp('tf_tgl_jt').setValue(currentTime)
		//Ext.getCmp('tf_jc').setValue(1)
		Ext.getCmp('cb_status').setValue(true);
		
		Ext.getCmp('cb_tipe_pencairan').setValue('- Pilih -');
		Ext.getCmp('tf_no_rek').setValue('');
		// Ext.getCmp('tf_tgl_pencairan').setValue('');
		Ext.getCmp('lbl_no_rek').hide();
		Ext.getCmp('tf_no_rek').hide();
		
		store_upload.removeAll();
		comboManager.removeAll();
		comboManager.setProxy({
			type:'ajax',
			
			// url:'<?PHP echo PATHAPP ?>/combobox/combobox_manager_id.php?pemohon=<?PHP echo $emp_name; ?>',
			
			url:'<?PHP echo PATHAPP ?>/combobox/combobox_manager_id_v2.php?pemohon=<?PHP echo $emp_name; ?>',
			
			reader: {
				type: 'json',
				root: 'data', 
				totalProperty:'total'   
			}
		});
		comboManager.load();
		
		
		Ext.getCmp('cb_perusahaan').setValue('<?php echo $io_id;?>');
		comboPerusahaan.load();
		
		Ext.Ajax.request({
			url:'delete_upload.php',
			method:'POST',
			success:function(response){
			},
			failure:function(error){
				alertDialog('Warning','Save failed.');
			}
		});
		store_upload.removeAll();
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
			name:'DATA_TIPE'
		},{
			name:'DATA_NOMINAL'
		},{
			name:'DATA_KET'
		},{
			name:'DATA_AKTIF'
		},{
			name:'DATA_ID_UPL'
		},{
			name:'DATA_PERUSAHAAN_BS'
		},{
			name:'DATA_TGL_JT'
		},{
			name:'DATA_SYARAT1'
		},{
			name:'DATA_SYARAT2'
		},{
			name:'DATA_SYARAT3'
		},{
			name:'DATA_SYARAT4'
		},{
			name:'DATA_SYARAT5'
		},{
			name:'TIPE_PENCAIRAN'
		},{
			name:'NO_REK'
		}
		],
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
			dataIndex:'DATA_ASSIGNMENT_ID',
			header:'Pembuat',
			width:100,
			hidden:true
		},{
			dataIndex:'DATA_PERSON_ID_PJ',
			header:'Pembuat',
			width:100,
			hidden:true
		},{
			dataIndex:'DATA_ID_UPL',
			header:'id Upload',
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
			dataIndex:'DATA_PERUSAHAAN_BS',
			header:'Perusahaan BS',
			width:120,
			hidden:true
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
			dataIndex:'DATA_TGL_JT',
			header:'Tgl Jatuh Tempo',
			width:100,
		},{
			dataIndex:'DATA_KET',
			header:'Keterangan',
			width:200,
			//hidden:true
		},{
			dataIndex:'DATA_AKTIF',
			header:'Aktif',
			width:50,
			hidden:true
		},{
			dataIndex:'DATA_SYARAT1',
			header:'Syarat 1',
			width:120,
			hidden:true
		},{
			dataIndex:'DATA_SYARAT2',
			header:'Syarat 2',
			width:120,
			hidden:true
		},{
			dataIndex:'DATA_SYARAT3',
			header:'Syarat 3',
			width:120,
			hidden:true
		},{
			dataIndex:'DATA_SYARAT4',
			header:'Syarat 4',
			width:120,
			hidden:true
		},{
			dataIndex:'DATA_SYARAT5',
			header:'Syarat 5',
			width:120,
			hidden:true
		},{
			dataIndex:'TIPE_PENCAIRAN',
			header:'Syarat 5',
			width:120,
			hidden:true
		},{
			dataIndex:'NO_REK',
			header:'Syarat 5',
			width:120,
			hidden:true
		}],
		listeners: {
			dblclick: {
				element: 'body', //bind to the underlying body property on the panel
				fn: function(){
					var hdid=grid_popuptransaksi.getSelectionModel().getSelection()[0].get('HD_ID');
					Ext.getCmp('tf_hdid').setValue(hdid);
					Ext.getCmp('tf_typeform').setValue('edit');
					//Ext.getCmp('cb_user').setDisabled(true);
					Ext.getCmp('tf_nb').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_NO_BS'));
					var statusA=grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_AKTIF');
					if(statusA == 'Y'){
						Ext.getCmp('cb_status').setValue(true);
					} else {
						Ext.getCmp('cb_status').setValue(false);
					}
					
					Ext.getCmp('cb_perusahaan').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_PERUSAHAAN_BS'));
					comboPerusahaan.load();
					Ext.getCmp('cb_pj').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_PERSON_ID_PJ'));
					comboManager.load();
					Ext.getCmp('cb_tipe').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_TIPE'));
					comboTipeBS.load();
					Ext.getCmp('tf_nominal').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_NOMINAL'));
					Ext.getCmp('tf_tgl_jt').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_TGL_JT'));
					Ext.getCmp('tf_ket').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_KET'));
					Ext.getCmp('tf_syarat1').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_SYARAT1'));
					Ext.getCmp('tf_syarat2').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_SYARAT2'));
					Ext.getCmp('tf_syarat3').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_SYARAT3'));
					Ext.getCmp('tf_syarat4').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_SYARAT4'));
					Ext.getCmp('tf_syarat5').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_SYARAT5'));
					
					Ext.getCmp('cb_tipe_pencairan').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('TIPE_PENCAIRAN'));
					
					if ( grid_popuptransaksi.getSelectionModel().getSelection()[0].get('TIPE_PENCAIRAN') == 'TRANSFER' )
					{
						
						Ext.getCmp('lbl_no_rek').show();
						Ext.getCmp('tf_no_rek').show();
						Ext.getCmp('tf_no_rek').setValue(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('NO_REK'));
					}
					
					if(grid_popuptransaksi.getSelectionModel().getSelection()[0].get('DATA_TIPE') == 'SPPD'){
						Ext.getCmp('tf_syarat1').setReadOnly(true);
						Ext.getCmp('tf_syarat2').setReadOnly(true);
					}else{
						Ext.getCmp('tf_syarat1').setReadOnly(false);
						Ext.getCmp('tf_syarat2').setReadOnly(false);
					}
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
					/* backup cara view upload old
					if(id_upload != 'null'){
						store_upload.setProxy({
							type:'ajax',
							url:'<?PHP echo PATHAPP ?>/transaksi/bskaryawan/isi_grid_upload.php?id_temp='+id_upload,
							reader: {
								type: 'json',
								root: 'data', 
								totalProperty:'total'   
							}
						});
						store_upload.load();
					} */
					
					Ext.Ajax.request({
						url:'<?PHP echo PATHAPP ?>/transaksi/bskaryawan/insert_grid_upload.php',
						params:{
							idtrans:hdid,
						},
						method:'POST',
						success:function(response){
							store_upload.load();
						},
						failure:function(error){
							alertDialog('Warning','Save failed.');
						}
					});
					
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
					alertDialog('Kesalahan','Tanggal from lebih besar dari tanggal to.');
				}
				
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
				html:'<div align="center"><font size="5"><b>Pengajuan Bon Sementara (BS)</b></font></div>',
			},{
				xtype:'label',
				html:'<br/><br/>',
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
						html:'',
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
							store_popuptransaksi.removeAll();
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
							{boxLabel: 'Aktif', id: 'cb_status', name: 'cb_status', inputValue: 1, checked: true},
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
					defaultType: 'textfield',
					items:[{		
						xtype:'textfield',
						fieldLabel:'Nama Karyawan',
						//store: comboPemohon,
						//displayField: 'DATA_NAME',
						//valueField: 'DATA_VALUE',
						width:450,
						labelWidth:120,
						id:'cb_user',
						//value:'<?PHP echo $emp_name; ?>',
						emptyText : '- Pilih -',
						fieldStyle:'background:#B8B8B8 ;font-weight:bold;',
						readOnly: true,
						//editable: false 
						//enableKeyEvents : true,
						//minChars:1,
						/* listeners: {
							render:function(f,r,i){
								//var coba = f.value;//comboPemohon.getAt(0).DATA_VALUE;
								//var coba2 = f;//comboPemohon.getAt(0).DATA_VALUE;
								//console.log(coba2);
								var nama_pem=r[0].data.DATA_VALUE;
								var nama_pemohon=r[0].data.DATA_NAME;
								Ext.Ajax.request({
									url:'<?php echo 'isi_nama_header.php'; ?>',
										timeout: 500000,
										params:{
											nama_pem:nama_pem,
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
								comboManager.setProxy({
									type:'ajax',
									
									// url:'<?PHP echo PATHAPP ?>/combobox/combobox_manager.php?pemohon=' + nama_pemohon,
									
									url:'<?PHP echo PATHAPP ?>/combobox/combobox_manager_id_v2.php?pemohon=' + nama_pemohon,
									
									reader: {
										type: 'json',
										root: 'data', 
										totalProperty:'total'   
									}
								});
								comboManager.load();
								Ext.getCmp('tf_nominal').setValue(0);
								//untuk set max nominal
								Ext.Ajax.request({
									url:'<?php echo 'max_nominal.php'; ?>',
										params:{
											personid:nama_pem,
										},
										success:function(response){
											var json=Ext.decode(response.responseText);
											var nominal = json.results;
											if(nominal == ''){
												nominal = 0;
											}
											//var deskripsisplit = deskripsiasli.split('.');
											//var nama_dept = deskripsisplit[1];
											Ext.getCmp('tf_maxnominal').setValue(nominal);
										},
									method:'POST',
								}); 
							}
						} */
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
						html:'<div style="color:#FF0000">*</div>',
					}]
				},{
					columnWidth:.6,
					border:false,
					layout: 'anchor',
					defaultType: 'combobox',
					items:[{		
						xtype:'combobox',
						fieldLabel:'Perusahaan BS',
						width:450,
						labelWidth:120,
						id:'cb_perusahaan',
						store: comboPerusahaan,
						displayField: 'DATA_NAME',
						valueField: 'DATA_VALUE',
						//value:'- Pilih -',
						minChars:1,
						//editable:false,
						//fieldStyle:'background:#B8B8B8 ;font-weight:bold;'
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
						html:'<div style="color:#FF0000">*</div>',
					}]
				},{
					columnWidth:.6,
					border:false,
					layout: 'anchor',
					defaultType: 'combobox',
					items:[{		
						xtype:'combobox',
						fieldLabel:'Penanggung jawab',
						width:450,
						labelWidth:120,
						id:'cb_pj',
						store: comboManager,
						displayField: 'DATA_NAME',
						valueField: 'DATA_VALUE',
						//value:'- Pilih -',
						minChars:1,
						//editable:false,
						//fieldStyle:'background:#B8B8B8 ;font-weight:bold;'
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
						html:'<div style="color:#FF0000">*</div>',
					}]
				},{
					columnWidth:.6,
					border:false,
					layout: 'anchor',
					defaultType: 'combobox',
					items:[{		
						xtype:'combobox',
						fieldLabel:'Tipe BS',
						width:300,
						labelWidth:120,
						id:'cb_tipe',
						store: comboTipeBS,
						displayField: 'DATA_NAME',
						valueField: 'DATA_VALUE',
						value:'- Pilih -',
						minChars:1,
						editable:false,
						queryMode: 'Local',
						//fieldStyle:'background:#B8B8B8 ;font-weight:bold;'
						listeners: {
							select:function(f,r,i){
								var tipenya=r[0].data.DATA_VALUE;
								
								if(tipenya=='SPPD'){
									Ext.getCmp('tf_syarat1').setValue('Surat SPPD');
									Ext.getCmp('tf_syarat2').setValue('Nota Claim (Transportasi, Penginapan dan Nota Makan)');
									Ext.getCmp('tf_syarat1').setReadOnly(true);
									Ext.getCmp('tf_syarat2').setReadOnly(true);
								}else{
									Ext.getCmp('tf_syarat1').setValue('');
									Ext.getCmp('tf_syarat2').setValue('');
									Ext.getCmp('tf_syarat1').setReadOnly(false);
									Ext.getCmp('tf_syarat2').setReadOnly(false);
								}
							}
						}
					}]
				}]	
			}, {
				layout:'column',
				border:false,
				items:[,{
					columnWidth:.02,
					border:false,
					layout: 'anchor',
					defaultType: 'label',
					items:[{
						xtype:'label',
						html:'<div style="color:#FF0000">*</div>',
					}]
				},{
					columnWidth:.6,
					border:false,
					layout: 'anchor',
					defaultType: 'combobox',
					items:[{		
						xtype:'combobox',
						fieldLabel:'Tipe Pencairan',
						width:300,
						labelWidth:120,
						id:'cb_tipe_pencairan',
						store: comboTipePencairan,
						displayField: 'DATA_NAME',
						valueField: 'DATA_VALUE',
						value:'- Pilih -',
						minChars:1,
						editable:false,
						queryMode: 'Local',
						listeners: {
							select:function(f,r,i){
								var tipePencairan=r[0].data.DATA_VALUE;
								if(tipePencairan=='CASH'){
									Ext.getCmp('lbl_no_rek').hide();
									Ext.getCmp('tf_no_rek').hide();
									Ext.getCmp('tf_no_rek').setValue('');
								}else{
									Ext.getCmp('lbl_no_rek').show();
									Ext.getCmp('tf_no_rek').show();
									
									// var person_id = Ext.getCmp('cb_user_id').getValue();
									
									var person_id = Ext.getCmp('tf_emp_id').getValue();
									
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
												var rek = deskripsisplit[8];
												Ext.getCmp('tf_no_rek').setValue(rek);
											},
										method:'POST',
									});
								}
							}
						},
						//fieldStyle:'background:#B8B8B8 ;font-weight:bold;'
					}]
				}]	
			}, {
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
						html:'<div style="color:#FF0000">*</div>',
					}]
				},{
					columnWidth:.6,
					border:false,
					layout: 'anchor',
					defaultType: 'combobox',
					items:[{		
						xtype:'textfield',
						fieldLabel:'No Rekening',
						width:450,
						labelWidth:120,
						id:'tf_no_rek',
					}]
				}]	
			}, {
				layout:'column',
				border:false,
				items:[,{
					columnWidth:.02,
					border:false,
					layout: 'anchor',
					defaultType: 'label',
					items:[{
						xtype:'label',
						html:'<div style="color:#FF0000">*</div>',
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
						//currencySymbol: 'Rp',
						//decimalSeparator: ',',
						//thousandSeparator: '.',
						minValue: 0,
						maxValue: 1000000000000,
						allowBlank: false,
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
						html:'<div style="color:#FF0000">*</div>',
					}]
				},{
					columnWidth:.6,
					border:false,
					layout: 'anchor',
					defaultType: 'combobox',
					items:[{		
						xtype:'datefield',
						fieldLabel:'Tgl Jatuh Tempo',
						width:220,
						labelWidth:120,
						id:'tf_tgl_jt',
						value: currentTime,
						minValue: currentTime,
						renderer: formatDate,
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
						html:'<div style="color:#FF0000">*</div>',
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
						maxValue: 5,
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
						html:'<div style="color:#FF0000">*</div>',
						// html:'<div style="color:#FF0000"></div>',
					}]
				},{
					columnWidth:.98,
					border:false,
					layout: 'anchor',
					defaultType: 'combobox',
					items:[{
						xtype:'textfield',
						fieldLabel:'Syarat A',
						width:450,
						//height:150,
						labelWidth:120,
						id:'tf_syarat1',
						//maxValue: 5,
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
						xtype:'textfield',
						fieldLabel:'Syarat B',
						width:450,
						//height:150,
						labelWidth:120,
						id:'tf_syarat2',
						//maxValue: 5,
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
						xtype:'textfield',
						fieldLabel:'Syarat C',
						width:450,
						//height:150,
						labelWidth:120,
						id:'tf_syarat3',
						//maxValue: 5,
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
						xtype:'textfield',
						fieldLabel:'Syarat D',
						width:450,
						//height:150,
						labelWidth:120,
						id:'tf_syarat4',
						//maxValue: 5,
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
						xtype:'textfield',
						fieldLabel:'Syarat E',
						width:450,
						//height:150,
						labelWidth:120,
						id:'tf_syarat5',
						//maxValue: 5,
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
							},{
								columnWidth:.14,
								border:false,
								layout: 'anchor',
								defaultType: 'button',
								items:[{
									name: 'cari',
									text: 'Browse',
									width:50,
									handler:function(){
										upload_panel.show();
									}
								}]
							},{
								columnWidth:.14,
								border:false,
								layout: 'anchor',
								defaultType: 'button',
								items:[{xtype:'button',
									text:'Delete',
									width:50,
									handler:function(){
										var hdselec = grid_upload.getSelectionModel().getSelection();
										if (hdselec != ''){
											var hdid=grid_upload.getSelectionModel().getSelection()[0].get('HD_ID');
											if(hdid!=''){
												Ext.Ajax.request({
													url:'<?php echo 'delete_grid_upload.php'; ?>',
													params:{
														idupload:hdid,
													},
													method:'POST',
													success:function(response){
														store_upload.load();
													},
													failure:function(result,action){
														alertDialog('Warning','Save failed');
													}
												});
											}
											else {
												alertDialog('Warning','Attachment not selected.');
											}
										}
										else {
											alertDialog('Warning','Attachment not selected.');
										}
									}
								}]
							}]	
						}]
					}]
				}]	
			},{
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
						//alert(Ext.getCmp('tf_tgl_jt').getValue());
						//alert(Ext.getCmp('cb_perusahaan').getValue());
						if (	Ext.getCmp('cb_user').getValue()!='' 
								&& Ext.getCmp('tf_nominal').getValue() != 0 
								&& Ext.getCmp('tf_nominal').getValue() != '' 
								&& Ext.getCmp('tf_nominal').getValue() != null 
								&& Ext.getCmp('cb_pj').getValue()!= ''
								&& Ext.getCmp('cb_pj').getValue()!= '- Pilih -'
								&& Ext.getCmp('cb_tipe').getValue()!= '' 
								&& Ext.getCmp('cb_tipe').getValue()!= '- Pilih -' 
								&& Ext.getCmp('tf_ket').getValue()!=''
								// && Ext.getCmp('tf_tgl_jt').getValue()!=null 
								// && Ext.getCmp('tf_tgl_jt').getValue()!='' 
								&& Ext.getCmp('cb_perusahaan').getValue()!= null
								&& Ext.getCmp('tf_syarat1').getValue() != '' 
								&& Ext.getCmp('tf_syarat1').getValue() != null
								&& Ext.getCmp('cb_tipe_pencairan').getValue() != '' 
								&& Ext.getCmp('cb_tipe_pencairan').getValue() != null
								&& Ext.getCmp('cb_tipe_pencairan').getValue()!= '- Pilih -'
							) 
						{
							
							var check_tipe = 'N';
							
							if ( Ext.getCmp('cb_tipe').getValue() == 'OPERASIONAL KHUSUS' ) {
								
								check_tipe = 'Y';
								
							} else {
								
								if ( Ext.getCmp('tf_tgl_jt').getValue() == null || Ext.getCmp('tf_tgl_jt').getValue() == ''  ) {
									
									check_tipe = 'N';
									
								} else {
									
									check_tipe = 'Y';
									
								}
							
							}
							
							if ( check_tipe == 'N' ) {
								
								alertDialog('Peringatan','Kolom bertanda bintang (*) harus diisi.');
								
							} else {
								
								if ( 
										Ext.getCmp('cb_tipe_pencairan').getValue() == 'TRANSFER'
										&& ( Ext.getCmp('tf_no_rek').getValue() == null || Ext.getCmp('tf_no_rek').getValue() == '' )
										
									) 
								{
									
									alertDialog('Peringatan','Kolom bertanda bintang (*) harus diisi.');
									
								} else {
									
									console.log( 'cb_tipe_pencairan: ' + Ext.getCmp('cb_tipe_pencairan').getValue() );
									console.log( 'tf_nominal: ' + Ext.getCmp('tf_nominal').getValue() );
									
									if ( Ext.getCmp('cb_tipe_pencairan').getValue() == 'CASH'
											&& Ext.getCmp('tf_nominal').getValue() > 2000000 ) {
										
										alertDialog('Peringatan','Maksimal nominal BS untuk tipe pencairan Cash adalah Rp. 2.000.000.');
										
									} else {
										
										
										
										/* DIPAKAI */
										
										//alert(Ext.getCmp('tf_nominal').getValue());
										var i = 0;
										var arrDataFile = new Array();
										store_upload.each(
											function(record) {
												arrDataFile[i]=record.get('DATA_FILE');
												i++;
											}
										);
										
										var statusString = '';
										if (Ext.getCmp('cb_status').getValue()) {
											statusString = 'Y';
										} else {
											statusString = 'N';
										}
										Ext.Ajax.request({
											
											// url:'<?php echo 'simpan_bs_v2.php'; ?>',
											
											url:'<?php echo 'simpan_bs.php'; ?>',
											
											params:{
												hdid:Ext.getCmp('tf_hdid').getValue(),
												typeform:Ext.getCmp('tf_typeform').getValue(),
												//tgl:Ext.Date.dateFormat(Ext.getCmp('tf_tp').getValue(), 'Y-m-d'),
												nama_user:Ext.getCmp('cb_user').getValue(),
												person_id:Ext.getCmp('tf_emp_id').getValue(),
												perusahaan_bs:Ext.getCmp('cb_perusahaan').getValue(),
												pj:Ext.getCmp('cb_pj').getValue(),
												tipe:Ext.getCmp('cb_tipe').getValue(),
												nominal:Ext.getCmp('tf_nominal').getValue(),
												tgl_jt:Ext.getCmp('tf_tgl_jt').getValue(),
												ket:Ext.getCmp('tf_ket').getValue(),
												syarat1:Ext.getCmp('tf_syarat1').getValue(),
												syarat2:Ext.getCmp('tf_syarat2').getValue(),
												syarat3:Ext.getCmp('tf_syarat3').getValue(),
												syarat4:Ext.getCmp('tf_syarat4').getValue(),
												syarat5:Ext.getCmp('tf_syarat5').getValue(),
												
												tipe_pencairan:Ext.getCmp('cb_tipe_pencairan').getValue(),
												no_rek:Ext.getCmp('tf_no_rek').getValue(),
												
												arrDataFile:Ext.encode(arrDataFile),
												status:statusString,
											},
											method:'POST',
											success:function(response){
												var json=Ext.decode(response.responseText);
												var jsonresults = json.results;
												var jsonsplit = jsonresults.split('|');
												var transId = jsonsplit[0];
												var transNo = jsonsplit[1];
												if (json.rows == "sukses") {
													
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
													
												} else if(json.rows == "sudahapprove"){
													alertDialog('Kesalahan', "Data tidak bisa diubah karena sedang proses approval. ");
												} else {
													alertDialog('Kesalahan', "Data gagal disimpan. ");
												} 
											},
											failure:function(error){
												alertDialog('Kesalahan','Data gagal disimpan');
											}
										});
										
										
										/* DIPAKAI */
										
										
										alertDialog('Sukses', "Data tersimpan (TESTING).");
									
																			
										
									}
									
								}
								
								
							}
							
							
							
						} else {
							alertDialog('Peringatan','Kolom bertanda bintang (*) harus diisi.');
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
   comboManager.setProxy({
		type:'ajax',
		
		// url:'<?PHP echo PATHAPP ?>/combobox/combobox_manager_id.php?pemohon=<?PHP echo $emp_name; ?>',
		
		url:'<?PHP echo PATHAPP ?>/combobox/combobox_manager_id_v2.php?pemohon=<?PHP echo $emp_name; ?>',
		
		reader: {
			type: 'json',
			root: 'data', 
			totalProperty:'total'   
		}
	});
	comboManager.load();
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