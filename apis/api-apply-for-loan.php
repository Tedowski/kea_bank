<?php

// ini_set('user_agent', 'any');
ini_set('display_errors', 0);

// Get session
session_start();
$sUserId = $_SESSION['sUserId'];

if(!isset($_SESSION['sUserId'])) {
    sendResponse(-1, __LINE__, 'You must be logged in to use this API');
}

$iAmount = $_POST['txtAmount'] ?? '';
if( empty($iAmount) ) {sendResponse(0, __LINE__, 'Amount missing');}
if(!ctype_digit($iAmount)) {sendResponse(0, __LINE__, 'Amount can be only digits');}
if( intval($iAmount) < 5000 ) {sendResponse(0, __LINE__, 'Requested amount must be at least 5000');}
if( intval($iAmount) > 30000 ) {sendResponse(0, __LINE__, "This bank only issues online loans to 30000 DKK");}

$iDueDate = $_POST['slcDueDate'] ?? '';
if( empty($iDueDate) ) {sendResponse(0, __LINE__, "You must select loan length");}

$sLoanAccount = $_POST['slcLoanAccount'];
if( empty($sLoanAccount) ) {sendResponse(0, __LINE__, 'You must select targt account');}

// Get data from database
$sData = file_get_contents('../data/clients.json');
$jData = json_decode($sData);
if($jData == null) {sendResponse(0, __LINE__, 'Cannot procces your request at the moment, please try again later');}

// Match profile from session with database
$jInnerData = $jData->data;
$jClient = $jInnerData->$sUserId;

// Add loan to the loans array
$jLoanId = uniqid();
$jLoan->date = time();
$jLoan->amount = intval($iAmount);
$jLoan->account = $sLoanAccount;
$jLoan->dueDate = strtotime("+$iDueDate years", time());
$jLoan->approved = 0;
$jClient->loans->$jLoanId = $jLoan;


// Write data back to the database
$sData = json_encode($jData, JSON_PRETTY_PRINT);
file_put_contents('../data/clients.json', $sData);
sendResponse(1, __LINE__, 'Great! Application has been successfully processed');


// ****************************************************************************************************

function sendResponse($iStatus, $iLineNumber, $sMessage) {
    echo '{"status":'.$iStatus.', "code":'.$iLineNumber.',"message":"'.$sMessage.'"}';
    exit;
}

// User will fill form to apply for a new loan
// He can choose for how much and for how long (and which account)
// The loan will be added to the loans object with active "key" set to 0
// Admin will have to approve tho loan -> money will be added to the account

// date('M-Y',$jClient->loans->$jLoanId->dueDate)