@php
    $date1 = isset($date) ? $date : '';
    $isRequired = isset($is_required) ? $is_required : '';
    $isReadOnly = isset($is_read_only) ? $is_read_only : false;
@endphp

<div class="input-group">
    <span class="input-group-addon" style="border-left: 1px solid #ccc;">
        Date
        @if($isRequired)
            <sup class="text-danger">*</sup>
        @endif
    </span>

    <input type="text"
           name="from"
           class="input-sm form-control date-picker text-center"
           value="{{ fdate($date) }}"
           autocomplete="off"
           style="cursor: pointer"
        {{ $isReadOnly ? 'readonly' : '' }}
        {{ $isRequired ? 'required' : '' }}
    >
</div>
