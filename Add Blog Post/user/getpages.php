<?php
//1.identify the type of user is logged in through the api.
include 'load_pages.php';
$userRole = getUserId();
error_log(json_encode($userRole));
//2. from the page id, get the visibility and availability values.
$page_id =  $_GET['pageid'];
$pageContent = getContent($page_id);
$url = $pageContent['ExternalURL']; 
$title  = $pageContent['Title'];
$contents = $pageContent['Content'];
$isVisible = $pageContent['VisibleTo'];

$serverdate = $pageContent['CreatedDateTime'];
error_log('server date ' . $serverdate);
//$modifiedDate  = $pageContent['CreatedDateTime'] + $timezone;
error_log('with added tz ' . $modifiedDate);

$fdate =  date('F j, Y', $serverdate);
error_log('fdata ' . $fdate);
//var_dump($fdate);
//echo $fdate;
//====================META=============
$meta = $pageContent['Meta'];
$metaencode = json_decode($meta,true);
$metaTitle = $metaencode['title'];
$metaDesc =  $metaencode['desc']; 
$imgPath = $metaencode['imgUrl'];
//$imgPath = ''; 

//$protocol = strpos(strtolower($_SERVER['SERVER_PROTOCOL']),'https') === FALSE ? 'http' : 'https';
 $protocol = $_COOKIE["protocol"];
$urlexp =   explode("/", parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)); 

$host = $urlexp[0];
$host1 = $urlexp[1];
$host2 =$urlexp[2];
$host3 = $urlexp[3];
$host4 = $urlexp[4];
$host5 = $urlexp[5];

$packageURL=$protocol . '://'.$host1.'/'.$host4 . '/'.$host2.'/'.$host3;

?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $metaTitle; ?></title>

<link href="<?php echo $packageURL;?>/css/styles.css" rel="stylesheet" type="text/css">
<meta name = "description" content = "<?php echo $metaDesc;?>">

<?php

$isAvailable = $pageContent['Available'];
$error404 = 'Error 404';
$errorContent = 'Oops! We couldn’t find the page you’re looking for.'; // if the page is hidden.
$errorRestricted = 'Sorry, you are not authorized to view this page.'; // if the user is restricted to view the page.
$errorDescription = 'Unauthorized Access';
$successDescription = 'Published and user is authorized to view.';
//===============================3. Validate first if the Page content is Published or Hidden
if($isAvailable == 'Hide'){ //if hidden, return the 404 page.
    displayPage($error404, $errorRestricted,$errorDescription,'1',$imgPath);
} else { //otherwise, return the page content depending it's availability on the logged in users.
 //===============================4. Set conditions depending on user roles.=====================================================================
 $isAnonymous = 1; // wher will i use this for?
 $isMerchant = 0;
 $isMerchantandConsumer = 0;
        if(in_array('Merchant',$userRole) || in_array('SubMerchant', $userRole)) { //ki      count($find['Roles']) == 1
            $isMerchant = 1;
            error_log('ismerch ' . $isMerchant);
        }
            //if the user is both merchant and at the same time, a consumer
        elseif (in_array('Merchant',$userRole) || in_array('User', $userRole)){
                $isMerchantandConsumer = 1;
                error_log($isMerchantandConsumer);
        }
        else {
            //set another condition here, 
        }
//================================4. Display and load the page if it is a valid user ======================================================================= 
if($isMerchant == 1 && $isVisible == 'MerchantOnly') {
    displayPage($title, $contents,$successDescription,'0',$imgPath,$fdate);
} elseif($isMerchantandConsumer == 1 && $isVisible == 'MerchantAndConsumer') {
    displayPage($title, $contents,$successDescription,'0',$imgPath,$fdate);
} elseif($isVisible == 'All') {
    displayPage($title, $contents,$successDescription,'0',$imgPath,$fdate);
}else {
    displayPage($error404, $errorRestricted,$errorDescription,'1',$imgPath,$fdate);
}} //else statement cond ?>
<?php
function displayPage($title,$content,$desciption,$isError,$imgPath,$fdate){

  echo   "<div class='blg-detail'>";
     echo "<div class='container'>";
         echo   "<h1 class='display-4'>" . $title. "</h1>";
         echo "<div class='divider'> </div>";
         echo "<span class='release-date'>".$fdate."</span>";
             echo	"<div class='row'>";
                if ($isError == '1'){
                    echo "<p><img src='images/404_icon.svg'/></p>";
                }
                echo "<div class='img-blg-jambo'>";

                if($imgPath!=''){ echo "<img src=".$imgPath.">";}
                echo "</div>";
               // echo   "<p class='lead'>"  .  $desciption . "</p>";
                echo "<div class='content_blg'>";
                echo "<p>" .  $content . "</p>"; 
                echo "</div>";
          echo "</div>";
        echo "<div class='blg-back-btn text-center '>" . "<a href='javascript:void(0)' onclick='window.history.back();'>" . "BACK" . "</a>" . "</div>";
    echo  "</div>";    
echo "</div>";

}
function getUserId(){
    $baseUrl = getMarketplaceBaseUrl();
    $admin_token = getAdminToken();
    $userToken = $_COOKIE["webapitoken"];
    error_log('usertoken ' . $userToken);
    $url = $baseUrl . '/api/v2/users/'; 
    error_log('this is the url ' . $url);
    $result = callAPI("GET", $userToken, $url, false);
    error_log('api result ' . json_encode($result));
    $userRole = $result['Roles'];
    return $userRole;   //hmm, return a response just in case the user is invalid? like it has no apitoken
}
    //reserve function
    function meta($pgKeywords,$pgDesc)
    {?>
        <meta charset="utf-8">
         <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
         <meta name="keywords" content="<?php echo $pgKeywords ?>">
         <meta name="description" content="<?php echo $pgDesc ?>"><?php
     }?>