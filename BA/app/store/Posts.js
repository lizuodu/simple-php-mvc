Ext.define('BA.store.Posts', {
	extend: 'Ext.data.Store',
	autoLoad: false,
	pageSize: 15,
	storeId: 'postStore',
	model: 'BA.model.Post',
	proxy: {
		type: 'ajax',
		noCache: false,
		url: BA.config.Global.url + '/index.php?url=post/list/',
		reader: {
			type: 'json',
			rootProperty: 'list'
		},
		writer: {
			type: 'json'
		}
	}
});

var store = Ext.create('BA.store.Posts', {});

store.load({
	params: {
		start: 0,
		limit: 15,
		category: '',
		status: '',
		title: ''
	}
});
