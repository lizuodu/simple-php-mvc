Ext.define('BA.view.login.Login', {
    extend: 'Ext.window.Window',
    
    requires: [
        'BA.view.login.LoginController',
        'BA.view.login.LoginModel',
        'Ext.form.Panel',
        'Ext.button.Button',
        'Ext.form.field.Text'
    ],
    
    viewModel: 'login',
    
    controller: 'login',
    bodyPadding: 10,
	resizable: false,
	icon: 'resources/images/favicon.ico',
    title: '登录 - <a href="'+ BA.config.Global.url +'">lizuodu.com</a>',
    closable: false,
    width: 450,
    height: 270,
	layout: 'fit',
	draggable: false,
        
    items: {
        xtype: 'form',
        reference: 'form',
        	fieldDefaults: {
				labelWidth: 100,
				labelAlign: 'left',
				labelStyle: 'font-weight:bold;',
				fieldStyle: 'font-size:14px;',
				msgTarget: 'side'
		},
        items: [{
        	xtype: 'panel',
			name: 'loginPanel',
			id: 'loginPanel',
			height: 170,
			bodyStyle: {
				background: '#F1F0DF',
				padding: '10px'
			},
			border: 1,
        	items: [{
						xtype: 'textfield',
						id: 'username',
						name: 'username',
						fieldLabel: '用户名',
						allowBlank: false,
						blankText: "请填写用户名",
						enableKeyEvents: true,
						listeners: {
							specialKey: 'onSpecialKey'
						},
						cls: 'login-input-left'
					}, {
						xtype: 'textfield',
						name: 'password',
						inputType: 'password',
						fieldLabel: '密&nbsp;&nbsp;&nbsp;码',
						allowBlank: false,
						blankText: "请填写密码",
						enableKeyEvents: true,
						cls: 'password',
						listeners: {
							specialKey: 'onSpecialKey'
						},
						cls: 'login-input-left'
					}, {
						xtype: 'markcode',
						name: 'mark',
						id: 'mark',
						enableKeyEvents: true,
						listeners: {
							specialKey: 'onSpecialKey'
						}
					}, {
						xtype: 'displayfield',
						hideEmptyLabel: false,
						value: '点击图片刷新验证码',
						cls: 'login-input-left'
					}, {
						xtype: 'displayfield',
						hideEmptyLabel: false,
						value: '',
						id: 'msg',
						cls: 'msg'
					}]
        }]
    },

    buttons: [{
        text: '登录',
		scope: this,
		scale: 'medium',
        listeners: {
        	// 调用LoginController.js中的onLoginClick事件
            click: 'onLoginClick'
        }
    }]
});





