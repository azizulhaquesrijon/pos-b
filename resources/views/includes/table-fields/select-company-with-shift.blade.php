
@php
    use Illuminate\Support\Str;

    $chosenSize = isset($chosen_size) ? $chosen_size : '100-percent';
    $isRequired = isset($is_required);

@endphp

<select name="company_id" class="chosen-select-{{ $chosenSize }} {{ $isRequired ? 'required' : '' }}" {{ $isRequired ? 'required' : '' }} onchange="getShift()" id="company" 
        data-placeholder="- Select Company -">
        
    @foreach($companies as $id => $name)
        <option {{ request('company_id') == $id ? 'selected' : '' }} value="{{ $id }}">{{ $name }}</option>
    @endforeach
</select>

