<?php 

// Disable php errors
//ini_set('user_agent', 'any');
ini_set('display_errors', 0);

// Validate Get Input
if( empty($_GET['key'] )){
    sendResponse(-1, __LINE__, 'Error! Activation key missing - Invalid link');
}
$sActivationKey = $_GET['key'];

// Get data
$sData = file_get_contents('../data/clients.json');
$jData = json_decode($sData);

if($jData == null) {sendResponse(0, __LINE__, 'Cannot procces your request at the moment, please try again later');}
$jInnerData = $jData->data;

//Loop through clients and find matching Activation key
foreach($jInnerData as $sClientKey => $jClient) {
    if( $sActivationKey == $jClient->activationKey) {
        $jClient->active = 1;

        $jClient->attemptsLeft = 3;
        $jClient->lastLoginAttempt = time();
        
        //echo json_encode($jClient);
        $sData = json_encode($jData, JSON_PRETTY_PRINT);
        file_put_contents('../data/clients.json', $sData);

        // sendResponse(1, __LINE__, 'Profile activation successful');
        header('Location: ../login');
    }
}

// ****************************************************************************************************

function sendResponse($iStatus, $iLineNumber, $sMessage) {
    echo '{"status":'.$iStatus.', "code":'.$iLineNumber.',"message":"'.$sMessage.'"}';
    exit;
}