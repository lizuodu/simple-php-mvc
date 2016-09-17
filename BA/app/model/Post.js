Ext.define('BA.model.Post', {
	extend: 'Ext.data.Model',
	fields: [
		{name: 'id', type: 'int', sortable: true},
		{name: 'title', type: 'string'},
		{name: 'content', type: 'string'},
		{name: 'tags', type: 'string'},
		{name: 'status', type: 'string'},
		{name: 'create_time', type: 'string'},
		{name: 'update_time', type: 'string'},
		{name: 'author_id', type: 'int'}
	]
});