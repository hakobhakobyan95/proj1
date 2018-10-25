$(document).ready(function(){

	loadNotifications();
	setInterval(loadNotifications, 5000);


	function loadNotifications(){
		$.ajax({
			type: "post",
			url: "private/server.php",
			data: {action: "load_notifications"},
			success: function(r){
				r = JSON.parse(r);
				if(r.length){
					$("a[href='#notifications']").find(".badge").html(r.length).removeClass("hide");			
					$('#notifications .collection').empty();
					r.forEach(function(item){
						let li = $('<li class="collection-item note"></li>');
						li.attr("data-user",item.user2);
						li.attr("data-request",item.request_id);
						let vw = $('<div class="valign-wrapper"></div>');
						let img = (item.image != null) ? item.image : "img/nophoto1.png";
						vw.append(`<img src="${img}" alt="I" class="circle">`);
						let ns = $('<span class="name-surname"></span>');
						ns.append('<span class="u-name">'+item.name+' </span><span class="u-surname"> '+item.surname+'</span>');
						if(item.status == 1){
							ns.append("<span> accepted your friend request</span>");
						}
						else if(item.status == 0){
							ns.append("<span> denied your friend request</span>");
						}
						else{
							ns.append("<span> has sent you a friend request</span>");
						}
						vw.append(ns);
						if(item.status === null){
							let a1 = $('<span class="secondary-content"><i class="material-icons accept-request">done</i></span>');
							vw.append(a1);
							let a2 = $('<span class="secondary-content"><i class="material-icons deny-request">clear</i></span>');
							vw.append(a2);
						}
						else{
							let a = $('<span class="secondary-content"><i class="material-icons delete-notification">clear</i></span>');
							vw.append(a);
						}
						li.append(vw);
						$('#notifications .collection').append(li);
					});
				}
				else if($('#notifications .collection').find("label").length == 0){
					$("a[href='#notifications']").find(".badge").html("").addClass("hide");
					$('#notifications .collection').html("<label>No new notifications</label>");
				}
			}
		});
	}


	//delete_notification
	$("#notifications").on("click", ".delete-notification", function(){
		var _this = $(this);
		var request_id = $(this).parents(".note").attr("data-request");
		$.ajax({
			type: "post",
			url: "private/server.php",
			data: {request_id: request_id, action: "delete_notification"},
			success: function(){
				_this.parents(".note").remove();
				let badge = $("a[href='#notifications']").find(".badge");
				badge.html(badge.html()-1);
				if(badge.html() == 0){ 
					badge.addClass("hide"); 
					$('#notifications .collection').html("<label>No new notifications</label>");
				}
			}
		});
	});


	//accept_friendship
	$("#notifications").on("click", ".accept-request", function(){
		var _this = $(this);
		var user = $(this).parents(".note").attr("data-user");
		$.ajax({
			type: "post",
			url: "private/server.php",
			data: {user: user, action: "friend_confirm"},
			success: function(status){
				var div = $('<div class="chip messenger-friend send-message"></div>');
				div.attr("data-user", user);
				div.attr("data-status", status);
				var src = _this.parents(".note").find("img").attr("src");
				var img = $(`<img src="${src}" alt="M">`);
				div.append(img);
				var name = _this.parents(".note").find(".u-name").html();
				var surname = _this.parents(".note").find(".u-surname").html();
				div.append("<span>" + name + " " + surname + "</span>");
				var o_status = $('<div class="online-status"></div>');
				(status == 1 ? o_status.addClass("online") : o_status.addClass("offline"));
				div.append(o_status);
				$("#messenger").append(div);
				_this.parents(".note").remove();
				let badge = $("a[href='#notifications']").find(".badge");
				badge.html(badge.html()-1);
				if(badge.html() == 0){ 
					badge.addClass("hide"); 
					$('#notifications .collection').html("<label>No new notifications</label>");
				}
			}
		});
	});


	//deny_frendship
	$("#notifications").on("click", ".deny-request", function(){
		var _this = $(this);
		var user = $(this).parents(".note").attr("data-user");
		$.ajax({
			type: "post",
			url: "private/server.php",
			data: {user: user, action: "friend_deny"},
			success: function(){
				_this.parents(".note").remove();
				let badge = $("a[href='#notifications']").find(".badge");
				badge.html(badge.html()-1);
				if(badge.html() == 0){ 
					badge.addClass("hide"); 
					$('#notifications .collection').html("<label>No new notifications</label>");
				}
			}
		});
	});

});