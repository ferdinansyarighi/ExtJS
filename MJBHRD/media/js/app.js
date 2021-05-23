Ext.ns('SMEP.app');
SMEP.app.handleErrorRequest = function(action,fnCallback){
		fnCallback = fnCallback || Ext.emptyFn();
		switch (action.failureType) {
			case Ext.form.action.Action.CLIENT_INVALID:
				Ext.Msg.alert('Failure', 'Form fields may not be submitted with invalid values');
				break;
			case Ext.form.action.Action.CONNECT_FAILURE:
				Ext.Msg.alert('Failure', 'Ajax communication failed');
				break;
			case Ext.form.action.Action.SERVER_INVALID:
			   Ext.Msg.alert('Failure', action.result.message);
	   }
	   fnCallback.call(this);
}

alertDialog = function(title,msg,icon){
		Ext.Msg.show({
			title: title,
			msg: msg,
			width: 300,
			buttons: Ext.Msg.OK,
			icon: icon,
			modal: true
		});
}