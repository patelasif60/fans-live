@extends('layouts.backend')

@section('plugin-styles')
	<link rel="stylesheet" href="{{ asset('plugins/datatables/dataTables.bootstrap4.css') }}">
@endsection

@section('plugin-scripts')
	<script type="text/javascript"
			src="https://maps.googleapis.com/maps/api/js?key={{config('fanslive.GOOGLE_AUTH_KEY.key')}}"></script>
	{{--<script src="{{ asset('plugins/map/jquery.googlemap.js') }}"></script>--}}
@endsection

@section('page-scripts')
	<script src="{{ asset('js/backend/googlemap.js') }}"></script>
	<script src="{{ asset('js/backend/pages/stadiumentrances/edit.js') }}"></script>
@endsection

@section('content')
	<div class="block">
		<div class="block-header block-header-default">
			<h3 class="block-title">Stadium entrances</h3>
			<div class="block-options">
			</div>
		</div>
		<div class="block-content block-content-full">
			<form class="" id="stadiumentranceForm"
				  action="{{ route('backend.stadiumentrance.update', ['club' => app()->request->route('club')]) }}"
				  method="post">
				{{ method_field('PUT') }}
				{{ csrf_field() }}
				@php
					if(count($stadiumEntrance)>0){
						$blockClass='d-none';
						$hideClass='d-block';
					}
					else{
						$hideClass='d-none';
						$blockClass='d-block';
					}
				@endphp
				<div class="row">
					<div class="col-xl-12">
						<div class="table-responsive">
							<table class="table table-striped table-hover table-vcenter custom-listing-table js-table">
								@php
									$dbDataArray=array();
									$gsettingArray = array();
									$gsetId = null;
	                                $isUsingAllocatedSeating = 0;
								@endphp
								@php
									$gsettingArray[] = ['id'=>'Genral_Setting','name' =>'Center','latitude'=>$stadiumGeneralSetting->latitude,'longitude'=>$stadiumGeneralSetting->longitude,'blocks'=>$stadiumGeneralSetting->blocks];

									$gsetId = $stadiumGeneralSetting->id;
	                                $isUsingAllocatedSeating = $stadiumGeneralSetting->is_using_allocated_seating;
									$gsDatastr = json_encode($gsettingArray);
								@endphp
								<thead>
								<tr>
									<th>Name</th>
									<th>Latitude</th>
									<th>Longitude</th>
									@if($isUsingAllocatedSeating == 1)<th>Blocks</th>@endif
									<th class="text-center">Actions</th>
								</tr>
								</thead>
								<tbody id="submitData">
								@foreach($stadiumEntrance as $key)
									<tr id="{{ $loop->index }}">
										<td id="name">{{$key->name}}</td>
										<td id='latitude'>{{$key->latitude}}</td>
										<td id='longitude'>{{$key->longitude}}</td>
										@if($isUsingAllocatedSeating == 1)
											<td id='blocks'>{{$key->blocks}}</td>
										@endif
										<td class="text-center" nowrap="nowrap">
											<a href="javascript:void(0)" editid="{{$key->id}}" class="btn btn-sm js-edit"><i
													class="fal fa-pencil"></i></a>
											<a href="javascript:void(0)" delid="{{$loop->index}}"
											   class="btn btn-sm btn-outline-danger js-delete"><i class="fal fa-trash"></i></a>
										</td>
										@php
											$dbDataArray[] = ['id'=>$key->id,'name' =>$key->name,'latitude'=>$key->latitude,'longitude'=>$key->longitude,'blocks'=>$key->blocks];
										@endphp
									</tr>
								@endforeach
								@php($dbDatastr=json_encode($dbDataArray))
								</tbody>
								<input type="hidden" id="dbdata" name="dbdata" value="{{ $dbDatastr }}"/>
								<input type="hidden" id="gsettingdata" name="gsettingdata" gset-id="{{$gsetId}}"
									   value="{{$gsDatastr}}"/>
								<input type="hidden" id="mapdata" name="mapdata" value=""/>
								<input type="hidden" id="status_flag" name="status_flag" value="">
							</table>
						</div>
						<div class="js-norecord {{$blockClass}}">
							<h6 class="text-center block-header-default py-3 mb-10">No record found</h6>
						</div>
						{{-- <div class="mb-10 {{$hideClass}} js-hide">
							<button type="submit" class="btn btn-primary btn-noborder">Update entrances</button>
						</div> --}}
					</div>
				</div>
			</form>
			@if($stadiumGeneralSetting)
				<div class="row">
					<div class="col-xl-12">
						<form class="create-entrance-form"
							  action="{{ route('backend.stadiumentrance.update', ['club' => app()->request->route('club')]) }}"
							  method="post">
							<div class="row">
								<div class="col-xl-12">
									<div
										class="row mt-3 js-polls-answer-fields-wrapper {{ $errors->has('entrances_list.*.answer') ? ' is-invalid' : '' }}">
										<input type="hidden" id="idlist" name="id" value="">
										<div class="col-3">
											<div class="form-group">
												<label for="answer" class="required">Entrance name:</label>
												<input type="text" id="namelist" class="form-control answer-group" name="name"
													   value="">
											</div>
											@if ($errors->has('entrances_list.'. '*' . '.name'))
												<div class="invalid-feedback animated fadeInDown">
													<strong>{{ $errors->first('entrances_list.'. '*' . '.name') }}</strong>
												</div>
											@endif
										</div>
										<div class="col-3">
											<div class="form-group">
												<label for="answer" class="required">Latitude:</label>
												<input type="text" readonly class="form-control answer-group" name="latitude"
													   id="latitudelist">
												@if ($errors->has('entrances_list.'. '*' . '.latitude'))
													<div class="invalid-feedback animated fadeInDown">
														<strong>{{ $errors->first('entrances_list.'. '*' . '.latitude') }}</strong>
													</div>
												@endif
											</div>
										</div>
										<div class="col-3">
											<div class="form-group">
												<label for="answer" class="required">Longitude:</label>
												<input type="text" readonly id="longitudelist" class="form-control answer-group"
													   name="longitude">
												@if ($errors->has('entrances_list.'. '*' . '.longitude'))
													<div class="invalid-feedback animated fadeInDown">
														<strong>{{ $errors->first('entrances_list.'. '*' . '.longitude') }}</strong>
													</div>
												@endif
											</div>
										</div>
										<div class="col-3 {{ $isUsingAllocatedSeating == 0 ? 'd-none' : ''}}">
											<div class="form-group">
												<label for="blocks" class="required">Blocks:</label>
												<div>
													<select class="form-control answer-group" id="blockslist"
															name="blocks" multiple size="7">
														@foreach($stadiumBlocks as $key=>$val)
															<option value="{{ $val }}" data-id="{{ $key }}">{{ $val }}</option>
														@endforeach
													</select>
												</div>
											</div>
										</div>
										<input type="hidden" name="blockValidaton" id="blockValidaton" value="{{ $isUsingAllocatedSeating }}">
									</div>

									<div class="row mb-3">
										<div class="col-lg-4 col-md-6">
											<div class="form-group">
												<div class="row">
													<div class="col-6">
														<button type="button" bid="-1"
																class="stadiumEntranceList btn btn-primary btn-noborder btn-block">
															Add
														</button>
													</div>
													<div class="col-6">
														<button type="reset"
																class="js-cancel btn btn-noborder btn-alt-secondary d-none btn-block">
															Cancel
														</button>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</form>
					</div>
					<div class="col-xl-12">
						<div id="map" style="width:100%; height:450px;"></div>
					</div>
				</div>
			@else
				<div class="row">
					<div class="col-xl-12">
						<br/><br/>
						<h5 class="text-danger font-w300 text-center">Please add address in stadium general settings.</h5>
					</div>
				</div>
			@endif
		</div>
	</div>
@endsection
