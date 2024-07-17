@if (strtoupper($type) == 'EUR')
    @if ($formattedData)
        @foreach($formattedData as $transaction)
            @foreach($transaction as $t)
            <tr>
                <td>{{ $t[0] }}</td>
            </tr>
            @endforeach
        @endforeach
    @endif
@elseif (strtoupper($type) == 'GBP')
    @if ($formattedData)
        @foreach($formattedData as $transaction)
            <tr>
                <td>{{ $transaction['sort_code'] }}</td>
                <td>{{ $transaction['account_name'] }}</td>
                <td>{{ $transaction['account_number'] }}</td>
                <td>{{ $transaction['total_owed'] }}</td>
                <td>{{ $transaction['transaction_reference'] }}</td>
            </tr>
        @endforeach
    @endif
@endif