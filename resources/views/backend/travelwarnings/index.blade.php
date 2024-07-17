@extends('layouts.backend')

@section('plugin-styles')
    <link rel="stylesheet" href="{{ asset('plugins/datatables/dataTables.bootstrap4.css') }}">
@endsection

@section('plugin-scripts')
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
@endsection

@section('page-scripts')
    <script src="{{ asset('js/backend/pages/travelwarnings/index.js') }}"></script>
@endsection

@section('content')

    <div id="travelWarnings_list" v-cloak class="search-table-result">

		<div class="d-flex justify-content-between align-items-center mb-20">
			<h2 class="h4 font-w300 mb-0">Travel warnings list</h2>
			<div><a class="btn btn-sm btn-outline-primary"
					href="{{ route('backend.travelwarnings.create', ['club' => app()->request->route('club')])}}">
					<i class="far fa-plus mr-1"></i> Add new
				</a></div>
		</div>

        <div class="block" id="frm_search_data">
            <form class="travel-warnings-search-form" method="POST">
                <div class="block-header block-header-default">
                    <h3 class="block-title">Filter</h3>
                    <div class="block-options">
                        <button type="submit" class="btn btn-noborder btn-primary" id="searchTravelWarnings">Search</button>
                        <button type="button" class="btn btn-noborder btn-alt-secondary" @click="clearForm('frmSearchData')">Clear</button>
                    </div>
                </div>
                <div class="block-content block-content-full">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row mb-0">
                                <label class="col-12" for="name">Text:</label>
                                <div class="col-12">
                                    <input type="text" class="form-control" id="text" name="text" placeholder="Text">
                                </div>
                            </div>
                        </div>
                       <div class="col-md-3">
                            <div class="form-group row mb-0">
                                <label class="col-12" for="fromdate">From date:</label>
                                <div class='input-group date js-datepicker col-12' data-target-input="nearest" id="fromdate">
                                    <input type="text" class="form-control datetimepicker-input" name="fromdate" data-target="#fromdate" readonly id="from_date" data-toggle="datetimepicker"/>
                                    <div class="input-group-append" data-target="#fromdate" data-toggle="datetimepicker">
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
                            <div class="form-group row">
                                <label class="col-12" for="todate">To date:</label>
                                <div class='input-group date js-datepicker col-12' data-target-input="nearest" id="todate">
                                    <input type="text" class="form-control datetimepicker-input" name="todate" data-target="#todate" readonly data-toggle="datetimepicker"/>
                                    <div class="input-group-append" data-target="#todate" data-toggle="datetimepicker">
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
                                <th data-field="travel_warnings.text" @click="sortByKey('travel_warnings.text')" :class="[sortKey != 'travel_warnings.text' ? 'sorting' : sortOrder == 1 ? 'sorting_asc' : 'sorting_desc']" >Display Name</th>
                                <th data-field="travel_warnings.publication_date_time" @click="sortByKey('travel_warnings.publication_date_time')" :class="[sortKey != 'travel_warnings.publication_date_time' ? 'sorting' : sortOrder == 1 ? 'sorting_asc' : 'sorting_desc']">Publication Date</th>
                                <th data-field="travel_warnings.show_until" @click="sortByKey('travel_warnings.show_until')" :class="[sortKey != 'travel_warnings.show_until' ? 'sorting' : sortOrder == 1 ? 'sorting_asc' : 'sorting_desc']">Show until</th>
                                <th data-field="travel_warnings.status" @click="sortByKey('travel_warnings.status')" :class="[sortKey != 'travel_warnings.status' ? 'sorting' : sortOrder == 1 ? 'sorting_asc' : 'sorting_desc']">Status</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="travelWarnings in travelWarningsData">
                                <td>@{{ travelWarnings.text | formattext(20, '...') }}</td>
                                <td>@{{ travelWarnings.publication_date_time | formatDate }}</td>
                                <td>@{{ travelWarnings.show_until | dataCompare }}</td>
                                <td>@{{ travelWarnings.status }}</td>
                                <td class="text-center" nowrap="nowrap">
                                    <a :href="'travelwarning/' + travelWarnings.id + '/edit'" class="btn btn-sm edit-user-button" title="Edit">
                                        <i class="fal fa-pencil"></i>
                                    </a>
                                    <a class="btn btn-sm btn-outline-danger delete-confirmation-button" :href="'travelwarning/' + travelWarnings.id" title="Delete">
                                        <i class="fal fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div v-if="travelWarningsCount == 0">
                        <h6 class="text-center block-header-default py-3 mb-0">No record found</h6>
                    </div>
                <div v-else>
                    <div class="row align-items-center">
                        <div class="col-md-5 col-sm-12 dataTables_info table-pagination-info">
                            <pagination>
                            </pagination>
                        </div>
                        <div class="col-md-7 col-sm-12 dataTables_paginate">
                            <ul id="travelWarnings_pagination" class="pagination-sm justify-content-center justify-content-md-end mb-0">
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
