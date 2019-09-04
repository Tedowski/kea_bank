<?php

// Disable php errors
// ini_set('user_agent', 'any');
ini_set('display_errors', 0);

// Get data from database
$sData = file_get_contents('../data/clients.json');
$jData = json_decode($sData);
if($jData == null) {sendResponse(0, __LINE__,'Data corrupted');}

$jInnerData = $jData->data;

//Validate Input
$sPhone = $_POST['txtResetPhone'] ?? '';
if( empty($sPhone) ) {sendResponse(0, __LINE__, 'Phone missing');}
if(!ctype_digit($sPhone)) {sendResponse(-1, __LINE__, 'Phone must be only digits');}
if( strlen($sPhone) != 8 ) {sendResponse(-1, __LINE__, 'Phone must be 8 digits');}

$sActivationKey = $_POST['txtActivationKey'] ?? '';
if( empty($sActivationKey) ) {sendResponse(0, __LINE__, 'Activation key missing');}

$sNewPassword = $_POST['txtNewPassword'] ?? '';
if( empty($sNewPassword) ) {sendResponse(0, __LINE__ ,'Password cannot be empty');}
if( strlen($sNewPassword) < 6 ) {sendResponse(0, __LINE__,'Password must be at least 6 characters');}
if( strlen($sNewPassword) > 20 ) {sendResponse(0, __LINE__,'Password must be under 20 characters');}
if( password_verify($sNewPassword, $jClient->password)) { sendResponse(0, __LINE__,'Password cannot be the same as the old one');}

// Check if Confirm password matches
$NewPassConfirm = $_POST['txtNewPassConfirm'] ?? '';
if( $NewPassConfirm != $sNewPassword) {sendResponse(0, __LINE__,'Passwords do not match');}

// If phone not found
if( !$jInnerData->$sPhone ) {
    sendResponse(0, __LINE__, "Error! Invalid link - User not in the database");
}

// If phone found and activation key matches -> change password
if( $jInnerData->$sPhone && $jInnerData->$sPhone->activationKey == $sActivationKey ) {
    $jClient = $jInnerData->$sPhone;

    $jClient->password = password_hash($sNewPassword, 1);

    $sData = json_encode($jData, JSON_PRETTY_PRINT);
    file_put_contents('../data/clients.json', $sData);

    sendResponse(1, __LINE__, "Password changed with Success");
}

sendResponse(0, __LINE__, "Error! Invalid link");
// ****************************************************************************************************

function sendResponse($iStatus, $iLineNumber, $sMessage) {
    echo '{"status":'.$iStatus.', "code":'.$iLineNumber.',"message":"'.$sMessage.'"}';
    exit;
}