
<?php
//Header
$headers =  'MIME-Version: 1.0' . "\r\n"; 
$headers .= 'From: Your name <fontaine.s@outlook.fr>' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n"; 

// Le message
$message = "Line 1\r\nLine 2\r\nLine 3";

// Dans le cas où nos lignes comportent plus de 70 caractères, nous les coupons en utilisant wordwrap()
$message = wordwrap($message, 70, "\r\n");

// Envoi du mail
mail('fontaine.s@outlook.fr', 'Mon Sujet', $message, $headers);
?>
