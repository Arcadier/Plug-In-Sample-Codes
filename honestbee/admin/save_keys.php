<?php
include 'callAPI.php';
include 'admin_token.php';

$contentBodyJson = file_get_contents('php://input');
$content = json_decode($contentBodyJson, true);

$clientSecret = $content['clientSecret'];
$userId = $content['userId'];

$baseUrl = getMarketplaceBaseUrl();
$admin_token = getAdminToken();
$customFieldPrefix = getCustomFieldPrefix();

// Query to get marketplace id
$url = $baseUrl . '/api/v2/marketplaces/';
$marketplaceInfo = callAPI("GET", null, $url, false);

// Query to get package custom fields
$url = $baseUrl . '/api/developer-packages/custom-fields?packageId=' . getPackageID();
$packageCustomFields = callAPI("GET", null, $url, false);
$shippingIdCustomField = '';
$shippingIntegerIdCustomField = '';
$courierClientSecretCustomField = '';
foreach ($packageCustomFields as $cf) {
    if ($cf['Name'] == 'Shipping Method' && substr($cf['Code'], 0, strlen($customFieldPrefix)) == $customFieldPrefix) {
        $shippingIdCustomField = $cf['Code'];
    }
    if ($cf['Name'] == 'Shipping Method Int ID' && substr($cf['Code'], 0, strlen($customFieldPrefix)) == $customFieldPrefix) {
        $shippingIntegerIdCustomField = $cf['Code'];
    }
    if ($cf['Name'] == 'Courier Client Secret' && substr($cf['Code'], 0, strlen($customFieldPrefix)) == $customFieldPrefix) {
        $courierClientSecretCustomField = $cf['Code'];
    }
}

$shippingMethodId = '';
$shippingMethodIntegerId = '';
// Add shipping method if there is none from db
foreach ($marketplaceInfo['CustomFields'] as $cf) {
    if ($cf['Code'] == $shippingIdCustomField) {
        $shippingMethodId = $cf['Values'][0];
    }
}

if ($shippingMethodId == null || $shippingMethodId == '') {
    $data = [
        "Description" => "Honestbee",
        "CurrencyCode" => "SGD",
        "Active" => true,
    ];
    
    $url = $baseUrl . '/api/v2/merchants/' . $userId . '/shipping-methods/';
    $r = callAPI("POST", $admin_token['access_token'], $url, $data);
    $shippingMethodId = $r['ID'];
    

    //TODO: this API should be removed from the upcoming version
    //The API itself will be moved to v2 only
    $url = $baseUrl . '/api/merchant/account/shippingmethod';
    $r = callAPI("GET", $admin_token['access_token'], $url);
    foreach ($r as $shippingInfo) {
       
        if ($shippingInfo['Guid'] == $shippingMethodId) {
            $shippingMethodIntegerId = $shippingInfo['ID'];
            break;
        }
    }
}

// Update marketplace info
$data = [
    'ID' => $marketplaceInfo['ID'],
    'CustomFields' => [
        [
            'Code' => $shippingIdCustomField,
            'Values' => [$shippingMethodId],
        ],
        [
            'Code' => $shippingIntegerIdCustomField,
            'Values' => [$shippingMethodIntegerId],
        ],
        [
            'Code' => $courierClientSecretCustomField,
            'Values' => [$clientSecret],
        ],
    ],
];

$url = $baseUrl . '/api/v2/marketplaces/';
$result = callAPI("POST", $admin_token['access_token'], $url, $data);

?>

