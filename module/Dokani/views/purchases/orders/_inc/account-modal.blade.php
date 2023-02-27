<div id="add-new" class="modal">

    <div class="modal-dialog">

        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="blue bigger"><i class="fa fa-plus-circle"></i> Select Account </h4>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">

                        <div class="table-responsive">
                            <table class="table table-bordered" id="accountTable">
                                <thead>
                                <tr>
                                    <th width="30%">Select Account</th>
                                    <th>Amount</th>
                                    <th width="7%">
                                        <i class="ace-icon fa fa-times-circle fa-lg"></i>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <th width="30%">
                                        <select name="account_ids[]" id="account_id1" style="width: 100%"
                                                class="form-control select2" data-placeholder="- Select Account -"
                                                aria-hidden="true" onchange="bankStatement(1)" required>
                                            @foreach (accountInfo() as $type)
                                                <option value="{{ $type->id }}" data-balance = "{{ $type->balance }}"
                                                        data-name ="{{ $type->name }}"
                                                    {{ $type->balance <= 0 ? 'disabled' : '' }}>
                                                    {{ $type->name .' ('.$type->balance.')' }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </th>

                                    <th style="display: flex">
                                        <input name="amount[]" type="text" onkeyup="totalAmount(1)"
                                               placeholder="Enter Amount" class="form-control pamount" value="{{ $order->paid_amount }}" required/>&nbsp;
                                        <input name="check_no[]" type="text" style="display: none"
                                               placeholder="Enter Check No" class="form-control bankAccount1"/>&nbsp;
                                        <input name="check_date[]" type="text" style="display: none" autocomplete="off"
                                               placeholder="Enter Check Date" class="form-control date-picker bankAccount1"/>
                                    </th>

                                    <th width="7%">
                                        <button type="button" class="remove-row" style="background-color: transparent;border: none;" title="Remove"
                                                disabled=""><i class="far fa-times-circle fa-lg text-danger"></i></button>
                                        <label>
                                            <input onclick="bankStatement(1)"
                                                   class="ace ace-checkbox-2 bankStatement1" type="checkbox">
                                            <span class="lbl">Info.</span>
                                        </label>
                                    </th>
                                </tr>

                                </tbody>
                                <tfoot>
                                <tr>
                                    <td><strong>Total</strong></td>
                                    <td class="text-center">
                                        <input type="text" name="total_amount" class="form-control total_amount" value="{{ $order->paid_amount }}" readonly>
                                    </td>
                                    <td>
                                        <button type="button" id="addrow"
                                                style="background-color: transparent;border: none;">
                                            <i class="ace-icon fa fa-plus-circle text-success fa-lg rotate"></i>
                                        </button>
                                    </td>

                                </tr>
                                </tfoot>
                            </table>

                            <table style="width: 100%; background-color: #F2F2F2">
                                <tfoot>


                                <tr>

                                    <td class="calculation_tr">

                                        <div class="row">

                                            <div class="col-md-7">
                                                Payable Amount:
                                            </div>
                                            <div class="col-md-5">
                                                <span class="grand_total"> {{ $order->payable_amount }}</span>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="calculation_tr">
                                        <div class="row">
                                            <div class="col-md-7">
                                                Paid Amount:
                                            </div>
                                            <div class="col-md-5">
                                                <span class="paid_amount">{{ $order->paid_amount }}</span>
                                            </div>
                                        </div>

                                    </td>
                                </tr>

                                <tr>

                                    <td class="calculation_tr">
                                        <div class="row">
                                            <div class="col-md-7">
                                                Due Amount:
                                            </div>
                                            <div class="col-md-5">
                                                <span class="due_amount">{{ $order->due_amount }}</span>
                                            </div>
                                        </div>

                                    </td>
                                </tr>

                                </tfoot>
                            </table>

                        </div>

                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <div class="btn-group" style="display: flex">
                    <button class="btn btn-sm btn-success btn-block" style="width: 100%; border-radius: 0px !important; background-color: #ffc3be !important; border-color: #ffbebe; color: black !important;" data-dismiss="modal">
                        <i class="ace-icon fa fa-times"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-sm btn-primary btn-block"
                            style="background-color: #0044ff !important; border-color: #0044ff !important; border-radius: 0px !important;">
                        <i class="fa fa-check-circle"></i> Save
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

