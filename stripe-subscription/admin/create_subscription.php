<?php
require '../license/license.php';
require 'callAPI.php';
require 'admin_token.php';

$contentBodyJson = file_get_contents('php://input');

$baseUrl = getMarketplaceBaseUrl();
$packageId = getPackageID();

parse_str(file_get_contents("php://input"), $content);
error_log(json_encode($_POST));
error_log(json_encode($content));

try
{
    $licence = new License();
    $licence->activate($content['stripeEmail'], $content['stripeToken']);
    $location = $baseUrl . '/admin/plugins/' . $packageId . '/index.php';
    header('Location: ' . $location);
    exit;
} catch (Exception $e) {
    error_log("unable to sign up customer:" . $content['stripeEmail'] . ", error:" . $e->getMessage());
    $location = $baseUrl . '/admin/plugins/' . $packageId . '/oops.php';
    header('Location: ' . $location);
}

?>