(function() {
    var scriptSrc = document.currentScript.src;
    var urlss = window.location.href.toLowerCase();
    var packagePath = scriptSrc.replace('/scripts/package.js', '').trim();
    var indexPath = scriptSrc.replace('/scripts/package.js', '/index.php').trim();
    var pagelist = scriptSrc.replace('/scripts/package.js', '/blog_list.php').trim();
    console.log(pagelist);
    var token = commonModule.getCookie('webapitoken');
    var re = /([a-f0-9]{8}(?:-[a-f0-9]{4}){3}-[a-f0-9]{12})/i;
    var packageId = re.exec(scriptSrc.toLowerCase())[1];
    var marketplace = scriptSrc.replace('/admin/plugins/'+ packageId +'/scripts/package.js', '/blog/').trim(); //package dir should be dynamic
    console.log(marketplace);
    var customFieldPrefix = packageId.replace(/-/g, "");
    console.log(customFieldPrefix);
    var userId = $('#userGuid').val();
    var isAvailable;
    var isVisible;
    var content;
    var data1;
    var data2;
    var plain_text;
    var dom;
    var html;
    console.log(userId);
    var timezone_offset_minutes = new Date().getTimezoneOffset();
    timezone_offset_minutes = timezone_offset_minutes == 0 ? 0 : -timezone_offset_minutes;
    console.log(timezone_offset_minutes*60);
    var pagedids;
    var path =   $('#path').val();
    console.log(path);

    function setTimezoneOffset(){
        var data = {'timezone':  timezone_offset_minutes };
         var apiUrl = packagePath + '/load_blogs.php';
        $.ajax({
            url: apiUrl,          
            headers: {
                'Authorization': 'Bearer ' + token,
            },
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(data),
            success: function(result) {
               // toastr.success('Page Contents successfully saved.');
            },
            error: function (jqXHR, status, err) {
            }
        });
    }
    function setTimezoneOffsetindex(){
        var data = {'timezone':  timezone_offset_minutes };
         var apiUrl = packagePath + '/blog_list.php';
        $.ajax({
            url: apiUrl,          
            headers: {
                'Authorization': 'Bearer ' + token,
            },
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(data),
            success: function(result) {
               // toastr.success('Page Contents successfully saved.');
            //    window.location.href = indexPath;
            },
            error: function (jqXHR, status, err) {
            }
        });
    }

    function savePageContent() {
        data1 = CKEDITOR.instances.editor1.getData();
        data2 = CKEDITOR.instances.metadescs.getData();
        html=CKEDITOR.instances.metadescs.getSnapshot();
        dom=document.createElement("DIV");
        dom.innerHTML=html;
        var plain_text=(dom.textContent || dom.innerText);
        console.log(plain_text);
        var data = { 'userId': userId, 'title': $('#title').val(), 'content': data1, 'blogURL': marketplace + $('#metaurl').val(), 'metadesc' : plain_text, 'imgUrl': $('#imgUrl').val(), 'visibility': isVisible, 'availability': isAvailable,'timezone':  timezone_offset_minutes , 'blogURLshort' : '/blog/' +  $('#metaurl').val() };
        console.log(data);
        // console.log($('#editor').val());
        console.log($('#metaurl').val());
        console.log($('#metadesc').val());
         var apiUrl = packagePath + '/save_new_content.php';
        $.ajax({
            url: apiUrl,          
            headers: {
                'Authorization': 'Bearer ' + token,
            },
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(data),
            success: function(result) {
                toastr.success('Page Contents successfully saved.');
                window.location.href = pagelist;
            },
            error: function (jqXHR, status, err) {
            }
        });
    }

    function saveModifiedPageContent() {
        data1 = CKEDITOR.instances.editor1.getData();
        // data2 = CKEDITOR.instances.metadescs.getData();
        // html=CKEDITOR.instances.metadescs.getSnapshot();
        // dom=document.createElement("DIV");
        // dom.innerHTML=html;
        // var plain_text=(dom.textContent || dom.innerText);
        var data = { 'pageId' : $('#pageid').val(),'userId': userId, 'title': $('#title').val(), 'content': data1, 'blogURL': marketplace +  $('#metaurl').val(), 'metadesc': $('#metadesc').val(), 'imgUrl': $('#imgUrl').val(), 'visibility': isVisible, 'availability': isAvailable,'timezone':  timezone_offset_minutes, 'blogURLshort' :  '/blog/' + $('#metaurl').val() };
        var apiUrl = packagePath + '/save_modified_content.php';
        $.ajax({
            url: apiUrl,          
            headers: {
                'Authorization': 'Bearer ' + token,
            },
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(data),
            success: function(result) {
                toastr.success('Blog Contents successfully updated.');
                window.location.href = pagelist;
            },
            error: function (jqXHR, status, err) {
            }
        });
    }

    function deletePage() {
        var data = { 'pageId' : pagedids,'userId': userId};
        console.log(pagedids);
       // console.log($('#pageid').val())
         var apiUrl = packagePath + '/delete_content.php';
         console.log(path);
        $.ajax({
            url: apiUrl,          
            headers: {
                'Authorization': 'Bearer ' + token,
            },
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(data),
            success: function(result) {
                toastr.success('Page Content successfully deleted.');
                location.reload(); 
            },
            error: function (jqXHR, status, err) {
            }
        });
    }
 
    function getMarketplaceCustomFields(callback) {
        var apiUrl = '/api/v2/marketplaces'
        $.ajax({
            url: apiUrl,
            method: 'GET',
            contentType: 'application/json',
            success: function(result) {
                if (result) {
                    callback(result.CustomFields);
                }
            }
        });
    }
    $(document).ready(function() {

        if (urlss.indexOf('/create_blog.php') >= 0) {
        if(isVisible == null || isAvailable == null){
        $('input:radio[name="opt_del"]').filter('[value="0"]').attr('checked', true);
        isAvailable = $("input[name='opt_del']:checked").val();
        $('input:radio[name="visible-to"]').filter('[value="0"]').attr('checked', true);
        isVisible = $("input[name='visible-to']:checked").val();
        }
        // setInterval(countmetadesc , 500);
     }


        var pathname = (window.location.pathname + window.location.search).toLowerCase();
      //  const url = window.location.href.toLowerCase();
        const index1 = '/admin/plugins/' + packageId;
        const index2 = '/admin/plugins/' + packageId + '/';
        const index3 = '/admin/plugins/' + packageId + '/index.php';
        if ( pathname == index1 ||  pathname == index2 ||  pathname == index3) {
            setTimezoneOffsetindex();
            window.location = pagelist;
        }
        // const url = window.location.href.toLowerCase();
        // if(url.indexOf('/admin/plugins/debfc8b9-385b-e911-80ed-000d3aa14e08/index.php') >= 0) {
        //     setTimezoneOffsetindex();
        //     window.location.href = pagelist;
        // }
     
        //set the timezone offset for date time conversion in front end
       // setTimezoneOffset();
        
        //set the marketplace URL
        $('#marketplaceURL').text(marketplace);
        //check availability
            $('input:radio[name="opt_del"]').change(function() {
                isAvailable = $("input[name='opt_del']:checked").val();
                console.log(isAvailable);
                if(isAvailable){
                }
            });

         // check the visibilty
         /*$('input:radio[name="visible-to"]').change(function() {
            isVisible = $("input[name='visible-to']:checked").val();
            console.log(isVisible);
            if(isVisible){
            }
         });*/
       //save the page contents
          $('#save').click(function() {
            $('#save').off('click');
             if($("#title").val() == ""   ){ 
                 console.log('true');
                 toastr.error('Please fill in empty fields.');
          
             }else if(isVisible == null || isAvailable == null) {
                toastr.error('Please choose visibility or availability.');
             }else if($("#metaurl").val() == "") {
                toastr.error('URL is required.');
             }else {
                savePageContent();
             }
         });
        //save modified page contents
          $('#edit').click(function() {
            saveModifiedPageContent();
            
          });
        
          //delete the page contents
          $('#popup_btnconfirm').click(function() {
            console.log('funct touched,');
            pagedids = $('.record_id').val();
            deletePage();
            //
          });

          //cancel button
          $('#popup_btnconfirm_cancel').click(function() {
            window.location.href = pagelist;
          });
         //minimize button
         if($('.pgcrtseo-aplyllink').text() == 'Save') {
            $('#saveNew').click(function() {  
                if ($("#metatitle").val() !="") {
                //data1 = CKEDITOR.instances.editor1.getData();
                data2 = CKEDITOR.instances.metadescs.getData();
                html=CKEDITOR.instances.metadescs.getSnapshot();
                dom=document.createElement("DIV");
                dom.innerHTML=html;
                var plain_text=(dom.textContent || dom.innerText);
                console.log(plain_text);
            $('.pgcrt-meta-seosec').removeClass('hide');
            $('.pgcrt-meta-seoeditsec').addClass('hide');
            //handle the values of seo details here
            $("#seotitle").text($('#metatitle').val());  
            $('#seolink').text(marketplace +  $('#metaurl').val());
            $('#seodesc').text(plain_text);
            }
            else {
                toastr.error('Meta Title is required.');
            }
          });
          }

        // for updating contents

        if($('.pgcrtseo-aplyllink').text() == 'Save') {
            $('#editContent').click(function() {  
                //data1 = CKEDITOR.instances.editor1.getData();
                //data2 = CKEDITOR.instances.metadescs.getData();
                // html=CKEDITOR.instances.metadescs.getSnapshot();
                // dom=document.createElement("DIV");
                // dom.innerHTML=html;
                // var plain_text=(dom.textContent || dom.innerText);
                // console.log(plain_text);
            $('.pgcrt-meta-seosec').removeClass('hide');
            $('.pgcrt-meta-seoeditsec').addClass('hide');
            //handle the values of seo details here
            $("#seotitle").text($('#metatitle').val());  
            $('#seolink').text(marketplace +  $('#metaurl').val());
            $('#seodesc').text($('#metadesc').val());
          });
          }

    });
})();