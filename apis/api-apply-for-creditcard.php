<?php

// ini_set('user_agent', 'any');
ini_set('display_errors', 0);

// Get session
session_start();
$sUserId = $_SESSION['sUserId'];

if(!isset($_SESSION['sUserId'])) {
    sendResponse(-1, __LINE__, 'You must be logged in to use this API');
}

$sCardType = $_POST['slcCardType'];
if( empty($sCardType) ) {sendResponse(0, __LINE__, 'You must select Card type');}

$sCardAccount = $_POST['slcCardAccount'];
if( empty($sCardAccount) ) {sendResponse(0, __LINE__, 'You must select target Account');}

// Get data from database
$sData = file_get_contents('../data/clients.json');
$jData = json_decode($sData);
if($jData == null) {sendResponse(-1, __LINE__, 'Cannot procces your request at the moment, please try again later');}

// Match profile from session with database
$jInnerData = $jData->data;
$jClient = $jInnerData->$sUserId;
$jAccounts = $jClient->accounts;

// Add card to the cards object array
$jCardId = rand(1000000000000000,9999999999999999);
$jCard->type = $sCardType;
$jCard->account = $sCardAccount;
$jCard->cvv = rand(100,999);
$jCard->sent = 0;
$jClient->cards->$jCardId = $jCard;

// Add Card to the account object
foreach($jAccounts as $sAccountId => $jAccount) {
    if($sAccountId == $sCardAccount) {
        $jAccount->card = $jCardId;
        // echo json_encode($jAccounts);
        
        // Write data back to the database
        $sData = json_encode($jData, JSON_PRETTY_PRINT);
        file_put_contents('../data/clients.json', $sData);
        sendResponse(1, __LINE__, 'Great! Application has been successfully processed');
    }
}
sendResponse(0, __LINE__, 'Invalid data - Account number fail');


// ****************************************************************************************************


function sendResponse($iStatus, $iLineNumber, $sMessage) {
    echo '{"status":'.$iStatus.', "code":'.$iLineNumber.',"message":"'.$sMessage.'"}';
    exit;
}

// User will fill form to get a new credit card
// He can choose which brand (VISA/VISA ELECTRON/DANKORT /MASTERCARD)