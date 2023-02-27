<div class="footer hidden-print">
    <div class="footer-inner">
        <div class="footer-content">
            <span class="bigger-120" style="float: left; padding-left: 12%;">
                {{-- Copyright &copy;{{ date('Y') }} <span class="blue bolder">{{ optional(optional(optional(auth()->user())->company)->group)->name }}</span> --}}
                Copyright &copy;{{ date('Y') }} <span class="blue bolder">{{ optional(auth()->user()->businessProfileByUser)->shop_name == null ? optional(auth()->user())->name : optional(auth()->user()->businessProfileByUser)->shop_name }}</span>
            </span>
            <strong class="pull-right" style="padding-right:100px">Developed By: <a href="https://www.smartsoftware.com.bd/" target="__blank"> Smart Software Ltd</a></strong>
        </div>
    </div>
</div>

<a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
    <i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
</a>
