<?php

ini_set('display_errors', 0);

// Validate input

$sLoginPhone = $_POST['txtLoginPhone'] ?? '';
if( empty($sLoginPhone) ) {sendResponse(0, __LINE__, 'Missing Phone');}
if(!ctype_digit($sLoginPhone)) {sendResponse(0, __LINE__, 'Phone must be only digits');}
if( strlen($sLoginPhone) != 8 ) {sendResponse(0, __LINE__, 'Phone must be 8 digits long');}

$sLoginPassword = $_POST['txtLoginPassword'] ?? '';
if( empty($sLoginPassword) ) {sendResponse(0, __LINE__, 'Missing Password');}
if( strlen($sLoginPassword) < 6 ) {sendResponse(0, __LINE__, 'Password must be at least 6 characters long');}
if( strlen($sLoginPassword) > 20 ) {sendResponse(0, __LINE__, 'Password must be bellow 20 characters');}

// Get data

$sData = file_get_contents('../data/clients.json');
$jData = json_decode($sData);
if($jData == null) {sendResponse(0, __LINE__, 'Cannot procces your request at the moment, please try again later');}
$jInnerData = $jData->data;

// Match login to data

if(!$jInnerData->$sLoginPhone) {
    sendResponse(0, __LINE__, 'Phone not registered');
}

if(!password_verify($sLoginPassword, $jInnerData->$sLoginPhone->password)) {
    sendResponse(0, __LINE__, 'Incorrect password');
}

if( !$jInnerData->$sLoginPhone->active == 1) {
    sendResponse(0, __LINE__, 'User is not active');
}

//SUCCESS -> start session
session_start();
$_SESSION['sUserId'] = $sLoginPhone;
sendResponse(1, __LINE__, 'Log-in successful');

// ****************************************************************************************************

function sendResponse($iStatus, $iLineNumber, $sMessage) {
    echo '{"status":'.$iStatus.', "code":'.$iLineNumber.',"message":"'.$sMessage.'"}';
    exit;
}