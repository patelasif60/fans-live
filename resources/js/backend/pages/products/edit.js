var productEdit = function() {

    var initFormValidations = function () {
        var productForm = $('.edit-product-form');

        productForm.validate({
            ignore: [],
            errorClass: 'invalid-feedback animated fadeInDown',
            errorElement: 'div',
            errorPlacement: function(error, e)
            {
				if (e.attr("name") == "logo") {
					$(e).parents('.form-group .logo-input').append(error);
				} else if (e.attr("name") == "description") {
                    $(e).parent().find('.cke_editor_js-ckeditor').addClass('is-invalid');
                    $(e).parents('.form-group').append(error);
                } else {
					$(e).parents('.form-group').append(error);
				}
                showTabError();
            },
            highlight: function(e) {
                $(e).closest('.form-group').removeClass('is-invalid').addClass('is-invalid');
            },
            unhighlight: function (e) {
                $(e).closest('.form-group').removeClass('is-invalid');
            },
            success: function(e) {
                $(e).closest('.form-group').removeClass('is-invalid');
                $(e).remove();
            },
            rules: {
                'title' : {
                    required : true,
                },
                'short_description' : {
                    required : true,
                },
				'price': {
                    required: true,
                    number: true,
                    min: 0
                },
                'vat_rate': {
                    required: true,
                    number: true,
                    min: 0
                },
				'description': {
                    required: function(textarea){
						CKEDITOR.instances[textarea.id].updateElement();
						var editorcontent = textarea.value.replace(/<[^>]*>/gi, '');
                        if(editorcontent.length > 0) {
                            $("#"+textarea.id).parent().find('.cke_editor_js-ckeditor').removeClass('is-invalid');
                        }
						return editorcontent.length === 0;
					}
                },
				'rewards_percentage_override': {
                    number: true,
                    min: 0,
                    max: 100
                },
				'logo': {
					required: function(text) {
						return $('#image_file_name').val() ? false : true;
					},
					accept: "image/png",
					extension: "png",
					icondimension: [840, 630,'logo'],
				}
			},
            messages: {
                'logo': {
                    accept: 'Please upload the correct file format.',
                    icondimension: 'Please upload the correct file size.'
                }
            }
        });
        $('.edit-product-form').data('validator').settings.ignore = ".note-editor *";
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
            },).on( 'change', function(e) {
                var editorcontent = e.editor.getData().replace(/<[^>]*>/gi, '');
                if(editorcontent.length > 0) {
                    $("."+e.editor.id).removeClass('is-invalid');
                    $("."+e.editor.id).closest('.form-group').removeClass('is-invalid');
                    $("."+e.editor.id).closest('.form-group').find('.invalid-feedback').remove();
                } else {
                    $('#btn_product_submit').trigger('click');
                }
                showTabError();
            });
            jQuery('#js-ckeditor').addClass('js-ckeditor-enabled');
        }
    };

	var manageOnLoad = function() {
		 $(document).on('click', ".js-custom-option-btn", function() {
            if($('.add-home-team').attr('id') >= 0) {
                var addCustomOptionId = parseInt($('#edit_custom_option').children().last().attr('id'))+1;
            }
            else {
                var addCustomOptionId = 0;
            }
            addCustomOption(addCustomOptionId);
        });

		$(document).on('click','.js-custom-option-delete',function(){
			$(this).closest('.add-home-team').remove();
		});

		// $(document).on('click', '#btn_product_submit', function () {
  //           checkTabsvalidation();
  //       });

	};

    return {
        init: function() {
			manageOnLoad();
            initFormValidations();
            uiHelperCkeditor();
			formLineUpsValidation();
        }
    };
}();

// Initialize when page loads
jQuery(function() {
    productEdit.init();
});

