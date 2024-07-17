@extends('layouts.backend')

@section('plugin-styles')
	<link rel="stylesheet" href="{{ asset('plugins/datatables/dataTables.bootstrap4.css') }}">
@endsection

@section('plugin-scripts')
	<script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
	<script src="{{ asset('plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
@endsection

@section('page-scripts')
	<script src="{{ asset('js/backend/pages/contentfeeds/index.js') }}"></script>
@endsection

@section('content')
	<div id="content_feed_list" v-cloak class="search-table-result">

		<div class="d-flex justify-content-between align-items-center mb-20">
			<h2 class="h4 font-w300 mb-0">Content feeds list</h2>
			<div><a class="btn btn-sm btn-outline-primary"
					href="{{ route('backend.contentfeed.create', ['club' => app()->request->route('club')])}}">
					<i class="far fa-plus mr-1"></i> Add new
				</a></div>
		</div>

        <div class="block">
            <div class="block-header block-header-default">
                <h3 class="block-title">Result</h3>
            </div>
            <div class="block-content block-content-full">
            	<div class="table-responsive">
					<table class="table table-striped table-hover table-vcenter content-feed-list-table custom-listing-table">
						<thead>
							<tr>
								<th data-field="content_feeds.name" @click="sortByKey('content_feeds.name')" :class="[sortKey != 'content_feeds.name' ? 'sorting' : sortOrder == 1 ? 'sorting_asc' : 'sorting_desc']">Name</th>
								<th data-field="content_feeds.type" @click="sortByKey('content_feeds.type')" :class="[sortKey != 'content_feeds.type' ? 'sorting' : sortOrder == 1 ? 'sorting_asc' : 'sorting_desc']">Type</th>
								<th data-field="content_feeds.last_imported" @click="sortByKey('content_feeds.last_imported')" :class="[sortKey != 'content_feeds.last_imported' ? 'sorting' : sortOrder == 1 ? 'sorting_asc' : 'sorting_desc']">Last imported</th>
								<th class="text-center">Actions</th>
							</tr>
						</thead>
						<tbody>
							<tr v-for="contentFeed in contentFeedData">
								<td>@{{ contentFeed.name }}</td>
								<td>@{{ contentFeed.type }}</td>
								<td>@{{ contentFeed.last_imported | formatDate }}</td>
								<td class="text-center" nowrap="nowrap">
									<a :href="'contentfeed/' + contentFeed.id + '/edit'" class="btn btn-sm edit-user-button" title="Edit">
										<i class="fal fa-pencil"></i>
									</a>
									<a class="btn btn-sm btn-outline-danger delete-confirmation-button" :href="'contentfeed/' + contentFeed.id" title="Delete">
										<i class="fal fa-trash"></i>
									</a>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div v-if="contentFeedCount == 0">
					<h6 class="text-center block-header-default py-3 mb-0">No record found</h6>
				</div>
				<div v-else>
					<div class="row align-items-center">
						<div class="col-md-5 col-sm-12 dataTables_info table-pagination-info">
							<pagination>
							</pagination>
						</div>
						<div class="col-md-7 col-sm-12 dataTables_paginate">
							<ul id="content_feed_pagination" class="pagination-sm justify-content-center justify-content-md-end mb-0">
							</ul>
						</div>
					</div>
				</div>
            </div>
        </div>
	</div>
@endsection
