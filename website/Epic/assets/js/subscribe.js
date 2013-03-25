// JavaScript Document

$(document).ready(function() {
	
	$('.email_wrap #submitsub').click(function() {
		// Fade in the progress bar
		$('.email_wrap #formSubProgress').hide();
		$('.email_wrap #formSubProgress').html('<img src="images/ajax-loader.png" /> Sending&hellip;');
		$('.email_wrap #formSubProgress').fadeIn();
		
		// Disable the submit button
		$('.email_wrap #submitsub').attr("disabled", "disabled");
		
		// Clear and hide any error messages
		$('.email_wrap .subError').html('');
		
		// Set temaprary variables for the script
		var isFocus=0;
		var isError=0;
		
		// Get the data from the form
	//	var name=$('.email_wrap #name').val();
		var email=$('.email_wrap #emailsub').val();
	//	var subject=$('.email_wrap #subject').val();
	//	var message=$('.email_wrap #message').val();
		
		// Validate the data
	//	if(name=='') {
	//	$('.email_wrap #errorName').html('This is a required field.');
	//		$('.email_wrap #name').focus();
	//		isFocus=1;
	//		isError=1;
	//	}
		if(email=='') {
			$('.email_wrap #errorSubEmail').html('Please enter your email address.');
			if(isFocus==0) {
				$('.email_wrap #emailsub').focus();
				isFocus=1;
			}
			isError=1;
		} else {
			var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
			if(reg.test(email)==false) {
				$('.email_wrap #errorSubEmail').html('Please enter a valid email address.');
				if(isFocus==0) {
					$('.email_wrap #emailsub').focus();
					isFocus=1;
				}
				isError=1;
			}
		}
	//	if(message=='') {
	//		$('.email_wrap #errorMessage').html('This is a required field.');
	//		if(isFocus==0) {
	//			$('.email_wrap #message').focus();
	//			isFocus=1;
	//		}
	//		isError=1;
	//	}
		
		// Terminate the script if an error is found
		if(isError==1) {
			$('.email_wrap #formSubProgress').html('');
			$('.email_wrap #formSubProgress').hide();
			
			// Activate the submit button
			$('.email_wrap #submitsub').attr("disabled", "");
			
			return false;
		}
		
		$.ajaxSetup ({
			cache: false
		});
		
		var dataString = //'name='+ name + '&
		'email=' + email;// + //'&subject=' + subject + 
	//	'&message=' + message;  
		$.ajax({
			type: "POST",
			url: "php/submit-sub-ajax.php",
			data: dataString,
			success: function(msg) {
				
				//alert(msg);
				
				// Check to see if the mail was successfully sent
				if(msg=='Mail sent') {
					// Update the progress bar
					$('.email_wrap #formSubProgress').html('<img src="images/ajax-complete.png" /> Thankyou!').delay(2000).fadeOut(400);
					
					// Clear the subject field and message textbox
				//	$('.email_wrap #subject').val('');
				//	$('.email_wrap #message').val('');
				} else {
					$('.email_wrap #formSubProgress').html('');
					alert('There was an error sending your email. Please try again.');
				}
				
				// Activate the submit button
				$('.email_wrap #submitsub').attr("disabled", "");
			},
			error: function(ob,errStr) {
				$('.email_wrap #formSubProgress').html('');
				alert('There was an error sending your email. Please try again.');
				
				// Activate the submit button
				$('.email_wrap #submitsub').attr("disabled", "");
			}
		});
		
		return false;
	});
});