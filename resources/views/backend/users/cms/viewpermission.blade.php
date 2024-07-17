<div class="form-group row">
    @foreach($permission_own as $key => $permission_own)
         @if(in_array( $key , $permission))
            <div class="col-xl-4 mb-5">
                <div class=" custom-checkbox">
                    <label class="" for="<?=strtolower(str_replace(' ', '_', $permission_own))?>">{{ $permission_own }}</label>
                
                </div>
            </div>
        @endif
    @endforeach
</div>