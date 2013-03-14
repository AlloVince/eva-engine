<?php

if(!isset($_POST['email']) || !isset($_POST['message'])) {
	die('Error: Missing variables');
}

$email=$_POST['email'];
$message=$_POST['message'];

$to='you@email.com';

$headers = 'You have received a new message on your website: '."\r\n" ."\r\n" .
	'From: '.$email."\r\n" .
	'Message: '.$message."\r\n";

$body='Message: '.$message."\r\n";
$subject = 'You have recieved a new message';		

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