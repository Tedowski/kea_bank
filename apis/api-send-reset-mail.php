<?php

// Disable php errors
ini_set('user_agent', 'any');
ini_set('display_errors', 0);

if( empty($_GET['phone'] )){
    sendResponse(-1, __LINE__, 'Phone missing');
}

// Variables from GET
$sResetPhone = $_GET['phone'] ?? '';
if(!ctype_digit($sResetPhone)) {sendResponse(-1, __LINE__, 'Phone must be only digits');}
if( strlen($sResetPhone) != 8 ) {sendResponse(-1, __LINE__, 'Phone must be 8 digits');}

// Get data from database
$sData = file_get_contents('../data/clients.json');
$jData = json_decode( $sData );

if($jData == null) {
    sendResponse(-1, __LINE__, 'Cannot convert to JSON');
}

$jInnerData = $jData->data;

// ****************************************************************************************************

// Check if phone is in the local database
if( !$jInnerData->$sResetPhone) {
    sendResponse(0, __LINE__, 'Phone not in local database');
} else {
    // Get email address and activation key from the user object
    $jClient = $jInnerData->$sResetPhone;
    $sEmail = $jClient->email;
    $sActivationKey = $jClient->activationKey;

    //Build email
    $sTo = $sEmail;
    $sSubject = "Password reset";

    $sLink = "http://takodesign.one/bank/reset-password.php?phone=$sResetPhone&key=$sActivationKey";

    $sMailMessage = '
    <html>
    <head>
        <title>Password reset</title>
    </head>
    <body>
        <h3>Click on this link to reset your password:</h3>
        <div>
            <a href="'.$sLink.'">Reset password!</a>
        </div>
    </body>
    </html>
    ';

    // Always set content-type when sending HTML email
    $sHeaders = "MIME-Version: 1.0" . "\r\n";
    $sHeaders .= 'Content-type: text/html; charset=utf-8' . "\r\n";
    $sHeaders .= 'From: mail@takodesign.one' . "\r\n";

    if( !mail($sTo,$sSubject,$sMailMessage,$sHeaders)) {
        sendResponse(0, __LINE__, 'We have problems processing your request. Try again later');
    } else {

        $sData = json_encode($jData, JSON_PRETTY_PRINT);
        file_put_contents('../data/clients.json', $sData);

        sendResponse(1, __LINE__, 'Your account has been created. Check your E-mails to activate your account. NOTE: This might take up to several minutes');
    }
}


// ****************************************************************************************************

function sendResponse($iStatus, $iLineNumber, $sMessage) {
    echo '{"status":'.$iStatus.', "code":'.$iLineNumber.',"message":"'.$sMessage.'"}';
    exit;
}