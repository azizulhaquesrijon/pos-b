

@php
    $isRequired = isset($is_required);
    $colSm      = isset($colSm) ? $colSm : 8;
    $title      = isset($title) ? $title : 'Image';
    $value      = isset($value) ? $value : '';
    $type       = isset($type)  ? $type  : 'text';
@endphp



<div class="form-group">
    <label class="col-sm-4 control-label no-padding-right"> 
        {{ $title }} 
        
        @if(isset($src))
            <img src="{{ $src }}" style="height: 120px; width: 120px; margin-top: 12px;">
        @endif
    </label>

    <div class="col-sm-8">

        @if(isset($multiple))
            <input multiple="" class="file image-upload" type="file" name="{{ $name }}[]" />
        @else 
            <input class="file image-upload" name="{{ $name }}" type="file"/>
        @endif
    </div>
</div>

<br>
<br>