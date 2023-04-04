$(document).ready(function() {
	$("#proceed").click(function() {
		var err = 0;
		var msg = new Array();
		if ($("#fullname").val() == "") {
			$("#fullname").addClass("error");
			msg.push("Your name is required.");
			err++;
		} else {
			$("#fullname").removeClass("error");
		}
		
		if ($("#email").val() == "") {
			$("#email").addClass("error");
			msg.push("Your email is required.");
			err++;
		} else {
			if (!isEmail($("#email").val())) {
				$("#email").addClass("error");
				msg.push("Invalid email address.");
				err++;
			} else {
				$("#email").removeClass("error");
			}
		}
		
		if ($("#amount").val() == "") {
			$("#amount").addClass("error");
			msg.push("Amount to pay is required.");
			err++;
		} else {
			$("#amount").removeClass("error");
		}
		
		if($("#note").val() == "") {
			$("#note").addClass("error");
			msg.push("Note for payment is required.");
			err++;
		} else {
			$("#note").removeClass("error");
		}
		
		var response = grecaptcha.getResponse();
		if (response.length == 0) {
			$(".g-recaptcha > div > div").addClass("error");
			msg.push("Please check on the Captcha box before submitting.");
			err++;
		} else {
			$(".g-recaptcha > div > div").removeClass("error");
		}
		
		if (err == 0) {
			return true;
		}
		else {
			showErrorMessage(msg);
			return false;
		}
	});
});


