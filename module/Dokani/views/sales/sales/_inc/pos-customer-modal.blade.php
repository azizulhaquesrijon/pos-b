<div id="add-customer" class="modal">

    <div class="modal-dialog">

        <div class="modal-content" style="width: 130% !important;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="blue bigger"><i class="fa fa-plus-circle"></i> Select Customer </h4>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">

                        <div class="row mb-1">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select name="customer_id" id="customer" class="form-control select2" style="width: 100%" data-placeholder="--Select Customer--" required>
                                        <option></option>
                                        @foreach ($customers as $customer)
                                            <option value="{{ $customer->id }}"
                                                    data-point = "{{ $customer->point }}"
                                                    data-discount = "{{ $customer->discount ?? 0 }}"
                                                {{ $customer->is_default == 1 ? 'selected' : '' }}>
                                                {{ $customer->name }} &#8658; {{ $customer->mobile }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span class="input-group-addon">
                                        <a href="#" data-toggle="modal" data-target="#myModal2">
                                            <i class="ace-icon far fa-plus-circle fa-lg rotate"></i>
                                        </a>
                                    </span>
                                </div>
                            </div>
                            <input type="hidden" name="customer_own_discount" class="customer_own_discount" id="">
                            <div class="col-md-6">
                                <select name="refer_customer_id" id="refer_customer" class="form-control select2" style="width: 100%"
                                        data-placeholder="--Select Refer Customer--">
                                    <option></option>
                                    @foreach ($customers as $customer)
                                        <option value="{{ $customer->id }}">
                                            {{ $customer->name }} &#8658; {{ $customer->mobile }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-md-6">
                                <input type="text" name="note" id="note" class="form-control" placeholder="Type Account Note">
                            </div>

                            <div class="col-md-6">
                                <input type="text" name="date" id="pos_date" value="{{ Carbon\Carbon::now()->format('Y-m-d') }}"
                                       class="form-control date-picker" autocomplete="off">
                            </div>
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



