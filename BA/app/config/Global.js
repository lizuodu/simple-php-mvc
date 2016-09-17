/**
 * 这个类主要用来存放全局的变量，并且是单实例的，
 * 你可以这样访问配置参数：
 * BA.config.Global.url
 * BA.config.Global.markUrl
 * ......
 */
Ext.define('BA.config.Global',{
	// 单例模式
    singleton: true,
	
	url: 'http://lizuodu.com/',
	logout: 'http://lizuodu.com/index.php?url=home/logout/',
	markUrl: 'http://lizuodu.com/index.php?url=home/mark/',
	loginUrl: 'http://lizuodu.com/index.php?url=home/login/',
	tagsUrl: 'http://lizuodu.com/index.php?url=tag/index',
	postsUrl: 'http://lizuodu.com/index.php?url=post/list/',
	savePostsUrl: 'http://lizuodu.com/index.php?url=post/save',
	delPostsUrl: 'http://lizuodu.com/index.php?url=post/delete',
	modifyCommentUrl: 'http://lizuodu.com/index.php?url=comment/modify',
	delCommentUrl: 'http://lizuodu.com/index.php?url=comment/delete',
	requestUpload: 'http://lizuodu.com/index.php?url=home/upload/',
	uploadPath: '/assets/upload/'

});


