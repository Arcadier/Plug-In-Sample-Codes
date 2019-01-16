<?php
include 'callAPI.php';
include 'admin_token.php';

$contentBodyJson = file_get_contents('php://input');
$content = json_decode($contentBodyJson, true);

$baseUrl = getMarketplaceBaseUrl();
$admin_token = getAdminToken();

$packageCustomFields = $content['package_custom_fields'];
$customFieldPrefix = getCustomFieldPrefix();
$shippingIdCustomField = '';
foreach ($packageCustomFields as $cf) {
    if ($cf['Name'] == 'Shipping Method' && substr($cf['Code'], 0, strlen($customFieldPrefix)) == $customFieldPrefix) {
        $shippingIdCustomField = $cf['Code'];
    }
}
// Get marketplace info
$url = $baseUrl . '/api/v2/marketplaces/';
$marketplaceInfo = callAPI("GET", $admin_token['access_token'], $url, false);
$shippingMethodId = '';
foreach ($marketplaceInfo['CustomFields'] as $cf) {
    if ($cf['Code'] == $shippingIdCustomField) {
        $shippingMethodId = $cf['Values'][0];
    }
}

$userId = $content['userId'];
$orderIds = $content['orders'];
$option = $content['option'];

foreach ($orderIds as $orderId) {
    $url = $baseUrl . '/api/v2/users/' . $userId . '/orders/' . $orderId;
    $order = callAPI("GET", $admin_token['access_token'], $url, false);

    if ($order != null) {
        # TODO:
        # Call Courier Server here to calculate the correct fee
        # Save the cost to $freight

        $freight = 10.00;
        if ($option == 1) {
            $freight = 50.00;
        } else if ($option == 2) {
            $freight = 20.00;
        }
        $cartId = '';
        foreach ($order['CartItemDetails'] as $cart) {
            if (array_key_exists('ShippingMethod', $cart)) {
                if ($cart['ShippingMethod']['ID'] == $shippingMethodId) {
                    $cartId = $cart['ID'];
                    break;
                }
            }
        }

        // TODO: temporary fix the freight by calling 2 API, it supposes to 1 API to update
        $merchant_info = $order['MerchantDetail'];
        $userId = $merchant_info['ID'];
        if ($cartId != null && $cartId != '') {
            $data = [
                'ID' => $cartId,
                'Freight' => $freight,
            ];

            $url = $baseUrl . '/api/v2/merchants/' . $userId . '/carts/' . $cartId;
            $result = callAPI("POST", $admin_token['access_token'], $url, $data);

            $data = [
                'ID' => $orderId,
                'Freight' => $freight,
            ];
            $url = $baseUrl . '/api/v2/merchants/' . $userId . '/orders/' . $orderId;
            $result = callAPI("POST", $admin_token['access_token'], $url, $data);
        }
    }
}

?>

