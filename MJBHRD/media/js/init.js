//First of you will need to enable to loader - dynamic dependency loader

Ext.Loader.setConfig({enabled:true});

Ext.application({

name: 'App', //name this as you like
autoCreateViewport: true, //set as true - it will seach for the Viewport.js file automatically
launch:function(){
//similar to jQuerys $(document).ready, fired on when application is ready
}

});