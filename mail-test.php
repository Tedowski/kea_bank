<?php

$sEmail = 'teddcz@gmail.com';

$sTo = $sEmail;
$sSubject = "Account Activation";

$sLink = 'https://seznam.cz';

$sMessage = '
<html>
<head>
    <title>Account Activation</title>
</head>
<body>
    <h3>Click on this link to to activate your account:</h3>
    <div style"display: flex; justify-content: center">
        <a style"display: inline-block; padding: 1em 1.5em; background-color: #252525;" href="'.$sLink.'">Activate</a>
    </div>
</body>
</html>
';

// Always set content-type when sending HTML email
$sHeaders = "MIME-Version: 1.0" . "\r\n";
$sHeaders .= 'Content-type: text/html; charset=utf-8' . "\r\n";
$sHeaders .= 'From: mail@takodesign.one' . "\r\n";

if( mail($sTo,$sSubject,$sMessage,$sHeaders)) {
    echo 'Email sent';
    exit;
} else {
echo 'One.com je sračka';
}