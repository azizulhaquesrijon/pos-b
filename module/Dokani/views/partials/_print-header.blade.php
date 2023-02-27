<div class="text-center print-header" style="display: none">
    {{-- <img src="{{ asset(auth()->user()->image) }}" style="height: 60px" alt="image"> --}}
    <h3>{{ $business_setting->shop_name }}</h3>
    <h6>{{ $business_setting->shop_address }}</h6>
    <h6>Phone: {{ $business_setting->business_mobile }} Email: {{ $business_setting->business_email }}</h6>
</div>
