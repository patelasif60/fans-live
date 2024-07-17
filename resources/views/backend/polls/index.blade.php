@extends('layouts.backend')

@section('plugin-styles')
	<link rel="stylesheet" href="{{ asset('plugins/datatables/dataTables.bootstrap4.css') }}">
@endsection

@section('plugin-scripts')
	<script src="{{ asset('plugins/moment/moment.min.js') }}"></script>
	<script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
@endsection

@section('page-scripts')
	<script src="{{ asset('js/backend/pages/polls/index.js') }}"></script>
	<script type="text/javascript">
        var clubId = @json($club);
    </script>
@endsection

@section('content')
	<div id="poll_list" v-cloak class="search-table-result">

		<div class="d-flex justify-content-between align-items-center mb-20">
			<h2 class="h4 font-w300 mb-0">Polls list</h2>
			<div><a class="btn btn-sm btn-outline-primary"
					href="{{ route('backend.poll.create', ['club' => app()->request->route('club')])}}">
					<i class="far fa-plus mr-1"></i> Add new
				</a></div>
		</div>

		<div class="block" id="frm_search_data">
            <form class="poll-search-form" method="POST">
                <div class="block-header block-header-default">
                    <h3 class="block-title">Filter</h3>
                    <div class="block-options">
                        <button type="submit" class="btn btn-noborder btn-primary" id="searchPoll">Search</button>
    					<button type="button" class="btn btn-noborder btn-alt-secondary" @click="clearForm('frmSearchData')">Clear</button>
                    </div>
                </div>
                <div class="block-content block-content-full">
                	<div class="row">
						<div class="col-md-6">
    						<div class="form-group row mb-0">
                                <label class="col-12" for="name">Title:</label>
                                <div class="col-12">
                                    <input type="text" class="form-control" id="title" name="title" placeholder="Title">
                                </div>
                            </div>
    					</div>
						<div class="col-md-3">
                            <div class="form-group row mb-0">
                                <label class="col-12" for="from_date">From date:</label>
                                <div class='input-group date js-datepicker col-12' data-target-input="nearest" id="from_date">
                                    <input type="text" class="form-control datetimepicker-input" name="from_date" data-target="#from_date" readonly id="fromdate" data-toggle="datetimepicker"/>
                                    <div class="input-group-append" data-target="#from_date" data-toggle="datetimepicker">
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
                                <div class='input-group date js-datepicker col-12' data-target-input="nearest" id="to_date">
                                    <input type="text" class="form-control datetimepicker-input" name="to_date" data-target="#to_date" readonly data-toggle="datetimepicker"/>
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
					<table class="table table-striped table-hover table-vcenter competition-list-table custom-listing-table">
						<thead>
							<tr>
								<th data-field="polls.title" @click="sortByKey('polls.title')" :class="[sortKey != 'polls.title' ? 'sorting' : sortOrder == 1 ? 'sorting_asc' : 'sorting_desc']">Title</th>
								<th data-field="polls.associated_match" @click="sortByKey('polls.associated_match')" :class="[sortKey != 'polls.associated_match' ? 'sorting' : sortOrder == 1 ? 'sorting_asc' : 'sorting_desc']">Associated match</th>
								<th data-field="polls.publication_date" @click="sortByKey('polls.publication_date')" :class="[sortKey != 'polls.publication_date' ? 'sorting' : sortOrder == 1 ? 'sorting_asc' : 'sorting_desc']">Poll start date</th>
                                <th>Status</th>
								<th class="text-center">Actions</th>
							</tr>
						</thead>
						<tbody>
							<tr v-for="polls in pollData">
								<td>@{{ polls.title }}</td>
								 <td v-if="polls.home_team_id == clubId"> @{{ polls.away_team_name }} @{{ polls.home_team_id == clubId ? '(A)' : '' }}</td>
                                 <td v-else>@{{ polls.home_team_name }} @{{ polls.away_team_id == clubId ? '(H)' : '' }}</td>
                                <td>@{{ polls.publication_date | formatDate }}</td>
								<td>@{{ polls | checkStatus }}</td>
								<td class="text-center" nowrap="nowrap">
                                    <a :href="'poll/' + polls.id + '/edit'" class="btn btn-sm edit-poll-button" title="Edit">
                                        <i class="fal fa-pencil"></i>
                                    </a>
                                    <a class="btn btn-sm btn-outline-danger delete-confirmation-button" :href="'poll/' + polls.id" title="Delete">
                                        <i class="fal fa-trash"></i>
                                    </a>
                                </td>
							</tr>
						</tbody>
					</table>
				</div>
				<div v-if="pollCount == 0">
                    <h6 class="text-center block-header-default py-3 mb-0">No record found</h6>
                </div>
                <div v-else>
                    <div class="row align-items-center">
                        <div class="col-md-5 col-sm-12 dataTables_info table-pagination-info">
                            <pagination>
                            </pagination>
                        </div>
                        <div class="col-md-7 col-sm-12 dataTables_paginate">
                            <ul id="poll_pagination" class="pagination-sm justify-content-center justify-content-md-end mb-0">
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
	</div>
@endsection
