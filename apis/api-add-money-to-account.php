<?php

$sTargetPhone = $_GET['txtPhone'];

$iAmount = $_GET['txtAmount'];

// Get data
$sData = file_get_contents('../data/clients.json');
$jData = json_decode($sData);

$jInnerData = $jData->data;
$jClient = $jInnerData->$sTargetPhone;
$jAccounts = $jClient->accounts;

foreach($jAccounts as $jAccountId => $jAccount) {
    if($jAccount->main == 1) {
        $jAccount->balance += $iAmount;

        $sData = json_encode($jData, JSON_PRETTY_PRINT);

        file_put_contents('../data/clients.json', $sData);
        header("Location: ../show-customer.php?phone=$sTargetPhone");
    }
}