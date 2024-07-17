var VideoCreate = function() {

    var $source = $('#video_here');

    var initFormValidations = function () {

        var userForm = $('.create-video-form');

        userForm.validate({
            ignore: [],
            errorClass: 'invalid-feedback animated fadeInDown',
            errorElement: 'div',
            errorPlacement: function(error, e)
            {
                if (e.attr("name") == "thumbnail" || e.attr("name") == "video" || e.attr("name") == "access") {
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
                'title' : {
                    required : true,
                },
                'description' : {
                    required : true,
                },
                'pubdate' : {
                    required : true,
                },
				'access[]': {
					required: true,
					//extension: 'ogg|ogv|avi|mpe?g|mov|wmv|flv|mp4'
				},
                'video': {
                    required: true,
                    extension: 'mov|mp4'
                },
                'thumbnail': {
					required: true,
					accept: "image/png",
					extension: "png",
					icondimension: [840, 525,'thumbnail'],
				},
            },
            messages: {
                'thumbnail': {
                    accept: 'Please upload the correct file format.',
                    icondimension: 'Please upload the correct file size.'
                },
                'video': {
                     extension: 'Please enter a value with a valid extension (.mov, .mp4)'
                }
            }
        });
    };

    var uiHelperDateTimePicker = function(){
        $(".js-datetimepicker").datetimepicker({
            ignoreReadonly: true,
            format: Site.dateTimeCmsFormat,
            timeZone: Site.clubTimezone
        });
    };

    var initImageLoad = function() {
        $("#thumbnail").change(function() {
            var ext = $(this).val().split('.').pop().toLowerCase();
            if($.inArray(ext, ['png','jpg','jpeg']) != -1) {
                readImageURL(this);
            }
        });
    };

    var readImageURL = function(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
              $('.js-manage-thumbnail-width').removeClass('col-12').addClass('col-9');
              $('#thumbnail_preview').attr('src', e.target.result);
              $('#thumbnail_preview_container').removeClass('d-md-none');
            }
            reader.readAsDataURL(input.files[0]);
        }
    };

    var initVideoLoad = function() {
        $(document).on("change", "#video", function(evt) {
            var ext = $(this).val().split('.').pop().toLowerCase();
            if($.inArray(ext, ['ogg','ogv','avi','mpe?g','mov','wmv','flv','mp4']) != -1) {
                readVideoURL(this.files[0]);
            }
        });

        $('#view_video').on('hide.bs.modal', function (e) {
            $source.parent()[0].pause();
        });
    };


    var readVideoURL = function(input) {
        $source[0].src = URL.createObjectURL(input);
        $source.parent()[0].load();
        $('.js-manage-video-width').removeClass('col-12').addClass('col-9');
        $('#video_preview_container').removeClass('d-md-none');
    };

    return {
        init: function() {
            initFormValidations();
            uiHelperDateTimePicker();
            initImageLoad();
            initVideoLoad();
        }
    };
}();

// Initialize when page loads
jQuery(function() {
    VideoCreate.init();
});

$(".default-package").click(function() {
    if(this.checked) {
        $(".premium-package").prop('checked', true);
        $(".premium-package").prop('disabled', true);
    } else {
        $(".premium-package").prop('checked', false);
        $(".premium-package").prop('disabled', false);
    }
});