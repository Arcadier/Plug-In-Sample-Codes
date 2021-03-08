<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Blog List</title>
<!-- package css-->

<?php 
  // include 'load_pages.php';
   include 'callAPI.php';
   include 'admin_token.php';
  // $protocol = strpos(strtolower($_SERVER['SERVER_PROTOCOL']),'https') === FALSE ? 'http' : 'https';
  // $protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === 0 ? 'https' : 'http';
   $protocol = $_COOKIE["protocol"];
   
  //here comes the explosion
  $urlexp =   explode("/", parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)); 
  error_log($protocol);
  $host = $urlexp[0];
  error_log('host ' . $host);
  $host1 = $urlexp[1];
  error_log('host1 ' .$host1);
  $host2 =$urlexp[2];
  error_log('host2 ' . $host2);
  $host3 = $urlexp[3];
  error_log('host1 ' . $host3);
  $host4 = $urlexp[4];
  error_log('host1 ' . $host4);
  $host5 = $urlexp[5];
  error_log('host1 ' . $host5);  
  $userpage =  $protocol . '://'.$host1 . '/' .  'user' .'/' . $host2 . '/' . $host3 . '/'. 'getpages.php';
  error_log($userpage);

  $packageURL=$protocol . '://'.$host1.'/'.$host4 . '/'.$host2.'/'.$host3;

  $contentBodyJson = file_get_contents('php://input');
  $content = json_decode($contentBodyJson, true);
  $timezone = $content['timezone'];
  $timezone_name = timezone_name_from_abbr("", $timezone*60, false);
  error_log($timezone_name);
  date_default_timezone_set($timezone_name);
  error_log('timzeone ' . $timezone);
       
     // $pages = getPages($timezone);
      ?>
      <link href="<?php echo $packageURL;?>/css/styles.css" rel="stylesheet" type="text/css">
<div class="clearfix"></div>
</div>
<div class="page-content">
    <div class="gutter-wrapper">
        <div class="panel-box">
            <div class="page-content-top">
                <div> <i class="icon icon-blog icon-3x"></i> </div>
                <div>
                    <p>Add blog posts to your blog page</p>
                </div>
                <div class="private-setting-switch">
                    <a href="create_blog.php" class="blue-btn">Create New Post</a>
                </div>
            </div>
        </div>
        <div class="panel-box">
            <div class=" form-area">
                <div class="blsl-list-tblsec blg_list_area">
                    <table id="no-more-tables">
                        <thead>
                            <tr>
                                <!-- <th class="spacer"></th> -->
                                <th>Blog Post Title</th>
                                <th>Last Updated</th>
                                <th>Available</th>
                                <th>Actions</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            <?php  
                         
                          //$timezone_name = timezone_name_from_abbr("", $timezone*60, false);
                          date_default_timezone_set($timezone_name);
                          error_log('load page ' . $timezone_name);
                          error_log('timzeone ' . $timezone);
                          $baseUrl = getMarketplaceBaseUrl();
                          $admin_token = getAdminToken();
                          $customFieldPrefix = getCustomFieldPrefix();
                          $url = $baseUrl . '/api/v2/content-pages'; 
                          error_log('this is the url ' . $url);
                          $blogUrl=$baseUrl.'/blog';
                          $getPages = callAPI("GET", $admin_token['access_token'], $url, false);
                          foreach($getPages['Records'] as $page) {
                            if (array_key_exists('ExternalURL', $page)) { // do not include About Us,Terms of service etc..since they lack ExternalURL key.
                            
                            //$blogPage=$baseUrl.'/pages/blog';
                               $metaencode = json_decode($page['Meta'],true);
                                $metaImg = $metaencode['imgUrl'];
                                $metaPage = $metaencode['blogPageOnly'];
                            if (strpos($page['ExternalURL'], 'blog')==true && $metaPage!='yes')
                             { 
                               //print_r($page);
                                $pageID = $page['ID'];
                                $title  = $page['Title']; 
                                $visibility = $page['VisibleTo'];
                                //fix the string 
                                if ($visibility == 'MerchantAndConsumer') { $visibility = 'Merchant and Consumer'; }
                                elseif ($visibility == 'MerchantOnly') { $visibility = 'Merchant Only';}
                                elseif ($visibility == 'All') {$visibility = 'All Users';}
                                $availability = $page['Available'];
                                //change Hide to 'Hidden'
                                if ($availability == 'Hide') { $availability = 'Hidden';}
                                $serverdate = $page['CreatedDateTime'];
                                error_log('server date ' . $serverdate);
                                $modifiedDate  = $page['CreatedDateTime'] + $timezone;
                                error_log('with added tz ' . $modifiedDate);

                                $fdate =  date('d/m/Y H:i', $modifiedDate);
                                error_log('fdata ' . $fdate);
                                //var_dump($fdate);
                                 //echo $fdate;
                                //fix the date format 
                                // $date = date('d/m/Y H:i', $modifiedDate); //varies per timezone, set the timezone first upon page creations.
                                 echo "<tr>";
                                 echo  "<td>". $title."</td>";
                                 echo "<td>".$fdate." </td>";
                                 echo "<td>".$availability."</td>";
                                 echo "<td>";
                          ?>
                            <a href="edit_content.php?pageid=<?php echo $pageID; ?>"><i class="icon icon-edit"></i></a>
                            <?php //link the ID of the page content--done ?>
                            <a href="#" class="btn_delete_act" dir="<?php echo $pageID; ?>" id="del"><i class="icon icon-delete"></i></a>&nbsp;
                            <a href="<?php echo $page['ExternalURL']; ?>" target="_blank"><i class="icon icon-view"></i></a>
                            <?php //link the ID of the page content--done ?>
                            </td>
                            </tr>
                            <?php
                        } 
                     }
                      }
                      ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <nav class="text-center" aria-label="Page navigation">
                <ul class="pagination">
                    <li class="previous-page"> <a href="javascript:void(0)" aria-label=Previous><span aria-hidden=true>&laquo;</span></a></li>
                </ul>
            </nav>
        </div>
    </div>
