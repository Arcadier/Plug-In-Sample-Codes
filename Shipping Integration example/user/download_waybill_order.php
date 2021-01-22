<?php
include 'callAPI.php';
include 'admin_token.php';

function download($file_source, $file_target) {
    $rh = fopen($file_source, 'rb');
    $wh = fopen($file_target, 'w+b');
    if (!$rh || !$wh) {
        return false;
    }
    while (!feof($rh)) {
        if (fwrite($wh, fread($rh, 4096)) === FALSE) {
            return false;
        }
        echo ' ';
        flush();
    }

    fclose($rh);
    fclose($wh);

    return true;
}

$contentBodyJson = file_get_contents('php://input');
$content = json_decode($contentBodyJson, true);

$customFieldPrefix = getCustomFieldPrefix();
$baseUrl = getMarketplaceBaseUrl();
$admin_token = getAdminToken();

$userId = $content['user_id'];
$orderId = $content['orderId'];

$url = $baseUrl . '/api/v2/users/' . $userId . '/orders/' . $orderId;
$order = callAPI("GET", $admin_token['access_token'], $url, false);
error_log($r, 0);
if ($order == null || array_key_exists('Error', $order)) {
    echo "false";
} else {
    $orderId = $order['ID'];
    $merchantDetails = $order['MerchantDetail'];
    $customFields = $order['CustomFields'];
    $packageCustomFields = $content['package_custom_fields'];
    $trackingCustomField = '';
    $courierClientSecretCustomField = '';
    foreach ($packageCustomFields as $cf) {
        error_log(json_encode($cf), 0);
        if ($cf['Name'] == 'Tracking Info' && substr($cf['Code'], 0, strlen($customFieldPrefix)) === $customFieldPrefix) {
            $trackingCustomField = $cf['Code'];
        }
        if ($cf['Name'] == 'Courier Client Secret' && substr($cf['Code'], 0, strlen($customFieldPrefix)) == $customFieldPrefix) {
            $courierClientSecretCustomField = $cf['Code'];
        }
    }
    $trackingInfo = '';
    foreach ($customFields as $cf) {
        if ($cf['Code'] == $trackingCustomField) {
            $trackingInfo = $cf['Values'][0];
            error_log($trackingInfo, 0);
        }
    }
    $url = $baseUrl . '/api/v2/marketplaces/';
    $marketplaceInfo = callAPI("GET", $admin_token['access_token'], $url, false);
    $courierAccessToken = '';
    foreach ($marketplaceInfo['CustomFields'] as $cf) {
        if ($cf['Code'] == $courierClientSecretCustomField) {
            $courierAccessToken = $cf['Values'][0];
        }
    }
    if ($courierAccessToken != null && $courierAccessToken != '') {
        ////////////////////////////////////////////////////////////
        // START SECTION TO GET COURIER WAYBILL FROM TRACKING CODE//
        ////////////////////////////////////////////////////////////
        if (file_exists('downloads/waybill/' . $trackingInfo . '.pdf') == false) {
            // TODO: download the waybill from those information
            $courierUrl = 'https://goodship-staging.honestbee.com/waybill?tracking_ids=' . $trackingInfo . '&access_token=' . $courierAccessToken;
            //header('Content-type: application/pdf');
            //header('Content-Disposition: attachment; filename="waybill.pdf"');
            //echo readfile($courierUrl);
            download($courierUrl, 'downloads/waybill/' . $trackingInfo . '.pdf');

            ///////////////////////////////////////////////////////////
            // END SECTION TO GET COURIER WAYBILL FROM TRACKING CODE///
            ///////////////////////////////////////////////////////////
        }
        echo json_encode(['url' => 'downloads?file=' . $trackingInfo . '.pdf&contentType=application/pdf']);
    }
}

?>

