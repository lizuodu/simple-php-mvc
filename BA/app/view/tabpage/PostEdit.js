/**
 * 文章编辑页面
 */
Ext.define('BA.view.tabpage.PostEdit', {
	extend: 'Ext.form.Panel',
	requires: [
		'BA.model.Tag',
		'BA.store.Tags'
	],
	
	alias: 'widget.postEdit',
	tabName: '编辑文章',
	reference: 'form',
	
	layout: 'border',
	bodyStyle: 'padding:5px 5px 5px 0px',
	defaultType: 'textfield',
	fieldDefaults: {
		labelWidth: 100,
		labelAlign: 'left',
		labelStyle: 'font-weight:bold;padding-bottom:5px',
		fieldStyle: 'font-size:14px; font-family:Arial',
		msgTarget: 'side'
	},
			
	initComponent: function() {
		Ext.apply(this, {
			items: [{
				xtype:'panel',
				region: 'north',
				items: [{
						xtype: 'textfield',
						id: 'post-edit-title',
						name: 'post-title',
						allowBlank: false,
    					msgTarget: 'side',
    					blankText: '必须填写文章标题',
    					emptyText: '标题',
						fieldStyle: { height: '30px', width: '850px'}
					},{
						xtype: 'hiddenfield',
						id: 'post-edit-id',
						name: 'id'
					},{
						xtype: 'hiddenfield',
				        name: 'post-author',
				        value: '1'					
					},{
						xtype: 'hiddenfield',
				        name: 'update_time',
				        value: Ext.util.Format.date(new Date(), 'Y-m-d')					
					}]
			}, {
				xtype: 'heditor',
				region: 'center',
				scrollable: 'x',
				id: 'post-edit-content',
				name: 'post-content',
				allowBlank: false,
				msgTarget: 'side',
				blankText: '必须填写文章内容'
			},{
				title: '菜单',
				region: 'east',
				xtype: 'panel',
				split: true, 
				width: 220,
		        split: true,
		        statefdiv: true,
		        collapsed: false,
		        animCollapse: true,
		        collapsible: true,
				items: {
					xtype: 'panel',
					style: 'padding: 10px',
					items: [{
						xtype: 'label',
						text: '分类'
					},{
						xtype: 'combobox',
						id: 'post-edit-tags',
						name: 'post-tags',
						queryMode: 'local',
						store: Ext.create('BA.store.Tags'),
						displayField: 'name',
    					valueField: 'name',
    					autoSelect: true,
    					allowBlank: false,
    					msgTarget: 'side',
    					blankText: '必须选择或填写分类'
					},{
						xtype: 'label',
						text: '状态'
					},{
						xtype: 'combobox',
						id: 'post-edit-status',
						name: 'post-status',
						queryMode: 'local',
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
						value: '1',
						forceSelection: true,
						autoSelect: true,
						allowBlank: false,
    					msgTarget: 'side',
    					blankText: '必须选择发布状态'
					},{
						xtype: 'label',
						text: '日期'
					},{
						xtype: 'datefield',
				        anchor: '100%',
				        id: 'post-edit-create_time',
				        name: 'create_time',
				        value: new Date(),
				        format: 'Y-m-d'
					},{
						xtype: 'button',
						text: '保存文章',
						width: 200,
						height: 35,
						margin: '10 10 0 0',
						handler: 'onSubmitForm'
					}]
				}
			}]
		});
		this.callParent();
	}
});

