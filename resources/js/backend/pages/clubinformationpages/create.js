var chkCkEditorFlag = 0;
var ClubInformationCreate = function() {
    var initFormValidations = function () {
        var userForm = $('.create-club-information-form');
        userForm.validate({
            ignore: [],
            errorClass: 'invalid-feedback animated fadeInDown',
            errorElement: 'div',
            errorPlacement: function(error, e)
            {
                if (e.attr("name") == "icon") {

                    $(e).parents('.form-group .logo-input').append(error);
                }
                else {
                	$(e).parents('.form-group').append(error);
            	}
            },
            highlight: function(e) {
                if ($(e).attr("name") == "icon") {
                    $(e).removeClass('is-invalid').addClass('is-invalid');
                }
                $(e).closest('.form-group').removeClass('is-invalid').addClass('is-invalid');
            },
            unhighlight: function (e) {
                 if ($(e).attr("name") == "icon") {
                    $(e).removeClass('is-invalid').removeClass('is-invalid');
                }
                $(e).closest('.form-group').removeClass('is-invalid').removeClass('is-invalid');
            },
            success: function(e) {
                $(e).closest('.form-group').removeClass('is-invalid');
                $(e).remove();
            },
            rules: {
                'title': {
                   required: true
                },
                'publication_date': {
                    required: true
                },
                'status' : {
                    required : true,
                },
				'icon': {
					required: true,
					accept: "image/png",
					extension: "png",
					icondimension: [150, 150, 'icon'],
                },
            },
            messages: {
            	'icon': {
					accept: 'Please upload the correct file format.',
                    icondimension: 'Please upload the correct file size.'
                }
            },
        });

    };

    var uiHelperDateTimePicker = function(){
        $(".js-datetimepicker").datetimepicker({
            ignoreReadonly: true,
            format: Site.dateTimeCmsFormat,
            timeZone: Site.clubTimezone,
        });
    };

    /*
     * CKEditor init, for more examples you can check out http://ckeditor.com/
     *
     * Codebase.helper('ckeditor');
     *
     */
    var uiHelperCkeditor = function(){
        // Init full text editor
        if (jQuery('#js-ckeditor:not(.js-ckeditor-enabled)').length) {
            CKEDITOR.replace('js-ckeditor').on( 'change', function(e) {
                var editorcontent = e.editor.getData().replace(/<[^>]*>/gi, '');
                if(editorcontent.length > 0) {
                    $("."+e.editor.id).removeClass('is-invalid');
                    $("."+e.editor.id).closest('.form-group').find('.invalid-feedback').remove();
                } else {
                    if(chkCkEditorFlag == 1) {
                        $('.js-club-info-value-save').trigger('click');
                    }
                }
            });

            // Add .js-ckeditor-enabled class to tag it as activated
            jQuery('#js-ckeditor').addClass('js-ckeditor-enabled');
        }
    };
    return {
        init: function() {
            initFormValidations();
            uiHelperDateTimePicker();
            uiHelperCkeditor();
        }
    };
}();

/*
* Draggable items with jQuery, for more examples you can check out https://jqueryui.com/sortable/
*
* Codebase.helper('draggable-items');
*
*/
var uiHelperDraggableItems = function(){
    // Init draggable items functionality (with .js-draggable-items class)
    jQuery('.js-draggable-items:not(.js-draggable-items-enabled)').each(function(){
        var el = jQuery(this);

        // Add .js-draggable-items-enabled class to tag it as activated
        el.addClass('js-draggable-items-enabled');

        // Init
        el.children('.draggable-column').sortable({
            connectWith: '.draggable-column',
            items: '.draggable-item',
            dropOnEmpty: true,
            opacity: .75,
            handle: '.draggable-handler',
            placeholder: 'draggable-placeholder',
            tolerance: 'pointer',
            start: function(e, ui){
                ui.placeholder.css({
                    'height': ui.item.outerHeight(),
                    'margin-bottom': ui.item.css('margin-bottom')
                });
            }
        });
    });
};




var clubInformationPageContent = [];

var clubInformationPageContentHtml = '<div class="block block-rounded draggable-item js-draggable-display-order js-draggable-items-section-remove"><div class="block-header block-header-default"><h3 class="block-title">{title}</h3><div class="block-options"><button type="button" class="btn-block-option js-tooltip-enabled js-club-info-edit-content-section" title="" data-toggle="modal" data-target="#add_club_info_content" data-original-title="Edit"" data-index="{index}"><i class="fal fa-pencil"></i></button><button type="button" class="btn-block-option text-danger js-tooltip-enabled js-club-info-content-section-delete text-danger" data-toggle="modal" title="" data-index="{index}" data-original-title="Delete"><i class="fal fa-trash"></i></button><a class="btn-block-option draggable-handler" href="javascript:void(0)"><i class="si si-cursor-move"></i></a></div></div></div>';

// Initialize when page loads
jQuery(function() {
    ClubInformationCreate.init();
});

var sectionContentForm =  $('#section_content_form');

sectionContentForm.validate({
    ignore: [],
    errorClass: 'invalid-feedback animated fadeInDown',
    errorElement: 'div',
    errorPlacement: function(error, e)
    {
        if (e.attr("name") == "content_description") {
            $(e).parent().find('.cke_editor_js-ckeditor').addClass('is-invalid');
        }
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
        'content_title': {
           required: true
        },
        'content_description' : {
            required: function(textarea) {
                CKEDITOR.instances[textarea.id].updateElement(); // update textarea
                var editorcontent = textarea.value.replace(/<[^>]*>/gi, ''); // strip tags
                if(editorcontent.length > 0) {
                    $("#"+textarea.id).parent().find('.cke_editor_js-ckeditor').removeClass('is-invalid');
                }
                return editorcontent.length === 0;
            }
        },
    },
});

