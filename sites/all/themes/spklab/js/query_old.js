(function ($) {
// your standard jquery code goes here with $ prefix
// best used inside a page with inline code,
// or outside the document ready, enter code here


// Drupal.behaviors.updateshipping = {
//   attach: function (context, settings) {
//     var classes = $(".view-id-commerce_cart_summary").attr('class');
//             if(classes){
//             var domid = $.grep(classes.split(" "), function(v, i){
//                   return v.indexOf('view-dom-id-') === 0;
//                 }).join();
//             }
//     //alert('Kaddu');
//     var new_country = $("#edit-customer-profile-shipping select[id*='-country']").val();
//             if (new_country == 'US') {
//                 $('#shipping-options-local').css("display", "block");
//                 $('#shipping-options-inter').css("display", "none");
//                 //$("#shipping-options-local .radio:first input:radio").attr('checked', true);
//                 //$("#shipping-options-inter .radio input:radio").attr('checked', false);
//                 var shipid = 1;
//             } else {
//                 $('#shipping-options-local').css("display", "none");
//                 $('#shipping-options-inter').css("display", "block");
//                 //$("#shipping-options-inter input[type='radio']").attr('checked', false);
//                 //$("#shipping-options-inter label:first").addClass('selected');
//                 //$("#shipping-options-local .radio input:radio").attr('checked', false);
//                 var shipid = 4;
//             }
//             var pathname = window.location.pathname;
//             var parts = pathname.split("/");
//             var orderid = parts[parts.length-1];
//           //  var shipid = $(this).val();
//             $.ajax({
//               method: "GET",
//               url: "/checkout/shipupdate",
//               data: { orderid: orderid, shipid: shipid},
//             }).done(function( result ) {
//                 $('.'+domid).trigger('views_refresh'); 
                
//             });
//   }
// };

    $(document).ready(function () {
        if ($(window).width() < 490) {
            data_sign = $(".form-item-my-module-my-module-pane-news-check").html();
            data_ = '<div class="form-item form-item-my-module-my-module-pane-news-check_cus form-type-checkbox checkbox">' + data_sign + '</div>';
            $(".form-item-my-module-my-module-pane-news-email").append(data_);
            $(".form-item-my-module-my-module-pane-news-check").hide();
            $(".form-item-my-module-my-module-pane-news-check_cus input").css("width", "20px");
        }

        if (!$('.page-learn-items #block-views-projects-view-block').length) {
            $('body').addClass('no-projects');
        }
        if (!$('.page-learn-items #block-views-teacher-aids-view-block').length) {
            $('body').addClass('no-teacheing');
        }

        $(".node-type-product .views-field-field-image-or-video .field-content .file-video").parents(".views-row").addClass("video-title-none");
        $('.page-cart #mini-cart-subtotal').nextAll().andSelf().wrapAll('<div id="side-mini-checkout" class="mini-checkout col-sm-4">');
        $('<div class="next-untill-coupon"></div>').appendTo('.commerce_coupon');
        $('#side-mini-checkout #mini-cart-subtotal').nextUntil('.next-untill-coupon').andSelf().wrapAll('<div id="side-mini-checkout-inner-container">');
        
        var title = $("body.page-learn-items .view-id-item_title .title-area").text();
        var str = $("#block-upm-custom-component-custom-referer .ref").text();
        var res = str.replace("Product", title);
        $("#block-upm-custom-component-custom-referer .ref").text(res);
        var getcart_popup = $("#cart-popup").html();
        $("#cartBox .modal-body").html(getcart_popup);
        $("#block-menu-menu-utilities li#cart").after("<div id='cart-popup-main'><div id='cart-popup'>" + getcart_popup + "</div></div>");
        // prevent minicart for cart page
        if ($(".commerce_product_added").length) {
            if($('.page-cart').length == 0){
                $('#cart-popup-main').fadeIn();
                setTimeout(function () {
                    $('#cart-popup-main').fadeOut();
                }, 3000);
            }
        }
         $("#block-menu-menu-utilities li#cart, #cart-popup-main").hover(function () {
            if($('.page-cart').length == 0){ // prevent minicart for cart page
                $("#block-menu-menu-utilities #cart-popup-main").css("display", "block");
            }
        },function () {
            $("#block-menu-menu-utilities #cart-popup-main").css("display", "none");
        });
        
         
            
        
        var left = '';
        var right = '';
        if ($("#block-pager .pager-main-next .pager-path").length != 0) {
            left = $("#block-pager .pager-main-next .pager-path").html();
        } else {
            left = '<span class="no-link-left" title="No Link"></span>';
            $("#block-pager .pager-main-next ").html('<span class="no-link-left" title="No Link"></span>');
        }
        if ($("#block-pager .pager-main-previous .pager-path").length != 0) {
            right = $("#block-pager .pager-main-previous .pager-path").html();
        } else {
            right = '<span class="no-link-right" title="No Link"></span>';
            $("#block-pager .pager-main-previous ").html('<span class="no-link-right" title="No Link"></span>');
        }
        if ($(window).width() < 480) {
            $("#block-views-learn-projects-node-block, #block-views-lesson-node-block").before("<section id='flippynxtpre'><ul class='flippy'><li class='right-arrow'>" + right + "</li><li class='left-arrow'>" + left + "</li></ul></section>");
        } else {
            $("#block-views-learn-projects-node-block, #block-views-lesson-node-block").before("<section id='flippynxtpre'><ul class='flippy'><li class='right-arrow'>" + right + "</li><li class='left-arrow'>" + left + "</li></ul></section>");
        }
        $("span.zoom").zoom({on: 'click'});
        $("#block-views-project-node-block").after("<section id='flippy-arrows' class='copyflippy col-sm-2 p-0'><ul class='flippy'>" + $(".flippy").html() + "</ul></section>");
        $("#block-views-learn-projects-node-block, #block-views-lesson-node-block, #block-views-project-node-block, #block-views-teaching-aid-node-block").after("<section id='sharethis-buttons-block' class='col-sm-offset-1 col-sm-3  p-0'>" + $("#block-sharethis-sharethis-block .sharethis-wrapper").html() + "</section>");
        $(".node-type-product .main-content-cart .sidebar2-content .sidebar-sections .share-section").after("<section id='sharethis-buttons-block'>" + $("#block-sharethis-sharethis-block .sharethis-wrapper").html() + "</section>");
        $("aside #sharethis-buttons-block").html($("#block-sharethis-sharethis-block .sharethis-wrapper").html());
        $("#navbar .region-container").after("<div id='custommenu' class='custommenu1'>" + $("#block-system-main-menu").html() + "</div>");
        $("button.navbar-toggle").click(function () {
            $('#custommenu.custommenu1').toggleClass('custommenu2');
        });
        $("#block-menu-menu-utilities #search").click(function () {
            $('.small_menu').toggleClass('show-search');
            var1 = $(".small_menu").hasClass('show-search');
            if (var1) {
                $('#custommenu').removeClass('custommenu2');
            }
        });
        $(".navbar-toggle").click(function () {
            var1 = $(".small_menu").hasClass('show-search');
            if (var1) {
                $('.small_menu').removeClass('show-search');
            }
        });
        $('html').click(function (e) {
            if(e.target.id == "edit-search-block-form--2" ){
                
            } else if(e.target.id == "search"  ){
                
            }else{
                $('.small_menu').removeClass('show-search');
            } 
        });
        var count = $("#block-commerce-popup-cart-commerce-popup-cart .cart_popup_count").text();
        if (count == 0) {
            count = "";
            $("li#cart a").html('');
        } else {
            $("li#cart a").html('<span class="count-containor">' + count + '</span>');
        }
        $(".views-field-field-product-tags .field-content").each(function (index) {
            var tags = $(this).text();
            var tags_array = tags.split(",");
            var leng = tags_array.length;
            var color = $(".views-field-field-tag-colorr .field-content").eq(index).text();
            var color_array = color.split(",");
            var newhtml = '';
            $.each(tags_array, function (index, value) {
                newhtml += '<span style="color:' + color_array[index] + ' ">' + value + '</span>';
            });
            $(this).html(newhtml);
        });
        $(".views-field-field-tag .field-content").each(function (index) {
            var tags = $(this).text();
            var tags_array = tags.split(",");
            var leng = tags_array.length;
            var color = $(".views-field-field-tag-color .field-content").eq(index).text();
            var color_array = color.split(",");
            var newhtml = '';
            $.each(tags_array, function (index, value) {
                newhtml += '<span style="color:' + color_array[index] + ' ">' + value + '</span>';
            });
            $(this).html(newhtml);
        });
        $(".product-main .product-tags").each(function (index) {
            var color = $(this).find(".product-color").text();
            var color_array = color.split(",");
            var tags = $(this).find(".tags").text();
            var tags_array = tags.split(",");
            var newhtml = '';
            $.each(tags_array, function (index, value) {
                newhtml += '<span style="color:' + color_array[index] + ' ">' + value + '</span>';
            });
            $(this).html(newhtml);
        });
        $(".tags-container .tag").each(function (index) {
            var tags = $(this).text();
            var color = $("#tags_colors").text();
            var color_array = color.split(",");
            var newhtml = '';
            newhtml += '<div class="tags-content tag" style="color:' + color_array[index] + ' ">' + tags + '</div>';
            $(this).html(newhtml);
        });
        $("#block-views-blog-detail-page-title-block h2").html("<div id='abc'><div class='breadcrumb'>" + $(".breadcrumb").html() + "</div></div>");
        $(".page-blogs #page-header,.node-type-blog  #page-header").text('News');
        $(".breadcrumb").remove();
        // Waqar ka kam
        $('#edit-customer-profile-billing .panel-body').prepend('<div class="form-item form-type-checkbox checkbox"><label class="copy-address control-label" for="copy-address"><input id="copy-address" class="locality-copy-address form-checkbox" type="checkbox"> Same as shipping address </label></div>');
        $('#edit-cart-contents .panel-heading .panel-title').text('Summary');
        $('form.commerce-add-to-cart .form-submit').text('Add to Bag');
        $('form.commerce-add-to-cart .form-submit[disabled=disabled]').text('Out of Stock');
        $(".commerce_stock_notifications_fieldset").wrap('<div id="notification" class="modal fade" role="dialog"></div>');
        $('form.commerce-add-to-cart .form-submit[disabled=disabled]').before('<a id="notifi-link" data-toggle="modal" data-target="#notification">Email me when Back in stock</a>');
        $('#notification .form-submit').text('Submit');
        if ($('#learn-item-cart .form-submit').is('[disabled=disabled]'))
        {
            var buttext = 'Out of Stock | ' + $('#learn-item-cart .inline-price').text();
        } else {
            var buttext = 'Add to Cart | ' + $('#learn-item-cart .inline-price').text();
        }
        $('#learn-item-cart .form-submit').text(buttext);
        $("#block-block-7").after($(".learn-item-cart-button").html());
        $('.main-content-cart form.commerce-add-to-cart .form-item-quantity .control-label').text('Qty.');
        $('.main-content-cart form.commerce-add-to-cart .form-item-quantity .form-text').after('<a id="increment" class="btn">+</a>');
        $('.main-content-cart form.commerce-add-to-cart .form-item-quantity .form-text').before('<a id="decrement" class="btn">-</a>');
        $('#block-views-all-projects-block .view-content').append($('#block-views-all-projects-block-1 .view-content').html());
        $('#block-views-items-block .view-content').append('<div class="view-display-id-block_1">' + $('#block-views-items-block-1 .view-content').html() + '</div>');
        $('#block-views-items-block-1 .view-content').remove();
        $('#block-views-all-projects-the-lab-block .view-content').append($('#block-views-all-projects-the-lab-block-1 .view-content').html());
        $("#edit-customer-profile-shipping div[class*='-postal-code']").append('<div class="text-error"></div>');
        $('#edit-customer-profile-billing label.copy-address input').change(function (e) {
            if ($(this).is(":checked")) {
                
                var idShip = jQuery('#edit-customer-profile-shipping-commerce-customer-address').children().children()[0];
                //jQuery(idd).find('.thoroughfare').val()
                console.log($(idShip).find('.administrative-area').val());
                $("#edit-customer-profile-billing input[id*='-first-name']").val($(idShip).find('.first-name').val());
                $("#edit-customer-profile-billing input[id*='-last-name']").val($(idShip).find('.last-name').val());
                $("#edit-customer-profile-billing input[id*='-thoroughfare']").val($(idShip).find('.thoroughfare').val());
                $("#edit-customer-profile-billing input[id*='-premise']").val($(idShip).find('.premise').val());
                $("#edit-customer-profile-billing input[id*='-postal-code']").val($(idShip).find('.postal-code').val());
                $("#edit-customer-profile-billing input[id*='-locality']").val($(idShip).find('.locality').val());
                var ex_val = $(idShip).find('.state').val();
                $("#edit-customer-profile-billing select[id*='-administrative-area']").val(ex_val);
                var ex_vall = $("#edit-customer-profile-shipping select[id*='-title-und']").val();
                $("#edit-customer-profile-billing select[id*='-title-und']").val(ex_vall);
                // var val_country = $("#edit-customer-profile-shipping select[id*='-country']").val();
                // $("#edit-customer-profile-billing select[id*='-country']").val(val_country);
            } else {
                $("#edit-customer-profile-billing input[id*='-first-name']").val('');
                $("#edit-customer-profile-billing input[id*='-last-name']").val('');
                $("#edit-customer-profile-billing input[id*='-thoroughfare']").val('');
                $("#edit-customer-profile-billing input[id*='-premise']").val('');
                $("#edit-customer-profile-billing input[id*='-postal-code']").val('');
                $("#edit-customer-profile-billing input[id*='-locality']").val('');
                $("#edit-customer-profile-billing select[id*='-administrative-area'] ").val('');
            }
        });
        function isEmail(email) {
            var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
            return regex.test(email);
        }
        $('a#decrement').click(function (e) {
            e.preventDefault();
            var qty = $('form.commerce-add-to-cart .form-item-quantity .form-text').val();
            if (qty != 1) {
                $('form.commerce-add-to-cart .form-item-quantity .form-text').val(qty - 1);
            }

        });
        $('a#increment').click(function (e) {
            e.preventDefault();
            var qty = $('form.commerce-add-to-cart .form-item-quantity .form-text').val();
            if (qty < 10) {
                $('form.commerce-add-to-cart .form-item-quantity .form-text').val(parseInt(qty) + 1);
            }
        });
        $('a.product-qty-inc').click(function (e) {
            e.preventDefault();
            $('#main-cart-item').removeClass('active');
            $(this).parents('#main-cart-item').addClass('active');
            var qty = $('#main-cart-item.active .product-qty .form-text').val();
            if (qty < 10) {
                $('#main-cart-item.active .product-qty .form-text').val(parseInt(qty) + 1);
                // code to autoupdate cart prices
                var orderid = $('input[name="tax_form_wrapper[order_id]"]').val();
                var lineitemid = $(this).attr('lineitemid');
                var new_qty = parseInt(qty) + 1;
                cartAutoUpdate(orderid, lineitemid, new_qty);
            }  
        });
        $('a.product-qty-dec').click(function (e) {
            e.preventDefault();
            $('#main-cart-item').removeClass('active');
            $(this).parents('#main-cart-item').addClass('active');
            var qty = $('#main-cart-item.active .product-qty .form-text').val();
            if (qty != 0) {
                $('#main-cart-item.active .product-qty .form-text').val(qty - 1);
                // code to autoupdate cart prices
                var orderid = $('input[name="tax_form_wrapper[order_id]"]').val();
                var lineitemid = $(this).attr('lineitemid');
                if((parseInt(qty) - 1) == 0){
                    $('.delete-'+lineitemid).find('button.delete-line-item').click();
                    return;
                }
                var new_qty = parseInt(qty) - 1;
                cartAutoUpdate(orderid, lineitemid, new_qty);
            }
        });
        $('.product-qty').focusout(function() {
          var orderid = $('input[name="tax_form_wrapper[order_id]"]').val();
          var lineitemid = $(this).attr('qtyitemid');
          var new_qty = $(this).find('input').val();
          cartAutoUpdate(orderid, lineitemid, new_qty);
        });
        $('.product-qty').keyup(function(e){
            if(e.keyCode == 13){
                var orderid = $('input[name="tax_form_wrapper[order_id]"]').val();
                var lineitemid = $(this).attr('qtyitemid');
                var new_qty = $(this).find('input').val();
                cartAutoUpdate(orderid, lineitemid, new_qty);
                $(this).find('input').trigger('blur');
            }
        });
        function cartAutoUpdate(orderid, lineitemid, new_qty){
            $.ajax({
              method: "GET",
              url: "/cart/autoupdate",
              data: { orderid: orderid, lineitemid: lineitemid, qty: new_qty},
            }).done(function( result ) {
                var myresult = $.parseJSON(result);
                $('.item-'+lineitemid).text(myresult.item_price);
                $('#mini-cart-subtotal .component-type-commerce-subtotal .component-total').text(myresult.subtotal_price);
                $('button#edit-tax-form-wrapper-submit').mousedown();
            });
        }
        var referrer =  document.referrer;
        var currenturl = window.location.href;
        if(referrer == currenturl){
            referrer = '/content/shop';
        }
        
        var emptycart = '<div id="cart-view-main"><div id="cart-view-main-title"><h2>Your Bag (0) items</h2></div><p>Your shopping bag is empty.</p><a class="btn btn-default" href="'+ referrer +'">CONTINUE SHOPPING</a>';
        //$('.page-cart .cart-empty-page').html(emptycart);
        $('.page-cart .cart-empty-page').parents('body').addClass('empty-cart');
        $('#spk-custom-form1 #msg-newsletter,#spk-custom-form2 #msg-newsletter').click(function () {
            $(this).hide();
            $(this).parents('#spk-custom-form1,#spk-custom-form2').removeClass('done');
            $(this).parents('#spk-custom-form1,#spk-custom-form2').removeClass('error');
            $(this).siblings('input').focus();
        });
        $('#spk-custom-form2').submit(function (e) {
            e.preventDefault();
            var email = $('#newsletter-email2').val();
            if (isEmail(email)) {
                var data = "email=" + email;
                $.ajax
                        ({
                            type: "POST",
                            url: "/custom-newsletter",
                            data: data,
                            success: function (result)
                            {
                                $('#spk-custom-form2 #msg-newsletter').html('<p>Thank you for signing up! You will recieve an email confrmation from us.</p>');
                                $('#spk-custom-form2').addClass('done');
                                $('#spk-custom-form2 #msg-newsletter').show();
                            }
                        });
            } else {
                $('#spk-custom-form2 #msg-newsletter').html('<p>Please enter a valid email address.</p>');
                $('#spk-custom-form2').addClass('error');
                $('#spk-custom-form2 #msg-newsletter').show();
            }

        });
        $('#spk-custom-form1').submit(function (e) {
            e.preventDefault();
            var email = $('#newsletter-email1').val();
            if (isEmail(email)) {
                var data = "email=" + email;
                $.ajax
                        ({
                            type: "POST",
                            url: "/custom-newsletter",
                            data: data,
                            success: function (result)
                            {
                                $('#spk-custom-form1 #msg-newsletter').html('<p style="color:#6cbb44">Thank you for signing up! You will recieve an email confrmation from us.</p>');
                                $('#spk-custom-form1').addClass('done');
                                $('#spk-custom-form1 #msg-newsletter').show();
                            }
                        });
            } else {
                $('#spk-custom-form1 #msg-newsletter').html('<p style="color:#900">Please enter a valid email address.</p>');
                $('#spk-custom-form1').addClass('error');
                $('#spk-custom-form1 #msg-newsletter').show();
            }

        });
        $('#newsletter-learn-item-follow-submit').click(function () {
            var email = $('#newsletter-learn-item-follow-email').val();
            if (isEmail(email)) {
                var type = $('#learn-item-follow-type').text().replace(/\s/g, '');
                var data = "email=" + email + '&type=' + type;
                $.ajax
                        ({
                            type: "POST",
                            url: "/custom-newsletter",
                            data: data,
                            success: function (result)
                            {
                                $('#learn-item-follow-kits #msg-newsletter').html('<p style="color:#6cbb44">Thank you for signing up! You will recieve an email confrmation from us.</p>');
                            }
                        });
            } else {
                $('#learn-item-follow-kits #msg-newsletter').html('<p style="color:#900">Please enter a valid email address.</p>');
            }
        });
        var container = '';
        /** Handle successful response */
        function handleResp(data)
        {   

            if (data.error_msg)
                errorDiv.text(data.error_msg);
            else
            {
                $("#edit-customer-profile-shipping input[id$='-locality']").val(data.city);
                $('#edit-customer-profile-shipping').find("select[id$='-administrative-area'] option").filter(function () {
                    //may want to use $.trim in here
                    return $(this).text() == data.state;
                }).prop('selected', true);
            }
        }
          $('#edit-customer-profile-shipping').on('click','input[id$="-postal-code"]',function(){
                  $("#edit-customer-profile-shipping input[id$='-postal-code']").change(function() {
                    //console.log('------1');
                    var zipcode = $("#edit-customer-profile-shipping input[id$='-postal-code']").val();
                    if (zipcode.length == 5 && /^[0-9]+$/.test(zipcode))
                    {
                        var url = "/zipcode/autocomplete";
                        var data = "zipcode=" + zipcode;
                        $.ajax({
                            type: "POST",
                            url: url,
                            data: data,
                            beforeSend: function () {
                                //$('#edit-customer-profile-shipping [class*="-postal-code"]').css("background-position", "right -30px bottom -5px");
                            },
                            success: function (result)

                            {
                                handleResp(result);
                            },
                            complete: function () {
                                //$('#edit-customer-profile-shipping [class*="-postal-code"]').css("background-position", "0 15px ");
                            },
                        });
                    }
                    else{
                        alert("Enter a Valid Zipcode! Or Choose a Valid Country!");
                    }
                });
               
            });

        // Set up event handlers
        // $("#edit-customer-profile-shipping input[id$='-postal-code']").change(function() {
        //     //var zipcode = $(this).val().substring(0, 5);
        //     var zipcode = $(this).val();
        //     if (zipcode.length == 5 && /^[0-9]+$/.test(zipcode))
        //     {
        //         var url = "/zipcode/autocomplete";
        //         var data = "zipcode=" + zipcode;
        //         $.ajax({
        //             type: "POST",
        //             url: url,
        //             data: data,
        //             beforeSend: function () {
        //                 //$('#edit-customer-profile-shipping [class*="-postal-code"]').css("background-position", "right -30px bottom -5px");
        //             },
        //             success: function (result)

        //             {
        //                 handleResp(result);
        //             },
        //             complete: function () {
        //                 //$('#edit-customer-profile-shipping [class*="-postal-code"]').css("background-position", "0 15px ");
        //             },
        //         });
        //     }
        //     else{
        //         alert("Enter a Valid Zipcode! Or Choose a Valid Country!");
        //     }
        // });
        

        $("#block-views-all-projects-the-lab-block .views-row-3").append("<div id='project-cartoon-right'><img src='/sites/all/themes/spklab/css/images/project-cartoon-right.png'></div>");
        $("#block-views-all-projects-the-lab-block-2 .views-row-3.views-row-last").prepend("<div id='project-cartoon-1'><img src='/sites/all/themes/spklab/css/images/project-cartoon-1.png'></div>");
        $("#block-views-all-projects-the-lab-block-2 .views-row-2").prepend("<div id='project-cartoon-2'><img src='/sites/all/themes/spklab/css/images/project-cartoon-2.png'></div>");
        $("#edit-commerce-giftwrap-my-gift-block .form-textarea-wrapper").after("<p class='invoice-text'>Your message will be printed on the invoice and prices will be omitted.</p>");
        $('#edit-commerce-giftwrap-my-gift-block-my-gift-pane-gift-option .form-item input[type="radio"]').each(function () {

            if ($(this).is(':checked')) {
                if ($(this).val() == 'free') {
                    $('#edit-commerce-giftwrap-my-gift-block').addClass('free-option');
                    $('#edit-commerce-giftwrap-my-gift-block').removeClass('price-option');
                } else {
                    $('#edit-commerce-giftwrap-my-gift-block').addClass('price-option');
                    $('#edit-commerce-giftwrap-my-gift-block').removeClass('free-option');
                }
                $(this).parents("label").addClass('selected');
            } else {
                $(this).parents("label").removeClass('selected');
            }
        });
        $('#edit-commerce-giftwrap-my-gift-block-my-gift-pane-gift-option .form-item input[type="radio"]').click(function () {
            if ($(this).is(':checked')) {
                if ($(this).val() == 'free') {
                    $('#edit-commerce-giftwrap-my-gift-block').addClass('free-option');
                    $('#edit-commerce-giftwrap-my-gift-block').removeClass('price-option');
                } else {
                    $('#edit-commerce-giftwrap-my-gift-block').addClass('price-option');
                    $('#edit-commerce-giftwrap-my-gift-block').removeClass('free-option');
                }
                $(this).parents("label").addClass('selected');
                $(this).parents(".form-type-radio").siblings('.form-type-radio').find("label").removeClass('selected');
            }
        });
        $('fieldset.commerce_shipping_rates .panel-body .form-radios label input[type="radio"]').each(function () {
            if ($(this).is(':checked')) {
                $(this).parents("label").addClass('selected');
            } else {
                $(this).parents("label").removeClass('selected');
            }
        });
        $('fieldset.commerce_shipping_rates .panel-body .form-radios label input[type="radio"]').click(function () {
            if ($(this).is(':checked')) {
                $(this).parents("label").addClass('selected');
                $(this).parents(".form-type-radio").siblings('.form-type-radio').find("label").removeClass('selected');
            }
        });
        $("#side-mini-checkout #edit-actions").prepend("<div class='proceed-to'>Proceed to</div>");
        /*Paypal Express Checkout Button New Location*/
        $("#side-mini-checkout #edit-actions").append("<div id='custom-ecimg'><span>or pay with</span><img src='/sites/default/files/paypal2.png' /></div>");
        $("#custom-ecimg img").click(function(){
            $("input#edit-paypal-ec").trigger("click");
        });
        $('.block-block-16-customer-care').appendTo('#side-mini-checkout');
        //$('#side-mini-checkout').append($('.block-block-16-customer-care').html())
        
        $(".page-checkout #edit-cart-contents .panel-body .view-footer .table-responsive table tbody tr.component-type-commerce-price-formatted-amount .component-title").text("Estimated Total");
        $(".page-checkout #edit-cart-contents .panel-body .view-footer .table-responsive table tbody tr.component-type-taxsales-tax .component-title").text("Estimated Sales Tax");
        $("#edit-buttons #edit-continue").text("Continue");
        $("#commerce-checkout-coupon-ajax-wrapper .commerce_coupon .panel-body .form-item label").text("Promo code");
        $("#commerce-checkout-coupon-ajax-wrapper .commerce_coupon .panel-body button").text("apply");
        $(document).ajaxComplete(function () {
            $("#commerce-checkout-coupon-ajax-wrapper .commerce_coupon .panel-body button").text("apply");
          //  $("#edit-customer-profile-shipping .addressfield-container-inline .form-item-customer-profile-shipping-commerce-customer-address-und-0-administrative-area label").text("State");
          //  $("#edit-customer-profile-shipping .addressfield-container-inline .form-item-customer-profile-shipping-commerce-customer-address-und-0-postal-code label").text("ZIP code");
        })

        // checkout page for manege discount


        var checkprice = $(".view-commerce-cart-summary .view-footer table tr.component-type-base-price .component-total").text();
        var checkdiscount = $(".view-commerce-cart-summary .view-footer table tr.component-type-discount .component-total").text();
        if (checkdiscount !== "") {
            var checkdis = checkdiscount.replace("-$", "");
            var checkpri = checkprice.replace("$", "");
            var checkactual = parseFloat(checkpri) - parseFloat(checkdis);
            $(".view-commerce-cart-summary .view-footer table tr.component-type-base-price .component-total").text("$" + checkactual.toFixed(2));
        }

        var placemyorder = $(".page-checkout #block-system-main").html();
        $("#block-upm-order-compelete-cart-content-review .view-commerce-cart-summary").append(placemyorder);
        $("#block-upm-order-compelete-cart-content-review .view-commerce-cart-summary form#commerce-checkout-form-review .panel-body button#edit-continue").text("Place my order");
        $(".page-cart .commerce_coupon .form-group label").text("Promo code");
        $(".page-cart .commerce_coupon .form-group button").text("apply");
        $('#block-upm-order-compelete-gift-review').nextUntil("#block-upm-order-compelete-payment-method-review").andSelf().wrapAll('<div id="left-four-colums">');
        $('#block-views-b5635eeba3b601bc0b3cc90f6e960d43').nextUntil("#block-upm-order-compelete-cart-content-review").andSelf().wrapAll('<div id="left-main-colum">');
        if (window.matchMedia('(max-width: 380px)').matches) {
            $(".main-top-header .navbar-header .logo #block-imageblock-3 a").html("<img typeof='foaf:Image' class='img-responsive' src='/sites/default/files/styles/logo/public/logo.png' alt='Sparkle Lab' title='Sparkle Lab'>");
        }


        $("#block-upm-order-compelete-cart-content-review h2, .view-checkout-confirmation-title .views-field-field-email .field-content").append("<a href='/checkout'>edit</a>");
        $("#left-main-colum #left-four-colums").prepend("<a href='/checkout'>edit</a>");
        $('#edit-cart-contents').nextUntil("#edit-my-module").andSelf().wrapAll('<div id="right-side-colums">');
        // $(document).ajaxComplete(function(){
        //     $(".form-item-customer-profile-shipping-commerce-customer-address-und-0-administrative-area.form-type-select label").text("State  *");
        // });

        if ($("#edit-commerce-giftwrap-my-gift-block-my-gift-pane-gift-option .form-item-commerce-giftwrap-my-gift-block-my-gift-pane-gift-option label").hasClass("selected")) {
            $(this).parent().addClass('active');
        }
        if (window.matchMedia('(max-width: 720px)').matches) {
            var h = $('#block-views-item-title-block .pagee-title').height();
            h = parseInt(h) + parseInt(5);
            $('#learn-item-cart button').css('top', h);
            h = parseInt(h) + parseInt(50);
            $('#block-block-9').css('margin-top', h);
        }

        /////.  

        // $(document).ajaxComplete(function () {
        //     if ($("body").hasClass("page-checkout")) {
        //         change_shipping_options();
        //     }
        // });
        
            $('body').on('click','.country',function(){
                 $("#edit-customer-profile-shipping select[id*='-country']").change(function() {
                   change_shipping_options();
                   var val_country = $("#edit-customer-profile-shipping select[id*='-country']").val();
                   $("#edit-customer-profile-billing select[id*='-country']").val(val_country).trigger('change');
                });
            });
        
       

        function change_shipping_options() {
            var new_country = $("#edit-customer-profile-shipping select[id*='-country']").val();
            var classes = $(".view-id-commerce_cart_summary").attr('class');
            if(classes){
            var domid = $.grep(classes.split(" "), function(v, i){
                  return v.indexOf('view-dom-id-') === 0;
                }).join();
            }
            if (new_country == 'US') {
                $('#shipping-options-local').css("display", "block");
                $('#shipping-options-inter').css("display", "none");
                //$("#shipping-options-local .radio:first input:radio").attr('checked', true);
                //$("#shipping-options-inter .radio input:radio").attr('checked', false);
                var shipid = $('fieldset.commerce_shipping_rates .panel-body #shipping-options-local .form-radios label.selected input[type="radio"]').val();
            } else {
                $('#shipping-options-local').css("display", "none");
                $('#shipping-options-inter').css("display", "block");
                var shipid = $('fieldset.commerce_shipping_rates .panel-body #shipping-options-inter .form-radios label.selected input[type="radio"]').val();
            }
           // console.log($('fieldset.commerce_shipping_rates .panel-body #shipping-options-local .form-radios label.selected input[type="radio"]').val());
            var pathname = window.location.pathname;
            var parts = pathname.split("/");
            var orderid = parts[parts.length-1];
            $.ajax({
              method: "GET",
              url: "/checkout/shipupdate",
              data: { orderid: orderid, shipid: shipid},
            }).done(function( result ) {
                $('.'+domid).trigger('views_refresh');                 
            });
            
        }

        $("#navbar .navbar-header button.navbar-toggle").text("menu");

    });
})(jQuery);



