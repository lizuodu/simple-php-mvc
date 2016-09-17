Ext.define('BA.model.Comment', {
	extend: 'Ext.data.Model',
	fields: [
		{name: 'id', type: 'int', sortable: true},
		{name: 'content', type: 'string'},
		{name: 'status', type: 'int'},
		{name: 'create_time', type: 'string'},
		{name: 'author', type: 'string'},
		{name: 'email', type: 'string'},
		{name: 'url', type: 'string'},
		{name: 'post_id', type: 'int'}
	]
});