</div>
</div>
</div>
<div class="clearfix"></div>
<!-- </div> -->
<div class="popup  popup-area popup-delete-confirm " id="DeleteCustomMethod">
    <input type="hidden" class="record_id" value="">
    <div class="wrapper"> <a href="javascript:;" class="close-popup"><img src="images/cross-icon.svg"></a>
        <div class="content-area">
            <p>Are you sure you want to delete this?</p>
        </div>
        <div class="btn-area text-center smaller">
            <input type="button" value="Cancel" class="btn-black-mdx " id="popup_btncancel">
            <input id="popup_btnconfirm" type="button" value="Okay" class="my-btn btn-blue">
            <div class="clearfix"></div>
        </div>
    </div>
</div>
<div id="cover"></div>
<!-- begin footer -->
<script type="text/javascript">
jQuery(document).ready(function() {

    // $('#del').click(function(){

    // });

    // console.log('Userlink2 ' + userLink2);
    jQuery(".mobi-header .navbar-toggle").click(function(e) {
        e.preventDefault();
        jQuery("body").toggleClass("sidebar-toggled");
    });
    jQuery(".navbar-back").click(function() {
        jQuery(".mobi-header .navbar-toggle").trigger('click');
    });

    /*nice scroll */
    jQuery(".sidebar").niceScroll({ cursorcolor: "#000", cursorwidth: "6px", cursorborderradius: "5px", cursorborder: "1px solid transparent", touchbehavior: true, preventmultitouchscrolling: false, enablekeyboard: true });

    jQuery(".sidebar .section-links li > a").click(function() {
        jQuery(".sidebar .section-links li").removeClass('active');
        jQuery(this).parents('li').addClass('active');
    });


    jQuery('.btn_delete_act').click(function() {
        var page_id = $(this).attr('dir');
        console.log(page_id);
        $('.record_id').val(page_id);

        jQuery('#DeleteCustomMethod').show();
        jQuery('#cover').show();
    });

    jQuery('#popup_btnconfirm').click(function() {

        jQuery('#DeleteCustomMethod').hide();
        jQuery('#cover').hide();
    });

    jQuery('#popup_btncancel,.close-popup').click(function() {
        jQuery('#DeleteCustomMethod').hide();
        jQuery('#cover').hide();
    });
});
</script>
<script type="text/javascript" src="scripts/package.js"></script>
<script>
var numRows = $("#no-more-tables tbody tr").length;
//  alert(numRows);
var limitperpage = 20;
$("#no-more-tables tbody tr:gt(" + (limitperpage - 1) + ")").hide();
var totalpages = Math.ceil(numRows / limitperpage);
//  alert(totalpages);
$(".pagination").append("<li class ='current-page active'><a href='javascript:void(0)'>" + 1 + "</a></li>");

