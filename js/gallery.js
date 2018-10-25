$(document).ready(function(){

	$('.modal').modal();
	var add_image_modal = $("#add-image").html();
	var delete_photo = null;
	var spp_btn = null;



	//load gallery

	function addPhoto(item,main){
		var photo = $(`<div class="photo col s3" style="background-image: url('${item}');"></div>`);
		var fnc = $("<div class='fnc'></div>");
		var btns = $("<div class='btns row'></div>");
		btns.append("<a href='#zoom-image'><button data-src='" + item + "' class='btn-flat col s4 zoom'><i class='material-icons'>zoom_in</i></button></a>");
		btns.append("<a href='#profile-image'><button data-main='" + main + "' data-src='" + item + "' class='btn-flat col s4 spp'><i class='material-icons'>face</i></button></a>");
		btns.append("<a href='#delete-image'><button data-src='" + item + "' class='btn-flat col s4 del'><i class='material-icons'>delete</i></button></a>");
		fnc.append(btns);
		photo.append(fnc);
		$("#gallery").append(photo);
	}

	$('#showPhotos').click(function(){
		$.ajax({
			type: "post",
			url: "private/server.php",
			data: {action: "load_images"},
			success: function(r){
				var tmp = $("#gallery>.photo:first-child");
				$("#gallery").html(tmp);
				var images = JSON.parse(r);
				var main_image = $("#m_image").attr("src");
				images.forEach(function(item){
					var main = (main_image == item) ? 1 : 0;
					addPhoto(item, main);
				});
			}
		});
	});

	$("#gallery").on("mouseenter", ".photo", function(){
		$(this).children(".fnc").fadeIn(250);
	});

	$("#gallery").on("mouseleave", ".photo", function(){
		$(this).children(".fnc").fadeOut(250);
	});



	//add-image
	$('#_body').on("change", "#upload", function(event){
		if($(this).val() != ""){
			$("#save").removeAttr("disabled");
			var img = URL.createObjectURL(event.target.files[0]);
			$("#img-form img").removeClass("hide").attr("src",img);
		}
		else{
			$("#save").attr("disabled","");
			$("#img-form img").addClass("hide").attr("src","");
		}
	});

	$('#_body').on("change", "input[name='main']", function(){
		$("input[name='main']").removeAttr("checked");
		$(this).attr("checked","checked");
	});

	$('#_body').on("click", "#save", function(){
		var data = new FormData(document.querySelector("#img-form"));
		data.append("action", "add_profile_image");
		$.ajax({
			type: "post",
			url: "private/server.php",
			data: data,
			contentType: false,
			processData: false,
			success: function(r){
				var main = $("#add-image input[checked='checked']").val();
				if(main == "1"){
					$("#main-img").empty().append(`<img id="m_image" class="col s12 materialboxed" src="${r}">`);
					$('.materialboxed').materialbox();
				}
				addPhoto(r, main);
				$("#add-image").html(add_image_modal);
			}
		});
	});

	$('#_body').on("click", "#cancel", function(){
		$("#add-image").html(add_image_modal);
	});



	//delete-image
	$('#gallery').on("click", ".del", function(){
		var src = $(this).attr("data-src");
		$("#delete-image img").attr("src",src);
		delete_photo = $(this).parents(".photo");
	});

	$('#_body').on("click", "#delete", function(){
		var img = $("#delete-image img").attr("src");
		$.ajax({
			type: "post",
			url: "private/server.php",
			data: {img: img, action: "delete_image"},
			success: function(r){
				if(r == "1"){
					$("#main-img").html('<a href="#add-image"><img id="profile-image" class="col s12" src="img/addphoto.png"></a>');
				}	
				delete_photo.remove();
				delete_photo = null;
			}
		});
	});



	//zoom-image
	$('#gallery').on("click", ".zoom", function(){
		var src = $(this).attr("data-src");
		$("#zoom-image img").attr("src",src);
	});



	//profile-image
	$('#_body').on("change", "input[name='set_main']", function(){
		$("input[name='set_main']").removeAttr("checked");
		$(this).attr("checked","checked");
	});

	$('#gallery').on("click", ".spp", function(){
		spp_btn = $(this);
		var src = $(this).attr("data-src");
		var main = $(this).attr("data-main");

		$("#profile-image img").attr("src",src).attr("data-main",main);

		if(main == "1"){
			$("#profile-image h6").html("This is your current profile picture.");
			$("#profile-image input[name='set_main']").removeAttr("checked");
			$("#mn2").attr("checked","checked");
		} 
		else{
			$("#profile-image h6").html("");
			$("#profile-image input[name='set_main']").removeAttr("checked");
			$("#mn1").attr("checked","checked");
		}
	});

	$('#_body').on("click", "#setMain", function(){
		var img = $("#profile-image img").attr("src");
		var main = $("#profile-image img").attr("data-main");
		var set_main = $("#profile-image input[checked='checked']").val();
		if((main == 1 && set_main == 0) || (main == 0 && set_main == 1)){
			$.ajax({
				type: "post",
				url: "private/server.php",
				data: {img: img, main: set_main, action: "set_profile_image"},
				success: function(r){
					if(r == "0"){
						$("#main-img").html('<a href="#add-image"><img id="profile-image" class="col s12" src="img/addphoto.png"></a>');
						spp_btn.attr("data-main","0");
					}
					else{
						$("#main-img").html(`<img id="m_image" class="col s12 materialboxed" src="${img}">`);
						$("#gallery .spp[data-main='1']").attr("data-main","0");
						spp_btn.attr("data-main","1");
						$('.materialboxed').materialbox();
					}
				}
			});
		}
	});

});