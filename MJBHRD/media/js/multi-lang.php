

Ext.onReady(function(){
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
			'media/js/locale/ext-lang-id.js',
			setupApp,
			Ext.emptyFn,
			this,
			'ascii'
		);
});         
var setupDemo = function() {
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

