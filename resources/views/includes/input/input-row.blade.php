@php
$isRequired = isset($is_required);
$colSm = isset($colSm) ? $colSm : 9;
$title = isset($title) ? $title : ucwords($name);
$value = isset($value) ? $value : '';
$type = isset($type) ? $type : 'text';
$group = isset($group) ? true : false;
$icon = isset($icon) ? $icon : 'user';
$read_only = isset($read_only) ? true : false;
$select_value = isset($selectValue) ? $selectValue : ''
@endphp

<div class="form-group">
    <label for="{{ $name }}">
        <b>{{ ucwords($title) }}:
            @if ($isRequired)
                <sup class="text-danger">*</sup>
            @endif
        </b>
    </label>
    @if ($group)
        <div class="input-group">
            <span class="input-group-addon">
                <i class="ace-icon fa fa-{{ $icon }}"></i>
            </span>
            <input id="{{ $name }}" name="{{ $name }}" {{ $isRequired ? 'required' : '' }}
                type="{{ $type }}" @if (isset($is_number)) onkeypress="return onlyNumber(event)" @endif class="form-control"
                autocomplete="off" placeholder="Type {{ ucwords($title) }}" value="{{ old($name, $value) }}" {{ $read_only ? 'readonly' : ''}}>
        </div>
    @elseif (isset($option))
        <select name="{{ $name }}" {{ $isRequired ? 'required' : '' }} class="chosen-select">
            <option value="">--Select Option--</option>

            @foreach ($modelVariable as $item)
                <option value="{{ $item->id }}" {{ oldSelect($name, $item->id) }} {{ ($item->id==$select_value) ? 'selected' : '' }} >
                    {{ $item->name }}</option>
            @endforeach
        </select>
    @else
        <input id="{{ $name }}" name="{{ $name }}" {{ $isRequired ? 'required' : '' }}
            type="{{ $type }}" @if (isset($is_number)) onkeypress="return onlyNumber(event)" @endif class="form-control"
            autocomplete="off" placeholder="Type {{ ucwords($title) }}" value="{{ old($name, $value) }}" {{ $read_only ? 'readonly' : ''}} >
    @endif
</div>
