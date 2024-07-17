@extends('layouts.backend')

@section('plugin-styles')
	<link rel="stylesheet" href="{{ asset('plugins/datatables/dataTables.bootstrap4.css') }}">
@endsection

@section('plugin-scripts')
	<script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
	<script src="{{ asset('plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
@endsection

@section('page-scripts')
	<script src="{{ asset('js/backend/pages/transactions/index.js') }}"></script>
@endsection

@section('content')
	<div id="transaction_list" v-cloak class="search-table-result mb-60">
		<h2 class="content-heading">
			{{ strtoupper($clubCurrency) }} transactions
		</h2>

		<div class="block" id="frm_search_data">
			<form class="transaction-search-form" method="POST">
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
								<label class="col-12" for="text">User:</label>
								<div class="col-12">
									<select class="js-select2 js-select2-allow-clear form-control" id="consumer_id"
											name="consumer_id">
										<option value="">Select email</option>
										@foreach($consumers as $consumer)
											<option value="{{ $consumer->id }}">{{ $consumer->email }}</option>
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
						<div class="col-md-3">
							<div class="form-group row mb-0">
								<label class="col-12" for="payment_status">Status:</label>
								<div class="col-12">
									<select class="js-select2 js-select2-allow-clear form-control" id="payment_status"
											name="payment_status">
										<option value="">Select payment status</option>
										@foreach($paymentStatuses as $key=>$value)
											<option value="{{ $value }}">{{ $value }}</option>
										@endforeach
									</select>
								</div>
							</div>
						</div>
					</div>
					<div class="row mt-4">
						<div class="col-md-3">
							<div class="form-group row mb-0">
								<label class="col-12" for="last_four_digit">Last 4 digits:</label>
								<div class="col-12">
									<input type="text" class="form-control" id="last_four_digit" name="last_four_digit" placeholder="Last 4 digits of credit card">
								</div>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group row mb-0">
								<label class="col-12" for="payment_brand">Card type:</label>
								<div class="col-12">
									<select class="js-select2 js-select2-allow-clear form-control" id="payment_brand"
											name="payment_brand">
										<option value="">Select card type</option>
										@foreach($paymentCards as $paymentCard)
											<option value="{{ $paymentCard }}">{{ $paymentCard }}</option>
										@endforeach
									</select>
								</div>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group row mb-0">
								<label class="col-12" for="amount">Amount:</label>
								<div class="col-12">
									<input type="text" class="form-control" id="amount" name="amount" placeholder="Enter amount">
								</div>
							</div>
						</div>
						@if($currentPanel == 'superadmin')
						<div class="col-md-3">
							<div class="form-group row mb-0">
								<label class="col-12" for="club">Club:</label>
								<div class="col-12">
									<select class="js-select2 js-select2-allow-clear form-control" id="club" name="club">
										<option value="">Select club</option>
										@foreach($clubs as $club)
											<option value="{{ $club->id }}">{{ $club->name }}</option>
										@endforeach
									</select>
								</div>
							</div>
						</div>
						@endif
					</div>
				</div>
			</form>
		</div>

		<div class="block">
			<div class="block-header block-header-default">
				<h3 class="block-title">Result</h3>
				<div class="block-options">
					@if($currentPanel == 'superadmin')
					<a href="{{ route('backend.transaction.report.review', strtolower($clubCurrency)) }}" class="btn btn-noborder btn-alt-secondary">Review</a>
					@endif
					<button type="button" class="btn btn-noborder btn-primary js-transaction-type active-transaction" id="successful_transaction" data-value="successful">Successful transactions</button>
					<button type="button" class="btn btn-noborder btn-alt-secondary js-transaction-type" id="failed_transaction" data-value="failed">Failed transactions</button>
				</div>
			</div>
			<div class="block-content block-content-full">
				<div class="table-responsive">
					<table
						class="table table-striped table-hover table-vcenter transaction-list-table custom-listing-table">
						<thead>
						<tr>
							<th data-field="transaction_timestamp" @click="sortByKey('transaction_timestamp')"
								:class="[sortKey != 'transaction_timestamp' ? 'sorting' : sortOrder == 1 ? 'sorting_asc' : 'sorting_desc']">
								Date
							</th>
							@if($currentPanel == 'superadmin')
							<th data-field="club" @click="sortByKey('club')"
								:class="[sortKey != 'club' ? 'sorting' : sortOrder == 1 ? 'sorting_asc' : 'sorting_desc']">
								Club
							</th>
							@endif
							<th data-field="email" @click="sortByKey('email')"
								:class="[sortKey != 'email' ? 'sorting' : sortOrder == 1 ? 'sorting_asc' : 'sorting_desc']">
								User
							</th>
							<th class="nowrap" data-field="transaction_type" @click="sortByKey('transaction_type')"
								:class="[sortKey != 'transaction_type' ? 'sorting' : sortOrder == 1 ? 'sorting_asc' : 'sorting_desc']">
								Product/Service Purchase
							</th>
							{{-- <th data-field="payment_type" @click="sortByKey('payment_type')"
								:class="[sortKey != 'payment_type' ? 'sorting' : sortOrder == 1 ? 'sorting_asc' : 'sorting_desc']">
								Type
							</th> --}}
							<th data-field="price" @click="sortByKey('price')"
								:class="[sortKey != 'price' ? 'sorting' : sortOrder == 1 ? 'sorting_asc' : 'sorting_desc']">
								Gross
							</th>
							<th data-field="fee_amount" @click="sortByKey('fee_amount')"
								:class="[sortKey != 'fee_amount' ? 'sorting' : sortOrder == 1 ? 'sorting_asc' : 'sorting_desc']">
								Fee
							</th>
							<th>
								Net
							</th>
							<th class="nowrap" data-field="status" @click="sortByKey('status')"
								:class="[sortKey != 'status' ? 'sorting' : sortOrder == 1 ? 'sorting_asc' : 'sorting_desc']">
								Transaction Status
							</th>
							<th class="nowrap" data-field="payment_status" @click="sortByKey('payment_status')"
								:class="[sortKey != 'payment_status' ? 'sorting' : sortOrder == 1 ? 'sorting_asc' : 'sorting_desc']">
								Payment Status
							</th>
							<th class="text-center">Actions</th>
						</tr>
						</thead>
						<tbody>
						<tr v-for="transaction in transactionData">
							<td class="nowrap">@{{ transaction.transaction_timestamp | formatDate(transaction.club_time_zone) }}</td>
							@if($currentPanel == 'superadmin')
								<td>@{{ transaction.club }}</td>
							@endif
							<td>@{{ transaction.email }}</td>
							<td>@{{ transaction.transaction_type | formattype }}</td>
							{{-- <td>@{{ transaction.payment_type }}</td> --}}
							<td>{{ Config::get('fanslive.CURRENCY_SYMBOL.' . strtoupper($clubCurrency)) }}@{{ transaction.price | numberformat }}</td>
							<td>{{ Config::get('fanslive.CURRENCY_SYMBOL.' . strtoupper($clubCurrency)) }}@{{ transaction.fee | formatCurrency }}</td>
							<td>{{ Config::get('fanslive.CURRENCY_SYMBOL.' . strtoupper($clubCurrency)) }}@{{ transaction.price | netamount(transaction.fee_amount)}}</td>
							<td>@{{ transaction.status | ucFirst }}</td>
							<td>@{{ transaction.payment_status }}</td>
							<td class="text-center" nowrap="nowrap">
								<a :href="'javascript:void(0);'" class="btn btn-sm" title="View" @click="viewTransactionDetail" :data-id="transaction.id" :data-type="transaction.transaction_type">
									<i class="fal fa-eye"></i>
								</a>
								<a :href="'javascript:void(0);'" class="btn btn-sm" title="Edit" @click="editTransactionDetail" :data-id="transaction.id" :data-type="transaction.transaction_type">
									<i class="fal fa-pencil"></i>
								</a>
							</td>
						</tr>
						</tbody>
					</table>
				</div>
				<div v-if="transactionCount == 0">
					<h6 class="text-center block-header-default py-3 mb-0">No record found</h6>
				</div>
				<div v-else>
					<div class="row align-items-center">
						<div class="col-md-5 col-sm-12 dataTables_info table-pagination-info">
							<pagination>
							</pagination>
						</div>
						<div class="col-md-7 col-sm-12 dataTables_paginate">
							<ul id="transaction_pagination"
								class="pagination-sm justify-content-center justify-content-md-end mb-0">
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- Fade In Modal -->
	<div class="modal fade" id="transaction_info_content" role="dialog"
		 aria-labelledby="transaction_info_content" aria-hidden="true">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="block block-themed block-transparent mb-0">
					<div class="block-header bg-primary-dark">
						<h3 class="block-title">Transaction details</h3>
						<div class="block-options">
							<button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
								<i class="si si-close"></i>
							</button>
						</div>
					</div>
					<div class="block-content block-content-full">
						<div class="edit-content-wrapper transactions-information-body"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="transaction_payment_status" role="dialog"
		 aria-labelledby="transaction_payment_status" aria-hidden="true">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="block block-themed block-transparent mb-0">
					<div class="block-header bg-primary-dark">
						<h3 class="block-title">Edit transaction</h3>
						<div class="block-options">
							<button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
								<i class="si si-close"></i>
							</button>
						</div>
					</div>
					<div class="edit-status-wrapper"></div>
				</div>
			</div>
		</div>
	</div>
	<!-- END Fade In Modal -->
@endsection
