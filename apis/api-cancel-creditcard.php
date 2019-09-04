<?php

//ini_set('user_agent','any');
ini_set('display_errors',0);

session_start();
$sUserId = $_SESSION['sUserId'];

if( !isset($_SESSION['sUserId'] ) ){
    sendResponse(-1, __LINE__, 'You must be logged in to use this API');
}

//GET Card number from GET and Validate
$iCardToCancel = $_GET['cardToCancel'];
if( empty($iCardToCancel) ) {sendResponse(0, __LINE__, 'Invalid data - Missing cardToCancel');}
if(!ctype_digit($iCardToCancel)) {sendResponse(0, __LINE__, 'Invalid data');}
if( strlen($iCardToCancel) != 16 ) {sendResponse(0, __LINE__, 'Invalid data');}
if( intval($iCardToCancel) < 1000000000000000 ) {sendResponse(0, __LINE__, 'Invalid data');}
if( intval($iCardToCancel) > 9999999999999999 ) {sendResponse(0, __LINE__, 'Invalid data');}

// Get data from database
$sData = file_get_contents('../data/clients.json');
$jData = json_decode($sData);
if($jData == null) {sendResponse(-1, __LINE__, 'Cannot update data - Check your connection or contact tech support');}

// Match profile from session with database
$jInnerData = $jData->data;
$jClient = $jInnerData->$sUserId;
$jCards = $jClient->cards;
$jAccounts = $jClient->accounts;

// Loop through the cards object and find matching card
foreach($jCards as $sCardId => $jCard) {
    if( $iCardToCancel == $sCardId ) {

        //Remove the object
        $jCard = json_decode('{}');
        $jCards->$iCardToCancel = $jCard;

        //Unset the key
        unset($jCards->$iCardToCancel);

        // Loop through the accounts
        foreach($jAccounts as $sAccountKey => $jAccount) {
            // Find the account that has the associated card
            if($jAccount->card == $iCardToCancel) {
                // remove the card from the account
                $jAccount->card = null;
            }
        }

        //Write back to the database
        $sData = json_encode($jData, JSON_PRETTY_PRINT);
        file_put_contents('../data/clients.json',$sData);

        sendTargetObjectWithMessage(1, $jCards, 'Card Successfully canceled!');
    }
}
sendResponse(0, __LINE__, 'Card not found');


// ************************************************************************************************
    
function sendResponse($iStatus, $iLineNumber, $sMessage) {
    echo '{"status":'.$iStatus.', "code":'.$iLineNumber.',"message":"'.$sMessage.'"}';
    exit;
}

function sendTargetObjectWithMessage($iStatus, $jTargetData, $sMessage) {
    $jFinalObject->status = 1;
    $jFinalObject->data = $jTargetData;
    $jFinalObject->message = $sMessage;
    echo json_encode($jFinalObject);
    exit;
}