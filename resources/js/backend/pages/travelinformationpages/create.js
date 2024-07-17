var chkCkEditorFlag = 0;
var TravelInformationCreate = function () {
	var initFormValidations = function () {
		var userForm = $('.create-travel-information-form');
		userForm.validate({
			ignore: [],
			errorClass: 'invalid-feedback animated fadeInDown',
			errorElement: 'div',
			errorPlacement: function (error, e) {
				if (e.attr("name") == "icon") {
					$(e).parents('.form-group .logo-input').append(error);
				} else {
					$(e).parents('.form-group').append(error);
				}
			},
			highlight: function (e) {
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
			success: function (e) {
				if ($(e).attr("name") == "icon") {
					$(e).removeClass('is-invalid');
				}
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
				'status': {
					required: true,
				},
				'logo': {
					accept: "image/png",
					extension: "png",
					icondimension: [840, 630, 'logo'],
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
				},
				'logo': {
					accept: 'Please upload the correct file format.',
					icondimension: 'Please upload the correct file size.'
				}
			},
		});

	};

	var uiHelperDateTimePicker = function () {
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
	var uiHelperCkeditor = function () {
		// Init full text editor
		if (jQuery('#js-ckeditor:not(.js-ckeditor-enabled)').length) {
			CKEDITOR.replace('js-ckeditor').on('change', function (e) {
				var editorcontent = e.editor.getData().replace(/<[^>]*>/gi, '');
				if (editorcontent.length > 0) {
					$("." + e.editor.id).removeClass('is-invalid');
					$("." + e.editor.id).parent().find('.invalid-feedback').remove();
				} else {
					if (chkCkEditorFlag == 1) {
						$('.js-travel-info-value-save').trigger('click');
					}
				}
			});

			// Add .js-ckeditor-enabled class to tag it as activated
			jQuery('#js-ckeditor').addClass('js-ckeditor-enabled');
		}
	};

	return {
		init: function () {
			initFormValidations();
			uiHelperDateTimePicker();
			uiHelperCkeditor();
			/*ValidateAuthorImage();*/

			/*function ValidateAuthorImage(event, obj) {
				var files = event.target.files;
				var valid = true;
				var height = 0;
				var width = 0;
				var _URL = window.URL || window.webkitURL;
				for (var i = 0; i < files.length; i++) {
					var img = new Image();
					img.onload = function () {
						height = img.height;
						width = img.width;
						alert(width + " " + height);
						if (width <= 0 || width != height) {
							$("#lblErrorMessageAuthorImage").html("Please upload author image in squire.");
							obj.value = "";
							return false;
						} else {
							$("#lblErrorMessageAuthorImage").html("");
						}
					}
					img.src = _URL.createObjectURL(files[i]);
				}
			}*/


		}
	};
}();

/*
* Draggable items with jQuery, for more examples you can check out https://jqueryui.com/sortable/
*
* Codebase.helper('draggable-items');
*
*/
var uiHelperDraggableItems = function () {
	// Init draggable items functionality (with .js-draggable-items class)
	jQuery('.js-draggable-items:not(.js-draggable-items-enabled)').each(function () {
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
			start: function (e, ui) {
				ui.placeholder.css({
					'height': ui.item.outerHeight(),
					'margin-bottom': ui.item.css('margin-bottom')
				});
			}
		});
	});
};


var travelInformationPageContent = [];

var travelInformationPageContentHtml = '<div class="block block-rounded draggable-item js-draggable-display-order js-draggable-items-section-remove"><div class="block-header block-header-default"><h3 class="block-title">{title}</h3><div class="block-options"><button type="button" class="btn-block-option js-tooltip-enabled js-travel-info-edit-content-section" title="" data-toggle="modal" data-target="#add_travel_info_content" data-original-title="Edit"" data-index="{index}"><i class="fal fa-pencil"></i></button><button type="button" class="btn-block-option text-danger js-tooltip-enabled js-travel-info-content-section-delete" data-toggle="modal" title="" data-index="{index}" data-original-title="Delete"><i class="fal fa-trash"></i></button><a class="btn-block-option draggable-handler" href="javascript:void(0)"><i class="si si-cursor-move"></i></a></div></div></div>';

