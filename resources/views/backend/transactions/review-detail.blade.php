<h3 class="mb-30">
    <small>Account details</small>
</h3>
<dl class="row mb-10">
    <dt class="col-sm-4">Name:</dt>
    <dd class="col-sm-8">{{ $consumer->user->first_name }} {{ $consumer->user->last_name }}</dd>
</dl>
<dl class="row mb-10">
    <dt class="col-sm-4">Club name:</dt>
    <dd class="col-sm-8">{{ $consumer->club->name }}</dd>
</dl>
<dl class="row mb-10">
    <dt class="col-sm-4">Email:</dt>
    <dd class="col-sm-8">
        <a href="mailto:{{ $consumer->user->email }}">{{ $consumer->user->email }}</a>
    </dd>
</dl>
<dl class="row mb-10">
    <dt class="col-sm-4">Bank name:</dt>
    <dd class="col-sm-8">{{ $consumer->club->clubBankDetail->bank_name }}</dd>
</dl>
<dl class="row mb-10">
    <dt class="col-sm-4">Account name:</dt>
    <dd class="col-sm-8">{{ $consumer->club->clubBankDetail->account_name }}</dd>
</dl>
<dl class="row mb-10">
    <dt class="col-sm-4">Sort code:</dt>
    <dd class="col-sm-8">{{ $consumer->club->clubBankDetail->sort_code }}</dd>
</dl>
<dl class="row mb-10">
    <dt class="col-sm-4">Account number:</dt>
    <dd class="col-sm-8">{{ $consumer->club->clubBankDetail->account_number }}</dd>
</dl>
@if(strtoupper($type) == 'EUR')
<dl class="row mb-10">
    <dt class="col-sm-4">BIC:</dt>
    <dd class="col-sm-8">{{ $consumer->club->clubBankDetail->bic }}</dd>
</dl>
<dl class="row mb-10">
    <dt class="col-sm-4">IBAN:</dt>
    <dd class="col-sm-8">{{ $consumer->club->clubBankDetail->iban }}</dd>
</dl>
@endif
<hr>
<h3 class="mb-30">
    <small>Transaction details</small>
</h3>
<div class="table-responsive">
    <table class="table table-striped table-vcenter">
        <thead>
            <tr>
                <th>Date</th>
                <th>Item description</th>
                <th>Gross</th>
                <th>Fee</th>
                <th>Net</th>
                <th>Transaction status</th>
                <th>Payment status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $transaction)
                <?php 
                $fee = $transaction->price * ($transaction->fee/100);
                $net = $transaction->price - number_format($fee,2,'.','');
                ?>
                <tr>
                    <td>
                        {{ convertDateTimezone($transaction->transaction_timestamp, null, $transaction->club_time_zone, config('fanslive.DATE_TIME_CMS_FORMAT.php')) }} ({{ $transaction->club_time_zone }})
                    <td>
                        @if ($transaction->transaction_type == 'food_and_drink')
                            Food and Drink
                        @elseif ($transaction->transaction_type == 'merchandise')
                            Merchandise
                        @elseif ($transaction->transaction_type == 'event')
                            Event
                        @elseif ($transaction->transaction_type == 'ticket')
                            Tickets
                        @elseif ($transaction->transaction_type == 'membership')
                            Membership
                        @elseif ($transaction->transaction_type == 'hospitality')
                            Hospitality
                        @else
                            {{ $transaction->transaction_type }}
                        @endif
                    </td>
                    <td class="text-nowrap">{{ config('fanslive.CURRENCY_SYMBOL.' . $transaction->currency) . $transaction->price }}</td>
                    <td class="text-nowrap">{{ config('fanslive.CURRENCY_SYMBOL.' . $transaction->currency) . number_format($fee,2,'.','') }}</td>
                    <td class="text-nowrap">{{ config('fanslive.CURRENCY_SYMBOL.' . $transaction->currency) . number_format($net,2,'.','') }}</td>
                    <td>{{ ucfirst($transaction->status) }}</td>
                    <td>{{ $transaction->payment_status }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>