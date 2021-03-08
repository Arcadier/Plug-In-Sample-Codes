<?php
include 'load_blogs.php';

$protocol = $_COOKIE["protocol"];
$urlexp =   explode("/", parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)); 

$host = $urlexp[0];
$host1 = $urlexp[1];
$host2 =$urlexp[2];
$host3 = $urlexp[3];
$host4 = $urlexp[4];
$host5 = $urlexp[5];

$userpage =  $protocol . '://'.$host1 . '/' .  'user' .'/' . $host2 . '/' . $host3 . '/'. 'show_preview.php';
$packageURL=$protocol . '://'.$host1.'/'.$host4 . '/'.$host2.'/'.$host3;

$blog_id = $_GET['pageid'];
$pageContent = getContent($blog_id);

$url = $pageContent['ExternalURL']; 
$isVisible = $pageContent['VisibleTo'];
$isAvailable = $pageContent['Available'];
error_log($isAvailable);
// meta details
$meta = $pageContent['Meta'];
$metaencode = json_decode($meta,true);
$metaTitle = $metaencode['title'];
$metaDesc =  $metaencode['desc'];
// $imgUrl =  $metaencode['imgUrl'];
$imgUrl =  $metaencode['imgUrl'];

$sliceURL = strstr($url, 'blog/');
$remSlash = strstr($sliceURL, '/');
$webURL = ltrim($remSlash,"/");

$ln=strlen($pageContent['Title']);
$Titlechar = (int) $ln;

$mln =strlen($metaTitle );
$mTitlechar = (int) $mln;

?>

<title>Blog Edit</title>
<script src="https://cdn.ckeditor.com/4.11.4/full/ckeditor.js"></script>

<!-- NicEdit -->
<script type="text/javascript" src="<?php echo $packageURL;?>/scripts/nicEdit.js"></script>
<script type="text/javascript" src="<?php echo $packageURL;?>/scripts/tinymce.min.js"></script>
<!-- package css-->
<link href="<?php echo $packageURL;?>/css/styles.css" rel="stylesheet" type="text/css">
<style>
.texteditor-container {	
	width:700px;
	height:365px;
}
textarea#editor1 {
	width:500px !important;
	border:1px solid red;
	
}
</style>


    <div class="page-content">
      <div class="gutter-wrapper">

        <form >
          <input type = "hidden" id="path" value = <?php echo $userpage; ?>>
        <div class="panel-box">
          <div class="page-content-top">
            <div> <i  class="icon icon-blog icon-3x"></i> </div>
              <div>
                  <span>Add blog posts to your blog page</span> 
              </div>
              <div class="private-setting-switch">
                <a href="#" class="btn-black-mdx" id = "showpreview">Preview</a>
                 <span  class="grey-btn btn_delete_act">Cancel</span>
                 <a href="#" class="btn-blue" id="edit">Save</a> 
                 <input type="hidden" id="pageid"  value="<?php echo $blog_id; ?>">
                  <!-- <button class="blue-btn" id ="save">Save</button> -->
              </div>
          </div>
        </div>
          <div class="row pgcreate-frmsec">
              <div class="col-md-8 pgcreate-frm-l ">
                <div class="panel-box">
                    <div class="pgcreate-frmarea form-area">
                        <div class="row">
                        <div class="col-md-7">
                           <div class="form-group ">
                              <label class="">Blog Title</label>
                              <input class="form-control" type="text" name="pg_title" onKeyDown="limitText(this.form.pg_title,this.form.countdown,80,'#txtcountdown');" 
onKeyUp="limitText(this.form.pg_title,this.form.countdown,80,'#txtcountdown');" maxlength="80" maxlength="80" value ="<?php echo $pageContent['Title'];?>" id = "title" required/>
                             <div>You have <span id="txtcountdown"><?php echo (80-$Titlechar);?></span><input readonly type="hidden" name="countdown" size="3" value="<?php echo (80-$Titlechar);?>"> characters left.</div>
                           </div>
                        </div>   
                        <div class="col-md-12">
                          <label class="">Content</label> <br>
                          <!-- <textarea class="form-control"  id="niceEditorTextarea" name="pg_desc"></textarea> -->
                           <!-- <textarea class="form-control" name="editor" id="editor" ></textarea> -->
                            <textarea class = "ckeditor" name="editor1" id="editor1"><?php echo $pageContent['Content']; ?> </textarea>
                            <!-- <input type="button" id="buttonpost" value="Publish Post"  /> -->
                        </div>  
                        <div id="display-post" style="width:700px;" ></div>
                        <div class="clearfix"></div>
                        </div>
                    </div>

                </div>

                <div class="panel-box">


                      <div class="pgcreate-frmarea  pgcrt-meta-seosec">
                            <h4 id = "seotitle"><?php echo $metaTitle; ?></h4>
                            <div class="pgcrt-meta-seobtn">
                                <span class="pgcrt-link-cstmseo">Edit</span>
                            </div>
                            <div class="clearfix"></div>
                            <div class="seopg-link" id ="seolink"><?php echo $url; ?></div>  
                            <p id = "seodesc"> <?php echo $metaDesc ?></p>
                      </div>

                      <div class="pgcreate-frmarea  pgcrt-meta-seoeditsec hide">
                            <div class="pgcrt-meta-seoedit">
                               <div class="row">
                                   <div class="col-md-6">
                                       <div class="form-group ">
                                          <label class="">Meta Title  <span>(Maximum characters: 65)</span></label>
                                          <input class="form-control" type="text" name="meta_title"  id="metatitle" onKeyDown="limitText(this.form.meta_title,this.form.mtcountdown,65,'#mttxtcountdown');" 
