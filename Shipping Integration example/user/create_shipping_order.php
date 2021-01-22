<?php
include 'callAPI.php';
include 'admin_token.php';

$contentBodyJson = file_get_contents('php://input');
$content = json_decode($contentBodyJson, true);

$baseUrl = getMarketplaceBaseUrl();
$admin_token = getAdminToken();

$packageCustomFields = $content['package_custom_fields'];
$trackingCustomFields = '';
$customFieldPrefix = getCustomFieldPrefix();
$courierClientSecretCustomField = '';
foreach ($packageCustomFields as $cf) {
    if ($cf['Name'] == 'Tracking Info' && substr($cf['Code'], 0, strlen($customFieldPrefix)) === $customFieldPrefix) {
        $trackingCustomFields = $cf['Code'];
    }
    if ($cf['Name'] == 'Courier Client Secret' && substr($cf['Code'], 0, strlen($customFieldPrefix)) == $customFieldPrefix) {
        $courierClientSecretCustomField = $cf['Code'];
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
    foreach ($content['orders'] as $order) {
        $orderId = $order['ID'];
        $consumerDetails = $order['ConsumerDetail'];
        $shipTo = $order['DeliveryToAddress'];
        $merchantDetails = $order['MerchantDetail'];
        $shipFrom = $order['DeliveryFromAddress'];
        $customFields = array_key_exists('CustomFields', $order) ? $order['CustomFields'] : null;
        $submitted = false;
        if ($customFields != null) {
            foreach ($customFields as $cf) {
                if ($cf['Code'] == $trackingCustomFields) {
                    $trackingInfo = $cf['Values'][0];
                    $trackingInfo = trim($trackingInfo);
                    if ($trackingInfo != null && $trackingInfo != '') {
                        $submitted = true;
                    }
                }
            }
        }
        if ($submitted == false) {
            foreach ($order['CartItemDetails'] as $cartItemDetail) {
                $found = false;
                $width = 10;
                $height = 10;
                $length = 10;
                $weight = 10;
                if ($cartItemDetail['DeliveryType'] == 'delivery'
                    && array_key_exists('ShippingMethod', $cartItemDetail)
                    && $cartItemDetail['ShippingMethod']['Description'] == 'Honestbee') {
                    $found = true;
                    if (array_key_exists('ItemDetail', $cartItemDetail) && array_key_exists('CustomFields', $cartItemDetail['ItemDetail'])) {
                        $customFields = $cartItemDetail['ItemDetail']['CustomFields'];
                        // TODO: find the width, height, weight from those custom fields
                        // Calculate the total package heigh, width, length, weight if we need to
                    }
                }
            }

            /////////////////////////////////////////////////////
            // START SECTION TO BUILD COURIER DATA REQUIREMENTS//
            /////////////////////////////////////////////////////
            // TODO: call Courier Server to send those information
            // Update $courierResponse to save the tracking information

            // Get marketplace info

            $courierUrl = 'https://goodship-staging.honestbee.com/orders';
            $courierData = [
                "orders" => [
                    [
                        "reference" => '' . $orderId,
                        "service_level_code" => "NDA",
                        "pickup" => [
                            "contact" => [
                                "email" => $merchantDetails['Email'],
                                "first_name" => $merchantDetails['FirstName'],
                                "last_name" => $merchantDetails['LastName'],
                                "contact_number" => $merchantDetails['PhoneNumber'],
                            ],
                            "address" => [
                                "street1" => $shipFrom['Line1'],
                                "city" => array_key_exists('City', $shipFrom) ? $shipFrom['City'] : '',
                                "state" => array_key_exists('State', $shipFrom) ? $shipFrom['State'] : '',
                                "country_code" => $shipFrom['CountryCode'],
                                "postal_code" => array_key_exists('PostCode', $shipFrom) ? $shipFrom['PostCode'] : '',
                            ],
                            "start_date" => "2019-01-15",
                            "end_date" => "2019-01-25",
                        ],
                        "delivery" => [
                            "customer" => [
                                "email" => $consumerDetails['Email'],
                                "first_name" => $consumerDetails['FirstName'],
                                "last_name" => $consumerDetails['LastName'],
                                "contact_number" => $consumerDetails['PhoneNumber'],
                            ],
                            "address" => [
                                "street1" => $shipTo['Line1'],
                                "city" => array_key_exists('City', $shipTo) ? $shipTo['City'] : '',
                                "state" => array_key_exists('State', $shipTo) ? $shipTo['State'] : '',
                                "country_code" => $shipTo['CountryCode'],
                                "postal_code" => array_key_exists('PostCode', $shipTo) ? $shipTo['PostCode'] : '',
                            ],
                        ],
                        "packages" => [
                            [
                                "width" => $width,
                                "height" => $height,
                                "length" => $length,
                                "weight" => $weight,
                                "package_type" => "small",
                                "commodity_type" => "electronics",
                                "commercial_value" => [
                                    "amount" => $order['Total'],
                                    "currency_code" => $order['CurrencyCode'],
                                ],
                            ],
                        ],
                    ],
                ],
            ];
            $result = callAPI("POST", $courierAccessToken, $courierUrl, $courierData);
            $courierResponse = '';
            if ($result != null && array_key_exists('order_requests', $result) && array_key_exists('tracking_id', $result['order_requests'][0])) {
                $courierResponse = $result['order_requests'][0]['tracking_id'];
            }

            ///////////////////////////////////////////////////
            // END SECTION TO BUILD COURIER DATA REQUIREMENTS//
            ///////////////////////////////////////////////////

            $customFieldPrefix = getCustomFieldPrefix();
            $packageCustomFields = $content['package_custom_fields'];
            $trackingCustomFields = '';
            foreach ($packageCustomFields as $cf) {
                if ($cf['Name'] == 'Tracking Info' && substr($cf['Code'], 0, strlen($customFieldPrefix)) == $customFieldPrefix) {
                    $trackingCustomFields = $cf['Code'];
                }
            }
            $data = [
                'ID' => $orderId,
                'CustomFields' => [
                    [
                        'Code' => $trackingCustomFields,
                        'Values' => [$courierResponse],
                    ],
                ],
            ];

            $userId = $merchantDetails['ID'];
            $url = $baseUrl . '/api/v2/merchants/' . $userId . '/orders/' . $orderId;
            $result = callAPI("POST", $admin_token['access_token'], $url, $data);
        }
    }
}

?>

