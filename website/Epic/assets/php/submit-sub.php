<?php

if(!isset($_POST['email'])
	) {
	die('Error: Missing variables');
}

$email=$_POST['email'];

$to='you@email.com';

$headers = 'The following person has registered to be notified when your webite launches: '."\r\n" ."\r\n" .
	'Email: '.$email."\r\n";

$subject = 'A new person has registered to be updated when you go live';	

// Bug fix for Microsoft IIS. Remove the // from the next two lines if you use Microsoft IIS and then correct the address of your SMTP server
// ini_set ( "SMTP", "smtp.yoursite.com" ); 
// date_default_timezone_set('America/New_York');


if(mail($to, $subject, 
	$headers)) {
	die('Mail sent');
} else {
	die('Error: Mail failed');
}

?>