onKeyUp="limitText(this.form.meta_title,this.form.mtcountdown,65,'#mttxtcountdown');" maxlength="65" value = "<?php echo $metaTitle; ?>"  />
<div>You have <span id="mttxtcountdown"><?php echo (65-$mTitlechar);?></span><input readonly type="hidden" name="mtcountdown" size="3" value="<?php echo (65-$mTitlechar);?>"> characters left.</div>
                                       </div>
                                   </div>
                                   <div class="col-md-6 pgcrtseo-aplybtnsec">
                                        <span class="pgcrtseo-aplyllink" id ="editContent">Save</span>
                                   </div> 

                                   <div class="col-md-12">
                                       <div class="form-group ">
                                          <label class="">Web URL</label>
                                          <div class="pgcrtseo-weburlsec">
                                              <span id = "marketplaceURL"></span>
                                              <input  type="text" name="meta_weburl" id="metaurl"  value= "<?php echo trim($webURL); ?> " />
                                          </div>        
                                       </div>
                                   </div>

                                   <div class="col-md-12">
                                       <div class="form-group ">
                                          <label class="">Meta Description <span> (Maximum characters: 170)</span></label>
                                          <textarea class="form-control" name="meta_desc" maxlength="170" id="metadesc"><?php echo $metaDesc; ?></textarea>
                                       </div>
                                   </div>
                               </div>

                            </div>    
                            <div class="pgcreat-btmbtn-sec">
                  <!-- <span  class="grey-btn btn_delete_act">Cancel</span> -->
                    <!-- <button class="blue-btn" type="submit">Save</button> -->
                    <div class="clearfix"></div>
                </div>
                      </div>
                   
                </div>  

              </div>
              <div class="col-md-4 pgcreate-frm-r">
                <div class="panel-box">
                    <div class="pgcreate-sbar">
                      <div class="pgcreate-sbarcon pgfncyopt">
                        <div class="form-group">
                          <label class="pgcreate-sbar-title">Cover Image (Insert image URL)</label> <input type="text"  name="imgUrl" id="imgUrl" value="<?php echo $imgUrl;?>" class="form-control">
                        </div>
                      </div>
                    </div>
                 </div>
                 <div class="panel-box">
                    <div class="pgcreate-sbar">
                        <div class="pgcreate-sbar-title">Available</div>
                        <div class="pgcreate-sbardesc ">
                            <div class="pgcreate-sbarcon pgfncyopt">
                                <div class="fancy-radio">
                                    <input type="radio" value="0"  <?php echo ($isAvailable == "Publish") ?  "checked" : "" ; ?> name="opt_del" id="pg_avail_pub"  class="" id = "available">
                                    <label for="pg_avail_pub"><span>Publish</span></label>
                                  </div>
                            </div>

                            <div class="pgcreate-sbarcon pgfncyopt">
                                <div class="fancy-radio">
                                    <input type="radio" value="1" <?php echo ($isAvailable == 'Hide') ?  "checked" : "" ; ?> name="opt_del" id="pg_avail_hide"  class="" id = "hide">
                                    <label for="pg_avail_hide">Hide</label>
                                  </div>
                            </div>
                        </div>
                    </div>
                 </div>
                    
                 <div class="panel-box hide">
                    <div class="pgcreate-sbar">
                        <div class="pgcreate-sbar-title">Visible to</div>
                        <div class="pgcreate-sbardesc ">
                            <div class="pgcreate-sbarcon pgfncyopt">
                                <div class="fancy-radio">
                                    <input type="radio" value="0"  <?php echo ($isVisible == "All") ?  "checked" : "" ; ?> name="visible-to" id="visible-to1"  class="">
                                    <label for="visible-to1"><span>All users including anonymous</span></label>
                                  </div>
                            </div>

                            <div class="pgcreate-sbarcon pgfncyopt">
                                <div class="fancy-radio">
                                    <input type="radio" value="1" <?php echo ($isVisible == "MerchantAndConsumer") ?  "checked" : "" ; ?> name="visible-to" id="visible-to2"  class="">
                                    <label for="visible-to2">Merchant and Consumer</label>
                                  </div>
                            </div>
                             <div class="pgcreate-sbarcon pgfncyopt">
                                <div class="fancy-radio">
                                    <input type="radio" value="2" <?php echo ($isVisible == "MerchantOnly") ?  "checked" : "" ; ?> name="visible-to" id="visible-to3"  class="">
                                    <label for="visible-to3">Merchant only</label>
                                  </div>
                            </div>
                            
                        </div>
                    </div>
                 </div>

                
              </div>
              <div class="clearfix"></div>
          </div>
          </form>
      </div>
    </div>
  </div>
  <div class="clearfix"></div>
