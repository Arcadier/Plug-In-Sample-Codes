(function() {
    var scriptSrc = document.currentScript.src;
    var packagePath = scriptSrc.replace('/scripts/package.js', '').trim();
    var token = commonModule.getCookie('webapitoken');
    var re = /([a-f0-9]{8}(?:-[a-f0-9]{4}){3}-[a-f0-9]{12})/i;
    var packageId = re.exec(scriptSrc.toLowerCase())[1];
    var customFieldPrefix = packageId.replace(/-/g, "");
    var userId = $('#userGuid').val();

    function saveKeys() {
        var data = { 'clientSecret': $('#client-secret').val(), 'userId': userId };
        var apiUrl = packagePath + '/save_keys.php';
        $.ajax({
            url: apiUrl,
            headers: {
                'Authorization': 'Bearer ' + token,
            },
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(data),
            success: function(result) {
                toastr.success('Key is saved successfully');
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
        getMarketplaceCustomFields(function(result) {
            $.each(result, function(index, cf) {
                if (cf.Name == 'Courier Client Secret' && cf.Code.startsWith(customFieldPrefix)) {
                    var code = cf.Code;
                    var clientSecret = cf.Values[0];
                    $('#client-secret').val(clientSecret);
                }
            })
        });
        $('#edit-btn').click(function() {
            saveKeys();
        });
    });

})();