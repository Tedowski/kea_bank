<?php
$sUserId = $_GET['sUserId'];
$sMessages = file_get_contents("to-$sUserId.txt");
file_put_contents("to-$sUserId.txt", '');
echo $sMessages;