<!-- </div> -->

<div class="popup  popup-area popup-delete-confirm " id="DeleteCustomMethod">
  <div class="wrapper"> <span class="close-popup"><img src="images/cross-icon.svg"></span>
    <div class="content-area">
      <p>Are you sure you want to cancel this?</p>
    </div>
    <div class="btn-area text-center smaller">
      <input  type="button" value="Cancel" class="btn-black-mdx " id="popup_btncancel">
      <input id="popup_btnconfirm_cancel" type="button" value="Okay" class="my-btn btn-blue">
      <div class="clearfix"></div>
    </div>
  </div>
</div>

<div id="cover"></div>

   <!-- begin footer -->

   <script>
     function limitText(limitField, limitCount, limitNum, countId) {
      if (limitField.value.length > limitNum) {
        var totalChar= limitField.value.substring(0, limitNum);
        $(countId).html(totalChar);
      } else {
        var totalChar= limitNum - limitField.value.length;
        $(countId).html(totalChar);
      }
    }
       //    CKEDITOR.plugins.addExternal('youtube', 'youtube/plugin.js'),
       //    CKEDITOR.plugins.addExternal( 'Youtube', '/C:/Users/natashamaruska/Downloads/youtube', 'plugin.js' );
             CKEDITOR.replace( 'editor1', {
                                
               toolbar: [
            { name: 'basicstyles', items: ['Bold', 'Italic', 'Strike', '-', 'RemoveFormat'] },
            { name: 'paragraph', items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote'] },
            { name: 'clipboard', items: ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo'] },
            { name: 'editing', items: ['Scayt'] },
            { name: 'links', items: ['Link', 'Unlink', 'Anchor'] },
            { name: 'insert', items: ['Image', 'Table', 'HorizontalRule', 'SpecialChar'] },
            { name: 'styles', items: ['Styles', 'Format'] },
            { name: 'tools', items: ['Maximize'] },
            { name: 'document', items: ['Source'] }
        ]

    });     //removing the status bar below,
        // CKEDITOR.config.extraPlugins = 'youtube';

        CKEDITOR.config.removePlugins = 'elementspath';
        CKEDITOR.config.resize_enabled = false;
       // CKEDITOR.inline( 'editor2');

 </script>

<script type="text/javascript">

var packagePath = '<?php echo $userpage;?>';
function preview() {
  var data1 = CKEDITOR.instances.editor1.getData();
  var title =  $('input[name=pg_title]').val();
  var image =  $('input[name=imgUrl]').val();
  
  localStorage.setItem('preview', JSON.stringify({
    'content': data1,
    'title': title,
    'image': image
  }));
  fullurl =  packagePath;
  $('#showpreview').attr("target", target="_blank")
  $("#showpreview").attr("href", fullurl);
}
    jQuery(document).ready(function() {
      
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


                 // nicEditor
        //  bkLib.onDomLoaded(function() {                 
        //          new nicEditor({
        //                 iconsPath : 'images/nicEditorIcons.gif',
        //                 buttonList : ['save','bold','italic','underline','left','center','right','justify','ol','ul','fontSize','fontFormat','indent','outdent','link','unlink','forecolor'],
        //                 maxHeight : 300
                
        //         }).panelInstance('niceEditorTextarea');
        //  });


         jQuery('.pgcrt-link-cstmseo').click(function(){  
              jQuery('.pgcrt-meta-seosec').addClass('hide');
              jQuery('.pgcrt-meta-seoeditsec').removeClass('hide');
         });


         jQuery('.pgcrtseo-canclelink').click(function(){  
              jQuery('.pgcrt-meta-seosec').removeClass('hide');
              jQuery('.pgcrt-meta-seoeditsec').addClass('hide');
         });

         jQuery('.pgcrtseo-aplyllink').click(function(){  
              jQuery('.pgcrt-meta-seosec').removeClass('hide');
              jQuery('.pgcrt-meta-seoeditsec').addClass('hide');
         });


         jQuery('.btn_delete_act').click(function(){  
            jQuery('#DeleteCustomMethod').show();
            jQuery('#cover').show();
        });

        jQuery('#popup_btnconfirm').click(function(){  
            jQuery('#DeleteCustomMethod').hide();
            jQuery('#cover').hide();
        });

        jQuery('#popup_btncancel,.close-popup').click(function(){  
            jQuery('#DeleteCustomMethod').hide();
            jQuery('#cover').hide();
        });

        //pre fill the meta title with the page title
        $("#title").keyup(function(){
          $("#metatitle").val($('#title').val());

          
        
      });
  
  
    //Post the content as usual using content.

     jQuery('#showpreview').click(function(){ 
           preview();
        });

    }); //document.ready tag
    
    </script> 
<!-- <script type="text/javascript" src="http://bootstrap.arcadier.com/adminportal/js/custom-nicescroll.js"></script> -->
<script type="text/javascript" src="<?php echo $packageURL;?>/scripts/package.js"></script>
<!-- end footer --> 
