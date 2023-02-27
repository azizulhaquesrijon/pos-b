

<a class="status-update" data-route="{{ route('status-update') }}" data-table-name="{{ $table_name }}" data-id="{{ $id }}" href="javascript:void(0)">
    @if ($status == 1)
        <i class="fa fa-toggle-on fa-2x text-success" data-status="1"></i>
    @else
        <i class="fa fa-toggle-off fa-2x text-danger" data-status="0"></i>
    @endif
</a>