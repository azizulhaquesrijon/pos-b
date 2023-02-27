  <!-- Modal -->
  <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">Upload 50 Products </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <!-- body -->
            <div class="widget-body">
                <div class="widget-main">

                    <div class="row">
                        <div class="col-xs-12">
                            <div class="" style="margin: 15px;border: 1px solid #d2d2d2;padding-top: 10px;">
                                <form action="{{ route('dokani.product-uploads-with-single-branch-store') }}" method="post" style="display: flex; justify-content: space-around">
                                    @csrf
                                    {{-- <h3 style="margin-top: 5px;">Transfer All Porducts To Brnach</h3> --}}
                                    <div class="form-group">
                                        {{-- <label for="name">Branch Name</label> --}}
                                        <select name="branch_id" class="form-control select2" style="min-width: 200px" id="">
                                            <option disabled selected>-Select-</option>
                                            @foreach ($branches as $key => $item)
                                                <option value="{{ $key }}">{{ $item }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-info btn-sm"><i class="fa fa-check"></i>Transfer</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary">Save changes</button>
        </div> --}}
      </div>
    </div>
  </div>
