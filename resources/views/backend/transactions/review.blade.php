@extends('layouts.backend')

@section('plugin-styles')
	<link rel="stylesheet" href="{{ asset('plugins/datatables/dataTables.bootstrap4.css') }}">
@endsection

@section('plugin-scripts')
	<script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
	<script src="{{ asset('plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
@endsection

@section('page-scripts')
	<script src="{{ asset('js/backend/pages/transactions/review.js') }}"></script>
@endsection

@section('content')
	<div id="transaction_list" v-cloak class="search-table-result mb-60">
		<h2 class="content-heading">
			Transaction list
		</h2>

		<div class="block">
			<div class="block-header block-header-default">
				<h3 class="block-title">Result</h3>
				<div class="block-options">
					<button type="button" data-url="{{ route('backend.transaction.report.export', ['type' => strtolower($type)]) }}" class="btn btn-noborder btn-alt-secondary js-export-button">Export</a>
				</div>
			</div>
			<div class="block-content block-content-full">
				<div class="table-responsive">
					<table
						class="table table-striped table-hover table-vcenter transaction-list-table custom-listing-table">
						<thead>
						<tr>
							<th>Consumer name</th>
							<th>Consumer email</th>
							<th>No. of transactions</th>
							<th>Total gross</th>
							<th>Total net</th>
							<th>Total owed</th>
							<th>Date range</th>
							<th class="text-center">Actions</th>
						</tr>
						</thead>
						<tbody>
						<tr v-for="transaction in transactionData">
							<td>@{{ transaction | name }}</td>
							<td>@{{ transaction | email }}</td>
							<td>@{{ transaction | totalTransactions }}</td>
							<td>@{{ transaction | totalGross }}</td>
							<td>@{{ transaction | totalNet }}</td>
							<td>@{{ transaction | totalOwed }}</td>
							<td>@{{ transaction | dateRange }}</td>
							<td class="text-center" nowrap="nowrap">
								<a :href="'#'" class="btn btn-sm js-view-review-transaction-detail" title="View" :data-id="transaction|consumerId" :data-type="transaction|transactionType" data-currency="{{ $type }}">
									<i class="fal fa-eye"></i>
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
	<div class="modal fade" id="transaction_review_content" role="dialog"
		 aria-labelledby="transaction_review_content" aria-hidden="true">
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
					<div class="review-content-wrapper ml-15 mr-15"></div>
				</div>
			</div>
		</div>
	</div>
	<!-- END Fade In Modal -->
@endsection
