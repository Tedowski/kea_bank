<?php require_once 'top.php'; ?>

<div>
    <h1>Forgotten password</h1>
    <form id="frmForgotPassword">
        <label for="txtResetPhone">Enter your phone</label>
        <input type="text" id="txtResetPhone" name="txtResetPhone" placeholder="Enter Your phone"
        data-validate="yes" data-type="integer" data-min="8" data-max="8">
        <button>Send E-mail</button>
    </form>
</div>

<?php 
    $sLinkToScript = '<script src="js/forgotten-pass.js"></script>';
    require_once 'bottom.php'; 

    // Password Reset
    // Enter phone number -> Check if number is in the database (Validation)
    // If the number is in the database, send mail to the assigned e-mail
    // The mail will have a link to the reset-password page with phone in URL
    // This page will override password without having to check for old One

?>