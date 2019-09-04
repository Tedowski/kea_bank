<?php 

// Disable php errors
//ini_set('user_agent', 'any');
ini_set('display_errors', 0);

// Validation of GET input
if( empty($_GET['phone'] )){
    sendResponse(-1, __LINE__, 'Error! Phone missing - Invalid link');
}

$sPhone = $_GET['phone'];
if(!ctype_digit($sPhone)) {sendResponse(-1, __LINE__, 'Phone must be only digits');}
if( strlen($sPhone) != 8 ) {sendResponse(-1, __LINE__, 'Phone must be 8 digits');}

if( empty($_GET['key'] )){
    sendResponse(-1, __LINE__, 'Error! Activation key missing - Invalid link');
}

$sActivationKey = $_GET['key'];

// ****************************************************************************************************

function sendResponse($iStatus, $iLineNumber, $sMessage) {
    echo '{"status":'.$iStatus.', "code":'.$iLineNumber.',"message":"'.$sMessage.'"}';
    exit;
}

// ****************************************************************************************************

require_once 'top.php'; 
?>

<div>
    <h1>Reset password</h1>
    <form id="frmResetPassword">
        <input readonly type="text" id="txtResetPhone" name="txtResetPhone" value="<?= $sPhone; ?>">
        <input readonly type="text" id="txtActivationKey" name="txtActivationKey" value="<?= $sActivationKey ?>">
        <h3>Type in new password</h3>
        <input type="password" id="txtNewPassword" name="txtNewPassword" placeholder="New password"
        data-validate="yes" data-type="string" data-min="6" data-max="20">
        <input type="password" id="txtNewPassConfirm" name="txtNewPassConfirm" placeholder="Confirm new password"
        data-validate="yes" data-type="string" data-min="6" data-max="20">
        <button>Reset password</button>
    </form>
</div>

<?php 
    $sLinkToScript = '<script src="js/reset-pass.js"></script>';
    require_once 'bottom.php'; 

    // Password Reset
    // Enter phone number -> Check if number is in the database (Validation)
    // If the number is in the database, send mail to the assigned e-mail
    // The mail will have a link to the reset-password page with phone in URL
    // This page will override password without having to check for old One

?>