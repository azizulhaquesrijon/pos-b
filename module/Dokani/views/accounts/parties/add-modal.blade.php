<div id="add-new" class="modal" tabindex="-1">

    <div class="modal-dialog">

        <form action="{{ route('dokani.ac.parties.store') }}" method="post" class="form-horizontal">

            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="blue bigger"><i class="fa fa-plus-circle"></i> Add New Party </h4>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-8 col-sm-offset-2">
                            @csrf


                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon">Name <sup class="text-danger">*</sup></span>
                                    <input type="text" class="form-control" name="name" required
                                        value="{{ old('name') }}" placeholder="Type Name">
                                </div>

                                @error('name')
                                    <span class="text-danger">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon">Mobile</span>
                                    <input type="number" class="form-control" name="mobile"
                                           value="{{ old('mobile') }}" placeholder="Type Mobile">
                                </div>

                                @error('name')
                                <span class="text-danger">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon">Address</span>
                                    <textarea class="form-control" name="address">{{ old('address') }}</textarea>
                                </div>

                                @error('address')
                                <span class="text-danger">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>

                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <div class="btn-group">
                        <button class="btn btn-sm" data-dismiss="modal">
                            <i class="ace-icon fa fa-times"></i> Cancel
                        </button>
                        <button type="submit" class="btn btn-sm btn-success">
                            <i class="fa fa-save"></i> Save
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
