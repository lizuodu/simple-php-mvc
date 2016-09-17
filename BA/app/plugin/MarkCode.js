Ext.onReady(function() {
	var code = /[0-9]/i;	
	Ext.apply(Ext.form.field.VTypes, {
		markCode: function(val, field) {
			return code.test(val);
		},
		//markCodeText: '验证码必须是数字',
		markCodeMask: code
	});
});

/**
 * 验证码插件
 * @author lizuodu
 */
Ext.define('BA.plugin.MarkCode', {
	extend: 'Ext.form.field.Text',
	alias: 'widget.markcode',
	url: BA.config.Global.markUrl,
	
	fieldLabel: '验证码',
	allowBlank: false,
	grow: false,
	selectOnFocus: true,
	/*vtype: 'markCode',*/
	blankText: "请填写验证码",
	cls: 'login-input-left login-mark',
		
	onRender: function(parentNode, containerIdx) {
		var me = this;
        me.autoSize();
        me.callParent();
        var p = Ext.get('mark');  // 没有使用parentNode，否则不能变成块级元素，验证码将显示到下一行
		me.imgEl = p.createChild({ 
			tag: 'img',
			src: me.url
		});
		me.loadMarkCodeUrl();
		me.imgEl.on('click', this.loadMarkCodeUrl, this);
	},
		
	loadMarkCodeUrl: function() {
		this.imgEl.set({ src: this.url + 'num/' + Math.random() });
	}
});

/*var markCode = Ext.create('BA.plugin.MarkCode', {
	name: 'mark',
	fieldLabel: '验证码',
	allowBlank: false,
	blankText: "请填写验证码",
	enableKeyEvents: true,
	listeners: {
		specialKey: 'onSpecialKey'
	},
	width: 200,
	cls: 'login-input-left'
});*/



