<?php
$sUserId = $_POST['txt-user-id'];
$sMessage = $_POST['txt-message'];
$sUserId = $sUserId == 'a' ? 'b' : 'a';
file_put_contents( "to-$sUserId.txt", $sMessage );