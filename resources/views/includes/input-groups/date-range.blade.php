@php
    $date1 = isset($date1) ? $date1 : '';
    $date2 = isset($date2) ? $date2 : '';
    $isRequired = isset($is_required) ? $is_required : '';
    $isReadOnly = isset($is_read_only) ? $is_read_only : false;
@endphp

<div class="input-daterange input-group">
    <span class="input-group-addon" style="border-left: 1px solid #ccc;">
        Date
        @if($isRequired)
            <sup class="text-danger">*</sup>
        @endif
    </span>

    <input type="text"
           name="from"
           class="input-sm form-control date-picker"
           value="{{fdate($date1)}}"
           autocomplete="off"
           placeholder="From"
           style="cursor: pointer"
        {{ $isReadOnly ? 'readonly' : '' }}
        {{ $isRequired ? 'required' : '' }}
    >

    <span class="input-group-addon">
        <i class="fa fa-exchange"></i>
    </span>

    <input type="text"
           name="to"
           class="input-sm form-control date-picker"
           value="{{fdate($date2)}}"
           autocomplete="off"
           placeholder="To"
           style="cursor: pointer"
        {{ $isReadOnly ? 'readonly' : '' }}
        {{ $isRequired ? 'required' : '' }}
    >
</div>
