<?php 

// Validate the key
// If phone exists in this bank
// Get the amount and the message
// set new balance to the phone number
// Reply to the server that the transaction was succesfull
// else reply with error

// Disable errors
ini_set('display_errors', 0);

// Get data from local database
$sData = file_get_contents('../data/clients.json');
$jData = json_decode($sData);

//Check if data is valid
if( $jData == null) {
    fnvSendResponse(-1,__LINE__,"Cannot convert data to json");
}

// Get inside the database data layer
$jInnerData = $jData->data;

// Get data from provided URL params
$sPhoneFromOtherServer = $_GET['phone'];
$iAmount = $_GET['amount'];
$sMessageFromOtherServer = $_GET['message'];


// THE MAIN BRAIN
if( !$jInnerData->$sPhoneFromOtherServer) {
    // If the phone is not in our database, send error response

    fnvSendResponse(0,__LINE__,"Phone not found in TEDsky bank");
} else {
    // If the phone is in our database, add the amount to main account

    // $jInnerData->$sPhoneFromOtherServer->balance += $iAmount;

    $jAccounts = $jInnerData->$sPhoneFromOtherServer->accounts;
    foreach($jAccounts as $jAccountId => $jAccount) {
        if($jAccount->main == 1) {
            $jAccount->balance += $iAmount;
        }
    }

    //Make the transaction object
    $jTransactionId = uniqid();

    $jTransaction->date = time();
    $jTransaction->amount = $iAmount;
    $jTransaction->direction = "in";
    $jTransaction->name = 'Bob';
    $jTransaction->lastName = 'Marley';
    $jTransaction->phone = '10101010';
    $jTransaction->message = $sMessageFromOtherServer;

    $jInnerData->$sPhoneFromOtherServer->transactions->$jTransactionId = $jTransaction;

    // Write the data back to local database
    $sData = json_encode($jData, JSON_PRETTY_PRINT);
    file_put_contents('../data/clients.json', $sData);
    fnvSendResponse(1, __LINE__, "Transaction Success to a phone registered at TEDsky Bank");
}




// ****************************************************************************************************

function fnvSendResponse($iStatus, $iLineNumber, $sMessage) {
    echo '{"status":'.$iStatus.',"code":'.$iLineNumber.',"message":"'.$sMessage.'"}';
    exit;
}