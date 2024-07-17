@extends('layouts.backend')

@section('plugin-styles')
	<link rel="stylesheet" href="{{ asset('plugins/datatables/dataTables.bootstrap4.css') }}">
@endsection

@section('plugin-scripts')
	<script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
	<script src="{{ asset('plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
@endsection

@section('page-scripts')
	<script src="{{ asset('js/backend/pages/feeditems/index.js') }}"></script>
@endsection

@section('content')
	<div id="feed_item_list" v-cloak class="search-table-result mb-60">
		<h2 class="content-heading">
			Feed items list
		</h2>

		<div class="block" id="frm_search_data">
			<form class="feed-search-form" method="POST">
				<div class="block-header block-header-default">
					<h3 class="block-title">Filter</h3>
					<div class="block-options">
						<button type="submit" class="btn btn-noborder btn-primary" id="searchFeed">Search</button>
						<button type="button" class="btn btn-noborder btn-alt-secondary"
								@click="clearForm('frmSearchData')">Clear
						</button>
					</div>
				</div>
				<div class="block-content block-content-full">
					<div class="row">
						<div class="col-md-3">
							<div class="form-group row mb-0">
								<label class="col-12" for="text">Text:</label>
								<div class="col-12">
									<input type="text" class="form-control" id="text" name="text" text="text"
										   placeholder="Text">
								</div>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group row mb-0">
								<label class="col-12" for="feed_name">Feed name:</label>
								<div class="col-12">
									<select class="js-select2 js-select2-allow-clear form-control" id="feed_name"
											name="feed_name">
										<option value="">Select feed name</option>
										@foreach($contentFeeds as $contentFeed)
											<option value="{{ $contentFeed->id }}">{{ $contentFeed->name }}</option>
										@endforeach
									</select>
								</div>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group row mb-0">
								<label class="col-12" for="from_date">From date:</label>
								<div class='input-group date js-datepicker col-12' data-target-input="nearest"
									 id="from_date">
									<input type="text" class="form-control datetimepicker-input" name="from_date"
										   data-target="#from_date" readonly id="fromdate" data-toggle="datetimepicker"/>
									<div class="input-group-append" data-target="#from_date"
										 data-toggle="datetimepicker">
										<div class="input-group-text"><i class="fal fa-calendar-alt"></i></div>
									</div>
									<div class="input-group-append">
										<div class="input-group-text datetimepickerClear" data-toggle="datetimepicker">
											<i class="fal fa-times"></i>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group row mb-0">
								<label class="col-12" for="to_date">To date:</label>
								<div class='input-group date js-datepicker col-12' data-target-input="nearest"
									 id="to_date">
									<input type="text" class="form-control datetimepicker-input" name="to_date"
										   data-target="#to_date" readonly data-toggle="datetimepicker"/>
									<div class="input-group-append" data-target="#to_date" data-toggle="datetimepicker">
										<div class="input-group-text"><i class="fal fa-calendar-alt"></i></div>
									</div>
									<div class="input-group-append">
										<div class="input-group-text datetimepickerClear" data-toggle="datetimepicker">
											<i class="fal fa-times"></i>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>

		<div class="block">
			<div class="block-header block-header-default">
				<h3 class="block-title">Result</h3>
			</div>
			<div class="block-content block-content-full">
				<div class="table-responsive">
					<table
						class="table table-striped table-hover table-vcenter feed-item-list-table custom-listing-table">
						<thead>
						<tr>
							<th data-field="content_feeds.name" @click="sortByKey('content_feeds.name')"
								:class="[sortKey != 'content_feeds.name' ? 'sorting' : sortOrder == 1 ? 'sorting_asc' : 'sorting_desc']">
								Type
							</th>
							<th data-field="feed_items.text" @click="sortByKey('feed_items.text')"
								:class="[sortKey != 'feed_items.text' ? 'sorting' : sortOrder == 1 ? 'sorting_asc' : 'sorting_desc']">
								Text
							</th>
							<th data-field="feed_items.imported_at" @click="sortByKey('feed_items.imported_at')"
								:class="[sortKey != 'feed_items.imported_at' ? 'sorting' : sortOrder == 1 ? 'sorting_asc' : 'sorting_desc']">
								Imported at
							</th>
							<th data-field="feed_items.status" @click="sortByKey('feed_items.status')"
								:class="[sortKey != 'feed_items.status' ? 'sorting' : sortOrder == 1 ? 'sorting_asc' : 'sorting_desc']">
								Status
							</th>
							<th class="text-center">Actions</th>
						</tr>
						</thead>
						<tbody>
						<tr v-for="feedItem in feedItemData">
							<td>@{{ feedItem.feed_item_name }}</td>
							<td>@{{ feedItem.text | formattext(20, '...') }}</td>
							<td>@{{ feedItem.imported_at | formatDate }}</td>
							<td>@{{ feedItem.status }}</td>
							<td class="text-center" nowrap="nowrap">
								<a :href="'feeditem/' + feedItem.id + '/edit'" class="btn btn-sm" title="View">
									<i class="fal fa-eye"></i>
								</a>
							</td>
						</tr>
						</tbody>
					</table>
				</div>
				<div v-if="feedItemCount == 0">
					<h6 class="text-center block-header-default py-3 mb-0">No record found</h6>
				</div>
				<div v-else>
					<div class="row align-items-center">
						<div class="col-md-5 col-sm-12 dataTables_info table-pagination-info">
							<pagination>
							</pagination>
						</div>
						<div class="col-md-7 col-sm-12 dataTables_paginate">
							<ul id="feed_item_pagination"
								class="pagination-sm justify-content-center justify-content-md-end mb-0">
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
