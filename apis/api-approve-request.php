<?php

//ini_set('user_agent','any');
ini_set('display_errors',0);

session_start();
$sUserId = $_SESSION['sUserId'];

if( !isset($_SESSION['sUserId'] ) ){
    sendResponse(-1, __LINE__, 'You must be logged in to use this API');
}

//GET Request number from GET and Validate
$iRequestToApprove = $_GET['requestToApprove'];
if( empty($iRequestToApprove) ) {sendResponse(0, __LINE__, 'Invalid data - missing request ID');}

// Get data from database
$sData = file_get_contents('../data/clients.json');
$jData = json_decode($sData);
if($jData == null) {sendResponse(-1, __LINE__, 'Cannot update data - Check your connection or contact tech support');}

// Match profile from session with database
$jInnerData = $jData->data;
$jClient = $jInnerData->$sUserId;
$jRequests = $jClient->requests;
$jAccounts = $jClient->accounts;

//Loop through requests and find the coresponding one
foreach($jRequests as $sRequestId => $jRequest) {
    if( $iRequestToApprove == $sRequestId ) {
        //Get the amount from request
        $iAmount = intval($jRequest->amount);

        // Check if sender has enough money
        foreach($jAccounts as $jAccountId => $jAccount) {
            if($jAccount->main == 1) {
                $sBalance = $jAccount->balance;
        
                if($sBalance < $iAmount) {
                    sendResponse(0, __LINE__, 'Not enough money');
                }
            }
        }

        //Get the target phone from the request
        $sTargetPhone = $jRequest->from;

        //Check if client is in the local database
        if(!$jInnerData->$sTargetPhone) {
            sendResponse(0, __LINE__, 'Sender not found in local database');
        } else {
            //Get target client data
            $jTargetClient = $jInnerData->$sTargetPhone;
            $jTargetAccounts = $jTargetClient->accounts;
            
            //Loop through his accounts and find his main account and add money
            foreach($jTargetAccounts as $jTargetAccountId => $jTargetAccount) {
                if($jTargetAccount->main == 1) {
                    $jTargetAccount->balance += $iAmount;
                }
            }

            //Loop through senders account and deduct the amount
            foreach($jAccounts as $jAccountId => $jAccount) {
                if($jAccount->main == 1) {
                    $jAccount->balance -= $iAmount;
                }
            }

            //Add incoming transaction data to recievers transactions object
            $jTransactionId = uniqid();

            $jTransaction->date = time();
            $jTransaction->amount = $iAmount;
            $jTransaction->direction = 'in';
            $jTransaction->name = 'Jon';
            $jTransaction->lastName = 'Doe';
            $jTransaction->phone = $sUserId;
            $jTransaction->message = "request";

            $jTargetClient->transactions->$jTransactionId = $jTransaction;

            //Make an outgoing transaction object to the senders transaction object
            $jTransactionLocalOut->date = time();
            $jTransactionLocalOut->amount = $iAmount;
            $jTransactionLocalOut->direction = "out";
            $jTransactionLocalOut->name = 'Jon';
            $jTransactionLocalOut->lastName = 'Doe';
            $jTransactionLocalOut->phone = $sTargetPhone;
            $jTransactionLocalOut->message = "Request";

            $jClient->transactions->$jTransactionId = $jTransactionLocalOut;

            //Remove the object
            $jRequest = json_decode('{}');
            $jRequests->$iRequestToApprove = $jRequest;
    
            //Unset the key
            unset($jRequests->$iRequestToApprove);

            // Write data back to local database
            $sData = json_encode($jData, JSON_PRETTY_PRINT);
            file_put_contents('../data/clients.json', $sData);
    
            // sendResponse(1, __LINE__, 'Request handled successfully');
            sendTargetObjectWithMessage(1, $jRequests, 'Request succesfully handled');
        }
    }
}
sendResponse(-1, __LINE__, 'Request not found');

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