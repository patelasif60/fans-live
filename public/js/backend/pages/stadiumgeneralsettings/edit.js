!function(e){var i={};function a(n){if(i[n])return i[n].exports;var r=i[n]={i:n,l:!1,exports:{}};return e[n].call(r.exports,r,r.exports,a),r.l=!0,r.exports}a.m=e,a.c=i,a.d=function(e,i,n){a.o(e,i)||Object.defineProperty(e,i,{enumerable:!0,get:n})},a.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},a.t=function(e,i){if(1&i&&(e=a(e)),8&i)return e;if(4&i&&"object"==typeof e&&e&&e.__esModule)return e;var n=Object.create(null);if(a.r(n),Object.defineProperty(n,"default",{enumerable:!0,value:e}),2&i&&"string"!=typeof e)for(var r in e)a.d(n,r,function(i){return e[i]}.bind(null,r));return n},a.n=function(e){var i=e&&e.__esModule?function(){return e.default}:function(){return e};return a.d(i,"a",i),i},a.o=function(e,i){return Object.prototype.hasOwnProperty.call(e,i)},a.p="/",a(a.s=46)}({"2G1v":function(e,i){var a={init:function(){$(".edit-stadium-settings-form").validate({ignore:[],errorClass:"invalid-feedback animated fadeInDown",errorElement:"div",errorPlacement:function(e,i){"image"==i.attr("name")||"aerial_view_ticketing_graphic"==i.attr("name")?$(i).parents(".form-group .logo-input").append(e):$(i).parents(".form-group").append(e)},highlight:function(e){"image"!=$(e).attr("name")&&"aerial_view_ticketing_graphic"!=$(e).attr("name")||$(e).removeClass("is-invalid").addClass("is-invalid"),$(e).closest(".form-group").removeClass("is-invalid").addClass("is-invalid")},unhighlight:function(e){"image"!=$(e).attr("name")&&"aerial_view_ticketing_graphic"!=$(e).attr("name")||$(e).removeClass("is-invalid"),$(e).closest(".form-group").removeClass("is-invalid").removeClass("is-invalid")},success:function(e){$(e).closest(".form-group").removeClass("is-invalid"),$(e).remove()},rules:{name:{required:!0},address:{required:!0},town:{required:!0},postcode:{required:!0},aerial_view_ticketing_graphic:{required:function(e){return!($("#aerial_view_ticketing_graphic_file_name").val()||!$("#is_using_allocated_seating").is(":checked"))}},image:{accept:"image/png",extension:"png",imagedimension:[840,525,1]},number_of_seats:{required:function(e){return!$("#is_using_allocated_seating").is(":checked")}}},messages:{image:{accept:"Please upload the correct file format.",imagedimension:"Please upload the correct file size."}}})}};jQuery(function(){a.init()}),$(".uploadimage").change(function(){var e=$(this).val().split(".").pop().toLowerCase();-1!=$.inArray(e,["png","jpg","jpeg"])&&function(e){if(e.files&&e.files[0]){var i=new FileReader;i.onload=function(i){$(".js-manage-logo-width").removeClass("col-12").addClass("col-9"),$("#"+e.id+"_preview").attr("src",i.target.result),$("#"+e.id+"_preview_container").removeClass("d-md-none"),$("#"+e.id+"_preview_container").addClass("new_upload")},i.readAsDataURL(e.files[0])}}(this)}),$("#is_using_allocated_seating").click(function(){$(".js-number-of-seats").removeClass("d-none"),$(".js-aerial-view-ticketing-graphic").addClass("d-none"),$("#is_using_allocated_seating").is(":checked")&&($(".js-number-of-seats").addClass("d-none"),$(".js-aerial-view-ticketing-graphic").removeClass("d-none"))}),$("#aerial_view_ticketing_graphic").change(function(){var e=$(this).val().split(".").pop().toLowerCase();-1!=$.inArray(e,["png","jpg","jpeg"])&&function(e){if(e.files&&e.files[0]){var i=new FileReader;i.onload=function(e){$(".js-manage-arial-logo-width").removeClass("col-12").addClass("col-9"),$("#logo_preview_container").hasClass("d-md-none")&&$("#logo_preview_container").removeClass("d-md-none"),$("#remove").removeClass("d-md-none"),$("#logo_preview_container").html('<div class="logo_preview_container ml-3"><img id="logo_preview" alt="Category logo" src="'+e.target.result+'" /></div>')},i.readAsDataURL(e.files[0])}}(this)}),$(document).on("click","#remove",function(){$("#logo").val(""),document.getElementById("lbl_aerial_view_ticketing_graphic").innerText="Choose File",$("#logo_preview_container").addClass("d-md-none"),$("#remove").addClass("d-md-none"),$(".js-manage-arial-logo-width").removeClass("col-9").addClass("col-12")})},46:function(e,i,a){e.exports=a("2G1v")}});