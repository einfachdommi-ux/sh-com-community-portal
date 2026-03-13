<?php
$to = 'dominik.friedrich@dofrivo.de';
$subject = 'Testmail Schleswig-Holstein Community';
$message = '<h1>Test erfolgreich</h1><p>PHP mail() funktioniert.</p>';

$headers = [];
$headers[] = 'MIME-Version: 1.0';
$headers[] = 'Content-type: text/html; charset=UTF-8';
$headers[] = 'From: Schleswig-Holstein Community <noreply@sh-com.de>';
$headers[] = 'Reply-To: noreply@sh-com.de';

$result = mail($to, '=?UTF-8?B?' . base64_encode($subject) . '?=', $message, implode("\r\n", $headers));

var_dump($result);