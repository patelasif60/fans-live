!function(e){var t={};function n(i){if(t[i])return t[i].exports;var o=t[i]={i:i,l:!1,exports:{}};return e[i].call(o.exports,o,o.exports,n),o.l=!0,o.exports}n.m=e,n.c=t,n.d=function(e,t,i){n.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:i})},n.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},n.t=function(e,t){if(1&t&&(e=n(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var i=Object.create(null);if(n.r(i),Object.defineProperty(i,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var o in e)n.d(i,o,function(t){return e[t]}.bind(null,o));return i},n.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return n.d(t,"a",t),t},n.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},n.p="/",n(n.s=95)}({"841E":function(e,t){var n,i,o,a=(n=function(){jQuery(".js-select2-allow-clear:not(.js-select2-enabled), .js-select2:not(.js-select2-enabled)").each(function(){var e=jQuery(this);e.addClass("js-select2-enabled"),e.select2({tags:!0,tokenSeparators:[","],allowClear:!!$(this).hasClass("js-select2-allow-clear"),placeholder:"Please select"})})},i=function(){arguments.length>0&&void 0!==arguments[0]&&arguments[0],arguments.length>1&&void 0!==arguments[1]&&arguments[1],$(document).on("click",".js-fill-in-the-blank-add-question-answer",function(){if($(".add-new-question").attr("id")>=0)var e=parseInt($("#fill_in_the_blank_add_question_answer").children().last().attr("id"))+1;else e=0;!function(e){var t='<div class="block block-bordered block-default block-rounded"> <div class="block-header block-header-default"> <div></div><div class="block-options"><button type="button" class="btn-block-option js-fill-in-the-blank-question-info-content-section-delete text-danger" title="" data-original-title="Delete"><i class="fal fa-trash"></i></button></div></div><div class="block-content"> <div class="row"> <div class="col-xl-4"><div class="form-group"><label for="fill_in_the_blank_hint" class="required">Hint:</label><input type="text" class="form-control line-ups-home-number fill-in-the-blank-hint" id="fill_in_the_blank_hint'+e+'" name="fill_in_the_blank['+e+'][hint]" value=""></div></div><div class="col-xl-4"><div class="form-group"><label for="fill_in_the_blank_answer" class="required">Answer:</label><input type="text" class="form-control line-ups-home-number fill-in-the-blank-hint" id="fill_in_the_blank_answer'+e+'" name="fill_in_the_blank['+e+'][answer]" value=""></div></div><div class="col-xl-4"><div class="form-group"><label for="fill_in_the_blank_accepted_answer" class="required">Accepted answers:</label><select class="js-select2 form-control fill-in-the-blank-hint" multiple="multiple" id="fill_in_the_blank_accepted_answer'+e+'" name="fill_in_the_blank['+e+'][accepted_answer][]"></select></div></div><div class="col-xl-4"> </div></div></div></div>';$("#fill_in_the_blank_add_question_answer").append('<div class="col-xl-12 add-new-question" id='+e+">"+t+"</div>")}(e),n(),$(".fill-in-the-blank-hint").each(function(){$(this).rules("add",{required:!0,messages:{required:"This field is required."}})}),p()})},o=function(e){if(e.files&&e.files[0]){var t=new FileReader;t.onload=function(e){$(".js-manage-logo-width").removeClass("col-12").addClass("col-9"),$("#logo_preview").attr("src",e.target.result),$("#logo_preview_container").removeClass("d-md-none")},t.readAsDataURL(e.files[0])}},{init:function(){i(),n(),$(".create-quiz-form").validate({ignore:[],errorClass:"invalid-feedback animated fadeInDown",errorElement:"div",errorPlacement:function(e,t){"logo"==t.attr("name")?$(t).parents(".form-group .logo-input").append(e):"description"==t.attr("name")?($(t).parent().find(".cke_editor_js-ckeditor").addClass("is-invalid"),$(t).parents(".form-group").append(e)):$(t).parents(".form-group").append(e)},highlight:function(e){$(e).closest(".form-group").removeClass("is-invalid").addClass("is-invalid")},unhighlight:function(e){$(e).closest(".form-group").removeClass("is-invalid").removeClass("is-invalid")},success:function(e){$(e).closest(".form-group").removeClass("is-invalid"),$(e).remove()},rules:{title:{required:!0},publication_date:{required:!0},description:{required:function(e){CKEDITOR.instances[e.id].updateElement();var t=e.value.replace(/<[^>]*>/gi,"");return t.length>0&&$("#"+e.id).parent().find(".cke_editor_js-ckeditor").removeClass("is-invalid"),0===t.length}},time_limit:{required:{depends:function(e){return $(e).closest("form").find("#type_fill_in_the_blanks").is(":checked")}},min:1,max:172800,number:!0},logo:{accept:"image/png",extension:"png",icondimension:[840,630,"logo"]}},messages:{logo:{accept:"Please upload the correct file format.",icondimension:"Please upload the correct file size."}}}),$(".js-datetimepicker").datetimepicker({ignoreReadonly:!0,format:Site.dateTimeCmsFormat,timeZone:Site.clubTimezone}),jQuery("#js-ckeditor:not(.js-ckeditor-enabled)").length&&(CKEDITOR.replace("js-ckeditor",{toolbar:[["Bold","Link","Maximize","Source"]]}).on("change",function(e){e.editor.getData().replace(/<[^>]*>/gi,"").length>0?($("."+e.editor.id).removeClass("is-invalid"),$("."+e.editor.id).closest(".form-group").find(".invalid-feedback").remove()):$(".js-question-info-create-content").trigger("click")}),jQuery("#js-ckeditor").addClass("js-ckeditor-enabled")),$("#logo").change(function(){var e=$(this).val().split(".").pop().toLowerCase();-1!=$.inArray(e,["png","jpg","jpeg"])&&o(this)})}}),s=$(".repeater").repeater({show:function(){$(this).slideDown(),r(),l()},hide:function(e){$(this).slideUp(function(){e(),l()})},isFirstItemUndeletable:!0});function r(){$('input[name^="answers"]').filter('input[name$="[answer]"]').each(function(){$(this).rules("add",{required:!0,messages:{required:"This field is required."}})})}function l(){var e=1;$(".js-quizzes-answer-fields-wrapper").each(function(t){$(this).find(".option_cnt").text("Option "+e+":"),e++});var t=0;$(document).ready(function(){$(".js-question-is-correct").each(function(e){$(this).find(".is_correct_radio").attr("id","correct"+t),$(this).find(".is_false_radio").attr("id","false"+t),$(this).find(".lbl_is_correct").attr("for","correct"+t),$(this).find(".lbl_is_false").attr("for","false"+t),t++})})}var d=0!==Site.quizMultipleChoiseQuestionsJson.length?JSON.parse(Site.quizMultipleChoiseQuestionsJson):[],c='<div class="block block-rounded draggable-item js-draggable-display-order js-draggable-items-section-remove"><div class="block-header block-header-default"><h3 class="block-title">{content_question}</h3><div class="block-options"><button type="button" class="btn-block-option js-tooltip-enabled js-question-info-edit-content-section" title="" data-toggle="modal" data-target="#add_question_info_content" data-original-title="Edit"" data-index="{index}"><i class="fal fa-pencil"></i></button><button type="button" class="btn-block-option js-tooltip-enabled js-question-info-content-section-delete text-danger" data-toggle="modal" title="" data-index="{index}" data-original-title="Delete"><i class="fal fa-trash"></i></button><a class="btn-block-option draggable-handler" href="javascript:void(0)"><i class="si si-cursor-move"></i></a></div></div></div>';jQuery(function(){a.init()});var u=$("#section_content_form");function _(){return 0==$("#add_question_info_section").text().trim().length?($("#mcq_draggable_questions_validation_error").addClass("mb-15"),$("#mcq_draggable_questions_validation_error").text(""),$("#mcq_draggable_questions_validation_error").text("Please add at least one MCQ"),!1):($("#mcq_draggable_questions_validation_error").removeClass("mb-15"),$("#mcq_draggable_questions_validation_error").text(""),!0)}function f(){return 0==$("#add_end_of_quiz_response").text().trim().length?($("#end_of_quiz_text_validation_error").addClass("mb-15"),$("#end_of_quiz_text_validation_error").text(""),$("#end_of_quiz_text_validation_error").text("Please add at least one end of quiz text."),!1):($("#end_of_quiz_text_validation_error").removeClass("mb-15"),$("#end_of_quiz_text_validation_error").text(""),!0)}function p(){return 0==$("#fill_in_the_blank_add_question_answer").text().trim().length?($("#fill_in_the_blank_answers_validation_error").addClass("mb-15"),$("#fill_in_the_blank_answers_validation_error").text(""),$("#fill_in_the_blank_answers_validation_error").text("Please add at least one answer."),!1):($("#fill_in_the_blank_answers_validation_error").removeClass("mb-15"),$("#fill_in_the_blank_answers_validation_error").text(""),!0)}u.validate({ignore:[],errorClass:"invalid-feedback animated fadeInDown",errorElement:"div",errorPlacement:function(e,t){"radio"==t.attr("type")?$(t).parents(".form-group1").append(e):$(t).parents(".form-group").append(e)},highlight:function(e){$(e).closest(".form-group").removeClass("is-invalid").addClass("is-invalid")},unhighlight:function(e){$(e).closest(".form-group").removeClass("is-invalid").removeClass("is-invalid")},success:function(e){$(e).closest(".form-group").removeClass("is-invalid"),$(e).remove()},rules:{content_question:{required:!0},content_post_answer_text:{required:!0}}}),$(document).on("click",".js-quiz-add-another-answer-save",function(){var e=!0;if(0==$('input[name$="[is_correct]"]:checked').length?(e=!1,$("#radio_option_validation_error").text(""),$("#radio_option_validation_error").text("* Please select one correct option.")):$("#radio_option_validation_error").empty(),!u.valid()||!e)return!1;var t=$(".js-content-section-question").val(),n=$(".js-content-section-post-answer-text").val(),i=$(".repeater").repeaterVal().answers;if("add"==$("#add_edit_section_content").val()){jQuery(".js-draggable-items:not(.js-draggable-items-enabled)").each(function(){var e=jQuery(this);e.addClass("js-draggable-items-enabled"),e.children(".draggable-column").sortable({connectWith:".draggable-column",items:".draggable-item",dropOnEmpty:!0,opacity:.75,handle:".draggable-handler",placeholder:"draggable-placeholder",tolerance:"pointer",start:function(e,t){t.placeholder.css({height:t.item.outerHeight(),"margin-bottom":t.item.css("margin-bottom")})}})}),$("#add_question_info_section").show(),d.push({content_question:t,content_post_answer_text:n,answers:i}),$("#add_question_content").val(JSON.stringify(d));var o=c.replace("{content_question}",t).replace(/{index}/g,d.length-1);$("#add_question_info_section").append(o)}else{$("#add_question_info_section").show();var a=$("#add_edit_question_index").val();d[a].content_question=t,d[a].content_post_answer_text=n,d[a].answers=i,$("#add_question_content").val(JSON.stringify(d));o=c.replace("{content_question}",t).replace(/{index}/g,d+1);$("#add_question_info_section .block-title:eq("+a+")").html(t)}$(".js-content-section-question").val(""),$("#add_question_info_content").modal("hide"),_()}),d.length||$("#add_question_info_section").hide(),$(document).on("click",".js-question-info-edit-content-section",function(){var e;e=$(this).attr("data-index"),$("#add_edit_section_content").val("edit"),$("#add_edit_question_index").val(e),d[e].content_question,d[e].content_post_answer_text,$(".js-content-section-question").val(d[e].content_question),$(".js-content-section-post-answer-text").val(d[e].content_post_answer_text),s.setList(d[e].answers),$("[data-repeater-delete]").first().remove()}),$(document).on("click",".js-add-question-answer-info-content",function(){$("[data-repeater-list]").empty(),$("[data-repeater-create]").click(),$("[data-repeater-delete]").first().remove(),$("#add_edit_section_content").val("add"),$(".js-content-section-question,.js-content-section-post-answer-text").val(""),$("#add_edit_question_index").val(""),r()}),$(document).on("click",".js-fill-in-the-blank-question-info-content-section-delete",function(){$(this).closest(".add-new-question").remove()}),$(document).on("click",".js-question-info-content-section-delete",function(){var e;e=$(this).attr("data-index"),d.splice(e,1),$("#add_question_content").val(JSON.stringify(d)),$(this).closest(".js-draggable-items-section-remove").remove(),d.length||$("#add_question_info_section").hide();var t=0;$("#add_question_info_section .draggable-item").each(function(e){$(this).find(".js-question-info-edit-content-section, .js-question-info-content-section-delete").attr("data-index",t),t++})}),$(".js-draggable-items").sortable({start:function(e,t){quizQuestionDraggable=[]},stop:function(e,t){quizQuestionDraggable=[],$("#add_question_info_section .js-draggable-display-order").each(function(e){e=$(this).find(".js-question-info-edit-content-section").attr("data-index");var t=d[e];quizQuestionDraggable.push(t)}),d=quizQuestionDraggable,$("#add_question_content").val(JSON.stringify(d));var n=0;$("#add_question_info_section .draggable-item").each(function(e){$(this).find(".js-question-info-edit-content-section, .js-question-info-content-section-delete").attr("data-index",n),n++})}}),$(document).on("click",".js-question-info-create-content",function(){$(".create-quiz-form").valid();var e=$("input[name$='type']:checked").val();if("fill_in_the_blanks"==e){if(!p())return!1}else if("multiple_choice"==e){var t=_(),n=f();if(!t||!n)return!1}$("#add_question_content").val(JSON.stringify(d)),$(".js-create-question-info-content").submit()}),$(document).on("click",".js-question-info-content-section-delete",function(){$(this).closest(".add-match-event").remove()}),$(document).on("click",".js-add-end-quiz-response",function(){if($(".add-quiz-response").attr("id")>=0)var e=parseInt($("#add_end_of_quiz_response").children().last().attr("id"))+1;else e=0;!function(e){var t='<div class="block block-bordered block-default block-rounded js-quiz-response-main-div"> <div class="block-header block-header-default"> <div></div><div class="block-options"><button type="button" class="btn-block-option js-add-response-delete text-danger" title="" data-original-title="Delete"><i class="fal fa-trash"></i></button></div></div><div class="block-content"> <div class="row"> <div class="col-xl-8"><div class="form-group"><label for="end_of_quiz_text" class="required">Text:</label><input type="text" class="form-control end-of-quiz-text" id="end_of_quiz_text'+e+'" name="end_of_quiz['+e+'][text]" value=""></div></div><div class="col-xl-4"><div class="form-group"><label for="end_of_quiz_points_thershold" class="required">Points threshold:</label><input type="number" class="form-control end-of-quiz-points-thershold" min="0" max="999" id="end_of_quiz_points_thershold'+e+'" name="end_of_quiz['+e+'][points_thershold]"></div></div></div></div></div>';$("#add_end_of_quiz_response").append('<div class="col-xl-12 add-quiz-response" id='+e+">"+t+"</div>"),$(".end-of-quiz-points-thershold").each(function(){$(this).rules("add",{required:!0,number:!0,min:0,messages:{required:"This field is required."}})}),$(".js-content-section-post-answer-option, .end-of-quiz-text").each(function(){$(this).rules("add",{required:!0,messages:{required:"This field is required."}})})}(e),f()}),$(document).on("click",".js-add-response-delete",function(){$(this).closest(".add-quiz-response").remove()}),$("#logo").change(function(){var e=$(this).val().split(".").pop().toLowerCase();-1!=$.inArray(e,["png","jpg","jpeg"])&&function(e){if(e.files&&e.files[0]){var t=new FileReader;t.onload=function(e){$(".js-manage-logo-width").removeClass("col-12").addClass("col-9"),$("#logo_preview").attr("src",e.target.result),$("#logo_preview_container").removeClass("d-md-none"),$("#remove").removeClass("d-md-none")},t.readAsDataURL(e.files[0])}}(this)}),$("#remove").on("click",function(){$("#logo").val(""),document.getElementById("lbl_logo").innerText="Choose File",$("#logo_preview_container").addClass("d-md-none"),$(".js-manage-logo-width").removeClass("col-9").addClass("col-12"),$("#logo_preview").attr("src","")}),$(document).ready(function(){$("input[name$='type']").click(function(){var e=$(this).val();"multiple_choice"==e&&$("#time_limit").val(""),$("div.desc").hide(),$("#radio_"+e).removeClass("d-none"),$("#radio_"+e).show()})}),$(document).on("click",".js-is-correct-status",function(){$(".is_correct_radio").prop("checked",!1),$(this).prop("checked",!0),$('input[name$="[is_correct]"]:checked').length>0&&$("#radio_option_validation_error").empty()})},95:function(e,t,n){e.exports=n("841E")}});