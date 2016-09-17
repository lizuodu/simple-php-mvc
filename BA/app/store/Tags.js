Ext.define('BA.store.Tags', {
	extend: 'Ext.data.Store',
	autoLoad: true,
	storeId: 'tagStore',
	model: 'BA.model.Tag',
	proxy: {
		type: 'ajax',
		url: BA.config.Global.url + '/index.php?url=tag/index',
		reader: {
			type: 'json',
			rootProperty: 'list'
		},
		writer: {
			type: 'json'
		}
	}
});

var store = Ext.create('BA.store.Tags', {});

