$(document).ready(function(){

	//messenger_height_control
	$("#messenger").height(100 - $("#top").height()*100/$('body').height() + "%");
	$(window).resize(function(){
		$("#messenger").height(100 - $("#top").height()*100/$('body').height() + "%");
	});
	var chatdown= true;



	//loading_friend_list_for_messanger
	function loadFriendList(){	
		$.ajax({
			type: "post",
			url: "private/server.php",
			data: {action: "load_friends"},
			success: function(r){
				var friends = JSON.parse(r);
				$("#messenger").empty();
				friends.forEach(function(item){
					var div = $('<div class="chip messenger-friend send-message"></div>');
					div.attr("data-user",item.id);
					div.attr("data-status",item.status);
					var src = ((item.image == null) ? "img/nophoto1.png" : item.image);
					var img = $(`<img src="${src}" alt="M">`);
					var o_status = $('<div class="online-status"></div>');
					(item.status == 1 ? o_status.addClass("online") : o_status.addClass("offline"));
					div.append(o_status);
					div.append(img);
					div.append("<span>" + item.name + " " + item.surname + "</span>");
					$("#messenger").append(div);
				});
			}
		});
	}
	loadFriendList();
	setInterval(loadFriendList,15000);


	//.send-message_buttons_click
	function openChat(d_user, image, ns, online){
		var l_chat = $(`<div class="live-chat" data-user="${d_user}" data-img="${image}"></div>`);
		(online == "1" ? l_chat.addClass("online") : l_chat.addClass("offline"));
		var header = $('<header class="clearfix"></header>');
		var c_close = $('<a href="#" class="chat-close">x</a>');
		var user = $(`<h4>${ns}</h4>`);
		var c_counter = $('<span class="chat-message-counter"></span>');
		header.append(c_close);
		header.append(user);
		var chat = $('<div class="chat"></div>');
		var h_chat = $('<div class="chat-history"></div>');

		$.ajax({
			type: 'post',
			url: 'private/server.php',
			data: {user: d_user, action: "load_messages"},
			success: function(r){
				r = JSON.parse(r);
				var newMessages = 0;
				r.forEach(function(item){
					var me = $("#profile").attr("data-user");
					var msg = $('<div class="chat-message clearfix"></div>');
					var src;
					var ns;
					if(item.user1 != me){
						if(item.status == 0){
							newMessages++;
						}
						msg.addClass("received-message");
						src = image;
						ns = $(`<h5>${user.html()}</h5>`);
					}
					else{
						msg.addClass("sent-message");
						src = $("#profile #main-img img").attr("src");
						ns = $(`<h5>${$("#profile #myname").html() + " " + $("#profile #mysurname").html()}</h5>`);
					}
					var img = $(`<img src="${src}" alt="M" width="32" height="32">`);
					msg.append(img);
					var content = $('<div class="chat-message-content clearfix">');

					var d = new Date(0);
   					d.setSeconds(item.time);
					var time = $(`<span class="chat-time">${d.toLocaleString()}</span>`);

					var message = $(`<p>${item.txt}</p>`);
					message.attr("data-status",item.status);
					content.append(time);
					content.append(ns);
					content.append(message);
					msg.append(content);
					h_chat.append(msg).append("<hr>");
				});
				if(newMessages){
					c_counter.html(newMessages);
					header.append(c_counter);
				}

				var inp = $('<input type="text" placeholder="Type your message…" maxlength="2048" autofocus>');
				var btn = $('<i class="material-icons send">send</i>');
				chat.append(h_chat);
				chat.append(inp);
				chat.append(btn);
				l_chat.append(header);
				l_chat.append(chat);
				$("#chat-area").html(l_chat);
			}
		}).then(function(){
			$("#chat-area .live-chat:last-child .chat-history")[0].scrollTop = $("#chat-area .live-chat:last-child .chat-history")[0].scrollHeight;
		});
	}


	$("#messenger").on("click", ".send-message", function(){
		var d_user = $(this).attr('data-user');
		var image = $(this).find("img").attr("src");
		var ns = $(this).find("span").html();
		var online = $(this).attr('data-status');
		openChat(d_user,image,ns,online);
	});


	$("#guest").on("click", ".send-message", function(){
		var d_user = $("#guest").attr('data-user');
		var image = $("#guest #m_image").attr("src");
		var ns = $("#guest .userInfo .u-name").html() + " " + $("#guest .userInfo .u-surname").html();
		var online = $("#guest").attr('data-status');
		openChat(d_user,image,ns,online);		
	});


	$("#search-result").on("click", ".send-message", function(event){
		event.stopPropagation();
		var d_user = $(this).parents(".collection-item").attr('data-user');
		var image = $(this).parents(".collection-item").find("img").attr("src");
		var ns = $(this).parents(".collection-item").find(".u-name").html() + " " + $(this).parents(".collection-item").find(".u-surname").html();
		var online = $(this).parents(".collection-item").attr('data-status');
		openChat(d_user,image,ns,online);		
	});	



	//chat_slideDown_slideUp
	$('#chat-area').on('click', "header", function(e) {
		if(chatdown){			
			$(this).parents('.live-chat').animate({"bottom":"-90%"}, 75, "linear");
			$(this).parents('.live-chat').find('.chat-message-counter').css("display","block");
		}else{
			$(this).parents('.live-chat').animate({"bottom":"0"}, 75, "linear");
			$(this).parents('.live-chat').find('.chat-message-counter').css("display","none");
		}
		chatdown = !chatdown;
	});


	$('#chat-area').on('click', '.chat-close', function(e) {
		e.preventDefault();
		$(this).parents('.live-chat').remove();
	});



	//send_message
	function sendMsg(_this){
		if(_this.val() == "") {
			_this.parent().children(".chat-history")[0].scrollTop = _this.parent().children(".chat-history")[0].scrollHeight;
			return;
		}
		var user = _this.parents(".live-chat").attr("data-user");
		var txt = _this.val();
		_this.val("");
		$.ajax({
			type: 'post',
			url: 'private/server.php',
			data: {user: user, message: txt, action: "send_message"},
			success: function(time){
				var msg = $('<div class="chat-message sent-message clearfix"></div>');
				var src = $("#profile #main-img img").attr("src");
				var img = $(`<img src="${src}" alt="M" width="32" height="32">`);
				msg.append(img);
				var content = $('<div class="chat-message-content clearfix">');
				var d = new Date(0);
   				d.setSeconds(time);
				var time = $(`<span class="chat-time">${d.toLocaleString()}</span>`);
				var me = $(`<h5>${$("#profile #myname").html() + " " + $("#profile #mysurname").html()}</h5>`);
				var message = $(`<p>${txt}</p>`);
				content.append(time);
				content.append(me);
				content.append(message);
				msg.append(content);
				_this.parent().children(".chat-history").append(msg).append("<hr>");
				_this.parent().children(".chat-history")[0].scrollTop = _this.parent().children(".chat-history")[0].scrollHeight;
			}
		});
	}


	$('#chat-area').on('input', '.live-chat input[type="text"]', function() {
		$(this).one("keydown", function(k) {
			if(k.keyCode == 13) {
				sendMsg($(this));
			}
		});
		$(this).parent().find(".send").one("click", function() { 
			sendMsg($(this).parent().children("input")); 
		});
	});



	//read_message
	$('#chat-area').on('focus', '.live-chat input[type="text"]', function() {
		$(this).one("keydown", function() {	
			if($(this).parents(".live-chat").find(".chat-message-counter").length != 0){
				var user = $(this).parents(".live-chat").attr("data-user");
				var count = $(this).parents(".live-chat").find(".chat-message-counter");
				$.ajax({
					type: 'post',
					url: 'private/server.php',
					data: {user: user, action: "read_message"},
					success: function(){
						count.remove();
					}
				});
			}
		});
	});

});