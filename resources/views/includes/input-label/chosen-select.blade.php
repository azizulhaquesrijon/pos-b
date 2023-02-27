

@php
    $title = isset($title) ? $title : 'Title';
    $required_class = '';

    if (isset($required)) {
        $required_class = 'required';
    }


    if(isset($value)) {

        if (isset($multiple)) {
            $value = old($name, $value);
        } else {
            $value = [$value]; 
        }
    } else {
        $value = [];
    }
@endphp





<div class="form-group">
    <label class="col-sm-4 control-label no-padding-right"> {{ $title }} 
        @if (isset($required))
            <sup class="text-danger">*</sup>
        @endif
    </label>

    <div class="col-sm-8">
        <select name="{{ $name }}" class="chosen-select-100-percent form-control"
        
            @if (isset($multiple)) 
                multiple
            @else 
                data-placeholder="- Select {{ $title }} -"
            @endif

            @if($required_class) 
                required="required" 
            @endif


            @if(isset($method_name))
                onchange="{{ $method_name }}()"
            @endif
        >

            @if (!isset($multiple))
                <option  value="" selected></option>
            @endif

            @foreach($items as $key => $item)
                <option  value="{{ $key }}" {{ in_array($key, $value) ? 'selected' : '' }}>{{ $item }}</option>
            @endforeach
        </select>
    </div>
</div>





<br>
<br>



