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
                            <a class="block block-transparent block-dashboard" href="{{ route('backend.cms.club.index', ['club' => app()->request->route('club')]) }}">
                                <div class="block-content block-content-full bg-cyan-lighter">
                                    <div class="bg-white-op-25 py-20 text-center">
                                        <div class="mb-20">
                                            <i class="fal fa-user-friends fa-4x text-cyan"></i>
                                        </div>
                                        <div class="font-size-h4 font-w600 text-cyan">{{ $users['cms_users'] }}</div>
                                        <div class="font-size-h5 text-cyan">CMS users</div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a class="block block-transparent block-dashboard" href="{{ route('backend.consumer.club.index', ['club' => app()->request->route('club')]) }}">
                                <div class="block-content block-content-full bg-blue-lighter">
                                    <div class="py-20 text-center bg-white-op-25">
                                        <div class="mb-20">
                                            <i class="fal fa-user-friends fa-4x text-dark-blue"></i>
                                        </div>
                                        <div class="font-size-h4 font-w600 text-dark-blue">{{ $users['consumer_users'] }}</div>
                                        <div class="font-size-h5 text-dark-blue">App users</div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a class="block block-transparent block-dashboard" href="{{ route('backend.staff.club.index', ['club' => app()->request->route('club')]) }}">
                                <div class="block-content block-content-full bg-dark-gray-lighter">
                                    <div class="py-20 text-center bg-white-op-25">
                                        <div class="mb-20">
                                            <i class="fal fa-user-friends fa-4x text-dark-gray"></i>
                                        </div>
                                        <div class="font-size-h4 font-w600 text-dark-gray">{{ $users['staff_users'] }}</div>
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
                    <h3 class="block-title">Transactions</h3>
                </div>
                <div class="block-content block-content-full">
                    <div class="row">
                        <div class="col-md-3">
                            <a class="block block-transparent block-dashboard" href="{{ route('backend.transaction.index', ['club' => app()->request->route('club')]) }}">
                                <div class="block-content block-content-full bg-cyan-lighter">
                                    <div class="bg-white-op-25 py-20 text-center">
                                        <div class="mb-20">
                                            <i class="fal fa-ticket-alt fa-4x text-cyan"></i>
                                        </div>
                                        <div class="font-size-h4 font-w600 text-cyan">{{ $transactionsCount['ticket'] }}</div>
                                        <div class="font-size-h5 text-cyan">Tickets</div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a class="block block-transparent block-dashboard" href="{{ route('backend.transaction.index', ['club' => app()->request->route('club')]) }}">
                                <div class="block-content block-content-full bg-blue-lighter">
                                    <div class="py-20 text-center bg-white-op-25">
                                        <div class="mb-20">
                                            <i class="fal fa-glass-cheers fa-4x text-dark-blue"></i>
                                        </div>
                                        <div class="font-size-h4 font-w600 text-dark-blue">{{ $transactionsCount['food_and_drink'] }}</div>
                                        <div class="font-size-h5 text-dark-blue">Food & Drink</div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a class="block block-transparent block-dashboard" href="{{ route('backend.transaction.index', ['club' => app()->request->route('club')]) }}">
                                <div class="block-content block-content-full bg-blue-lighter">
                                    <div class="py-20 text-center bg-white-op-25">
                                        <div class="mb-20">
                                            <i class="fal fa-store fa-4x text-dark-blue"></i>
                                        </div>
                                        <div class="font-size-h4 font-w600 text-dark-blue">{{ $transactionsCount['merchandise'] }}</div>
                                        <div class="font-size-h5 text-dark-blue">Merchandise</div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a class="block block-transparent block-dashboard" href="{{ route('backend.transaction.index', ['club' => app()->request->route('club')]) }}">
                                <div class="block-content block-content-full bg-blue-lighter">
                                    <div class="py-20 text-center bg-white-op-25">
                                        <div class="mb-20">
                                            <i class="fal fa-user-tag fa-4x text-dark-blue"></i>
                                        </div>
                                        <div class="font-size-h4 font-w600 text-dark-blue">{{ $transactionsCount['membership'] }}</div>
                                        <div class="font-size-h5 text-dark-blue">Membership</div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

             <div class="block">
                <div class="block-header block-header-default">
                    <h3 class="block-title">Revenue</h3>
                </div>
                <div class="block-content block-content-full">
                    <div class="row">
                        <div class="col-md-3">
                            <a class="block block-transparent block-dashboard" href="{{ route('backend.transaction.index', ['club' => app()->request->route('club')]) }}">
                                <div class="block-content block-content-full bg-cyan-lighter">
                                    <div class="bg-white-op-25 py-20 text-center">
                                        <div class="mb-20">
                                            <i class="fal fa-ticket-alt fa-4x  text-cyan"></i>
                                        </div>
                                        <div class="font-size-h4 font-w600 text-cyan">{{ config('fanslive.CURRENCY_SYMBOL.'. $clubInfo->currency) . $transactionsPriceTotal['ticket'] }}</div>
                                        <div class="font-size-h5 text-cyan">Tickets</div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a class="block block-transparent block-dashboard" href="{{ route('backend.transaction.index', ['club' => app()->request->route('club')]) }}">
                                <div class="block-content block-content-full bg-blue-lighter">
                                    <div class="py-20 text-center bg-white-op-25">
                                        <div class="mb-20">
                                            <i class="fal fa-glass-cheers fa-4x text-dark-blue"></i>
                                        </div>
                                        <div class="font-size-h4 font-w600 text-dark-blue">{{ config('fanslive.CURRENCY_SYMBOL.'. $clubInfo->currency) . $transactionsPriceTotal['food_and_drink'] }}</div>
                                        <div class="font-size-h5 text-dark-blue">Food & Drink</div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a class="block block-transparent block-dashboard" href="{{ route('backend.transaction.index', ['club' => app()->request->route('club')]) }}">
                                <div class="block-content block-content-full bg-blue-lighter">
                                    <div class="py-20 text-center bg-white-op-25">
                                        <div class="mb-20">
                                            <i class="fal fa-store fa-4x text-dark-blue"></i>
                                        </div>
                                        <div class="font-size-h4 font-w600 text-dark-blue">{{ config('fanslive.CURRENCY_SYMBOL.'. $clubInfo->currency) . $transactionsPriceTotal['merchandise'] }}</div>
                                        <div class="font-size-h5 text-dark-blue">Merchandise</div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a class="block block-transparent block-dashboard" href="{{ route('backend.transaction.index', ['club' => app()->request->route('club')]) }}">
                                <div class="block-content block-content-full bg-blue-lighter">
                                    <div class="py-20 text-center bg-white-op-25">
                                        <div class="mb-20">
                                            <i class="fal fa-user-tag fa-4x text-dark-blue"></i>
                                        </div>
                                        <div class="font-size-h4 font-w600 text-dark-blue">{{ config('fanslive.CURRENCY_SYMBOL.'. $clubInfo->currency) . $transactionsPriceTotal['membership'] }}</div>
                                        <div class="font-size-h5 text-dark-blue">Membership</div>
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
