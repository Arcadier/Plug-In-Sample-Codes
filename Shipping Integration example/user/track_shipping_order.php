<?php
include 'callAPI.php';
include 'admin_token.php';

$contentBodyJson = file_get_contents('php://input');
$content = json_decode($contentBodyJson, true);

$baseUrl = getMarketplaceBaseUrl();
$admin_token = getAdminToken();
$userId = $content['user_id'];
$invoiceNo = $content['invoice_no'];
$url = $baseUrl . '/api/v2/users/' . $userId . '/transactions/' . $invoiceNo;
$result = callAPI("GET", $admin_token['access_token'], $url, $data);
if ($result == null || array_key_exists('Error', $result)) {
    echo "false";
} else {
    foreach ($result['Orders'] as $order) {
        $orderId = $order['ID'];
        $merchantDetails = $order['MerchantDetail'];
        $customFields = $order['CustomFields'];
        $packageCustomFields = $content['package_custom_fields'];

        $customFieldPrefix = getCustomFieldPrefix();
        $trackingCustomFields = '';
        $courierClientSecretCustomField = '';
        $courierStatusCustomFields = '';
        foreach ($packageCustomFields as $cf) {
            if ($cf['Name'] == 'Tracking Info' && substr($cf['Code'], 0, strlen($customFieldPrefix)) === $customFieldPrefix) {
                $trackingCustomFields = $cf['Code'];
            }
            if ($cf['Name'] == 'Courier Status' && substr($cf['Code'], 0, strlen($customFieldPrefix)) === $customFieldPrefix) {
                $courierStatusCustomFields = $cf['Code'];
            }
            if ($cf['Name'] == 'Courier Client Secret' && substr($cf['Code'], 0, strlen($customFieldPrefix)) == $customFieldPrefix) {
                $courierClientSecretCustomField = $cf['Code'];
            }
        }
        // Get marketplace info
        $url = $baseUrl . '/api/v2/marketplaces/';
        $marketplaceInfo = callAPI("GET", $admin_token['access_token'], $url, false);
        $courierAccessToken = '';
        foreach ($marketplaceInfo['CustomFields'] as $cf) {
            if ($cf['Code'] == $courierClientSecretCustomField) {
                $courierAccessToken = $cf['Values'][0];
            }
        }
        if ($courierAccessToken != null && $courierAccessToken != '') {
            $lastestShippingStatus = 'Delivered';
            $courierShippingStatus = '';
            foreach ($customFields as $cf) {
                if ($cf['Code'] == $trackingCustomFields) {
                    $trackingInfo = $cf['Values'][0];
                    ///////////////////////////////////////////////////////////
                    // START SECTION TO GET COURIER STATUS FROM TRACKING CODE//
                    ///////////////////////////////////////////////////////////

                    // TODO: create url to query the status from the tracking info we have stored in the custom fields
                    // Store the status into $lastestShippingStatus
                    // Check ... to have the list of accepted statuses

                    $courierShippingStatus = 'courier status';
                    $courierUrl = 'https://goodship-staging.honestbee.com/orders/search?tracking_id=' . $trackingInfo;
                    $result = callAPI("GET", $courierAccessToken, $courierUrl, false);
                    if (array_key_exists('packages', $result[0]) && array_key_exists('current_status', $result[0]['packages'][0])) {
                        $courierShippingStatus = $result[0]['packages'][0]['current_status'];
                    }
                    ///////////////////////////////////////////////////////////
                    // END SECTION TO GET COURIER STATUS FROM TRACKING CODE//
                    ///////////////////////////////////////////////////////////
                }
            }

            $data = [
                'ID' => $orderId,
                'FulfilmentStatus' => $lastestShippingStatus,
                'CustomFields' => [
                    [
                        'Code' => $courierStatusCustomFields,
                        'Values' => $courierShippingStatus,
                    ],
                ],
            ];

            $userId = $merchantDetails['ID'];
            $url = $baseUrl . '/api/v2/merchants/' . $userId . '/orders/' . $orderId;
            $r = callAPI("POST", $admin_token['access_token'], $url, $data);
        }
    }
}

?>

