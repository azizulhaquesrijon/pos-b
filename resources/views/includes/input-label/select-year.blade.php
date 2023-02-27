
<div class="form-group">
    <label class="col-sm-4 control-label no-padding-right" for="form-field-1"> Year <sup class="text-danger">*</sup></label>
    <div class="col-sm-8">
        <select name="year" id="" class="chosen-select required" required="required">
            <option value="" selected disabled>- Year -</option>
            @for ($y = date('Y') + 2; $y >= 2017; $y--)
                <option  {{ old('year') == $y ? 'selected' : ''  }} value="{{ $y }}">{{ $y }}</option>
            @endfor

        </select>
    </div>
</div>

<br>
<br>