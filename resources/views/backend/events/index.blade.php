@extends('layouts.backend')

@section('plugin-styles')
	<link rel="stylesheet" href="{{ asset('plugins/datatables/dataTables.bootstrap4.css') }}">
@endsection

@section('plugin-scripts')
	<script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
	<script src="{{ asset('plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
@endsection

@section('page-scripts')
	<script src="{{ asset('js/backend/pages/events/index.js') }}"></script>
@endsection

@section('content')

	<div id="event_list" v-cloak class="search-table-result">

		<div class="d-flex justify-content-between align-items-center mb-20">
			<h2 class="h4 font-w300 mb-0">Event list</h2>
			<div><a class="btn btn-sm btn-outline-primary"
					href="{{ route('backend.event.create', ['club' => app()->request->route('club')])}}">
					<i class="far fa-plus mr-1"></i> Add new
				</a></div>
		</div>

		<div class="block" id="frm_search_data">
			<form class="event-search-form" method="POST">
				<div class="block-header block-header-default">
					<h3 class="block-title">Filter</h3>
					<div class="block-options">
						<button type="submit" class="btn btn-noborder btn-primary" id="searchEvent">Search</button>
						<button type="button" class="btn btn-noborder btn-alt-secondary"
								@click="clearForm('frmSearchData')">Clear
						</button>
					</div>
				</div>
				<div class="block-content block-content-full">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group row mb-0">
								<label class="col-12" for="title">Event:</label>
								<div class="col-12">
									<input type="text" class="form-control" id="title" name="title" placeholder="Event">
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
							<th data-field="events.title" @click="sortByKey('events.title')"
								:class="[sortKey != 'events.title' ? 'sorting' : sortOrder == 1 ? 'sorting_asc' : 'sorting_desc']">
								Event
							</th>
							<th data-field="events.price" @click="sortByKey('events.price')"
								:class="[sortKey != 'events.price' ? 'sorting' : sortOrder == 1 ? 'sorting_asc' : 'sorting_desc']">
								PRICE (INC VAT)
							</th>
							<th data-field="events.date_time" @click="sortByKey('events.date_time')"
								:class="[sortKey != 'events.date_time' ? 'sorting' : sortOrder == 1 ? 'sorting_asc' : 'sorting_desc']">
								Date
							</th>
							<th data-field="events.status" @click="sortByKey('events.status')"
								:class="[sortKey != 'events.status' ? 'sorting' : sortOrder == 1 ? 'sorting_asc' : 'sorting_desc']">
								Status
							</th>
							<th class="text-center">Actions</th>
						</tr>
						</thead>
						<tbody>
						<tr v-for="event in eventData">
							<td>@{{ event.title | formattext(20, '...') }}</td>
							<td>{{ $currencyIcon }}@{{ parseFloat(event.price) + (( event.price * event.vat_rate ) / 100) | numberformat }}</td>
							<td>@{{ event.date_time | formatDate }}</td>
							<td>@{{ event.status }}</td>
							<td class="text-center" nowrap="nowrap">
								<a :href="'event/' + event.id + '/edit'" class="btn btn-sm edit-user-button"
								   title="Edit">
									<i class="fal fa-pencil"></i>
								</a>
								<a class="btn btn-sm btn-outline-danger delete-button" @click="deleteData(event.id)" href="javascript:void(0);" title="Delete">
									<i class="fal fa-trash"></i>
								</a>
							</td>
						</tr>
						</tbody>
					</table>
				</div>
				<div v-if="eventCount == 0">
					<h6 class="text-center block-header-default py-3 mb-0">No record found</h6>
				</div>
				<div v-else>
					<div class="row align-items-center">
						<div class="col-md-5 col-sm-12 dataTables_info table-pagination-info">
							<pagination>
							</pagination>
						</div>
						<div class="col-md-7 col-sm-12 dataTables_paginate">
							<ul id="event_pagination"
								class="pagination-sm justify-content-center justify-content-md-end mb-0">
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
