$(document).ready(function(){

	//load profile image
	$.ajax({
		type: "post",
		url: "private/server.php",
		data: {action: "load_main_image"},
		success: function(r){
			if(r == 0){
				$("#main-img").append('<a href="#add-image"><img id="profile-image" class="col s12" src="img/nophoto.png"></a>');
			}
			else{
				$("#main-img").append(`<img id="m_image" class="col s12 materialboxed" src="${r}">`);
				$('.materialboxed').materialbox();
			}
		}
	});


	//load_friends
	var allfriends = "";
	$(".tabs a[href='#friends']").click(function(){
		$.ajax({
			type: "post",
			url: "private/server.php",
			data: {action: "load_friends"},
			success: function(r){
				if(r == allfriends){ return; }
				allfriends = r;
				$("#friends").empty();
				var friends = JSON.parse(r);
				friends.forEach(function(item){
					var a = $("<a href='#zoom-image'></a>");
					var div = $(`<div class="friend col s3 blue" data-user="${item.id}"></div>`);
					var src = ((item.image == null) ? "img/nophoto1.png" : item.image);
					div.attr("data-img",src);
					div.attr("data-status",item.status);
					div.css("background-image", "url('" + src + "')");
					div.attr("data-src", src);
					div.append("<div class='friend-ns'><span class='u-name'>" + item.name + "</span> <span class='u-surname'> " + item.surname + "</span></div>");
					a.append(div);
					$("#friends").append(a);
				});
			}
		});
	});


	$("#friends").on("click", ".friend", function(){
		var src = $(this).attr("data-img");
		$("#zoom-image").find("img").attr("src",src);
	});


	//log out
	$("#log-out").click(function(){
		$.ajax({
			type: "post",
			url: "private/server.php",
			data: {action: "log_out"},
			success: function(){
				location.href = "login.php";
			}
		});
	});	

});