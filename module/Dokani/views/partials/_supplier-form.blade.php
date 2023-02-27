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

.modal-body>label>b, .modal-title{
    color: black;
}
.loader {
            border: 3px solid #f3f3f3;
            border-radius: 50%;
            border-top: 3px solid #3498db;
            width: 30px;
            height: 30px;
            -webkit-animation: spin 2s linear infinite;
            /* Safari */
            animation: spin 2s linear infinite;
        }

        /* Safari */
        @-webkit-keyframes spin {
            0% {
                -webkit-transform: rotate(0deg);
            }

            100% {
                -webkit-transform: rotate(360deg);
            }
        }
</style>
<!-- Modal -->
<div class="modal right fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel2">Add Supplier</h4>
            </div>

            <div class="modal-body">
                <form action="{{ route('dokani.suppliers.store') }}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-sm-12">
                            <!-- Name -->
                        @include('includes.input.input-row', [
                            'name' => 'name',
                            'is_required' => true,
                        ])

                            <!-- Mobile -->
                            @include('includes.input.input-row', ['name' => 'mobile','is_required' => true])

                            <!-- Email -->
                            <div class="form-group">
                                <label for="address">
                                    <b>Email:</b>
                                </label>
                                <input type="email" name="email" placeholder="Email" class="form-control">
                            </div>


                         <!-- Opening balance -->
                        <div class="form-group">
                                <label for="address">
                                    <b>Opening Due:</b>
                                </label>
                                <input type="number" name="opening_due" placeholder="Opening Due" value="0" class="form-control">
                            </div>


                            <!-- Submit -->
                            <div class="row" style="display: inline-block">
                                <div class="col-sm-3">
                                    <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-save"></i> Save</button>
                                </div>
                                <div class="col-sm-5"></div>
                                <div class="col-sm-4" id="loader"></div>
                            </div>

                        </div>
                    </div>
                </form>
            </div>

        </div><!-- modal-content -->
    </div><!-- modal-dialog -->
</div><!-- modal -->