for (var i = 2; i <= totalpages; i++) {
    $(".pagination").append("<li class='current-page'> <a href='javascript:void(0)'>" + i + "</a></li>");
}
$(".pagination").append("<li id='next-page'><a href='javascript:void(0)' aria-label=Next><span aria-hidden=true>&raquo;</span></a></li>");

// Function that displays new items based on page number that was clicked
$(".pagination li.current-page").on("click", function() {
    // Check if page number that was clicked on is the current page that is being displayed
    if ($(this).hasClass('active')) {
        return false; // Return false (i.e., nothing to do, since user clicked on the page number that is already being displayed)
    } else {
        var currentPage = $(this).index(); // Get the current page number
        $(".pagination li").removeClass('active'); // Remove the 'active' class status from the page that is currently being displayed
        $(this).addClass('active'); // Add the 'active' class status to the page that was clicked on
        $("#no-more-tables tbody tr").hide(); // Hide all items in loop, this case, all the list groups
        var grandTotal = limitperpage * currentPage; // Get the total number of items up to the page number that was clicked on

        // Loop through total items, selecting a new set of items based on page number
        for (var i = grandTotal - limitperpage; i < grandTotal; i++) {
            $("#no-more-tables tbody tr:eq(" + i + ")").show(); // Show items from the new page that was selected
        }
    }
});

// Function to navigate to the next page when users click on the next-page id (next page button)
$("#next-page").on("click", function() {
    var currentPage = $(".pagination li.active").index(); // Identify the current active page
    // Check to make sure that navigating to the next page will not exceed the total number of pages
    if (currentPage === totalpages) {
        return false; // Return false (i.e., cannot navigate any further, since it would exceed the maximum number of pages)
    } else {
        currentPage++; // Increment the page by one
        $(".pagination li").removeClass('active'); // Remove the 'active' class status from the current page
        $("#no-more-tables tbody tr").hide(); // Hide all items in the pagination loop
        var grandTotal = limitperpage * currentPage; // Get the total number of items up to the page that was selected

        // Loop through total items, selecting a new set of items based on page number
        for (var i = grandTotal - limitperpage; i < grandTotal; i++) {
            $("#no-more-tables tbody tr:eq(" + i + ")").show(); // Show items from the new page that was selected
        }

        $(".pagination li.current-page:eq(" + (currentPage - 1) + ")").addClass('active'); // Make new page number the 'active' page
    }
});

// Function to navigate to the previous page when users click on the previous-page id (previous page button)
$("#previous-page").on("click", function() {
    var currentPage = $(".pagination li.active").index(); // Identify the current active page
    // Check to make sure that users is not on page 1 and attempting to navigating to a previous page
    if (currentPage === 1) {
        return false; // Return false (i.e., cannot navigate to a previous page because the current page is page 1)
    } else {
        currentPage--; // Decrement page by one
        $(".pagination li").removeClass('active'); // Remove the 'activate' status class from the previous active page number
        $("#no-more-tables tbody tr").hide(); // Hide all items in the pagination loop
        var grandTotal = limitperpage * currentPage; // Get the total number of items up to the page that was selected

        // Loop through total items, selecting a new set of items based on page number
        for (var i = grandTotal - limitperpage; i < grandTotal; i++) {
            $("#no-more-tables tbody tr:eq(" + i + ")").show(); // Show items from the new page that was selected
        }

        $(".pagination li.current-page:eq(" + (currentPage - 1) + ")").addClass('active'); // Make new page number the 'active' page
    }
});
</script>
<!-- <script type="text/javascript" src="http://bootstrap.arcadier.com/adminportal/js/custom-nicescroll.js"></script> -->
<!-- end footer -->