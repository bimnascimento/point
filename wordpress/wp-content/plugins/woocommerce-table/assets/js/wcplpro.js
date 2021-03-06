jQuery(document).ready(function($) {
  if ( jQuery.isFunction(jQuery.fn.select2) ) {
    jQuery('select.wcplproselect2').select2().on("change", function(e) {
      var bindto = jQuery(this).attr('data-bindto');
      jQuery('input[name="'+bindto+'"]').val(e.val);
    });
  }
  jQuery('.wcplpro_filters_form a.wcplpro_reset').on('click', function(event) {
    event.preventDefault();
    var curform = jQuery(this).closest('form.wcplpro_filters_form');
    curform.find('input[name="wcpl_product_tag"]').val('');
    curform.find('input[name="wcpl_product_cat"]').val('');
    curform.submit();
    return false;
  });
  var ajaxurl             = wcplprovars.ajax_url;
  var carturl             = wcplprovars.cart_url;
  var currency_symbol     = wcplprovars.currency_symbol;
  var thousand_separator  = wcplprovars.thousand_separator;
  var decimal_separator   = wcplprovars.decimal_separator;
  var decimal_decimals    = wcplprovars.decimal_decimals;
  var currency_pos        = wcplprovars.currency_pos;
  var price_display_suffix= wcplprovars.price_display_suffix;
  var gclicked            = 0;
  var glob_clicked        = 0;
  var count               = 0;
  var numofadded          = 0;
  var wcplpro_ajax        = wcplprovars.wcplpro_ajax;
  var $fragment_refresh   = '';
  var get_global_cart_id  = '';
  var formdata            = new Array;
  /** Cart Handling */
  $supports_html5_storage = ( 'sessionStorage' in window && window['sessionStorage'] !== null );
  if (wcplpro_ajax == 1) {
    $fragment_refresh = {
        url: ajaxurl,
        // url: woocommerce_params.ajax_url,
        type: 'POST',
        data: { action: 'woocommerce_get_refreshed_fragments' },
        success: function( data ) {
            //console.log("WCPLPRO - AJAX");
            if ( data && data.fragments ) {
                $.each( data.fragments, function( key, value ) {
                    $(key).replaceWith(value);
                });
                if ( $supports_html5_storage ) {
                    sessionStorage.setItem( "wc_fragments", JSON.stringify( data.fragments ) );
                    sessionStorage.setItem( "wc_cart_hash", data.cart_hash );
                }
                //console.log('refresh');
                $('body').trigger( 'wc_fragments_refreshed' );
            }
        }
    };
  }
  if (jQuery("#wcplpro_added_to_cart_notification").length > 0) {
    jQuery('#wcplpro_added_to_cart_notification .slideup_panel').on('click', function(event) {
      event.preventDefault();
      jQuery('#wcplpro_added_to_cart_notification').stop(true).slideUp(200);
      glob_clicked = 0;
      return false;
    });
  }
  if (jQuery("table.wcplprotable").length > 0) {
    jQuery("table.wcplprotable").each(function(index) {
      var wcplprotable            = jQuery(this);
      var random_id               = jQuery(this).data('random');
      var wcplprotable_ajax       = jQuery(this).data('wcplprotable_ajax');
      var cartredirect            = jQuery(this).data('cartredirect');
      var sorting                 = jQuery(this).data('sort');
      var wcplprotable_globalcart = jQuery(this).data('globalcart');
      var preorder                = jQuery(this).data('preorder');
      var preorder_direction      = jQuery(this).data('preorder_direction');
      // console.log(random_id);
      update_global_sum(jQuery(this).find('input[name="wcplpro_quantity"]'));
      // gift wrap
      if (wcplprotable.find("input.wcplpro_gift_wrap").length > 0) {
        wcplprotable.find("input.wcplpro_gift_wrap").on("change", function() {
          if(jQuery(this).is(":checked")) {
            jQuery(this).closest('tr').find(".cartcol input.gift_wrap").val("yes");
          } else {
            jQuery(this).closest('tr').find(".cartcol input.gift_wrap").attr("value", "");
          }
        });
      }
      if (wcplprotable.find("div.qtywrap").length > 0) {
        jQuery("#tb_"+ random_id +" div.qtywrap").each(function() {
          var qtythis = jQuery(this);
          qtythis.find(".minusqty").on("click", function() {
            var valnum = parseInt(qtythis.find("input").val());
            var valmin = qtythis.find("input").attr('min');
            if( typeof valmin === 'undefined' || valmin === null ){
              valmin = 0;
            }
            if (qtythis.find("input").attr("step") && qtythis.find("input").attr("step") > 0) {
              var step = parseInt(qtythis.find("input").attr("step"));
            } else {
              var step = 1;
            }
            if (valnum - step >= valmin) {
              qtythis.find("input").val(valnum - step);
              qtythis.closest('tr').find(".cartcol input.hidden_quantity").val(valnum - step);
              qtythis.closest('tr').find(".totalcol").text(get_price_html((valnum - step) * qtythis.closest('tr').data('price')));
              qtythis.find("input").trigger( "qty:change" );
            }
          });
          qtythis.find(".plusqty").on("click", function() {
            var valnum = parseInt(qtythis.find("input").val());
            var valmax = qtythis.find("input").attr('max');
            if( typeof valmax === 'undefined' || valmax === null ){
              valmax = -1;
            }
            if (qtythis.find("input").attr("step") && qtythis.find("input").attr("step") > 0) {
              var step = parseInt(qtythis.find("input").attr("step"));
            } else {
              var step = 1;
            }
            if ((valnum + step <= valmax) || valmax == -1) {
              qtythis.find("input").val(valnum + step);
              qtythis.closest('tr').find(".cartcol input.hidden_quantity").val(valnum + step);
              qtythis.closest('tr').find(".totalcol").text(get_price_html((valnum + step) * qtythis.closest('tr').data('price')));
              qtythis.find("input").trigger( "qty:change" );
            }
          });
        });
      }
      if (sorting == 'yes') {
        var $table = jQuery("#tb_"+ random_id +"").stupidtable();
        if (preorder != '' && preorder != 'custom') {
          var $th_to_sort = $table.find("thead th."+preorder);
          $th_to_sort.stupidsort();
        }
        if ($th_to_sort !== undefined && $th_to_sort !== null) {
          if (preorder_direction != '' && preorder_direction != 'custom') {
            $th_to_sort.stupidsort(preorder_direction);
          } else {
            $th_to_sort.stupidsort('asc');
          }
        }
        $table.bind('aftertablesort', function (event, data) {
            // data.column - the index of the column sorted after a click
            // data.direction - the sorting direction (either asc or desc)
            // $(this) - this table object
            // console.log("The sorting direction: " + data.direction);
            // console.log("The column index: " + data.column);
        });
      }
      jQuery("a#gc_"+ random_id +"_top, a#gc_"+ random_id +"_bottom").on("click", function(event) {
        gclicked = 1;
      });
    });
    jQuery(document).on("submit", "form.vtajaxform2", function(event) {
      event.preventDefault();
      //console.log("wcplpro triggered 1");
      if (jQuery(this).find(".hidden_quantity").val() > 0 ) { // && jQuery(this).closest('tr').find("input.globalcheck").is(":checked")
        // reset the formdata array
        formdata = [];
        formdata.length = 0;
        $formdata = get_form_data(jQuery(this));
        if (jQuery('.add2cartbtn_'+formdata['thisbuttonid']).length > 0){
          jQuery('.add2cartbtn_'+formdata['thisbuttonid']).attr('disabled', 'disabled');
          jQuery('.add2cartbtn_'+formdata['thisbuttonid']).addClass('working');
          //jQuery(".vtspinner_"+ formdata['thisbuttonid']).fadeIn(200);
          jQuery(".vtspinner_"+ formdata['thisbuttonid']).css("display","inline-block");
        }
        numofadded = numofadded + parseInt(jQuery(this).find("input.hidden_quantity").val());
        wcplpro_request(formdata);
      }
      return false;
    });
    if (jQuery(".globalcartbtn.submit").length > 0) {
      jQuery(".globalcartbtn.submit:not(.working)").on("click", function(event) {
        event.preventDefault();
        glob_clicked = 1;
        gclicked = 1;
        numofadded = 0;
        if (jQuery(this).hasClass('working')) { return false; }
        // reset the formdata array
        formdata = [];
        formdata.length = 0;
        var clickthis = jQuery(this);
        var pid = clickthis.attr("id").split("_");
        get_global_cart_id = '';
        get_global_cart_id = pid[1];
        var wcplprotable            = jQuery('#tb_'+pid[1]);
        var random_id               = wcplprotable.data('random');
        var wcplprotable_ajax       = wcplprotable.data('wcplprotable_ajax');
        var cartredirect            = wcplprotable.data('cartredirect');
        var sorting                 = wcplprotable.data('sort');
        var wcplprotable_globalcart = wcplprotable.data('globalcart');
        var preorder                = wcplprotable.data('preorder');
        var preorder_direction      = wcplprotable.data('preorder_direction');
        var position                = jQuery(this).data('position');
        count = 0;
        var ajaxtrigger = 0;
        jQuery("table#tb_"+ random_id +" tr").not(".descrow").each(function(index) {
          if(jQuery(this).find("input.globalcheck").length > 0 && jQuery(this).find("input.globalcheck").is(":checked") && jQuery(this).find("input.hidden_quantity").val() > 0) {
            count = count +1;
            ajaxtrigger = count;
          }
        });
        if (count == 0) { return false; }
        clickthis.addClass('working').attr('disabled', 'disabled');
        jQuery(".vtspinner_"+ position +".vtspinner_"+ pid[1]).stop(true).fadeIn(100, function() {
          // count = jQuery("table#tb_"+ pid[1] +" tr:not('.descrow') input.globalcheck:checked").length;
          console.log("rows: "+ count);
          var trig = 0;
          var promises=[];
          jQuery("table#tb_"+ random_id +" tr").not(".descrow").each(function(index) {
            if(jQuery(this).find("input.globalcheck").length > 0 && jQuery(this).find("input.globalcheck").is(":checked") && jQuery(this).find("input.hidden_quantity").val() > 0) {
              var formobj = jQuery(this).find("form.vtajaxform");
              get_form_data(formobj, index);
              numofadded = numofadded + parseInt(jQuery(this).find("input.hidden_quantity").val());
              // console.log(jQuery("#add2cartbtn_"+vpid[1]).text());
              // execute only when the loop is done
              if (!--ajaxtrigger) {
                var request= wcplpro_request(formdata);
                promises.push(request);
              }
              trig = 1;
            }
          });
          jQuery(document).on('wcplpro_global_add_finished', function() {
            jQuery.when.apply(null, promises).done(function(){
              if (wcplprotable_ajax != 1 || cartredirect == 'yes') {
                if (trig == 1) {
                  if (cartredirect == 'yes') {
                    window.location.href = ""+carturl+"";
                  }
                  if (cartredirect == 'no') {
                    location.reload();
                  }
                }
              }
              if (trig == 0) { jQuery(".vtspinner_"+ pid[1]).stop(true, true).fadeOut(100); }
              if (trig == 1)  {
                jQuery(".added2cartglobal_"+ pid[1]).stop(true).fadeIn(200); jQuery(".added2cartglobal_"+ pid[1]).delay(3000).fadeOut(200);
              }
              if (numofadded > 0) {
                jQuery('#wcplpro_added_to_cart_notification span').text(numofadded);
                jQuery('#wcplpro_added_to_cart_notification').stop(true).slideDown(100, function() {
                  if (jQuery('#wcplpro_added_to_cart_notification').hasClass('autoclose')) {
                    jQuery('#wcplpro_added_to_cart_notification').delay(6000).slideUp(200);
                  }
                  glob_clicked = 0;
                });
              } else {
                glob_clicked = 0;
              }
              clickthis.removeClass('working').removeAttr('disabled');
              numofadded = 0;
              console.log('finished');
              gclicked = 0;
            });
          });
        });
      });
    }
  }
  jQuery(".wcplprotable .qtycol input.input-text.qty").on("input", function() {
    var valmin  =jQuery(this).attr('min');
    var valmax  =jQuery(this).attr('max');
    if( typeof valmin === 'undefined' || valmin === null ){
      valmin = 0;
    }
    if( typeof valmax === 'undefined' || valmax === null ){
      valmax = -1;
    }
    if (parseInt(jQuery(this).val()) < valmin) {
      jQuery(this).val(valmin);
    }
    if (parseInt(jQuery(this).val()) > valmax && valmax != -1) {
      jQuery(this).val(valmax);
    }
    jQuery(this).closest('tr').find(".totalcol").text(get_price_html(jQuery(this).val() * jQuery(this).closest('tr').data('price')));
    jQuery(this).closest('tr').find(".cartcol input.hidden_quantity").val(jQuery(this).val());
    jQuery(this).trigger( "qty:change" );
  });
  if (jQuery('.giftcol').length > 0) {
    jQuery("input.wcplpro_gift_wrap").on("change", function() {
      if(jQuery(this).is(":checked")) {
        jQuery(this).closest('tr').find(".cartcol input.gift_wrap").val("yes");
      } else {
        jQuery(this).closest('tr').find(".cartcol input.gift_wrap").attr("value", "");
      }
    });
  }
  jQuery('input[name="wcplpro_quantity"]').on('qty:change', function() {
    update_global_sum(jQuery(this));
  });
  jQuery('input.globalcheck').on('change', function() {
    update_global_sum(jQuery(this));
  });
  // update global cart button count on page load
  jQuery(window).load(function() {
    if (jQuery('table.wcplprotable').length > 0) {
      jQuery('table.wcplprotable').each(function() {
        update_global_sum(jQuery(this));
      });
    }
  });
  function update_global_sum(object) {
    var random_id = object.closest('table.wcplprotable').data('random');
    var numofchecked = 0;
      jQuery("table#tb_"+random_id+"").find('tr').each(function(row) {
        if (jQuery(this).find('.globalcheck').is(":checked")) {
          if (parseInt(jQuery(this).find('input[name="quantity"]').val()) > 0) {
            numofchecked = numofchecked + parseInt(jQuery(this).find('input[name="quantity"]').val());
          }
        }
      });
    jQuery('#gc_'+ random_id +'_top span.vt_products_count, #gc_'+ random_id +'_bottom span.vt_products_count').text(' ('+ numofchecked +')');
  }
  jQuery(".wcplprotable_selectall_check").on("change", function(event) {
    var said = jQuery(this).attr("id").split("_");
    if(this.checked) {
      jQuery("table#tb_"+ said[1] +" tr").each(function(index) {
        if(jQuery(this).find("input.globalcheck").length > 0) {
          jQuery(this).find("input.globalcheck").attr("checked", "checked");
        }
      });
    } else {
      jQuery("table#tb_"+ said[1] +" tr").each(function(index) {
        if(jQuery(this).find("input.globalcheck").length > 0) {
          jQuery(this).find("input.globalcheck").removeAttr("checked");
        }
      });
    }
    // update global cart count
    update_global_sum(jQuery(this));
  });
  Number.prototype.formatMoney = function(c, d, t){
  var n = this,
      c = isNaN(c = Math.abs(c)) ? 2 : c,
      d = d == undefined ? "." : d,
      t = t == undefined ? "," : t,
      s = n < 0 ? "-" : "",
      i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "",
      j = (j = i.length) > 3 ? j % 3 : 0;
     return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
   };
  function get_price_html(price) {
    price = parseFloat(price).formatMoney(decimal_decimals,decimal_separator,thousand_separator);
    if (currency_pos == 'left') {
      price = currency_symbol + price;
    }
    if (currency_pos == 'right') {
      price = price + currency_symbol;
    }
    if (currency_pos == 'left_space') {
      price = currency_symbol +' '+price;
    }
    if (currency_pos == 'right_space') {
      price = price + ' ' + currency_symbol;
    }
    if (price_display_suffix != '') {
      price = price +' '+ price_display_suffix;
    }
    return price;
  }
  function get_form_data(formobj, i){
    i = typeof i !== 'undefined' ? i : 0;
    if (typeof formdata['variation_id'] === 'undefined') { formdata['variation_id'] = new Array; }
    if (typeof formdata['product_id'] === 'undefined') { formdata['product_id'] = new Array; }
    if (typeof formdata['quantity'] === 'undefined') { formdata['quantity'] = new Array; }
    if (typeof formdata['gift_wrap'] === 'undefined') { formdata['gift_wrap'] = new Array; }
    if (typeof formdata['variations'] === 'undefined') { formdata['variations'] = new Array; }
    if (typeof formdata['wcplprotable_ajax'] === 'undefined') { formdata['wcplprotable_ajax'] = new Array; }
    if (typeof formdata['wcplprotable_globalcart'] === 'undefined') { formdata['wcplprotable_globalcart'] = new Array; }
    if (typeof formdata['cartredirect'] === 'undefined') { formdata['cartredirect'] = new Array; }
    if (typeof formdata['thisbuttonid'] === 'undefined') { formdata['thisbuttonid'] = new Array; }
    formdata['variation_id'].push(formobj.find('input[name="variation_id"]').val());
    formdata['product_id'].push(formobj.find('input[name="product_id"]').val());
    formdata['quantity'].push(formobj.find('input[name="quantity"]').val());
    formdata['gift_wrap'].push(formobj.find('input[name="gift_wrap"]').val());
    formdata['variations'].push(formobj.find('input[name="form_wcplprotable_attribute_json"]').val());
    formdata['wcplprotable_ajax']       = formobj.closest('table.wcplprotable').data('wcplprotable_ajax');
    formdata['wcplprotable_globalcart'] = formobj.closest('table.wcplprotable').data('globalcart');
    formdata['cartredirect']            = formobj.closest('table.wcplprotable').data('cartredirect');
    var thisbuttonid = formobj.find('button.add_to_cart').attr('id').split('_');
    formdata['thisbuttonid'].push(thisbuttonid[1]);
    // formdata['addvtdata'].push(formobj.serialize());
    // formdata['thisid'].push(formobj.attr("data-variation_id"));
    // formdata['thisbutton'] = formobj.find('button.add_to_cart');
    return formdata;
  }
  function wcplpro_request(formdata){
    //console.log("REQUEST 0" + ajaxurl);
    //console.log(JSON.stringify( parseInt(formdata['quantity']) ));
    //console.log(formdata);

    jQuery(".added2cart_"+ formdata['thisbuttonid']).hide();

    var cart_qnt_atual = jQuery(document).find('#cart-'+formdata['product_id']).attr('data-qnt');

    jQuery.ajaxQueue({
          type:"POST",
          url: ajaxurl,
          data: {
            "action" : "add_product_to_cart",
            "product_id" : JSON.stringify(formdata['product_id']),
            "variation_id" : JSON.stringify(formdata['variation_id']),
            "quantity" : JSON.stringify( formdata['quantity'] ),
            "gift_wrap" : JSON.stringify(formdata['gift_wrap'])
          },
          success:function(data){
            //$.ajax($fragment_refresh);
            $.ajax({
                    url: ajaxurl,
                    // url: woocommerce_params.ajax_url,
                    type: 'POST',
                    data: { action: 'woocommerce_get_refreshed_fragments' },
                    success: function( data ) {
                        //console.log("WCPLPRO - AJAX");
                        if ( data && data.fragments ) {

                            window.info = data;
                            window.fragmento = data.fragments;
                            //jQuery(fragmento).find('#cart-5374').attr('data-qnt');
                            //jQuery("#cart-5374").attr('data-qnt');

                            $.each( data.fragments, function( key, value ) {
                                //console.log(value);
                                $(key).replaceWith(value);
                            });
                            if ( $supports_html5_storage ) {
                                sessionStorage.setItem( "wc_fragments", JSON.stringify( data.fragments ) );
                                sessionStorage.setItem( "wc_cart_hash", data.cart_hash );
                            }

                            //console.log('refresh');
                            $('body').trigger( 'wc_fragments_refreshed' );
                            MyObject.atualizaPedidoPeca(formdata['product_id'],formdata['quantity'],cart_qnt_atual);
                        }
                    },
                    error:function(responseTxt, statusTxt, jqXHR){
                          //$('#maincont').html("ERRO: TENTE NOVAMENTE!");
                          console.log("STATUS: "+statusTxt+" - Error: " + jqXHR.status + " " + jqXHR.statusText);
                          //jQuery(".loading-site").hide();
                          //$('.navbar-more-overlay').click();
                          //if ($('body').hasClass('navbar-more-show'))	{
                          //  $('body').toggleClass('navbar-more-show');
                      		//	$('.navbar-more-overlay').closest('li').removeClass('active');
                      	  //	}
                    }
                });
            ///*
            //console.log("REQUEST 1");
            //conditionally perform fragment refresh
            //WC_AJAX::get_refreshed_fragments();
            if (glob_clicked == 0) {
              /*
              jQuery('#wcplpro_added_to_cart_notification span').text(formdata['quantity']);
              jQuery('#wcplpro_added_to_cart_notification').stop(true, true).slideDown(200, function() {
                if (jQuery('#wcplpro_added_to_cart_notification').hasClass('autoclose')) {
                  //jQuery('#wcplpro_added_to_cart_notification').delay(6000).slideUp(200);
                  //jQuery('mobile-sidebar');
                  jQuery('.sidebar-toggle').trigger('click');
                  setTimeout(function () {
                        jQuery('.sidebar-overlay').trigger('click');
                  }, 500);
                }
              });
              */
                /*$.confirm({
                    title: '',
                    content: 'Adicionado ao carrinho com sucesso!',
                    autoClose: 'Ok|3000',
                    buttons: {
                        Ok: function () {}
                    }
                });*/


              /*
              setTimeout(function () {
                    //jQuery('.sidebar-toggle').trigger('click');
                    jQuery('#mini-cart').addClass("open");
              }, 5000);
              */


              jQuery(".vtspinner_"+ formdata['thisbuttonid']).fadeOut(200, function() {

                //console.log(formdata['thisbuttonid']);
                //console.log(formdata['quantity']);
                //jQuery(fragmento).find('cart-5374')
                jQuery('body').trigger( 'wcplpro_global_add_finished' );

              });



                  /*
                  //jQuery( document ).ajaxComplete(function( event, xhr, settings ) {
                    setTimeout(function () {

                        var cart_qnt_new = jQuery(document).find('#cart-'+formdata['product_id']).attr('data-qnt');
                        var enviado_qnt = formdata['quantity'];

                        console.log('qnt new : '+parseInt(cart_qnt_new));
                        console.log('qnt atual : '+parseInt(cart_qnt_atual));
                        console.log('enviado: '+parseInt(enviado_qnt));

                        if( ( parseInt(enviado_qnt) + parseInt(cart_qnt_atual) ) > parseInt(cart_qnt_new) ){

                            console.log('ADD ERROR');
                            jQuery('#added2cart_5374 .added2cart').html('&#10008;')
                            jQuery('#added2cart_5374').css('background-color','#000');


                        }else{

                            console.log('ADD SUCCESS');
                            jQuery('#added2cart_5374 .added2cart').html('&#10004;')
                            jQuery('#added2cart_5374').css('background-color','#4e6d4e');

                        }

                    },10);
                  //});
                  */



              //*/

              //var habilita_botao = jQuery('.widget_shopping_cart_content').find('#cart-684');
              //.widget_shopping_cart_content .buttons


              jQuery(".added2cart_"+ formdata['thisbuttonid']).fadeIn(200, function() {

                //jQuery("#added2cart_"+ formdata['thisbuttonid']).delay(10000).fadeOut(1000);
                //jQuery("#added2cart_"+ formdata['thisbuttonid']);

                if (jQuery('.add2cartbtn_'+formdata['thisbuttonid']).length > 0){
                  jQuery('.add2cartbtn_'+formdata['thisbuttonid']).removeAttr('disabled');
                  jQuery('.add2cartbtn_'+formdata['thisbuttonid']).removeClass('working');
                }
                 //console.log('ajax: '+ wcplprotable_ajax);
                 //console.log('ajax: '+ wcplprotable_globalcart);
                // && (wcplprotable_globalcart == 1 || wcplprotable_globalcart == 2)
                if ((formdata['wcplprotable_ajax'] != 1 ) || formdata['cartredirect'] == 'yes') {
                    if (formdata['cartredirect'] == 'yes') {
                      //window.location.href = ""+carturl+"";
                    }
                    if (formdata['cartredirect'] == 'no') {
                      //location.reload();
                    }
                }
              });
              jQuery(".added2cart_"+ formdata['thisbuttonid']).hide();
            }
            if (glob_clicked == 1 || gclicked == 1) {
              jQuery(".vtspinner_"+ get_global_cart_id).fadeOut(200, function() {
                jQuery('body').trigger( 'wcplpro_global_add_finished' );
              });
            }

            /*
              setTimeout(function () {
                    if(!window["cart-684"]){
                        console.log('nao existe');
                        jQuery('.widget_shopping_cart_content .buttons').attr('style', 'display:none !important');
                      }else if(window["cart-684"]){
                        console.log('existe');
                        jQuery('.widget_shopping_cart_content .buttons').attr('style', 'display:block !important');
                      }
              }, 500);
            */

            //*/
          },
          error: function(data) {
            console.log("ERRO");
            console.log(data);
          }
        });
  }
  (function($) {
    var ajaxQueue = $({});
    $.ajaxQueue = function(ajaxOpts) {
      var oldComplete = ajaxOpts.complete;
      ajaxQueue.queue(function(next) {
        ajaxOpts.complete = function() {
          if (oldComplete) oldComplete.apply(this, arguments);
          next();
        };
        $.ajax(ajaxOpts);
      });
    };
  })(jQuery);
});
