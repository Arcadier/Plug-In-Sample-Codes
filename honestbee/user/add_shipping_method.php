<?php
include 'callAPI.php';
include 'admin_token.php';

$contentBodyJson = file_get_contents('php://input');
$content = json_decode($contentBodyJson, true);

$itemId = $content['itemId'];
$shippingMethodId = $content['shippingMethodId'];

$baseUrl = getMarketplaceBaseUrl();
$admin_token = getAdminToken();
$customFieldPrefix = getCustomFieldPrefix();
$packageId = getPackageID();

$url = $baseUrl . '/api/v2/items/' . $itemId;
$result = callAPI("GET", $admin_token['access_token'], $url, false);
if ($result != null && array_key_exists('ShippingMethods', $result)) {
    $found = false;
    $shippingMethodIds = [];
    foreach ($result['ShippingMethods'] as $shippingMethod) {
        if ($shippingMethod['ID'] == $shippingMethodId) {
            $found = true;
        } else {
            array_push($shippingMethodIds, ['ID' => '' . $shippingMethod['ID']]);
        }
    }
    if ($found == false) {
        // Query to get marketplace id
        $url = $baseUrl . '/api/v2/marketplaces/';
        $marketplaceInfo = callAPI("GET", $admin_token['access_token'], $url, false);

        // Query to get package custom fields
        $url = $baseUrl . '/api/developer-packages/custom-fields?packageId=' . $packageId;
        $packageCustomFields = callAPI("GET", null, $url, false);
        $shippingIdCustomField = '';
        foreach ($packageCustomFields as $cf) {
            if ($cf['Name'] == 'Shipping Method' && substr($cf['Code'], 0, strlen($customFieldPrefix)) == $customFieldPrefix) {
                $shippingIdCustomField = $cf['Code'];
            }
        }

        foreach ($marketplaceInfo['CustomFields'] as $cf) {
            if ($cf['Code'] == $shippingIdCustomField) {
                if ($shippingMethodId == $cf['Values'][0]) {
                    $url = $baseUrl . '/api/v2/merchants/' . $result['MerchantDetail']['ID'] . '/items/' . $itemId;
                    array_push($shippingMethodIds, ['ID' => $shippingMethodId]);
                    $data = [
                        'ID' => $result['ID'],
                        "ShippingMethods" => $shippingMethodIds,
                    ];

                    $result = callAPI("PUT", $admin_token['access_token'], $url, $data);
                    $return = '';
                    if ($result == null || array_key_exists('Error', $result)) {
                        $return = "false";
                    } else {
                        $return = "true";
                    }
                    echo json_encode(['result' => $return]);
                    return;
                }
            }
        }
    } else {
        echo json_encode(['result' => 'false']);
    }
}

?>

