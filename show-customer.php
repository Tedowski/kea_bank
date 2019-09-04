<?php

$sPhone = $_GET['phone'];

// Get data
$sData = file_get_contents('data/clients.json');
$jData = json_decode($sData);
$jInnerData = $jData->data;

require_once 'top.php';
?>

<?php
//loop through clients and find match in the id
foreach($jInnerData as $sClientId => $jClient) {
    //flip the active key to zero
    if($sClientId == $_GET['phone']) {
        $jLoans = $jClient->loans;
        $jAccounts = $jClient->accounts;

        echo "<div class='client box item drop-shadow-main'>
                <div>id:$sClientId</div>
                <div>name:$jClient->name $jClient->lastName</div>
                <div>active:$jClient->active</div>

                <div class='form-div'>
                    <form action='apis/api-add-money-to-account.php'>
                        <input readonly type='text' id='txtPhone' name='txtPhone' value='$sClientId'>
                        <input type='text' id='txtAmount' name='txtAmount' placeholder='Amount'>
                        <button class='button button-main'>Add money to the account</button>
                    </form>
                </div>
            </div>";

        foreach($jLoans as $sLoanId => $jLoan) {
            if($jLoan->approved == 0) {
                echo "<div class='loan box item drop-shadow-main'>
                        <div>ID:$sLoanId</div>
                        <div>Amount:$jLoan->amount</div>
                        <div>Date:$jLoan->date</div>
                        <div>Account:$jLoan->account</div>
                        <div>Due date:$jLoan->dueDate</div>
                        <a class='button button-main button-a' href='apis/api-approve-loan.php?phone=$sClientId&loanId=$sLoanId'>Approve Loan</a>
                    </div>";
            }
        }
    }
}
?>

<?php
$sLinkToScript = '<script src="js/admin.js"></script>';
require_once 'bottom.php'; 

?>