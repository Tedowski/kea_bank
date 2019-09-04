<?php

// ****************************************************************************************************

session_start();

// Disable php errors
ini_set('user_agent', 'any');
ini_set('display_errors', 0);

// Validation of GET input
if(!isset($_SESSION['sUserId'])) {
    sendResponse(-1, __LINE__, 'You must Login to use this API');
}

if( empty($_GET['phone'] )){
    sendResponse(-1, __LINE__, 'Phone missing');
}

if( empty($_GET['amount'] )){
    sendResponse(-1, __LINE__, 'Amount missing');
}

// Variables from GET
$sPhoneTo = $_GET['phone'] ?? '';
if(!ctype_digit($sPhoneTo)) {sendResponse(0, __LINE__, 'Phone must be only digits');}
if( strlen($sPhoneTo) != 8 ) {sendResponse(0, __LINE__, 'Phone must be 8 digits');}

$iAmount = $_GET['amount'] ?? '';
if(!ctype_digit($iAmount)) {sendResponse(0, __LINE__, 'Amount must be digits');}

//Get sender phone
$sPhoneFrom = $_SESSION['sUserId'];

if($sPhoneFrom == $sPhoneTo) {sendResponse(0, __LINE__, 'Cannot request money from yourself');}

// Get data from database
$sData = file_get_contents('../data/clients.json');
$jData = json_decode( $sData );
if($jData == null) {
    sendResponse(-1, __LINE__, 'Cannot convert to JSON');
}
$jInnerData = $jData->data;

//Check if phone is not the same - cannot request money from myself

// ****************************************************************************************************

// Check if the phone is in the local database
if( !$jInnerData->$sPhoneTo) {
    sendResponse(0, __LINE__, 'Phone not in local database');
} else {
    // Create a request object and put in the target phones "requests" array object

    $jRequestId = uniqid();
    $jRequest->date = time();
    $jRequest->amount = $iAmount;
    $jRequest->from = $sPhoneFrom;

    $jInnerData->$sPhoneTo->requests->$jRequestId = $jRequest;

    // Write data back to local database
    $sData = json_encode($jData, JSON_PRETTY_PRINT);
    file_put_contents('../data/clients.json', $sData);

    //Send transaction data to the sender
    sendResponse(1, __LINE__, "Payment request sent successfully to $sPhoneTo");
}

// ****************************************************************************************************

function sendResponse($iStatus, $iLineNumber, $sMessage) {
    echo '{"status":'.$iStatus.', "code":'.$iLineNumber.',"message":"'.$sMessage.'"}';
    exit;
}