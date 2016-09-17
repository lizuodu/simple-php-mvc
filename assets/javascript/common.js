var name, email, website, content, pid, mark, flag = 'OK';
var $msg;

// 检测验证码
function checkMark() {
	flag = "500服务器端错误";
	$.ajax({
		url: 'index.php?url=post/checkmark',
		type: 'POST',
		data: { 'mark': mark },
		cache: false,
		success: function(data, textStatus) {
			if ((data != "OK") && (data.length <= 100)) {
				flag = data;
				$msg.html(data);
			}
		}
	});
}

// 刷新验证码
function refreshMark() {
	$.ajax({
		url: 'index.php?url=home/mark/num/',
		cache: false,
		success: function(data, textStatus) {
			$('#markimg').attr('src', this.url.replace('?_=', ''));
		}
	});
}

$(function() {
	$msg = $(".msg");
	
	// 评论提交客户端验证
	var getCheck = function() {
		$msg.html("");
		flag = "OK";
		name = $("#name").val();
		if (name == "") {
			flag = "昵称不能为空白 ";
			$msg.append(flag);
		}
		content = $("#comment").val();
		if (content == "") {
			flag = "留言不能为空白 ";
			$msg.append(flag);
		}
		mark = $("#mark").val();
		if (mark == "") {
			flag = "验证码不能为空白 ";
			$msg.append(flag);
		}
		email = $("#email").val();
		website = $("#website").val();
		pid = $("#pid").val();
	};
	
	// 点击加载验证码 
	$("#imgmark").on("click", function() {
		$(this).replaceWith("<img src='index.php?url=home/mark/num/' id='markimg' />");
	});
	
	// 重新获取验证码
	$(document).on('click', '#markimg', function(){
		refreshMark();
		return false;
	});
	// 检测验证码
	$(document).on("blur", "#mark", function(){
		getCheck();
		if (flag == "OK") {
			checkMark();
		}
	});
	
	// 提交评论
	var btnSubmit = $('#btnSubmit').on('click', function() {
		getCheck();
		if ("OK" != flag) {
			return;
		}
		else {
			$(".msg").html("正在提交评论");
		}
		$.ajax({ 
				url: "index.php?url=post/comment", 
				type: 'POST',
				data: 'mark='+mark+'&pid='+pid+'&name='+name+'&website='+website+'&content='+content+'&email='+email,
				dataType: 'html',
				success: function (data, textStatus) {
				    if (data == '保存成功，请等待审核') {
				    	clearVals();
				    	$(".msg").html(data);
				    }
				    else {
				    	$(".msg").html(data);
				    }
				},
				error: function (XMLHttpRequest, textStatus, errorThrown) {
					$(".msg").html(textStatus);
				}
		});
	});
	
	// 清空评论输入框
	function clearVals() {
		$("#name").val("");
		$("#comment").val("");
		$("#mark").val("");
		$("#email").val("");
		$("#website").val("");
		refreshMark();
	}
	
	var click = "click";
	if ('ontouchstart' in window) {
		click = 'touchstart';
	}
	// 头部导航菜单按钮
	$("#tmenu").unbind(click).bind(click, function() {
		var col1 = $("#col1");
		if (col1.css("display") == "none") {
			col1.css({ "display": "block" });
		}
		else {
			col1.css({ "display": "none" });
		}
	});
	
	// Google插件，语法加亮
	prettyPrint();
	
});









