

@php
    $isRequired = isset($is_required);
    $colSm      = isset($colSm) ? $colSm : 8;
    $title      = isset($title) ? $title : 'Select' . ucwords($name);
    $value      = isset($value) ? $value : '';
    $type       = isset($type)  ? $type  : 'text';
@endphp



<div class="form-group">
    <label class="col-sm-4 control-label no-padding-right" for="form-field-1"> {{ $title }} </label>
    
    <div class="col-sm-8">
        <span class="btn btn-dark btn-mini btn-round btn-file">
            <i class="fa fa-folder-open"></i> Browse  {{ $title }}
            @if(isset($multiple))
                <input type="file" class="photos" id="fileUpload" multiple="multiple" name="{{ $name }}[]">
            @else 
                <input type="file" class="photos" id="fileUpload" name="{{ $name }}">
            @endif
        </span>

        <br>
        <small class="text-info">Max Upload File Size: 300 KB</small>
    </div>
</div>

<br>
<br>