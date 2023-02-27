
@php
    $isRequired = isset($is_required);
    $colSm      = isset($colSm) ? $colSm    : 8;
    $name       = isset($name)  ? $name     : 'status';
    $title      = isset($title) ? $title    : ucwords($name);
    $value      = isset($value) ? $value    : 1;
@endphp


<div class="form-group">
    <label class="col-sm-4 control-label no-padding-right" for="order-status">
        {{ $title }}
    </label>

    <div class="col-sm-8">
        <label>
            <input name="{{ $name }}" class="ace ace-switch ace-switch-7" {{ $value != 0 ? 'checked' : '' }} type="checkbox">
            
            <span class="lbl"></span>
        </label>
    </div>
</div>