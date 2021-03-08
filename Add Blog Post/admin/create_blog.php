<!-- <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="viewport" content="width=device-width">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> -->
<title>Blog Add</title>
<?php 
 //$protocol = strpos(strtolower($_SERVER['SERVER_PROTOCOL']),'https') === FALSE ? 'http' : 'https';
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
 $userpage =  $protocol . '://'.$host1 . '/' .  'user' .'/' . $host2 . '/' . $host3 . '/'. 'show_preview.php';
 error_log($userpage);

 $packageURL=$protocol . '://'.$host1.'/'.$host4 . '/'.$host2.'/'.$host3;
?>
<!-- NicEdit -->
<!-- <script type="text/javascript" src="scripts/package.js"></script> -->
<script type="text/javascript" src="<?php echo $packageURL;?>/scripts/tinymce.min.js"></script>
<script src="https://cdn.ckeditor.com/4.11.4/full/ckeditor.js"></script>

<!-- package css-->
<link href="<?php echo $packageURL;?>/css/styles.css" rel="stylesheet" type="text/css">


    <div class="page-content">
      <div class="gutter-wrapper">
      <input type = "hidden" id="path" value = <?php echo $userpage; ?>>

        <form >
        <div class="panel-box">
          <div class="page-content-top">
            <div> <i  class="icon icon-blog icon-3x"></i> </div>
              <div>
                  <span>Add blog posts to your blog page</span> 
              </div>
              <div class="private-setting-switch">
                 <a href="#" class="btn-black-mdx" id = "showpreview">Preview</a>
                 <span  class="grey-btn btn_delete_act">Cancel</span>
                 <a href="#" class="btn-blue" id="save">Save</a> 
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
onKeyUp="limitText(this.form.pg_title,this.form.countdown,80,'#txtcountdown');" maxlength="80"  id = "title" required/>
                               <div>You have <span id="txtcountdown">80</span><input readonly type="hidden" name="countdown" size="3" value="80"> characters left.</div>
                           </div>
                        </div>  
                        
                        <div class="col-md-12">
                           <div class="form-group ">
                              <label class="">Web URL</label>
                              <div class="pgcrtseo-weburlsec">
                                  <span id = "marketplaceURL"></span>
                                  <input  type="text" name="meta_weburl" id="metaurl" />
                              </div>        
                           </div>
                       </div>

                        <div class="col-md-12">
                          <label class="">Content</label> <br>
                          <!-- <textarea class="form-control"  id="niceEditorTextarea" name="pg_desc"></textarea> -->
                          <textarea class = "ckeditor" name="editor1" id="editor1"></textarea required>
                            <!-- <textarea class="form-control" name="editor" id="editor"></textarea> -->
                            <!-- <input type="button" id="buttonpost" value="Publish Post"  /> -->
                        </div>  
                        <!-- <div id="display-post" style="width:700px;" ></div> -->
                        <div class="clearfix"></div>
                        </div>
                    </div>

                </div>


                <div class="panel-box">

                      <div class="pgcreate-frmarea  pgcrt-meta-seosec">
                            <h4 id = "seotitle">Meta Title of The Seo</h4>
                            <div class="pgcrt-meta-seobtn">
                                <span class="pgcrt-link-cstmseo">Edit</span>
                            </div>
                            <div class="clearfix"></div>
                            <div class="seopg-link" id ="seolink"><?php echo $protocol . '://'.$host1;?>/blog/</div>  
                            <p id = "seodesc">This is the meta description of the seo the people can see when they find the site in the search engine</p>
                      </div>

                      <div class="pgcreate-frmarea  pgcrt-meta-seoeditsec hide">
                            <div class="pgcrt-meta-seoedit">
                               <div class="row">
                                   <div class="col-md-6">
                                       <div class="form-group ">
                                          <label class="">Meta Title <span>(maximum 65 Characters)</span></label>
                                          <input class="form-control" type="text" name="meta_title"  id="metatitle" onKeyDown="limitText(this.form.meta_title,this.form.mtcountdown,65,'#mttxtcountdown');" 
onKeyUp="limitText(this.form.meta_title,this.form.mtcountdown,65,'#mttxtcountdown');" maxlength="65" />
<div>You have <span id="mttxtcountdown">65</span><input readonly type="hidden" name="mtcountdown" size="3" value="65"> characters left.</div>
                                       </div>
                                   </div>
                                   <div class="col-md-6 pgcrtseo-aplybtnsec">
                                        <span class="pgcrtseo-aplyllink" id="saveNew">Save</span>
                                   </div> 

                                   <!-- <div class="col-md-12">
                                       <div class="form-group ">
                                          <label class="">Web URL</label>
                                          <div class="pgcrtseo-weburlsec">
                                              <span id = "marketplaceURL"></span>
                                              <input  type="text" name="meta_weburl" id="metaurl" />
                                          </div>        
                                       </div>
                                   </div> -->

                                   <div class="col-md-12">
                                       <div class="form-group ">
                                          <label class="">Meta Description <span>(maximum 170 Characters)</span></label>
                                          <textarea class="form-control" name="meta_desc" id="metadescs"  onKeyDown="limitText(this.form.meta_desc,this.form.mdcountdown,107,'#mdtxtcountdown');" 
