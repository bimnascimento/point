jQuery(document).ready(function(){jQuery('[class^="rating_form_"]').each(function(){var i=jQuery(this);if(jQuery("head").find("#rating-form-"+i.attr("class").replace("rating_form_","")+"-css").length===0){var h=document.createElement("link");h.rel="stylesheet";h.id="rating-form-"+i.attr("class").replace("rating_form_","")+"-css";h.href=rating_form_script.uploadurl+"/rating-form/css/rating_form_"+i.attr("class").replace("rating_form_","")+".css?ver="+rating_form_script.pluginversion;h.type="text/css";h.media="all";document.head.appendChild(h)}setTimeout(function(){jQuery(i).removeAttr("style")},1000)});var c="#rating_form_add_edit";var e=jQuery(c+" .rating_form");var f=e.find('[id^="rate_"]');jQuery(c+' input[type="text"]').keydown(function(h){if(h.keyCode==13){jQuery(this).change();h.preventDefault();return false}});jQuery(c+" #time").change(function(h){if(jQuery(this)[0].selectedIndex==1){jQuery(c+" #time_custom").show()}else{jQuery(c+" #time_custom").hide()}});jQuery(c+" #options #type").change(function(h){if(jQuery(this)[0].selectedIndex===0&&jQuery(this).val()==="0"){jQuery(c+" #options .switchToCustom").show()}else{jQuery(c+" #options .switchToCustom").hide()}});jQuery(c+" #time_custom input").change(function(h){jQuery(c+" #time option").eq(1).val(jQuery(this).val())});jQuery(c+" .radio").on("change",function(){jQuery(c+" .radio").not(this).prop("checked",false)});jQuery("#rating_form_add_edit #rfToolsIC input:button").click(function(){if(jQuery(this).parent().find("input:checkbox").prop("checked")){jQuery(this).parent().find("input:checkbox").prop("checked",false)}else{jQuery(this).parent().find("input:checkbox").prop("checked",true)}});jQuery(c+" #max").change(function(){var h=jQuery(this).val();f.slice(0,h).removeClass("hide").addClass("show");f.slice(h,10).removeClass("show").addClass("hide");jQuery("#texts tr").slice(0,h).removeClass("hide").addClass("show");jQuery("#texts tr").slice(h,10).removeClass("show").addClass("hide");jQuery(".rating_score").removeClass("hide").html("0/"+h);if(h==1){jQuery(".rating_score").addClass("hide")}});e.on("mouseenter",'[id^="rate_"]',function(){if(jQuery(this).parents().eq(1).find(".rating_form_result").length===0){itemParent=jQuery(this).parent();item=itemParent.find('[id^="rate_"]');rtl_enabled=itemParent.parent().attr("dir")=="rtl"?true:false;rtl_half=rtl_enabled?(jQuery(this).find("img").length>0?"-half":"-rtl-half"):"-half";indexDefItems=itemParent.find(".def").length;indexHalfItem=itemParent.find('[class*="'+rtl_half+'"]').index();indexHalfItem=indexHalfItem>-1?(rtl_enabled?(indexHalfItem<=2?(indexDefItems-indexHalfItem):(indexHalfItem-indexDefItems)):indexHalfItem):-1;halfIconClass=indexHalfItem==-1?"":itemParent.find('[class*="'+rtl_half+'"]').attr("class").split(" ")[0];indexFullItems=(item.length-item.not(".hover").length);indexAllItems=item.length;var j;item.removeClass("hover");if(item.attr("title")){jQuery(this).append('<div class="tooltip">'+jQuery(this).attr("title")+"</div>");if(jQuery(this).css("padding-right").replace(/\D/g,"")===0){j=(jQuery(this).width()/2+jQuery(this).outerWidth()-jQuery(this).width())}else{if(jQuery(this).css("padding-left").replace(/\D/g,"")===0){j=(jQuery(this).width()/2)}else{j=(jQuery(this).outerWidth()/2)}}var l=(jQuery(this).find(".tooltip").outerWidth()/2);var h=jQuery(this).find(".tooltip").css("top").replace(/\D/g,"");jQuery(this).find(".tooltip").css({top:jQuery(this).position().top-h+"px",left:jQuery(this).position().left-l+j+"px"});jQuery(this).attr("title","")}if(jQuery(this).find("img").length>0){var i=jQuery(this).find("img").attr("src");var k=i.substring(0,i.lastIndexOf("/")+1);jQuery(this).find("img").attr("src",k+"custom-full.png");if(rtl_enabled){jQuery(this).prevAll().find("img").attr("src",k+"custom-empty.png")}else{jQuery(this).nextAll().find("img").attr("src",k+"custom-empty.png")}if(indexAllItems>3){if(rtl_enabled){jQuery(this).nextAll().find("img").attr("src",k+"custom-full.png")}else{jQuery(this).prevAll().find("img").attr("src",k+"custom-full.png")}}}if(indexHalfItem>-1){item.eq(indexHalfItem).attr("class",halfIconClass.replace(rtl_half,""))}if(itemParent.hasClass("empty_on")){jQuery(this).attr("class",jQuery(this).attr("class").replace("-empty",""));if(rtl_enabled){jQuery(this).nextAll().not(".def").attr("class",jQuery(this).attr("class").replace("-empty",""));jQuery(this).prevAll().not(".def").attr("class",jQuery(this).attr("class")+"-empty")}else{jQuery(this).prevAll().not(".def").attr("class",item.attr("class").replace("-empty",""));jQuery(this).nextAll().not(".def").attr("class",item.attr("class")+"-empty")}}jQuery(this).addClass("hover");if(indexAllItems>3){if(rtl_enabled){jQuery(this).nextAll().not(".def").addClass("hover")}else{jQuery(this).prevAll().not(".def").addClass("hover")}}}});e.on("mouseleave",'[id^="rate_"]',function(){if(jQuery(this).parents().eq(1).find(".rating_form_result").length===0){itemParent=jQuery(this).parent();item=itemParent.find('[id^="rate_"]').not(".def");item.removeClass("hover");if(jQuery(this).find("img").length>0){var h=jQuery(this).find("img").attr("src");var i=h.substring(0,h.lastIndexOf("/")+1);item.find("img").attr("src",i+"custom-empty.png");if(rtl_enabled){item.slice((indexAllItems-indexFullItems),indexAllItems).find("img").attr("src",i+"custom-full.png")}else{item.slice(0,indexFullItems).find("img").attr("src",i+"custom-full.png")}if(indexHalfItem>-1){item.eq(indexHalfItem).find("img").attr("src",i+"custom-half.png")}}if(itemParent.hasClass("empty_on")){item.attr("class",item.attr("class").replace("-empty",""));if(rtl_enabled){item.slice((indexAllItems-indexFullItems),indexAllItems).attr("class",item.attr("class").replace("-empty",""));item.slice(0,(indexAllItems-indexFullItems)).attr("class",item.attr("class")+"-empty")}else{item.slice(0,indexFullItems).attr("class",item.attr("class").replace("-empty",""));item.slice(indexFullItems,indexAllItems).attr("class",item.attr("class")+"-empty")}}if(indexHalfItem>-1){item.eq(indexHalfItem).attr("class",halfIconClass)}if(rtl_enabled){item.slice((indexAllItems-indexFullItems),indexAllItems).addClass("hover")}else{item.slice(0,indexFullItems).addClass("hover")}if(jQuery(this).parent().find(".tooltip")){jQuery(this).attr("title",jQuery(this).parent().find(".tooltip").html());jQuery(this).parent().find(".tooltip").remove()}}});jQuery(c+" #texts .text_select select").change(function(){jQuery("#texts .text_select select").each(function(h){jQuery(f).eq(h).attr("title",jQuery("#texts .text_select select :selected").eq(h).text())})});jQuery(".rf_wrap #block_ip_new").submit(function(h){jQuery.ajax({type:jQuery(this).attr("method"),url:jQuery(this).attr("action"),data:jQuery(this).serialize(),success:function(i){jQuery(".rf_wrap #block_ip_new").append(i);jQuery(".rf_wrap #block_ip_new").find(".updated, .error").delay(3250).fadeOut()},error:function(i){console.log(i)}});h.preventDefault()});jQuery(".rf_wrap #block_ip_edit tr").on("click",".block_ip_edit",function(){if(jQuery(this).parent().closest("tr").find("span.block_span").is(":visible")){jQuery(this).parent().closest("tr").find("span.block_span").hide();jQuery(this).parent().closest("tr").find("input").show()}else{if(jQuery(this).parent().closest("tr").find("input").is(":visible")){jQuery(this).parent().closest("tr").find("span.block_span").show();jQuery(this).parent().closest("tr").find("input").hide()}}return false});jQuery(".rf_wrap #block_ip_edit tr").on("click",".block_ip_edit_submit",function(i){var h=jQuery(this).parent().closest("tr");if(h.find("span.block_span").eq(0).text()!==h.find("input").not(".block_ip_edit_submit").eq(0).val()||h.find("span.block_span").eq(1).text()!==h.find("input").not(".block_ip_edit_submit").eq(1).val()){jQuery.ajax({type:jQuery(".rf_wrap #block_ip_new").attr("method"),url:jQuery(".rf_wrap #block_ip_new").attr("action"),data:{action:"rating_form_block_ip_edit",ip:h.find("span.block_span").eq(0).text(),edited_ip:h.find("input").not(".block_ip_edit_submit").eq(0).val(),reason:h.find("input").not(".block_ip_edit_submit").eq(1).val()},success:function(j){jQuery(j).insertBefore(h.find(".row-actions"));h.find(".ip_edited, .ip_error").delay(3250).fadeOut();if(h.find("input").not(".block_ip_edit_submit").eq(0).val()!==""){h.find("input").hide();h.find("span.block_span").eq(0).text(h.find("input").not(".block_ip_edit_submit").eq(0).val()).show();h.find("span.block_span").eq(1).text(h.find("input").not(".block_ip_edit_submit").eq(1).val()).show()}},error:function(j){console.log(j)}})}else{if(h.find("span.block_span").eq(0).text()==h.find("input").not(".block_ip_edit_submit").eq(0).val()||h.find("span.block_span").eq(1).text()==h.find("input").not(".block_ip_edit_submit").eq(1).val()){h.find("input").not(".block_ip_edit_submit").hide();h.find("span.block_span").eq(0).show();h.find("span.block_span").eq(1).show()}}i.preventDefault()});jQuery(c+" #font_size_btn").click(function(){jQuery(this).parent().find("#font_size").change();return false});jQuery(c+" #font_size_text_btn").click(function(){jQuery(this).parent().find("#font_size_text").change();return false});jQuery(c+" #font_size").change(function(){var h=jQuery(this).val();jQuery(c+" #admin_rf_add_new_css").html(g("","","","",h));d("","","","",h);a("","","","","","","","",h)});jQuery(c+" #font_size_text").change(function(){var h=jQuery(this).val();jQuery(c+" #admin_rf_add_new_css").html(g("","","","","",h));d("","","","","",h);a("","","","","","","","","",h)});jQuery(".rf_wrap .datePicker").datepicker({dateFormat:"yy-mm-dd"});if(jQuery().wpColorPicker){jQuery(c+" #font_color").wpColorPicker({change:function(h,i){jQuery(c+" #admin_rf_add_new_css").html(g(i.color,""));d(i.color)}});jQuery(c+" #font_hover_color").wpColorPicker({change:function(h,i){jQuery(c+" #admin_rf_add_new_css").html(g("",i.color));d("",i.color)}});jQuery(c+" #font_color_text").wpColorPicker({change:function(h,i){jQuery(c+" #admin_rf_add_new_css").html(g("","",i.color));d("","",i.color)}});jQuery(c+" #background_def_text").wpColorPicker({change:function(h,i){jQuery(c+" #admin_rf_add_new_css").html(g("","","",i.color));d("","","",i.color)}});jQuery(c+" #tu_font_color").wpColorPicker({change:function(h,i){jQuery(c+" #admin_rf_add_new_css").html(g(i.color));a(i.color)}});jQuery(c+" #tu_font_hover_color").wpColorPicker({change:function(h,i){jQuery(c+" #admin_rf_add_new_css").html(g("",i.color));a("",i.color)}});jQuery(c+" #tu_font_color_text").wpColorPicker({change:function(h,i){jQuery(c+" #admin_rf_add_new_css").html(g("","",i.color));a("","",i.color)}});jQuery(c+" #tu_background_def_text").wpColorPicker({change:function(h,i){jQuery(c+" #admin_rf_add_new_css").html(g("","","",i.color));a("","","",i.color)}});jQuery(c+" #td_font_color").wpColorPicker({change:function(h,i){jQuery(c+" #admin_rf_add_new_css").html(g("","","","",i.color));a("","","","",i.color)}});jQuery(c+" #td_font_hover_color").wpColorPicker({change:function(h,i){jQuery(c+" #admin_rf_add_new_css").html(g("","","","","",i.color));a("","","","","",i.color)}});jQuery(c+" #td_font_color_text").wpColorPicker({change:function(h,i){jQuery(c+" #admin_rf_add_new_css").html(g("","","","","","",i.color));a("","","","","","",i.color)}});jQuery(c+" #td_background_def_text").wpColorPicker({change:function(h,i){jQuery(c+" #admin_rf_add_new_css").html(g("","","","","","","",i.color));a("","","","","","","",i.color)}})}function g(C,i,v,j,q,t,n,B,x,k){var p="";var h=jQuery(c).find("#tu_font_color").length?true:false;if(h){var u=(C?C:jQuery(c+" #tu_font_color").val());var r=(i?i:jQuery(c+" #tu_font_hover_color").val());var w=(v?v:jQuery(c+" #tu_font_color_text").val());var m=(j?j:jQuery(c+" #tu_background_def_text").val());var y=(q?q:jQuery(c+" #td_font_color").val());var z=(t?t:jQuery(c+" #td_font_hover_color").val());var l=(n?n:jQuery(c+" #td_font_color_text").val());var A=(B?B:jQuery(c+" #td_background_def_text").val());var s=(x?x:jQuery(c+" #font_size").val());var o=(k?k:jQuery(c+" #font_size_text").val());p+='#rating_form_add_edit .rating_form [id^="rate_"] { font-size: '+s+"; }\n";p+="#rating_form_add_edit .rating_form li.up_rated_txt { color: "+w+"; background-color: "+m+"; font-size: "+o+"; }\n";p+="#rating_form_add_edit .rating_form li.down_rated_txt { color: "+l+"; background-color: "+A+"; font-size: "+o+"; }\n";p+="#rating_form_add_edit .rating_form .up_rated { color: "+u+"; }\n";p+="#rating_form_add_edit .rating_form .up_rated.hover { color: "+r+"; }\n";p+="#rating_form_add_edit .rating_form .down_rated { color: "+y+"; }\n";p+="#rating_form_add_edit .rating_form .down_rated.hover { color: "+z+"; }\n";p+="#rating_form_add_edit .rating_form li.def { font-size: "+o+"; }\n"}else{C=(C?C:jQuery(c+" #font_color").val());i=(i?i:jQuery(c+" #font_hover_color").val());v=(v?v:jQuery(c+" #font_color_text").val());j=(j?j:jQuery(c+" #background_def_text").val());q=(q?q:jQuery(c+" #font_size").val());t=(t?t:jQuery(c+" #font_size_text").val());p+="#rating_form_add_edit .rating_form li:not(.def) { color: "+C+"; font-size: "+q+"; }\n";p+="#rating_form_add_edit .rating_form li.hover { color: "+i+"; }\n";p+="#rating_form_add_edit .rating_form li.def { color: "+v+"; background-color: "+j+"; font-size: "+t+"; }\n"}return p}function d(i,k,p,m,o,n){var h=c+" #admin_rf_new_style";var l="";var j=b("style");i=(i?i:jQuery(c+" #font_color").val());k=(k?k:jQuery(c+" #font_hover_color").val());p=(p?p:jQuery(c+" #font_color_text").val());m=(m?m:jQuery(c+" #background_def_text").val());o=(o?o:jQuery(c+" #font_size").val());n=(n?n:jQuery(c+" #font_size_text").val());l+="<span>.rating_form_"+j+' .rating_form [id^="rate_"]</span><br />&emsp;color: <b>'+i+"</b>;<br />&emsp;font-size: <b>"+o+"</b>;<br /><br />";l+="<span>.rating_form_"+j+' .rating_form [id^="rate_"].hover</span><br />&emsp;color: <b>'+k+"</b>;<br /><br />";l+="<span>.rating_form_"+j+" .rating_form .def</span><br />&emsp;color: <b>"+p+"</b>;<br />&emsp;background-color: <b>"+m+"</b>;<br />&emsp;font-size: <b>"+n+"</b>;<br /><br />";jQuery(h).show();return jQuery(h).find(".admin_rf_style_inner").html(l)}function a(o,m,r,k,i,q,p,n,t,s){var h=c+" #admin_rf_new_style";var l="";var j=b("style");o=(o?o:jQuery(c+" #tu_font_color").val());m=(m?m:jQuery(c+" #tu_font_hover_color").val());r=(r?r:jQuery(c+" #tu_font_color_text").val());k=(k?k:jQuery(c+" #tu_background_def_text").val());i=(i?i:jQuery(c+" #td_font_color").val());q=(q?q:jQuery(c+" #td_font_hover_color").val());p=(p?p:jQuery(c+" #td_font_color_text").val());n=(n?n:jQuery(c+" #td_background_def_text").val());t=(t?t:jQuery(c+" #font_size").val());s=(s?s:jQuery(c+" #font_size_text").val());l+="<span>.rating_form_"+j+' .rating_form [id^="rate_"]</span><br />&emsp;font-size: <b>'+t+"</b>;<br /><br />";l+="<span>.rating_form_"+j+" .rating_form .def</span><br />&emsp;font-size: <b>"+s+"</b>;<br /><br />";l+="<span>.rating_form_"+j+" .rating_form li.up_rated_txt</span><br />&emsp;color: <b>"+r+"</b>;<br />&emsp;background-color: <b>"+k+"</b>;<br />&emsp;font-size: <b>"+s+"</b>;<br /><br />";l+="<span>.rating_form_"+j+" .rating_form li.down_rated_txt</span><br />&emsp;color: <b>"+p+"</b>;<br />&emsp;background-color: <b>"+n+"</b>;<br />&emsp;font-size: <b>"+s+"</b>;<br /><br />";l+="<span>.rating_form_"+j+" .rating_form .up_rated</span><br />&emsp;color: <b>"+o+"</b>;<br /><br />";l+="<span>.rating_form_"+j+" .rating_form .up_rated.hover</span><br />&emsp;color: <b>"+m+"</b>;<br /><br />";l+="<span>.rating_form_"+j+" .rating_form .down_rated</span><br />&emsp;color: <b>"+i+"</b>;<br /><br />";l+="<span>.rating_form_"+j+" .rating_form .down_rated.hover</span><br />&emsp;color: <b>"+q+"</b>;<br /><br />";jQuery(h).show();return jQuery(h).find(".admin_rf_style_inner").html(l)}var b=function b(h){var l=decodeURIComponent(window.location.search.substring(1)),k=l.split("&"),m,j;for(j=0;j<k.length;j++){m=k[j].split("=");if(m[0]===h){return m[1]===undefined?true:m[1]}}}});