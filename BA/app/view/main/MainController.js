/**
 * This class is the main view for the application. It is specified in app.js as the
 * "autoCreateViewport" property. That setting automatically applies the "viewport"
 * plugin to promote that instance of this class to the body element.
 *
 * TODO - Replace this content of this view to suite the needs of your application.
 */
Ext.define('BA.view.main.MainController', {
    extend: 'Ext.app.ViewController',

    requires: [
        'BA.view.tabpage.PostEdit',
        'BA.view.tabpage.PostList',
		'BA.view.tabpage.CommentList',
        'BA.model.Comment',
        'BA.store.Comments',
        'BA.plugin.HEditor'
    ],

    alias: 'controller.main',
    
    // 处理单击菜单事件
    onClickHandler: function (button) {
        var id = button.id;
        var tabPanel = Ext.getCmp('tabHome');  // 右边Tab窗口
		var tabPrefix = 'tab';
		var tabPage = Ext.getCmp(tabPrefix + id); // 获取打开Tab的id
		
		if (!tabPage) { // 没有打开，则新创建
			var tabPageTitle, tabPageObj; // tab标题，tab对象
				
			if ('post-list' == id) { // 文章列表
				var postList = Ext.createByAlias('widget.postList', {
					region: 'center'
				});
				tabPageTitle = postList.tabName;
				tabPageObj = postList;
			}
			else if ('post-edit' == id) {   // 新增或编辑文章
				var postEdit = Ext.createByAlias('widget.postEdit', {
					region: 'center'
				});
				tabPageObj = postEdit;
				tabPageTitle = postEdit.tabName;
			}
			else if ('category-list' == id) {  // 分类列表
				
			}
			else if ('comment-list' == id) {   // 评论列表
				var commentList = Ext.createByAlias('widget.commentList', {
					region: 'center'
				});
				tabPageObj = commentList;
				tabPageTitle = commentList.tabName;
			}
				
			tabPage = tabPanel.add({  // 添加新Tab
				id: tabPrefix + id,
				closable: true,
				layout: 'border',
				title: tabPageTitle,
				items: tabPageObj
			});
		}
		tabPanel.setActiveTab(tabPage);  // 不管是新创建还是重新打开的Tab，都将其激活
    },
    
    // 退出系统
    logout: function(btn) {
    	Ext.Msg.confirm('确认', '确定退出系统？', function(btn) {
	    	if ('yes' == btn) {
	    		Ext.util.Cookies.clear('user');
	    		window.location = BA.config.Global.logout;
	    	}
    	}, this);    	
    },
    
    ////////////////////////////////////////////////////////////////////////////////////////////
    
    // 保存文章
    onSubmitForm: function() {
    	var form = this.lookupReference('form');    	
    	// var form = this.up('form').getForm();
    	    	
		if (form.isValid()) {
    		Ext.getBody().mask('正在提交表单...');
    		
			Ext.Ajax.request({
				method: 'POST',
				url: BA.config.Global.savePostsUrl,
				params: form.getValues(),    // username,password,mark
				scope: this,
				timeout: 6000,
				success: function(response, opts) {
					Ext.getBody().unmask();
					var msg = response.responseText;
					if ('success' == msg) {
						alert('保存成功');
						form.reset();
					}
					else {
						alert('保存失败，' + msg);
					}
				},
				failure: function(response, opts) {
					Ext.getBody().unmask();
					alert('请求失败，' + response.responseText);
				}
			});
		}
    },
    
    // 文章列表grid记录编辑操作
    onEditPostById: function(btn) {
	 	var grid = btn.ownerCt.ownerCt;
	 	var selected = grid.getSelectionModel().getSelection();
	 	if (selected.length != 1) {
	 		alert('请选中一条记录');
	 	}
	 	else {
	 		var record = selected[0]; // 选中记录
	 		var tabPanel = Ext.getCmp('tabHome');
			var tabPage = Ext.getCmp('tabpost-edit');
			if (!tabPage) {
				var postEdit = Ext.createByAlias('widget.postEdit', {
					region: 'center'
				});
				tabPage = tabPanel.add({  // 添加新Tab
					id: 'tabpost-edit',
					closable: true,
					layout: 'border',
					title: postEdit.tabName,
					items: postEdit
				});
			}
			tabPanel.setActiveTab(tabPage);
			// 暂时使用这种方法
			var postId = record.get('id');
			var postTitle = record.get('title');
			var postContent = record.get('content');
			var postTags = record.get('tags');
			var postStatus = record.get('status');
			var postDate = record.get('create_time');
			Ext.getCmp('post-edit-id').setValue(postId);
			Ext.getCmp('post-edit-title').setValue(postTitle);
			Ext.getCmp('post-edit-content').setValue(postContent);
			Ext.getCmp('post-edit-tags').setValue(postTags);
			Ext.getCmp('post-edit-status').setValue(postStatus);
			Ext.getCmp('post-edit-create_time').setValue(postDate);
	 	}
    },
    
    // 文章列表grid记录删除操作
    onDeletePostById: function(btn) {
    	// var grid = btn.findParentByType('grid');
	 	var grid = btn.ownerCt.ownerCt;
	 	// alert(grid.getStore().getCount());
	 	var selected = grid.getSelectionModel().getSelection();
	 	if (selected.length <= 0) {
	 		alert('请选择记录');
	 	}
	 	else {
	 		var ids = [];
	 		var store = grid.getStore();
	 		Ext.Array.each(selected, function(record) {
	 			ids.push(record.get('id'));	 		
	 		});
	 		Ext.Ajax.request({
	 			url: BA.config.Global.delPostsUrl,
	 			method: 'POST',
	 			timeout: 6000,
	 			params: {
	 				ids: ids.join(',')
	 			},
	 			noCache: false,
	 			success: function(response, opts) {
	 				var text = response.responseText;
	 				if ('success' == text) {
	 					alert('删除成功');
	 					Ext.Array.each(selected, function(record) {
				 			store.remove(record);	 		
				 		});
	 				}
	 				else {
	 					alert('删除失败');
	 				}
	 			}
	 		});
	 	}
    },
	
	// 查询，对grid的store进行筛选，重新加载store
	loadPostStoreBySearch: function(btn) {
		var category = Ext.getCmp('post-list-tags').getValue();  // 分类
		var status = Ext.getCmp('post-list-status').getValue();  // 状态
		var title = Ext.getCmp('post-list-title').getValue();   // 状态
		var grid = Ext.getCmp('post-list-grid');
		var store = Ext.data.StoreManager.lookup('postStore');
		store.load({
			params: {
				start: 0,
				limit: 15,
				category: category,
				status: status,
				title: title
			}
		});
		grid.setStore(store);
	},
	
	////////////////////////////////////////////////////////////////////////////////////////////
	
	// 根据评论状态查询评论
	loadCommentStoreBySearch: function(btn) {
		var status = Ext.getCmp('comment-list-status').getValue();
		Ext.getCmp('comment-list-grid').getStore().load({
			params: {
				start: 0,
				limit: 15,
				status: status
			}
		});
	},
	
	// 根据评论id保存评论
	onSaveCommentById: function(btn) {
		var gridStore = Ext.getCmp('comment-list-grid').getStore();
		var records = gridStore.getModifiedRecords();// Ext.data.Model[]
		if (records.length == 0) {
			alert('没有被修改的记录');
			return;
		}
		var ids = '';  // 用逗号分隔的评论id
		var status = ''; // 用逗号分隔的状态编号
		Ext.Array.each(records, function(name, index) {
			// alert(name.get('id') + name.get('status'));
			ids += name.get('id') + ',';
			status += name.get('status') + ',';
		}),
		Ext.Ajax.request({
			url: BA.config.Global.modifyCommentUrl,
			method: 'POST',
			timeout: 6000,
			params: {
				ids: ids,
				status: status
			},
			success: function(response) {
				var text = response.responseText;
				if ('success' == text) {
					alert('修改成功');
					gridStore.sync();
					var update_recrods = gridStore.getUpdatedRecords();
					Ext.Array.each(update_recrods, function(model, index) {
						model.commit();
					});
				}
				else {
					alert('修改失败');
				}
			},
			failure: function(response) {
				
			}
		});
	},
	
	// 根据评论id删除评论
	onDeleteCommentById: function(btn) {
    	// var grid = btn.findParentByType('grid');
	 	var grid = btn.ownerCt.ownerCt;
	 	// alert(grid.getStore().getCount());
	 	var selected = grid.getSelectionModel().getSelection();
	 	if (selected.length <= 0) {
	 		alert('请选择记录');
	 	}
	 	else {
	 		var ids = [];
	 		var store = grid.getStore();
	 		Ext.Array.each(selected, function(record) {
	 			ids.push(record.get('id'));	 		
	 		});
	 		Ext.Ajax.request({
	 			url: BA.config.Global.delCommentUrl,
	 			method: 'POST',
	 			timeout: 6000,
	 			params: {
	 				ids: ids.join(',')
	 			},
	 			noCache: false,
	 			success: function(response, opts) {
	 				var text = response.responseText;
	 				if ('success' == text) {
	 					alert('删除成功');
	 					Ext.Array.each(selected, function(record) {
				 			store.remove(record);	 		
				 		});
	 				}
	 				else {
	 					alert('删除失败');
	 				}
	 			}
	 		});
	 	}
    }
    
});







