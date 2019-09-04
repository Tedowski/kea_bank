<?php

session_start();
$jUserId = $_SESSION['sUserId'];

if( !isset($_SESSION['sUserId']) ) {
    header('Location: login');
}

$sData = file_get_contents('data/clients.json');
$jData = json_decode($sData);
$jInnerData = $jData->data;
$jClient = $jInnerData->$jUserId;

// Get accounts object for Credit cards and Loans
$jAccounts = $jClient->accounts;

foreach($jAccounts as $jAccountId => $jAccount) {
    if($jAccount->main == 1) {
        $jAccountToDisplayOnSplash = $jAccount;
        $iAmount = $jAccount->balance;
    }
}

if($jClient->active == 0) {
    // echo '<div>You are blocked!</div>';
}

?>

<?php require_once 'top.php'; ?>

    <div class="header flex-center">
        <div class="container nav-profile">
            <button class="nav-button pointer" data-toOpen="#dashboard" data-toUpdate="transactions">Dashboard</button>
            <button class="nav-button pointer" data-toOpen="#transfer">Transfers</button>
            <button class="nav-button pointer" data-toOpen="#requests" data-toUpdate="requests">Requests</button>
            <button class="nav-button pointer" data-toOpen="#loans" data-toUpdate="loans">Loans</button>
            <button class="nav-button pointer" data-toOpen="#cards" data-toUpdate="cards">Credit cards</button>
            <button class="nav-button pointer" data-toOpen="#accounts" data-toUpdate="accounts">Accounts</button>
            <button class="nav-button pointer" data-toOpen="#options">Options</button>
            <button id="logoutButton" class="nav-button" href="logout.php"><a href="logout.php">Log out</a></button>
        </div>
    </div>

    <section id="dashboard" class="page">
        <div class="container">
            <h1>Hello <?= $jClient->name.' '.$jClient->lastName; ?>!</h1>
            <div class="page-content">
                <div class="box item aside drop-shadow-main">
                    <div class="box-header pointer" data-toOpen="#accounts" data-toUpdate="accounts">
                        <div class="box-header-icon circle"></div>
                        <div class="box-header-text">
                            <p>Account</p>
                            <h3><?= $jClient->name.' '.$jClient->lastName; ?></h3>
                        </div>
                    </div>
                    <hr>
                    <div class="box-content pointer" data-toOpen="#accounts" data-toUpdate="accounts">
                        <p>Balance</p>
                        <h4 class="money"><?= $iAmount; ?> <span><?= $jAccountToDisplayOnSplash->currency; ?></span></h4>
                    </div>
                    <div class="box-footer pointer" data-toOpen="#transfer">
                        New Transfer
                    </div>
                </div>
                <div class="box scroll item main drop-shadow-main">
                    <div class="box-header">
                        <div class="box-header-icon circle"></div>
                        <div class="box-header-text">
                            <h2>Transactions history</h2>
                        </div>
                    </div>
                    <hr>
                    <div class="box-content">
                        <div id="transactionsDiv"></div>
                    </div>
                </div>
                <!-- <div class="box drop-shadow-main aside item">
                    <h2>Hello <?= $jClient->name.' '.$jClient->lastName; ?>!</h2>
                    <p>Email: <?= $jClient->email; ?></p>
                    <p>Phone: <?= $jUserId; ?></p>
                    <p class="light">Last login:  <?= date('h:i A d-M-Y',$jClient->lastLoginAttempt) ?></p>
                </div> -->
            </div>
        </div>
        <template id="transactionTemplate">
            <div class="table-row">
                <div class="row-header">
                    <h3 id="transactionDate">22/02/19</h3>
                </div>
                <div class="row-content">
                    <h3 id="transactionFromPhone"></h3>
                    <h4 id="transactionFullName"></h4>
                    <div id="transactionMessage"></div>
                </div>
                <div class="row-footer">
                    <h3 id="transactionAmount">22/02/19</h3>
                </div>
            </div>
        </template>
    </section>

    <section id="transfer" class="page hidden">
        <div class="container">
            <h1>Transfer</h1>
            <div class="page-content">
                <div class="box item aside drop-shadow-main">
                    <div class="box-header pointer" data-toOpen="#accounts" data-toUpdate="accounts">
                        <div class="box-header-icon circle"></div>
                        <div class="box-header-text">
                            <p>Account</p>
                            <h3><?= $jClient->name.' '.$jClient->lastName; ?></h3>
                        </div>
                    </div>
                    <hr>
                    <div class="box-content pointer" data-toOpen="#accounts" data-toUpdate="accounts">
                        <p>Balance</p>
                        <h4 class="money"><?= $iAmount; ?> <span><?= $jAccountToDisplayOnSplash->currency; ?></span></h4>
                    </div>
                </div>
                <div class="box scroll item main drop-shadow-main">
                    <div class="box-header">
                        <div class="box-header-icon circle"></div>
                        <div class="box-header-text">
                            <h2>Send money</h2>
                        </div>
                    </div>
                    <hr>
                    <div class="box-content">
                        <form id="frmTransfer">
                            <label for="txtTransferToPhone">Phone</label>
                            <input name="txtTransferToPhone" id="txtTransferToPhone" type="text" placeholder="Insert phone"
                            data-validate="yes" data-type="integer" data-min="8" data-max="8">
                            <label for="txtTransferAmount">Amount</label>
                            <input name="txtTransferAmount" id="txtTransferAmount" type="text" placeholder="Amount"
                            data-validate="yes" data-type="integer" data-min="1" data-max="20">
                            <label for="txtTransferMessage">Message</label>
                            <input name="txtTransferMessage" id="txtTransferMessage" type="text" placeholder="Message"
                            data-validate="yes" data-type="string" data-min="0" data-max="100">
                            <button class="button button-main">Transfer</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="requests" class="page hidden">
        <div class="container">
            <h1>Request money</h1>
            <div class="page-content">
                <div class="box item aside drop-shadow-main">
                    <div class="box-header pointer" data-toOpen="#accounts" data-toUpdate="accounts">
                        <div class="box-header-icon circle"></div>
                        <div class="box-header-text">
                            <p>Account</p>
                            <h3><?= $jClient->name.' '.$jClient->lastName; ?></h3>
                        </div>
                    </div>
                    <hr>
                    <div class="box-content pointer" data-toOpen="#accounts" data-toUpdate="accounts">
                        <p>Balance</p>
                        <h4 class="money"><?= $iAmount; ?> <span><?= $jAccountToDisplayOnSplash->currency; ?></span></h4>
                    </div>
                </div>
                <div class="box scroll item main drop-shadow-main">
                    <div class="box-header">
                        <div class="box-header-icon circle"></div>
                        <div class="box-header-text">
                            <h2>Send Request</h2>
                        </div>
                    </div>
                    <hr>
                    <div class="box-content">
                        <form id="frmRequestMoney">
                            <label for="txtRequestAmount">Enter the amount</label>
                            <input type="text" id="txtRequestAmount" name="txtRequestAmount" placeholder="Amount"
                            data-validate="yes" data-type="integer" data-min="1" data-max="20">
                            <label for="txtRequestPhone">Phone</label>
                            <input type="text" id="txtRequestPhone" name="txtRequestPhone" placeholder="From Phone"
                            data-validate="yes" data-type="integer" data-min="8" data-max="8">
                            <button class="button button-main">Request money</button>
                        </form>
                    </div>
                </div>
                <h2 class="grid-span-full">Open requests</h2>
                <div id="requestsDiv" class="div-show-data grid-span-full"></div>
            </div>
        </div>
        <template id="requestTemplate">
            <div class="box item drop-shadow-main">
                <div class="box-header">
                    <div class="box-header-icon circle"></div>
                    <div class="box-header-text">
                        <p>From</p>
                        <h3 id="requestFrom">Phone</h3>
                    </div>
                </div>
                <hr>
                <div class="box-content">
                    <p>Amount</p>
                    <h3 class="money" id="requestAmount">01/02/19</h3>
                    <p>Date</p>
                    <h3 id="requestDate"></h3>
                    <p id="requestID">03/22</p>
                </div>
                <hr>
                <button class="button button-main green approveRequest">
                    Approve request
                </button>
            </div>
        </template>
    </section>

    <section id="loans" class="page hidden">
        <div class="container">
            <h1>Apply for loan</h1>
            <div class="page-content">
                <div class="box item drop-shadow-main">
                    <div class="box-header pointer" data-toOpen="#accounts" data-toUpdate="accounts">
                        <div class="box-header-icon circle"></div>
                        <div class="box-header-text">
                            <p>Account</p>
                            <h3><?= $jClient->name.' '.$jClient->lastName; ?></h3>
                        </div>
                    </div>
                    <hr>
                    <div class="box-content pointer" data-toOpen="#accounts" data-toUpdate="accounts">
                        <p>Balance</p>
                        <h4 class="money"><?= $iAmount; ?> <span><?= $jAccountToDisplayOnSplash->currency; ?></span></h4>
                    </div>
                </div>
                <div class="box scroll item main drop-shadow-main">
                    <div class="box-header">
                        <div class="box-header-icon circle"></div>
                        <div class="box-header-text">
                            <h2>Apply for Loan</h2>
                        </div>
                    </div>
                    <hr>
                    <div class="box-content">
                        <form id="frmApplyForLoan">
                            <label for="txtAmount">Amount</label>
                            <input type="text" id="txtAmount" name="txtAmount" placeholder="Desired Amount"
                            data-validate="yes" data-type="integer" data-min="1" data-max="20">
                            <label for="slcLoanAccount">Select Loan account</label>
                            <select name="slcLoanAccount" id="slcLoanAccount" data-validate="yes" data-type="select">
                                <option value="">Select Account</option>
                                <?php 
                                    foreach($jAccounts as $sAccountId => $jAccount) {
                                        echo '<option value="'.$sAccountId.'">'.$sAccountId.'</option>';
                                    }
                                ?>
                            </select>
                            <label for="slcDueDate">Select length of loan payback</label>
                            <select name="slcDueDate" id="slcDueDate" data-validate="yes" data-type="select">
                                <option value="">Select length</option>
                                <option value="1">1 year</option>
                                <option value="2">2 years</option>
                                <option value="3">3 years</option>
                            </select>
                            <button class="button button-main">Apply for Loan</button>
                        </form>
                    </div>
                </div>
            </div>
            <h2 class="grid-span-full">Loans to be approved</h2>
            <div id="loansDiv" class="div-show-data"></div>
            <template id="loanTemplate">
                <div class="box item drop-shadow-main">
                    <div class="box-header">
                        <div class="box-header-icon circle"></div>
                        <div class="box-header-text">
                            <p>Amount</p>
                            <h3 id="loanAmount" class="money">20.000</h3>
                        </div>
                    </div>
                    <hr>
                    <div class="box-content">
                        <p>date of inquiry</p>
                        <h3 id="loanDate">01/02/19</h3>
                        <p>Account</p>
                        <h4 id="loanAccount">Account no.</h4>
                        <p>Due</p>
                        <h4 id="loanDueDate">03/22</h4>
                    </div>
                </div>
            </template>
        </div>
    </section>

    <section id="cards" class="page hidden">
        <div class="container">
            <h1>Credit cards</h1>
            <div class="cards-content">
                <div id="cardsDiv" class="main sub-grid"></div>
                <div class="box scroll item aside drop-shadow-main">
                    <div class="box-header">
                        <div class="box-header-icon circle"></div>
                        <div class="box-header-text">
                            <h2>Apply for new credit card</h2>
                        </div>
                    </div>
                    <hr>
                    <div class="box-content">
                        <form id="frmApplyForCreditcard">
                            <label for="slcCardType">Select type of the card</label>
                            <select name="slcCardType" id="slcCardType" data-validate="yes" data-type="select">
                                <option value="">Select card make</option>
                                <option value="VISA">VISA</option>
                                <option value="VISA DANKORT">VISA Dankort</option>
                                <option value="VISA ELECTRON">VISA Electron</option>
                                <option value="MASTERCARD">MasterCard</option>
                            </select>
                            <label for="slcCardAccount">Select Card account</label>
                            <select name="slcCardAccount" id="slcCardAccount" data-validate="yes" data-type="select">
                                <option value="">Select Account</option>
                                <?php 
                                    foreach($jAccounts as $sAccountId => $jAccount) {
                                        if(!$jAccount->card || $jAccount->card == null) {
                                            echo '<option value="'.$sAccountId.'">'.$sAccountId.'</option>';
                                        }
                                    }
                                ?>
                            </select>
                            <button class="button button-main">Apply for Credit card</button>
                        </form>
                    </div>
                </div>
            </div>
            <div>
            </div>
            <template id="cardTemplate">
                <div class="box item drop-shadow-main">
                    <div class="box-header">
                        <div id="cardType">VISA</div>
                    </div>
                    <div class="box-content">
                        <h3 id="cardNumber">1111-2222-3333-4444</h3>
                        <p id="cardCVV">123</p>
                        <div id="cardAccount">Account no.</div>
                        <button class="button button-main cancelCard">Cancel credit card</button>
                    </div>
                </div>
            </template>
        </div>
    </section>

    <section id="accounts" class="page hidden">
        <div class="container">
            <h1>Accounts</h1>
            <div id="accountsDiv" class="div-show-data"></div>
            <div>
                <h1>Create new account</h1>
            </div>
            <div class="box scroll item drop-shadow-main">
                    <div class="box-header">
                        <div class="box-header-icon circle"></div>
                        <div class="box-header-text">
                            <h2>Create new account</h2>
                        </div>
                    </div>
                    <hr>
                    <div class="box-content">
                        <form id="frmCreateNewAccount">
                            <label for="slcAccountType">Select type of the Account</label>
                            <select name="slcAccountType" id="slcAccountType" data-validate="yes" data-type="select">
                                <option value="">Select Account</option>
                                <option value="main">Main account</option>
                                <option value="loan">Loan account</option>
                                <option value="saving">Saving account</option>
                            </select>
                            <label for="slcCurrency">Select main currency</label>
                            <select name="slcCurrency" id="slcCurrency" data-validate="yes" data-type="select">
                                <option value="">Select Currency</option>
                                <option value="DKK">DKK</option>
                                <option value="USD">USD</option>
                            </select>
                            <button class="button button-main">Create new account</button>
                        </form>
                    </div>
                </div>
            <template id="accountTemplate">
                <div class="box item drop-shadow-main">
                    <div class="box-header">
                        <div class="box-header-text">
                            <p>Balance</p>
                            <h3 id="accountBalance"><span id="accountCurrency">Currency</span> Balance</h3>
                        </div>
                    </div>
                    <hr>
                    <div class="box-content">
                        <p>Type</p>
                        <h3 id="accountType">Type</h3>
                        <p>ID</p>
                        <h4 id="accountID">ID</h4>
                        <p>Card</p>
                        <h4 id="accountCard"></h4>
                    </div>
                </div>
            </template>
        </div>
    </section>

    <section id="options" class="page hidden">
        <div class="container">
            <h1>Password Change</h1>
            <form id="frmChangePassword">
                <label for="txtOldPassword">Enter Old password</label>
                <input type="password" id="txtOldPassword" name="txtOldPassword" placeholder="password"
                data-validate="yes" data-type="string" data-min="6" data-max="20">
                <label for="txtNewPassword">Enter new password</label>
                <input type="password" id="txtNewPassword" name="txtNewPassword" placeholder="password"
                data-validate="yes" data-type="string" data-min="6" data-max="20">
                <label for="txtNewPassConfirm">Conform new password</label>
                <input type="password" id="txtNewPassConfirm" name="txtNewPassConfirm" placeholder="password"
                data-validate="yes" data-type="string" data-min="6" data-max="20">
                <button class="button button-main">Change password</button>
            </form>
        </div>
    </section>
<?php 
$sLinkToScriptMain = '<script src="js/main.js"></script>';
$sLinkToScript = '<script src="js/profile.js"></script>';
require_once 'bottom.php'; 
?>