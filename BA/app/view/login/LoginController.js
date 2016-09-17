/**
 * This View Controller is associated with the Login view.
 */
Ext.define('BA.view.login.LoginController', {
    extend: 'Ext.app.ViewController',
    alias: 'controller.login',
        
    loginText: '验证登录信息...',

    constructor: function () {
        this.callParent(arguments);
    },

    // 回车键触发登录
    onSpecialKey: function(field, e) {
        if (e.getKey() === e.ENTER) {
            this.onLoginClick();
        }
    },
	    
	// 登录按钮事件
    onLoginClick: function() {
    	var form = this.lookupReference('form'); // reference: 'form',

        if (form.isValid()) {    // 表单前端验证
        	Ext.getBody().mask(this.loginText);
			Ext.Ajax.request({   // 表单后端验证
				url: BA.config.Global.loginUrl,   
				method: 'POST',
				params: form.getValues(),    // username,password,mark
				scope: this,
				timeout: 6000,
				success: function(response, opts) {
					this.onLoginSuccess(response);
				},
				failure: function(response, opts) {
					this.onLoginFailure(response);
				}
			});
        }
    },
        
    onLoginFailure: function(r) {
        Ext.getBody().unmask();
        alert('请求失败：' + r.responseText);
    },

    onLoginSuccess: function(r) {
        Ext.getBody().unmask();
        var result = r.responseText;
		if (result == 'mark-failure') {
			$("#msg").html('验证码错误');
		}
		else if (result == 'login-failure') {
			$("#msg").html('登录失败');
		}
		else {
			console.log("登录成功");
			// 将人员登录信息作为对象存起来
			//var user = Ext.JSON.decode(r.responseText);
			var user = Ext.getCmp('username').getValue();
			// 触发login事件，这个事件是在登录窗体加载时候在Root.js注册的
			// 主要作用是：销毁登录窗口，实例化主窗口
			this.fireViewEvent('login', this.getView(), user);
		}
    }
});


