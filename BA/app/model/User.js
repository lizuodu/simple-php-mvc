Ext.define('BA.model.User', {
    extend: 'Ext.data.Model',

    fields: [
        'name',
        { name: 'id', type: 'int' },
        { name: 'username', type: 'string' }
    ]
});
