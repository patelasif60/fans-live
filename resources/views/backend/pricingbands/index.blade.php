@extends('layouts.backend')

@section('plugin-styles')
	<link rel="stylesheet" href="{{ asset('plugins/datatables/dataTables.bootstrap4.css') }}">
@endsection

@section('plugin-scripts')
	<script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
	<script src="{{ asset('plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
@endsection

@section('page-scripts')
	<script src="{{ asset('js/backend/pages/pricingbands/index.js') }}"></script>
@endsection

@section('content')
	<div id="pricing_band">

		<div class="d-flex justify-content-between align-items-center mb-20">
			<h2 class="h4 font-w300 mb-0">Pricing bands list</h2>
			<div><a class="btn btn-sm btn-outline-primary"
					href="{{ route('backend.pricingbands.create', ['club' => app()->request->route('club')])}}">
					<i class="far fa-plus mr-1"></i> Add new
				</a></div>
		</div>

        <div class="block">
            <div class="block-header block-header-default">
                <h3 class="block-title">Result</h3>
            </div>
            <div class="block-content block-content-full">
            	<div class="table-responsive">
					<table class="table table-striped table-hover table-vcenter pricing-band-list-table custom-listing-table">
						<thead>
							<tr>
								<th data-field="pricing_bands.display_name" @click="sortByKey('pricing_bands.display_name')" :class="[sortKey != 'pricing_bands.display_name' ? 'sorting' : sortOrder == 1 ? 'sorting_asc' : 'sorting_desc']">Display name</th>
								<th data-field="pricing_bands.internal_name" @click="sortByKey('pricing_bands.internal_name')" :class="[sortKey != 'pricing_bands.internal_name' ? 'sorting' : sortOrder == 1 ? 'sorting_asc' : 'sorting_desc']">Internal name</th>
								<th data-field="pricing_bands.price" @click="sortByKey('pricing_bands.price')" :class="[sortKey != 'pricing_bands.price' ? 'sorting' : sortOrder == 1 ? 'sorting_asc' : 'sorting_desc']">PRICE (INC VAT)</th>
								@if($clubDetail->stadium && $clubDetail->stadium->is_using_allocated_seating == 1)<th class="text-center">Seats configured</th>@endif
								<th class="text-center">Actions</th>
							</tr>
						</thead>
						<tbody>
							<tr v-for="pricingBand in pricingBandData">
								<td>@{{ pricingBand.display_name }}</td>
								<td>@{{ pricingBand.internal_name }}</td>
								<td>{{ $currencyIcon }}@{{ parseFloat(pricingBand.price) + (( pricingBand.price * pricingBand.vat_rate ) / 100) | numberformat }}</td>
								@if($clubDetail->stadium && $clubDetail->stadium->is_using_allocated_seating == 1)
									<td class="text-center"><i class="fal fa-check" v-if='pricingBand.seat_file_name'></i></td>
								@endif
								<td class="text-center" nowrap="nowrap">
									<a :href="'pricingband/' + pricingBand.id + '/edit'" class="btn btn-sm edit-user-button" title="Edit">
										<i class="fal fa-pencil"></i>
									</a>
									<a class="btn btn-sm btn-outline-danger delete-confirmation-button" :href="'pricingband/' + pricingBand.id" title="Delete">
										<i class="fal fa-trash"></i>
									</a>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div v-if="pricingBandCount == 0">
					<h6 class="text-center block-header-default py-3 mb-0">No record found</h6>
				</div>
				<div v-else>
					<div class="row align-items-center">
						<div class="col-md-5 col-sm-12 dataTables_info table-pagination-info">
							<pagination>
							</pagination>
						</div>
						<div class="col-md-7 col-sm-12 dataTables_paginate">
							<ul id="pricing_band_pagination" class="pagination-sm justify-content-center justify-content-md-end mb-0">
							</ul>
						</div>
					</div>
				</div>
            </div>
        </div>
	</div>
@endsection
