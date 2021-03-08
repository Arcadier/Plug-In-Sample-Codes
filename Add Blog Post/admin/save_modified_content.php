<?php
include 'callAPI.php';
include 'admin_token.php';
$contentBodyJson = file_get_contents('php://input');
$content = json_decode($contentBodyJson, true);

//FOR TIMEZONES - POSTING LAST SYNC DETAILS   02/11/19 
$timezone = $content['timezone'];  // $_GET['timezone_offset_minutes']
error_log($timezone);
// Convert minutes to seconds
$timezone_name = timezone_name_from_abbr("", $timezone*60, false);
date_default_timezone_set($timezone_name);
$blogId = $content['pageId'];
error_log('Blog id ' . $blogId);
$userId = $content['userId'];
$title = trim($content['title']);
$contents = $content['content'];
$urls = trim($content['blogURL']);
$isAvailbleTo = $content['availability'];
$isVisibleTo = $content['visibility'];
$metadesc = $content['metadesc'];
$shortURL = $content['pageURLshort'];
$imgUrl = $content['imgUrl'];
$meta = array('title' => $title , 'desc'=> $metadesc,'imgUrl'=>$imgUrl);
$meta2 = json_encode($meta);

error_log($userId);
error_log('Title ' . $title);
error_log('URL ' . $urls);
error_log('Metadesc ' . $metadesc);
error_log('content ' . $contents);
error_log('available to ' . $isAvailbleTo);
error_log('isvisible to ' . $isVisibleTo);

$baseUrl = getMarketplaceBaseUrl();
$admin_token = getAdminToken();
$customFieldPrefix = getCustomFieldPrefix();

$data = [
    'Title' => $title,
    'Content' => $contents,
    'ExternalURL'=> $urls,
    'ModifiedDateTime' => "",
    'Active' => true,
    'Available' => $isAvailbleTo,
    'VisibleTo' => $isVisibleTo,
    'Meta' => $meta2,     
];
$url = $baseUrl . '/api/v2/content-pages/'.$blogId;
$result = callAPI("PUT", $admin_token['access_token'], $url, $data);
error_log(json_encode($result));
//add for another api edit url
//1.get the ID of the response
 $blogid =  $result['ID'];
 $meta = $result['Meta'];
 $metaencode = json_decode($meta,true);
error_log($metaencode['title']);
error_log($metaencode['desc']);
//2.get the value of the short and long url
$blogURL =  '/' . 'blog/' .$urls;
//3.get the value of long page url
$protocol = strpos(strtolower($_SERVER['SERVER_PROTOCOL']),'https') === FALSE ? 'http' : 'https';
//4. here comes the explosion
$urlexp =   explode("/", parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)); 
$host  = $urlexp[0];
$host1 = $urlexp[1];
$host2 = $urlexp[2];
$host3 = $urlexp[3];
$host4 = $urlexp[4];
$host5 = $urlexp[5]; 
$pathURL =  '/' .  'user' .'/' . $host2 . '/' . $host3 . '/'. 'getpages.php' . '?pageid=' . $blogid;
error_log($blogURL);
// POST THE DATA
$data = [
    'Key' => $shortURL,
    'Value' => $pathURL,
];
$url = $baseUrl . '/api/v2/rewrite-rules';
$result = callAPI("POST", $admin_token['access_token'], $url, $data);
error_log(json_encode($result));

?>