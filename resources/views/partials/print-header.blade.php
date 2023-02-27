
{{--@dd($company)--}}
<div class="d-none" style="display: none">
    <div class="row print-header">

{{--        <div class="col-sm-2" style="position: fixed">--}}
{{--            <img src="{{ asset('uploads/company/'.$company->logo) ?? '' }}" width="90px;" height="90px;">--}}
{{--        </div>--}}
        <div class="col-8 text-center">
            <address>
                <h2 style="font-weight: 900; color: #000099 "> {{ $company->name }}</h2>
                <h4>{{ $company->head_office }}</h4>
                <h5>{{ $company->phone_number }} {{ $company->email }}</h5>
            </address>
        </div>
    </div>
</div>
