<div id="add-supplier" class="modal" tabindex="-1">

    <div class="modal-dialog">

        <div class="modal-content" style="width: 130% !important;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="blue bigger"><i class="fa fa-plus-circle"></i> Select Supplier </h4>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-5">
                        <div class="form-group">

                            <div class="input-group">

                                <select name="supplier_id" id="supplier" style="width: 100%"
                                        class="form-control chosen-select required"
                                        data-placeholder="--Select Supplier--" required>
                                    <option></option>
                                    @foreach ($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}"
                                                data-pre_due="{{ $supplier->balance }}">
                                            {{ $supplier->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="input-group-addon">
                                    <a href="#" data-toggle="modal" data-target="#myModal2"><i
                                            class="ace-icon fa fa-plus-circle fa-lg"></i></a>
                                </span>
                            </div>
                        </div>

                        <input type="hidden" class="acc_balance" value="0">


                    </div>

                    <div class="col-lg-4 col-lg-offset-2">
                        <div class="form-group">

                            <input type="text" name="date" id="pos_date"
                                   value="{{ Carbon\Carbon::now()->format('Y-m-d') }}"
                                   class="form-control date-picker">
                        </div>
                    </div>

                </div>
            </div>

            <div class="modal-footer">
                <div class="btn-group">
                    <button class="btn btn-sm btn-primary btn-block" data-dismiss="modal"
                            style="background-color: #147a08 !important; border-color: #147a08 !important; border-radius: 0px !important;width: 80px;">
                        <i class="ace-icon fa fa-check-circle"></i> OK
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>




