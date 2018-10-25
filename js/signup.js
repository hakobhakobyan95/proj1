$(document).ready(function(){

	function signup(){
		var name = $("#name").val();
		var surname = $("#surn").val();
		var age = $("#age").val();
		var email = $("#email").val();
		var pwd1 = $("#pwd1").val();
		var pwd2 = $("#pwd2").val();

		if(pwd1 != pwd2){
			$("#error").removeClass("hide");
			$("#error").html("Passwords are not equal.");
		}
		else{
			$.ajax({
				type: "post",
				url: "private/server.php",
				data: {name: name, surname: surname, age: age, email: email, password: pwd2, action: "sign_up"},
				success: function(r){
					if(r != ""){
						$("#error").removeClass("hide").html(r);
					}
					else{
						location.href = "profile.php";
					}
					
				}
			});
		}		
	}

	$("#signup").on("change", "#pwd1", function(){
		if($(this).val() != $("#pwd2").val()){
			$("#pwd2").addClass("red lighten-3");
		}
		else{
			if($("#pwd2").hasClass("red lighten-3")){
				$("#pwd2").removeClass("red lighten-3");
			}
		}
	});

	$("#signup").on("input", "#pwd2", function(){
		if($(this).val() != $("#pwd1").val()){
			$(this).addClass("red lighten-3");
		}
		else{
			if($(this).hasClass("red lighten-3")){
				$(this).removeClass("red lighten-3");
			}
		}
	});

	$("#sbm").click(signup);
	$("#pwd2").keydown(function(k) {
		if(k.keyCode == 13) {
			signup();
		}
	});

	$("#error").on("click", ".email", function(){
		$("#email").val($(this).html());
	});
});