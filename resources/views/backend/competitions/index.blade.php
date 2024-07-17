@extends('layouts.backend')

@section('plugin-styles')
	<link rel="stylesheet" href="{{ asset('plugins/datatables/dataTables.bootstrap4.css') }}">
@endsection

@section('plugin-scripts')
	<script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
	<script src="{{ asset('plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
@endsection

@section('page-scripts')
	<script src="{{ asset('js/backend/pages/competitions/index.js') }}"></script>
@endsection

@section('content')
	<div id="competition_list" v-cloak class="search-table-result">

		<div class="d-flex justify-content-between align-items-center mb-20">
			<h2 class="h4 font-w300 mb-0">Competition list</h2>
			<div><a class="btn btn-sm btn-outline-primary"
					href="{{ route('backend.competition.create')}}">
					<i class="far fa-plus mr-1"></i> Add new
				</a></div>
		</div>

		<div class="block" id="frm_search_data">
            <div class="block-header block-header-default">
                <h3 class="block-title">Filter</h3>
                <div class="block-options">
                    <button type="button" class="btn btn-noborder btn-primary" @click="searchCompetitionData()">Search</button>
					<button type="button" class="btn btn-noborder btn-alt-secondary" @click="clearForm('frmSearchData')">Clear</button>
                </div>
            </div>
            <div class="block-content block-content-full">
            	<div class="row">
					<div class="col-md-6">
						<div class="form-group row mb-0">
                            <label class="col-12" for="name">Name</label>
                            <div class="col-12">
                                <input type="text" class="form-control" id="name" name="name" placeholder="Name">
                            </div>
                        </div>
					</div>
				</div>
            </div>
        </div>

        <div class="block">
            <div class="block-header block-header-default">
                <h3 class="block-title">Result</h3>
            </div>
            <div class="block-content block-content-full">
            	<div class="table-responsive">
					<table class="table table-striped table-hover table-vcenter competition-list-table custom-listing-table">
						<thead>
							<tr>
								<th data-field="competitions.name" @click="sortByKey('competitions.name')" :class="[sortKey != 'competitions.name' ? 'sorting' : sortOrder == 1 ? 'sorting_asc' : 'sorting_desc']">Name</th>
								<th data-field="competitions.status" @click="sortByKey('competitions.status')" :class="[sortKey != 'competitions.status' ? 'sorting' : sortOrder == 1 ? 'sorting_asc' : 'sorting_desc']">Status</th>
								<th class="text-center">Actions</th>
							</tr>
						</thead>
						<tbody>
							<tr v-for="competition in competitionData">
								<td>@{{ competition.name }}</td>
								<td>@{{ competition.status }}</td>
								<td class="text-center" nowrap="nowrap">
									<a :href="'competition/' + competition.id + '/edit'" class="btn btn-sm edit-user-button" title="Edit">
										<i class="fal fa-pencil"></i>
									</a>
									<a class="btn btn-sm btn-outline-danger delete-confirmation-button" :href="'competition/' + competition.id" title="Delete">
										<i class="fal fa-trash"></i>
									</a>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div v-if="competitionCount == 0">
					<h6 class="text-center block-header-default py-3 mb-0">No record found</h6>
				</div>
				<div v-else>
					<div class="row align-items-center">
						<div class="col-md-5 col-sm-12 dataTables_info table-pagination-info">
							<pagination>
							</pagination>
						</div>
						<div class="col-md-7 col-sm-12 dataTables_paginate">
							<ul id="competition_pagination" class="pagination-sm justify-content-center justify-content-md-end mb-0">
							</ul>
						</div>
					</div>
				</div>
            </div>
        </div>
	</div>
@endsection
