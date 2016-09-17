/**
 * 扩展Ext.form.field.HtmlEditor
 * 增加了文件上传功能
 * 增加了代码插入功能
 * @author lizuodu
 */
Ext.tip.QuickTipManager.init();
Ext.define('BA.plugin.HEditor', {
	extend: 'Ext.form.field.HtmlEditor',
	alias: 'widget.heditor',
		
	defaultLinkValue: 'http://lizuodu.com',
	fontFamilies: ['SimSun'],
	height: 500,
	
	// 当控件渲染完成后，给ToolBar增加按钮
	listeners: {
		afterrender: function(editor, opts) {
			// 增加上传图片按钮
			var uploadBtn = Ext.create('Ext.button.Button', {
			    text: '',
			    scope: this,
			    icon: 'resources/images/image_add.png',
			    tooltip: {
					title: '插入附件',
					width: 80
				},
				handler: function() {
					this.createUploadForm(editor).show();
				}
			});
			// 增加代码插入按钮
			var codeBtn = Ext.create('Ext.button.Button', {
				text: '',
			    scope: this,
			    icon: 'resources/images/code.png',
			    tooltip: {
					title: '插入代码',
					width: 80
				},
				handler: function() {
					// this.createCodeForm(editor).show();
					editor.insertAtCursor('<pre class="prettyprint" style="border:1px solid #888;padding:3px;">code</pre>');
				}
			});
			// 插入摘要分隔符
			var splitBtn = Ext.create('Ext.button.Button', {
				text: '',
			    scope: this,
			    icon: 'resources/images/split.png',
			    tooltip: {
					title: '分隔符，已禁用',
					width: 120
				},
				handler: function() {
					editor.insertAtCursor('<div id="summary"> </div>');
				}
			});
			editor.getToolbar().add(uploadBtn);
			editor.getToolbar().add(codeBtn);
			// editor.getToolbar().add(splitBtn);
		}
	},
		
	// 上传文件窗口
	createUploadForm: function(editor) {
		return Ext.create('Ext.window.Window', {
					width: 450,
    				height: 103,
    				title: '选择要插入的文件',
    				resizable: false,
    				items: {
    					xtype: 'form',
			        	fieldDefaults: {
							msgTarget: 'side'
						},
						items: [{
							xtype: 'filefield',
							width: 440,
					        name: 'filename',
					        fieldLabel: 'Photo',
					        msgTarget: 'side',
					        allowBlank: false,
					        hideLabel: true,
					        style: 'padding-top: 5px',
					        buttonText: '浏览文件'
						}],
						buttons: [{
							text: '上传',
							handler: function() {
								var fp = this.up('form').getForm();
								var date = Ext.getCmp('post-edit-create_time').getValue();
								date = Ext.Date.format(date, 'y/m/d').toString();
								var msg = '';
								if (fp.isValid()) {
									fp.submit({
										url: BA.config.Global.requestUpload,
										waitMsg: '正在上传...',
										method: 'POST',
										params: {
											date: date
										},
					                    success: function(form, action) {
					                    	msg = buildUrl(Ext.JSON.decode(action.response.responseText));
											alert(msg);return;
					                    	if ('error' != msg) {
					                        	alert('文件上传成功。');
					                        	editor.insertAtCursor(msg);
					                    	}
					                    	else {
					                    		alert('文件上传失败')
					                    	}
					                    },
					                    failure: function(form, action){
					                    	msg = buildUrl(Ext.JSON.decode(action.response.responseText));
						                    if ('error' != msg) {
					                        	alert('文件上传成功。');
					                        	editor.insertAtCursor(msg);
					                    	}
					                    	else {
					                    		alert('文件上传失败')
					                    	}
						                }
									});
									function buildUrl(filename) {
										var imgPath = '';
										var createDate = Ext.getCmp('post-edit-create_time').getValue();
										if ('error' != filename) {
											imgPath = "<img src='"+ BA.config.Global.uploadPath + 
																date + '/'+filename+"' alt=''/><br/><br/>";
										}
										return imgPath;
									}
								}
							}
						}]
    				}
				});
	},
	
	// 代码选择窗口
	createCodeForm: function(editor) {
		return Ext.create('Ext.window.Window', {
					width: 450,
    				height: 300,
    				title: '选择要插入的代码语言',
    				resizable: false,
    				items: [{
						xtype: 'button',
						text: 'csharp',
						handler: function() {	
							// editor.insertAtCursor('<pre><code class="'+this.text+'">'+this.text+'</code></pre><br/><br/>');
							editor.insertAtCursor('<pre class="prettyprint">'+this.text+'</pre><br/><br/>');
							// close();
						}
					}, {
						xtype: 'button',
						text: 'php',
						handler: function() {			
							editor.insertAtCursor('<pre class="prettyprint">'+this.text+'</pre><br/><br/>');
							close();
						}
					}, {
						xtype: 'button',
						text: 'javascript',
						handler: function() {			
							editor.insertAtCursor('<pre class="prettyprint">'+this.text+'</pre><br/><br/>');
							close();
						}
					}, {
						xtype: 'button',
						text: 'jquery',
						handler: function() {			
							editor.insertAtCursor('<pre class="prettyprint">'+this.text+'</pre><br/><br/>');
							close();
						}
					}, {
						xtype: 'button',
						text: 'bash',
						handler: function() {			
							editor.insertAtCursor('<pre class="prettyprint">'+this.text+'</pre><br/><br/>');
							close();
						}
					}]
		});					
	}
	
	
});






