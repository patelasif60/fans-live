!function(e){var a={};function t(l){if(a[l])return a[l].exports;var i=a[l]={i:l,l:!1,exports:{}};return e[l].call(i.exports,i,i.exports,t),i.l=!0,i.exports}t.m=e,t.c=a,t.d=function(e,a,l){t.o(e,a)||Object.defineProperty(e,a,{enumerable:!0,get:l})},t.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},t.t=function(e,a){if(1&a&&(e=t(e)),8&a)return e;if(4&a&&"object"==typeof e&&e&&e.__esModule)return e;var l=Object.create(null);if(t.r(l),Object.defineProperty(l,"default",{enumerable:!0,value:e}),2&a&&"string"!=typeof e)for(var i in e)t.d(l,i,function(a){return e[a]}.bind(null,i));return l},t.n=function(e){var a=e&&e.__esModule?function(){return e.default}:function(){return e};return t.d(a,"a",a),a},t.o=function(e,a){return Object.prototype.hasOwnProperty.call(e,a)},t.p="/",t(t.s=59)}({59:function(e,a,t){e.exports=t("N84I")},N84I:function(e,a){var t,l,i=(t=function(e){$(e).find('input[name^="sponsors"]').filter('input[name$="[sponsor]"]').each(function(){})},l=function(e,a){var t="";function l(e){$("#add_player").modal("hide"),$("#player_name").val(""),toastr.success("Player added successfully.","Success!"),t+="<option value="+e.id+">"+e.name+"</option>",$(".edit-home-team .line-ups-home-name").each(function(){$(this).append("<option value="+e.id+">"+e.name+"</option>")}),$(".edit-away-team .line-ups-away-name").each(function(){$(this).append("<option value="+e.id+">"+e.name+"</option>")})}$.each(Site.players,function(e,a){t+="<option value="+a.id+">"+a.name+"</option>"}),$(document).on("click",".js-edit-home-team-player",function(){if($(".edit-home-team").attr("id")>=0)var e=parseInt($("#edit_home_team_player").children().last().attr("id"))+1;else e=0;!function(e,a){var t='<div class="block block-bordered block-default block-rounded"> <div class="block-header block-header-default"> <div></div><div class="block-options"><button type="button" class="btn-block-option js-edit-home-team-delete text-danger" title="" data-original-title="Delete"><i class="fal fa-trash"></i></button></div></div><div class="block-content"> <div class="row"> <div class="col-xl-4"><div class="form-group"><label for="line_ups_home_number" class="required">No:</label><input type="number" min="0" class="form-control line-ups-home-number" id="line_ups_home_number'+e+'" name="line_ups_home_number_new['+e+']" value=""><input type="hidden" name="line_ups_home_number_edit[]" value="0"></div></div><div class="col-xl-4"><div class="form-group"><label for="line_ups_home_name" class="required">Name:</label><select class="js-select2 form-control line-ups-home-name" id="line_ups_home_name'+e+'" name="line_ups_home_name_new['+e+']"><option value="">Please select</option>'+a+'</select></div></div><div class="col-xl-4"><div class="form-group row"> <label class="col-12">Sub</label> <div class="col-12"> <div class="custom-control custom-checkbox mb-5"> <input class="custom-control-input sub-home" type="checkbox" name="sub_home_new['+e+']" id="sub_home'+e+'"><label class="custom-control-label" for="sub_home'+e+'"></label> </div></div></div></div></div></div></div>';$("#edit_home_team_player").append('<div class="col-xl-12 edit-home-team" id='+e+">"+t+"</div>"),s()}(e,t),n()}),$(document).on("click",".js-edit-away-team-player",function(){if($(".edit-away-team").attr("id")>=0)var e=parseInt($("#edit_away_team_player").children().last().attr("id"))+1;else e=0;!function(e,a){var t='<div class="block block-bordered block-default block-rounded"><div class="block-header block-header-default"> <div></div><div class="block-options"><button type="button" class="btn-block-option js-edit-home-team-delete text-danger" title="" data-original-title="Delete"><i class="fal fa-trash"></i></button></div></div><div class="block-content"> <div class="row"> <div class="col-xl-4"><div class="form-group"><label for="line_ups_away_number" class="required">No:</label><input type="number" min="0" class="form-control line-ups-away-number" id="line_ups_away_number'+e+'" name="line_ups_away_number_new['+e+']" value=""><input type="hidden" name="line_ups_away_number_edit[]" value="0"></div></div><div class="col-xl-4"><div class="form-group"><label for="line_ups_away_name" class="required">Name:</label><select class="js-select2 form-control line-ups-away-name" id="line_ups_away_name'+e+'" name="line_ups_away_name_new['+e+']"><option value="">Please select</option>'+a+'</select></div></div><div class="col-xl-4"><div class="form-group row"> <label class="col-12">Sub</label> <div class="col-12"> <div class="custom-control custom-checkbox mb-5"> <input class="custom-control-input sub-away" type="checkbox" name="sub_away['+e+']" id="sub_away'+e+'"> <label class="custom-control-label" for="sub_away'+e+'"></label> </div></div></div></div></div></div></div>';$("#edit_away_team_player").append('<div class="col-xl-12 edit-away-team" id='+e+">"+t+"</div>"),s()}(e,t),n()}),$(document).on("click",".js-add-player-save",function(){if(!c.valid())return!1;var e=$("#player_name").val();ajaxCall("../addPlayer","name="+e,"POST","json",l)})},{init:function(){$(".edit-match-form").validate({ignore:[],errorClass:"invalid-feedback animated fadeInDown",errorElement:"div",errorPlacement:function(e,a){a.hasClass("uploadimage")?$(a).parents(".form-group .js-manage-sponsor-logo-width").append(e):a.hasClass("uploadhospitality")?$(a).parents(".form-group .logo-fields-wrapper").append(e):$(a).parents(".form-group").append(e),showTabError()},highlight:function(e){$(e).closest(".form-group").removeClass("is-invalid").addClass("is-invalid")},unhighlight:function(e){$(e).closest(".form-group").removeClass("is-invalid")},success:function(e){$(e).closest(".form-group").removeClass("is-invalid"),$(e).remove()},rules:{kickoff_time:{required:!0},home:{required:!0},away:{required:!0},maximum_number_of_ticket_per_user:{required:{depends:function(e){return $(e).closest("form").find("#is_enable_ticket").is(":checked")}}},"available_blocks[]":{required:{depends:function(e){return 1==$("#seatValidaton").val()&&$(e).closest("form").find("#is_enable_ticket").is(":checked")}}},"pricing_bands[]":{required:{depends:function(e){return $(e).closest("form").find("#is_enable_ticket").is(":checked")}}},rewards_percentage_override:{number:!0,min:0,max:100},ticket_resale_fee_type:{required:{depends:function(e){return $(e).closest("form").find("#allow_ticket_returns_resales").is(":checked")}}},ticket_resale_fee_amount:{required:{depends:function(e){return $(e).closest("form").find("#allow_ticket_returns_resales").is(":checked")}}},hospitality_override_base_price:{number:!0,min:0},hospitality_rewards_percentage_override:{number:!0,min:0,max:100},"hospitality_suites[]":{required:{depends:function(e){return $(e).closest("form").find("#is_enable_hospitality").is(":checked")}}}}}),$(".js-sponsors-fields-wrapper").each(function(){t($(this))}),$(".js-datetimepicker").datetimepicker({ignoreReadonly:!0,format:Site.dateTimeCmsFormat,timeZone:Site.clubTimezone,buttons:{showClear:!0}}),$(".js-datepicker").datetimepicker({ignoreReadonly:!0,format:Site.dateCmsFormat,timeZone:Site.clubTimezone,buttons:{showClear:!0}}),s(),$(".repeater").repeater({show:function(){$(this).slideDown(),t(this)},isFirstItemUndeletable:!0}),$(".js-hospitality-datepicker").datetimepicker({ignoreReadonly:!0,format:Site.dateTimeCmsFormat,timeZone:Site.clubTimezone,buttons:{showClear:!0}}),$.validator.addMethod("atLeastOneTicketingPackageDate",function(e,a,t){return!$("#is_enable_ticket").is(":checked")||$('input[name^="package"]').filter(function(){return""!=$(this).val()}).length>0},"Please select atleast one on sale date."),$.validator.addClassRules("ticketing-package-date-required",{atLeastOneTicketingPackageDate:!0}),$.validator.addMethod("atLeastOneHospitalityPackageDate",function(e,a,t){return!$("#is_enable_hospitality").is(":checked")||$('input[name^="hospitality_package"]').filter(function(){return""!=$(this).val()}).length>0},"Please select atleast one on sale date."),$.validator.addClassRules("hospitality-package-date-required",{atLeastOneHospitalityPackageDate:!0}),l()}});jQuery(function(){i.init()});function s(){$(".match-event-team, .line-ups-home-number, .line-ups-away-number, .match_events_time").each(function(e){$(this).rules("add",{required:!0,number:!0,min:0,messages:{required:"This field is required."}})}),$(".line-ups-home-name, .line-ups-away-name, .match-type-of-event, .match-event-player, .select2-substitution-player").each(function(){$(this).rules("add",{required:!0,messages:{required:"This field is required."}})})}$(".edit-match-form").submit(function(e){if($(".edit-match-form").valid())return $(".match-submit-btn").attr("disabled","disabled"),!0;e.preventDefault()});var n=function(){jQuery(".js-select2-allow-clear:not(.js-select2-enabled), .js-select2:not(.js-select2-enabled)").each(function(){var e=jQuery(this);e.addClass("js-select2-enabled"),e.select2({allowClear:!!$(this).hasClass("js-select2-allow-clear"),placeholder:"Please select"})})};function o(e){var a=[],t={id:"",text:"Please select"};return a.push(t),$.each(e,function(e,t){var l={};l.id=t.player.id,l.text=t.player.name,a.push(l)}),a}$(document).on("click",".js-edit-home-team-delete",function(){$(this).closest(".edit-home-team").remove()}),$(document).on("click",".js-edit-home-team-delete",function(){$(this).closest(".edit-away-team").remove()}),$(document).on("click",".js-edit-match-event",function(){if($(".edit-match-event").attr("id")>=0)var e=parseInt($("#edit_match_event").children().last().attr("id"))+1;else e=0;var a,t;!function(e){var a='<div class="block block-bordered block-default"><div class="block-header block-header-default"><div></div><div class="block-options"><button type="button" class="btn-block-option js-match-event-delete text-danger" title="" data-original-title="Delete"><i class="fal fa-trash"></i></button></div></div><div class="block-content block-content-full"><div class="row"><div class="col-xl-4"><div class="form-group"><label class="required" for="match_event'+e+'">Team:</label><select class="js-select2 form-control match-event-team" id="match_event'+e+'" name="match_event['+e+']" style="width:100%"><option value="">Please select</option><option value="'+Site.homeTeam.id+'" data-type="home">'+Site.homeTeam.name+'</option><option value="'+Site.awayTeam.id+'" data-type="away">'+Site.awayTeam.name+'</option></select></div></div><div class="col-xl-4"><div class="form-group"><label class="required" for="match_event_player'+e+'">Player:</label><select class="js-select2 form-control match-event-player" id="match_event_player'+e+'" name="match_event_player['+e+']" style="width:100%"><option value="1">Please select</option></select></div></div><div class="col-xl-4"><div class="form-group"><label class="required" for="match_events_time'+e+'">Time (mins):</label><input type="number" min="0" class="form-control match_events_time" id="match_events_time'+e+'" name="match_events_time['+e+']" value=""></div></div><div class="col-xl-4"><div class="form-group mb-0"><label>Action replay video:</label><div class="custom-file"><input type="file" class="custom-file-input action_replay_video" id="action_replay_video'+e+'" name="action_replay_video['+e+']" data-toggle="custom-file-input" accept="video/*"><label class="custom-file-label text-truncate pr-100" for="action_replay_video'+e+'">Choose file</label></div></div></div><div class="col-xl-8"><div class="row type-of-event-main-div"><div class="col-xl-6"><div class="form-group mb-0" data-select2-id="21"><label class="required" for="match_type_of_event'+e+'">Type of event:</label><select class="form-control select2-match-event-type match-type-of-event js-select2" id="match_type_of_event'+e+'" name="match_type_of_event['+e+']" style="width:100%"><option value="">Please select</option></select></div></div></div></div></div></div>';$("#edit_match_event").append('<div class="col-xl-12 edit-match-event" id='+e+">"+a+"</div>"),s()}(e),jQuery('[data-toggle="custom-file-input"]:not(.js-custom-file-input-enabled)').each(function(e,a){var t=jQuery(a);t.addClass("js-custom-file-input-enabled").on("change",function(e){var a=e.target.files.length>1?e.target.files.length+" "+(t.data("lang-files")||"Files"):e.target.files[0].name;t.next(".custom-file-label").css("overflow-x","hidden").html(a)})}),n(),$(".select2-match-event-type").select2({data:(a=[],t={id:"",text:"Please select"},a.push(t),$.each(Site.matchEventtype,function(e,t){var l={};l.id=e,l.text=t,a.push(l)}),a),placeholder:"Please select",allowClear:!0}),o(Site.awayLineupPlayer),$(".match-event-player:last").html("").select2({data:o(Site.homeLineupPlayer),placeholder:"Please select",allowClear:!0})}),$(document).on("change",".match-type-of-event",function(){!function(e){var a=e.closest(".edit-match-event").attr("id"),t=e.val().trim();if(e.closest(".edit-match-event").find(".substitution-player").remove(),t&&"substitution"==t){e.closest(".edit-match-event").find(".type-of-event-main-div").append('<div class="col-xl-6 substitution-player"><div class="form-group mb-0" data-select2-id="21"><label for="match_type_of_event'+a+'">Subbed for:</label><select class="form-control js-select2 select2-substitution-player" id="substitution-player'+a+'" name="substitution_player['+a+']" style="width:100%"><option value="">Please select</option></select></div>');var l=e.closest(".edit-match-event").find(".match-event-team").children("option:selected").attr("data-type");"home"==l?e.closest(".row").find(".select2-substitution-player").select2({data:o(Site.homeBenchPlayer),placeholder:"Please select",allowClear:!0}):"away"==l&&e.closest(".row").find(".select2-substitution-player").select2({data:o(Site.awayBenchPlayer),placeholder:"Please select",allowClear:!0}),e.closest(".edit-match-event").find(".select2-substitution-player").rules("add",{required:!0,messages:{required:"This field is required."}})}}($(this))}),$(document).on("change",".match-event-team",function(){var e=$(this).closest(".edit-match-event").attr("id");"home"==$(this).children("option:selected").attr("data-type")?($(this).closest(".edit-match-event").find(".match-event-player").html("").select2({data:o(Site.homeLineupPlayer),placeholder:"Please select",allowClear:!0}),$("#substitution-player"+e).html("").select2({data:o(Site.homeBenchPlayer),placeholder:"Please select",allowClear:!0})):($(this).closest(".edit-match-event").find(".match-event-player").html("").select2({data:o(Site.awayLineupPlayer),placeholder:"Please select",allowClear:!0}),$("#substitution-player"+e).html("").select2({data:o(Site.awayBenchPlayer),placeholder:"Please select",allowClear:!0}))}),$(document).on("click",".js-match-event-delete",function(){$(this).closest(".edit-match-event").remove()}),$("#unavailable_seats_preview_remove").on("click",function(){$("#unavailable_seats").val(""),document.getElementById("lbl_unavailable_seats").innerText="Choose File",$("#unavailable_seats_preview_container").addClass("d-md-none"),$(".js-manage-unavailable-seats-width").removeClass("col-9").addClass("col-12"),$("#unavailable_seats_preview_remove").addClass("d-md-none")}),$(document).on("change","#unavailable_seats",function(e){!function(e){if(e.files&&e.files[0]){var a=new FileReader;a.onload=function(e){$(".js-manage-unavailable-seats-width").removeClass("col-12").addClass("col-9"),$("#unavailable_seats_image").hasClass("d-md-none")&&$("#unavailable_seats_image").removeClass("d-md-none"),$("#unavailable_seats_preview_remove").removeClass("d-md-none"),$("#unavailable_seats_preview_container").removeClass("d-md-none"),$("#unavailable_seats_preview_container").html('<a download href="'+e.target.result+'">Download</a>')},a.readAsDataURL(e.files[0])}}(this)}),$("#hospitality_unavailable_seats_preview_remove").on("click",function(){$("#hospitality_unavailable_seats").val(""),document.getElementById("lbl_hospitality_unavailable_seats").innerText="Choose File",$("#unavailable_hospitality_seats_preview_container").addClass("d-md-none"),$(".js-manage-hospitality-unavailable-seats-width").removeClass("col-9").addClass("col-12"),$("#hospitality_unavailable_seats_preview_remove").addClass("d-md-none")}),$(document).on("change","#hospitality_unavailable_seats",function(e){!function(e){if(e.files&&e.files[0]){var a=new FileReader;a.onload=function(e){$(".js-manage-hospitality-unavailable-seats-width").removeClass("col-12").addClass("col-9"),$("#hospitality_unavailable_seats_image").hasClass("d-md-none")&&$("#hospitality_unavailable_seats_image").removeClass("d-md-none"),$("#hospitality_unavailable_seats_preview_remove").removeClass("d-md-none"),$("#unavailable_hospitality_seats_preview_container").removeClass("d-md-none"),$("#unavailable_hospitality_seats_preview_container").html('<a download href="'+e.target.result+'">Download</a>')},a.readAsDataURL(e.files[0])}}(this)}),$(document).on("click",".manage-hide-show",function(){this.checked?($(this).closest(".tab-pane").find(".manage-hide-show-div").removeClass("d-none"),$(this).closest(".tab-pane").find(".manage-hide-show-div").addClass("d-block")):($(this).closest(".tab-pane").find(".manage-hide-show-div").removeClass("d-block"),$(this).closest(".tab-pane").find(".manage-hide-show-div").addClass("d-none"))}),$(document).on("click",".manage-ticket-type-amount",function(){this.checked?($(this).closest(".tab-pane").find(".manage-ticket-type-amount-container").removeClass("d-none"),$(this).closest(".tab-pane").find(".manage-ticket-type-amount-container").addClass("d-block")):($(this).closest(".tab-pane").find(".manage-ticket-type-amount-container").removeClass("d-block"),$(this).closest(".tab-pane").find(".manage-ticket-type-amount-container").addClass("d-none"))}),$(document).on("click",".js-remove-img",function(e){$(".js-remove-thumb:last").addClass("d-md-none")}),$(document).on("click",".logo-delete",function(){$(this).closest(".js-manage-sponsor-logo-width").remove()}),$(document).on("change",".uploadimage",function(){var e=this.files[0].name;$(this).next(".custom-file-label").css("overflow-x","hidden").html(e);var a=$(this).val().split(".").pop().toLowerCase();-1!=$.inArray(a,["png","jpg","jpeg"])&&function(e){var a=e.name;if(e.files&&e.files[0]){var t=new FileReader;t.onload=function(e){$('img[name="'+a.replace("[sponsor]","[preview]")+'"]').attr("src",e.target.result),$('div[name="'+a.replace("[sponsor]","[preview_container]")+'"]').removeClass("d-md-none")},t.readAsDataURL(e.files[0])}}(this)}),$(document).on("change","#home",function(){var e=$('#home option[value="'+Site.clubId+'"]').text();if($("#home").select2("val")==Site.clubId)$("#is_enable_ticket").removeAttr("disabled"),$("#is_enable_hospitality").removeAttr("disabled"),$("#away").select2("val",""),$('#away option[value="'+Site.clubId+'"]').detach();else if($("#is_enable_ticket").attr("disabled","disabled"),$("#is_enable_hospitality").attr("disabled","disabled"),$("#away").find("option[value='"+Site.clubId+"']").length)$("#away").val(Site.clubId).trigger("change.select2");else{var a={id:Site.clubId,text:e},t=new Option(a.text,a.id,!0,!0);$("#away").append(t).trigger("change")}}),$(document).on("change","#away",function(){var e=$('#away option[value="'+Site.clubId+'"]').text();if($("#away").select2("val")==Site.clubId)$("#is_enable_ticket").attr("disabled","disabled"),$("#is_enable_hospitality").attr("disabled","disabled"),$("#home").select2("val",""),$('#home option[value="'+Site.clubId+'"]').detach();else if($("#is_enable_ticket").removeAttr("disabled"),$("#is_enable_hospitality").removeAttr("disabled"),$("#home").find("option[value='"+Site.clubId+"']").length)$("#home").val(Site.clubId).trigger("change.select2");else{var a={id:Site.clubId,text:e},t=new Option(a.text,a.id,!0,!0);$("#home").append(t).trigger("change")}}),$(window).on("load",function(){$("#home").select2("val")==Site.clubId&&($("#away").select2("val",""),$('#away option[value="'+Site.clubId+'"]').detach()),$("#away").select2("val")==Site.clubId&&($("#home").select2("val",""),$('#home option[value="'+Site.clubId+'"]').detach())});var c=$("#add_player_form");c.validate({ignore:[],errorClass:"invalid-feedback animated fadeInDown",errorElement:"div",errorPlacement:function(e,a){$(a).parents(".form-group").append(e)},highlight:function(e){$(e).closest(".form-group").removeClass("is-invalid").addClass("is-invalid")},unhighlight:function(e){$(e).closest(".form-group").removeClass("is-invalid").removeClass("is-invalid")},success:function(e){$(e).closest(".form-group").removeClass("is-invalid"),$(e).remove()},rules:{player_name:{required:!0}}}),$("#available_blocks").select2(),$("#pricing_bands").select2(),$("#hospitality_suites").select2()}});