/**
 *  这个类的作用主要是管理登录窗口对象和后台页面对象
 */
Ext.define('BA.controller.Root', {
    extend: 'Ext.app.Controller',
    
    requires: [
        'BA.view.login.Login',
        'BA.view.main.Main'
    ],
    
    loadingText: '加载中...',
    
    onLaunch: function () {
    	var session = this.session = new Ext.data.Session(); 
        if (Ext.isIE8) {
            alert('不支持IE8及以下浏览器，请升级浏览器。');
            return;
        }
        
        // 如果登录过的直接showUI显示Main窗口
        if ((null != Ext.util.Cookies.get('user'))
        		&& typeof(Ext.util.Cookies.get('user')) != undefined) {
        	this.showUI();
        	return;
        }
        
        // 创建登录窗口对象
        this.login = Ext.create('BA.view.login.Login', {
            autoShow: true,
            listeners: {
                scope: this,
                login: 'onLogin'  // 在登录窗口注册login事件，调用onLogin方法
            }
        });
    },

    /**
     * 在../view/login/LoginControll.js中触发login事件被调用
     * 
     * @param {} loginController 控制器对象
     * @param {} user 登录人员对象
     */
    onLogin: function (loginController, user) { 
        this.login.destroy();// 销毁登录窗口 
        // document.cookie = Ext.String.format("user='{0}'", user);
        // document.addCookie(Ext.String.format("user='{0}'", user));
        Ext.util.Cookies.set("user", user);
        this.showUI();       // 显示主窗口
    },
    
    showUI: function() {
    	// 创建主窗口实例
        this.viewport = Ext.create('BA.view.main.Main', {

        });
    }
    
});



