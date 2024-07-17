@extends('layouts.backend')

@section('plugin-styles')
	<link rel="stylesheet" href="{{ asset('plugins/datatables/dataTables.bootstrap4.css') }}">
@endsection

@section('plugin-scripts')
	<script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
	<script src="{{ asset('plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
@endsection

@section('page-scripts')
	<script src="{{ asset('js/backend/pages/news/index.js') }}"></script>
@endsection

@section('content')

	<div id="news_list" v-cloak class="search-table-result">


		<div class="d-flex justify-content-between align-items-center mb-20">
			<h2 class="h4 font-w300 mb-0">News list</h2>
			<div><a class="btn btn-sm btn-outline-primary"
					href="{{ route('backend.news.create', ['club' => app()->request->route('club')])}}">
					<i class="far fa-plus mr-1"></i> Add new
				</a></div>
		</div>

		<div class="block" id="frm_search_data">
			<form class="news-search-form" method="POST">
				<div class="block-header block-header-default">
					<h3 class="block-title">Filter</h3>
					<div class="block-options">
						<button type="submit" class="btn btn-noborder btn-primary" id="searchNews">Search</button>
						<button type="button" class="btn btn-noborder btn-alt-secondary"
								@click="clearForm('frmSearchData')">Clear
						</button>
					</div>
				</div>
				<div class="block-content block-content-full">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group row mb-0">
								<label class="col-12" for="name">Name:</label>
								<div class="col-12">
									<input type="text" class="form-control" id="name" name="title" placeholder="Name">
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
					<table class="table table-striped table-hover table-vcenter custom-listing-table">
						<thead>
						<tr>
							<th data-field="news.title" @click="sortByKey('news.title')"
								:class="[sortKey != 'news.title' ? 'sorting' : sortOrder == 1 ? 'sorting_asc' : 'sorting_desc']">
								Title
							</th>
							<th data-field="news.publication_date" @click="sortByKey('news.publication_date')"
								:class="[sortKey != 'news.publication_date' ? 'sorting' : sortOrder == 1 ? 'sorting_asc' : 'sorting_desc']">
								Publication Date
							</th>
							<th data-field="news.status" @click="sortByKey('news.status')"
								:class="[sortKey != 'news.status' ? 'sorting' : sortOrder == 1 ? 'sorting_asc' : 'sorting_desc']">
								Status
							</th>
							<th class="text-center">Actions</th>
						</tr>
						</thead>
						<tbody>
						<tr v-for="news in newsData">
							<td>@{{ news.title }}</td>
							<td>@{{ news.publication_date | formatDate }}</td>
							<td>@{{ news.status }}</td>
							<td class="text-center" nowrap="nowrap">
								<a :href="'news/' + news.id + '/edit'" class="btn btn-sm edit-user-button" title="Edit">
									<i class="fal fa-pencil"></i>
								</a>
								<a class="btn btn-sm btn-outline-danger delete-confirmation-button"
								   :href="'news/' + news.id" title="Delete">
									<i class="fal fa-trash"></i>
								</a>
							</td>
						</tr>
						</tbody>
					</table>
				</div>
				<div v-if="newsCount == 0">
					<h6 class="text-center block-header-default py-3 mb-0">No record found</h6>
				</div>
				<div v-else>
					<div class="row align-items-center">
						<div class="col-md-5 col-sm-12 dataTables_info table-pagination-info">
							<pagination>
							</pagination>
						</div>
						<div class="col-md-7 col-sm-12 dataTables_paginate">
							<ul id="news_pagination"
								class="pagination-sm justify-content-center justify-content-md-end mb-0">
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
