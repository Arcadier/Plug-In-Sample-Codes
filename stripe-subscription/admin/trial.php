<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $contentBodyJson = file_get_contents('php://input');
    $content = json_decode($contentBodyJson, true);
    $phpExit = '<?php exit(); ?>';

    // TODO:
    // In this sample code, we use file to contain the expiry date
    // We could use other server to store when the subscription is expired
    if (file_exists('../license/trial-expire.php') == false) {
        $time = time() + 15 * 24 * 60 * 60;
        file_put_contents('../license/trial-expire.php', $phpExit . $time);
        echo $time;
    } else {
        $time = file_get_contents('../license/trial-expire.php');
        $time = str_replace($phpExit, '', $time);
        echo $time;
    }
}
?>