onKeyUp="limitText(this.form.meta_desc,this.form.mdcountdown,170,'#mdtxtcountdown');"  maxlength="170" placeholder = "This is the meta description of the seo the people can see when they find the site in the search engine"></textarea>
<div class="hide">You have <span id="mdtxtcountdown">170</span><input readonly type="hidden" name="mdcountdown" size="3" value="170"> characters left.</div>
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
                          <label class="pgcreate-sbar-title">Cover Image (Insert image URL)</label> <input type="text" name="imgUrl" id="imgUrl" value="" class="form-control">
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
                                    <input type="radio" value="0" name="opt_del" id="pg_avail_pub"  checked="checked" id = "available">
                                    <label for="pg_avail_pub"><span>Publish</span></label>
                                  </div>
                            </div>

                            <div class="pgcreate-sbarcon pgfncyopt">
                                <div class="fancy-radio">
                                    <input type="radio" value="1" name="opt_del" id="pg_avail_hide"  id = "hide">
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
                                    <input type="radio" value="0" name="visible-to" checked="checked" id="visible-to1"  class="">
                                    <label for="visible-to1"><span>All users including anonymous</span></label>
                                  </div>
                            </div>

                            <div class="pgcreate-sbarcon pgfncyopt">
                                <div class="fancy-radio">
                                    <input type="radio" value="1" name="visible-to" id="visible-to2"  class="">
                                    <label for="visible-to2">Merchant and Consumer</label>
                                  </div>
                            </div>
                             <div class="pgcreate-sbarcon pgfncyopt">
                                <div class="fancy-radio">
                                    <input type="radio" value="2" name="visible-to" id="visible-to3"  class="">
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
        var editor =  CKEDITOR.replace( 'editor1', {
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
       });  
var editor2 = CKEDITOR.replace( 'metadescs', {
              toolbar: []
            } );
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

function GetContents()
        {
          // Get the editor instance that we want to interact with.

          var oEditor = CKEDITOR.instances.editor1;
          var content = oEditor.getData();
          var meta = CKEDITOR.instances.metadescs;
          meta.setData(content);
          //document.getElementById( 'contentdiv' ).innerHTML = '<div style="border: 1px solid black">The Content: <br><br>' + content + '</div>';
        }
        editor.on('key', function(ev){ GetContents(); }); 

        //removing the status bar below,
        // CKEDITOR.config.extraPlugins = 'youtube';

        CKEDITOR.config.removePlugins = 'elementspath';
        // CKEDITOR.config.resize_enabled = false;
               
    </script>

<script type="text/javascript">
    jQuery(document).ready(function() {

        //auto check atleast one radio button

        // document.visible-to.checked=true;
        // document.getElementById("#visible-to1").checked=true;
     
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


         jQuery('.pgcrt-link-cstmseo').click(function(){  
              jQuery('.pgcrt-meta-seosec').addClass('hide');
              jQuery('.pgcrt-meta-seoeditsec').removeClass('hide');
         });


         jQuery('.pgcrtseo-canclelink').click(function(){  
              jQuery('.pgcrt-meta-seosec').removeClass('hide');
              jQuery('.pgcrt-meta-seoeditsec').addClass('hide');
         });

        //  jQuery('.pgcrtseo-aplyllink').click(function(){  
        //       jQuery('.pgcrt-meta-seosec').removeClass('hide');
        //       jQuery('.pgcrt-meta-seoeditsec').addClass('hide');
        //       //handle the values of seo details here
        //       $("#seotitle").val($('#metatitle').val());  
        //       $('#seolink').val();
        //  });


         jQuery('.btn_delete_act').click(function(){  
            jQuery('#DeleteCustomMethod').show();
            jQuery('#cover').show();
        });

        jQuery('#popup_btnconfirm').click(function(){  
            jQuery('#DeleteCustomMethod').hide();
            jQuery('#cover').hide();
        });

        jQuery('#popup_btnconfirm_cancel').click(function(){  
           // jQuery('#DeleteCustomMethod').hide();
           // jQuery('#cover').hide();
        });

        jQuery('#popup_btncancel,.close-popup').click(function(){  
            jQuery('#DeleteCustomMethod').hide();
            jQuery('#cover').hide();
        });

        //pre fill the meta title with the page title and replace the spaces with (-)
        $("#title").keyup(function(){
          title = $('#title').val();
          $("#metatitle").val($('#title').val());

          str = title.replace(/\s+/g, '-').toLowerCase();
          $("#metaurl").val(str);
        
        });
        //pre-fill the meta desc with the page description
        $(".cke_editable cke_editable_themed cke_contents_ltr cke_show_borders").keyup(function(){
          var oEditor = CKEDITOR.instances.editor1;
          // document.getElementById('metadesc').value = oEditor.getData();
          $("#metadescs").html(oEditor.getData());
          // $("#metadesc").val(CKEDITOR.instances.editor1.getData());   
        });

        jQuery('#showpreview').click(function(){  
           preview();
        });


    }); //document.ready tag
    
    </script> 
<script type="text/javascript" src="<?php echo $packageURL;?>/scripts/package.js"></script>
<!-- end footer --> 
