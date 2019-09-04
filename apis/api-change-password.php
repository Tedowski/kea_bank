<?php

ini_set('user_agent', 'any');
// ini_set('display_errors', 0);

// Get session
session_start();
$sUserId = $_SESSION['sUserId'];

if( !isset($_SESSION['sUserId'] ) ){
    sendResponse(-1, __LINE__, 'You must be logged in to use this API');
}

// Get data from database
$sData = file_get_contents('../data/clients.json');
$jData = json_decode($sData);
if($jData == null) {sendResponse(0, __LINE__, 'Cannot procces your request at the moment, please try again later');}

// Match profile from session with database
$jInnerData = $jData->data;
$jClient = $jInnerData->$sUserId;

// Check if old password matches hashed saved password // if( $sOldPassword != $jClient->password) {sendResponse(0, __LINE__);}
$sOldPassword = $_POST['txtOldPassword'] ?? '';
if(!password_verify($sOldPassword, $jClient->password)) { sendResponse(0, __LINE__, 'Invalid old password');}


// Validate New password + Check if it is not the same as the old password
$sNewPassword = $_POST['txtNewPassword'] ?? '';
if( empty($sNewPassword) ) {sendResponse(0, __LINE__, 'You must enter new password');}
if( strlen($sNewPassword) < 6 ) {sendResponse(0, __LINE__, 'New password must be at least 6 characters long');}
if( strlen($sNewPassword) > 20 ) {sendResponse(0, __LINE__, 'New password must be bellow 20 characters long');}
if( password_verify($sNewPassword, $jClient->password)) { sendResponse(0, __LINE__, 'New password cannot be same as old password');}

// Check if Confirm password matches
$NewPassConfirm = $_POST['txtNewPassConfirm'];
if( $NewPassConfirm != $sNewPassword) {sendResponse(0, __LINE__, 'New password and Confirm password do not match');}

$jClient->password = password_hash($sNewPassword, 1);

$sData = json_encode($jData, JSON_PRETTY_PRINT);

file_put_contents('../data/clients.json', $sData);

sendResponse(1, __LINE__, 'Password changed successfully');

// ****************************************************************************************************

function sendResponse($iStatus, $iLineNumber, $sMessage) {
    echo '{"status":'.$iStatus.', "code":'.$iLineNumber.',"message":"'.$sMessage.'"}';
    exit;
}