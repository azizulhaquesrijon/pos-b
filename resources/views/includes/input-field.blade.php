@php
    $title = isset($title) ? $title : str_replace('_', ' ', ucfirst($name));
    $type = isset($type) ? $type : 'text';
    $placeholder = isset($placeholder) ? $placeholder : 'Type ' . ucfirst($name);
    $value = isset($value) ? $value : '';
@endphp

<div class="form-group row">
    <label class="col-sm-3 control-label" for="{{ $name }}">
        <b>{{ $title }}
            @if (isset($is_required))
                <span class="text-danger">*</span>
            @endif
        </b>
    </label>
    <div class="col-xs-12 col-sm-8 ">
        @if (isset($textarea))
            <textarea name="{{ $name }}" id="{{ $name }}" class="form-control input-sm"
                placeholder="{{ $placeholder }}">{{ $value }}</textarea>
        @else
            <input type="{{ $type }}"
                class="form-control @if (isset($date)) date-picker @endif input-sm @error($name) has-error @enderror"
                name="{{ $name }}" value="{{ old($name, $value) }}" id="{{ $name }}" autocomplete="off"
                placeholder="{{ $placeholder }}" @if (isset($is_required)) required @endif>
        @endif

        @error($name)
            <span class="text-danger">
                {{ $message }}
            </span>
        @enderror
    </div>
</div>
