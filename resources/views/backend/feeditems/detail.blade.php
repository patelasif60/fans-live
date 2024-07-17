@extends('layouts.backend')

@section('plugin-styles')
	<link rel="stylesheet" href="{{ asset('plugins/slick/slick.css') }}">
	<link rel="stylesheet" href="{{ asset('plugins/slick/slick-theme.css') }}">
@endsection

@section('plugin-scripts')
	<script src="{{ asset('plugins/slick/slick.min.js') }}"></script>
@endsection

@section('page-scripts')
	<script src="{{ asset('js/backend/pages/feeditems/detail.js') }}"></script>
@endsection

@section('content')

	<div class="d-flex justify-content-between align-items-center mb-20">
		<h2 class="h4 font-w300 mb-0">Feed item details</h2>
	</div>
	<div class="row items-push">
		{{-- <div class="col-xl-12">
			@if($feedItem->contentFeed->name == 'rss' || $feedItem->contentFeed->name == 'youtube')
            	<h3 class="h4 font-w700 mb-5">{{ $feedItem->title }}</h3>
        	@endif
			<div class="mb-50">
				@if($feedItem->contentFeed->name == 'twitter' || $feedItem->contentFeed->name == 'facebook' || $feedItem->contentFeed->name == 'Instagram')
					@if($feedItem->media)
            			<div class="overflow-hidden rounded mb-20">
            				<div class="block">
            					@foreach($videos as $url)
	                    			<iframe src="{{ $url }}" height="500" width="500"></iframe>
            					@endforeach
                                <div class="js-slider slick-nav-white slick-nav-hover" data-arrows="true">
								@foreach($images as $url)
	                        			<div>
	                                    	<img class="img-fluid" src="{{ $url }}" alt="Image">
	                            		</div>
                            		@endforeach
                                </div>
                            </div>
            			</div>
                	@endif
            	@endif
            </div>
		</div> --}}
		<div class="col-xl-12">
            <div class="block socialmedia-block">
                <div class="block-header">
                    <h3 class="block-title">{{ $feedItem->contentFeed->type }} feed</h3>
                    <div class="block-options">
                        <span>
                        	<i class="fal fa-fw fa-calendar mr-5"></i>{{ $feedItem->contentFeed->last_imported }}
                    	</span>
                    </div>
                </div>
                <div class="block-content block-content-full">
		            <div class="row">
		            	@if($feedItem->contentFeed->name == 'youtube')
	            			<div class="col-xl-8">
        						<iframe src="https://www.youtube.com/embed/{{ $feedItem->youtube_id }}" height="450px" width="100%" frameborder="0"></iframe>
    						</div>
		            	@endif
	            		@if($feedItem->contentFeed->name == 'twitter' || $feedItem->contentFeed->name == 'facebook' || $feedItem->contentFeed->name == 'Instagram')
            				@if($feedItem->media)
		            			<div class="col-xl-8">
				            		<div class="js-slider slick-nav-white slick-nav-hover socialmedia-image-carousel" data-arrows="true">
										@foreach(json_decode($feedItem->media) as $media)
				                			<div class="img-block">
				                				@if ($media->type == 'image')
				                            		<img src="{{ $media->url }}" alt="Image">
				                				@else
			                    					<iframe src="{{ $media->url }}"></iframe>
			                            		@endif
				                    		</div>
				                		@endforeach
				                    </div>
		            			</div>
		                    @endif
	                    @endif
		            	<div class="col-xl-4">
		            		@if($feedItem->contentFeed->name == 'rss' || $feedItem->contentFeed->name == 'youtube')
				            	<h3 class="h4 font-w700 mb-5">{{ $feedItem->title }}</h3>
				        	@endif
		            		<div data-toggle="slimscroll" data-height="450px" data-color="#cdcdcd" data-opacity="1" data-always-visible="false">
			                	<p>{!! $feedItem->text !!}</p>
			            	</div>
		            	</div>
		            </div>
                </div>
            	<div class="block-content block-content-full block-content-sm bg-body-light font-size-sm">
            		<form class="detail-feed-item" action="{{ route('backend.feeditem.update', ['club' => app()->request->route('club'), 'feeditem' => $feedItem]) }}" method="post">
    		            {{ method_field('PUT') }}
                        {{ csrf_field() }}
                        <div class="row align-items-center">
                        <div class="col-xl-8">
    							<div class="form-group mb-0">
    	                        	<div class="custom-control custom-checkbox">
    		                            <input class="custom-control-input" type="checkbox" name="status" id="status" value="Published" {{ $feedItem->status == 'Published' ? 'checked' : ''}}>
    		                            <label class="custom-control-label" for="status">Published</label>
    	                        	</div>
    	                        </div>
    						</div>
    						<div class="col-xl-4">
        	                    <div class="form-group mb-0 pull-right">
                                    <button type="submit" class="btn btn-sm btn-noborder btn-alt-primary ">
                                        Save
                                    </button>
                                    <a href="{{ route('backend.feeditem.index', ['club' => app()->request->route('club')]) }}" class="btn btn-sm btn-noborder btn-alt-secondary">
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