var travelInformationContentSection = '';

// Initialize when page loads
jQuery(function () {
	TravelInformationCreate.init();
});

var sectionContentForm = $('#section_content_form');


sectionContentForm.validate({
	ignore: [],
	errorClass: 'invalid-feedback animated fadeInDown',
	errorElement: 'div',
	errorPlacement: function (error, e) {
		if (e.attr("name") == "content_description") {
			$(e).parent().find('.cke_editor_js-ckeditor').addClass('is-invalid');
			$(e).parents('.form-group').append(error);
		} else {
			$(e).parents('.form-group').append(error);
		}
	},
	highlight: function (e) {
		$(e).closest('.form-group').removeClass('is-invalid').addClass('is-invalid');
	},
	unhighlight: function (e) {
		$(e).closest('.form-group').removeClass('is-invalid').removeClass('is-invalid');
	},
	success: function (e) {
		$(e).closest('.form-group').removeClass('is-invalid');
		$(e).remove();
	},
	rules: {
		'content_title': {
			required: true
		},
		'content_description': {
			required: function (textarea) {
				CKEDITOR.instances[textarea.id].updateElement(); // update textarea
				var editorcontent = textarea.value.replace(/<[^>]*>/gi, ''); // strip tags
				if (editorcontent.length > 0) {
					$("#" + textarea.id).parent().find('.cke_editor_js-ckeditor').removeClass('is-invalid');
				}
				return editorcontent.length === 0;
			},

		},


	},
});


$(document).on('click', ".js-travel-info-value-save", function () {

	chkCkEditorFlag = 1;

	if (!sectionContentForm.valid()) {
		return false;
	}

	var title = $(".js-content-section-title").val();

	var description = CKEDITOR.instances['js-ckeditor'].getData();

	var addTravelInfoContentSection = $('#add_edit_section_content').val();
	if (addTravelInfoContentSection == 'add') {
		uiHelperDraggableItems();
		$('#add_travel_info_section').show();

		travelInformationPageContent.push({'title': title, 'description': description});
		$("#addTravelContent").val(JSON.stringify(travelInformationPageContent));

		var travelInfoDataContent = travelInformationPageContentHtml.replace('{title}', title).replace(/{index}/g, travelInformationPageContent.length - 1);
		$("#add_travel_info_section").append(travelInfoDataContent);

	} else {
		var index = $('#add_edit_travel_index').val();
		travelInformationPageContent[index].title = title;
		travelInformationPageContent[index].description = description;
		var travelInfoDataContent = travelInformationPageContentHtml.replace('{title}', title).replace(/{index}/g, travelInformationPageContent + 1);

		$("#add_travel_info_section .block-title:eq(" + index + ")").html(title);
	}
	$(".js-content-section-title").val('');
	$('#add_travel_info_content').modal('hide');

});


// Edit travel information section content
$(document).on('click', ".js-travel-info-edit-content-section", function () {
	editTravelInfoContentSection($(this).attr('data-index'));
});

$(document).on('click', ".js-add-travel-info-content", function () {
    $('.js-modal-title').html('Add contents section');
	chkCkEditorFlag = 0;
	$('#add_edit_section_content').val('add');
	$(".js-content-section-title").val('');
	$('#add_edit_travel_index').val('');
	CKEDITOR.instances['js-ckeditor'].setData('');
});

function editTravelInfoContentSection(index) {
    $('.js-modal-title').html('Edit contents section');
	chkCkEditorFlag = 1;
	$('#add_edit_section_content').val('edit');
	$('#add_edit_travel_index').val(index);

	travelInformationPageContent[index].title;
	travelInformationPageContent[index].description;
	$('.js-content-section-title').val(travelInformationPageContent[index].title);
	CKEDITOR.instances['js-ckeditor'].setData(travelInformationPageContent[index].description);
}

