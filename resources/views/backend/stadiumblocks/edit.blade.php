@extends('layouts.backend')
@section('plugin-styles')
	<link rel="stylesheet" href="{{ asset('plugins/summer/imagemap.css')}}">
@endsection
@section('plugin-scripts')
    <script src="{{ asset('plugins/summer/imagemap.js') }}"></script>
@endsection

@section('page-scripts')
    <script src="{{ asset('js/backend/pages/stadiumblocks/edit.js') }}"></script>
@endsection

@section('content')
	<div class="block">
		<div class="block-header block-header-default">
            <h3 class="block-title">Edit block</h3>
            <div class="block-options">
            </div>
        </div>
        <div class="block-content block-content-full">
        	<div class="row">
                <div class="col-xl-12">
		            <form id="stadiumblocksForm" class="edit-stadium-block-form align-items-center" action="{{ route('backend.stadiumblocks.update', ['club' => app()->request->route('club'), 'stadiumBlock' => $stadiumBlock]) }}" method="post" enctype="multipart/form-data">
		    			{{ method_field('PUT') }}
                        {{ csrf_field() }}

                        <div class="row">
                            <div class="col-xl-6">
                                <div class="form-group{{ $errors->has('name') ? ' is-invalid' : '' }}">
                                    <label for="name" class="required">Name:</label>
                                    <input type="text" class="form-control" name="name" value="{{$stadiumBlock->name}}">
                                    @if ($errors->has('name'))
                                        <div class="invalid-feedback animated fadeInDown">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="col-xl-6">
                                <div class="form-group {{ $errors->has('seating_plan') ? ' is-invalid' : '' }}">
                                    <div class="logo-fields-wrapper">
                                        <label class="required">Seating plan:</label>
                                        <div class="d-flex align-items-center">
                                            <div class="logo-input flex-grow-1">
                                                <div class="input-group">
                                                    <div class="custom-file">
                                                        <div>
                                                            <input type="file" class="form-control custom-file-input uploadStadiumBlockSeatingFile" id="seating_plan" name="seating_plan" data-toggle="custom-file-input">
                                                            <label class="custom-file-label text-truncate pr-100" for="seating_plan">{{ $stadiumBlock->seating_plan_file_name ? $stadiumBlock->seating_plan_file_name  : 'Choose file'}}</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="{{ $stadiumBlock->seating_plan ? '' : 'd-none' }} d-flex align-items-center ml-3" id ="seating_plan">
                                                <div id="seating_plan_preview_container">
                                                    <a download href="{{ $stadiumBlock->seating_plan }}" v-if='{{ $stadiumBlock->seating_plan }}'>Download</a>
                                                </div> 
                                            </div>   
                                        </div>
                                    </div>
                                </div>

                                {{-- <div class="form-group{{ $errors->has('seating_plan') ? ' is-invalid' : '' }}">
                                    <label class="required">Seating plan:</label>
                                    <div class="row align-items-center">
                                        <div class="{{ $stadiumBlock->seating_plan ? 'col-9' : 'col-12' }}">
                                            <div class="custom-file">
                                                <input type="file" class="form-control custom-file-input uploadStadiumBlockSeatingFile" id="seating_plan" name="seating_plan" data-toggle="custom-file-input">
                                                <label class="form-control custom-file-label" for="seating_plan">{{ $stadiumBlock->seating_plan_file_name ? $stadiumBlock->seating_plan_file_name  : 'Choose file'}}
                                            </div>
                                        </div>
                                        <div class="col-3 {{ $stadiumBlock->seating_plan ? '' : 'd-none' }}" id ="seating_plan">
                                            <div class="d-flex justify-content-center" id="seating_plan_preview_container">
                                                <a download href="{{ $stadiumBlock->seating_plan }}" v-if='{{ $stadiumBlock->seating_plan }}'>Download</a>
                                            </div>
                                        </div>
                                    </div>
                                </div> --}}
                            </div>

                            <div class="col-xl-12">
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox mb-5">
                                        <input class="custom-control-input" type="checkbox" value="1" name="is_active" id="is_active" {{ $stadiumBlock->is_active == 1 ? 'checked' : ''}}>
                                        <label class="custom-control-label"  for="is_active"></label>
                                        <label for="is_active">Is active?</label>
                                    </div>
                                </div>
                            </div>
                        </div>
						<input type="hidden" name="pos_data" id="pos_data" value="{{ $stadiumBlock->area }}" />
                        <input type="hidden" name="stadium_block_id" id="stadium_block_id" value="{{ $stadiumBlock->id }}" />
					</form>

                	@if(isset($stadiumGeneralSetting->aerial_view_ticketing_graphic))
                        <div class="row">
                            <div class="col-xl-12">
    							<div class="form-group">
                                    <label class="required">Position:</label>
                                    <div class="js-position-error"></div>
                                    <div class="">
                                    	<div id="wrapper">
                                            <header id="header">
                                                <div id="nav" class="p-1 d-block">
                                                    <div class="d-flex justify-content-between">

                                                        <!-- <li id="load"><a href="#">load</a></li>
                                                        <li id="from_html"><a href="#">from html</a></li> -->
                                                        <div class="d-flex js-area-type"> 
                                                        <div class="mr-1 js-li" id="circle"><a href="#">circle</a></div>
                                                        <div class="mr-1 js-li" id="polygon"><a href="#">polygon</a></div><div class='js-li' id="rectangle"><a href="#">rectangle</a></div>
                                                        </div>
                                                        <!-- <div id="to_html"><a href="#">to html</a></div>
                                                        <div id="preview"><a href="#">preview</a></div> -->
                                                        <!-- <div id="clear"><a href="#">clear</a></div> -->
                                                        <div class="d-flex"> 
                                                        <div class="mr-1 js-li" id="edit"><a href="#">edit</a></div>
                                                        <div class="mr-1" id="remove"><a href="#">remove</a></div>
                                                        <div  id="save"><a href="#">save</a></div>
                                                        </div>
                                                        <!-- <li id="new_image"><a href="#">new image</a></li>
                                                        <li id="show_help"><a href="#">?</a></li> -->
                                                    </div>
                                                </div>
                                                <div id="coords"></div>
                                                <div id="debug"></div>
                                            </header>
                                            <div id="image_wrapper">
                                                <div id="image">
                                                    <img src="" alt="#" id="img" />
                                                    <svg xmlns="http://www.w3.org/2000/svg" version="1.2" baseProfile="tiny" id="svg"></svg>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="code">
                                            <span class="close_button" title="close"></span>
                                            <div id="code_content"></div>
                                        </div>
                                        <form id="edit_details">
                                            <h5>Attributes</h5>
                                            <span class="close_button" title="close"></span>
                                            <p>
                                                <label for="href_attr">href</label>
                                                <input type="text" id="href_attr" />
                                            </p>
                                            <p>
                                                <label for="alt_attr">alt</label>
                                                <input type="text" id="alt_attr" />
                                            </p>
                                            <p>
                                                <label for="title_attr">title</label>
                                                <input type="text" id="title_attr" />
                                            </p>
                                            <button id="save_details">Save</button>
                                        </form>
                                        <div id="from_html_wrapper">
                                            <form id="from_html_form">
                                                <h5>Loading areas</h5>
                                                <span class="close_button" title="close"></span>
                                                <p>
                                                    <label for="code_input">Enter your html code:</label>
                                                    <textarea id="code_input">
                                                    	@foreach($areas as $area)
                                                            @if($area['type'] !== null && $area['coords'] !== null)
                                            				    <area href="#" data-id="{{ $area['stadiumBlockId'] }}" shape="{{ $area['type'] }}"  coords="{{ $area['coords'] }}" />
                                                            @endif
                                                		@endforeach
                                                   	</textarea>
                                                </p>
                                                <button id="load_code_button">Load</button>
                                            </form>
                                        </div>
                                        <div id="get_image_wrapper">
                                    		<div id="loading">Loading</div>
                                            <span class="clear_button" title="clear">x</span>
                                            <input type="hidden" id="url" value="{{$stadiumGeneralSetting->aerial_view_ticketing_graphic}}"/>
                                        </div>
                                        <div id="overlay"></div>
                                        <div id="help">
    										<span class="close_button" title="close"></span>
										</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="form-group">
                        <button type="button" class="btn btn-hero btn-noborder btn-primary min-width-125 updateBlock">Update
                        </button>
                        <a href="{{ route('backend.stadiumblocks.index', ['club' => app()->request->route('club')]) }}" class="btn btn-hero btn-noborder btn-alt-secondary">
                            Cancel
                        </a>
                    </div>
                </div>
            </div>
        </div>
	</div>
@endsection
