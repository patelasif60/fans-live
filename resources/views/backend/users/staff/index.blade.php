@extends('layouts.backend')

@section('plugin-styles')
    <link rel="stylesheet" href="{{ asset('plugins/datatables/dataTables.bootstrap4.css') }}">
@endsection

@section('plugin-scripts')
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
@endsection

@section('page-scripts')
    <script src="{{ asset('js/backend/pages/users/staff/index.js') }}"></script>
@endsection

@section('content')
    <div id="staff_users_list" v-cloak class="search-table-result">

		<div class="d-flex justify-content-between align-items-center mb-20">
			<h2 class="h4 font-w300 mb-0">Staff users list</h2>
			<div><a class="btn btn-sm btn-outline-primary"
					href="{{ isset($clubData) ? route('backend.staff.club.create',['club' => app()->request->route('club')]) : route('backend.staff.create') }}">
					<i class="far fa-plus mr-1"></i> Add new
				</a></div>
		</div>

        <div class="block" id="frm_search_data">
            <div class="block-header block-header-default">
                <h3 class="block-title">Filter</h3>
                <div class="block-options">
                    <button type="button" class="btn btn-noborder btn-primary" @click="searchAPPUserData()">Search</button>
                    <button type="button" class="btn btn-noborder btn-alt-secondary" @click="clearForm('frmSearchData')">Clear</button>
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
                                <select {{isset($clubData)?'disabled':''}}  class="js-select2 form-control js-select2-allow-clear" id="club" name="club">
                                    @if(isset($clubData))
                                        <option value='{{$clubData->id}}'>{{$clubData->name.'('.$clubData->category->name.')'}}</option>
                                    @else
                                        <option value="">Select club</option>
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
                    <table class="table table-striped table-hover table-vcenter staff-users-list-table custom-listing-table">
                        <thead>
                            <tr>
                                <th data-field="users.first_name" @click="sortByKey('users.first_name')" :class="[sortKey != 'users.first_name' ? 'sorting' : sortOrder == 1 ? 'sorting_asc' : 'sorting_desc']">First name</th>
                                <th data-field="users.last_name" @click="sortByKey('users.last_name')" :class="[sortKey != 'users.last_name' ? 'sorting' : sortOrder == 1 ? 'sorting_asc' : 'sorting_desc']">Last name</th>
                                <th data-field="users.email" @click="sortByKey('users.email')" :class="[sortKey != 'users.email' ? 'sorting' : sortOrder == 1 ? 'sorting_asc' : 'sorting_desc']">Email</th>
                                <th data-field="clubs.name" @click="sortByKey('clubs.name')" :class="[sortKey != 'clubs.name' ? 'sorting' : sortOrder == 1 ? 'sorting_asc' : 'sorting_desc']">Club</th>
                                <th data-field="users.status" @click="sortByKey('users.status')" :class="[sortKey != 'users.status' ? 'sorting' : sortOrder == 1 ? 'sorting_asc' : 'sorting_desc']">Status</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="staffUser in staffUserData">
                                <td>@{{ staffUser.first_name }}</td>
                                <td>@{{ staffUser.last_name }}</td>
                                <td>@{{ staffUser.email }}</td>
                                <td>@{{ staffUser.club_name }} (@{{ staffUser.club_category_name }})</td>
                                <td>@{{ staffUser.status }}</td>
                                <td class="text-center" nowrap="nowrap">
                                    <a :href="'staffuser/' + staffUser.id + '/edit'" class="btn btn-sm edit-user-button" title="Edit">
                                        <i class="fal fa-pencil"></i>
                                    </a>
                                    <a class="btn btn-sm btn-outline-danger delete-button" @click="deleteData(staffUser.id)" href="javascript:void(0);" title="Delete">
                                        <i class="fal fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div v-if="staffUserCount == 0">
                    <h6 class="text-center block-header-default py-3 mb-0">No record found</h6>
                </div>
                <div v-else>
                    <div class="row align-items-center">
                        <div class="col-md-5 col-sm-12 dataTables_info table-pagination-info">
                            <pagination>
                            </pagination>
                        </div>
                        <div class="col-md-7 col-sm-12 dataTables_paginate">
                            <ul id="staff_user_pagination" class="pagination-sm justify-content-center justify-content-md-end mb-0">
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
