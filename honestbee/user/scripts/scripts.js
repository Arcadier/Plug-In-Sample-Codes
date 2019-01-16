(function() {
    var pathname = (window.location.pathname + window.location.search).toLowerCase();
    var token = commonModule.getCookie('webapitoken');
    const packageVersion = "1.0.1";
    const localstorageLifetime = 86400;
    var hostname = window.location.hostname;
    var scriptSrc = document.currentScript.src;
    var packagePath = scriptSrc.replace('/scripts/scripts.js', '').trim();
    var re = /([a-f0-9]{8}(?:-[a-f0-9]{4}){3}-[a-f0-9]{12})/i;
    var packageId = re.exec(scriptSrc.toLowerCase())[1];
    var customFieldPrefix = packageId.replace(/-/g, "");
    var userId = $('#userGuid').val();
    var getPackageCustomFieldCache = userId + "_" + packageId;

    function getURLParam(key, target) {
        var values = [];
        if (!target) target = location.href;

        key = key.replace(/[\[]/, "\\\[").replace(/[\]]/, "\\\]");

        var pattern = key + '=([^&#]+)';
        var o_reg = new RegExp(pattern, 'ig');
        while (true) {
            var matches = o_reg.exec(target);
            if (matches && matches[1]) {
                values.push(matches[1]);
            } else {
                break;
            }
        }

        if (!values.length) {
            return null;
        } else {
            return values;
        }
    }

    function getPackageCustomFields(callback) {
        if (window.localStorage.getItem(getPackageCustomFieldCache) != null) {
            var value = JSON.parse(window.localStorage.getItem(getPackageCustomFieldCache));
            var version = value['version'];
            if (version === packageVersion) {
                var customFields = value['customFields'];
                callback(customFields);
                return;
            }
        }

        var apiUrl = '/api/developer-packages/custom-fields?packageId=' + packageId;
        $.ajax({
            url: apiUrl,
            method: 'GET',
            contentType: 'application/json',
            success: function(response) {
                if (response) {
                    const packageInfo = {
                        version: packageVersion,
                        customFields: response,
                    }
                    window.localStorage.setItem(getPackageCustomFieldCache, JSON.stringify(packageInfo));
                    callback(response);
                }
            }
        });
    }

    function getMarketplaceCustomFields(callback) {
        if (window.localStorage.getItem(hostname) != null) {
            var value = JSON.parse(window.localStorage.getItem(hostname));
            var version = value['version'];
            if (version === packageVersion) {
                var customFields = value['customFields'];
                callback(customFields);
                return;
            }
        }

        var apiUrl = '/api/v2/marketplaces'
        $.ajax({
            url: apiUrl,
            method: 'GET',
            contentType: 'application/json',
            success: function(response) {
                if (response) {
                    const marketplaceInfo = {
                        version: packageVersion,
                        customFields: response.CustomFields,
                    }
                    window.localStorage.setItem(hostname, JSON.stringify(marketplaceInfo));
                    callback(response.CustomFields);
                }
            }
        });
    }

    function getUserInfo(id, callback) {

        var apiUrl = '/api/v2/users/' + (id == null ? userId : id);
        $.ajax({
            url: apiUrl,
            method: 'GET',
            headers: {
                'Authorization': 'Bearer ' + token,
            },
            contentType: 'application/json',
            success: function(response) {
                if (response) {
                    callback(response);
                }
            }
        });
    }

    var orderDeliveryPath = '/user/order/deliverydetail';
    if (pathname.indexOf(orderDeliveryPath) > -1) {

    }

    var orderCartPath = '/user/order/cart';
    if (pathname.indexOf(orderCartPath) > -1) {
        $('div.cart-total-bottom').hide();
        $('div.cart-top-sec-left .cart-total-txt').hide();
        $('div.cart-top-sec-left .cart-total-amount').hide();
    }

    var orderSummaryPath = '/user/order/ordersummary';
    if (pathname.indexOf(orderSummaryPath) > -1) {
        if (token != null && token.length > 0) {
            var orders = $('#orderGuids').val().split(",");
            var isUpdated = window.localStorage.getItem('order-updated');
            var lastHonestbeeSelection = window.localStorage.getItem('honestbee-option');
            var orderUpdated = [];
            if (isUpdated != null) {
                orderUpdated = isUpdated.split(",");
            }
            if (lastHonestbeeSelection == undefined) {
                lastHonestbeeSelection = "3";
            }

            var options = '<div class="honestbee-shipping-opt"><div class="item-form-group"><label>Honestbe Shipping Type</label><select name="shipping-option"><option value="1">Same Day Express</option><option value="2">Next Day Anytime</option><option value="3">3-Day Anytime</option></select></div></div><div class="clearfix"></div>';
            $(options).insertAfter($('div.cart-item-row').last());
            $('select[name=shipping-option]').val(lastHonestbeeSelection);
            $('select[name=shipping-option]').on('change', function(e) {
                var orders = $('#orderGuids').val().split(",");
                updateOrderShippingCost(orders);
            });

            orders = orders.filter(function(el) { return orderUpdated.indexOf(el) < 0; });
            updateOrderShippingCost(orders);
        }
    }

    function updateOrderShippingCost(orders) {
        if (orders.length > 0) {
            getPackageCustomFields(function(packageCustomFields) {
                var data = {
                    'orders': orders,
                    'userId': userId,
                    'package_custom_fields': packageCustomFields,
                    'option': $('select[name=shipping-option]').val()
                };
                var apiUrl = packagePath + '/calculate_delivery.php';
                $.ajax({
                    url: apiUrl,
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify(data),
                    success: function(response) {
                        window.localStorage.removeItem('order-updated');
                        window.localStorage.setItem('order-updated', orders.join());
                        window.localStorage.setItem('honestbee-option', $('select[name=shipping-option]').val());
                        window.location.reload(true);
                    }
                });
            });
        }
    }

    var sellerSettings = '/user/marketplace/seller-settings';
    if (pathname.indexOf(sellerSettings) > -1) {
        if (true) {
            getUserInfo(null, function(userInfo) {
                var found = false;
                var selection = undefined;
                if (userInfo.CustomFields != undefined) {
                    $.each(userInfo.CustomFields, function(index, cf) {
                        if (cf.Name == 'Enable' && cf.Code.startsWith(customFieldPrefix)) {
                            found = true;
                            selection = cf.Values[0];
                            return;
                        }
                    });
                }
                var honestBeeDiv = '<div class="seller-common-box honestBee"><div class="item-form-group"><div class="col-md-12"><label>HONESTBEE </label><p>Conﬁgure your Honestbee shipping method</p><div class="honestbee-radio-list" data-id="1"><input type="radio" name="auto_all_items" id="auto_all"><label for="auto_all"> Automatically enabled on all items</label></div><div class="honestbee-radio-list" data-id="2"><input type="radio" name="auto_all_items" id="all_optional"><label for="all_optional">Available on all items but is optional</label></div><div class="honestbee-radio-list" data-id="3"><input type="radio" name="auto_all_items" id="dont_use"><label for="dont_use">Do not want to use this shipping method</label></div></div></div></div>';
                $(honestBeeDiv).insertAfter($('div.seller-common-box').last());
                if (selection != undefined) {
                    $("div.honestbee-radio-list[data-id='" + selection + "']").addClass('checked');
                }
                var $radios = $('.honestBee .honestbee-radio-list input:radio');
                $radios.change(function() {
                    var selection = $(this).parent().attr("data-id");
                    $radios.parent().removeClass('checked');
                    $(this).parent().addClass('checked');
                    if (selection != null) {
                        getPackageCustomFields(function(response) {
                            $.each(response, function(index, cf) {
                                if (cf.Name == 'Enable') {
                                    var code = cf.Code;
                                    var apiUrl = '/api/v2/users/' + userId;
                                    var data = {
                                        "ID": userId,
                                        "CustomFields": [{
                                            "Code": code,
                                            "Values": ['' + selection]
                                        }]
                                    }
                                    $.ajax({
                                        url: apiUrl,
                                        method: 'PUT',
                                        headers: {
                                            'Authorization': 'Bearer ' + token,
                                        },
                                        contentType: 'application/json',
                                        data: JSON.stringify(data),
                                        success: function(response) {
                                            if (response) {}

                                        }
                                    });
                                }
                            });
                        });
                    }
                });
            });
        }
    }

    var itemEditPage = '/user/item/edit';
    var itemUploadPage = '/user/item/upload'
    if (pathname.indexOf(itemEditPage) > -1 || pathname.indexOf(itemUploadPage) > -1) {
        getUserInfo(null, function(userInfo) {
            var selection = undefined;
            if (userInfo.CustomFields != undefined) {
                $.each(userInfo.CustomFields, function(index, cf) {
                    if (cf.Name == 'Enable' && cf.Code.startsWith(customFieldPrefix)) {
                        selection = cf.Values[0];
                        return;
                    }
                });
            }

            if (selection == undefined || selection == 0) return;
            if (selection == 1 || selection == 2) { //enable for all items or //enable for selected items
                getMarketplaceCustomFields(function(response) {
                    var shippingMethodGuid = '';
                    var shippingMethodId = '';
                    var found = false;
                    $.each(response, function(index, cf) {
                        if (cf.Name == 'Shipping Method' && cf.Code.startsWith(customFieldPrefix)) {
                            var code = cf.Code;
                            shippingMethodGuid = cf.Values[0];
                        }
                        if (cf.Name == 'Shipping Method Int ID' && cf.Code.startsWith(customFieldPrefix)) {
                            var code = cf.Code;
                            shippingMethodId = cf.Values[0];
                        }
                    });

                    if ($("#item-delivery-method").length) {
                        $("#item-delivery-method input[name='delivery-method']").each(function() {
                            var id = $(this).attr('shippingmethodGuid');
                            if (id == shippingMethodGuid) {
                                found = true;
                            }
                        });

                        if (found == false) {
                            var target = $("#item-delivery-method");
                            var html = '<div class="option-row fancy-checkbox">' +
                                '<input type="checkbox" name="delivery-method" id="delivery' +
                                shippingMethodId + '" shippingmethodid="' + shippingMethodId + '" shippingmethodGuid="' + shippingMethodGuid + '">' +
                                '<label for="delivery' + shippingMethodId + '">' +
                                '<span>Honestbee</span></label></div>';
                            target.append(html);
                        }
                    }
                    return;
                });
            }
        });
    }

    var itemDetailPage = '/user/item/detail/';
    if (pathname.indexOf(itemDetailPage) > -1) {
        var itemId = $('#itemGuid').val();
        var sellerId = $('#merchantGuid').val();
        getUserInfo(sellerId, function(userInfo) {
            var selection = undefined;
            if (userInfo.CustomFields != undefined) {
                $.each(userInfo.CustomFields, function(index, cf) {
                    if (cf.Name == 'Enable' && cf.Code.startsWith(customFieldPrefix)) {
                        selection = cf.Values[0];
                        return;
                    }
                });
            }

            if (selection == undefined || selection == 0) return;
            var shippingMethodId = '';
            var shippingMethodGuid = '';
            if (selection == 1 || selection == 2) { //enable for all items or //enable for selected items
                getMarketplaceCustomFields(function(response) {
                    $.each(response, function(index, cf) {
                        if (cf.Name == 'Shipping Method' && cf.Code.startsWith(customFieldPrefix)) {
                            var code = cf.Code;
                            shippingMethodGuid = cf.Values[0];
                        }
                        if (cf.Name == 'Shipping Method Int ID' && cf.Code.startsWith(customFieldPrefix)) {
                            var code = cf.Code;
                            shippingMethodId = cf.Values[0];
                        }
                    });
                });
            }

            var found = false;
            if ($(".delivery-options").length > 0) {
                $(".delivery-options .option-row input[type='radio']").each(function() {
                    var id = $(this).attr('data-delivery');
                    if (id == shippingMethodId) {
                        found = true;
                    }
                });
            }
            if (!found) {
                if (selection == 1) { //enable for all items
                    var data = { 'itemId': itemId, 'shippingMethodId': shippingMethodGuid };
                    var apiUrl = packagePath + '/add_shipping_method.php';
                    $.ajax({
                        url: apiUrl,
                        method: 'POST',
                        contentType: 'application/json',
                        data: JSON.stringify(data),
                        success: function(response) {
                            //console.log(response);
                            var json = $.parseJSON(response);
                            if (json['result'].toLowerCase() == 'true') {
                                var ele = '<div class="option-row fancy-radio">' +
                                    '<input type="radio" name="delivery-method" id="DeliveryMethods_' + shippingMethodId + '__IsSelected" data-delivery="' + shippingMethodId + '" data-delivery-type="delivery">' +
                                    '<label for="DeliveryMethods_' + shippingMethodId + '__IsSelected"><span>Honestbee</span></label></div>';
                                if ($(".delivery-options").length > 0) {

                                } else {
                                    var options = '<div class="delivery-method delivery-options"><div class="desc-title">Delivery</div></div>';
                                    $('div.delivery-details').append(options);
                                }
                                $(".delivery-options").append(ele);
                            }
                        }
                    });
                }
            }
        });
    }

    var checkoutSuccessPage = '/user/checkout/success';
    if (pathname.indexOf(checkoutSuccessPage) > -1) {
        var invoiceId = $('.thank-page-invoice .invoice-id').text();

        getPackageCustomFields(function(packageCustomFields) {
            var apiUrl = '/api/v2/users/' + userId + '/transactions/' + invoiceId;
            $.ajax({
                url: apiUrl,
                headers: {
                    'Authorization': 'Bearer ' + token,
                },
                method: 'GET',
                success: function(obj) {
                    console.log(obj);
                    if (obj) {
                        var data = { 'orders': obj.Orders, 'package_custom_fields': packageCustomFields };
                        var apiUrl = packagePath + '/create_shipping_order.php';
                        $.ajax({
                            url: apiUrl,
                            method: 'POST',
                            contentType: 'application/json',
                            data: JSON.stringify(data),
                            success: function(response) {}
                        });
                    }
                }
            });
        });
    }

    var userOrderDetails = '/user/order/orderhistorydetail';
    if (pathname.indexOf(userOrderDetails) > -1) {
        var invoices = getURLParam('invoiceNo');
        if (invoices.length > 0) {
            getPackageCustomFields(function(packageCustomFields) {
                var invoiceId = invoices[0];
                var apiUrl = packagePath + '/track_shipping_order.php';
                var data = { 'invoice_no': invoiceId, 'package_custom_fields': packageCustomFields, 'user_id': userId };
                $.ajax({
                    url: apiUrl,
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify(data),
                    success: function(response) {}
                });
            });
        }
    }

    var merchantManageOrder = '/user/manage/order/details/';
    if (pathname.indexOf(merchantManageOrder) > -1) {
        var orderId = $('#orderGuid').val();
        if (orderId.length > 0) {
            var ele = '<a href="javascript:void(0);" id="download-waybill" target="_blank"><i class="icon fa fa-download fa-fw fa-2x"></i></a>';
            $('div.ordr-dtls-prnt-btnarea').prepend(ele);

            getPackageCustomFields(function(packageCustomFields) {
                var apiUrl = packagePath + '/download_waybill_order.php';
                var data = { 'orderId': orderId, 'package_custom_fields': packageCustomFields, 'user_id': userId };
                $.ajax({
                    url: apiUrl,
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify(data),
                    success: function(response) {
                        var json = $.parseJSON(response);
                        $('#download-waybill').attr("href", packagePath + '/' + json['url']);
                    }
                });
            });
        }
    }

})();