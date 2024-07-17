var contentFeedEdit = function() {

    var initFormValidations = function () {
        var competitionForm = $('.edit-content-feed-form');

        competitionForm.validate({
            ignore: [],
            errorClass: 'invalid-feedback animated fadeInDown',
            errorElement: 'div',
            errorPlacement: function(error, e)
            {
                $(e).parents('.form-group').append(error);
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
                'name' : {
                    required : true
                },
                'api_app_id' : {
                    required: {
                        depends: function(element) {
                        	return $(element).closest('form').find('.js-content-feed-type').val() == "Twitter"?$(element).closest('form').find('.js-content-feed-type').val() == "Twitter":$(element).closest('form').find('.js-content-feed-type').val() == "Facebook" ? $(element).closest('form').find('.js-content-feed-type').val() == "Facebook" : $(element).closest('form').find('.js-content-feed-type').val() == "Instagram";
                        }
                    } 
                },
                'api_key' : {
                    required: {
                        depends: function(element) {
                        	return $(element).closest('form').find('.js-content-feed-type').val() == "Twitter"?$(element).closest('form').find('.js-content-feed-type').val() == "Twitter":$(element).closest('form').find('.js-content-feed-type').val() == "Youtube";
                        }
                    } 
                },
                'api_token' : {
                    required: {
                        depends: function(element) {
                        	return $(element).closest('form').find('.js-content-feed-type').val() == "Twitter"?$(element).closest('form').find('.js-content-feed-type').val() == "Twitter":$(element).closest('form').find('.js-content-feed-type').val() == "Facebook" ? $(element).closest('form').find('.js-content-feed-type').val() == "Facebook" : $(element).closest('form').find('.js-content-feed-type').val() == "Instagram";
                        	
                        }
                    } 
                },
                'api_secret_key' : {
                    required: {
                        depends: function(element) {
                        	return $(element).closest('form').find('.js-content-feed-type').val() == "Twitter"?$(element).closest('form').find('.js-content-feed-type').val() == "Twitter":$(element).closest('form').find('.js-content-feed-type').val() == "Facebook";
                        }
                    } 
                },
                'api_token_secret_key' : {
                    required: {
                        depends: function(element) {
                        	return $(element).closest('form').find('.js-content-feed-type').val() == "Twitter";
                        }
                    } 
                },
                'api_channel_id' : {
                    required: {
                        depends: function(element) {
                        	return $(element).closest('form').find('.js-content-feed-type').val() == "Youtube";
                        }
                    } 
                },
                'rss_url' : {
                    required: {
                        depends: function(element) {
                            return $(element).closest('form').find('.js-content-feed-type').val() == "RSS";
                        }
                    },
                    url: true
                }
            }
        });
    };

    return {
        init: function() {
            initFormValidations();
        }
    };
}();

// Initialize when page loads
jQuery(function() { 
    contentFeedEdit.init();
});
$( document ).ready( function() {
	if($('.js-content-feed-type').val() == 'RSS'){ 
        $('.js-content-feed-app-id,.js-content-feed-channel-id,.js-content-feed-app-key,.js-content-feed-app-token,.js-content-feed-secert-key,.js-content-feed-token-secert-key').hide();   
        $('.js-content-feed-rss').show();
    } 
    else if($('.js-content-feed-type').val() == 'Youtube'){
    	$('.js-content-feed-app-id,.js-content-feed-rss,.js-content-feed-app-token,.js-content-feed-secert-key,.js-content-feed-token-secert-key').hide();
    	$('.js-content-feed-app-key').show();
        $('.js-content-feed-channel-id').show();
    }
    else if($('.js-content-feed-type').val() == 'Twitter'){
    	$('.js-content-feed-app-id,.js-content-feed-app-key,.js-content-feed-app-token,.js-content-feed-secert-key,.js-content-feed-token-secert-key').show(); 
    	$('.js-content-feed-rss').hide();
        $('.js-content-feed-channel-id').hide();
    }
    else if($('.js-content-feed-type').val() == 'Facebook'){
    	$('.js-content-feed-app-key,.js-content-feed-rss,.js-content-feed-token-secert-key,.js-content-feed-channel-id').hide();
    	$('.js-content-feed-app-id').show();
    	$('.js-content-feed-secert-key').show();
        $('.js-content-feed-app-token').show();
    }
    else {
    	$('.js-content-feed-channel-id,.js-content-feed-rss,.js-content-feed-app-token,.js-content-feed-secert-key,.js-content-feed-token-secert-key,.js-content-feed-app-id,.js-content-feed-app-key').hide();
        $('.js-content-feed-app-token').show();
        $('.js-content-feed-app-id').show();
    }

    $(document).on('change', '.js-content-feed-type', function(){
    	if($(this).val() == 'RSS'){ 
            $('.js-content-feed-app-id,.js-content-feed-channel-id,.js-content-feed-app-key,.js-content-feed-app-token,.js-content-feed-secert-key,.js-content-feed-token-secert-key').hide();   
            $('.js-content-feed-rss').show();
        } 
        else if($(this).val() == 'Youtube'){
        	$('.js-content-feed-app-id,.js-content-feed-rss,.js-content-feed-app-token,.js-content-feed-secert-key,.js-content-feed-token-secert-key').hide();
        	$('.js-content-feed-app-key').show();
            $('.js-content-feed-channel-id').show();
        }
        else if($(this).val() == 'Twitter'){
        	$('.js-content-feed-app-id,.js-content-feed-app-key,.js-content-feed-app-token,.js-content-feed-secert-key,.js-content-feed-token-secert-key').show(); 
        	$('.js-content-feed-rss').hide();
            $('.js-content-feed-channel-id').hide();
        }
        else if($('.js-content-feed-type').val() == 'Facebook'){
        	$('.js-content-feed-app-key,.js-content-feed-rss,.js-content-feed-token-secert-key,.js-content-feed-channel-id').hide();
        	$('.js-content-feed-app-id').show();
        	$('.js-content-feed-secert-key').show();
            $('.js-content-feed-app-token').show();
        }
        else {
        	$('.js-content-feed-channel-id,.js-content-feed-rss,.js-content-feed-app-token,.js-content-feed-secert-key,.js-content-feed-token-secert-key,.js-content-feed-app-id,.js-content-feed-app-key').hide();
            $('.js-content-feed-app-token').show();
            $('.js-content-feed-app-id').show();
        }
    });
});

