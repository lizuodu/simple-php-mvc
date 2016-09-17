Ext.define('BA.store.Comments', {
	extend: 'Ext.data.Store',
	autoLoad: false,
	pageSize: 15,
	storeId: 'commentStore',
	model: 'BA.model.Comment',
	proxy: {
		type: 'ajax',
		noCache: false,
		url: BA.config.Global.url + '/index.php?url=comment/index',
		reader: {
			type: 'json',
			rootProperty: 'list'
		},
		writer: {
			type: 'json'
		}
	}
});

var store = Ext.create('BA.store.Comments', {});

store.load({
	params: {
		id: 0,
		start: 0,
		limit: 15,
		status: ''
	}
});
