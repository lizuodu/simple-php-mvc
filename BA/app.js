Ext.onReady(function() {
	
	Ext.Loader.setConfig({
		enabled: true
	});

	Ext.application({
		name: 'BA',

		extend: 'BA.Application',
		
		requires: [
			'BA.config.Global',
			'BA.plugin.MarkCode',
			'BA.model.Post',
			'BA.store.Posts',
			'BA.model.Tag',
			'BA.store.Tags',
			'BA.model.Comment',
			'BA.store.Comments'
		]
		
		// ExtJS 5.1 API Docs不建议使用，程序入口见BA.Application中的controllers
		// autoCreateViewport: 'BA.view.main.Main'
	});

	oldalert = window.alert;

	// 重写原生alert提示框
	window.alert = function(msg) {
		Ext.Msg.show({
			title: '提示',
			message: msg,
			buttons: Ext.Msg.OK,
			icon: Ext.Msg.INFO
		});
	}
	
	$("#loading").remove(); 

});



