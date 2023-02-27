<div id="add-new" class="modal">

    <div class="modal-dialog">

        <div class="modal-content" style="width: 148% !important;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="blue bigger"><i class="fa fa-plus-circle"></i> Select Account </h4>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">

                        <table class="table table-bordered" id="accountTable">
                            <thead>
                                <tr>
                                    <th width="30%">Select Account</th>
                                    <th>Amount</th>
                                    <th>
                                        <i class="ace-icon fa fa-times-circle fa-lg"></i>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th>
                                        <select name="account_ids[]" id="account_id1" style="width: 100%"
                                            class="form-control select2" data-placeholder="- Select Account -"
                                            aria-hidden="true" required>
                                            <option value=""></option>
                                            @foreach (accountInfo() as $type)
                                                <option value="{{ $type->id }}" data-balance="{{ $type->balance }}"
                                                    data-name="{{ $type->name }}"
                                                    {{ $type->name == 'Cash' ? 'selected' : '' }}>
                                                    {{ $type->name . ' (' . $type->balance . ')' }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </th>

                                    <th style="display: flex">
                                        <input name="amount[]" type="text" onkeyup="totalAmount(1)"
                                            placeholder="Enter Amount" class="form-control pamount" required />&nbsp;
                                        <input name="check_no[]" type="text" style="display: none"
                                            placeholder="Enter Check No" class="form-control bankAccount1" />&nbsp;
                                        <input name="check_date[]" type="text" style="display: none"
                                            placeholder="Enter Check Date"
                                            class="form-control date-picker bankAccount1" />
                                    </th>

                                    <th>
                                        <button type="button" class="remove-row"
                                            style="background-color: transparent;border: none;" title="Remove"
                                            disabled=""><i
                                                class="far fa-times-circle fa-lg text-danger"></i></button>
                                        <br>
                                        <label>
                                            <input onclick="bankStatement(1)" class="ace ace-checkbox-2 bankStatement1"
                                                type="checkbox">
                                            <span class="lbl">Info.</span>
                                        </label>
                                    </th>

                                </tr>

                            </tbody>
                            <tfoot>
                                <tr>
                                    <td><strong>Total</strong></td>
                                    <td class="text-center">
                                        <input type="text" name="total_amount" class="form-control total_amount"
                                            value="0" readonly>
                                    </td>
                                    <td>
                                        <button type="button" id="addrow"
                                            style="background-color: transparent;border: none;">
                                            <i class="ace-icon fa fa-plus-circle text-success fa-lg rotate"></i>
                                        </button>
                                    </td>

                                </tr>
                                <tr class="change_amount_account" style="display: none">
                                    <td>
                                        <strong>Change Amount Account</strong>
                                        <select name="change_account_id" id="change_account_id" style="width: 100%"
                                            class="form-control select2" data-placeholder="- Select Account -">
                                            <option value=""></option>
                                            @foreach (accountInfo() as $type)
                                                <option value="{{ $type->id }}" data-balance="{{ $type->balance }}"
                                                    data-name="{{ $type->name }}"
                                                    {{ $type->name == 'Cash' ? 'selected' : '' }}>
                                                    {{ $type->name . ' (' . $type->balance . ')' }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="text-center">
                                        <strong>Change Amount</strong>
                                        <input type="text" name="change_amount" id="change_amount" class="form-control" value="0" readonly>
                                    </td>
                                    <td>
                                    </td>

                                </tr>
                            </tfoot>
                        </table>

                        <table class="table table-bordered table-hover" style="width: 100%; background-color: #F2F2F2">
                            <tfoot>

                                <tr>
                                    <td class="calculation_tr">
                                        <div class="row">
                                            <div class="col-md-7">
                                                Subtotal:
                                            </div>
                                            <div class="col-md-5">
                                                <span class="pos-subtotal">0.00</span>
                                                <input type="hidden" name="subtotal" class="form-control text-right" id="subtotal">
                                            </div>
                                        </div>

                                    </td>
                                    <td class="calculation_tr">
                                        <div class="row">
                                            <div class="col-md-7">
                                                Payable:
                                            </div>
                                            <div class="col-md-5">
                                                <span class="payable">0.00</span>
                                                <input type="hidden" class="form-control text-right"
                                                    name="payable_amount" id="payable">
                                            </div>
                                        </div>

                                    </td>
                                    <td class="calculation_tr">
                                        <div class="row">
                                            <div class="col-md-7">
                                                VAT Amount:
                                            </div>
                                            <div class="col-md-5">
                                                <span class="total_vat">0.00</span>
                                                <input type="hidden" class="form-control text-right" name="total_vat"
                                                    id="total_vat">
                                            </div>
                                        </div>

                                    </td>
                                </tr>
                                <tr>
                                    <td class="calculation_tr">
                                        <div class="row">
                                            <div class="col-md-7">
                                                Paid Amount:
                                            </div>
                                            <div class="col-md-5">
                                                <span class="paid_amount">0.00</span>
                                                <input type="hidden" class="form-control text-right paid_amount"
                                                    onkeyup="calculateDueAmount()" value="" style="height: 25px"
                                                    name="paid_amount" id="paid_amount" autocomplete="off" required />
                                            </div>
                                        </div>

                                    </td>
                                    <td class="calculation_tr">
                                        <div class="row">
                                            <div class="col-md-7">
                                                Discount:
                                            </div>
                                            <div class="col-md-5">
                                                <span class="discount">0.00</span>
                                                <input type="hidden" class="form-control text-right discount"
                                                    name="discount" value="0" id="discount"
                                                    autocomplete="off">
                                                <input type="hidden" class="form-control input-sm text-right"
                                                    id="pointDiscount">
                                            </div>
                                        </div>

                                    </td>
                                    <td class="calculation_tr" style="background-color: #a8ceee">

                                        <div class="row">
                                            <div class="col-md-7">
                                                <input name="" class="point" type="checkbox" id="test3">
                                                <label for="test3"
                                                    style="font-size: 16px; font-weight: 500; color: #000000 !important">
                                                    Point <span
                                                        class="customer_point">{{ customerPoint() }}</span></label>
                                            </div>
                                            <div class="col-md-5 point_show"
                                                style="float: right;font-size: 15px;color: #666666; display:none;">
                                                <input type="hidden" name="customer_point" id="customer_point">
                                                <input type="number" name="point" class="form-control"
                                                    value="" id="use_point" placeholder="Enter Point"
                                                    onkeyup="updateDiscount(this)" style="height: 28px !important;">
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="calculation_tr">
                                        <div class="row">
                                            <div class="col-md-7">
                                                Delivery Charge:
                                            </div>
                                            <div class="col-md-5">

                                                <input type="text" class="form-control text-right delivery_charge"
                                                    onkeyup="calculatePayable()" style="height: 25px"
                                                    name="delivery_charge" value="0" id="delivery_charge"
                                                    autocomplete="off" />
                                            </div>
                                        </div>

                                    </td>
                                    <td class="calculation_tr">
                                        <div class="row">
                                            <div class="col-md-7">
                                                Change:
                                            </div>
                                            <div class="col-md-5">
                                                <span class="change_amount">0.00</span>
                                            </div>
                                        </div>

                                    </td>
                                    <td class="calculation_tr">

                                        <div class="row">

                                            <div class="col-md-7">
                                                Due Amount:
                                            </div>
                                            <div class="col-md-5">
                                                <span class="due_amount">0.00</span>
                                                <input type="hidden" class="form-control text-right due_amount"
                                                    name="due_amount" id="due_amount" autocomplete="off" />
                                            </div>
                                        </div>
                                    </td>


                                </tr>

                                <tr>
                                    <td class="calculation_tr" style="background-color: #fff5f5">
                                        <div class="row">
                                            <div class="col-md-5">
                                                Print:
                                            </div>
                                            <div class="col-md-7">
                                                <div class="radio">
                                                    <label>
                                                        <input name="invoice_type" value="pos-invoice" type="radio"
                                                            class="ace print"
                                                            {{ optional($invoice)->invoice_type == 1 ? 'checked' : '' }}
                                                            required="required">
                                                        <span class="lbl"> POS</span>
                                                    </label>

                                                    <label>
                                                        <input name="invoice_type" value="normal-invoice"
                                                            type="radio"
                                                            {{ optional($invoice)->invoice_type != 1 ? 'checked' : '' }}
                                                            class="ace print">
                                                        <span class="lbl"> Normal</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>

                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <div class="btn-group" style="display: flex">
                    <button class="btn btn-sm btn-success btn-block"
                        style="width: 100%; border-radius: 0px !important; background-color: #ffc3be !important; border-color: #ffbebe; color: black !important;"
                        data-dismiss="modal">
                        <i class="ace-icon fa fa-times"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-sm btn-primary btn-block save-btn"
                        style="background-color: #0044ff !important; border-color: #0044ff !important; border-radius: 0px !important;">
                        <i class="fa fa-check-circle"></i> Save
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>
