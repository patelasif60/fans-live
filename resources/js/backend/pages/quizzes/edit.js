var QuizCreate = function () {
	var initFormValidations = function () {
		var userForm = $('.create-quiz-form');
		userForm.validate({
			ignore: [],
			errorClass: 'invalid-feedback animated fadeInDown',
			errorElement: 'div',
			errorPlacement: function (error, e) {
				if (e.attr("name") == "logo") {
					$(e).parents('.form-group .logo-input').append(error);
				} else if (e.attr("name") == "description") {
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
				'title': {
					required: true
				},
				'publication_date': {
					required: true
				},
				'description': {
					required: function (textarea) {
						CKEDITOR.instances[textarea.id].updateElement();
						var editorcontent = textarea.value.replace(/<[^>]*>/gi, '');
						if(editorcontent.length > 0) {
                            $("#"+textarea.id).parent().find('.cke_editor_js-ckeditor').removeClass('is-invalid');
                        }
						return editorcontent.length === 0;
					}
				},
				'time_limit': {
					required: {
                        depends: function(element) {
                            return $(element).closest('form').find('#type_fill_in_the_blanks').is(':checked');
                        }
                    },
					min: 1,
					max: 172800,
					number: true
				},
				'logo': {
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

	};

	var uiHelperDateTimePicker = function () {
		$(".js-datetimepicker").datetimepicker({
			ignoreReadonly: true,
			format: Site.dateTimeCmsFormat,
			timeZone: Site.clubTimezone
		});
	};

	var uiHelperCkeditor = function () {
		if (jQuery('#js-ckeditor:not(.js-ckeditor-enabled)').length) {
			CKEDITOR.replace('js-ckeditor', {
				toolbar: [
					['Bold', 'Link', 'Maximize', 'Source']
				],
			},).on( 'change', function(e) {
                var editorcontent = e.editor.getData().replace(/<[^>]*>/gi, '');
                if(editorcontent.length > 0) {
					$("."+e.editor.id).removeClass('is-invalid');
                    $("."+e.editor.id).closest('.form-group').find('.invalid-feedback').remove();
                } else {
                    $('.js-question-info-create-content').trigger('click');
                }
            });
			jQuery('#js-ckeditor').addClass('js-ckeditor-enabled');
		}
	};

	var uiHelperSelect2 = function () {        // Init Select2 (with .js-select2-allow-clear class)
		jQuery('.js-select2-allow-clear:not(.js-select2-enabled), .js-select2:not(.js-select2-enabled)').each(function () {
			var el = jQuery(this);

			// Add .js-select2-enabled class to tag it as activated
			el.addClass('js-select2-enabled');

			// Init
			el.select2({
				tags: true,
				tokenSeparators: [','],
				allowClear: $(this).hasClass('js-select2-allow-clear') ? true : false,
				placeholder: "Please select",
			});
		});
	};

	var manageOnLoad = function (id = '', name = '') {
		// Question Answer
		$(document).on('click', ".js-fill-in-the-blank-add-question-answer", function () {
			if ($('.add-new-question').attr('id') >= 0) {
				var addFillInTheBlankQuestionAnswerId = parseInt($('#fill_in_the_blank_add_question_answer').children().last().attr('id')) + 1;
			} else {
				var addFillInTheBlankQuestionAnswerId = 0;
			}
			addFillInTheBlankQuestionAnswer(addFillInTheBlankQuestionAnswerId);
			uiHelperSelect2();
			formFillInTheBlankValidation();
			fillInTheBlankValidation();
		});
	};

	var initImageLoad = function() {
        $("#logo").change(function() {
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
              $('.js-manage-logo-width').removeClass('col-12').addClass('col-9');
              $('#logo_preview').attr('src', e.target.result);
              $('#logo_preview_container').removeClass('d-md-none');
            }
            reader.readAsDataURL(input.files[0]);
        }
    };

	return {
		init: function () {
			// $('#add_question_info_section').show();
			manageOnLoad();
			uiHelperSelect2();
			// initFormRepeaters();
			initFormValidations();
			uiHelperDateTimePicker();
			uiHelperCkeditor();
			initImageLoad();
		}
	};
}();

var $repeater = $('.repeater').repeater({
	show: function () {
		$(this).slideDown();
		addRepeaterValidations();
		repeaterIncrementText();
	},
	hide: function (deleteElement) {
		$(this).slideUp(function(){
			deleteElement();
			repeaterIncrementText();
		});
	},
	isFirstItemUndeletable: true,
});

function addRepeaterValidations() {
	var answers = $('input[name^="answers"]');
	answers.filter('input[name$="[answer]"]').each(function() {
	    $(this).rules("add", {
	        required: true,
	        messages: {
	            required: "This field is required."
	        }
	    });
	});
}

function repeaterIncrementText() {
	var repeaterIncrement = 1;
	$(".js-quizzes-answer-fields-wrapper").each(function (index) {
		$(this).find('.option_cnt').text('Option ' + repeaterIncrement + ':');
		repeaterIncrement++;
	});
	var repeaterIncrementRadio = 0;
	$(document).ready(function () {
		$(".js-question-is-correct").each(function (index) {
			$(this).find('.is_correct_radio').attr('id', 'correct' + repeaterIncrementRadio);
			$(this).find('.is_false_radio').attr('id', 'false' + repeaterIncrementRadio);
			$(this).find('.lbl_is_correct').attr('for', 'correct' + repeaterIncrementRadio);
			$(this).find('.lbl_is_false').attr('for', 'false' + repeaterIncrementRadio);
			repeaterIncrementRadio++;
		});
	});
}

function addFillInTheBlankQuestionAnswer(addFillInTheBlankQuestionAnswerId) {
	var addFillInTheBlankQuestionAnswer = '<div class="block block-bordered block-default block-rounded"> <div class="block-header block-header-default"> <div></div><div class="block-options"><button type="button" class="btn-block-option js-fill-in-the-blank-question-info-content-section-delete text-danger" title="" data-original-title="Delete"><i class="fal fa-trash"></i></button></div></div><div class="block-content"> <div class="row"> <div class="col-xl-4"><div class="form-group"><label for="fill_in_the_blank_hint" class="required">Hint:</label><input type="text" class="form-control line-ups-home-number fill-in-the-blank-hint" id="fill_in_the_blank_hint' + addFillInTheBlankQuestionAnswerId + '" name="fill_in_the_blank[' + addFillInTheBlankQuestionAnswerId + '][hint]" value=""></div></div><div class="col-xl-4"><div class="form-group"><label for="fill_in_the_blank_answer" class="required">Answer:</label><input type="text" class="form-control line-ups-home-number fill-in-the-blank-hint" id="fill_in_the_blank_answer' + addFillInTheBlankQuestionAnswerId + '" name="fill_in_the_blank[' + addFillInTheBlankQuestionAnswerId + '][answer]" value=""></div></div><div class="col-xl-4"><div class="form-group"><label for="fill_in_the_blank_accepted_answer" class="required">Accepted answers:</label><select class="js-select2 form-control fill-in-the-blank-hint" multiple="multiple" id="fill_in_the_blank_accepted_answer' + addFillInTheBlankQuestionAnswerId + '" name="fill_in_the_blank[' + addFillInTheBlankQuestionAnswerId + '][accepted_answer][]"></select></div></div><div class="col-xl-4"> </div></div></div></div>';
	$('#fill_in_the_blank_add_question_answer').append('<div class="col-xl-12 add-new-question" id=' + addFillInTheBlankQuestionAnswerId + '>' + addFillInTheBlankQuestionAnswer + '</div>');
}

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

var questionInformationPageContent = Site.quizMultipleChoiseQuestionsJson.length !== 0 ? JSON.parse(Site.quizMultipleChoiseQuestionsJson) : [];
var questionInformationPageContentHtml = '<div class="block block-rounded draggable-item js-draggable-display-order js-draggable-items-section-remove"><div class="block-header block-header-default"><h3 class="block-title">{content_question}</h3><div class="block-options"><button type="button" class="btn-block-option js-tooltip-enabled js-question-info-edit-content-section" title="" data-toggle="modal" data-target="#add_question_info_content" data-original-title="Edit"" data-index="{index}"><i class="fal fa-pencil"></i></button><button type="button" class="btn-block-option js-tooltip-enabled js-question-info-content-section-delete text-danger" data-toggle="modal" title="" data-index="{index}" data-original-title="Delete"><i class="fal fa-trash"></i></button><a class="btn-block-option draggable-handler" href="javascript:void(0)"><i class="si si-cursor-move"></i></a></div></div></div>';

// Initialize when page loads
jQuery(function () {
	QuizCreate.init();
});

function formEndOfQuizValidation() {
	$('.end-of-quiz-points-thershold').each(function () {
		$(this).rules("add", {
			required: true,
			number: true,
			min: 0,
			messages: {
				required: "This field is required."
			}
		});
	});

	$('.js-content-section-post-answer-option, .end-of-quiz-text').each(function () {
		$(this).rules('add', {
			required: true,
			messages: {
				required: "This field is required."
			}
		});
	});
};

function formFillInTheBlankValidation() {
	$('.fill-in-the-blank-hint').each(function () {
		$(this).rules("add", {
			required: true,
			messages: {
				required: "This field is required."
			}
		});
	});
}

var sectionContentForm = $('#section_content_form');

sectionContentForm.validate({
	ignore: [],
	errorClass: 'invalid-feedback animated fadeInDown',
	errorElement: 'div',
	errorPlacement: function (error, e) {
		if (e.attr("type") == 'radio') {
			$(e).parents('.form-group1').append(error);
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
		'content_question': {
			required: true
		},
		'content_post_answer_text': {
			required: true
		}
	},
});


$(document).on('click', ".js-quiz-add-another-answer-save", function () {

	var option_validation_success = true;
	if ($('input[name$="[is_correct]"]:checked').length == 0) {
		option_validation_success = false;
		$("#radio_option_validation_error").text('');
		$("#radio_option_validation_error").text("* Please select one correct option.");
	} else {
		$("#radio_option_validation_error").empty();
	}

	if (!sectionContentForm.valid() || !option_validation_success) {
		return false;
	}
	var content_question = $(".js-content-section-question").val();
	var content_post_answer_text = $(".js-content-section-post-answer-text").val();
	var repeaterValue = $('.repeater').repeaterVal();
	var answer = repeaterValue.answers;
	var addAnotherAnswerContentSection = $('#add_edit_section_content').val();

	if (addAnotherAnswerContentSection == 'add') {
		uiHelperDraggableItems();
		$('#add_question_info_section').show();

		questionInformationPageContent.push({
			'content_question': content_question,
			'content_post_answer_text': content_post_answer_text,
			'answers': answer
		});

		$("#add_question_content").val(JSON.stringify(questionInformationPageContent));

		var anotherQuestionDataContent = questionInformationPageContentHtml.replace('{content_question}', content_question).replace(/{index}/g, questionInformationPageContent.length - 1);
		$("#add_question_info_section").append(anotherQuestionDataContent);

	} else {
		$('#add_question_info_section').show();
		var index = $('#add_edit_question_index').val();
		questionInformationPageContent[index].content_question = content_question;
		questionInformationPageContent[index].content_post_answer_text = content_post_answer_text;
		questionInformationPageContent[index].answers = answer;
		$("#add_question_content").val(JSON.stringify(questionInformationPageContent));
		var anotherQuestionDataContent = questionInformationPageContentHtml.replace('{content_question}', content_question).replace(/{index}/g, questionInformationPageContent + 1);
		$("#add_question_info_section .block-title:eq(" + index + ")").html(content_question);
	}
	$(".js-content-section-question").val('');
	$('#add_question_info_content').modal('hide');
	mcqValidation();
});

if (!questionInformationPageContent.length) {
	$('#add_question_info_section').hide();
}

// Edit Question information section content
$(document).on('click', ".js-question-info-edit-content-section", function () {
	editQuestionInfoContentSection($(this).attr('data-index'));
});

$(document).on('click', ".js-add-question-answer-info-content", function () {
	$('[data-repeater-list]').empty();
	$('[data-repeater-create]').click();
	$('[data-repeater-delete]').first().remove();
	$('#add_edit_section_content').val('add');
	$(".js-content-section-question,.js-content-section-post-answer-text").val('');
	$('#add_edit_question_index').val('');
	addRepeaterValidations();
});

function editQuestionInfoContentSection(index) {
	$('#add_edit_section_content').val('edit');
	$('#add_edit_question_index').val(index);

	questionInformationPageContent[index].content_question;
	questionInformationPageContent[index].content_post_answer_text;
	$('.js-content-section-question').val(questionInformationPageContent[index].content_question);
	$('.js-content-section-post-answer-text').val(questionInformationPageContent[index].content_post_answer_text);

	$repeater.setList(questionInformationPageContent[index].answers);
	$('[data-repeater-delete]').first().remove();
}

// Delete fill-in the blanks answers
$(document).on('click', ".js-fill-in-the-blank-question-info-content-section-delete", function () {
	$(this).closest('.add-new-question').remove();
});
// Delete club information section content
$(document).on('click', ".js-question-info-content-section-delete", function () {
	deleteQuestionInfoContentSection($(this).attr('data-index'));
	$(this).closest('.js-draggable-items-section-remove').remove();
	if (!questionInformationPageContent.length) {
		$('#add_question_info_section').hide();
	}
	var i = 0;
	$('#add_question_info_section .draggable-item').each(function (index) {
		$(this).find('.js-question-info-edit-content-section, .js-question-info-content-section-delete').attr('data-index', i);
		i++;
	});
});

function deleteQuestionInfoContentSection(index) {
	questionInformationPageContent.splice(index, 1);
	$("#add_question_content").val(JSON.stringify(questionInformationPageContent));
}

$(".js-draggable-items").sortable({
	start: function (event, ui) {
		quizQuestionDraggable = [];
	},
	stop: function (event, ui) {
		quizQuestionDraggable = [];
		$('#add_question_info_section .js-draggable-display-order').each(function (index) {
			var index = $(this).find('.js-question-info-edit-content-section').attr('data-index');
			var data = questionInformationPageContent[index];
			quizQuestionDraggable.push(data);
		});
		questionInformationPageContent = quizQuestionDraggable
		$("#add_question_content").val(JSON.stringify(questionInformationPageContent));
		var i = 0;
		$('#add_question_info_section .draggable-item').each(function (index) {
			$(this).find('.js-question-info-edit-content-section, .js-question-info-content-section-delete').attr('data-index', i);
			i++;
		});
	}
});

$(document).on('click', ".js-question-info-create-content", function () {
	$(".create-quiz-form").valid();
	var questionType = $("input[name$='type']:checked").val();
	if (questionType == "fill_in_the_blanks") {
		if (!fillInTheBlankValidation()) {
			return false;
		}
	} else if (questionType == "multiple_choice") {
		var mcq_validation = mcqValidation();
		var end_of_quiz = endOfQuizTextValidation();
		if (!mcq_validation || !end_of_quiz) {
			return false;
		}
	}
	$("#add_question_content").val(JSON.stringify(questionInformationPageContent));
	$(".js-create-question-info-content").submit();
});

$(document).on('click', '.js-question-info-content-section-delete', function () {
	$(this).closest('.add-match-event').remove();
});

// Quiz end text
$(document).on('click', ".js-add-end-quiz-response", function () {
	if ($('.add-quiz-response').attr('id') >= 0) {
		var addEndOfQuizId = parseInt($('#add_end_of_quiz_response').children().last().attr('id')) + 1;
	} else {
		var addEndOfQuizId = 0;
	}
	addEndOfQuizResponse(addEndOfQuizId);
	endOfQuizTextValidation();
});

function addEndOfQuizResponse(addEndOfQuizId) {
	var addEndOfQuizQuizResponse = '<div class="block block-bordered block-default block-rounded js-quiz-response-main-div"> <div class="block-header block-header-default"> <div></div><div class="block-options"><button type="button" class="btn-block-option js-add-response-delete text-danger" title="" data-original-title="Delete"><i class="fal fa-trash"></i></button></div></div><div class="block-content"> <div class="row"> <div class="col-xl-8"><div class="form-group"><label for="end_of_quiz_text" class="required">Text:</label><input type="text" class="form-control end-of-quiz-text" id="end_of_quiz_text' + addEndOfQuizId + '" name="end_of_quiz[' + addEndOfQuizId + '][text]" value=""></div></div><div class="col-xl-4"><div class="form-group"><label for="end_of_quiz_points_thershold" class="required">Points threshold:</label><input type="number" class="form-control end-of-quiz-points-thershold" min="0" max="999" id="end_of_quiz_points_thershold' + addEndOfQuizId + '" name="end_of_quiz[' + addEndOfQuizId + '][points_thershold]"></div></div></div></div></div>';
	$('#add_end_of_quiz_response').append('<div class="col-xl-12 add-quiz-response" id=' + addEndOfQuizId + '>' + addEndOfQuizQuizResponse + '</div>');
	formEndOfQuizValidation();
}

$(document).on('click', '.js-add-response-delete', function () {
	$(this).closest('.add-quiz-response').remove();
});

// file upload
function readLogoURL(input) {
	if (input.files && input.files[0]) {
		var reader = new FileReader();
		reader.onload = function (e) {
			$('.js-manage-logo-width').removeClass('col-12').addClass('col-9');
			$('#logo_preview').attr('src', e.target.result);
			$('#logo_preview_container').removeClass('d-md-none');
			$('#remove').removeClass('d-md-none');
		}
		reader.readAsDataURL(input.files[0]);
	}
}


$("#logo").change(function () {
	var ext = $(this).val().split('.').pop().toLowerCase();
	if ($.inArray(ext, ['png', 'jpg', 'jpeg']) != -1) {
		readLogoURL(this);
	}
});

$("#remove").on('click', function () {
	$('#logo').val('');
	let lbl = document.getElementById('lbl_logo');
	lbl.innerText = "Choose File";
	$('#logo_preview_container').addClass('d-md-none');
	$('.js-manage-logo-width').removeClass('col-9').addClass('col-12');
	$('#logo_preview').attr('src', '');
});

$(document).ready(function () {
	$("input[name$='type']").click(function () {
		var radio = $(this).val();
		if (radio == 'multiple_choice') {
			$("#time_limit").val('');
		}
		$("div.desc").hide();
		$("#radio_" + radio).removeClass('d-none');
		$("#radio_" + radio).show();
	});
});

$(document).on('click', '.js-is-correct-status', function() {
	$('.is_correct_radio').prop('checked', false);
	$(this).prop('checked', true);
	if ($('input[name$="[is_correct]"]:checked').length > 0)
	{
		$("#radio_option_validation_error").empty();
	}
});

// Validation for mcq validation
function mcqValidation()
{
	if ($("#add_question_info_section").text().trim().length == 0) {
		$("#mcq_draggable_questions_validation_error").addClass('mb-15');
		$("#mcq_draggable_questions_validation_error").text('');
		$("#mcq_draggable_questions_validation_error").text('Please add at least one MCQ');
		return false;
	} else {
		$("#mcq_draggable_questions_validation_error").removeClass('mb-15');
		$("#mcq_draggable_questions_validation_error").text('');
		return true;
	}
}

// Validation for end of quiz
function endOfQuizTextValidation()
{
	if ($("#add_end_of_quiz_response").text().trim().length == 0) {
		$("#end_of_quiz_text_validation_error").addClass('mb-15');
		$("#end_of_quiz_text_validation_error").text('');
		$("#end_of_quiz_text_validation_error").text('Please add at least one end of quiz text.');
		return false;
	} else {
		$("#end_of_quiz_text_validation_error").removeClass('mb-15');
		$("#end_of_quiz_text_validation_error").text('');
		return true;
	}
}

// Validation for fill in the blank
function fillInTheBlankValidation()
{
	if ($("#fill_in_the_blank_add_question_answer").text().trim().length == 0) {
		$("#fill_in_the_blank_answers_validation_error").addClass('mb-15');
		$("#fill_in_the_blank_answers_validation_error").text('');
		$("#fill_in_the_blank_answers_validation_error").text('Please add at least one answer.');
		return false;
	} else {
		$("#fill_in_the_blank_answers_validation_error").removeClass('mb-15');
		$("#fill_in_the_blank_answers_validation_error").text('');
		return true;
	}
}
