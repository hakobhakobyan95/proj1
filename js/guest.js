$(document).ready(function(){

	var guest_page = $("#guest").html();


	//send_friend_request
	$("#guest").on("click", ".add-friend", function(){
		var rq = $(this).attr("data-request");

		if(rq == "1"){ return; }

		var user = $("#guest").attr("data-user");
		var action = (rq == "0" ? "friend_request" : "friend_confirm");
		
		$.ajax({
			type: "post",
			url: "private/server.php",
			data: {user: user, action: action},
			success: function(){
				$("#guest .add-friend").attr("disabled", "");
				if(action == "friend_confirm"){
					var div = $('<div class="chip messenger-friend" data-user="' + $("#guest").attr("data-user") + '"></div>');
					var src = $("#guest #m_image").attr("src");
					var img = $(`<img src="${src}" alt="M">`);
					div.append(img);
					var name = $("#guest .u-name").html();
					var surname = $("#guest .u-surname").html();
					div.append(name + " " + surname);
					$("#messenger").append(div);
					$("#guest .add-friend").attr("disabled","").fadeOut(250, function(){ $(this).remove(); });
					let btn = '<p><button class="btn indigo accent-2 mbtn unfriend hide"><span>Unfriend </span><i class="material-icons">clear</i></button></p>';
					$("#guest .userInfo").append(btn);
					$("#guest .unfriend").fadeOut(0).removeClass("hide").fadeIn(250);
				}
			}
		});
	});


	//unfriend
	$("#guest").on("click", ".unfriend", function(){
		$.ajax({
			type: "post",
			url: "private/server.php",
			data: {user: $("#guest").attr("data-user"), action: "unfriend"},
			success: function(){
				$("#guest").attr("data-friend","0");
				$("#guest .unfriend").attr("disabled","").fadeOut(250, function(){ $(this).remove(); });
				let btn = '<p><button class="btn indigo accent-2 mbtn add-friend hide" data-request="0"><span>Add Friend </span><i class="material-icons">accessibility</i></button></p>';
				$("#guest .userInfo").append(btn);
				$("#guest .add-friend").fadeOut(0).removeClass("hide").fadeIn(250);

				$("#messenger").find(`.messenger-friend[data-user='${$("#guest").attr("data-user")}']`).remove();
			}
		});
	});


	//guest_from_friends
	$("#friends").on("click", ".friend-ns", function(){
		event.stopPropagation();
		$("#guest").html(guest_page);
		var id = $(this).parents(".friend").attr("data-user");
		var img = $(this).parents(".friend").attr("data-src");
		var name = $(this).children(".u-name").html();
		var status = $(this).parents(".friend").attr("data-status");
		var surname = $(this).children(".u-surname").html();
		var unfriend = '<p><button class="btn indigo accent-2 mbtn unfriend"><span>Unfriend </span><i class="material-icons">clear</i></button></p>';
		$("#guest").attr("data-friend","1").attr("data-user",id);
		$("#guest").attr("data-status", status);
		$("#guest #g_main-img").append(`<img id="m_image" class="col s12 materialboxed" src="${img}">`);
		$("#guest .userInfo .txt:first-child").append(`<span class='u-name'>${name}</span>`);
		$("#guest .userInfo .txt:nth-child(2)").append(`<span class='u-surname'>${surname}</span>`);
		$("#guest .userInfo").append(unfriend);
		$(".tabs a[href='#guest']").parents("li").removeClass("hide");
		$(".tabs a[href='#guest']").trigger("click");
		$('.materialboxed').materialbox();
	});


	//guest_from_search
	$("#search-result").on("click", ".collection-item", function(){
		$('#search-result .collection').empty();
		$("#search").val("");
		$("#guest").html(guest_page);
		$("#guest").attr("data-user", $(this).attr("data-user"));
		$("#guest").attr("data-status", $(this).attr("data-status"));
		$(".tabs a[href='#guest']").parents("li").removeClass("hide");
		$(".tabs a[href='#guest']").trigger("click");

		$.ajax({
			type: "post",
			url: "private/server.php",
			data: {user: $("#guest").attr("data-user"), action: "check_friend"},
			success: function(r){
				$("#guest").attr("data-friend", r);
				if(r == "1"){
					let btn = '<p><button class="btn indigo accent-2 mbtn unfriend"><span>Unfriend </span><i class="material-icons">clear</i></button></p>';
					$("#guest .userInfo").append(btn);
				}
				else{
					let btn = '<p><button class="btn indigo accent-2 mbtn add-friend"><span>Add Friend </span><i class="material-icons">accessibility</i></button></p>';
					$("#guest .userInfo").append(btn);

					$.ajax({
						type: "post",
						url: "private/server.php",
						data: {user: $("#guest").attr("data-user"), action: "check_request"},
						success: function(r){
							$("#guest .add-friend").attr("data-request", r);
							if(r == "1"){ 
								$("#guest .add-friend").attr("disabled", ""); 
							}
							else if(r == "2"){
								$("#guest .add-friend span").html("Confirm Friend");
							}
						}
					});								
				}
			}
		});

		$("#guest .userInfo .txt:first-child").append("<span class='u-name'>"+$(this).find(".name-surname>span:first-child").html()+"</span>");
		$("#guest .userInfo .txt:nth-child(2)").append("<span class='u-surname'>"+$(this).find(".name-surname>span:last-child").html()+"</span>");
		$("#guest #g_main-img").append('<img id="m_image" class="col s12 materialboxed" src="' + $(this).find("img").attr("src") + '">');
		$('.materialboxed').materialbox();
	});


	$(".tabs li").click(function(){
		if($(this).children("a").attr("href") != "#guest"){
			$(".tabs a[href='#guest']").parents("li").addClass("hide");
			$("#guest").html(guest_page);
		}
	});

});