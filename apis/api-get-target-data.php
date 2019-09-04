<?php

// ini_set('user_agent', 'any');
ini_set('display_errors', 0);

session_start();
$sUserId = $_SESSION['sUserId'];

if(!isset($_SESSION['sUserId'])) {
    sendResponse(-1, __LINE__, 'You must be logged in to use this API');
}

// Validate GET input
$sObjectToUpdate = $_GET['objectToUpdate'];
if( empty($sObjectToUpdate) ) {sendResponse(0, __LINE__, 'Invalid data - Missing ObjectToUpdate');}

// Get data from database
$sData = file_get_contents('../data/clients.json');
$jData = json_decode($sData);
if($jData == null) {sendResponse(-1, __LINE__, 'Cannot update data - Check your connection or contact tech support');}

// Match profile from session with database
$jInnerData = $jData->data;
$jClient = $jInnerData->$sUserId;

// Match GET input with object array I want to update
if(!$jClient->$sObjectToUpdate) {
    sendResponse(-1, __LINE__, 'Invalid request!');
} else {
    $jTargetObject = $jClient->$sObjectToUpdate;

    // return the object as json back to the client
    sendTargetObject(1, $jTargetObject);
}




// ****************************************************************************************************

function sendResponse($iStatus, $iLineNumber, $sMessage) {
    echo '{"status":'.$iStatus.', "code":'.$iLineNumber.',"message":"'.$sMessage.'"}';
    exit;
}

function sendTargetObject($iStatus, $jTargetData) {
    $jFinalObject->status = 1;
    $jFinalObject->data = $jTargetData;
    echo json_encode($jFinalObject);
    exit;
}