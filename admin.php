<?php 

// Get data
$sData = file_get_contents('data/clients.json');
$jData = json_decode($sData);

if($jData == null) {sendResponse(-1, __LINE__, 'Cannot process your request. Please try again later');}
$jInnerData = $jData->data;

require_once 'top.php';
?>

<div class="container">
    <div class="admin-page">
        <h1>Admin view</h1>
        <div class="admin-grid">
                <?php
                foreach($jInnerData as $jClientId => $jClient) {
                
                    $sWord = ($jClient->active == 0) ? 'unblock client' : 'block client';
                    // echo 'x';
                    echo "<div class='client box drop-shadow-main'>
                            <div>id:$jClientId</div>
                            <div>name:$jClient->name $jClient->lastName</div>
                            <div>active:$jClient->active</div>
                            <a class='button button-sec' href='apis/api-block-or-unblock-customer.php?id=$jClientId'>$sWord</a>
                            <a class='button button-main' href='show-customer.php?phone=$jClientId'>Show customer</a>
                        </div>";
                }
                ?>
        </div>
    </div>
</div>

<?php
$sLinkToScript = '<script src="js/admin.js"></script>';
require_once 'bottom.php'; 

// Main view - All Clients
// Each client will have button that will block/unblock them
// There will be a list of loans at each of the clients
// There will be an object of client's main account plus input field to give money
?>