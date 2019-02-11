<?php
require '../license/license.php';
$licence = new License();
if (!$licence->isValid()) {
    exit;
}

?>

<?php
require 'callAPI.php';
require 'admin_token.php';

$baseUrl = getMarketplaceBaseUrl();
$packageId = getPackageID();
echo 'it is working';
?>