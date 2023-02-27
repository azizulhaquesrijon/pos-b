<style>
    .modal.right .modal-dialog {
        position: fixed;
        margin: auto;
        width: 320px;
        height: 100%;
        -webkit-transform: translate3d(0%, 0, 0);
        -ms-transform: translate3d(0%, 0, 0);
        -o-transform: translate3d(0%, 0, 0);
        transform: translate3d(0%, 0, 0);
    }

    .modal.right .modal-content {
        height: 100%;
        overflow-y: auto;
    }

    .modal.right .modal-body {
        padding: 15px 15px 80px;
    }

    /*Right*/
    .modal.right.fade .modal-dialog {
        right: -320px;
        -webkit-transition: opacity 0.3s linear, right 0.3s ease-out;
        -moz-transition: opacity 0.3s linear, right 0.3s ease-out;
        -o-transition: opacity 0.3s linear, right 0.3s ease-out;
        transition: opacity 0.3s linear, right 0.3s ease-out;
    }

    .modal.right.fade.in .modal-dialog {
        right: 0;
    }

    /* ----- MODAL STYLE ----- */
    .modal-content {
        border-radius: 0;
        border: none;
    }

    .modal-header {
        border-bottom-color: #EEEEEE;
        background-color: #FAFAFA;
    }

    .modal-body>p,
    .modal-title {
        color: black;
    }

</style>


<!-- Modal -->
<div class="modal right fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel2">ADD Customer</h4>
            </div>

            <div class="modal-body">


                <form action="{{ route('dokani.customers.store') }}" method="post">
{{--                    <input type="hidden" name="_token" id="customer_token" value="{{ csrf_token() }}">--}}
                    @csrf

                    <input name="is_customer" value="1" type="hidden">
                    <div class="row">
                        <div class="col-sm-12">

                            <!-- Name -->
                            @include('includes.input.input-row', [
                                'name' => 'name',
                                'is_required' => true,
                            ])

                            <!-- Mobile -->
                            @include('includes.input.input-row', ['name' => 'mobile', 'is_required' => true,])


                            <!-- Address -->
                            <div class="form-group">
                                <label for="address">
                                    <b>Address:</b>
                                </label>
                                <textarea name="address" id="customer_address" class="form-control"></textarea>
                            </div>


                            <!-- Opening balance -->
                            @include('includes.input.input-row', [
                                'name' => 'opening_due',
                                'title' => 'Opening Due',
                                'value' => 0,
                                'is_required' => true,
                            ])

{{--                            <!-- Current balance -->--}}
{{--                            @include('includes.input.input-row', [--}}
{{--                                'name' => 'due_limit',--}}
{{--                                'title' => 'Due Limit',--}}
{{--                                'value' => 999999999,--}}
{{--                                'is_required' => true,--}}
{{--                            ])--}}

                        <!-- Refer By  -->
                            <div class="form-group" id="refer_by">
                                <label for="address">
                                    <b>Refer By :</b>
                                </label>
                                    <label>
                                        <input name="refer_by" value="user" type="radio" class="ace refer">
                                        <span class="lbl"> User</span>
                                    </label>

                                    <label>
                                        <input value="customer" name="refer_by" type="radio" class="ace refer">
                                        <span class="lbl"> Customer</span>
                                    </label>
                            </div>

                        <!-- Refer Customer -->

                            <div class="form-group" id="customer_refer" style="display: none">
                                <label for="address">
                                    <b>Refer Customer :</b>
                                </label>
                                <select name="refer_by_customer_id" id="refer_by_customer_id" class="form-control chosen-select"
                                        data-placeholder="-- Select Refer Customer --">
                                    <option value=""></option>
                                    @foreach($customers as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Refer User -->

                            <div class="form-group" id="user_refer" style="display: none">
                                <label for="address">
                                    <b>Refer User :</b>
                                </label>
                                <select name="refer_by_user_id" id="refer_by_user_id" class="form-control chosen-select"
                                        data-placeholder="-- Select Refer User --">
                                    <option value=""></option>
                                    @foreach($users as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>


                        @if($areas->count() > 0)
                            <!-- Area -->

                                <div class="form-group">
                                    <label for="address">
                                        <b>Area :</b>
                                    </label>
                                    <select name="cus_area_id" id="cus_area_id" class="form-control chosen-select"
                                            data-placeholder="-- Select Area --">
                                        <option value=""></option>
                                        @foreach($areas as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                        @endif




                        @if($cus_categories->count() > 0)
                            <!-- Area -->
                                <div class="form-group">
                                    <label for="address">
                                        <b>Category :</b>
                                    </label>
                                    <select name="cus_category_id" id="cus_category_id" class="form-control chosen-select"
                                            data-placeholder="-- Select Category --">
                                        <option value=""></option>
                                        @foreach($cus_categories as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                        @endif


                            <!-- Submit -->
                            <div class="row" style="display: inline-block">
                                <div class="col-sm-3">
                                    <button type="submit" id="customerBtn" class="btn btn-primary btn-sm">
                                        <i class="fa fa-save"></i> Save
                                    </button>
                                </div>

                                <div class="col-sm-5"></div>
                                <div class="col-sm-4" id="loader"></div>
                            </div>

                        </div>
                    </div>
                </form>
            </div>

        </div>
        <!-- modal-content -->

    </div>
    <!-- modal-dialog -->

</div>
<!-- modal -->

