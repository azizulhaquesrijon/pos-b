

@php
    $required_class = '';

    if (isset($required)) {
        $required_class = 'required';
    }

@endphp

<select class="chosen-select-100-percent {{ $required_class }}" name="{{ $name }}"

    @if($required_class) 
        required="required" 
    @endif
>

    <option value="" selected>- Select-</option>

    @foreach($items as $id => $item_name)
        <option {{ (isset($value) ? $value : getOldValue($name)) == $id ? 'selected' : ''  }} value="{{ $id }}">{{ $item_name }}</option>
    @endforeach
</select>
