!function(){function e(e){return document.getElementById(e)}function r(e){for(var r,a=e.target.files||e.dataTransfer.files,i=0;r=a[i];i++){var n=jQuery(this).attr("data-val");t(r,n)}}function a(e){for(var r,a=e.target.files||e.dataTransfer.files,t=0;r=a[t];t++){var n=jQuery(this).attr("data-val");i(r,n)}}function i(e,r){var a=e.name,i=a.lastIndexOf("."),i=a.substring(i+1);if("csv"!=i.toLowerCase())return alert("Please upload csv files only"),!1;jQuery(".arf_preset_field_content_wrapper_"+r).find("button.btn_2").css("opacity","0.5");var t=new XMLHttpRequest,n=(new Date).getTime(),s=e.name.lastIndexOf("."),l=e.name.substring(s+1),o=/.*(?=\.)/.exec(e.name),f=n+"_"+o;fname=f.replace(/[^\w\s]/gi,"").replace(/ /g,"")+"."+l;var d=jQuery(".arf_ajax_url").val();t.open("POST",d+"?action=arf_add_new_preset",!0),t.setRequestHeader("X_FILENAME",fname),t.setRequestHeader("X-FILENAME",fname),t.send(e),t.onreadystatechange=function(){if(4==t.readyState&&200==t.status){jQuery(".arf_preset_field_content_wrapper_"+r).find("button.btn_2").css("opacity","");var e=t.responseText;jQuery(".arf_preset_data_"+r).attr("db_file_name",e)}}}function t(e,r){if(e.type.indexOf("image")<0)return alert("Please upload image files only"),!1;if("form_bg"==r?jQuery("#ajax_form_loader").show():"submit_hover_bg"==r?jQuery("#ajax_submit_hover_loader").show():jQuery("#ajax_submit_loader").show(),!(location.host.indexOf("sitepointstatic")>=0)){var a=new XMLHttpRequest,i=(new Date).getTime(),t=e.name.lastIndexOf("."),n=e.name.substring(t+1),s=/.*(?=\.)/.exec(e.name),l=i+"_"+s;fname=l.replace(/[^\w\s]/gi,"").replace(/ /g,"")+"."+n;var o=(jQuery("#arfmainformurl").val(),jQuery("#arffiledragurl").val());a.open("POST",o+"/js/filedrag/upload.php",!0),a.setRequestHeader("X_FILENAME",fname),a.setRequestHeader("X-FILENAME",fname),a.send(e),a.onreadystatechange=function(){if(4==a.readyState&&200==a.status){var e=a.responseText,i="";if("undefined"!=typeof r&&(i=r.split("_smiley_image_")),"form_bg"==r&&"undefined"!=r)document.getElementById("imagename_form").value=e,change_form_bg_img();else if("submit_hover_bg"==r&&"undefined"!=r)document.getElementById("imagename_submit_hover").value=e,change_submit_hover_img();else if("arf_smiley_add"==i[0]&&"undefined"!=r){var t=i[1]+"_arf_smiley_"+e;document.getElementById("arf_smiley_image_name").value=t,change_smiley_image()}else document.getElementById("imagename").value=e,change_submit_img()}}}}window.File&&window.FileList&&window.FileReader,jQuery(document).on("click",".original",function(){var a=e(jQuery(this).attr("id"));a.addEventListener("change",r,!1);var i=new XMLHttpRequest;i.upload}),jQuery(document).on("click",".arf_preset_data",function(){var r=e(jQuery(this).attr("id"));r.addEventListener("change",a,!1);var i=new XMLHttpRequest;i.upload}),jQuery(".iframe_original_btn").click(function(){var e=jQuery("#arfmainform_bg_img").val();if(""!=e)return!1;var r=jQuery(this).attr("data-id").replace("div_","");jQuery("#"+r+"_iframe").contents().find("#fileselect").click();var a=jQuery(this).attr("data-id");jQuery("#arf_field_"+a+"_container").find(".help-block").empty(),jQuery("#progress_"+r).hide();var i=jQuery("#"+r+"_iframe").contents().find("#fileselect").val();if(void 0!==i&&""!=i){var t=jQuery("#file_types_"+r).val();types_arr=t.split(",");var a=jQuery(this).attr("data-id"),n=i.replace(/C:\\fakepath\\/i,""),s=jQuery(this).attr("data-form-id"),l=(new Date).getTime(),o=n.lastIndexOf("."),f=n.substring(o+1),d=/.*(?=\.)/.exec(n),_=l+"_"+d,u=_.replace(/[^\w\s]/gi,"").replace(" ","")+"."+f,m=r,c=0,p=(jQuery("#arfmainformurl").val(),jQuery("#arffiledragurl").val()),v="",y=9,j="Internet Explorer";"arfmfbi"==r?jQuery("#ajax_form_loader").show():jQuery("#ajax_submit_loader").show(),jQuery("#"+r+"_iframe").contents().find("form").attr("action",p+"/js/filedrag/upload.php?frm="+s+"&field_id="+m+"&fname="+u+"&file_type="+v+"&types_arr="+types_arr+"&is_preview="+c+"&ie_version="+y+"&browser="+j),jQuery("#"+r+"_iframe").contents().find("form").submit(),jQuery("#div_"+r).css("margin-top","-4px"),jQuery("#info_"+r).css("display","inline-block"),jQuery("#info_"+r+" #file_name").html(i.replace(/C:\\fakepath\\/i,"")),jQuery("#info_"+r+" .percent").html("").show(),jQuery("#info_"+r+" #percent").html("Uploading..."),jQuery("#progress_"+r+" div.bar").animate({width:"100%"},"slow");var Q=setInterval(function(){if(jQuery("#"+r+"_iframe").contents()){var e=jQuery("#"+r+"_iframe").contents()?jQuery("#"+r+"_iframe").contents().find(".uploaded").length:0;e>0&&(clearInterval(Q),jQuery("#progress_"+r).removeClass("active"),jQuery("#imagename_form").val(u),jQuery("#form_bg_img_div").css("background","none"),jQuery("#form_bg_img_div").css("border","0px"),jQuery("#form_bg_img_div").css("padding","0px"),jQuery("#form_bg_img_div").css("box-shadow","none"),change_form_bg_img(),jQuery("#div_"+r).hide(),jQuery("#remove_"+r).css("display","block"),jQuery("#div_"+r).css("margin-top","0px"),jQuery("#remove_"+r).css("margin-top","-4px"),jQuery("#"+r+"_iframe_div").html(" ").append('<iframe id="'+r+'_iframe" src="'+p+'/core/views/iframe.php"></iframe>'));var i=jQuery("#"+r+"_iframe").contents()?jQuery("#"+r+"_iframe").contents().find(".error_upload").length:0;if(i>0){clearInterval(Q);var t=a;if(jQuery('input[name="file'+t+'"]').attr("data-file-valid","false"),"undefined"!=typeof __ARFERR)var n=__ARFERR;else var n="Sorry, this file type is not permitted for security reasons.";if(void 0!==jQuery("#field_"+r).attr("data-invalid-message")&&""!=jQuery("#field_"+r).attr("data-invalid-message"))var s=jQuery("#field_"+r).attr("data-invalid-message");else var s=n;jQuery("#arf_field_"+t+"_container").removeClass("success");var l=jQuery("#arf_field_"+t+"_container .controls"),o=l.parents(".control-group").first(),f=o.find(".help-block").first(),d=l.closest("form").find("#form_id").val(),_="advance"==jQuery("#form_tooltip_error_"+d).val()?"advance":"normal";"advance"==_?arf_show_tooltip(o,f,s):f.length?(f=jQuery("<ul><li>"+s+"</li></ul>"),o.find(".controls .help-block").append(f)):(f=jQuery('<div class="help-block"><ul><li>'+s+"</li></ul></div>"),o.find(".controls").append(f)),jQuery("#"+r+"_iframe_div").html(" ").append('<iframe id="'+r+'_iframe" src="'+p+'/core/views/iframe.php"></iframe>')}}},100)}}),jQuery(".iframe_submit_original_btn").click(function(){var e=jQuery("#arfsubmitbuttonimagesetting").val();if(""!=e)return!1;var r=jQuery(this).attr("data-id").replace("div_","");jQuery("#"+r+"_iframe").contents().find("#fileselect").click();var a=jQuery(this).attr("data-id");jQuery("#arf_field_"+a+"_container").find(".help-block").empty();var i=jQuery("#"+r+"_iframe").contents().find("#fileselect").val();if(""!=i&&void 0!==i){var t=jQuery("#file_types_"+r).val();types_arr=t.split(",");var a=jQuery(this).attr("data-id"),n=i.replace(/C:\\fakepath\\/i,""),s=jQuery(this).attr("data-form-id"),l=(new Date).getTime(),o=n.lastIndexOf("."),f=n.substring(o+1),d=/.*(?=\.)/.exec(n),_=l+"_"+d,u=_.replace(/[^\w\s]/gi,"").replace(" ","")+"."+f,m=r,c=0,p=(jQuery("#arfmainformurl").val(),jQuery("#arffiledragurl").val()),v="",y=9,j="Internet Explorer";jQuery("#"+r+"_iframe").contents().find("form").attr("action",p+"/js/filedrag/upload.php?frm="+s+"&field_id="+m+"&fname="+u+"&file_type="+v+"&types_arr="+types_arr+"&is_preview="+c+"&ie_version="+y+"&browser="+j),jQuery("#"+r+"_iframe").contents().find("form").submit(),jQuery("#div_"+r).css("margin-top","-4px"),jQuery("#info_"+r).css("display","inline-block"),jQuery("#info_"+r+" #file_name").html(i.replace(/C:\\fakepath\\/i,"")),jQuery("#info_"+r+" .percent").html("").show(),jQuery("#info_"+r+" #percent").html("Uploading..."),jQuery("#progress_"+r+" div.bar").animate({width:"100%"},"slow");var Q=setInterval(function(){if(jQuery("#"+r+"_iframe").contents()){var e=jQuery("#"+r+"_iframe").contents().find(".uploaded").length;e>0&&(clearInterval(Q),jQuery("#progress_"+r).removeClass("active"),jQuery("#imagename").val(u),jQuery("#submit_btn_img_div").css("background","none"),jQuery("#submit_btn_img_div").css("border","0px"),jQuery("#submit_btn_img_div").css("padding","0px"),jQuery("#submit_btn_img_div").css("box-shadow","none"),change_submit_img(),jQuery("#div_"+r).hide(),jQuery("#remove_"+r).css("display","block"),jQuery("#div_"+r).css("margin-top","0px"),jQuery("#remove_"+r).css("margin-top","-4px"),jQuery("#info_"+r+" #percent").html("File Uploaded"),jQuery('input[name="file'+a+'"]').val(d),jQuery('input[name="item_meta['+a+']"]').val(d),jQuery('input[name="file'+a+'"]').attr("data-file-valid","true"),jQuery("#"+r+"_iframe_div").html(" ").append('<iframe id="'+r+'_iframe" src="'+p+'/core/views/iframe.php"></iframe>'));var i=jQuery("#"+r+"_iframe").contents().find(".error_upload").length;if(i>0){clearInterval(Q);var t=a;if(jQuery('input[name="file'+t+'"]').attr("data-file-valid","false"),"undefined"!=typeof __ARFERR)var n=__ARFERR;else var n="Sorry, this file type is not permitted for security reasons.";if(void 0!==jQuery("#field_"+r).attr("data-invalid-message")&&""!=jQuery("#field_"+r).attr("data-invalid-message"))var s=jQuery("#field_"+r).attr("data-invalid-message");else var s=n;jQuery("#arf_field_"+t+"_container").removeClass("success");var l=jQuery("#arf_field_"+t+"_container .controls"),o=l.parents(".control-group").first(),f=o.find(".help-block").first(),_=l.closest("form").find("#form_id").val(),m="advance"==jQuery("#form_tooltip_error_"+_).val()?"advance":"normal";"advance"==m?arf_show_tooltip(o,f,s):f.length?(f=jQuery("<ul><li>"+s+"</li></ul>"),o.find(".controls .help-block").append(f)):(f=jQuery('<div class="help-block"><ul><li>'+s+"</li></ul></div>"),o.find(".controls").append(f)),jQuery("#"+r+"_iframe_div").html(" ").append('<iframe id="'+r+'_iframe" src="'+p+'/core/views/iframe.php"></iframe>')}}},100)}}),jQuery(".iframe_submit_hover_original_btn").click(function(){var e=jQuery("#arfsubmithoverbuttonimagesetting").val();if(""!=e)return!1;var r=jQuery(this).attr("data-id").replace("div_","");jQuery("#"+r+"_iframe").contents().find("#fileselect").click();var a=jQuery(this).attr("data-id");jQuery("#arf_field_"+a+"_container").find(".help-block").empty();var i=jQuery("#"+r+"_iframe").contents().find("#fileselect").val();if(""!=i&&void 0!==i){var t=jQuery("#file_types_"+r).val();types_arr=t.split(",");var a=jQuery(this).attr("data-id"),n=i.replace(/C:\\fakepath\\/i,""),s=jQuery(this).attr("data-form-id"),l=(new Date).getTime(),o=n.lastIndexOf("."),f=n.substring(o+1),d=/.*(?=\.)/.exec(n),_=l+"_"+d,u=_.replace(/[^\w\s]/gi,"").replace(" ","")+"."+f,m=r,c=0,p=(jQuery("#arfmainformurl").val(),jQuery("#arffiledragurl").val()),v="",y=9,j="Internet Explorer";jQuery("#"+r+"_iframe").contents().find("form").attr("action",p+"/js/filedrag/upload.php?frm="+s+"&field_id="+m+"&fname="+u+"&file_type="+v+"&types_arr="+types_arr+"&is_preview="+c+"&ie_version="+y+"&browser="+j),jQuery("#"+r+"_iframe").contents().find("form").submit(),jQuery("#div_"+r).css("margin-top","-4px"),jQuery("#info_"+r).css("display","inline-block"),jQuery("#info_"+r+" #file_name").html(i.replace(/C:\\fakepath\\/i,"")),jQuery("#info_"+r+" .percent").html("").show(),jQuery("#info_"+r+" #percent").html("Uploading..."),jQuery("#progress_"+r+" div.bar").animate({width:"100%"},"slow");var Q=setInterval(function(){if(jQuery("#"+r+"_iframe").contents()){var e=jQuery("#"+r+"_iframe").contents().find(".uploaded").length;e>0&&(clearInterval(Q),jQuery("#progress_"+r).removeClass("active"),jQuery("#imagename").val(u),jQuery("#submit_hover_btn_img_div").css("background","none"),jQuery("#submit_hover_btn_img_div").css("border","0px"),jQuery("#submit_hover_btn_img_div").css("padding","0px"),jQuery("#submit_hover_btn_img_div").css("box-shadow","none"),change_submit_hover_img(),jQuery("#div_"+r).hide(),jQuery("#remove_"+r).css("display","block"),jQuery("#div_"+r).css("margin-top","0px"),jQuery("#remove_"+r).css("margin-top","-4px"),jQuery("#info_"+r+" #percent").html("File Uploaded"),jQuery('input[name="file'+a+'"]').val(d),jQuery('input[name="item_meta['+a+']"]').val(d),jQuery('input[name="file'+a+'"]').attr("data-file-valid","true"),jQuery("#"+r+"_iframe_div").html(" ").append('<iframe id="'+r+'_iframe" src="'+p+'/core/views/iframe.php"></iframe>'));var i=jQuery("#"+r+"_iframe").contents().find(".error_upload").length;if(i>0){clearInterval(Q);var t=a;if(jQuery('input[name="file'+t+'"]').attr("data-file-valid","false"),"undefined"!=typeof __ARFERR)var n=__ARFERR;else var n="Sorry, this file type is not permitted for security reasons.";if(void 0!==jQuery("#field_"+r).attr("data-invalid-message")&&""!=jQuery("#field_"+r).attr("data-invalid-message"))var s=jQuery("#field_"+r).attr("data-invalid-message");else var s=n;jQuery("#arf_field_"+t+"_container").removeClass("success");var l=jQuery("#arf_field_"+t+"_container .controls"),o=l.parents(".control-group").first(),f=o.find(".help-block").first(),_=l.closest("form").find("#form_id").val(),m="advance"==jQuery("#form_tooltip_error_"+_).val()?"advance":"normal";"advance"==m?arf_show_tooltip(o,f,s):f.length?(f=jQuery("<ul><li>"+s+"</li></ul>"),o.find(".controls .help-block").append(f)):(f=jQuery('<div class="help-block"><ul><li>'+s+"</li></ul></div>"),o.find(".controls").append(f)),jQuery("#"+r+"_iframe_div").html(" ").append('<iframe id="'+r+'_iframe" src="'+p+'/core/views/iframe.php"></iframe>')}}},100)}})}();