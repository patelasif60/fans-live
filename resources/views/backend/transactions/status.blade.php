@if ($currentPanel == 'superadmin')
<form action="{{ route('backend.transaction.report.updatestatus', ['id' => $transaction->id, 'type' => $type]) }}" method="post">
@else 
<form action="{{ route('backend.transaction.update.status', ['club' => $club,'id' => $transaction->id, 'type' => $type]) }}" method="post">
@endif
    <div class="block-content block-content-full">
        {{ csrf_field()  }}
        {{ method_field('PUT') }}
        <div class="form-group row">
            <label class="col-lg-4 col-form-label" for="example-hf-email">Payment status:</label>
            <div class="col-lg-6">
                <select name="payment_status" id="form_payment_status" class="form-control js-select2 js-select2-allow-clear" data-minimum-results-for-search="-1">
                    @foreach(config('fanslive.PAYMENT_STATUS') as $option)
                        <option value="{{ $option }}" @if($option === $transaction->payment_status) selected @endif>{{ $option }}</option>
                    @endforeach
                </select>
            </div>
            <input type="hidden" name="type" value="{{ $type }}">
        </div>
    </div>
    <div class="bg-body-light block-content block-content-full">
        <button type="submit" class="btn btn-hero btn-noborder btn-primary">
            Update
        </button>
    </div>
</form>