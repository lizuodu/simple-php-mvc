/**
 * 文章列表页面
 */
Ext.define('BA.view.tabpage.CommentList', {
	extend: 'Ext.panel.Panel',	
	
	requires: ['BA.store.Comments', 'BA.model.Comment'],
		
	tabName: '评论列表',
	alias: 'widget.commentList',
	
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
			id: 'comment-list-status',
			name: 'comment-status',
			queryMode: 'local',
			fieldLabel: '状态',
			width: 130,
			store: Ext.create('Ext.data.Store', {
			    fields: ['id', 'name'],
			    data : [
			    	{'id': '1', 'name': '已审核'},
			    	{'id': '2', 'name': '未审核'}
			    ]
			}),
			displayField: 'name',
			valueField: 'id',
			value: '',
			forceSelection: true
		},{
			xtype: 'button',
			html: '<h1>查询</h1>',
			handler: 'loadCommentStoreBySearch'
		}]
	}, {
		xtype: 'grid',
		id: 'comment-list-grid',
		name: 'comment-list-grid',
		minWidth: 980,
		autoLoad: true,
		selType: 'checkboxmodel',
		layout: 'anchor',
		multiSelect: false,   // 不起作用？？？
		store: Ext.data.StoreManager.lookup('commentStore'),
		columns: [
			{xtype: 'rownumberer'},
			{text: 'ID', dataIndex: 'id', flex: 0.05},
			{text: '内容', dataIndex: 'content', flex: 0.3},
			{text: '状态', dataIndex: 'status', flex: 0.08, tooltip: '1已审核，2未审核',
				renderer: function(status) {
					if (1 === status) {
						return '<font color="red">已审核</font>';
					}
					else if (2 === status) {
						return '<font color="blue">未审核</font>';
					}
				},
				field: {
		        	xtype: 'combobox',
		        	allowBlank: false,
		        	store: Ext.create('Ext.data.Store', {
					    fields: ['id', 'name'],
					    data : [
					    	{'id': '1', 'name': '已审核'},
					    	{'id': '2', 'name': '未审核'}
					    ]
					}),
					displayField: 'name',
					valueField: 'id',
					forceSelection: true
		        }
			},
			{text: '创建时间', dataIndex: 'create_time', flex: 0.1},
			{text: '昵称', dataIndex: 'author', flex: 0.1},
			{text: '电子邮件', dataIndex: 'email', flex: 0.1,
				renderer: function(email) {
					return Ext.String.format('<a href="mailto:{0}">{0}</a>', email);
				}
			},
			{text: '博客', dataIndex: 'url', flex: 0.15, 
				renderer: function(blog_url){
					return Ext.String.format("<a href='{0}' target='_blank'>{0}</a>", blog_url);
				}
			},
			{text: '文章ID', dataIndex: 'post_id', 
				renderer: function(post_id) {
					return Ext.String.format("<a href='{0}' target='_blank'>{1}</a>", BA.config.Global.url + 'index.php?url=post/show/id/' + post_id, post_id);
				}
			}
		],
		plugins: [
			Ext.create('Ext.grid.plugin.CellEditing', {
				clicksToEdit: 1   // 1表示单击，2表示双击
			})
		],
		// dockedItems
		tbar: [
			{xtype: 'button', text: '保存', icon: 'resources/images/save.gif', handler: 'onSaveCommentById' },
			{xtype: 'button', text: '删除', icon: 'resources/images/delete.png',handler: 'onDeleteCommentById'}
		],
		bbar: [{
			xtype: 'pagingtoolbar',
			store: Ext.data.StoreManager.lookup('commentStore'),
			displayInfo: true,
			refreshText: '刷新当前页',
			style: {
				background: '#FFFFCC'
			}
		}]
	}]
});
