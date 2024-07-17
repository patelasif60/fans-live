@extends('layouts.backend')

@section('content')

	<div class="row">
		<div class="col-12">
			<div class="block">
				<div class="block-header block-header-default">
					<h3 class="block-title">Users</h3>
				</div>
				<div class="block-content block-content-full">
					<div class="row">
						<div class="col-md-4">
							<a class="block block-transparent block-dashboard" href="{{ route('backend.cms.index') }}">
								<div class="block-content block-content-full bg-cyan-lighter">
									<div class="bg-white-op-25 py-20 text-center">
										<div class="mb-20">
											<i class="si si-users fa-4x text-cyan"></i>
										</div>
										<div
											class="font-size-h4 font-w600 text-cyan">{{isset($userCount) ? $userCount['cms_users'] : '' }}</div>
										<div class="font-size-h5 text-cyan">CMS users</div>
									</div>
								</div>
							</a>
						</div>
						<div class="col-md-4">
							<a class="block block-transparent block-dashboard" href="{{ route('backend.consumer.index') }}">
								<div class="block-content block-content-full bg-blue-lighter">
									<div class="py-20 text-center bg-white-op-25">
										<div class="mb-20">
											<i class="si si-users fa-4x text-dark-blue"></i>
										</div>
										<div
											class="font-size-h4 font-w600 text-dark-blue">{{isset($userCount) ? $userCount['consumer_users'] : '' }}</div>
										<div class="font-size-h5 text-dark-blue">App users</div>
									</div>
								</div>
							</a>
						</div>
						<div class="col-md-4">
							<a class="block block-transparent block-dashboard" href="{{ route('backend.staff.index') }}">
								<div class="block-content block-content-full bg-dark-gray-lighter">
									<div class="py-20 text-center bg-white-op-25">
										<div class="mb-20">
											<i class="si si-users fa-4x text-dark-gray"></i>
										</div>
										<div
											class="font-size-h4 font-w600 text-dark-gray">{{isset($userCount) ? $userCount['staff_users'] : '' }}</div>
										<div class="font-size-h5 text-dark-gray">Staff users</div>
									</div>
								</div>
							</a>
						</div>
					</div>
				</div>
			</div>

			<div class="block">
				<div class="block-header block-header-default">
					<h3 class="block-title">Key data</h3>
				</div>
				<div class="block-content block-content-full">
					<div class="row">
						<div class="col-md-4">
							<a class="block block-transparent block-dashboard" href="{{ route('backend.clubcategory.index') }}">
								<div class="block-content block-content-full bg-cyan-lighter">
									<div class="bg-white-op-25 py-20 text-center">
										<div class="mb-20">
											<i class="si si-grid fa-4x text-cyan"></i>
										</div>
										<div
											class="font-size-h4 font-w600 text-cyan">{{isset($clubCategoryCount) ? $clubCategoryCount : '' }}</div>
										<div class="font-size-h5 text-cyan">Categories</div>
									</div>
								</div>
							</a>
						</div>
						<div class="col-md-4">
							<a class="block block-transparent block-dashboard" href="{{ route('backend.competition.index') }}">
								<div class="block-content block-content-full bg-blue-lighter">
									<div class="py-20 text-center bg-white-op-25">
										<div class="mb-20">
											<i class="si si-trophy fa-4x text-dark-blue"></i>
										</div>
										<div
											class="font-size-h4 font-w600 text-dark-blue">{{isset($competitionCount) ? $competitionCount : '' }}</div>
										<div class="font-size-h5 text-dark-blue">Competitions</div>
									</div>
								</div>
							</a>
						</div>
						<div class="col-md-4">
							<a class="block block-transparent block-dashboard" href="{{ route('backend.club.index') }}">
								<div class="block-content block-content-full bg-dark-gray-lighter">
									<div class="py-20 text-center bg-white-op-25">
										<div class="mb-20">
											<i class="si si-organization fa-4x text-dark-gray"></i>
										</div>
										<div
											class="font-size-h4 font-w600 text-dark-gray">{{isset($clubCount) ? $clubCount : '' }}</div>
										<div class="font-size-h5 text-dark-gray">Clubs</div>
									</div>
								</div>
							</a>
						</div>
					</div>
				</div>
			</div>

			<div class="block">
				<div class="block-header block-header-default">
					<h3 class="block-title">Transactions</h3>
				</div>
				<div class="block-content block-content-full">
					<div class="row">
						<div class="col-md-4">
							<a class="block block-transparent block-dashboard" href="{{ route('backend.transaction.report',['type' => 'gbp']) }}">
								<div class="block-content block-content-full bg-cyan-lighter">
									<div class="bg-white-op-25 py-20 text-center">
										<div class="mb-20">
											<i class="si si-credit-card fa-4x  text-cyan"></i>
										</div>
										<div class="font-size-h4 font-w600 text-cyan">{{isset($gbpTransactionSum) ? $gbpTransactionSum : 0.00 }}</div>
										<div class="font-size-h5 text-cyan">£</div>
									</div>
								</div>
							</a>
						</div>
						<div class="col-md-4">
							<a class="block block-transparent block-dashboard" href="{{ route('backend.transaction.report',['type' => 'eur']) }}">
								<div class="block-content block-content-full bg-blue-lighter">
									<div class="py-20 text-center bg-white-op-25">
										<div class="mb-20">
											<i class="si si-credit-card fa-4x text-dark-blue"></i>
										</div>
										<div class="font-size-h4 font-w600 text-dark-blue">{{isset($eurTransactionSum) ? $eurTransactionSum : 0.00 }}</div>
										<div class="font-size-h5 text-dark-blue">€</div>
									</div>
								</div>
							</a>
						</div>
						<div class="col-md-4">
							<a class="block block-transparent block-dashboard" href="javascript:void(0);">
								<div class="block-content block-content-full bg-dark-gray-lighter">
									<div class="py-20 text-center bg-white-op-25">
										<div class="mb-20">
											<i class="si si-wallet fa-4x text-dark-gray"></i>
										</div>
										<div class="font-size-h4 font-w600 text-dark-gray">{{isset($total) ? $total : 0.00 }}</div>
										<div class="font-size-h5 text-dark-gray">Total</div>
									</div>
								</div>
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
@endsection
