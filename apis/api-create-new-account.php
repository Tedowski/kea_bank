<?php

// ini_set('user_agent', 'any');
ini_set('display_errors', 0);

// Get session
session_start();
$sUserId = $_SESSION['sUserId'];

if(!isset($_SESSION['sUserId'])) {
    sendResponse(-1, __LINE__, 'You must be logged in to use this API');
}

$sAccountType = $_POST['slcAccountType'];
if( empty($sAccountType) ) {sendResponse(0, __LINE__, 'You must select Account type');}

$sAccountCurrency = $_POST['slcCurrency'];
if( empty($sAccountCurrency) ) {sendResponse(0, __LINE__, 'You must select Currency');}

// Get data from database
$sData = file_get_contents('../data/clients.json');
$jData = json_decode($sData);
if($jData == null) {sendResponse(0, __LINE__, 'Cannot procces your request at the moment, please try again later');}

// Match profile from session with database
$jInnerData = $jData->data;
$jClient = $jInnerData->$sUserId;
$jAccounts = $jClient->accounts;

// Add new account to the accounts object array
$jAccountId = uniqid();
$jAccount->balance = 0;
$jAccount->type = $sAccountType;
$jAccount->currency = $sAccountCurrency;
$jAccount->main = 0;
$jAccounts->$jAccountId = $jAccount;

// Write data back to the database
$sData = json_encode($jData, JSON_PRETTY_PRINT);
file_put_contents('../data/clients.json', $sData);
sendResponse(1, __LINE__, 'Great! New account has been successfully created!');


// ****************************************************************************************************

function sendResponse($iStatus, $iLineNumber, $sMessage) {
    echo '{"status":'.$iStatus.', "code":'.$iLineNumber.',"message":"'.$sMessage.'"}';
    exit;
}