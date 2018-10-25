$(document).ready(function(){

	function login(){
		var email = $("#email").val();
		var pwd = $("#pwd").val();

		$.ajax({
			type: "post",
			url: "private/server.php",
			data: {email: email, password: pwd, action: "log_in"},
			success: function(r){
				try{
					JSON.parse(r);
					location.href = "profile.php";
				}
				catch(err){
					$("#error").removeClass("hide").html(r);
				}
			}
		});
	}

	$("#sbm").click(login);
	$("#pwd").keydown(function(k) {
		if(k.keyCode == 13) {
			login();
		}
	});

});