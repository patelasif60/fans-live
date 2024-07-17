var chkCkEditorFlag = 0;
var clubInformationPageEdit = function() {

    var initFormValidations = function () {
        var userForm = $('.edit-club-information-form');

        userForm.validate({
            ignore: [],
            errorClass: 'invalid-feedback animated fadeInDown',
            errorElement: 'div',
            errorPlacement: function(error, e)
            {
            	if (e.attr("name") == "icon") {
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
                'title': {
                    required : true,
                },
                'publication_date' : {
                    required : true,
                },
                'status' : {
                    required : true,
                },
				'icon': {
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
            timeZone: Site.clubTimezone
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
                        $('.js-edit-club-info-save-value').trigger('click');
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


var clubInformationPageContent = Site.clubInfo;

var clubInformationDraggable = [];

var clubInformationPageContentHtml = '<div class="js-draggable-items block block-rounded draggable-item"><div class="block-header block-header-default"><h3 class="block-title">{title}</h3><div class="block-options"><button type="button" class="btn-block-option js-tooltip-enabled js-club-edit-content" title="" data-toggle="modal" data-target="#js_edit_club_info_section" data-original-title="Edit"" data-index="{index}"><i class="fal fa-pencil"></i></button><button type="button" class="btn-block-option js-tooltip-enabled js-club-info-content-section-delete text-danger" data-toggle="modal" title="" data-index="{index}" data-original-title="Delete"><i class="fal fa-trash"></i></button><a class="btn-block-option draggable-handler" href="javascript:void(0)"><i class="si si-cursor-move"></i></a></div></div></div>';

// Initialize when page loads
jQuery(function() {
    clubInformationPageEdit.init();
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

$(document).on('click', ".js-edit-club-info-save-value", function() {

    chkCkEditorFlag = 1;

    if(!sectionContentForm.valid()) {
        return  false;
    }

    var title = $(".js-club-info-content-section-title").val();

    var addClubInfoContentSection = $('#js_add_edit_club_info').val();

    var description = CKEDITOR.instances['js-ckeditor'].getData();

    if(addClubInfoContentSection == 'add') {
        uiHelperDraggableItems();
        $('#edit_club_info_content').show();

        clubInformationPageContent.push({'id': '0', 'title': title , 'description': description});
        $("#edit_club_info_content_section").val(JSON.stringify(clubInformationPageContent));

        var clubInfoContentSectionValue = clubInformationPageContentHtml.replace('{title}', title).replace('data-index="{index}"', "data-index='"+(clubInformationPageContent.length - 1)+"'");
        $("#edit_club_info_content").append(clubInfoContentSectionValue);

    } else {

        var index = $('#js-add_edit_club_index').val();
        clubInformationPageContent[index].description = description;
        clubInformationPageContent[index].title = title;
        clubInformationPageContent[index].description = description;

        var clubInfoContentSectionValue = clubInformationPageContentHtml.replace('{title}', title).replace('{index}', clubInformationPageContent.length + 1);

        $("#edit_club_info_content .block-title:eq("+index+")").html(title);
    }

    $(".js-club-info-content-section-title").val('');
    $('#js_edit_club_info_section').modal('hide');

});

// Edit club information content
$(document).on('click', ".js-club-edit-content", function() {
    editClubInfoContentSection($(this).attr('data-index'));
});

$(document).on('click', ".js-club-info-content-section", function() {
    $('.js-modal-title').html('Add contents section');
    chkCkEditorFlag = 0;
    $('#js_add_edit_club_info').val('add');
    $(".js-club-info-content-section-title").val('');
    $('#js-add_edit_club_index').val('');
    CKEDITOR.instances['js-ckeditor'].setData('');
});


function editClubInfoContentSection(index) {
    $('.js-modal-title').html('Edit contents section');
    chkCkEditorFlag = 1;
    $('#js_add_edit_club_info').val('edit');
    $('#js-add_edit_club_index').val(index);

    $('#add-edit-id').val(clubInformationPageContent[index].id);
    $('.js-club-info-content-section-title').val(clubInformationPageContent[index].title);
    CKEDITOR.instances['js-ckeditor'].setData(clubInformationPageContent[index].description);
}

if(!clubInformationPageContent.length) {
    $('#edit_club_info_content').hide();
}

// Delete club information content
$(document).on('click', ".js-club-info-content-section-delete", function() {
    deleteClubInfoContent($(this).attr('data-index'));
    $(this).closest('.draggable-item').remove();

    if(!clubInformationPageContent.length) {
        $('#edit_club_info_content').hide();
    }

    var i = 0;
    $('#edit_club_info_content .draggable-item').each(function(index){
        $(this).find('.js-club-edit-content, .js-club-info-content-section-delete').attr('data-index', i);

        i++;
    });
});

function deleteClubInfoContent(index) {
   clubInformationPageContent.splice(index, 1);
}

$(".js-draggable-items").sortable({
    start: function(event, ui) {
        clubInformationDraggable = [];
    },
    stop: function(event, ui) {
        clubInformationDraggable = [];
        $('#edit_club_info_content .draggable-item').each(function(index){
            var index = $(this).find('.js-club-edit-content').attr('data-index');
            var data = clubInformationPageContent[index];
            clubInformationDraggable.push(data);
        });
        clubInformationPageContent = clubInformationDraggable

        var i = 0;
        $('#edit_club_info_content .draggable-item').each(function(index){
            $(this).find('.js-club-edit-content, .js-club-info-content-section-delete').attr('data-index', i);

            i++;
        });
    }
});


$(document).on('click', ".js-save-club-info-content-section", function() {
	$('.edit-club-information-form').valid();
	var mcq_validation = mcqValidation();
	if (!mcq_validation) {
		return false;
	}
    $("#edit_club_info_content_section").val(JSON.stringify(clubInformationPageContent));
    $(".js-edit-club-info-content").submit();
});

// Validation for mcq validation
function mcqValidation() {
	if ($("#edit_club_info_content").text().trim().length == 0) {
		$("#mcq_draggable_content_validation_error").addClass('mb-15');
		$("#mcq_draggable_content_validation_error").text('');
		$("#mcq_draggable_content_validation_error").text('Please add at least one content section');
		return false;
	} else {
		$("#mcq_draggable_content_validation_error").removeClass('mb-15');
		$("#mcq_draggable_content_validation_error").text('');
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
