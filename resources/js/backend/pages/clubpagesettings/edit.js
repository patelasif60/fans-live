var ClubAppSettingsPage = function() {

    var initFormValidations = function () {
        var userForm = $('.club-app-settings-form');

        userForm.validate({
            ignore: [],
            errorClass: 'invalid-feedback animated fadeInDown',
            errorElement: 'div',
            errorPlacement: function(error, e) 
            {
                if (e.attr("name") == "hospitality_post_purchase_text" ||  e.attr("name") == "hospitality_introduction_text" || e.attr("name") == "membership_packages_introduction_text") {
                    $(e).closest(".form-group").find('.cke').addClass('is-invalid');
                    $(e).parents('.form-group .col-12').append(error);
                } else {
                    $(e).parents('.form-group').append(error);
                }
                showTabError();
            },
            highlight: function(e) {
                $(e).closest('.form-group').removeClass('is-invalid').addClass('is-invalid');
            },
            unhighlight: function (e) {
                $(e).closest('.form-group').removeClass('is-invalid').removeClass('is-invalid');
            },
            success: function(e) {
                $(e).closest('.form-group').removeClass('is-invalid');
                $(e).remove();
            },
            rules: {
                'food_and_drink_minutes_open_before_kickoff' : {
                    required : true,
                    digits: true
                },
                'food_and_drink_minutes_closed_after_fulltime' : {
                    required : true,
                    digits: true
                },
                'merchandise_minutes_open_before_kickoff' : {
                    required : true,
                    digits: true
                },
                'merchandise_minutes_closed_after_fulltime' : {
                    required : true,
                    digits: true
                },
                'loyalty_rewards_minutes_open_before_kickoff' : {
                    required : true,
                    digits: true
                },
                'loyalty_rewards_minutes_closed_after_fulltime' : {
                    required : true,
                    digits: true
                },
                'food_and_drink_reward_percentage' : {
                    required : true,
                    number: true
                },
                'merchandise_reward_percentage' : {
                    required : true,
                    number: true
                },
                'tickets_reward_percentage' : {
                    required : true,
                    number: true
                },
                'membership_packages_reward_percentage' : {
                    required : true,
                    number: true
                },
                'hospitality_reward_percentage' : {
                    required : true,
                    number: true
                },
                'events_reward_percentage' : {
                    required : true,
                    number: true
                },
                'hospitality_post_purchase_text' : {
                    required: function(textarea) {
                        CKEDITOR.instances[textarea.id].updateElement(); // update textarea
                        var editorcontent = textarea.value.replace(/<[^>]*>/gi, ''); // strip tags
                        if(editorcontent.length > 0) {
                            $("#"+textarea.id).parent().find('.cke').removeClass('is-invalid');
                        }
                        return editorcontent.length === 0;
                    }
                },
                'hospitality_introduction_text' : {
                    required: function(textarea) {
                        CKEDITOR.instances[textarea.id].updateElement(); // update textarea
                        var editorcontent = textarea.value.replace(/<[^>]*>/gi, ''); // strip tags
                        if(editorcontent.length > 0) {
                            $("#"+textarea.id).parent().find('.cke').removeClass('is-invalid');
                        }
                        return editorcontent.length === 0;
                    }
                },
                'membership_packages_introduction_text' : {
                    required: function(textarea) {
                        CKEDITOR.instances[textarea.id].updateElement(); // update textarea
                        var editorcontent = textarea.value.replace(/<[^>]*>/gi, ''); // strip tags
                        if(editorcontent.length > 0) {
                            $("#"+textarea.id).parent().find('.cke').removeClass('is-invalid');
                        }
                        return editorcontent.length === 0;
                    }
                },
            }
        });
        $('.club-app-settings-form').data('validator').settings.ignore = ".note-editor *";
    };
    
    /*
    * Summernote, for more examples you can check out https://github.com/summernote/summernote/
    *
    * Codebase.helper('summernote');
    *
    */

    var uiHelperCkeditor = function(){
        if (jQuery('#js-purchase-text:not(.js-purchase-text-enabled)').length) {
            CKEDITOR.replace('js-purchase-text',{
                toolbar: [
                    ['Bold','Link','Maximize','Source']
                ],
            },).on( 'change', function(e) { 
                checkEditorData(e);
            });
            jQuery('#js-purchase-text').addClass('js-purchase-text-enabled');
        }

        if (jQuery('#js-introduction-text:not(.js-introduction-text-enabled)').length) {
            CKEDITOR.replace('js-introduction-text',{
                toolbar: [
                    ['Bold','Link','Maximize','Source']
                ],
            },).on( 'change', function(e) { 
                checkEditorData(e);
            });
            jQuery('#js-introduction-text').addClass('js-introduction-text-enabled');
        }

        if (jQuery('#js-packages-introduction-text:not(.js-packages-introduction-text-enabled)').length) {
            CKEDITOR.replace('js-packages-introduction-text',{
                toolbar: [
                    ['Bold','Link','Maximize','Source']
                ],
            },).on( 'change', function(e) { 
                checkEditorData(e);
            });
            jQuery('#js-packages-introduction-text').addClass('js-packages-introduction-text-enabled');
        }
    };
    return {
        init: function() {
            initFormValidations();
            uiHelperCkeditor();
        }
    };
}();

function checkEditorData(e)
{
    var editorcontent = e.editor.getData().replace(/<[^>]*>/gi, '');
    if(editorcontent.length > 0) {
        $("."+e.editor.id).removeClass('is-invalid');
        $("."+e.editor.id).closest('.form-group').find('.invalid-feedback').remove();
    } else {
        $('.js-clubpagesetting-save').trigger('click');
    }
}

// Initialize when page loads
jQuery(function() { 
    ClubAppSettingsPage.init();
});