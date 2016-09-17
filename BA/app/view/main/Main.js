/**
 * This class is the main view for the application. It is specified in app.js as the
 * "autoCreateViewport" property. That setting automatically applies the "viewport"
 * plugin to promote that instance of this class to the body element.
 *
 * TODO - Replace this content of this view to suite the needs of your application.
 */
Ext.define('BA.view.main.Main', {
    extend: 'Ext.container.Viewport',
    requires: [
        'BA.view.main.MainController',
        'BA.view.main.MainModel'
    ],

    controller: 'main',
    viewModel: {
        type: 'main'
    },

    layout: 'border',
    
    items: [{
    	xtype: 'container',
        id: 'ba-header',
        cls: 'ba-header',
        region: 'north',
        height: 30,
        layout: {
            type: 'hbox',
            align: 'middle'
        },
        items: [{
        	xtype: 'container',
        	height: 30,
			cls: 'ba-header',
        	flex: 0.2
        }, {
        	xtype: 'label',
        	text: '欢迎你：' + (Ext.util.Cookies.get('user') || 'lizuodu'),
        	flex: 0.5,
        	height: 30,
        	cls: 'ba-header'
        }, {
        	xtype: 'container',
        	flex: 0.3,
        	height: 30,
        	cls: 'ba-header',
        	items: {
        		xtype: 'button',
        		text: '退出系统',
        		style: 'background:#2A3F5D;border:none',
        		handler: 'logout'
        	}
        }]
    },{
        xtype: 'panel',
        id: 'menuPanel',
        bind: {
            title: '{name}'
        },
        region: 'west',
        width: 150,
        split: true,
        statefdiv: true,
        collapsed: false,
        animCollapse: true,
        collapseDirection: 'bottom',
        collapsible: true,
        layout: {
        	type: 'accordion',
        	animate: true
        },
        items: [{
        	id: 'post',
        	title: '文章管理',
            items: [{
            	xtype: 'button',
            	text: '新增文章',
            	id: 'post-edit',
            	margin: '10 10 0 10',
            	width: 125,
            	height: 25,
                listeners: {
		            click: 'onClickHandler'
		        }
            },{
            	xtype: 'button',
            	text: '文章列表',
            	id: 'post-list',
            	margin: '5 10 0 10',
            	width: 125,
            	height: 25,
            	handler: 'onClickHandler'
            }],
            statefdiv: true,
        	autoScroll: true
        }, {
        	id: 'category',
        	title: '分类管理',
        	items: {
        		xtype: 'button',
            	text: '分类列表',
            	id: 'category-list',
            	margin: '5 10 0 10',
            	width: 125,
            	height: 25,
            	handler: 'onClickHandler'
        	},
        	autoScroll: true
        }, {
        	id: 'comment',
        	title: '评论管理',
        	items: {
        		xtype: 'button',
            	text: '评论列表',
            	id: 'comment-list',
            	margin: '5 10 0 10',
            	width: 125,
            	height: 25,
            	handler: 'onClickHandler'
        	},
        	autoScroll: true
        }]
    },{
        region: 'center',
        xtype: 'tabpanel',
        id: 'tabHome',
        activeTab: 0,
        items:[{
            title: '主页',
            html: ''
        }]
    }]
});
