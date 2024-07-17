@extends('layouts.backend')

@section('plugin-styles')
	<link rel="stylesheet" href="{{ asset('plugins/datatables/dataTables.bootstrap4.css') }}">
@endsection

@section('plugin-scripts')
	<script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
	<script src="{{ asset('plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
@endsection

@section('page-scripts')
	<script src="{{ asset('js/backend/pages/quizzes/index.js') }}"></script>
@endsection

@section('content')
	<div id="quiz_list" v-cloak class="search-table-result">

		<div class="d-flex justify-content-between align-items-center mb-20">
			<h2 class="h4 font-w300 mb-0">Quizzes list</h2>
			<div><a class="btn btn-sm btn-outline-primary"
					href="{{ route('backend.quizzes.create', ['club' => app()->request->route('club')])}}">
					<i class="far fa-plus mr-1"></i> Add new
				</a></div>
		</div>

		<div class="block" id="frm_search_data">
			<div class="block">
				<div class="block-header block-header-default">
					<h3 class="block-title">Result</h3>
				</div>
				<div class="block-content block-content-full">
					<div class="table-responsive">
						<table class="table table-striped table-hover table-vcenter custom-listing-table">
							<thead>
							<tr>
								<th data-field="title" @click="sortByKey('title')"
									:class="[sortKey != 'title' ? 'sorting' : sortOrder == 1 ? 'sorting_asc' : 'sorting_desc']">
									Title
								</th>
								<th data-field="type" @click="sortByKey('type')"
									:class="[sortKey != 'type' ? 'sorting' : sortOrder == 1 ? 'sorting_asc' : 'sorting_desc']">Type</th>
								<th data-field="publication_date" @click="sortByKey('publication_date')"
									:class="[sortKey != 'publication_date' ? 'sorting' : sortOrder == 1 ? 'sorting_asc' : 'sorting_desc']">
									Publication Date
								</th>
								<th data-field="status" @click="sortByKey('status')"
									:class="[sortKey != 'status' ? 'sorting' : sortOrder == 1 ? 'sorting_asc' : 'sorting_desc']">
									Status
								</th>
								<th class="text-center">Actions</th>
							</tr>
							</thead>
							<tbody>
							<tr v-for="quiz in quizData">
								<td>@{{ quiz.title }}</td>
								<td>@{{ quiz.type | formattype }}</td>
								<td>@{{ quiz.publication_date | formatDate }}</td>
								<td>@{{ quiz.status }}</td>
								<td class="text-center" nowrap="nowrap">
									<a :href="'quiz/' + quiz.id + '/edit'"
									   class="btn btn-sm edit-user-button" title="Edit">
										<i class="fal fa-pencil"></i>
									</a>
									<a class="btn btn-sm btn-outline-danger delete-confirmation-button"
									   :href="'quiz/' + quiz.id" title="Delete">
										<i class="fal fa-trash"></i>
									</a>
								</td>
							</tr>
							</tbody>
						</table>
					</div>
					<div v-if="quizCount == 0">
						<h6 class="text-center block-header-default py-3 mb-0">No record found</h6>
					</div>
					<div v-else>
						<div class="row align-items-center">
							<div class="col-md-5 col-sm-12 dataTables_info table-pagination-info">
								<pagination>
								</pagination>
							</div>
							<div class="col-md-7 col-sm-12 dataTables_paginate">
								<ul id="special_offer_pagination"
									class="pagination-sm justify-content-center justify-content-md-end mb-0">
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
@endsection