function checkTabsvalidation() {
    setTimeout(function () {
        var currentActiveTab = $(".nav-tabs > li > a.active").attr("href");
        var validationCount = 0;
        if (currentActiveTab == "#btabs-settings") {

            CKEDITOR.instances["js-ckeditor"].updateElement();
            validationCount = $('#btabs-settings').find('.is-invalid').length;

            var editorText = $("#js-ckeditor").val();
            if (editorText != "" && validationCount == 1) {
                validationCount -= 1;
            }

            var prigingCount = $('#btabs-pricing').find('.is-invalid').length;
            if (validationCount == 0 && prigingCount > 0) {
                $('.nav-tabs a[href="#btabs-pricing"]').tab('show');
            }

        } else if (currentActiveTab == "#btabs-pricing") {
            CKEDITOR.instances["js-ckeditor"].updateElement();
			validationCount = $('#btabs-pricing').find('.is-invalid').length;
            var settingCount = $('#btabs-settings').find('.is-invalid').length;

            var editorText = $("#js-ckeditor").val();
            if (editorText != "" && settingCount == 1) {
                settingCount -= 1;
            }

            if (validationCount == 0 && settingCount > 0) {
                $('.nav-tabs a[href="#btabs-settings"]').tab('show');
            }

        } else {
			CKEDITOR.instances["js-ckeditor"].updateElement();
            var settingCount = $('#btabs-settings').find('.is-invalid').length;
            var prigingCount = $('#btabs-pricing').find('.is-invalid').length;

            var editorText = $("#js-ckeditor").val();
            if (editorText != "" && settingCount == 1) {
                settingCount -= 1;
            }

            if (settingCount > 0) {
                $('.nav-tabs a[href="#btabs-settings"]').tab('show');
            } else if (prigingCount > 0) {
                $('.nav-tabs a[href="#btabs-pricing"]').tab('show');
            }
        }
    }, 0);
}

function readLogoURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $('.js-manage-logo-width').removeClass('col-12').addClass('col-9');
            if ($('#logo_preview_div').hasClass('d-md-none')) {
                $('#logo_preview_div').removeClass('d-md-none');
            }
            $('#logo_preview_container').html('<div class="logo_preview_container"><img id="logo_preview" src="' + e.target.result + '" /></div>');
        }
        reader.readAsDataURL(input.files[0]);
    }
}

$("#logo").change(function() {
    readLogoURL(this);
});

function formLineUpsValidation() {
    $('.custom-option-name-cls').each(function () {
        $(this).rules('add', {
            required: true,
            messages: {
                required: "This field is required."
            }
        });
    });
    $('.custom-option-number-cls').each(function () {
		$(this).rules('add', {
			required: true,
			messages: {
				required: "This field is required."
			}
		});
	});
};

function addCustomOption(addCustomOptionId)
{
    var addCustomOptionHtml = '<div class="block block-bordered block-default block-rounded js-home-main-div"><div class="block-header block-header-default"><div></div><div class="block-options"><button type="button" class="btn-block-option js-custom-option-delete text-danger" title="" data-original-title="Delete"><i class="fal fa-trash"></i></button>			</div></div><div class="block-content"><div class="row"><div class="col-xl-4"><div class="form-group"><label for="additional_cost" class="required">Additional base cost:</label><div class="input-group"><div class="input-group-text"><i class="font-size-sm font-w600 text-uppercase text-muted">'+Site.currencySymbol+'</i></div><input type="text" min="0" class="form-control custom-option-number-cls" id="additional_cost'+addCustomOptionId+'" name="additional_cost['+addCustomOptionId+']" value=""></div></div></div><div class="col-xl-4"><div class="form-group"><label for="name" class="required">Name:</label><input type="text" class="form-control custom-option-name-cls" id="name'+addCustomOptionId+'" name="name['+addCustomOptionId+']" value=""></div></div></div></div></div>';
    //var addCustomOptionHtml = '<div class="block block-bordered block-default block-rounded js-home-main-div"><div class="block-header block-header-default"><div></div><div class="block-options"><button type="button" class="btn-block-option js-custom-option-delete text-danger" title="" data-original-title="Delete"><i class="fal fa-trash"></i></button>			</div></div><div class="block-content"><div class="row"><div class="col-xl-4"><div class="form-group"><label for="name" class="required">Name:</label><input type="text" class="form-control custom-option-name-cls" id="name'+addCustomOptionId+'" name="name['+addCustomOptionId+']" value=""></div></div><div class="col-xl-4"><div class="form-group"><label for="additional_cost" class="required">Additional base cost:</label><input type="text" min="0" class="form-control custom-option-number-cls" id="additional_cost'+addCustomOptionId+'" name="additional_cost['+addCustomOptionId+']" value=""></div></div></div></div></div>';
    $('#edit_custom_option').append('<div class="col-xl-12 add-home-team" id='+addCustomOptionId+'>'+addCustomOptionHtml+'</div>');
    formLineUpsValidation();
}
