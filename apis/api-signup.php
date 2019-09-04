<?php

ini_set('display_errors', 0);

$sPhone = $_POST['txtSignupPhone'] ?? '';

if( empty($sPhone) ) {sendResponse(0, __LINE__, 'Phone missing');}
if(!ctype_digit($sPhone)) {sendResponse(0, __LINE__, 'Phone must be only digits');}
if( strlen($sPhone) != 8 ) {sendResponse(0, __LINE__, 'Phone must be 8 digits');}
if( intval($sPhone) < 10000000 ) {sendResponse(0, __LINE__, 'Phone invalid');}
if( intval($sPhone) > 99999999 ) {sendResponse(0, __LINE__, 'Phone invalid');}

// Validate name and last name

$sName = $_POST['txtSignupName'] ?? '';
if( empty($sName) ) {sendResponse(0, __LINE__, 'Name missing');}
if( !preg_match('/^([^0-9]*)$/', $sName) ) {sendResponse(0, __LINE__, 'Name cannot contain numbers');}
if( strlen($sName) < 2 ) {sendResponse(0, __LINE__, 'Name must be at least 2 characters');}
if( strlen($sName) > 20 ) {sendResponse(0, __LINE__, 'Name must be below 20 characters');}

$sLastName = $_POST['txtSignupLastName'] ?? '';
if( empty($sLastName) ) {sendResponse(0, __LINE__, 'Last name missing');}
if( !preg_match('/^([^0-9]*)$/', $sLastName) ) {sendResponse(0, __LINE__, 'Name cannot contain numbers');}
if( strlen($sLastName) < 2 ) {sendResponse(0, __LINE__, 'Name must be at least 2 characters');}
if( strlen($sLastName) > 20 ) {sendResponse(0, __LINE__, 'Name must be below 20 characters');}

// Validate email

$sEmail = $_POST['txtSignupEmail'] ?? '';
if( empty($sEmail) ) {sendResponse(0, __LINE__, 'E-mail missing');}
//if( strlen($sEmail) < 2 ) {sendResponse(0, __LINE__);}
if( strlen($sEmail) > 50 ) {sendResponse(0, __LINE__, 'E-mail must be bellow 50 characters');}
if (!filter_var($sEmail, FILTER_VALIDATE_EMAIL)) {sendResponse(0, __LINE__, 'invalid E-mail');}

// Validate CPR

$iCPR = $_POST['txtSignupCPR'] ?? '';
$regExCPR = '/^(?:(?:31(?:0[13578]|1[02])|(?:30|29)(?:0[13-9]|1[0-2])|(?:0[1-9]|1[0-9]|2[0-8])(?:0[1-9]|1[0-2]))[0-9]{2}\s?-?\s?[0-9]|290200-?[4-9]|2902(?:(?!00)[02468][048]|[13579][26])\s?-?\s?[0-3])[0-9]{3}|000000\s?-?\s?0000$/'; 

if( empty($iCPR) ) {sendResponse(0, __LINE__, 'CPR Missing');}
if( strlen($iCPR) != 10 ) {sendResponse(0, __LINE__, 'CPR Must be 10 characters long');}
if( !preg_match($regExCPR, $iCPR) ) {sendResponse(0, __LINE__, 'Invalid CPR');}

// Validate password

$sPassword = $_POST['txtSignupPassword'] ?? '';
$sPasswordRetyped = $_POST['txtSignupPasswordRetyped'] ?? '';
if( empty($sPassword) ) {sendResponse(0, __LINE__, 'Password Missing');}
if( strlen($sPassword) < 6 ) {sendResponse(0, __LINE__, 'Password must be atleast 6 characters');}
if( strlen($sPassword) > 20 ) {sendResponse(0, __LINE__, 'Password must be bellow 20 characters');}
if( empty($sPasswordRetyped) ) {sendResponse(0, __LINE__, 'You need to confirm new password');}
if($sPassword != $sPasswordRetyped) {sendResponse(0, __LINE__, 'Passwords do not match');}

// Get data
$sData = file_get_contents('../data/clients.json');
$jData = json_decode($sData);

if($jData == null) {sendResponse(-1, __LINE__, 'Cannot process your request. Please try again later');}
$jInnerData = $jData->data;

//Check if phone is not already registered in our database
if( $jInnerData->$sPhone ) {
    sendResponse(0, __LINE__, 'Phone already registered');
}

// "111":{"name": "A"}
$jClient = new stdclass(); //json object
$jClient->name = $sName;
$jClient->lastName = $sLastName;
$jClient->email = $sEmail;
$jClient->password = password_hash($sPassword, 1);
$jClient->cpr = $iCPR;

$jClient->active = 0;
$jClient->activationKey = uniqid();

// Login Attempts will be added when the account is activated by the user via mail

$jClient->accounts = new stdClass();

$jAccountId = uniqid();
$jAccount->balance = 0;
$jAccount->type = "main";
$jAccount->currency = "DKK";
$jAccount->main = 1;
$jClient->accounts->$jAccountId = $jAccount;

$jClient->loans = new stdClass();
$jClient->cards = new stdClass();
$jClient->requests = new stdClass();

$jClient->transactions = new stdClass();
$jInnerData->$sPhone = $jClient;

// Success
// Send the link to the user's mail
$sTo = $sEmail;
$sSubject = "Activate Account";

$sLink = "http://takodesign.one/bank/apis/api-activate-index.php?key={$jClient->activationKey}";

$sMailMessage = '
<html>
<head>
    <title>Account Activation</title>
</head>
<body>
    <h3>Click on this link to to activate your account:</h3>
    <div>
        <a href="'.$sLink.'">Activate Account!</a>
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



// ****************************************************************************************************

function sendResponse( $iStatus, $iLineNumber, $sMessage) {
    echo '{"status":'.$iStatus.', "code":'.$iLineNumber.',"message":"'.$sMessage.'"}';
    exit;
}