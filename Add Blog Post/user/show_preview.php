<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<head>
<?php
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
<link href="<?php echo $packageURL;?>/css/styles.css" rel="stylesheet" type="text/css">
</head>
<?php
$title = ''; 
//$_GET['pagetitle'];
$content = '';  
//$_GET['content'];
$imgPath='';
/*echo   "<div class='page-not-found'>";
    echo "<div class='container'>";
      echo   "<h1 class='display-4'>" .  $title . "</h1>";
        echo	"<div class='row'>";
        //    if ($isError == '1'){
        //        echo "<p> " . "<img src='images/404_icon.svg'/>" . "</p>";
        //    }
            echo   "<p class='lead'>"  .  "Sample Description" . "</p>";
           echo "<div class='blog_description'>" .  $content . "</div>"; 
     echo "</div>";
//    echo "<div class='page-not-found-back-btn '>" . "<a href='javascript:void(0)' onclick='window.history.back();'>" . "BACK" . "</a>" . "</div>";
echo  "</div>";    
echo "</div>";*/
 echo   "<div class='blg-detail'>";
     echo "<div class='container'>";
         echo   "<h1 class='display-4'>" . $title. "</h1>";
         echo "<div class='divider'> </div>";
		 echo '<span class="release-date">'.date('F d, Y').'</span>';
             echo	"<div class='row'>";
                echo "<div class='img-blg-jambo'>";
					echo "<img src=".$imgPath."><br/><br/>";
                echo "</div>";
                echo "<div class='content_blg'>";
					echo "<div class='blog_description'>" .  $content . "</div>"; 
                echo "</div>";
          echo "</div>";
        echo "<div class='blg-back-btn text-center '>" . "<a href='javascript:void(0)' onclick='window.history.back();'>" . "BACK" . "</a>" . "</div>";
    echo  "</div>";    
echo "</div>";

?>

<script type="text/javascript">
 //preview
    var content  = JSON.parse( localStorage.getItem('preview'));
    if( typeof content.content != 'undefined' ) {
        $('.blog_description').html( content.content );
    }
    if( typeof content.title != 'undefined' ) {
        $('.display-4').html( content.title );
    }
    if( typeof content.image != 'undefined' && content.image !='') {
        $('.img-blg-jambo img').attr('src',content.image);
    } else {
    	$('.img-blg-jambo').hide();
    }

    //preview

</script>
