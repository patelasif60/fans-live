
@extends('layouts.backend')

@section('plugin-styles')
    <link rel="stylesheet" href="{{ asset('plugins/datatables/dataTables.bootstrap4.css') }}">
@endsection

@section('plugin-scripts')
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
@endsection

@section('page-scripts')
    <script src="{{ asset('js/backend/pages/hospitalitysuites/index.js') }}"></script>
@endsection

@section('content')

    <div id="HospitalitySuites_list" v-cloak class="search-table-result">


		<div class="d-flex justify-content-between align-items-center mb-20">
			<h2 class="h4 font-w300 mb-0">Hospitality and suites list</h2>
			<div><a class="btn btn-sm btn-outline-primary"
					href="{{ route('backend.hospitalitysuite.create', ['club' => app()->request->route('club')])}}">
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
                                <th data-field="hospitality_suites.title" @click="sortByKey('hospitality_suites.title')" :class="[sortKey != 'hospitality_suites.title' ? 'sorting' : sortOrder == 1 ? 'sorting_asc' : 'sorting_desc']" >DISPLAY NAME</th>
                                <th data-field="hospitality_suites.price" @click="sortByKey('hospitality_suites.price')" :class="[sortKey != 'hospitality_suites.price' ? 'sorting' : sortOrder == 1 ? 'sorting_asc' : 'sorting_desc']">PRICE (INC VAT)</th>
                                <th data-field="hospitality_suites.short_description" @click="sortByKey('hospitality_suites.short_description')" :class="[sortKey != 'hospitality_suites.short_description' ? 'sorting' : sortOrder == 1 ? 'sorting_asc' : 'sorting_desc']">Short description</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="hospitalitysuites in HospitalitySuitesData">
                                <td>@{{ hospitalitysuites.title }}</td>
                                <td>{{ $currencyIcon }}@{{ parseFloat(hospitalitysuites.price) + (( hospitalitysuites.price * hospitalitysuites.vat_rate ) / 100) | numberformat }}</td>
                                <td>@{{ hospitalitysuites.short_description }}</td>
                                <td class="text-center" nowrap="nowrap">
                                    <a :href="'hospitalitysuite/' + hospitalitysuites.id + '/edit'" class="btn btn-sm edit-user-button" title="Edit">
                                        <i class="fal fa-pencil"></i>
                                    </a>
                                    <a class="btn btn-sm btn-outline-danger delete-button" @click="deleteData(hospitalitysuites.id)" href="javascript:void(0);" title="Delete">
                                        <i class="fal fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div v-if="HospitalitySuitesCount == 0">
                    <h6 class="text-center block-header-default py-3 mb-0">No record found</h6>
                </div>
                <div v-else>
                    <div class="row align-items-center">
                        <div class="col-md-5 col-sm-12 dataTables_info table-pagination-info">
                            <pagination>
                            </pagination>
                        </div>
                        <div class="col-md-7 col-sm-12 dataTables_paginate">
                            <ul id="hospitalitysuites_pagination" class="pagination-sm justify-content-center justify-content-md-end mb-0">
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
