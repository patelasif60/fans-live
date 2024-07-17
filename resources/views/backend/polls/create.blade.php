@extends('layouts.backend')

@section('plugin-styles')
@endsection

@section('plugin-scripts')
    <script src="{{ asset('plugins/jquery-repeater/jquery.repeater.min.js') }}"></script>
@endsection

@section('page-scripts')
	<script src="{{ asset('js/backend/pages/polls/create.js') }}"></script>
@endsection

@section('content')
	<div class="block">
		<div class="block-header block-header-default">
			<h3 class="block-title">Add poll</h3>
			<div class="block-options">
			</div>
		</div>
		<div class="block-content block-content-full">
			<div class="row">
				<div class="col-xl-12">
					<form class="create-poll-form repeater" action="{{ route('backend.poll.store',['club' => app()->request->route('club')]) }}" method="post">
						@csrf
						<div class="row">
							<div class="col-xl-6">
								<div class="form-group{{ $errors->has('title') ? ' is-invalid' : '' }}">
									<label for="title" class="required">Title:</label>
									<input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}">
									@if ($errors->has('title'))
										<div class="invalid-feedback animated fadeInDown">
											<strong>{{ $errors->first('title') }}</strong>
										</div>
									@endif
								</div>
							</div>

	                    	<div class="col-xl-6">
		                        <div class="form-group{{ $errors->has('question') ? ' is-invalid' : '' }}">
									<label for="question" class="required">Question:</label>
									<input type="text" class="form-control" id="question" name="question" value="{{ old('question') }}">
									@if ($errors->has('question'))
										<div class="invalid-feedback animated fadeInDown">
											<strong>{{ $errors->first('question') }}</strong>
										</div>
									@endif
								</div>
							</div>

							<div class="col-xl-6">
                                <div class="form-group {{ $errors->has('publication_date') ? ' is-invalid' : '' }}">
                                    <label for="email" class="required">Publication date:</label>
                                    <div class='input-group date js-datetimepicker' data-target-input="nearest" id="publication_date">
					                    <input type="text" class="form-control datetimepicker-input" name="publication_date" data-target="#publication_date" readonly value="{{ old('publication_date') }}" id="publication_datetime" />
					                    <div class="input-group-append" data-target="#publication_date" data-toggle="datetimepicker">
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
                                <div class="form-group {{ $errors->has('closing_date') ? ' is-invalid' : '' }}">
                                    <label for="closing_date">Closing date:</label>
                                    <div class='input-group date js-datetimepicker' data-target-input="nearest" id="closing_date">
	                                    <input type="text" class="form-control datetimepicker-input" name="closing_date" id="closing_datetime"  data-target="#closing_date" readonly value="{{ old('closing_date') }}"/>
	                                    <div class="input-group-append" data-target="#closing_date" data-toggle="datetimepicker">
					                        <div class="input-group-text"><i class="fal fa-calendar-alt"></i></div>
					                    </div>
					                </div>
                                    @if ($errors->has('closing_date'))
                                        <div class="invalid-feedback animated fadeInDown">
                                            <strong>{{ $errors->first('closing_date') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="col-xl-6">
                                <div class="form-group {{ $errors->has('display_results_date') ? ' is-invalid' : '' }}">
                                    <label for="display_results_date" class="required">Display results date:</label>
                                    <div class='input-group date js-datetimepicker' data-target-input="nearest" id="display_results_date">
	                                    <input type="text" class="form-control datetimepicker-input"  name="display_results_date" data-target="#display_results_date" readonly value="{{ old('display_results_date') }}">
	                                    <div class="input-group-append" data-target="#display_results_date" data-toggle="datetimepicker">
					                        <div class="input-group-text"><i class="fal fa-calendar-alt"></i></div>
					                    </div>
					                </div>
                                    @if ($errors->has('display_results_date'))
                                        <div class="invalid-feedback animated fadeInDown">
                                            <strong>{{ $errors->first('display_results_date') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="col-xl-6">
	                            <div class="form-group">
	                            	<label for="associated_match" class="required">Associated with match:</label>
	                                <select class="js-select2 js-select2-allow-clear form-control" id="associated_match" name="associated_match">
	                                	<option value="">Please select</option>
											@foreach($pollAssociatedMatch as $match)
												<option value="{{ $match->id }}">{{ $match->homeTeam->name }} vs {{ $match->awayTeam->name }} ({{ convertDateTimezone($match->kickoff_time, null, null,'jS F Y') }})</option>
											@endforeach()
	                                </select>
	                       		</div>
	                       	</div>


                            <div class="col-xl-6">
								<div class="form-group">
									<label class="required">Status:</label>
									<div>
			                        	@foreach($pollStatus as $key => $status)
	                                        <div class="custom-control custom-radio custom-control-inline mb-5">
	                                            <input class="custom-control-input" type="radio" name="status" id="status_{{$key}}" value="{{$status}}" {{ $key == 'published' ? 'checked': '' }}>
	                                            <label class="custom-control-label" for="status_{{$key}}">{{$status}}</label>
	                                        </div>
	                                    @endforeach()
	                                </div>
		                        </div>
							</div>
						</div>

						<div class="row js-polls-answer">
							<div class="col-xl-12">
								<div class="form-group row">
									<div class="col-xl-6">
										<div class="row align-items-center">
											<div class="col-8">
				                                <h3 class="block-title">Poll options</h3>
				                            </div>
				                            <div class="col-4 text-right">
				                            	<button type="button" class="btn btn-primary btn-noborder" data-repeater-create>Add</button>
				                            </div>
										</div>
									</div>
								</div>
							</div>

                            <div class="col-xl-6 js-polls-answer" data-repeater-list="answers">
                                <div class="form-group js-polls-answer-fields-wrapper {{ $errors->has('answers.*.answer') ? ' is-invalid' : '' }}" data-repeater-item>
                                    <label for="answer" class="required">Option:</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control answer-group" name="answer">
										<div class="input-group-append">
	                                       <button type="button" class="btn btn-danger btn-noborder" data-repeater-delete> <i class="fal fa-times"></i></button>
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

			                <div class="col-xl-12">
								<div class="form-group">
									<button type="submit" class="btn btn-hero btn-noborder btn-primary min-width-125">
										Create
									</button>
									<a href="{{ route('backend.poll.index',['club' => app()->request->route('club')]) }}" class="btn btn-hero btn-noborder btn-alt-secondary">
										Cancel
									</a>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
@endsection
