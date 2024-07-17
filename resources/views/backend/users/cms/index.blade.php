@extends('layouts.backend')

@section('plugin-styles')
	<link rel="stylesheet" href="{{ asset('plugins/datatables/dataTables.bootstrap4.css') }}">
@endsection

@section('plugin-scripts')
	<script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
	<script src="{{ asset('plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
@endsection

@section('page-scripts')
	<script src="{{ asset('js/backend/pages/users/cms/index.js') }}"></script>
@endsection

@section('content')
	<div id="cms_users_list" v-cloak class="search-table-result">

		<div class="d-flex justify-content-between align-items-center mb-20">
			<h2 class="h4 font-w300 mb-0">CMS users list</h2>
			<div>
				<a class="btn btn-sm btn-outline-primary"
				   href="{{ isset($clubData) ? route('backend.cms.club.create',['club' => app()->request->route('club')]) : route('backend.cms.create')}}">
					<i class="far fa-plus mr-1"></i> Add new
				</a>
			</div>
		</div>

		<div class="block" id="frm_search_data">
			<div class="block-header block-header-default">
				<h3 class="block-title">Filter</h3>
				<div class="block-options">
					<button type="button" class="btn btn-noborder btn-primary" @click="searchCMSUserData()">Search</button>
					<button type="button" id='resetForm' class="btn btn-noborder btn-alt-secondary" @click="clearForm('frmSearchData')">Clear</button>
				</div>
			</div>
			<div class="block-content block-content-full">
				<div class="row">
					<div class="col-md-4">
						<div class="form-group row mb-0">
							<label class="col-12" for="first_name">First name</label>
							<div class="col-12">
								<input type="text" class="form-control" id="first_name" name="first_name" placeholder="First name">
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group row mb-0">
							<label class="col-12" for="last_name">Last name</label>
							<div class="col-12">
								<input type="text" class="form-control" id="last_name" name="last_name" placeholder="Last name">
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group row mb-0">
							<label class="col-12" for="club">Club</label>
							<div class="col-12">
								<select {{isset($clubData)?'disabled':''}} class="js-select2 form-control js-select2-allow-clear" id="club" name="club">
									@if(isset($clubData))
										<option value='{{$clubData->id}}'>{{$clubData->name}}</option>
									@else
										<option value="">Select club</option>
										<option value='Superadmin'>Super admin</option>
										@foreach($clubs as $club)
											<option value="{{ $club->id }}">{{ $club->category ? $club->name . ' (' . $club->category->name . ')' : $club->name }}</option>
										@endforeach
									@endif
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
					<table class="table table-striped table-hover table-vcenter cms-users-list-table custom-listing-table">
						<thead>
						<tr>
							<th class="nowrap" data-field="users.first_name" @click="sortByKey('users.first_name')" :class="[sortKey != 'users.first_name' ? 'sorting' : sortOrder == 1 ? 'sorting_asc' : 'sorting_desc']">First name</th>
							<th class="nowrap" data-field="users.last_name" @click="sortByKey('users.last_name')" :class="[sortKey != 'users.last_name' ? 'sorting' : sortOrder == 1 ? 'sorting_asc' : 'sorting_desc']">Last name</th>
							<th data-field="users.email" @click="sortByKey('users.email')" :class="[sortKey != 'users.email' ? 'sorting' : sortOrder == 1 ? 'sorting_asc' : 'sorting_desc']">Email</th>
							<th data-field="clubs.name" @click="sortByKey('clubs.name')" :class="[sortKey != 'clubs.name' ? 'sorting' : sortOrder == 1 ? 'sorting_asc' : 'sorting_desc']">Club</th>
							<th data-field="roles.name" @click="sortByKey('roles.name')" :class="[sortKey != 'roles.name' ? 'sorting' : sortOrder == 1 ? 'sorting_asc' : 'sorting_desc']">Role</th>
							<th class="text-center">Status</th>
							<th class="text-center">Actions</th>
						</tr>
						</thead>
						<tbody>
						<tr v-for="cmsUser in cmsUserData">
							<td>@{{ cmsUser.first_name }}</td>
							<td>@{{ cmsUser.last_name }}</td>
							<td>@{{ cmsUser.email }}</td>
							<td class="nowrap" v-if="cmsUser.club_name == null">Super admin</td>
							<td class="nowrap" v-else>
								@{{ cmsUser.club_name + ' (' + cmsUser.club_category_name + ')' }}
							</td>
							<td class="text-capitalize">@{{ cmsUser.role_name }}</td>
							<td v-if="cmsUser.is_verified == 1"> <span class="font-w600 text-success">Active</span></td>
							<td v-else><form :action="'cmsuser/email/' + cmsUser.id" method="post" :id="'resend_email_form_'+ cmsUser.id">{{ csrf_field() }} @method('PUT')</form><a href="javascript:void(0);" class="font-w600 text-danger" @click="resendEmail(cmsUser.id)">Re-send</a></td>
							<td class="text-center" nowrap="nowrap">
								<a :href="'cmsuser/' + cmsUser.id + '/edit'" class="btn btn-sm edit-user-button" title="Edit">
									<i class="fal fa-pencil"></i>
								</a>
								<a class="btn btn-sm btn-outline-danger delete-confirmation-button" :href="'cmsuser/' + cmsUser.id" title="Delete">
									<i class="fal fa-trash"></i>
								</a>
							</td>
						</tr>
						</tbody>
					</table>
				</div>
				<div v-if="cmsUserCount == 0">
					<h6 class="text-center block-header-default py-3 mb-0">No record found</h6>
				</div>
				<div v-else>
					<div class="row align-items-center">
						<div class="col-md-5 col-sm-12 dataTables_info table-pagination-info">
							<pagination>
							</pagination>
						</div>
						<div class="col-md-7 col-sm-12 dataTables_paginate">
							<ul id="cms_user_pagination" class="pagination-sm justify-content-center justify-content-md-end mb-0">
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