$(document).on('click', ".js-club-info-value-save", function() {

    chkCkEditorFlag = 1;

    if(!sectionContentForm.valid()) {
        return  false;
    }

    var title = $(".js-content-section-title").val();

    var description = CKEDITOR.instances['js-ckeditor'].getData();

    var addClubInfoContentSection = $('#add_edit_section_content').val();

    if(addClubInfoContentSection == 'add') {
        uiHelperDraggableItems();
        $('#add_club_info_section').show();

        clubInformationPageContent.push({'title': title , 'description': description});
        $("#addClubContent").val(JSON.stringify(clubInformationPageContent));

        var clubInfoDataContent = clubInformationPageContentHtml.replace('{title}', title).replace(/{index}/g, clubInformationPageContent.length - 1);
        $("#add_club_info_section").append(clubInfoDataContent);

    } else {
        var index = $('#add_edit_club_index').val();
        clubInformationPageContent[index].title = title;
        clubInformationPageContent[index].description = description;
        var clubInfoDataContent = clubInformationPageContentHtml.replace('{title}', title).replace(/{index}/g, clubInformationPageContent + 1);

        $("#add_club_info_section .block-title:eq("+index+")").html(title);
    }
    $(".js-content-section-title").val('');
    $('#add_club_info_content').modal('hide');
});


// Edit club information section content
$(document).on('click', ".js-club-info-edit-content-section", function() {
    editClubInfoContentSection($(this).attr('data-index'));
});

$(document).on('click', ".js-add-club-info-content", function() {
    $('.js-modal-title').html('Add contents section');
    chkCkEditorFlag = 0;
    $('#add_edit_section_content').val('add');
    $(".js-content-section-title").val('');
    $('#add_edit_club_index').val('');
    CKEDITOR.instances['js-ckeditor'].setData('');
});

function editClubInfoContentSection(index) {
    $('.js-modal-title').html('Edit contents section');
    chkCkEditorFlag = 1;
    $('#add_edit_section_content').val('edit');
    $('#add_edit_club_index').val(index);

    clubInformationPageContent[index].title;
    clubInformationPageContent[index].description;
    $('.js-content-section-title').val(clubInformationPageContent[index].title);
    CKEDITOR.instances['js-ckeditor'].setData(clubInformationPageContent[index].description);
}

if(!clubInformationPageContent.length) {
    $('#add_club_info_section').hide();
}


// Delete club information section content
$(document).on('click', ".js-club-info-content-section-delete", function() {
    deletClubInfoContentSection($(this).attr('data-index'));
    $(this).closest('.js-draggable-items-section-remove').remove();

    if(!clubInformationPageContent.length) {
        $('#add_club_info_section').hide();
    }

    var i = 0;
    $('#add_club_info_section .draggable-item').each(function(index){
        $(this).find('.js-club-info-edit-content-section, .js-club-info-content-section-delete').attr('data-index', i);
        i++;
    });
});

function deletClubInfoContentSection(index) {
   clubInformationPageContent.splice(index, 1);
}


$(".js-draggable-items").sortable({
    start: function(event, ui) {
        clubInformationDraggable = [];
    },
    stop: function(event, ui) {
        clubInformationDraggable = [];
        $('#add_club_info_section .js-draggable-display-order').each(function(index){
            var index = $(this).find('.js-club-info-edit-content-section').attr('data-index');
            var data = clubInformationPageContent[index];
            clubInformationDraggable.push(data);
        });
        clubInformationPageContent = clubInformationDraggable

        var i = 0;
        $('#add_club_info_section .draggable-item').each(function(index){
            $(this).find('.js-club-info-edit-content-section, .js-club-info-content-section-delete').attr('data-index', i);

            i++;
        });
    }
});

$(document).on('click', ".js-club-info-create-content", function() {
	$('.create-club-information-form').valid();
	var mcq_validation = mcqValidation();
	if (!mcq_validation) {
		return false;
	}
	$("#addClubContent").val(JSON.stringify(clubInformationPageContent));
    $(".js-create-club-info-content").submit();

});

// Validation for mcq validation
function mcqValidation() {
	if ($("#add_club_info_section").text().trim().length == 0) {
		$("#mcq_draggable_club_info_validation_error").addClass('mb-15');
		$("#mcq_draggable_club_info_validation_error").text('');
		$("#mcq_draggable_club_info_validation_error").text('Please add at least one content section');
		return false;
	} else {
		$("#mcq_draggable_club_info_validation_error").removeClass('mb-15');
		$("#mcq_draggable_club_info_validation_error").text('');
		return true;
	}
}


function readLogoURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
          $('.js-manage-logo-width').removeClass('col-12').addClass('col-9');
          $('#'+input.id+'_preview').attr('src', e.target.result);
          $('#'+input.id+'_preview_container').removeClass('d-md-none');
        }
        reader.readAsDataURL(input.files[0]);
    }
}
$(".uploadimage").change(function() {
       var ext = $(this).val().split('.').pop().toLowerCase();
        if($.inArray(ext, ['png','jpg','jpeg']) != -1) {
            readLogoURL(this);
        }

});


