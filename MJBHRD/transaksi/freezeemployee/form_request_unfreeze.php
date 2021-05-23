<?PHP
	include_once '../../main/koneksi.php';
	session_start();
	require_once 'helper/define_session.php';
	
	//print_r($_SESSION);
	 
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
					var awalbulan = new Date(currentTime.getFullYear(), currentTime.getMonth(), 1);
					var month = currentTime.getMonth() + 1;
					var day = currentTime.getDate();
					var year = currentTime.getFullYear();
					var tanggalskr = day + "-" + month + "-" + year;
					var rowGrid = 1;

					Ext.define('Ext.ux.form.NumericField',
					{
						extend: 'Ext.form.field.Number',//Extending the NumberField
						alias: 'widget.numericfield',//Defining the xtype,
						currencySymbol: null,
						useThousandSeparator: true,
						thousandSeparator: ',',
						alwaysDisplayDecimals: false,
						fieldStyle: 'text-align: right;',
						initComponent: function(){
							if (this.useThousandSeparator && this.decimalSeparator == ',' && this.thousandSeparator == ',')
								this.thousandSeparator = '.';
							else
								if (this.allowDecimals && this.thousandSeparator == '.' && this.decimalSeparator == '.')
									this.decimalSeparator = ',';
						   
							this.callParent(arguments);
						},
						setValue: function(value){
							Ext.ux.form.NumericField.superclass.setValue.call(this, value != null ? value.toString().replace('.', this.decimalSeparator) : value);
						   
							this.setRawValue(this.getFormattedValue(this.getValue()));
						},
						getFormattedValue: function(value){
							if (Ext.isEmpty(value) || !this.hasFormat())
								return value;
							else
							{
								var neg = null;
							   
								value = (neg = value < 0) ? value * -1 : value;
								value = this.allowDecimals && this.alwaysDisplayDecimals ? value.toFixed(this.decimalPrecision) : value;
							   
								if (this.useThousandSeparator)
								{
									if (this.useThousandSeparator && Ext.isEmpty(this.thousandSeparator))
										throw ('NumberFormatException: invalid thousandSeparator, property must has a valid character.');
								   
									if (this.thousandSeparator == this.decimalSeparator)
										throw ('NumberFormatException: invalid thousandSeparator, thousand separator must be different from decimalSeparator.');
								   
									value = value.toString();
								   
									var ps = value.split('.');
									ps[1] = ps[1] ? ps[1] : null;
								   
									var whole = ps[0];
								   
									var r = /(\d+)(\d{3})/;
								   
									var ts = this.thousandSeparator;
								   
									while (r.test(whole))
										whole = whole.replace(r, '$1' + ts + '$2');
								   
									value = whole + (ps[1] ? this.decimalSeparator + ps[1] : '');
								}
							   
								return Ext.String.format('{0}{1}{2}', (neg ? '-' : ''), (Ext.isEmpty(this.currencySymbol) ? '' : this.currencySymbol + ' '), value);
							}
						},
						/**
						 * overrides parseValue to remove the format applied by this class
						 */
						parseValue: function(value){
							//Replace the currency symbol and thousand separator
							return Ext.ux.form.NumericField.superclass.parseValue.call(this, this.removeFormat(value));
						},
						/**
						 * Remove only the format added by this class to let the superclass validate with it's rules.
						 * @param {Object} value
						 */
						removeFormat: function(value){
							if (Ext.isEmpty(value) || !this.hasFormat())
								return value;
							else
							{
								value = value.toString().replace(this.currencySymbol + ' ', '');
							   
								value = this.useThousandSeparator ? value.replace(new RegExp('[' + this.thousandSeparator + ']', 'g'), '') : value;
							   
								return value;
							}
						},
						/**
						 * Remove the format before validating the the value.
						 * @param {Number} value
						 */
						getErrors: function(value){
							return Ext.ux.form.NumericField.superclass.getErrors.call(this, this.removeFormat(value));
						},
						hasFormat: function(){
							return this.decimalSeparator != '.' || (this.useThousandSeparator == true && this.getRawValue() != null) || !Ext.isEmpty(this.currencySymbol) || this.alwaysDisplayDecimals;
						},
						/**
						 * Display the numeric value with the fixed decimal precision and without the format using the setRawValue, don't need to do a setValue because we don't want a double
						 * formatting and process of the value because beforeBlur perform a getRawValue and then a setValue.
						 */
						onFocus: function(){
							this.setRawValue(this.removeFormat(this.getRawValue()));
						   
							this.callParent(arguments);
						}
					});
					
					function formatDate(value){
						return value ? Ext.Date.dateFormat(value, 'Y-m-d') : '';
					}
					
					var comboOrganization = Ext.create('Ext.data.Store', {
						fields: [{
							name:'DATA_VALUE'
						},{
							name:'DATA_NAME'
						}],
						proxy:{
							type:'ajax',
							url:'<?PHP echo PATHAPP ?>/combobox/combobox_OU.php',
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
					
					var comboDepartment = Ext.create('Ext.data.Store', {
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

					var comboEmployee = Ext.create('Ext.data.Store', {
						fields: [{
							name:'DATA_VALUE'
						},{
							name:'DATA_NAME'
						}],
						proxy:{
							type:'ajax',
							/* url:'<?PHP echo PATHAPP ?>/combobox/combobox_pemohon_dept_id.php', */
							url:'<?PHP echo PATHAPP ?>/combobox/combobox_employee_per_mgr.php?mgr_id=<?php echo $emp_id?>',
							reader: {
								type: 'json',
								root: 'data', 
								totalProperty:'total'   
							},
						}
					});
					
					var comboPeriodeFreeze = Ext.create('Ext.data.Store', {
						fields: [{
							name:'DATA_VALUE'
						},{
							name:'DATA_NAME'
						}],
						proxy:{
							type:'ajax',
							url:'<?PHP echo PATHAPP ?>/combobox/combobox_periode_freeze.php?salary_type=monthly',
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
							name:'DATA_NO'
						},{
							name:'DATA_HD_ID'
						},{
							name:'DATA_PERSON_ID'
						},{
							name:'DATA_EMPLOYEE_NAME'
						},{
							name:'DATA_ORGANIZATION_ID'
						},{
							name:'DATA_ORGANIZATION_UNITS'
						},{
							name:'DATA_DEPARTMENT_ID'
						},{
							name:'DATA_DEPARTMENT'
						},{
							name:'DATA_PLANT_ID'
						},{
							name:'DATA_PLANT'
						},{
							name:'DATA_POS_ID'
						},{
							name:'DATA_POS_NAME'
						},{
							name:'DATA_TOTAL_ALPHA'
						},{
							name:'DATA_START_DATE_ALPHA'
						},{
							name:'DATA_END_DATE_ALPHA'
						},{
							name:'DATA_START_PERIOD'
						},{
							name:'DATA_END_PERIOD'
						},{
							name:'DATA_MGR_NOTES'
						},{
							name:'DATA_ATTACHMENT'
						},{
							name:'DATA_GROUP_SALARY'
						}],
						proxy:{
							type:'ajax',
							url:'grid_unfreeze.php?formType=request', 
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
						title: 'Freeze Employee Salaries',
						frame: false,
						selModel:sm,
						loadMask: true,
						columns: [
						{
							dataIndex:'DATA_HD_ID',
							header:'id',
							width:100,
							// hidden:true
						},{
							dataIndex:'DATA_NO',
							header:'No',
							// hidden:true,
							width:50,
							align:'right',
						},{
							dataIndex:'DATA_PERSON_ID',
							header:'ID Karyawan',
							width:160,
							hidden:true
						},{
							dataIndex:'DATA_EMPLOYEE_NAME',
							header:'Nama Karyawan',
							width:160,
							//hidden:true
						},{
							dataIndex:'DATA_ORGANIZATION_ID',
							header:'Organization ID',
							width:160,
							hidden:true
						},{
							dataIndex:'DATA_ORGANIZATION_UNITS',
							header:'Organization Units',
							width:160,
							//hidden:true
						},{
							dataIndex:'DATA_DEPARTMENT_ID',
							header:'Department ID',
							width:150,
							hidden:true
						},{
							dataIndex:'DATA_DEPARTMENT',
							header:'Department',
							width:150,
							//hidden:true
						},{
							dataIndex:'DATA_PLANT',
							header:'Plant',
							width:150,
							//hidden:true
						},{
							dataIndex:'DATA_PLANT_ID',
							header:'Plant ID',
							width:150,
							hidden:true
						},{
							dataIndex:'DATA_POS_NAME',
							header:'Jabatan',
							width:150,
							//hidden:true
						},{
							dataIndex:'DATA_POS_ID',
							header:'Position ID',
							width:150,
							hidden:true
						},{
							dataIndex:'DATA_TOTAL_ALPHA',
							header:'Total Tidak Absen >= 5 Hari',
							width:150,
							align:'right',
						//	hidden:true
						},{
							dataIndex:'DATA_START_DATE_ALPHA',
							header:'Tgl Awal Tidak Absen',
							width:150,
							align:'center',
						//	hidden:true
						},{
							dataIndex:'DATA_END_DATE_ALPHA',
							header:'Tgl Akhir Tidak Absen',
							width:150,
							align:'center',
						//	hidden:true
						},{
							dataIndex:'DATA_START_PERIOD',
							header:'Periode Awal Absen',
							width:150,
							align:'center',
						//	hidden:true
						},{
							dataIndex:'DATA_END_PERIOD',
							header:'Periode  Akhir Absen',
							width:150,
							align:'center',
						//	hidden:true
						},{
							dataIndex:'DATA_GROUP_SALARY',
							header:'Group Gaji',
							width:150,
							align:'left',
						//	hidden:true
						},{
							dataIndex:'DATA_MGR_NOTES',
							header:'Keterangan',
							width:150,
						//	hidden:true
							field: {
								// xtype:'combobox',
								xtype:'textfield',
								id: 'tf_ket',
								name: 'tf_ket',
								editable:true,
							}
						},{
							dataIndex:'DATA_ATTACHMENT',
							header:'Attachment',
							width:280,
							//hidden:true
						}],
						plugins: [cellEditing],
					
						listeners:{
							dblclick:{
								element:'body',
								fn:function(){
								//if (col == 19) {
									//alert('asda');
									Ext.MessageBox.wait('Loading ...');
									Ext.Ajax.request({
										url:'helper/delete_upload.php',
										method:'POST',
										success:function(response){
										},
										failure:function(error){
											alertDialog('Warning','Save failed.');
										}
									});
									
									win_apv.show();
									var hd_id = grid_detil.getSelectionModel().getSelection()[0].get('DATA_HD_ID');
									Ext.getCmp('tf_trans_id').setValue(hd_id);
									Ext.getCmp('tf_emp_id').setValue(grid_detil.getSelectionModel().getSelection()[0].get('DATA_PERSON_ID'));
									Ext.getCmp('tf_emp_name').setValue(grid_detil.getSelectionModel().getSelection()[0].get('DATA_EMPLOYEE_NAME'));
									Ext.getCmp('tf_org').setValue(grid_detil.getSelectionModel().getSelection()[0].get('DATA_ORGANIZATION_UNITS'));
									Ext.getCmp('tf_org_id').setValue(grid_detil.getSelectionModel().getSelection()[0].get('DATA_ORGANIZATION_ID'));
									
									Ext.getCmp('tf_dept_id').setValue(grid_detil.getSelectionModel().getSelection()[0].get('DATA_DEPARTMENT_ID'));
									Ext.getCmp('tf_dept_name').setValue(grid_detil.getSelectionModel().getSelection()[0].get('DATA_DEPARTMENT'));
									Ext.getCmp('tf_plant_id').setValue(grid_detil.getSelectionModel().getSelection()[0].get('DATA_PLANT_ID'));
									Ext.getCmp('tf_plant_name').setValue(grid_detil.getSelectionModel().getSelection()[0].get('DATA_PLANT'));
									Ext.getCmp('tf_pos_id').setValue(grid_detil.getSelectionModel().getSelection()[0].get('DATA_POS_ID'));
									Ext.getCmp('tf_pos_name').setValue(grid_detil.getSelectionModel().getSelection()[0].get('DATA_POS_NAME'));
									Ext.getCmp('tf_tot_alpha').setValue(grid_detil.getSelectionModel().getSelection()[0].get('DATA_TOTAL_ALPHA'));
									Ext.getCmp('tf_start_date_alpha').setValue(grid_detil.getSelectionModel().getSelection()[0].get('DATA_START_DATE_ALPHA'));
									Ext.getCmp('tf_end_date_alpha').setValue(grid_detil.getSelectionModel().getSelection()[0].get('DATA_END_DATE_ALPHA'));
									Ext.getCmp('tf_start_date_absn').setValue(grid_detil.getSelectionModel().getSelection()[0].get('DATA_START_PERIOD'));
									Ext.getCmp('tf_end_date_absn').setValue(grid_detil.getSelectionModel().getSelection()[0].get('DATA_END_PERIOD'));
									Ext.getCmp('tf_grp_salary').setValue(grid_detil.getSelectionModel().getSelection()[0].get('DATA_GROUP_SALARY'));
									Ext.getCmp('tf_mgr_notes').setValue(grid_detil.getSelectionModel().getSelection()[0].get('DATA_MGR_NOTES'));
								
									Ext.Ajax.request({
										url:'/MJBHRD/transaksi/freezeemployee/helper/insert_grid_upload.php?idtrans='+hd_id,
										params:{
											idtrans:hd_id,
										},
										method:'POST',
										success:function(response){
											store_upload_attc.load();
										},
										failure:function(error){
											Ext.MessageBox.hide();
											alertDialog('Warning','Save failed.');
										}
									})
									Ext.MessageBox.hide();
								}
							}
							
						}
					});

	
				var contentPanel = Ext.create('Ext.panel.Panel',{

					bodyStyle: 'spacing: 10px;border:none',
					items:[{
						xtype:'label',
						html:'<div align="center"><font size="5"><b>Form Unfreeze Employee Salaries</b></font></div>',
					},{
						xtype:'label',
						html:'<br/><br/><br/>',
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
								fieldLabel:'Organization Units',
								store: comboOrganization,
								displayField: 'DATA_NAME',
								valueField: 'DATA_VALUE',
								width:340,
								labelWidth:120,
								id:'cb_organization_units',
								value:'',
								emptyText : '- Pilih -',
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
								labelWidth:120,
								id:'cb_plant',
								value:'',
								emptyText : '- Pilih -',
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
							columnWidth:.4,
							border:false,
							layout: 'anchor',
							defaultType: 'combobox',
							items:[{		
								xtype:'combobox',
								fieldLabel:'Department',
								store: comboDepartment,
								displayField: 'DATA_NAME',
								valueField: 'DATA_VALUE',
								width:340,
								labelWidth:120,
								id:'cb_department',
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
								fieldLabel:'Nama Karyawan',
								store: comboEmployee,
								displayField: 'DATA_NAME',
								valueField: 'DATA_VALUE',
								width:340,
								labelWidth:120,
								id:'cb_emp_freeze',
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
							columnWidth:.4,
							border:false,
							layout: 'anchor',
							defaultType: 'combobox',
							items:[{		
								xtype:'combobox',
								fieldLabel:'Periode Absen',
								store: comboPeriodeFreeze,
								displayField: 'DATA_NAME',
								valueField: 'DATA_VALUE',
								width:340,
								labelWidth:120,
								id:'cb_absence_period',
								value:'',
								emptyText : '- Pilih -',
								enableKeyEvents : true,
								minChars:1,
							}]
						}]	
					},{
						xtype:'label',
						html:'<br/>',
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
							defaultType: 'button',
							items:[{
								name: 'btn_view',
								text: 'View',
								width:85,
								handler:function(){
									var error_msg = "";
									
									if(Ext.getCmp('cb_organization_units').getValue() == ''){
										error_msg = error_msg+'- Organization Units Harus Diisi<br>';
									}
									/* RRU Sementara
									if(Ext.getCmp('cb_department').getValue() == ''){
										error_msg = error_msg+'- Department Harus Diisi<br>';
									}
									*/
									if(error_msg != "")
									{
										alertDialog('Peringatan', error_msg);
									}
									else
									{
										maskgrid = new Ext.LoadMask(Ext.getCmp('grid_detil_id'), {msg: "Memuat . . ."});
										/*
										Ext.Ajax.request({
											url:'helper/delete_upload.php',
											method:'POST',
											success:function(response){
											},
											failure:function(error){
												alertDialog('Warning','Save failed.');
											}
										});
										*/
										maskgrid.show();
										store_detil.load({
											params:{
												org_id:Ext.getCmp('cb_organization_units').getValue(),
												plant:Ext.getCmp('cb_plant').getValue(),
												dept:Ext.getCmp('cb_department').getValue(),
												emp_freeze:Ext.getCmp('cb_emp_freeze').getValue(),
												absn_period:Ext.getCmp('cb_absence_period').getValue(),
												formType:'request',
											}
										});
									}
								} 
							},{
								xtype:'label',
								html:'&nbsp',
							},{
								name: 'btn_clear',
								text: 'Clear',
								width:85,
								handler:function(){
									Ext.getCmp('cb_organization_units').setValue('');
									Ext.getCmp('cb_plant').setValue('');
									Ext.getCmp('cb_department').setValue('');
									Ext.getCmp('cb_emp_freeze').setValue('');
									Ext.getCmp('cb_absence_period').setValue('');
								} 
							}]
						}]	
					},{
						xtype:'label',
						html:'<br/><br/>',
					}, grid_detil,{
						xtype:'label',
						html:'&nbsp',
					},{
						xtype:'label',
						html:'<br/><br/>',
					},{
						xtype:'panel',
						bodyStyle: 'padding-left: 300px;padding-bottom: 50px;border:none',
						items:[{
							xtype:'button',
							text:'Request Unfreeze',
							width:100,
							handler:function(){
								var data=sm.getSelection();
								var countGrid = 0;
								var i=0;
								var error_val=0;
								var err_attc = 0;
								var arrTransID = new Array();
								var arrPersonID = new Array();
								var arrPersonName = new Array();
								var arrOrgID = new Array();
								var arrDeptID = new Array();
								var arrDeptName = new Array();
								var arrPlantID = new Array();
								var arrPlantName = new Array();
								var arrPosID = new Array();
								var arrPosName = new Array();
								var arrTotAlpha = new Array();
								var arrStartDateAlpha = new Array();
								var arrEndDateAlpha = new Array();
								var arrStartPeriod = new Array();
								var arrEndPeriod = new Array();
								var arrMgrNotes = new Array();
								var arrAttc = new Array();
								for(var i in data) {
									arrTransID[i]=data[i].get('DATA_HD_ID');
									arrPersonID[i]=data[i].get('DATA_PERSON_ID');
									arrPersonName[i]=data[i].get('DATA_EMPLOYEE_NAME');
									arrOrgID[i]=data[i].get('DATA_ORGANIZATION_ID');
									arrDeptID[i]=data[i].get('DATA_DEPARTMENT_ID');
									arrDeptName[i]=data[i].get('DATA_DEPARTMENT');
									arrPlantID[i]=data[i].get('DATA_PLANT_ID');
									arrPlantName[i]=data[i].get('DATA_PLANT');
									arrPosID[i]=data[i].get('DATA_POS_ID');
									arrPosName[i]=data[i].get('DATA_POS_NAME');
									arrTotAlpha[i]=data[i].get('DATA_TOTAL_ALPHA');
									arrStartDateAlpha[i]=data[i].get('DATA_START_DATE_ALPHA');
									arrEndDateAlpha[i]=data[i].get('DATA_END_DATE_ALPHA');
									arrStartPeriod[i]=data[i].get('DATA_START_PERIOD');
									arrEndPeriod[i]=data[i].get('DATA_END_PERIOD');
									arrMgrNotes[i]=data[i].get('DATA_MGR_NOTES');
									arrAttc[i]=data[i].get('DATA_ATTACHMENT');
									
									if(data[i].get('DATA_ATTACHMENT') == '' || data[i].get('DATA_ATTACHMENT') == null)
									{
										err_attc++;
									}
									countGrid++;
								}
								/*
								console.log(countGrid);
								console.log(arrPersonID);
								console.log(arrOrgID);
								console.log(arrDeptID);
								console.log(arrPlantID);
								console.log(arrTotAlpha);
								console.log(arrStartDateAlpha);
								console.log(arrEndDateAlpha);
								console.log(arrTransKet);
								*/
								var tingkat_app = grid_detil.getSelectionModel().getSelection()[0].get('DATA_TINGKAT');
								var error_msg = "";
							
								if(countGrid>0){
									if(err_attc > 0)
									{
										error_msg = error_msg+"- Wajib melampirkan file <br>";
									}	
									
									if(error_msg != "")
									{
										alertDialog('Peringatan', error_msg);
									}
									else
									{
										maskgrid = new Ext.LoadMask(Ext.getCmp('grid_detil_id'), {msg: "Harap Tunggu. . .Sedang Proses Approval . . ."});
										maskgrid.show();
										Ext.Ajax.request({
											url:'<?php echo 'controllers/freeze_employee.php'; ?>',
											timeout: 500000,
											params:{
												/*typeform:'Request Unfreeze',*/
												arrTransID:Ext.encode(arrTransID),
												arrPersonID:Ext.encode(arrPersonID),
												arrPersonName:Ext.encode(arrPersonName),
												arrOrgID:Ext.encode(arrOrgID),
												arrDeptID:Ext.encode(arrDeptID),
												arrDeptName:Ext.encode(arrDeptName),
												arrPlantID:Ext.encode(arrPlantID),
												arrPlantName:Ext.encode(arrPlantName),
												arrPosID:Ext.encode(arrPosID),
												arrPosName:Ext.encode(arrPosName),
												arrTotAlpha:Ext.encode(arrTotAlpha),
												arrStartDateAlpha:Ext.encode(arrStartDateAlpha),
												arrEndDateAlpha:Ext.encode(arrEndDateAlpha),
												arrStartPeriod:Ext.encode(arrStartPeriod),
												arrEndPeriod:Ext.encode(arrEndPeriod),
												arrMgrNotes:Ext.encode(arrMgrNotes),
												activeStatus:'Y',
												formType:'ReqUnfreeze',
											},
											method:'POST',
											success:function(response){
												maskgrid.hide();
												var json=Ext.decode(response.responseText);
												if (json.rows == "sukses"){
													alertDialog('Sukses', "Data tersimpan.");
													rowGrid = 0;
													store_detil.removeAll();
													maskgrid = new Ext.LoadMask(Ext.getCmp('grid_detil'), {msg: "Memuat . . ."});
													maskgrid.show();
													Ext.getCmp('cb_organization_units').setValue('');
													Ext.getCmp('cb_plant').setValue('');
													Ext.getCmp('cb_department').setValue('');
													Ext.getCmp('cb_emp_freeze').setValue('');
													Ext.getCmp('cb_absence_period').setValue('');
													store_detil.load({
														params:{													
															org_id:Ext.getCmp('cb_organization_units').getValue(),
															plant:Ext.getCmp('cb_plant').getValue(),
															dept:Ext.getCmp('cb_department').getValue(),
															emp_freeze:Ext.getCmp('cb_emp_freeze').getValue(),
															absn_period:Ext.getCmp('cb_absence_period').getValue(),
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
									}
								} else {
									alertDialog('Kesalahan','Data Pengajuan Unfreeze belum dipilih.');
								} 
								
							}
						},{
							xtype:'label',
							html:'&nbsp',
						},{
							xtype:'button',
							text:'Exit',
							width:70,
							handler:function(){
								document.location.href = "../../main/indexUtama.php";
							}
						}]
					}],
				});	
				
				
				
			   //contentPanel.render('page');
			   
				Ext.Ajax.request({
					url:'helper/delete_upload.php',
					method:'POST',
					success:function(response){
					},
					failure:function(error){
						alertDialog('Warning','Save failed.');
					}
				});
				
				
			 
			// }
			
	
					/*
					* Upload & Grid Attachment
					*/
					var frmfileattc = new Ext.FormPanel({
						fileUpload	: true,
						bodyStyle	: 'padding: 5px;border:none',
						defaultType	: 'textfield',
						frame		: true,
						items		:[{
							xtype		:'fileuploadfield',
							fieldLabel	: 'File Lampiran',
							name		: 'arsipfileattc',
							id			: 'arsipfileattc',
							width		: 375,
							labelWidth	: 75,
						}],
					});
					
					var upload_panel_attc = Ext.create('Ext.Window', {
						//bodyStyle	: 'border:none;padding: 10px',
						layout		: 'form',
						height		: 150,
						width		: 450,
						closeAction	: 'hide',
						buttonAlign	: 'right',
						items		:[frmfileattc],
						title		:'Pilih File',
						buttons: [{
							text     : 'Ok',
							handler: function () {
								Ext.Ajax.request({
									url			: 'helper/cek_upload.php',
									method		: 'POST',
									waitTitle	: 'Connecting',
									waitMsg		: 'Sending data...',
									params:{
										file	:Ext.getCmp('arsipfileattc').getValue(), 
									},
									success: function (response) {
										var json=Ext.decode(response.responseText);
										if(json.jumlah==1){
											Ext.MessageBox.alert('Failed', 'Anda tidak diperbolehkan menambah file jenis TXT atau PHP');
										}
										else{
											frmfileattc.getForm().submit({
												url: 'helper/upload_attachment.php', 
												method:'POST', 
												waitTitle:'Connecting', 
												waitMsg:'Sending data...',
												
												success:function(fp, o){ 											
													var data_response = Ext.decode(o.response.responseText);		
													if(data_response.results=='sukses'){
														store_upload_attc.load();
														upload_panel_attc.hide();
													} else if(data_response.results=='gagal2'){
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
								Ext.getCmp('arsipfileattc').setValue('');
								upload_panel_attc.hide();
							}
						}]
					});
					
					var store_upload_attc=new Ext.data.JsonStore({
						id:'store_upload_attc_id',
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
							url:'helper/grid_upload.php', 
							reader: {
								type: 'json',
								root: 'data',
								totalProperty:'total'        
							},
						}
					});
					
					var grid_upload_attc=Ext.create('Ext.grid.Panel',{
						id:'grid_upload_attc_id',
						region:'center',
						store:store_upload_attc,
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
	
			/*
			* Form
			*/
			var CP_CL = Ext.create('Ext.panel.Panel',{
							bodyStyle: 'spacing: 10px;border:none',
							items:[{
									xtype:'label',
									html:'<div align="center"><font size="5"><b>Form Request Unfreeze</b></font></div>',
								},{
									xtype:'label',
									html:'&nbsp<br/><br/><br/>',
								},{		
									xtype:'textfield',
									fieldLabel:'formType',
									width:350,
									labelWidth:75,
									id:'tf_formType',
									value:'update_unfreeze',
									hidden:true
								},{
									layout: 'column',
									border: false,
									items: [{
										columnWidth:.02,
										border:false,
										layout: 'anchor',
										defaultType: 'label',
										items:[{
											xtype:'label',
											id: 'lbl_trans_id',
											html:'<div style="color:#FF0000">*</div>',
										}]
									}, {
										columnWidth: .7,
										border:false,
										layout: 'anchor',
										defaultType: 'textfield',
										//hidden:true,
										items:[{		
											xtype:'textfield',
											readOnly:true,
											fieldLabel:'ID Transaksi',
											width:350,
											labelWidth:150,
											id:'tf_trans_id',
											value: '',
										}]
									}]
								},{
									layout: 'column',
									border: false,
									items: [{
										columnWidth:.02,
										border:false,
										layout: 'anchor',
										defaultType: 'label',
										items:[{
											xtype:'label',
											id: 'lbl_emp_id',
											html:'<div style="color:#FF0000">*</div>',
										}]
									}, {
										columnWidth: .7,
										border:false,
										layout: 'anchor',
										defaultType: 'textfield',
										//hidden:true,
										items:[{		
											xtype:'textfield',
											readOnly:true,
											fieldLabel:'ID Karyawan',
											width:350,
											labelWidth:150,
											id:'tf_emp_id',
											value: '',
										}]
									}]
								},{
									layout: 'column',
									border: false,
									items: [{
										columnWidth:.02,
										border:false,
										layout: 'anchor',
										defaultType: 'label',
										items:[{
											xtype:'label',
											id: 'lbl_emp_name',
											html:'<div style="color:#FF0000">*</div>',
										}]
									}, {
										columnWidth: .7,
										border: false,
										layout: 'anchor',
										defaultType: 'textfield',
										items: [{		
											xtype:'textfield',
											readOnly:true,
											fieldLabel:'Nama Karyawan',
											width:350,
											labelWidth:150,
											id:'tf_emp_name',
											value: '',
										}]
									}]
								},{
									layout: 'column',
									border: false,
									items: [{
										columnWidth:.02,
										border:false,
										layout: 'anchor',
										defaultType: 'label',
										items:[{
											xtype:'label',
											id: 'lbl_org_id',
											html:'<div style="color:#FF0000">*</div>',
										}]
									}, {
										columnWidth: .7,
										border:false,
										layout: 'anchor',
										defaultType: 'textfield',
										//hidden:true,
										items:[{		
											xtype:'textfield',
											readOnly:true,
											fieldLabel:'Organization ID',
											width:350,
											labelWidth:150,
											id:'tf_org_id',
											value: '',
										}]
									}]
								},{
									layout: 'column',
									border: false,
									items: [{
										columnWidth:.02,
										border:false,
										layout: 'anchor',
										defaultType: 'label',
										items:[{
											xtype:'label',
											id: 'lbl_org_name',
											html:'<div style="color:#FF0000">*</div>',
										}]
									}, {
										columnWidth: .7,
										border: false,
										layout: 'anchor',
										defaultType: 'textfield',
										items: [{		
											xtype:'textfield',
											readOnly:true,
											fieldLabel:'Organization Unit',
											width:350,
											labelWidth:150,
											id:'tf_org',
											value: '',
										}]
									}]
								},{
									layout: 'column',
									border: false,
									items: [{
										columnWidth:.02,
										border:false,
										layout: 'anchor',
										defaultType: 'label',
										items:[{
											xtype:'label',
											id: 'lbl_dept_id',
											html:'<div style="color:#FF0000">*</div>',
										}]
									}, {
										columnWidth: .7,
										border: false,
										layout: 'anchor',
										defaultType: 'textfield',
										items: [{		
											xtype:'textfield',
											fieldLabel:'Department ID',
											readOnly:true,
											width:380,
											labelWidth:150,
											id:'tf_dept_id',
										}]
									}]
								},{
									layout: 'column',
									border: false,
									items: [{
										columnWidth:.02,
										border:false,
										layout: 'anchor',
										defaultType: 'label',
										items:[{
											xtype:'label',
											id: 'lbl_dept_name',
											html:'<div style="color:#FF0000">*</div>',
										}]
									}, {
										columnWidth: .7,
										border: false,
										layout: 'anchor',
										defaultType: 'textfield',
										items: [{		
											xtype:'textfield',
											fieldLabel:'Department',
											readOnly:true,
											width:380,
											labelWidth:150,
											id:'tf_dept_name',
										}]
									}]
								},{
									layout: 'column',
									border: false,
									items: [{
										columnWidth:.02,
										border:false,
										layout: 'anchor',
										defaultType: 'label',
										items:[{
											xtype:'label',
											id: 'lbl_plant_id',
											html:'<div style="color:#FF0000">*</div>',
										}]
									}, {
										columnWidth: .7,
										border: false,
										layout: 'anchor',
										defaultType: 'textfield',
										items: [{		
											xtype:'textfield',
											fieldLabel:'Plant ID',
											readOnly:true,
											width:380,
											labelWidth:150,
											id:'tf_plant_id',
										}]
									}]
								},{
									layout: 'column',
									border: false,
									items: [{
										columnWidth:.02,
										border:false,
										layout: 'anchor',
										defaultType: 'label',
										items:[{
											xtype:'label',
											id: 'lbl_plant_name',
											html:'<div style="color:#FF0000">*</div>',
										}]
									}, {
										columnWidth: .7,
										border: false,
										layout: 'anchor',
										defaultType: 'textfield',
										items: [{		
											xtype:'textfield',
											fieldLabel:'Plant',
											readOnly:true,
											width:380,
											labelWidth:150,
											id:'tf_plant_name',
										}]
									}]
								},{
									layout: 'column',
									border: false,
									items: [{
										columnWidth:.02,
										border:false,
										layout: 'anchor',
										defaultType: 'label',
										items:[{
											xtype:'label',
											id: 'lbl_pos_id',
											html:'<div style="color:#FF0000">*</div>',
										}]
									}, {
										columnWidth: .7,
										border: false,
										layout: 'anchor',
										defaultType: 'textfield',
										items: [{		
											xtype:'textfield',
											fieldLabel:'Pos ID',
											readOnly:true,
											width:380,
											labelWidth:150,
											id:'tf_pos_id',
										}]
									}]
								},{
									layout: 'column',
									border: false,
									items: [{
										columnWidth:.02,
										border:false,
										layout: 'anchor',
										defaultType: 'label',
										items:[{
											xtype:'label',
											id: 'lbl_pos_name',
											html:'<div style="color:#FF0000">*</div>',
										}]
									}, {
										columnWidth: .7,
										border: false,
										layout: 'anchor',
										defaultType: 'textfield',
										items: [{		
											xtype:'textfield',
											fieldLabel:'Jabatan',
											readOnly:true,
											width:380,
											labelWidth:150,
											id:'tf_pos_name',
										}]
									}]
								},{
									layout: 'column',
									border: false,
									items: [{
										columnWidth:.02,
										border:false,
										layout: 'anchor',
										defaultType: 'label',
										items:[{
											xtype:'label',
											id: 'lbl_tot_alpha',
											html:'<div style="color:#FF0000">*</div>',
										}]
									}, {
										columnWidth: .7,
										border: false,
										layout: 'anchor',
										defaultType: 'textfield',
										items: [{		
											xtype:'textfield',
											fieldLabel:'Total Alpha',
											readOnly:true,
											width:380,
											labelWidth:150,
											id:'tf_tot_alpha',
										}]
									}]
								},{
									layout: 'column',
									border: false,
									items: [{
										columnWidth:.02,
										border:false,
										layout: 'anchor',
										defaultType: 'label',
										items:[{
											xtype:'label',
											id: 'lbl_start_date_alpha',
											html:'<div style="color:#FF0000">*</div>',
										}]
									}, {
										columnWidth: .7,
										border: false,
										layout: 'anchor',
										defaultType: 'textfield',
										items: [{		
											xtype:'textfield',
											fieldLabel:'Tgl Awal Alpha',
											readOnly:true,
											width:380,
											labelWidth:150,
											id:'tf_start_date_alpha',
										}]
									}]
								},{
									layout: 'column',
									border: false,
									items: [{
										columnWidth:.02,
										border:false,
										layout: 'anchor',
										defaultType: 'label',
										items:[{
											xtype:'label',
											id: 'lbl_end_date_alpha',
											html:'<div style="color:#FF0000">*</div>',
										}]
									}, {
										columnWidth: .7,
										border: false,
										layout: 'anchor',
										defaultType: 'textfield',
										items: [{		
											xtype:'textfield',
											fieldLabel:'Tgl Akhir Alpha',
											readOnly:true,
											width:380,
											labelWidth:150,
											id:'tf_end_date_alpha',
										}]
									}]
								},{
									layout: 'column',
									border: false,
									items: [{
										columnWidth:.02,
										border:false,
										layout: 'anchor',
										defaultType: 'label',
										items:[{
											xtype:'label',
											id: 'lbl_start_date_absn',
											html:'<div style="color:#FF0000">*</div>',
										}]
									}, {
										columnWidth: .7,
										border: false,
										layout: 'anchor',
										defaultType: 'textfield',
										items: [{		
											xtype:'textfield',
											fieldLabel:'Tgl Awal Absen',
											readOnly:true,
											width:380,
											labelWidth:150,
											id:'tf_start_date_absn',
										}]
									}]
								},{
									layout: 'column',
									border: false,
									items: [{
										columnWidth:.02,
										border:false,
										layout: 'anchor',
										defaultType: 'label',
										items:[{
											xtype:'label',
											id: 'lbl_end_date_absn',
											html:'<div style="color:#FF0000">*</div>',
										}]
									}, {
										columnWidth: .7,
										border: false,
										layout: 'anchor',
										defaultType: 'textfield',
										items: [{		
											xtype:'textfield',
											fieldLabel:'Tgl Akhir Absen',
											readOnly:true,
											width:380,
											labelWidth:150,
											id:'tf_end_date_absn',
										}]
									}]
								},{
									layout: 'column',
									border: false,
									items: [{
										columnWidth:.02,
										border:false,
										layout: 'anchor',
										defaultType: 'label',
										items:[{
											xtype:'label',
											id: 'lbl_grp_salary',
											html:'<div style="color:#FF0000">*</div>',
										}]
									}, {
										columnWidth: .7,
										border: false,
										layout: 'anchor',
										defaultType: 'textfield',
										items: [{		
											xtype:'textfield',
											fieldLabel:'Group Gaji',
											readOnly:true,
											width:380,
											labelWidth:150,
											id:'tf_grp_salary',
										}]
									}]
								},{
									layout: 'column',
									border: false,
									items: [{
										columnWidth:.02,
										border:false,
										layout: 'anchor',
										defaultType: 'label',
										items:[{
											xtype:'label',
											id: 'lbl_mgr_notes',
											html:'',
										}]
									}, {
										columnWidth: .7,
										border: false,
										layout: 'anchor',
										defaultType: 'textfield',
										items: [{		
											xtype:'textfield',
											fieldLabel:'Keterangan Manager',
											width:380,
											labelWidth:150,
											id:'tf_mgr_notes',
										}]
									}]
								},
								
								{
									layout:'column',
									border:false,
									items:[,{
										columnWidth:.02,
										border:false,
										layout: 'anchor',
										defaultType: 'label',
										items:[{
											xtype:'label',
											id: 'lbl_upload_attc',
											html:'<div style="color:#FF0000">*</div>',
										}]
									},{
										columnWidth:.60,
										border:false,
										layout: 'anchor',
										defaultType: 'fieldset',
										items:[{
											xtype: 'fieldset',
											flex: 1,
											title: '<div style="color:#0F6777">Lampiran</div>',
											layout: 'anchor',
											items: [{
												layout:'column',
												border:false,
												items:[{
													columnWidth:0.7,
													border:false,
													layout: 'anchor',
													defaultType: 'textfield',
													items:[grid_upload_attc]
												},{
													columnWidth:.16,
													border:false,
													layout: 'anchor',
													defaultType: 'button',
													items:[{
														name: 'cari',
														text: 'Cari File',
														width:60,
														handler:function(){
															upload_panel_attc.show();
														}
													}]
												},{
													columnWidth:.14,
													border:false,
													layout: 'anchor',
													defaultType: 'button',
													items:[{xtype:'button',
														text:'Hapus',
														width:50,
														handler:function(){
															var hdselec = grid_upload_attc.getSelectionModel().getSelection();
															if (hdselec != ''){
																var hdid=grid_upload_attc.getSelectionModel().getSelection()[0].get('HD_ID');
																if(hdid!=''){
																	Ext.Ajax.request({
																		url:'<?php echo 'helper/delete_upload.php'; ?>',
																		params:{
																			idupload:hdid,
																		},
																		method:'POST',
																		success:function(response){
																			store_upload_attc.load();
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
									html:'<br/>',
								},
								
								{
									xtype:'panel',
									bodyStyle: 'padding-left: 100px;padding-bottom: 30px;border:none',
									items:[{
										xtype:'button',
										text:'Simpan',
										id:'btn_simpan_2',
										width:100,
										handler:function(){
											
											var err_msg = "";
											var a = 0;
											var arrDataFileAttc = new Array();
											store_upload_attc.each(
												function(record) {
													arrDataFileAttc[a]=record.get('HD_ID');
													a++;
												}
											);
											
											if(a < 1)
											{
												err_msg = err_msg + "- Lampiran Harus Diisi<br>";
											}
											
											if(err_msg == "")
											{
												Ext.Ajax.request({
													url:'controllers/freeze_employee.php',
													params:{
														trans_id:Ext.getCmp('tf_trans_id').getValue(),
														emp_id:Ext.getCmp('tf_emp_id').getValue(),
														emp_name:Ext.getCmp('tf_emp_name').getValue(),
														org_id:Ext.getCmp('tf_org_id').getValue(),
														org_name:Ext.getCmp('tf_org').getValue(),
														dept_id:Ext.getCmp('tf_dept_id').getValue(),
														dept_name:Ext.getCmp('tf_dept_name').getValue(),
														plant_id:Ext.getCmp('tf_plant_id').getValue(),
														plant_name:Ext.getCmp('tf_plant_name').getValue(),
														pos_id:Ext.getCmp('tf_pos_id').getValue(),
														pos_name:Ext.getCmp('tf_pos_name').getValue(),
														tot_alpha:Ext.getCmp('tf_tot_alpha').getValue(),
														start_date_alpha:Ext.getCmp('tf_start_date_alpha').getValue(),
														end_date_alpha:Ext.getCmp('tf_end_date_alpha').getValue(),
														start_date_absn:Ext.getCmp('tf_start_date_absn').getValue(),
														end_date_absn:Ext.getCmp('tf_end_date_absn').getValue(),
														grp_salary:Ext.getCmp('tf_grp_salary').getValue(),
														mgr_notes:Ext.getCmp('tf_mgr_notes').getValue(),
														typeform:Ext.getCmp('tf_formType').getValue(),
																	
														activeStatus:'Y',
														formType:'UpdateReqUnfreeze',
													},
													method:'POST',
													success:function(response){
														var json=Ext.decode(response.responseText);
														var jsonresults = json.results;
														
														if (json.rows == "sukses") {
															Ext.MessageBox.hide();
															alertDialog('Sukses', "Data berhasil disimpan. ");
															//Ext.clearForm();
															store_detil.load({
																params:{
																	org_id:Ext.getCmp('cb_organization_units').getValue(),
																	plant:Ext.getCmp('cb_plant').getValue(),
																	dept:Ext.getCmp('cb_department').getValue(),
																	emp_freeze:Ext.getCmp('cb_emp_freeze').getValue(),
																	absn_period:Ext.getCmp('cb_absence_period').getValue(),
																	formType:'ReqUnfreeze',
																}
															});
															win_apv.close(); 
														} else {
															if (json.rows == "sudahapprove") {
																Ext.MessageBox.hide();
																alertDialog( 'Peringatan', 'Data gagal disimpan, karena data sedang proses Approve.' );
															}else {
																Ext.MessageBox.hide();
																alertDialog('Kesalahan', json.rows);
															}
														}
													},
													failure:function(error){
														Ext.MessageBox.hide();
														alertDialog('Kesalahan','Data gagal disimpan');
													}
												});
											}
											else
											{
												alertDialog('Peringatan', err_msg);
											}
										}
									},{
										xtype:'label',
										html:'&nbsp;&nbsp;',
									},
									{		
										xtype:'button',
										text:'Close',
										id:'btn_cancel2',
										width:100,
										handler:function(){
											store_detil.load({
												params:{
													org_id:Ext.getCmp('cb_organization_units').getValue(),
													plant:Ext.getCmp('cb_plant').getValue(),
													dept:Ext.getCmp('cb_department').getValue(),
													emp_freeze:Ext.getCmp('cb_emp_freeze').getValue(),
													absn_period:Ext.getCmp('cb_absence_period').getValue(),
													formType:'ReqUnfreeze',
												}
											});
											this.up('window').close(); 
											
										}
									}]
								},
								
							]
						});
					
						var win_apv= Ext.create('Ext.Window',{
										title:'Detail Form Request Unfreeze',
										width: 750,
										height: 650,						
										closeAction:'hide',
										autoScroll:true,
										items:[CP_CL]
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