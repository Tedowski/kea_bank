<?php

// Check if the user is logged
session_start();
$sUserId = $_SESSION['sUserId'];

$sData = file_get_contents('../data/clients.json');
$jData = json_decode($sData);
$jInnerData = $jData->data;

$sTransactionsNotRead = $jInnerData->$sUserId->transactionsNotRead;

echo json_encode($sTransactionsNotRead);
// TODO Delete what is is read from NotRead