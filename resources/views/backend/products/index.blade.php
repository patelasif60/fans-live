@extends('layouts.backend')

@section('plugin-styles')
    <link rel="stylesheet" href="{{ asset('plugins/datatables/dataTables.bootstrap4.css') }}">
@endsection

@section('plugin-scripts')
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
@endsection

@section('page-scripts')
    <script src="{{ asset('js/backend/pages/products/index.js') }}"></script>
@endsection

@section('content')
	 <div id="product_list" v-cloak class="search-table-result">

		 <div class="d-flex justify-content-between align-items-center mb-20">
			 <h2 class="h4 font-w300 mb-0">Product list</h2>
			 <div><a class="btn btn-sm btn-outline-primary"
					 href="{{ route('backend.product.create', ['club' => app()->request->route('club')])}}">
					 <i class="far fa-plus mr-1"></i> Add new
				 </a></div>
		 </div>

		 <div class="block" id="frm_search_data">
			 <div class="block-header block-header-default">
                <h3 class="block-title">Filter</h3>
                <div class="block-options">
                    <button type="button" class="btn btn-noborder btn-primary" @click="searchProductData()">Search</button>
                    <button type="button" class="btn btn-noborder btn-alt-secondary" @click="clearForm('frmSearchData')">Clear</button>
                </div>
            </div>

			<div class="block-content block-content-full">
				<div class="row">
                    <div class="col-md-6">
                        <div class="form-group row mb-0">
                            <label class="col-12" for="title">Product:</label>
                            <div class="col-12">
                                <input type="text" class="form-control" id="title" name="title" placeholder="Product">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group row mb-0">
							<label class="col-12" for="categoryType">Type:</label>
							<div class="col-12">
                                <select name="categoryType" class="form-control" id="categoryType">
                                	<option value="">Select type</option>
									@foreach($categoryTypes as $categoryTypeKey => $categoryType)
										<option value="{{$categoryTypeKey}}">{{$categoryType}}</option>
									@endforeach
								</select>
                            </div>
						</div>
                    </div>

					<div class="col-md-3">
						<div class="form-group row">
							<label class="col-12" for="categorylist">Category:</label>
							<div class="col-12">
								<select name="categorylist" class="form-control" id="categorylist">
									<option value="">Select category</option>
								</select>
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
						<table class="table table-striped table-hover table-vcenter custom-listing-table">
							<thead>
								<tr>
									<th data-field="products.title" @click="sortByKey('products.title')" :class="[sortKey != 'products.title' ? 'sorting' : sortOrder == 1 ? 'sorting_asc' : 'sorting_desc']" >Name</th>

									<th data-field="categories.title" @click="sortByKey('categories_name')" :class="[sortKey != 'categories.title' ? 'sorting' : sortOrder == 1 ? 'sorting_asc' : 'sorting_desc']">Categories</th>

									<th data-field="products.price" @click="sortByKey('products.price')" :class="[sortKey != 'products.price' ? 'sorting' : sortOrder == 1 ? 'sorting_asc' : 'sorting_desc']">BASE PRICE (INC VAT)</th>

									<th data-field="products.status" @click="sortByKey('products.status')" :class="[sortKey != 'products.status' ? 'sorting' : sortOrder == 1 ? 'sorting_asc' : 'sorting_desc']">Status</th>
									<th class="text-center">Actions</th>
								</tr>
							</thead>

							<tbody>
								<tr v-for="product in productData">
									<td>@{{ product.title }}</td>
									<td>@{{ product.categories_name }}</td>
									<td>{{ $currencyIcon }}@{{ parseFloat(product.price) + (( product.price * product.vat_rate)/ 100) | numberformat }}</td>
									<td>@{{ product.status }}</td>
									<td class="text-center" nowrap="nowrap">
										<a :href="'product/' + product.id + '/edit'" class="btn btn-sm edit-user-button" title="Edit">
											<i class="fal fa-pencil"></i>
										</a>
										<a class="btn btn-sm btn-outline-danger delete-button" @click="deleteData(product.id)" href="javascript:void(0);" title="Delete">
											<i class="fal fa-trash"></i>
										</a>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
					<div v-if="productCount == 0">
							<h6 class="text-center block-header-default py-3 mb-0">No record found</h6>
						</div>
					<div v-else>
						<div class="row align-items-center">
							<div class="col-md-5 col-sm-12 dataTables_info table-pagination-info">
								<pagination>
								</pagination>
							</div>
							<div class="col-md-7 col-sm-12 dataTables_paginate">
								<ul id="product_pagination" class="pagination-sm justify-content-center justify-content-md-end mb-0">
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
    </div>
@endsection
