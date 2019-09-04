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

$sSenderPhone = $_SESSION['sUserId'];

if( empty($_GET['phone'] )){
    sendResponse(-1, __LINE__, 'Phone missing');
}

if( empty($_GET['amount'] )){
    sendResponse(-1, __LINE__, 'Amount missing');
}

// Variables from GET
$sPhone = $_GET['phone'] ?? '';
if(!ctype_digit($sPhone)) {sendResponse(-1, __LINE__, 'Phone must be only digits');}
if( strlen($sPhone) != 8 ) {sendResponse(-1, __LINE__, 'Phone must be 8 digits');}

$iAmount = $_GET['amount'] ?? '';
if(!ctype_digit($iAmount)) {sendResponse(-1, __LINE__, 'Amount must be digits');}

// Validate message
$sTransferMessage = $_GET['message'];
if( !preg_match('/^[A-Za-z0-9 _]*[A-Za-z0-9][A-Za-z0-9 _]*$/', $sTransferMessage) ) {
    sendResponse(0, __LINE__, 'Message must contain only letters and numbers');
}

// Get data from database
$sData = file_get_contents('../data/clients.json');
$jData = json_decode( $sData );

// Check if data is valid
if($jData == null) {
    sendResponse(-1, __LINE__, 'Cannot convert to JSON');
}

// Get inside the json object
$jInnerData = $jData->data;


// ****************************************************************************************************

//Check if sender has enough money //

//Get the sender info
$jClient = $jInnerData->$sSenderPhone;
$jClientAccounts = $jClient->accounts;

//Check if sender is not same as reciever
if($sPhone == $sSenderPhone) {sendResponse(0, __LINE__, 'Cannot send money to yourself');}

//Check the balance of senders main accounts
foreach($jClientAccounts as $jClientAccountId => $jClientAccount) {
    if($jClientAccount->main == 1) {
        $sBalance = $jClientAccount->balance;

        if($sBalance < $iAmount) {
            sendResponse(0, __LINE__, 'Not enough money');
        }
    }
}

$jTransactionId = uniqid();

// If the phone is not in the local database talk to other banks
if( !$jInnerData->$sPhone) {
    $jListOfBanks = fnjGetListOfBanksFromCentralBank();

    // Loop through list banks
    foreach($jListOfBanks as $sKey => $jBank) {
        $sUrl = $jBank->url.'/apis/api-handle-transaction?phone='.$sPhone.'&amount='.$iAmount.'&message='.$sTransferMessage;
        $sBankResponse = file_get_contents($sUrl);
        $jBankResponse = json_decode($sBankResponse);


        // If the bank sends good response
        if( $jBankResponse->status == 1 && $jBankResponse->code && $jBankResponse->message) {
            
            // If everything went fine, deduct money from Senders account
            foreach($jClientAccounts as $jClientAccountId => $jClientAccount) {
                if($jClientAccount->main == 1) {
                    $jClientAccount->balance -= $iAmount;
                }
            }

            //Make an outgoing transaction object

            $jTransactionOut->date = time();
            $jTransactionOut->amount = $iAmount;
            $jTransactionOut->direction = "out";
            $jTransactionOut->name = 'Jon';
            $jTransactionOut->lastName = 'Doe';
            $jTransactionOut->phone = $sPhone;
            $jTransactionOut->message = $sTransferMessage;

            $jInnerData->$sSenderPhone->transactions->$jTransactionId = $jTransactionOut;

            // Write data back to local database
            $sData = json_encode($jData, JSON_PRETTY_PRINT);
            file_put_contents('../data/clients.json', $sData);

            //Success
            sendResponse(1, __LINE__, 'message: '.$jBankResponse->message);
        }
    }
    sendResponse(2, __LINE__, 'Phone does not exist');
} else {
    // If the phone is in the local database, add money to local account
    // $jInnerData->$sPhone->balance += $iAmount;
    $jAccounts = $jInnerData->$sPhone->accounts;
    foreach($jAccounts as $jAccountId => $jAccount) {
        if($jAccount->main == 1) {
            $jAccount->balance += $iAmount;
        }
    }

    // If everything went fine, deduct money from Senders account
    foreach($jClientAccounts as $jClientAccountId => $jClientAccount) {
        if($jClientAccount->main == 1) {
            $jClientAccount->balance -= $iAmount;
        }
    }
    
    //Add incoming transaction data to recievers transactions object
    $jTransaction->date = time();
    $jTransaction->amount = $iAmount;
    $jTransaction->direction = 'in';
    $jTransaction->name = 'Jon';
    $jTransaction->lastName = 'Doe';
    $jTransaction->phone = $sSenderPhone;
    $jTransaction->message = $sTransferMessage;

    $jInnerData->$sPhone->transactions->$jTransactionId = $jTransaction;

    //Make an outgoing transaction object to the senders transaction object
    $jTransactionLocalOut->date = time();
    $jTransactionLocalOut->amount = $iAmount;
    $jTransactionLocalOut->direction = "out";
    $jTransactionLocalOut->name = 'Jon';
    $jTransactionLocalOut->lastName = 'Doe';
    $jTransactionLocalOut->phone = $sPhone;
    $jTransactionLocalOut->message = $sTransferMessage;

    $jInnerData->$sSenderPhone->transactions->$jTransactionId = $jTransactionLocalOut;


    // Write data back to local database
    $sData = json_encode($jData, JSON_PRETTY_PRINT);
    file_put_contents('../data/clients.json', $sData);

    //Send transaction data to the reciever
    sendResponse(1, __LINE__, "Transfer Successful to locally registered phone");
}




// ****************************************************************************************************

function sendResponse($iStatus, $iLineNumber, $sMessage) {
    echo '{"status":'.$iStatus.', "code":'.$iLineNumber.',"message":"'.$sMessage.'"}';
    exit;
}

function fnjGetListOfBanksFromCentralBank() {
    // Get the list of banks from remote server
    $sData = file_get_contents('https://ecuaguia.com/central-bank/api-get-list-of-banks.php?key=1111-2222-3333');
    return json_decode($sData);
}