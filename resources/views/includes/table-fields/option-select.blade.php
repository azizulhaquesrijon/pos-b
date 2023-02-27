@php
    use Illuminate\Support\Str;
    
    $chosenSize = isset($chosen_size) ? $chosen_size : '100-percent';
    $selectName = Str::snake(Str::singular($name)) . '_id';
    $isRequired = isset($is_required);

    $selectTitle =   isset($selectTitle) ? $selectTitle : ucwords(str_replace("_", ' ', Str::singular($name)));
    $dataVariable = Str::plural($name);
    
    $existing_id = -1;
    $value = isset($value) ? $value : '';
    
    if (request($selectName)) {
        $existing_id = request($selectName, $value);
    } else if(old($selectName)) {
        $existing_id = old($selectName, $value);
    }
    
@endphp

<select id="{{ $selectName }}" name="{{ $selectName }}" {{ $isRequired ? 'required' : '' }} class="chosen-select-{{ $chosenSize }}  {{ $isRequired ? 'required' : '' }}"
        data-placeholder="- Select {{ $selectTitle }} -">

    <option value=""></option>
    @foreach($$dataVariable as $id => $val)

        <option {{ $existing_id == $id ? 'selected' : '' }} value="{{ $id }}">{{ $val }}</option>
    @endforeach
</select>
