<?php

// ini_set('display_errors', 0);

$sLoginPhone = $_POST['txtLoginPhone'] ?? '';
if( empty($sLoginPhone) ) {fnvSendResponse(0, __LINE__);}
if( strlen($sLoginPhone) != 8 ) {fnvSendResponse(0, __LINE__);}
if(!ctype_digit($sLoginPhone)) {fnvSendResponse(0, __LINE__);}


$sLoginPassword = $_POST['txtLoginPassword'] ?? '';
if( empty($sLoginPassword) ) {fnvSendResponse(0, __LINE__);}
if( strlen($sLoginPassword) < 6 ) {fnvSendResponse(0, __LINE__);}
if( strlen($sLoginPassword) > 20 ) {fnvSendResponse(0, __LINE__);}

// Get data from database
$sData = file_get_contents('../data/clients.json');
$jData = json_decode($sData);
if($jData == null) {fnvSendResponse(0, __LINE__);}

// Match profile from session with database
$jInnerData = $jData->data;
$jClient = $jInnerData->$sLoginPhone;

if( !$jInnerData->$sLoginPhone->active == 1) {
    fnvSendResponse(0, __LINE__, "User has not been activated yet");
}


if( $jClient->attemptsLeft == 0 ) {
    $iSecondsElapsedSinceLastLoginAttempt = $jClient->lastLoginAttempt + 5 - time(); //Timer

    if($iSecondsElapsedSinceLastLoginAttempt <= 0) {

        if( !password_verify($sLoginPassword, $jClient->password) ) {
            $jInnerData->$sPhone->lastLoginAttemptTime = time();

            $iSecondsElapsedSinceLastLoginAttempt = $jClient->lastLoginAttempt + 5 - time();

            fnvSendResponse(0, __LINE__, "Wrong credentials. You have to wait $iSecondsElapsedSinceLastLoginAttempt seconds again");

        } else {
            $jClient->attemptsLeft = 3;
            $jClient->lastLoginAttempt = time();

            $sData = json_encode($jData, JSON_PRETTY_PRINT);
            file_put_contents("../data/clients.json", $sData);
            
            session_start();
            $_SESSION['sUserId'] = $sLoginPhone;
            fnvSendResponse(1, __LINE__, "Login Successfull");
        }
    } else {
        fnvSendResponse(0, __LINE__, "Wait $iSecondsElapsedSinceLastLoginAttempt seconds and then try again");
    }
}

if( !password_verify($sLoginPassword, $jClient->password) ) {
    $jClient->attemptsLeft --; //just like in JS
    $jClient->lastLoginAttempt = time();

    $sData = json_encode($jData, JSON_PRETTY_PRINT);
    file_put_contents("../data/clients.json", $sData); //Always use pretty print
    fnvSendResponse(0, __LINE__, "Wrong credentials. You have {$jClient->attemptsLeft} login attempts Left");
} else {
    $jClient->attemptsLeft = 3;
    $jClient->lastLoginAttempt = time();

    $sData = json_encode($jData, JSON_PRETTY_PRINT);
    file_put_contents("../data/clients.json", $sData);
    
    session_start();
    $_SESSION['sUserId'] = $sLoginPhone;
    fnvSendResponse(1, __LINE__, "Login Successfull");
}

// ****************************************************************************************************

function fnvSendResponse($iStatus, $iLineNumber, $sMessage = false) {
    echo '{"status":'.$iStatus.',"code":'.$iLineNumber.',"message":"'.$sMessage.'"}';
    exit;
}