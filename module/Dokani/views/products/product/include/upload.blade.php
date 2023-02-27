<form method="POST" action="{{ route('dokani.products.store') }}" class="form-horizontal"
    enctype="multipart/form-data">
    @csrf


    <input type="hidden" name="store_type" value="upload">

    <div class="row">
        <div class="col-sm-12">

            <!-- file upload -->
            <div class="col-sm-8 col-sm-offset-2">
                <input type="file" class="form-control ace-file-upload" name="csv_file">
            </div>


            <!-- Action -->
            <div class="col-sm-8 col-sm-offset-2 text-right">
                <a href="{{ asset('assets/product-samples.csv') }}" download class="btn btn-primary btn-sm">
                    <span class="translate">
                        Download Sample
                    </span>
                    <i class="fa fa-download"></i>
                </a>
                <button class="btn btn-inverse btn-sm" type="submit">
                    <span class="translate">
                        Import Product
                    </span>
                    <i class="fa fa-upload"></i>
                </button>
            </div>


        </div>
    </div>
</form>
