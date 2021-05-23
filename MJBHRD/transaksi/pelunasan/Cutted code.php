
<?PHP

	// store_detil.removeAll();
	// store_detil.deselect();
	// grid_detil_id.getSelectionModel().deselectRow( index );
	// store_detil.getSelectionModel().deselectRow( 0 );
	// Ext.getCmp('grid_detil_id').getSelectionModel().deselectAll();
	// Ext.getCmp('grid_detil_id').getSelectionModel().deselectRow();

	
	// in_this.getSelectionModel().deselectRow(rowIndex);
	// grid_detil_id.getSelectionModel().deselectRow( index );
	// sm.getSelection().deselectRow( index );
	// var selectedRecord = store_detil.getSelectionModel().getSelection()[0];
	// var selectedRecord = grid_detil_id.getSelectionModel().getSelection()[0];
	// grid_detil_id.getSelectionModel().getSelection()[0];
	// Ext.getCmp("grid_detil_id").getSelectionModel().clearSelections();
	// Ext.getCmp("grid_detil_id").getSelectionModel().clearSelections( index );


	// outstanding = parseInt(outstanding + parseInt(record.get('DATA_OUTSTANDING_ASLI')));

	/*
	var data=sm.getSelection();
	var i=0;
	var jumlah=0;
	var arrNominal = new Array();
	for(var i in data) {
		arrNominal[i]=data[i].get('DATA_OUTSTANDING_RP');
		jumlah = jumlah + arrNominal[i];
	}

	console.log( 'jumlah: ' + jumlah );
	*/


	/*
	Ext.Ajax.request({
		url:'<?php echo 'update_outstanding.php'; ?>',
			timeout: 500000,
			params:{
				hd_id:hd_id,
				outstanding:outstanding,
				process_type:'deselect',
			},
			success:function(response){
				
				var json = Ext.decode(response.responseText);
				var jsonresults = json.results;
				
				Ext.getCmp('tf_nominal').setValue(jsonresults);
				
			},
		method:'POST',
	});
	*/
	
	/*
	outstanding =  parseInt(record.get('DATA_OUTSTANDING_ASLI'));
	
	hd_id =  record.get('HD_ID');
	
	console.log( 'deselect: ' + outstanding + ' hd_id: ' + hd_id );
	
	// console.log( 'deselect: ' + outstanding );
	
	Ext.getCmp('tf_nominal').setValue(outstanding);
	*/
	
	// outstanding = parseInt(outstanding - parseInt(record.get('DATA_OUTSTANDING_ASLI')));
	
	//var selectedRecord = grid.getSelectionModel().getSelection()[0];
	//var row = grid.store.indexOf(selectedRecord);
	/* var data=sm.getSelection();
	var i=0;
	var jumlah=0;
	var arrNominal = new Array();
	for(var i in data) {
		arrNominal[i]=data[i].get('DATA_OUTSTANDING_RP');
		jumlah = jumlah + arrNominal[i];
	} */
	//Ext.Msg.alert('', "You've clicked " + outstanding);//record.get('DATA_OUTSTANDING_ASLI'));//index.toString());
	
	/*
	var selectionHeadID = Ext.getCmp('grid_detil_id').headerCt.getHeaderAtIndex(0).getId();
	var el = Ext.get(selectionHeadID);
	el.removeCls('x-grid-hd-checker-on');
	*/

	
	/*
	, selectionchange : function() {
		var recLen = Ext.getCmp('grid_detil_id').store.getRange().length;
		var selectedLen = this.selections.items.length;
		var view   = Ext.getCmp('grid_detil_id').getView();
		var chkdiv = Ext.fly(view.innerHd).child(".x-grid3-hd-checker")
		if(selectedLen == recLen){
			chkdiv.addClass("x-grid3-hd-checker-on");
		} else {
			chkdiv.removeClass("x-grid3-hd-checker-on");
		}
	}
	, rowdeselect : function ( sm ,rowIndex ,record) {
		var view   = Ext.getCmp('grid_detil_id').getView();
		var chkdiv = Ext.fly(view.innerHd).child(".x-grid3-hd-checker")
		chkdiv.removeClass('x-grid3-hd-checker-on');
	}
	*/
	
	
	/*
	columns: [{
		xtype: 'rownumberer',
		listeners: {
			headerclick: function() {
				// var grid = this.up('grid'),
				var grid = this.up('grid_detil_id'),
					selectionModel = grid.getSelectionModel(),
					selectionCount = selectionModel.getSelection().length,
					storeCount = grid.getStore().getCount();
				if(selectionCount < storeCount) {
					selectionModel.selectAll();
				} else if(selectionCount == storeCount) {
					selectionModel.deselectAll();
				}
			}
		}
	}],		
	*/
	
	
	// outstanding =  parseInt(record.get('DATA_OUTSTANDING_ASLI'));
								
?>


