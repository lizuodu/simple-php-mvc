/**
 * 文章列表页面
 */
Ext.define('BA.view.tabpage.PostList', {
	extend: 'Ext.panel.Panel',	
	
	requires: [
		   'BA.model.Post',
		   'BA.store.Posts', 
		   'BA.model.Tag', 
		   'BA.store.Tags'
       ],
		
	tabName: '文章列表',
	alias: 'widget.postList',
	
	width: 980,
	layout: 'anchor',
	items: [{
		xtype: 'form',
		bodyStyle: 'background: #ffc; padding: 5px',
		region: 'north',						
		height: 33,
		fieldDefaults: {
			style: 'float: left;margin-left: 5px',
	        labelAlign: 'left',
	        labelWidth: 40
	    },
		items: [{
			xtype: 'combobox',
			id: 'post-list-tags',
			name: 'post-tags',
			queryMode: 'local',
 			store: Ext.data.StoreManager.lookup('tagStore'),
			// store: Ext.create('BA.store.Tags'),
			displayField: 'name',
			valueField: 'name',
			autoSelect: true,
			fieldLabel: '分类',
			width: 200
		}, {
			xtype: 'combobox',
			id: 'post-list-status',
			name: 'post-status',
			queryMode: 'local',
			fieldLabel: '状态',
			width: 130,
			store: Ext.create('Ext.data.Store', {
			    fields: ['id', 'name'],
			    data : [
			    	{'id': '1', 'name': '发布'},
			    	{'id': '2', 'name': '草稿'},
			    	{'id': '3', 'name': '归档'}
			    ]
			}),
			displayField: 'name',
			valueField: 'id',
			value: '',
			forceSelection: true
		},{
			xtype: 'textfield',
			id: 'post-list-title',
			name: 'post-title',
			fieldLabel: '标题',
			width: 300
		},{
			xtype: 'button',
			html: '<h1>查询</h1>',
			handler: 'loadPostStoreBySearch'
		}]
	}, {
		xtype: 'grid',
		id: 'post-list-grid',
		name: 'post-list-grid',
		minWidth: 980,
		autoLoad: true,
		selType: 'checkboxmodel',
		layout: 'fit',
		multiSelect: false,   // 不起作用？？？
		store: Ext.data.StoreManager.lookup('postStore'),
		columns: [
			{xtype: 'rownumberer', text: '序号', flex: 0.04},
			{text: 'ID', dataIndex: 'id', flex: 0.05},
			{text: '标题【点击查看对应的评论】', dataIndex: 'title', flex: 0.35},
			{text: '分类标签', dataIndex: 'tags', flex: 0.15},
			{text: '状态', dataIndex: 'status', flex: 0.05, tooltip: '1发布，2草稿，3归档',
				renderer: function(value){
			        if (value === '1') {
			            return '<font color="red">发布</font>';
			        }
			        else if (value === '2') {
			        	return '<font color="green">草稿</font>';
			        }
			        else if (value === '3') {
			        	return '<font color="blue">归档</font>';
			        }
			    }
			},
			{text: '创建时间', dataIndex: 'create_time', flex: 0.1, xtype: 'datecolumn', format: 'Y年m月d日'},
			{text: '更新时间', dataIndex: 'update_time'}
		],
		// dockedItems
		tbar: [
			{xtype: 'button', text: '编辑', icon: 'resources/images/edit.png',handler: 'onEditPostById'},
			{xtype: 'button', text: '删除', icon: 'resources/images/delete.png',handler: 'onDeletePostById'}
		],
		dockedItems: [{
			xtype: 'pagingtoolbar',
			dock: 'bottom',
			store: Ext.data.StoreManager.lookup('postStore'),
			displayInfo: true,
			refreshText: '刷新当前页',
			style: {
				width: '100%',
				background: '#FFFFCC'
			}
		}]
	}]
});

