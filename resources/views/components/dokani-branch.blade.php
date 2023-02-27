@if (hasBranching())
    <div class="form-group">
        <div class="input-group">
            <span class="input-group-addon">Branch</span>
            <select id="branch_id" name="branch_id" class="form-control chosen-select-100-percent" data-placeholder="--Select Branch--"
                required>
                <option></option>
                @foreach (dokanBranches() as $id => $name)
                    <option value="{{ $id }}">
                        {{ $name }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
@endif
