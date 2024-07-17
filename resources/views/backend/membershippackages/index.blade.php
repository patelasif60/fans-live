@extends('layouts.backend')

@section('plugin-styles')
	<link rel="stylesheet" href="{{ asset('plugins/datatables/dataTables.bootstrap4.css') }}">
@endsection

@section('plugin-scripts')
	<script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
	<script src="{{ asset('plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
@endsection

@section('page-scripts')
	<script src="{{ asset('js/backend/pages/membershippackages/index.js') }}"></script>
@endsection

@section('content')
	<div id="membership_package">

		<div class="d-flex justify-content-between align-items-center mb-20">
			<h2 class="h4 font-w300 mb-0">Membership packages list</h2>
			<div><a class="btn btn-sm btn-outline-primary"
					href="{{ route('backend.membershippackages.create', ['club' => app()->request->route('club')])}}">
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
								<th data-field="membership_packages.title" @click="sortByKey('membership_packages.title')" :class="[sortKey != 'membership_packages.title' ? 'sorting' : sortOrder == 1 ? 'sorting_asc' : 'sorting_desc']">Package name</th>
								<th data-field="membership_packages.price" @click="sortByKey('membership_packages.price')" :class="[sortKey != 'membership_packages.price' ? 'sorting' : sortOrder == 1 ? 'sorting_asc' : 'sorting_desc']">PRICE (INC VAT)</th>
								<th data-field="membership_packages.status" @click="sortByKey('membership_packages.status')" :class="[sortKey != 'membership_packages.status' ? 'sorting' : sortOrder == 1 ? 'sorting_asc' : 'sorting_desc']">Status</th>
								<th class="text-center">Actions</th>
							</tr>
						</thead>
						<tbody>
							<tr v-for="membershipPackage in membershipPackageData">
								<td>@{{ membershipPackage.title }}</td>
								<td>{{ $currencyIcon }}@{{ parseFloat(membershipPackage.price) + (( membershipPackage.price * membershipPackage.vat_rate)/ 100) | numberformat }}</td>
								<td>@{{ membershipPackage.status }}</td>
								<td class="text-center" nowrap="nowrap">
									<a :href="'membershippackage/' + membershipPackage.id + '/edit'" class="btn btn-sm edit-user-button" title="Edit">
										<i class="fal fa-pencil"></i>
									</a>
									{{-- <a class="btn btn-sm btn-outline-danger delete-confirmation-button" :href="'membershippackage/' + membershipPackage.id" title="Delete">
										<i class="fal fa-trash"></i>
									</a> --}}
									<a class="btn btn-sm btn-outline-danger delete-button" @click="deleteData(membershipPackage.id)" href="javascript:void(0);" title="Delete">
										<i class="fal fa-trash"></i>
									</a>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div v-if="membershipPackageCount == 0">
					<h6 class="text-center block-header-default py-3 mb-0">No record found</h6>
				</div>
				<div v-else>
					<div class="row align-items-center">
						<div class="col-md-5 col-sm-12 dataTables_info table-pagination-info">
							<pagination>
							</pagination>
						</div>
						<div class="col-md-7 col-sm-12 dataTables_paginate">
							<ul id="membership_package_pagination" class="pagination-sm justify-content-center justify-content-md-end mb-0">
							</ul>
						</div>
					</div>
				</div>
            </div>
        </div>
	</div>
@endsection