if (!travelInformationPageContent.length) {
	$('#add_travel_info_section').hide();
}


// Delete travel information section content
$(document).on('click', ".js-travel-info-content-section-delete", function () {
	deletTarvelInfoContentSection($(this).attr('data-index'));
	$(this).closest('.js-draggable-items-section-remove').remove();

	if (!travelInformationPageContent.length) {
		$('#add_travel_info_section').hide();
	}

	var i = 0;
	$('#add_travel_info_section .draggable-item').each(function (index) {
		$(this).find('.js-travel-info-edit-content-section, .js-travel-info-content-section-delete').attr('data-index', i);
		i++;
	});
});

function deletTarvelInfoContentSection(index) {
	travelInformationPageContent.splice(index, 1);
}


$(".js-draggable-items").sortable({
	start: function (event, ui) {
		travelInformationDraggable = [];
	},
	stop: function (event, ui) {
		travelInformationDraggable = [];
		$('#add_travel_info_section .js-draggable-display-order').each(function (index) {
			var index = $(this).find('.js-travel-info-edit-content-section').attr('data-index');
			var data = travelInformationPageContent[index];
			travelInformationDraggable.push(data);
		});
		travelInformationPageContent = travelInformationDraggable

		var i = 0;
		$('#add_travel_info_section .draggable-item').each(function (index) {
			$(this).find('.js-travel-info-edit-content-section, .js-travel-info-content-section-delete').attr('data-index', i);

			i++;
		});
	}
});

$(document).on('click', ".js-travel-info-create-content", function () {
	$('.create-travel-information-form').valid();
	var mcq_validation = mcqValidation();
	if (!mcq_validation) {
		return false;
	}
	$("#addTravelContent").val(JSON.stringify(travelInformationPageContent));
	$(".js-create-travel-info-content").submit();
});

// Validation for mcq validation
function mcqValidation() {
	if ($("#add_travel_info_section").text().trim().length == 0) {
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
		reader.onload = function (e) {
			$('.js-manage-icon-width').removeClass('col-12').addClass('col-9');
			$('#' + input.id + '_preview').attr('src', e.target.result);
			$('#' + input.id + '_preview_container').removeClass('d-md-none');
			$('#remove').removeClass('d-md-none');
		}
		reader.readAsDataURL(input.files[0]);
	}
}

$(".uploadimage").change(function () {
	var ext = $(this).val().split('.').pop().toLowerCase();
	if ($.inArray(ext, ['png', 'jpg', 'jpeg']) != -1) {
		readLogoURL(this);
	}

});

function readImageURL(input) {
	if (input.files && input.files[0]) {
		var reader = new FileReader();
		reader.onload = function (e) {
			$('.js-manage-logo-width').removeClass('col-12').addClass('col-9');
			$('#' + input.id + '_preview').attr('src', e.target.result);
			$('#' + input.id + '_preview_container').removeClass('d-md-none');
			$('#remove').removeClass('d-md-none');
		}
		reader.readAsDataURL(input.files[0]);
	}
}

$("#logo").change(function () {
	var ext = $(this).val().split('.').pop().toLowerCase();
	if ($.inArray(ext, ['png', 'jpg', 'jpeg']) != -1) {
		readImageURL(this);
	}
});

$("#remove").on('click', function (event) {
	event.preventDefault();
	$('#logo').val('');
	let lbl = document.getElementById('lbl_logo');
	lbl.innerText = "Choose File";
	$('#logo_preview_container').addClass('d-md-none');
	$('.js-manage-logo-width').removeClass('col-9').addClass('col-12');
	$('#logo_preview').attr('src', '');
	$('#remove').addClass('d-md-none');
});
