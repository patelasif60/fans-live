var categoryEdit = function() {

    var initFormValidations = function () {
        var userForm = $('.edit-news-form');

        userForm.validate({
            ignore: [],
            errorClass: 'invalid-feedback animated fadeInDown',
            errorElement: 'div',
            errorPlacement: function(error, e)
            {
				if (e.attr("name") == "logo") {
					$(e).parents('.form-group .logo-input').append(error);
				} else {
					$(e).parents('.form-group').append(error);
				}
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
                    required : true,
                },'pubdate' : {
                    required : true,
                },
				'logo': {
					accept: "image/png",
					extension: "png",
					icondimension: [840, 525,'logo'],
				}
            },
            messages: {
                'logo': {
                    accept: 'Please upload the correct file format.',
                    icondimension: 'Please upload the correct file size.'
                }
            }
        });
        $('.edit-news-form').data('validator').settings.ignore = ".note-editor *";
    };

    var  uiHelperDateTimePicker = function(){
        $(".js-datetimepicker").datetimepicker({
            ignoreReadonly: true,
            format: Site.dateTimeCmsFormat,
            timeZone: Site.clubTimezone
        });
    };

    /*
    * Summernote, for more examples you can check out https://github.com/summernote/summernote/
    *
    * Codebase.helper('summernote');
    *
    */
    var uiHelperCkeditor = function(){
        if (jQuery('#js-ckeditor:not(.js-ckeditor-enabled)').length) {
            CKEDITOR.replace('js-ckeditor',{
                toolbar: [
                	['Bold','Link','Maximize','Source']
                ],
            },);
            jQuery('#js-ckeditor').addClass('js-ckeditor-enabled');
        }
    };
    return {
        init: function() {
            initFormValidations();
            uiHelperCkeditor();
            uiHelperDateTimePicker();
        }
    };
}();

// Initialize when page loads
jQuery(function() {
    categoryEdit.init();
});

function readLogoURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $('.js-manage-logo-width').removeClass('col-12').addClass('col-9');
            if ($('#logo_preview_container').hasClass('d-md-none')) {
                $('#logo_preview_container').removeClass('d-md-none');

            }
			$('#remove').removeClass('d-md-none');
			//$('#logo_preview_container').removeClass('d-md-none');
            $('.logo_preview_container').html('<img id="logo_preview" alt="Category logo" src="' + e.target.result + '" /><a href="#" id="remove" name="remove" class="close-preview" data-toggle="tooltip" title="Delete"><i class="far fa-trash-alt text-muted"></i> </a>');
        }
        reader.readAsDataURL(input.files[0]);
    }
}

$("#logo").change(function() {
    readLogoURL(this);
});

$(document).on('click','#remove',function() {
	event.preventDefault();
    $('#logo').val('');
    $('#logo_edit').val('');
    let lbl = document.getElementById('lbl_logo');
	lbl.innerText = "Choose File";
	$('#logo_preview_container').addClass('d-md-none');
	$('#remove').addClass('d-md-none');
    $('#logo_preview').attr('src', '');
	$('.js-manage-logo-width').removeClass('col-9').addClass('col-12');
});
