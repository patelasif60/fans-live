<div class="content-heading pt-0">
    Transaction
</div>
<table class="table table-striped table-borderless">
    <tr>
        <th scope="row">Date:</th>
        <td>{{ convertDateTimezone($transaction->transaction_timestamp, null, $transaction->club->time_zone, config('fanslive.DATE_TIME_CMS_FORMAT.php')) }} ({{ $transaction->club->time_zone }})</td>
    </tr>
    <tr>
        <th scope="row">Item description:</th>
        <td>
            @if ($type == 'food_and_drink')
            Food and Drink
            @elseif ($type == 'merchandise')
                Merchandise
            @elseif ($type == 'event')
                Event
            @elseif ($type == 'ticket')
                Tickets
            @elseif ($type == 'membership')
                Membership
            @elseif ($type == 'hospitality')
                Hospitality
            @else
                {{ $type }}
            @endif
        </td>
    </tr>
    <tr>
        <th scope="row">Payworks identifier:</th>
        <td>{{ $transaction->transaction_id }}</td>
    </tr>
   {{--  <tr>
        <th scope="row">Type:</th>
        <td>{{ $transaction->payment_type }}</td>
    </tr> --}}
    <tr>
        <th scope="row">Gross amount:</th>
        <td>{{ Config('fanslive.CURRENCY_SYMBOL.' . $transaction->currency) }}{{ $transaction->price }}</td>
    </tr>
    <tr>
        <th scope="row">Card fee:</th>
        <td>{{ Config('fanslive.CURRENCY_SYMBOL.' . $transaction->currency) }}{{ number_format($transaction->price * ($transaction->fee/100), 2) }}</td>
    </tr>
    <tr>
        <th scope="row">Net amount:</th>
        <td>{{ Config('fanslive.CURRENCY_SYMBOL.' . $transaction->currency) }}{{ number_format($transaction->price / (1+($transaction->fee/100)), 2) }}</td>
    </tr>
    <tr>
        <th scope="row">Transaction status:</th>
        <td>{{ ucfirst(trans($transaction->status)) }}</td>
    </tr>
</table>

<div class="content-heading pt-0">
    Payment
</div>

<table class="table table-striped table-borderless">
    <tr>
        <th scope="row">Payment scheme:</th>
        <td>{{ (($cardDetails != NULL) && (isset($cardDetails['holder']))) ? $cardDetails['holder'] : '' }}</td>
    </tr>
    <tr>
        <th scope="row">Account (last 4 digits):</th>
        <td>{{ (($cardDetails != NULL) && (isset($cardDetails['last4Digits']))) ? $cardDetails['last4Digits'] : '' }}</td>
    </tr>
    <tr>
        <th scope="row">Payment status:</th>
        <td>{{ $transaction->payment_status }}</td>
    </tr>
</table>

<div class="content-heading pt-15">
    Purchase
</div>

<table class="table table-striped table-borderless">
    <tr>
        <th scope="row">Name:</th>
        <td>{{ $transaction->consumer->user->first_name }} {{ $transaction->consumer->user->last_name }}</td>
    </tr>
    <tr>
        <th scope="row">Email:</th>
        <td><a href="mailto:{{ $transaction->consumer->user->email }}">{{ $transaction->consumer->user->email }}</a></td>
    </tr>
</table>

@if (in_array($type, ['ticket','food_and_drink','merchandise','hospitality']))
    <div class="block block-bordered">
        <div class="block-header block-header-default block-title">
            Match details
        </div>
        <div class="block-content block-content-full">
            <table class="table table-bordered mb-0">
                <tr>
                    <th>Home</th>
                    <th>Away</th>
                    <th>Kick off date/time</th>
                </tr>
                <tr>
                    <td>{{ $transaction->match->homeTeam->name }}</td>
                    <td>{{ $transaction->match->awayTeam->name }}</td>
                    <td>{{ convertDateTimezone($transaction->match->kickoff_time, null, null, config('fanslive.DATE_TIME_CMS_FORMAT.php')) }}</td>
                </tr>
            </table>
        </div>
    </div>
@endif
@if ($type == 'membership')
    <div class="block block-bordered">
        <div class="block-header block-header-default block-title">
            Membership details
        </div>
        <div class="block-content block-content-full">
            <table class="table table-bordered mb-0">
                <tr>
                    <th scope="row">Membership title:</th>
                    <td>{{ $transaction->membershipPackage->title }}</td>
                </tr>
            </table>
        </div>
    </div>
