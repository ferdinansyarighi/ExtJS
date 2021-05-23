Ext.Loader.setConfig({enabled: true});

Ext.require([
    'Ext.grid.*',
    'Ext.panel.*',
    'Ext.tip.QuickTipManager',
    'Ext.ux.data.PagingMemoryProxy',
	'Ext.selection.CheckboxModel'
]);
	
Ext.onReady(function () {
		Ext.tip.QuickTipManager.init();
		Ext.Loader.injectScriptElement(
			'../media/js/locale/ext-lang-id.js',
			setupDemo,
			Ext.emptyFn,
			this,
			'ascii'
		);
});         

function setupDemo (){
                /* Email field */
                Ext.create('Ext.FormPanel', {
                    renderTo: 'emailfield',
                    labelWidth: 100, // label settings here cascade unless overridden
                    frame: true,
                    title: 'Email Field',
                    bodyStyle: 'padding:5px 5px 0',
                    width: 380,
                    defaults: {
                        msgTarget: 'side',
                        width: 340
                    },
                    defaultType: 'textfield',
                    items: [{
                        fieldLabel: 'Email',
                        name: 'email',
                        vtype: 'email'
                    }]
                });
            
                /* Datepicker */
                Ext.create('Ext.FormPanel', {
                    renderTo: 'datefield',
                    labelWidth: 100, // label settings here cascade unless overridden
                    frame: true,
                    title: 'Datepicker',
                    bodyStyle: 'padding:5px 5px 0',
                    width: 380,
                    defaults: {
                        msgTarget: 'side',
                        width: 340
                    },
                    defaultType: 'datefield',
                    items: [{
                        fieldLabel: 'Date',
                        name: 'date'
                    }]
                });
            
                // shorthand alias                
                var monthArray = Ext.Array.map(Ext.Date.monthNames, function (e) { return [e]; });
                var ds = Ext.create('Ext.data.Store', {
                     fields: ['month'],
                     remoteSort: true,
                     pageSize: 6,
                     proxy: {
                         type: 'pagingmemory',
                         data: monthArray,
                         reader: {
                             type: 'array'
                         }
                     }
                 });
                             
                 Ext.create('Ext.grid.Panel', {
                     renderTo: 'grid',
                     width: 380,
                     height: 203,
                     title:'Month Browser',
                     columns:[{
                         text: 'Month of the year',
                         dataIndex: 'month',
                         width: 240 
                     }],
                     store: ds,            
                     bbar: Ext.create('Ext.toolbar.Paging', {
                             pageSize: 6,
                             store: ds,
                             displayInfo: true
                     })
                 });
            
                // trigger the data store load
                ds.load();            
}
function setupApp(){
	//Ext.QuickTips.init();
	var tbmenu = Ext.create('Ext.toolbar.Toolbar',{
			cls: 'tbmenu',
			id : 'maintoolbar',
			items: [{ xtype: 'button', text: 'Rencana Aksi', iconCls: 'aksi', scale: 'large', iconAlign: 'left', minWidth: 90, handler: function () {window.location.href='http://localhost/linux/application/2012/inpres_bnn/rencana_aksi';}},{ xtype: 'button', text: 'Pendataan', iconCls: 'pendataan', scale: 'large', iconAlign: 'left', minWidth: 90, handler: function () {window.location.href='http://localhost/linux/application/2012/inpres_bnn/pendataan';}},{ xtype: 'button', text: 'Laporan', iconCls: 'laporan', scale: 'large', iconAlign: 'left', minWidth: 90, handler: function () {window.location.href='http://localhost/linux/application/2012/inpres_bnn/laporan';}},{ xtype: 'button', text: 'Statistik', iconCls: 'statistik', scale: 'large', iconAlign: 'left', minWidth: 90, handler: function () {window.location.href='http://localhost/linux/application/2012/inpres_bnn/statistik';}},{ xtype: 'button', text: 'Master', iconCls: 'master', scale: 'large', iconAlign: 'left', minWidth: 90, handler: function () {window.location.href='http://localhost/linux/application/2012/inpres_bnn/master';},cls : 'x-btn-activ'},'->', '-', {text: 'Pengaturan',iconCls: 'pengaturan',handler: function () { pengaturanWin.show(); }},{text: 'Ubah Password',iconCls: 'ubah_sandi',handler: function () { ubahPasswordWindow.show(); }},{text: 'Keluar', iconCls: 'logout', handler: function () { window.location.href='http://localhost/linux/application/2012/inpres_bnn/login/logout'; } }]	});
	var pengaturanWin = Ext.create('Ext.window.Window',{
		title: 'Pengaturan',
		id:'pengaturanWin',
		closeAction: 'hide',
		items: {
			xtype: 'form',
			id:'formPengaturan',
			bodyPadding: 5,
			layout: 'absolute',
			fieldDefaults: {
				msgTarget: 'side',
				// labelWidth: 150,
				// labelAlign: 'right'
			},
			defaultType: 'textfield',
			items: [{
				x: 10,
				y: 10,
				xtype:'label',
				text: 'Periode:'
			},{
				x: 110,
				y: 10,
				width: 100,
				disabled: false,allowBlank: false,value: "2011",				xtype: 'datefield',
				format: 'Y',
				name:'awal'
			},{
				x: 220,
				y: 10,
				xtype:'label',
				text: 's/d:'
			},{
				x: 240,
				y: 10,
				width: 100,
				disabled: false,allowBlank: false,value: "2015",				xtype: 'datefield',
				format: 'Y',
				name:'akhir'
			},{
				x: 10,
				y: 40,
				xtype:'label',
				text: 'Tampilkan Tahun :'
			},{
				x: 110,
				y: 40,
				width: 100,
				allowBlank: false,
				xtype: 'datefield',
				format: 'Y',
				value: "2011",				name:'tampil'
			}],
			 buttons: [{
				text: 'Save',
				formBind: true,
				disabled: true,
				handler:function(){
				  Ext.getCmp('formPengaturan').getForm().submit({
					waitTitle:'Menyimpan...', 
					waitMsg:'Mohon tunggu, sedang menyimpan...',
					url: 'http://localhost/linux/application/2012/inpres_bnn/systems/setting_tahun',
					success: function() {
					   Ext.getCmp('formPengaturan').getForm().reset();
						Ext.getCmp('pengaturanWin').hide();
						location.reload();
					},
					failure: function(form, action) {
						// Ext.getCmp('formPengaturan').getForm().reset();
						Ext.Msg.alert('Failure', action.result.msg);
						 }
					});
				}
			},{
				text: 'Cancel',
				handler:function(){
					Ext.getCmp('pengaturanWin').hide();
				}
			}]
		}
	});
	var ubahPasswordWindow = Ext.create('Ext.window.Window',{
		title: 'Ubah Password',
		id:'ubahWin',
		closeAction: 'hide',
		items: {
			xtype: 'form',
			id:'formUbah',
			bodyPadding: 5,
			fieldDefaults: {
				msgTarget: 'side',
			},
			defaultType: 'textfield',
			items: [{
				hidden: true,
				value:"1",				name:'userid'
				},{
				fieldLabel: 'Password Baru',
				allowBlank: false,
				name: 'password',
				inputType: 'password'
			}],
			 buttons: [{
				text: 'Save',
				formBind: true,
				disabled: true,
				handler:function(){
				  Ext.getCmp('formUbah').getForm().submit({
					waitTitle:'Menyimpan...', 
					waitMsg:'Mohon tunggu, sedang menyimpan...',
					url: 'http://localhost/linux/application/2012/inpres_bnn/systems/ubah_password',
					success: function() {
					   Ext.getCmp('formUbah').getForm().reset();
						Ext.getCmp('ubahWin').hide();
						// location.reload();
					},
					failure: function(form, action) {
						Ext.Msg.alert('Failure', action.result.msg);
						 }
					});
				}
			},{
				text: 'Cancel',
				handler:function(){
					Ext.getCmp('ubahWin').hide();
				}
			}]
		}
	});
	var urlbidang = 'http://localhost/linux/application/2012/inpres_bnn/bidang/simpan_bidang';
var urltujuan = 'http://localhost/linux/application/2012/inpres_bnn/bidang/simpan_tujuan';
var urlindikator = 'http://localhost/linux/application/2012/inpres_bnn/bidang/simpan_indikator';
 var storeBidang = Ext.create('Ext.data.Store', {
        pageSize: 10,
        fields: [
            'bidangid','bidang'
        ],
		autoLoad: true,
        remoteSort: true,
        proxy: {
            type: 'jsonp',
            url: 'http://localhost/linux/application/2012/inpres_bnn/bidang/get_bidang',
            reader: {
                root: 'bidang',
                totalProperty: 'totalCount'
            },
            simpleSortMode: true
        },
        sorters: [{
            property: 'bidangid',
            direction: 'ASc'
        }]
    });
	 var storeTujuan = Ext.create('Ext.data.Store', {
        pageSize: 10,
        fields: [
            'tujuanid','tujuan'
        ],
        remoteSort: true,
        proxy: {
            type: 'jsonp',
            url: 'http://localhost/linux/application/2012/inpres_bnn/bidang/get_tujuan',
            reader: {
                root: 'tujuan',
                totalProperty: 'totalCount'
            },
            simpleSortMode: true
        },
        sorters: [{
            property: 'tujuanid',
            direction: 'ASc'
        }]
    });
	 var storeIndikator = Ext.create('Ext.data.Store', {
        pageSize: 10,
        fields: [
            'indikatorid','indikator'
        ],
        remoteSort: true,
        proxy: {
            type: 'jsonp',
            url: 'http://localhost/linux/application/2012/inpres_bnn/bidang/get_indikator',
            reader: {
                root: 'indikator',
                totalProperty: 'totalCount'
            },
            simpleSortMode: true
        },
        sorters: [{
            property: 'indikatorid',
            direction: 'ASc'
        }]
    });
var formBidang = Ext.create('Ext.form.Panel',{
		bodyPadding: 5,
		width: 300,
		url: 'save-form.php',
		layout: 'anchor',
        fieldDefaults: {
            msgTarget: 'side',
            labelAlign: 'left'
        },
        defaultType: 'textfield',
        items: [{
            fieldLabel: 'Bidang',
			id:'idBidang',
			hidden:true,
			labelWidth: 50,
			anchor: '100%',
            name: 'bidangid'
        },{
            fieldLabel: 'Bidang',
			labelWidth: 50,
			anchor: '100%',
			allowBlank: false,
            name: 'bidang'
        }],
        buttons: [{
            text: 'Save',
			formBind: true,
			disabled: true, 
			handler: function(){
				formBidang.getForm().submit({
									waitTitle:'Saving...', 
									waitMsg:'Please wait, Sending data...',
									url: urlbidang,
									success: function() {
									   formBidang.getForm().reset();
										Ext.getCmp('addWinBidang').hide();
									   storeBidang.load();
									},
									failure: function(form, action) {
										formBidang.getForm().reset();
											switch (action.failureType) {
												case Ext.form.action.Action.CLIENT_INVALID:
													Ext.Msg.alert('Failure', 'Form fields may not be submitted with invalid values');
													break;
												case Ext.form.action.Action.CONNECT_FAILURE:
													Ext.Msg.alert('Failure', 'Ajax communication failed');
													break;
												case Ext.form.action.Action.SERVER_INVALID:
												   Ext.Msg.alert('Failure', action.result.msg);
										   }
										 }
									});
			}
        },{
            text: 'Cancel',
			handler: function(){
				formBidang.getForm().reset();
				Ext.getCmp('addWinBidang').hide();
			}
        }]
	});
var addWinBidang = Ext.create('Ext.window.Window',{
		title: 'Tambah Bidang',
		id:'addWinBidang',
		closeAction: 'hide',
		modal: true,
		height: 100,
		width: 310,
		layout: 'fit',
		items: [formBidang]
	});
var formTujuan = Ext.create('Ext.form.Panel',{
		bodyPadding: 5,
		width: 300,
		url: 'save-form.php',
		layout: 'anchor',
        fieldDefaults: {
            msgTarget: 'side',
            labelAlign: 'left'
        },
        defaultType: 'textfield',
        items: [{
            fieldLabel: 'Tujuan',
			labelWidth: 50,
			hidden:true,
			id:'idTujuan',
            name: 'tujuanid'
        },{
			labelWidth: 50,
			hidden:true,
			id:'idBidangTujuan',
            name: 'bidangid'
        },{
            fieldLabel: 'Tujuan',
			labelWidth: 50,
			anchor: '100%',
			allowBlank: false,
            name: 'tujuan'
        }],
        buttons: [{
            text: 'Save',
			formBind: true,
			disabled: true,
			handler: function(){
				formTujuan.getForm().submit({
									waitTitle:'Saving...', 
									waitMsg:'Please wait, Sending data...',
									url: urltujuan,
									success: function() {
									   formTujuan.getForm().reset();
										Ext.getCmp('addWinTujuan').hide();
										var idx = Ext.getCmp('gridBidang').getSelectionModel().getSelection();
									   storeTujuan.load({params:{id:idx[0].get('bidangid')}});
									},
									failure: function(form, action) {
										formTujuan.getForm().reset();
											switch (action.failureType) {
												case Ext.form.action.Action.CLIENT_INVALID:
													Ext.Msg.alert('Failure', 'Form fields may not be submitted with invalid values');
													break;
												case Ext.form.action.Action.CONNECT_FAILURE:
													Ext.Msg.alert('Failure', 'Ajax communication failed');
													break;
												case Ext.form.action.Action.SERVER_INVALID:
												   Ext.Msg.alert('Failure', action.result.msg);
										   }
										 }
									});
			}
        },{
            text: 'Cancel',
			handler: function(){
				formTujuan.getForm().reset();
				Ext.getCmp('addWinTujuan').hide();
			}
        }]
	});
var addWinTujuan = Ext.create('Ext.window.Window',{
		title: 'Tambah Tujuan',
		closeAction: 'hide',
		id:'addWinTujuan',
		modal: true,
		height: 100,
		width: 310,
		layout: 'fit',
		items: [formTujuan]
	});
var formIndikator = Ext.create('Ext.form.Panel',{
		bodyPadding: 5,
		width: 300,
		url: 'save-form.php',
		layout: 'anchor',
        fieldDefaults: {
            msgTarget: 'side',
            labelAlign: 'left'
        },
        defaultType: 'textfield',
        items: [{
			labelWidth: 50,
			hidden:true,
			id:'idIndikator',
            name: 'indikatorid'
        },{
			labelWidth: 50,
			hidden:true,
			id:'idTujuanIndikator',
            name: 'tujuanid'
        },{
            fieldLabel: 'Indikator',
			labelWidth: 50,
			anchor: '100%',
			allowBlank: false,
            name: 'indikator'
        }],
        buttons: [{
            text: 'Save',
			formBind: true,
			disabled: true,
			handler: function(){
				formIndikator.getForm().submit({
									waitTitle:'Saving...', 
									waitMsg:'Please wait, Sending data...',
									url: urlindikator,
									success: function() {
									   formIndikator.getForm().reset();
										Ext.getCmp('addWinIndikator').hide();
									   var idx = Ext.getCmp('gridTujuan').getSelectionModel().getSelection();
									   storeIndikator.load({params:{id:idx[0].get('tujuanid')}});
									},
									failure: function(form, action) {
										formIndikator.getForm().reset();
											switch (action.failureType) {
												case Ext.form.action.Action.CLIENT_INVALID:
													Ext.Msg.alert('Failure', 'Form fields may not be submitted with invalid values');
													break;
												case Ext.form.action.Action.CONNECT_FAILURE:
													Ext.Msg.alert('Failure', 'Ajax communication failed');
													break;
												case Ext.form.action.Action.SERVER_INVALID:
												   Ext.Msg.alert('Failure', action.result.msg);
										   }
										 }
									});
			}
        },{
            text: 'Cancel',
			handler: function(){
				formIndikator.getForm().reset();
				Ext.getCmp('addWinIndikator').hide();
			}
        }]
	});
var addWinIndikator = Ext.create('Ext.window.Window',{
		title: 'Tambah Indikator',
		id:'addWinIndikator',
		closeAction: 'hide',
		modal: true,
		height: 100,
		width: 310,
		layout: 'fit',
		items: [formIndikator]
	});

	var nav_panel = Ext.create('Ext.menu.Menu', {
		title: 'Master',
		id: 'test',
		width: 250,
		region: 'west',
        showSeparator: false,
		floating: false,
		// cls: 'bs',
		bodyCls: 'bsm',
		// defaults: {bodyBorder: true},
		items: [{
			text: 'Bidang',
			cls: 'nav-clicked',
			handler: function(){
				window.location = 'http://localhost/linux/application/2012/inpres_bnn/master/bidang';
			}
		},{
			text: 'Pelaksana',
			handler: function(){
				window.location = 'http://localhost/linux/application/2012/inpres_bnn/master/pelaksana';
			}
		},{
			text: 'Rencana Aksi',
			handler: function(){
				window.location = 'http://localhost/linux/application/2012/inpres_bnn/master/aksi';
			}
		},{
			text: 'Masalah',
			handler: function(){
				window.location = 'http://localhost/linux/application/2012/inpres_bnn/master/masalah';
			}
		},{
			text: 'User',
			handler: function(){
				window.location = 'http://localhost/linux/application/2012/inpres_bnn/master/user';
			}
		},{
			text: 'Usergroup',
			handler: function(){
				window.location = 'http://localhost/linux/application/2012/inpres_bnn/master/usergroup';
			}
		}]
	});	var panelItem = 
		Ext.create('Ext.panel.Panel',		
			{
				layout: 'border',
				defaults: {
					split: true
				},
				items: [
								nav_panel
							,
							{
								region: 'center',
								xtype: 'panel', 
								defaults: {
									split: true
								},
								layout: 'border',
								items: [{
											xtype: 'grid',
											id:'gridBidang',
											title: 'Master Bidang',
											store: storeBidang,
											border: false,
											region:'center',
											tbar: ['->',{text:'Tambah',iconCls:'tambah',
													handler:function(){
														urlbidang = 'http://localhost/linux/application/2012/inpres_bnn/bidang/simpan_bidang';
														addWinBidang.show();
													}
												},
												{text:'Ubah',iconCls:'ubah',
													handler:function(){
														var idx = Ext.getCmp('gridBidang').getSelectionModel().getSelection();
														if(idx.length > 0){
															formBidang.load({
																url: 'http://localhost/linux/application/2012/inpres_bnn/bidang/get_bidang_by',
																method: 'POST',
																params: {id:idx[0].get('bidangid')}
															});
															Ext.getCmp('idBidang').setValue(idx[0].get('bidangid'));
															urlbidang = 'http://localhost/linux/application/2012/inpres_bnn/bidang/edit_bidang';
															addWinBidang.setTitle('Ubah Bidang');
															addWinBidang.show();
														}
														else{
															Ext.Msg.alert('Pesan','Pilih data bidang terlebih dahulu');
														}
													}
												},
												{text:'Hapus',iconCls:'hapus',
													handler: function(){
														var idx = Ext.getCmp('gridBidang').getSelectionModel().getSelection();
														if(idx.length > 0){
															Ext.Ajax.request({							
																			url: 'http://localhost/linux/application/2012/inpres_bnn/bidang/cek_bidang', 
																			params : {
																				id:idx[0].get('bidangid')
																			},
																			method: 'POST',
																			success: function ( result, action ) {	
																				if(result.responseText==1){
																					Ext.Msg.show({
																					   title:'Hapus Bidang',
																					   msg: 'Apakah anda yakin akan menghapus bidang ini dan seluruh tujuan, indikator, dll di bawahnya?',
																					   buttons: Ext.Msg.YESNO,
																					   fn: function (btn){
																							if(btn == 'yes'){
																								Ext.Ajax.request({							
																									url: 'http://localhost/linux/application/2012/inpres_bnn/bidang/hapus_bidang', 
																									params : {
																										id:idx[0].get('bidangid')
																									},
																									method: 'POST',
																									success: function ( result, request ) {								
																										storeBidang.load();
																										storeTujuan.load({params:{id:0}});
																										storeIndikator.load({params:{id:0}});
																									},
																									failure: function ( result, action) { 
																										switch (action.failureType) {
																											case Ext.form.action.Action.CLIENT_INVALID:
																												Ext.Msg.alert('Failure', 'Form fields may not be submitted with invalid values');
																												break;
																											case Ext.form.action.Action.CONNECT_FAILURE:
																												Ext.Msg.alert('Failure', 'Ajax communication failed');
																												break;
																											case Ext.form.action.Action.SERVER_INVALID:
																											   Ext.Msg.alert('Failure', action.result.msg);
																									   }
																									} 
																								});
																							}
																					   },
																					   animEl: 'elId',
																					   icon: Ext.MessageBox.QUESTION
																					});
																				}
																				else{
																					Ext.Ajax.request({							
																									url: 'http://localhost/linux/application/2012/inpres_bnn/bidang/hapus_bidang', 
																									params : {
																										id:idx[0].get('bidangid')
																									},
																									method: 'POST',
																									success: function ( result, request ) {								
																										storeBidang.load();
																										storeTujuan.load({params:{id:0}});
																										storeIndikator.load({params:{id:0}});
																									},
																									failure: function ( result, action) { 
																										switch (action.failureType) {
																											case Ext.form.action.Action.CLIENT_INVALID:
																												Ext.Msg.alert('Failure', 'Form fields may not be submitted with invalid values');
																												break;
																											case Ext.form.action.Action.CONNECT_FAILURE:
																												Ext.Msg.alert('Failure', 'Ajax communication failed');
																												break;
																											case Ext.form.action.Action.SERVER_INVALID:
																											   Ext.Msg.alert('Failure', action.result.msg);
																									   }
																									} 
																								});
																				}
																			}
															});
															
														}else{
															Ext.Msg.alert('Pesan', 'Pilih data yang akan dihapus');
														}
													  }
												}],
											bbar: Ext.create('Ext.PagingToolbar', {
												store: storeBidang,
												displayInfo: true,
												displayMsg: 'Displaying topics {0} - {1} of {2}',
												emptyMsg: "No topics to display"
											}),
											columns: [{xtype: 'rownumberer',text: 'NO',width:80},
											{hidden:true, dataIndex:'bidangid',text: 'Bidang'},
											{dataIndex:'bidang',text: 'Bidang',flex:1}
											]
										},{
											xtype: 'grid',
											id:'gridTujuan',
											border: false,
											title: 'Master tujuan',
											region:'east',
											width: '60%',
											bbar: Ext.create('Ext.PagingToolbar', {
												store: storeTujuan,
												displayInfo: true,
												displayMsg: 'Displaying topics {0} - {1} of {2}',
												emptyMsg: "No topics to display"
											}),
											tbar: ['->',{text:'Tambah',iconCls:'tambah',
													handler:function(){
														var idx = Ext.getCmp('gridBidang').getSelectionModel().getSelection();
														if(idx.length > 0){
															urltujuan = 'http://localhost/linux/application/2012/inpres_bnn/bidang/simpan_tujuan';
															Ext.getCmp('idBidangTujuan').setValue(idx[0].get('bidangid'));
															addWinTujuan.show();
														}
														else{
															Ext.Msg.alert('Pesan','Pilih data bidang terlebih dahulu');
														}
													}
											},
												{text:'Ubah',iconCls:'ubah',
													handler:function(){
														var idx = Ext.getCmp('gridTujuan').getSelectionModel().getSelection();
														if(idx.length > 0){
															formTujuan.load({
																url: 'http://localhost/linux/application/2012/inpres_bnn/bidang/get_tujuan_by',
																method: 'POST',
																params: {id:idx[0].get('tujuanid')}
															});
															Ext.getCmp('idTujuan').setValue(idx[0].get('tujuanid'));
															urltujuan = 'http://localhost/linux/application/2012/inpres_bnn/bidang/edit_tujuan';
															addWinIndikator.setTitle('Ubah Tujuan');
															addWinTujuan.show();
														}
														else{
															Ext.Msg.alert('Pesan','Pilih data tujuan terlebih dahulu');
														}
													}
												},
												{text:'Hapus',iconCls:'hapus',
													handler: function(){
														var idx = Ext.getCmp('gridTujuan').getSelectionModel().getSelection();
														var idx2 = Ext.getCmp('gridBidang').getSelectionModel().getSelection();
														if(idx.length > 0){
														Ext.Ajax.request({							
																			url: 'http://localhost/linux/application/2012/inpres_bnn/bidang/cek_tujuan', 
																			params : {
																				id:idx[0].get('tujuanid')
																			},
																			method: 'POST',
																			success: function ( result, action ) {	
																				if(result.responseText==1){
																					Ext.Msg.show({
																					   title:'Hapus Tujuan',
																					   msg: 'Apakah anda yakin akan menghapus tujuan, indikator, dll di bawahnya??',
																					   buttons: Ext.Msg.YESNO,
																					   fn: function (btn){
																							if(btn == 'yes'){
																								Ext.Ajax.request({							
																									url: 'http://localhost/linux/application/2012/inpres_bnn/bidang/hapus_tujuan', 
																									params : {
																										id:idx[0].get('tujuanid')
																									},
																									method: 'POST',
																									success: function ( result, request ) {								
																										 storeIndikator.load({params:{id:0}});
																										 storeTujuan.load({params:{id:idx2[0].get('bidangid')}});
																									},
																									failure: function ( result, action) { 
																										switch (action.failureType) {
																											case Ext.form.action.Action.CLIENT_INVALID:
																												Ext.Msg.alert('Failure', 'Form fields may not be submitted with invalid values');
																												break;
																											case Ext.form.action.Action.CONNECT_FAILURE:
																												Ext.Msg.alert('Failure', 'Ajax communication failed');
																												break;
																											case Ext.form.action.Action.SERVER_INVALID:
																											   Ext.Msg.alert('Failure', action.result.msg);
																									   }
																									} 
																								});
																							}
																					   },
																					   animEl: 'elId',
																					   icon: Ext.MessageBox.QUESTION
																					});
																				}
																				else{
																					Ext.Ajax.request({							
																									url: 'http://localhost/linux/application/2012/inpres_bnn/bidang/hapus_tujuan', 
																									params : {
																										id:idx[0].get('tujuanid')
																									},
																									method: 'POST',
																									success: function ( result, request ) {								
																										 storeIndikator.load({params:{id:0}});
																										 storeTujuan.load({params:{id:idx2[0].get('bidangid')}});
																									},
																									failure: function ( result, action) { 
																										switch (action.failureType) {
																											case Ext.form.action.Action.CLIENT_INVALID:
																												Ext.Msg.alert('Failure', 'Form fields may not be submitted with invalid values');
																												break;
																											case Ext.form.action.Action.CONNECT_FAILURE:
																												Ext.Msg.alert('Failure', 'Ajax communication failed');
																												break;
																											case Ext.form.action.Action.SERVER_INVALID:
																											   Ext.Msg.alert('Failure', action.result.msg);
																									   }
																									} 
																								});
																				}
																			}
															});
														}else{
															Ext.Msg.alert('Pesan', 'Pilih data yang akan dihapus');
														}
													  }
												}],
											columns: [{xtype: 'rownumberer',text: 'NO',width:80},
											{hidden:true, dataIndex:'tujuanid',text: 'tujuanid'},
											{dataIndex:'tujuan',text: 'Tujuan',flex:1}
											],
											store: storeTujuan
									},{
											xtype: 'grid',
											id:'gridIndikator',
											title: 'Master Indikator',
											border: false,
											region:'south',
											height: '40%',
											bbar: Ext.create('Ext.PagingToolbar', {
												store: storeIndikator,
												displayInfo: true,
												displayMsg: 'Displaying topics {0} - {1} of {2}',
												emptyMsg: "No topics to display"
											}),
											tbar: ['->',{text:'Tambah',iconCls:'tambah',
													handler:function(){
														var idx = Ext.getCmp('gridTujuan').getSelectionModel().getSelection();
														if(idx.length > 0){
															urlindikator = 'http://localhost/linux/application/2012/inpres_bnn/bidang/simpan_indikator';
															Ext.getCmp('idTujuanIndikator').setValue(idx[0].get('tujuanid'));
															addWinIndikator.show();
														}
														else{
															Ext.Msg.alert('Pesan','Pilih data tujuan terlebih dahulu');
														}
													}
											},
												{text:'Ubah',iconCls:'ubah',
													handler:function(){
														var idx = Ext.getCmp('gridIndikator').getSelectionModel().getSelection();
														if(idx.length > 0){
															formIndikator.load({
																url: 'http://localhost/linux/application/2012/inpres_bnn/bidang/get_indikator_by',
																method: 'POST',
																params: {id:idx[0].get('indikatorid')}
															});
															Ext.getCmp('idIndikator').setValue(idx[0].get('indikatorid'));
															urlindikator = 'http://localhost/linux/application/2012/inpres_bnn/bidang/edit_indikator';
															addWinIndikator.setTitle('Ubah Indikator');
															addWinIndikator.show();
														}
														else{
															Ext.Msg.alert('Pesan','Pilih data indikator terlebih dahulu');
														}
													}
												},
												{text:'Hapus',iconCls:'hapus',
													handler: function(){
														var idx = Ext.getCmp('gridIndikator').getSelectionModel().getSelection();
														var idx2 = Ext.getCmp('gridTujuan').getSelectionModel().getSelection();
														if(idx.length > 0){
														Ext.Ajax.request({							
																			url: 'http://localhost/linux/application/2012/inpres_bnn/bidang/cek_indikator', 
																			params : {
																				id:idx[0].get('indikatorid')
																			},
																			method: 'POST',
																			success: function ( result, action ) {	
																				if(result.responseText==1){
																					Ext.Msg.show({
																					   title:'Hapus Indikator',
																					   msg: 'Apakah anda yakin menghapus data indikator dan data dibawahnya?',
																					   buttons: Ext.Msg.YESNO,
																					   fn: function (btn){
																							if(btn == 'yes'){
																								Ext.Ajax.request({							
																									url: 'http://localhost/linux/application/2012/inpres_bnn/bidang/hapus_indikator', 
																									params : {
																										id:idx[0].get('indikatorid')
																									},
																									method: 'POST',
																									success: function ( result, request ) {								
																										 storeIndikator.load({params:{id:idx2[0].get('tujuanid')}});
																									},
																									failure: function ( result, action) { 
																										switch (action.failureType) {
																											case Ext.form.action.Action.CLIENT_INVALID:
																												Ext.Msg.alert('Failure', 'Form fields may not be submitted with invalid values');
																												break;
																											case Ext.form.action.Action.CONNECT_FAILURE:
																												Ext.Msg.alert('Failure', 'Ajax communication failed');
																												break;
																											case Ext.form.action.Action.SERVER_INVALID:
																											   Ext.Msg.alert('Failure', action.result.msg);
																									   }
																									} 
																								});
																							}
																					   },
																					   animEl: 'elId',
																					   icon: Ext.MessageBox.QUESTION
																					});
																					}
																					else{
																						Ext.Ajax.request({							
																									url: 'http://localhost/linux/application/2012/inpres_bnn/bidang/hapus_indikator', 
																									params : {
																										id:idx[0].get('indikatorid')
																									},
																									method: 'POST',
																									success: function ( result, request ) {								
																										 storeIndikator.load({params:{id:idx2[0].get('tujuanid')}});
																									},
																									failure: function ( result, action) { 
																										switch (action.failureType) {
																											case Ext.form.action.Action.CLIENT_INVALID:
																												Ext.Msg.alert('Failure', 'Form fields may not be submitted with invalid values');
																												break;
																											case Ext.form.action.Action.CONNECT_FAILURE:
																												Ext.Msg.alert('Failure', 'Ajax communication failed');
																												break;
																											case Ext.form.action.Action.SERVER_INVALID:
																											   Ext.Msg.alert('Failure', action.result.msg);
																									   }
																									} 
																								});
																					}
																			}
															});
														}else{
															Ext.Msg.alert('Pesan', 'Pilih data yang akan dihapus');
														}
													  }
												}],
											columns: [{xtype: 'rownumberer',text: 'NO',width:80},
											{hidden:true, dataIndex:'indikatorid',text: 'indikatorid'},
											{dataIndex:'indikator',text: 'Indikator',flex:1}
											],
											store: storeIndikator
										}
								]
							}
						]
			}	
		);
	Ext.getCmp('gridBidang').on('cellClick', function(){
			var idx = Ext.getCmp('gridBidang').getSelectionModel().getSelection();
			storeTujuan.load({params:{id:idx[0].get('bidangid')}});
			storeIndikator.load({params:{id:0}});
			Ext.getCmp('idBidangTujuan').setValue(idx[0].get('bidangid'));
	});
	Ext.getCmp('gridTujuan').on('cellClick', function(){
			var idx = Ext.getCmp('gridTujuan').getSelectionModel().getSelection();
			storeIndikator.load({params:{id:idx[0].get('tujuanid')}});
			Ext.getCmp('idTujuanIndikator').setValue(idx[0].get('tujuanid'));
	});
						var contentPanel = Ext.create('Ext.panel.Panel',{
			xtype:'panel',
			id: 'content-panel',
			region: 'center', 
			layout: 'fit',
			margins: '2 2 2 2',
			border: false,
			tbar: tbmenu
			,bbar: [{
				xtype: 'tbtext',
				text: '&copy;2011 BADAN NARKOTIKA NASIONAL. All Rights reserved. '
			}],
			items:[
					panelItem
				]
		});
    Ext.create('Ext.container.Viewport', {
        layout: 'border',
		title: 'SMEP',
        items: [
            {
			xtype: 'box',
			region: 'north',
			applyTo: 'header',
			height: 30
		},
			contentPanel
        ]
    });
}
