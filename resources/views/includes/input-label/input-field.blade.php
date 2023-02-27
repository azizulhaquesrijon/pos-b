
@php
    $isRequired = isset($is_required);
    $colSm      = isset($colSm) ? $colSm : 8;
    $title      = isset($title) ? $title : ucwords($name);
    $value      = isset($value) ? $value : '';
    $type       = isset($type)  ? $type  : 'text';
@endphp


<div class="form-group">
    <label class="col-sm-4 control-label no-padding-right" for="{{ $name }}">
        {{ $title }}

        @if($isRequired)
            <sup class="text-danger">*</sup>
        @endif
    </label>


    
    <div class="col-sm-8">
        <input id="{{ $name }}" name="{{ $name }}" {{ $isRequired ? 'required' : '' }} type="{{ $type }}"

           @if(isset($is_number))
                onkeypress="return onlyNumber(event)"
           @endif

           class="form-control input-sm" autocomplete="off" placeholder="Type {{ ucwords($title) }}" value="{{ old($name, $value) }}">
    </div>
</div>

<br>
<br>
