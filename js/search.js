$(document).ready(function(){

	$("#srch").on("input", "#search", function(){
		$.ajax({
			type: "post",
			url: "private/server.php",
			data: {txt: $(this).val(), action: "search_user"},
			success: function(r){
				var res = JSON.parse(r);
				$('#search-result .collection').empty();
				res.forEach(function(item){
					let li = $('<li class="collection-item"></li>');
					li.attr("data-user",item.id);
					li.attr("data-status",item.status);
					let ns = $('<div class="valign-wrapper"></div>');
					let img = (item.image != null) ? item.image : "img/nophoto1.png";
					ns.append(`<img src="${img}" alt="I" class="circle">`);
					ns.append('<span class="name-surname"><span class="u-name">'+item.name+' </span><span class="u-surname"> '+item.surname+'</span>');
					let a = $('<span class="secondary-content"><i class="material-icons send-message">textsms</i></span>');
					ns.append(a);
					li.append(ns);
					$('#search-result .collection').append(li);
				});
			}
		});
	});

});