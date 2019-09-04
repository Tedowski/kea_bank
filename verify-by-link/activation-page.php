<?php

$sPhone = $_GET['phone'];
$sActivationKey = $_GET['activation-key'];

$sUser = file_get_contents($sPhone.'.json');

//echo $sUser;
$jUser = json_decode($sUser);

if( $sActivationKey != $jUser->activationKey) {
    echo 'Cannot Activate';
    exit;
}

$jUser->active = 1;

$sUser = json_encode($jUser);

file_put_contents($sPhone.'.json', $sUser);

echo 'User activated. <a href="login">Click here to login</a>';