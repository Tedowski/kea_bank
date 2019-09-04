<?php

$sTargetPhone = $_GET['phone'];

$sLoanId = $_GET['loanId'];

// Get data
$sData = file_get_contents('../data/clients.json');
$jData = json_decode($sData);

$jInnerData = $jData->data;
$jClient = $jInnerData->$sTargetPhone;
$jAccounts = $jClient->accounts;
$jLoans = $jClient->loans;

foreach($jLoans as $sLoanKey => $jLoan) {
    if($sLoanKey == $sLoanId) {
        $jLoan->approved = 1;

        foreach($jAccounts as $jAccountId => $jAccount) {
            if($jAccountId == $jLoan->account) {
                $jAccount->balance += $jLoan->amount;
            }
        }

        $sData = json_encode($jData, JSON_PRETTY_PRINT);

        file_put_contents('../data/clients.json', $sData);
        header("Location: ../show-customer.php?phone=$sTargetPhone");
    }
}