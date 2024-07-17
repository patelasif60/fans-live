@extends('layouts.backend')

@section('plugin-styles')
	<link rel="stylesheet" href="{{ asset('plugins/datatables/dataTables.bootstrap4.css') }}">
@endsection

@section('plugin-scripts')
	<script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
	<script src="{{ asset('plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
@endsection

@section('page-scripts')
	<script src="{{ asset('js/backend/pages/travelinformationpages/index.js') }}"></script>
@endsection

@section('content')
	<div id="travel_information_list">

		<div class="d-flex justify-content-between align-items-center mb-20">
			<h2 class="h4 font-w300 mb-0">Travel information list </h2>
			<div><a class="btn btn-sm btn-outline-primary"
					href="{{ route('backend.travelinformationpages.create', ['club' => app()->request->route('club')])}}">
					<i class="far fa-plus mr-1"></i> Add new
				</a></div>
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
							<th data-field="travel_information_pages.title"
								@click="sortByKey('travel_information_pages.title')"
								:class="[sortKey != 'travel_information_pages.title' ? 'sorting' : sortOrder == 1 ? 'sorting_asc' : 'sorting_desc']">
								Title
							</th>
							<th data-field="travel_information_pages.publication_date"
								@click="sortByKey('travel_information_pages.publication_date')"
								:class="[sortKey != 'travel_information_pages.publication_date' ? 'sorting' : sortOrder == 1 ? 'sorting_asc' : 'sorting_desc']">
								Publication date
							</th>
							<th data-field="travel_information_pages.status"
								@click="sortByKey('travel_information_pages.status')"
								:class="[sortKey != 'travel_information_pages.status' ? 'sorting' : sortOrder == 1 ? 'sorting_asc' : 'sorting_desc']">
								Status
							</th>
							<th class="text-center">Actions</th>
						</tr>
						</thead>
						<tbody>
						<tr v-for="travelInformation in travelInformationData">
							<td>@{{ travelInformation.title }}</td>
							<td>@{{ travelInformation.publication_date | formatDate }}</td>
							<td>@{{ travelInformation.status }}</td>
							<td class="text-center" nowrap="nowrap">
								<a :href="'travelinformationpage/' + travelInformation.id + '/edit'"
								   class="btn btn-sm edit-user-button" title="Edit">
									<i class="fal fa-pencil"></i>
								</a>
								<a class="btn btn-sm btn-outline-danger delete-confirmation-button"
								   :href="'travelinformationpage/' + travelInformation.id" title="Delete">
									<i class="fal fa-trash"></i>
								</a>
							</td>
						</tr>
						</tbody>
					</table>
				</div>
				<div v-if="travelInformationCount == 0">
					<h6 class="text-center block-header-default py-3 mb-0">No record found</h6>
				</div>
				<div v-else>
					<div class="row align-items-center">
						<div class="col-md-5 col-sm-12 dataTables_info table-pagination-info">
							<pagination>
							</pagination>
						</div>
						<div class="col-md-7 col-sm-12 dataTables_paginate">
							<ul id="travel_information_pagination"
								class="pagination-sm justify-content-center justify-content-md-end mb-0">
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
