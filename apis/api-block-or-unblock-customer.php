<?php

// Get data
$sData = file_get_contents('../data/clients.json');
$jData = json_decode($sData);

if($jData == null) {sendResponse(-1, __LINE__, 'Cannot process your request. Please try again later');}
$jInnerData = $jData->data;

//loop through clients and find match in the id
foreach($jInnerData as $jClientId => $jClient) {
    //flip the active key to zero
    if($jClientId == $_GET['id']) {
        $jClient->active = !$jClient->active;
        $sData = json_encode($jData, JSON_PRETTY_PRINT); //convert back to text

        file_put_contents('../data/clients.json', $sData);
        header('Location: ../admin.php');
    }
}