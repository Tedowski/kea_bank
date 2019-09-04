<?php require_once 'top.php'; ?>

<div class="index">
    <h1>Signup</h1>
    <div class="box drop-shadow-main">
        <form id="frmSignup">
            <input type="text" id="txtSignupPhone" name="txtSignupPhone" placeholder="Phone" maxlength="8"
            data-validate="yes" data-type="integer" data-min="8" data-max="8">
            <input type="text" id="txtSignupName" name="txtSignupName" placeholder="Name"
            data-validate="yes" data-type="string" data-min="2" data-max="20">
            <input type="text" id="txtSignupLastName" name="txtSignupLastName" placeholder="Last name"
            data-validate="yes" data-type="string" data-min="2" data-max="20">
            <input type="text" id="txtSignupEmail" name="txtSignupEmail" placeholder="E-mail"
            data-validate="yes" data-type="email" data-min="6" data-max="50">
            <input type="text" id="txtSignupCPR" name="txtSignupCPR" placeholder="CPR"
            data-validate="yes" data-type="integer" data-min="10" data-max="10">
            <input type="password" id="txtSignupPassword" name="txtSignupPassword" placeholder="Password"
            data-validate="yes" data-type="string" data-min="6" data-max="20">
            <input type="password" id="txtSignupPasswordRetyped" name="txtSignupPasswordRetyped" placeholder="Confirm Password"
            data-validate="yes" data-type="string" data-min="6" data-max="20">
            <button class="button button-main drop-shadow-main">Sign up</button>
        </form>
    </div>
    <a class="button button-main button-a" href="admin">Admin page</a>
</div>

<?php 
    $sLinkToScript = '<script src="js/signup.js"></script>';
    require_once 'bottom.php';
?>