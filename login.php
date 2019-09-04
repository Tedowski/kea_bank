<?php require_once 'top.php'; ?>


<div class="index">
    <h1>Login</h1>
    <div class="box drop-shadow-main">
        <form id="frmLogin">
            <input type="text" id="txtLoginPhone" name="txtLoginPhone" placeholder="Phone"
            data-validate="yes" data-type="integer" data-min="8" data-max="8">
            <input type="password" id="txtLoginPassword" name="txtLoginPassword" placeholder="Password"
            data-validate="yes" data-type="string" data-min="3" data-max="30">
            <input type="password" id="txtEmail" name="txtEmail" placeholder="Password"
            data-validate="yes" data-type="email" data-min="3" data-max="30">
            <button class="button button-main drop-shadow-main">Login</button>
            <a href="forgotten-pass">Forgot password?</a>
        </form>
    </div> 
    <a class="button button-main button-a" href="admin">Admin page</a>  
</div>


<?php 
    $sLinkToScript = '<script src="js/login.js"></script>';
    require_once 'bottom.php'; 

    // Password Reset
    // Enter phone number -> Check if number is in the database (Validation)
    // If the number is in the database, send mail to the assigned e-mail
    // The mail will have a link to the reset-password page with phone in URL
    // This page will override password without having to check for old One

?>