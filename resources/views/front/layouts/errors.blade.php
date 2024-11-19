@if($errors->has($field))
    <div class="error"><span class='text-danger'>{{ $errors->first($field) }}</span></div>
@endif
