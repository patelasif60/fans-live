!function(e){var r={};function n(i){if(r[i])return r[i].exports;var o=r[i]={i:i,l:!1,exports:{}};return e[i].call(o.exports,o,o.exports,n),o.l=!0,o.exports}n.m=e,n.c=r,n.d=function(e,r,i){n.o(e,r)||Object.defineProperty(e,r,{enumerable:!0,get:i})},n.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},n.t=function(e,r){if(1&r&&(e=n(e)),8&r)return e;if(4&r&&"object"==typeof e&&e&&e.__esModule)return e;var i=Object.create(null);if(n.r(i),Object.defineProperty(i,"default",{enumerable:!0,value:e}),2&r&&"string"!=typeof e)for(var o in e)n.d(i,o,function(r){return e[r]}.bind(null,o));return i},n.n=function(e){var r=e&&e.__esModule?function(){return e.default}:function(){return e};return n.d(r,"a",r),r},n.o=function(e,r){return Object.prototype.hasOwnProperty.call(e,r)},n.p="/",n(n.s=56)}({56:function(e,r,n){e.exports=n("kr+z")},"kr+z":function(e,r){var n={init:function(){$(".edit-membership-package-form").validate({ignore:[],errorClass:"invalid-feedback animated fadeInDown",errorElement:"div",errorPlacement:function(e,r){"icon"==r.attr("name")?$(r).parents(".form-group .logo-input").append(e):$(r).parents(".form-group").append(e)},highlight:function(e){$(e).closest(".form-group").removeClass("is-invalid").addClass("is-invalid")},unhighlight:function(e){$(e).closest(".form-group").removeClass("is-invalid").removeClass("is-invalid")},success:function(e){$(e).closest(".form-group").removeClass("is-invalid"),$(e).remove()},rules:{title:{required:!0},membership_duration:{required:!0,number:!0},rewards_percentage_override:{number:!0,min:0,max:100},price:{required:!0,number:!0,min:0},vat_rate:{required:!0,number:!0,min:0},icon:{accept:"image/png",extension:"png",icondimension:[150,150,"icon"]}},messages:{icon:{accept:"Please upload the correct file format.",icondimension:"Please upload the correct file size."}}}),$(".edit-membership-package-form").data("validator").settings.ignore=".note-editor *",jQuery("#js-ckeditor:not(.js-ckeditor-enabled)").length&&(CKEDITOR.replace("js-ckeditor",{toolbar:[["Bold","Link","Maximize","Source"]]}),jQuery("#js-ckeditor").addClass("js-ckeditor-enabled"))}};jQuery(function(){n.init()}),$(".uploadimage").change(function(){!function(e){if(e.files&&e.files[0]){var r=new FileReader;r.onload=function(r){$(".js-manage-logo-width").removeClass("col-12").addClass("col-9"),$("#"+e.id+"_image").hasClass("d-md-none")&&$("#"+e.id+"_image").removeClass("d-md-none"),$("#"+e.id+"_preview_container").html('<img class="img-avatar img-avatar-square" id="icon_preview" name="icon_preview" src="'+r.target.result+'" />')},r.readAsDataURL(e.files[0])}}(this)})}});