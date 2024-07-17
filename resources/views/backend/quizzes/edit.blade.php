@extends('layouts.backend')

@section('plugin-styles')
	<link rel="stylesheet" href="{{ asset('plugins/summernote/summernote-bs4.css')}}">
@endsection

@section('plugin-scripts')
	<script src="{{asset('plugins/jquery-ui/jquery-ui.min.js') }}"></script>
	<script src="{{asset('plugins/jquery-ui/jquery.ui.touch-punch.min.js') }}"></script>
	<script src="{{ asset('plugins/ckeditor/ckeditor.js') }}"></script>
	<script src="{{ asset('plugins/jquery-repeater/jquery.repeater.min.js') }}"></script>
@endsection

@section('page-scripts')
	<script src="{{ asset('js/backend/pages/quizzes/edit.js') }}"></script>
@endsection

@section('content')
	<div class="block">
		<div class="block-header block-header-default">
			<h3 class="block-title">Edit quiz</h3>
			<div class="block-options">
			</div>
		</div>
		<div class="block-content block-content-full">
			<div class="row">
				<div class="col-xl-12">
					<form class="create-quiz-form"
						  action="{{ route('backend.quizzes.update', ['club' => app()->request->route('club'), 'quiz' => $quiz]) }}"
						  method="post" enctype="multipart/form-data">
						{{ method_field('PUT') }}
						{{ csrf_field() }}
						<div class="row">
							<div class="col-xl-6">
								<div class="form-group{{ $errors->has('title') ? ' is-invalid' : '' }}">
									<label for="title" class="required">Title:</label>
									<input type="text" class="form-control" id="title" name="title"
										   value="{{ old('title', $quiz->title) }}">
									@if ($errors->has('title'))
										<div class="invalid-feedback animated fadeInDown">
											<strong>{{ $errors->first('title') }}</strong>
										</div>
									@endif
								</div>
							</div>

							<div class="col-xl-6">
								<div class="form-group {{ $errors->has('publication_date') ? ' is-invalid' : '' }}">
									<label for="publication_date" class="required">Publication date:</label>
									<div class='input-group date js-datetimepicker' data-target-input="nearest"
										 id="publication_date">
										<input type="text" class="form-control datetimepicker-input" name="publication_date" value="{{ old('publication_date', convertDateTimezone($quiz->publication_date, null, $clubDetail->time_zone, config('fanslive.DATE_TIME_CMS_FORMAT.php'))) }}" data-target="#publication_date" readonly
											   data-toggle="datetimepicker"/>
										<div class="input-group-append" data-target="#publication_date"
											 data-toggle="datetimepicker">
											<div class="input-group-text"><i class="fal fa-calendar-alt"></i></div>
										</div>
									</div>

									@if ($errors->has('publication_date'))
										<div class="invalid-feedback animated fadeInDown">
											<strong>{{ $errors->first('publication_date') }}</strong>
										</div>
									@endif
								</div>
							</div>
							<div class="col-xl-6">
								<div class="form-group">
                                    <div class="logo-fields-wrapper">
                                        <div class="d-flex">
                                            <div class="logo-input flex-grow-1">
                                                <label class="required">Image:</label>
                                                <div class="input-group">
                                                    <div class="custom-file">
                                                        <div>
                                                            <input type="file" class="form-control custom-file-input" id="logo" name="logo" data-toggle="custom-file-input" accept="image/*" value="{{ $quiz->image_file_name }}">
                                                            <label id="lbl_logo" name="lbl_logo" class="form-control custom-file-label" for="logo">Choose file</label>
                                                        </div>
                                                    </div>
                                                </div> 
                                            </div>
                                            <div id="logo_preview_container" class="ml-3 {{ $quiz->image ? '' : 'd-md-none' }}">
                                                <div class="logo_preview_container">
                                                    <img src="{{ $quiz->image }}" id="logo_preview" alt="Category logo">
                                                </div>
                                            </div>
                                        </div>
                                        <label class="helper m-0">Image dimensions: 840px X 630px (png only)</label>
                                    </div>
                                </div>
								{{-- <div class="form-group row">
									<div class="{{ $quiz->image ? 'col-9' : 'col-12' }} js-manage-logo-width">
										<label class="required">Image:</label>
										<div class="row">
											<div class="col-12">
												<div class="custom-file">
													<input type="file" class="form-control custom-file-input" id="logo" name="logo"
														   data-toggle="custom-file-input" accept="image/*" value="{{ $quiz->image_file_name }}">
													<label id="lbl_logo" name="lbl_logo" class="form-control custom-file-label"
														   for="logo">Choose file</label>
												</div>
											</div>
										</div>
									</div>
									<div class="col-3 {{ $quiz->image ? '' : 'd-md-none' }}" id="logo_preview_container">
										<div class="logo_preview_container m-auto">
											<img src="{{ $quiz->image }}" id="logo_preview" alt="Category logo">
										</div>
									</div>
								</div> --}}
							</div>

							<div class="col-xl-6">
								<div class="form-group">
									<label class="required">Status:</label>
									<div>
										@foreach($quizStatus as $statusKey => $status)
											<div class="custom-control custom-radio custom-control-inline mb-5">
												<input class="custom-control-input" type="radio" name="status"
													   id="status_{{$statusKey}}"
													   value="{{$status}}" {{ $status == old('status', $quiz->status) ? 'checked': '' }}>
												<label class="custom-control-label"
													   for="status_{{$statusKey}}">{{$status}}</label>
											</div>
										@endforeach()
									</div>
								</div>
							</div>

							<div class="col-xl-6">
								<div class="form-group {{ $errors->has('description') ? ' is-invalid' : '' }}">
									<label for="description" class="required">Description:</label>
									<div class="row">
										<div class="col-12">
											<textarea id="js-ckeditor" name="description">{{ old('description', $quiz->description) }}</textarea>
										</div>
										@if ($errors->has('description'))
											<div class="invalid-feedback animated fadeInDown">
												<strong>{{ $errors->first('description') }}</strong>
											</div>
										@endif
									</div>
								</div>
							</div>
						</div>

						<div class="row">

							<div class="col-xl-12">
								<div class="form-group">
									<label class="required">Type:</label>
									<div>
										@foreach($quizType as $key => $type)
											<div class="custom-control custom-radio custom-control-inline mb-5">
												<input class="custom-control-input" type="radio" name="type"
													   id="type_{{$key}}"
													   value="{{$key}}" {{ $key == old('type', $quiz->type) ? 'checked': '' }}>
												<label class="custom-control-label"
													   for="type_{{$key}}">{{$type}}</label>
											</div>
										@endforeach()
									</div>
								</div>
							</div>

							<div id="radio_multiple_choice" class="desc col-xl-12 {{ $quiz->type == 'multiple_choice' ? '' : 'd-none' }}">
								<div class="row">
									<div class="col-xl-12">
										<div class="content-heading">
											<div class="d-flex align-items-center justify-content-between">
												<label class="mb-0 required text-black">Questions and answers</label>
												<button type="button" class="btn btn-primary btn-noborder
                                                js-add-question-answer-info-content" data-toggle="modal"
														data-target="#add_question_info_content"><i
														class="fal fa-plus mr-5"></i>Add a question
												</button>
											</div>
										</div>
									</div>
								</div>
								<div id="mcq_draggable_questions_validation_error" class="text-danger animated fadeInDown font-size-sm"></div>
								<div class="row">
									<?php $mcqExists = FALSE; ?>
									@if (count($quizMultipleChoiseQuestions) > 0)
										<?php $mcqExists = TRUE; ?>
									@endif
									<div class="col-xl-12 draggable-column js-draggable-items" id="add_question_info_section" style="{{ $mcqExists ? '' : 'display:none;' }}">
										 <?php $j = 0; ?>
										 @foreach ($quizMultipleChoiseQuestions as $quizMultipleChoiseQuestion)
											<div class="block block-rounded draggable-item js-draggable-display-order js-draggable-items-section-remove">
											 	<div class="block-header block-header-default">
											 		<h3 class="block-title">{{ $quizMultipleChoiseQuestion->question }}</h3>
											 		<div class="block-options">
														<button type="button" class="btn-block-option js-tooltip-enabled js-question-info-edit-content-section" title="" data-toggle="modal" data-target="#add_question_info_content" data-original-title="Edit" data-index="<?php echo $j; ?>"><i class="fal fa-pencil"></i></button>
														<button type="button" class="btn-block-option js-tooltip-enabled js-question-info-content-section-delete text-danger" data-toggle="modal" title="" data-index="<?php echo $j; ?>" data-original-title="Delete"><i class="fal fa-trash"></i></button>
														<a class="btn-block-option draggable-handler" href="javascript:void(0)"><i class="si si-cursor-move"></i></a>
											 		</div>
											 	</div>
											</div>
											<?php $j++; ?>
										 @endforeach
									</div>
									{{-- @endif --}}
								</div>

								<div class="row js-end-of-quiz-detail-div">
									<div class="col-xl-12">
										<div class="content-heading">
											<div class="d-flex align-items-center justify-content-between">
												<label class="mb-0 required text-black">End of quiz text</label>
												<input type="hidden" name="line_ups_home">
												<button type="button" class="btn btn-primary btn-noborder
                                                js-added-end-quiz js-add-end-quiz-response"><i
														class="fal fa-plus mr-5"></i>Add another response
												</button>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-xl-12">
										<div id="end_of_quiz_text_validation_error" class="text-danger animated fadeInDown font-size-sm"></div>
									</div>
								</div>
								<div class="row js-end-of-quiz-main-div" id="add_end_of_quiz_response">
									<?php $i = 0; ?>
									@foreach ($quizEndTexts as $quizEndText)
										<div class="col-xl-12 add-quiz-response" id="<?php echo $i; ?>">
											<div class="block block-bordered block-default block-rounded js-quiz-response-main-div">
												<div class="block-header block-header-default">
													<div></div>
													<div class="block-options">
														<button type="button" class="btn-block-option js-add-response-delete text-danger" title="" data-original-title="Delete"><i class="fal fa-trash"></i></button>
													</div>
												</div>
												<div class="block-content">
													<div class="row">
														<div class="col-xl-8">
															<div class="form-group">
																<label for="end_of_quiz_text" class="required">Text:</label>
																<input type="text" class="form-control end-of-quiz-text" id="end_of_quiz_text<?php echo $i; ?>" name="end_of_quiz[<?php echo $i; ?>][text]" value="{{ $quizEndText->end_text }}">
															</div>
														</div>
														<div class="col-xl-4">
															<div class="form-group">
																<label for="end_of_quiz_points_thershold" class="required">Points threshold:</label>
																<input type="number" class="form-control end-of-quiz-points-thershold" min="0" max="999" id="end_of_quiz_points_thershold<?php echo $i; ?>" name="end_of_quiz[<?php echo $i; ?>][points_thershold]" value="{{ $quizEndText->points_threshold }}">
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
										<?php $i++ ?>
									@endforeach
								</div>
							</div>


							<input type="hidden" name="addQuestionContent[]" value="{{ $quizMultipleChoiseQuestionsJson }}" id="add_question_content">

							<div id="radio_fill_in_the_blanks" class="desc col-xl-12 {{ $quiz->type == 'fill_in_the_blanks' ? '' : 'd-none' }}">

								<div class="form-group px-0 col-xl-6{{ $errors->has('time_limit') ? ' is-invalid' : '' }}">
									<label for="time_limit" class="required">Time limit (seconds):</label>
									<input type="number" min="1" max="172800" class="form-control" id="time_limit" name="time_limit"
										   value="{{ old('time_limit', $quiz->time_limit) }}">
									@if ($errors->has('time_limit'))
										<div class="invalid-feedback animated fadeInDown">
											<strong>{{ $errors->first('time_limit') }}</strong>
										</div>
									@endif
								</div>
								<div class="row">
									<div class="col-xl-12">
										<div class="content-heading">
											<div class="d-flex align-items-center justify-content-between">
												{{-- <h5 class="mb-0">Answers</h5> --}}
												<label class="mb-0 required text-black">Answers</label>
												<button type="button"
														class="btn btn-primary btn-noborder js-fill-in-the-blank-add-question-answer">
													<i class="fal fa-plus mr-5"></i>Add another answer
												</button>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-xl-12">
										<div id="fill_in_the_blank_answers_validation_error" class="text-danger animated fadeInDown"></div>
									</div>
								</div>
								<div class="row js-end-of-quiz-main-div" id="fill_in_the_blank_add_question_answer">
									<?php $k = 0; ?>
									@foreach ($quizFillInTheBlanks as $quizFillInTheBlank)
									<div class="col-xl-12 add-new-question" id="<?php echo $k; ?>">
										<div class="block block-bordered block-default block-rounded">
											<div class="block-header block-header-default">
												<div></div>
												<div class="block-options">
													<button type="button" class="btn-block-option js-fill-in-the-blank-question-info-content-section-delete text-danger" title="" data-original-title="Delete"><i class="fal fa-trash"></i></button>
												</div>
											</div>
											<div class="block-content">
												<div class="row">
													<div class="col-xl-4">
														<div class="form-group">
															<label for="fill_in_the_blank_hint" class="required">Hint:</label>
															<input type="text" class="form-control line-ups-home-number fill-in-the-blank-hint" id="fill_in_the_blank_hint<?php echo $k; ?>" name="fill_in_the_blank[<?php echo $k; ?>][hint]" value="{{ $quizFillInTheBlank->hint }}">
														</div>
													</div>
													<div class="col-xl-3">
														<div class="form-group">
															<label for="fill_in_the_blank_answer" class="required">Answer:</label>
															<input type="text" class="form-control line-ups-home-number fill-in-the-blank-hint" id="fill_in_the_blank_answer<?php echo $k; ?>" name="fill_in_the_blank[<?php echo $k; ?>][answer]" value="{{ $quizFillInTheBlank->answer }}">
														</div>
													</div>
													<div class="col-xl-5">
														<div class="form-group">
															<label for="fill_in_the_blank_accepted_answer" class="required">Accepted answers: (enter a "," to separate answers)</label>
															<select class="js-select2 form-control  fill-in-the-blank-hint" multiple="multiple" id="fill_in_the_blank_accepted_answer<?php echo $k; ?>" name="fill_in_the_blank[<?php echo $k; ?>][accepted_answer][]">
																@if ($quizFillInTheBlank->accepted_answer != "")
																	@foreach(explode(',', $quizFillInTheBlank->accepted_answer) as $acceptedAnswer)
																		<option value="{{$acceptedAnswer}}" selected="selected">{{$acceptedAnswer}}</option>
																	@endforeach
																@endif
															</select>
														</div>
													</div>
													<div class="col-xl-4"> </div>
												</div>
											</div>
										</div>
									</div>
									<?php $k++; ?>
									@endforeach
								</div>
							</div>
						</div>

						<div class="form-group">
							<button type="submit" class="btn btn-hero btn-noborder btn-primary min-width-125 js-question-info-create-content">
								Update
							</button>
							<a href="{{ route('backend.quizzes.index', ['club' => app()->request->route('club')])}}"
							   class="btn btn-hero btn-noborder btn-alt-secondary">
								Cancel
							</a>
						</div>

					</form>
					<!-- Fade In Modal -->
					<div class="modal fade" id="add_question_info_content" role="dialog"
						 aria-labelledby="add_question_info_content" aria-hidden="true">
						<div class="modal-dialog modal-lg" role="document">
							<div class="modal-content">
								<div class="block block-themed block-transparent mb-0">
									<div class="block-header bg-primary-dark">
										<h3 class="block-title">Add a question</h3>
										<div class="block-options">
											<button type="button" class="btn-block-option" data-dismiss="modal"
													aria-label="Close">
												<i class="si si-close"></i>
											</button>
										</div>
									</div>
									<form class="block-content repeater" id="section_content_form">
										<div class="form-group">
											<label for="title" class="required">Question:</label>
											<input type="text" class="form-control js-content-section-question"
												   id="content_question" name="content_question"
												   value="{{ old('content_question') }}">
										</div>
										<div class="form-group">
											<label for="title" class="required">Post-answer text:</label>
											<input type="text" class="form-control js-content-section-post-answer-text"
												   id="content_post_answer_text" name="content_post_answer_text"
												   value="{{ old('content_post_answer_text') }}">
										</div>

										<div class="row js-quizzes-answer">
											<div class="col-xl-12">
												<div class="form-group row">
													<div class="col-xl-12 content-heading mb-0 pt-20">
														<div class="row align-items-center">
															<div class="col-8">
																<h5 class="mb-0">Answers</h5>
															</div>
															<div class="col-4 text-right">
																<button type="button"
																		class="btn btn-primary btn-noborder"
																		data-repeater-create>Add another answer
																</button>
															</div>
														</div>
													</div>
												</div>
											</div>

											<div class="col-xl-6">
												<div id="radio_option_validation_error" class="text-danger mb-10 animated fadeInDown"></div>
											</div>

											<div class="col-xl-6">
												<label class="mb-10 required">Correct answer</label>
											</div>

											<div class="col-xl-12 js-quizzes-answer" data-repeater-list="answers">
												<div class="" data-repeater-item>
													<div class="row">
														<div class="col-md-6">
															<div
																class="form-group js-quizzes-answer-fields-wrapper {{ $errors->has('answers.*.answer') ? ' is-invalid' : '' }}">
																<label for="answer" id="testing_id"
																	   class="option_cnt required">Option:</label>
																<div class="input-group">
																	<input type="text" class="form-control answer-group js-content-section-post-answer-option"
																		   name="answer">
																	<div class="input-group-append">
																		<button type="button"
																				class="btn btn-danger btn-noborder"
																				data-repeater-delete><i
																				class="fal fa-times"></i>
																		</button>
																	</div>
																</div>
																@if ($errors->has('answers.'. '*' . '.answer'))
																	<div class="invalid-feedback animated fadeInDown">
																		<strong>{{ $errors->first('answers.'. '*' . '.answer') }}</strong>
																	</div>
																@endif
															</div>
															@if ($errors->has('answers'))
																<div class="invalid-feedback animated fadeInDown">
																	<strong>{{ $errors->first('answers') }}</strong>
																</div>
															@endif

														</div>
														<div class="col-md-6 js-question-is-correct">
															<label>&nbsp;</label>
															<div class="form-group">
																<div class="custom-control custom-radio custom-control-inline mb-5">
																	<input class="custom-control-input js-is-correct-status is_correct_radio quizCheckbox" type="radio" name="is_correct" id="correct" value="true">
																	<label class="custom-control-label lbl_is_correct" for="correct"></label>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>

										<input type="hidden" name="addContent" id="add_edit_section_content"
											   value="addClubInfoContent">
										<input type="hidden" name="addEditIndex" id="add_edit_question_index" value="">
									</form>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-hero btn-noborder btn-alt-secondary"
											data-dismiss="modal">Close
									</button>
									<button type="button"
											class="btn btn-hero btn-noborder btn-primary js-quiz-add-another-answer-save">Save
									</button>
								</div>
							</div>
						</div>
					</div>
					<!-- END Fade In Modal -->
				</div>
			</div>
		</div>
	</div>
@endsection