@elseif ($type == 'event')
    <div class="block block-bordered">
        <div class="block-header block-header-default block-title">
            Event details
        </div>
        <div class="block-content block-content-full">
            <table class="table table-bordered mb-0">
                <tr>
                    <th>Event title</th>
                    <th>Event location</th>
                    <th>Seat(s)</th>
                </tr>
                <tr>
                    <td>{{ $transaction->event->title }}</td>
                    <td>{{ $transaction->event->location }}</td>
                    <td>{{ $transaction->bookedEvents->implode('seat',', ') }}</td>
                </tr>
            </table>
        </div>
    </div>
@elseif ($type == 'hospitality')
    <div class="block block-bordered">
        <div class="block-header block-header-default block-title">
            Hospitality details
        </div>
        <div class="block-content block-content-full">
            <table class="table table-bordered mb-0">
                <tr>
                    <th>Hospitality title</th>
                    <th>Seat(s)</th>
                </tr>
                <tr>
                    <td>{{ $transaction->hospitalitySuite->title }}</td>
                    <td>{{ $transaction->bookedHospitalitySuits->implode('seat',', ') }}</td>
                </tr>
            </table>
        </div>
    </div>
@elseif ($type == 'ticket')
    <div class="block block-bordered">
        <div class="block-header block-header-default block-title">
            Ticket details
        </div>
        <div class="block-content block-content-full">
            <table class="table table-bordered mb-0">
                <tr>
                    <th scope="row">Seat(s):</th>
                    @if(isset($club->stadium->is_using_allocated_seating) && $club->stadium->is_using_allocated_seating > 0)
                    <td>
                        @foreach ($transaction->bookedTickets as $key => $value)
                            {{ $value->stadiumBlockSeat->stadiumBlock->name }} - {{ $value->stadiumBlockSeat->row }}x{{ $value->stadiumBlockSeat->seat }}
                            @if ($transaction->bookedTickets->keys()->last() != $key)
                                ,
                            @endif
                        @endforeach
                    </td>
                    @else
                        <td>
                        @foreach ($transaction->bookedTickets as $key => $value)
                            {{ $value->seat }}
                            @if ($transaction->bookedTickets->keys()->last() != $key)
                                ,
                            @endif
                        @endforeach
                        </td>
                    @endif
                </tr>
            </table>
        </div>
    </div>
@elseif ($type == 'food_and_drink')
    <div class="block block-bordered">
        <div class="block-header block-header-default block-title">
            Food & Drinks
        </div>
        <div class="block-content block-content-full">
            <table class="table table-bordered mb-0">
                <tr>
                    <th>Product</th>
                    <th>Product options</th>
                    <th>Product price</th>
                </tr>
                @if ($transaction->purchasedProducts)
                    @foreach ($transaction->purchasedProducts as $purchasedProduct)
                        <tr>
                            <td>{{ $purchasedProduct->product->title }}</td>
                            <td>{{ $purchasedProduct->options->implode('name',', ') }}</td>
                            <td>
                                @if ($purchasedProduct->per_quantity_actual_price != null)
                                    {{ Config('fanslive.CURRENCY_SYMBOL.' . $transaction->currency) }}{{ $purchasedProduct->per_quantity_price }} <del>{{ Config('fanslive.CURRENCY_SYMBOL.' . $transaction->currency) }}{{ $purchasedProduct->per_quantity_actual_price }}</del>
                                @else
                                    {{ Config('fanslive.CURRENCY_SYMBOL.' . $transaction->currency) }}{{ $purchasedProduct->per_quantity_price }}
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @endif
            </table>
        </div>
    </div>
@elseif ($type == 'merchandise')
    <div class="block block-bordered">
        <div class="block-header block-header-default block-title">
            Merchandise
        </div>
        <div class="block-content block-content-full">
            <table class="table table-bordered mb-0">
                <tr>
                    <th>Product</th>
                    <th>Product options</th>
                    <th>Product Price</th>
                </tr>
                @if ($transaction->purchasedProducts)
                    @foreach ($transaction->purchasedProducts as $purchasedProduct)
                        <tr>
                            <td>{{ $purchasedProduct->product->title }}</td>
                            <td>{{ $purchasedProduct->options->implode('name',', ') }}</td>
                            <td>
                                @if ($purchasedProduct->per_quantity_actual_price != null)
                                    {{ Config('fanslive.CURRENCY_SYMBOL.' . $transaction->currency) }}{{ $purchasedProduct->per_quantity_price }} <del>{{ Config('fanslive.CURRENCY_SYMBOL.' . $transaction->currency) }}{{ $purchasedProduct->per_quantity_actual_price }}</del>
                                @else
                                    {{ Config('fanslive.CURRENCY_SYMBOL.' . $transaction->currency) }}{{ $purchasedProduct->per_quantity_price }}
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @endif
            </table>
        </div>
    </div>
@endif
