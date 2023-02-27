<form action="{{ route('dokani.send.sms') }}" method="POST">
    @csrf
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <label class="control-label col-md-12" for="mobile">
                    Mobiles :
                </label>
                <div class="col-md-12" id="selected_customer">
                    <div class="phone_box scroll_phone">
                        <select name="mobiles[]" data-placeholder="-- Select Customer Mobiles--"
                                style="width: 100%" class="form-control select2" multiple>
                            <option value=""></option>
                            @foreach($customers as $mobile)
                                <option value="{{ '88'.$mobile }}">{{ '88'.$mobile }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label style="width: 100%">Message
                    <strong class="pull-right"><span class="total-character-count">0</span>/640</strong> <!-- character length count  -->
                    <strong class="pull-right text-center" style="width: 30px !important;">|</strong>
                    <strong class="pull-right"><span class="part-count">0</span>/4</strong> <!-- sms count -->
                </label>
                <textarea class="form-control message-area" maxlength="640" rows="5" name="message"
                          placeholder="Type your message here..."></textarea>
            </div>
        </div>
    </div>

    <article class="feature1 my-3">
        <input type="checkbox" id="all_customer" value="1" onchange="allCustomer(this)"/>
        <div>
            <b> Select All Customer</b>
        </div>
    </article>

    <div class="form-group" style="float: right;margin-top: 27px;margin-right: 97px;">
        <button type="submit" class="btn btn-sm btn-primary save-btn"
                style="background-color: #478d24 !important; border-color: #478d24 !important; border-radius: 0px !important;width: 240%">
            <i class="fa fa-send"></i> SEND
        </button>
    </div>

</form>


